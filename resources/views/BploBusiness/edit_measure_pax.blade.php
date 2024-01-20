{{ Form::open(array('url' => 'business-permit/application/update_measure_pax','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
         .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
    .modal-lg, .modal-xl {
    max-width: 975px !important;
  }
    </style>

<div class="modal form fade" id="edit-measure-pax" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="departmentalRequisitionLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">

    <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="departmentalRequisitionLabel">Manage Application</h5>
            </div>
    <div class="modal-body">
        <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('buspx_no_units', 'Number of Units', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'buspx_no_units', $value = '0', 
                                            $attributes = array(
                                                'id' => 'buspx_no_units',
                                                'class' => 'form-control form-control-solid numeric-only'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('buspx_capacity', 'Capacity', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'buspx_capacity', $value = '0.00', 
                                            $attributes = array(
                                                'id' => 'buspx_capacity',
                                                'class' => 'form-control form-control-solid numeric-only'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('busn_psic_id', 'Line of Business', ['class' => '']) }}
                                        {{
                                            Form::select('busn_psic_id', $bplo_busn, $value = '', ['id' => 'busn_psic_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('buspx_charge_id', 'Measure or Pax', ['class' => '']) }}
                                        {{
                                            Form::select('buspx_charge_id', $measure_pax, $value = '', ['id' => 'buspx_charge_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="Save" class="btn  btn-primary">
        </div>
    </div>
</div> 
</div>
    </div>
</div> 
{{Form::close()}}

<script src="{{ asset('js/hoapphealthcert.js') }}"></script>
<script src="{{ asset('js/add_hoapphealthcert.js') }}"></script>



