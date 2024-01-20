<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoInventoryAdjustment extends Model
{
    use HasFactory; 
    protected $guarded;


    public function getLastRow(){
        try {
           return Self::orderBy('id', 'DESC')->first();
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function addSingleData($request){
        try {
           return Self::create($request);
         } catch (\Exception $e) {
             return ($e->getMessage());
         }
    }

    public function getAdjustments($hia_no){
        return Self::where('hia_no', $hia_no)->first();
    }

    public function updateData($adj_details_id, $data){
        return Self::where('id', $adj_details_id)->update($data);
    }
    

}
