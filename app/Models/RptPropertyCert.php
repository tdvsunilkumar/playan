<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptPropertyCert extends Model
{
    public function updateActiveInactive($id,$columns){
        return DB::table('rpt_property_certs')->where('id',$id)->update($columns);
    }  
    public function addRptCertRelation($postdata){
         return DB::table('rpt_property_cert_details')->insert($postdata);
        // return DB::getPdo()->lastInsertId();
    }
    public function updatePropertyCertDetails($id,$columns){
        return DB::table('rpt_property_cert_details')->where('id',$id)->update($columns);
    }
    public function checkCertRelationExit($id){
        return DB::table('rpt_property_cert_details')->select('id')->where('rpc_code',(int)$id)->get()->toArray();
    }
    public function getctoCashier(){
         return DB::table('cto_cashier')->select('id','cashier_or_date','ctc_place_of_issuance','or_no')->where('payee_type',1)->get();
    }
    public function getctoCashierIdPropertyNoLandHoldingAjaxold($search="",$client_id=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($client_id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $client_id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9);
            })
            ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_land_holding');
            })
            ->select('a.or_no')
            ->distinct();
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('a.or_no',$search);
          }else{
            $sql->where(DB::raw('LOWER(a.or_no)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('a.or_no','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getctoCashierIdPropertyNoLandHolding($client_id) {
        return DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($client_id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $client_id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9);
            })
            ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_land_holding');
            })
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC')
            ->get();
    }
     public function getctoCashierIdPropertyNoLandHoldingnew($client_id,$taxpayerid) {
        $citizentds = array($client_id,$taxpayerid);
        return DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($citizentds) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->whereIn('b.client_citizen_id', $citizentds)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9)
                    ->where('b.ocr_id', 0);
            })
            ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_land_holding');
            })
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC')
            ->get();
    }
    public function getctoCashierIdPropertyNoLandHoldingAjax($search="",$id,$taxpayerid){
      $citizentds = array($id,$taxpayerid);
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
         $sql = DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($citizentds) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->whereIn('b.client_citizen_id', $citizentds)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9)
                    ->where('b.ocr_id', 0);
            })
           ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_land_holding');
            }) 
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
            $sql->where(DB::raw('LOWER(a.or_no)'),'like',"%".strtolower($search)."%");
        });
      }
      $sql->orderBy('a.or_no','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getctoCashierIdPropertyNoimprovementAjax($search="",$id){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
         $sql = DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9)
                    ->where('b.ocr_id', 0);
            })
           ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_improvement');
            }) 
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
            $sql->where(DB::raw('LOWER(a.or_no)'),'like',"%".strtolower($search)."%");
        });
      }
      $sql->orderBy('a.or_no','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getctoCashierIdPropertyholdingAjax($search="",$id){
       $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
         $sql = DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9)
                    ->where('b.ocr_id', 0);
            })
           ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_property_holding');
            }) 
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
            $sql->where(DB::raw('LOWER(a.or_no)'),'like',"%".strtolower($search)."%");
        });
      }
      $sql->orderBy('a.or_no','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }


    public function getctoCashierIdPropertyImprovment($client_id) {
        return DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($client_id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $client_id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9);
            })
            ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_no_improvement');
            })
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC')
            ->get();
    }
    // public function getctoCashierId($id){
    //      return DB::table('cto_cashier')->select('id','cashier_or_date','ctc_place_of_issuance','or_no')->where('payee_type',1)->where('status',1)->where('tfoc_is_applicable',9)->where('client_citizen_id',$id)->orderBy('id','DESC')->get();
    // }
    // public function getctoCashierDetails($id){
    //      return DB::table('cto_cashier as cto')
    //                 ->join('cto_forms_miscellaneous_payments as cto_payment', 'cto_payment.cashier_id', '=', 'cto.id')
    //                 ->select('cto.id','cto.cashier_or_date','cto.ctc_place_of_issuance','cto.or_no')->where('cto.payee_type',1)->where('cto.status',1)->where('cto.tfoc_is_applicable',9)->where('cto.client_citizen_id',$id)->orderBy('cto.id','DESC')->get();
    // }
       public function getctoCashierDetailsPropertyHolding($client_id) {
        return DB::table('cto_cashier_details as a')
            ->join('cto_cashier as b', function ($join) use ($client_id) {
                $join->on('a.cashier_id', '=', 'b.id')
                    ->where('b.client_citizen_id', $client_id)
                    ->where('b.payee_type', 1)
                    ->where('b.status', 1)
                    ->where('b.tfoc_is_applicable', 9);
            })
            ->join('cto_forms_miscellaneous_payments as c', function ($join) {
                $join->on('a.tfoc_id', '=', 'c.tfoc_id')
                    ->where('c.fpayment_module_name', 'rpt_cert_of_property_holding');
            })
            ->select('a.or_no')
            ->distinct()
            ->orderBy('a.or_no', 'DESC')
            ->get();
    }

    public function getctoCashierIsseueanceDetails($id){
          return DB::table('cto_cashier as cto')
                    ->leftjoin('cto_cashier_details AS ctod', 'ctod.cashier_id', '=', 'cto.id')
                    ->select('cto.id','cto.cashier_or_date','cto.ctc_place_of_issuance','cto.or_no','ctod.tfc_amount','ctod.id as ctodetailsId')->where('cto.payee_type',1)->where('ctod.or_no',$id)->first();
    }
    public function UpdateCertRelationExit($id){
        return DB::table('rpt_property_cert_details')->select('*')->where('rpc_code',$id)->get()->toArray();
    }
     public function getCertRelation($id){
       return DB::table('rpt_property_cert_details AS cd')
       ->join('rpt_property_certs AS c', 'c.id', '=', 'cd.rpc_code')
       ->select('*','cd.id AS relationId')->where('rpc_code',$id)->get()->toArray();
    }
    public function updateData($id,$columns){
        return DB::table('rpt_property_certs')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('rpt_property_certs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function addData2($postdata){
         DB::table('rpt_property_certs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getCountries(){
         return DB::table('countries')->select('id','country_name')->where('is_active',1)->get();
    } 
    public function addDataClient($postdata){
        DB::table('clients')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getProfile(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name')->get();
    }

    
    public function getBarangay(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
    public function getClientsName(){
        return DB::table('clients')->select('id','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name')->where('is_rpt',1)->where('is_active',1)->orderBy('id','DESC')->get();
    }
    public function getClientsNameAjax($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('clients')
             ->where('is_rpt',1)
             ->where('is_active',1)
             ->select('full_name','id');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(full_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('full_name','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getClientsNameDetails($id){
        return DB::table('clients')->select('id','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name')->where('is_rpt',1)->where('is_active',1)->where('id','=',$id)->get();
    }
    public function getHrEmplyees(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname','fullname','suffix','title')->where('is_active',1)->get();
    }
     public function getHrEmplyeesAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('rpt_appraisers AS ra')
        ->join('hr_employees AS h', 'h.id', '=', 'ra.ra_appraiser_id')
             ->where('ra.ra_is_active',1)
             ->select('ra.id','h.firstname','h.middlename','h.lastname','h.fullname','h.suffix','h.title','ra.ra_appraiser_position AS description');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('ra.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(h.fullname)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('h.fullname','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getHrEmplyeesAppraisers(){
        return DB::table('rpt_appraisers AS ra')
        ->join('hr_employees AS h', 'h.id', '=', 'ra.ra_appraiser_id')
        ->select('ra.id','h.firstname','h.middlename','h.lastname','h.fullname','h.suffix','h.title','ra.ra_appraiser_position AS description')
        ->where('ra.ra_is_active',1)->get();
    }
    public function getAppraisersPositionDetails($id){
        return DB::table('rpt_appraisers AS ra')
        ->join('hr_employees AS h', 'h.id', '=', 'ra.ra_appraiser_id')
        ->select('ra.id','h.firstname','h.middlename','h.lastname','h.fullname','h.suffix','h.title','ra.ra_appraiser_position AS description')->where('ra.id',(int)$id)->first();
    }
    public function getCertPositionDetails($id){
         return DB::table('hr_employees AS h')
               ->join('hr_designations AS hd', 'hd.id', '=', 'h.hr_designation_id')
               ->select('h.id','hd.description')->where('h.is_active',1)->where('h.id',(int)$id)->first();
    }
    public function getClientsFirst($firstName){
        return DB::table('clients')->select('id','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name')->where('is_rpt',1)->where('is_active',1)->where('rpo_first_name','=',$firstName)->get();
    }


    
    public function getClientCodeAddressPropertyLand($lastNamePropertyLand){
        $result=array();
        $nameArray = explode(',', $lastNamePropertyLand);
        $sql=RptPropertyOwner::where('is_rpt',1)->where('is_active',1);
        foreach($nameArray as $key => $lastNamePropertyLands){
           if($key=="0"){
               $sql->where('rpo_custom_last_name','like','"%'.$lastNamePropertyLands.'%"');
           }else{
                $sql->orWhere('rpo_custom_last_name','like','"%'.$lastNamePropertyLands.'%"');
           }
           $sql->orWhere('full_name','like','"%'.$lastNamePropertyLands.'%"');   
           $sql->orWhere('rpo_first_name','like','"%'.$lastNamePropertyLands.'%"');
           $sql->orWhere('p_mobile_no','like','"%'.$lastNamePropertyLands.'%"');
           $sql->orWhere('p_telephone_no','like','"%'.$lastNamePropertyLands.'%"');
           $sql->orWhere('p_email_address','like','"%'.$lastNamePropertyLands.'%"');
           $sql->orWhereRaw('CONCAT_WS(" ", trim(rpo_first_name), trim(rpo_middle_name), trim(rpo_custom_last_name)) like "%' . $lastNamePropertyLands . '%"');
        }
        $sql->whereExists(function ($query) {
               $query->select("rpo_code")
                  ->from('rpt_properties')
                  ->whereRaw('rpo_code = clients.id')
                  ->where('pk_is_active', 1);
        });
        
        $result=$sql->get();
        return $result;
    }
    public function getClientCodeAddressImprovement($lastNamePropertyLand){
        
        
        $sql=DB::table('clients')->select ('id','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','rpo_address_street_name','rpo_address_house_lot_no','rpo_address_subdivision','p_mobile_no','p_telephone_no','p_email_address')->where('is_rpt',1)->where('is_active',1);
            
        
               $sql->where('rpo_custom_last_name','like','"%'.$lastNamePropertyLand.'%"');
          
                $sql->orWhere('rpo_custom_last_name','like','"%'.$lastNamePropertyLand.'%"');
          
           $sql->orWhere('full_name','like','"%'.$lastNamePropertyLand.'%"');    
           $sql->orWhere('rpo_first_name','like','"%'.$lastNamePropertyLand.'%"');
           $sql->orWhere('p_mobile_no','like','"%'.$lastNamePropertyLand.'%"');
           $sql->orWhere('p_telephone_no','like','"%'.$lastNamePropertyLand.'%"');
           $sql->orWhere('p_email_address','like','"%'.$lastNamePropertyLand.'%"');
           $sql->orWhereRaw('CONCAT_WS(" ", trim(rpo_first_name), trim(rpo_middle_name), trim(rpo_custom_last_name)) like "%' . $lastNamePropertyLand . '%"');
       
        $sql->whereExists(function ($query) {
               $query->select("rpo_code")
                  ->from('rpt_properties')
                  ->whereRaw('rpo_code = clients.id')
                  ->where('pk_is_active', 1);
        });
        $result=$sql->get()->toArray();
        return $result;
        
       
    }
    public function getClientCodeAddress($lastName,$firstName){
        // return DB::table('rpt_properties AS rp')
        //          ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
        //          ->select('c.id','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.rpo_address_street_name','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.p_mobile_no','c.p_telephone_no','c.p_email_address')->where('c.is_rpt',1)->where('c.is_active',1)
        //        ->where('c.rpo_custom_last_name','like','%'.$lastName.'%')
        //        ->orWhere('c.rpo_first_name','like','%'.$lastName.'%')
        //        ->orWhere('c.p_mobile_no','like','%'.$lastName.'%')
        //        ->orWhere('c.p_telephone_no','like','%'.$lastName.'%')
        //        ->orWhere('c.p_email_address','like','%'.$lastName.'%')
        //        ->orWhereRaw('CONCAT_WS(" ", trim(c.rpo_first_name), trim(c.rpo_middle_name), trim(c.rpo_custom_last_name)) like "%' . $lastName . '%"')->get()->toArray();
               return DB::table('clients')->select('id','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','rpo_address_street_name','rpo_address_house_lot_no','rpo_address_subdivision','p_mobile_no','p_telephone_no','p_email_address')->where('is_rpt',1)->where('is_active',1)
           ->where('rpo_custom_last_name','like','%'.$lastName.'%')
		   ->orWhere('full_name','like','%'.$lastName.'%')
           ->orWhere('rpo_first_name','like','%'.$lastName.'%')
           ->orWhere('p_mobile_no','like','%'.$lastName.'%')
           ->orWhere('p_telephone_no','like','%'.$lastName.'%')
           ->orWhere('p_email_address','like','%'.$lastName.'%')
           ->orWhereRaw('CONCAT_WS(" ", trim(rpo_first_name), trim(rpo_middle_name), trim(rpo_custom_last_name)) like "%' . $lastName . '%"')->get()->toArray();
    }

    public function getClientCodeAddressnolandHolding($lastName,$firstName){

        return RptPropertyOwner::where('is_active',1)
           ->where('full_name','like','%'.$lastName.'%')
           ->orWhere('p_mobile_no','like','%'.$lastName.'%')
           ->orWhere('p_telephone_no','like','%'.$lastName.'%')
           ->orWhere('p_email_address','like','%'.$lastName.'%')->where('is_rpt',1)->get();
    }
    public function getClientNolandHolding($id){
        return DB::table('rpt_property_certs AS rptcert')
               ->join('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->select('c.id AS clientsId','c.full_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.rpo_address_street_name','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.p_mobile_no','c.p_telephone_no','c.p_email_address')->where('c.is_rpt',1)->where('c.is_active',1)->where('rptcert.id','=',$id)->get()->toArray();
    }
    public function getClientsAppraisalsDetials($id){
        return DB::table('rpt_property_certs AS rptcert')
               ->leftJoin('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rptcertD.rp_code')
               ->leftJoin('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->leftJoin('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->leftJoin('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->leftJoin('rpt_property_kinds AS kind', 'kind.id', '=', 'rp.pk_id')
               ->select('rp.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no','rp_market_value_adjustment','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','kind.pk_code','rp.rpb_assessed_value','rp.rp_assessed_value','rp.rp_market_value','rptcertD.id AS rptcertDId','rp.pk_id','rp.rp_class','rp.rp_lot_cct_unit_desc',
                DB::raw("CASE WHEN rp.pk_id = 1 THEN (SELECT SUM(COALESCE(rpbfv_floor_area,0)) FROM rpt_building_floor_values WHERE rpt_building_floor_values.rp_code = rp.id) WHEN rp.pk_id = 2 THEN (SELECT SUM((CASE WHEN rpt_property_appraisals.lav_unit_measure = 1 THEN (rpt_property_appraisals.rpa_total_land_area/10000) ELSE rpt_property_appraisals.rpa_total_land_area END)) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rp.id) ELSE 0 END as area")
           )->where('rp.pk_is_active',1)
               ->where('rptcert.id','=',$id)
               ->groupBy('rptcertD.rp_code')
               ->get()
               ->toArray();
    }
    public function getClientsAppraisalsExit($id){
        return DB::table('rpt_property_certs AS rptcert')
            ->leftjoin('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
            ->leftjoin('rpt_properties AS rp', 'rp.id', '=', 'rptcertD.rp_code')
            ->select('rp.id','rptcertD.id AS rptcertDId')->where('rp.pk_is_active',1)->where('rptcert.id','=',$id)->get()->toArray();
    }
    public function checkAddAppraisalsExit($columns){
        return DB::table('rpt_property_cert_details')->select('id')->where('rpc_code',$columns['rpc_code'])->where('rp_code',$columns['rp_code'])->get()->toArray();
    }
    public function getClientsAppraisals($id){
      return DB::table('rpt_properties AS rp')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->leftjoin('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->leftjoin('rpt_property_kinds AS kind', 'kind.id', '=', 'rp.pk_id')
               ->leftjoin('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rp.id AS propertyId','rp.pk_id','rp.rp_class','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no','rp.rp_tax_declaration_no','rp.rp_lot_cct_unit_desc','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','kind.pk_code','rp.rpb_assessed_value','rp.rp_assessed_value','rp.rp_market_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get();

    }
    
    public function getClientsAppraisalsMech($id){
      
        return DB::table('rpt_property_machine_appraisals AS rpam')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpam.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rpam.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpam.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpam.pk_code','rpam.rpma_base_market_value','rpam.rpm_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->whereIn('rp.rpo_code',$id)->get()->toArray();

    }
    public function getClientsAppraisalsDetialsMech($id){
      
         return DB::table('rpt_property_certs AS rptcert')
               ->join('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->join('rpt_property_machine_appraisals AS rpam', 'rpam.id', '=', 'rptcertD.rpam_code')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpam.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rpam.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpam.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpam.pk_code','rpam.rpma_base_market_value','rpam.rpm_assessed_value','year.rvy_revision_year','rptcertD.id AS rptcertDId')->where('rp.pk_is_active',1)->where('rptcert.id',$id)->get()->toArray();

    }
    public function getClientsAppraisalsBuilding($id){
      
        return DB::table('rpt_building_floor_values AS rpab')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpab.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpab.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','pk.pk_code','rpab.rpbfv_floor_base_market_value','rpab.rpb_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->whereIn('rp.rpo_code',$id)->get()->toArray();

    }
     public function getClientsAppraisalsDetialsBuilding($id){
      
         return DB::table('rpt_property_certs AS rptcert')
               ->join('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->join('rpt_building_floor_values AS rpab', 'rpab.id', '=', 'rptcertD.rpab_code')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpab.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpab.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','pk.pk_code','rpab.rpbfv_floor_base_market_value','rpab.rpb_assessed_value','year.rvy_revision_year','rptcertD.id AS rptcertDId')->where('rp.pk_is_active',1)->where('rptcert.id',$id)->get()->toArray();

    }
    public function getClientsAppraisalsImprovement($id){
      
        return DB::table('rpt_property_appraisals AS rpa')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpa.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rpa.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpa.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpa.pk_code','rpa.rpa_base_market_value','rpa.rpa_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get()->toArray();

    }
    public function getClientsAppraisalsMechImprovement($id){
      
        return DB::table('rpt_property_machine_appraisals AS rpam')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpam.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rpam.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpam.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpam.pk_code','rpam.rpma_base_market_value','rpam.rpm_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get()->toArray();

    }
    public function getClientsAppraisalsBuildingImprovement($id){
      
        return DB::table('rpt_building_floor_values AS rpab')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpab.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpab.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','pk.pk_code','rpab.rpbfv_floor_base_market_value','rpab.rpb_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get()->toArray();
    }
    public function getClientsAppraisalsNoLand($id){
      
        return DB::table('rpt_properties AS rp')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->leftjoin('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->leftjoin('rpt_property_kinds AS kind', 'kind.id', '=', 'rp.pk_id')
               ->leftjoin('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rp.id AS propertyId','rp.pk_id','rp.rp_class','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no','rp.rp_tax_declaration_no','rp.rp_lot_cct_unit_desc','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','kind.pk_code','rp.rpb_assessed_value','rp.rp_assessed_value','rp.rp_market_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.pk_id',2)->where('rp.rpo_code',$id)->get();
    }
    public function getClientsAppraisalsMechNoLand($id){
      
        return DB::table('rpt_property_machine_appraisals AS rpam')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpam.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rpam.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpam.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpam.pk_code','rpam.rpma_base_market_value','rpam.rpm_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get()->toArray();

    }
    public function getClientsAppraisalsBuildingNoLand($id){
      
        return DB::table('rpt_building_floor_values AS rpab')
               ->join('rpt_properties AS rp', 'rp.id', '=', 'rpab.rp_code')
               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
               ->join('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'rp.rvy_revision_year_id')
               ->select('rpab.id','rp.id AS propertyId','b.brgy_name','rp.rp_total_land_area','rp.rp_cadastral_lot_no AS lotNo','rp.rp_tax_declaration_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','pk.pk_code','rpab.rpbfv_floor_base_market_value','rpab.rpb_assessed_value','year.rvy_revision_year')->where('rp.pk_is_active',1)->where('rp.rpo_code',$id)->get()->toArray();

    }
    
    public function getRevision(){
        // $getId=$request->input('rvy_revision_year_id');
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->get();
    }
    public function getRevisionDefault(){
        // $getId=$request->input('rvy_revision_year_id');
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_default_value',1)->get();
    }
    public function getRevisionall($id){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('rvy_revision_year',$id)->get();
    }
    public function CertPholdingPrint($id){
        return DB::table('rpt_property_certs AS rptcert')
               ->leftjoin('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->leftjoin('rpt_properties AS rp', 'rp.id', '=', 'rptcertD.rp_code')
               ->leftjoin('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->leftjoin('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->leftjoin('rpt_appraisers AS assessorAppraisers', 'assessorAppraisers.id', '=', 'rptcert.rpc_city_assessor_code')
               ->leftjoin('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraisers.ra_appraiser_id')
               ->leftjoin('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->leftjoin('rpt_appraisers AS certifiedbyAppraisers', 'certifiedbyAppraisers.id', '=', 'rptcert.rpc_certified_by_code')
               ->leftjoin('hr_employees AS certifiedby', 'certifiedby.id', '=', 'certifiedbyAppraisers.ra_appraiser_id')
               ->leftjoin('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
               ->leftjoin('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->leftjoin('rpt_property_classes AS class', 'class.id', '=', 'rp.pc_class_code')
               ->select('rptcert.id AS rptcertId','b.brgy_name','rp.rp_cadastral_lot_no','rp.rp_tax_declaration_no','rp.rp_total_land_area','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rp.pk_id','rp.rp_market_value','rp.rpb_assessed_value','rp.rp_assessed_value','rptcert.rpc_control_no','requestor.full_name As requestorfull','requestor.suffix AS requestor4','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_year','rptcert.rpc_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.title AS assessor5','assessor.suffix AS assessor4','assessor.firstname AS assessor1','assessor.middlename AS assessor2','assessor.lastname AS assessor3','assessorAppraisers.ra_appraiser_position AS assessordescription','certifiedby.title AS certifiedby5','certifiedby.suffix AS certifiedby4','certifiedby.firstname AS certifiedby1','certifiedby.middlename AS certifiedby2','certifiedby.lastname AS certifiedby3','c.id AS clientid','rptcert.rpc_certified_by_position AS certifiedbydescription')->where('rptcert.id','=',$id)->get();
    }
    
    public function CertPholdingPrintNoLanddata($id){
        return DB::table('rpt_property_certs AS rptcert')
               ->leftjoin('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->leftjoin('rpt_property_appraisals AS rpa', 'rpa.id', '=', 'rptcertD.rpa_code')
               ->leftjoin('rpt_properties AS rp', 'rp.id', '=', 'rptcertD.rp_code')
               ->leftjoin('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->leftjoin('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->leftjoin('rpt_appraisers AS assessorAppraisers', 'assessorAppraisers.id', '=', 'rptcert.rpc_city_assessor_code')
               ->leftjoin('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraisers.ra_appraiser_id')
               ->leftjoin('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->leftjoin('rpt_appraisers AS certifiedbyAppraisers', 'certifiedbyAppraisers.id', '=', 'rptcert.rpc_certified_by_code')
               ->leftjoin('hr_employees AS certifiedby', 'certifiedby.id', '=', 'certifiedbyAppraisers.ra_appraiser_id')
               ->leftjoin('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
               ->leftjoin('barangays AS b', 'b.id', '=', 'rp.brgy_code_id')
               ->leftjoin('rpt_property_classes AS class', 'class.id', '=', 'rpa.pc_class_code')
               ->select('rptcert.id AS rptcertId','b.brgy_name','rp.rp_cadastral_lot_no','rp.rp_tax_declaration_no','rp.rp_total_land_area','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','class.pc_class_code','rpa.pk_code','rpa.rpa_base_market_value','rpa.rpa_assessed_value','rptcert.rpc_control_no','requestor.full_name As requestorfull','requestor.suffix AS requestor4','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_year','rptcert.rpc_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.title AS assessor5','assessor.suffix AS assessor4','assessor.firstname AS assessor1','assessor.middlename AS assessor2','assessor.lastname AS assessor3','assessorAppraisers.ra_appraiser_position AS assessordescription','certifiedby.title AS certifiedby5','certifiedby.suffix AS certifiedby4','certifiedby.firstname AS certifiedby1','certifiedby.middlename AS certifiedby2','certifiedby.lastname AS certifiedby3','c.id AS clientid','rptcert.rpc_certified_by_position AS certifiedbydescription')->where('rptcert.id','=',$id)->get();
    }


    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $rpc_cert_type = 1;
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"rpc_control_no",
          1 =>"rpc_year",
          2 =>"rpc_control_no", 
          3 =>"c.full_name", 
          4 =>"requestor.full_name",  
          5 =>"assessor1", 
          6 =>"certifiedby1",
          7 =>"rpc_remarks",
          8 =>"rpc_or_no",
          9 =>"rpc_date"
        
         );
              $sql = DB::table('rpt_property_certs AS rptcert')
               ->join('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->join('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->join('rpt_appraisers AS assessorAppraiser', 'assessorAppraiser.id', '=', 'rptcert.rpc_city_assessor_code')
               ->join('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraiser.ra_appraiser_id')
               ->join('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->join('rpt_appraisers AS assessorCertifiedby', 'assessorCertifiedby.id', '=', 'rptcert.rpc_certified_by_code')
               ->join('hr_employees AS certifiedby', 'certifiedby.id', '=', 'assessorCertifiedby.ra_appraiser_id')
               ->join('hr_employees AS creatby', 'creatby.id', '=', 'rptcert.created_by')
               ->join('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
              ->select('rptcert.id','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','rptcert.rpc_control_no','requestor.full_name AS requestorfull','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.fullname AS assessor1','assessorhd.description AS assessordescription','certifiedby.fullname AS certifiedby1','certifiedbyhd.description AS certifiedbydescription','rptcert.rpc_remarks','rptcert.rpc_date','rptcert.rpc_year','creatby.fullname AS creatby1','rptcert.status','rptcert.rpc_cert_type')->where('rptcert.rpc_cert_type',$rpc_cert_type);

               // $sql->groupBy([
               //      'rptcert.id',
               //      'c.full_name',
               //      'c.rpo_address_house_lot_no',
               //      'c.rpo_address_street_name',
               //      'c.rpo_address_subdivision',
               //      'rptcert.rpc_control_no',
               //      'requestor.full_name',
               //      'requestor.rpo_first_name',
               //      'requestor.rpo_middle_name',
               //      'requestor.rpo_custom_last_name',
               //      'rptcert.rpc_city_assessor_code',
               //      'rptcert.rpc_certified_by_code',
               //      'rptcert.rpc_or_date',
               //      'rptcert.rpc_or_no',
               //      'rptcert.rpc_or_amount',
               //      'assessor.fullname',
               //      'assessorhd.description',
               //      'certifiedby.fullname',
               //      'certifiedbyhd.description',
               //      'rptcert.rpc_remarks',
               //      'rptcert.rpc_date',
               //      'rptcert.rpc_year',
               //      'creatby.fullname',
               //      'rptcert.status',
               //      'rptcert.rpc_cert_type',
               //  ]);
            
            if(!empty($q) && isset($q) || !empty($rpc_cert_type) && isset($rpc_cert_type) || !empty($year) && isset($year)){
                    $sql->where(function ($sql) use($year)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($year)."%");
                            
                            
                    });
                    $sql->where(function ($sql) use($q)  {
                        $sql->where(DB::raw('LOWER(rptcert.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_control_no)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_remarks)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(requestor.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_or_no)'),'like',"%".strtolower($q)."%");
                    });
                    $sql->where(function ($sql) use($rpc_cert_type)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_cert_type)'),'like',"%".strtolower($rpc_cert_type)."%");
                            
                    });
                    
                }
                if (isset($params['order'][0]['column'])) {
                    $sql->orderBy($columns[$params['order'][0]['column']], $params['order'][0]['dir']);
                } else {
                    $sql->orderBy('rptcert.id', 'desc');
                }

                /*  #######  Get count without limit  ###### */
                $data_cnt=$sql->count();
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data=$sql->get();
                return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function getListImprovement($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $rpc_cert_type = 3;
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"rpc_control_no",
          1 =>"rpc_year",
          2 =>"rpc_control_no", 
          3 =>"c.full_name", 
          4 =>"requestor.full_name",  
          5 =>"assessor1", 
          6 =>"certifiedby1",
          7 =>"rpc_remarks",
          8 =>"rpc_or_no",
          9 =>"rpc_date"
        
         );
              $sql = DB::table('rpt_property_certs AS rptcert')
               ->join('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->join('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->join('rpt_appraisers AS assessorAppraiser', 'assessorAppraiser.id', '=', 'rptcert.rpc_city_assessor_code')
               ->join('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraiser.ra_appraiser_id')
               ->join('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->join('rpt_appraisers AS assessorCertifiedby', 'assessorCertifiedby.id', '=', 'rptcert.rpc_certified_by_code')
               ->join('hr_employees AS certifiedby', 'certifiedby.id', '=', 'assessorCertifiedby.ra_appraiser_id')
               ->join('hr_employees AS creatby', 'creatby.id', '=', 'rptcert.created_by')
               ->join('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
              ->select('rptcert.id','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','rptcert.rpc_control_no','requestor.full_name AS requestorfull','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.fullname AS assessor1','assessorhd.description AS assessordescription','certifiedby.fullname AS certifiedby1','certifiedbyhd.description AS certifiedbydescription','rptcert.rpc_remarks','rptcert.rpc_date','rptcert.rpc_year','creatby.fullname AS creatby1','rptcert.status','rptcert.rpc_cert_type')->where('rptcert.rpc_cert_type',$rpc_cert_type);
                if(!empty($q) && isset($q) || !empty($rpc_cert_type) && isset($rpc_cert_type) || !empty($year) && isset($year)){
                    $sql->where(function ($sql) use($year)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($year)."%");
                            
                            
                    });
                    $sql->where(function ($sql) use($q)  {
                        $sql->where(DB::raw('LOWER(rptcert.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_control_no)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_remarks)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(requestor.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_or_no)'),'like',"%".strtolower($q)."%");
                    });
                    $sql->where(function ($sql) use($rpc_cert_type)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_cert_type)'),'like',"%".strtolower($rpc_cert_type)."%");
                            
                    });
                    
                }
                if (isset($params['order'][0]['column'])) {
                    $sql->orderBy($columns[$params['order'][0]['column']], $params['order'][0]['dir']);
                } else {
                    $sql->orderBy('rptcert.id', 'desc');
                }

                /*  #######  Get count without limit  ###### */
                $data_cnt=$sql->count();
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data=$sql->get();
                return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      
      public function getListNoHolding($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $rpc_cert_type = 2;
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"rpc_control_no",
          1 =>"rpc_year",
          2 =>"rpc_control_no", 
          3 =>"c.full_name", 
          4 =>"requestor.full_name",  
          5 =>"assessor1", 
          6 =>"certifiedby1",
          7 =>"rpc_remarks",
          8 =>"rpc_or_no",
          9 =>"rpc_date"
        
         );
              $sql = DB::table('rpt_property_certs AS rptcert')
               ->join('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               ->join('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->join('rpt_appraisers AS assessorAppraiser', 'assessorAppraiser.id', '=', 'rptcert.rpc_city_assessor_code')
               ->join('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraiser.ra_appraiser_id')
               ->join('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->join('rpt_appraisers AS assessorCertifiedby', 'assessorCertifiedby.id', '=', 'rptcert.rpc_certified_by_code')
               ->join('hr_employees AS certifiedby', 'certifiedby.id', '=', 'assessorCertifiedby.ra_appraiser_id')
               ->join('hr_employees AS creatby', 'creatby.id', '=', 'rptcert.created_by')
               ->join('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
              ->select('rptcert.id','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','rptcert.rpc_control_no','requestor.full_name AS requestorfull','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.fullname AS assessor1','assessorhd.description AS assessordescription','certifiedby.fullname AS certifiedby1','certifiedbyhd.description AS certifiedbydescription','rptcert.rpc_remarks','rptcert.rpc_date','rptcert.rpc_year','creatby.fullname AS creatby1','rptcert.status','rptcert.rpc_cert_type')->where('rptcert.rpc_cert_type',$rpc_cert_type);

               // $sql->groupBy([
               //      'rptcert.id',
               //      'c.full_name',
               //      'c.rpo_address_house_lot_no',
               //      'c.rpo_address_street_name',
               //      'c.rpo_address_subdivision',
               //      'rptcert.rpc_control_no',
               //      'requestor.full_name',
               //      'requestor.rpo_first_name',
               //      'requestor.rpo_middle_name',
               //      'requestor.rpo_custom_last_name',
               //      'rptcert.rpc_city_assessor_code',
               //      'rptcert.rpc_certified_by_code',
               //      'rptcert.rpc_or_date',
               //      'rptcert.rpc_or_no',
               //      'rptcert.rpc_or_amount',
               //      'assessor.fullname',
               //      'assessorhd.description',
               //      'certifiedby.fullname',
               //      'certifiedbyhd.description',
               //      'rptcert.rpc_remarks',
               //      'rptcert.rpc_date',
               //      'rptcert.rpc_year',
               //      'creatby.fullname',
               //      'rptcert.status',
               //      'rptcert.rpc_cert_type',
               //  ]);
            
            if(!empty($q) && isset($q) || !empty($rpc_cert_type) && isset($rpc_cert_type) || !empty($year) && isset($year)){
                    $sql->where(function ($sql) use($year)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($year)."%");
                            
                            
                    });
                    $sql->where(function ($sql) use($q)  {
                        $sql->where(DB::raw('LOWER(rptcert.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_control_no)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_remarks)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(requestor.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_or_no)'),'like',"%".strtolower($q)."%");
                    });
                    $sql->where(function ($sql) use($rpc_cert_type)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_cert_type)'),'like',"%".strtolower($rpc_cert_type)."%");
                            
                    });
                    
                }
                if (isset($params['order'][0]['column'])) {
                    $sql->orderBy($columns[$params['order'][0]['column']], $params['order'][0]['dir']);
                } else {
                    $sql->orderBy('rptcert.id', 'desc');
                }

                /*  #######  Get count without limit  ###### */
                $data_cnt=$sql->count();
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data=$sql->get();
                return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
     public function getReacord($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $rpc_cert_type = $request->input('rpc_cert_type');
        $allType = $request->input('allType');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"rpc_control_no",
          1 =>"rpc_year",
          2 =>"rpc_control_no", 
          3 =>"full_name", 
          4 =>"requestorfull",  
          5 =>"assessor1", 
          6 =>"rpc_remarks",
          7 =>"rpc_cert_type",
          8 =>"rpc_date"
        
         );
               $sql = DB::table('rpt_property_certs AS rptcert')
               ->join('clients AS c', 'c.id', '=', 'rptcert.rpc_owner_code')
               // ->leftjoin('rpt_property_cert_details AS rptcertD', 'rptcertD.rpc_code', '=', 'rptcert.id')
               ->join(DB::raw('(SELECT DISTINCT rpo_code,pk_id FROM rpt_properties) AS rp'), 'rp.rpo_code', '=', 'c.id')
               ->join('clients AS requestor', 'requestor.id', '=', 'rptcert.rpc_requestor_code')
               ->join('rpt_appraisers AS assessorAppraiser', 'assessorAppraiser.id', '=', 'rptcert.rpc_city_assessor_code')
               ->join('hr_employees AS assessor', 'assessor.id', '=', 'assessorAppraiser.ra_appraiser_id')
               ->join('hr_designations AS assessorhd', 'assessorhd.id', '=', 'assessor.hr_designation_id')
               ->join('rpt_appraisers AS assessorCertifiedby', 'assessorCertifiedby.id', '=', 'rptcert.rpc_certified_by_code')
               ->join('hr_employees AS certifiedby', 'certifiedby.id', '=', 'assessorCertifiedby.ra_appraiser_id')
               ->join('hr_employees AS creatby', 'creatby.id', '=', 'rptcert.created_by')
               ->join('hr_designations AS certifiedbyhd', 'certifiedbyhd.id', '=', 'certifiedby.hr_designation_id')
              ->select('rptcert.id','c.full_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','rptcert.rpc_control_no','requestor.full_name AS requestorfull','requestor.rpo_first_name AS requestor1','requestor.rpo_middle_name AS requestor2','requestor.rpo_custom_last_name AS requestor3','rptcert.rpc_city_assessor_code','rptcert.rpc_certified_by_code','rptcert.rpc_or_date','rptcert.rpc_or_no','rptcert.rpc_or_amount','assessor.fullname AS assessor1','assessorhd.description AS assessordescription','certifiedby.fullname AS certifiedby1','certifiedbyhd.description AS certifiedbydescription','rptcert.rpc_remarks','rptcert.rpc_date','rptcert.rpc_year','creatby.fullname AS creatby1','rptcert.status','rptcert.rpc_cert_type','rp.pk_id')->where('rptcert.rpc_cert_type',$rpc_cert_type)->where('rp.pk_id',$allType);
                if(!empty($q) && isset($q) || !empty($rpc_cert_type) && isset($rpc_cert_type) || !empty($year) && isset($year)){
                    $sql->where(function ($sql) use($year)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($year)."%");
                            
                            
                    });
                    $sql->where(function ($sql) use($q)  {
                        $sql->where(DB::raw('LOWER(rptcert.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_control_no)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_remarks)'),'like',"%".strtolower($q)."%")
							->orWhere(DB::raw('LOWER(requestor.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_year)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_date)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(rptcert.rpc_or_no)'),'like',"%".strtolower($q)."%");
                    });
                    $sql->where(function ($sql) use($rpc_cert_type)  {
                        $sql->where(DB::raw('LOWER(rptcert.rpc_cert_type)'),'like',"%".strtolower($rpc_cert_type)."%");
                            
                    });
                   
                   
                    
                    
                }
                if (isset($params['order'][0]['column'])) {
                    $sql->orderBy($columns[$params['order'][0]['column']], $params['order'][0]['dir']);
                } else {
                    $sql->orderBy('rptcert.id', 'desc');
                }

                /*  #######  Get count without limit  ###### */
                $data_cnt=$sql->count();
                /*  #######  Set Offset & Limit  ###### */
                $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data=$sql->get();
                return array("data_cnt"=>$data_cnt,"data"=>$data);
            
        /*  #######  Set Order By  ###### */
       
      }
}



