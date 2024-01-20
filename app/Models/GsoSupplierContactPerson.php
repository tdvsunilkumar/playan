<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoSupplierContactPerson extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_suppliers_contact_persons';
    
    public $timestamps = false;

    public function supplier()
    {
        return $this->belongsTo('App\Models\GsoSupplier', 'supplier_id', 'id');
    }
    public function supDataById($id)
    {
        $suppliers = self::where('id',$id)->orderBy('id', 'asc')->first();
        $suppliers['barangay_id']=$suppliers->supplier->barangay_id;
        return $suppliers;
    }
    public function allSupplier($vars = '')
    {
        $suppliers = self::where('is_active', 1)->orderBy('id', 'asc')->get();

        $suppl = array();
        if (!empty($vars)) {
            $suppl[] = array('' => 'select a '.$vars);
        } else {
            $suppl[] = array('' => 'select an Supplier');
        }
        foreach ($suppliers as $supplier1) {
            $fullname = $supplier1->contact_person." - ".$supplier1->supplier->branch_name;
            $suppl[] = array(
                $supplier1->id => $fullname
            );
        }

        $suppliers = array();
        foreach($suppl as $sup) {
            foreach($sup as $key => $val) {
                $suppliers[$key] = $val;
            }
        }

        return $suppliers;
    }
    
}
