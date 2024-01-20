<?php
use App\Models\HrEmployee;
use App\Models\HR\Policy as HrPolicy;
use App\Models\UserAccessApprovalApprover;

function currency_format($number) {
    if ($number) {
        return number_format($number,2);
    }
    return '0.00';
}
function currency_to_float($number) {
    $convert = str_replace( ',', '', $number );
    if (is_numeric($convert)) {
        return floatval($convert);
    }
    return $number;
}
function various_user() {
    return (new HrEmployee)->variousEmployee();
}
function user_mayor() {
    return (new HrEmployee)->getMayor();
}
function hr_policy($code) {
    $policy = HrPolicy::where([
        'hrsp_code'=> $code,
        'hrsp_is_active'=> 1
        ])->first();
    return $policy ? $policy->hrsp_value : '';
}
//
function approveButton($slugs, $sequence, $start = 0, $applicant_dept = null) {
    $user = Auth::user();
    $query = '';
    $sequence = $sequence - $start;
    if ($sequence == 1) {
        $query .= 'FIND_IN_SET('.$user->id.',user_access_approval_approvers.primary_approvers)';
    } else if ($sequence == 2) {
        $query .= 'FIND_IN_SET('.$user->id.',user_access_approval_approvers.secondary_approvers)';
    } else if ($sequence == 3) {
        $query .= 'FIND_IN_SET('.$user->id.',user_access_approval_approvers.tertiary_approvers)';
    } else {
        $query .= 'FIND_IN_SET('.$user->id.',user_access_approval_approvers.quaternary_approvers)';
    }

    $res = UserAccessApprovalApprover::select('*')
    ->leftJoin('user_access_approval_settings', function($join)
    {
        $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
    })
    ->leftJoin('menu_modules', function($join)
    {
        $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
    })
    ->whereRaw($query)
    ->where([
        'menu_modules.slug' => $slugs, 
    ]);
    if ($applicant_dept) {
        $res->where([
            'user_access_approval_approvers.department_id' => $applicant_dept 
        ]);
    }
    $res = $res->first();
    $status = $sequence + $start;

    if ($res === null) {
        $sequence = null;
    } else {
        if ($res && $sequence >= $res->levels) {
            if ($sequence > $res->levels) {
                $status = (int)$res->levels + $start;
            }
            $sequence = $res->levels;
        }
    }
        return [
            'sequence' => $sequence,
            'status' => $status,
        ];
}
