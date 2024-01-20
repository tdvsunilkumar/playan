<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\SPFamilyComposition;
use App\Models\SocialWelfare\SPFiles;
use DB;
use App\Traits\ModelUpdateCreate;

class SoloParentID extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_solo_parent_application';
    public $timestamps = false;
    protected $guarded = ['id'];

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
    public function getNextNumberAttribute()
    {
        // dd($this->where('wspa_is_renewal',0)->orderBy('id','desc')->get());
        $lastNum = $this->orderBy('id','desc')->first()->wspa_id_number;
        $lastNum = (int)explode('-',$lastNum)[1];
        $lastNum = sprintf('%04d',$lastNum+1);
        return $lastNum;
    }
    public function claimant() 
    { 
        return $this->belongsTo(Citizen::class, 'cit_id', 'id'); 
    }
    public function family() 
    { 
        return $this->hasMany(SPFamilyComposition::class, 'wspa_id', 'id'); 
    } 
    public function files() 
    { 
        return $this->hasMany(SPFiles::class, 'wspa_id', 'id'); 
    } 
    public function familyCount()
    {
        return SPFamilyComposition::all()->count();
    }
    public function updateRelation($data)
    {
        if ($data->type == 'requirement') {
            $assist = SPFiles::find($data->id);
            $assist->fwsc_is_active = $data->status;
            $assist->save();
        }
        elseif($data->type == 'dependent') {
            $assist = SPFamilyComposition::find($data->id);
            $assist->wsfc_is_active = $data->status;
            $assist->save();
        }
    }

    public function addRelation($data)
    {
        if (isset($data->dependent)) {
            foreach ($data->dependent as $key => $value) {
                if ($value['cit_id']){
                    Citizen::updateData($value['cit_id'],$value['data']);
                    $add = SPFamilyComposition::updateOrCreate(
                        [
                            'wspa_id' => $data->id,//assistance id
                            'id' => $key,//dependent id
                        ],
                        [
                            'wsfc_cit' => $value['cit_id'],//dependent name
                            'cit_id' => $data['cit_id'],//Name of Claimant
                            'wsfc_relation' => $value['relation'], // dependant relation
                            'wsfc_occupation' => $value['data']['cit_occupation'], // dependant relation
                            'wsfc_monthly_income' => currency_to_float($value['income']), // dependant relation
                            'wsfc_is_active' => 1, // dependant relation
                            'updated_at' => date('Y-m-d H:i:s'),
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
                if ($file = SPFiles::where([['id',$key],['wspa_id',$data->id],])
                ->orWhere([['req_id',$value['req_id']],['wspa_id',$data->old_id]])
                ->first()){ 
                    $fileName = $file->fwsc_name;
                    $fileType = $file->fwsc_type;
                    $fileSize = $file->fwsc_size;
                    $filePath = $file->fwsc_path;
                }
                if (isset($value['file'])){ 
                    $size = $value['file']->getSize() * .001;
                    $fileSize = round($size,2);
                    $fileName = $value['req_type'].$value['req_id'].'-'.time().'.'.$value['file']->getClientOriginalExtension();
                    $filePath = "uploads/socialwelfare/".$fileName;
                    $fileType = $value['file']->getClientOriginalExtension();
                    $value['file']->move(public_path('uploads/socialwelfare'), $fileName);
                    $fileName = $value['file']->getClientOriginalName();
                }
                    $add = SPFiles::updateOrCreate(
                        [
                            'wspa_id' => $data->id,//assistance id
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
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                // }
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
            3 =>"cit_date_of_birth",   
            3 =>"wspa_id_number",   
            4 =>"wspa_is_active",   
        );
        $sql = $this->select('welfare_solo_parent_application.*','cit_fullname','cit_full_address','cit_age','cit_date_of_birth')->join('citizens', 'citizens.id', '=', 'welfare_solo_parent_application.cit_id');
        if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($query) use($q) {
                            $query->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(cit_full_address)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(cit_age)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(wspa_id_number)'),'like',"%".strtolower($q)."%");
                        });
        }
           /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('welfare_solo_parent_application.id','DESC');

           /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
           /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        $firstdata = $this->find(1);
        if ($firstdata->wspa_occupation === 'Test Data') {
            $data = $data->except(1);
            $data_cnt=$sql->count()-1;
        }
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
