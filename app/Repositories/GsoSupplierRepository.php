<?php

namespace App\Repositories;

use App\Interfaces\GsoSupplierRepositoryInterface;
use App\Models\GsoSupplier;
use App\Models\GsoProductLine;
use App\Models\GsoSupplierContactPerson;
use App\Models\GsoSupplierProductLine;
use App\Models\Barangay;
use App\Models\FileUpload;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgExpandedVatableTax;
use App\Models\CboPayee;

class GsoSupplierRepository implements GsoSupplierRepositoryInterface 
{
    public function getAll() 
    {
        return GsoSupplier::all();
    }

    public function find($id) 
    {
        return GsoSupplier::findOrFail($id);
    }
    public function lastId() 
    {
        return GsoSupplier::lastId();
    }    
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoSupplier::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoSupplier::where(['code' => $code])->count();
    }

    public function create(array $details, $product_lines, $timestamp, $user) 
    {
        $supplier = GsoSupplier::create($details);

        $payee = CboPayee::where('scp_id' , $supplier->id)->get();
        if ($payee->count() > 0) {
            $payeeDetails = array(
                'paye_type' => 2,
                'scp_id' => $supplier->id,
                'paye_name' => $details['business_name'],
                'paye_address_lotno' => $details['house_lot_no'],
                'paye_address_street' => $details['street_name'],
                'paye_address_subdivision' => $details['subdivision'],
                'paye_full_address' => $details['address'],
                'brgy_code' => $details['barangay_id'],
                'paye_telephone_no' => $details['telephone_no'],
                'paye_mobile_no' => $details['mobile_no'],
                'paye_email_address' => $details['email_address'],
                'paye_fax_no' => $details['fax_no'],
                'paye_tin_no' => $details['tin_no'],
                'updated_at' => $details['created_at'],
                'paye_modified_by' => $details['created_by']
            );
            CboPayee::whereId($payee->first()->id)->update($payeeDetails);
        } else {
            $payeeDetails = array(
                'paye_type' => 2,
                'scp_id' => $supplier->id,
                'paye_name' => $details['business_name'],
                'paye_address_lotno' => $details['house_lot_no'],
                'paye_address_street' => $details['street_name'],
                'paye_address_subdivision' => $details['subdivision'],
                'paye_full_address' => $details['address'],
                'brgy_code' => $details['barangay_id'],
                'paye_telephone_no' => $details['telephone_no'],
                'paye_mobile_no' => $details['mobile_no'],
                'paye_email_address' => $details['email_address'],
                'paye_fax_no' => $details['fax_no'],
                'paye_tin_no' => $details['tin_no'],
                'created_at' => $details['created_at'],
                'paye_generated_by' => $details['created_by']
            );
            $payee = CboPayee::create($payeeDetails);
            GsoSupplier::whereId($supplier->id)->update(['payee_id' => $payee->id]);
        }

        GsoSupplier::whereId($id)->update($newDetails);

        if (!empty($product_lines)) {
            foreach ($product_lines as $product_line) {
                $products = GsoSupplierProductLine::create([
                    'supplier_id' => $supplier->id,
                    'product_line_id' => $product_line,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        }

        return $supplier;
    }

    public function update($id, array $newDetails, $product_lines, $timestamp, $user) 
    {
        $supplier = GsoSupplier::whereId($id)->update($newDetails);

        $payee = CboPayee::where('scp_id' , $id)->get();
        if ($payee->count() > 0) {
            $payeeDetails = array(
                'paye_type' => 2,
                'scp_id' => $id,
                'paye_name' => $newDetails['business_name'],
                'paye_address_lotno' => $newDetails['house_lot_no'],
                'paye_address_street' => $newDetails['street_name'],
                'paye_address_subdivision' => $newDetails['subdivision'],
                'paye_full_address' => $newDetails['address'],
                'brgy_code' => $newDetails['barangay_id'],
                'paye_telephone_no' => $newDetails['telephone_no'],
                'paye_mobile_no' => $newDetails['mobile_no'],
                'paye_email_address' => $newDetails['email_address'] ,
                'paye_fax_no' => $newDetails['fax_no'],
                'paye_tin_no' => $newDetails['tin_no'],
                'updated_at' => $newDetails['created_at'],
                'paye_modified_by' => $newDetails['created_by']
            );
            CboPayee::whereId($payee->first()->id)->update($payeeDetails);
        } else {
            $payeeDetails = array(
                'paye_type' => 2,
                'scp_id' => $id,
                'paye_name' => $newDetails['business_name'],
                'paye_address_lotno' => $newDetails['house_lot_no'],
                'paye_address_street' => $newDetails['street_name'],
                'paye_address_subdivision' => $newDetails['subdivision'],
                'paye_full_address' => $newDetails['address'],
                'brgy_code' => $newDetails['barangay_id'],
                'paye_telephone_no' => $newDetails['telephone_no'],
                'paye_mobile_no' => $newDetails['mobile_no'],
                'paye_email_address' => $newDetails['email_address'],
                'paye_fax_no' => $newDetails['fax_no'],
                'paye_tin_no' => $newDetails['tin_no'],
                'created_at' => $newDetails['created_at'],
                'paye_generated_by' => $newDetails['created_by']
            );
            $payee = CboPayee::create($payeeDetails);
            GsoSupplier::whereId($id)->update(['payee_id' => $payee->id]);
        }

        GsoSupplierProductLine::where('supplier_id', $id)->update(['updated_at' => $timestamp, 'updated_by' => $user, 'is_active' => 0]);
        if (!empty($product_lines)) {
            foreach ($product_lines as $product_line) {
                $products = GsoSupplierProductLine::where(['supplier_id' => $id, 'product_line_id' => $product_line])->get();
                if ($products->count() > 0) {
                    $products = GsoSupplierProductLine::find($products->first()->id);
                    $products->updated_at = $timestamp;
                    $products->updated_by = $user;
                    $products->is_active = 1;
                    $products->update();
                } else {
                    $products = GsoSupplierProductLine::create([
                        'supplier_id' => $id,
                        'product_line_id' => $product_line,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        return $supplier;
    }

    public function toggleUpdate($id, array $newDetails)
    {
        return GsoSupplier::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_suppliers.id',
            1 => 'gso_suppliers.code',
            2 => 'gso_suppliers.branch_name',
            3 => 'gso_suppliers.business_name',
            6 => 'gso_suppliers.address'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_suppliers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoSupplier::select([
            '*',
            'gso_suppliers.id as supId',
            'gso_suppliers.code as supCode',
            'gso_suppliers.branch_name as supBranch',
            'gso_suppliers.business_name as supBusiness',
            'gso_suppliers.telephone_no as supTelno',
            'gso_suppliers.mobile_no as supMobile',
            'gso_suppliers.address as supAddress',
            'gso_suppliers.remarks as supRemarks',
            'gso_suppliers.updated_at as supUpdatedAt',
            'gso_suppliers.created_at as supCreatedAt',
            'gso_suppliers.is_active as supStatus'
        ])
        // ->leftJoin('gso_suppliers_product_lines', function($join)
        // {
        //     $join->on('gso_suppliers_product_lines.supplier_id', '=', 'gso_suppliers.id');
        // })
        // ->leftJoin('gso_product_lines', function($join)
        // {
        //     $join->on('gso_product_lines.id', '=', 'gso_suppliers_product_lines.product_line_id');
        // })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_suppliers.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.branch_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.telephone_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.mobile_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.email_address', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.address', 'like', '%' . $keywords . '%');
                // ->orWhere('gso_product_lines.description', 'like', '%' . $keywords . '%');
            }
        })
        // ->groupBy('gso_suppliers.id')
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function getProductLine($supplier)
    {
        $res = GsoSupplierProductLine::
        with([
            'product_line' => function($q1) {
                $q1->select([
                    'gso_product_lines.id', 'gso_product_lines.description'
                ])
                ->where('gso_product_lines.is_active', 1);
            }
        ])
        ->where(['supplier_id' => $supplier, 'is_active' => 1])->get();

        $productLines = array();
        foreach ($res as $r) {
            if (!empty($r->product_line->description)) {
                $productLines[] = $r->product_line->description;
            }
        }

        return implode(', ', $productLines);
    }

    public function allBarangays()
    {
        return (new Barangay)->allBarangays();
    }

    public function allProductLines()
    {
        return (new GsoProductLine)->allProductLines();
    }

    public function upload_listItems($request, $supplier)
    {   
        $columns = array( 
            0 => 'name',
            1 => 'type',
            2 => 'size'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'name' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = FileUpload::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('type', 'like', '%' . $keywords . '%')
                ->orWhere('size', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['category' => 'suppliers', 'category_id' => $supplier])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function contact_listItems($request, $supplier)
    {   
        $columns = array( 
            0 => 'contact_person',
            1 => 'telephone_no',
            2 => 'mobile_no',
            3 => 'email_address'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'contact_person' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoSupplierContactPerson::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('contact_person', 'like', '%' . $keywords . '%')
                ->orWhere('telephone_no', 'like', '%' . $keywords . '%')
                ->orWhere('mobile_no', 'like', '%' . $keywords . '%')
                ->orWhere('email_address', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['supplier_id' => $supplier])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function generate_code()
    {
        $year  = date('Y'); 
        $count = GsoSupplier::whereYear('created_at', '=', $year)->count();
        $code  = $year.'-';

        if($count < 9) {
            $code .= '0000' . ($count + 1);
        } else if($count < 99) {
            $code .= '000' . ($count + 1);
        } else if($count < 999) {
            $code .= '00' . ($count + 1);
        } else if($count < 9999) {
            $code .= '0' . ($count + 1);
        } else {
            $code .= ($count + 1);
        }
        return $code;
    }

    public function delete($id)
    {
        FileUpload::destroy($id);
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function findLines($supplierId)
    {
        $res = GsoSupplierProductLine::where(['supplier_id' => $supplierId, 'is_active' => 1])->get();        
        return $res->map(function($line) {
            return  $line->product_line_id;
        });
    }

    public function findContacts($supplierId)
    {
        return GsoSupplierContactPerson::select(['*'])
        ->where('supplier_id', $supplierId)
        ->get();
    }

    public function validateContactPerson($departmentId, $contact_person, $id = '') 
    {   
        if ($id !== '') {
            return GsoSupplierContactPerson::where(['supplier_id' => $departmentId, 'contact_person' => $contact_person])->where('id', '!=', $id)->count();
        }
        return GsoSupplierContactPerson::where(['supplier_id' => $departmentId, 'contact_person' => $contact_person])->count();
    }

    public function createContactPerson(array $details) 
    {
        return GsoSupplierContactPerson::create($details);
    }

    public function updateContactPerson($id, array $newDetails) 
    {
        return GsoSupplierContactPerson::whereId($id)->update($newDetails);
    }

    public function findContactPerson($id) 
    {
        return GsoSupplierContactPerson::findOrFail($id);
    }

    public function allEWT()
    {
        return (new AcctgExpandedWithholdingTax)->allEWT('', 1);
    }

    public function allEVAT()
    {
        return (new AcctgExpandedVatableTax)->allEVAT();
    }
}
