{{ Form::open(array('url' => 'engineering/consultantexternal','class'=>'formDtls','id'=>'storeJobService')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ept_id', __('Profession Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ept_id') }}</span>
                    <div class="form-icon-user">
					{{
						Form::select('ept_id', $ept_id, $data->ept_id, ['id' => 'ept_id', 'class' => 'form-control select', 'data-placeholder' => 'Please select','required'=>'required'])
					 }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('esp_id', __('Profession Sub Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('esp_id') }}</span>
                    <div class="form-icon-user">
					 {{
						Form::select('esp_id', $esp_id,$data->esp_id, ['id' => 'esp_id', 'class' => 'form-control select', 'data-placeholder' => 'Please select','required'=>'required'])
					 }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('firstname', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('firstname') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('firstname', $data->firstname, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('middlename', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('middlename') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('middlename', $data->middlename, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('lastname', __('Last Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('lastname') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('lastname', $data->lastname, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('fullname', __('Full name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('fullname') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('fullname', $data->fullname, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('suffix', __('Suffix'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('suffix') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('suffix', $data->suffix, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('title') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('title', $data->title, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row hide">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('gender', __('Gender'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('gender') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('gender', $data->gender, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('birthdate', __('Birthdate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('birthdate') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('birthdate', $data->birthdate, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row hide">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('house_lot_no', __('House & Lot No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('house_lot_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('house_lot_no', $data->house_lot_no, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('street_name', __('Street Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('street_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('street_name', $data->street_name, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row hide">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('subdivision', __('Subdivision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('subdivision') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('subdivision', $data->subdivision, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6 ">
                <div class="form-group">
                    {{ Form::label('brgy_code', __('Barangay'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                    <div class="form-icon-user">   
					 {{
						Form::select('brgy_code', $brgy_code, $data->brgy_code, ['id' => 'ept_id', 'class' => 'form-control select', 'data-placeholder' => 'Please select'])
					 }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row hide">
            <div class="col-md-6">
                <div class="form-group">
						{{ Form::label('country', __('Country'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('country') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('country', $data->country, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('email_address', __('Email Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('email_address') }}</span>
                    <div class="form-icon-user">
                         {{ Form::email('email_address', $data->email_address, array('type'=>'email','class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row hide">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('telephone_no', __('Telephone No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('telephone_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('telephone_no', $data->telephone_no, array('class' => 'form-control','maxlength'=>'15')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('mobile_no', __('Mobile No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('mobile_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('mobile_no', $data->mobile_no, array('class' => 'form-control','maxlength'=>'12')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ptr_no', __('PTR No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ptr_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ptr_no', $data->ptr_no, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ptr_date_issued', __('PTR Date Issued'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ptr_date_issued') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('ptr_date_issued', $data->ptr_date_issued, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('prc_no', __('PRC No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('prc_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('prc_no', $data->prc_no, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('prc_validity', __('Validity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('prc_validity') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('prc_validity', $data->prc_validity, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('prc_date_issued', __('PRC Date Issued'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('prc_date_issued') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('prc_date_issued', $data->prc_date_issued, array('class' => 'form-control','maxlength'=>'10')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div> 
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tin_no', __('TIN'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('tin_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('tin_no', $data->tin_no, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>			
        </div>
		<div class="row">
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('iapoa_no', __('IAPOA No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('tin_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('iapoa_no', $data->iapoa_no, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>	
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('iapoa_or_no', __('O.R. No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('iapoa_or_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('iapoa_or_no', $data->iapoa_or_no, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_df_desc"></span>
                </div>
            </div>		
        </div>
		<!-- <div class="row">
            			
        </div> -->
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
 
<script type="text/javascript">
      $(document).ready(function () {
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#storeJobService');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: "Are you sure?",
                    html: '<span class="red-text">Details changes will be saved</span>',
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
            var form = $('#storeJobService');
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
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
});
  </script>