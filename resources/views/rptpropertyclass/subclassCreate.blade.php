{{ Form::open(array('url' => 'real-property/property/class/subclasssStore')) }}
   
  {{ Form::hidden('id',$subclassdata->id, array('id' => 'id')) }}
  <?php $classId=$_GET['pc_class_code'];?>
   {{ Form::hidden('pc_class_code',$classId, array('id' => 'pc_class_code')) }}
   
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript">
               
</script>
    <div class="modal-body">

         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('ps_subclass_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        <input type="hidden" id="addPropertyClassUrl" value="{{ asset('js/add_propertyclass.js') }}">
                        {{ Form::text('ps_subclass_code',$subclassdata->ps_subclass_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ps_subclass_code"></span>
                </div>
            </div>
           
            <div class="col-md-5">
               <div class="form-group">
                    {{ Form::label('ps_subclass_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ps_subclass_desc',$subclassdata->ps_subclass_desc, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('ps_is_for_plant_trees', __('Plant|Trees'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::checkbox('ps_is_for_plant_trees','1', ($subclassdata->ps_is_for_plant_trees)?true:false, array('id'=>'ps_is_for_plant_trees','class'=>'form-check-input new','style'=>'margin-top:9px')) }} 
                       
                    </div>
                    <span class="validate-err" id="err_bbef_tax_schedule"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('is_td_display', __('TD Description'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::checkbox('is_td_display','1', ($subclassdata->is_td_display)?true:false, array('id'=>'is_td_display','class'=>'form-check-input new','style'=>'margin-top:9px')) }} 
                       
                    </div>
                    <span class="validate-err" id="err_bbef_tax_schedule"></span>
                </div>
            </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <table id="example" style="text-align: center;width: 100%;height: 100px;">
            
                <tr style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
                 padding-bottom: 5px;">
                <th style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
                padding-bottom: 5px;">NO.</th>
                <th style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
                 padding-bottom: 5px;">CODE</th>
                <th style="text-align: left;padding-top: 5px;
                 padding-bottom: 5px;">DESCRIPTION</th>
                 <th style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
                padding-bottom: 5px;">PLANT|TREES </th>
                <th style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
                  padding-bottom: 5px;">ACTION</th>
                </tr>
                <?php $i=0; ?>
                 @foreach($SubclasDetails AS $subclass)
                 <?php $i=$i+1;?>
                <tr style="">
                <td>{{$i}}</td>

                <td>{{$subclass->ps_subclass_code}}</td>
                <td style="text-align: left;">{{$subclass->ps_subclass_desc}}</td>
                <td>
                    @if($subclass->ps_is_for_plant_trees==1)
                    <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>
                    @else
                    <span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>
                    @endif
                </td>
                <td>
                    <a href="#" data-size="lg" data-url="{{ url('/real-property/property/class/subclasssStore?id='. $subclass->id.'&pc_class_code='. $subclass->pc_class_code) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Sub-class')}}" class="btn btn-sm btn-warning">
                        <i class="ti-pencil"></i>
                    </a>
                    @if($subclass->ps_is_active==1)
                    <div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id={{$subclass->id}}></a>
                    @else
                    <div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id={{$subclass->id}}></a>
                    @endif
                </td>
               </tr>
               @endforeach
            
            </table>
        </div>


        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($subclassdata->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            
        </div>
    </div>

{{Form::close()}}


<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/add_propertyclass.js') }}?rand={{ rand(000,999) }}"></script>



