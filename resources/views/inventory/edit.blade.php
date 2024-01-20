{{ Form::open(array('url' => 'Medicine-supplies-inventory/update','class'=>'formDtls', 'id' => 'inventory')) }}
{!! Form::hidden('cip_receiving', $inventory->cip_receiving, array('class' => 'cip_receiving')) !!}
{!! Form::hidden('supplier_id', $inventory->sup_id, array('class' => 'supplier_select_val')) !!}
{!! Form::hidden('cip_status', $inventory->cip_status, array('class' => 'cip_status')) !!}
{!! Form::hidden('inv_control_number', $inventory->cip_control_no, array('class' => 'cip_control_no')) !!}
{!! Form::hidden('inv_id', $inventory->id, array('class' => 'cip_control_no')) !!}
{!! Form::hidden('validation', 1, array('class' => 'validation')) !!}

<div class="modal-body">
  <div class="row pt10">
     <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
        <div class="accordion accordion-flush">
           <div class="accordion-item">
              <h6 class="accordion-header" id="flush-headingone">
                 <button class="accordion-button  btn-primary" type="button">
                  Receiving Information
                 </button>
              </h6> 
              <div id="flush-collapseone" class="accordion-collapse collapse show">
                 <div class="basicinfodiv">
                    <div class="row">

                       <div class="col-md-3">
                          <div class="form-group">
                             {{ Form::label('fam_ref_id', __('Receive Type'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('cip_receiv',
                                [ '' => 'Select', '1' => 'Internal', '2' => 'External' ]
                                , $inventory->cip_receiving, 
                                ['class' => 'form-control receiving', 'disabled' => true, 'required'=>'required']) !!}
                             </div>
                             <span class="validate-err" id="err_cip_receiving"></span>
                          </div>
                       </div>

                       <div class="col-md-3">
                          <div class="form-group" id="control-number">
                             {{ Form::label('fam_ref_id', __('Control Number'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                 @php
                                    $condition = ['class' => 'form-control control_number', 'onclick'=> 'showSelectedControl()', 'id' => 'control'];
                                    $disable = [];
                                    if($inventory->cip_receiving == 2){
                                       $disable = ['disabled' => true];
                                    }
                                 @endphp
                                {!! Form::select('control_number', 
                                $control_numbers, 
                                $inventory->cip_control_no,
                                array_merge($condition,$disable)) !!}
                             </div>
                             <span class="validate-err" id="err_control_number"></span>
                          </div>
                       </div>

                       <div class="col-md-3">
                          <div class="form-group">
                             {{ Form::label('fam_ref_id', __('Status'),['class'=>'form-label']) }}
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('status', 
                                [ '' => 'Select Status', '1' => 'Posted', '0' => 'Saved' ],
                                $inventory->cip_status ,
                                ['class' => 'form-control select3', 'disabled' => 'true']) !!}
                             </div>
                             <span class="validate-err" id="err_status"></span>
                          </div>
                       </div>

                       <div class="col-md-3">
                          <div class="form-group">
                             {{ Form::label('fam_ref_id', __('Date Received'),['class'=>'form-label']) }}
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {{ Form::date('cip_date_received',date('Y-m-d', strtotime($inventory->cip_date_received)), 
                                array('id'=>'fam_date','class' => 'form-control','required'=>'required')) }}
                             </div>
                             <span class="validate-err" id="err_cip_date_received"></span>
                          </div>
                       </div>

                    </div>
                    <div class="row">

                       <div class="col-md-{{ $inventory->cip_receiving !== 1 ? '7' : '9' }}">
                          <div class="form-group" id="supplier">
                             {{ Form::label('fam_ref_id', __('Supplier Name'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                              @php 
                                 $condition = ['class' => 'form-control supplier_select', 'id' => 'supplier_select'];
                                 $disable = [];
                                 if($inventory->cip_receiving == 1){
                                    $disable = ['disabled' => true];
                                 }
                              @endphp
                              {!! Form::select('test', $suppliers, $inventory->sup_id, array_merge($condition,$disable)) !!}
                             </div>
                             <span class="validate-err" id="err_supplier_id"></span>
                          </div>
                       </div>

                       @if($inventory->cip_receiving !== 1)
                       <div class="col-md-2" style="padding-top: 30px;">
                        <div class="action-btn bg-info">
                           <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect1 ti-reload text-white" 
                           name="stp_print"
                           onclick="getAllSuppliers()"
                           title="Refresh"></a>
                        </div>
                           <div class="action-btn bg-info">
                              <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect1 ti-plus text-white" 
                              name="stp_print" target="_black" 
                              href="{{ url('/general-services/setup-data/suppliers') }}" 
                              title="Add New Supplier"></a>
                           </div>
                       </div>
                       @endif

                       <div class="col-md-3">
                          <div class="form-group" id="cip-category">
                             {{ Form::label('fam_ref_id', __('Category'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('category_id', 
                                $categories, 
                                $inventory->inv_cat_id, 
                                ['class' => 'form-control', 'id' => 'category', 'required'=>'required']) !!}
                             </div>
                             <span class="validate-err" id="err_category_id"></span>
                          </div>
                       </div>
                    </div>
                    <div class="row">
                       <div class="col-md-12">
                          <div class="form-group">
                             {{ Form::label('fam_ref_id', __('Remarks'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::text('remarks', 
                                $inventory->cip_remarks, 
                                ['class' => 'form-control', 'placeholder' => 'Add Remarks..', 'required'=>'required']) !!}
                             </div>
                             <span class="validate-err" id="err_remarks"></span>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <div class="accordion-item">
              <h6 class="accordion-header" id="flush-headingone">
                 <button class="accordion-button  btn-primary" type="button">
                 Item Information
                 </button>
              </h6>
              <div id="flush-collapseone" class="accordion-collapse collapse show">
                @if($inventory->cip_receiving == 1)  
                  <div class="internal">
                     <div class="basicinfodiv">
                        <div class="row">
                           <div class="col-md-1 text-center" style="font-size:10px">
                              
                           </div>
                           <div class="col-md-2 text-center" style="font-size:10px">
                              Product Description
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Category
                           </div>
                           <div class="col-md-2 text-center" style="font-size:10px">
                              Qty
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px"> 
                              Unit
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Expiration Date
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Expires
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Unit Cost
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Total
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              Action
                           </div>
                        </div>
                     </div>
                     <div class="basicinfodiv internal-items-add">
                        @foreach ($inventories as $key=> $value)
                        <div class="row mt-1 int-row-{{ $key }}">
                           <div class="col-md-1 text-center">
                              {{ ($key+1) }}   
                           </div>
                           <div class="col-md-2 text-center" style="font-size:10px">
                                 <input type="text"style="padding-right: 6px;" 
                                    class="form-control" 
                                    readonly 
                                    value="{{ $value->cip_item_name }}"> 
                                 <input type="hidden" name="items[{{ $key }}][item_id]" value="{{ $value->item_id }}">
                                 <input type="hidden" name="items[{{ $key }}][item_name]" value="{{ $value->cip_item_name }}">
                                 <input type="hidden" name="items[{{ $key }}][item_code]" value="{{ $value->cip_item_code }}">
                                 <input type="hidden" name="items[{{ $key }}][uom_code]" value="{{ $value->cip_uom }}">
                                 <input type="hidden" name="items[{{ $key }}][inventory_id]" value="{{ $value->id }}">
                           </div>
                           <div class="col-md-1" style="font-size:10px">
                              <select class="form-control cat" name="items[{{ $key }}][category]">
                                  <option value="">Category</option>
                                  @foreach ($category_items as $cat)
                                      <option value="{{ $cat->id }}"
                                       {{ $cat->id == $value->inv_cat_id ? 'selected' : '' }}
                                       >
                                       {{ ucwords($cat->inv_category) }}
                                    </option>
                                  @endforeach
                              </select>
                          </div>
                           <div class="col-md-2 text-center" style="font-size:10px">
                                 <input type="text" style="padding-right: 6px;" 
                                    readonly
                                    name="items[{{ $key }}][item_quantity]" 
                                    class="form-control quantity{{ $key }}"
                                    value="{{ $value->current_qty }}">
                                    <span class="validation-error-{{ $key }}" style="display:none;color:red;">Breakdown quantity must be equal to Received quantity</span>
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px"> 
                                 <input type="text" 
                                    style="padding-right: 6px;" 
                                    readonly 
                                    name="items[{{ $key }}][uom]" 
                                    class="form-control" 
                                    value="{{ $value->uom_name }}"> 
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                                 <input type="date" 
                                    style="padding-right: 6px;" 
                                    name="items[{{ $key }}][expiry_date]"
                                    {{ isset($value->children) || $value->is_expirable != 1 ? '' : 'required' }}
                                    {{ isset($value->children) ? 'readonly' : '' }}
                                    class="form-control expiry-{{ $key }}" 
                                    value="{{ $value->cip_expiry_date != null ? date('Y-m-d', strtotime($value->cip_expiry_date)) : null }}">
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              <input type="text"
                                 readonly
                                 style="padding-right: 6px;"
                                 class="form-control" 
                                 value="{{ $value->is_expirable == 1 ? 'Yes' : 'No' }}"> 
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                                 <input type="text" 
                                    style="padding-right: 6px;" 
                                    onkeyup="updateCost({{ $key }})" 
                                    readonly name="items[{{ $key }}][unit_cost]" 
                                    class="form-control unit_cost{{$key}}" 
                                    value="{{ $value->cip_unit_cost }}">
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                                 <input type="text" 
                                    style="padding-right: 6px;" 
                                    readonly name="items[{{ $key }}][total_cost]" 
                                    class="form-control" 
                                    value="{{ ($value->cip_unit_cost * $value->cip_qty_posted) }}"> 
                           </div>
                           <div class="col-md-1 text-center" style="font-size:10px">
                              @if($value->is_expirable)
                              <a href="#" 
                                    onclick="addBreakDown({{ $value->item_id }}, {{ $value->cip_control_no }} ,{{ $key }})"
                                    class="btn btn-sm btn-primary">
                                    <i class="ti-plus"></i>
                              </a>
                              @endif
                           </div>
                        </div>
                        @if(isset($value->children))
                           @foreach ($value->children as $k => $v)
                           <div class="row mt-1 brk-{{ $k }}-0">
                              <div class="col-md-1 text-center"></div>
                              <div class="col-md-2 text-center" style="font-size:10px">
                                    <input type="hidden" name="items[{{ $key }}][breakdown][{{ $k }}][item_id]" value="{{ $v->item_id }}">
                                    <input type="hidden" name="items[{{ $key }}][breakdown][{{ $k }}][item_name]" value="{{ $v->cip_item_name }}">
                                    <input type="hidden" name="items[{{ $key }}][breakdown][{{ $k }}][item_code]" value="{{ $v->cip_item_code }}">
                                    <input type="hidden" name="items[{{ $key }}][breakdown][{{ $k }}][uom_code]" value="{{ $v->hrb_uom }}">
                                    <input type="hidden" name="items[{{ $key }}][breakdown][{{ $k }}][inventory_id]" value="{{ $v->id }}">
                              </div>
                              <div class="col-md-1" style="font-size:10px">
                                 
                              </div>
                              <div class="col-md-2 text-center" style="font-size:10px">
                                    <input type="text" style="padding-right: 6px;"
                                       name="items[{{ $key }}][breakdown][{{ $k }}][item_quantity]" 
                                       onkeyup="updateBreakDownQuantity({{ $k }}, {{ $key }}, {{ $value->current_qty }})"
                                       class="form-control
                                       quantity{{ $key }}
                                       brk-qty-{{ $key }}
                                       brk-key{{ $k }}
                                       brk-key-{{ $key }}-{{ $k }}"
                                       value="{{ $v->hrb_current_qty }}">
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px">
                                    <input type="text"
                                       style="padding-right: 6px;"
                                       readonly
                                       name="items[{{ $key }}][breakdown][{{ $k }}][uom]"
                                       class="form-control"
                                       value="{{ $v->uom_name }}">
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px">
                                    <input type="date"
                                       style="padding-right: 6px;" 
                                       name="items[{{ $key }}][breakdown][{{ $k }}][expiry_date]"
                                       required
                                       class="form-control" 
                                       value="{{ $v->hrb_expiry_date != null ? date('Y-m-d', strtotime($v->hrb_expiry_date)) : null }}"> 
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px"> 
                                 <input type="text" 
                                       style="padding-right: 6px;" 
                                       readonly
                                       class="form-control"
                                       value="Yes"> 
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px">
                                    <input type="text" 
                                       style="padding-right: 6px;" 
                                       onkeyup="updateCost(0)" 
                                       readonly name="items[{{ $key }}][breakdown][{{ $k }}][unit_cost]" 
                                       class="form-control unit_cost{{$key}}" 
                                       value="{{ $v->hrb_unit_cost }}"> 
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px">
                                    <input type="text" 
                                       style="padding-right: 6px;" 
                                       readonly name="items[{{ $key }}][breakdown][{{ $k }}][total_cost]" 
                                       class="form-control total-key-{{ $key }}-brkkey-{{ $k }}"
                                       value="{{ ($v->hrb_unit_cost * $v->hrb_qty_posted) }}"> 
                              </div>
                              <div class="col-md-1 text-center" style="font-size:10px">
                                 <a type="button" 
                                    onclick="removeBreakDown({{ $key }},{{ $k }})" 
                                    class="btn btn-danger btn-sm text-white">
                                    <i class="ti-trash"></i>
                                 </a>
                             </div>
                           </div>
                           @endforeach
                        @endif
                        @endforeach
                     </div>
                  </div>
                @elseif($inventory->cip_receiving == 2)
                  <div class="external">
                     <div class="basicinfodiv">
                        <div class="row new-item-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="">Add New Item</label>
                                 <div class="action-btn bg-info">
                                    <a class="mx-3 btn btn-sm  align-ite ms-center refeshbuttonselect1 ti-plus text-white"
                                     href="{{ url('/general-services/setup-data/item-managements') }}" name="stp_print"
                                     target="_blank"
                                     title="Add New Item"></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive ext-table">
                                 <table class="table align-middle" style="width: 120%;">
                                   <thead>
                                     <tr>
                                       <th></th>
                                       <th>Product Information</th>
                                       <th>Category</th>
                                       <th>Qty</th>
                                       <th>Unit</th>
                                       <th>Expiration Date</th>
                                       <th>Unit Cost</th>
                                       <th>Total Cost</th>
                                       <th>
                                          <div class="action-btn bg-secondary">
                                             <a class="mx-3 btn btn-sm  align-items-center ti-plus text-white" 
                                                onclick="addNewRow()" title="Refesh">
                                             </a>
                                          </div>
                                       </th>
                                     </tr>
                                   </thead>
                                   <tbody class="external-tbody">
                                    @foreach ($inventories as $ext_key => $value)
                                    <tr>
                                       <td>
                                          <div class="action-btn bg-info">
                                             <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect1 ti-reload text-white" 
                                                onclick="getAllItems({{ $ext_key }})" name="stp_print" title="Refesh"></a>
                                         </div>
                                       </td>
                                        <td>
                                            <input type="hidden" 
                                            class="item-id{{ $ext_key }}" 
                                            value="{{ $value->item_id }}"
                                            name="items_external[{{$ext_key}}][item_id]">

                                            <input type="hidden" 
                                            value="{{ $value->id }}"
                                            name="items_external[{{$ext_key}}][inventory_id]">
                                            
                                            <input type="hidden" 
                                                class="item-name{{ $ext_key }}" 
                                                value="{{ $value->cip_item_name }}"
                                                name="items_external[{{$ext_key}}][item_name]">
                                            
                                            <input type="hidden" 
                                                class="item-code{{ $ext_key }}" 
                                                value="{{ $value->cip_item_code }}"
                                                name="items_external[{{$ext_key}}][item_code]">
                        
                                            {{-- <input type="hidden"
                                                class="uom-code{{ $ext_key }}"
                                                value="{{ $value->cip_uom }}"
                                            name="items_external[{{$ext_key}}][uom_code]"> --}}
                        
                                            <div class="parent" id="cip-external-items{{ $ext_key }}">
                                                <select name="" required
                                                   class="external-items{{ $ext_key }} form-control"
                                                   id="external-items{{ $ext_key }}"
                                                   onchange="updateItem(this.value, {{ $ext_key }})">
                                                   <option value="">Select Items</option>;
                                                   @foreach ($item_details as $key => $item_detail)
                                                      <option 
                                                      {{ $item_detail->item_id == $value->item_id ? 'selected' : '' }} 
                                                      value="{{ $item_detail->item_id }}">
                                                         {{ $item_detail->item_name }}
                                                      </option>
                                                   @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                          <select name="items_external[{{$ext_key}}][category]" required
                                                class="form-control cat">
                                                   <option value="">Category</option>
                                                   @foreach ($category_items as $key => $ext_cat)
                                                      <option value="{{ $ext_cat->id }}"
                                                         {{ $ext_cat->id == $value->inv_cat_id ? 'selected' : '' }}
                                                         >
                                                         {{ ucwords($ext_cat->inv_category) }}
                                                      </option>
                                                   @endforeach
                                          </select>
                                      </td>
                                        <td>
                                            <input type="text" required
                                                onkeyup="updateCost({{ $ext_key }})"
                                                name="items_external[{{$ext_key}}][item_quantity]"
                                                value="{{ $value->cip_qty_posted }}"
                                                class="form-control qty{{ $ext_key }}" />
                                        </td>
                                        <td>
                                            <span id="uom-code{{$ext_key}}">
                                             <select
                                                   required
                                                   name="items_external[{{$ext_key}}][uom_code]" value="" 
                                                   class="form-control uom-code{{$ext_key}}">
                                                   <option value="">Select Unit</option>';
                                                   @foreach ($gso_units as $unit)
                                                      <option {{ $value->uom_code == $unit->id ? 'selected' : '' }} 
                                                         value="{{ $unit->id }}">
                                                         {{ $unit->code }}</option>
                                                   @endforeach
                                                </select>
                                            </span>
                                        </td>
                                        <td>
                                            <input type="date"
                                            name="items_external[{{$ext_key}}][expiry_date]"
                                            value="{{ $value->cip_expiry_date != null ? date('Y-m-d', strtotime($value->cip_expiry_date)) : '' }}"
                                            class="form-control" />
                                        </td>
                                        <td>
                                            <input type="text" required
                                            onextkeyup="updateCost({{ $ext_key }})"
                                            name="items_external[{{$ext_key}}][unit_cost]"
                                            value="{{ $value->cip_unit_cost }}"
                                            class="form-control unit-cost{{ $ext_key }}" />
                                        </td>
                                        <td>
                                            <input type="text"  required
                                            name="items_external[{{$ext_key}}][total_cost]" readonly
                                            value="{{ ($value->cip_unit_cost * $value->cip_qty_posted) }}"
                                            class="form-control total-cost{{ $ext_key }}" />
                                        </td>
                                        <td>
                                        <div class="action-btn bg-danger">
                                            <a class="mx-3 btn btn-sm 
                                            align-items-center ti-trash text-white btnDelete" name="stp_print" 
                                            title="Refesh"></a>
                                        </div>
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
                @endif
              </div>
           </div>
        </div>
     </div>
  </div>
  @if($inventory->cip_status == 0)
   <div class="modal-footer">
      <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
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

<script type="text/javascript">
   var receive_type = {{ $inventory->cip_receiving }};
   var external_sr_no = {{ count($inventories) }};

   // For Brekdowns
   var brk_array = [{
      column : -2,
      break_downs : [],
      validation : true
   }];

   // For Breakdowns
   var start_validation = false;
   var brk_down_current_index = 1;
   var validation = true;

   $(document).ready(function () {
      $.ajax({
            url :DIR+'get-breakdowns', // json datasource
            type: "get",
            data:{id : {{$inventory->id}}},
            success: function(response){
               brk_array = response;
               console.log(brk_array);
            }
      })
      for (let index = 0; index < external_sr_no; index++) {
         $('.uom-code'+index).select3({dropdownAutoWidth : false,dropdownParent: $('#uom-code'+index)});
         $("#external-items"+index).select3({dropdownAutoWidth : false,dropdownParent: $("#cip-external-items"+index)});
      }
      
      $("#supplier_select").select3({dropdownAutoWidth : false,dropdownParent: $("#supplier")});
      $("#control").select3({dropdownAutoWidth : false,dropdownParent: $("#control-number")});
      $("#category").select3({dropdownAutoWidth : false,dropdownParent: $("#cip-category")});
      $("#status").select3({dropdownAutoWidth : false,dropdownParent: $("#cip-status")});
      
      // While changing the Receiving 
      $('.receiving').on('change', function(){
         receive_type = $(this).val();
         $('.control_number').attr('disabled', false);
         if(receive_type == 1){
            $('.supplier_select').attr('disabled', true);
            $('.internal').show();
            $('.external').hide();
         }else{
            $('.supplier_select').attr('disabled', false);
            $('.internal').hide();
            $('.external').show();
            addNewRow();
         }
      });
      // While changing the Control No 
      $('.control_number').on('change', function(){
            if(receive_type == 1){ // If Its internal
               let control_number = $(this).val();
               $.ajax({
                     url :DIR+'get-item-details-by-control-number', // json datasource
                     type: "get",  
                     data: {control_number: control_number, receive_type : receive_type},
                     success: function(response){
                        if(response.status == 200){
                              if($('.receiving').val() == 1){
                                 $('.supplier_select').val(response.data.supplier.supplier_id).trigger('change');
                                 $('.supplier_select_val').val(response.data.supplier.supplier_id);
                                 $('.supplier_select').attr('disabled', true);
                              }
                           $('.internal-items-add').html(response.data.item_details);
                           $('.cat').val($("#category").val());
                        }
                     }
               })
            }
      });

      // While changing the category
      $("#category").change(function (e) { 
         $('.cat').val($(this).val());
      });

      $('.supplier_select').on('change', function(){
         let sup_id = $(this).val();
         $('.supplier_select_val').val(sup_id);
      });

      $(".ext-table").on('click','.btnDelete',function(){
         $(this).closest('tr').remove();
      })

      $('#form').submit(function(event) {
         event.preventDefault(); // Prevent the default form submission
         // Get the value of the clicked submit button
         var clickedButtonValue = $(document.activeElement).val();
         // Now you can perform different actions based on the clicked button value
         if (clickedButtonValue === 'Submit') {
            $('.cip_status').val(1);
            start_validation = true;
            formValidate(brk_array);
            if(validation == false){
               return false;
            }
         } else if (clickedButtonValue === 'Save Changes') {
            $('.cip_status').val(0);
         }

         $(".validate-err").html('');
         $("form input[name='submit']").unbind("click");
         var myform = $('form');
         var disabled = myform.find(':input:disabled').removeAttr('disabled');
         var data = myform.serialize().split("&");
         disabled.attr('disabled','disabled');
         var obj={};
         for(var key in data){
               obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
         }
         $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
               if(html.ESTATUS){
                  $("#err_"+html.field_name).html(html.error)
                  $("#"+html.field_name).focus();
               }else{
                  $('form').unbind('submit');
                  $("form input[name='submit']").trigger("click");
                  $("form input[name='submit']").attr("type","button");
               }
            }
         })
      });
   });

   // Updating the total cost of external Items
   updateCost = (key) =>{
      let unit_cost = parseFloat($('.unit-cost' + key).val());
      let quantity = parseFloat($('.qty' + key).val());
      if(quantity == 0){
         $('.qty' + key).val(1)
      }
      $('.total-cost' + key).val(unit_cost * quantity);
   }

   // Adding New External Items
   addNewRow = () =>{
      external_sr_no++;
      $.ajax({
            url :DIR+'get-item-details-external', // json datasource
            type: "get",  
            data: {external_sr_no: external_sr_no},
            success: function(response){
               if(response.status == 200){
                  $('.external-tbody').append(response.data.item_details);
                  $("#external-items"+external_sr_no).select3({dropdownAutoWidth : false,dropdownParent: $("#cip-external-items"+external_sr_no)});
               }
            } 
      })
   }
   // while updating the external Items
   updateItem = (item_id, key) =>{ 
      $.ajax({
            url :DIR+'get-item-details-by-item-id', // json datasource
            type: "get",  
            data: {item_id: item_id},
            success: function(response){
               if(response.status == 200){
                  $('.uom-code'+key).select3({dropdownAutoWidth : false,dropdownParent: $('#uom-code'+key)});
                  let unit_cost = response.data.item_details.unit_cost != null ? parseFloat(response.data.item_details.unit_cost) : 0;
                  let qty = response.data.item_details.qty != null ? parseFloat(response.data.item_details.qty) : 1;
                  console.log(response.data.item_details.uom_id);
                  $('.item-id'+key).val(response.data.item_details.item_id);
                  $('.item-name'+key).val(response.data.item_details.item_name);
                  $('.item-code'+key).val(response.data.item_details.item_code);
                  $('.uom-code'+key).val(response.data.item_details.uom_id);
               }
            } 
      })
   }

   getAllSuppliers = () =>{
      let loading = '<option value="">loading...</option>'
      $('.supplier_select').html(loading);
      $.ajax({
         type: "get",
         url: "{{ url('get-all-suppliers-inventory') }}",
         success: function (response) {
            $('.supplier_select').html(response.data.supplier);
         }
      });
   }

   getAllItems = (key) =>{
      let loading = '<option value="">loading...</option>'
      $('.external-items'+key).html(loading);
      $.ajax({
         type: "get",
         url: "{{ url('get-all-items-inventory') }}",
         success: function (response) {
            $('.external-items'+key).html(response.data.items);
            $('.item-id'+key).val('');
            $('.item-name'+key).val('');
            $('.item-code'+key).val('');
            $('.uom-code'+key).val('');
            $('.unit-cost'+key).val(0);
            $('.total-cost'+key).val(0);
            $('.uom'+key).val('');
            $('.qty'+key).val(0);
         }
      });
   }

   addBreakDown = (item_id,control_number, key) =>{
      $('.expiry-'+key).prop('required', false);
      $('.expiry-'+key).prop('readonly', true);
      if(brk_array.length > 1){ // If existing 
         let i = 0;
         brk_array.forEach((element, ind) => {
            if(element.column == key){
               i++;
               brk_array[ind]['break_downs'].push(1);
               brk_array[ind]['validation'] = false;
               brk_down_current_index = brk_array[ind]['break_downs'].length;
            }
         });
         if(i == 0){ // If existing but data does not match
            brk_array.push({
               column : key,
               break_downs : [1],
               validation : false
            })
         }
      }else{  // If New
         brk_array.push({
            column : key,
            break_downs : [1],
            validation : false
         })
      }
      $.ajax({
            url :DIR+'get-single-item-details/'+item_id, // json datasource
            type: "get",
            data : {key:key, brk_down_current_index:brk_down_current_index, control_number:control_number},
            success: function(response){
               if(response.status == 200){
               $('.int-row-'+response.key).after(response.data);
               brk_down_current_index = 1;
               }
            }
      })
      
      if(start_validation){
         formValidate(brk_array);
      }
      
   }

   removeBreakDown = (key, brk_key, qty) =>{
      brk_array.forEach((element, index) => {
         if(element.column == key){
            brk_array[index]['break_downs'].pop();
         }
      });
      $('.brk-'+brk_key+'-'+key).remove();

      updateBreakDownQuantity(brk_key, key, qty);
      updateParentRequiredAndReadonly(key);
      if(start_validation){
         formValidate(brk_array);
      }
      
   }

   updateParentRequiredAndReadonly = (key) =>{
      // Parent required and readonly update
      let count = $('.brk-qty-'+key).length;
      if(count == 0){
         $('.expiry-'+key).prop('required', true);
         $('.expiry-'+key).prop('readonly', false);
      }
   }

   updateBreakDownQuantity = (brk_key, key, qty) =>{
      var inputValues = 0;
      // Updating the total cost
      updateSingleRowTotal(key, brk_key);
      // If No breakdown found then validation will true for parent
      if($('.brk-qty-'+key).length == 0){
         brk_array.forEach((element, index) => {
            if(element.column == key){
               brk_array[index]['validation'] = true;
            }
         });
         return false;
      }

      $('.brk-qty-'+key).each(function() {
         inputValues+=parseInt($(this).val() == '' ? 0 : $(this).val())
      });

      if(inputValues > qty){
         $('.brk-key-'+key+'-'+brk_key).val(0);
         brk_array.forEach((element, index) => {
            if(element.column == key){
               brk_array[index]['validation'] = false;
            }
         });
      }

      // If Parent quantity is same with breakdowns
      if(inputValues == qty){
         brk_array.forEach((element, index) => {
            if(element.column == key){
               brk_array[index]['validation'] = true;
            }
         });
      }

      if(inputValues < qty){
         brk_array.forEach((element, index) => {
            if(element.column == key){
               brk_array[index]['validation'] = false;
            }
         });
      }

      if(start_validation){
         formValidate(brk_array);
      }
   }

   updateSingleRowTotal = (key, brk_key) =>{
      let unit_cost = $('.unit_cost'+key).val();
      let item_qty = $('.brk-key-'+key+'-'+brk_key).val();
      console.log(unit_cost, item_qty);
      let total  = parseFloat(unit_cost) * parseFloat(item_qty == '' ? 0 : item_qty);
      $('.total-key-'+key+'-brkkey-'+brk_key).val(total);
   }

   formValidate = (brk_array) =>{
      let i = 0;
      brk_array.forEach(element => {
         if(element.validation == false){
            i++;
            validation = false;
            $('.validation-error-'+element.column).show();
         }else{
            $('.validation-error-'+element.column).hide();
         }
      });
      if(i == 0){
         validation = true;
      }
      if(validation == false){
         $('.validation').val(null)
      }else{
         $('.validation').val(1)
      }
   }
$(document).ready(function () {

   var shouldSubmitForm = false;
   $('#savechanges').click(function (e) {
            var form = $('#inventory');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        shouldSubmitForm = true;
                        form.submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
            }
   });
	
    function checkIfFieldsFilled() {
      var form = $('#inventory');
      var requiredFields = form.find('[required="required"]');
      var isValid = true;

      requiredFields.each(function () {
            var field = $(this);
            var fieldValue = field.val();

            if (fieldValue === '') {
               isValid = false;
               return false; // Exit the loop early if any field is empty
            }
      });

      if (!isValid) {
            
      }

      return isValid;
   }
	 });
</script>
  