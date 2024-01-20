<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\Bplotaxcredit;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class BplotaxcreditController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_taxcredit = new Bplotaxcredit(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'treasury-business-taxcreditfile';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrBusinessnames = array('0'=>'All');
         foreach ($this->_taxcredit->GetBussinessids() as $val){
         	if($val->busns_id_no)
             $arrBusinessnames[$val->id]="[".$val->busns_id_no."]=>[".$val->busn_name."]";
         }
        return view('Bplo.taxcredit.index',compact('startdate','enddate','arrBusinessnames'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_taxcredit->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['businessidno']=$row->busns_id_no;
            $arr[$i]['taxpayername']=$row->full_name;
            $businessname = wordwrap($row->busn_name, 100, "<br />\n");
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($row->tax_credit_sl_id,$row->tax_credit_gl_id);
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $accountdescription = wordwrap($accountdescription, 100, "<br />\n");
            $arr[$i]['businessname']="<div class='showLess'>".$businessname."</div>";
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_amount']=number_format($row->total_amount, 2);
            $arr[$i]['date']=$row->cashier_or_date;
            $arr[$i]['credit_amount']=number_format($row->tax_credit_amount, 2);
            $arr[$i]['description']="<div class='showLess'>".$accountdescription."</div>";
             $arr[$i]['status']=($row->tax_credit_is_useup > 0?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Applied</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['details']='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm viewdetails align-items-center"  title="view" previouscashierid='.$row->tax_credit_is_useup.' data-title="View" id='.$row->id.'>
                    <i class="ti-eye text-white"></i>
                    </a></div>';
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

    public function viewdetails(Request $request){
    	$id = $request->input('id'); 
        $precashid = $request->input('precashid');
        if($precashid <= 0){
            $data = $this->_taxcredit->getdetails($id);
            $returarray = array(); $html="";
            $returarray['reforno'] = $data->or_no;
            $returarray['ordate'] = $data->cashier_or_date;
            $returarray['oramount'] = $data->total_amount;
            $returarray['precashid'] = $precashid;
            $returarray['cashier'] = $data->cashier; $accountdescription ="";
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['chartofaccount'] = $accountdescription; 
        }else{
            $data = $this->_taxcredit->getdetails($id);
            $returarray = array(); $html="";
            $returarray['reforno'] = $data->or_no;
            $returarray['ordate'] = $data->cashier_or_date;
            $returarray['oramount'] = $data->total_amount;
            $returarray['cashier'] = $data->cashier; $accountdescription ="";
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['chartofaccount'] = $accountdescription;
            $returarray['precashid'] = $precashid;
            $data = $this->_taxcredit->getdetailsutilized($id);
            $returarray['currentreforno'] = $data->or_no;
            $returarray['currentordate'] = $data->cashier_or_date;
            $returarray['currentoramount'] = $data->tax_credit_amount;
            $returarray['currentcashier'] = $data->cashier; $accountdescription ="";
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['currentchartofaccount'] = $accountdescription;  


        }
    	
        echo json_encode($returarray);
    }
    
    public function exportdepartmentalcollection(Request $request){
        $data =$this->_taxcredit->getList($request);
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/departmentalcollectionlists.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
           'No.',
			'Taxpayer.',
			'Permit No',
			'Business Name',
			'Particulars',
			'O.R.Number',
			'Date',
			'Amount',
			'Details',
			'Status',
			'Cashier'
           ]);
           $i=1;
           foreach($data['data'] as $row){
				$fullname = $row->full_name;
				$Date = date("M d, Y",strtotime($row->created_at));
				if($row->status == '1'){ $status = "active"; } else{ $status = "Cancelled"; }
				   fputcsv($handle, [ 
					$i,
					$fullname,
					$row->busn_name,
					$row->cashier_particulars,
					$row->or_no,
					$Date,
					$row->total_amount,
					$row->total_amount,
					$status,
					$row->cashier
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "departmentalcollectionlists.csv", $headers);
      }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'busloc_desc'=>'required|unique:bplo_business_locations,busloc_desc,'.(int)$request->input('id'),
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
}
