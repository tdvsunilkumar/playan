<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoTaxInterestSurcharge;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CtoTaxInterestSurchargeController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_CtoTaxInterestSurcharge = new CtoTaxInterestSurcharge(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tis_interest_amount'=>'','tis_interest_rate_type'=>'','tis_interest_schedule'=>'','tis_interest_max_month'=>'','tis_interest_formula'=>'','tis_interest_compute_mode'=>'','tis_surcharge_amount'=>'','tis_surcharge_rate_type'=>'','tis_surcharge_schedule'=>'','tis_surcharge_formula'=>'','tis_surcharge_compute_mode'=>'');  

        $this->slugs = 'business-data-interest-surcharges';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arrDtls = $this->_CtoTaxInterestSurcharge->getList();
        $data = (object)$this->data;
        if(isset($arrDtls) && count($arrDtls)>0){
            $data =$arrDtls[0];
        }
        return view('Bplo.interest_surcharge.index',compact('data'));
    }
    
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $arrRate = array("1"=>"Rate(Percentage %)","2"=>"Fixed Amount");
        $arrSchedule = array(""=>"Please Select");
        foreach ($this->_CtoTaxInterestSurcharge->getSchedule() as $val) {
            $arrSchedule[$val->id]=$val->ds_schedule;
        }
        $arrInterest = array(""=>"Please Select");
        foreach ($this->_CtoTaxInterestSurcharge->getFormula() as $val) {
            if($val->id!=1)
                $arrInterest[$val->id]=$val->df_desc;
        }

        $arrSurcharge = array(""=>"Please Select");
        foreach ($this->_CtoTaxInterestSurcharge->getFormula() as $val) {
            if($val->id!=2)
                $arrSurcharge[$val->id]=$val->df_desc;
        }

        $arrMode = array(""=>"Please Select");
        foreach ($this->_CtoTaxInterestSurcharge->getComputeMode() as $val) {
            $arrMode[$val->id]=$val->dcm_desc;
        }

        $data = (object)$this->data;

        if($request->input('submit')==""){
            $arrDtls = $this->_CtoTaxInterestSurcharge->getEditDetails();
            if(isset($arrDtls)){
                $data =$arrDtls;
            }
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CtoTaxInterestSurcharge->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Bplo Interest/Surcharges '"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $request->id = $this->_CtoTaxInterestSurcharge->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Bplo Interest/Surcharges '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('interestSurcharge.index')->with('success', __($success_msg));
        }
        return view('Bplo.interest_surcharge.create',compact('data','arrRate','arrMode','arrSurcharge','arrInterest','arrSchedule'));
    }
    
   
}
