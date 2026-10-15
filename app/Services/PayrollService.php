<?php

namespace App\Services;

use App\Models\Worker;
use App\Models\Attendance;
use App\Models\WorkerBonus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PayrollService
{
    /**
     * Taux horaire standard par défaut pour les anciens ouvriers (en FCFA).
     */
    public const DEFAULT_HOURLY_RATE = 275;
    public const HOURLY_RATE = 275;

    /**
     * Taux horaire standard par défaut pour les nouveaux ouvriers (en FCFA).
     */
    public const DEFAULT_NEW_HOURLY_RATE = 250;

    /**
     * Récupère le taux horaire standard (anciens ouvriers).
     */
    public static function getHourlyRate(): int
    {
        return (int) \App\Models\Setting::get('worker_hourly_rate', self::DEFAULT_HOURLY_RATE);
    }

    /**
     * Récupère le taux horaire pour les nouveaux ouvriers.
     */
    public static function getNewHourlyRate(): int
    {
        return (int) \App\Models\Setting::get('worker_new_hourly_rate', self::DEFAULT_NEW_HOURLY_RATE);
    }

    /**
     * Calcule le récapitulatif complet de la paie mensuelle des ouvriers (avec primes et distinction nouveaux/anciens).
     *
     * @param Carbon $month
     * @param string|null $shift ('all', 'day', 'night')
     * @param string|null $search
     * @param int|null $customRate Taux horaire optionnel pour anciens
     * @param int|null $customNewRate Taux horaire optionnel pour nouveaux
     * @return array
     */
    public function getMonthlyPayroll(
        Carbon $month, 
        ?string $shift = null, 
        ?string $search = null, 
        ?int $customRate = null,
        ?int $customNewRate = null
    ): array {
        $regularRate = $customRate ?? self::getHourlyRate();
        $newRate = $customNewRate ?? self::getNewHourlyRate();
        $monthStr = $month->format('Y-m');
        $startOfMonth = $month->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $month->copy()->endOfMonth()->format('Y-m-d');

        // Récupérer les primes du mois groupées par ouvrier
        $monthlyBonuses = WorkerBonus::where('month', $monthStr)
            ->get()
            ->groupBy('worker_id');

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
        $totalBaseSalarySum = 0;
        $totalBonusesSum = 0;
        $totalNetSalarySum = 0;
        $dayWorkersCount = 0;
        $nightWorkersCount = 0;
        $daySalarySum = 0;
        $nightSalarySum = 0;
        $activeWorkersCount = 0;
        $newWorkersCount = 0;
        $regularWorkersCount = 0;
        $workersWithBonusCount = 0;

        foreach ($workers as $worker) {
            $isNew = $worker->isNew();
            if ($isNew) {
                $newWorkersCount++;
            } else {
                $regularWorkersCount++;
            }

            // Calcul du taux effectif de cet ouvrier
            $effectiveRate = $worker->getEffectiveHourlyRate($regularRate, $newRate);

            // Récupérer les primes de cet ouvrier pour ce mois
            $workerBonusesList = $monthlyBonuses->get($worker->id, collect());
            $workerBonusTotal = (int) $workerBonusesList->sum('amount');
            if ($workerBonusTotal > 0) {
                $workersWithBonusCount++;
            }

            $calc = $this->calculateWorkerPayrollForMonth($worker, $effectiveRate, $workerBonusTotal);
            $calc['is_new'] = $isNew;
            $calc['bonuses_list'] = $workerBonusesList;

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
            $totalBaseSalarySum += $calc['base_salary'];
            $totalBonusesSum += $calc['bonus_amount'];
            $totalNetSalarySum += $calc['salary'];

            $payrollItems[] = $calc;
        }

        $totalHoursDecimal = round($totalMinutesSum / 60, 2);
        $totalHoursInt = floor($totalMinutesSum / 60);
        $totalHoursMinutes = $totalMinutesSum % 60;
        $totalHoursFormatted = sprintf('%dh%02d', $totalHoursInt, $totalHoursMinutes);

        return [
            'month' => $month,
            'selectedMonth' => $monthStr,
            'start_of_month' => $startOfMonth,
            'end_of_month' => $endOfMonth,
            'hourly_rate' => $regularRate,
            'new_hourly_rate' => $newRate,
            'items' => collect($payrollItems),
            'summary' => [
                'total_workers' => $workers->count(),
                'active_workers' => $activeWorkersCount,
                'new_workers_count' => $newWorkersCount,
                'regular_workers_count' => $regularWorkersCount,
                'workers_with_bonuses_count' => $workersWithBonusCount,
                'total_minutes' => $totalMinutesSum,
                'total_hours_decimal' => $totalHoursDecimal,
                'total_hours_formatted' => $totalHoursFormatted,
                'total_base_salary' => $totalBaseSalarySum,
                'total_bonuses' => $totalBonusesSum,
                'total_salary' => $totalNetSalarySum,
                'day_workers_count' => $dayWorkersCount,
                'night_workers_count' => $nightWorkersCount,
                'day_salary' => $daySalarySum,
                'night_salary' => $nightSalarySum,
                'average_salary' => $activeWorkersCount > 0 ? round($totalNetSalarySum / $activeWorkersCount) : 0,
            ],
        ];
    }

    /**
     * Calcule le détail et le salaire d'un ouvrier pour ses présences chargées.
     *
     * @param Worker $worker
     * @param int|null $rate
     * @param int $bonusAmount
     * @return array
     */
    public function calculateWorkerPayrollForMonth(Worker $worker, ?int $rate = null, int $bonusAmount = 0): array
    {
        $rate = $rate ?? $worker->getEffectiveHourlyRate();
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

        // Calcul du salaire de base : (Total minutes / 60) * taux horaire effectif
        $exactBaseSalary = ($totalMinutes * $rate) / 60;
        $roundedBaseSalary = (int) round($exactBaseSalary);

        // Salaire net total = Salaire de base + Prime
        $totalSalary = $roundedBaseSalary + $bonusAmount;

        return [
            'worker' => $worker,
            'shift' => $worker->shift,
            'is_new' => $worker->isNew(),
            'days_present' => $daysPresent,
            'total_minutes' => $totalMinutes,
            'hours_decimal' => $hoursDecimal,
            'hours_formatted' => $hoursFormatted,
            'hourly_rate' => $rate,
            'base_salary' => $roundedBaseSalary,
            'bonus_amount' => $bonusAmount,
            'salary' => $totalSalary,
            'daily_details' => $dailyDetails,
        ];
    }
}
