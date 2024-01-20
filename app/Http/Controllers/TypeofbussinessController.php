<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\TypeofBussiness;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TypeofbussinessController extends Controller
{
     public $data = [];
    public $postdata = [];
    public function __construct(){
        $this->_typeofbussiness= new TypeofBussiness(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','bussiness_code'=>'','bussiness_type'=>'','is_active'=>'');  
    }
    
    public function index(Request $request)
    {
        
            $Typeofbussiness = $this->_typeofbussiness->getTypeofByssiness();
            return view('typeofbussiness.index', compact('Typeofbussiness'));
        
    }

    public function store(Request $request)
    {
        $data = (object)$this->data;
        
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = TypeofBussiness::find($request->input('id'));
           
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if($request->input('id')>0){
                  $id= $request->input('id');
                $this->data['updated_by']=\Auth::user()->creatorId();
                 $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->_typeofbussiness->updateData($request->input('id'),$this->data);
                $success_msg = 'Byssiness Type updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Byssiness Type ".$this->data['bussiness_type'];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->_typeofbussiness->addData($this->data);
                $success_msg = 'Business Type added successfully.';
                $content = "User ".\Auth::user()->name." Added Byssiness Type ".$this->data['bussiness_type'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('typeofbussiness.index')->with('success', __($success_msg));
        }
        return view('typeofbussiness.create',compact('data'));
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bussiness_type'=>'required|unique:typeof_bussinesses,bussiness_type,'.(int)$request->input('id'),
                'bussiness_code'=>'required'
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
        
            $TaxCategory = TaxCategory::find($id);
            if($TaxCategory->created_by == \Auth::user()->creatorId()){
                $TaxCategory->delete();
                return redirect()->route('TaxCategory.index')->with('success', __('Tax Category successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
