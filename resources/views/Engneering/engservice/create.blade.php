{{ Form::open(array('url' => 'engservice','class'=>'formDtls','id'=>'excavationgroundtype')) }}
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
<div class="modal-body" style="overflow-x: hidden;">
                    <div class="row">
                         <div class="col-md-10">
                            <div class="form-group">
                                {{ Form::label('tfoc_id', __('Tax, Fee & Other Charges'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                        <div class="col-md-2" style="text-align:right;padding-top: 32px;">
                            <div class="d-flex radio-check">
                                     <div class="form-check form-check-inline form-group">
                                        {{ Form::checkbox('is_main_service', '1', ($data->is_main_service =='1')?true:false, array('id'=>'is_main_service','class'=>'form-check-input')) }}
                                        {{ Form::label('is_main_service', __('Main Service'),['class'=>'form-label']) }}
                                      </div>
                                    </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('servicefee', __('Service Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('servicefee','', array('class' => 'form-control disabled-field','id'=>'servicefee','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                        @if(($data->id)>0)
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('eat_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('eat_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('eat_idshow',$arrApptype,$data->eat_id, array('class' => 'form-control  select3','id'=>'eat_id','required'=>'required','disabled'=>true)) }}
                                    {{ Form::hidden('eat_id',$data->eat_id, array('id' => 'id')) }}
                                </div>
                                <span class="validate-err" id="err_eat_id"></span>
                            </div>
                        </div>
                        @else
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('eat_id', __('Application Type'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('eat_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('eat_id',$arrApptype,$data->eat_id, array('class' => 'form-control select3','id'=>'eat_id')) }}
                                </div>
                                <span class="validate-err" id="err_eat_id"></span>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('emf_id', __('Module Form'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('emf_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('emf_id',$arrModuleform,$data->emf_id, array('class' => 'form-control select3','id'=>'emf_id')) }}
                                </div>
                                <span class="validate-err" id="err_emf_id"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('top_transaction_type_id', __('Tax Order Of Payment (Transaction Type)'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('top_transaction_type_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('top_transaction_type_id',$arrtransactiontype,$data->top_transaction_type_id, array('class' => 'form-control select3','id'=>'top_transaction_type_id')) }}
                                </div>
                                <span class="validate-err" id="err_top_transaction_type_id"></span>
                            </div>
                        </div>
                        <div class="col-md-12" style="text-align:right;">
                            <div class="d-flex radio-check">
                                     <div class="form-check form-check-inline form-group">
                                        {{ Form::checkbox('is_flcno_required', '1', ($data->is_flcno_required =='1')?true:false, array('id'=>'is_flcno_required','class'=>'form-check-input')) }}
                                        {{ Form::label('is_flcno_required', __('FALC No. Required[Zoning Clearance Certificate Details]'),['class'=>'form-label']) }}
                                      </div>
                                    </div>
                        </div>
                          <div class="row" style="width: 100%;padding: 0px; margin: 0px;padding-bottom: 20px;margin-top: -25px;">
                          <div class="col-md-12">
                            <div class="row field-requirement-details-status" style="color:white;">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    {{Form::label('requirement',__('Requirements'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('taxable_item_qty',__('Required'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('Order'),['class'=>'form-label numeric'])}}
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1"> <span class="btn_addmore_requirement btn btn-primary" id="btn_addmore_requirement" ><i class="ti-plus"></i></span></div>
                            </div>

                            <span class="RequirementDetails activity-details tablestripped" id="RequirementDetails">
                                @php $i=0; @endphp
                                @foreach($requirements as $key=>$val)
                                <div class="removerequirementdata row pt10">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('req_id[]',$arrRequirements,$val->req_id,array('class' => 'form-control req_id','required'=>'required','id'=>'req_id')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                          {{ Form::checkbox("esr_is_required[$key]", '1', ($val->esr_is_required == '1') ? true : false, array('id' => "csr_is_required_$key", 'class' => 'form-check-input esr_is_required')) }}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('order[]',$val->orderno,array('class'=>'form-control order','required'=>'required','id'=>'order'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <button type="button" class="btn btn-danger btn_cancel_requiremnt" style=" padding: 6px 15px;"><i class="ti-trash"></i></button>
                                    </div>
                                    @php $i++; @endphp
                                </div>

                                @endforeach
                        </span>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
    {{Form::close()}}
<div id="hidenRequirementHtml" class="hide">
     <div class="row removerequirementdata" style="padding: 5px 0px;" id="reqdropdown">
        <div class="col-lg-8 col-md-8 col-sm-8">
           {{ Form::select('req_id[]',$arrRequirements,'',array('class' => 'form-control req_id','required'=>'required','id'=>'req_id0')) }}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
                 {{ Form::checkbox('esr_is_required[]', '1', ('')?true:false, array('id'=>'esr_is_required','class'=>'form-check-input esr_is_required')) }}
            </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('order[]','',array('class'=>'form-control order','id'=>'order'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-danger btn_cancel_requiremnt" style=" padding: 6px 15px;"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
 <script src="{{ asset('js/Engneering/ajax_validengservices.js') }}"></script>  
 <script src="{{ asset('js/Engneering/add_service.js') }}"></script> 
 <script type="text/javascript">
      $(document).ready(function () {
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#excavationgroundtype');
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
            var form = $('#excavationgroundtype');
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
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
});
  </script>
 
           