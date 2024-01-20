<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoPaymentOrType;
use App\Models\Bplo\PaymentDetilsStore;
use App\Models\Bplo\PaymentCashierSystem;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\Carbon;
class CtoPaymentOrTypeController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrDepaertments = array(""=>"Select Department");
     public function __construct(){
        $this->_CtoPaymentOrType = new CtoPaymentOrType(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ortype_name'=>'','or_short_name'=>'','ora_remarks'=>'');  
        $this->slugs = 'bplo-or-type';
		
		foreach ($this->_CtoPaymentOrType->PaymentCashierSystem() as $val) {
             $this->arrDepaertments[$val->id]=$val->pcs_name;
        }
		//$this->arrDepaertments = array('1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');

    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.or_type.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CtoPaymentOrType->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
			$view_button = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/CtoPaymentOrType/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage OR Type">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->ora_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			$view_button .= '<div class="action-btn bg-success ms-2">
								<a href="#" class="btn btn-success  align-items-center" data-url="'.url('/casherviewpay/casherview?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Cashier System"  data-title="View Cashier System">
									View
								</a>
							</div>';
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ortype_name']=$row->ortype_name;
            $arr[$i]['or_short_name']=$row->or_short_name;
			$arr[$i]['view_button']= $view_button;
            $arr[$i]['ora_remarks']=$row->ora_remarks;
            $arr[$i]['ora_status']=($row->ora_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('ora_status' => $is_activeinactive);
        $this->_CtoPaymentOrType->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' CTO OR Type ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    public function casherview(Request $request){
		if($request->input('id')>0){
			$data_details = PaymentDetilsStore::where('ortype_id',$request->input('id'))->select('*')->get();
        }
		
		$arrDepaertments = $this->arrDepaertments;
		//$view_casher = $arrDepaertments[$data_details->pcs_id];
		return view('Bplo.or_type.create_blade',compact('data_details','arrDepaertments'));
	}
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$data_details="";
        $data = (object)$this->data;
        $arrDepaertments = $this->arrDepaertments;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CtoPaymentOrType->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
				$y=count($_POST['pcs_id'])-1;
				$i=0;
				while($i < $y){
					if(isset($_POST['id_details'][$i]) > 0){
						
						PaymentDetilsStore:: where('id', $_POST['id_details'][$i])
						->update(['ortype_id' => $request->input('id'),'pcs_id'=>$_POST['pcs_id'][$i]]);
					 
					}else{
						
						 $paymentdetils = new PaymentDetilsStore();
						 $paymentdetils->ortype_id = $request->input('id');
						 $paymentdetils->pcs_id = $_POST['pcs_id'][$i];
						 $paymentdetils->created_by = \Auth::user()->creatorId();
						 $paymentdetils->created_at = date('Y-m-d H:i:s');
						 $paymentdetils->save();
					}
					$i++;
				}
                $this->_CtoPaymentOrType->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated CTO OR Type '".$this->data['ora_remarks']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ora_status'] = 1;
                $request->id = $this->_CtoPaymentOrType->addData($this->data);
				$y=count($_POST['pcs_id'])-1;
				$i=0;
				while($i < $y){
					
					 $paymentdetils = new PaymentDetilsStore();
					 $paymentdetils->ortype_id = $request->id;
					 $paymentdetils->pcs_id = $_POST['pcs_id'][$i];
					 $paymentdetils->created_by = \Auth::user()->creatorId();
					 $paymentdetils->created_at = date('Y-m-d H:i:s');
					 $paymentdetils->save();
					 
					$i++;
				}
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added CTO OR Type '".$this->data['ora_remarks']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('ORType.index')->with('success', __($success_msg));
        }
        if(!empty($data->ora_document)){
        $arrdocDtls = $this->generateDocumentList($data->ora_document,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
		if($request->input('id') >0 ){
			$data_details = PaymentDetilsStore::where('ortype_id',$request->input('id'))->select('*')->get();
		}else{
			$data_details = array();
		}
        return view('Bplo.or_type.create',compact('data','arrDepaertments','data_details'));
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ortype_name'=>'required|unique:cto_payment_or_types,ortype_name,'.(int)$request->input('id'),
                'or_short_name'=>'required|unique:cto_payment_or_types,or_short_name,'.(int)$request->input('id'),
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
	
	 /* public function addpaymentdetils(Request $request){
		 $this->validate($request, [
			'ortype_id' => 'required|unique:cto_payment_or_type_details',
			'pcs_id' => 'required'
		 ]);
		 $paymentdetils = new PaymentDetilsStore();
		 $paymentdetils->ortype_id = $request->input('ortype_id');
		 $paymentdetils->pcs_id = $request->input('pcs_id');
		 $paymentdetils->created_by = \Auth::user()->creatorId();
		 $paymentdetils->created_at = date('Y-m-d H:i:s');
		 $paymentdetils->save();
		 $success_msg = 'Added successfully.';
		 return response()->json(['msg' => $success_msg]);
	 } */
	 
	  public function deletefuntion(Request $request){
		  
		 PaymentDetilsStore::where('id',$request->input('id'))->delete();
		 $success_msg = 'Delete successfully.';		 
		 return response()->json(['msg' => $success_msg]);
	 }

    public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = CtoPaymentOrType::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->ora_document,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/ortype/';
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
                    $arrJson = json_decode($arrEndrosment->ora_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['ora_document'] = json_encode($finalJsone);
                $this->_CtoPaymentOrType->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['ora_document'],$healthCertId);
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
                                <a class='btn' href='".asset('uploads/ortype').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = CtoPaymentOrType::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->ora_document,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/ortype/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['ora_document'] = json_encode($arrJson);
                    $this->_CtoPaymentOrType->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
   
}
