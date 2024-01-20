<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZoningClearanceLine;
use Auth;
use DB;

class ZoningClearance extends Model
{
    use HasFactory;
    protected $table = "cpdo_zoning_computation_clearance";
    protected $guarded;

    public function addData($request){
        $czcc = [
            'cm_id' =>  $request['cm_id'],
            'created_by' =>  Auth::user()->id,
            'updated_by' =>  Auth::user()->id
        ];
        Self::create($czcc);
        $lastInsertedRecord = Self::latest()->first();
        foreach ($request['data'] as $key => $value) {
            $czccl = [
                'czcc_id' =>  $lastInsertedRecord->id,
                'czccl_below' =>  $value['czccl_below'],
                'czccl_over' =>  isset($value['czccl_over']) ? $value['czccl_over'] : null,
                'czccl_over_by_amount' =>  isset($value['czccl_over_by_amount']) ? 1 : 0,
                'czccl_amount' =>  $value['czccl_amount'],
                'is_active' =>  $request['is_active'],
                'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id
            ];
            ZoningClearanceLine::create($czccl);
        }
        return true;
    }

    public function updateData($id, $request){
        $czcc = [
            'cm_id' =>  $request['cm_id'],
            'is_active' =>  1,
            'created_by' =>  Auth::user()->id,
            'updated_by' =>  Auth::user()->id
        ];
        Self::find($id)->update($czcc);
        $czcc_data = Self::where('cm_id', $id)->first();
        foreach ($request['data'] as $key => $value) {
            $czccl = [
                'czcc_id' =>  $czcc_data->id,
                'czccl_below' =>  $value['czccl_below'],
                'czccl_over' =>  isset($value['czccl_over']) ? $value['czccl_over'] : null,
                'czccl_over_by_amount' =>  isset($value['czccl_over_by_amount']) ? 1 : 0,
                'czccl_amount' =>  $value['czccl_amount'],
                'is_active' =>  $request['is_active'],
                'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id
            ];
            
            if(isset($value['id'])){
                ZoningClearanceLine::find($value['id'])->update($czccl);
            }else{
                ZoningClearanceLine::create($czccl);
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
                1 =>"cm.cm_module_desc",
                3 =>"is_active",
            );
            
            $sql = DB::table('cpdo_zoning_computation_clearance AS czcc')
                    ->join('cpdo_module AS cm', 'cm.id', '=', 'czcc.cm_id')
                    ->groupBy('czcc.cm_id')
                    ->select('cm.*', 'czcc.is_active AS czcc_is_active');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(cdpcl_description)'),'like',"%".strtolower($q)."%");
                });
            }
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('czcc.created_at','DESC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getSingleData($cm_id){
        return Self::where('cm_id', $cm_id)->first();
    }

    public function getCZCCLines($czcc_id){
        return ZoningClearanceLine::where('czcc_id', $czcc_id)->get();
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('cpdo_zoning_computation_clearance')->where('cm_id',$id)->update($columns);
    }

}
