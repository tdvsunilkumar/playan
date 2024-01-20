
{{Form::open(array('url'=>'custom-question','method'=>'post'))}}
<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('question',__('Question'),['class'=>'form-label'])}}
                {{Form::text('question',null,array('class'=>'form-control','placeholder'=>__('Enter question')))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('is_required',__('Is Required'),['class'=>'form-label'])}}
                {{ Form::select('is_required', $is_required,null, array('class' => 'form-control select','required'=>'required')) }}
            </div>
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

