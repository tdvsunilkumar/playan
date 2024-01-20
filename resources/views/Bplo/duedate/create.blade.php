{{ Form::open(array('url' => 'CtoPaymentDueDate','enctype'=>'multipart/form-data','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
.modal-content {
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
                <div class="form-group">
                    {{ Form::label('app_type_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('app_type_id',$arrType,$data->app_type_id, array('class' => 'form-control select3','id'=>'app_type_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_app_type_id"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('due_1st_payment', __('For First Payment'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::text('due_1st_payment', $data->due_1st_payment, array('class' => 'form-control dayMonthDatepicker','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_due_1st_payment"></span>
                </div>
            </div>   

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('due_semi_annual_2nd_sem', __('2nd Semester'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('due_semi_annual_2nd_sem', $data->due_semi_annual_2nd_sem, array('class' => 'form-control dayMonthDatepicker','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_due_semi_annual_2nd_sem"></span>
                </div>
            </div>   

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('due_quarterly_2nd', __('2nd Quarter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('due_quarterly_2nd') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('due_quarterly_2nd', $data->due_quarterly_2nd, array('class' => 'form-control dayMonthDatepicker','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_due_quarterly_2nd"></span>
                </div>
            </div>   

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('due_quarterly_3rd', __('3rd Quarter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('due_quarterly_3rd', $data->due_quarterly_3rd, array('class' => 'form-control dayMonthDatepicker','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_due_quarterly_3rd"></span>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('due_quarterly_4th', __('4th Quarter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('due_quarterly_4th', $data->due_quarterly_4th, array('class' => 'form-control dayMonthDatepicker','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_due_quarterly_4th"></span>
                </div>
            </div>    
            <div class="col-lg-4 col-md-4 col-sm-4"> 
                <div class="form-group">
                    {{ Form::label('Attachment', __('Attachment'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::input('file','due_attached_docs','',array('class'=>'form-control'))}}  
                    </div>
                    @if(!empty($data->due_attached_docs))
                        <p class="attacmentDtls">
                            <a href="uploads/due_date/{{$data->due_attached_docs}}" target="_blank">View Attachment</a>
                            <a href="#" class="deleterow" id="{{$data->id}}" name="{{$data->due_attached_docs}}"><span class="mx-3 btn btn-sm  ti-trash"></span></a> 
                        </p>
                    @endif
                </div>         
            </div>
           

        </div> 
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/ajax_common_save.js') }}"></script>
<script src="{{ asset('js/Bplo/add_CtoPaymentDueDate.js') }}"></script>
<script>
    $(".dayMonthDatepicker").flatpickr({
        altInput: true,
        dateFormat: "m/d",
        altFormat: "m/d",
        ariaDateFormat: "m/d"
    });
</script>

  