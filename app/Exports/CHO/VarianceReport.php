<?php

namespace App\Exports\CHO;

use App\Models\HoInventoryAdjustmentDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class VarianceReport implements FromView, WithTitle
{
    private $year;
    public function __construct(int $year) 
    {
        $this->year = $year;
    }
    public function view(): View
    {
        $year = $this->year;
        $start = Carbon::parse('1/1/'.$this->year)->startofmonth()->toDateTimeString();
        $end = Carbon::parse('12/1/'.$this->year)->endofmonth()->toDateTimeString();
        $data = HoInventoryAdjustmentDetail::getAdjustmentByDays($start,$end)->get();
        return view('health-and-safety.prints.variance-report', [
            'data' => $data,
            'year' => $year,
        ]);
    }

    public function title() : string
    {
        return "Variance";
    }
}
