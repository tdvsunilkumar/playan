<?php
namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BusinessPermitRetire extends Model
{
    public function getYearDetails(){
        return DB::table('bplo_business_retirement')->select('retire_year')->groupBy('retire_year')->orderBy('retire_year','DESC')->get()->toArray(); 
    }
    public function updateData($id,$columns){
        return DB::table('bplo_business_retirement')->where('id',$id)->update($columns);
    }
    public function updateRetirementDetails($id,$columns){
        return DB::table('bplo_business_retirement_psic')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bplo_business_retirement')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function addRetirementDetails($postdata){
        DB::table('bplo_business_retirement_psic')->insert($postdata);
    }
    public function checkRecordIsExist($subclass_id,$retire_id){
        return DB::table('bplo_business_retirement_psic')->select('id')->where('subclass_id','=',$subclass_id)->where('busnret_id',$retire_id)->get();
    } 
    public function getEditDetails($id){
        return DB::table('bplo_business_retirement')->where('id',$id)->first();
    }
    public function getRetireReason($id=0){
        return DB::table('bplo_retire_reason')->select('id','name')->where('is_active','1')->orWhere('id',$id)->orderBy('id','ASC')->get()->toArray();
    }
    public function getbussDtls($id=0){
        return DB::table('bplo_business')->select('id','busn_name','busns_id_no','busn_bldg_area','busn_bldg_total_floor_area','busn_employee_no_female','busn_employee_no_male','busn_employee_total_no','busn_employee_no_lgu','busn_vehicle_no_van_truck','busn_vehicle_no_motorcycle','app_code')->where('id',$id)->first();
    }
    public function checkPrevPermitIssuance($busn_id=0){
         return DB::table('bplo_business_permit_issuance')->select('bpi_issued_date')->where('busn_id',(int)$busn_id)->where('busn_id',(int)$busn_id)->orderBy('id','DESC')->first();
    }
    public function getBussinessList($id=0){
        $sql= DB::table('bplo_business')->select('id','busn_name','busns_id_no')->where('busns_id_no','<>','');
        if($id>0){
            $sql->where('id',(int)$id);
        }else{
            $sql->where('is_active','1')->whereIn('app_code',[1,2]);
        }
        return $sql->orderBy('id','DESC')->get()->toArray();
    }
    public function getLineOfBusiness($busn_id=0){
        return DB::table('bplo_business_psic AS bp')
            ->join('psic_subclasses AS ps', 'ps.id', '=', 'bp.subclass_id') 
            ->select('bp.id AS psic_id','subclass_id','subclass_description','busp_capital_investment','busp_essential','busp_non_essential','busp_no_units','subclass_code')->where('bp.busn_id',$busn_id)->orderBy('subclass_description','ASC')->get()->toArray();
    }
    public function prevPSICExist($id=0,$subclass_id=0){
        return DB::table('bplo_business_retirement_psic')->select('busnret_capital_investment','busnret_essential','busnret_non_essential')->where('subclass_id',$subclass_id)->where('busnret_id',$id)->first();
    }
    public function deletePSICSubclass($busn_id=0,$id=0,$subclassIds){
        return DB::table('bplo_business_retirement_psic')->where('busn_id',$busn_id)->where('busnret_id',$id)->whereNotIn('subclass_id',$subclassIds)->delete();
    }
    public function getRequirementList($sub_class_id=0){
        $arrSub = DB::table('psic_subclasses')->select('id','group_id','class_id','division_id','section_id')->where('id','=',$sub_class_id)->first();
        $sql = DB::table('requirements AS req')
            ->join('bplo_requirement_relations AS brr', 'brr.requirement_id', '=', 'req.id') 
            ->join('bplo_requirements AS br', 'br.id', '=', 'brr.bplo_requirement_id') 
            ->select('req.req_code_abbreviation','req.req_description','req.id')->where('req_dept_bplo',1)->where('brr.is_active',1)->where('br.apptype_id',3)->where('br.subclass_id',$sub_class_id)->orderBy('req_description','ASC');
        if(isset($arrSub)){
            $sql->where('br.group_id',(int)$arrSub->group_id);
            $sql->where('br.class_id',(int)$arrSub->class_id);
            $sql->where('br.division_id',(int)$arrSub->division_id);
            $sql->where('br.section_id',(int)$arrSub->section_id);
            $sql->where('br.subclass_id',(int)$arrSub->id);
        }
        
        return $sql->get()->toArray();
    }
    public function deleteRetirementApplication($retirment_id){
        DB::table('bplo_business_retirement')->where('id',$retirment_id)->delete();
        DB::table('bplo_business_retirement_psic')->where('busnret_id',$retirment_id)->delete();
        return true;
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 =>"busns_id_no",
          2 =>"ownar_name",
          3 =>"busn_name",
          4 =>"retire_application_type",
          5 =>"name",
          6 =>"retire_date_start",
          7 =>"retire_date_closed",  
          9 =>"retire_status"     
        );
        $sql = DB::table('bplo_business_retirement AS br')
            ->join('bplo_business AS bb', 'bb.id', '=', 'br.busn_id') 
            ->join('clients AS c', 'c.id', '=', 'bb.client_id') 
            ->join('bplo_retire_reason AS brr', 'brr.id', '=', 'br.retire_reason_ids')
            ->select('br.*',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name','busn_name','busns_id_no','brr.name');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")  
                ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ', COALESCE(rpo_custom_last_name), ', ', suffix)"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw('LOWER(retire_reason_remarks)'),'like',"%".strtolower($q)."%")
                ->orWhere(function ($sql) use ($q) {
                          if ($q === 'Per Line of Business') {
                              $sql->where('retire_application_type', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'Entire Business' || $q === 'entire business') {
                              $sql->where('retire_application_type', '=', 2); // Condition for Exempt (option 2)
                          }else {
                              $sql->where('retire_application_type', '=', ''); // Condition to return no results for other search terms
                          }
                    })
                    
                ->orWhere(DB::raw('DATE_FORMAT(retire_date_start, "%b %d, %Y")'), 'LIKE', "%" . strtolower($q) . "%")
                ->orWhere(DB::raw('DATE_FORMAT(retire_date_closed, "%b %d, %Y")'), 'LIKE', "%" . strtolower($q) . "%")
                ->orWhere(DB::raw('LOWER(brr.name)'), 'LIKE', "%" . strtolower($q) . "%")
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($year)){
            $sql->where("retire_year",(int)$year);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('br.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
