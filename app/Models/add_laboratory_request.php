<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class add_laboratory_request extends Model
{
    public function updateServiceData($id,$columns){
        return DB::table('add_laboratory_request')->where('id',$id)->update($columns);
      }
      public function addServiceData($postdata){
          DB::table('add_laboratory_request')->insert($postdata);
          return DB::getPdo()->lastInsertId();
      }
      public function getAddMoreDataServices($id){
        return DB::table('add_laboratory_request')->where('hahc_id',$id)->get();
    }
      
}
