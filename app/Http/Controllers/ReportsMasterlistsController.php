<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportsMasterlists;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Exports\ReportsMasterlistsExport;
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
class ReportsMasterlistsController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ReportsMasterlists = new ReportsMasterlists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-business-establishments';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$busn_tax_year=date('Y');
		$to_date=Carbon::now()->format('Y-m-d');
        $from_date=Carbon::now()->format('Y-m-d');
		$from_date=Date('Y-m-d', strtotime('-30 days'));
        return view('reportsmasterlists.index')->with(compact('from_date','to_date','busn_tax_year'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ReportsMasterlists->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
    
			$complete_address=$this->_commonmodel->getbussinesAddress($row->id);
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
			}else{
				$nature="";
			}
			
			
			$arr[$i]['srno']=$sr_no;
			$arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['bpi_permit_no']=$row->bpi_permit_no;
			$arr[$i]['busn_name']=$row->busn_name;
			$arr[$i]['rpo_first_name']=$row->rpo_first_name;
			$arr[$i]['rpo_middle_name']=$row->rpo_middle_name;
			$arr[$i]['rpo_custom_last_name']=$row->rpo_custom_last_name;
			$arr[$i]['suffix']=$row->suffix;
			$arr[$i]['gender']=($row->gender==0?'Male':'Female');
			$arr[$i]['location_address']="<div class='showLess'>".wordwrap($complete_address, 40, "<br />\n")."</div>";
            $arr[$i]['ownar_address']="<div class='showLess'>".wordwrap($row->ownar_address, 40, "<br />\n")."</div>";
			$arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
			$arr[$i]['app_type']=$row->app_type;
			$arr[$i]['capital_investment']=$cap_invest;
			$arr[$i]['busp_total_gross']=$t_gross;
			$arr[$i]['payment_type']=$row->payment_type;
			$arr[$i]['btype_desc']=$row->btype_desc;
			$arr[$i]['total_paid_surcharge']=$row->total_paid_surcharge;
			$arr[$i]['total_paid_interest']=$row->total_paid_interest;
			$arr[$i]['total_paid_amount']=$row->total_paid_amount;
			$arr[$i]['or_no']=$row->or_no;
			$arr[$i]['cashier_or_date']=$row->cashier_or_date;
			$arr[$i]['busn_tin_no']=$row->busn_tin_no;
			$arr[$i]['busn_registration_no']=$row->busn_registration_no;
			$arr[$i]['busn_employee_no_male']=$row->busn_employee_no_male;
			$arr[$i]['busn_employee_no_female']=$row->busn_employee_no_female;
			$arr[$i]['busn_employee_total_no']=$row->busn_employee_total_no;
			$arr[$i]['p_mobile_no']=$row->p_mobile_no;
			$arr[$i]['p_email_address']=$row->p_email_address;
			$arr[$i]['nature_of_business']="<div class='showLess'>".$nature."</div>";
			$arr[$i]['bpi_remarks']=$row->bpi_remarks;
			$arr[$i]['busn_plate_number']=$row->busn_plate_number;
			$arr[$i]['busn_app_method']=$row->busn_app_method;
			$arr[$i]['bpi_issued_date']=Carbon::parse($row->bpi_issued_date)->format('d-M-Y');
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

	public function exportreportsmasterlists(Request $request)
	{
		$data =  DB::table('bplo_business AS bb')
				->join('clients AS cl','cl.id','=','bb.client_id')
				->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
				->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
				->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
				->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
				->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
				->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
				'apt.name As payment_type','bbt.btype_desc As btype_desc','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
				'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date')->groupBy('bbpi.bpi_permit_no')->get();

		$filename = Str::slug('reportsmasterlists', '_') . '.xlsx';

		return Excel::download(new ReportsMasterlistsExport($data), $filename);
	}
    
    
   public function exportreportsmasterlists1(Request $request){
	   
        $data =  $this->_ReportsMasterlists->exportreportsmaster($request);
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/reportsmasterlists.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
           'No.',
			'Business Id-No.',
			'Permit No.',
			'Business Name',
			'First Name',
			'Middle Name',
			'Last Name',
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
			'No. of Employees',
			'Contact No',
			'Email Address',
			'Nature of Business',
			'Remarks',
			'Plate No.',
			'Application Method',
			'Date Issued',
			'Business Area',
			'Floor Area',
           ]);
           $i=1;
           foreach($data['data'] as $row){
				$complete_address=$this->_commonmodel->getbussinesAddress($row->id);
				// $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
				if($row->id > 0){
					$naturss=[];
					$natur_des=$this->_ReportsMasterlists->getureofname($row->id);
					$x=1;
					foreach($natur_des as $val){
					  $naturss[]= $x.").".$val->subclass_description;	
					  $x++;
					}
					$aaa= implode(" ",$naturss);
					$nature = $aaa;
					$cap_invest=$this->_ReportsMasterlists->calCapInvest($row->id);
					$t_gross=$this->_ReportsMasterlists->calTotalGross($row->id);
				}else{
					$nature="";
				}
				   fputcsv($handle, [ 
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
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "reportsmasterlists.csv", $headers);
   }
}
