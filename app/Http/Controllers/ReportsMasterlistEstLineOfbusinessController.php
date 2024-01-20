<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportsMasterlists;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Exports\ReportsMasterlistsEstLineOfBusinessExport;
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
class ReportsMasterlistEstLineOfbusinessController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ReportsMasterlists = new ReportsMasterlists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-lines-of-business-of-registered-establishment';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$busn_tax_year=date('Y');
		$to_date=Carbon::now()->format('Y-m-d');
        $from_date=Carbon::now()->format('Y-m-d');
		$from_date=Date('Y-m-d', strtotime('-30 days'));
        return view('ReportsMasterListsEstLineOfbusiness.index')->with(compact('from_date','to_date','busn_tax_year'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ReportsMasterlists->getListEstLineOfbusiness($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0; 
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
			$complete_address=$this->_commonmodel->getbussinesAddress($row->id);
			$lineofbdata=$this->_ReportsMasterlists->getBusinessPSIC($row->busn_id); 
			$lineofbusiness = "";
			$line_of_business_code=[];
            $capitalinvestment = 0;
			$grosssale = 0;
			$line_b_code = "";
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
				$line_of_business_code[] =$srno.'.'.wordwrap($value->subclass_code, 40, "<br />\n")."<br>";
				
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
			$lbc=implode(" ",$line_of_business_code);
			$line_b_code = wordwrap($lbc, 40, "<br />\n");
			
			$arr[$i]['srno']=$sr_no;
			$arr[$i]['busns_id_no']=$row->busns_id_no;
			$arr[$i]['busn_name']=$row->busn_name;
			$arr[$i]['location_address']="<div class='showLess'>".wordwrap($complete_address, 40, "<br />\n")."</div>";
            $arr[$i]['full_name']=$row->full_name;
			$arr[$i]['ownar_address']="<div class='showLess'>".wordwrap($row->ownar_address, 40, "<br />\n")."</div>";
			$arr[$i]['p_mobile_no']=$row->p_mobile_no;
			$arr[$i]['busn_tax_year']=$row->busn_tax_year;
			$arr[$i]['line_of_busn_code']=$line_b_code; 
			$arr[$i]['line_of_busn']="<div class='showLess'>".$lineofbusiness."</div>";
			$arr[$i]['busn_plate_number']=$row->busn_plate_number;
			$arr[$i]['bpi_issued_date']=Carbon::parse($row->bpi_issued_date)->format('d-M-Y');
			$arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
			$arr[$i]['btype_desc']=$row->btype_desc;
			$arr[$i]['busn_app_method']=$row->busn_app_method;
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
		$data=$this->_ReportsMasterlists->getexportlistOfbusiness($request);
		$filename = Str::slug('reportsEstablishmentLineOfBusiness', '_') . '.xlsx';
		return Excel::download(new ReportsMasterlistsEstLineOfBusinessExport($data), $filename);
	}
    
   
}
