<?php

namespace App\Exports;

use App\Models\ReportsMasterlists;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

class ReportsMasterlistsRegBusnExport implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
   private $keywords;

    public function __construct(String $keywords = null)
    {
        $this->keywords = $keywords;
        $this->_ReportsMasterlists = new ReportsMasterlists();
        $this->_commonmodel = new CommonModelmaster();
        
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
        $res = $this->_ReportsMasterlists->getDataExportListBusnReg();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:P1');
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
                    $event->sheet->getStyle('A'.$rows.':N'.$rows)->getAlignment()->setHorizontal('center');
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
                    $event->sheet->getStyle('K'.$rows)->applyFromArray($styleArray3);
					$event->sheet->getStyle('L'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('M'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('N'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('O'.$rows)->applyFromArray($styleArray3);
					$event->sheet->getStyle('P'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->setCellValue('A'.$rows, 'NO.');
                    $event->sheet->setCellValue('B'.$rows, 'BUSINESS NAME'); 
                    $event->sheet->setCellValue('C'.$rows, 'TYPE OF BUSINESS'); 
					$event->sheet->setCellValue('D'.$rows, 'REGISTRATION NO.');
                    $event->sheet->setCellValue('E'.$rows, 'REGISTERED BUSINESS NAME IN BUSINESS PERMIT');
					$event->sheet->setCellValue('F'.$rows, 'PERMIT NO.');
                    $event->sheet->setCellValue('G'.$rows, 'TYPE OF APPLICATION'); 
                    $event->sheet->setCellValue('H'.$rows, 'DATE APPLIED');
					$event->sheet->setCellValue('I'.$rows, 'DATE ISSUED');
					$event->sheet->setCellValue('J'.$rows, 'OWNERS NAME');
					$event->sheet->setCellValue('K'.$rows, 'BUSINESS ADDRESS');
					$event->sheet->setCellValue('L'.$rows, 'LINE OF BUSINESS');
					$event->sheet->setCellValue('M'.$rows, 'SIZE OF BUSINESS((BY NO. OF EMPLOYEE)');
                    $event->sheet->setCellValue('N'.$rows, 'SIZE OF BUSINESS (BY CAPITAL INVESTMENT/GROSS INCOME)'); 
					$event->sheet->setCellValue('O'.$rows, 'CONTACT NO. OF OWNER');
                    $event->sheet->setCellValue('P'.$rows, 'EMAIL ADDRESS');
                    
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
                        $event->sheet->getStyle('J'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('K'.$rows)->applyFromArray($styleArray);
						$event->sheet->getStyle('L'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('M'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('N'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('O'.$rows)->applyFromArray($styleArray);
						$event->sheet->getStyle('P'.$rows)->applyFromArray($styleArray);
                        $event->sheet->setCellValue('A'.$rows, $count);
                        $event->sheet->setCellValue('B'.$rows, $val->busn_name); 
                        $event->sheet->setCellValue('C'.$rows, ($val->btype_desc ? $val->btype_desc : ''));
                        
						$event->sheet->setCellValue('D'.$rows, ($val->busn_registration_no ? $val->busn_registration_no : '')); 
                        $event->sheet->setCellValue('E'.$rows, ($val->busn_name ? $val->busn_name : ''));
						$event->sheet->setCellValue('F'.$rows,  ($val->bpi_permit_no ? $val->bpi_permit_no : ''));
                        $event->sheet->setCellValue('G'.$rows,($val->app_type ? $val->app_type : ''));
                        $event->sheet->setCellValue('H'.$rows, ($val->created_at ? $val->created_at : ''));
						$event->sheet->setCellValue('I'.$rows, ($val->bpi_issued_date ? $val->bpi_issued_date : ''));
						$event->sheet->setCellValue('J'.$rows, ($val->full_name ? $val->full_name : ''));
						// $complete_address=(!empty($val->busn_office_main_building_no) ? $val->busn_office_main_building_no . ', ' : '') . (!empty($val->busn_office_main_building_name) ? $val->busn_office_main_building_name . ', ' : '') . (!empty($val->busn_office_main_add_block_no) ? $val->busn_office_main_add_block_no . ', ' : ''). (!empty($val->busn_office_main_add_lot_no) ? $val->busn_office_main_add_lot_no . ', ' : ''). (!empty($val->busn_office_main_add_street_name) ? $val->busn_office_main_add_street_name . ', ' : ''). (!empty($val->busn_office_main_add_subdivision) ? $val->busn_office_main_add_subdivision . ', ' : '') . (!empty($brgy_det) ? $brgy_det : '');
                        // $complete_address2=$this->_commonmodel->getbussinesAddressBarangay($row->id);
                        $complete_address=(!empty($val->busn_office_main_add_block_no) ? $val->busn_office_main_add_block_no . ', ' : '') . (!empty($val->busn_office_main_add_lot_no) ? $val->busn_office_main_add_lot_no . ', ' : '') . (!empty($val->busn_office_main_add_street_name) ? $val->busn_office_main_add_street_name . ', ' : '') . (!empty($val->busn_office_main_add_subdivision) ? $val->busn_office_main_add_subdivision . '' : '');
                $complete_address2=$this->_commonmodel->getbussinesAddressBarangay($val->busn_office_main_barangay_id);
                $totalAddress=($complete_address) ? $complete_address. ', '.$complete_address2 : $complete_address2;
						$event->sheet->setCellValue('K'.$rows, ($totalAddress ? $totalAddress : ''));
                        if($val->id > 0){
							$naturss=[];
							$natur_des=$this->_ReportsMasterlists->getureofname($val->busn_id);
							$x=1;
							foreach($natur_des as $vals){
							  $naturss[]= $x.").".$vals->subclass_description;	
							  $x++;
							}
							$aaa= implode(" ",$naturss);
							$nature = $aaa;
							$cap_invest=$this->_ReportsMasterlists->calCapInvest($val->busn_id);
							$t_gross=$this->_ReportsMasterlists->calTotalGross($val->busn_id);
							$sizeByCap=$this->_ReportsMasterlists->sizeByCap($val->busn_id,$val->b_typ_id);
						}else{
							$nature="";
						}
                        $event->sheet->setCellValue('L'.$rows, ($nature ? $nature : ''));
						$event->sheet->setCellValue('M'.$rows, $this->getSizeByEmp($val->busn_employee_total_no));
						$event->sheet->setCellValue('N'.$rows, $this->getSizeByCap($sizeByCap));
                        $event->sheet->setCellValue('O'.$rows, $val->p_mobile_no); 
                        $event->sheet->setCellValue('P'.$rows, $val->p_email_address); 
                        $rows++;
                        
                    }
                }
            },
        ];
    }

    public function getSizeByEmp($val){
		if($val < 1){
			return "Micro";
		}elseif ($val >= 1 && $val <= 99) {
			return "Small";
		}elseif ($val >= 100 && $val <= 199) {
			return "Medium";
		}
		elseif ($val >= 200) {
			return "Large";
		}
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
        return view('reportsmasterlistsregbusn.exprot_view', ['keywords' => $this->keywords]);
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
