<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\ReportsMasterlists;
use Carbon\Carbon;

class ReportsMasterlistsEstLineOfBusinessExport implements FromArray, ShouldAutoSize
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->_ReportsMasterlists = new ReportsMasterlists(); 
        $this->data = $data;
    }

    public function array(): array
    {
        $exportData = [];
        $exportData[] = [
            'No.',
			'Business Id-No.',
			'Business Name',
			'Business Address',
			"Owner's Name",
			"Owner's Address",
			'Contact No',
			'For Year',
			'Line of Business Code',
			'Line of Business',
			'Plate No.',
			'Release Date',
			'Date Registered',
			'Type of Organization',
			'Application Method',
        ];

        // Add data rows
        $i = 1;
        foreach ($this->data as $row) {
            $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
            
			$lineofbdata=$this->_ReportsMasterlists->getBusinessPSIC($row->busn_id); 
			$lineofbusiness = "";
			$line_of_business_code=[];
            $capitalinvestment = 0;
			$grosssale = 0;
			$line_b_code = "";
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.$value->subclass_description;
				$line_of_business_code[] =$srno.'.'.$value->subclass_code;
				
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
			$lbc=implode(" ,",$line_of_business_code);
			$line_b_code = $lbc;
			
            $exportData[] = [
                $i,
                $row->busns_id_no,
                $row->busn_name,
                $complete_address,
                (!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name . ' ' : ''). (!empty($row->suffix) ? ', '.$row->suffix  : ''),
                $row->ownar_address,
                $row->p_mobile_no,
                $row->busn_tax_year,
                $line_b_code,
                $lineofbusiness,
                $row->busn_plate_number,
                Carbon::parse($row->bpi_issued_date)->format('d-M-Y'),
                Carbon::parse($row->created_at)->format('d-M-Y'),
                $row->btype_desc,
                $row->busn_app_method,
            ];

            $i++;
        }

        return $exportData;
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