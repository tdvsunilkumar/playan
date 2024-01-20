<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploAssessPenaltyRates;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;


class SummaryReportController extends Controller
{
    public function __construct(){
		$this->_penaltyrates = new BploAssessPenaltyRates();
        $this->_commonmodel = new CommonModelmaster();  
        $this->data = array('id'=>'','prate_surcharge_percent'=>'','prate_annual_interest_percentage'=>'','prate_max_penalty_years'=>'','prate_discount_rate'=>'');
        }
        public function index(Request $request)
	    {
	    	$data = BploAssessPenaltyRates::find(1);
	        
	        //$data = $this->_psicClass->getclassList();
	        return view('summaryreport.index',compact('data'));
	       
	    }
	    public function updatePenaltyRates(Request $request){
	    	$updatedata = array();
	    	$updatedata['prate_surcharge_percent'] = $request->input('surcharge');
	    	$updatedata['prate_annual_interest_percentage'] = $request->input('annualpercent');
	    	$updatedata['prate_max_penalty_years'] = $request->input('maxpenalty');
	    	$updatedata['prate_discount_rate'] = $request->input('discountrate');

	    	$this->_penaltyrates->updateData($request->input('id'),$updatedata);
	    	if($request->input('surcharge') != $request->input('oldsurcharge') || $request->input('annualpercent') != $request->input('oldannualpercent') || $request->input('maxpenalty') != $request->input('oldmaxpenalty') || $request->input('discountrate') != $request->input('olddiscountrate')){
	    	  $penaltylogdata= array();
	    	    $penaltylogdata['oldprate_surcharge_percent'] = $request->input('oldsurcharge');
		    	$penaltylogdata['oldprate_annual_interest_percentage'] = $request->input('oldannualpercent');
		    	$penaltylogdata['oldprate_max_penalty_years'] = $request->input('oldmaxpenalty');
		    	$penaltylogdata['oldprate_discount_rate'] = $request->input('olddiscountrate');
	    	    $penaltylogdata['prate_surcharge_percent'] = $request->input('surcharge');
		    	$penaltylogdata['prate_annual_interest_percentage'] = $request->input('annualpercent');
		    	$penaltylogdata['prate_max_penalty_years'] = $request->input('maxpenalty');
		    	$penaltylogdata['prate_discount_rate'] = $request->input('discountrate');
		    	$penaltylogdata['updated_by'] = \Auth::user()->creatorId();
		    	$penaltylogdata['created_at'] = date('Y-m-d H:i:s');
                $penaltylogdata['updated_at'] = date('Y-m-d H:i:s');
	    		$this->_penaltyrates->addPenaltyRatelogData($penaltylogdata);
	    	}
	    	echo "Success";
	    }
}
