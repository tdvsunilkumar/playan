<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\PsicClass;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;
class PsicClassController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrSection = array(""=>"Please Select");
    public $arrDivision = array(""=>"Please Select");
    public $arrGroup = array(""=>"Please Select");
    public function __construct(){
		$this->_psicClass = new PsicClass();
        $this->_commonmodel = new CommonModelmaster();  
        $this->data = array('id'=>'','section_id'=>'','division_id'=>'','group_id'=>'','is_active'=>'1','class_code'=>'','class_description'=>'');
		$this->slugs = 'administrative/psic-libraries/class';
        //  foreach ($this->_psicClass->sectioncode() as $val) {
        //    $arrSection[$val->id]="[".$val->section_code." - ".$val->section_description."]=>[".$val->division_code." - ".$val->division_description."]";
        // }
        
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        $arrsection[''] ="Select Section";
        $arrdivision[''] ="Select Division";
        $arrgroup[''] ="Select Group";
        $arrclass[''] ="Select Class";
        return view('psicclass.index',compact('arrsection','arrdivision','arrgroup','arrclass'));
    }
    
    
    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_psicClass->getList($request);
    	$arr=array();
		$i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['class_code']=$row->class_code;
            $section_description = wordwrap($row->section_description, 100, "<br />\n");
            $division_description = wordwrap($row->division_description, 100, "<br />\n");
            $arr[$i]['section_description']="<div class='showLess'>".$section_description."</div>";
            $arr[$i]['division_description']="<div class='showLess'>".$division_description."</div>";
            $group_description = wordwrap($row->group_description, 100, "<br />\n");
            $arr[$i]['group_description']="<div class='showLess'>".$group_description."</div>";
            $class_description = wordwrap($row->class_description, 40, "<br />\n");
            $arr[$i]['class_description']="<div class='showLess'>".$class_description."</div>";
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/psicclass/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage PSIC Class">
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
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
        $arrsection[''] ="Select Section";
        foreach ($this->_psicClass->sectioncode() as $val) {
           $arrsection[$val->id]="[".$val->section_code." - ".$val->section_description."]=>[".$val->division_code." - ".$val->division_description."]";
        }

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PsicClass::find($request->input('id'));

            foreach ($this->_psicClass->getDivision($data->section_id) as $val) {
            $this->arrDivision[$val->id]=$val->division_description;
            }
            foreach ($this->_psicClass->getGroup($data->division_id) as $val) {
                $this->arrGroup[$val->id]=$val->group_description;
            } 
           
        }
        $arrDivision = $this->arrDivision;
        $arrGroup = $this->arrGroup;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['modified_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $arrPrevData = PsicClass::find($request->input('id'));
                if($arrPrevData->is_active != $request->is_active){
                    $updatecolumn =array();
                    $updatecolumn['is_active'] = $request->is_active;
                    $updatecolumn['subclass_modified_by'] = \Auth::user()->creatorId();
                    $updatecolumn['subclass_modified_date'] = date('Y-m-d H:i:s');
                    $ids = array('class_id' => $request->id);
                    $this->_commonmodel->updatestatusmaster('psic_subclasses',$ids,'class_id',$updatecolumn);
                }

                $this->_psicClass->updateData($request->input('id'),$this->data);
                $success_msg = 'Psic Class updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Class ".$this->data['class_code']; 
                Session::put('remort_serv_session_det', ['table' => "psic_classes",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['generated_by']=\Auth::user()->creatorId();
                $this->data['generated_date'] = date('Y-m-d H:i:s');
                $id=$this->_psicClass->addData($this->data);
                $success_msg = 'Psic Class added successfully.';
                $content = "User ".\Auth::user()->name." Added Class ".$this->data['class_code']; 
                Session::put('remort_serv_session_det', ['table' => "psic_classes",'action' =>"store",'id'=>$id]);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('psicclass.index')->with('success', __($success_msg));
    	 }
        
        return view('psicclass.create',compact('data','arrsection','arrDivision','arrGroup'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'class_code'=>'required|unique:psic_classes,class_code,'.$request->input('id'),
                'section_id'=>'required',
                'division_id'=>'required', 
                'group_id'=>'required', 
                'class_description'=>'required'
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

    public function destroy($id)
    {
		$this->is_permitted($this->slugs, 'delete');
        
            $PsicClass = PsicClass::find($id);
            if($PsicClass->generated_by == \Auth::user()->creatorId()){
                $PsicClass->delete();
                return redirect()->route('psicclass.index')->with('success', __('PSIC class successfully deleted.'));
            }
            
    }

}
