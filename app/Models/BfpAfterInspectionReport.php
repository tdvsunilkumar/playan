<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BfpAfterInspectionReport extends Model
{
    
   

    public function getYearDetails(){
        return DB::table('bplo_business_endorsement')->select('bend_year')->groupBy('bend_year')->orderBy('bend_year','DESC')->get()->toArray(); 
    }

  
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $bbendo_id=$request->input('bbendo_id');
        $year=$request->input('year');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"busns_id_no",
          2 =>"ownar_name",
          3 =>"busn_name",
          4 =>"app_code",
          5 =>"bb.created_at",
          6 =>"busn_app_method",
          7 =>"busn_app_status"
        );
        

        $sql = DB::table('bplo_business AS bb')
        ->Leftjoin('clients AS cl', 'bb.client_id', '=', 'cl.id')
        ->Leftjoin('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
        // ->Leftjoin('bfp_application_forms AS bfp', 'bfp.busn_id', '=', 'bb.id')
        ->select('bb.id','endorsing_dept_id','busn_name','busns_id_no','app_code','busn_app_status','busn_app_method','suffix','bb.created_at','bend_status','bend_year',DB::raw("CASE 
        WHEN rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,'')))
        WHEN rpo_first_name IS NULL AND rpo_middle_name IS NULL AND suffix IS NULL THEN COALESCE(rpo_custom_last_name,'')
        ELSE TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''))) END as ownar_name"))
        ->where('busn_app_status','>=',2)->where('endorsing_dept_id',1)->whereNotNull('inspection_report_attachment');
        
        if(!empty($year)){
            $sql->where('bend_year',(int)$year);
        }

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ', COALESCE(rpo_custom_last_name), ', ', suffix)"), 'LIKE', "%{$q}%")   
                    ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(function ($sql) use ($q) {
                          if ($q === 'New' || $q === 'new') {
                              $sql->where('app_code', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'Renew' || $q === 'renew') {
                              $sql->where('app_code', '=', 2); // Condition for Exempt (option 2)
                          }elseif ($q === 'Retire' || $q === 'retire') {
                              $sql->where('app_code', '=', 3); // Condition for Exempt (option 2)
                          }else {
                              $sql->where('app_code', '=', ''); // Condition to return no results for other search terms
                          }
                    })
                    ->orWhere(DB::raw('DATE_FORMAT(bb.created_at, "%Y-%m-%d %H:%i:%s")'), 'LIKE', "%" . $q . "%")
                     ->orWhere(DB::raw('LOWER(busn_app_method)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%"); 
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

