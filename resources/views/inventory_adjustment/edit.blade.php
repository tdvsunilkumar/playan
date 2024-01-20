{{ Form::open(array('url' => 'medicine-supplies-sdjustment/update','class'=>'formDtls')) }}
{!! Form::hidden('hia_status', null, array('id' => 'hia_status')) !!}
{!! Form::hidden('hia_id', isset($adjustment_details[0]->hia_id) ? $adjustment_details[0]->hia_id : 0, array('id' => 'hia_id')) !!}
{!! Form::hidden('hiad_series', isset($adjustment_details[0]->hiad_series) ? $adjustment_details[0]->hiad_series : 0, array('id' => 'hiad_series')) !!}

<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Stock Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-10"> 
                                    <div class="form-group">
                                        {{ Form::label('remarks_label', __('Remarks'),['class'=>'form-label']) }}
                                        <span style="color: red">*</span>
                                        <span class="validate-err">{{ $errors->first('remarks') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('remarks',
                                                $adjustments->hia_remarks, 
                                                ['class' => 'form-control', 'id' => 'remarks', 'placeholder' => 'Remarks']) !!}
                                        </div>
                                        <span class="validate-err" id="err_remarks"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{ Form::label('hia_date_label', __('Adjustment Date'),['class'=>'form-label']) }}
                                        <span style="color: red">*</span>
                                        <span class="validate-err">{{ $errors->first('hia_date') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::date('hia_date',
                                                $adjustments->hia_date, 
                                                ['class' => 'form-control', 'id' => 'hia_date']) !!}
                                        </div>
                                        <span class="validate-err" id="err_hia_date"></span>
                                    </div>
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
                                        <th>#</th>
                                        <th>Control #</th>
                                        <th>Delivery Type</th>
                                        <th>Product Name & Description</th>
                                        <th>Delivery Qty</th>
                                        <th>Balanced Qty</th>
                                        <th>Expiration Date</th>
                                        <th>Unit</th>
                                        <th>Converted Qty</th>
                                        <th>Converted Unit</th>
                                        <th>Qty to Adjust</th>
                                        <th>Remarks</th>
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
                                    @php 
                                    $i=0; 
                                    $existing_key = count($items);
                                    @endphp  
                                    @foreach ($adjustment_details as $k => $v)
                                        <tr class="table-row-{{$existing_key + 1}}">
                                            <td>{{$i + 1}}</td>
                                            <td>{{ date('Y') .'-'. $v->cip_control_no }}</td>
                                            <td>{{ $v->cip_receiving == 1 ? 'Internal' : 'External' }}</td>
                                            <td>{{ $v->cip_item_name }}</td>
                                            <td>{{ $v->cip_balance_qty }}</td>
                                            <td>{{ $v->cip_balance_qty }}</td>
                                            <td>{{ $v->cip_expiry_date != null ?
                                                date('Y-m-d', strtotime($v->cip_expiry_date)) : null }}</td>
                                            <td>{{ $v->uom_code }}</td>
                                            <td>
                                                <input type="text"
                                                name="items[{{$existing_key + 1}}][conversion_uom_qty]"
                                                class="form-control"
                                                id="conv-uom-qty{{ $existing_key + 1 }}"
                                                value="{{ $v->hiad_converted_qty  }}" readonly="readonly">
                                            </td>
                                            <td>
                                                <select id="conv-uom{{ $existing_key + 1 }}"
                                                name="items[{{$existing_key + 1}}][conversion_uom]"
                                                class="form-control" 
                                                onchange="updateConvertedUOM(this.value, {{ $v->uom_id }}, {{$v->cip_balance_qty}}, {{$existing_key + 1}})">
                                                    <option value="">Select UOM</option>
                                                    @if($v->gso_conversions) 
                                                        @foreach($v->gso_conversions AS $i_conversion)
                                                            @if($i_conversion->code != null)
                                                                <option {{ $v->hiad_base_uom == $i_conversion->conversion_uom ? 'selected' : ''}}
                                                                    value="{{ $i_conversion->item_id }},{{ $i_conversion->conversion_uom }}, {{ $v->hiad_base_qty }}, {{ $v->cip_balance_qty }}">
                                                                    {{ $i_conversion->code }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select> 
                                            </td>
                                            <td>
                                                <input type="text"
                                                    value="{{ $v->hiad_qty }}"
                                                    name="items[{{ $existing_key + 1 }}][issuance_quantity]"
                                                    placeholder="Quantity"
                                                    class="form-control item-qty{{$existing_key + 1}}" 
                                                    onkeyup="updateItemQty({{$existing_key + 1}}, {{ $v->cip_balance_qty }})" />
                                                    <small class="hide item-qty-symbol{{$existing_key + 1}}">(+ | -) Symbol Is Missing...</small>
                                                <input type="hidden"
                                                    value="{{ $v->id }}"
                                                    name="items[{{$existing_key + 1}}][ho_inv_posting_id]"
                                                    class="form-control item-id{{$existing_key + 1}}" 
                                                    id="qty-{{ $existing_key + 1 }}" />
                                                <input type="hidden"
                                                    value="{{ $v->adj_details_id }}"
                                                    name="items[{{$existing_key + 1}}][adj_details_id]" />
                                                    
                                                <input type="hidden"
                                                    name="items[{{$existing_key + 1}}][item_id]"
                                                    value="{{ $v->item_id }}" 
                                                    class="form-control item_id{{$existing_key + 1}}" />
                                                <input type="hidden"
                                                    name="items[{{ $existing_key + 1 }}][inv_cat_id]"
                                                    value="{{ $v->category_id }}" 
                                                    class="form-control category{{$existing_key + 1}}" />
                                                <input type="hidden"
                                                    name="items[{{ $existing_key + 1 }}][issuance_uom]"
                                                    value="{{ $v->uom_id }}" 
                                                    class="form-control issuance-uom{{$existing_key + 1}}" />
                                                <input type="hidden"
                                                    name="items[{{ $existing_key + 1 }}][base_uom]"
                                                    value="{{ $v->hiad_base_uom }}"
                                                    class="form-control base-uom{{$existing_key + 1}}" />
                                                <input type="hidden"
                                                    name="items[{{ $existing_key + 1 }}][is_parent]"
                                                    value="{{ $v->is_parent }}" 
                                                    class="form-control is-parent{{$existing_key + 1}}" />
                                                <input type="hidden"
                                                    name="items[{{ $existing_key + 1 }}][parent_id]"
                                                    value="{{ $v->parent_id }}" 
                                                    class="form-control parent-id{{$existing_key + 1}}" />
                                            </td> 
                                            <td>
                                                <input type="text"
                                                    name="items[{{ $existing_key + 1 }}][hiad_remarks]"
                                                    placeholder="Remarks"
                                                    required
                                                    value="{{ $v->hiad_remarks }}"
                                                    onkeyup="updateRemarks({{$existing_key + 1}}, this.value)"
                                                    class="form-control remarks{{$existing_key + 1}}" />
                                            </td>
                                            <td>
                                                <a href="#" 
                                                    title="{{__('Remove Row')}}"
                                                    class="btn btn-sm btn-danger" onclick="removeTR({{$existing_key + 1}})">
                                                    <i class="ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Tr For Adding in top Modal --}}
    <table>
    @php $i=0;  @endphp  
    @foreach ($items as $k => $v)
    <tbody id="hidden-tr-{{ ($k+1) }}" class="hide">
        <tr class="table-row-{{$k+1}}">
            <td>{{$i + 1}}</td>
            <td>{{ date('Y') .'-'. $v->cip_control_no }}</td>
            <td>{{ $v->cip_receiving == 1 ? 'Internal' : 'External' }}</td>
            <td>{{ $v->cip_item_name }}</td>
            <td>{{ $v->cip_qty_posted }}</td>
            <td>{{ $v->cip_balance_qty }}</td>
            <td>{{ $v->cip_expiry_date != null ?
                date('Y-m-d', strtotime($v->cip_expiry_date)) : null }}</td>
            <td>{{ $v->uom_code }}</td>
            <td>
                <input type="text" 
                class="form-control"
                id="conv-uom-qty{{ ($k+1) }}"
                value="{{ $v->cip_balance_qty }}">
            </td>
            <td>
                <select id="conv-uom{{ ($k+1) }}"
                class="form-control" 
                onchange="updateConvertedUOM(this.value, {{ $v->uom_id }}, {{ $v->cip_balance_qty }}, {{ ($k + 1) }})">
                    <option value="">Select UOM</option> 
                    @if($v->gso_conversions)
                        @foreach($v->gso_conversions AS $i_conversion)
                            @if($i_conversion->code != null)
                                <option {{ $v->uom_code == $i_conversion->code ? 'selected' : ''}}
                                    value="{{ $i_conversion->item_id }},{{ $i_conversion->conversion_uom }},{{ $i_conversion->based_quantity }},{{ $v->cip_balance_qty }}">
                                    {{ $i_conversion->code }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>

            <td>
                <input type="text"
                    placeholder="Quantity" 
                    class="form-control item-qty{{($k+1)}}" 
                    onkeyup="updateItemQty({{($k+1)}}, {{ $v->cip_balance_qty }})" />
                    <small class="hide item-qty-symbol{{($k+1)}}">(+ | -) Symbol Is Missing...</small>
                <input type="hidden"
                    value="{{ $v->id != null ? $v->id : $v->inventory_id }}"
                    class="form-control item-id{{($k+1)}}" 
                    id="qty-{{ ($k+1) }}" />
                <input type="hidden"
                    value="{{ $v->item_id !=null ? $v->item_id : $v->inv_item_id }}"
                    class="form-control item_id{{($k+1)}}" />
                <input type="hidden"
                    value="{{ $v->category_id }}" 
                    class="form-control category{{($k+1)}}" />
                <input type="hidden"
                    value="{{ $v->uom_id }}"
                    class="form-control issuance-uom{{($k+1)}}" />
                <input type="hidden" 
                    value="{{ $v->base_uom }}"
                    class="form-control base-uom{{($k+1)}}" />
                <input type="hidden"
                    value="{{ $v->is_parent }}" 
                    class="form-control is-parent{{($k+1)}}" />
                {{-- <input type="hidden"
                    value="{{ $v->parent_id }}" 
                    class="form-control parent-id{{$v->id}}" /> --}}
            </td>
            <td>
                <input type="text"
                    placeholder="Remarks"
                    onkeyup="updateRemarks({{($k+1)}}, this.value)"
                    class="form-control remarks{{($k+1)}}" />
            </td>
            <td>
                <a href="#" 
                    title="{{__('Remove Row')}}"
                    class="btn btn-sm btn-danger" onclick="removeTR({{$k+1}})">
                    <i class="ti-trash"></i>
                </a>
            </td>
        </tr>
    </tbody>
    @endforeach
    </table>
    @if($adjustment_details[0]->adj_details_hiad_status == 0)
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
<script src="{{ asset('js/ho_inventory_adjustments.js') }}"></script>
