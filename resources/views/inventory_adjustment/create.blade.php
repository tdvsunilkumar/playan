{{ Form::open(array('url' => 'medicine-supplies-sdjustment/add','class'=>'formDtls', 'id'=>'adjustment')) }}
{!! Form::hidden('hia_status', null, array('id' => 'hia_status')) !!}

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
                                                null, 
                                                ['class' => 'form-control', 'id' => 'remarks', 'placeholder' => 'Remarks', 'required'=>'required']) !!}
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
                                                date('Y-m-d'), 
                                                ['class' => 'form-control', 'id' => 'hia_date','required'=>'required']) !!}
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
                                        {{-- <th>#</th> --}}
                                        <th>Control #</th>
                                        <th>Delivery Type</th>
                                        <th>Product Name & Description</th>
                                        {{-- <th>Delivery Qty</th> --}}
                                        <th>Balance Qty</th>
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
                                <tbody id="tbody"></tbody>
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
    @php 
        if($v->hrb_balance_qty !== null){
            $balance_qty = $v->hrb_balance_qty;
        }else{
            $balance_qty = $v->cip_balance_qty;
        }
    @endphp
    <tbody id="hidden-tr-{{ ($k+1) }}" class="hide">
        <tr class="table-row-{{ ($k+1) }}">
            {{-- <td>{{($k+1)}}</td> --}}
            <td>{{ date('Y') .'-'. $v->cip_control_no }}</td>
            <td>{{ $v->cip_receiving == 1 ? 'Internal' : 'External' }}</td>
            <td>{{ $v->cip_item_name }}</td>
            {{-- <td>{{ $v->cip_qty_posted }}</td> --}}
            <td>{{ $balance_qty }}</td>
            <td>
                @if($v->hrb_expiry_date != null)
                    {{ date('Y-m-d', strtotime($v->hrb_expiry_date)) }}
                @else
                    {{ date('Y-m-d', strtotime($v->cip_expiry_date)) }}
                @endif
            </td>
            <td>{{ $v->uom_code }}</td>
            <td>
                <input type="text" 
                class="form-control"
                id="conv-uom-qty{{ ($k+1) }}"
                value="{{ $balance_qty }}" readonly="readonly"  >
            </td>
            <td>
                <select id="conv-uom{{ ($k+1) }}"
                class="form-control" 
                onchange="updateConvertedUOM(this.value, {{ $v->uom_id }}, {{ $balance_qty }}, {{ ($k + 1) }})">
                    <option value="">Select UOM</option> 
                    @if($v->gso_conversions)
                        @foreach($v->gso_conversions AS $i_conversion)
                            @if($i_conversion->code != null)
                                <option {{ $v->uom_code == $i_conversion->code ? 'selected' : ''}}
                                    value="{{ $i_conversion->item_id }},{{ $i_conversion->conversion_uom }},{{ $i_conversion->based_quantity }},{{ $balance_qty }}">
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
</div> 
{{Form::close()}}
@include('ho_issuance.all_items')
<script src="{{ asset('js/ho_inventory_adjustments.js') }}"></script> 
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<script>
   $(document).ready(function () {
      FormNormal()
   });
</script>