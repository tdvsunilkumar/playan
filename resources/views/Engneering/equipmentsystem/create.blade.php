{{ Form::open(array('url' => 'engelectricequpment','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <style type="text/css">
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 50%;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
    }
</style>


            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('eeet_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('eeet_description') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('eeet_description', $data->eeet_description, array('class' => 'form-control','maxlength'=>'100','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_eegt_description"></span>
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
    <script src="{{ asset('js/ajax_validation.js') }}"></script>
  