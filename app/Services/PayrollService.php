<?php

namespace App\Services;

use App\Models\Worker;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PayrollService
{
    /**
     * Taux horaire standard par défaut pour les ouvriers Bio Farm Trading (en FCFA).
     */
    public const DEFAULT_HOURLY_RATE = 275;
    public const HOURLY_RATE = 275;

    /**
     * Récupère le taux horaire dynamique défini par l'administrateur.
     */
    public static function getHourlyRate(): int
    {
        return (int) \App\Models\Setting::get('worker_hourly_rate', self::DEFAULT_HOURLY_RATE);
    }

    /**
     * Calcule le récapitulatif complet de la paie mensuelle des ouvriers.
     *
     * @param Carbon $month
     * @param string|null $shift ('all', 'day', 'night')
     * @param string|null $search
     * @param int|null $customRate Taux horaire optionnel
     * @return array
     */
    public function getMonthlyPayroll(Carbon $month, ?string $shift = null, ?string $search = null, ?int $customRate = null): array
    {
        $rate = $customRate ?? self::getHourlyRate();
        $startOfMonth = $month->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $month->copy()->endOfMonth()->format('Y-m-d');

        // Récupérer les ouvriers avec leurs présences sur le mois
        $query = Worker::query()
            ->with(['attendances' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth, $endOfMonth])
                  ->orderBy('date', 'asc');
            }])
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');

        if ($shift && in_array($shift, ['day', 'night'])) {
            $query->where('shift', $shift);
        }

        if ($search && trim($search) !== '') {
            $searchTerm = '%' . trim($search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('last_name', 'like', $searchTerm)
                  ->orWhere('first_name', 'like', $searchTerm);
            });
        }

        $workers = $query->get();

        $payrollItems = [];
        $totalMinutesSum = 0;
        $totalSalarySum = 0;
        $dayWorkersCount = 0;
        $nightWorkersCount = 0;
        $daySalarySum = 0;
        $nightSalarySum = 0;
        $activeWorkersCount = 0;

        foreach ($workers as $worker) {
            $calc = $this->calculateWorkerPayrollForMonth($worker, $rate);

            if ($calc['total_minutes'] > 0) {
                $activeWorkersCount++;
            }

            if ($worker->shift === 'night') {
                $nightWorkersCount++;
                $nightSalarySum += $calc['salary'];
            } else {
                $dayWorkersCount++;
                $daySalarySum += $calc['salary'];
            }

            $totalMinutesSum += $calc['total_minutes'];
            $totalSalarySum += $calc['salary'];

            $payrollItems[] = $calc;
        }

        $totalHoursDecimal = round($totalMinutesSum / 60, 2);
        $totalHoursInt = floor($totalMinutesSum / 60);
        $totalHoursMinutes = $totalMinutesSum % 60;
        $totalHoursFormatted = sprintf('%dh%02d', $totalHoursInt, $totalHoursMinutes);

        return [
            'month' => $month,
            'start_of_month' => $startOfMonth,
            'end_of_month' => $endOfMonth,
            'hourly_rate' => $rate,
            'items' => collect($payrollItems),
            'summary' => [
                'total_workers' => $workers->count(),
                'active_workers' => $activeWorkersCount,
                'total_minutes' => $totalMinutesSum,
                'total_hours_decimal' => $totalHoursDecimal,
                'total_hours_formatted' => $totalHoursFormatted,
                'total_salary' => $totalSalarySum,
                'day_workers_count' => $dayWorkersCount,
                'night_workers_count' => $nightWorkersCount,
                'day_salary' => $daySalarySum,
                'night_salary' => $nightSalarySum,
                'average_salary' => $activeWorkersCount > 0 ? round($totalSalarySum / $activeWorkersCount) : 0,
            ],
        ];
    }

    /**
     * Calcule le détail et le salaire d'un ouvrier pour ses présences chargées.
     *
     * @param Worker $worker
     * @param int|null $rate
     * @return array
     */
    public function calculateWorkerPayrollForMonth(Worker $worker, ?int $rate = null): array
    {
        $rate = $rate ?? self::getHourlyRate();
        $attendances = $worker->attendances;
        $totalMinutes = 0;
        $daysPresent = 0;
        $dailyDetails = [];

        if ($worker->shift === 'night') {
            // Équipe de nuit : chaque jour présent équivaut à 4h de travail forfaitaire (240 min)
            $groupedByDate = $attendances->groupBy('date');

            foreach ($groupedByDate as $date => $dayAtts) {
                $isPresent = $dayAtts->contains(function ($att) {
                    return $att->status === 'present';
                });

                if ($isPresent) {
                    $daysPresent++;
                    $minutesForDay = 240; // 4 heures
                    $totalMinutes += $minutesForDay;

                    $dailyDetails[] = [
                        'date' => $date,
                        'status' => 'present',
                        'arrival_time' => null,
                        'departure_time' => null,
                        'raw_duration_minutes' => 240,
                        'pause_minutes' => 0,
                        'net_minutes' => 240,
                        'net_hours_text' => '4h00',
                    ];
                } else {
                    $firstAtt = $dayAtts->first();
                    $dailyDetails[] = [
                        'date' => $date,
                        'status' => $firstAtt ? $firstAtt->status : 'absent',
                        'arrival_time' => null,
                        'departure_time' => null,
                        'raw_duration_minutes' => 0,
                        'pause_minutes' => 0,
                        'net_minutes' => 0,
                        'net_hours_text' => '0h00',
                    ];
                }
            }
        } else {
            // Équipe de jour : Durée = (Heure départ - Heure arrivée) - 2h de pause journalière (120 min)
            $groupedByDate = $attendances->groupBy('date');

            foreach ($groupedByDate as $date => $dayAtts) {
                $att = $dayAtts->first();

                if ($att && $att->arrival_time && $att->departure_time) {
                    $arrivalTime = Carbon::parse($att->arrival_time)->format('H:i:s');
                    $departureTime = Carbon::parse($att->departure_time)->format('H:i:s');

                    $start = Carbon::parse($date . ' ' . $arrivalTime);
                    $end = Carbon::parse($date . ' ' . $departureTime);

                    if ($end->lessThan($start)) {
                        $end->addDay();
                    }

                    $rawDuration = $start->diffInMinutes($end);
                    $netDuration = max(0, $rawDuration - 120); // 2 heures de pause déduites

                    if ($netDuration > 0 || $att->status === 'present') {
                        $daysPresent++;
                    }

                    $totalMinutes += $netDuration;

                    $h = floor($netDuration / 60);
                    $m = $netDuration % 60;

                    $dailyDetails[] = [
                        'date' => $date,
                        'status' => $att->status ?? 'present',
                        'arrival_time' => Carbon::parse($att->arrival_time)->format('H:i'),
                        'departure_time' => Carbon::parse($att->departure_time)->format('H:i'),
                        'raw_duration_minutes' => $rawDuration,
                        'pause_minutes' => 120,
                        'net_minutes' => $netDuration,
                        'net_hours_text' => sprintf('%dh%02d', $h, $m),
                    ];
                } elseif ($att && $att->status === 'present') {
                    // Présent mais sans pointage horaire précis
                    $daysPresent++;
                    $dailyDetails[] = [
                        'date' => $date,
                        'status' => 'present',
                        'arrival_time' => null,
                        'departure_time' => null,
                        'raw_duration_minutes' => 0,
                        'pause_minutes' => 0,
                        'net_minutes' => 0,
                        'net_hours_text' => '0h00',
                    ];
                } elseif ($att) {
                    $dailyDetails[] = [
                        'date' => $date,
                        'status' => $att->status,
                        'arrival_time' => null,
                        'departure_time' => null,
                        'raw_duration_minutes' => 0,
                        'pause_minutes' => 0,
                        'net_minutes' => 0,
                        'net_hours_text' => '0h00',
                    ];
                }
            }
        }

        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;
        $hoursFormatted = sprintf('%dh%02d', $h, $m);
        $hoursDecimal = round($totalMinutes / 60, 2);

        // Calcul du salaire : (Total minutes / 60) * taux horaire (275 FCFA)
        $exactSalary = ($totalMinutes * $rate) / 60;
        $roundedSalary = (int) round($exactSalary);

        return [
            'worker' => $worker,
            'shift' => $worker->shift,
            'days_present' => $daysPresent,
            'total_minutes' => $totalMinutes,
            'hours_decimal' => $hoursDecimal,
            'hours_formatted' => $hoursFormatted,
            'hourly_rate' => $rate,
            'salary' => $roundedSalary,
            'daily_details' => $dailyDetails,
        ];
    }
}
