<?php

namespace App\Http\Controllers;
use App\Models\CboBudget;
use App\Models\CboBudgetBreakdown;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CboBudgetControllerBackup extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arraglCode = array(""=>"Please Select");
     public $arrdeptCode = array(""=>"Please Select");
     public $arrdivCode = array(""=>"Please Select");
     public $arrfcCode = array(""=>"Please Select");
     public $arrdate = array(""=>"Please Select");

    public function __construct(){
        $this->_cbobudget = new CboBudget();
        $this->_cbobudgetbreadown = new CboBudgetBreakdown();
        

  $this->data = array('id'=>'','dept_id'=>'','budget_status'=>'','ddiv_id'=>'','fc_code'=>'','bud_year'=>'','bud_budget_quarter'=>'','bud_budget_annual'=>'','bud_budget_total'=>'',

 'bud_is_locked'=>'','bud_approved_by'=>'','bud_approved_date'=>'','bud_disapproved_by'=>'','bud_disapproved_date'=>'');
        
        foreach ($this->_cbobudget->getAgl() as $val) {
            $this->arraglCode[$val->id]=$val->code.'-'.$val->description;
        } 
        foreach ($this->_cbobudget->getDept() as $val) {
            $this->arrdeptCode[$val->id]=$val->code.'-'.$val->name;
        } 
        foreach ($this->_cbobudget->getDDiv() as $val) {
            $this->arrdivCode[$val->id]=$val->code.'-'.$val->name;
        }
        
        foreach ($this->_cbobudget->getFund() as $val) {
            $this->arrfcCode[$val->id]=$val->code.'-'.$val->description;
        } 
       
       
        foreach ($this->_cbobudget->getHrEmployeeCode() as $val) {
            if($val->suffix){
              $this->arrempcode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;   
            }
            else{
              $this->arrempcode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }    
    }
   
    public function getdivclasss(Request $request){
       $getgroups = $this->_cbobudget->getdivclass($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->name.'</option>';
      }
      echo $htmloption;
    }  
    public function getNatureDetails($id=''){
        $arrNature= array();

        if(empty($id)){
            // dd($id);
            $arrNature[0]['id']='';
            $arrNature[0]['agl_id']='';
            $arrNature[0]['bud_budget_quarter']='';
            $arrNature[0]['bud_budget_annual']='';
            $arrNature[0]['bud_budget_total']='';
            
        }else{
            $arr = $this->_cbobudgetbreadown->getAssRequiet($id);
            // echo "<pre>"; print_r($arr); exit;
            foreach($arr as $key=>$val){
            //    dd($arr);
                $arrNature[$key]['id']=$val->relationId;
                $arrNature[$key]['agl_id']=$val->agl_id;
                $arrNature[$key]['bud_budget_quarter']=$val->bud_budget_quarter;
                $arrNature[$key]['bud_budget_annual']=$val->bud_budget_annual;
                $arrNature[$key]['bud_budget_total']=$val->bud_budget_total;
            }
        }
        return $arrNature;
    }
    public function index(Request $request)
    {   $data = (object)$this->data;
        $arrempcode = $this->arrempcode;
        $arraglcode = $this->arraglCode;
        $arrNature = $this->getNatureDetails();
        return view('cbobudget.index',compact('arrempcode','arraglcode','data'));
        
    }
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }
    
    public function getList(Request $request){
        $data=$this->_cbobudget->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
           
            $arr[$i]['srno']=$i+1;
            $arr[$i]['bud_year']=$row->bud_year;
            $arr[$i]['ddiv_id']=$row->code.'-'.$row->name;
            $arr[$i]['dept_id']=$row->dept_code.'-'.$row->dept_name;
            // $arr[$i]['agl_id']=$row->agl_code;
            // $arr[$i]['description']=$row->agl_description;
            
            // $arr[$i]['fc_code']=$row->fc_code;
            
            $arr[$i]['bud_budget_quarter']=$this->money_format($row->bud_budget_quarter);
            $arr[$i]['bud_budget_annual']=$this->money_format($row->bud_budget_annual);
            // $arr[$i]['bud_budget_total']=$this->money_format($row->bud_budget_quarter+$row->bud_budget_annual);
            // $arr[$i]['bud_is_locked']=$row->bud_is_locked;
            // $arr[$i]['bud_generated_by']=$row->firstname.'-'.$row->lastname;
            // $arr[$i]['bud_approved_by']=$row->bud_approved_by;
            // $arr[$i]['bud_approved_date']=$row->bud_approved_date;
            // $arr[$i]['bud_disapproved_by']=$row->bud_disapproved_by;
            if($row->budget_status == 1){
                $budgetStatus = '<span>Submited</span>';
            }
            else if($row->budget_status == 2){
                $budgetStatus = '<span>Approved</span>';
            }else if($row->budget_status == 0){
                $budgetStatus = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Draft</span>';
            }else{
                $budgetStatus = '<span>Adjusted</span>';
            }
            $arr[$i]['budget_status'] = $budgetStatus;
            // if($row->budget_status == 1){
            //     $next = '<span class="btn btn-success updatecodefunctionality" value="'.$row->id.'" data-actionname="approve" data-propertyid="'.$row->id.'" style="padding: 0.1rem 0.5rem !important;">Approve</span>';
            // }
            // else if($row->budget_status == 2){
            //     $next = '<span class="btn btn-success updatecodefunctionality" value="'.$row->id.'" data-actionname="adjust" data-propertyid="'.$row->id.'" style="padding: 0.1rem 0.5rem !important;">Adjust</span>';
            // }else if($row->budget_status == 0){
            //     $next = '<span class="btn btn-success updatecodefunctionality" value="'.$row->id.'" value="'.$row->id.'" data-actionname="submit" data-propertyid="'.$row->id.'" style="padding: 0.1rem 0.5rem !important;">Submit</span>';
            // }else{
            //     $next = '<span class="updatecodefunctionality" value="'.$row->id.'"> - </span>';
            // }
            // $arr[$i]['next_step']  = $next;
            // $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            // $arr[$i]['next_step']  = $this->updateCodeSelectList($row->id);
            if($row->budget_status == 0){
                $harddelete = '<div class="action-btn bg-dark ms-2"><a href="#" title="hard Delete" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                </a></div>';
            }else{
                $harddelete = ' ';
            }
            // $harddel = $harddelete;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center " data-url="'.url('/cbobudget/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" data-title="Edit Budget">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div> 
                </div>
                '.$harddelete.'
            </div>'
                ;
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

    // public function updateCodeSelectList($id = ''){
    //     $select = '<select class="form-control updatecodefunctionality" name="updatecodefunctionality" style="min-width:100px;">';
    //      $select .= '<option value="">Select Action</option><option value="'.$id.'" data-actionname="edit" data-propertyid="'.$id.'" data-icon="glyphicon-music"> &#xf044; Edit</option><option value="'.$id.'" data-actionname="submit" data-propertyid="'.$id.'">Submit</option><option value="'.$id.'" data-actionname="approve" data-propertyid="'.$id.'">&#xf14d; Approve Budget</option><option value="'.$id.'" data-actionname="adjust" data-propertyid="'.$id.'"> &#xf021; Adjust Budget</option>';
    //     $select .= '</select>';
    //     return $select;
    // }

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_cbobudget->updateActiveInactive($id,$data);
    }

    public function DraftSubmit(Request $request){
        $id = $request->input('id');
        $budget_status = 1;
        $save_draft = $request->input('save_draft');
        $data=array('save_draft' => $save_draft, 'budget_status' => $budget_status);
        $this->_cbobudget->updateSubmitDraft($id,$data);
    }

    public function Unlock(Request $request){
        $id = $request->input('id');
        $budget_status = 3;
        $is_approve = 0;
        $save_draft = 0;
        $bud_is_locked = $request->input('bud_is_locked');
        $data=array('bud_is_locked' => $bud_is_locked, 'budget_status' => $budget_status);
        $this->_cbobudget->updateUnlock($id,$data);
    }


    public function Approve(Request $request){
        $id = $request->input('id');
        $is_approve = 1;
        $budget_status = 2;
        $is_approveby = \Auth::user()->creatorId();
        $approve_date = date('Y-m-d H:i:s');
        $data=array('bud_approved_by' => $is_approveby, 'bud_is_approve' => $is_approve, 'bud_approved_date' => $approve_date, 'budget_status' => $budget_status);
        $this->_cbobudget->updateApprove($id,$data);
    }

    // public function Adjust(Request $request){
    //     $id = $request->input('id');
    //     $budget_status = 3;
    //     $agl_id = $request->input('aglcode');
    //     $bud_budget_quarter = $request->input('quarter');
    //     $bud_budget_annual = $request->input('anual');
    //     $updatedata = CboBudgetBreakdown::find($request->input('id'));
    //     $data=array('agl_id' => $agl_id, 'bud_budget_quarter' => $bud_budget_quarter, 'bud_budget_annual' => $bud_budget_annual, 'budget_status' => $budget_status, 'bud_id' => $updatedata->bud_id,'dept_id' => $updatedata->dept_id,'ddiv_id' => $updatedata->ddiv_id,
    //     'fc_code' => $updatedata->fc_code, 'bud_year' => $updatedata->bud_year,'bud_is_locked' => 1,'bud_year' => $updatedata->bud_year,'is_active' => $updatedata->is_active );
    //     $this->_cbobudgetbreadown->updateApprove($id,$data);
    //     $this->_cbobudgetbreadown->addData($data);

    // }

    public function store(Request $request){
        $data = (object)$this->data;
        $arraglCode = $this->arraglCode;
        $arrdeptCode = $this->arrdeptCode;
        $arrdivCode = $this->arrdivCode;
        $arrfcCode = $this->arrfcCode;
        $arrempcode = $this->arrempcode;
        $arrdate = $this->arrdate;
        $arrNature = $this->getNatureDetails();
        
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CboBudget::find($request->input('id'));
            $arrNature = $this->getNatureDetails($request->input('id'));
        }
        if($request->input('submit')!=""){
           
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $budgetQuarter = collect($this->data['bud_budget_quarter']);
            $budgetQuarterTotal = $budgetQuarter->sum();
            //dd($budgetQuarterTotal);
            $budgetTotal = collect($this->data['bud_budget_total']);
            $budgetAllTotal = $budgetTotal->sum();
            $budgetAnnual  = collect($this->data['bud_budget_annual']);
            $budgetAnnualTotal = $budgetAnnual->sum();
            $this->data['bud_budget_quarter'] = $budgetQuarterTotal;
            $this->data['bud_budget_annual'] = $budgetAnnualTotal;
            $this->data['bud_budget_total'] = $budgetAllTotal;
            $this->data['budget_status'] = 0;
    
            unset($this->data['agl_id']);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            
            if($request->input('id')>0){
                $this->_cbobudget->updateData($request->input('id'),$this->data);
                $lastInsertId = $request->input('id');
                $success_msg = 'Budget updated successfully.';
               } 
              else{
                $budgetQuarter = collect($this->data['bud_budget_quarter']);
                $budgetQuarterTotal = $budgetQuarter->sum();
                //dd($budgetQuarterTotal);
                $budgetTotal = collect($this->data['bud_budget_total']);
                $budgetAllTotal = $budgetTotal->sum();
                $budgetAnnual  = collect($this->data['bud_budget_annual']);
                $budgetAnnualTotal = $budgetAnnual->sum();
                $this->data['bud_budget_quarter'] = $budgetQuarterTotal;
                $this->data['bud_budget_annual'] = $budgetAnnualTotal;
                $this->data['bud_budget_total'] = $budgetAllTotal;
                $this->data['budget_status'] = 0;
        
                unset($this->data['agl_id']);
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
             
                $lastInsertId=$this->_cbobudget->addData($this->data);
                $success_msg = 'Budget added successfully.';
            }
            if($lastInsertId>0){
                $this->addAssRelation($request,$lastInsertId);
             }

            return redirect()->route('cbobudget.index')->with('success', __($success_msg));
        }
        //echo 'Yes i am called here';exit;
        return view('cbobudget.create',compact('data','arraglCode','arrdeptCode','arrdivCode','arrfcCode','arrempcode','arrNature','arrdate'));
        
    }

    public function addAssRelation($request,$lastInsertId){
        $relationId = $request->input('relationId');
        $bud_year = $request->input('bud_year');
        // dd($bud_year)
        $fc_code = $request->input('fc_code');
        $dept_id = $request->input('dept_id');
        $ddiv_id = $request->input('ddiv_id');
        $arr = array();

        $i=0;
       
        foreach ($relationId as $key => $value) {


                $arr[$i]['bud_id']=$lastInsertId;
                $arr[$i]['bud_year']=$request->input('bud_year');
                $arr[$i]['fc_code']=$request->input('fc_code');
                $arr[$i]['ddiv_id']=$request->input('ddiv_id');
                $arr[$i]['dept_id']=$request->input('dept_id');
                $arr[$i]['agl_id']=$request->input('agl_id')[$key];
                // print_r($arr);exit;
                // $arr[$i]['description']=$request->input('description')[$key];
                $arr[$i]['bud_budget_quarter']=$request->input('bud_budget_quarter')[$key];
                $arr[$i]['bud_budget_annual']=$request->input('bud_budget_annual')[$key];
                $arr[$i]['bud_budget_total']=$request->input('bud_budget_total')[$key];
                $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');

                
                // print_r($arr);exit;
                $check= $this->_cbobudgetbreadown->checkAssRequietExit($request->input('relationId')[$key]);
                
                if(count($check)>0){
                   $this->_cbobudgetbreadown->updateAssRelationData($check[0]->id,$arr[$i]);
                }
                else{
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_cbobudgetbreadown->addAssRelationData($arr[$i]);
                }
                $i++;
            }
    }


    public function formValidation(Request $request){
        
        $validator = \Validator::make(
            $request->all(), [
                'dept_id'=>'required|unique:cbo_budgets,dept_id,'.$request->input('id'),
                'ddiv_id'=>'required|unique:cbo_budgets,ddiv_id,'.$request->input('id'),
                // 'agl_id'=>'required|unique:cbo_budget_breakdowns,agl_id,'.$request->input('bud_id'),
                'fc_code'=>'required',
                'bud_year'=>'required'
                // 'bud_budget_quarter'=>'required',
                // 'bud_budget_annual'=>'required',
                // 'bud_budget_total'=>'required',
                // 'bud_is_locked'=>'required',
                // 'bud_approved_by'=>'required',
                // 'bud_approved_date'=>'required',
                // 'bud_disapproved_by'=>'required',
                // 'bud_disapproved_date'=>'required'

              ]
          );
          $validator->after(function ($validator) use($request) {
            $agl_id = collect($request->agl_id);
            $duplicate = $agl_id->duplicates();                               
                if(!$duplicate->isEmpty()){
                    $validator->errors()->add('agl_id', 'Should not be same');
                }
    });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        else{

        }
        echo json_encode($arr);exit;
    }

   
    public function Delete(Request $request){
        $id = $request->input('id');
            $CboBudget = CboBudget::find($id);
            // if($CboBudgetBreakdown->created_by == \Auth::user()->creatorId()){
                $CboBudget->delete();
            // }
    }
}
