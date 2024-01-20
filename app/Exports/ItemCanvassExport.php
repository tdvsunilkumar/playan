<?php

namespace App\Exports;

use App\Models\BacRfqSupplierCanvass;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DateTime;

class ItemCanvassExport implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
    private $keywords;

    public function __construct(String $keywords = null)
    {
         $this->keywords = $keywords;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 14,
            'C' => 14,
            'D' => 14,
            'E' => 14,
            'F' => 14,
            'G' => 14,
            'H' => 14,
            'I' => 14,
            'J' => 14,
            'K' => 14,
            'L' => 14,
            'M' => 14,
            'N' => 14,   
        ];
    }

    public function registerEvents(): array
    {   
        $keywords = $this->keywords;
        $res = BacRfqSupplierCanvass::select([
            'bac_rfqs_suppliers_canvass.*'
        ])
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers_canvass.supplier_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'bac_rfqs_suppliers_canvass.item_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('bac_rfqs_suppliers_canvass.id', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.description', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.quantity', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.unit_cost', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.total_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.branch_name', 'like', '%' . $keywords . '%');
            }
        })
        ->get();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:J1');
                $event->sheet->getDelegate()->mergeCells('A2:J2');

                $event->sheet->getDelegate()->mergeCells('A3:E3');
                $event->sheet->getDelegate()->mergeCells('F3:J3');
                $event->sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A3:E3')->getAlignment()->setHorizontal('right');
                $event->sheet->getStyle('F3:J3')->getAlignment()->setHorizontal('left');
                $event->sheet->setCellValue('A3', 'Keywords:');
                $event->sheet->setCellValue('F3', $keywords);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $styleArray2 = [
                    'font' => [
                        // 'name'      =>  'Calibri',
                        'size'      =>  13,
                        'bold'      =>  true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'c4c5d6']
                    ]
                ];

                $styleArray3 = [
                    'font' => [
                        // 'name'      =>  'Calibri',
                        // 'size'      =>  12,
                        'color' => ['rgb' => 'ffffff'],
                        'bold'      =>  true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '99999e']
                    ]
                ];

                $rows = 5;
                if (!empty($res)) {
                    $event->sheet->getStyle('A'.$rows.':J'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('J'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->setCellValue('A'.$rows, 'ID'); 
                    $event->sheet->setCellValue('B'.$rows, 'SUPPLIER'); 
                    $event->sheet->setCellValue('C'.$rows, 'BRANCH'); 
                    $event->sheet->setCellValue('D'.$rows, 'GL ACCOUNT'); 
                    $event->sheet->setCellValue('E'.$rows, 'ITEM DESCRIPTION'); 
                    $event->sheet->setCellValue('F'.$rows, 'BRAND/MODEL'); 
                    $event->sheet->setCellValue('G'.$rows, 'QUANTITY'); 
                    $event->sheet->setCellValue('H'.$rows, 'UNIT COST'); 
                    $event->sheet->setCellValue('I'.$rows, 'TOTAL COST'); 
                    $event->sheet->setCellValue('J'.$rows, 'LAST MODIFIED');
                    $rows++;
                    foreach ($res as $canvass) {
                        $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('J'.$rows)->applyFromArray($styleArray);
                        $event->sheet->setCellValue('A'.$rows, $canvass->id); 
                        $event->sheet->setCellValue('B'.$rows, ($canvass->supplier ? $canvass->supplier->business_name : '')); 
                        $event->sheet->setCellValue('C'.$rows, ($canvass->supplier ? $canvass->supplier->branch_name : '')); 
                        $event->sheet->setCellValue('D'.$rows, ($canvass->item->gl_account ? $canvass->item->gl_account->code.' - ' .$canvass->item->gl_account->description : '')); 
                        $event->sheet->setCellValue('E'.$rows, ($canvass->item->remarks != 'NULL' ? $canvass->item->code.' - ' .$canvass->item->name. ' {'.$canvass->item->remarks.'}' : $canvass->item->code.' - ' .$canvass->item->name)); 
                        $event->sheet->setCellValue('F'.$rows, $canvass->description); 
                        $event->sheet->setCellValue('G'.$rows, $canvass->quantity); 
                        $event->sheet->setCellValue('H'.$rows, $this->money_format($canvass->unit_cost)); 
                        $event->sheet->setCellValue('I'.$rows, $this->money_format($canvass->total_cost)); 
                        $event->sheet->setCellValue('J'.$rows, ($canvass->updated_at !== NULL) ? date('d-M-Y H:i', strtotime($canvass->updated_at)) : date('d-M-Y H:i', strtotime($canvass->created_at))); 
                        $rows++;
                    }
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            // 1    => ['font' => ['bold' => true]],

            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'A'  => ['font' => ['size' => 12]],
            'B'  => ['font' => ['size' => 12]],
            'C'  => ['font' => ['size' => 12]],
            'D'  => ['font' => ['size' => 12]],
            'E'  => ['font' => ['size' => 12]],
            'F'  => ['font' => ['size' => 12]],
            'G'  => ['font' => ['size' => 12]],
            'H'  => ['font' => ['size' => 12]],
            'I'  => ['font' => ['size' => 12]],
            'J'  => ['font' => ['size' => 12]],
            'K'  => ['font' => ['size' => 12]],
            'L'  => ['font' => ['size' => 12]],
            'M'  => ['font' => ['size' => 12]],
            'N'  => ['font' => ['size' => 12]],

            'A1'  => ['font' => ['size' => 24, 'bold' => true]],
            'A2'  => ['font' => ['size' => 18, 'bold' => true]],
            'A3:F3'  => ['font' => ['size' => 14]],
            'A4:F4'  => ['font' => ['size' => 13]],
        ];
    }

    public function view(): View
    {   
        return view('reports.general-services.item-canvass.view', ['keywords' => $this->keywords]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ITEM CANVASS SHEET';
    }
}