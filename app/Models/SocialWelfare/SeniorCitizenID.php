<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\ModelUpdateCreate;

class SeniorCitizenID extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_seniors_citizen_application';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function addData($postdata){
        $this->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
        return $this->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return $this->where('id',$id)->update($columns);
    }

    public function getNextNumberAttribute()
    {
        $lastNum = $this->orderBy('id','desc')->first()->wsca_new_osca_id_no;
        $lastNum = (int)explode('-',$lastNum)[1];
        $lastNum = sprintf('%04d',$lastNum+1);
        return $lastNum;
    }

    public function claimant() 
    { 
        return $this->belongsTo(Citizen::class, 'cit_id', 'id'); 
    }

    public function spouse() 
    { 
        return $this->belongsTo(Citizen::class, 'wsca_name_of_spouse', 'id'); 
    }

    public function family() 
    { 
        return $this->hasMany(SCFamilyComposition::class, 'wsca_id', 'id'); 
    } 
    public function familyCount()
    {
        return SCFamilyComposition::all()->count();
    }
    
    public function files() 
    { 
        return $this->hasMany(SCFiles::class, 'wsca_id', 'id'); 
    } 

    public function associateCount()
    {
        return SCAssociation::all()->count();
    }
    public function residence() 
    { 
        return $this->hasOne(TypeResidency::class, 'id', 'wstor_id'); 
    } 
    public function associate() 
    { 
        return $this->hasMany(SCAssociation::class, 'wsca_id', 'id'); 
    } 
    public function updateRelation($data)
    {
        if ($data->type == 'requirement') {
            $assist = SCFiles::find($data->id);
            $assist->fwsc_is_active = $data->status;
            $assist->save();
        }
        elseif($data->type == 'dependent') {
            $assist = SCFamilyComposition::find($data->id);
            $assist->wsfc_is_active = $data->status;
            $assist->save();
        }
        elseif($data->type == 'associate') {
            $assist = SCAssociation::find($data->id);
            $assist->wsa_is_active = $data->status;
            $assist->save();
        }
    }

    public function addRelation($data)
    {
        if (isset($data->dependent)) {
            foreach ($data->dependent as $key => $value) {
                if ($value['cit_id']){
                    // Citizen::updateData($value['cit_id'],$value['data']);
                    $add = SCFamilyComposition::updateOrCreate(
                        [
                            'wsca_id' => $data->id,//senior id
                            'id' => $key,//dependent id
                        ],
                        [
                            'wsfc_cit' => $value['cit_id'],//dependent name
                            'cit_id' => $data['cit_id'],//Name of Claimant
                            'wsfc_relation' => $value['relation'], // dependant relation
                            'wsfc_monthly_income' => currency_to_float($value['income']), // dependant relation
                            'updated_at' => date('Y-m-d H:i:s'),
                            'wsfc_is_active' => 1
                        ]
                    );
                }
            }
        }
        if (isset($data->require)) {
            foreach ($data->require as $key => $value) {
                $fileName = '';
                $fileType = '';
                $fileSize = '';
                $filePath = '';
                if ( $file = SCFiles::
                    where([['id',$key],['wsca_id',$data->id]])
                    ->orWhere([['req_id',$value['req_id']],['wsca_id',$data->old_id]])
                    ->first()){ 
                    $fileName = $file->fwsc_name;
                    $fileType = $file->fwsc_type;
                    $fileSize = $file->fwsc_size;
                    $filePath = $file->fwsc_path;
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
                $add = SCFiles::updateOrCreate(
                    [
                        'wsca_id' => $data->id,//senior id
                        'id' => $key,//file id
                    ],
                    [
                        'req_id' => $value['req_id'], // req id
                        'req_type' => $value['req_type'], // req id
                        'fwsc_name' => $fileName,//file name
                        'fwsc_type' => $fileType,//file type
                        'fwsc_size' => $fileSize, // file size
                        'fwsc_path' => $filePath,
                        'fwsc_is_active' => 1, // file size
                    ]
                );
            }
            
        }
        if (isset($data->associate)) {
            foreach ($data->associate as $key => $value) {
                if ($value['name']){
                    $add = SCAssociation::updateOrCreate(
                        [
                            'wsca_id' => $data->id,//senior id
                            'id' => $key,//associate id
                        ],
                        [
                            'wsa_association_name' => $value['name'],// name
                            'wsa_assocation_address' => $value['address'],//address
                            'wsa_association_position' => $value['position'], // position
                            'wsa_is_active' => 1, // file size
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
                4 =>"wsca_new_osca_id_no",   
                5 =>"wsca_fscap_id_no",   
                6 =>"wsca_is_active",   
            );
            $sql = $this->select('welfare_seniors_citizen_application.*','cit_fullname','cit_full_address','cit_age')->join('citizens', 'citizens.id', '=', 'welfare_seniors_citizen_application.cit_id');
            if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($query) use($q) {
                            $query->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(cit_full_address)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(cit_age)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(wsca_new_osca_id_no)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(wsca_fscap_id_no)'),'like',"%".strtolower($q)."%");
                        });
            }
           /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
                $sql->orderBy('welfare_seniors_citizen_application.id','DESC');
           /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
           /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            $firstdata = $this->find(1);
            if ($firstdata->wsca_remarks === 'Test Data') {
                $data = $data->except(1);
                $data_cnt=$sql->count()-1;
            }
            return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getAllResidenceType()
    {
        $data = [];
        $type = TypeResidency::where('wstor_is_active',1)->get();
        foreach ($type as $key => $value) {
            $data[$value->id] = $value->wstor_description;
        }
        return $data;
    }
}
