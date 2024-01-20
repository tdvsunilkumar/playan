<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseRequestType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_purchase_request_types';
    
    public $timestamps = false;

    public function allRequestTypes($vars = '')
    {
        $request_types = self::where(['is_hidden' => 0, 'is_active' => 1])->orderBy('id', 'asc')->get();
    
        $reqs = array();
        if (!empty($vars)) {
            $reqs[] = array('' => 'select a '.$vars);
        } else {
            $reqs[] = array('' => 'select a request type');
        }
        foreach ($request_types as $request_type) {
            $reqs[] = array(
                $request_type->id => $request_type->description
            );
        }

        $request_types = array();
        foreach($reqs as $req) {
            foreach($req as $key => $val) {
                $request_types[$key] = $val;
            }
        }

        return $request_types;
    }
}
