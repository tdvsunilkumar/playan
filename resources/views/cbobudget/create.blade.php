{{ Form::open(array('url' => 'cbobudget')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
   .modal-lg {
   max-width: 1200px !important;
   }
   .accordion-button{
   margin-bottom: 12px;
   }
   .form-group{
   margin-bottom: unset;
   }
   .form-group label {
   font-weight: 600;
   font-size: 12px;
   }
   .form-control, .custom-select{
   padding-left: 5px;
   font-size: 12px;
   }
   .pt10{
   padding-top:10px;
   }
   .field-requirement-details-status {
   border-bottom: 1px solid #f1f1f1;
   font-size: 13px;
   color: black;
   background: #8080802e;
   text-transform: uppercase;
   margin: 20px 0px 6px 0px;
   margin-top: 20px;
   }
   .choices__inner {
   min-height: 35px;
   padding:5px ;
   padding-left:5px;
   }
   .field-requirement-details-status label{margin-top: 7px;}
   #flush-collapsetwo{
   /*        padding-bottom: 80px;*/
   }
</style>
<!-- <script>
   $("#bud_year").datepicker({
           format: "yyyy",
           viewMode: "years", 
           minViewMode: "years",
           autoclose:true //to close picker once year is selected
       });
   </script> -->
<div class="modal-body">
   <div class="row budgetmain">
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('bud_year', __('Budget Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::number('bud_year','2023',array('class' => 'form-control','required'=>'required','id'=>'selectYear')) }}
            </div>
            <span class="validate-err" id="err_bbef_code"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('fc_code', __('Fund'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::select('fc_code',$arrfcCode,$data->fc_code, array('class' => 'form-control select3 
               ','id'=>'fc_code','required'=>'required')) }}
            </div>
            <span class="validate-err" id="err_tax_class_id"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('Total', __('Total'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user currency">
               {{ Form::number('Budget Ceiling[]','',array('class' => 'form-control totalbudget','id'=>'ceiling')) }}
               <div class="currency-sign"><span>Php</span></div>
            </div>
            <span class="validate-err" id="err_bbef_code"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('dept_id', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::select('dept_id',$arrdeptCode,$data->dept_id, array('class' => 'form-control select3 
               ','id'=>'dept_id','required'=>'required')) }}
            </div>
            <span class="validate-err" id="err_dept_id"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('ddiv_id', __('Division'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::select('ddiv_id',$arrdivCode,$data->ddiv_id, array('class' => 'form-control select3 
               ','id'=>'ddiv_id','required'=>'required')) }}
            </div>
            <span class="validate-err" id="err_ddiv_id"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group" style="padding-top: 23px;">
            @if($data->id && $data->budget_status == 1)
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Draft'):__('Draft')}}" class="btn btn-success" disabled="true">
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="submit" data-propertyid="{{ ($data->id) }}" disabled="true" >Submit</span>
            <span class="btn btn-info updatecodefunctionality" value="{{ ($data->id) }}" data-actionname="approve" data-propertyid="{{ ($data->id) }}" >Approve</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="unlock" data-propertyid="{{ ($data->id) }}" >Unlock</span>
            @elseif($data->id && $data->budget_status == 2)
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Draft'):__('Draft')}}"  class="btn btn-success" disabled="true">
            <span class="btn btn-info"  style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" data-actionname="submit" data-propertyid="{{ ($data->id) }}" disabled="true">Submit</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="approve" data-propertyid="{{ ($data->id) }}" >Approve</span>
            <span class="btn btn-info updatecodefunctionality" value="{{ ($data->id) }}" data-actionname="unlock" data-propertyid="{{ ($data->id) }}" >Unlock</span>
            
            @elseif($data->id && $data->budget_status == 3)
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Draft'):__('Draft')}}"  class="btn btn-info">
            <span class="btn btn-info updatecodefunctionality" value="{{ ($data->id) }}" data-actionname="submit" data-propertyid="{{ ($data->id) }}">Submit</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="approve" data-propertyid="{{ ($data->id) }}" >Approve</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="unlock" data-propertyid="{{ ($data->id) }}" >Unlock</span>
            @elseif($data->id)
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Draft'):__('Draft')}}"  class="btn btn-info">
            <span class="btn btn-info updatecodefunctionality" value="{{ ($data->id) }}" data-actionname="submit" data-propertyid="{{ ($data->id) }}"  >Submit</span>
            <span class="btn btn-info updatecodefunctionality" value="{{ ($data->id) }}" data-actionname="approve" data-propertyid="{{ ($data->id) }}" >Approve</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="unlock" data-propertyid="{{ ($data->id) }}" >Unlock</span>
            @else
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Draft'):__('Draft')}}" class="btn btn-info">
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="submit" data-propertyid="{{ ($data->id) }}" >Submit</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="approve" data-propertyid="{{ ($data->id) }}" >Approve</span>
            <span class="btn btn-info" style="background: #9edd84; border: 0px;" value="{{ ($data->id) }}" value="{{ ($data->id) }}" data-actionname="unlock" data-propertyid="{{ ($data->id) }}" >Unlock</span>
            @endif
         </div>
      </div>
      <div class="row totaldata" >
         <div class="row field-requirement-details-status">
            <div class="col-lg-4 col-md-4 col-sm-4">
               {{Form::label('agl_id',__('GL Description'),['class'=>'form-label'])}}
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               {{Form::label('assessment_level',__('Quarterly'),['class'=>'form-label numeric'])}}
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               {{Form::label('is_active',__('Annual'),['class'=>'form-label numeric'])}}
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               {{Form::label('is_active',__('Total'),['class'=>'form-label numeric'])}}
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <input type="button" id="btn_addmore_nature" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
            </div>
         </div>
         @php $i=0; @endphp
        @foreach($arrNature as $key=>$val)
         <span class="natureDetails nature-details" id="natureDetails">
            <div class="row removenaturedata pt10">
               <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                     <div class="form-icon-user">
                        {{ Form::hidden('relationId[]',$val['id'], array('id' => 'relationId'.$i)) }}
                        {{ Form::select('agl_id[]',$arraglCode,$val['agl_id'], array('class' => 'form-control','id'=>'agl_id'.$i,'required'=>'required')) }}
                     </div>
                     <span class="validate-err" id="err_agl_id"></span>
                  </div>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-2">
                  <div class="form-group">
                     <div class="form-icon-user currency ">
                        {{ Form::number('bud_budget_quarter[]',$val['bud_budget_quarter'],array('class' => 'form-control getannual','id'=>'num','required'=>'required')) }}
                        <div class="currency-sign"><span>Php</span></div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-2">
                  <div class="form-group">
                     <div class="form-icon-user currency">
                        {{ Form::number('bud_budget_annual[]',$val['bud_budget_annual'],array('class' => 'form-control bud_budget_annual','id'=>'anual_num','required'=>'required')) }}
                        <div class="currency-sign"><span>Php</span></div>
                     </div>
                  </div>
               </div>
               <!-- $total = (($val['bud_budget_annual']) + ($val['bud_budget_quarter']));
                  printr($total); -->
               <div class="col-lg-2 col-md-2 col-sm-2">
                  <div class="form-group">
                     <div class="form-icon-user currency">
                        {{ Form::number('bud_budget_total[]',$val['bud_budget_annual'],array('class' => 'form-control bud_budget_total','id'=>'total_num','required'=>'required')) }}
                        <div class="currency-sign"><span>Php</span></div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-1">
                  <input type="button" name="btn_cancel_nature" class="btn btn-success btn_cancel_nature delete" id="" value="Delete" style="padding: 0.4rem 1rem !important;">
               </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#agl_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});
                });
            </script>
            @php $i++; @endphp
            @endforeach 
         </span>
      </div>
      <!-- <div class="modal-footer">
         <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
         </div> -->
   </div>
</div>
{{Form::close()}}
@php 
$i=(count($arrNature)>0)?count($arrNature):0;
@endphp
<div id="hidennatureHtml" class="hide">
   <div class="removenaturedata row pt10">
      <div class="col-lg-4 col-md-4 col-sm-4">
         <div class="form-group">
            <div class="form-icon-user">
               {{ Form::hidden('relationId[]','', array('id' => 'relationId')) }}
               {{ Form::select('agl_id[]',$arraglCode,'', array('class' => 'form-control','id'=>'agl_id'.$i)) }}
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user currency increment">
               {{ Form::number('bud_budget_quarter[]','',array('class' => 'form-control getannual','id'=>'num1')) }}
               <div class="currency-sign"><span>Php</span></div>
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user currency">
               {{ Form::number('bud_budget_annual[]','',array('class' => 'form-control bud_budget_annual','id'=>'anual_num1')) }}
               <div class="currency-sign"><span>Php</span></div>
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user currency">
               {{ Form::number('bud_budget_total[]','',array('class' => 'form-control bud_budget_total','id'=>'total_num1')) }}
               <div class="currency-sign"><span>Php</span></div>
            </div>
         </div>
      </div>
      <div class="col-sm-1">
         <input type="button" name="btn_cancel"  class="btn btn-success btn_cancel_nature delete" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
      </div>
   </div>
</div>

<script src="{{ asset('js/addcbobudget.js') }}"></script>
<script src="{{ asset('js/ajax_validationbudget.js') }}"></script>  
