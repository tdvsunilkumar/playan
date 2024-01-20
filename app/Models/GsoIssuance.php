<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoIssuance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_issuances';
    
    public $timestamps = false;

    public function requestor()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'requested_by', 'id');
    }

    public function requestor_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'requested_by_designation', 'id');
    }

    public function issuer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'issued_by', 'id');
    }

    public function issuer_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'issued_by_designation', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'received_by', 'id');
    }

    public function receiver_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'received_by_designation', 'id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public function approver_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'approved_by_designation', 'id');
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
