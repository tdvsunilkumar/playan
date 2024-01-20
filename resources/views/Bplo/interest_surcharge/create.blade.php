{{ Form::open(array('url' => 'CtoTaxInterestSurcharge','class'=>'formDtls','id'=>'CtoTaxInterestSurcharge')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        accordion-button:not(.collapsed)::after, .accordion-button::after {
             background-image: unset !important;
        }
    </style>
    <div class="modal-body">
        
        <h6 class="accordion-header" id="flush-headingtwo">
            <button class="accordion-button collapsed btn-primary" type="button" style="padding-top: 7px;">
            <h6 class="sub-title accordiantitle">{{__('Surcharge Details' )}}</h6>
            </button>
        </h6>
        <br>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('tis_surcharge_amount', __('Surcharge'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('tis_surcharge_amount', $data->tis_surcharge_amount, array('class' => 'form-control numeric','maxlength'=>'150','required'=>'required')) }}
                         <div class="currency-sign"><span>Php</span></div>
                    </div>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_surcharge_rate_type', __('Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_surcharge_rate_type',$arrRate,$data->tis_surcharge_rate_type, array('class' => 'form-control select3','id'=>'tis_surcharge_rate_type','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_surcharge_rate_type"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_surcharge_schedule', __('Surcharge Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_surcharge_schedule',$arrSchedule,$data->tis_surcharge_schedule, array('class' => 'form-control select3','id'=>'tis_surcharge_schedule','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_surcharge_schedule"></span>
                </div>
            </div>
           
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_surcharge_formula', __('Formula'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_surcharge_formula',$arrSurcharge,$data->tis_surcharge_formula, array('class' => 'form-control select3','id'=>'tis_surcharge_formula','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_surcharge_formula"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_surcharge_compute_mode', __('Compute Mode'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_surcharge_compute_mode',$arrMode,$data->tis_surcharge_compute_mode, array('class' => 'form-control select3','id'=>'tis_surcharge_compute_mode','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_surcharge_compute_mode"></span>
                </div>
            </div>
        </div>

        <h6 class="accordion-header" id="flush-headingtwo">
            <button class="accordion-button collapsed btn-primary" type="button" style="padding-top: 7px;">
            <h6 class="sub-title accordiantitle">{{__('Interest Details' )}}</h6>
            </button>
        </h6>
        <br>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('tis_interest_amount', __('Interest'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::text('tis_interest_amount', $data->tis_interest_amount, array('class' => 'form-control numeric','maxlength'=>'150')) }}
                         <div class="currency-sign"><span>Php</span></div>
                    </div>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_interest_rate_type', __('Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_interest_rate_type',$arrRate,$data->tis_interest_rate_type, array('class' => 'form-control select3','id'=>'tis_interest_rate_type','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_interest_rate_type"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_interest_schedule', __('Interest Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_interest_schedule',$arrSchedule,$data->tis_interest_schedule, array('class' => 'form-control select3','id'=>'tis_interest_schedule','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_interest_schedule"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_interest_max_month', __('Max. Month'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('tis_interest_max_month', $data->tis_interest_max_month, array('class' => 'form-control numeric','maxlength'=>'2','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="tis_tis_interest_max_month"></span>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_interest_formula', __('Formula'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_interest_formula',$arrInterest,$data->tis_interest_formula, array('class' => 'form-control select3','id'=>'tis_interest_formula','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_interest_formula"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tis_interest_compute_mode', __('Compute Mode'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tis_interest_compute_mode',$arrMode,$data->tis_interest_compute_mode, array('class' => 'form-control select3','id'=>'tis_interest_compute_mode','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tis_interest_compute_mode"></span>
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br><br>
        </div> 
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script type="text/javascript">
    $(document).ready(function(){
        $('.numeric').numeric();
   });
</script>
<script type="text/javascript">
    $(document).ready(function() {
    var shouldSubmitForm = false;

    $('#savechanges').click(function(e) {
        if (!shouldSubmitForm) {
            var form = $('#CtoTaxInterestSurcharge');

            if (validateForm(form)) {
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
                        $('#savechanges').click();
                    } else {
                        console.log("Form submission canceled");
                    }
                });

                e.preventDefault();
            }
        }
    });

    function validateForm(form) {
        var requiredFields = form.find('[required="required"]');
        var isValid = true;

        requiredFields.each(function() {
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