<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PsicSection extends Model
{
     public function updateData($id,$columns){
        return DB::table('psic_sections')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('psic_sections')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function divisioncode($id)
	{
		return DB::table('psic_divisions')->select('id','division_description')->where('division_status','1')->where('section_id','=',$id)->get();
	}
    public function getSections()
    {
        return DB::table('psic_sections')->select('*')->get();
    }
}
