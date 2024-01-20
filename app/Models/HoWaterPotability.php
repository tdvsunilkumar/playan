<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class HoWaterPotability extends Model
{
    public function updateData($id,$columns){
        return DB::table('ho_water_potabilities')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ho_water_potabilities')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_water_potabilities')->where('id',$id)->update($columns);
    }
    public function getctoId(){
      return DB::table('cto_cashier')->select('id','or_no')->get();
    }
    public function getordateId(){
      return DB::table('cto_cashier')->select('id','cashier_or_date')->get();
    }
    public function getoramountId(){
        return DB::table('cto_cashier_details')->select('id','tfc_amount')->get();
    }
    public function getinspectedId(){
    return DB::table('hr_employees')->select('id','fullname')->get();
    }
    public function getinspectedName($id){
      return DB::table('hr_employees')->select('fullname')->where('id',$id)->first();
    }
    public function getCitizenFullname(){
      return DB::table('citizens')->select('id','cit_fullname')->get();
      }
      public function getCitizenAddress($citizen_id){
        return Citizen::
                where('citizens.id', $citizen_id)->first();
    }
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','firstname','middlename','lastname','suffix','title')->get();
    }
      public function getOrNumbers($citizen_id){
        // return DB::table('cto_cashier')->select('or_no', 'id')->orderBy('id', 'DESC')->get();
        return DB::table('cto_cashier')
            ->leftJoin('cto_cashier_details', 'cto_cashier_details.cashier_id', 'cto_cashier.id')
            ->join('cto_forms_miscellaneous_payments', 'cto_forms_miscellaneous_payments.tfoc_id', 'cto_cashier_details.tfoc_id')
            ->where('cto_forms_miscellaneous_payments.fpayment_module_name', 'hs_water_potability')
            ->where('cto_cashier.payee_type', 2)
            ->where('cto_cashier_details.client_citizen_id', $citizen_id)
            ->select('cto_cashier.or_no', 'cto_cashier.id')
            ->orderBy('cto_cashier.id', 'DESC');
    }

    public function getOrNumberDetails($or_no){
        return DB::table('cto_cashier')
            ->where('cto_cashier.or_no', $or_no)
            ->join('cto_cashier_details', 'cto_cashier_details.cashier_id', 'cto_cashier.id')
            ->select('cto_cashier.cashier_or_date', 'cto_cashier_details.tfc_amount', 'cto_cashier_details.id AS cashierd_id', 'cto_cashier.id as cashier_id')
            ->first();
    }
    public function getBrgyDetails($id){
      return  DB::table('bplo_business')
              ->select('bplo_business.*')
              ->where('bplo_business.id',$id)->first();
  }
  public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
  public function getInsPosDetails($id){
    return  DB::table('hr_employees')
            ->Leftjoin('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
            ->select('hr_designations.description')
            ->where('hr_employees.id',$id)->first();
  }
  public function getAppPosDetails($id){
    return  DB::table('hr_employees')
            ->Leftjoin('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
            ->select('hr_designations.description')
            ->where('hr_employees.id',$id)->first();
  }
    public function getBusiness(){
      return DB::table('bplo_business')
      // ->Leftjoin('bplo_business_endorsement', 'bplo_business_endorsement.busn_id', '=', 'bplo_business.id')
      // ->where('bplo_business_endorsement.endorsing_dept_id',3)
      // ->where('busn_app_status','>=',2)
      // ->select('bplo_business_endorsement.id','bplo_business_endorsement.busn_id','bplo_business.busn_name','bend_year','bend_status')
      ->select('id','busn_name')->orderby('bplo_business.id','DESC')->get();
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
                1 =>"bb.busn_name",
                2 =>"hwp.brgy_id",
                3 =>"hwp.certificate_no",
                4 =>"hwp.or_no",
                5 =>"hwp.or_date",
                6 =>"hwp.or_amount",
                7 =>"hwp.date_start",
                8 =>"hwp.date_end",
                9 =>"citizens.cit_fullname",
                10 =>"hwp.date_issued",
                11 =>"he.fullname",
                12 =>"hwp.inspector_position",
                13 =>"hee.fullname",
                14 =>"hwp.approver_position",
                15 =>"hwp.is_approved",
                16 =>"hwp.is_free",
                17 =>"hwp.status",
              );
              $sql = DB::table('ho_water_potabilities AS hwp')
                    ->leftJoin('cto_cashier AS cc', 'cc.id', '=', 'hwp.or_no')
                    ->leftJoin('cto_cashier_details AS ccd', 'ccd.id', '=', 'hwp.or_amount')
                    ->leftJoin('hr_employees AS he', 'he.id', '=', 'hwp.inspected_by')
                    ->leftJoin('hr_employees AS hee', 'hee.id', '=', 'hwp.approved_by')
                    ->leftJoin('citizens', 'citizens.id', '=', 'hwp.requestor_id')
                    ->leftJoin('bplo_business AS bb', 'bb.id', '=', 'hwp.business_id')
                    ->select('hwp.*','ccd.tfc_amount','citizens.cit_fullname','he.fullname','hee.fullname as app_fullname','bb.busn_name');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(hwp.or_no)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%");
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('hwp.id','DESC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
			
			public function getDetailsofwater($id){
				return DB::table('ho_water_potabilities AS hwp')
                    ->leftJoin('cto_cashier AS cc', 'cc.id', '=', 'hwp.or_no')
                    ->leftJoin('cto_cashier_details AS ccd', 'ccd.id', '=', 'hwp.or_amount')
                    ->leftJoin('hr_employees AS he', 'he.id', '=', 'hwp.inspected_by')
                    ->leftJoin('hr_employees AS hee', 'hee.id', '=', 'hwp.approved_by')
                    ->leftJoin('citizens', 'citizens.id', '=', 'hwp.requestor_id')
                    ->leftJoin('bplo_business AS bb', 'bb.id', '=', 'hwp.business_id')
                    ->select('hwp.*','ccd.tfc_amount','citizens.cit_fullname','he.fullname','he.user_id As user_id_inspected_by','hee.fullname as app_fullname','hee.user_id As user_id_approved_by','bb.busn_name')->where('hwp.id',$id)->first();
			}
}
