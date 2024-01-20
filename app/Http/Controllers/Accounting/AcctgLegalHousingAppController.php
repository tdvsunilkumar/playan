<?php

namespace App\Http\Controllers\Accounting;
use App\Models\OnlineRptPropertyOwner;
use App\Http\Controllers\Controller;
use App\Models\BploBusiness;
use App\Models\Accounting\LegalHousingApplication;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PDF;

class AcctgLegalHousingAppController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTypeTrans = array("" => "Please Select");
    public $gender = [
        '0' => 'Female',
        '1' => 'Male'
    ];
     public function __construct(){
		$this->_LegalHousingApplication= new LegalHousingApplication(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_BploBusiness = new BploBusiness(); 
        $this->_rptpropertyowner = new OnlineRptPropertyOwner();
        $this->data = array(
            'id'=>'',
            'reference_id'=>'',
            'type_of_transaction_id'=>'',
            'app_date'=>'',
            'client_id'=>'',
            'contact_no'=>'',
            'gender'=>'',
            'civil_status'=>'',
            'status'=>'',
            'email_address'=>'',
            'current_address'=>'',
            'barangay_id'=>'',
            'month_terms'=>'',
            'terms_date_from'=>'',
            'terms_date_to'=>'',
            'total_amount'=>'',
            // 'initial_monthly'=>'',
            'status'=>'',
            'penalty'=>'',
            'terms_condition'=>''
        );  
        $this->slugs = 'legal-housing-application'; 
        foreach($this->_LegalHousingApplication->getTypeTrans() as $val) {
            $this->arrTypeTrans[$val->id]=$val->tfoc_name;
        }
        $this->trans_status = [
            '',
            'pending',
            'initial paid',
            'for payment',
            'partial',
            'forfeit',
            'complete',
        ];;
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('accounting.LegalHousingApplication.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_LegalHousingApplication->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['reference_id']=$row->reference_id;
            $arr[$i]['top_id']=$row->top_transaction_no;
            $arr[$i]['taxpayer_name']=$row->cit_fullname;
            $arr[$i]['trans_type']=$row->type_of_transaction;
            $arr[$i]['amount']=currency_format($row->total_amount);
            $arr[$i]['panalty']=$row->penalty;
            $arr[$i]['balance']=currency_format($row->remaining_amount);
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['or_date']=$row->or_date;
            $arr[$i]['app_date']=Carbon::parse($row->created_at)->toFormattedDateString();
            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/legal-housing-application/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Legal Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="'.url('/legal-housing-application/printOrderPayment/'.$row->id).'" class="mx-3 btn btn-sm  align-items-center digital-sign-btn" target="_blank" data-bs-toggle="tooltip" title="ORDER OF PAYMENT"  data-title="ORDER OF PAYMENT">
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>
                    '.$status.'
                    </div>';

            //transaction status
            switch ($row->status) {
                case 1:
                    $stat_color = 'info';
                    break;
                case 2:
                    $stat_color = 'warning';
                    break;
                    
                case 3:
                    $stat_color = 'success';
                    break;
                    
                case 4:
                    $stat_color = 'secondary';
                    break;
                    
                case 5:
                    $stat_color = 'warning';
                    break;
                    
                case 6:
                    $stat_color = 'success';
                    break;
                
                default:
                    $stat_color = 'success';
                    break;
            }
                // $arr[$i]['status'] = '<span class="btn btn-'.$stat_color.'" style="padding: 0.1rem 0.5rem !important;">'.$this->trans_status[$row->status].'</span>';
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_LegalHousingApplication->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'  Residential Name ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $data->initial_monthly = '';
        $requirements = array();
        $reqids ="";
        $arrTypeTrans = $this->arrTypeTrans;
        $gender = config('constants.citGender');
        $civil_status = config('constants.citCivilStatus');
        $residence = $this->_LegalHousingApplication->getResidence();
        $penalties = $this->_LegalHousingApplication->getPenalties();
        // $client=$this->_BploBusiness->getOwnerName();
        $app_date=$app_date = date('Y-m-d');;
        $maxReferenceId = DB::table('eco_housing_application')->max('reference_id');
        if (!$maxReferenceId) {
            $maxReferenceId = 0;
        }
        $maxReferenceId = preg_replace('~\D~', '', $maxReferenceId) ;
        $reference_id = 'HA-'. str_pad($maxReferenceId + 1, 6, '0', STR_PAD_LEFT);
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_LegalHousingApplication->find($request->input('id'));
            // $data->initial_monthly = '';
            $reference_id=$data->reference_id;
            $app_date=$data->app_date;
        }
		if($request->isMethod('post')){
            //next id
            $id = $this->_LegalHousingApplication->getLastId();
            if ($id) {
                $next_id = $id->id;
            } else {
                $next_id = 0;
            }
            $next_id = $next_id + 1;

            //for uploading
            if (isset($request->file)) {
                $file = $request->file;
                $size = $file->getSize() * .001;
                $fileSize = round($size,2);

                $filename = $next_id.'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/housing-application'), $filename);

                return [
                    'name'=>$filename,
                    'type'=>$file->getClientOriginalExtension(),
                    'size'=>$fileSize,
                    'id'=>$next_id,
                    'message'=>'success',
                ];
            }

            // saving
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['status']=1;
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_LegalHousingApplication->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Residential Name Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated  Residential Name '".$lastinsertid."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->data['remaining_amount'] = $this->data['total_amount'];

                $lastinsertid = $this->_LegalHousingApplication->addData($this->data);
                //send to top
                $transaction_no = $this->_LegalHousingApplication->transaction($lastinsertid);
                $success_msg = ' Residential Name added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added  Residential Name '".$lastinsertid."'"; 
            }
            $this->_LegalHousingApplication->uploadFile($lastinsertid, $request->upload_file);
            $this->_LegalHousingApplication->updateResidential($lastinsertid, $request->housing);
            if ($data->initial_monthly) {
                $data = $this->_LegalHousingApplication->find($lastinsertid);
                $monthly = $this->_LegalHousingApplication->updateBreakdown($data);
                $this->_LegalHousingApplication->updateData($lastinsertid,['status'=>3,'monthly_pay'=>$monthly]);
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('LegalHousingApplication.index')->with('success', __($success_msg));
    	}

        return view('accounting.LegalHousingApplication.create',compact('data','arrTypeTrans','reference_id','app_date','gender','civil_status','residence','penalties'));
	}
    
    
    public function formValidation(Request $request){
        // dd($request->all());
            $validator = \Validator::make(
            $request->all(), [
                // 'residential_name'=>'required',
                'barangay_id'=>'required',
                'month_terms'=>'required_with:initial_monthly',
                'terms_date_from'=>'required_with:initial_monthly',
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
    public function getClientDetails(Request $request,$id){
        
        $data=$this->_rptpropertyowner->getClientDetails($id);
        // $barangayDesc = $this->_barangay->findDetails($data->p_barangay_id_no);
        // $data->p_barangay_desc = $barangayDesc;
        return response()->json([
            'data' =>$data,
        ]);
    }

    //select housing
    public function getPhase(Request $request, $residential_id){
        $q = $request->input('search');
        $data = [];
        $phases = $this->_LegalHousingApplication->getPhase($q, $residential_id);
        foreach ($phases['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->phase;
        }
        $data['data_cnt']=$phases['data_cnt'];
        echo json_encode($data);
    }
    public function getBlk(Request $request,$phase_id){
        $q = $request->input('search');
        $data = [];
        $phases = $this->_LegalHousingApplication->getBlk($q, $phase_id);
        foreach ($phases['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->lot_number;
        }
        $data['data_cnt']=$phases['data_cnt'];
        echo json_encode($data);
    }
    
    public function triggerPenalties(Request $request){
        try {
            $now = Carbon::now()->toDateString();
            $housing = LegalHousingApplication::where('remaining_amount','!=',0)
                        ->where(function ($query) use ($now) {
                            $query->where('terms_date_to',$now)
                                ->WhereNull('next_penalty_date');
                        })
                        ->orWhere('next_penalty_date',$now)
                        ->get();
            foreach ($housing as $value) {
                $value->setPenalty();
            }
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
        
        
    }

    public function breakdownPrint(Request $request, $data){
        $data = LegalHousingApplication::find($data);
        // dd($data);
        PDF::SetTitle('Assistance Application Form '.$data->client->cit_fullname.'');    
        PDF::SetMargins(15, 15, 15, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 10);
        PDF::AddPage('P', 'LETTER');
       

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<p style="text-align:right">Reference No.: '.$data->top_transaction_no.'</p>',true, false, false, false, 'center');
        // PDF::ln(5);
        //Barcode
        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode($data->top_transaction_no, 'QRCODE,H', 175, 20, 15, 15, $style, 'N');

        PDF::writeHTML('<p>Applicant Name : <b>'.$data->client->cit_fullname.'</b></p>',true, false, false, false, 'center');
        PDF::ln(3);
        PDF::writeHTML('<p>Contact No. : <b>'.$data->contact_no.'</b></p>',true, false, false, false, 'center');
        PDF::ln(3);
        PDF::writeHTML('<p>Contact Housing Address : <b>'.$data->client->cit_fullname.'</b></p>',true, false, false, false, 'center');
        PDF::ln(3);
        PDF::writeHTML('<p>Total Amount : <b>'.currency_format($data->total_amount).'</b></p>',true, false, false, false, 'center');
        PDF::ln(3);
        $paid_amount = $data->total_amount - $data->remaining_amount;
        PDF::writeHTML('<p>Total Paid : <b>'.currency_format($paid_amount).'</b></p>',true, false, false, false, 'center');
        PDF::ln(10);

        // breakdown
        $table = '<table style="padding:5px;text-align:center" border="1">
        <tr>
            <td>Month</td>
            <td>Date</td>
            <td>O.R. No.</td>
            <td>Amount</td>
            <td>Paid</td>
            <td>Balance</td>
        </tr>';
        foreach ($data->breakdown as $breakdown) {
            $table .='<tr>
                        <td>'.Carbon::parse($breakdown->due_date)->format('F Y').'</td>
                        <td>'.Carbon::parse($breakdown->or_date)->toDateString().'</td>
                        <td>'.$breakdown->or_no.'</td>
                        <td>'.currency_format($breakdown->amount_due).'</td>
                        <td>'.currency_format($breakdown->amount_pay).'</td>
                        <td>'.currency_format($breakdown->remaining_amount).'</td>
                    </tr>';
        }
        $table .='</table>';
        PDF::writeHTML($table,true, false, false, false, 'center');


        PDF::Output('Assistance_Application_Form_'.$data->client->cit_fullname.'.pdf');
        
    }
}
