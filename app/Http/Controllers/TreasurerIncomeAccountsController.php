<?php

namespace App\Http\Controllers;
use App\Imports\IncomeAccount;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\TreasurerIncomeAccount;
use App\Models\CommonModelmaster;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgFundCode;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class TreasurerIncomeAccountsController extends Controller
{
      public $data = [];
    public $postdata = [];
    public $arrAccGenLedCode = array(""=>"Please Select");
    public $arrFunCode = array(""=>"Please Select");
    public $arrDepCode = array(""=>"Please Select");
    public function __construct(){
		$this->_incomeaccount = new TreasurerIncomeAccount(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','agl_code'=>'','fund_code'=>'','tia_fund_code'=>'','tia_account_code'=>'','tia_account_description'=>'','tia_account_short_name'=>'','tia_initial_amount'=>'0','tia_remarsk'=>'','tia_applicable'=>'','tia_tax_cy'=>'','tia_tax_py'=>'','tia_discount_code'=>'','tia_penalty_cy'=>'','tia_penalty_py'=>'','tia_tax_credit'=>''); 
        foreach ($this->_incomeaccount->getAccGenLedCode() as $val) {
            $this->arrAccGenLedCode[$val->id]=$val->code.'-'.$val->description;
        } 
        foreach ($this->_incomeaccount->getFunCode() as $val) {
            $this->arrFunCode[$val->id]=$val->code.'-'.$val->description;
        } 
        foreach ($this->_incomeaccount->getDepCode() as $val) {
            $this->arrDepCode[$val->id]=$val->code.'-'.$val->name;
        }     
    }
    public function index(Request $request)
    {
        
            $fund_codes = (new AcctgFundCode)->allFundCodes();
            $gl_accounts = (new AcctgAccountGeneralLedger)->allGLAccounts();
            return view('incomeaccount.index')->with(compact('gl_accounts', 'fund_codes'));
        
    }


    public function getList(Request $request){
        $data=$this->_incomeaccount->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['tia_account_code']=$row->tia_account_code;
            $arr[$i]['tia_account_description']=$row->tia_account_description;
            $arr[$i]['code']=$row->code;
            $arr[$i]['tia_tax_cy']=$row->tia_tax_cy;
            $arr[$i]['tia_tax_py']=$row->tia_tax_py;
            $arr[$i]['tia_discount_code']=$row->tia_discount_code;
            $arr[$i]['tia_penalty_cy']=$row->tia_penalty_cy;
            $arr[$i]['tia_penalty_py']=$row->tia_penalty_py;
            $arr[$i]['tia_tax_credit']=$row->tia_tax_credit;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/tax-revenue/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Tax Revenue">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
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
        $data = (object)$this->data;
        $arrAccGenLedCode = $this->arrAccGenLedCode;
        $arrFunCode = $this->arrFunCode;
        $arrDepCode = $this->arrDepCode;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = TreasurerIncomeAccount::find($request->input('id'));
            
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_incomeaccount->updateData($request->input('id'),$this->data);
                $success_msg = 'Income Account updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Income Account ".$this->data['tia_account_short_name'];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->_incomeaccount->addData($this->data);
                $success_msg = 'Income Account added successfully.';
                $content = "User ".\Auth::user()->name." Added Income Account ".$this->data['tia_account_short_name'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('incomeaccount.index')->with('success', __($success_msg));
    	}
        return view('incomeaccount.create',compact('data','arrFunCode','arrAccGenLedCode','arrDepCode'));
        
	}

   public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tia_account_code'=>'required',
                'tia_account_description'=>'required',
                'tia_account_short_name'=>'required'
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
    public function importExcel(Request $request){
    	request()->file('file');
    	if($request->input('submit')!=""){
    		 $rules = [
            'file' => 'required|mimes:csv,txt,xlsx,xlsm',
	        ];

	        $validator = \Validator::make($request->all(), $rules);

	        if($validator->fails())
	        {
	            $messages = $validator->getMessageBag();

	            return redirect()->back()->with('error', $messages->first());
	        }
	        $incomeaccounts = (new IncomeAccount())->toArray(request()->file('file'))[0];
	        //echo "<pre>"; print_r($incomeaccounts); exit;
	          $totalIncomeaccount = count($incomeaccounts) - 1;
		        $errorArray    = [];
		        for($i = 1; $i <= count($incomeaccounts) - 1; $i++)
		        {
		            $incomeaccount = $incomeaccounts[$i];

		              $AccountDatarecord = TreasurerIncomeAccount::where('tia_account_short_name', $incomeaccounts[3])->first();
		            if(!empty($customerByEmail))
		            {
		                $IncomeAccountData = $customerByEmail;
		            }
		            else
		            {
		                $IncomeAccountData = new TreasurerIncomeAccount();
            		}

		            $IncomeAccountData->tia_fund_code             = $incomeaccount[0];
		            $IncomeAccountData->tia_account_code          = $incomeaccount[1];
		            $IncomeAccountData->tia_account_description   = $incomeaccount[2];
		            $IncomeAccountData->tia_account_short_name    = $incomeaccount[3];
		            $IncomeAccountData->is_active        = 1;
		            $IncomeAccountData->tia_initial_amount     = $incomeaccount[4];
		            $IncomeAccountData->tia_group_code  = $incomeaccount[5];
		            $IncomeAccountData->tia_group_description    = $incomeaccount[6];
		            $IncomeAccountData->created_at     = date('Y-m-d H:i:s');
		            $IncomeAccountData->updated_at         = date('Y-m-d H:i:s');
		            $IncomeAccountData->created_by       = \Auth::user()->creatorId();
		            $IncomeAccountData->updated_by       = \Auth::user()->creatorId();

		            if(empty($IncomeAccountData))
		            {
		                $errorArray[] = $IncomeAccountData;
		            }
		            else
		            {
		                $IncomeAccountData->save();
		            }
		        }

		        $errorRecord = [];
		        if(empty($errorArray))
		        {
		            $data['status'] = 'success';
		            $data['msg']    = __('Record successfully imported');
		        }
		        else
		        {
		            $data['status'] = 'error';
		            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' .$totalIncomeaccount. ' ' .' record');


		            foreach($errorArray as $errorData)
		            {

		                $errorRecord[] = implode(',', $errorData);

		            }

		            \Session::put('errorArray', $errorRecord);
		        }

		        return redirect()->back()->with($data['status'], $data['msg']);
            echo "<pre>"; print_r($customers); exit;
    	}
        return view('incomeaccount.importexport');
    }

    public function destroy($id)
    {
            $IncomeAccount = TreasurerIncomeAccount::find($id);
            if($IncomeAccount->created_by == \Auth::user()->creatorId()){
                $IncomeAccount->delete();
                return redirect()->route('incomeaccount.index')->with('success', __('Income account successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
       
    }
}
