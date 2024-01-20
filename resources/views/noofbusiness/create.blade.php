{{ Form::open(array('url' => 'rptappraisers')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   <style type="text/css">
       .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 500px;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
        }
   </style>

    <div class="modal-body">

         <div class="row">
             

            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('ra_appraiser_id', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                   
                        <div class="form-icon-user">
                                    {{ Form::select('ra_appraiser_id',$arrHrEmpCode,$data->ra_appraiser_id, array('class' => 'form-control select3','id'=>'ra_appraiser_id','required'=>'required')) }}
                                    
                               
                       </div>
                    <span class="validate-err" id="err_ra_appraiser_id"></span>
                </div>
            </div>
           
           
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('ra_appraiser_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ra_appraiser_position', $data->ra_appraiser_position, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            
           
                    
</div>
       





        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addRptAppraisal.js') }}"></script>




