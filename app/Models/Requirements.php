<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Requirements extends Model
{
    protected $guarded = ['id'];

    public $table = 'requirements';
    
    public $timestamps = false;

    public function updateData($id,$columns){
        return DB::table('requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		 DB::table('requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
	}
	public function getRequirements()
    {
        return DB::table('requirements')->select('*')->get();
    }
}
