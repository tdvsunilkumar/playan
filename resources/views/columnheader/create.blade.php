{{ Form::open(array('url' => 'setupcolumn-header','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
         .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
    </style>
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('pcs_id', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('pcs_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('pcs_id',$arrdepartments,$data->pcs_id, array('class' => 'form-control select3','id'=>'pcs_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_pcs_id"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('rep_header_name', __('Report Column[Header]Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rep_header_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('rep_header_name',$data->rep_header_name, array('class' => 'form-control ','id'=>'rep_header_name','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rep_header_name"></span>
                            </div>
                        </div>
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('tfoc_id', __('Tax, Fee & Other Charges'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('description') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('description',$data->description, array('class' => 'form-control disabled-field','id'=>'description')) }}
                                </div>
                                <span class="validate-err" id="err_description"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('remark', __('Remarks'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('remark') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('remark',$data->remark, array('class' => 'form-control','id'=>'remark')) }}
                                </div>
                                <span class="validate-err" id="err_remark"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-icon-user" style="color:red; font-size: 10px;">
                                   <p>NOTE: Do not modify the values without permission from the System Provider.This will change the calculation[output] of the reports.</p>
                                   <p></p>
                                   <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div> 
        
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
 <script type="text/javascript">
  $(document).ready(function (){  
    if($("#tfoc_id option:selected").val() > 0 ){
          var val = $("#tfoc_id option:selected").val();
          getserviceName(val);
     }

     $('#tfoc_id').on('change', function() {
        var id =$(this).val();
         getserviceName(id);
     });

    $('#pcs_id').on('change', function() {
      var pcs_id =$(this).val();
      $.ajax({
        url :DIR+'setupcolumn-header/getTaxfessoption', // json datasource
        type: "POST", 
        data: {
          "pcs_id": pcs_id, "_token": $("#_csrf_token").val(),dataType: "html",
        },
        success: function(html){
          $("#tfoc_id").html(html)
         }
       })
    });


    function  getserviceName(aglcode){
     var id =aglcode;
       $.ajax({
            url :DIR+'cpdoservice/getserviceName', // json datasource
            type: "POST", 
            data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
                var arr = html.split('#');
               $("#description").val(arr[0]);
               $("#cs_amount").val(arr[1]);
            }
        })
   } 
     
  });
 </script>
  
 
           