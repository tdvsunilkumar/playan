<style type="text/css">
     .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .btn{padding: 0.575rem 0.5rem;}
    .field-requirement-details-status label{padding-top:5px;}
    .nofile{width: 39px; text-align: center;}
    .accordion-button::after {
    background-image: url();
  }
</style>
<div class="modal-body">
	<div class="row">
		<div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
			<div class="row field-requirement-details-status">
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('No',__('No.'),['class'=>'form-label','style'=>'color:#fff;padding-top: 12px;'])}}
				</div>
				<div class="col-lg-5 col-md-5 col-sm-7">
					{{Form::label('applicable',__('Applicable Cashier System'),['class'=>'form-label','style'=>'color:#fff;padding-top: 12px;'])}}
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					
				</div>
				<!--<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('action',__('Action'),['class'=>'form-label'])}}
					<span class="btn_addmore_requirements btn" id="btn_addmore_requirements" style="color:white;">
						<i class="ti-plus"></i>
					</span> 
				</div>-->
			</div>
			<span class="requirementsDetails activity-details" id="requirementsDetails">
				@php $i=1; @endphp
				@foreach($data_details as $row)
					<div class="removerequirementsdata row pt10">
						<div class="col-lg-2 col-md-2 col-sm-2">
							<div class="form-group">
								{{$i}}
							</div>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5">
							<div class="form-group">
								<div class="form-icon-user">
									{{ $arrDepaertments[$row->pcs_id]}}
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
					
						</div>
						<!--<div class="col-lg-2 col-md-2 col-sm-2">
							<div class="form-group">
								<div class="action-btn bg-danger ms-2">
									<a href="#" class="mx-3 btn btn-sm deletecasherfuntion ti-trash text-white text-white" name="stp_print" value="0" data-id='{{$row->id}}'></a>
								</div>
							</div>
						</div>-->
						@php $i++; @endphp
					</div>
				@endforeach
			</span>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
   $(".deletecasherfuntion").click(function(){
	    var id  = $(this).data("id");
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Are you sure?',
			text: "This action can not be undone. Do you want to continue?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed)
			{
			   
				var filtervars = {id:id};
				$.ajax({
					type: "post",
					url: DIR+'ctopaymentortypedetails-delete',
					data: filtervars,
					dataType: "json",
					success: function(html){
					window.location.reload();
					}
				});
			}
		})
	});
});
</script>