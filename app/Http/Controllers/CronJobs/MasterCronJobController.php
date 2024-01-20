<?php

namespace App\Http\Controllers\CronJobs;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;

class MasterCronJobController extends Controller
{
    use ApiResponser;
    public $App_KEY = '';
    public function __construct(){
        $this->App_KEY = \Config::get('app.key');
    }
    // public function yearlyBploStatusChanger(Request $request)
    // {
    //     $currentYear = Carbon::now()->year;
    //     $previousYear = Carbon::now()->subYear()->year;
    //     $currentMonth = Carbon::now()->month;

    //     // Start a transaction
    //     DB::beginTransaction();

    //     try {
    //         // Retrieve all rows to update
    //         $rowsToUpdate = DB::table('bplo_business')->where('busn_tax_year', $previousYear)->get();

    //         foreach ($rowsToUpdate as $rowToUpdate) {
    //             // Perform the update on the current row
    //             $rowToUpdate->update([
    //                 'busn_tax_year' => $currentYear,
    //                 'busn_tax_month' => $currentMonth,
    //                 'busn_app_status' => 0 ,
    //                 'busn_dept_involved' => 0 ,
    //                 'busn_dept_completed' => 0,
    //                 'is_final_assessment' => 0,
    //                 'app_code' => 2,
    //             ]);

    //              // Get the attributes of the current row
    //             $rowAttributes = $rowToUpdate->getAttributes();

    //             // Remove primary key and timestamp attributes
    //             unset($rowAttributes['id']);
    //             unset($rowAttributes['busn_tax_year']);
    //             unset($rowAttributes['busn_tax_month']);
    //             unset($rowAttributes['busn_app_status']);
    //             unset($rowAttributes['busn_dept_involved']);
    //             unset($rowAttributes['busn_dept_completed']);
    //             unset($rowAttributes['is_final_assessment']);
    //             unset($rowAttributes['app_code']);
    //             unset($rowAttributes['created_at']);
    //             unset($rowAttributes['updated_at']);

    //             // Set additional attributes
    //             $rowAttributes['busn_id'] = $rowToUpdate->id;
    //             $rowAttributes['busn_tax_year'] = $currentYear;
    //             $rowAttributes['busn_tax_month'] = $currentMonth;
    //             $rowAttributes['busn_app_status'] = 0;
    //             $rowAttributes['busn_dept_involved'] = 0;
    //             $rowAttributes['busn_dept_completed'] = 0;
    //             $rowAttributes['is_final_assessment'] = 0;
    //             $rowAttributes['app_code'] = 2;

    //             // Insert the updated data into bplo_business_history
    //             DB::table('bplo_business_history')->insert($rowAttributes);
    //         }

    //         // Commit the transaction
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         // Rollback the transaction if an exception occurs
    //         DB::rollback();
    //         // Handle the exception
    //     }
    // }

    public function yearlyBploStatusChanger(Request $request)
    {
        $currentYear = Carbon::now()->year;
        // $previousYear = Carbon::now()->subYear()->year;
        $currentMonth = Carbon::now()->month;
        $currentDate = Carbon::now()->format('Y-m-d');
        // Start a transaction
        DB::beginTransaction();
    
        try {
            // Retrieve all rows to update
            $rowsToUpdate = DB::table('bplo_business')->where('busn_tax_year','<',$currentYear)->get();
    
            foreach ($rowsToUpdate as $rowToUpdate) {
                // Update the current row
                DB::table('bplo_business')
                    ->where('id', $rowToUpdate->id)
                    ->update([
                        'busn_tax_year' => $currentYear,
                        'busn_tax_month' => $currentMonth,
                        'busn_app_status' => 0,
                        'busn_dept_involved' => 0,
                        'busn_dept_completed' => 0,
                        'is_final_assessment' => 0,
                        'app_code' => 2,
                        'application_date' => $currentDate
                    ]);
    
                // Convert the object to an array
                $rowAttributes = get_object_vars($rowToUpdate);
    
                // Remove unnecessary attributes
                unset($rowAttributes['id']);
                unset($rowAttributes['busn_tax_year']);
                unset($rowAttributes['busn_tax_month']);
                unset($rowAttributes['busn_app_status']);
                unset($rowAttributes['busn_dept_involved']);
                unset($rowAttributes['busn_dept_completed']);
                unset($rowAttributes['is_final_assessment']);
                unset($rowAttributes['app_code']);
                unset($rowAttributes['application_date']);
                unset($rowAttributes['created_at']);
                unset($rowAttributes['updated_at']);
    
                // Set additional attributes
                $rowAttributes['busn_id'] = $rowToUpdate->id;
                $rowAttributes['busn_tax_year'] = $currentYear;
                $rowAttributes['busn_tax_month'] = $currentMonth;
                $rowAttributes['busn_app_status'] = 0;
                $rowAttributes['busn_dept_involved'] = 0;
                $rowAttributes['busn_dept_completed'] = 0;
                $rowAttributes['is_final_assessment'] = 0;
                $rowAttributes['app_code'] = 2;
                $rowAttributes['application_date'] = $currentDate;
    
                // Insert the updated data into bplo_business_history
                DB::table('bplo_business_history')->insert($rowAttributes);
            }
    
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }
    }
}
