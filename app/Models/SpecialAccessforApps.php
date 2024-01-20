<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SpecialAccessforApps extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('menu_special_access')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('menu_special_access')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('menu_special_access')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('menu_special_access')->where('id',$id)->first();
    }
	public function get_module_id($id){
        return DB::table('menu_modules')->where('id',$id)->first()->id;
    }
	public function get_sub_module($menu_module_id)
    {
        return (new MenuSubModule)->reload_sub_module($menu_module_id);
    }
	public function getMenugroup(){
        return DB::table('menu_modules As a')
		->join('menu_groups As b', 'a.menu_group_id', '=', 'b.id') 
		->select('a.id','b.id As groupsid','a.name As modules_name','b.name As groups_name')
		->where('b.is_active','=',1)
		->where('a.is_active','=',1)
		->get();
    }
	
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
		$menu_group_id=$request->input('menu_group_id');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"group_id",
		  2 =>"module_id",
          3 =>"sub_module_id",
		  4 =>"application",
		  5 =>"remarks",
          6 =>"is_active" 
        );

        $sql = DB::table('menu_special_access As dhm')
			   ->leftjoin('menu_sub_modules As msm', 'msm.id', '=', 'dhm.sub_module_id')
               ->select('dhm.*','msm.name');
			   
		if(!empty($menu_group_id) && isset($menu_group_id)){
            $sql->where('dhm.module_id','=',$menu_group_id);  
        }
		
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(dhm.group_id)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(dhm.remarks)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('dhm.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
