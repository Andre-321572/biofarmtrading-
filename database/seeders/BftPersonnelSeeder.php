<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Database\Seeder;

class BftPersonnelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Postes clés de l'organigramme de Bio Farm Trading
        $dgPos = JobPosition::firstOrCreate(
            ['title' => 'Directeur Général'],
            [
                'department' => 'Direction Générale',
                'description' => 'Direction stratégique, gouvernance et développement de Bio Farm Trading.',
                'is_active' => true,
            ]
        );

        $rhPos = JobPosition::firstOrCreate(
            ['title' => 'Responsable Ressources Humaines'],
            [
                'department' => 'Ressources Humaines',
                'description' => 'Gestion du personnel permanent, contrats, conformité légale et climat social.',
                'is_active' => true,
            ]
        );

        $rpPos = JobPosition::firstOrCreate(
            ['title' => 'Responsable de Production'],
            [
                'department' => 'Production',
                'description' => 'Supervision de l\'usine, ordonnancement des lignes et contrôle du rendement.',
                'is_active' => true,
            ]
        );

        $comptaPos = JobPosition::firstOrCreate(
            ['title' => 'Comptable Principal'],
            [
                'department' => 'Administration & Finance',
                'description' => 'Tenue des comptes, fiscalité, trésorerie et états financiers.',
                'is_active' => true,
            ]
        );

        $magaPos = JobPosition::firstOrCreate(
            ['title' => 'Responsable Magasin & Stocks'],
            [
                'department' => 'Logistique & Stocks',
                'description' => 'Contrôle des stocks de fruits, emballages et expéditions de produits finis.',
                'is_active' => true,
            ]
        );

        $chauffeurPos = JobPosition::firstOrCreate(
            ['title' => 'Chauffeur / Livreur'],
            [
                'department' => 'Transport & Logistique',
                'description' => 'Acheminement des approvisionnements et livraisons des commandes.',
                'is_active' => true,
            ]
        );

        $securitePos = JobPosition::firstOrCreate(
            ['title' => 'Gardien de Sécurité'],
            [
                'department' => 'Sécurité & Surveillance',
                'description' => 'Surveillance du site, contrôle des accès et sécurité des biens.',
                'is_active' => true,
            ]
        );

        // 2. Personnel permanent de Bio Farm Trading (strictement distinct des ouvriers de production)
        $staffMembers = [
            [
                'matricule' => 'BFT-DIR-001',
                'first_name' => 'Komi Mawuena',
                'last_name' => 'ADJALIM',
                'gender' => 'M',
                'job_position_id' => $dgPos->id,
                'phone' => '+228 90 12 34 56',
                'email' => 'direction@biofarmtrading.com',
                'address' => 'Lomé, Tokoin Habitat',
                'contract_type' => 'CDI',
                'hired_at' => '2020-01-15',
                'monthly_salary' => 650000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-RH-002',
                'first_name' => 'Akossiwa Marie',
                'last_name' => 'KOUASSI',
                'gender' => 'F',
                'job_position_id' => $rhPos->id,
                'phone' => '+228 91 23 45 67',
                'email' => 'rh@biofarmtrading.com',
                'address' => 'Lomé, Agoè Nyivé',
                'contract_type' => 'CDI',
                'hired_at' => '2021-03-01',
                'monthly_salary' => 450000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-PRD-003',
                'first_name' => 'Koffi Eric',
                'last_name' => 'MENSAH',
                'gender' => 'M',
                'job_position_id' => $rpPos->id,
                'phone' => '+228 92 34 56 78',
                'email' => 'rp@biofarmtrading.com',
                'address' => 'Lomé, Baguida',
                'contract_type' => 'CDI',
                'hired_at' => '2021-06-10',
                'monthly_salary' => 450000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-FIN-004',
                'first_name' => 'Yao Daniel',
                'last_name' => 'AMOUZOU',
                'gender' => 'M',
                'job_position_id' => $comptaPos->id,
                'phone' => '+228 93 45 67 89',
                'email' => 'compta@biofarmtrading.com',
                'address' => 'Lomé, Bè Kpota',
                'contract_type' => 'CDI',
                'hired_at' => '2022-02-01',
                'monthly_salary' => 380000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-LOG-005',
                'first_name' => 'Essi Delali',
                'last_name' => 'LAWSON',
                'gender' => 'F',
                'job_position_id' => $magaPos->id,
                'phone' => '+228 90 98 76 54',
                'email' => 'magasin@biofarmtrading.com',
                'address' => 'Lomé, Hedzranawoé',
                'contract_type' => 'CDI',
                'hired_at' => '2022-08-15',
                'monthly_salary' => 250000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-TRP-006',
                'first_name' => 'Essowavana',
                'last_name' => 'TCHALLA',
                'gender' => 'M',
                'job_position_id' => $chauffeurPos->id,
                'phone' => '+228 98 76 54 32',
                'email' => 'logistique@biofarmtrading.com',
                'address' => 'Lomé, Adidogomé',
                'contract_type' => 'CDD',
                'hired_at' => '2023-01-10',
                'monthly_salary' => 190000,
                'status' => 'actif',
            ],
            [
                'matricule' => 'BFT-SEC-007',
                'first_name' => 'Tidjani',
                'last_name' => 'BASSOWA',
                'gender' => 'M',
                'job_position_id' => $securitePos->id,
                'phone' => '+228 97 65 43 21',
                'email' => null,
                'address' => 'Lomé, Zanguéra',
                'contract_type' => 'CDI',
                'hired_at' => '2022-11-01',
                'monthly_salary' => 160000,
                'status' => 'actif',
            ],
        ];

        foreach ($staffMembers as $data) {
            Employee::firstOrCreate(
                ['matricule' => $data['matricule']],
                $data
            );
        }
    }
}
