<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DashboardGroupMenu extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('dashboard_group_menus')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('dashboard_group_menus')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('dashboard_group_menus')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('dashboard_group_menus')->where('id',$id)->first();
    }
	
	public function getMenugroup(){
        return DB::table('menu_groups')->select('id','name')->where('is_dashboard','=',1)->where('is_active','=',1)->get();
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
          0 =>"id",
          1 =>"menu_group_id",
		      2 =>"menu_name",
          4 =>"slug",
          5 =>"is_active"
           
        );

        $sql = DB::table('dashboard_group_menus As dhm')
			   ->join('menu_groups As mg', 'mg.id', '=', 'dhm.menu_group_id')
              ->select('dhm.*','mg.name');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(dhm.menu_name)'),'like',"%".strtolower($q)."%")
				            ->orWhere(DB::raw('LOWER(mg.name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(dhm.slug)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
