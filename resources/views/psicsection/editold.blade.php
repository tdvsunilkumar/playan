 {{ Form::model($PsicSection, array('route' => array('psicsection.update', $PsicSection->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">


        
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('section_code', __('Section Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('section_code',$PsicSection->section_code, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('status',array('' =>'Select status','1' =>'Active','0' =>'In Active'), $PsicSection->section_status, array('class' => 'form-control spp_type','id'=>'status','required'=>'required')) }}
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {!! Form::textarea('description', $PsicSection->section_description, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
