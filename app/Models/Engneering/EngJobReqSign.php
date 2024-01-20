<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqSign extends Model
{
    public $table = 'eng_sign_app';

    // permit
    public function permit() 
    {
        return $this->hasOne(EngBldgPermitApp::class, 'id', 'ebpa_permit_no');
    }
    
    // Type of Occupancy
    public function bldgOccupancyType() 
    {
        return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebot_id');
    }
    
    public function getBldgOccupancyTypeAttribute()
    {
        return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description : '';
    }
    // Display Type
    public function signDisplayType() 
    {
        return $this->hasOne(EngSignDisplayType::class, 'id', 'esdt_id')->select(['esdt_description as name']);
    }
    public function getDisplayTypeAttribute()
    {
        return ($this->signDisplayType)? $this->signDisplayType->name : '';
    }
    public function displayTypeCheck($check)
    {
        return $this->esdt_id === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

    // Installation Type
    public function signInstallationType() 
    {
        return $this->hasOne(EngSignInstallationType::class, 'id', 'esit_id')->select(['esit_description as name']);
    }
    public function getInstallationTypeAttribute()
    {
        return ($this->signInstallationType)? $this->signInstallationType->name : '';
    }
    public function installationTypeCheck($check)
    {
        return $this->esit_id === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

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
        return $this->ebs_id === $check ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

    // client / applicant of job request
    public function client() 
    {
        return $this->hasOne(EngClients::class, 'id', 'p_code');
    }

    // Lot Owner
    public function owner() 
    {
        return $this->hasOne(EngClients::class, 'id', 'esa_owner_id');
    }

    // applicant consultant / building owner 
    public function applicantConsultant() 
    { 
        return $this->hasOne(EngClients::class, 'id', 'esa_applicant_consultant_id');
    }

    // Project Consultant
    public function consultant() 
    { 
        switch ($this->esa_sign_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'esa_sign_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'esa_sign_consultant_id', 'id'); 
                break;
                
            default:
                return $this->belongsTo(ConsultantExternal::class, 'esa_sign_consultant_id', 'id'); 
                break;
        }
    }

    // Project Incharge
    public function incharge() 
    { 
        switch ($this->esa_incharge_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'esa_incharge_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'esa_incharge_consultant_id', 'id'); 
                break;

            default:
                return $this->belongsTo(ConsultantExternal::class, 'esa_incharge_consultant_id', 'id'); 
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
        return $this->belongsTo(HrEmployee::class, 'esa_building_official', 'id'); 
    }
}   
