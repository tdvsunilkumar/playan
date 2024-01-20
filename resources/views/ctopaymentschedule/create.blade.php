{{ Form::open(array('url' => 'ctopaymentschedule','class'=>'formDtls')) }}
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
    @php $disabledclass= ($data->id) ? 'disabled-field':'disabled-field'; @endphp
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                           <div class="form-group">
                                {{ Form::label('sd_mode', __('Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('sd_mode',$arrClassCode,$data->sd_mode, array('class' => 'form-control select3','id'=>'sd_mode','required'=>'required')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_sd_mode"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="toyear">
                                {{ Form::label('rcpsched_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rcpsched_year') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rcpsched_year', (isset($data->rcpsched_year) && $data->rcpsched_year != '')?$data->rcpsched_year:((session()->get('paymentScheduleYear') != '')?session()->get('paymentScheduleYear'):date("Y")), array('class' => 'form-control '.$disabledclass,'maxlength'=>'4','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_year"></span>
                            </div>
                        </div>
                         
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('rcpsched_date_start', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rcpsched_date_start') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::date('rcpsched_date_start', $data->rcpsched_date_start, array('class' => 'form-control','maxlength'=>'30','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_date_start"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('rcpsched_date_end', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rcpsched_date_end') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::date('rcpsched_date_end', $data->rcpsched_date_end, array('class' => 'form-control','maxlength'=>'30','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_date_end"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('rcpsched_penalty_due_date', __('Penalty Due Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rcpsched_penalty_due_date') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::date('rcpsched_penalty_due_date', $data->rcpsched_penalty_due_date, array('class' => 'form-control','maxlength'=>'30','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_penalty_due_date"></span>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('rcpsched_discount_due_date', __('Discount Due Date'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('rcpsched_discount_due_date') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::date('rcpsched_discount_due_date', $data->rcpsched_discount_due_date, array('class' => 'form-control','maxlength'=>'30')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_discount_due_date"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('rcpsched_discount_rate', __('Discount Rate'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('rcpsched_discount_rate') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rcpsched_discount_rate', $data->rcpsched_discount_rate, array('class' => 'form-control decimalvalue','maxlength'=>'30')) }}
                                </div>
                                <span class="validate-err" id="err_rcpsched_discount_rate"></span>
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
   <script type="text/javascript">
       $(document).ready(function () {
           $('.yearpicker').yearpicker({dropdownAutoWidth: false, dropdownParent: $("#toyear")});
        });
   </script>
   <!-- <script src="{{ asset('js/ajax_validation.js') }}"></script>  -->
   <script src="{{ asset('js/ajax_common_save.js') }}"></script>