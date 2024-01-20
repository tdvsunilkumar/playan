{{ Form::open(array('url' => 'real-property/property/class/store','id'=>'service')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
<style>
    
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
        float: left;
        margin-left: 50%;
        margin-top: 50%;
        transform: translate(-50%, -50%);
  }
   
   
 </style>
    <div class="modal-body">

         <div class="row">
            
                    
                

            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('pc_class_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('pc_class_code', $data->pc_class_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_class_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('pc_class_no', __('Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('pc_class_no', $data->pc_class_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_class_no"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('pc_class_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('pc_class_description', $data->pc_class_description, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_class_description"></span>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group" id="div_pc_unit_value_option">
                    {{ Form::label('pc_unit_value_option', __('Unit Value Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('pc_unit_value_option',array('' =>'Select Unit Value','1' =>'Scheduled By District','2' =>'Scheduled By Property Location'), $data->pc_unit_value_option, array('class' => 'form-control spp_type select3','id'=>'pc_unit_value_option','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_unit_value_option"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group" id="div_pc_taxability_option">
                    {{ Form::label('pc_taxability_option', __('Taxability Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('pc_taxability_option',array('' =>'Select Taxability','1' =>'Taxable','2' =>'Exempt',), $data->pc_taxability_option, array('class' => 'form-control spp_type select3','id'=>'pc_taxability_option','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pc_taxability_option"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group" id="div_class_id">
                    {{ Form::label('class_id', __('Category[Dashboard Reports]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('class_id',$rpt_class, $data->class_id, array('class' => 'form-control spp_type select3','id'=>'class_id')) }}
                    </div>
                    <span class="validate-err" id="err_class_id"></span>
                </div>
            </div>
              
          
                    
</div>
      
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Svae Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script>
$(document).ready(function(){
    $("#commonModal").find('.body').css({overflow: 'unset'});
    $("#class_id").select3({dropdownAutoWidth : false,dropdownParent: $("#div_class_id")});
    $("#pc_taxability_option").select3({dropdownAutoWidth : false,dropdownParent: $("#div_pc_taxability_option")});
    $("#pc_unit_value_option").select3({dropdownAutoWidth : false,dropdownParent: $("#div_pc_unit_value_option")});  
});
</script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    $('#savechanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'rptpropertyclass/formValidation', // json datasource
            type: "POST", 
            data: $('#service').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
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
                    $('#service').submit();
                    form.submit();
                    // location.reload();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                    
                }
            }
        })
     
   });
});


</script>




