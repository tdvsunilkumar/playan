<?php
namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\Biometrics;

class BiometricsController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
		$this->_Biometrics= new Biometrics(); 
        $this->data = array(
            'id'=>'',
            'bio_ip'=>'192.168.',
            'bio_proxy'=>4370,
            'bio_desc'=>'',
            'bio_model'=>'',
            'bio_code'=>'',
            'bio_department'=>'',
            'bio_is_copied'=>0,
        );  
        $this->slugs = 'hr-biometrics';
    }
    public function index()
    {
        $this->is_permitted($this->slugs, 'read');
        return view('HR.Biometrics.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Biometrics->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="xl" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Biometric" data-title="Manage Biometric">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Manage Biometrics"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Biometric"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ip']=$row->bio_ip;
            $arr[$i]['proxy']=$row->bio_proxy;
            $arr[$i]['department']=$row->bio_department;
            $arr[$i]['code']=$row->bio_code;
            $arr[$i]['model']=$row->bio_model;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
    public function show()
    {
        # code...
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = Biometrics::find($request->input('id'));
        }
        if($request->method() == "POST"){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            Biometrics::updateOrCreate(
                ['id'=>$request->input('id')],
                $this->data
            );
            return redirect()->route('hr.biometrics')->with('success', __('Updated'));
        }
        return view('HR.Biometrics.create',compact('data'));
    }
    public function formValidation(Request $request){
        $rule =[
            "bio_ip" => "required|ip",
            "bio_proxy" => "required|int",
        ];
        if (!$request->id) {
            $rule = array_merge($rule, [
                "bio_code" => "required|unique:App\Models\HR\Biometrics,bio_code",
                "bio_ip" => "required|ip|unique:App\Models\HR\Biometrics,bio_ip"
            ]);
        }
        $validator = \Validator::make(
            $request->all(), 
            $rule
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
    
    public function ActiveInactive(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        Biometrics::updateData($id,$data);
    }

    public function import(Request $request){
        $file = file($request->bio_file);
        foreach ($file as $key => $data) {
            $data = explode("\t",trim($data," \r\n"));
            $datetime = explode(' ',$data[1]);
            $date = $datetime[0];
            $time = $datetime[1];
            $val = [
                'hrtc_emp_id_no' => $data[0],
                'hrbr_date' => $datetime[0],
                'hrbr_time' => $datetime[1],
            ];
            $insert = Biometrics::addRecord($val);
        }
    }
    public function importValidation(Request $request){
        // dd($request->all());
        $rule =[
            "bio_file" => "nullable",
            // "bio_file" => "required|file|mimes:dat",
        ];
        
        $validator = \Validator::make(
            $request->all(), 
            $rule
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
    // can be remove?
    public function connection(Request $request)
    {
        $ip = $request->ip;
        $data = Biometrics::testBiometric($ip);
        $success_msg = 'Success';
        return json_encode(
            [
                'ESTATUS'=>0,
                'msg'=>$success_msg,
                'data' => $data
            ]
        );
    }
    public function tester()
    {
        dd(Biometrics::find(1)->getAttendance());
    }
}
