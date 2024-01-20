<?php

namespace App\Exports\CHO;

use App\Models\HoInventoryPosting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class UtilityReport implements FromView, WithTitle
{
    private $year;
    private $category;
    private $type;
    public function __construct(int $year, int $category, int $type) 
    {
        $this->year = $year;
        $this->category = $category;
        $this->type = $type;
    }
    public function view(): View
    {
        $year = $this->year;
        $newInventory = collect(HoInventoryPosting::getInventoryIssuance($this->year,$this->type,$this->category));
        $oldInventory = collect(HoInventoryPosting::getInventoryRemaining($this->year,$this->type,$this->category));
        $data = $newInventory->merge($oldInventory)->sortBy('cip_item_name');
        return view('health-and-safety.prints.utility-report', [
            'data' => $data,
            'year' => $year,
            'total' => (object)[
                'delivery' => $newInventory->sum('total_cost'),
                'beginning' => $oldInventory->sum('total_cost'),
                'total' =>$data->sum('total_cost'),
                'adjust' =>$data->sum('adjust_cost'),
                'issue' =>$data->sum('issue_cost'),
                'balance' =>$data->sum('bal_cost'),
            ]
        ]);
    }

    public function title() : string
    {
        return "Utilization";
    }
}
