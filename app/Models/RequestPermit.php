<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\SocialWelfare\Citizen;
use App\Models\HrEmployee;
use Auth;
class RequestPermit extends Model
{
  public $table = 'ho_request_permit';
  public $timestamps = false;

  public function requestor() 
  { 
      return $this->hasOne(Citizen::class, 'id', 'requestor_id'); 
  }
  
  public function getEmployee(){
    return DB::table('hr_employees')->select('id','fullname')->get();
  }
  public function fees() 
  { 
      return $this->hasMany(RequestPermitFees::class, 'req_permit_id', 'id'); 
  }
  public function removeFee($id)
  {
    $fee = RequestPermitFees::destroy($id);
    return $fee;
  }
  public function checkFees($id) {
    $check = RequestPermitFees::where([['service_id',$id],['req_permit_id',$this->id]])->first();
    return ($check) ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
  }
  public function get_or_results($service_id) {
      $check = RequestPermitFees::join('ho_services','ho_services.id','ho_lab_fees.service_id')->where([['req_permit_id',$this->id],['ho_services.ho_service_form',$service_id],['is_free',0]])->get();
      return ($check) ? $this->lab_req_or : '';
  }
  public function updateData($id,$columns){
      return DB::table('ho_request_permit')->where('id',$id)->update($columns);
  }
  public function addData($postdata){
    // dd($postdata);
      DB::table('ho_request_permit')->insert($postdata);
      return DB::getPdo()->lastInsertId();
  }
  public function updateActiveInactive($id,$columns){
    return DB::table('ho_request_permit')->where('id',$id)->update($columns);
  }
  public function getCitizenDetails($id){
      return  DB::table('citizens')
              ->select('*')
              ->where('id',$id)->first();
  }
  public function getCitizen(){
      return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name')->get();
  }
  public function deleteLaboratoryReq($id){
    return DB::table('ho_request_permit')->where('id', $id)->delete();
  }
  public function getServices(){
    return DB::table('ho_services')->select('*')->where('ho_is_active',1)->get();
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
		1 =>"control_no",
        2 =>"ctz.cit_fullname",
        3 =>"brgy_name",
		4 =>"top_transaction_no",
		5 =>"or_no",
		6 =>"request_amount",
		7 =>"request_amount",
        8 =>"request_date", 
        9 =>"is_posted",
        10 =>"status"
      
      );
      $sql = self::join('citizens AS ctz', 'ctz.id', '=', 'ho_request_permit.requestor_id')
            ->leftjoin('cto_cashier AS cash', 'cash.id', '=', 'ho_request_permit.cashier_id')
            ->leftjoin('barangays AS bar', 'bar.id', '=', 'ctz.brgy_id')
            ->select('ho_request_permit.*','ctz.cit_fullname','ctz.cit_age','bar.brgy_name','ctz.cit_last_name','ctz.brgy_id','ctz.cit_gender');
      if(!empty($q) && isset($q)){
          $sql->where(function ($sql) use($q) {
              $sql->where(DB::raw('LOWER(ho_request_permit.lab_control_no)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ctz.cit_fullname)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(bar.brgy_name)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ho_request_permit.request_date)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ho_request_permit.control_no)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ho_request_permit.request_amount)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ho_request_permit.top_transaction_no)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(ho_request_permit.trans_id)'),'like',"%".strtolower($q)."%")
				  ;
        });
      }
      /*  #######  Set Order By  ###### */
      if(isset($params['order'][0]['column']))
   
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      else
        $sql->orderBy('ho_request_permit.id','DESC');

      /*  #######  Get count without limit  ###### */
      $data_cnt=$sql->count();
      /*  #######  Set Offset & Limit  ###### */
      $sql->offset((int)$params['start'])->limit((int)$params['length']);
      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
  }

  public function getIssueByPatient($params,$cit_id){
    try {
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="5";
        }
        $columns = array( 
            0 =>"id",
            1 =>"service_name",
            2 =>"or_no",
            5 =>"is_active"
        );
        // Query for withrwals type
        $sql = RequestPermitFees::where('ho_lab_fees.cit_id', $cit_id)
        ->leftJoin('ho_services as ser','ser.id','=','ho_lab_fees.service_id')
        ->leftJoin('ho_lab_requests AS labreq', 'labreq.id', '=', 'ho_lab_fees.lab_req_id')
        ->select('labreq.lab_req_or','ho_lab_fees.*','ser.ho_service_name','labreq.lab_reg_date'
                );
                /*  #######  Set Order By  ###### */
                if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                else
                    $sql->orderBy('ho_lab_fees.id','DESC');

                /*  #######  Get count without limit  ###### */
                // $cnt=$sql->count();
                $cnt=$sql->count();
                // $sql->groupBy('ho_issuances.issuance_code');
                /*  #######  Set Offset & Limit  ###### */
                $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
                $data = $sql->get();
        return array("data_cnt"=>$cnt,"data"=> $data);
    }catch (\Exception $e) {
        return ($e->getMessage());
    }
}
      
  public function addFees($data)
  {
      if (isset($data['fees'])) {
        foreach ($data['fees'] as $key => $value) {
            if (isset($value['service_id'])){
           //dd($value);
              $service = HealthSafetySetupDataService::find($value['service_id']);
              $accounting = DB::table('cto_tfocs')->where('id',$service->tfoc_id)->first();
                $add = RequestPermitFees::updateOrCreate(
                    [
                        'req_permit_id' => $data['id'],
                        'id' => $key,
                    ],
                    [
                        'service_id' => $value['service_id'],
                        'lab_control_no' => $data['control_no'],
                        'requestor_id' => $data['requestor_id'],
                        'hlf_service_name' => $value['service_name'],
                        'is_free' => (isset($value['hlf_is_free']))? 1:0,
                        'tfoc_id' => $service->tfoc_id,
                        'agl_account_id' => $accounting->gl_account_id,
                        'sl_id' => $accounting->sl_id,
                        'top_transaction_type_id' => $service->top_transaction_type_id,
                        'permit_fee' => ($value['fee'])? str_replace(',', '',$value['fee']):'', 
                        'updated_at' => date('Y-m-d H:i:s'),
                        // 'wsd_is_active' => 1,
                    ]
                );
            }
        }
      }
  }

  public function transaction($id){
    $data = self::find($id);
    $last_id = DB::table('cto_top_transactions')->orderBy('id','desc')->first();
    $lastNum = sprintf('%06d',$last_id->id+1);
    $top_type = DB::table('cto_top_transaction_type')->where('ttt_table_reference',$this->table)->orderBy('id','asc')->first();
	
    if (!($data->top_transaction_id) && ($data->request_amount != '0.00')) {

      DB::table('cto_top_transactions')->insert([
        'transaction_no' => $lastNum,
        'top_transaction_type_id' => $top_type->id,
        'transaction_ref_no' => $id,
        'tfoc_is_applicable' => 9,
        'tfoc_id' => 0,
        'amount' => $data->request_amount,
        'created_by' => Auth::user()->creatorId(),
        'created_at' => date('Y-m-d H:i:s'),
      ]);

      $trans_id = DB::getPdo()->lastInsertId();
		
      return [
        'TOP' => $lastNum,
        'TOP_id' => $trans_id,
      ];
    }
    
    return null;
  }
  public function getAcceptAttribute() 
  { 
    $accept = 0;
    $or = DB::table('cto_cashier')->where('or_no',$this->or_no)->first();
    if($or){
      if ($or->status != 0) {
        $accept = 1;
      }
    }
    if ($this->is_free == 1 && $this->is_posted) {
        $accept = 1;
    }
    return $accept;
  }
}
