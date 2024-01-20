{{ Form::open(array('url' => 'inventory-category/update','id'=>'inventorycat')) }}
{{ Form::hidden('id',$category->id) }}
<style>
.modal-content {
   position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
@csrf
<div class="modal-body">
  <div class="row pt10">
     <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
        <div class="accordion accordion-flush">
           <div class="accordion-item">
              <h6 class="accordion-header" id="flush-headingone">
                 <button class="accordion-button  btn-primary" type="button">
                 Update Inventory Category
                 </button>
              </h6>
              <div id="flush-collapseone" class="accordion-collapse collapse show">
                 <div class="basicinfodiv">
                    <div class="row">
                       <div class="col-md-12">
                          <div class="form-group">
                             {{ Form::label('cat_id', __('Category Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                             <span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
                             <div class="form-icon-user">
                                {{ Form::text('inv_category',$category->inv_category, array('class' => 'form-control', 'placeholder' => 'Category Name','required'=>'required')) }}
                             </div>
                             <span class="validate-err" id="err_inv_category"></span>
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
      <input type="submit" name="submit" id="savechanges" value="{{ 'Save Changes' }}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
   </div>
  </div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {

    var shouldSubmitForm = false;
    $('#savechanges').click(function (e) {
            var form = $('#inventorycat');
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
            var form = $('#inventorycat');
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
  