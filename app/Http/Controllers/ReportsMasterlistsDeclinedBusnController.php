<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportsMasterlists;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Exports\ReportsMasterlistsDeclinedExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
class ReportsMasterlistsDeclinedBusnController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ReportsMasterlists = new ReportsMasterlists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-declined-applications-business';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$busn_tax_year=date('Y');
		$to_date=Carbon::now()->format('Y-m-d');
        $from=Carbon::now();
        $oneMonthBefore = $from->subMonth();
        $from_date = $oneMonthBefore->format('Y-m-d');
        return view('reportsMasterListsDeclinedBusn.index')->with(compact('from_date','to_date','busn_tax_year'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ReportsMasterlists->getDeclinedBusnList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
    
			$complete_address=$this->_commonmodel->getbussinesAddress($row->id);
            // $complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
			if($row->id > 0){
				$naturss=[];
				$natur_des=$this->_ReportsMasterlists->getureofname($row->id);
				$x=1;
				foreach($natur_des as $val){
				  $naturss[]= $x.").".$val->subclass_description;	
				  $x++;
				}
				$aaa= implode(" ",$naturss);
				$nature = wordwrap($aaa, 40, "<br />\n");
				$cap_invest=$this->_ReportsMasterlists->calCapInvest($row->id);
				$t_gross=$this->_ReportsMasterlists->calTotalGross($row->id);
				$sizeByCap=$this->_ReportsMasterlists->sizeByCap($row->id,$row->b_typ_id);
			}else{
				$nature="";
			}
            $owner_name=(!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name : ''). (!empty($row->suffix) ? ', '.$row->suffix  : '');
			$arr[$i]['srno']=$sr_no;
			$arr[$i]['busns_id_no']=$row->busns_id_no;
			$arr[$i]['busn_name']=$row->busn_name;
			$arr[$i]['rpo_first_name']=$row->rpo_first_name;
			$arr[$i]['rpo_middle_name']=$row->rpo_middle_name;
			$arr[$i]['rpo_custom_last_name']=$row->rpo_custom_last_name;
			$arr[$i]['suffix']=$row->suffix;
            $arr[$i]['line_of_busn']="<div class='showLess'>".$nature."</div>" ;

            $arr[$i]['owner_name']=$owner_name;
			$arr[$i]['gender']=($row->gender==0?'Male':'Female');
			$arr[$i]['location_address']="<div class='showLess'>".wordwrap($complete_address, 40, "<br />\n")."</div>";
            $arr[$i]['ownar_address']="<div class='showLess'>".wordwrap($row->ownar_address, 40, "<br />\n")."</div>";
			$arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
			$arr[$i]['app_type']=$row->app_type;
			$arr[$i]['capital_investment']=$cap_invest;
			$arr[$i]['busp_total_gross']=$t_gross;
			$arr[$i]['sizeByCap']=$this->getSizeByCap($sizeByCap);
			$arr[$i]['payment_type']=$row->payment_type;
			$arr[$i]['btype_desc']=$row->btype_desc;
			$arr[$i]['busn_tin_no']=$row->busn_tin_no;
			$arr[$i]['busn_registration_no']=$row->busn_registration_no;
			$arr[$i]['busn_employee_no_male']=$row->busn_employee_no_male;
			$arr[$i]['busn_employee_no_female']=$row->busn_employee_no_female;
			$arr[$i]['busn_employee_total_no']=$this->getSizeByEmp($row->busn_employee_total_no);
			$arr[$i]['p_mobile_no']=$row->p_mobile_no;
			$arr[$i]['p_email_address']=$row->p_email_address;
			$arr[$i]['nature_of_business']="<div class='showLess'>".$nature."</div>";
			$arr[$i]['busn_plate_number']=$row->busn_plate_number;
			$arr[$i]['busn_app_method']=$row->busn_app_method;
			$arr[$i]['busn_bldg_area']=$row->busn_bldg_area;
			$arr[$i]['busn_bldg_total_floor_area']=$row->busn_bldg_total_floor_area;

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

	// public function exportreportsmasterlists(Request $request)
	// {
	// 	$data = DB::table('bplo_business AS bb')
	// 	->leftjoin('clients AS cl','cl.id','=','bb.client_id')
	// 	->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
	// 	->leftjoin('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
	// 	->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
	// 	->where('bb.busn_app_status',7)
	// 	->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
	// 	'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id')->get();

	// 	$filename = Str::slug('reportsDeclinedBusiness', '_') . '.xlsx';

	// 	return Excel::download(new ReportsMasterlistsDeclinedExport($data), $filename);
	// }
    
   // public function exportreportsmasterlists1(Request $request){
	   
       
       
   //      $headers = array(
   //        'Content-Type' => 'text/csv'
   //      );
        
   //      if (!File::exists(public_path()."/files")) {
   //          File::makeDirectory(public_path() . "/files");
   //      }
        
   //      $filename =  public_path("files/reportsDecliendApplications.csv");
   //      $handle = fopen($filename, 'w');
        
   //      fputcsv($handle, [ 
   //          'No.',
   //          "Owners Name",
// 			'Business Name',
   //          'Business Address',
   //          'Type of Application',
   //          'Line of Business',
   //          'Contact Information',
// 			'Email Address'
   //         ]);
   //         $i=1;
   //         foreach($data as $row){
// 				$cap_invest=$this->_ReportsMasterlists->calCapInvest($row->id);
// 				$t_gross=$this->_ReportsMasterlists->calTotalGross($row->id);
// 				// $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
// 				$complete_address=$this->_commonmodel->getbussinesAddress($row->id);
   //                  if($row->id > 0){
   //                      $naturss=[];
   //                      $natur_des=$this->_ReportsMasterlists->getureofname($row->id);
   //                      $x=1;
   //                      foreach($natur_des as $val){
   //                      $naturss[]= $x.").".$val->subclass_description;	
   //                      $x++;
   //                      }
   //                      $nature= implode(" ",$naturss);
   //                  }else{
   //                      $nature="";
   //                  }
// 				   fputcsv($handle, [ 
// 					$i,
   //                  (!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name . ' ' : ''). (!empty($row->suffix) ? ', '.$row->suffix  : ''),
// 					$row->busn_name,
   //                  (!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : ''),
// 					$row->app_type,
   //                  $nature,
   //                  $row->p_mobile_no,
// 					$row->p_email_address,
// 				   ]);
				   
// 			$i++;
   //         }
   //        fclose($handle);
   //        return Response::download($filename, "reportsDecliendApplications.csv", $headers);
   // }
   public function exportreportsmasterlists(Request $request){
		return Excel::download(new ReportsMasterlistsDeclinedExport($request->get('keywords')), 'ReportsDecliendApplications_sheet'.time().'.xlsx');
	}
}
