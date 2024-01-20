<?php

namespace App\Http\Controllers\Treasury;

use App\Http\Controllers\Controller;
use App\Models\Treasury\AccountReceivableHousing;
use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class AccountReceivableHousingController extends Controller
{
    public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_accrcblhousing = new AccountReceivableHousing(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'housing-ar';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrDepaertments = array('0'=>'All');
         foreach ($this->_accrcblhousing->GetCemeteryName() as $val) {
             $arrDepaertments[$val->id]=$val->cem_name;
         }
        $arrlocations = array(""=>"Select Location");
        foreach ($this->_accrcblhousing->Getlocationarray() as $val) {
             $arrlocations[$val->id]=$val->brgy_name;
         } 
        // print_r($arrlocations); exit;
        return view('treasury.accountreceivable.housing.index',compact('startdate','enddate','arrDepaertments','arrlocations'));
    }

    public function getpaymentlist(Request $request){
    	$id = $request->input('id');

    	$data=$this->_accrcblhousing->getpaymentList($request,$id);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ordate']="";
            $arr[$i]['orno']=$row->or_no;
            $arr[$i]['amount']=$row->cem_total_amount;
            $arr[$i]['payment']=$row->cem_paid_amount;
            $arr[$i]['balance']=$row->cem_remaining_balance;
            $arr[$i]['status']=($row->cem_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Paid</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');;
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

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_accrcblhousing->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']='<input type="checkbox" class="select_item" name="selected_items['. $row->id .']" value="'. $row->id .'">';
            $arr[$i]['transactionno']=$row->transaction_no;
            $arr[$i]['name']=$row->cit_fullname;
            $arr[$i]['address']=$row->full_address;
            $arr[$i]['location']=$row->brgy_name;
            $arr[$i]['totalamt']=number_format($row->total_amount, 2, '.', ',');;
            $arr[$i]['remainingamt']=number_format($row->remaining_amount, 2, '.', ',');;
            $arr[$i]['topno']=str_pad($row->top_transaction_id, 6, '0', STR_PAD_LEFT);
            $arr[$i]['orno']=$row->or_no;
            $arr[$i]['oramount']=number_format($row->total_paid_amount, 2, '.', ','); 
            $arr[$i]['status']=$row->status;
            $arr[$i]['action']='<a href="javascript:;" class="action-btn viewdetails bg-info btn m-1 btn-sm align-items-center" title="view summary" data-row-id="'.$row->id.'" data-row-code="'.$row->transaction_no.'" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-file-text text-white"></i></a>';
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
    
    public function exportdepartmentalcollection(Request $request){
        $data =$this->_accrcblhousing->getListexport($request);
		
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
            'TOP NO',
			'O.R.Number',
			'Date',
			'Amount',
			'Details',
			'Status',
			'Cashier'
           ]);
           $i=1;
           foreach($data['data'] as $row){
				$fullname = $row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
				$Date = date("M d, Y",strtotime($row->created_at));
				if($row->status == '1'){ $status = "active"; } else{ $status = "Cancelled"; }
				   fputcsv($handle, [ 
					$i,
					$fullname,
					$row->busn_name,
					$row->cashier_particulars,
                    $row->transaction_no,
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
