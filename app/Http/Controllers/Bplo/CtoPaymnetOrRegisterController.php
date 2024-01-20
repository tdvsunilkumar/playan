<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoPaymnetOrRegister;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\Carbon;

class CtoPaymnetOrRegisterController extends Controller
{
    public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrOrtypes = array(""=>"Select Department");
     public $Ortypes = array(""=>"Select OR Type");
     public function __construct(){
        $this->_CtoOrRegister = new CtoPaymnetOrRegister(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','cpot_id'=>'','coa_no'=>'','ora_from'=>'','ora_to'=>'','or_count'=>'');  
        $this->slugs = 'ctoorregister';
		foreach ($this->_CtoOrRegister->getoRtypes() as $key => $value) {
			$this->arrOrtypes[$value->id] = $value->ortype_name;
		}
		//$this->arrDepaertments = array('1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');

    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        foreach ($this->_CtoOrRegister->getoRtypes() as $key => $value) {
            $this->Ortypes[$value->ortype_name] = $value->ortype_name. ' [' .$value->or_short_name. ']';
        }
        $Ortypes = $this->Ortypes;
        return view('Bplo.orregister.index',compact('Ortypes'));
    }

    public function getShortname(Request $request){
        $id = $request->input('id');
        $data = $this->_CtoOrRegister->getShortname($id);
        echo $data->or_short_name;
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CtoOrRegister->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $idexist = $this->_CtoOrRegister->checkidExist($row->id);
             $status ="";
            if(count($idexist) ==0 && $row->cpor_status==1){
                 $status = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id="' . $row->id . '" data-massage="Do you want to de-active the details?" data-itemno="' . $sr_no . '" data-ortype="' . $row->ortype_name . '" data-tag="' . $row->or_short_name . ',#' . $row->coa_no . '"></a></div>';

             
            }elseif(count($idexist) ==0 && $row->cpor_status==0){
                $status = '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-reload text-white text-white" name="stp_print" value="1" id="' . $row->id . '" data-massage="Do you want to restore the details?" data-itemno="' . $sr_no . '" data-ortype="' . $row->ortype_name . '" data-tag="' . $row->or_short_name . ',#' . $row->coa_no . '"></a></div>';
            }
            
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ortype']=$row->ortype_name;
            //$arr[$i]['coa_no']=$row->coa_no;
            $arr[$i]['ora_from']=$row->ora_from;
            $arr[$i]['ora_to']=$row->ora_to;
            $arr[$i]['cpor_series']=$row->or_short_name.",#".$row->coa_no;
            $arr[$i]['registerby']=$row->fullname;
            $arr[$i]['date']=date('Y-m-d',strtotime($row->created_at));
            if($row->cpor_status==1){
                $is_active='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>';
            }elseif($row->cpor_status==2){
                $is_active='<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Completed</span>';
            }else{
                $is_active='<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>'; 
            }
            $arr[$i]['is_active']=$is_active;
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ctoorregister/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage/Edit OR Register">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status;
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
        $data=array('cpor_status' => $is_activeinactive);
        $this->_CtoOrRegister->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' CTO OR Type ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
  
    public function store(Request $request){
        $data = (object)$this->data;
        $data->document_details = "";
        $arrOrtypes = $this->arrOrtypes;
        if($request->input('id')>0 && $request->input('submit')==""){
           $data =  $this->_CtoOrRegister->getRecordforedit($request->input('id'));
           $arrdocDtls = $this->generateDocumentListnew($data->ora_document,$data->id);
           $data->document_details ="";
            if(isset($arrdocDtls)){
                $data->document_details = $arrdocDtls;
            }
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['cpor_series'] = $_POST['shortname'].",#".$this->data['coa_no']; 
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CtoOrRegister->updateData($request->input('id'),$this->data);
                $success_msg = 'Or Register updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['cpor_status'] = 1;
                $this->_CtoOrRegister->addData($this->data);
                $success_msg = 'Or Register added successfully.';
            }
            return redirect()->route('bplo.orregister.index')->with('success', __($success_msg));
    	}
        return view('Bplo.orregister.create',compact('data','arrOrtypes'));
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arr = $this->_CtoOrRegister->getEditDetails($id);
        if(isset($arr)){
            $arrJson = json_decode($arr->ora_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/orregister/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['ora_document'] = json_encode($arrJson);
                    $this->_CtoOrRegister->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $arrDtls = $this->_CtoOrRegister->getEditDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(isset($arrDtls)){
            $arrJson = (array)json_decode($arrDtls->ora_document,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/orregister/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrDtls)){
                    $arrJson = json_decode($arrDtls->ora_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['ora_document'] = json_encode($finalJsone);
                $this->_CtoOrRegister->updateData($id,$data);
                $arrDocumentList = $this->generateDocumentListnew($data['ora_document'],$id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }

     public function generateDocumentListnew($arrJson,$id, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$val['filename']."</span></td>
                        <td><a class='btn' href='".asset('uploads/orregister').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td><td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteDocument ti-trash text-white text-white' id='".$id."' rid='".$val['filename']."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    
    // public function formValidation(Request $request){
    //     $ora_to = $request->ora_to;
    //     $ora_from = $request->ora_from;
    //     $cpot_id = $request->cpot_id;
    //     $id = $request->id;
    //     $Details = [];
    //     $validator = \Validator::make(
    //         $request->all(), [
    //             // 'cpot_id' => 'required|unique:cto_payment_or_registers,cpot_id,' .$request->input('id').',id,coa_no,' .$request->input('coa_no'),
    //             'cpot_id'=>'required',
    //             'ora_from'=>'required',
    //             'ora_to'=>'required',
    //             'coa_no'=>'required'
    //         ]
    //     );
    //      $validator->after(function ($validator) use($ora_to,$ora_from,$cpot_id,$id,  &$Details) {
    //         $data = $validator->getData();
    //         $exist = $this->_CtoOrRegister->checktoExist($ora_from,$ora_to,$cpot_id,$id); 
    //         if ($exist > 0) {
    //             $validator->errors()->add('ora_from', 'From and To already Exists!');
    //         }
             
    //          //print_r($checkfromandto); exit;

    //         //  $checkfromandto = $this->_CtoOrRegister->checktoExist($ora_from,$ora_to,$cpot_id,$id); 
    //         //  foreach ($checkfromandto as $key => $value) {
    //         //      if($ora_to <= $value->ora_from || $ora_to <= $value->ora_to){
    //         //         $validator->errors()->add('ora_from', 'From and To allready Exists!');
    //         //      }
    //         //      if($ora_from <= $value->ora_from || $ora_from <= $value->ora_to){
    //         //         $validator->errors()->add('ora_from', 'From and To allready Exists!');
    //         //      }
    //         //  }
    //     });
      
    //     $arr=array('ESTATUS'=>0);
    //     if($validator->fails()){
    //         $messages = $validator->getMessageBag();
    //         $arr['field_name'] = $messages->keys()[0];
    //         $arr['error'] = $messages->all()[0];
    //         $arr['ESTATUS'] = 1;
    //     }
        
    //     echo json_encode($arr);exit;
    // }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cpot_id' => 'required',
                'ora_from' => 'required',
                'ora_to' => 'required',
                'coa_no' => 'required'
            ]
        );

        $arr = ['ESTATUS' => 0];

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }

        if ($arr['ESTATUS'] == 0) {
            $exist = $this->_CtoOrRegister->checktoExist(
                $request->input('ora_from'),
                $request->input('ora_to'),
                $request->input('cpot_id'),
                $request->input('id')
            );

            if ($exist > 0) {
                $arr['field_name'] = 'ora_from';
                $arr['error'] = 'From and To already Exist!';
                $arr['ESTATUS'] = 1;
            }
        }

        echo json_encode($arr);
        exit;
    }
	
}
