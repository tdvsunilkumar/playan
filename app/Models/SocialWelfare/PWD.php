<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProfileMunicipality;
use App\Models\ProfileProvince;
use App\Models\ProfileRegion;
use App\Models\HrEmployee;
use App\Models\Barangay;
use App\Traits\ModelUpdateCreate;

use DB;

class PWD extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_pwd_application_form';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getSelectNameAttribute()
    {
        return [
            'wpsoe_id' => isset($this->employ_status->wpsoe_description)?$this->employ_status->wpsoe_description:'',
            'wptod_id' => isset($this->disability->wptod_description)?$this->disability->wptod_description:'',
            'wpcoe_id' => isset($this->employ_category->wpcoe_description)?$this->employ_category->wpcoe_description:'',
            'wptoe_id' => isset($this->employ_type->wptoe_description)?$this->employ_type->wptoe_description:'',
            'wptoo_id' => isset($this->occupation->wptoo_description)?$this->occupation->wptoo_description:'',
            'wpcodi_id' => isset($this->cause_inborn->wpcodi_description)?$this->cause_inborn->wpcodi_description:'',
            'wpcoda_id' => isset($this->cause_acquire->wpcoda_description)?$this->cause_acquire->wpcoda_description:'',
        ];
    }

    public function addData($postdata){
        // dd($postdata);
        $this->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
        return $this->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return $this->where('id',$id)->update($columns);
    }
    public function locality()//get rpt_locality table 
    {
        $locality = Citizen::locality();
        $municipal = ProfileMunicipality::find($locality->mun_no);
        $province = $municipal->province($municipal->prov_no);
        $region = $municipal->region($municipal->reg_no);
        return (object)[
            'region' => $region,
            'province' => $province,
            'municipality' => $municipal,
            'locality' => $locality,
        ];
    }
    public function getDefaultRegionAttribute()
    {
        return self::locality()->region->uacs_code;
    }
    public function getDefaultProvinceAttribute()
    {
        return self::locality()->province->uacs_code;
    }
    public function getDefaultMunicipalAttribute()
    {
        return self::locality()->municipality->uacs_code;
    }
    public function getFirstIdAttribute()
    {
        return config('constants.defaultCityCode')['region'].'-'.config('constants.defaultCityCode')['province'].config('constants.defaultCityCode')['municipality'];
    }
    public function getNextNumberAttribute()
    {
        // dd($this->where('wspa_is_renewal',0)->orderBy('id','desc')->get());
        $lastNum = $this->orderBy('id','desc')->first()->wpaf_pwd_id_number;
        $lastNum = (int)explode('-',$lastNum)[3];
        $lastNum = sprintf('%07d',$lastNum+1);
        return $lastNum;
    }
    public function claimant() 
    { 
        return $this->belongsTo(Citizen::class, 'cit_id', 'id'); 
    }
    public function mother() 
    { 
        return $this->belongsTo(Citizen::class, 'wpaf_mothersname', 'id'); 
    }
    public function father() 
    { 
        return $this->belongsTo(Citizen::class, 'wpaf_fathersname', 'id'); 
    }
    public function guardian() 
    { 
        return $this->belongsTo(Citizen::class, 'wpaf_guardiansname', 'id'); 
    }
    public function processing() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wpaf_processing_officer', 'id'); 
    }
    public function approver() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wpaf_approving_officer', 'id'); 
    }
    public function encoder() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wpaf_encoder', 'id'); 
    }
    public function brgy() 
    { 
        return $this->belongsTo(Barangay::class, 'wpaf_brgy_id', 'id'); 
    }
    public function municipal() 
    { 
        return $this->belongsTo(ProfileMunicipality::class, 'wpaf_municipal', 'id'); 
    }
        
    public function province() 
    { 
        return $this->belongsTo(ProfileProvince::class, 'wpaf_province', 'id'); 
    }
    public function region() 
    { 
        return $this->belongsTo(ProfileRegion::class, 'wpaf_region', 'id'); 
    }
    public function files() 
    { 
        return $this->hasMany(PWDFiles::class, 'wpaf_id', 'id'); 
    } 
    public function associate() 
    { 
        return $this->hasMany(PWDAssociation::class, 'wpaf_id', 'id'); 
    } 
    public function associateCount()
    {
        return PWDAssociation::all()->count();
    }
    public function updateRelation($data)
    {
        if ($data->type == 'requirement') {
            $assist = PWDFiles::find($data->id);
            $assist->fwp_is_active = $data->status;
            $assist->save();
        }
        elseif($data->type == 'associate') {
            $assist = PWDAssociation::find($data->id);
            $assist->wpo_is_active = $data->status;
            $assist->save();
        }
    }

    public function addRelation($data)
    {
        if (isset($data->require)) {
            foreach ($data->require as $key => $value) {
                $fileName = '';
                $fileType = '';
                $fileSize = '';
                $filePath = '';
                if ( $file = PWDFiles::
                    where([['id',$key],['wpaf_id',$data->id]])
                    ->orWhere([['req_id',$value['req_id']],['wpaf_id',$data->old_id]])
                    ->first()){ 
                    $key = $file->id;
                    $fileName = $file->fwp_name;
                    $fileType = $file->fwp_type;
                    $fileSize = $file->fwp_size;
                    $filePath = $file->fwp_path;
                }
                if (isset($value['file'])){ 
                    $size = $value['file']->getSize() * .001;
                    $fileSize = round($size,2);
                    $fileName =  $value['req_type'].$value['req_id'].'-'.time().'.'.$value['file']->getClientOriginalExtension();
                    $filePath = "uploads/socialwelfare/".$fileName;
                    $fileType = $value['file']->getClientOriginalExtension();
                    $value['file']->move(public_path('uploads/socialwelfare'), $fileName);
                    $fileName = $value['file']->getClientOriginalName();
                }
                $add = PWDFiles::updateOrCreate(
                    [
                        'wpaf_id' => $data->id,//senior id
                        'id' => $key,//file id
                    ],
                    [
                        'req_id' => $value['req_id'], // req id
                        'req_type' => $value['req_type'], // req id
                        'fwp_name' => $fileName,//file name
                        'fwp_type' => $fileType,//file type
                        'fwp_size' => $fileSize, // file size
                        'fwp_path' => $filePath,
                        'fwp_is_active' => 1, // file size
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }
            
        }
        if (isset($data->associate)) {
            foreach ($data->associate as $key => $value) {
                if ($value['name']){
                    $add = PWDAssociation::updateOrCreate(
                        [
                            'wpaf_id' => $data->id,//senior id
                            'id' => $key,//associate id
                        ],
                        [
                            'wpo_organization' => $value['name'],// org name
                            'wpo_contact_person' => $value['person'],// org address
                            'wpo_office_address' => $value['address'], // org address
                            'wpo_contact_number' => $value['number'], // number
                            'wpo_is_active' => 1, // is active
                        ]
                    );
                }
            }
        }
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
            1 =>"cit_fullname",
            2 =>"cit_full_address",   
            3 =>"cit_age",   
            4 =>"wpaf_application_type",   
            5 =>"barangay_pwd_no",   
            6 =>"wptod_id",   
            7 =>"wpaf_is_active",   
        );
        $sql = $this->select('welfare_pwd_application_form.*','cit_fullname','cit_full_address','cit_age', DB::raw("IF(wpaf_application_type = 1, 'Renewal', 'New Applicant') AS apply_type"),'wptod_description')->leftJoin('citizens', 'citizens.id', '=', 'welfare_pwd_application_form.cit_id')->leftJoin('welfare_pwd_type_of_disability', 'welfare_pwd_type_of_disability.id', '=', 'welfare_pwd_application_form.wptod_id');
        if(!empty($q) && isset($q)){
            $sql = $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(cit_full_address)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(cit_age)'),'like',"%".strtolower($q)."%");
                        // $query->orWhere(DB::raw('LOWER(apply_type)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wpaf_pwd_id_number)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wptod_description)'),'like',"%".strtolower($q)."%");
                    });
        }
       /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('welfare_pwd_application_form.id','DESC');
       /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
       /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        
        $data=$sql->get();
        $firstdata = $this->find(1);
        if ($firstdata->wpaf_control_no === 'Test Data') {
            $data = $data->except(1);
            $data_cnt=$sql->count()-1;
    }
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function employ_status() 
    { 
        return $this->belongsTo(EmploymentStatus::class, 'wpsoe_id', 'id'); 
    }
    public function getEmployStatus(){
            $data = [];
            $brgy = EmploymentStatus::where('wpsoe_is_active', 1)->get();
            foreach ($brgy as $key => $value) {
                $data += [$value->id => $value->wpsoe_description];
            }
            return $data;
    }
    public function disability() 
    { 
        return $this->belongsTo(TypeDisability::class, 'wptod_id', 'id'); 
    }
    public function getDisability(){
            $data = [];
            $brgy = TypeDisability::where('wptod_is_active', 1)->get();
            foreach ($brgy as $key => $value) {
                $data += [$value->id => $value->wptod_description];
            }
            return $data;
    }
    public function employ_category() 
    { 
        return $this->belongsTo(EmploymentCategory::class, 'wpcoe_id', 'id'); 
    }
    public function getEmployCategory(){
        $data = [];
        $brgy = EmploymentCategory::where('wpcoe_is_active', 1)->get();
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->wpcoe_description];
        }
        return $data;
    }
    public function employ_type() 
    { 
        return $this->belongsTo(EmploymentType::class, 'wptoe_id', 'id'); 
    }
    public function getEmployType(){
        $data = [];
        $brgy = EmploymentType::where('wptoe_is_active', 1)->get();
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->wptoe_description];
        }
        return $data;
    }
    public function occupation() 
    { 
        return $this->belongsTo(TypeOccupation::class, 'wptoo_id', 'id'); 
    }
    public function getOccupation(){
        $data = [];
        $brgy = TypeOccupation::where('wptoo_is_active', 1)->get();
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->wptoo_description];
        }
        return $data;
    }
    public function cause_inborn() 
    { 
        return $this->belongsTo(CauseDisability::class, 'wpcodi_id', 'id'); 
    }
    public function getCauseInborn(){
        $data = [];
        $brgy = CauseDisability::where('wpcodi_is_active', 1)->get();
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->wpcodi_description];
        }
        return $data;
    }
    public function cause_acquire() 
    { 
        return $this->belongsTo(CauseDisabilityAquire::class, 'wpcoda_id', 'id'); 
    }
    public function getCauseAcquire(){
        $data = [];
        $brgy = CauseDisabilityAquire::where('wpcoda_is_active', 1)->get();
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->wpcoda_description];
        }
        return $data;
    }
    public function getBrgyData($id)
    {
        return Barangay::find($id);
        
    }
}
