<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\ReportsMasterlists;
use Carbon\Carbon;

class ReportsMasterlistsExport implements FromArray, ShouldAutoSize
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
			'Permit No',
			'Business Name',
			'Last Name',
			'First Name',
			'Middle Name',
			'Extension Name',
			'Gender',
			'Location of Business',
			'Address of Owner',
			'Application Date',
			'Type of Application',
			'Capital Investment',
			'Gross Sales',
			'Mode of Payment',
			'Type of Business',
			'Surcharge',
			'Interest',
			'Total Amount Paid',
			'Or Number',
			'Or Date',
			'TIN',
			'Registration No.',
			'Number of Male',
			'Number of Female',
			'No. of Employess',
			'Contact No',
			'Email Address',
			'Nature of Business',
			'Remarks',
			'Plate No.',
			'Application Method',
			'Date Issued',
			'Business Area',
			'Floor Area',
        ];

        // Add data rows
        $i = 1;
        foreach ($this->data as $row) {
				$complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
				if($row->id > 0){
					$natur_des=$this->_ReportsMasterlists->getureofname($row->id);
					$x=1;
					foreach($natur_des as $val){
					$naturss[]= $x.").".$val->subclass_description;	
					$x++;
					}
					$nature= implode(" ",$naturss);
					$cap_invest=$this->_ReportsMasterlists->calCapInvest($row->id);
					$t_gross=$this->_ReportsMasterlists->calTotalGross($row->id);
				}else{
					$nature="";
				}
            $exportData[] = [
                $i,
                $row->busns_id_no,
                $row->bpi_permit_no,
                $row->busn_name,
                $row->rpo_first_name,
                $row->rpo_middle_name,
                $row->rpo_custom_last_name,
                $row->suffix,
                ($row->gender==0?'Male':'Female'),
                $complete_address,
                $row->ownar_address,
                Carbon::parse($row->created_at)->format('d-M-Y'),
                $row->app_type,
                $cap_invest,
                $t_gross,
                $row->payment_type,
                $row->btype_desc,
                $row->total_paid_surcharge,
                $row->total_paid_interest,
                $row->total_paid_amount,
                $row->or_no,
                $row->cashier_or_date,
                $row->busn_tin_no,
                $row->busn_registration_no,
                $row->busn_employee_no_male,
                $row->busn_employee_no_female,
                $row->busn_employee_total_no,
                $row->p_mobile_no,
                $row->p_email_address,
                $nature,
                $row->bpi_remarks,
                $row->busn_plate_number,
                $row->busn_app_method,
                Carbon::parse($row->bpi_issued_date)->format('d-M-Y'),
                $row->busn_bldg_area,
                $row->busn_bldg_total_floor_area,
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