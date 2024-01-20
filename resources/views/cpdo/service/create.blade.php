{{ Form::open(array('url' => 'cpdoservice','class'=>'formDtls','id'=>'cpdoservice')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .modal.show .modal-dialog {
    transform: none;
    width: 1050px;
}
         .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
    </style>
<div class="modal-body">
                    <div class="row">
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('tfoc_id', __('Tax, Fee & Other Charges'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('servicefee', __('Service Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('servicefee','', array('class' => 'form-control disabled-field','id'=>'servicefee','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('top_transaction_type_id', __('Tax Order of Payment (Transaction Type)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('top_transaction_type_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('top_transaction_type_id',$arrtransactiontype,$data->top_transaction_type_id, array('class' => 'form-control select3','id'=>'top_transaction_type_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_top_transaction_type_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('cs_amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('cs_amount',$data->cs_amount, array('class' => 'form-control disabled-field','id'=>'cs_amount','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_cs_amount"></span>
                            </div>
                        </div>
                          <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                          <div class="col-md-12">
                            <div class="row field-requirement-details-status" style="color:white;">
                                <div class="col-lg-7 col-md-7 col-sm-7">
                                    {{Form::label('requirement',__('Requirements'),['class'=>'form-label'])}}
                                </div>
                                
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('taxable_item_qty',__('Required'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('Order'),['class'=>'form-label numeric'])}}
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2"> <span class="btn_addmore_requirement btn btn-primary" id="btn_addmore_requirement" ><i class="ti-plus"></i></span></div>
                            </div>
                            <span class="RequirementDetails activity-details tablestripped" id="RequirementDetails">
                                @php $i=1; @endphp
                                @foreach($requirements as $key=>$val)
                                <div class="removerequirementdata row pt10" >
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('req_id[]',$arrRequirements,$val->req_id,array('class' => 'form-control select3','required'=>'required','id'=>'req_id'.$i)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                          {{ Form::checkbox("csr_is_required[$key]", '1', ($val->csr_is_required == '1') ? true : false, array('id' => "csr_is_required_$key", 'class' => 'form-check-input csr_is_required')) }}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('order[]',$val->orderno,array('class'=>'form-control order','required'=>'required','id'=>'order'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <button type="button" class="btn btn-danger btn_cancel_requiremnt" style=" padding: 6px 15px;"><i class="ti-trash"></i></button>
                                    </div>
                                    @php $i++; @endphp
                                </div>

                                @endforeach
                        </span>
                      </div>
                    </div>
                         <!--  <div class="row" style="padding-left: 20px;">
                            <div class="col-md-10">
                                <div class="form-group">
                                     {{ Form::label('requirement', __('Requirements'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                         {{ Form::select('requiremets_ids[]',$arrRequirements,$data->requiremets_ids, array('class' => 'form-control select3','id'=>'requiremets_ids','multiple','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_tfoc_id"></span>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                    </div>
        </div>    
    {{Form::close()}}
	<div id="hidenRequirementHtml" class="hide">
		 <div class="row removerequirementdata" style="padding: 5px 0px;" id="reqdropdown">
			<div class="col-lg-7 col-md-7 col-sm-7">
			   {{ Form::select('req_id[]',$arrRequirements,'',array('class' => 'form-control req_id select3
select3','required'=>'required','id'=>'req_id0')) }}
			</div>
            <div class="col-lg-1 col-md-1 col-sm-1">
                 {{ Form::checkbox('csr_is_required[]', '1', ('')?true:false, array('id'=>'csr_is_required','class'=>'form-check-input csr_is_required')) }}
            </div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				{{Form::text('order[]','',array('class'=>'form-control order','required'=>'required','id'=>'order'))}}
			</div>
			 <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"><button type="button" class="btn btn-danger btn_cancel_requiremnt" style=" padding: 6px 15px;"><i class="ti-trash"></i></button></div></div>
		</div>
	</div>
 <script src="{{ asset('js/Cpdo/ajax_validationservice.js') }}"></script>  
 <script src="{{ asset('js/Cpdo/add_service.js') }}?rand={{ rand(000,999) }}"></script> 
<script type="text/javascript">
      $(document).ready(function () {
        $("#cm_type").select3({ dropdownAutoWidth: false });
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#cpdoservice');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        shouldSubmitForm = true;
                        form.submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
            }
        });

        function checkIfFieldsFilled() {
            var form = $('#cpdoservice');
            var requiredFields = form.find('[required="required"]');
            var isValid = true;

            requiredFields.each(function () {
                var field = $(this);
                var fieldValue = field.val();

                if (fieldValue === '') {
                    isValid = false;
                    return false; // Exit the loop early if any field is empty
                }
            });

            if (!isValid) {
                console.log("All required fields must be filled");
            }

            return isValid;
        }
});
  </script>  
 
           