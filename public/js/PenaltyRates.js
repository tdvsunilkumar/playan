$(document).ready(function(){
	$(".submit").click(function(){
		updatePenaltyRates();
	}); 
});

function updatePenaltyRates(){
	var penaltyid =$('#id').val();
    var surcharge =$("#prate_surcharge_percent").val();
    var annualpercent =$("#prate_annual_interest_percentage").val();
    var maxpenalty =$("#prate_max_penalty_years").val();
    var discountrate =$("#prate_discount_rate").val();
    var oldsurcharge =$("#oldsurcahrge").val();
    var oldannualpercent =$("#oldannualinterest").val();
    var oldmaxpenalty =$("#oldmaxpenalty").val();
    var olddiscountrate =$("#olddiscountrate").val();
    $.ajax({
        url :DIR+'updatepenaltyrates', // json datasource
        type: "POST", 
        data: {
          "id": penaltyid, 
          "surcharge":surcharge,
          "annualpercent":annualpercent,
          "maxpenalty":maxpenalty,
          "discountrate":discountrate,
          "oldsurcharge":oldsurcharge,
          "oldannualpercent":oldannualpercent,
          "oldmaxpenalty":oldmaxpenalty,
          "olddiscountrate":olddiscountrate,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
        	if(html=='Success'){
        		Swal.fire({
    				  position: 'center',
    				  icon: 'success',
    				  title: 'Penalty Rate Updated Successfully.',
    				  showConfirmButton: false,
    				  timer: 1500
    				})
        	}
        }
    })
}