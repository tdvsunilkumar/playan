{{ Form::open(array('url' => 'healthy-and-safety/reports/morbid-cases','target' => '_blank')) }}
  
   <style type="text/css">
       .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 500px;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
        }
   </style>

    <div class="modal-body" style="height: 327px;">
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('range', __('Date Range'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('range', $range,'', array('class' => 'form-control select3','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('range_year', __('Year'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('range_year', $year,'', array('class' => 'form-control select3','required'=>'required')) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="Export" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {

	$("#commonModal").find('.body').css({overflow: 'unset'}) 
});
</script>




