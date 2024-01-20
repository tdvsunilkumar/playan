<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DevelopmentPermitComputationLine;
use Auth;
use DB;

class DevelopmentPermitComputation extends Model
{
    use HasFactory;
    protected $guarded;
    protected $table = 'cpdo_development_permit_computation';

    public function getServices($cm_id = null){
        $services = DB::table('cpdo_module')->select('id', 'cm_module_desc')->where('cm_type', 2)->get();
        foreach ($services as $key => $value) {
            if(Self::where('cm_id', $value->id)->count() > 0){
                unset($services[$key]);
            }
        }
        return $services;
    }

    public function getZoningServices($cm_id = null){
        $services = DB::table('cpdo_module')->select('id', 'cm_module_desc')->where('cm_type', 1)->get();
        foreach ($services as $key => $value) {
            if(DB::table('cpdo_zoning_computation_clearance')->where('cm_id', $value->id)->count() > 0){
                unset($services[$key]);
            }
        }
        return $services;
    }

    public function getServicesById($cm_id){
        return $services = DB::table('cpdo_module')
        ->select('id', 'cm_module_desc')
        ->where('id', $cm_id)->get();
    }

    public function getImperials(){
        return DB::table('cpdo_imperial_system')->select('id', 'cis_code', 'cis_imperial_system')->where('is_active', 1)->get();
    }

    public function addData($request){
        $cdpc = [
            'cm_id' =>  $request['cm_id'],
            'cis_status' =>  1,
            'is_active' =>  $request['is_active'],
            'created_by' =>  Auth::user()->id,
            'updated_by' =>  Auth::user()->id
        ];
        Self::create($cdpc);
        $lastInsertedRecord = Self::latest()->first();
        foreach ($request['data'] as $key => $value) {
            $cdpc = [
                'cdpc_id' =>  $lastInsertedRecord->id,
                'cdpcl_description' =>  $value['cdpcl_description'],
                'cdpcl_amount' =>  $value['cdpcl_amount'],
                'cis_id' =>  $value['cis_id'],
                'is_active' =>  $request['is_active'],
                'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id
            ];
            DevelopmentPermitComputationLine::create($cdpc);
        }
        return true;
    }

    public function updateData($id, $request){
        $cdpc = [
            'cm_id' =>  $request['cm_id'],
            'cis_status' =>  1,
            'is_active' =>  $request['is_active'],
            'created_by' =>  Auth::user()->id,
            'updated_by' =>  Auth::user()->id
        ];
        Self::where('cm_id', $id)->update($cdpc);
        $cdpc_data = Self::where('cm_id', $id)->first();
        foreach ($request['data'] as $key => $value) {
            $cdpcl = [
                'cdpc_id' =>  $cdpc_data->id,
                'cdpcl_description' =>  $value['cdpcl_description'],
                'cdpcl_amount' =>  $value['cdpcl_amount'],
                'cis_id' =>  $value['cis_id'],
                'is_active' =>  $request['is_active'],
                'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id
            ];
            if(isset($value['cdpcl_id'])){
                DevelopmentPermitComputationLine::find($value['cdpcl_id'])->update($cdpcl);
            }else{
                DevelopmentPermitComputationLine::create($cdpcl);
            }
        }
        return true;
    }

    public function getList($request){
        try {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }

            $columns = array( 
                0 =>"id",
                1 =>"cm_module_desc",
                3 =>"is_active",
            );
            
            $sql = DB::table('cpdo_development_permit_computation AS cdpc')
            ->join('cpdo_module AS cm', 'cdpc.cm_id', 'cm.id')
            ->groupBy('cdpc.cm_id')
            ->select('cm.*', 'cdpc.is_active AS cpdo_is_active');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(cm.cm_module_desc)'),'like',"%".strtolower($q)."%");
                });
            }
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('cdpc.created_at','DESC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->get()->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getSingleData($cm_id){
        return Self::where('cm_id',$cm_id)->first();
    }

    public function getCDPOLines($cdpc_id){
        return DevelopmentPermitComputationLine::where('cdpc_id', $cdpc_id)->get();
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('cpdo_development_permit_computation')->where('cm_id',$id)->update($columns);
    } 

}
