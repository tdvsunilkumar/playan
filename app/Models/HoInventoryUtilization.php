<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoInventoryUtilization extends Model
{
    use HasFactory;
    protected $guarded;

    public function getSuppliers(){
        return DB::table('gso_suppliers')->get();
    }
    public function getcategoryId(){
        return DB::table('ho_inventory_category')->select('id','inv_category')->get();
      }

    public function addData($request){
        return Self::create($request);
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
                1 =>"util_rep_type",
                2 =>"util_rep_path",
                3 =>"util_rep_name",
                4 =>"util_rep_range",
                5 =>"util_rep_year",
                7 =>"util_rep_status",
            );
            
            $sql = DB::table('ho_inventory_utilizations')
            ->join('ho_inventory_category AS hic', 'hic.id', '=', 'ho_inventory_utilizations.util_rep_remarks')
            ->select('ho_inventory_utilizations.*','hic.inv_category');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    // $sql->where(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                    // ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%"); 
                });
            }
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('created_at','DESC');

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

    public function updateActiveInactive($id,$columns){
        return DB::table('ho_inventory_utilizations')->where('id',$id)->update($columns);
    }
}
