{{ Form::open(array('url' => 'real-property/property/class/actualUseStore')) }}
   
  {{ Form::hidden('id',$actualdata->id, array('id' => 'id')) }}
  <?php $classId=$_GET['pc_class_code'];?>
   {{ Form::hidden('pc_class_code',$classId, array('id' => 'pc_class_code')) }}
   
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript">
               
</script>
    <div class="modal-body">

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('pau_actual_use_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        <input type="hidden" id="addPropertyClassUrl" value="{{ asset('js/add_propertyclass.js') }}">
                        {{ Form::text('pau_actual_use_code',$actualdata->pau_actual_use_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_class_code"></span>
                </div>
            </div>
           
           
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('pau_actual_use_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('pau_actual_use_desc',$actualdata->pau_actual_use_desc, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('pau_with_land_stripping', __('Land Stripping'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::checkbox('pau_with_land_stripping','1', ($actualdata->pau_with_land_stripping)?true:false, array('id'=>'pau_with_land_stripping','class'=>'form-check-input new','style'=>'margin-top:9px')) }} 
                       
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
                      padding-bottom: 5px;">LAND STRIPPING</th>
                <th style="border: ;color: #fff;background:#20b7cc;padding-top: 5px;
    padding-bottom: 5px;">ACTION</th>
                </tr>
                <?php $i=0; ?>
                 @foreach($ActualDetails AS $actual)
                 <?php $i=$i+1;?>
                <tr style="">
                <td>{{$i}}</td>
                <td>{{$actual->pau_actual_use_code}}</td>
                <td style="text-align: left;">{{$actual->pau_actual_use_desc}}</td>
                <td>
                    @if($actual->pau_with_land_stripping==1)
                    <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>
                    @else
                    <span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>
                    @endif
                </td>
                <td>
                    <a href="#" data-size="lg" data-url="{{ url('/real-property/property/class/actualUseStore?id='. $actual->id.'&pc_class_code='. $actual->pc_class_code) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Sub-class')}}" class="btn btn-sm btn-warning">
                        <i class="ti-pencil"></i>
                    </a>
                    @if($actual->pau_is_active==1)
                    <div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactiveActual ti-trash text-white text-white" name="stp_print" value="0" id={{$actual->id}}></a>
                    @else
                    <div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactiveActual ti-reload text-white"  name="stp_print" value="1" id={{$actual->id}}></a>
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
                <input type="submit" name="submit" value="{{ ($actualdata->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            
            
        </div>
    </div>

{{Form::close()}}

<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/add_propertyclass.js') }}?rand={{ rand(000,999) }}"></script>
<!-- <script src="{{ asset('js/RptPropertySubclass.js') }}"></script> -->


