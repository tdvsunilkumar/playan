<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CtoCashReciept;
use Carbon\Carbon;

class TreasuryCashReceiptController extends Controller
{
    //
    public function __construct(){
        $this->_CashReciept = new CtoCashReciept(); 
        $this->data = array(
            'id'=>'',
            'fund_code_id'=>'',
            'type_of_charge_id'=>'',
            'date'=>date("Y-m-d"),
            'gl_id'=>'',
            'amount'=>'',
            'or_no'=>'',
            'particulars'=>'',
            'is_income'=>0,
            );
        $this->slugs = 'treasury/cash-receipt';
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('treasury.cash-receipt.index');
    }
    public function lists(Request $request){
        // $this->is_permitted($this->slugs, 'read');
        $data=CtoCashReciept::getList($request);
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
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasury/cash-receipt/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Cash Receipt">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Citizen"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Citizen"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['date']=$row->date;
            $arr[$i]['or_num']=$row->or_no;
            $arr[$i]['type_of_charge']=$row->type_of_charge;
            $arr[$i]['gl']=$row->gl_desc;
            $arr[$i]['particulars']=$row->particulars;
            $arr[$i]['debit']=currency_format(0);
            $arr[$i]['credit']=currency_format(0);
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
            $data =CtoCashReciept::find($id);
        } 
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['is_active'] = 1;
            // dd($request->all(), $this->data);
            
            if($request->id>0){
                CtoCashReciept::find($request->id)->update($this->data);
                $success_msg = 'Updated successfully.';
            }else{
                $request->id = CtoCashReciept::create($this->data);
                $success_msg = 'Added successfully.';
            }

            return redirect()->back();
        }
        $fund_code = $this->_CashReciept->getFundCodes();
        $type_of_charge = $this->_CashReciept->getTypeOfCharges();
        $gl = $this->_CashReciept->getGl();
        return view('treasury.cash-receipt.create',compact('data','fund_code','type_of_charge','gl'));
    }
    public function validation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'fund_code_id'=>'required',
                'type_of_charge_id'=>'required',
                'date'=>'required',
                // 'gl_id'=>'required',
                'amount'=>'required',
                'particulars'=>'required',
                'is_income'=>'required',
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
