<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CronJob; // Assuming you have a model for your table
use Carbon\Carbon;
use DB;

class DynamicCronJob extends Command
{
    protected $signature = 'dynamic-cron:run';
    protected $description = 'Schedule dynamic cron jobs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get cron jobs from the database
        $cronJobs = CronJob::all()->where('status',1);
        foreach ($cronJobs as $cronJob) {
            // Schedule the cron job based on the specified schedule type and value
            $this->scheduleJob($cronJob);
        }
        $this->info('Dynamic cron job scheduling completed.');
    }

    protected function scheduleJob($cronJob)
    {
        if(!empty($cronJob->last_run_datetime)){
            switch ($cronJob->schedule_type) {
                case '1':
                    $this->callminuteUrl($cronJob);
                    break;
                case '2':
                    $this->callHourlyUrl($cronJob);
                    break;
                case '3':
                    $this->callDayOfMonthUrl($cronJob);
                    break;
                case '4':
                    $this->callMonthlyUrl($cronJob);
                    break;
                case '5':
                    $this->callWeeklyUrl($cronJob);
                    break;
                default:
                    $this->error("Invalid schedule type for job ID {$cronJob->id}");
            }
        }else{
            $column=array();
            $column['last_run_datetime'] = date('Y-m-d H:i:s');
            $this->updateCronData($cronJob->id,$column);
        }
    }
    protected function callWeeklyUrl($data){
        $currentDate = Carbon::now();
        $previousDate = Carbon::parse($data->last_run_datetime);
        $diff = $currentDate->diffInMinutes($previousDate);
        $currentDay = (int)date("d");
        $currentHrs = date("H:i");
        $arrWeek = array('Sunday' => 1,'Monday' => 2,'Tuesday' => 3,'Wednesday' => 4,'Thursday' => 5,'Friday' => 6,'Saturday' => 7);  
        $dateName =  date('l');           
        if($diff>0 && $arrWeek[$dateName]==$data->schedule_value && $currentHrs==$data->hours){
            $this->callUrl($data->url,$data->id);
        }
    }

    protected function callMonthlyUrl($data){
        $currentDate = Carbon::now();
        $previousDate = Carbon::parse($data->last_run_datetime);
        $diff = $currentDate->diffInMinutes($previousDate);
        $currentDay = (int)date("d");
        $currentHrs = date("H:i");

        $currentMonth = (int)date("m");
        if($diff>0 && $currentMonth==$data->schedule_value  && $currentDay==$data->day && $currentHrs==$data->hours){
            $this->callUrl($data->url,$data->id);
        }
    }

    protected function callDayOfMonthUrl($data){
        $currentDate = Carbon::now();
        $previousDate = Carbon::parse($data->last_run_datetime);
        $diff = $currentDate->diffInMinutes($previousDate);
        $currentDay = (int)date("d");
        $currentHrs = date("H:i");
        if($diff>0 && $currentDay==$data->schedule_value && $currentHrs==$data->hours){
            $this->callUrl($data->url,$data->id);
        }
    }

    protected function callHourlyUrl($data){
        $currentDate = Carbon::now();
        $previousDate = Carbon::parse($data->last_run_datetime);
        $diff = $currentDate->diffInHours($previousDate);
        if($data->schedule_value <= $diff){
            $this->callUrl($data->url,$data->id);
        }
    }

    protected function callminuteUrl($data){
       $currentDate = Carbon::now();
        $previousDate = Carbon::parse($data->last_run_datetime);
        $minutesDifference = $currentDate->diffInMinutes($previousDate);
        if($data->schedule_value <= $minutesDifference){
            $this->callUrl($data->url,$data->id);
        }
    }

    protected function callUrl($url,$id=0)
    {
        $response = Http::get($url);
        $this->info("HTTP GET request completed for URL: {$url}, Status Code: {$response->status()}");
        $data=array();
        $data['response'] = "Status Code: ".$response->status();
        if($response->status()==200){
            $data['last_run_datetime'] = date('Y-m-d H:i:s');
        }
        $this->updateCronData($id,$data);
        
    }
    public function updateCronData($id,$data){
        DB::table('cron_job')->where('id',$id)->update($data);
    }
}
