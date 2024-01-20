<?php

namespace App\Exports;

use App\Models\ReportsMasterlists;
use App\Models\Barangay;
use App\Models\CommonModelmaster;
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

class ReportsMasterlistsDeclinedExport implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
   private $keywords;

    public function __construct(String $keywords = null)
    {
         $this->keywords = $keywords;
         $this->_commonmodel = new CommonModelmaster();
         $this->_ReportsMasterlists = new ReportsMasterlists(); 
         $this->_Barangay = new Barangay();
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
            'O' => 14,
            'P' => 14,  
            'Q' => 14, 
        ];
    }
    public function registerEvents(): array
    {   
        $keywords = $this->keywords;
        $res = $this->_ReportsMasterlists->getDeclinedBusnListexport();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:J1');
                // $event->sheet->getDelegate()->mergeCells('A2:J2');

                // $event->sheet->getDelegate()->mergeCells('A3:E3');
                // $event->sheet->getDelegate()->mergeCells('F3:J3');
                $event->sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
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
                    $event->sheet->setCellValue('A'.$rows, 'No.');
                    $event->sheet->setCellValue('B'.$rows, 'OWNER NAME'); 
                    $event->sheet->setCellValue('C'.$rows, 'BUSINESS NAME'); 
                    $event->sheet->setCellValue('D'.$rows, 'BUSINESS ADDRESS'); 
                    $event->sheet->setCellValue('E'.$rows, 'TYPE OF APPLICATION'); 
                    $event->sheet->setCellValue('F'.$rows, 'LINE OF BUSINESS'); 
                    $event->sheet->setCellValue('G'.$rows, 'CONTACT INFORMATION'); 
                    $event->sheet->setCellValue('H'.$rows, 'METHOD'); 
                    $event->sheet->setCellValue('I'.$rows, 'EMAIL ADDRESS');
                    
                    $rows++;
                    $count=0;
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
                        $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray);
                        $event->sheet->setCellValue('A'.$rows, $count);
                        $owner_name=(!empty($val->rpo_first_name) ? $val->rpo_first_name . ' ' : '') . (!empty($val->rpo_middle_name) ? $val->rpo_middle_name . ' ' : '') . (!empty($val->rpo_custom_last_name) ? $val->rpo_custom_last_name : ''). (!empty($val->suffix) ? ', '.$val->suffix  : '');
                        $event->sheet->setCellValue('B'.$rows, $owner_name); 
                        $event->sheet->setCellValue('C'.$rows, ($val->busn_name ? $val->busn_name : '')); 
                        $complete_address=$this->_commonmodel->getbussinesAddress($val->id);
                        $event->sheet->setCellValue('D'.$rows, ($complete_address ? $complete_address : '')); 
                        $event->sheet->setCellValue('E'.$rows, ($val->app_type ? $val->app_type:''));
                         if($val->id > 0){
                            $naturss=[];
                            $natur_des=$this->_ReportsMasterlists->getureofname($val->id);
                            $x=1;
                            foreach($natur_des as $vals){
                              $naturss[]= $x.").".$vals->subclass_description;   
                              $x++;
                            }
                            $aaa= implode(" ",$naturss);
                            $nature =$aaa ;
                            $cap_invest=$this->_ReportsMasterlists->calCapInvest($val->id);
                            $t_gross=$this->_ReportsMasterlists->calTotalGross($val->id);
                            $sizeByCap=$this->_ReportsMasterlists->sizeByCap($val->id,$val->b_typ_id);
                        }else{
                            $nature="";
                        }   
                        $event->sheet->setCellValue('F'.$rows, ($nature ? $nature : '')); 
                        $event->sheet->setCellValue('G'.$rows, ($val->p_mobile_no ? $val->p_mobile_no : '')); 
                        $event->sheet->setCellValue('H'.$rows, ($val->busn_app_method ? $val->busn_app_method : ''));
                        $event->sheet->setCellValue('I'.$rows, ($val->p_email_address ? $val->p_email_address : ''));                          
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
            // 'A2'  => ['font' => ['size' => 18, 'bold' => true]],
            // 'A3:F3'  => ['font' => ['size' => 14]],
            // 'A4:F4'  => ['font' => ['size' => 13]],
        ];
    }

    public function view(): View
    {   
        return view('reportsMasterListsDeclinedBusn.exportview', ['keywords' => $this->keywords]);
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
