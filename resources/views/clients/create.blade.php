{{ Form::open(array('url' => 'clients')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','placeholder'=>__('Enter client Name'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('email', __('E-Mail Address'),['class'=>'form-label']) }}
            {{ Form::email('email', null, array('class' => 'form-control','placeholder'=>__('Enter Client Email'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', __('Password'),['class'=>'form-label']) }}
            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            @error('password')
            <small class="invalid-password" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>

        @if(!$customFields->isEmpty())
            @include('custom_fields.formBuilder')
        @endif

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" value="{{ ('create')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
    <!-- <input type="submit" value="{{__('Create')}}" class="btn  btn-primary"> -->
</div>

{{Form::close()}}


