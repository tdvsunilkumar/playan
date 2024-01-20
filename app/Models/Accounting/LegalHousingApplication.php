<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\EcoHousingPenalty;
use App\Models\EcoService;
use App\Models\SocialWelfare\Citizen;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LegalHousingApplication extends Model
{
    public $table = 'eco_housing_application';
    public function updateData($id,$columns){
        return DB::table('eco_housing_application')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
      DB::table('eco_housing_application')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function findById($id){
        return DB::table('eco_housing_application')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eco_housing_application')->where('id',$id)->update($columns);
    } 
    public function getLastId(){
      return DB::table('eco_housing_application')->latest('id')->first();
    } 

    //Client data
    public function client() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'client_id'); 
    }
    //Penalty data
    public function penalty_details() 
    { 
        return $this->hasOne(EcoHousingPenalty::class, 'id', 'penalty'); 
    }
    //Sevice/Transaction data
    public function eco_service() 
    { 
        return $this->hasOne(EcoService::class, 'id', 'type_of_transaction_id'); 
    }
    //Lot Bought data
    public function houses() 
    { 
        return $this->hasMany(LegalHousingApplicationLocation::class, 'housing_application_id', 'id'); 
    }
    //Files data
    public function files() 
    { 
        return $this->hasMany(LegalHousingApplicationFile::class, 'housing_application_id', 'id'); 
    }
    //Summary Of Payment
    public function getBreakdownAttribute() 
    { 
        return DB::table('cto_receivables as cr')->select('*')->where('application_id',$this->id)->where('category','housing')->orderby('cr.due_date','ASC')->get(); 
    }
    // for add 
    public function uploadFile($id, $files){
      if ($files) {
        foreach ($files as $file_id => $file) {
          LegalHousingApplicationFile::updateOrCreate(
            [
              'housing_application_id'=> $id,
              'id'=> $file_id,
            ],
            $file
          );
        }
      }
    }
    public function updateResidential($id, $residences){
      foreach ($residences as $residence_id => $residence) {
        LegalHousingApplicationLocation::updateOrCreate(
          [
            'housing_application_id'=> $id,
            'id'=> $residence_id,
          ],
          [
            'residential_name_id' => $residence['residential_name_id'],
            'residential_location_id' => $residence['residential_location_id'],
            'blk_lot_id' => $residence['blk_lot_id'],
            'is_active' => 1,
          ]
        );
        $resident = LegalResidentialLocDetails::find($residence['blk_lot_id'])->update(['lot_status'=>0]);
        // dd($residence['blk_lot_id']);
      }
    }
    public function updateBreakdown($data){
      $firstMonth = Carbon::parse($data->terms_date_from)->addMonths(1);
      $start = Carbon::parse($data->terms_date_from)->addMonths(2);
      $end = Carbon::parse($data->terms_date_to);
      $period = new CarbonPeriod($start, '1 month', $end);
      $citizen = Citizen::find($data->client_id);
      $remaining = $data->remaining_amount;
      $monthly = $remaining / $data->month_terms;
      $monthly = intval($monthly);
      $firstMonthPay = $monthly + $remaining - ($monthly * $data->month_terms);
      // firstmonth pay
      $payment['category'] = 'housing';
      $payment['top_transaction_type_id'] = $data->top_transaction_type_id;
      $payment['fund_code_id'] = $data->eco_service->tfoc->fund_id;
      $payment['gl_account_id'] = $data->gl_account_id;
      $payment['sl_account_id'] = $data->sl_account_id;
      $payment['description'] = $data->eco_service->tfoc_name;
      $payment['amount_due'] = $firstMonthPay;
      $payment['remaining_amount'] = $firstMonthPay;
      $payment['amount_type'] = 'monthly';
      $payment['is_active'] = 1;
      $payment['due_date'] = $firstMonth->toDateString();
      $payment['taxpayer_id'] = $data->client_id;
      $payment['taxpayer_name'] = $citizen->cit_fullname;
      $payment['pcs_id'] = 10;
      $payment['updated_at'] = Carbon::now()->toDateString();
      $payment['updated_by'] = Auth::user()->id;
      CtoReceivable::updateOrCreate(
        [
          'application_id'=> $data->id,
          'due_date' =>$payment['due_date']
        ],
        $payment
      );
      // if ($breakdown) {
        foreach ($period as $date) {
          $payment['amount_due'] = $monthly;
          $payment['remaining_amount'] = $monthly;
          $payment['due_date'] = $date->toDateString();
          // dd($payment);
          CtoReceivable::updateOrCreate(
            [
              'application_id'=> $data->id,
              'due_date' =>$payment['due_date']
            ],
            $payment
          );
        }
      // }
        return $monthly;
    }
    // for select
    public function getResidence(){
      $residence = LegalResidentialName::where('eco_residential_name.is_active',1)
      ->leftJoin('barangays', 'barangays.id', '=', 'eco_residential_name.barangay_id')
      ->leftJoin('eco_type_of_transaction', 'eco_type_of_transaction.id', '=', 'eco_residential_name.residential_name')
      ->get(['brgy_name','type_of_transaction','eco_residential_name.id as id'])->mapWithKeys(function ($village, $key) {
          $array[$village->id] = $village->brgy_name." => ".$village->type_of_transaction;
          return $array;
      })->toArray();
      $residence = [''=>'Please Select'] + $residence;
      return $residence;
    }
    public function getPhase($search="",$residential_id)
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = LegalResidentialLocation::where([['is_active',1],['residential_id',$residential_id]]);
        if(!empty($search)){
            $sql->where(function ($sql) use($search) {
                    if(is_numeric($search)){
                        $sql->Where('id',$search);
                    }else{
                        $sql->where(DB::raw('LOWER(phase)'),'like',"%".strtolower($search)."%");
                    }
            });
        }
        $sql->orderBy('phase','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getBlk($search="",$phase_id)
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = LegalResidentialLocDetails::where([['is_active',1],['residential_location_id',$phase_id],['lot_status',1]]);
        if(!empty($search)){
            $sql->where(function ($sql) use($search) {
                    if(is_numeric($search)){
                        $sql->Where('id',$search);
                    }else{
                        $sql->where(DB::raw('LOWER(lot_number)'),'like',"%".strtolower($search)."%");
                    }
            });
        }
        $sql->orderBy('lot_number','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getPenalties(){
      $penalty = EcoHousingPenalty::where('is_active',1)->get()->mapWithKeys(function ($penalty, $key) {
                    $percent = $penalty->id;
                    $array[$percent] = $penalty->name;
                    return $array;
                })->toArray();
      $penalty = array_merge([0=>'Please Select'],$penalty);
      return $penalty;
    }
    //listing
    public function getList($request){
      $params = $columns = $totalRecords = $data = array();
      $params = $_REQUEST;
      $q=$request->input('q');

      if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
      }

      $columns = array( 
        "lha.id",
        "lha.reference_id",
        "lha.top_transaction_no",
        "cc.cit_fullname",
        "type_trans.tfoc_name",
        "lha.total_amount",
        "penalty.name",
        "lha.remaining_amount",
        "lha.or_no",
        "lha.or_date",
      );

      $sql = DB::table('eco_housing_application as lha')
            ->leftJoin('eco_services AS type_trans', 'type_trans.id', '=', 'lha.type_of_transaction_id')
            ->leftJoin('citizens AS cc', 'cc.id', '=', 'lha.client_id')
            ->leftJoin('eco_housing_penalties AS penalty', 'penalty.id', '=', 'lha.penalty')
            ->select('lha.*','type_trans.tfoc_name as type_of_transaction', 'cc.cit_fullname','penalty.name as penalty');
      if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
          $sql->where(DB::raw('LOWER(lha.reference_id)'),'like',"%".strtolower($q)."%")
            ->orWhere(DB::raw('LOWER(lha.top_transaction_no)'),'like',"%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(cc.cit_fullname)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(type_trans.tfoc_name)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(lha.total_amount)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(penalty.name)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(lha.remaining_amount)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(lha.or_no)"), 'LIKE', "%".strtolower($q)."%")
            ->orWhere(DB::raw("LOWER(lha.or_date)"), 'LIKE', "%".strtolower($q)."%");
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

    public function getTopTransactionAttribute(){
      return DB::table('cto_top_transaction_type')->where('ttt_table_reference',$this->table)->orderBy('id','asc')->first();
    }

    public function getTypeTrans(){
        return DB::table('eco_services')->where('top_transaction_type_id',$this->top_transaction->id)->orderBy('id','DESC')->get();
    }
    //transaction
    public function transaction($id){

      $data = self::find($id);
      $last_id = DB::table('cto_top_transactions')->orderBy('id','desc')->first();
      $lastNum = sprintf('%06d',$last_id->id+1);
      $top_type = $this->top_transaction;
      if (!($data->top_transaction_id)) {
  
        DB::table('cto_top_transactions')->insert([
          'transaction_no' => $lastNum,
          'top_transaction_type_id' => $top_type->id,
          'transaction_ref_no' => $data->id,
          'tfoc_is_applicable' => $top_type->tfoc_is_applicable,
          'tfoc_id' => $data->eco_service->tfoc_id,
          'amount' => $data->remaining_amount,
          'created_by' => Auth::user()->creatorId(),
          'created_at' => date('Y-m-d H:i:s'),
        ]);
  
        $trans_id = DB::getPdo()->lastInsertId();

        // save to eco housing
        $data->top_transaction_no = $lastNum;
        $data->trans_id = $trans_id;
        $data->top_transaction_type_id = $data->eco_service->top_transaction_type_id;
        $data->tfoc_id = $data->eco_service->tfoc_id;
        $data->gl_account_id = $data->eco_service->tfoc->gl_account_id;
        $data->sl_account_id = $data->eco_service->tfoc->sl_id;
        $data->update();

        return [
          'TOP' => $lastNum,
          'TOP_id' => $trans_id,
        ];
      }
      
      return null;
    }

    public function setPenalty(){
      $tfoc_name = $this->eco_service->tfoc_name;
      $date = Carbon::now();
      $type_of_transaction = LegaltypeTransaction::where('type_of_transaction','LIKE','%'.$tfoc_name.'%')->first();
      switch ($type_of_transaction->trigger_type) {
        case 'daily':
          $next_trigger = $date->addDays($type_of_transaction->trigger_count)->toDateString();
          break;
        case 'monthly':
          $next_trigger = $date->addMonths($type_of_transaction->trigger_count)->toDateString();
          break;
        case 'annually':
          $next_trigger = $date->addYears($type_of_transaction->trigger_count)->toDateString();
          break;
        default:
          # code...
          break;
      }
      $compute = $type_of_transaction->computation;
      $compute = str_replace('[total_amount]',$this->total_amount, $compute);
      $compute = str_replace('[initial_monthly]',$this->initial_monthly, $compute);
      $compute = str_replace('[penalty]',$this->penalty_details->percentage, $compute);
      $compute = str_replace('[month_terms]',$this->month_terms, $compute);
      $compute = str_replace('[monthly_pay]',$this->monthly_pay, $compute);
      $compute = str_replace('[remaining_amount]',$this->remaining_amount, $compute);

      $penalty_amt = eval('return '.$compute.';');


      $payment['application_id'] = $this->id;
      $payment['category'] = 'housing';
      $payment['top_transaction_type_id'] = $this->top_transaction_type_id;
      $payment['fund_code_id'] = $this->eco_service->tfoc->fund_id;
      $payment['gl_account_id'] = $this->gl_account_id;
      $payment['sl_account_id'] = $this->sl_account_id;
      $payment['description'] = $this->eco_service->tfoc_name;
      $payment['amount_due'] = $penalty_amt;
      $payment['remaining_amount'] = $payment['amount_due'];
      $payment['amount_type'] = 'penalty';
      $payment['is_active'] = 1;
      $payment['due_date'] = $date->toDateString();
      CtoReceivable::create(
        $payment
      );

      // dd($type_of_transaction);
      $data = self::find($this->id);
      $data->remaining_amount = $data->remaining_amount + $penalty_amt;
      $data->next_trigger = $next_trigger;
      $data->update();
    }

}
