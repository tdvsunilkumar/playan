<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Exports\CHO\MorbidReport;
use App\Exports\CHO\InternalInventoryReport;
use App\Exports\CHO\MorbidSheet;
use DB;
use Carbon\Carbon;
use App\Models\HoInventoryPosting;
class HoInventoryReport extends Controller
{
    public function morbidReportPrint($year)
    {
        return Excel::download(new MorbidReport($year), 'Morbid Report-'.$year.'.xlsx');
    }
    public function morbidPrint($range,$year)
    {
        $range =config('constants.reportingDateRanges')[$range];
        return Excel::download(new MorbidSheet($range,$year), 'Morbid Report-'.$range['name'].'-'.$year.'.xlsx');
    }
    public function utilReportPrint($year,$type = 1,$category = 0)
    {
        return Excel::download(new InternalInventoryReport($year,$type,$category), 'Utility Report-'.$year.'.xlsx');
    }
    public function monthlyUtilBalance() 
    {
        $date = Carbon::yesterday();
        $inventory = HoInventoryPosting::where('cip_balance_qty', '!=', 0)->get();
        foreach ($inventory as $key => $value) {
            DB::table('ho_utility_yearly_balance')->updateOrInsert(
                [
                    // 'month' => 12,
                    'month' => $date->month,
                    'year' => $date->year,
                    'ho_inv_posting_id' => $value->id,
                ],
                [
                    'beginning_qty' => $value->cip_balance_qty,
                    'created_at' => Carbon::now()
                ]
                );
        }
        return 'done';
    }
}
