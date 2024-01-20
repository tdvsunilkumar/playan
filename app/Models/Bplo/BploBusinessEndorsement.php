<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bplo\BploBusinessType;
use DB;

class BploBusinessEndorsement extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_business_endorsement';
    
    public $timestamps = false;
    public function create(array $detail) 
    {
        DB::table('bplo_business_endorsement')->insert($detail);
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('bplo_business_endorsement')->where('id',$id)->update($columns);
    }  
    public function updateData($id,array $columns){
        return DB::table('bplo_business_endorsement')->where('id',$id)->update($columns);
    }
    public function updateClient($id,array $columns){
        return DB::table('clients')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
       DB::table('bplo_business_endorsement')->insert($postdata);
       return DB::getPdo()->lastInsertId();
    }
    public function checkExistEndrosMent($busn_id,$bend_year,$endorsing_dept_id){
        return DB::table('bplo_business_endorsement')->select('id')
            ->where('busn_id',(int)$busn_id)
            ->where('bend_year',(int)$bend_year)
            ->where('endorsing_dept_id',(int)$endorsing_dept_id)
            ->first();
    }
}
