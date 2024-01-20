{{ Form::open(array('url' => 'CtoTfocComputationBasis','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        accordion-button:not(.collapsed)::after, .accordion-button::after {
             background-image: unset !important;
        }
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tfoc_id', __('TFOC Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tfoc_id',$arrTFOC,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div>
            <h6 class="accordion-header" id="flush-headingfour">
                <button class="accordion-button  btn-primary" type="button">
                    <h6 class="sub-title accordiantitle" style="padding-top: 5px;">{{__('Basis Of Computation')}}</h6>
                </button>
            </h6><br><br><br>
            <div class="row" style="padding-left: 20px;">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-icon-user">
                             {{ Form::select('basis_ids[]',$arrTFOCBasis,$data->basis_ids, array('class' => 'form-control select3','id'=>'basis_ids','multiple')) }}
                        </div>
                        <span class="validate-err" id="err_tfoc_id"></span>
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
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
  