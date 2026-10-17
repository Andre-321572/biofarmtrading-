<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\JobPosition;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffSyncService
{
    /**
     * Postes de référence pour Bio Farm Trading.
     */
    public const DEFAULT_POSITIONS = [
        [
            'title' => 'Ouvrier de Production',
            'department' => 'Production',
            'description' => 'Activités de production, transformation de matière première, tri et manutention.',
            'is_active' => true,
        ],
        [
            'title' => 'Chef d\'équipe',
            'department' => 'Production',
            'description' => 'Supervision et coordination opérationnelle des équipes de travail jour et nuit.',
            'is_active' => true,
        ],
        [
            'title' => 'Magasinier',
            'department' => 'Logistique & Stocks',
            'description' => 'Gestion des stocks, entrées/sorties magasins, inventaires et préparation.',
            'is_active' => true,
        ],
        [
            'title' => 'Chauffeur / Livreur',
            'department' => 'Transport & Logistique',
            'description' => 'Transport des marchandises, approvisionnements et livraisons.',
            'is_active' => true,
        ],
        [
            'title' => 'Gardien de Sécurité',
            'department' => 'Sécurité & Surveillance',
            'description' => 'Surveillance des sites, contrôle des accès et sécurité des biens.',
            'is_active' => true,
        ],
        [
            'title' => 'Électricien / Maintenance',
            'department' => 'Technique & Maintenance',
            'description' => 'Maintenance des machines, groupes électrogènes et installations électriques.',
            'is_active' => true,
        ],
        [
            'title' => 'Comptable',
            'department' => 'Administration & Finance',
            'description' => 'Tenue des comptes, facturation, trésorerie et conformité fiscale.',
            'is_active' => true,
        ],
    ];

    /**
     * Initialise les postes par défaut si la table est vide.
     */
    public function seedDefaultPositionsIfNeeded(): void
    {
        if (JobPosition::count() === 0) {
            foreach (self::DEFAULT_POSITIONS as $posData) {
                JobPosition::firstOrCreate(
                    ['title' => $posData['title']],
                    $posData
                );
            }
        }
    }

    /**
     * Récupère le poste par défaut pour les ouvriers de production.
     */
    public function getDefaultWorkerPosition(): JobPosition
    {
        $this->seedDefaultPositionsIfNeeded();

        return JobPosition::where('title', 'Ouvrier de Production')->first() 
            ?? JobPosition::firstOrCreate(
                ['title' => 'Ouvrier de Production'],
                [
                    'department' => 'Production',
                    'description' => 'Activités de production, transformation et manutention.',
                    'is_active' => true,
                ]
            );
    }

    /**
     * Helper statique pour synchroniser un ouvrier.
     */
    public static function syncWorker(Worker $worker): Employee
    {
        return app(self::class)->syncWorkerInstance($worker);
    }

    /**
     * Helper statique pour synchroniser tous les ouvriers.
     */
    public static function syncAll(): int
    {
        return app(self::class)->syncAllInstance();
    }

    /**
     * Alias statique pour syncAll().
     */
    public static function syncAllWorkers(): int
    {
        return self::syncAll();
    }

    /**
     * Synchronise un ouvrier avec son dossier personnel (Employee).
     */
    public function syncWorkerInstance(Worker $worker): Employee
    {
        $defaultPosition = $this->getDefaultWorkerPosition();

        // Trouver par worker_id ou par nom/prénoms
        $employee = Employee::where('worker_id', $worker->id)->first();

        if (!$employee) {
            $employee = Employee::where('first_name', $worker->first_name)
                ->where('last_name', $worker->last_name)
                ->first();
        }

        $positionId = $worker->job_position_id 
            ?? ($employee?->job_position_id ?? $defaultPosition->id);

        if (!$employee) {
            $matricule = 'BFT-' . str_pad((string)$worker->id, 3, '0', STR_PAD_LEFT);
            // Vérifier unicité du matricule
            if (Employee::where('matricule', $matricule)->exists()) {
                $matricule = 'BFT-W' . str_pad((string)$worker->id, 3, '0', STR_PAD_LEFT);
            }

            $employee = new Employee([
                'worker_id' => $worker->id,
                'job_position_id' => $positionId,
                'matricule' => $matricule,
                'first_name' => $worker->first_name,
                'last_name' => $worker->last_name,
                'hired_at' => $worker->getHiredAt(),
                'contract_type' => 'Journalier',
                'status' => 'actif',
            ]);
            $employee->save();
        } else {
            $employee->worker_id = $worker->id;
            if (!$employee->job_position_id) {
                $employee->job_position_id = $positionId;
            }
            if (!$employee->hired_at && $worker->hired_at) {
                $employee->hired_at = $worker->getHiredAt();
            }
            $employee->save();
        }

        // Mettre à jour la relation inverse sur l'ouvrier si nécessaire
        if ($worker->job_position_id !== $employee->job_position_id) {
            $worker->job_position_id = $employee->job_position_id;
            $worker->position = $employee->jobPosition?->title ?? 'Ouvrier';
            $worker->saveQuietly();
        }

        return $employee;
    }

    /**
     * Synchronise tous les ouvriers de la base de données dans la table du personnel.
     *
     * @return int Nombre d'employés synchronisés
     */
    public function syncAllInstance(): int
    {
        $this->seedDefaultPositionsIfNeeded();
        $defaultPosition = $this->getDefaultWorkerPosition();

        $workers = Worker::all();
        $syncedCount = 0;

        DB::transaction(function () use ($workers, $defaultPosition, &$syncedCount) {
            foreach ($workers as $worker) {
                $this->syncWorkerInstance($worker);
                $syncedCount++;
            }
        });

        return $syncedCount;
    }

    /**
     * Magic call pour supporter les appels $instance->syncWorker() et $instance->syncAll().
     */
    public function __call(string $method, array $arguments)
    {
        if ($method === 'syncWorker') {
            return $this->syncWorkerInstance(...$arguments);
        }
        if ($method === 'syncAll' || $method === 'syncAllWorkers') {
            return $this->syncAllInstance();
        }

        throw new \BadMethodCallException("Method {$method} does not exist on " . static::class);
    }
}
