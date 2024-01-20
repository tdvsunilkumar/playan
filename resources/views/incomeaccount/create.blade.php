{{ Form::open(array('url' => 'tax-revenue/store','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="modal-body">
                    <div class="row">

                        
                        
                         <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('agl_code', __('Chart of Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     
                                     {{ Form::select('agl_code',$arrAccGenLedCode,$data->agl_code, array('class' => 'form-control select3','id'=>'agl_code','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tia_fund_code"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('fund_code', __('Fun'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     
                                     {{ Form::select('fund_code',$arrFunCode,$data->fund_code, array('class' => 'form-control select3','id'=>'fund_code','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tia_fund_code"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('tia_account_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::text('tia_account_code', $data->tia_account_code, array('class' => 'form-control')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_tia_fund_code"></span>
                            </div>
                        </div>
                         
                          <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('tia_account_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                   {!! Form::textarea('tia_account_description',$data->tia_account_description, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
                                </div>
                                <span class="validate-err" id="err_tia_account_description"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('tia_account_short_name', __('Short Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::text('tia_account_short_name', $data->tia_account_short_name, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tia_account_short_name"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('tia_initial_amount', __('Amount'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_initial_amount', $data->tia_initial_amount, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('tia_applicable', __('Applicable To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     
                                     {{ Form::select('tia_applicable',$arrDepCode,$data->tia_applicable, array('class' => 'form-control select3','id'=>'tia_applicable','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tia_account_short_name"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('tia_remarsk', __('Remarks'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::text('tia_remarsk', $data->tia_remarsk, array('class' => 'form-control')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                        </div>
                    
                    
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_tax_cy', __('Tax Current Year'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_tax_cy', $data->tia_tax_cy, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_tax_py', __('Tax Present Year'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_tax_py', $data->tia_tax_py, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_discount_code', __('Discount Code'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_discount_code', $data->tia_discount_code, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_penalty_cy', __('Penalty Current Year'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_penalty_cy', $data->tia_penalty_cy, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_penalty_py', __('Penalty Present Year'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_penalty_py', $data->tia_penalty_py, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('tia_tax_credit', __('Tax Credit'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::number('tia_tax_credit', $data->tia_tax_credit, array('class' => 'form-control','step'=>'0.01')) }}
                                </div>
                                 <span class="validate-err" id="err_tia_account_code"></span>
                            </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
   <script src="{{ asset('js/ajax_validation.js') }}"></script>
