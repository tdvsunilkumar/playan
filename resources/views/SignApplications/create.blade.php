{{ Form::open(array('url' => 'signing-settings','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('menu_module_id', __('Menu Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('menu_module_id') }}</span>
				<div class="form-icon-user">
					{{
                        Form::select('menu_module_id', $modules, $value = $data->menu_module_id , ['id' => 'menu_module_id', 'class' => 'form-control select3 required', 'data-placeholder' => 'select a module'])
                    }}
				</div>
				<span class="validate-err" id="err_menu_module_id"></span>
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-10">
			<div class="form-group">
				{{ Form::label('menu_sub_id', __('Sub-Menu'), ['class' => 'form-label']) }}
				<span class="validate-err">{{ $errors->first('menu_sub_id') }}</span>
				<div class="form-icon-user">
					{{
						Form::select('menu_sub_id', $sub_moddule, $data->menu_sub_id, ['id' => 'menu_sub_id', 'class' => 'form-control select3', 'data-placeholder' => 'select'])
					}}
				</div>
				<span class="validate-err" id="err_menu_sub_id"></span>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<div class="form-icon-user" style="margin-top: 35px;" >
					{{ Form::checkbox('status', 1, $data->status == 1, ['id' => 'status', 'class' => 'form-check-input status']) }}
					{{ Form::label('status', 'Active', ['class' => 'form-check-label status']) }}
				</div>
				<span class="validate-err" id="err_status"></span>
			</div>
		</div>
	</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('var_id', __('Variable'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('var_id') }}</span>
					<div class="form-icon-user">
						{{
							Form::select('var_id', $veriable, $value = $data->var_id, ['id' => 'var_id', 'class' => 'form-control select3 required', 'data-placeholder' => 'select'])
						}}
					</div>
					<span class="validate-err" id="err_var_id"></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('print_slug', __('Print Slug'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('print_slug') }}</span>
					<div class="form-icon-user">
						{{
							Form::text('print_slug', $value = $data->print_slug, ['id' => 'print_slug', 'class' => 'form-control  required', 'data-placeholder' => 'select'])
						}}
					</div>
					<span class="validate-err" id="err_print_slug"></span>
				</div>
			</div>
		</div>		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('section_id', __('Section'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('section_id') }}</span>
					<div class="form-icon-user">
						{{
							Form::select('section_id', $section, $value = $data->section_id, ['id' => 'section_id', 'class' => 'form-control select3 required', 'data-placeholder' => 'select'])
						}}
					</div>
					<span class="validate-err" id="err_section_id"></span>
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
					<span class="validate-err">{{ $errors->first('remarks') }}</span>
					<div class="form-icon-user">
						{{ Form::textarea('remarks', $data->remarks, array('class' => 'form-control','rows'=>3,'id'=>'remarks')) }}
					</div>
					<span class="validate-err" id="err_remarks"></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
						<div class="row"  id="accordionFlushExample">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                            <h6 class="sub-title accordiantitle">{{__("Digital Signature Position")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                                        <div class="basicinfodiv">
                                            <div class="row">      
                                                <div class="col-lg-3 col-md-12 col-sm-3">
                                                    <div class="form-group">
														{{ Form::label('pos_x', __('Position Start(X)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('pos_x') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('pos_x',$data->pos_x,array('class'=>'form-control','id'=>'pos_x','min'=>'10','max'=>'9999'))
															}}
														</div>
														<span class="validate-err" id="err_pos_x"></span>
													</div>
                                                </div>
                                                <div class="col-lg-3 col-md-12 col-sm-3">
													<div class="form-group">
														{{ Form::label('pos_x_end', __('Position End(X)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('pos_x_end') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('pos_x_end',$data->pos_x_end,array('class'=>'form-control','id'=>'pos_x_end','min'=>'10','max'=>'9999'))
															}}
														</div>
														<span class="validate-err" id="err_pos_x_end"></span>
													</div>
                                                </div>
												<div class="col-lg-3 col-md-12 col-sm-3">
													<div class="form-group">
														{{ Form::label('width_x', __('Width(X).'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('width_x') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('width_x',$width_x,array('class'=>'form-control','id'=>'width_x','readonly' => 'true'))
															}}
														</div>
														<span class="validate-err" id="err_width_x"></span>
													</div>
                                                </div>
												<div class="col-lg-3 col-md-12 col-sm-3">
													<div class="form-group">
														{{ Form::label('d_page_no', __('Page No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('d_page_no') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('d_page_no',$data->d_page_no,array('class'=>'form-control','id'=>'d_page_no'))
															}}
														</div>
														<span class="validate-err" id="err_d_page_no"></span>
													</div>
                                                </div>

                                            </div>
											<div class="row">   
												<div class="col-lg-3 col-md-6 col-sm-3">
													<div class="form-group">
														{{ Form::label('pos_y', __('Position Start(Y)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('pos_y') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('pos_y',$data->pos_y,array('class'=>'form-control','id'=>'pos_y','min'=>'10','max'=>'9999'))
															}}
														</div>
														<span class="validate-err" id="err_pos_y"></span>
													</div>
                                                </div>   
												
                                                <div class="col-lg-3 col-md-6 col-sm-3">
                                                    <div class="form-group">
														{{ Form::label('pos_y_end', __('Position End(Y)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('pos_y_end') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('pos_y_end',$data->pos_y_end,array('class'=>'form-control','id'=>'pos_y_end','min'=>'10','max'=>'9999'))
															}}
														</div>
														<span class="validate-err" id="err_pos_y_end"></span>
													</div>
                                                </div>
												<div class="col-lg-3 col-md-12 col-sm-3">
													<div class="form-group">
														{{ Form::label('height_y', __('Height(Y).'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('height_y') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('height_y',$height_y,array('class'=>'form-control','id'=>'height_y','readonly' => 'true'))
															}}
														</div>
														<span class="validate-err" id="err_height_y"></span>
													</div>
                                                </div>
                                               

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>				
			</div>
			<div class="col-md-6">
						<div class="row"  id="accordionFlushExample2">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone2">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-headingtwo2">
                                            <h6 class="sub-title accordiantitle">{{__("e-Signature Position")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone2" class="accordion-collapse collapse show" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample2">
                                        <div class="basicinfodiv">
											<div class="row">      
                                                <div class="col-lg-6 col-md-12 col-sm-6">
                                                    <div class="form-group">
														{{ Form::label('esign_pos_x', __('Position(X)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('esign_pos_x') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('esign_pos_x',$data->esign_pos_x,array('class'=>'form-control','id'=>'esign_pos_x','min'=>'10','max'=>'999'))
															}}
														</div>
														<span class="validate-err" id="err_esign_pos_x"></span>
													</div>
                                                </div>
												<div class="col-lg-6 col-md-12 col-sm-6">
                                                    <div class="form-group">
														{{ Form::label('esign_resolution', __('Resolution(Pixel)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('esign_resolution') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('esign_resolution',$data->esign_resolution,array('class'=>'form-control','id'=>'esign_resolution','min'=>'10','max'=>'9999'))
															}}
														</div>
														<span class="validate-err" id="err_esign_resolution"></span>
													</div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
													<div class="form-group">
														{{ Form::label('esign_pos_y', __('Position(Y)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
														<span class="validate-err">{{ $errors->first('esign_pos_y') }}</span>
														<div class="form-icon-user">
															{{
																Form::number('esign_pos_y',$data->esign_pos_y,array('class'=>'form-control','id'=>'esign_pos_y','min'=>'10','max'=>'999'))
															}}
														</div>
														<span class="validate-err" id="err_esign_pos_y"></span>
													</div>
                                                </div>

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
			<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
				<i class="fa fa-save icon"></i>
				<input type="submit" name="submit" id="save_setting" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
			</div>
		</div>
	</div>    
    {{Form::close()}}
<script src="{{ asset('js/AddSignApplications.js') }}"></script>  
<script src="{{ asset('js/ajax_validationAddSign.js') }}"></script>  

  
 
           