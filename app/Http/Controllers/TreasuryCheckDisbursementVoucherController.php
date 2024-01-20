<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CtoCheckDisbursement;

class TreasuryCheckDisbursementVoucherController extends Controller
{
    public function __construct(){
        $this->_CheckDisbursement = new CtoCheckDisbursement(); 
        $this->data = array(
            'id'=>'',
            'fund_code_id'=>'',
            'payee'=>'',
            'disbursement_no'=>'',
            'date'=>date("Y-m-d"),
            'voucher_no'=>'',
            'amount'=>'',
            'particulars'=>'',
            'payment_type'=>'',
            'transaction_no'=>CtoCheckDisbursement::getNextTransactionNo(),
            );
        $this->slugs = 'treasury/journal-entries/check-disbursement';
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $segment = 'check disbursement';
        return view('treasury.journal-entries.check-disbursement.index')->with(compact('segment'));
    }
    public function lists(Request $request){
        // $this->is_permitted($this->slugs, 'read');
        $data=CtoCheckDisbursement::getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        $balance = 0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasury/journal-entries/check-disbursement/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Cash Receipt">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Citizen"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Citizen"></a>';  
            }
            // dd($row->modified);
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['date']=\Carbon\Carbon::parse($row->date)->format('d-M-Y');
            $arr[$i]['voucher_no']='<strong class="text-primary">' .$row->voucher_no. '</strong>';
            $arr[$i]['particulars']=$row->particulars;
            $arr[$i]['transaction_type']=$row->transaction_type->name;
            // $arr[$i]['gl']=$row->gl_desc;
            $arr[$i]['debit']=currency_format(0);
            $arr[$i]['credit']=currency_format(0);
            $arr[$i]['last_modified']='<strong>'.$row->modified->hr_employee->fullname.'</strong><br/>'. \Carbon\Carbon::parse($row->modified_at)->format('d-M-Y h:i A');
            if ($row->is_income != 1) {
                $arr[$i]['credit']=currency_format($row->amount);
                $balance -= $row->amount;
            } else {
                $arr[$i]['debit']=currency_format($row->amount);
                $balance += $row->amount;
            }
            $arr[$i]['balance']=currency_format($balance);
    
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
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

    public function store(Request $request){
        $id =  $request->input('id');
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        $arrdocDtls="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data =CtoCheckDisbursement::find($id);
        } 
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['is_active'] = 1;
            // dd($request->all(), $this->data);
            
            if($request->id>0){
                CtoCheckDisbursement::find($request->id)->update($this->data);
                $success_msg = 'Updated successfully.';
            }else{
                $request->id = CtoCheckDisbursement::create($this->data);
                $success_msg = 'Added successfully.';
            }
            return redirect()->back();
        }
        $fund_code = $this->_CheckDisbursement->getFundCodes();
        $payment_type = $this->_CheckDisbursement->getPaymentTypeCodes();
        return view('treasury.journal-entries.check-disbursement.create',compact('data','fund_code','payment_type'));
    }
    public function validation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'fund_code_id'=>'required',
                'disbursement_no'=>'required',
                'date'=>'required',
                'amount'=>'required',
                'particulars'=>'required',
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
