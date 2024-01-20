<?php

namespace App\Repositories;

use App\Interfaces\InquiriesByArpNoInterface;
use App\Models\RptProperty;
use App\Models\RptPropertyDetails;
use App\Models\AcctgDepartment;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\AcctgDepartmentDivision;
use App\Models\GsoItem;
use App\Models\RptBuildingKind;
use App\Models\Barangay;
use DB;

class InquiriesByArpNoRepository implements InquiriesByArpNoInterface 
{
    public function find($id) 
    {
        $gso_issuance= RptProperty::findOrFail($id);
        $gso_issuance['requestor']=$gso_issuance->issue_req_by->fullname;
        $gso_issuance['requestor_position']=$gso_issuance->issue_req_position->description;
        $gso_issuance['approver']=$gso_issuance->approver->fullname;
        $gso_issuance['approver_position']=$gso_issuance->approver_position->description;
        $gso_issuance['departments']=$gso_issuance->acctg_departments->name;
        $gso_issuance['division']=$gso_issuance->acctg_departments_divisions->name;
        return $gso_issuance;
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return RptProperty::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return RptProperty::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        RptProperty::create($details);
        $gsoInss=RptProperty::orderBy('id','DESC')->first();
        return $gsoInss;
    }
    public function createIssuanceDetails(array $details) 
    {
        RptPropertyDetails::create($details);
    }
    

    public function update($id, array $newDetails) 
    {
        return RptProperty::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'td_no',
            2 => 'propertyOwner.full_name',
            3 => 'rp_section_no',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('query');


        $res = RptProperty::select([
                '*',
                'rpt_properties.id as ID',
                'rpt_properties.rp_tax_declaration_no as td_no',
				'propertyOwner.full_name as full_name',
                'propertyKindDetails.pk_description as kind',
                'rpt_properties.rp_assessed_value as value',
                'rpt_properties.dist_code as dist_code',
                'barangay.brgy_code as brgy_code',
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_class',
                'rpt_properties.rp_suffix as rp_suffix',
                'class.pc_class_code as class'
                
            ])
            ->leftJoin('clients as propertyOwner', function ($join) {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })

            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.pc_class_code');
            })
        ->where(function($q) use ($f_keywords) {
            if (!empty($f_keywords)) {
                $q->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $f_keywords . '%');
            }
        })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")   
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsByTct($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'propertyOwner.rpo_custom_last_name',
            2 => 'propertyOwner.rpo_first_name',
            3 => 'propertyOwner.rpo_middle_name',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('query');

        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no as td_no',
				'propertyOwner.full_name as full_name',				
                'propertyOwner.rpo_custom_last_name as last_name', 
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rp_assessed_value as value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'class.pc_class_code as class',
                'rpt_properties.rp_oct_tct_cloa_no as tct_no', 
            ])
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.rp_class');
            })
        
        ->where(function($q) use ($f_keywords) {
            if (!empty($f_keywords)) {
                $q->where('rpt_properties.rp_oct_tct_cloa_no', 'like', '%' . $f_keywords . '%');
            }
        })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")    
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->where('propertyKindDetails.pk_description', "Land")
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsByCct($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'propertyOwner.rp_tax_declaration_no',
            2 => 'customername',
            3 => 'propertyOwner.brgy_code',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('query');

        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no as td_no',
				'propertyOwner.full_name as full_name',
                'propertyOwner.rpo_custom_last_name as last_name', 
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rpb_assessed_value as value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'rpt_properties.rp_pin_declaration_no',
                'class.pc_class_code as class',
                'rpt_properties.rp_building_cct_no as cct_no',
                'rpt_properties.rp_building_unit_no as unit_no', 
            ],DB::raw("CASE 
            WHEN propertyOwner.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(propertyOwner.rpo_middle_name,''),' ',COALESCE(propertyOwner.rpo_custom_last_name,''),', ',COALESCE(propertyOwner.suffix,'')))
            WHEN propertyOwner.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(propertyOwner.rpo_first_name,''),' ',COALESCE(propertyOwner.rpo_custom_last_name,''),', ',COALESCE(propertyOwner.suffix,'')))
            WHEN propertyOwner.suffix IS NULL THEN TRIM(CONCAT(COALESCE(propertyOwner.rpo_first_name,''),' ',COALESCE(propertyOwner.rpo_middle_name,''),' ',COALESCE(propertyOwner.rpo_custom_last_name,'')))
            WHEN propertyOwner.rpo_first_name IS NULL AND propertyOwner.rpo_middle_name IS NULL AND propertyOwner.suffix IS NULL THEN COALESCE(propertyOwner.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(propertyOwner.rpo_first_name,''),' ',COALESCE(propertyOwner.rpo_middle_name,''),' ',COALESCE(propertyOwner.rpo_custom_last_name,''),', ',COALESCE(propertyOwner.suffix,''))) END as customername
            "))
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.rp_class');
            })
        
        ->where(function($q) use ($f_keywords) {
            if (!empty($f_keywords)) {
                $q->where('rpt_properties.rp_building_cct_no', 'like', '%' . $f_keywords . '%');
            }
        })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")    
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->where('propertyKindDetails.pk_description', "Building")
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsByOwn($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'propertyOwner.rpo_custom_last_name',
            2 => 'propertyOwner.rpo_first_name',
            3 => 'propertyOwner.rpo_middle_name',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('query');

        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no as td_no', 
				'propertyOwner.full_name',
                'propertyOwner.rpo_custom_last_name as last_name', 
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rpb_assessed_value', 
                'rpt_properties.rp_assessed_value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'class.pc_class_code as class',
                'rpt_properties.rp_app_assessor_lot_no as lot_no'
            ])
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.rp_class');
            })
        
        ->where(function($q) use ($f_keywords) {
            if (!empty($f_keywords)) {
                $q->where(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$f_keywords}%")
                ->orWhere('propertyOwner.full_name', 'like', '%' . $f_keywords . '%');
            }
        })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")   
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsByBuildKind($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'propertyOwner.rpo_custom_last_name',
            2 => 'propertyOwner.rpo_first_name',
            3 => 'propertyOwner.rpo_middle_name',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('kind_id');

        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no as td_no', 
				'propertyOwner.full_name as full_name',
                'propertyOwner.rpo_custom_last_name as last_name', 
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rpb_assessed_value as value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'class.pc_class_code as class',
                'rpt_properties.rp_building_cct_no as cct_no',
                'rpt_properties.rp_building_unit_no as unit_no', 
            ])
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.rp_class');
            })
            ->leftJoin('rpt_building_kinds as rptBuildingKindDetails', function($join)
            {
                $join->on('rptBuildingKindDetails.id', '=', 'rpt_properties.bk_building_kind_code');
            })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")    
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->where('rpt_properties.bk_building_kind_code', $f_keywords)
        ->where('propertyKindDetails.pk_description', "Building")
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsByServey($request)
    {   
        $columns = array( 
            0 => 'rpt_properties.id',
            1 => 'propertyOwner.rpo_custom_last_name',
            2 => 'propertyOwner.rpo_first_name',
            3 => 'propertyOwner.rpo_middle_name',
            4 => 'propertyKindDetails.pk_description',
            5 => 'class.pc_class_code',
            6 => 'rpt_properties.rpb_assessed_value',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $f_keywords  = $request->get('query');

        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no',
				'propertyOwner.full_name as full_name',
                'propertyOwner.rpo_custom_last_name as last_name', 
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rp_assessed_value as value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'class.pc_class_code as class',
                'rpt_properties.rp_oct_tct_cloa_no as tct_no', 
                'rpt_properties.rp_cadastral_lot_no as survey_no', 
            ])
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            // ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            // {
            //     $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            // })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'rpt_properties.rp_class');
            })
        
        ->where(function($q) use ($f_keywords) {
            if (!empty($f_keywords)) {
                $q->where('rpt_properties.rp_cadastral_lot_no', 'like', '%' . $f_keywords . '%');
            }
        })
        ->where(function($p) use ($keywords) {
            if (!empty($keywords)) {
                $p->where('rpt_properties.rp_tax_declaration_no', 'like', '%' . $keywords . '%')
                ->orWhere(DB::raw("CONCAT(propertyOwner.rpo_first_name, ' ', COALESCE(propertyOwner.rpo_middle_name, ''), ' ', propertyOwner.rpo_custom_last_name)"), 'LIKE', "%{$keywords}%")    
                ->orWhere('propertyKindDetails.pk_description', 'like', '%' . $keywords . '%')
                ->orWhere('class.pc_class_code', 'like', '%' . $keywords . '%')
				->orWhere('propertyOwner.full_name', 'like', '%' . $keywords . '%')
                ->orWhere('rpt_properties.rpb_assessed_value', 'like', '%' . $keywords . '%');
            }
        })
        ->where('propertyKindDetails.pk_description', "Land")
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allBuildKinds()
    {
        return (new RptBuildingKind)->allBuildKinds();
    }

    public function listItemsById($id)
    {   
        $res = RptProperty::select([
                '*', 
                'rpt_properties.id as ID', 
                'rpt_properties.rp_tax_declaration_no as td_no', 
                'propertyOwner.suffix as suffix',
				'propertyOwner.full_name as full_name',
				'propertyOwner.rpo_custom_last_name as last_name',
                'propertyOwner.rpo_first_name as first_name', 
                'propertyOwner.rpo_middle_name as middle_name', 
                'propertyOwner.rpo_address_house_lot_no as rpo_address_house_lot_no', 
                'propertyOwner.rpo_address_street_name as rpo_address_street_name', 
                'propertyOwner.rpo_address_subdivision as rpo_address_subdivision', 
                'propertyOwner.p_telephone_no as own_tel_no',
                'propertyOwner.p_barangay_id_no as propertyOwner_barangay_id',
                'propertyAdmin.suffix as admin_suffix',
				'propertyAdmin.full_name as full_name',
                'propertyAdmin.rpo_custom_last_name as admin_last_name', 
                'propertyAdmin.rpo_first_name as admin_first_name', 
                'propertyAdmin.rpo_middle_name as admin_middle_name', 
                'propertyAdmin.rpo_address_house_lot_no as admin_rpo_address_house_lot_no', 
                'propertyAdmin.rpo_address_street_name as admin_rpo_address_street_name', 
                'propertyAdmin.rpo_address_subdivision as admin_rpo_address_subdivision', 
                'propertyAdmin.p_telephone_no as admin_own_tel_no',
                'propertyAdmin.p_tin_no as admin_p_tin_no',
                'propertyAdmin.p_barangay_id_no as admin_barangay_id',
                'propertyKindDetails.pk_description as kind', 
                'rpt_properties.rpb_assessed_value as value', 
                'rpt_properties.dist_code as dist_code', 
                'barangay.brgy_code as brgy_code', 
                'rpt_properties.rp_section_no as rp_section_no',
                'rpt_properties.rp_pin_no as rp_pin_no',
                'rpt_properties.rp_suffix as rp_suffix',
                'rpt_properties.rp_property_code',
                'class.pc_class_code as class'
            ])
            ->leftJoin('clients as propertyAdmin', function($join)
            {
                $join->on('propertyAdmin.id', '=', 'rpt_properties.rp_administrator_code');
            })
            ->leftJoin('clients as propertyOwner', function($join)
            {
                $join->on('propertyOwner.id', '=', 'rpt_properties.rpo_code');
            })
            ->leftJoin('rpt_property_kinds as propertyKindDetails', function($join)
            {
                $join->on('propertyKindDetails.id', '=', 'rpt_properties.pk_id');
            })
            ->leftJoin('barangays as barangay', function($join)
            {
                $join->on('barangay.id', '=', 'rpt_properties.brgy_code_id');
            })
            ->leftJoin('rpt_property_appraisals as landAppraisals', function($join)
            {
                $join->on('landAppraisals.rp_code', '=', 'rpt_properties.id');
            })
            ->leftJoin('rpt_property_classes as class', function($join)
            {
                $join->on('class.id', '=', 'landAppraisals.pc_class_code');
            })
        ->where('rpt_properties.id',$id)
        ->first();

        return $res;
    }
    public function listBarangayId($id)
    {   
        $res = Barangay::select([
                '*', 
                'barangays.id',
                'pr.reg_region',
                'pp.prov_desc',
                'pm.mun_desc'
                
            ])
           ->leftJoin('profile_regions as pr', function($join)
            {
                $join->on('pr.id', '=', 'barangays.reg_no');
            })
           ->leftJoin('profile_provinces as pp', function($join)
            {
                $join->on('pp.id', '=', 'barangays.prov_no');
            })
           ->leftJoin('profile_municipalities as pm', function($join)
            {
                $join->on('pm.id', '=', 'barangays.mun_no');
            })
            
        ->where('barangays.id',$id)
        ->first();

        return $res;
    }
    public function listHrDescId($id)
    {   
	//dd($id);
        $res = HrEmployee::select([
                '*', 
                'hr_employees.id',
                'hr_employees.fullname',
                'hd.description','ra.is_sgd'
            ])
           ->leftJoin('hr_designations as hd', function($join)
            {
                $join->on('hd.id', '=', 'hr_employees.hr_designation_id');
            })
           ->leftJoin('rpt_appraisers as ra', function($join)
            {
                $join->on('ra.ra_appraiser_id', '=', 'hr_employees.id');
            })
			
        ->where('hr_employees.id',$id)
        ->first();

        return $res;
    }
	
   public function CreatelistHrDescId($id)
    {   
	//dd($id);
        $res = HrEmployee::select([
                '*', 
                'hr_employees.id',
                'hr_employees.fullname',
                'hd.description'
            ])
           ->leftJoin('hr_designations as hd', function($join)
            {
                $join->on('hd.id', '=', 'hr_employees.hr_designation_id');
            })
			
        ->where('hr_employees.user_id',$id)
        ->first();

        return $res;
    }
    public function listClassId($id)
    {   
        $res = HrEmployee::select([
                '*', 
                'rpt_property_classes.id',
                'rpt_property_classes.pc_class_description',
                'pc_class_description.pc_class_code'
            ])
        ->where('rpt_property_classes.id',$id)
        ->first();

        return $res;
    }
    public function listBuildingKind($id)
    {   
        $res = RptBuildingKind::select([
                '*', 
                'rpt_building_kinds.id',
                'rpt_building_kinds.bk_building_kind_code',
                'rpt_building_kinds.bk_building_kind_desc'
            ])
        ->where('rpt_building_kinds.id',$id)
        ->first();

        return $res;
    }
    public function listSubClassId($id)
    {   
        $res = HrEmployee::select([
                '*', 
                'hr_employees.id',
                'hr_employees.fullname',
                'hd.description'
            ])
           ->leftJoin('hr_designations as hd', function($join)
            {
                $join->on('hd.id', '=', 'hr_employees.hr_designation_id');
            })
        ->where('rpt_property_classes.id',$id)
        ->first();

        return $res;
    }
    public function issuanceItemDetails($request)
    {   
        $columns = array( 
            0 => 'gso_issuance_details.id',
            1 => 'gso_issuance_details.item_code',
            2 => 'gso_issuance_details.item_name',
            3 => 'gso_issuance_details.item_desc',
            4 => 'gso_issuance_details.unit_code',   
            5 => 'gso_issuance.issue_control_no',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];


        $res = RptPropertyDetails::select([
                '*', 
                'gso_issuance.issue_control_no as control_no', 
                'gso_issuance.issue_date as issuance_date', 
            ])
            ->with([
                'gso_issuance' =>  function($q) { 
                    $q->select([
                        'gso_issuance.id', 'gso_issuance.issue_control_no']);
                }
            ])
            ->leftJoin('gso_issuance', function($join)
            {
                $join->on('gso_issuance.id', '=', 'gso_issuance_details.issue_id');
            })
        
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuance_details.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_agl_code', 'like', '%' . $keywords . '%')                
                ->orWhere('gso_issuance_details.item_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_desc', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.unit_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_issuance_details.issue_id',$request->get('issuance_id'))
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function newlistItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'gso_items.code',
            2 => 'gso_items.name',
            3 => 'gso_items.description',
            4 => 'gso_items.weighted_cost',   
            5 => 'gso_items.latest_cost', 
            6 => 'gso_items.quantity_inventory',
            7 => 'acctg_account_general_ledgers.code',
            8 => 'acctg_account_general_ledgers.description',
            9 => 'gso_item_types.code',
            10 => 'gso_item_types.description',
            11 => 'gso_item_categories.code',
            12 => 'gso_item_categories.description',
            13 => 'gso_unit_of_measurements.code',
            14 => 'gso_purchase_types.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
                '*', 
                'gso_items.id as itemId', 
                'gso_items.code as itemCode', 
                'gso_items.name as itemName',
                'gso_items.description as itemDesc',
                'gso_items.is_active as itemStatus',
                'gso_items.created_at as itemCreatedAt',
                'gso_items.updated_at as itemUpdatedAt',
                'gso_items.quantity_inventory as itemInventory',
                'gso_items.weighted_cost as itemWeightedCost',
                'gso_items.latest_cost as itemLatestCost',
                'gso_items.latest_cost_date as itemLatestCostDate',
                'gso_item_categories.code as catCode',
                'gso_unit_of_measurements.code as itemUOM'
            ])
            ->with([
                'gl_account' =>  function($q) { 
                    $q->select([
                        'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
                },
                'type' =>  function($q) { 
                    $q->select([
                        'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
                },
                'category' =>  function($q) { 
                    $q->select([
                        'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
                },
                'uom' =>  function($q) { 
                    $q->select([
                        'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
                },
                'pur_type' =>  function($q) { 
                    $q->select([
                        'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
                }
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
            })
            ->leftJoin('gso_item_types', function($join)
            {
                $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
            })
            ->leftJoin('gso_item_categories', function($join)
            {
                $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
            })
            ->leftJoin('gso_unit_of_measurements', function($join)
            {
                $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
            })
            ->leftJoin('gso_purchase_types', function($join)
            {
                $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
            })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function count() 
    {
        return RptProperty::count();
    }

    public function findBy($column, $data)
    {
        return RptProperty::where($column, $data)->first();
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function reload_employees($department)
    {
        return (new HrEmployee)->reload_employees($department);
    }

    public function reload_divisions($department)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department);
    }

    public function reload_designation($employee)
    {
        return (new HrDesignation)->find((new HrEmployee)->find($employee)->hr_designation_id);
    }
    public function findGsoItem($item_id)
    {
        return GsoItem::findOrFail($item_id);
    }
    public function lastIssueItemDet()
    {
        $data=RptPropertyDetails::orderBy('id','DESC')->first();
        return $data;
    }
    
    
}