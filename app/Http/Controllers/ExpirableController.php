<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\HoInventoryPosting;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpirableController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_inventoryposting = new HoInventoryPosting(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'health-and-safety/medicine-supplies/expirable-inventory';
       
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('expirable.index');
    }


    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_inventoryposting->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['expiry_date']= Carbon::parse($row->cip_expiry_date)->format('M d, Y');
            $arr[$i]['item_name']= $row->code. '=>' .'['.$row->name.']';
            $arr[$i]['uom']= $row->uom_code;
            $arr[$i]['qty']= $row->cip_balance_qty.'/'.$row->cip_qty_posted;
            $arr[$i]['receive_type']= ($row->cip_receiving==1?'Internal':'External');
            $arr[$i]['category']= $row->inv_category;
            $arr[$i]['receive_date']= Carbon::parse($row->cip_date_received)->format('M d, Y');
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
        $data=array('ser_is_active' => $is_activeinactive);
        $this->_inventoryposting->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Serology Method ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                //'df_desc'=>'required|unique:cto_data_formulas,df_desc,'.(int)$request->input('id'),
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
