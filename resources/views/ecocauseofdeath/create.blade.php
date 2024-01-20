{{ Form::open(array('url' => 'ecocauseofdeath')) }}
   
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

         <div class="row">
            
            
            
                  
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('cause_of_death', __('Cause of death'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     {{ Form::text('cause_of_death', $data->cause_of_death, array('class' => 'form-control')) }}
                    <div class="form-icon-user">
                       
                    </div>
                    <span class="validate-err" id="err_cause_of_death"></span>
                </div>
            </div>
            
             
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::textarea('remarks', $data->remarks, array('class' => 'form-control','rows'=>'2')) }}

                    </div>
                    <span class="validate-err" id="err_remarks"></span>
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
  // select3Ajax("brgy_id","accordionFlushExample","getBarngayNameList");
});
</script>

