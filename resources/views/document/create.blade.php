    {{Form::open(array('url'=>'document','method'=>'post'))}}
    <div class="modal-body">

    <div class="row">
        <div class="form-group col-12">
            {{Form::label('name',__('Name'),['class'=>'form-label'])}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Document Name')))}}
            @error('name')
            <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group col-12">
            {{ Form::label('is_required', __('Required Field'),['class'=>'form-label']) }}
            <select class="form-control select2" required name="is_required">
                <option value="0">{{__('Not Required')}}</option>
                <option value="1">{{__('Is Required')}}</option>
            </select>
        </div>

    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ('Create')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        <!-- <input type="submit" value="{{__('Create')}}" class="btn  btn-primary"> -->
    </div>
    {{Form::close()}}

