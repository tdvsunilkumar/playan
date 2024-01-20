{{ Form::open(array('url' => 'administrative/tax-libraries/category/store','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_category_code', __('Tax Category Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('tax_category_code', $data->tax_category_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_category_code"></span>
                </div>
            </div>
            
        <!-- </div>
        <div class="row">  -->  
            
            <div class="form-group col-md-6">
                {{ Form::label('tax_category_desc', __('Tax Category Desc'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {!! Form::textarea('tax_category_desc', $data->tax_category_desc, ['class'=>'form-control','required'=>'required','rows'=>'1']) !!}
                <span class="validate-err" id="err_tax_category_desc"></span>
            </div>
           <!--  <div class="form-group col-md-6">
                {{ Form::label('tax_category_complete_description', __('Tax Category Complete Description'),['class'=>'form-label']) }}
                <span class="validate-err">{{ $errors->first('tax_category_complete_description') }}</span>
                {!! Form::textarea('tax_category_complete_description', $data->tax_category_complete_description, ['class'=>'form-control','rows'=>'2']) !!}
            </div> -->
           
           
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" id="btn_submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
