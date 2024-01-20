<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\Requirements;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;
class RequirementsController extends Controller
{
    
    public $data = [];
    public $postdata = [];
    public function __construct(){
		$this->_Requirements = new Requirements(); 
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','req_code_abbreviation'=>'','req_description'=>'','req_dept_bplo'=>'','req_dept_bfp'=>'','req_dept_cpdo'=>'','req_dept_health_office'=>'','req_dept_eng'=>'','is_active'=>'1');  
    }
    public function index(Request $request)
    {
		
        
            $data = Requirements::where('created_by', '=', \Auth::user()->creatorId())->get();
            $data = $this->_Requirements->getRequirements();
            return view('requirements.index', compact('data'));
        
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = Requirements::find($request->input('id'));
            
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_Requirements->updateData($request->input('id'),$this->data);
                $success_msg = 'Requirements updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Requirements ".$this->data['req_code_abbreviation']; 
                Session::put('remort_serv_session_det', ['table' => "requirements",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $last_insert_id=$this->_Requirements->addData($this->data);
                $success_msg = 'Requirements added successfully.';
                $content = "User ".\Auth::user()->name." Added Requirements ".$this->data['req_code_abbreviation']; 
                Session::put('remort_serv_session_det', ['table' => "requirements",'action' =>"store",'id'=>$last_insert_id]);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('requirementscon.index')->with('success', __($success_msg));
    	}
        return view('requirements.create',compact('data'));
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'req_code_abbreviation'=>'required|unique:requirements,req_code_abbreviation,'.$request->input('id'),
                'req_description'=>'required'
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
        
            $Requirements = Requirements::find($id);
            if($Requirements->created_by == \Auth::user()->creatorId()){
                $Requirements->delete();
                return redirect()->route('requirements.index')->with('success', __('Requirements successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

}
