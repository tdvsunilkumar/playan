<?php

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: *'); 
header('Access-Control-Allow-Headers: Origin, X-Requested-With,Authorization,  Content-Type, Accept'); 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('login', 'Api\UserController@login');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', 'Api\UserController@logout');
});
/* Penalty Rate Schedular Cron Job, this cron job will run each 1st day of month*/
Route::get('cron-jon/update-penalty-rate-schedule', 'RptCtoPenaltyScheduleController@updatePenaltyRateSchedule');

/* RPT CTO Payment Schedular Cron Job Add Payment schedular for new Year this cron job will run 1st day of each year  */
Route::get('cron-jon/add-payment-schedule-data', 'CtoPaymentScheduleController@addSchedularDataForNewYear');

/* RPT Deliquent Payment Cron Job, it will fill data in rpt_deliquents table if any real property tax is not paid current year or previous year */
Route::get('cron-jon/get-rpt-deliquents', 'RptDeliquentsController@getDeliquentsTds');
Route::get('cron-jon/yearly-bplo-business-status-changer', 'CronJobs\MasterCronJobController@yearlyBploStatusChanger');

/* Update data in cto_accounts_receivables table each month based on deliquency and outstanding and add new data in cto_accounts_receivables_details table*/
Route::get('cron-jon/add-update-data-receivables', 'AccountRreceivablesPropertyController@addUpdateDataReceivables');


Route::post('remortMasterApi','Api\RemortMasterController@remortMasterApi');
Route::post('remoteUpdateBusinessTable','Api\RemortBploController@remoteUpdateBusinessTable');
Route::post('updateRemortBploBusnPlan','Api\RemortBploController@updateRemortBploBusnPlan');
Route::post('removeRemortBploBusnPlan','Api\RemortBploController@removeRemortBploBusnPlan');
Route::post('updateRemortBploMeasurePax','Api\RemortBploController@updateRemortBploMeasurePax');
Route::post('removeRemortBploMeasurePax','Api\RemortBploController@removeRemortBploMeasurePax');
Route::post('updateRemortBploReqDoc','Api\RemortBploController@updateRemortBploReqDoc');
Route::post('removeRemortBploReqDoc','Api\RemortBploController@removeRemortBploReqDoc');


//Set Cron for Create Delinquency for business permit
Route::get('cron-jon/create-bplo-renew-delinquency', 'CronJobs\BploCronJobController@cronJobForBploRenewDelinquency');
Route::get('cron-jon/create-bplo-retire-delinquency', 'CronJobs\BploCronJobController@cronJobForBploRetireDelinquency');












