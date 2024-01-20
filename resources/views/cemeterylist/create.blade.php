<style>
@if($data->id >0 )
.modal-content {
   position: static;
   float: left;
   margin-left: 50%;
   margin-right: 50%;
   margin-top: 70%;
  transform: translate(-50%, -50%);
}
@else
.modal-content {
   position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}	
.modal-footer{
	 margin-bottom: 150px;
}

@endif
#NumberofLotModal {
    width: 523px;
    margin-left: 17%;
    margin-top: 9%;
}
</style>
{{ Form::open(array('url' => 'cemeterylist','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group" id="ec_idparrent">
                    {{ Form::label('ec_id', __('Cemetery Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ec_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('ec_id',$CemeteryName, $data->ec_id, array('class' => 'form-control','id'=>'ec_id')) }}
						 {{ Form::hidden('brgy_id',$data->brgy_id, array('id' => 'brgy_id')) }}
                    </div>
                    <span class="validate-err" id="err_ec_id"></span>
                </div>
            </div>
			<div class="col-md-4">
                <div class="form-group" id="ecs_idparrent">
                    {{ Form::label('ecs_id', __('Cemetery Style'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ecs_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('ecs_id',$cemeterystyle,$data->ecs_id, array('class' => 'form-control','id'=>'ecs_id')) }}
                    </div>
                    <span class="validate-err" id="err_ecs_id"></span>
                </div>
            </div>			
        </div> 
       <div class="row" >
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('ecl_street', __('Street'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ecl_street') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ecl_street', $data->ecl_street, array('class' => 'form-control','id'=>'ecl_street')) }}
                    </div>
                    <span class="validate-err" id="err_ecl_street"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('ecl_block', __('Block No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ecl_block') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ecl_block', $data->ecl_block, array('class' => 'form-control','id'=>'ecl_block')) }}
                    </div>
                    <span class="validate-err" id="err_ecl_block"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('ecl_lot_no_from', __('Lot. No. From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ecl_lot_no_from') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('ecl_lot_no_from', $data->ecl_lot_no_from, array('class' => 'form-control lotnumbersub','id'=>'ecl_lot_no_from')) }}
                    </div>
                    <span class="validate-err" id="err_ecl_lot_no_from"></span>
                </div>
            </div>
			<div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('ecl_lot_no_to', __('Lot. No. To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ecl_lot_no_to') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('ecl_lot_no_to', $data->ecl_lot_no_to, array('class' =>'form-control lotnumbersub','id'=>'ecl_lot_no_to')) }}
                    </div>
                    <span class="validate-err" id="err_ecl_lot_no_to"></span>
                </div>
            </div>
			<div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ecl_slot', __('Lot Slot'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ecl_slot') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ecl_slot', $data->ecl_slot, array('class' => 'form-control','id'=>'ecl_slot','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_ecl_slot"></span>
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
				   <h6 class="sub-title accordiantitle">{{__("Cemetery Lot list")}}</h6>
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
										<th>{{__('Lot Status')}}</th>
										<th>{{__('STATUS')}}</th>
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
				<h4 class="modal-title">Cemetery List</h4>
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
<script src="{{ asset('js/add_cemeterylist.js') }}"></script>  