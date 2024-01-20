<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'faqs';
    
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo('App\Models\MenuGroup', 'group_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
