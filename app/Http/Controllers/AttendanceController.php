<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Carbon\Carbon;
use App\Models\Worker;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    /**
     * Display the attendance weekly grid.
     */
    public function index(Request $request)
    {
        // Determine week start (Monday)
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        
        $prevWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');
        
        // Days of week for header
        $days = [];
        $current = $startOfWeek->copy();
        while ($current <= $endOfWeek) {
            $days[] = $current->copy();
            $current->addDay();
        }

        $allWorkers = Worker::with(['attendances' => function($q) use ($startOfWeek, $endOfWeek) {
            $q->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
        }])->orderBy('last_name')->get();

        $dayWorkers = $allWorkers->where('shift', 'day');
        $nightWorkers = $allWorkers->where('shift', 'night');
        $workers = $allWorkers;

        return view('admin.attendance.index', compact('dayWorkers', 'nightWorkers', 'workers', 'days', 'startOfWeek', 'endOfWeek', 'prevWeek', 'nextWeek'));
    }

    /**
     * Update or create an attendance record via AJAX.
     */
    public function update(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'date' => 'required|date',
            'session' => 'required|string',
            'status' => 'nullable|in:present,absent,late,none',
            'arrival_time' => 'nullable',
            'departure_time' => 'nullable',
        ]);

        $data = [];
        if ($request->has('status')) $data['status'] = $request->status;
        if ($request->has('arrival_time')) {
            $data['arrival_time'] = $request->arrival_time;
            if ($request->arrival_time) $data['status'] = 'present';
        }
        if ($request->has('departure_time')) {
            $data['departure_time'] = $request->departure_time;
            if ($request->departure_time) $data['status'] = 'present';
        }

        $att = Attendance::updateOrCreate(
            [
                'worker_id' => $request->worker_id,
                'date' => $request->date,
                'session' => $request->session
            ],
            $data
        );

        // Recalculate total hours for the worker for the week containing this date
        $worker = Worker::with('attendances')->find($request->worker_id);
        $date = Carbon::parse($request->date);
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        $weekAttendances = $worker->attendances()
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        $totalMinutes = 0;
        
        if ($worker->shift === 'night') {
            // Night workers: 4 hours fixed per present day
            foreach($weekAttendances as $wa) {
                if ($wa->status === 'present') {
                    $totalMinutes += 240;
                }
            }
        } else {
            // Day workers: (Duration - 2h pause) per day logic
            $days = [];
            $current = $startOfWeek->copy();
            while ($current <= $endOfWeek) {
                $days[] = $current->copy();
                $current->addDay();
            }

            foreach($days as $day) {
                $dateStr = $day->format('Y-m-d');
                $dayAtt = $weekAttendances->where('date', $dateStr)->first();
                
                if ($dayAtt && $dayAtt->arrival_time && $dayAtt->departure_time) {
                    $arrivalTime = Carbon::parse($dayAtt->arrival_time)->format('H:i:s');
                    $departureTime = Carbon::parse($dayAtt->departure_time)->format('H:i:s');
                    
                    $start = Carbon::parse($dateStr . ' ' . $arrivalTime);
                    $end = Carbon::parse($dateStr . ' ' . $departureTime);
                    
                    if ($end->lessThan($start)) $end->addDay();
                    
                    $dayDuration = $start->diffInMinutes($end);
                    $totalMinutes += max(0, $dayDuration - 120);
                }
            }
        }

        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;
        $totalHours = $h . 'h' . ($m > 0 ? sprintf('%02d', $m) : '');

        return response()->json([
            'success' => true, 
            'total_hours' => $totalHours,
            'worker_id' => $worker->id
        ]);
    }

    /**
     * Store new workers (Bulk).
     */
    public function storeWorkers(Request $request)
    {
        $request->validate([
            'workers' => 'required|array',
            'workers.*.first_name' => 'required|string',
            'workers.*.last_name' => 'required|string',
            'workers.*.shift' => 'required|in:day,night',
        ]);
        
        foreach ($request->workers as $workerData) {
            Worker::create($workerData);
        }
        
        return back()->with('success', count($request->workers) . ' ouvriers ajoutés avec succès.');
    }

    /**
     * Update worker information.
     */
    public function updateWorker(Request $request, Worker $worker)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'shift' => 'required|in:day,night',
        ]);

        $worker->update($request->all());

        return back()->with('success', 'Ouvrier mis à jour avec succès.');
    }

    /**
     * Delete a worker.
     */
    public function destroyWorker(Worker $worker)
    {
        $worker->delete(); // Automatically deletes attendances if cascade is set, or we can do it manually
        return back()->with('success', 'Ouvrier supprimé avec succès.');
    }

    /**
     * Generate PDF Report for the week.
     */
    public function pdfReport(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        
        $days = [];
        $current = $startOfWeek->copy();
        while ($current <= $endOfWeek) {
            $days[] = $current->copy();
            $current->addDay();
        }

        $allWorkers = Worker::with(['attendances' => function($q) use ($startOfWeek, $endOfWeek) {
            $q->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
        }])->orderBy('last_name')->get();

        $dayWorkers = $allWorkers->where('shift', 'day');
        $nightWorkers = $allWorkers->where('shift', 'night');

        $pdf = Pdf::loadView('admin.attendance.pdf_weekly', compact('dayWorkers', 'nightWorkers', 'days', 'startOfWeek', 'endOfWeek'))
                  ->setPaper('a4', 'landscape'); // Landscape to fit the week
        
        return $pdf->download('Pointage_Semaine_' . $startOfWeek->format('d-m-Y') . '.pdf');
    }
}
