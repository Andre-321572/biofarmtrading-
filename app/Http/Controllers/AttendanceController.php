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
            $q->whereBetween('date', [$startOfWeek, $endOfWeek]);
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

        Attendance::updateOrCreate(
            [
                'worker_id' => $request->worker_id,
                'date' => $request->date,
                'session' => $request->session
            ],
            $data
        );

        return response()->json(['success' => true]);
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
            $q->whereBetween('date', [$startOfWeek, $endOfWeek]);
        }])->orderBy('last_name')->get();

        $dayWorkers = $allWorkers->where('shift', 'day');
        $nightWorkers = $allWorkers->where('shift', 'night');

        $pdf = Pdf::loadView('admin.attendance.pdf_weekly', compact('dayWorkers', 'nightWorkers', 'days', 'startOfWeek', 'endOfWeek'))
                  ->setPaper('a4', 'landscape'); // Landscape to fit the week
        
        return $pdf->download('Pointage_Semaine_' . $startOfWeek->format('d-m-Y') . '.pdf');
    }
}
