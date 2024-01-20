<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'estimations')) }}
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('client_id', __('Client'),['class'=>'form-label']) }}
            {{ Form::select('client_id', $client,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-label']) }}
            {{ Form::text('issue_date',null, array('class' => 'form-control datepicker','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('tax_id', __('Tax %'),['class'=>'form-label']) }}
            {{ Form::select('tax_id', $taxes,null, array('class' => 'form-control select2','required'=>'required')) }}
            @if(count($taxes) <= 0)
                <div class="text-muted text-xs">
                    {{__('Please create new Tax')}} <a href="{{route('taxes.index')}}">{{__('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-12 form-group">
            {{ Form::label('terms', __('Terms'),['class'=>'form-label']) }}
            {{ Form::textarea('terms',null, array('class' => 'form-control')) }}
        </div>
        <div class="col-12 text-end">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" value="{{ ('Create')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
            <!-- <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue"> -->
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
