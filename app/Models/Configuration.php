<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Configuration extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
      return DB::table('configurations')->where('id',$id)->update($columns);
  }
  public function addData($postdata){
      return DB::table('configurations')->insert($postdata);
  }
  public function addSystemData($postdata){
      return DB::table('configurations')->insert($postdata);
  }

}
