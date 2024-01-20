<?php
namespace App\Http\Controllers;
use App\Models\RptLandUnitValue;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RevisionYear;
use DB;

class RptLandUnitValueController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrLocalCode = array();
     public $arrBrgyCode = array();
     public $arrActualCode = array(""=>"Please Select");
     public $arrRevisionCode = array();
     public $arrClassCode = array(""=>"Please Select");
     public $arrSubclassCode = array(""=>"Please Select");
     private $slugs;
     private $slugs2;
     public $activeRevisionYear = "";
    public function __construct(){
        $this->_rptlandunitvalue = new RptLandUnitValue();
        $this->_revisionyear = new RevisionYear;
        
  $this->data = array('id'=>'','loc_local_code'=>'','loc_group_brgy_no'=>'','pau_actual_use_code'=>'','rvy_revision_year'=>'','pc_class_code'=>'','ps_subclass_code'=>'','lav_location_name'=>'','lav_unit_value'=>'0','lav_unit_measure'=>'0','rls_code'=>'','rls_percent'=>'0','rls_description'=>'','lav_strip_unit_value'=>'0');
        
        foreach ($this->_rptlandunitvalue->getRptClass() as $val) {
            $this->arrClassCode[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
        } 
        foreach ($this->_rptlandunitvalue->getLocal() as $val) {
            $this->arrLocalCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
        }
        foreach ($this->_rptlandunitvalue->getBrgy() as $val) {
            $this->arrBrgyCode[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        } 
        // foreach ($this->_rptlandunitvalue->getActual() as $val) {
        //     $this->arrActualCode[$val->id]=$val->pau_actual_use_code.'-'.$val->pau_actual_use_desc;
        // }

        foreach ($this->_rptlandunitvalue->getRevisionDefult() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        foreach ($this->_rptlandunitvalue->getRevision() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        foreach ($this->_rptlandunitvalue->getRptSubClass() as $val) {
            $this->arrSubclassCode[$val->id]=$val->ps_subclass_code.'-'.$val->ps_subclass_desc;
        }
        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
        $this->slugs = 'real-property/unit-value/land';
        $this->slugs2="real-property/property-data/generalrevision";

    }
    public function getsubclass(Request $request){
      $data = $this->_rptlandunitvalue->getsubclass($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
    
    
    public function getActualdata(Request $request){
       $data = $this->_rptlandunitvalue->getActualdata($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }  
    
    
    public function index(Request $request)
    {   
        $read1 = $this->is_permitted($this->slugs, 'read', 1);
        $read2 = $this->is_permitted($this->slugs2, 'read', 1);
        if (!($read1 > 0) && !($read2 > 0)){
            return abort(401);
        }
        if($request->query('permission') == 1){
            $permission=1;
        }else{
            $permission=0;
        }
        $revisionyear = array(""=>"Select Revision Year");
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        foreach ($this->_rptlandunitvalue->getRevisionyears() as $key => $value) {
            $revisionyear[$value->id] = $value->rvy_revision_year."-".$value->rvy_revision_code;
         } 
        return view('rptlandunitvalue.index',compact('revisionyear','activeRevisionYear','permission'));
      
    }

     public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 3);
    }
    
    public function getList(Request $request){
        $data=$this->_rptlandunitvalue->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status2 =($row->lav_strip_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm ti-trash " style="background: #8d8a8a;color:#fff;" ></a>': 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center  ti-reload text-white"  style="background: #8d8a8a"></a>';

            $status =($row->lav_strip_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['loc_local_code']=$row->mun_no.'-'.$row->mun_desc;
            $arr[$i]['loc_group_brgy_no']=$row->brgy_code.'-'.$row->brgy_name;
            $arr[$i]['pc_class_code']=$row->pc_class_code.'-'.$row->pc_class_description.', '.$row->ps_subclass_code.'-'.$row->ps_subclass_desc.', '.$row->pau_actual_use_code.'-'.$row->pau_actual_use_desc;
            $arr[$i]['ps_subclass_code']=$this->money_format($row->lav_unit_value);
            $arr[$i]['pau_actual_use_code']=($row->lav_unit_measure == 1) ?'Square Meter':'Hectare';
            $pkcode=2;
           $assessmentIds = $this->_rptlandunitvalue->getAssmentDetails($row->munId, $row->brgyId, $pkcode, $row->classId, $row->actulId,$row->yearId);
            // dd($assessmentIds);exit;
            if(!$assessmentIds->isEmpty()) {
                $arr[$i]['assessment'] = '';
                foreach ($assessmentIds as $assessmentId) {
                    $arr[$i]['assessment'] .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="'.url('/assessmentlevel/view?id='.$assessmentId).'&serial_no='.$j.'" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="View Details" data-title="View Assessment Level">
                            <i class="ti-eye text-white"></i>
                        </a>
                    </div>';
                }
            }else{
                $arr[$i]['assessment'] = '';
            }
            if($row->is_approve =='1'){
                 $approve = '<div class="action-btn  bg-success ms-2"><a href="#" class="mx-3 btn btn-sm approveunapprove ti ti-lock-open text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';
            }else{ 
				$approve = '<div class="action-btn  bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm unapproveunapprove ti ti-lock-off text-white text-white" name="stp_print" value="1" id='.$row->id.'></a></div>'; 
			}
            $arr[$i]['isapprove']=($row->lav_strip_is_active==1)?$approve:'';
            $arr[$i]['rvy_revision_year']=$row->rvy_revision_year.'-'.$row->rvy_revision_code;
            $arr[$i]['is_active']=($row->lav_strip_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            if($row->is_approve =='0'){
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptlandunitvalue/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Land Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    </div>';
                }
                
                else{
                    $arr[$i]['action']='
                <div class="action-btn bg- ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center ti-pencil" style="background: #8d8a8a;color:#fff;"  data-size="lg"  title="Edit"  data-title="Edit Land Unit Value">
                       
                    </a>
                    </div>
                    '.$status2.'
                    </div>';
                }
                // <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
            
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
        $data=array('lav_strip_is_active' => $is_activeinactive);
        $this->_rptlandunitvalue->updateActiveInactive($id,$data);
    }

    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptlandunitvalue->ApproveUnapprove($id,$data);
    }
    public function store(Request $request){
        $brgyId=$this->_rptlandunitvalue->getBrgyId();
        if($brgyId != null)
        {
            $this->data['loc_group_brgy_no'] = $brgyId;
        }
        $data = (object)$this->data;
        $arrLocalCode = $this->arrLocalCode;
        $arrBrgyCode = $this->arrBrgyCode;
        $arrActualCode = [];
        $arrRevisionCode = $this->arrRevisionCode;
        $arrClassCode = [];
        $arrSubclassCode = [];

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptLandUnitValue::where('rpt_land_unit_values.id',$request->input('id'))
                                     ->join('rpt_property_classes','rpt_property_classes.id','=','rpt_land_unit_values.pc_class_code')
                                     ->join('rpt_property_actual_uses','rpt_property_actual_uses.id','=','rpt_land_unit_values.pau_actual_use_code')
                                     ->join('rpt_property_subclassifications','rpt_property_subclassifications.id','=','rpt_land_unit_values.ps_subclass_code')
                                     ->select('rpt_land_unit_values.*',
                                        DB::raw('CONCAT(rpt_property_classes.pc_class_code,"-",rpt_property_classes.pc_class_description) as class'),
                                        DB::raw('CONCAT(rpt_property_subclassifications.ps_subclass_code,"-",rpt_property_subclassifications.ps_subclass_desc) as subClass'),
                                        DB::raw('CONCAT(rpt_property_actual_uses.pau_actual_use_code,"-",rpt_property_actual_uses.pau_actual_use_desc) as actualUses'))
                                     ->first();
           // dd($data);
            $arrActualCodeData = [];
            $arrActualCode = [];
            foreach ($arrActualCodeData as $key => $value) {
                $arrActualCode[$value->id] = $value->pau_actual_use_code.'-'.$value->pau_actual_use_desc;
            }
            //dd($arrActualCode);
            foreach ($this->_rptlandunitvalue->getRevisionall($data->rvy_revision_year) as $val) {
            $arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        }
        if($request->isMethod('post')){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $access1 = $this->is_permitted($this->slugs, 'update', 1);
                $access2 = $this->is_permitted($this->slugs2, 'update', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->_rptlandunitvalue->updateData($request->input('id'),$this->data);
                $success_msg = 'Land Unit Value updated successfully.';
            }else{
                $access1 = $this->is_permitted($this->slugs, 'create', 1);
                $access2 = $this->is_permitted($this->slugs2, 'create', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['lav_strip_is_active'] = 1;
               
                $this->_rptlandunitvalue->addData($this->data);
                $success_msg = 'Land Unit Value added successfully.';
            }

            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('rptlandunitvalue.index')->with('success', __($success_msg));
            }
            
        }

        
        return view('rptlandunitvalue.create',compact('data','arrClassCode','brgyId','arrLocalCode','arrBrgyCode','arrActualCode','arrRevisionCode','arrSubclassCode'));
        
    }
    

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
               'rvy_revision_year' => 'required|unique:rpt_land_unit_values,rvy_revision_year,' .$request->input('id'). ',id,loc_group_brgy_no,' .$request->input('loc_group_brgy_no').',pc_class_code,' .$request->input('pc_class_code').',ps_subclass_code,' .$request->input('ps_subclass_code').',pau_actual_use_code,' .$request->input('pau_actual_use_code'),
               'pc_class_code' => 'required',
               'ps_subclass_code' => 'required',
               'pau_actual_use_code' => 'required',
               'lav_unit_value' => 'required|numeric|min:0.001',
               'lav_unit_measure' => 'required'
              ],[
                'rvy_revision_year.required' => 'Required Field',
                'pc_class_code.required' => 'Required Field',
                'ps_subclass_code.required' => 'Required Field',
                'pau_actual_use_code.required' => 'Required Field',
                'lav_unit_value.required' => 'Required Field',
                'lav_unit_measure.required' => 'Required Field',
                'lav_unit_value.numeric' => 'Only Numeric Allowed',
                'lav_unit_value.min' => 'Min. value should be greater than 0',
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

   
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptLandUnitValue = RptLandUnitValue::find($id);
            if($RptLandUnitValue->created_by == \Auth::user()->creatorId()){
                $RptLandUnitValue->delete();
            }
    }

    public function classAjaxRequest(Request $request){
         $data = $this->_rptlandunitvalue->classAjaxRequest($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
}
