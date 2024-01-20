<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportColumnHeader;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ReportColumnHeaderController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $getServices = array(""=>"Please Select");
     public $arrdepartments = array(""=>"Please Select");
     public function __construct(){
		$this->_reportcolumnheader= new ReportColumnHeader(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','pcs_id'=>'','tfoc_id'=>'','rep_header_name'=>'','description'=>'','remark'=>'');  
        $this->slugs = 'setupcolumn-header'; 
        // foreach ($this->_reportcolumnheader->getServices() as $val) {
        //      $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        //  }
         foreach ($this->_reportcolumnheader->getdepartments() as $val) {
             $this->arrdepartments[$val->id]=$val->pcs_name;
         }
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
                return view('columnheader.index');
           
    }
     public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('cs_is_active' => $is_activeinactive);
        $this->_reportcolumnheader->updateActiveInactive($id,$data);
    }
       
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_reportcolumnheader->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['pcs_name']=$row->pcs_name;
            $arr[$i]['rep_header_name']=$row->rep_header_name;
            $arr[$i]['tfoc_id']=$row->code." - ".$row->gldescription;
            $arr[$i]['description']=$row->description;
            $arr[$i]['remark']=$row->remark;
            $arr[$i]['updatedby']=$row->fullname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/setupcolumn-header/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Column Header">
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

    public function getTaxfessoption(Request $request){
    	$pcsid = $request->input('pcs_id'); $html ='<option value="">Please Select</option>';
    	$optiondata = $this->_reportcolumnheader->getServices($pcsid);

    	foreach ($optiondata as $key => $val) {
        $html .='<option value="'.$val->id.'">['.$val->code." - ".$val->gldescription.']=>['.$val->prefix." - ".$val->description.']'.'</option>';
      }
      echo $html;
    }

    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $getServices = $this->getServices;
        $arrApptype = config('constants.arrCpdoAppModule');
        //echo "<pre>"; print_r($arrApptype); exit;
        $arrdepartments = $this->arrdepartments;
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = ReportColumnHeader::find($request->input('id'));
            foreach ($this->_reportcolumnheader->getServices($data->pcs_id) as $val) {
             $getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
            }
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_reportcolumnheader->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Column Header updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $lastinsertid = $this->_reportcolumnheader->addData($this->data);
                $success_msg = 'Column Header added successfully.';
            }
           
            return redirect()->route('columnheader.index')->with('success', __($success_msg));
    	}
        return view('columnheader.create',compact('data','getServices','arrdepartments'));
	}
	  public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'rep_header_name'=>'required| unique:report_column_headers,rep_header_name,'.(int)$request->input('id').',id,pcs_id,' .$request->input('pcs_id'),
                'pcs_id'=>'required',
                // 'tfoc_id'=>'required',
                // 'remark'=>'required',
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
