<?php

namespace App\Http\Controllers\CronJobs;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;

use DB;

class BploCronJobController extends Controller
{
    use ApiResponser;
    public function __construct(){
    }
    public function cronJobForBploRetireDelinquency(Request $request){
        $this->_TreasurerAssessmentCont = new TreasurerAssessmentController(); 
        $currentYear = date("Y");
        $arrData = DB::table('cto_bplo_final_assessment_details AS cd')
            ->join('bplo_business AS bb', 'bb.id', '=', 'cd.busn_id')
            ->select('busn_id','payment_date','bb.pm_id','bb.app_code','cd.assess_year')->where('cd.payment_status',0)
            ->where('cd.app_code',3)
            ->where('cd.assess_year','<',$currentYear)
            ->orderBy('cd.assess_year','ASC')->groupBy('cd.busn_id')->get()->toArray();
        
        foreach ($arrData as $key => $val) {
            $retire_id = DB::table('bplo_business_retirement')->where('busn_id',$val->busn_id)->orderBy('id','DESC')->pluck('id')->first();
           $year = $val->assess_year;
           if($year>0 && $retire_id>0){
                $request->merge(['id' =>$val->busn_id]);
                $request->merge(['year_type' =>2]);
                $request->merge(['isIndvidual' =>0]);
                $request->merge(['app_code' =>3]);
                $request->merge(['year' =>$year]);
                $request->merge(['isSaveDelinquencyData' =>1]);
                $request->merge(['pm_id' =>1]);
                $request->merge(['retire_id' =>$retire_id]);
                $this->_TreasurerAssessmentCont->displayOrReAssessment($request,'saveData');
           }
        }
        echo "Cron successfully run";
    }

    public function cronJobForBploRenewDelinquency(Request $request){
        $this->_TreasurerAssessmentCont = new TreasurerAssessmentController(); 

        $currentYear = date("Y");
        $arrData = DB::table('cto_bplo_final_assessment_details AS cd')
            ->join('bplo_business AS bb', 'bb.id', '=', 'cd.busn_id')
            ->select('busn_id','payment_date','bb.pm_id','bb.app_code','cd.assess_year')->where('cd.payment_status',0)
            ->where('cd.app_code',2)
            ->where('cd.assess_year','<',$currentYear)
            //->whereRaw('YEAR(payment_date) < ?', [$currentYear])
            ->orderBy('cd.assess_year','ASC')->groupBy('cd.busn_id')->get()->toArray();

        foreach ($arrData as $key => $val) {
            $year = $val->assess_year;
           if($year>0){
                $request->merge(['id' =>$val->busn_id]);
                $request->merge(['year_type' =>2]);
                $request->merge(['isIndvidual' =>0]);
                $request->merge(['app_code' =>$val->app_code]);
                $request->merge(['year' =>$year]);
                $request->merge(['isSaveDelinquencyData' =>1]);
                $request->merge(['pm_id' =>$val->pm_id]);
                $this->_TreasurerAssessmentCont->displayOrReAssessment($request,'saveData');
           }
        }
        echo "Cron successfully run";
    }
}
