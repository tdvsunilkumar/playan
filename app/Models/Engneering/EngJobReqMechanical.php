<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqMechanical extends Model
{
    public $table = 'eng_mechanical_app';

    public function slug()
    {
        return 'ema';
    }
    // Bldg Scope
    public function installationOperationType() 
    {
        return $this->hasOne(EngInstallationOperationType::class, 'id', 'eiot_id');
    }
    public function getInstallOperationTypeAttribute()
    {
        return ($this->installationOperationType)? $this->installationOperationType->eiot_description : '';
    }
    public function installOperationCheck($check)
    {
        return $this->install_operation_type === $check ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
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
        return $this->bldg_scope === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

    // note: functions below is just same with EngJobReqSanitary EngJobReqFence EngJobReqSign EngJobReqMechanical EngJobReqBuilding
    public function permit() 
    {
        return $this->hasOne(EngBldgPermitApp::class, 'id', 'ebpa_permit_no');
    }
    // client / applicant of job request
    public function client() 
    {
        return $this->hasOne(EngClients::class, 'id', 'p_code');
    }

    // Lot Owner
    public function owner() 
    {
        return $this->hasOne(EngClients::class, 'id', $this->slug().'_owner_id');
    }

    // applicant consultant / building owner 
    public function applicantConsultant() 
    { 
        return $this->hasOne(EngClients::class, 'id', $this->slug().'_applicant_consultant_id');
    }

    // employee
    public function employee($column)
    {
        return $this->belongsTo(HrEmployee::class, $column, 'id'); 
    }

    // external
    public function external($column)
    {
        return $this->belongsTo(ConsultantExternal::class, $column, 'id'); 
    }

    // Project Consultant
    public function consultant() 
    { 
        switch ($this->ema_sign_category) {
            case 1:
                return $this->employee($this->slug().'_sign_consultant_id'); 

            case 2:
                return $this->external($this->slug().'_sign_consultant_id'); 
                
            default:
                return $this->external($this->slug().'_sign_consultant_id'); 
        }
    }
    

    // Project Incharge
    public function incharge() 
    { 
        switch ($this->ema_incharge_category) {
            case 1:
                return $this->employee($this->slug().'_incharge_consultant_id'); 
                break;
            
            case 2:
                return $this->external($this->slug().'_incharge_consultant_id'); 
                break;
            
            default:
                return $this->external($this->slug().'_incharge_consultant_id'); 
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
        return $this->employee($this->slug().'_building_official'); 
    }

    // Type of Occupancy
    // $data->details->bldgOccupancyType = occupancy data
    public function bldgOccupancyType() 
    {
        return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebot_id');
    }
    public function getOccupancyTypeAttribute()
    {
        return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description : '';
    }
}
