{{ Form::open(array('url' => 'residential-housing-list','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group" id="residential_id_parrent">
                    {{ Form::label('residential_id', __('Subdivision / Village Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('residential_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('residential_id',$arrResidentialName, $data->residential_id, array('class' => 'form-control','id'=>'residential_id')) }}
                    </div>
                    <span class="validate-err" id="err_residential_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('phase', __('Phase'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('phase') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('phase', $data->phase, array('class' => 'form-control','id'=>'phase')) }}
                    </div>
                    <span class="validate-err" id="err_phase"></span>
                </div>
            </div>		
        </div> 
       <div class="row" style="margin-bottom:150px;">
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('street', __('Street'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('street') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('street', $data->street, array('class' => 'form-control','id'=>'street')) }}
                    </div>
                    <span class="validate-err" id="err_street"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('block', __('Block No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('block') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('block', $data->block, array('class' => 'form-control','id'=>'block')) }}
                    </div>
                    <span class="validate-err" id="err_brgy_id"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('lot_from', __('Lot. No. From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('lot_from') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('lot_from', $data->lot_from, array('class' => 'form-control lotnumbersub','id'=>'lot_from')) }}
                    </div>
                    <span class="validate-err" id="err_lot_from"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('lot_to', __('Lot. No. To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('lot_to') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('lot_to', $data->lot_to, array('class' =>'form-control lotnumbersub','id'=>'lot_to')) }}
                    </div>
                    <span class="validate-err" id="err_lot_to"></span>
                </div>
            </div>
			<div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('lot_slot', __('Lot Slot'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('lot_slot') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('lot_slot', $data->lot_slot, array('class' => 'form-control','id'=>'lot_slot','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_lot_slot"></span>
                </div>
            </div>			
        </div>
		@if($data->id >0 )
		<div class="row">
			 <div class="col-md-10 text-center"></div>	
			 <div class="col-md-2 text-center">
				<!--<input type="button"  class="btn btn-info" value="Add Lot" style="padding: 0.4rem 0.76rem !important;"> -->
				 <button type="button" style="float: right;" class="btn  btn-primary" id="btnofLotModal">Add Lot</button>
			 </div>
		</div>
		<div  class="accordion accordion-flush">
		  <div class="accordion-item">
			 <h6 class="accordion-header" >
				<button class="button  btn-primary" type="button" style="width: 100%;text-align: left;">
				   <h6 class="sub-title accordiantitle">{{__("Location Lot list")}}</h6>
				</button>
			 </h6>
			 <div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body table-border-style">
							<div class="table-responsive">
								<table class="table" id="Jq_datatablelistpnmodle">
									<thead>
									<tr>
										<th>{{__('No.')}}</th>
										<th>{{__('Block No.')}}</th>
										<th>{{__('Lot No.')}}</th>
										<th>{{__('lot status')}}</th>
                                        <th>{{__('Availability STATUS')}}</th>
										<th>{{__('Action')}}</th>
									</tr>
									</thead>
								 </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		  </div>
	   </div>
	   @endif
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
	
	
<div class="modal fade" id="NumberofLotModal">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
		   <div class="modal-header">
				<h4 class="modal-title">Manage Location Lot List</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		   </div>
			<div class="container">
				<div class="modal-body">
				   <div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{{ Form::label('ecl_lot', __('Number of Lot'),['class'=>'form-label']) }}<span class="text-danger">*</span>
								<span class="validate-err">{{ $errors->first('ecl_lot') }}</span>
								<div class="form-icon-user" id ="ecl_lot_select">
									 <select class='form-control' id="ecl_lot">
									 
									 </select>
								</div>
								<span class="validate-err" id="err_ecl_lot"></span>
							</div>
						</div>    
					</div>
				</div>    
			</div>
			<div class="modal-footer"> 
				<input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeOrderModal" data-bs-dismiss="modal">
			   <button class="btn btn-primary" id="saveofLot"> <i class="la la-save"></i> Save Changes</button>
			</div>
		</div>
     </div> 
</div>  
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
<script src="{{ asset('js/accounting/addLegalResidentialLocation.js') }}"></script>  