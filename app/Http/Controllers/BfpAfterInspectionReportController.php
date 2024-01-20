<?php

namespace App\Http\Controllers;

use App\Models\BfpAfterInspectionReport;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use File;

class BfpAfterInspectionReportController extends Controller
{
    public $data = [];
    public $arrYears = array(""=>"Select Year");
    public function __construct(){
        $this->_inspectionreports = new BfpAfterInspectionReport();
        $arrYrs = $this->_inspectionreports->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bend_year] =$val->bend_year;
        }
        $this->slugs = 'fire-protection/inspection-report';
    }
    
    public function InspectionReportFiles(Request $request)
    { 
		$arrYears = $this->arrYears;
		return view('aftertinspectionreport.reportfiles',compact('arrYears'));
    }
     public function getList(Request $request){
       
        $data=$this->_inspectionreports->getList($request);
        $pageTitle = $request->input('pageTitle');
        $bbendo_id=$request->input('bbendo_id');
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            $actions .= '<div class="action-btn bg-info ms-2" style="color: #fff;">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/Endrosement/application?id='.$row->id).'&bbendo_id='.$row->endorsing_dept_id.'&year='.$row->bend_year.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="After Inspection Report (AIR) Document Management"  data-title="After Inspection Report (AIR) Document Management" style="color: #fff;">
                            <i class=" ti-eye text-white"></i>
                        </a>
                    </div>';
                
            
            
            $ownar_name=$row->ownar_name;
            if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_registration_no']=$row->busns_id_no;
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['busn_name']=$row->busn_name;
            if (isset($row->app_code)) {
                $arr[$i]['app_type'] = config('constants.arrBusinessApplicationType')[(int)$row->app_code];
            } else {
                $arr[$i]['app_type'] ="";
            }
            $arr[$i]['end_status']=config('constants.arrBusEndorsementStatus')[(int)$row->bend_status];
            $arr[$i]['created_at']=$row->created_at;
            $arr[$i]['busn_app_method']=$row->busn_app_method;
            $arr[$i]['busn_app_status']=config('constants.arrBusinessApplicationStatus')[$row->busn_app_status];
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

     public function store(Request $request){
        $data = (object)$this->data;

        if($request->input('submit')!=""){
            
              $loop = count($_POST['id']);
                 $inspectfiles = array();
                for($i=0; $i<$loop;$i++){
                      if($image = $request->file('botoccupancypdftype'.$_POST['id'][$i])) {
                         $destinationPath =  public_path().'/uploads/inspectionfiles/';
                            if(!File::exists($destinationPath)) { 
                                File::makeDirectory($destinationPath, 0755, true, true);
                            }
                         $filename =  $_POST['name'][$i];  
                         $filename = str_replace(" ", "", $filename);   
                         $occupancypdf = $filename. "." . $image->extension();
                         $image->move($destinationPath, $occupancypdf);
                         $inspectfiles['occupancy_typepdfname'] = $occupancypdf;
                         $inspectfiles['occupancy_typeid'] = $_POST['id'][$i];
                         $this->data['updated_by']=\Auth::user()->creatorId();
                         $this->data['updated_at'] = date('Y-m-d H:i:s');
                       
                       if(!empty($_POST['irfid'][$i])){
                        $this->_inspectionreports->updateData($_POST['irfid'][$i],$inspectfiles);
                       }else{
                        $this->data['created_by']=\Auth::user()->creatorId();
                        $this->data['created_at'] = date('Y-m-d H:i:s');
                        $this->_inspectionreports->addData($inspectfiles);
                      }
                   }
                }

            $success_msg = 'BFP Occupancy Type updated successfully.';
           
            return redirect()->route('inspectionreportfile')->with('success', __($success_msg));
        }
    }

    public function Deletefile(Request $request){
        $id=$request->input('id');
        $name=$request->input('name');
         $destinationPath = public_path().'/uploads/inspectionfiles/';
         File::delete($destinationPath.$name);
         $inspectfiles = array();
         $inspectfiles['occupancy_typepdfname'] = "";
        $this->_inspectionreports->updateData($id,$inspectfiles); 
    }
}
