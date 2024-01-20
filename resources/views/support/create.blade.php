{{ Form::open(array('url' => 'support','enctype'=>"multipart/form-data")) }}
<div class="modal-body">

    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}
            {{ Form::text('subject', '', array('class' => 'form-control ','required'=>'required')) }}
        </div>
        @if(\Auth::user()->type=='company')
            <div class="form-group col-md-6">
                {{Form::label('user',__('Support for User'),['class'=>'form-label'])}}
                {{Form::select('user',$users,null,array('class'=>'form-control select'))}}
            </div>
        @endif
        <div class="form-group col-md-6">
            {{Form::label('priority',__('Priority'),['class'=>'form-label'])}}
            {{Form::select('priority',$priority,null,array('class'=>'form-control select'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('status',__('Status'),['class'=>'form-label'])}}
            {{Form::select('status',$status,null,array('class'=>'form-control select'))}}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
            {{ Form::date('end_date', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('attachment',__('Attachment'),['class'=>'form-label'])}}
            <label for="document" class="form-label">
                <input type="file" class="form-control" name="attachment" id="attachment" data-filename="attachment_create">
            </label>
            <img id="image" class="mt-2" style="width:25%;"/>
            {{--            <p class="attachment_create"></p>--}}
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        </div>
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
{{ Form::close() }}


<script>
    document.getElementById('attachment').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>


