<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptPropertyClass extends Model
{

  public $table = 'rpt_property_classes';
 
    public function updateData($id,$columns){
        return DB::table('rpt_property_classes')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_property_classes')->insert($postdata);
    }

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_property_classes')->where('id',$id)->update($columns);
    }  

    public function allRptClass($id = null) {
      $query = DB::table('rpt_classes');
  
      if ($id !== null) {
          $query->where('id', $id);
      }
  
      return $query->get();
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
          1 =>"pc_class_code", 
          2 =>"pc_class_description",
          3 =>"pc_class_no",   
          4 =>"pc_unit_value_option", 
          5 =>"pc_taxability_option",
		  6 =>"pc_is_active",
		  7 =>"pc_is_active",
          8 =>"pc_is_active"
         );
         $sql = DB::table('rpt_property_classes AS bgf')->select('*');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if (!empty($q) && isset($q)) {
          $sql->where(function ($sql) use ($q) {
              $sql->orWhere(DB::raw('LOWER(bgf.pc_class_description)'), 'like', "%" . strtolower($q) . "%")
                  ->orWhere(DB::raw('LOWER(bgf.pc_class_code)'), 'like', "%" . strtolower($q) . "%")
                  ->orWhere(DB::raw('LOWER(bgf.pc_class_no)'), 'like', "%" . strtolower($q) . "%")
                  ->orWhere(function ($sql) use ($q) {
                      if ($q === 'Taxable') {
                          $sql->where('bgf.pc_taxability_option', '=', 1); // Condition for Taxable (option 1)
                      } elseif ($q === 'Exempt') {
                          $sql->where('bgf.pc_taxability_option', '=', 2); // Condition for Exempt (option 2)
                      } else {
                          $sql->where('bgf.pc_taxability_option', '=', ''); // Condition to return no results for other search terms
                      }
                  })
                  ->orWhere(function ($sql) use ($q) {
                      if ($q === 'Scheduled By District') {
                          $sql->where('bgf.pc_unit_value_option', '=', 1); 
                      } elseif ($q === 'Scheduled By Property Location') {
                          $sql->where('bgf.pc_unit_value_option', '=', 2); 
                      } else {
                          $sql->where('bgf.pc_unit_value_option', '=', '');
                      }
                  });
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

