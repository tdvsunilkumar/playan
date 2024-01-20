{{ Form::open(array('url' => 'Medicine-supplies-inventory','class'=>'formDtls','id'=>'form')) }}
{!! Form::hidden('supplier_id', null, array('class' => 'supplier_select_val')) !!}
{!! Form::hidden('cip_status', null, array('class' => 'cip_status')) !!}
{!! Form::hidden('validation', 1, array('class' => 'validation')) !!}
<style>
   .col-md-2{
      flex: 0 0 auto !important;
      width: 16% !important;
   }
</style>
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
                          <div class="form-group" id="receiving-int-ext">
                             {{ Form::label('fam_ref_id', __('Receive Type'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('cip_receiving', [ '' => 'Select', '1' => 'Internal', '2' => 'External' ], null, ['class' => 'form-control receiving', 'id' => 'receiving']) !!}
                             </div>
                             <span class="validate-err" id="err_cip_receiving"></span>
                          </div>
                       </div>
                       <div class="col-md-3">
                          <div class="form-group" id="control-number">
                             {{ Form::label('fam_ref_id', __('Control Number'),['class'=>'form-label']) }}
                             <span style="color: red; display:none;" class="control_number_required">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('control_number', $control_numbers, null, ['class' => 'form-control control_number',  'disabled' => 'true', 'id' => 'control']) !!}
                             </div>
                             <span class="validate-err" id="err_control_number"></span>
                          </div>
                       </div>
                       <div class="col-md-3">
                          <div class="form-group" id="cip-status">
                             {{ Form::label('fam_ref_id', __('Status'),['class'=>'form-label']) }}
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('status', 
                                [ '' => 'Select Status', '1' => 'Saved', '2' => 'Posted' ], 
                                null, 
                                ['class' => 'form-control', 'disabled' => 'true', 'id' => 'status']) !!}
                             </div>
                             <span class="validate-err" id="err_status"></span>
                          </div>
                       </div>
                       <div class="col-md-3">
                          <div class="form-group">
                             {{ Form::label('fam_ref_id', __('Date Received'),['class'=>'form-label']) }}
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {{ Form::date('cip_date_received',date('Y-m-d'), array('id'=>'fam_date','class' => 'form-control','required'=>'required')) }}
                             </div>
                             <span class="validate-err" id="err_cip_date_received"></span>
                          </div>
                       </div>
                    </div>
                    <div class="row">
                       <div class="col-md-9 expand-col">
                          <div class="form-group" id="supplier">
                             {{ Form::label('fam_ref_id', __('Supplier Name'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                              {!! Form::select('test', $suppliers, null, 
                              ['class' => 'form-control supplier_select', 'id' => 'supplier_select', 'disabled' => true]) !!}
                             </div>
                             <span class="validate-err" id="err_supplier_id"></span>
                          </div>
                       </div> 
                       <div class="col-md-2 add-modal-supplier" style="padding-top: 30px; display:none;">
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
                       <div class="col-md-3">
                          <div class="form-group" id="cip-category">
                             {{ Form::label('fam_ref_id', __('Category'),['class'=>'form-label']) }}
                             <span style="color: red">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {!! Form::select('category_id', $categories, null, 
                                ['class' => 'form-control', 'id' => 'category']) !!}
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
                                {!! Form::text('remarks', '', ['class' => 'form-control', 'placeholder' => 'Add Remarks..']) !!}
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
                  <div class="internal" style="display:none;">
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
                              Expirable
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
                     <div class="basicinfodiv internal-items-add"></div>
                  </div>

                  {{-- For external --}}

                  <div class="external" style="display:none;">
                     <div class="basicinfodiv">
                        <div class="row new-item-row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="">Add New Item</label>
                                 <div class="action-btn bg-info">
                                    <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect1 ti-plus text-white" 
                                    name="stp_print" title="Add New Item" 
                                    target="_blank" 
                                    href="{{ url('/healthy-and-safety/setup-data/item-managements') }}"></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive ext-table">
                                 <table class="table align-middle" >
                                   <thead>
                                     <tr>
                                       <th></th>
                                       <th width="30%">Product Information</th>
                                       <th width="15%">Category</th>
                                       <th width="5%">Qty</th>
                                       <th width="13%">Unit</th>
                                       <th>Expiration Date</th>
                                       <th>Unit Cost</th>
                                       <th>Total Cost</th>
                                       <th>
                                          <div class="action-btn bg-secondary">
                                             <a class="mx-3 btn btn-sm  align-items-center ti-plus text-white" onclick="addNewRow()" title="Refesh"></a>
                                          </div>
                                       </th>
                                     </tr>
                                   </thead>
                                   <tbody class="external-tbody"></tbody>
                                 </table>
                               </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  
                  {{-- End of external --}}
              </div>
           </div>
        </div>
     </div>
  </div>
  <div class="modal-footer">
     <input type="button" value="{{__('Close')}}" class="btn btn-light" data-bs-dismiss="modal">
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

<script type="text/javascript">
   var receive_type; 
   var status = "";
   var external_sr_no = 0;
   var brk_array = [{
      column : -2,
      break_downs : [],
      validation : true
   }];
   var brk_down_current_index = 1;
   var validation = true;
   var start_validation = false;
   
   $(document).ready(function () {

   var shouldSubmitForm = false;
   // $('#savechanges').click(function (e) {
   //          var form = $('#inventory');
   //          var areFieldsFilled = checkIfFieldsFilled();

   //          if (areFieldsFilled) {
   //              e.preventDefault(); // Prevent the default form submission

   //              Swal.fire({
   //                  title: "Are you sure?",
   //                  html: '<span style="color: red;">This will save the current changes.</span>',
   //                  icon: 'warning',
   //                  showCancelButton: true,
   //                  confirmButtonText: 'Yes',
   //                  cancelButtonText: 'No',
   //                  reverseButtons: true,
   //                  customClass: {
   //                      confirmButton: 'btn btn-success',
   //                      cancelButton: 'btn btn-danger'
   //                  },
   //                  buttonsStyling: false
   //              }).then((result) => {
   //                  if (result.isConfirmed) {
   //                      shouldSubmitForm = true;
   //                      form.submit();
   //                  } else {
   //                      console.log("Form submission canceled");
   //                  }
   //              });
   //          }
   //      });

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
	// $('#savechanges1').click(function (e) {
   //      if (!shouldSubmitForm) {
   //          var form = $('#inventory');
   //          Swal.fire({
   //              title: "Are you sure?",
   //              html: '<span>Some Details may not be editable after saving</span>',
   //              icon: 'warning',
   //              showCancelButton: true,
   //              confirmButtonText: 'Yes',
   //              cancelButtonText: 'No',
   //              reverseButtons: true,
   //              customClass: {
   //                  confirmButton: 'btn btn-success',
   //                  cancelButton: 'btn btn-danger'
   //              },
   //              buttonsStyling: false
   //          }).then((result) => {
   //              if (result.isConfirmed) {
   //                  shouldSubmitForm = true;
   //                  $('#savechanges1').click();
   //              } else {
   //                  console.log("Form submission canceled");
   //              }
   //          });
   //          e.preventDefault();
   //      }
   //  });
      // $("#commonModal").find('.body').css({overflow: 'unset'})
      $("#receiving").select3({dropdownAutoWidth : false,dropdownParent: $("#receiving-int-ext")});
      $("#supplier_select").select3({dropdownAutoWidth : false,dropdownParent: $("#supplier")});
      $("#control").select3({dropdownAutoWidth : false,dropdownParent: $("#control-number")});
      $("#category").select3({dropdownAutoWidth : false,dropdownParent: $("#cip-category")});
      $("#status").select3({dropdownAutoWidth : false,dropdownParent: $("#cip-status")});
      
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
                        if(html.ESTATUS){
                           $("#err_"+html.field_name).html(html.error)
                           $("#"+html.field_name).focus();
                        }else{
                           $('form').unbind('submit');
                           $("form input[name='submit']").trigger("click");
                           $("form input[name='submit']").attr("type","button");
                        }
                     } else {
                           console.log("Form submission canceled");
                     }
                  });

                  

               }
         })
      });

      // While changing the Receiving
      $('.receiving').on('change', function(){
         receive_type = $(this).val();
         if(receive_type == 1){
            $('.supplier_select').attr('disabled', true);
            $('.internal').show();
            $('.external').hide();
            $('.add-modal-supplier').hide();
            $('.expand-col').removeClass('col-md-7');
            $('.expand-col').addClass('col-md-9');
            $('.control_number').attr('disabled', false);
            $('.control_number').prop('required', true);
            $('.control_number_required').show();
         }else{
            $('.supplier_select').attr('disabled', false);
            $('.internal').hide();
            $('.external').show();
            $('.add-modal-supplier').show();
            $('.expand-col').removeClass('col-md-9');
            $('.expand-col').addClass('col-md-7');
            $('.control_number').attr('disabled', true);
            $('.control_number').prop('required', false);
            $('.control_number_required').hide();
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

   });

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
   };

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
      let total  = parseFloat(unit_cost) * parseFloat(item_qty == '' ? 0 : item_qty);
      $('.total-key-'+key+'-brkkey-'+brk_key).val(total);
   }

   updateCost = (key) =>{
      let unit_cost = parseFloat($('.unit-cost' + key).val());
      let quantity = parseFloat($('.qty' + key).val());
      if(quantity == 0){
         $('.qty' + key).val(1)
      }
      $('.total-cost' + key).val(unit_cost * quantity);
   }

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
                  // $("#external-categories"+external_sr_no).select3({dropdownAutoWidth : false,dropdownParent: $("#cip-external-categories"+external_sr_no)});
                  $(".uom-code"+external_sr_no).select3({dropdownAutoWidth : false,dropdownParent: $("#uom-code"+external_sr_no)});
                  $("#external-categories"+external_sr_no).val($("#category").val());
               }
            }
      })
   }

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
                  $('.item-id'+key).val(response.data.item_details.item_id);
                  $('.item-name'+key).val(response.data.item_details.item_name);
                  $('.item-code'+key).val(response.data.item_details.item_code);
                  $('.uom-code'+key).val(response.data.item_details.uom_id);
                  // $('.uom'+key).val(response.data.item_details.uom_code);
                  // $('.unit-cost'+key).val(response.data.item_details.unit_cost);
                  // $('.total-cost'+key).val(unit_cost * qty);
                  // $('.qty'+key).val(response.data.item_details.qty == null ? 1 : response.data.item_details.qty);
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
      console.log(validation);
      if(validation == false){
         $('.validation').val(null)
      }else{
         $('.validation').val(1)
      }
   }

// formSubmit = (status_key) =>{
//    if(status == ''){
//       status = status_key;
//       $('.cip_status').val(status_key);
//    }

//    if(status_key == 1){
//       start_validation = true;
//       formValidate(brk_array);
//    }else{
//       validation = true;
//       $('.validation').val(1);
//    }
// }
</script>
