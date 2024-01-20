{{ Form::open(array('url' => 'form_builder')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=> 'required')) }}
        </div>
        <div class="col-12 form-group">
            <label for="exampleColorInput" class="form-label">{{__('Active')}}</label>
            <div class="d-flex radio-check">
                <div class="form-check form-check-inline">
                    <input type="radio" id="on" value="1" name="is_active" class="form-check-input" checked="checked">
                    <label class="custom-control-label form-label" for="on">{{__('On')}}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="off" value="0" name="is_active" class="form-check-input">
                    <label class="custom-control-label form-label" for="off">{{__('Off')}}</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" value="{{ __('Create')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
    
</div>
{{Form::close()}}
