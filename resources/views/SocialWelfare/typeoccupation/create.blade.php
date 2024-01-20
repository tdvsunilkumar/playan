{{ Form::open(array('url' => 'setup-data-type-of-occupation','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('wptoo_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('wptoo_description') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('wptoo_description', $data->wptoo_description, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_wptoo_description"></span>
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
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
  