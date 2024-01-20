{{ Form::open(array('url' => 'hemarange')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style type="text/css">
   .modal-content {
   position: relative;
   display: flex;
   flex-direction: column;
   width: 800px;
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
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('chp_id', __('Parameter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               <!-- {{ Form::text('chp_id', $data->chp_id, array('class' => 'form-control','required'=>'required')) }} -->
               {{ Form::select('chp_id',$arrchpCode,$data->chp_id, array('class' => 'form-control select3','id'=>'loc_local_code')) }}
            </div>
            <span class="validate-err" id="err_bbef_code"></span>
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('chc_id', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               <!-- {{ Form::text('chc_id', $data->chc_id, array('class' => 'form-control','required'=>'required')) }} -->
               {{ Form::select('chc_id',$arrchcCode,$data->chc_id, array('class' => 'form-control select3','id'=>'loc_local_code')) }}
            </div>
            <span class="validate-err" id="err_bbef_code"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('chr_range', __('Ref.Range'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::text('chr_range', $data->chr_range, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <span class="validate-err" id="err_bbef_code"></span>
         </div>
      </div>
   </div>
</div>
<div class="modal-footer">
   <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
   <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
      <i class="fa fa-save icon"></i>
      <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
   </div>
   <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>