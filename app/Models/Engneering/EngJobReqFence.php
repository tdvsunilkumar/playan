<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqFence extends Model
{
    public $table = 'eng_fencing_app';    

    // Bldg Scope
    public function signBldgScope() 
    {
        return $this->hasOne(EngBldgScope::class, 'id', 'ebs_id');
    }
    public function getBldgScopeAttribute()
    {
        return ($this->signBldgScope)? $this->signBldgScope->ebs_description : '';
    }
    public function bldgScopeCheck($check)
    {
        return $this->ebs_id === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }
    
    // client / applicant of job request
    public function client() 
    {
        return $this->hasOne(EngClients::class, 'id', 'p_code');
    }
    // Lot Owner
    public function owner() 
    {
        return $this->hasOne(EngClients::class, 'id', 'efa_owner_id');
    }
    // applicant consultant / building owner
    public function applicantConsultant() 
    { 
        return $this->hasOne(EngClients::class, 'id', 'efa_applicant_consultant_id');
    }

    
    // permit
    public function permit() 
    {
        return $this->hasOne(EngBldgPermitApp::class, 'id', 'ebpa_permit_no');
    }
    
    // employee
    public function employee($column)
    {
        return $this->belongsTo(HrEmployee::class, $column, 'id'); 
    }
    // Project Consultant
    public function consultant() 
    { 
        switch ($this->efa_sign_category) {
            case 1:
                return $this->employee('efa_sign_consultant_id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'efa_sign_consultant_id', 'id'); 
                break;
                
            default:
                return $this->hasOne(ConsultantExternal::class, 'id', 'eca_sign_consultant_id'); 
                break;
        }
    }

    // Project Incharge
    public function incharge() 
    { 
        switch ($this->efa_inspector_category) {
            case 1:
                return $this->employee('efa_inspector_consultant_id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'efa_inspector_consultant_id', 'id'); 
                break;
            
            default:
                return $this->belongsTo(ConsultantExternal::class, 'efa_inspector_consultant_id', 'id'); 
                break;
        }
    }

    // Client details [applicantConsultant,owner,client,consultant,incharge]
    public function taxDetails($client) 
    { 
        $tax_details = (object)[];
        $tax_details->rpo_address_house_lot_no = '';
        $tax_details->rpo_address_street_name = '';
        $tax_details->rpo_address_subdivision = '';
        $tax_details->cashier_batch_no = '';
        $tax_details->or_no = '';
        $tax_details->ctc_place_of_issuance = '';
        $tax_details->created_at = '';
        switch ($client) {
            case 'applicantConsultant':
                $data = EngJobRequest::getTaxcertificatedetails($this->applicantConsultant->id);
                break;
            case 'owner':
                $data = EngJobRequest::getTaxcertificatedetails($this->owner->id);
                break;
            case 'client':
                $data = EngJobRequest::getTaxcertificatedetails($this->client->id);
                break;
            // case 'consultant':
            //     $data = EngJobRequest::getTaxcertificatedetails($this->consultant->id);
            //     break;
            // case 'incharge':
            //     $data = EngJobRequest::getTaxcertificatedetails($this->incharge->id);
            //     break;
            default:
                break;
        }
        if (isset($data)) {
            $tax_details = $data;
        }
        return $tax_details;
    }

    // Building Official
    public function official() 
    { 
        return $this->employee('efa_building_official'); 
    }

    // Assesd fees
    public function linegrade()
    {
        return $this->employee('efa_linegrade_processed_by');
    }
    public function fencing()
    {
        return $this->employee('efa_fencing_processed_by');
    }
    public function electrical()
    {
        return $this->employee('efa_electrical_processed_by');
    }
    public function others()
    {
        return $this->employee('efa_others_processed_by');
    }
    public function total()
    {
        return $this->employee('efa_total_processed_by');
    }

    public function preparedBy($type)
    {
        switch ($type) {
            case 'linegrade':
                return isset($this->linegrade->fullname)?$this->linegrade->fullname:'';
            case 'fencing':
                return isset($this->fencing->fullname)?$this->fencing->fullname:'';
            case 'electrical':
                return isset($this->electrical->fullname)?$this->electrical->fullname:'';
            case 'others':
                return isset($this->others->fullname)?$this->others->fullname:'';
            case 'total':
                return isset($this->total->fullname)?$this->total->fullname:'';
            default:
                return '';
                break;
        }
    }
}