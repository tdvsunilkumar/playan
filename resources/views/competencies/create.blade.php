
    {{Form::open(array('url'=>'competencies','method'=>'post'))}}
    <div class="modal-body">

    <div class="row ">
        <div class="col-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{Form::label('type',__('Type'),['class'=>'form-label'])}}
                {{Form::select('type',$performance,null,array('class'=>'form-control select'))}}
            </div>
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
    {{Form::close()}}
