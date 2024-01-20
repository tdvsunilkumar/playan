<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqBuilding extends Model
{
    public $table = 'eng_bldg_permit_apps';

    // permit
    public function permit() 
    {
        return $this->hasOne(EngBldgPermitApp::class, 'ebpa_permit_no', 'id');
    }

    // Bldg Scope
    // $data->details->signBldgScope = scope data
    // $data->details->bldg_scope = scope desc
    // $data->details->bldgScopeCheck('[check]') = / or [space]
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
    public function bldgRemarks($check)
    {
        return $this->bldg_scope === $check ? ' OF <b>'.$this->ebpa_scope_remarks.'</b>': '';
    }

    // Application Type
    // $data->details->applicationType = application Type
    // $data->details->app_type = scope desc
    // $data->details->bldgScopeCheck('[check]') = / or [space]
    public function applicationType() 
    {
        return $this->hasOne(EngBldgAptype::class, 'id', 'eba_id');
    }
    public function getAppTypeAttribute()
    {
        return ($this->applicationType)? $this->applicationType->eba_description : '';
    }
    public function appTypeCheck($check)
    {
        return $this->app_type === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
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

    // use type of occupancy
    public function bldgOccupancySubType() 
    {
        return $this->hasOne(EngBldgOccupancySubType::class, 'id', 'ebost_id');
    }
    public function getOccupancySubtypeAttribute()
    {
        return ($this->bldgOccupancySubType)? $this->bldgOccupancySubType->ebost_description : '';
    }
    public function bldgSubtypeCheck($check)
    {
        return ($this->occupancy_subtype === $check) ? config('constants.checkbox.checked') : config('constants.checkbox.unchecked');
    }
    public function bldgSubtypeOtherCheck($check, $defaults)
    {
        // dd(config('constants.checkbox.checked'));
        $occupancy_type = $this->bldgOccupancyType->ebot_description;
        if ($occupancy_type === $check) {
            if (!in_array($this->occupancy_subtype, $defaults)) {
                return config('constants.checkbox.checked');
            }
        }
        return config('constants.checkbox.unchecked');
    }
    public function bldgSubtypeOther($check)
    {
        $occupancy_type = $this->bldgOccupancyType->ebot_description;
        if ($occupancy_type === $check) {
            return $this->ebpa_occ_other_remarks;
        }
        return '';
    }

    // Total Estimated Cost
    // $data->details->fees = data
    public function getFeesAttribute()
    {
        return EngJobRequest::getDetailsbldgFeesedit($this->id);
    }

    // Total Estimated Cost
    // $data->details->asses_fees = data
    public function getAssesFeesAttribute()
    {
        return EngJobRequest::getAssessmentFeesedit($this->id);
    }

    // employee
    public function employee($column)
    {
        return $this->belongsTo(HrEmployee::class, $column, 'id'); 
    }
    // Project Consultant
    public function getConsultantAttribute() 
    { 
        if (isset($this->fees->ebfd_sign_category)) {
            switch ($this->fees->ebfd_sign_category) {
                case 1:
                    return HrEmployee::find($this->fees->ebfd_sign_consultant_id); 
                    break;
                
                case 2:
                    return ConsultantExternal::find($this->fees->ebfd_sign_consultant_id); 
                    break;
                    
                default:
                    return ConsultantExternal::find($this->fees->ebfd_sign_consultant_id); 
                    break;
            }
        }
    }

    // Project Incharge
    public function getInchargeAttribute() 
    { 
        switch ($this->fees->ebfd_incharge_category) {
            case 1:
                return HrEmployee::find($this->fees->ebfd_incharge_consultant_id); 
                break;
            
            case 2:
                return ConsultantExternal::find($this->fees->ebfd_incharge_consultant_id); 
                break;

            default:
                return ConsultantExternal::find($this->fees->ebfd_incharge_consultant_id); 
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
    // $data->details->official = data
    public function official() 
    { 
        return $this->employee('ebpa_bldg_official_name'); 
    }

    // client / applicant of job request
    // $data->details->client = data
    public function client() 
    {
        return $this->hasOne(EngClients::class, 'id', 'p_code');
    }
    // Lot Owner
    // $data->details->owner = data
    public function getOwnerAttribute() 
    {
        return EngClients::find($this->fees->ebfd_consent_id);
    }
    // applicant consultant / building owner
    // $data->details->applicantConsultant = data
    public function applicantConsultant() 
    { 
        return $this->hasOne(EngClients::class, 'id', 'efa_applicant_consultant_id');
    }

    // Assesd fees
    public function getZoningAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_zoning_assessed_by);
    }
    public function getLinegradeAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_linegrade_assessed_by);
    }
    public function getBuildingAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_bldg_assessed_by);
    }
    public function getPlumbingAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_plum_assessed_by);
    }
    public function getElectricalAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_elec_assessed_by);
    }
    public function getMechanicalAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_mech_assessed_by);
    }
    public function getOthersAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_others_assessed_by);
    }
    public function getTotalAttribute()
    {
        return HrEmployee::find($this->asses_fees->ebaf_total_assessed_by);
    }

    public function preparedBy($type)
    {
        switch ($type) {
            case 'zoning':
                return isset($this->zoning->fullname)? $this->zoning->fullname : '';
            case 'linegrade':
                return isset($this->linegrade->fullname)? $this->linegrade->fullname : '';
            case 'building':
                return isset($this->building->fullname)? $this->building->fullname : '';
            case 'plumbing':
                return isset($this->plumbing->fullname)? $this->plumbing->fullname : '';
            case 'electrical':
                return isset($this->electrical->fullname)? $this->electrical->fullname : '';
            case 'mechanical':
                return isset($this->mechanical->fullname)? $this->mechanical->fullname : '';
            case 'others':
                return isset($this->others->fullname)? $this->others->fullname : '';
            case 'total':
                return isset($this->total->fullname)? $this->total->fullname : '';
            default:
                return '';
                break;
        }
    }
}
