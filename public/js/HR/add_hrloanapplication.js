$(document).ready(function () {
	$("#hrla_approved_by").select3({dropdownAutoWidth : false,dropdownParent: $("#parrent_hrla_approved_by")});
	$("#hrlc_id").select3({dropdownAutoWidth : false,dropdownParent: $("#parrent_hrlc_id")});
	$("#hrla_id").select3({dropdownAutoWidth : false,dropdownParent: $("#parrent_hrla_id")});
	$("#hrla_loan_status").select3({dropdownAutoWidth : false,dropdownParent: $("#parrent_hrla_loan_status")});
	$('#hrla_employeesid').change(function (e) { 
		$.ajax({
			type: "get",
			url: "loan-application/department-division/"+$(this).val(),
			success: function (response) {
				if(response.status == 200){
					$('#hrla_department_id').val(response.data.acctg_department_id);
					$('#hrla_division_id').val(response.data.acctg_department_division_id);
				}
			},error(error){
				$('#hrla_department_id').val('');
				$('#hrla_division_id').val('');
			}
		});
	});
	$(".onchangekeyup").on('keyup', function (e) {
		computeInterest()
	});
	$('#hrlc_id').change(function (e){ 
		computeInterest()
	});
	$('.approve').click(function (e){ 
        e.preventDefault();
        btn = $(this);
        form = btn.closest('form')
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Once submit, This form can't be updated.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
                if(result.isConfirmed)
                {
		            $('#submit_type').val(1);
                    form.trigger('submit', [true])
                }
        });

        return false;
	});
    var months = document.querySelector("#hrlc_id").value; 
	if(months > 0){
	  managecheckdetails(months);  
	}
}); 
function addmorecheck(total_amout,installment_amount,interest_per_month,effectivity_date,months){
	var filtervars = {total_amout:total_amout,
					installment_amount:installment_amount,
					interest_per_month:interest_per_month,
					effectivity_date:effectivity_date,
					months:months
					}; 
	$.ajax({
		type: "post",
		url: DIR+'loan-application/lone-ledeger',
		data: filtervars,
		dataType: "html",
		success: function(html){ 
			$("#checkDetails").html(html);
		 }
	  });
}
function computeInterest(){
	effectivity_date = document.querySelector("#hrla_effectivity_date").value;
	var loan_amount = document.querySelector("#hrla_loan_amount").value;
	var percentage = document.querySelector("#hrla_interest_percentage").value;
	var months = document.querySelector("#hrlc_id").value;
	var interest = (loan_amount * (percentage / 100)) ;
	$('#hrla_interest_amount').val(interest);
	var interest_per_month = parseFloat(interest) / months ;
	var total_amout = (parseFloat(loan_amount) + parseFloat(interest));
	var installment_amount = total_amout / months ;
	console.log(total_amout);
	// $('#hrla_amount_disbursed').val(total_amout);
	$('#hrla_installment_amount').val(installment_amount);

	managecheckdetails(months);
	total_balance = parseFloat(loan_amount);
	addmorecheck(total_balance,installment_amount,interest_per_month,effectivity_date,months);
}
function managecheckdetails(id){
  if(id > 0){
  $("#checkdetaildiv").removeClass('hide');
  }else{ 
  $("#checkdetaildiv").addClass('hide'); 
  }
}