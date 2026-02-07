<?php

namespace App\Exports;

use App\Models\Arrivage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ArrivageExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $arrivage;

    public function __construct(Arrivage $arrivage)
    {
        $this->arrivage = $arrivage;
    }

    public function array(): array
    {
        $data = [];
        
        // Détails des fruits
        foreach ($this->arrivage->details as $detail) {
            $variete = $detail->variete === 'cayenne_lisse' ? 'Cayenne Lisse' : 
                       ($detail->variete === 'braza' ? 'Braza' : '-');
            
            $data[] = [
                ucfirst($detail->fruit),
                $variete,
                number_format($detail->poids, 2),
            ];
        }
        
        // Lignes vides
        $data[] = ['', '', ''];
        $data[] = ['', '', ''];
        
        // Section ANANAS
        if ($this->arrivage->total_ananas > 0) {
            $data[] = ['🍍 ANANAS', '', ''];
            $data[] = ['Total Ananas Cayenne Lisse', '', number_format($this->arrivage->total_ananas_cayenne, 2) . ' kg'];
            $data[] = ['Total Ananas Braza', '', number_format($this->arrivage->total_ananas_braza, 2) . ' kg'];
            $data[] = ['TOTAL ANANAS (toutes variétés)', '', number_format($this->arrivage->total_ananas, 2) . ' kg'];
            
            // Ligne vide
            $data[] = ['', '', ''];
        }
        
        // Section PAPAYE
        if ($this->arrivage->total_papaye > 0) {
            $data[] = ['🥭 PAPAYE', '', ''];
            $data[] = ['Total Papaye', '', number_format($this->arrivage->total_papaye, 2) . ' kg'];
            
            // Ligne vide
            $data[] = ['', '', ''];
        }
        
        // Total général
        $data[] = ['TOTAL GÉNÉRAL (tous fruits)', '', number_format($this->arrivage->total_general, 2) . ' kg'];
        
        return $data;
    }

    public function headings(): array
    {
        return [
            ['ARRIVAGE #' . $this->arrivage->id],
            ['Date: ' . $this->arrivage->date_arrivage->format('d/m/Y')],
            ['Chauffeur: ' . $this->arrivage->chauffeur . ' | Matricule: ' . $this->arrivage->matricule_camion],
            ['Provenance: ' . $this->arrivage->zone_provenance],
            [],
            ['Fruit', 'Variété', 'Poids (kg)'],
        ];
    }

    public function title(): string
    {
        return 'Arrivage ' . $this->arrivage->id;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 25,
            'C' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        return [
            // En-tête principal
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => ['font' => ['size' => 11]],
            3 => ['font' => ['size' => 11]],
            4 => ['font' => ['size' => 11]],
            
            // Titres des colonnes
            6 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8E8E8']
                ],
            ],
            
            // Styles pour les sections de totaux
            'A:C' => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
