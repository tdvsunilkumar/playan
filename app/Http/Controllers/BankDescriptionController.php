<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\BankDescription;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BankDescriptionController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_BankDescription = new BankDescription(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','bdesc_description'=>'','bdesc_code'=>'');  
        $this->slugs = 'bank-description';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('BankDescription.index');
    }


    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_BankDescription->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bankdescription/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Bank Description">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->bdesc_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['bdesc_code']=$row->bdesc_code;
            $arr[$i]['bdesc_description']=$row->bdesc_description;
            $arr[$i]['bdesc_status']=($row->bdesc_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('bdesc_status' => $is_activeinactive);
        $this->_BankDescription->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bank Description ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_BankDescription->getEditDetails($request->input('id'));
        }
		if(!empty($data->bdesc_sample_doc)){
		$arrdocDtls = $this->generateDocumentList($data->bdesc_sample_doc,$data->id);
			if(isset($arrdocDtls)){
				$data->arrDocumentDetailsHtml = $arrdocDtls;
			}
		}else{
			$data->arrDocumentDetailsHtml ="";
		}
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_BankDescription->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Bank Description '".$this->data['bdesc_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bdesc_status'] = 1;
                $request->id = $this->_BankDescription->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Bank Description '".$this->data['bdesc_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('bankdescription.index')->with('success', __($success_msg));
        }
        return view('BankDescription.create',compact('data'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bdesc_description'=>'required|:cto_payment_bank_description,bdesc_description,'.(int)$request->input('id'),
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
	
	public function uploadDocument(Request $request){
		
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = BankDescription::find($healthCertId);
		
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
		
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->bdesc_sample_doc,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/bankdescription/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['doc_id'] = count($arrJson)+1;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->bdesc_sample_doc,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['bdesc_sample_doc'] = json_encode($finalJsone);
                $this->_BankDescription->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['bdesc_sample_doc'],$healthCertId);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn' href='".asset('uploads/bankdescription').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
	public function deleteAttachment(Request $request){
        $healthCertid = $request->input('healthCertid');
        $doc_id = $request->input('doc_id');
        $arrEndrosment = BankDescription::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->bdesc_sample_doc,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/bankdescription/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bdesc_sample_doc'] = json_encode($arrJson);
                    $this->_BankDescription->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
   
}
