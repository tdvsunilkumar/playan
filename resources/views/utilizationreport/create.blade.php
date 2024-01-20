<div class="modal-body">
	<div class="row pt10">
        <div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Utilization Report
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">

                            <div class="col-md-12">
                                <div class="form-group" id="rec-type-group">
                                    {{ Form::label('util_rep_type', __('Receive Type'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('util_rep_type') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('util_rep_type',
                                            ['' => 'Select Receive Type', '1' => 'Internal', '2' => 'External',], 
                                            2, ['class' => 'form-control rec_type', 'id' => 'rec_type']) !!}
                                    </div>
                                    <span class="validate-err" id="err_util_rep_type"></span>
                                </div>
                            </div>

							<div class="col-md-12">
                                <div class="form-group" id="supplier-group">
                                    {{ Form::label('supplier', __('Select Supplier'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('supplier') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('supplier_id',
                                            [], 
                                            null, ['class' => 'form-control supplier', 'id' => 'supplier']) !!}
                                    </div>
                                    <span class="validate-err" id="err_supplier_id"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" id="date-range-group">
                                    {{ Form::label('date_range', __('Select Date Range'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('date_range') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('util_rep_range',
                                            [], 
                                            null, ['class' => 'form-control date_range', 'id' => 'date_range']) !!}
                                    </div>
                                    <span class="validate-err" id="err_util_rep_range"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" id="year-group">
                                    {{ Form::label('year', __('Select Year'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('year') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('util_rep_year',
                                            ['' => 'Select Year', date('Y') => date('Y'), (date('Y') - 1) => (date('Y') - 1), (date('Y') - 2) => (date('Y') - 2)], 
                                            null, ['class' => 'form-control year', 'id' => 'year']) !!}
                                    </div>
                                    <span class="validate-err" id="err_util_rep_year"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" id="remarks-group">
                                    {{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('remarks') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::text('util_rep_remarks',  
                                            null, 
                                            ['class' => 'form-control remarks', 'id' => 'remarks', 'placeholder' => 'Remarks']) !!}
                                    </div>
                                    <span class="validate-err" id="err_util_rep_remarks"></span>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
   
    
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        {{-- <a href="" target="_blank">
            <input type="button" value="Print" class="btn print-btn btn-primary">
        </a> --}}
		<input type="submit" name="submit" onclick="formSubmit(2)" value="Save" class="btn  btn-primary">
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>