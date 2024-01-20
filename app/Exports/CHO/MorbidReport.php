<?php

namespace App\Exports\CHO;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class MorbidReport implements WithMultipleSheets 
{
    private $year;
    public function __construct(int $year) 
    {
        $this->year = $year;
        $this->sheets = config('constants.reportingDateRanges');
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheets as $value) {
            $sheets[$value['name']] = new MorbidSheet($value,$this->year);
        }
        return $sheets;
    }

}
