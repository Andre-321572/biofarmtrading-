<?php

namespace App\Exports;

use App\Models\ProductionReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductionReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $report;

    public function __construct(ProductionReport $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->report->lots as $lot) {
            $data[] = [
                $lot->numero_lot,
                $lot->type_produit ?? 'Ananas',
                number_format($lot->quantite_declayee_kg, 2) . ' kg',
                number_format($lot->poids_rcl_kg, 2) . ' kg',
                number_format($lot->poids_mcl_kg, 2) . ' kg',
                number_format($lot->poids_rps_kg, 2) . ' kg',
                number_format($lot->poids_mps_kg, 2) . ' kg',
                number_format($lot->poids_lpa_kg, 2) . ' kg',
                number_format($lot->cl_ttpm_kg, 2) . ' kg',
                $lot->sachets_rcl_2kg,
                $lot->sachets_rcl_1kg,
                $lot->sachets_mcl_2kg + $lot->sachets_2kg,
                $lot->sachets_mcl_1kg + $lot->sachets_1kg,
                $lot->sachets_rps_2kg,
                $lot->sachets_rps_1kg,
                $lot->sachets_mps_2kg,
                $lot->sachets_mps_1kg,
                $lot->sachets_lpa_2kg,
                $lot->sachets_lpa_1kg,
                $lot->sachets_lpa_100g,
                $lot->sachets_ttpm_1kg,
                number_format($lot->dechets_kg, 2) . ' kg',
                $lot->notes ?? '-',
            ];
        }

        // Separator
        $data[] = array_fill(0, 23, '');

        // Totals row
        $data[] = [
            'TOTAL GÉNÉRAL',
            '',
            number_format($this->report->total_declaye, 2) . ' kg',
            number_format($this->report->total_poids_rcl_kg, 2) . ' kg',
            number_format($this->report->total_poids_mcl_kg, 2) . ' kg',
            number_format($this->report->total_poids_rps_kg, 2) . ' kg',
            number_format($this->report->total_poids_mps_kg, 2) . ' kg',
            number_format($this->report->total_poids_lpa_kg, 2) . ' kg',
            number_format($this->report->total_ttpm, 2) . ' kg',
            $this->report->total_sachets_rcl_2kg,
            $this->report->total_sachets_rcl_1kg,
            $this->report->total_sachets_mcl_2kg,
            $this->report->total_sachets_mcl_1kg,
            $this->report->total_sachets_rps_2kg,
            $this->report->total_sachets_rps_1kg,
            $this->report->total_sachets_mps_2kg,
            $this->report->total_sachets_mps_1kg,
            $this->report->total_sachets_lpa_2kg,
            $this->report->total_sachets_lpa_1kg,
            $this->report->total_sachets_lpa_100g,
            $this->report->lots->sum('sachets_ttpm_1kg'),
            number_format($this->report->total_dechets, 2) . ' kg',
            '',
        ];

        if ($this->report->lots_peles) {
            $data[] = array_fill(0, 23, '');
            $data[] = array_merge(['LOTS PELÉS DU JOUR', $this->report->lots_peles], array_fill(0, 21, ''));
        }

        if ($this->report->notes) {
            $data[] = array_fill(0, 23, '');
            $data[] = array_merge(['OBSERVATIONS', $this->report->notes], array_fill(0, 21, ''));
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['RAPPORT JOURNALIER DE PRODUCTION - BIO FARM TRADING'],
            ['Référence: ' . $this->report->reference],
            ['Date du rapport: ' . $this->report->date_rapport->format('d/m/Y')],
            ['Ouvriers Présents: ' . ($this->report->nombre_ouvriers ?? 0) . ' | Absents: ' . ($this->report->nombre_ouvriers_absents ?? 0) . ' | Repos: ' . ($this->report->nombre_ouvriers_repos ?? 0)],
            ['Matériel Atelier: Couteaux (Début: ' . ($this->report->nombre_couteaux_debut ?? 0) . ' / Fin: ' . ($this->report->nombre_couteaux_fin ?? 0) . ') | Ciseaux (Début: ' . ($this->report->nombre_ciseaux_debut ?? 0) . ' / Fin: ' . ($this->report->nombre_ciseaux_fin ?? 0) . ')'],
            ['Auteur: ' . ($this->report->user->name ?? 'Responsable Production')],
            [],
            [
                'N° Lot',
                'Type Produit',
                'Qté Déclayée',
                'Poids RCL (kg)',
                'Poids MCL (kg)',
                'Poids RPS (kg)',
                'Poids MPS (kg)',
                'Poids LPA (kg)',
                'Poids TTPM (kg)',
                'RCL 2KG',
                'RCL 1KG',
                'MCL 2KG',
                'MCL 1KG',
                'RPS 2KG',
                'RPS 1KG',
                'MPS 2KG',
                'MPS 1KG',
                'LPA 2KG',
                'LPA 1KG',
                'LPA 100G',
                'TTPM 1KG',
                'Déchets (kg)',
                'Notes'
            ],
        ];
    }

    public function title(): string
    {
        return 'Rapport ' . $this->report->reference;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 22,
            'C' => 16,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 12,
            'N' => 12,
            'O' => 12,
            'P' => 12,
            'Q' => 12,
            'R' => 12,
            'S' => 12,
            'T' => 12,
            'U' => 12,
            'V' => 16,
            'W' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            8 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1FAE5']
                ],
            ],
        ];
    }
}
