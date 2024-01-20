<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use App\Models\Barangay;
use App\Models\AuditLog;
use App\Models\CtoCashReciept;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use URL;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class CommonModelmaster extends Model
{   
    public function __construct() 
    {   
        date_default_timezone_set('Asia/Manila');
        $this->_Barangay = new Barangay();
    }

    public function updatestatusmaster($tablename,$ids,$wherecolumn,$columns){
		   return DB::table($tablename)->whereIn($wherecolumn,$ids)->update($columns);
	  }

	  public function addSystemActivityLog($postdata){
        DB::table('system_activity_log')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getConfiguration($title){
        return DB::table('configurations')->where('configuration_title',$title)->pluck('configuration_value')->first();
    }
    public function encryptData($data){
        $encrypted = Crypt::encryptString($data);
        return $encrypted;
    }
    public function decryptData($data){
        $decrypt = Crypt::decryptString($data); //Don't change here
        return $decrypt;
    }
    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }

    public function getGetOrusernamerange($id,$userid){
      return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_completed','0')->where('created_by',$userid)->first();
    }

    public function getGetOrrange($id,$userid){
      return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_completed','0')->where('created_by',$userid)->first();
    }

    public function checkOrinrange($or_no,$ortype_id){
        return DB::table('cto_payment_or_assignments')->select('id')->where('ortype_id',$ortype_id)->where('ora_is_completed',0)->where('ora_from','<=',$or_no)->where('ora_to','>=',$or_no)->orderby('id', 'ASC')->first();
    }

    public function Getorregisterid($id,$ornumber){
       return DB::table('cto_payment_or_assignments')->select('id as assignid','ora_from','cpor_id as id','ora_to','or_count')->where('ortype_id',$id)->where('ora_is_completed',0)->where('ora_from','<=',$ornumber)->where('ora_to','>=',$ornumber)->orderby('id', 'ASC')->first();
    }

    public function getUserDetails($email){
      return DB::table('users as u')->join('hr_employees as he','u.id','=','he.user_id')->join('acctg_departments as ad','ad.id','=','he.acctg_department_id')->select('u.name as username','u.id as userid','ad.id as deptid','ad.name as deptname')->where('u.email',$email)->first();
    }

     public function getUserDetailsbyid($id){
      return DB::table('users as u')->join('hr_employees as he','u.id','=','he.user_id')->join('acctg_departments as ad','ad.id','=','he.acctg_department_id')->select('u.name as username','u.id as userid','ad.id as deptid','ad.name as deptname')->where('u.id',$id)->first();
    }

    public function addPaymentHistory($postdata){
        DB::table('payment_history')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }

    public function addPaymentHistoryremote($postdata){
        $remortServer = DB::connection('remort_server');
        $remortServer->table('payment_history')->insert($postdata);
    }
    
    public function checktrnsactionexist($transid){
       return DB::table('payment_history')->select('id')->where('transaction_no',$transid)->get();
    }
    public function updateLog($postdata)
    {
        /**
         * OLD AUDIT LOG
         * 
         * $postdata['created_by'] = \Auth::user()->id;
         * $postdata['created_at'] = date('Y-m-d H:i:s');
         * $postdata['updated_at'] = date('Y-m-d H:i:s');
         * DB::table('system_activity_log')->insert($postdata);
         * return DB::getPdo()->lastInsertId();
         * 
         */   
        $log_content="";  
        $public_ip = $_SERVER['REMOTE_ADDR'];  
        $platform = \Agent::platform();

        if(isset($postdata['log_content'])){
            $log_content = $postdata['log_content']." to [".$platform."] device at ".date('m/d/Y h:i a')." IP Address : ".$public_ip; ;
        }
        $fullname = ""; $email =""; $deparid =""; $deptname=""; 
        if(Auth::user()->id > 0 ){
             $logdata = $this->getUserDetailsbyid(Auth::user()->id);

        }
        if(isset($postdata['module_id'])){
            $res = AuditLog::create([
                'user_id' =>Auth::user()->id,
                'logs' => $log_content,
                'full_name' =>Auth::user()->name,
                'email_address' =>Auth::user()->email,
                'dept_id' =>$logdata->deptid,
                'dept_name' =>$logdata->deptname,
                'ip_address' => $public_ip,
                'log_type' => 2,
                'entity' => '', //'gso_purchase_types', 
                'entity_id' => $postdata['module_id'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->id
            ]);  
            return $res;
        } 
    }
    
    public function updateLoglogin($postdata)
    {
        $log_content=""; 
        if(isset($postdata['log_content'])){
            $log_content = $postdata['log_content'];
        }
        if(isset($postdata['module_id'])){
            $res = AuditLog::create([
                'user_id' =>$postdata['user_id'],
                'logs' => $log_content,
                'full_name' =>$postdata['full_name'],
                'email_address' =>$postdata['email_address'] ,
                'dept_id' =>$postdata['dept_id'] ,
                'dept_name' =>$postdata['dept_name'] ,
                'ip_address' => $postdata['ip_address'],
                'log_type' => 1,
                'entity' => '',
                'attempt' => $postdata['attempt'],   //'gso_purchase_types', 
                'entity_id' => $postdata['module_id'],
                'created_at' => Carbon::now(),
                'created_by' =>$postdata['created_by'] 
            ]);  
            return $res;
        } 
    }

    public function getappdatataxpayer($id){
        return DB::table('clients')->select('p_mobile_no','full_name')->where('id',$id)->first();
    }

    public function bploLocalityDetails(){
         return DB::table('rpt_locality')->where('department',2)->orderBy('id','DESC')->first();
    }
    public function checkMuncByBrgy($id){
        $rpt_locality= DB::table('rpt_locality')->where('department',2)->orderBy('id','DESC')->first();
        $barangays= DB::table('barangays')->where('id',$id)->first();
        if($rpt_locality->mun_no == $barangays->mun_no)
        {
            return 1;
        }else{
            return 0;
        }
   }
    public function getTaxPayerAddress($user_id=0){
		
        $fullAddress ="";
        $arr =  DB::table('clients')->select('p_barangay_id_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision')->where('id',$user_id)->first();
        if(isset($arr)){
            //$address =$arr->rpo_address_house_lot_no.', '.$arr->rpo_address_street_name.', '.$arr->rpo_address_subdivision;
			$address =(!empty($arr->rpo_address_house_lot_no) ? $arr->rpo_address_house_lot_no. ',' : '') . (!empty($arr->rpo_address_street_name) ? $arr->rpo_address_street_name. ',' : '') . (!empty($arr->rpo_address_subdivision) ? $arr->rpo_address_subdivision . ',' : '');
            $barngayAddress = $this->_Barangay->findDetails($arr->p_barangay_id_no);
            $fullAddress = $address.', '.$barngayAddress;
            $fullAddress = preg_replace('/,+/', ', ', $fullAddress);
            $fullAddress = trim($fullAddress,", ");
            $fullAddress = str_replace(', ', ', ', $fullAddress);
        }
        return $fullAddress;
    }
    public function updateTaxPayerFullAddress($client_id=0){
        if($client_id>0){
            // $address['full_address']=$this->getTaxPayerAddress($client_id);
            // DB::table('clients')->where('id',$client_id)->update($address);
        }
    }

    public function getcountryname($id){
        $arr =  DB::table('countries')->select('nationality')->where('id',$id)->first();
        $country ="";
        if(isset($arr)){
            $country =$arr->nationality;
        }
        return $country;
    }
    public function getCitizenAddress($user_id=0){
        $fullAddress ="";
        $arr =  DB::table('citizens')->select('brgy_id','cit_house_lot_no','cit_street_name','cit_subdivision')->where('id',$user_id)->first();
        if(isset($arr)){
            //$address =$arr->cit_house_lot_no.', '.$arr->cit_street_name.', '.$arr->cit_subdivision;
            $address =(!empty($arr->cit_house_lot_no) ? $arr->cit_house_lot_no. ',' : '') . (!empty($arr->cit_street_name) ? $arr->cit_street_name. ',' : '') . (!empty($arr->cit_subdivision) ? $arr->cit_subdivision . '' : '');

            $barngayAddress = $this->_Barangay->findDetails($arr->brgy_id);
            $fullAddress = $address.', '.$barngayAddress;
            $fullAddress = preg_replace('/,+/', ', ', $fullAddress);
            $fullAddress = trim($fullAddress,", ");
            $fullAddress = str_replace(', ', ', ', $fullAddress);
        }
        return $fullAddress;
    }
	public function getbussinesAddress($busn_id=0){
        $fullAddress ="";
        $arr =  DB::table('bplo_business')->select('busn_office_main_barangay_id','busn_office_main_add_block_no','busn_office_main_add_lot_no','busn_office_main_building_no','busn_office_main_add_street_name','busn_office_main_add_subdivision')->where('id',$busn_id)->first();
        if(isset($arr)){
            $address =(!empty($arr->busn_office_main_add_block_no) ? $arr->busn_office_main_add_block_no. ',' : '') . (!empty($arr->busn_office_main_add_lot_no) ? $arr->busn_office_main_add_lot_no. ',' : '') . (!empty($arr->busn_office_main_add_street_name) ? $arr->busn_office_main_add_street_name. ',' : '') . (!empty($arr->busn_office_main_add_subdivision) ? $arr->busn_office_main_add_subdivision . '' : '');
            $barngayAddress = $this->_Barangay->findDetails($arr->busn_office_main_barangay_id);
            $fullAddress = $address.','.$barngayAddress;
            $fullAddress = preg_replace('/,+/', ', ', $fullAddress);
            $fullAddress = trim($fullAddress,", ");
            $fullAddress = str_replace(', ', ', ', $fullAddress);
        }
        return $fullAddress;
    }
    public function getbussinesAddressBarangay($busn_id=0){
        $fullAddress ="";
        $arr =  DB::table('bplo_business')->select('busn_office_main_barangay_id')->where('busn_office_main_barangay_id',$busn_id)->first();
        if(isset($arr)){
            $address="";
            $barngayAddress = $this->_Barangay->findDetails($arr->busn_office_main_barangay_id);
            $fullAddress = $address.','.$barngayAddress;
            $fullAddress = preg_replace('/,+/', ', ', $fullAddress);
            $fullAddress = trim($fullAddress,", ");
            $fullAddress = str_replace(', ', ', ', $fullAddress);
        }
        return $fullAddress;
    }
    
    public function getCreatedByName($id=0){
        return DB::table('users')->where('id',(int)$id)->pluck('name')->first();
    }
    public function getUserName($first,$middle,$last,$suffix){
        $fullname =$first.' '.$middle.' '.$last.', '.$suffix;
        $fullname = trim($fullname);
        $fullname = trim($fullname,",");
        return $fullname;
    }
    public function getCitizenName($user_id){
        return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_full_address','brgy_id as p_barangay_id_no')->where('id',$user_id)->first();
    }

    public function getClientName($user_id){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix','p_barangay_id_no')->where('id',$user_id)->first();
    }

    public function gettopnumber($id){
             return DB::table('cto_top_transactions')->select('id')->where('transaction_no',$id)->first();
    }

    public function getTopnumberarray($id){
        return DB::table('cto_top_transactions')->select('id','transaction_no')->where('id',$id)->get();
    }

    public function calculateTotalYearMonth($start,$end){
        $datetime1 = new \DateTime($start);
        $datetime2 = new \DateTime($end);
        $difference = $datetime1->diff($datetime2);
        $output="";
        if($difference->y>0){
            $output .=$difference->y.'-Year/';
        }
        if($difference->m>0){
            $output .=$difference->m.'-months/';
        }
        if($difference->d>0){
            $output .=$difference->d.'-days';
        }
        $output = trim($output,'/');
        if(empty($output)){
            $output ='0-days';
        }
        return $output;

    }
    public function numberToWord($num = '')
    {
        $arramount = explode(".", number_format($num,2));
		
        $num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) ){
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
            foreach( $num_levels as $num_part ){
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } 
                $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , '' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                //$words  = str_replace( ',' , ' and' , $words );

            }
            $pointamount ="";
             if(count($arramount) > 1){
                if($arramount[1] > 0){
                  $pointamount = $arramount[1]."/100";
                }
             }
            if($pointamount == ''){
                return $words." Pesos" ;
             }else{
                return $words." and ".$pointamount.' '.'Pesos';
             }
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }
    public function getAllBusinessList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('bplo_business')
        ->select('id','busn_name','busns_id_no')->where('busns_id_no','!=',NULL);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('busn_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getActiveBusinessList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('bplo_business')
        ->select('id','busn_name','busns_id_no')->where('is_active',1)->where('busns_id_no','!=',NULL);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('busn_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBarangay($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('barangays AS bgf')
        ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
        ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
        ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','bgf.is_active')->where('bgf.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('bgf.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('brgy_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBarangaybyid($search){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('barangays AS bgf')
        ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
        ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
        ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','bgf.is_active')->where('bgf.is_active',1);
        $sql->where(function ($sql) use($search) {
            $sql->Where('bgf.id',$search);
        });
    
      $sql->orderBy('brgy_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBrgyMunicipalOnly(){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;
      $municipality = strtolower(config('constants.defaultCityCode.city'));
      $sql = DB::table('barangays AS bgf')
        ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
        ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
        ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','bgf.is_active')->where('bgf.is_active',1)->where('mun_desc','LIKE','%'.$municipality.'%');
      
      $sql->orderBy('brgy_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getTaxDecration($search=""){
      $search = trim(strtolower($search));
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('rpt_properties AS rpt')
                ->join('clients AS c', 'c.id', '=', 'rpt.rpo_code')
                ->select('rpt.id','c.full_name','rpt.rp_tax_declaration_no')->where('rpt.pk_is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          $sql->where(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($search)."%")
                ->orWhere(DB::raw('LOWER(rpt.rp_tax_declaration_no)'),'like',"%".strtolower($search)."%");
          
        });
      }
      $sql->orderBy('c.full_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBploRpt($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('rpt_properties')
             ->where('pk_id',1)
             ->where('pk_is_active',1)
             ->where('is_deleted',0)
             ->select('rp_tax_declaration_no','id');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(rp_tax_declaration_no)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('rp_tax_declaration_no','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBploRptVar($search=""){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
  
        $sql = DB::table('rpt_properties')
               ->where('pk_id',1)
               ->where('pk_is_active',1)
               ->where('is_deleted',0)
               ->select('rp_tax_declaration_no','id');
        if(!empty($search)){
          $sql->where(function ($sql) use($search) {
              $sql->where(DB::raw('LOWER(rp_tax_declaration_no)'),'like',"%".strtolower($search)."%");
          });
        }
        $sql->orderBy('rp_tax_declaration_no','DESC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
  
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
    

    public function allRptProperty($vars = ''){
      $rpt_properties = self::where('pk_id',1)->where('pk_is_active',1)->where('is_deleted',0)->orderBy('id')->get();
      $items = array();
      if (!empty($vars)) {
          $items[] = array('' => 'select a '.$vars);
      } else {
          $items[] = array('' => 'Please select...');
      }
      foreach ($rpt_properties as $rpt_property) {
          $items[] = array(
              $rpt_property->id => $rpt_property->rp_tax_declaration_no
          );
      }

      $data = array();
      foreach($items as $item) {
          foreach($item as $key => $val) {
              $data[$key] = $val;
          }
      }

      return $data;
 }

    public function getBploTaxpayersAutoSearchList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('clients')
        ->select('id','full_name')->where('is_active',1)->where('is_bplo',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(full_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_custom_last_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_first_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_middle_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('full_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

     public function getAllTaxpayersAutoSearchList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('clients')
        ->select('id','full_name')->where('is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(full_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_custom_last_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_first_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(rpo_middle_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('full_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

     public function getAllCitizenAutoSearchList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('citizens')
        ->select('id','cit_fullname as full_name')->where('cit_is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(cit_last_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(cit_first_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(cit_middle_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('cit_fullname','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBarngayLisByRpt($search="",$mun_id){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
  
        $sql = DB::table('barangays AS bgf')
          ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
          ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
          ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
          ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','bgf.is_active')
          ->where('bgf.mun_no',$mun_id)
          ->where('bgf.is_active',1);
        if(!empty($search)){
          $sql->where(function ($sql) use($search) {
            if(is_numeric($search)){
              $sql->Where('bgf.id',$search);
            }else{
              $sql->where(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($search)."%");
            }
          });
        }
        $sql->orderBy('brgy_name','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
  
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

    

    public function getPsicSubclass($search=""){
        $page=1;
        if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
  
        $sql = DB::table('psic_subclasses AS psc')
               ->select('psc.*')
               ->where('psc.is_active',1);
        if(!empty($search)){
          $sql->where(function ($sql) use($search) {
            if(is_numeric($search)){
              $sql->Where('psc.id',$search);
            }else{
              $sql->where(DB::raw('LOWER(psc.subclass_code)'),'like',"%".strtolower($search)."%")
              ->orWhere(DB::raw('LOWER(psc.subclass_description)'),'like',"%".strtolower($search)."%");
            }
          });
        }
        $sql->orderBy('psc.subclass_code','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
  
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
    
    // for Print receipt
    public function bank($id){//$this->bank(bank_id)
        return DB::table('cto_payment_banks')->select('id','bank_code')->where('id',$id)->first();
    }
    // data need
    //   $data = [
    //     'transacion_no' => [int],
    //     'or_number => [varchar],
    //     'date' => [date],
    //     'payor' => [string],
    //     'transactions' => (object)[array],
    //     'surcharge' => [decimal],
    //     'interest' => [decimal],
    //     'total' => [decimal],
    //     'payment_terms' => [int(1,2,3)],
    //     'cash_details' => (object)[array]
        
    // ];
    public function printReceipt($data)
    {
       /* echo "<pre>";
        print_r($data);exit;*/
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'A5');
        PDF::SetFont('Helvetica', '', 10);

        $border = 0;
        $topPos = 42;
        $rightPos = 12;
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::writeHTMLCell(50, 0, $rightPos + 60,$topPos -2, $data['or_number'], $border);//Or number

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(50, 0, $rightPos + 45,6 + $topPos,Carbon::parse($data['date'])->toFormattedDateString(), $border);//Date

        PDF::writeHTMLCell(50, 0, $rightPos + 20,15  + $topPos,config('constants.defaultCityCode.city'), $border);//agency

        PDF::writeHTMLCell(50, 0, $rightPos + 20,22 + $topPos,$data['payor'], $border);//Payor
        
        $htmldynahistory='<table border="'.$border.'">
                            <tr>
                                <td width="158"></td>
                                <td width="40"></td>
                                <td width="83px"></td>
                            </tr>
        ';
        foreach ($data['transactions'] as $key => $value) {
            if ($value->tax_amount != 0) {
                $htmldynahistory .='<tr>
                        <td style="text-align:left;">
                        '.$value->fees_description.'
                        </td>
                        <td></td>
                        <td style="text-align:left;">'.number_format($value->tax_amount,2).'</td>
                    </tr>';
            }
        }
        if (isset($data['surcharge']) && $data['surcharge']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Surcharge Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['surcharge'],2).'</td>
            </tr>
            ';
        }
        if (isset($data['interest']) && $data['interest']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Interest Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['interest'],2).'</td>
            </tr>
            ';
        }
        $htmldynahistory .='</table>';
        PDF::writeHTMLCell(90, 0, $rightPos + 6,35 + $topPos,$htmldynahistory, $border); //collection table

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(35, 0, $rightPos + 75,83 + $topPos,number_format($data['total'],2), $border); //total
	
        $amountinworld =  $this->numberToWord($data['total']); 
        PDF::writeHTMLCell(60, 0, $rightPos + 33,90 + $topPos,$amountinworld, $border);//amount in words

        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, $rightPos + 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, $rightPos + 8,103 + $topPos,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, $rightPos + 8,109 + $topPos,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, $rightPos + 8,115 + $topPos,$order, $border);// check money order
        
        $htmldynahistory='<table border="'.$border.'" style="text-align:center">';
        if (isset($data['cash_details'])) {
            foreach ($data['cash_details'] as $key => $value) {
                // dd($value);
                    $htmldynahistory .='<tr>
                            <td>'.$this->bank($value->bank_id)->bank_code.'</td>
                            <td>'.$value->opayment_check_no.'</td>
                            <td>'.Carbon::parse($value->opayment_date)->format('m/d/y').'</td>
                        </tr>';
            }
        }
        
        $htmldynahistory .='</table>';
        PDF::writeHTMLCell(65, 0, $rightPos + 31,120 + $topPos,$htmldynahistory, $border);// bank
        PDF::writeHTMLCell(67, 0, $rightPos + 29,131 + $topPos,Auth::user()->hr_employee->fullname, $border,0,0,true,'C'); //collecting officer

        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['transacion_no'].'.pdf';
        //echo $filename;exit;
        if(isset($data['varName'])){
            $arrSign= $this->isSignApply($data['varName']);
            $isSignVeified = isset($arrSign)?$arrSign->status:0;
            
            $signType = $this->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature = $this->getuserSignature(Auth::user()->id);
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                    if(!empty($signature) && File::exists($path)){
                        // Apply Digital Signature
                        PDF::Output($folder.$filename,'F');
                        $arrData['signaturePath'] = $signature;
                        $arrData['filename'] = $filename;
                        return $this->applyDigitalSignature($arrData);
                    }
                }
                if($isSignVeified==1 && $signType==1){
                    // Apply E-Signature
                    if(!empty($signature) && File::exists($path)){
                        PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                    }
                }
            }
        }
        PDF::Output($folder.$filename);
    }
     public function printReceiptoccu($data,$ortypeid){
        $paymnet_or_setups = $this->getPaymentOrSetup($ortypeid);
        if(!$paymnet_or_setups){
            return "OR SETUP Not Found...";
        }
        $setup_details = json_decode($paymnet_or_setups->setup_details);
        $setup_details = (array)$setup_details;
        $border = 0;
        $pdfwidth = $paymnet_or_setups->width != null ? $paymnet_or_setups->width : 0;
        $pdfheight = $paymnet_or_setups->height != null ? $paymnet_or_setups->height : 0;
        $orientation = "L";
        if($paymnet_or_setups->is_portrait == 1){
            $orientation = "P";
        }
        $resolution  = array($pdfheight,$pdfwidth);
        $width = 0; $height = 0;
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        //PDF::SetMargins(20, 30, 20);  
        PDF::SetMargins(20, 10, 3,10);  
        PDF::SetAutoPageBreak(FALSE, 0);
        // PDF::AddPage($orientation, 'cm', array(10, 20), true, 'UTF-8', false);
        PDF::AddPage($orientation, $resolution);
        PDF::SetFont('Helvetica', '', 10);

        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        $ln=0; $fill=0; $reset=""; $align='L';

        // Positioning For Or No
        $or_is_bold = "";
        $or_is_visible = 0;
        if(isset($setup_details['af51c_or_no'])){
            if($setup_details['af51c_or_no']){
                $or_top = $setup_details['af51c_or_no']->af51c_or_no_position_top;
                $or_left = $setup_details['af51c_or_no']->af51c_or_no_position_left;
                $or_font = $setup_details['af51c_or_no']->af51c_or_no_font_size;
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_font_is_bold)){
                    $or_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_visible)){
                    $or_is_visible = 1;
                };
            }
            if($or_is_visible == 1){
                PDF::SetFont('Helvetica', $or_is_bold, $or_font);
                PDF::writeHTMLCell($width, $height, $or_left , $or_top, $data['or_number'], $border,$ln,$fill,$reset,$align);    
            }
        }
        
        // Positioning For Or Date
        $or_date_is_bold = "";
        $or_date_is_visible = 0; $border =0; $align='L';
        if(isset($setup_details['af51c_or_date'])){
            if($setup_details['af51c_or_date']){
                $or_date_top = $setup_details['af51c_or_date']->af51c_or_date_position_top;
                $or_date_left = $setup_details['af51c_or_date']->af51c_or_date_position_left;
                $or_date_font = $setup_details['af51c_or_date']->af51c_or_date_font_size;
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_font_is_bold)){
                    $or_date_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_visible)){
                    $or_date_is_visible = 1;
                };
            }
            if($or_date_is_visible == 1){
                PDF::SetFont('Helvetica', $or_date_is_bold, $or_date_font);
                PDF::writeHTMLCell($width, $height, $or_date_left , $or_date_top, Carbon::parse($data['date'])->toFormattedDateString(), $border,$ln,$fill,$reset,$align);    
            }
        }
        $or_agency_is_visible = 0; $border =0; $align='L';
        $or_agency_is_bold = "";
        if(isset($setup_details['af51c_agency'])){
            if($setup_details['af51c_agency']){
                $or_agency_top = $setup_details['af51c_agency']->af51c_agency_position_top;
                $or_agency_left = $setup_details['af51c_agency']->af51c_agency_position_left;
                $or_agency_font = $setup_details['af51c_agency']->af51c_agency_font_size;
                if(isset($setup_details['af51c_agency']->af51c_agency_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_font_is_bold)){
                    $or_agency_is_bold = "B";
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_is_visible)){
                    $or_agency_is_visible = 1;
                };
            }
            if($or_agency_is_visible == 1){
                PDF::SetFont('Helvetica', $or_agency_is_bold, $or_agency_font);
                PDF::writeHTMLCell($width, $height, $or_agency_left , $or_agency_top,config('constants.defaultCityCode.city'), $border,$ln,$fill,$reset,$align);    
            }
        }
        // Positioning For Constant City
       //PDF::writeHTMLCell(50, 0, 20,52,config('constants.defaultCityCode.city'), $border);//agency

        // Positioning For Payor
        $payor_is_bold = ""; $border =0; $align='L';
        $payor_is_visible = 0;
        if(isset($setup_details['af51c_payor'])){
            if($setup_details['af51c_payor']){
                $payor_top = $setup_details['af51c_payor']->af51c_payor_position_top;
                $payor_left = $setup_details['af51c_payor']->af51c_payor_position_left;
                $payor_font = $setup_details['af51c_payor']->af51c_payor_font_size;
                if(isset($setup_details['af51c_payor']->af51c_payor_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_font_is_bold)){
                    $payor_is_bold = "B";
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_is_visible)){
                    $payor_is_visible = 1;
                };
            }
            if($payor_is_visible == 1){
                PDF::SetFont('Helvetica', $payor_is_bold, $payor_font);
                PDF::writeHTMLCell($width, $height, $payor_left, $payor_top, $data['payor'], $border,$ln,$fill,$reset,$align);    
            }
        }
        $aligntext ="left";  $alignamt ="left"; 
         if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_right_justify)){
                    $aligntext='right';
                };
         if(isset($setup_details['af51c_bfees_amount']->af51c_bfees_amount_right_justify)){
                    $alignamt='right';
        };
       
        //echo $htmldynahistory; exit;
        // Positioning For transactions, tax_amount
        $bfees_nature_col_is_bold = ""; $border =0; $align='L';
        $bfees_nature_col_is_visible = 0;
        if(isset($setup_details['af51c_bfees_nature_col'])){
            if($setup_details['af51c_bfees_nature_col']){
                $bfees_nature_col_top = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_top;
                $bfees_nature_col_left = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_left;
                $bfees_nature_col_font = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_size;
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_show_border)){
                    $border = 1;
                };

                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_is_bold)){
                    $bfees_nature_col_is_bold = "B";
                };
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_visible)){
                    $bfees_nature_col_is_visible = 1;
                };
            }
            if($bfees_nature_col_is_visible == 1){
                foreach ($data['transactions'] as $key => $value) { 
                        $htmldynahistory='<table border="'.$border.'">';
                        if ($value->tax_amount != 0) {
                            $htmldynahistory .='<tr>
                                    <td width="70%" style="text-align:'.$aligntext.';font-size:10px;">
                                    '.$value->fees_description.'
                                    </td>
                                    <td width="30%" style="text-align:'.$alignamt.';font-size:10px;">'.number_format($value->tax_amount,2).'</td>
                                </tr>';
                       
                        $htmldynahistory .='</table>';
                         PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                         PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);  
                         $bfees_nature_col_top = $bfees_nature_col_top + 4;  
                       }
                    }
                  if (isset($data['surcharge']) && $data['surcharge']) {
                      $htmldynahistory ='<table border="'.$border.'">
                      <tr>
                          <td style="text-align:'.$aligntext.';">
                          Surcharge Fee
                          </td>
                          <td></td>
                          <td style="text-align:'.$alignamt.';">'.number_format($data['surcharge'],2).'</td>
                      </tr></table>';
                     PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                     PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);  
                  }   
                   if (isset($data['interest']) && $data['interest']) {
                    $bfees_nature_col_top = $bfees_nature_col_top + 4;
                      $htmldynahistory ='<table border="'.$border.'">
                      <tr>
                          <td style="text-align:'.$aligntext.';">
                          Interest Fee
                          </td>
                          <td></td>
                          <td style="text-align:'.$alignamt.';">'.number_format($data['interest'],2).'</td>
                      </tr></table>';
                       PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                       PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);  
                  }
                // PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                // PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);    
            }
        }
        
        // Positioning For Total
        $total_is_bold = ""; $border =0; $align='L';
        $total_is_visible = 0;
        if(isset($setup_details['af51c_total'])){
            if($setup_details['af51c_total']){
                $total_top = $setup_details['af51c_total']->af51c_total_position_top;
                $total_left = $setup_details['af51c_total']->af51c_total_position_left;
                $total_font = $setup_details['af51c_total']->af51c_total_font_size;
                if(isset($setup_details['af51c_total']->af51c_total_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_total']->af51c_total_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_total']->af51c_total_font_is_bold)){
                    $total_is_bold = "B";
                };
                if(isset($setup_details['af51c_total']->af51c_total_is_visible)){
                    $total_is_visible = 1;
                };
            }
            if($total_is_visible == 1){
                PDF::SetFont('Helvetica', $total_is_bold, $total_font);
                PDF::writeHTMLCell($width, $height, $total_left , $total_top, number_format($data['total'],2), $border,$ln,$fill,$reset,$align);
            }
        }
        
        
        // Positioning for Amount In Words
        $amount_words_is_bold = ""; $border =0; $align='L';
        $amount_words_is_visible = 0;
        if(isset($setup_details['af51c_amount_words'])){
            if($setup_details['af51c_amount_words']){
                $amount_words_top = $setup_details['af51c_amount_words']->af51c_amount_words_position_top;
                $amount_words_left = $setup_details['af51c_amount_words']->af51c_amount_words_position_left;
                $amount_words_font = $setup_details['af51c_amount_words']->af51c_amount_words_font_size;
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_font_is_bold)){
                    $amount_words_is_bold = "B";
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_visible)){
                    $amount_words_is_visible = 1;
                };
            }
            $alignwordtext ="left"; 
            if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };

            if($amount_words_is_visible == 1){
                $amountinworld =  $this->numberToWord($data['total']);  
                // PDF::writeHTMLCell(55, 0, 33,129,$amountinworld, $border);//amount in words
                PDF::SetFont('Helvetica', $amount_words_is_bold, $amount_words_font);
                PDF::writeHTMLCell($width, $height, $amount_words_left , $amount_words_top, $amountinworld, $border,$ln,$fill,$reset,$align);
            }
        }
        
        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, 9,142,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, 9,148,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, 9,154,$order, $border);// check money order
        
        // Positioning for Amount In Words
        $htmldynahistory='<table border="'.$border.'" style="text-align:center">';
        foreach ($data['cash_details'] as $key => $value) {
            // dd($value);
                $htmldynahistory .='<tr>
                        <td>'.$this->bank($value->bank_id)->bank_code.'</td>
                        <td>'.$value->opayment_check_no.'</td>
                        <td>'.Carbon::parse($value->opayment_date)->format('m/d/y').'</td>
                    </tr>';
        }
        $htmldynahistory .='</table>';

        
        $cashier_details_is_bold = ""; $border =0; $align='L';
        $cashier_details_is_visible = 0;
        if(isset($setup_details['af51c_cashier_details'])){
            if($setup_details['af51c_cashier_details']){
                $cashier_details_top = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_top;
                $cashier_details_left = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_left;
                $cashier_details_font = $setup_details['af51c_cashier_details']->af51c_cashier_details_font_size;
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_font_is_bold)){
                    $cashier_details_is_bold = "B";
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_visible)){
                    $cashier_details_is_visible = 1;
                };
            }
            if($cashier_details_is_visible == 1){
                PDF::SetFont('Helvetica', $cashier_details_is_bold, $cashier_details_font);
                PDF::writeHTMLCell($width, $height, $cashier_details_left , $cashier_details_top, $htmldynahistory, $border,$ln,$fill,$reset,$align);
            }
        }
        // Positioning For Collecting Officer
        $collecting_officer_is_bold = ""; $border =0; $align='L';
        $collecting_officer_is_visible = 0;
        if(isset($setup_details['af51c_collecting_officer'])){
            if($setup_details['af51c_collecting_officer']){
                $collecting_officer_top = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_top;
                
                $collecting_officer_left = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_left;
                $collecting_officer_font = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_size;
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_is_bold)){
                    $collecting_officer_is_bold = "B";
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_visible)){
                    $collecting_officer_is_visible = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_right_justify)){
                    $align='R';
                };
            }
            if($collecting_officer_is_visible == 1){
                PDF::SetFont('Helvetica', $collecting_officer_is_bold, $collecting_officer_font);
                PDF::writeHTMLCell($width, $height, $collecting_officer_left , $collecting_officer_top, $data['cashiername'],$border,$ln,$fill,$reset,$align);
            }
        }
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['transacion_no'].'.pdf';
        //PDF::Output($filename,'I'); exit;
         if(isset($data['varName'])){
            $arrSign= $this->isSignApply($data['varName']);
            $isSignVeified = isset($arrSign)?$arrSign->status:0;
            $signType = $this->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature = $this->getuserSignature($data['cashierid']);
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                    if(!empty($signature) && File::exists($path)){
                        // Apply Digital Signature
                        PDF::Output($folder.$filename,'F');
                        $arrData['signaturePath'] = $signature;
                        $arrData['filename'] = $filename;
                        return $this->applyDigitalSignature($arrData);
                    }
                }
                if($isSignVeified==1 && $signType==1){
                    // Apply E-Signature
                    if(!empty($signature) && File::exists($path)){
                        PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                    }
                }
            }
        }
        PDF::Output($folder.$filename);
    }
    public function applyDigitalSignature($arrData){
        $arrData['isMultipleSign'] = isset($arrData['isMultipleSign'])?$arrData['isMultipleSign']:0;
        $arrData['isSavePdf'] = isset($arrData['isSavePdf'])?$arrData['isSavePdf']:1;
        $arrData['isDisplayPdf'] = isset($arrData['isDisplayPdf'])?$arrData['isDisplayPdf']:1;
        

        $config = config('filesystems.disks.digitalSignature');
        $path =  public_path().'/uploads/e-signature/'.$arrData['signaturePath'];
        if(File::exists($path) && !empty($arrData['signaturePath'])) { 
            $fileContents = file_get_contents($path);
            $source = '/SignatureImage/'.$arrData['signaturePath'];
            Storage::disk('digitalSignature')->put($source, $fileContents);

            // Start Save File into DSS server
            if($arrData['isSavePdf']==1){
                $path =  public_path().'/uploads/digital_certificates/'.$arrData['filename'];
                $fileContents = file_get_contents($path);
                $source = '/Original/'.$arrData['filename'];
                Storage::disk('digitalSignature')->put($source, $fileContents);
            }else{
                $source = '/Signed/'.$arrData['filename'];
            }
            
            $dest = '/Signed/'.$arrData['filename'];
            $parameters = [
                'pCode' => $config['pCode'],
                'userId' => $config['userId'],
                'orgId' => $config['orgId'],
                'pin' => $config['pin'],
                'dtsFlag' => '1',
                'fileServerId'=>$config['fileServerId'],
                'source'=>$source,
                'dest'=>$dest,
                "signerFontSize" =>isset($arrData['signerFontSize'])?$arrData['signerFontSize']:'2',
                "customSignatureText"=>$config['customSignatureText'],
                'signerXyPage'=>isset($arrData['signerXyPage'])?$arrData['signerXyPage']:'320,200,100,140,1',
                'signerImagePath'=>'/SignatureImage/'.$arrData['signaturePath']
            ];

            // Make a POST request with x-www-form-urlencoded content type
            $response = Http::asForm()->post($config['signPdfConfigB_url'], $parameters);
            
            // Retrieve the PDF file contents
            $fileContents = Storage::disk('digitalSignature')->get($dest);
            if ($fileContents) {
                if(!$arrData['isMultipleSign'] || $arrData['isDisplayPdf']==1){
                    Storage::disk('digitalSignature')->delete($dest);
                    Storage::disk('digitalSignature')->delete($source);
                    $path =  public_path().'/uploads/digital_certificates/'.$arrData['filename'];
                    if(File::exists($path)) { 
                        File::delete($path);
                    }

                    $headers = [
                        'Content-Type' => 'application/pdf',
                    ];
                    return response($fileContents, 200, $headers);
                }
            }else{
                echo  "File not found or unable to retrieve.";exit;
            }
        }else{
            echo  "Signature not found, Please upload signature.";exit;
        }
    }

    public function deleteDigitalSignature($filename){
        $path =  public_path().'/uploads/digital_certificates/'.$arrData['filename'];
        if(File::exists($path)) { 
            File::delete($path);
        }
        $dest = '/Signed/'.$filename;
        $source = '/Original/'.$filename;
        Storage::disk('digitalSignature')->delete($dest);
        Storage::disk('digitalSignature')->delete($source);
    }

    public function sendEmailThourghAjax($apiName,$params){
        $params = json_encode($params);
        ?>
        <script src="<?php echo asset('js/jquery.min.js');?>"></script>
        <script>
            var DIR = "<?php echo URL::asset('');?>";
            var data = <?php echo $params; ?>;
            var apiName ="<?php echo $apiName; ?>";
            var url = DIR+apiName;
            //alert("hii")
            $(document).ready(function(){
                $.ajax({
                    url :url, // json datasource
                    type: "GET", 
                    data:data,
                    success: function(html){
                    }
                })
            })
        </script>
        <?php
    }

    public function getCoanoRegister($id){
         return DB::table('cto_payment_or_registers')->select('coa_no','cpor_series')->where('id',$id)->first();
    }
    public function getSettingData($name){
        return DB::table('settings')->where('name',$name)->pluck('value')->first();
    }
    public function addcashierIncomeData($postdata){
      DB::table('cto_cashier_income')->insert($postdata);
    }
    public function deletecashierincome($id){
      DB::table('cto_cashier_income')->where('cashier_id',$id)->delete();
    }
    public function isSignApply($var=''){
        return DB::table('sign_applications')->where('var_name',$var)->first();
    }
    public function getuserSignature($id=0){
        return DB::table('users')->where('id',$id)->pluck('e_signature')->first();
    }
    public function getuseridbyempid($id=0){
        return DB::table('hr_employees')->where('id',$id)->select('user_id')->first();
    }
    public function getemployeefullname($id){
         return DB::table('hr_employees')->where('user_id',$id)->select('fullname')->first();
    }
    public function getPaymentOrSetup($ortype_id){
      return DB::table('cto_payment_or_setups')->where('user_id', Auth::user()->id)->where('ortype_id',$ortype_id)->first();
    }

    public function getBarangayname($id){
      $sql = DB::table('barangays')->select('brgy_name');
      $sql->where('id',$id);
       return $sql->first();
    }
    
    // for Treasury > Cash Reciept
    public function insertCashReceipt($tfoc_id, $amount, $or_no, $particulars ){
        return CtoCashReciept::insertCashReceipt($tfoc_id, $amount, $or_no, $particulars );
    }
}
