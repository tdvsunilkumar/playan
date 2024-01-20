<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\Models\SocialWelfare\Citizen;

use App\Traits\ModelUpdateCreate;

class Ho_Medical_Record extends Model
{
    use ModelUpdateCreate;

    public $table = 'ho_medical_records';
    public function updateMedicalData($id,$columns){
        return DB::table('ho_medical_records')->where('medical_rec_id',$id)->update($columns);
    }
    public function updateActiveInactive($id,$columns){
        return DB::table('ho_medical_records')->where('medical_rec_id',$id)->update($columns);
    }
    public function addMedicalData($postdata){
        DB::table('ho_medical_records')->insert($postdata);
        return DB::table('ho_medical_records')->orderBy('medical_rec_id','desc')->first()->medical_rec_id;
    }
    public function diagnosisData(){
    return DB::table('ho_diagnoses')->select('id','diag_name')->where('hd_is_active',1)->get();
    }
    public function empData(){
        return DB::table('hr_employees')
        ->leftjoin('acctg_departments', 'acctg_departments.id', '=', 'hr_employees.acctg_department_id')
        ->where('acctg_departments.shortname','CHO')
        ->get();
    }
    public function getAddMoreDataMedical($id){
        return DB::table('ho_medical_records')
        ->select('*')
        ->join('ho_medical_record_diagnoses', 'ho_medical_record_diagnoses.med_rec_id', '=', 'ho_medical_records.medical_rec_id')
        ->join('ho_treatments', 'ho_treatments.med_rec_id', '=', 'ho_medical_records.medical_rec_id')
        ->join('ho_diagnoses', 'ho_diagnoses.id', '=', 'ho_medical_record_diagnoses.disease_id')
        ->get();
    }
    public function deleteMedicalRecordCard($id){
        return DB::table('ho_medical_records')->where('medical_rec_id',$id)->delete();
    }
    public function addRelation($data){
        $rec_id = $data['medical_rec_id'];
        if (isset($data['treatment'])) {
            foreach ($data['treatment'] as $key => $value) {
                if ($value['treat_medication']){
                    $key = strpos($key, 'new') !== false ? '' : $key;
                    $add = Ho_Treatment::updateOrCreate(
                        [
                            'med_rec_id' => $rec_id,
                            'id' => $key,
                        ],
                        [
                            'treat_medication' => $value['treat_medication'],
                            'cit_id' => $data['cit_id'],
                            'cit_age' => $data['cit_age'],
                            'cit_age_days' => $data['cit_age_days'],
                            'treat_management' => $value['treat_management'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->creatorId(),
                            'treat_is_active' => 1,
                        ]
                    );
                }
            }
        }
        if (isset($data['diagnosis'])) {
            foreach ($data['diagnosis'] as $key => $value) {
                if (isset($value['disease'])){
                    $key = strpos($key, 'new') !== false ? '' : $key;
                    $add = Ho_Medical_Record_Diagnosis::updateOrCreate(
                        [
                            'med_rec_id' => $rec_id,
                            'id' => $key,
                        ],
                        [
                            'disease_id' => $value['disease'],
                            'cit_id' => $data['cit_id'],
                            'cit_age' => $data['cit_age'],
                            'cit_gender' => $data['cit_gender'],
                            'cit_age_days' => $data['cit_age_days'],
                            'is_specified' => $value['specify'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->creatorId(),
                            'is_active' => 1,
                        ]
                    );
                }
            }
        }
    }
    public function getList($params, $record_id = null){
        // $params = $columns = $totalRecords = $data = array();
        // $params = $_REQUEST;
        $q=$params->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }

        $columns = array( 
                0 =>"ho_medical_records.medical_rec_id", 
                1 =>"ho_record_cards.rec_card_num",
                2 =>"citizens.cit_fullname",
                3 =>"barangays.brgy_name", 
                4 =>"cit_age_days",
                5 =>"citizens.cit_gender",
                6 => "diagnoses.diag_name",
                7 =>"ho_medical_records.med_rec_nurse_note", 
                8 =>"hr_employees.fullname", 
                9 =>"ho_medical_records.med_rec_date", 
                10 => "ho_medical_records.med_rec_status"
              );
              $sql = $this->
            //   ->select('ho_medical_records.med_rec_date','ho_medical_records.med_rec_nurse_note','ho_medical_records.med_id as med_id')
            //   with('recordCard','patient','diagnose','treatment')
                        leftJoin(DB::raw("(SELECT med_rec_id, GROUP_CONCAT( ho_diagnoses.diag_name) as diag_name FROM `ho_medical_record_diagnoses` 
                                                LEFT JOIN ho_diagnoses on ho_medical_record_diagnoses.disease_id = ho_diagnoses.id
                                                GROUP BY med_rec_id
                                                ) AS diagnoses "),
                        'ho_medical_records.medical_rec_id','=','diagnoses.med_rec_id')
                        ->leftJoin('hr_employees', 'hr_employees.id', '=', 'ho_medical_records.hp_code')
                        ->leftJoin('citizens', 'citizens.id', '=', 'ho_medical_records.cit_id')
                        ->leftJoin('barangays', 'barangays.id', '=', 'citizens.brgy_id')
                        ->leftJoin('ho_record_cards', 'ho_record_cards.id', '=', 'ho_medical_records.rec_card_id');
              if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(barangays.brgy_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ho_medical_records.cit_age)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ho_medical_records.med_rec_nurse_note)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ho_medical_records.med_rec_date)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(diagnoses.diag_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ho_record_cards.rec_card_num)'),'like',"%".strtolower($q)."%");
                });
              }
            if ($record_id != null) {
                $sql = $sql->where([['rec_card_id',$record_id],['med_rec_status',1]]);
                if (!isset($params['length'])) {
                    $params['length']="5";
                }
            }
              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                // dd($columns[$params['order'][0]['column']]);
              else
                $sql = $sql->orderBy('ho_medical_records.created_at','DESC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    
    public function getTreatmentAttribute(){
        return DB::table('ho_treatments')->where('med_rec_id',$this->medical_rec_id)->get();
    }
    public function getDiagnosisAttribute(){
        return DB::table('ho_medical_record_diagnoses as mrd')->join('ho_diagnoses as d','mrd.disease_id','=','d.id')->select('mrd.*','d.diag_name')->where('med_rec_id',$this->medical_rec_id)->get();
    }
    public function getMedIdAttribute(){
        // dd($this);
        return $this->medical_rec_id; //it overlaps other id so i have to do this
    }
    public function recordCard() 
    { 
        return $this->hasOne(HoRecordCard::class, 'id', 'rec_card_id'); 
    }
    public function patient() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
    // public function diagnose() 
    // { 
    //     return $this->hasMany(Ho_Medical_Record_Diagnosis::class, 'med_rec_id', 'medical_rec_id'); 
    // }
    // public function treatment() 
    // { 
    //     return $this->hasMany(Ho_Treatment::class, 'med_rec_id', 'medical_rec_id'); 
    // }
    public function officer() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'hp_code'); 
    }
    public function selectDiagnosis($search="")
      {
            $page=1;
            if(isset($_REQUEST['page'])){
            $page = (int)$_REQUEST['page'];
            }
            $length = 20;
            $offset = ($page - 1) * $length;
            $sql = DB::table('ho_diagnoses');
            if(!empty($search)){
                  $sql->where(function ($sql) use($search) {
                        if(is_numeric($search)){
                              $sql->Where('id',$search);
                        }else{
                              $sql->where(DB::raw('LOWER(diag_name)'),'like',"%".strtolower($search)."%");
                        }
                  });
            }
            $sql->orderBy('diag_name','ASC');
            $data_cnt=$sql->count();
            $sql->offset((int)$offset)->limit((int)$length);
            
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

    // Updated By Nausad
    public function getIssuances($id){
        return DB::table('ho_issuances')
            ->where('ho_issuances.receiver_id', $id)
            // ->join('ho_inventory_posting', 'ho_inventory_posting.id', 'ho_issuances.ho_inv_posting_id')
            // ->join('gso_unit_of_measurements', 'ho_inventory_posting.cip_uom', 'gso_unit_of_measurements.id')
            ->select(
                // 'ho_inventory_posting.cip_item_name', 
                    // 'gso_unit_of_measurements.code AS uom_code', 
                    // 'ho_issuances.issuance_quantity', 
                    'ho_issuances.id',
                    'ho_issuances.is_active',
                    'ho_issuances.created_at')
            ->get();
    }

    public function updateDiagnosisActiveInactive($id,$columns){
        return DB::table('ho_medical_record_diagnoses')->where('id',$id)->update($columns);
    }

    public function updateTreatmentActiveInactive($id,$columns){
        return DB::table('ho_treatments')->where('id',$id)->update($columns);
    }

      
}
