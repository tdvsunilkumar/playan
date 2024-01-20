<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use DB;

// relation
use App\Models\SocialWelfare\Citizen;

class HoMedicalCertificate extends Model
{
    use HasFactory;
    protected $guarded;
    protected $table = "ho_medical_certificates";
	
	public function updateMedicalData($id,$columns){
        return DB::table('ho_medical_certificates')->where('id',$id)->update($columns);
    }
	
    public function patient() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
    public function officer() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'med_officer_id'); 
    }

    public function incident_brgy(){//brgy_add->region->
        return $this->hasOne(Barangay::class, 'id', 'incedent_place'); 
    }

    public function getCitizenAddress($citizen_id){
        return Citizen::
                where('citizens.id', $citizen_id)->first();
    }

    public function getOrNumbers($citizen_id){
        // return DB::table('cto_cashier')->select('or_no', 'id')->orderBy('id', 'DESC')->get();
        return DB::table('cto_cashier')
            ->leftJoin('cto_cashier_details', 'cto_cashier_details.cashier_id', 'cto_cashier.id')
            ->join('cto_forms_miscellaneous_payments', 'cto_forms_miscellaneous_payments.tfoc_id', 'cto_cashier_details.tfoc_id')
            ->where('cto_forms_miscellaneous_payments.fpayment_module_name', 'hs_medical_certificate')
            ->where('cto_cashier.payee_type', 2)
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
    public function getBarangay(){
		return DB::table('barangays As b')
		->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
		->select('b.id','b.brgy_name','pm.mun_desc')->get();	
	}

    public function addData($request){
        Self::create($request);
        $last_medical_report = Self::orderBy('id', 'DESC')->first();
        $form_id = DB::table('user_forms')->where('form_name', 'Health & Safety: Medical Certificate')->first();

        $is_data = [
                'officer_id' => $request["med_officer_id"], 
                'position' => $request["med_officer_position"],
                'med_cert_id' => $last_medical_report->id
            ];
        $user_form = [
            'is_data' => json_encode($is_data),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('user_last_save_data')->updateOrInsert(
            [
                'form_id' => $form_id->id,
                'user_id' => Auth::user()->id,
            ],
            $user_form
        );
    }

    public function updateData($id, $request){
        // $existing_user_form = DB::table('user_last_save_data')
        // ->whereJsonContains('is_data', ['med_cert_id' =>$id])
        // ->first();
        $existing_user_form = DB::table('user_forms')->where('form_name', 'Health & Safety: Medical Certificate')->first();
        
        if($existing_user_form != ''){
            
            $is_data = [
                'officer_id' => $request["med_officer_id"], 
                'position' => $request["med_officer_position"],
                'med_cert_id' => $id
            ];
            
            $user_form = [
            // 'form_id' => $existing_user_form->form_id,
            'form_id' => $existing_user_form->id,
            'user_id' => Auth::user()->id,
            'is_data' => json_encode($is_data),
            'updated_at' => Carbon::now(),
            ];
            
            DB::table('user_last_save_data')
            // ->whereJsonContains('is_data', ['med_cert_id' =>$id])
            ->where(['form_id' => $existing_user_form->id],['user_id' => Auth::user()->id])
            ->update($user_form);
        }
        
        return Self::find($id)->update($request);
    }

    public function getSingleReport($id){
        return Self::find($id);
    }
    public function getSingleUser($id){
        return DB::table('ho_medical_certificates')
        ->join('hr_employees', 'hr_employees.id', 'ho_medical_certificates.med_officer_id')
        ->select('hr_employees.user_id')
        ->where('ho_medical_certificates.id', $id)
        ->first();
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
                1 =>"cit_id",
                2 =>"cit_age",
                3 =>"profile_municipalities.mun_desc",
                4 =>"officer_name",
                5 =>"med_cert_date",
                6 =>"or_no",
                7 =>"or_amount",
                8 =>"is_active",
            );
            
            $sql = self::
                join('citizens', 'citizens.id', 'ho_medical_certificates.cit_id')
                ->join('barangays', 'barangays.id', 'citizens.brgy_id')
                ->join('profile_municipalities', 'profile_municipalities.id', 'barangays.mun_no')
                ->join('hr_employees', 'hr_employees.id', 'ho_medical_certificates.med_officer_id')
                ->select('ho_medical_certificates.*',
                 'citizens.cit_fullname AS citizen_name', 
                 'citizens.cit_age AS age', 
                 'barangays.brgy_name', 
                 'profile_municipalities.mun_desc', 
                 'hr_employees.fullname AS officer_name');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%"); 
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
        return DB::table('ho_medical_certificates')->where('id',$id)->update($columns);
    }
    
    public function getLastReport(){
        // return Self::orderBy('id', 'DESC')->first();
        $form_id = DB::table('user_forms')->where('form_name', 'Health & Safety: Medical Certificate')->first();
        return DB::table('user_last_save_data')
            ->where('user_id', Auth::user()->id)
            ->where('form_id', $form_id->id)
            ->orderBy('id', 'DESC')
            ->first();
    }

}
