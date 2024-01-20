<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptUpdateCode extends Model
{

    use HasFactory;

    protected $fillable = [
        'uc_code',
        'uc_description',
        'uc_usage_land',
        'uc_usage_building',
        'uc_usage_machine',
        'uc_change_property_of_ownership',
        'uc_cancel_existing_faas',
        'uc_consolidate_existing_faas',
        'uc_subdivide_existing_faas',
        'uc_cancel_only_one_existing_faas',
        'uc_cease_tax_declaration',
        'uc_revised_tax_declaration',
        'uc_is_active',
        'uc_registered_by',
        'uc_modified_by',
        'direct_cancellation'
    ];

    protected $table = 'rpt_update_codes';

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_update_codes')->where('id',$id)->update($columns);
    }  
    
     public function updateData($id,$columns){
        $data = tap(DB::table('rpt_update_codes')->where('id',$id))->update($columns);
        $respinseModel = $data->first();
        return $respinseModel->id;
    }
    //  public function updateData($id,$columns){
    //     return DB::table('rpt_update_codes')->where('id',$id)->update($columns);
    // }
    public function addData($postdata){
        DB::table('rpt_update_codes')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    // public function updatePrint($id,$columns){
    //     return DB::table('rpt_update_codes')->where('id',$id)->update($columns);
    // }  
    public function updatePrint($id = null){
      $updateNewFresh = $this->find($id);

      if($updateNewFresh->uc_new_fresh == 1){
        try {
          //dd($this->where('id','!=',$barangay->id)->get());
          $this->where('id','!=',$updateNewFresh->id)->update(['uc_new_fresh'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
      
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }



        $columns = array( 
          0 => "id",
          1 =>"uc_code",  
          2 =>"uc_description",
          3 =>"uc_usage_land",
          4 =>"uc_usage_building",
          5 =>"uc_usage_machine",
          6 =>"uc_new_fresh",
          7 =>"uc_is_active",
         );
         $sql = DB::table('rpt_update_codes AS bgf')->select('id','uc_code','uc_description','uc_usage_land','uc_usage_building','uc_usage_machine','uc_change_property_of_ownership','uc_cancel_existing_faas','uc_consolidate_existing_faas','uc_subdivide_existing_faas','uc_cancel_only_one_existing_faas','uc_cease_tax_declaration','uc_revised_tax_declaration','uc_is_active','uc_new_fresh');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(uc_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(uc_description)'),'like',"%".strtolower($q)."%");           
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
