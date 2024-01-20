<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\Configuration;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ConfigurationController extends Controller
{
   
   
    public function __construct(){
        $this->_configuration = new Configuration();
        $this->_commonmodel = new CommonModelmaster();  
        $this->data = array('id'=>'','configuration_value'=>'');
        }
        public function index(Request $request)
        {
            $data = Configuration::find(1);
            //$data = $this->_psicClass->getclassList();
            return view('configuration.index',compact('data'));
            
        }
        public function updateSystemSetup(Request $request){
            $updatedata = array();
            $updatedata['configuration_value'] = $request->input('configuration_value');
            $this->_configuration->updateData($request->input('id'),$updatedata);
            if($request->input('configuration_value')){
              $penaltylogdata= array();
                // $penaltylogdata['oldconfiguration_value'] = $request->input('oldconfiguration_value');
                $penaltylogdata['configuration_value'] = $request->input('configuration_value');
                $penaltylogdata['updated_by'] = \Auth::user()->creatorId();
                $penaltylogdata['created_at'] = date('Y-m-d H:i:s');
                $penaltylogdata['updated_at'] = date('Y-m-d H:i:s');
                // $this->_configuration->addSystemData($penaltylogdata);
            }
            echo "Success";
        }
}
