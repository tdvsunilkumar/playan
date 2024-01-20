<?php

namespace App\Http\Controllers;

use App\Models\RptPropertyOnlineAccess;
use App\Models\RptPropertyOwner;
use App\Models\RptProperty;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptPropertyOnlineAccessController extends Controller
{
 public $data = [];
 public $taxDeclaration = array(""=>"Select Tax Declaration");

 public function __construct(){
 $this->_rptpropertyonlineaccess= new RptPropertyOnlineAccess();
        
  $this->data = array('id'=>'','brgy_id'=>'','cem_name'=>'','remark'=>'','status'=>'');
            
    }
    
    public function index(Request $request)
    {
        
       return view('rptpropertyonlineaccess.index');
        
    }
    
    public function getList(Request $request){
        //$this->_rptpropertyonlineaccess->updateDataDuringCashBill(27,'bill');
        $data=$this->_rptpropertyonlineaccess->getList($request);
       // dd($data);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;        
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>'; 
            $access =($row->is_online == 1) ? 'Online' : 
            'Offline'; 
            $arr[$i]['srno']=$sr_no;
            $customername = wordwrap($row->full_name, 30, "<br />\n");
            $address = wordwrap($row->address, 40, "<br />\n");
            $arr[$i]['taxpayername']="<div class='showLess'>".$customername."</div>";
            $arr[$i]['address']="<div class='showLess'>".$address."</div>";
            $arr[$i]['mobile']=$row->p_mobile_no;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['access']=$access;
            $count= RptPropertyOnlineAccess::where('clients_id', $row->id)->count();
            $arr[$i]['count']=$count;
            $arr[$i]['accno']=$row->account_no;
            $arr[$i]['updatedby']=$row->fullname;
            $dateString = $row->updated_at;
            $dateOnly = substr($dateString, 0, 10);
            $arr[$i]['date']=$dateOnly;
            $arr[$i]['view']='
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rpt-online-taxpayers/view?id='.$row->id).'" data-ajax-popup="true" style="background:#20b7cc;color:#fff;"  data-size="lg" data-bs-toggle="tooltip" title="view"  data-title="Manage Real property: Online [Posting & Access] : Item #: '.$sr_no.' ">
                        Details
                    </a>
                    ';
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptpropertyonlineaccess/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Real property: Online [Posting & Access] : Item #: '.$sr_no.' ">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>
                
                </div>'  ;
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
    public function checkExit(Request $request)
    {
        $rpCode = $request->input('rp_code');
        $clientsId = $request->input('clients_id');
        $check = RptPropertyOnlineAccess::where('rp_code', $rpCode)
            ->where('clients_id', $clientsId)
            ->first();
        if ($check) {
            return response()->json(['message' => 'Tax Declaration already exists.'], 422);
        } else {
            return response()->json(['message' => 'Value does not exist'], 200);
        }
    }
    public function AddOnlineAccess(Request $request){
        $user_id= \Auth::user()->id;
        $clients_id=$request->input('clients_id');
        $propData = DB::table('rpt_properties')->where('id',$request->input('rp_code'))->first();
        $lastCashierDetailsl = DB::table('cto_cashier_real_properties as ccrp')
                                   ->select('cc.or_no','cc.cashier_or_date','cc.created_at','cc.status','cc.total_paid_amount','cc.net_tax_due_amount')
                                   ->where('ccrp.rp_code',$request->input('rp_code'))
                                   ->join('cto_cashier as cc',function($j){
                                    $j->on('cc.id','=','ccrp.cashier_id')->where('cc.tfoc_is_applicable',2)->where('cc.status',1);
                                   })
                                   ->orderBy('ccrp.id','desc')
                                   ->first();
        $data = array();
        $data['clients_id'] = $request->input('clients_id');
        $data['rp_code'] = $request->input('rp_code');
        $data['rp_property_code'] = $request->input('rp_property_code');
        $data['taxpayer_id'] = $propData->rpo_code;
        $data['tax_declaration_no'] = $propData->rp_tax_declaration_no;
        $data['property_index_no'] = $propData->rp_pin_declaration_no;;
        $data['market_value'] = $propData->rp_market_value_adjustment;
        $data['assessed_value'] = $propData->rp_assessed_value;
        $data['amount_due'] = (isset($lastCashierDetailsl->net_tax_due_amount))?$lastCashierDetailsl->net_tax_due_amount:'';
        $data['or_no'] = (isset($lastCashierDetailsl->or_no))?$lastCashierDetailsl->or_no:'';
        $data['or_date'] = (isset($lastCashierDetailsl->cashier_or_date))?$lastCashierDetailsl->cashier_or_date:'';
        $data['payment_date'] = (isset($lastCashierDetailsl->created_at))?$lastCashierDetailsl->created_at:'';
        $data['payment_status'] = (isset($lastCashierDetailsl->status))?$lastCashierDetailsl->status:'';;
        $data['is_active'] = 1;
        $data['created_by']=\Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s');
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('rpt_property_online_accesses')->select('id')->where('rp_code',$request->input('rp_code'))->first();
                try {
                        $this->_rptpropertyonlineaccess->addData($data);
                        //dd($arrBill);
                        if($arrBill == null){
                            $data['is_synced'] = 0;
                            $remortServer->table('rpt_property_online_accesses')->insert($data);
                        }

                }catch (\Throwable $error) {
                    return $error;
                }
        
        session()->flash('success', 'Submit successfully');
        $data['ESTATUS']=1;
        echo json_encode($data);exit;
    }
    public function getClientOnlineAccessView(Request $request){
        $clients_id = $request->input('clients_id');
        
        $result = $this->_rptpropertyonlineaccess->getClientData($clients_id);
        if(!count($result)){
                 return
                      '<tr class="ownerdetailsData">
                         <td colspan="9" style="text-align:center;">Data not found</td>' .
                        
                      '</tr>';
        }
        $no=0;
        $arrClientsDetails="";
        foreach($result as $row)
            { 
               
               if($row->pk_is_active == 0){
                   
                   $no=$no+1;
                   $arrClientsDetails .=
                          '<tr class="ownerdetailsData" >
                          <td style="width: 20px;color:red;"><s style="color:red;">'.$no.' </td>' .
                            '<td style="width: 20px;color:red;"><s style="color:red;">' . $row->rp_tax_declaration_no . '</td>' .
                             '<td style="width: 100px;text-align: left;color:red;"><s style="color:red;">' . $row->full_name. '</td>' .
                             '<td style="width: 200px;text-align: left;color:red;"><s style="color:red;">' . $row->rp_pin_declaration_no. '</td>' .
                             '<td style="width: 200px;text-align: left;color:red;"><s style="color:red;">' . $row->propertyClass. '</td>' .
                             '<td style="width: 200px;text-align: left;color:red;"><s style="color:red;">' . $row->brgy_name. '</td>' .
                             '<td style="width: 200px;text-align: left;"><s style="color:red;">'.$row->cctUnitNo.'</td>' .
                             '<td style="width: 200px;text-align: left;"><s style="color:red;">'.number_format($row->assessedValue, 2).'</td>' .
                             
                          '</tr>';
                }else{
                      
                       $no=$no+1;
                       $arrClientsDetails .=
                              '<tr class="ownerdetailsData" >
                              <td style="width: 20px;">'.$no.' </td>' .
                                '<td style="width: 20px;">' . $row->rp_tax_declaration_no . '</td>' .
                                 '<td style="width: 100px;text-align: left;">' . $row->full_name. '</td>' .
                                 '<td style="width: 200px;text-align: left;">' . $row->rp_pin_declaration_no. '</td>' .
                                 '<td style="width: 200px;text-align: left;">' . $row->propertyClass. '</td>' .
                                 '<td style="width: 200px;text-align: left;">' . $row->brgy_name. '</td>' .
                                 '<td style="width: 200px;text-align: left;">'.$row->cctUnitNo.'</td>' .
                                 '<td style="width: 200px;text-align: left;">'.number_format($row->assessedValue, 2).'</td>' .
                                 
                              '</tr>';
                }
               
            }
            return $arrClientsDetails;
    }
    public function getClientOnlineAccess(Request $request)
    {
        $clientsId = $request->input('clients_id');
        $data = $this->_rptpropertyonlineaccess->getClientData($clientsId);
        $formattedData = [];
        $sr_no =0;
        foreach ($data as $row) {
            $sr_no = $sr_no + 1;
            $record = [
                'srno' => $sr_no,
                'rp_tax_declaration_no' => $row->rp_tax_declaration_no,
                'full_name' => $row->full_name,
                'rp_pin_declaration_no' => $row->rp_pin_declaration_no,
                'propertyClass' => $row->propertyClass,
                'brgy_name' => $row->brgy_name,
                'cctUnitNo' => $row->cctUnitNo,
                'assessedValue' => number_format($row->assessedValue, 2),
                'action' => '<button style="background: red;" class="mx-2 btn btn-sm deleterow ti-trash text-red text-white" id='.$row->rptOnlineid.'></button>',
            ];
            if ($row->pk_is_active == 0) {
                $record['class'] = 'red-row';
            } else {
                $record['class'] = '';
            }

            $formattedData[] = $record;
        }

        return response()->json(['data' => $formattedData]);
    }
    public function getTdDetails(Request $request){
        $data = $this->_rptpropertyonlineaccess->getTdDetails($request->id);
        $dataToReturn = [
            'or_no' => (isset($data->or_no))?$data->or_no:'',
            'rpo_code' => (isset($data->rpo_code))?$data->rpo_code:'',
            'rp_property_code' => (isset($data->rp_property_code))?$data->rp_property_code:'',
            'cashier_or_date' => (isset($data->cashier_or_date))?$data->cashier_or_date:'',
            'rp_pin_declaration_no' => (isset($data->rp_pin_declaration_no))?$data->rp_pin_declaration_no:'',
            'brgy_name' => (isset($data->brgy_name))?$data->brgy_name:'',
            'customername' => (isset($data->customername))?$data->customername:'',
            'cctUnitNo' => (isset($data->cctUnitNo))?$data->cctUnitNo:'',
            'assessedValue' => (isset($data->assessedValue))?Helper::decimal_format($data->assessedValue):00.00,
            'propertyClass' => (isset($data->propertyClass))?$data->propertyClass:''
        ];
        return response()->json($dataToReturn);
    }
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $is_activeinactive);
        $this->_rptpropertyonlineaccess->updateActiveInactive($id,$data);
    }
    public function getTaxDeclaresionOnlineDetails(Request $request){
       $id= $request->input('id');
       $getgroups = $this->_rptpropertyonlineaccess->getTaxDeclaresionOnlineDetails($id);
       $htmloption ="<option value=''>Select Tax Declaration</option>";
      foreach ($getgroups as $key => $val) {
                $htmloption .='<option value="'.$val->id.'">'.$val->rp_tax_declaration_no.'=>'.$val->full_name.'</option>';
            
        
      }
      echo $htmloption;
    }
    public function getTaxDeclaresionOnlineDetailsAll(Request $request){
        
       $getgroups = $this->_rptpropertyonlineaccess->getRptproperty();
       $htmloption ="<option value=''>Select Tax Declaration</option>";
      foreach ($getgroups as $key => $val) {
        $htmloption .='<option value="'.$val->id.'">'.$val->rp_tax_declaration_no.'=>'.$val->full_name.'</option>';
            
      }
      echo $htmloption;
    }
    public function viewDetails(Request $request)
    {
        $data = RptPropertyOwner::where('id', $request->input('id'))->first();
        $ownername = $data->full_name;
        
        if($data->is_online == 1){
            $status = 'Online';
        }else{
              $status = 'Offline';
        }
        return view('rptpropertyonlineaccess.view',compact('data','ownername','status'));
         
    }
    public function store(Request $request){
        $data = (object)$this->data;
        
        foreach ($this->_rptpropertyonlineaccess->getRptproperty() as $val) {
            $this->taxDeclaration[$val->id]=$val->rp_tax_declaration_no.'=>'.$val->full_name;
        }
        $taxDeclaration = $this->taxDeclaration;
        // dd($taxDeclaration);
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyOwner::where('id', $request->input('id'))->first();
        }
        $ownername = $data->full_name;
        
        if($data->is_online == 1){
            $status = 'Online';
        }else{
              $status = 'Offline';
        }
        
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['status']);
                $this->_rptpropertyonlineaccess->updateData($request->input('id'),$this->data);
                $success_msg = 'Cemetery updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
               
                $this->_rptpropertyonlineaccess->addData($this->data);
                $success_msg = 'Cemetery added successfully.';
            }
            return redirect('rpt-online-taxpayers')->with('success', __($success_msg));
        }
		
        return view('rptpropertyonlineaccess.create',compact('data','ownername','status','taxDeclaration'));
        
    }


    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'country_name'=>'required|unique:countries,country_name,'.(int)$request->input('id'),
                // 'nationality'=>'required',
                
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

   
    public function deleteOnlineAccess(Request $request){
        $id = $request->input('id');
        $RptPropertyOnlineAccess = RptPropertyOnlineAccess::find($id);
        if($RptPropertyOnlineAccess->created_by == \Auth::user()->id){
            $RptPropertyOnlineAccess->delete();
            $remortServer = DB::connection('remort_server');
            $arrBill = $remortServer->table('rpt_property_online_accesses')->select('id')->where('rp_code',$RptPropertyOnlineAccess->rp_code)->delete();
        }
    }
}
