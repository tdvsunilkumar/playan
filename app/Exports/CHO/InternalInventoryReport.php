<?php

namespace App\Exports\CHO;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InternalInventoryReport implements WithMultipleSheets 
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

    public function sheets(): array
    {
        $sheets = [
            $sheets['Utility'] = new UtilityReport($this->year, $this->type, $this->category),
            $sheets['Variance'] = new VarianceReport($this->year)
        ];
        return $sheets;
    }

}
