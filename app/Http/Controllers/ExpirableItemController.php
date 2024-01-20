<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\GsoItem;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpirableItemController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_gsoitem = new GsoItem(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'health-and-safety/setup-data/expirable-item';
       
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('expirableitem.index');
    }


    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_gsoitem->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['item_name']= $row->code. '=>' .'['.$row->name.']';
            $arr[$i]['uom']= $row->uom_code;
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
        $this->_gsoitem->updateActiveInactive($id,$data);

       
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
