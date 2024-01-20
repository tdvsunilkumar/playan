<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\PsicTfoc;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use session;
class PsicTfocController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;
    public $formulaData = [];
    public $arrQuarterly = [];
    public $arrRange = [];
    public function __construct(){
        $this->_PsicTfoc = new PsicTfoc(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ptfoc_access_type'=>'0','section_id'=>'0','subclass_id'=>'0','ctype_id'=>'','tfoc_id'=>'','app_code'=>'','ptfoc_effectivity_date'=>'','ptfoc_is_no_of_units'=>'','ptfoc_is_distribute_per_barangay'=>'','cctype_id'=>'','ptfoc_constant_amount'=>'','ptfoc_remarks'=>'','ptfoc_gl_id'=>'','ptfoc_basis_id'=>'','ptfoc_sl_id'=>'');  
        $this->formulaData=array('formula'=>'','is_higher'=>'','higher_amount'=>'');
        $this->arrQuarterly=array('1'=>'First Quarter','2'=>'Second Quarter','3'=>'Third Quarter','4'=>'Fourth Quarter');
        $this->slugs = 'administrative/psic-libraries/section';
        $this->arrRange[0]['lower_amount']='0.00';
        $this->arrRange[0]['upper_amount']='0.00';
        $this->arrRange[0]['amount']='0';
        $this->arrRange[0]['is_formula']='0';
        $this->arrRange[0]['formula']='';
        $this->arrRange[0]['is_higher']='';
        $this->arrRange[0]['higher_amount']='0';
        $this->arrRange[0]['higher_amount']='0';
        $this->arrRange[0]['computation_type'] = '';
        $this->arrRange[0]['month_details'] = array(); 
        $this->arrRange[0]['qurtarly_details'] = array();
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.psic_tfoc.index');
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $pageHeading = $request->input('pageHeading');
        $data=$this->_PsicTfoc->getList($request);
        $arr=array();
        $i="0";   
      
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/PsicTfoc/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage '.$pageHeading.'">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->ptfoc_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $serial_number=$sr_no;
            if($row->tfoc_is_applicable!=1){
                $row->description='<strike style="color:red;">'.$row->description.' </strike>';
                $row->ctype_desc='<strike style="color:red;">'.$row->ctype_desc.' </strike>';
                $row->app_type='<strike style="color:red;">'.$row->app_type.' </strike>';
                $serial_number='<strike style="color:red;">'.$sr_no.' </strike>';
            }
            $arr[$i]['srno']=$serial_number;
            $arr[$i]['description']=$row->description;
            $arr[$i]['transaction']=$row->app_type;
            $arr[$i]['type']=$row->ctype_desc;

            $arr[$i]['is_active']=($row->ptfoc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ptfoc_is_active' => $is_activeinactive);
        $this->_PsicTfoc->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Tax Fee And Other Charges ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    public function getTypeComputationList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_PsicTfoc->getTypeComputationList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->cctype_desc;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getChargesList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_PsicTfoc->getChargesList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->charge_desc;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }  
    public function getTFOCDtlsList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_PsicTfoc->getTFOCDtlsList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    } 
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
            $this->data['ptfoc_access_type'] = (int)$request->input('type');
            if($this->data['ptfoc_access_type']==1){
                $this->data['section_id'] = (int)$request->input('sid');
            }else{
                $this->data['subclass_id'] = (int)$request->input('sid');
            }
        }
        
        $arrChargesType = array(""=>"Please Select");
        $arrGl = array(""=>"Please Select");
        $arrAccount= array(""=>"Please Select");
        $arrBasis= array(""=>"Please Select");
        $arrTypeComputation= array(""=>"Please Select");

        //Initialize Array 
        $arrVariable=array();
        $arrMeasure[0]['charge_id']=0;
        $arrMeasure[0]['measure_amount']=0;
        $arrMeasure[0]['is_per_unit']=0;

        $arrRange = $this->arrRange;
        $arrMonthDetails=array();
        $arrQuartarlyDetails=array();
        $arrQuarterly = $this->arrQuarterly;

        $arrFormulaCharges= array(""=>"Please Select");
        $arrMeasureCharges= array(""=>"Please Select");
        
        $arrType=array();
        foreach ($this->_PsicTfoc->getType() as $val) {
            $arrType[$val->id]=$val->app_type;
        }
        foreach ($this->_PsicTfoc->getTypeComputation() as $val) {
            $arrTypeComputation[$val->id]=$val->cctype_desc;
        }
        foreach ($this->_PsicTfoc->getChargesTypes() as $val) {
            $arrChargesType[$val->id]=$val->ctype_desc;
        }
        foreach ($this->_PsicTfoc->getTFOCDtls() as $val) {
            $arrAccount[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
        foreach ($this->_PsicTfoc->getCharges('req_formula') as $val) {
            $arrFormulaCharges[$val->id]=$val->charge_desc;
        }
        foreach ($this->_PsicTfoc->getCharges('req_measure_pax') as $val) {
            $arrMeasureCharges[$val->id]=$val->charge_desc;
        }


        $data = (object)$this->data;
        $formulaData = $this->formulaData;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_PsicTfoc->getEditDetails($request->input('id'));
            if(isset($data->ptfoc_json)){
                if($data->cctype_id==2){  // Formula Details
                    $formulaData = json_decode($data->ptfoc_json,true);
                    if(isset($formulaData['charge_details'])){
                        $arrVariable=$formulaData['charge_details'];
                    }
                }elseif($data->cctype_id==3){ // Measure and Pax Details
                    $ptfoc_json = json_decode($data->ptfoc_json,true);
                    if(isset($ptfoc_json)){
                        $arrMeasure=$ptfoc_json;
                    }
                }elseif($data->cctype_id==4){ // Monthly Details
                    $ptfoc_json = json_decode($data->ptfoc_json,true);
                    if(isset($ptfoc_json)){
                        $arrMonthDetails=$ptfoc_json;
                    }
                }elseif($data->cctype_id==5){ // Quarterly Details
                    $ptfoc_json = json_decode($data->ptfoc_json,true);
                    if(isset($ptfoc_json)){
                        $arrQuartarlyDetails=$ptfoc_json;
                    }
                }
                elseif($data->cctype_id==6){ // Range Details
                    $ptfoc_json = json_decode($data->ptfoc_json,true);
                    if(isset($ptfoc_json)){
                        $arrRange=$ptfoc_json;
                    }
                }
            }
        }
       
        if($request->input('submit')!=""){
            $arrJson=array();
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            // Formula Details
            if($this->data['cctype_id']==2){
                foreach((array)$this->formulaData as $key=>$val){
                    $this->arrJson[$key] = $request->input($key);
                }
                $this->arrJson['charge_details'] = $request->input('charges_id');
                $this->data['ptfoc_json'] =json_encode($this->arrJson);
            }
            // Measure and Pax Details
            else if($this->data['cctype_id']==3){
                $arrM = $request->input('measure_charges_id');
                foreach($arrM as $key=>$val){
                    $arrJson[$key]['charge_id'] = (int)$val;
                    $arrJson[$key]['measure_amount'] = (float)$request->input('measure_amount')[$key];
                    $arrJson[$key]['is_per_unit'] = $request->input('is_per_unit')[$key];
                }
                $this->data['ptfoc_json'] =json_encode($arrJson);
            }
            // Monthly Details
            else if($this->data['cctype_id']==4){
                $arrM = $request->input('month_id');
                foreach($arrM as $key=>$mid){
                    $arrJson[$key]['month_id'] = (int)$mid;
                    $arrJson[$key]['month_name'] =date('F', mktime(0,0,0,$mid));
                    $arrJson[$key]['amount'] = (float)$request->input('month_amount_'.$mid);
                    $arrJson[$key]['is_formula'] = (int)$request->input('month_is_formula_'.$mid);
                    $arrJson[$key]['formula'] = $request->input('month_formula_'.$mid);
                    $arrJson[$key]['higher_amount'] = (float)$request->input('month_higher_amount_'.$mid);
                    $arrJson[$key]['is_higher'] = (int)$request->input('month_is_higher_'.$mid);
                }
                $this->data['ptfoc_json'] =json_encode($arrJson);
            }
            // Quarterly Details
            else if($this->data['cctype_id']==5){
                $arrQ= $request->input('quarter_id');
                foreach($arrQ as $key=>$mid){
                    $arrJson[$key]['quarter_id'] = (int)$mid;
                    $arrJson[$key]['quarter_name'] =$this->arrQuarterly[$mid];
                    $arrJson[$key]['amount'] = (float)$request->input('qurtr_amount_'.$mid);
                    $arrJson[$key]['is_formula'] = (int)$request->input('qurtr_is_formula_'.$mid);
                    $arrJson[$key]['formula'] = $request->input('qurtr_formula_'.$mid);
                    $arrJson[$key]['higher_amount'] = (float)$request->input('qurtr_higher_amount_'.$mid);
                    $arrJson[$key]['is_higher'] = (int)$request->input('qurtr_is_higher_'.$mid);
                }
                $this->data['ptfoc_json'] =json_encode($arrJson);
            }
            // Range Details
            else if($this->data['cctype_id']==6){
                $arrR= $request->input('lower_amount');
                foreach($arrR as $key=>$lower_amt){
                    $arrJson[$key]['lower_amount'] = (float)$request->input('lower_amount')[$key];
                    $arrJson[$key]['upper_amount'] = (float)$request->input('upper_amount')[$key];
                    $arrJson[$key]['range_amount'] = (float)$request->input('range_amount')[$key];
                    $arrJson[$key]['is_formula'] = (int)$request->input('range_is_formula_'.$key);
                    $arrJson[$key]['formula'] = $request->input('range_formula')[$key];
                    $arrJson[$key]['higher_amount'] = $request->input('range_higher_amount')[$key];
                    $arrJson[$key]['is_higher'] = (int)$request->input('range_is_higher_'.$key);
                    $arrJson[$key]['computation_type'] = $request->input('computation_type_'.$key);
                    $arrJson[$key]['month_details'] = array(); 
                    $arrJson[$key]['qurtarly_details'] = array();

                    if($arrJson[$key]['computation_type']=='Monthly'){
                        $arrMonth = $request->input('range_month_id_'.$key);
                        $arrMData=array();
                        if(isset($arrMonth)){
                            foreach($arrMonth as $m_key=>$mid){
                                $arrMData[$m_key]['month_id'] = (int)$mid;
                                $arrMData[$m_key]['month_name'] =date('F', mktime(0,0,0,$mid));
                                $arrMData[$m_key]['amount'] = (float)$request->input('range_month_amount_'.$mid.'_'.$key);
                                $arrMData[$m_key]['is_formula'] = (int)$request->input('range_month_is_formula_'.$mid.'_'.$key);
                                $arrMData[$m_key]['formula'] = $request->input('range_month_formula_'.$mid.'_'.$key);
                                $arrMData[$m_key]['higher_amount'] = (float)$request->input('range_month_higher_amount_'.$mid.'_'.$key);
                                $arrMData[$m_key]['is_higher'] = (int)$request->input('range_month_is_higher_'.$mid.'_'.$key);
                            }
                        }
                        $arrJson[$key]['month_details'] = $arrMData;
                    }elseif($arrJson[$key]['computation_type']=='Quarterly'){
                        $arrQurter = $request->input('range_quarter_id_'.$key);
                        $arrQData=array();
                        if(isset($arrQurter)){
                            foreach($arrQurter as $m_key=>$mid){
                                $arrQData[$m_key]['quarter_id'] = (int)$mid;
                                $arrQData[$m_key]['quarter_name'] =$this->arrQuarterly[$mid];
                                $arrQData[$m_key]['amount'] = (float)$request->input('qurtr_amount_'.$mid.'_'.$key);
                                $arrQData[$m_key]['is_formula'] = (int)$request->input('qurtr_is_formula_'.$mid.'_'.$key);
                                $arrQData[$m_key]['formula'] = $request->input('qurtr_formula_'.$mid.'_'.$key);
                                $arrQData[$m_key]['higher_amount'] = (float)$request->input('qurtr_higher_amount_'.$mid.'_'.$key);
                                $arrQData[$m_key]['is_higher'] = (int)$request->input('qurtr_is_higher_'.$mid.'_'.$key);
                            }
                        }
                        $arrJson[$key]['qurtarly_details'] = $arrQData;
                    }
                }
                $this->data['ptfoc_json'] =json_encode($arrJson);
            }


            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_PsicTfoc->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated PSIC TFOC '"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ptfoc_is_active'] = 1;
                $request->id = $this->_PsicTfoc->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added PSIC TFOC '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            session()->flash('success', $success_msg);
            return redirect()->back();
            //return redirect()->route('PsicTfoc.index')->with('success', __($success_msg));
        }
        foreach ($this->_PsicTfoc->getBasis($data->app_code) as $val) {
            $arrBasis[$val->id]=$val->basis_name;
        }
        return view('Bplo.psic_tfoc.create',compact('data','arrChargesType','arrGl','arrAccount','arrType','arrTypeComputation','arrBasis','arrVariable','arrFormulaCharges','formulaData','arrMeasure','arrMeasureCharges','arrMonthDetails','arrQuarterly','arrQuartarlyDetails','arrRange'));
    }
    public function formValidation(Request $request){
        $fields = ',section_id,' .(int)$request->input('section_id');
        if($request->input('ptfoc_access_type')==2){
            $fields = ',subclass_id,' .(int)$request->input('subclass_id');
        }

        $validator = \Validator::make(
            $request->all(), [
                'ctype_id'=>'required',
                'tfoc_id'=>'required',
                'tfoc_id'=>'required|unique:psic_tfocs,tfoc_id,'.(int)$request->input('id'). ',id,app_code,' .$request->input('app_code').',ptfoc_access_type,' .$request->input('ptfoc_access_type').$fields
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
    public function getChartAccount(Request $request){
        $arr = $this->_PsicTfoc->getSubsidiaryList($request->input('id'));
        $subSidaryOptions ="";
        $gl_id='';
        foreach ($arr as $key => $value) {
            $subSidaryOptions .='<option value="'.$value->id.'">'.$value->description.'</option>';
            $gl_id = $value->gl_account_id;

        }
        $prev_id = $request->input('hidden_ctype_id');
        $arr = $this->_PsicTfoc->getTFOCTypeCharges($request->input('id'));
        $chargesTypeOptions ="";
        foreach ($arr as $key => $value) {
            $selected = ($prev_id==$value->id)?'selected':'';
            $chargesTypeOptions .='<option value="'.$value->id.'" '.$selected.'>'.$value->ctype_desc.'</option>';
        }

        $arr['subSidaryOptions'] = $subSidaryOptions;
        $arr['chargesTypeOptions'] = $chargesTypeOptions;
        $arr['ptfoc_gl_id'] = $gl_id;

        echo json_encode($arr);
    }
    public function getTfocBasis(Request $request){
        $app_code = $request->input('app_code');
        $html="<option>Please Select</option>";
        foreach ($this->_PsicTfoc->getBasis($app_code) as $val) {
            $html .="<option value='".$val->id."'>".$val->basis_name."</option>";
        }
        echo $html;
    }
   
}
