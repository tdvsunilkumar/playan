<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class RptCtoPenaltyTable extends Model
{

    public function updateData($id,$columns){
        return DB::table('rpt_cto_penalty_tables')->where('id',$id)->update($columns);
    }
    public function addData($postdata){

        return DB::table('rpt_cto_penalty_tables')->insert($postdata);
    }
    
    

    public function getList($request){


        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $currentYear=$request->input('current_year');
        $effectiveYear=$request->input('effect_year');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"cpt_current_year",
          1 =>"cpt_effective_year",  
          2 =>"cpt_month_1",
          3 =>"cpt_month_2",    
          4 =>"cpt_month_3",
          5 =>"cpt_month_4",
          6 =>"cpt_month_5",
          7 =>"cpt_month_6",
          8 =>"cpt_month_7",
          9 =>"cpt_month_8",
          10 =>"cpt_month_9",
          11 =>"cpt_month_10",
          12 =>"cpt_month_11",
          13 =>"cpt_month_12",
          14 =>"is_active"
         );
         $sql = DB::table('rpt_cto_penalty_tables AS bgf')->select('*');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cpt_current_year)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(cpt_effective_year)'),'like',"%".strtolower($q)."%");
                    
            });
        }if(!empty($currentYear) && isset($currentYear)){
            $sql->where(function ($sql) use($currentYear) {
                $sql->where('cpt_current_year',$currentYear);
                    
            });
        }if(!empty($effectiveYear) && isset($effectiveYear)){
            $sql->where(function ($sql) use($effectiveYear) {
                $sql->where('cpt_effective_year',$effectiveYear);
                    
            });
        }

        
        
      
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else

          //

          $sql->orderBy('bgf.cpt_current_year','DESC');
          $sql->orderBy('bgf.cpt_effective_year','DESC');
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}

