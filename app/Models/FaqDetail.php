<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqDetail extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'faqs_details';
    
    public $timestamps = false;

    public function faq()
    {
        return $this->belongsTo('App\Models\Faq', 'faq_id', 'id');
    }
}
