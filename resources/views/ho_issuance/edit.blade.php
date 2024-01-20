{{ Form::open(array('url' => 'medicine-supplies-issuance/update','class'=>'formDtls','id'=>'Issuance')) }}
{!! Form::hidden('issuance_type', $receiver_info['type'], array('id' => 'issuance_type_hide')) !!}
{!! Form::hidden('receiver_id', $receiver_info['patient_id'], array('id' => 'receiver_hide')) !!}
{!! Form::hidden('receiver_age', $receiver_info['age'], array('id' => 'receiver_age_hide')) !!}
{!! Form::hidden('position', $designation->description, array('id' => 'position_hide')) !!}
{!! Form::hidden('brgy_id', $receiver_info['barangay_id'], array('id' => 'receiver_brgy_hide')) !!}
{!! Form::hidden('issuance_status', null, array('id' => 'issuance_status')) !!}
{!! Form::hidden('issuance_code', $issuance['issuance_code'], array('issuance_code' => 'id')) !!}
{!! Form::hidden('issuance_series', $issuance['issuance_series'], array('issuance_series' => 'id')) !!}
 
<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-6 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Receiver Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
                            <div class="row">
                            <div class="col-md-8">
                                    <div class="form-group" id="receiver-group">
                                        {{ Form::label('receiver_name', __('Receiver Name.'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('receiver_name') }}</span>
                                        <div class="form-icon-user">
                                            @php
                                                $condition = ['class' => 'form-control', 'id' => 'receiver'];
                                                $disable = [];
                                                if($receiver_info['type'] == 1){
                                                    if($receiver_info['patient_id'] != ''){
                                                        $disable = ['disabled' => true];
                                                    }
                                                }
                                            @endphp
                                            {!! Form::select('receiver_name_show', 
                                                $select_receiver,
                                                $receiver_info['patient_id'], 
                                                array_merge($condition,$disable)) !!}
                                        </div>
                                        <span class="validate-err" id="err_receiver_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                            @php
                                                $condition = ['class' => 'form-control', 'id' => 'receiver_age', 'placeholder' => 'Age'];
                                                $disable = [];
                                                if($receiver_info['age'] != ''){
                                                    $disable = ['disabled' => true];
                                                }
                                            @endphp
                                        {{ Form::label('receiver_age_show', __('Age.'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('receiver_age') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('receiver_age',  
                                                $receiver_info['age'], 
                                                array_merge($condition,$disable)) !!}
                                        </div>
                                        <span class="validate-err" id="err_receiver_age"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" id="issuance-group">
                                        {{ Form::label('issuance_type', __('Type.'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('issuance_type') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::select('issuance_type_show', 
                                                [ '' => 'Select', '1' => 'Issuance', '2' => 'Withdrawal' ],
                                                $receiver_info['type'], 
                                                ['class' => 'form-control', 'id' => 'issuance', 'disabled' => 'true']) !!}
                                        </div>
                                        <span class="validate-err" id="err_issuance_type"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                            @php
                                                $condition = ['class' => 'form-control', 'id' => 'receiver_brgy', 'placeholder' => 'Barangay'];
                                                $disable = [];
                                                if($receiver_info['barangay'] != ''){
                                                    $disable = ['disabled' => true];
                                                }
                                            @endphp
                                        {{ Form::label('receiver_brgy', __('Barangay.'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('receiver_brgy') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('receiver_brgy',  
                                                $receiver_info['barangay'], 
                                                array_merge($condition,$disable)) !!}
                                        </div>
                                        <span class="validate-err" id="err_receiver_brgy"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="issuance_date-group">
                                        {{ Form::label('issuance_date_label', __('Issuance Date.'),['class'=>'form-label']) }}
                                        <span style="color: red">*</span>
                                        <span class="validate-err">{{ $errors->first('issuance_date') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::date('issuance_date', $issuance->issuance_date, ['class'=>'form-control issuance_date']) !!}
                                        </div>
                                        <span class="validate-err" id="err_issuance_date"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
        
        <div class="col-lg-6 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Personal Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="col-md-12">
                                <div class="form-group" id="hp_code-group">
                                    {{ Form::label('hp_code', __('Issued By.'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('hp_code') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('hp_code', 
                                            $select_employees_health, 
                                            $issuance->hp_code, ['class' => 'form-control hp_code', 'id' => 'hp_code']) !!}
                                    </div>
                                    <span class="validate-err" id="err_hp_code"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('position', __('Position.'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('position') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::text('position',  
                                            $designation->description, ['class' => 'form-control', 'id' => 'position', 'placeholder' => 'Positions', 'readonly' => true]) !!}
                                    </div>
                                    <span class="validate-err" id="err_position"></span>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    {{-- For Item Information --}}
    <div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
        <div class="accordion accordion-flush">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingone">
                    <button class="accordion-button  btn-primary" type="button">
                        Item Informations
                    </button>
                </h6>
                <div id="flush-collapseone" class="accordion-collapse collapse show">
                    <div class="basicinfodiv">
                        <div class="table-responsive">
                            <table class="table align-middle" id="item-table">
                                <thead>
                                    <tr>
                                        <th>Control No</th>
                                        <th>Receive Type</th>
                                        <th>Expiration Date</th>
                                        <th>Product Name & Description</th>
                                        {{-- <th>Delivery Qty</th> --}}
                                        <th>Balance Qty</th>
                                        <th>Unit</th>
                                        <th>Converted QTY</th>
                                        <th>Converted Unit</th>
                                        <th>Qty to Issue</th>
                                        <th>
                                            <div class="action-btn bg-danger ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center" 
                                                    onclick="openModal()"
                                                     title="Edit"  
                                                    data-title="Inventory Category">
                                                    <i class="ti-plus text-white"></i>
                                                </a>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @if(isset($selected_items))
                                    @php  
                                        $i=0;
                                        $new_key = $count_total_items;
                                    @endphp
                                        @foreach ($selected_items as $k=> $v)
                                        <tr class="table-row-{{ ($new_key + $k) }}">
                                            <td>{{ date('Y') .'-'. $v->control_number }}</td>
                                            <td>{{ $v->receiving == 1 ? 'Posted' : 'Saved' }}</td>
                                            <td>{{ $v->expiry_date != null ?
                                                date('Y-m-d', strtotime($v->expiry_date))  : 
                                                null }}</td>
                                            <td>{{ $v->item_name }}</td>
                                            <td>{{ $v->balance_qty }}</td>
                                            <td>{{ $v->uom_code }}</td>
                                            <td>
                                                <input type="text"
                                                name="items[{{($new_key + $k)}}][conversion_uom_qty]"
                                                class="form-control"
                                                id="conv-uom-qty{{ $new_key + $k }}"
                                                value="{{ $v->issuance_converted_quantity  }}" readonly="readonly"
                                                >
                                            </td> 
                                            <td>
                                                <select id="conv-uom{{ $new_key + $k }}"
                                                name="items[{{($new_key + $k)}}][conversion_uom]"
                                                class="form-control" 
                                                onchange="updateConvertedUOM(this.value, {{ $v->uom_id }}, {{$v->balance_qty}}, {{$new_key + $k}})">
                                                    <option value="">Select UOM</option>
                                                    @if($v->gso_conversions) 
                                                        @foreach($v->gso_conversions AS $i_conversion)
                                                            @if($i_conversion->code != null)
                                                                <option {{ $v->issuance_base_uom == $i_conversion->conversion_uom ? 'selected' : ''}}
                                                                    value="{{ $i_conversion->item_id }},{{ $i_conversion->conversion_uom }}, {{ $i_conversion->based_quantity }}, {{ $v->balance_qty }}">
                                                                    {{ $i_conversion->code }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{($new_key + $k)}}][issuance_quantity]"
                                                    value="{{ $v->issuance_quantity }}"
                                                    class="form-control item-qty{{($new_key + $k)}}" 
                                                    onkeyup="updateItemQty({{($new_key + $k)}}, this.value, {{$v->balance_qty}})" />
                                                <input type="hidden"
                                                    name="items[{{($new_key + $k)}}][ho_inv_posting_id]"
                                                    value="{{ $v->inv_id }}" 
                                                    class="form-control item-id{{($new_key + $k)}}" 
                                                    id="qty-{{ ($new_key + $k) }}" />
 
                                                <input type="hidden"
                                                    name="items[{{($new_key + $k)}}][is_parent]"
                                                    value="{{ $v->is_parent }}" 
                                                    class="form-control is-parent{{$v->is_parent}}"
                                                    id="parent-id-{{ ($new_key + $k) }}" />
                                                <input type="hidden"
                                                    name="items[{{($new_key + $k)}}][item_id]"
                                                    value="{{ $v->item_id }}" 
                                                    class="form-control item_id{{($new_key + $k)}}" />
                                                <input type="hidden"
                                                    value="{{ $v->item_name }}"
                                                    name="items[{{($new_key + $k)}}][issuance_item_name]"
                                                    class="form-control item-name{{($new_key + $k)}}" />
                                                <input type="hidden"
                                                    name="items[{{($new_key + $k)}}][issuance_uom]"
                                                    value="{{ $v->uom_id }}" 
                                                    class="form-control issuance-uom{{($new_key + $k)}}" />

                                                <input type="hidden"
                                                    name="items[{{($new_key + $k)}}][base_uom]"
                                                    value="{{ $v->base_uom }}" 
                                                    class="form-control base-uom{{($new_key + $k)}}" />
                                            </td>
                                            <td>
                                                <a href="#" 
                                                    title="{{__('Remove Row')}}"
                                                    class="btn btn-sm btn-danger" onclick="removeTR({{ ($new_key + $k) }})">
                                                    <i class="ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Tr For Adding --}}
    <table>
    @php $i=0;  @endphp
    @foreach ($items as $k => $v)
    @php 
        if($v->hrb_balance_qty !== null){
            $balance_qty = $v->hrb_balance_qty;
        }else{
            $balance_qty = $v->cip_balance_qty;
        }
    @endphp
    <tbody id="hidden-tr-{{ ($v->sl_no) }}" class="hide">
        <tr class="table-row-{{ $v->sl_no }}">
            <td>{{ date('Y') .'-'. $v->cip_control_no }}</td>
            <td>{{ $v->cip_receiving == 1 ? 'Posted' : 'Saved' }}</td>
            <td>
                @if($v->hrb_expiry_date != null)
                    {{ date('Y-m-d', strtotime($v->hrb_expiry_date)) }}
                @elseif($v->cip_expiry_date != null)
                    {{ date('Y-m-d', strtotime($v->cip_expiry_date)) }}
                @endif
            </td>
            <td>{{ $v->cip_item_name }}</td>
            <td>{{ $balance_qty }}</td>
            <td>{{ $v->uom_code }}</td>
            <td>
                <input type="text" 
                class="form-control"
                id="conv-uom-qty{{ $v->sl_no }}"
                value="{{ $balance_qty }}">
            </td>
            <td>
                <select id="conv-uom{{ $v->sl_no }}"
                class="form-control" 
                onchange="updateConvertedUOM(this.value, {{ $v->uom_id }}, {{ $balance_qty }}, {{ $v->sl_no }})">
                    <option value="">Select Converted UOM</option>
                    @if($v->gso_conversions)
                        @foreach($v->gso_conversions AS $i_conversion)
                            @if($i_conversion->code != null)
                                <option {{ $v->uom_code == $i_conversion->code ? 'selected' : ''}}
                                    value="{{ $i_conversion->item_id }}, {{ $i_conversion->conversion_uom }}, {{ $i_conversion->based_quantity }}, {{ $balance_qty }}">
                                    {{ $i_conversion->code }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>

            <td>
                <input type="number"
                    class="form-control item-qty{{($v->sl_no)}}" 
                    onkeyup="updateItemQty({{($v->sl_no)}}, this.value, {{ $v->hrb_balance_qty !=null ? $v->hrb_balance_qty : $v->cip_balance_qty }})" />
                <input type="hidden"
                    value="{{ $v->id != null ? $v->id : $v->inventory_id }}"
                    class="form-control item-id{{($v->sl_no)}}" 
                    id="qty-{{ ($v->sl_no) }}" />
                <input type="hidden"
                    value="{{ $v->item_id !=null ? $v->item_id : $v->inv_item_id }}" 
                    class="form-control item_id{{($v->sl_no)}}" />
                <input type="hidden" 
                    value="{{ $v->uom_id }}"
                    class="form-control issuance-uom{{($v->sl_no)}}" />
                <input type="hidden" 
                    value="{{ $v->base_uom }}"
                    class="form-control base-uom{{($v->sl_no)}}" />
                <input type="hidden"
                    value="{{ $v->is_parent }}"
                    class="form-control is-parent{{($v->sl_no)}}" />
            </td> 
            <td>
                <a href="#" 
                    title="{{__('Remove Row')}}"
                    class="btn btn-sm btn-danger" onclick="removeTR({{ $v->sl_no }})">
                    <i class="ti-trash"></i>
                </a>
            </td>
        </tr>
    </tbody>
    @endforeach
    </table>
    @if($issuance->issuance_status == 0)
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<button type="submit"  name="submit" value="submit" class="btn  btn-primary">
            Submit
        </button>
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <button type="submit" name="submit" id="savechanges" value="has_message" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            {{ 'Save Changes' }}
            </button>
        </div>
	</div>
    @endif
</div>
{{Form::close()}}
@include('ho_issuance.all_items')
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<!-- <script src="{{ asset('js/ajax_validation_issuance.js') }}?rand={{ rand(000,999) }}"></script> -->
<script src="{{ asset('js/ho_issuance_create.js') }}"></script>
<script>
   $(document).ready(function () {
      FormNormal()
   });
</script>