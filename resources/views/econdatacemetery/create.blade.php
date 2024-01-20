{{ Form::open(array('url' => 'EcoDataCemetery')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    
  
   <style type="text/css">
    
    .modal.show .modal-dialog {
            transform: none;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
			
        }
       .modal-content {
		position: relative;
		display: flex;
		flex-direction: column;
		width: 80%;
		pointer-events: auto;
		background-color: #ffffff;
		background-clip: padding-box;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 15px;
		outline: 0;
	    float: left;
	    margin-left: 50%;
	    margin-top: 50%;
	    transform: translate(-50%, -50%);
  }
   </style>

    <div class="modal-body">

         <div class="row" id="modalform">
               <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('brgy_id', __('Barangay Location'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user" id="accordionFlushExample">
                        {{ Form::select('brgy_id',$barangay,$data->brgy_id, array('class' => 'form-control','id'=>'brgy_id')) }}
                        
                    </div>
                    <span class="validate-err" id="err_brgy_id"></span>
                </div>
            </div>   
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cem_name', __('Cemetery Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     {{ Form::text('cem_name', $data->cem_name, array('class' => 'form-control')) }}
                    <div class="form-icon-user">
                       
                    </div>
                    <span class="validate-err" id="err_cem_name"></span>
                </div>
            </div>
            
             
            <div class="col-md-12" style="margin-bottom:150px;">
                <div class="form-group">
                    {{ Form::label('remark', __('Remarks'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::textarea('remark', $data->remark, array('class' => 'form-control','rows'=>'2')) }}

                    </div>
                    <span class="validate-err" id="err_remark"></span>
                </div>
            </div>
            
            
               
              
              
            
    </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#brgy_id").select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample")});
  // select3Ajax("brgy_id","accordionFlushExample","getBarngayNameList");
});
</script>

