<?php

namespace App\Http\Controllers\Accounting;
use App\Http\Controllers\Controller;
use App\Models\Accounting\LegalResidentialLocation;
use App\Models\CemeteryList;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ResidentialHousingListController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrResidentialName = array("" => "Please Select");
     public function __construct(){
        $this->LegalResidentialLocation = new LegalResidentialLocation(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','residential_id'=>'','phase'=>'','street'=>'','block'=>'','lot_from'=>'','lot_to'=>'','lot_slot'=>'');  
        $this->slugs = 'residential-housing-list';
		
		foreach($this->LegalResidentialLocation->residentialName() as $val) {
            $this->arrResidentialName[$val->id]= $val->brgy_name." => ".$val->name;
        }
		// foreach ($this->LegalResidentialLocation->getBarangay() as $val) {
        //     $this->arrCemeteryName[$val->id]=$val->brgy_name ." => ".$val->cem_name;
        // }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('accounting.setup-data.LegalResidentialLocation.index');
    }


    public function getList(Request $request){
        // dd(\Auth::user()->name);
		// $cemeterystyle =$this->arrCemeteryStyle;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->LegalResidentialLocation->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/residential-housing-list/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Residential Housing">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['location']=$row->brgy_name;
			$arr[$i]['residential_name']=$row->residential_name;
			$arr[$i]['phase']=$row->phase;
			$arr[$i]['street']=$row->street;
			$arr[$i]['block']=$row->block;
            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('is_active' => $is_activeinactive);
        $this->LegalResidentialLocation->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Residential Housing List ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
		$arrResidentialName = $this->arrResidentialName;
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->LegalResidentialLocation->findById($request->input('id'));
			
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $verified_data=$this->LegalResidentialLocation->verifyUnique($request);
            $error_msg = 'The residential housing list already exist.';
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if($verified_data > 1){
                    return redirect()->back()->with('error', __($error_msg));
                }
                $this->LegalResidentialLocation->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Residential Housing List '"; 
            }else{
                if($verified_data > 0){
                    return redirect()->back()->with('error', __($error_msg));
                }
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $request->id = $this->LegalResidentialLocation->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Residential Housing List '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('LegalResidentialLocation.index')->with('success', __($success_msg));
        }
        return view('accounting.setup-data.LegalResidentialLocation.create',compact('data','arrResidentialName'));
    }

    public function saveornumberoflot(Request $request){
        $LocDetailsData =array();
        $LocDetailsData['residential_location_id']= $request->input('residential_location_id');
        $LocDetailsData['lot_number']= $request->input('lot_number');
        $LegalResidentialLocation = $this->LegalResidentialLocation->findById($request->input('residential_location_id'));
       
        //    if($request->input('id')>0){
        //        $this->LegalResidentialLocation->updateData($request->input('id'),$this->data);
        //        $success_msg = 'Updated successfully.';
        //        $array = ["status"=>"success","message" =>"Data Saved Successfully."];
        //    }else{
               $LocDetailsData['residential_id']=$LegalResidentialLocation->residential_id;
               $LocDetailsData['created_by']=\Auth::user()->creatorId();
               $LocDetailsData['created_at'] = date('Y-m-d H:i:s');
               $LocDetailsData['lot_status'] = 1;
               $LocDetailsData['is_active'] = 1;
               $this->LegalResidentialLocation->addLocDetailsData($LocDetailsData);
               $success_msg = 'Added successfully.';
               $array = ["status"=>"success","message" =>"Data Saved Successfully."];
        //    }
         
        echo json_encode($array);
   }
    
    public function getbrgyid(Request $request){
        $id= $request->input('id');
        $data = $this->LegalResidentialLocation->getbrgyid($id);
        return $data->brgy_id;

    }
	
	public function lotnolist(Request $request){
        $ecl_id= $request->input('id');
		$ifo= $request->input('ifo');
		$to= $request->input('to');
        $data = $this->LegalResidentialLocation->lotnolist($ecl_id);
		$lotno=array();
		
		foreach($data as  $row){
			$lotno[]=$row->lot_number;
		}
		
		for ($i = $ifo; $i <= $to; $i++){
			$ilist[] = $i;
		}
		if(!empty($lotno)){
			$arrlistlot = array_diff($ilist,$lotno);
		}else{
			$arrlistlot =$ilist;
		}
		$totallist = count($arrlistlot);
		echo json_encode($arrlistlot);
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'residential_id'=>'required',
                'lot_from'=>'required',
                'lot_to'=>'required',
                'lot_slot'=>'required',
            ],[
			  'residential_id.required' => 'The Subdivision / Village Name field is required.',
			  'lot_from.required' => 'The Lot. No. From field is required.',
			  'lot_to.required' => 'The Lot. No. To field is required.',
			  'lot_slot.required' => 'The Lot Slot field is required.'
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
