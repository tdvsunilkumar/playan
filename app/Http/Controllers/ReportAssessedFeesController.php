<?php

namespace App\Http\Controllers;
use App\Models\BploBusiness;
use App\Models\CommonModelmaster;
use App\Models\ReportAssessedFees;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use File;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Response;
class ReportAssessedFeesController extends Controller
{
    private $carbon;
    private $slugs;
    public $status = [
        '0' => 'Not Completed',
        '1' => 'Completed/For Verification',
        '2' => 'For Endorsement',
        '3' => 'For Assessment',
        '4' => 'For Payment',
        '5' => 'For Issuance',
        '6' => 'License Issued',
        '7' => 'Declined',
        '8' => 'Cancelled Permit'
    ];
    public function __construct(Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->_BploBusiness = new BploBusiness();
		$this->_ReportAssessedFees = new ReportAssessedFees();
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'reports-masterlists-assessed-fees';
    }    

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $busn_tax_year=date('Y');
        
		$from_date = date('Y-m-d');   $to_date = date('Y-m-d');
		$from_date=Date('Y-m-d', strtotime('-30 days'));
        return view('reportAssessedFees.index')->with(compact('to_date','from_date','busn_tax_year',));
    }

    public function getList(Request $request){
       
      $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(1);
      $tfocidarray = array($tfoctaxesestial->tfoc_id);

      $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(2);
      $nontfocidarray = array($tfoctaxesestial->tfoc_id);

      $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(2);
      $sanitarytfocidarray = array($tfoctaxesestial->tfoc_id);
      
       $this->is_permitted($this->slugs, 'read');
       $data=$this->_ReportAssessedFees->getList($request);
	   /* echo '<pre>';
	   print_r($data);die; */
       $arr=array();
       $i="0";    
       $sr_no=(int)$request->input('start')-1; 
       $sr_no=$sr_no>0? $sr_no+1:0;
       foreach ($data['data'] as $row){
           $sr_no=$sr_no+1;
           
		   
		    $lineofbdata=$this->_ReportAssessedFees->getBusinessPSIC($row->busn_id); 
			$lineofbusiness = "";
            $capitalinvestment = 0;
			$grosssale = 0;
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
			

        $businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($tfocidarray,$row->id);
        $essetialamt = $businesstaxdata->amount;

        $businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($nontfocidarray,$row->id);
        $nonessetialamt = $businesstaxdata->amount;

        $businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($sanitarytfocidarray,$row->id);
        $sanitaryfee = $businesstaxdata->amount;
		   
           $serch_status =config('constants.arrBusinessApplicationStatus');
           $arr[$i]['srno']=$sr_no;
           $arr[$i]['permit_no']=$row->bpi_permit_no;
           $arr[$i]['busn_id_no']=$row->busns_id_no;
           $arr[$i]['owner']=$row->full_name;
           $arr[$i]['last_name']=$row->rpo_custom_last_name;
           $arr[$i]['first_name']=$row->rpo_first_name;
           $arr[$i]['busn_name']=$row->busn_name;
		   $addressnew = wordwrap($this->_commonmodel->getbussinesAddress($row->id), 40, "<br />\n");
           $arr[$i]['busn_address']="<div class='showLess'>".$addressnew."</div>";
           $arr[$i]['app_type']=$row->app_type;
           $arr[$i]['busn_plan']="<div class='showLess'>".$lineofbusiness."</div>";
           // $arr[$i]['contacts']=$row->p_telephone_no != NULL ? $row->p_telephone_no."/":"" . $row->p_mobile_no != NULL ? $row->p_mobile_no:"";
           $arr[$i]['contacts'] = ($row->p_telephone_no != NULL ? $row->p_telephone_no . " / " : "") . ($row->p_mobile_no != NULL ? $row->p_mobile_no : "");

           $arr[$i]['busn_type']= $row->btype_desc;
           $arr[$i]['no_emp']= $row->busn_employee_no_female +  $row->busn_employee_no_male;
           $arr[$i]['busp_capital_investment']=number_format($capitalinvestment, 2, '.', ',');
           $arr[$i]['busp_essential']=number_format($row->busp_essential, 2, '.', ',');
           $arr[$i]['busp_non_essential']=number_format($row->busp_non_essential, 2, '.', ',');
           $arr[$i]['busp_tax_essential']=number_format($essetialamt, 2, '.', ',');
           $arr[$i]['busp_tax_non_essential']=number_format($nonessetialamt, 2, '.', ',');
           $arr[$i]['sanitary_fee']=number_format($sanitaryfee, 2, '.', ',');
          
           $i++;
       }
       
       $totalRecords=$data['data_cnt'];
       $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
       );
       echo json_encode($json_data);
   }

	public function exportreportsmasterlists(Request $request){
	  $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(1);
      $tfocidarray = array($tfoctaxesestial->tfoc_id);

      $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(2);
      $nontfocidarray = array($tfoctaxesestial->tfoc_id);

      $tfoctaxesestial = $this->_ReportAssessedFees->gettfocsids(2);
      $sanitarytfocidarray = array($tfoctaxesestial->tfoc_id);
        $data =$this->_ReportAssessedFees->getListexport($request);

        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/reportassessedfeeslist.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [
            'No.',
			'Permit No.',
			'BUSINESS ID-No.',
			'Name of Proprietor',
			'Surname',
			'First Name',
			'Name of Establishment',
			'Business Address',
			'Type of Application',
			'Line of Business',
			'Contact No.',
			'Type of Ownership',
			'No. of Employees',
			'Capital Investment',
			'Gross Essential',
			'Gross Non-Essential',
			'Business Tax-Non Essential',
			'Business Tax-Essential',
			'Sanitary Inspection Fee'
           ]);
           $i=1;
           foreach($data['data'] as $row){
			    $businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($tfocidarray,$row->id);
				$essetialamt = $businesstaxdata->amount;

				$businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($nontfocidarray,$row->id);
				$nonessetialamt = $businesstaxdata->amount;

				$businesstaxdata = $this->_ReportAssessedFees->getBusinesstax($sanitarytfocidarray,$row->id);
				$sanitaryfee = $businesstaxdata->amount;
				
				$lineofbdata=$this->_ReportAssessedFees->getBusinessPSIC($row->busn_id); 
				$lineofbusiness = "";
				$capitalinvestment = 0;
				$grosssale = 0;
				foreach ($lineofbdata as $key => $value) { 
				$srno = $key +1;
					$lineofbusiness .=$srno.'.'.$value->subclass_description;
					$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
					$grosssale = $grosssale + $value->busp_total_gross;
				}
				
			   fputcsv($handle, [
				$i,
				$row->bpi_permit_no,
				$row->busns_id_no,
				$row->full_name,
				$row->rpo_custom_last_name,
				$row->rpo_first_name,
				$row->busn_name,
				$this->_commonmodel->getbussinesAddress($row->id),
				$row->app_type,
				$lineofbusiness,
				($row->p_telephone_no != NULL ? $row->p_telephone_no . " / " : "") . ($row->p_mobile_no != NULL ? $row->p_mobile_no : ""),
				$row->btype_desc,
				$row->busn_employee_no_female +  $row->busn_employee_no_male,
				number_format($capitalinvestment, 2, '.', ','),
				number_format($row->busp_essential, 2, '.', ','),
				number_format($row->busp_non_essential, 2, '.', ','),
				number_format($essetialamt, 2, '.', ','),
				number_format($nonessetialamt, 2, '.', ','),
				number_format($sanitaryfee, 2, '.', ',')
			   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "reportassessedfeeslist.csv", $headers);
      }
}
