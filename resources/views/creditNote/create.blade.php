{{ Form::open(array('route' => array('invoice.credit.note',$invoice_id),'mothod'=>'post')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            {{Form::date('date',null,array('class'=>'form-control','required'=>'required'))}}

           
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
           
                {{ Form::number('amount', !empty($invoiceDue)?$invoiceDue->getDue():0, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
          
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', '', ['class'=>'form-control','rows'=>'3']) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" value="{{ ('Add')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
    <!-- <input type="submit" value="{{__('Add')}}" class="btn  btn-primary"> -->
</div>
{{ Form::close() }}