<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class Auto_Backup_Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d-H-iA') . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
        $file =  storage_path() . "/app/backup/".$filename; 
        exec($command, $output, $returnVar);
        $this->sendemaildbattachment($file);
    }

    public function sendemaildbattachment($file){
                $data=array();
                $database = env('DB_DATABASE');
                $data['message'] = '<p>Hello Admin, <br> The Database Backup File Attached Please Find The Attchment</p>';
                $data['to_name']='Admin';
                $data['to_email']='dynedgebackup@gmail.com';
                $data['attachment'] = $file;
                $data['subject']='Palayan Backup of database name is '.$database.' ON Date- '.date('d/m/Y H:i A');
                try
                {
                    Mail::send([], ['data' =>$data], function ($m) use ($data) {
                        $m->to($data['to_email'], $data['to_name']);
                        $m->subject($data['subject']);
                        $m->setBody($data['message'], 'text/html');
                        $m->priority(1);
                        $m->attach($data['attachment']);
                    });
                }
               catch(Exception $e)
               {
                  echo $e->getMessage(); exit; 
               }  
    }
}
