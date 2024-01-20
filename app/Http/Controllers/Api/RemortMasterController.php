<?php

namespace App\Http\Controllers\Api;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class RemortMasterController extends Controller
{
    use ApiResponser;
    public $App_KEY = '';
    public function __construct(){
        $this->App_KEY = \Config::get('app.key');
    }
    public function remortMasterApi(Request $request)
    {
        $id=$request->input('method_id');
        $action=$request->input('action');
        $method=$request->input('method');

        $method_req=$request->input('method_req');
        $action_req=$request->input('action_req');
        $method_req_id=$request->input('method_req_id');
        $method_req_er_serv_id=$request->input('method_req_er_serv_id');

        $method_array=$request->input('method_array');
        $action_array=$request->input('action_array');
        $method_array_ids=$request->input('method_array_ids');

        $method_req_rltn=$request->input('method_req_rltn');
        $action_req_rltn=$request->input('action_req_rltn');
        $method_req_rltn_ids=$request->input('method_req_rltn_ids');
        
        if($id > 0 && !empty($action) && !empty($method))
        {
            $remortServer = DB::connection('remort_server');
            $pre_data= DB::table($method)->where('id',$id)->first();
            $data=(array)$pre_data;
            if (isset($data["is_synced"])) {
                unset($data["is_synced"]);
            }
            switch ($action) {
                case "store":
                    try {
                        $remortServer->table($method)->insert($data);
                    } catch (\Throwable $th) {
                        DB::table($method)->where('id',$id)->update(array('is_synced'=>0));
                    }
                    break;
                default:
                    try {
                        $remortServer->table($method)->where('id',$id)->update($data);
                    } catch (\Throwable $th) {
                        
                    }
            }
        }
        if($method_req != null)
        {
            //  dd($request->all());
            if($method_req =='cpdo_service_requirements'){
                $method_req_ids=json_decode($method_req_id);
                $remortServer = DB::connection('remort_server');
                $remortServer->table($method_req)->where('cs_id',(int)$method_req_er_serv_id)->whereNotIn('req_id',$method_req_ids)->delete();
                foreach($method_req_ids as $k=>$val)
                {
                  
                    $pre_data= DB::table($method_req)->where('cs_id',(int)$method_req_er_serv_id)->where('req_id',$val)->first();
                    $data=(array)$pre_data;
                    if (isset($data["is_synced"])) {
                        unset($data["is_synced"]);
                    }
                    $rmt_data=$remortServer->table($method_req)->where('id',$pre_data->id)->first();
                    if(!empty($rmt_data)){
                        try {
                            $remortServer->table($method_req)->where('id',$pre_data->id)->update($data);
                        } catch (\Throwable $th) {
                            DB::table($method_req)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                        }
                    }else{
                        try {
                            $remortServer->table($method_req)->insert($data);
                        } catch (\Throwable $th) {
                            DB::table($method_req)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                        }
                    }
                }
            }else{
                $method_req_ids=json_decode($method_req_id);
                $remortServer = DB::connection('remort_server');
                $remortServer->table($method_req)->where('es_id',(int)$method_req_er_serv_id)->whereNotIn('req_id',$method_req_ids)->delete();
                foreach($method_req_ids as $k=>$val)
                {
                  
                    $pre_data= DB::table($method_req)->where('es_id',(int)$method_req_er_serv_id)->where('req_id',$val)->first();
                    $data=(array)$pre_data;
                    if (isset($data["is_synced"])) {
                        unset($data["is_synced"]);
                    }
                    $rmt_data=$remortServer->table($method_req)->where('id',$pre_data->id)->first();
                    if(!empty($rmt_data)){
                        try {
                            $remortServer->table($method_req)->where('id',$pre_data->id)->update($data);
                        } catch (\Throwable $th) {
                            DB::table($method_req)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                        }
                    }else{
                        try {
                            $remortServer->table($method_req)->insert($data);
                        } catch (\Throwable $th) {
                            DB::table($method_req)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                        }
                    }
                }
            }
        }

        if($method_array != null)
        {
            $remortServer = DB::connection('remort_server');
            $method_array_ids=json_decode($method_array_ids);
            foreach($method_array_ids as $key=>$val)
            {
                $pre_data= DB::table($method_array)->where('id',$val)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
                switch ($action_array) {
                    case "store":
                        try {
                            $remortServer->table($method_array)->insert($data);
                        } catch (\Throwable $th) {
                            DB::table($method_array)->where('id',$val)->update(array('is_synced'=>0));
                        }
                        break;
                    default:
                        try {
                            $remortServer->table($method_array)->where('id',$val)->update($data);
                        } catch (\Throwable $th) {
                            DB::table($method_array)->where('id',$val)->update(array('is_synced'=>0));
                        }
                }
            }    
        }

        if($method_req_rltn != null)
        {
            $remortServer = DB::connection('remort_server');
            $method_req_rltn_ids=json_decode($method_req_rltn_ids);
            foreach($method_req_rltn_ids as $key=>$val)
            {
                $pre_data= DB::table($method_req_rltn)->where('id',$val)->first();
                $data=(array)$pre_data;
                if (isset($data["is_synced"])) {
                    unset($data["is_synced"]);
                }
                // switch ($action_req_rltn) {
                //     case "store":
                //         try {
                //             $remortServer->table($method_req_rltn)->insert($data);
                //         } catch (\Throwable $th) {
                //             DB::table($method_req_rltn)->where('id',$val)->update(array('is_synced'=>0));
                //         }
                //         break;
                //     default:
                //         try {
                //             $remortServer->table($method_req_rltn)->where('id',$val)->update($data);
                //         } catch (\Throwable $th) {
                //             DB::table($method_req_rltn)->where('id',$val)->update(array('is_synced'=>0));
                //         }
                // }
                $rmt_data=$remortServer->table($method_req_rltn)->where('id',$pre_data->id)->first();
                if(!empty($rmt_data)){
                    try {
                        $remortServer->table($method_req_rltn)->where('id',$pre_data->id)->update($data);
                    } catch (\Throwable $th) {
                        DB::table($method_req_rltn)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                    }
                }else{
                    try {
                        $remortServer->table($method_req_rltn)->insert($data);
                    } catch (\Throwable $th) {
                        DB::table($method_req_rltn)->where('id',$pre_data->id)->update(array('is_synced'=>0));
                    }
                }
            }    
        }
    }
}
