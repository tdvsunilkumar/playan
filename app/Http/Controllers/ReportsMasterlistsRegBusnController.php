<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportsMasterlists;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Exports\ReportsMasterlistsRegBusnExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
use Session;
class ReportsMasterlistsRegBusnController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ReportsMasterlists = new ReportsMasterlists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-registered-business';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$busn_tax_year=date('Y');
		$to_date=Carbon::now()->format('Y-m-d');
        $from=Carbon::now();
        $oneMonthBefore = $from->subMonth();
        $from_date = $oneMonthBefore->format('Y-m-d');
        return view('reportsmasterlistsregbusn.index')->with(compact('from_date','to_date','busn_tax_year'));
    }



    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ReportsMasterlists->getListBusnReg($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
    
			// $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ', ' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ', ' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '') . (!empty($brgy_det) ? $brgy_det : '');
			// $complete_address2=$this->_commonmodel->getbussinesAddress($row->id);
			$complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ', ' : '') . (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ', ' : '') . (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ', ' : '') . (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . '' : '');
                $complete_address2=$this->_commonmodel->getbussinesAddressBarangay($row->busn_office_main_barangay_id);
                $totalAddress=($complete_address) ? $complete_address. ', '.$complete_address2 : $complete_address2;
						// $event->sheet->setCellValue('K'.$rows, (($complete_address) ? $complete_address. ', '.$complete_address2 : $complete_address2));
			if($row->id > 0){
				$naturss=[];
				$natur_des=$this->_ReportsMasterlists->getureofname($row->busn_id);
				$x=1;
				foreach($natur_des as $val){
				  $naturss[]= $x.").".$val->subclass_description;	
				  $x++;
				}
				$aaa= implode(" ",$naturss);
				$nature = wordwrap($aaa, 40, "<br />\n");
				$cap_invest=$this->_ReportsMasterlists->calCapInvest($row->busn_id);
				$t_gross=$this->_ReportsMasterlists->calTotalGross($row->busn_id);
				$sizeByCap=$this->_ReportsMasterlists->sizeByCap($row->busn_id,$row->b_typ_id);
			}else{
				$nature="";
			}
			$arr[$i]['srno']=$sr_no;
            $arr[$i]['bpi_permit_no']=$row->bpi_permit_no;
			$arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['line_of_busn']="<div class='showLess'>".$nature."</div>" ;
            $arr[$i]['owner_name']=$row->full_name;
			$arr[$i]['location_address']="<div class='showLess'>".wordwrap($totalAddress, 40, "<br />\n")."</div>";
			$arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
			$arr[$i]['app_type']=$row->app_type;
			
			$arr[$i]['sizeByCap']=$this->getSizeByCap($sizeByCap);
			
			$arr[$i]['btype_desc']=$row->btype_desc;
			
			$arr[$i]['busn_registration_no']=$row->busn_registration_no;
		
			$arr[$i]['busn_employee_total_no']=$this->getSizeByEmp($row->busn_employee_total_no);
			$arr[$i]['p_mobile_no']=$row->p_mobile_no;
			$arr[$i]['p_email_address']=$row->p_email_address;
			
			$arr[$i]['bpi_issued_date']=($row->bpi_issued_date != null) ? Carbon::parse($row->bpi_issued_date)->format('d-M-Y') : "";
			
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
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
    public function exportreportsmasterlists (Request $request){
        return Excel::download(new ReportsMasterlistsRegBusnExport($request->get('keywords')), 'reportsRegisteredBusiness_sheet'.time().'.xlsx');
    }
    // public function exportreportsmasterlists(Request $request)
	// {
	// 	$data =   DB::table('bplo_business AS bb')
	// 	->join('clients AS cl','cl.id','=','bb.client_id')
	// 	->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
	// 	->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
	// 	->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
	// 	->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
	// 	->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
	// 	->where('bbt.id','!=',3)
	// 	->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
	// 	'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
	// 	'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date')->groupBy('bbpi.bpi_permit_no')->get();

	// 	$filename = Str::slug('reportsRegisteredBusiness', '_') . '.xlsx';

	// 	return Excel::download(new ReportsMasterlistsRegBusnExport($data), $filename);
	// }
    
}
