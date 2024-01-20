{{ Form::open(array('url' => 'treasurer-tax-credit')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('tcm_gl_id',$data->tcm_gl_id, array('id' => 'tcm_gl_id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
		.action-btn.bg-info.ms-4 {height: 44px; width: 79px;}
		.modal-content {
			width: 100% !important;
			position: absolute;
		    float: left;
		    margin-left: 50%;
		    margin-top: 50%;
		    transform: translate(-50%, -50%);
			}
    </style>
	<div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" id="fund_idparrent">
                    {{ Form::label('fund_id', __('Fund Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                     {{ Form::select('fund_id',$getFundCodes,$data->fund_id, array('class' => 'form-control','id'=>'fund_id','required')) }}
                    </div>
                    <span class="validate-err" id="err_busn_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" id="ctype_idparrent">
                    {{ Form::label('ctype_id', __('Type of Charges'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ctype_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('ctype_id',$getChargetypes,$data->ctype_id, array('class' => 'form-control','id'=>'ctype_id','required')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_year"></span>
                </div>
            </div>
            <div class="col-md-4">
				<div class="form-group">
					{{ Form::label('gl_code', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('sl_id') }}</span>
					<div class="form-icon-user">
						{{ Form::select('gl_code',$arrSubsidiaryLeader,$data->tcm_gl_id, array('class' => 'form-control','id'=>'gl_code','required'=>'required','readonly'=>'readonly')) }}
					</div>
					<span class="validate-err" id="err_tcm_gl_id"></span>
				</div>
			</div>
         </div>
		 <div class="row">
		    <div class="col-md-12">
                <div class="form-group" id="tcm_sl_idparrent">
                    {{ Form::label('tcm_sl_id', __('Chart of Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('tcm_sl_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('tcm_sl_id',$getGeneralledgers,$data->tcm_sl_id, array('class' => 'form-control','id'=>'tcm_sl_id','required')) }}
                    </div>
                    <span class="validate-err" id="err_p_tcm_sl_id"></span>
                </div>
            </div>
		</div>
		<div class="row">
		   <div class="col-md-12">
                <div class="form-group" id="pcs_idparrent">
                    {{ Form::label('pcs_id', __('Applicable Department [Cashiering]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('pcs_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('pcs_id',$arrDepaertments,$data->pcs_id, array('class' =>'form-control','id'=>'pcs_id','required')) }}
                    </div>
                    <span class="validate-err" id="err_pcs_id"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
		   <div class="col-md-12">
                <div class="form-group" id="pcs_idparrent">
                    {{ Form::label('rpt_category', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('rpt_category') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpt_category',$arrCategory,$data->rpt_category, array('class' =>'form-control select3','id'=>'rpt_category','required')) }}
                    </div>
                    <span class="validate-err" id="err_p_tfoc_is_applicable"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('tcm_remarks', __('Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_inspection_no') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('tcm_remarks', $data->tcm_remarks, array('class' => 'form-control','id'=>'tcm_remarks')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_inspection_no"></span>
                </div>
            </div>
		 </div>
		 <br> <br> <br> <br> <br> 
		 
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
		<!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>  
<script type="text/javascript">
$(document).ready(function (){
	$("#fund_id").select3({dropdownAutoWidth : false,dropdownParent: $("#fund_idparrent")});
	$("#ctype_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ctype_idparrent")});
	$("#tcm_sl_id").select3({dropdownAutoWidth : false,dropdownParent: $("#tcm_sl_idparrent")});
	$("#pcs_id").select3({dropdownAutoWidth : false,dropdownParent: $("#pcs_idparrent")});
	 $('#tcm_sl_id').on('change',function(){
		var agl_code =$(this).val();
		getAccoutdescription(agl_code);
	 });
	 if($("#tcm_sl_id").val() > 0) { 
       var agl_code = $("#tcm_sl_id option:selected").val();
        getAccoutdescription(agl_code);
     }
	 
  function  getAccoutdescription(aglcode){
     var agl_code =aglcode;
       $.ajax({
            url :DIR+'getAccountDescription', // json datasource
            type: "POST", 
            data: {
                    "agl_code": agl_code, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               var arr = html.split('#');
               $("#gl_code").html(arr[0]);
               $("#tcm_gl_id").val(arr[1]);

              var descval = $("#gl_code option:selected").text();
              $("#totaldesc").text(descval);
            }
        })
   }
   $('#pcs_id').on('change',function(){
		var pcs_val =$(this).val();
		if(pcs_val== '1'){
		$.ajax({
            url :DIR+'arr-depaertments-check', // json datasource
            type: "POST", 
            data: {
                    "pcs_val": pcs_val, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               var total = html;
			   if(total == '1'){
				   const swalWithBootstrapButtons = Swal.mixin({
			        customClass: {
			            confirmButton: 'btn btn-success',
			            //cancelButton: 'btn btn-danger'
			        },
			        buttonsStyling: false
			    })

			    swalWithBootstrapButtons.fire({
			        title: '',
			        text: "Maximum of 1 [Business Permit & Licenses] must be Active.",
			        icon: 'warning',
			        //showCancelButton: true,
			        confirmButtonText: 'OK',
			        //cancelButtonText: 'No',
			        reverseButtons: true
			    }).then((result) => {
			        if(result.isConfirmed)
			        {
					  $("#pcs_id").val($("#pcs_id option:first").val());
			        }else{
						$("#pcs_id").val($("#pcs_id option:first").val());
					}
			    })
				   
			   }				   
            }
        })
	  }
	 //  if(pcs_val== '2'){
		// $.ajax({
  //           url :DIR+'arr-depaertments-check', // json datasource
  //           type: "POST", 
  //           data: {
  //                   "pcs_val": pcs_val, "_token": $("#_csrf_token").val(),
  //               },
  //           success: function(html){
  //              var total = html;
		// 	   if(total == '3'){
		// 		const swalWithBootstrapButtons = Swal.mixin({
		// 	        customClass: {
		// 	            confirmButton: 'btn btn-success',
		// 	            //cancelButton: 'btn btn-danger'
		// 	        },
		// 	        buttonsStyling: false
		// 	    })

		// 	    swalWithBootstrapButtons.fire({
		// 	        title: '',
		// 	        text: "Maximum of 3 [Real Property(Land, Building & Machine)] must be Active.",
		// 	        icon: 'warning',
		// 	        //showCancelButton: true,
		// 	        confirmButtonText: 'OK',
		// 	        //cancelButtonText: 'No',
		// 	        reverseButtons: true
		// 	    }).then((result) => {
		// 	        if(result.isConfirmed)
		// 	        {
		// 			   $("#pcs_id").val($("#pcs_id option:first").val());
		// 	        }else{
		// 				$("#pcs_id").val($("#pcs_id option:first").val());
		// 			}
		// 	    })
			   	
		// 	   }				
  //           }
  //       }) 
	 //  }
	  categorychange(pcs_val);
   });

   function categorychange(id){
   	if(id ==2){
   		$("#rpt_category").prop('required',true);
   		$("#rpt_category").attr('readonly',false);
   	}else{
   		$("#rpt_category").prop('required',false);
   		$("#rpt_category").val($("#rpt_category option:first").val());
   		$("#rpt_category").attr('readonly',true);
   	}

   }
});
</script>
  