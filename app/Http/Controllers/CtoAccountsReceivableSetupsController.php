<?php

namespace App\Http\Controllers;

use App\Models\CtoAccountsReceivableSetup;
use App\Models\RptProperty;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

class CtoAccountsReceivableSetupsController extends Controller
{
    public $data = [];
    public $accoungGeneralLedger = [];
    public $getFundCodes = array();
    public $arrPropKinds = [];
    private $slugs;
    public function __construct(){

        $this->_receiveablesetup = new CtoAccountsReceivableSetup;
        $this->accoungGeneralLedger = $this->_receiveablesetup->getAccountGeneralLeader();
        $this->_rptproperty = new RptProperty();
        $this->data = array('id'=>'','pk_id'=>'','ars_category'=>'','ars_fund_id' => '','gl_id'=>'','sl_id' => '','ars_remarks' => '','status' => '','chart_of_account' => '');
        foreach ($this->_receiveablesetup->getFundCodes() as $val) {
             $this->getFundCodes[$val->id]=$val->code.'-'.$val->description;
         }
        foreach ($this->_rptproperty->getPropertyKinds() as $val) {
            $this->arrPropKinds[$val->id]=$val->pk_code.'-'.$val->pk_description;
        }
        $this->slugs = 'real-property/building/kind-structure';      
    }
    
    public function index(Request $request)
    {
        $kinds = $this->arrPropKinds;
       $this->is_permitted($this->slugs, 'read');
       return view('realpropreceiveablesetup.index',compact('kinds'));
        
    }
    
    public function getList(Request $request){
        $data=$this->_receiveablesetup->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;  
            $arr[$i]['srno']=$j;
            $arr[$i]['pk_description']=$row->pk_description;
            $arr[$i]['ars_category']=$row->ars_category;
            $arr[$i]['fund_id']=$row->fund_id;
            $glSlDesc = wordwrap($row->gl_sl_id, 30, "\n");
            $arr[$i]['gl_sl_id']="<span class='showLess2'>".$glSlDesc."</span>";
            $remarks = wordwrap($row->ars_remarks, 30, "\n");
            $arr[$i]['ars_remarks']="<span class='showLess2'>".$remarks."</span>";
            $arr[$i]['fullname']=$row->fullname;
            $arr[$i]['updated_at']=($row->updated_at != '')?date("d/m/Y",strtotime($row->updated_at)):'';
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/realpropertyarsetup/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Account Receivables Setup">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                </div>'  
                

                ;
               
            
            
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
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('bk_is_active' => $is_activeinactive);
        $this->_rptbuildingkind->updateActiveInactive($id,$data);
}
    public function store(Request $request){
        $glAndSlIds = [];
        $selectListData = $this->accoungGeneralLedger;
        foreach ($selectListData as $fee) {
            $glAndSlIds[$fee->gl_id.','.$fee->sl_id] = '['.$fee->code.'-'.$fee->gldescription.']=>['.$fee->prefix.'-'.$fee->description.']';
        }
        $fundCodes = $this->getFundCodes;
        $kinds = $this->arrPropKinds;
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CtoAccountsReceivableSetup::find($request->input('id'));
            $data->chart_of_account = $data->gl_id.','.$data->sl_id;
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['status'] = 1;
            unset($this->data['chart_of_account']);
            if($request->input('id')>0){
                $this->_receiveablesetup->updateData($request->input('id'),$this->data);
                $success_msg = 'Acoount Receivables setup updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');

                $this->_receiveablesetup->addData($this->data);
                $success_msg = 'Acoount Receivables setup added successfully.';
            }
            return redirect()->route('realpropertyarsetup.index')->with('success', __($success_msg));
        }
        return view('realpropreceiveablesetup.create',compact('data','glAndSlIds','fundCodes','kinds'));
        
    }

    public function formValidation(Request $request){
        //dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'pk_id' => 'required|unique:cto_accounts_receivable_setups,pk_id,' .$request->input('id'). ',id,ars_category,'.$request->input('ars_category').',gl_id,'.$request->input('gl_id').',sl_id,'.$request->input('sl_id'),
                'ars_category' => 'required|unique:cto_accounts_receivable_setups,ars_category,'.$request->input('id'). ',id,pk_id,'.$request->input('pk_id'),
                'ars_fund_id'  => 'required',
                'gl_id' => 'required',
                'sl_id' => 'required',
            ],[
                'pk_id.required' => 'Required Field',
                'ars_category.required' => 'Required Field',
                'ars_fund_id.required' => 'Required Field',
                'gl_id.required' => 'Required Field',
                'sl_id.required' => 'Required Field',
                'ars_remarks.required' => 'Required Field',
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
