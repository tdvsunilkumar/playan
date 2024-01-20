<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\UserLoginLogs;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;

class UserLoginLogsController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrDepartment = array("" => "Please Select");
     public function __construct(){
        $this->_userloginlogs = new UserLoginLogs(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','menu_group_id'=>'','menu_name'=>'');  
        $this->slugs = 'userloginlogs';
		foreach ($this->_userloginlogs->getDepartments() as $val) {
            $this->arrDepartment[$val->id]=$val->name;
        }
    }
    public function index(Request $request)
    {
		$to_date= date('Y-m-d');
        $from_date= date('Y-m-d');
		$from_date=Date('Y-m-d', strtotime('-15 days'));
        $this->is_permitted($this->slugs, 'read');
		$arrDepartment =$this->arrDepartment;
		$arrLogtype = array(''=>'All','1'=>'Log-in','2'=>'Transaction');
		return view('userloginlogs.index')->with(compact('to_date','from_date','arrDepartment','arrLogtype'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_userloginlogs->getList($request, \Auth::user()->roles()->first()->role_id);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            $location=$row->ip_address;
            $ip_registration=DB::table('ip_registration')->where('ip_address', $location)->first();
            if($ip_registration){
              $location = $ip_registration->local_name;
            }
            $arr[$i]['srno']=($row->attempt==1?'<span style="color:red;">'.$sr_no.'</span>':'<span>'.$sr_no.'</span>');
            $arr[$i]['full_name']=($row->attempt==1?'<span style="color:red;">'.$row->full_name.'</span>':'<span>'.$row->full_name.'</span>');
			$arr[$i]['email_address']=($row->attempt==1?'<span style="color:red;">'.$row->email_address.'</span>':'<span>'.$row->email_address.'</span>');
			$arr[$i]['dept_name']=($row->attempt==1?'<span style="color:red;">'.$row->dept_name.'</span>':'<span>'.$row->dept_name.'</span>');
			$arr[$i]['details']=($row->attempt==1?'<span style="color:red;">'.$row->logs.'</span>':'<span>'.$row->logs.'</span>');
			$arr[$i]['location']=($row->attempt==1?'<span style="color:red;">'.$location.'</span>':'<span>'.$location.'</span>');
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
    
 
}
