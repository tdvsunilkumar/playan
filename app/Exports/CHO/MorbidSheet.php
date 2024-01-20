<?php

namespace App\Exports\CHO;

use App\Models\HoDiagnosis;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;
class MorbidSheet implements FromView, WithTitle
{
    private $year;
    private $month;
    public function __construct($month,int $year) 
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function view(): View
    {
        $data = HoDiagnosis::select(
            'ho_diagnoses.*',
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days < 4 && ho_medical_record_diagnoses.cit_gender = 0,1,0) ) AS '0_4daysM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days < 4 && ho_medical_record_diagnoses.cit_gender = 1,1,0) ) AS '0_4daysF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days BETWEEN 7 and 28 && ho_medical_record_diagnoses.cit_gender = 0,1,0) ) AS '7_28daysM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days BETWEEN 7 and 28 && ho_medical_record_diagnoses.cit_gender = 1,1,0) ) AS '7_28daysF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days BETWEEN 29 and 364 && ho_medical_record_diagnoses.cit_gender = 0,1,0) ) AS '29_11monsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age_days BETWEEN 29 and 364 && ho_medical_record_diagnoses.cit_gender = 1,1,0) ) AS '29_11monsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 1 and 4 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '1_4yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 1 and 4 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '1_4yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 5 and 9 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '5_9yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 5 and 9 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '5_9yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 10 and 14 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '10_14yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 10 and 14 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '10_14yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 15 and 19 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '15_19yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 15 and 19 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '15_19yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 20 and 24 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '20_24yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 20 and 24 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '20_24yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 25 and 29 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '25_29yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 25 and 29 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '25_29yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 30 and 34 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '30_34yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 30 and 34 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '30_34yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 35 and 39 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '35_39yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 35 and 39 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '35_39yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 40 and 44 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '40_44yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 40 and 44 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '40_44yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 45 and 49 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '45_49yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 45 and 49 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '45_49yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 50 and 54 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '50_54yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 50 and 54 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '50_54yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 55 and 59 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '55_59yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 55 and 59 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '55_59yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 60 and 64 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '60_64yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 60 and 64 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '60_64yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 65 and 69 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '65_69yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age BETWEEN 65 and 69 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '65_69yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age > 70 && ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS '70yearsM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_age > 70 && ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS '70yearsF'"),

            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS 'totalM'"),
            DB::raw("sum(IF (ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS 'totalF'"),

            DB::raw("count(ho_medical_record_diagnoses.id) AS 'grandtotal'"),
        );
        if (strlen($this->month['name']) === 3 ) {
            $startMonth = $this->month['data'];
            $endMonth = $this->month['data'];            
        } else {
            $startMonth = $this->month['data'][0];
            $endMonth = $this->month['data'][1];
        }
            $start = Carbon::parse($startMonth.'/1/'.$this->year)->startofmonth()->toDateTimeString();
            $end = Carbon::parse($endMonth.'/1/'.$this->year)->endofmonth()->toDateTimeString();
            $data = $data->leftJoin(DB::raw("(select * from ho_medical_record_diagnoses where created_at BETWEEN '".$start."' AND '".$end."') as ho_medical_record_diagnoses "),
            'ho_medical_record_diagnoses.disease_id','ho_diagnoses.id');//i give up lol
        // dd($data->groupBy('ho_diagnoses.id')->orderBy('diag_name')->toSql());
        $data = $data->groupBy('ho_diagnoses.id')->orderBy('ho_diagnoses.id')->get();
        
        switch ($this->month['name']) {
            case 'Annual':
                $title = 'Annual '.$this->year;
                break;
            case 'Q1':
                $title = '1st Quarter ';
                break;
            case 'Q2':
                $title = '2nd Quarter ';
                break;
            case 'Q3':
                $title = '3rd Quarter ';
                break;
            case 'Q4':
                $title = '4th Quarter ';
                break;
            default:
                $title = Carbon::parse($startMonth.'/1/'.$this->year)->format('F');
                break;
        }
        return view('health-and-safety.prints.morbid-report', [
            'data' => $data,
            'title' => $title,
            'year' => $this->year,
            
        ]);
    }

    public function title() : string
    {
        return $this->month['name'];
    }

}
