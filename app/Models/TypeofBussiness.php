<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TypeofBussiness extends Model
{
     public function updateData($id,$columns){
        return DB::table('typeof_bussinesses')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('typeof_bussinesses')->insert($postdata);
    }
    public function getTypeofByssiness(){
        return DB::table('typeof_bussinesses')->select('*')->get();
    }
}
