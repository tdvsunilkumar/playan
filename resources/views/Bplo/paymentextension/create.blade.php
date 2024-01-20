{{ Form::open(array('url' => 'CtoPaymentExtensionBasis','enctype'=>'multipart/form-data','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('peb_desc', __('Legal Basis'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('peb_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('peb_desc', $data->peb_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_peb_desc"></span>
                </div>
            </div>    
            <div class="col-lg-6 col-md-6 col-sm-6"> 
                <div class="form-group">
                    {{ Form::label('peb_desc', __('Attachment'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::input('file','attached_docs','',array('class'=>'form-control'))}}  
                    </div>
                    @if(!empty($data->attached_docs))
                        <p class="attacmentDtls">
                            <a href="uploads/payment_extension/{{$data->attached_docs}}" target="_blank">View Attachment</a>
                            <a href="#" class="deleterow" id="{{$data->id}}" name="{{$data->attached_docs}}"><span class="mx-3 btn btn-sm  ti-trash"></span></a> 
                        </p>
                    @endif
                </div>         
            </div>
           

        </div> 
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/ajax_common_save.js') }}"></script>
<script src="{{ asset('js/Bplo/add_CtoPaymentExtensionBasis.js') }}"></script>

  