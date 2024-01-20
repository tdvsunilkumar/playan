<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoInventoryCategory extends Model
{
    use HasFactory;
    public $table = 'ho_inventory_category';
    protected $guarded;


    public function getCategoryList($request){
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
                1 =>"inv_category",
            );

            $sql = DB::table('ho_inventory_category')
            ->select('id', 'cat_is_active', 'inv_category');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(inv_category)'),'like',"%".strtolower($q)."%"); 
                });
            }
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('id','ASC');

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

    public function addCategory($request){
        try {
           Self::create($request);
           return DB::getPdo()->lastInsertId();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getSingleCategory($id){
        try {
           return Self::find($id);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function updateCategory($id, $request){
        try {
           Self::find($id)->update($request);
           return DB::getPdo()->lastInsertId();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('ho_inventory_category')->where('id',$id)->update($columns);
    }
    
}
