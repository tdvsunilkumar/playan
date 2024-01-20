<?php

namespace App\Http\Controllers\Api;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class RemortBploController extends Controller
{
    use ApiResponser;
    public $App_KEY = '';
    public function __construct(){
        $this->App_KEY = \Config::get('app.key');
    }
    public function remoteUpdateBusinessTable(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_id=$request->input('busn_id');
            $pre_data= DB::table('bplo_business')->where('id',$busn_id)->first();
            DB::table('bplo_business')->where('id',$busn_id)->update(array('is_synced'=>0));
            try {
                $remortServer = DB::connection('remort_server');
                $arrBusn = $remortServer->table('bplo_business')->select('id')->where('frgn_busn_id',$busn_id)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
                unset($data["id"]);
                unset($data["online_busn_bldg_tax_declaration_no"]);
                unset($data["online_busn_bldg_property_index_no"]);
                if(isset($arrBusn)){
                    $remortServer->table('bplo_business')->where('frgn_busn_id',$busn_id)->update($data);
                }else{
                    $data['frgn_busn_id'] = $busn_id;
                    $data['is_approved'] = 1;
                    $remortServer->table('bplo_business')->insert($data);
                }
                DB::table('bplo_business')->where('id',$busn_id)->update(array('is_synced'=>1));
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully updated.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }
    public function updateRemortBploBusnPlan(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_plan_id=$request->input('busn_plan_id');
            $pre_data= DB::table('bplo_business_psic')->where('id',$busn_plan_id)->first();
            DB::table('bplo_business_psic')->where('id',$busn_plan_id)->update(array('is_synced'=>0));
            try {
                $remortServer = DB::connection('remort_server');
                $arrBusn = $remortServer->table('bplo_business_psic')->select('id')->where('frgn_busn_psic_id',$busn_plan_id)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
               
                unset($data["id"]);
                if(isset($arrBusn)){
                    $remortServer->table('bplo_business_psic')->where('frgn_busn_psic_id',$busn_plan_id)->update($data);
                }else{
                    $data['frgn_busn_psic_id'] = $busn_plan_id;
                    $remortServer->table('bplo_business_psic')->insert($data);
                }
                DB::table('bplo_business_psic')->where('id',$busn_plan_id)->update(array('is_synced'=>1));
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully updated.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }
    public function updateRemortBploMeasurePax(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_id=$request->input('busn_id');
            $busn_psic_id=$request->input('busn_psic_id');
            $buspx_charge_id=$request->input('buspx_charge_id');
            $pre_data= DB::table('bplo_business_measure_pax')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->first();

            DB::table('bplo_business_measure_pax')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->update(array('is_synced'=>0));
            try {
                $remortServer = DB::connection('remort_server');
                $arrBusn = $remortServer->table('bplo_business_measure_pax')->select('id')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
               
                unset($data["id"]);
                if(isset($arrBusn)){
                    $remortServer->table('bplo_business_measure_pax')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->update($data);
                }else{
                    $remortServer->table('bplo_business_measure_pax')->insert($data);
                }
                DB::table('bplo_business_measure_pax')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->update(array('is_synced'=>1));
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully updated.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }

    public function updateRemortBploReqDoc(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_id=$request->input('busn_id');
            $busn_psic_id=$request->input('busn_psic_id');
            $req_code=$request->input('req_code');
            $pre_data= DB::table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->first();

            DB::table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->update(array('is_synced'=>0));
            try {
                $remortServer = DB::connection('remort_server');
                $arrBusn = $remortServer->table('bplo_business_psic_req')->select('id')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
               
                unset($data["id"]);
                if(isset($arrBusn)){
                    $remortServer->table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->update($data);
                }else{
                    $destinationPath =  public_path().'/uploads/bplo_business_req_doc/'.$data["attachment"];
                    $fileContents = file_get_contents($destinationPath);
                    $remotePath = 'public/uploads/bplo_business_req_doc/'.$data["attachment"];
                    Storage::disk('remote')->put($remotePath, $fileContents);
                    $remortServer->table('bplo_business_psic_req')->insert($data);
                }
                DB::table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->update(array('is_synced'=>1));
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully updated.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }

    public function removeRemortBploReqDoc(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_id=$request->input('busn_id');
            $busn_psic_id=$request->input('busn_psic_id');
            $req_code=$request->input('req_code');
            try {
                $remortServer = DB::connection('remort_server');
                $pre_data=$remortServer->table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->first();
                    $remotePath = 'public/uploads/bplo_business_req_doc/'.$pre_data->attachment;
                    Storage::disk('remote')->delete($remotePath);
                $remortServer->table('bplo_business_psic_req')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('req_code',$req_code)->delete();    
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully removed.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }
    public function removeRemortBploMeasurePax(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_id=$request->input('busn_id');
            $busn_psic_id=$request->input('busn_psic_id');
            $buspx_charge_id=$request->input('buspx_charge_id');
            try {
                $remortServer = DB::connection('remort_server');
                $remortServer->table('bplo_business_measure_pax')->where('busn_id',$busn_id)->where('busn_psic_id',$busn_psic_id)->where('buspx_charge_id',$buspx_charge_id)->delete();    
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully removed.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }
    public function removeRemortBploBusnPlan(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $busn_plan_id=$request->input('busn_plan_id');
            try {
                $remortServer = DB::connection('remort_server');
                $remortServer->table('bplo_business_psic')->where('frgn_busn_psic_id',$busn_plan_id)->delete();    
            }catch (\Throwable $error) {
                return $error;
            }
            return "Successfully removed.";
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }

    
    
}
