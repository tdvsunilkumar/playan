<?php

namespace App\Exports;

use App\Models\Report\BploTaxFeeOtherCharges;
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

class FinancialTfocList implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
   private $keywords;

    public function __construct(String $keywords = null)
    {
         $this->keywords = $keywords;
         $this->_bblotaxfeesothercharges = new BploTaxFeeOtherCharges(); 
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 14,
            'C' => 14,
            'D' => 14,
            'E' => 14,
        ];
    }

    public function registerEvents(): array
    {   
        $keywords = $this->keywords;
        $res = $this->_bblotaxfeesothercharges->getDataExport();
        //print_r($res); exit;
        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:J1');
                $event->sheet->getDelegate()->mergeCells('A2:J2');

                $event->sheet->getDelegate()->mergeCells('A3:E3');
                $event->sheet->getDelegate()->mergeCells('F3:J3');
                $event->sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A3:E3')->getAlignment()->setHorizontal('right');
                $event->sheet->getStyle('F3:J3')->getAlignment()->setHorizontal('left');
                // $event->sheet->setCellValue('A3', 'Keywords:');
                // $event->sheet->setCellValue('F3', $keywords);

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
                   
                    $event->sheet->setCellValue('A'.$rows, 'Description'); 
                    $event->sheet->setCellValue('B'.$rows, 'Total Amount Paid'); 
                    $event->sheet->setCellValue('C'.$rows, 'Total Penalty'); 
                    $event->sheet->setCellValue('D'.$rows, 'Credit Applied'); 
                    $event->sheet->setCellValue('E'.$rows, 'Totals');

                    $rows++;
                    foreach ($res as $val) {
                        $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray);
                       
                       
                        $event->sheet->setCellValue('A'.$rows, $val->accdesc); 
                        $event->sheet->setCellValue('B'.$rows, number_format($val->totalpaidamount, 2, '.', ',')); 
                        $event->sheet->setCellValue('C'.$rows, number_format($val->totalpenalty, 2, '.', ',')); 
                        $event->sheet->setCellValue('D'.$rows, number_format(0, 2, '.', ','));
                        $total = $val->totalpaidamount + $val->totalpenalty;
                        $event->sheet->setCellValue('E'.$rows, number_format($total, 2, '.', ',')); 
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
           

            'A1'  => ['font' => ['size' => 24, 'bold' => true]],
            'A2'  => ['font' => ['size' => 18, 'bold' => true]],
            'A3:F3'  => ['font' => ['size' => 14]],
            'A4:F4'  => ['font' => ['size' => 13]],
        ];
    }

    public function view(): View
    {   
        return view('report.fanancialtfoclist.view', ['keywords' => $this->keywords]);
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
