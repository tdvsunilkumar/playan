<?php

namespace App\Exports;

use App\Models\Bplo\Endrosement;
use App\Models\BfpApplicationForm;
use App\Models\CommonModelmaster;
use App\Models\BploBusinessPsic;
use App\Models\BploBusiness;
use App\Models\HoAppHealthCert;
use App\Models\HrEmployee;
use App\Models\Report\BusinessTaxCollection;
use App\Models\HoApplicationSanitary;
use App\Models\Barangay;
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
use Carbon\Carbon;
use Session;

class ExportBusinessTaxCollection implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
   private $keywords;

    public function __construct(String $keywords = null)
    {
        $this->keywords = $keywords;
        $this->_BusinessTaxCollection = new BusinessTaxCollection();
        $this->_commonmodel = new CommonModelmaster();
        $this->_barangay = new Barangay();
        $this->_hrEmployee = new HrEmployee(); 
        
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
        $res = $this->_BusinessTaxCollection->getDataExport();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                // $event->sheet->getDelegate()->mergeCells('A2:J2');

                // $event->sheet->getDelegate()->mergeCells('A3:E3');
                // $event->sheet->getDelegate()->mergeCells('F3:J3');
                 $event->sheet->getStyle('A1:H2')->getAlignment()->setHorizontal('center');
                // $event->sheet->getStyle('A3:E3')->getAlignment()->setHorizontal('right');
                // $event->sheet->getStyle('F3:J3')->getAlignment()->setHorizontal('left');
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

                $rows = 2;
                if (!empty($res)) {
                    $event->sheet->getStyle('A'.$rows.':N'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
					$event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray3);
                   
                    $event->sheet->setCellValue('A'.$rows, 'NO.');
                    $event->sheet->setCellValue('B'.$rows, 'DATE'); 
                    $event->sheet->setCellValue('C'.$rows, 'O.R. NO.'); 
					$event->sheet->setCellValue('D'.$rows, 'PAYEE');
                    $event->sheet->setCellValue('E'.$rows, 'BUSINESS ID-NO.');
					$event->sheet->setCellValue('F'.$rows, 'BUSINESS NAME');
                    $event->sheet->setCellValue('G'.$rows, 'AMOUNT COLLECTED'); 
                    $event->sheet->setCellValue('H'.$rows, 'TAX CREDIT');
					
                    $rows++;
                    $count=0;
                    $totalTaxCreditAmount = 0;
                    $totalnet_tax_due_amount=0;
                    foreach ($res as $val) {
                        $count=$count+1;
                        $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray);
						$event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray);
                        
                        $event->sheet->setCellValue('A'.$rows, $count);
                        $event->sheet->setCellValue('B'.$rows, ($val->cashier_or_date ? $val->cashier_or_date:'')); 
                        $event->sheet->setCellValue('C'.$rows, ($val->or_no ? $val->or_no: ''));
						$event->sheet->setCellValue('D'.$rows, ($val->taxpayers_name ? $val->taxpayers_name : '')); 
                        $event->sheet->setCellValue('E'.$rows, ($val->busns_id_no ? $val->busns_id_no : ''));
						$event->sheet->setCellValue('F'.$rows, ($val->busn_name ? $val->busn_name : ''));
                        $net_tax_due_amount = $val->net_tax_due_amount ? $val->net_tax_due_amount : 0;
                        $totalnet_tax_due_amount += $net_tax_due_amount;
                        $event->sheet->setCellValue('G'.$rows, $net_tax_due_amount);
                        $taxCreditAmount = $val->tax_credit_amount ? $val->tax_credit_amount : 0;
                        $totalTaxCreditAmount += $taxCreditAmount;
                        $event->sheet->setCellValue('H'.$rows,  $taxCreditAmount);
                        $rows++;
                        
                    }
                    $event->sheet->setCellValue('G' . $rows, $totalnet_tax_due_amount);
                    $event->sheet->setCellValue('H' . $rows, $totalTaxCreditAmount);
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
            // 'A2'  => ['font' => ['size' => 18, 'bold' => true]],
            // 'A3:F3'  => ['font' => ['size' => 14]],
            // 'A4:F4'  => ['font' => ['size' => 13]],
        ];
    }

    public function view(): View
    {   
        return view('report.businesstaxcollection.excelView', ['keywords' => $this->keywords]);
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

     public function getSizeByCap($val){
		if($val <= 150000){
			return "Micro";
		}elseif ($val >= 150001 && $val <= 5000000) {
			return "Small";
		}elseif ($val >= 5000001 && $val <= 20000000) {
			return "Medium";
		}
		elseif ($val >= 20000001) {
			return "Large";
		}
	}
}
