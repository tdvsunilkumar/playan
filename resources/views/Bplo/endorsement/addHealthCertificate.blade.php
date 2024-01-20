@php
    $owner=(!empty($data->rpo_first_name) ? $data->rpo_first_name . ' ' : '') . (!empty($data->rpo_middle_name) ? $data->rpo_middle_name . ' ' : '') . (!empty($data->rpo_custom_last_name) ? $data->rpo_custom_last_name . ' ' : '');
@endphp
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
  th.sorting_disabled {
    width: 100 !important;
}
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class=" mt-2 " id="multiCollapseExample1">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                    {{ Form::label('busn_name', 'Business Name', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::text('busn_name', $data->busn_name, array('class' => 'form-control','id'=>"busn_name",'readonly'=>'true')) }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('owner', 'Taxpayer Name', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::text('owner', $owner, array('class' => 'form-control','id'=>"owner",'readonly'=>'true')) }}
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('t_emp', 'Total Employees', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::text('t_emp', '', array('class' => 'form-control','id'=>"t_emp",'readonly'=>'true')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="healthcert_show">
            <div class="col-sm-12">
                <div class=" mt-2 " id="multiCollapseExample1">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box" id="healthCert_div">
                                        {{ Form::label('employee_name', 'Employee Name', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::hidden('bbendo_id',$bbendo_id, array('id' => 'bbendo_id')) }}
                                        {{ Form::hidden('busn_id',$busn_id, array('id' => 'busn_id')) }}
                                        {{ Form::hidden('end_id',$end_id, array('id' => 'end_id')) }}

                                        {{ Form::select('healthCert',$healthCert,'', array('class' => 'form-control','id'=>'healthCert')) }}
                                    </div>
                                </div>

                                <div class="col-auto float-end ms-2 mt-4">
                                    <a href="#" class="btn btn-sm btn-primary" id="btn_add">
                                        <span class="btn-inner--icon">Add</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="Jq_datatablelist1">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Employee')}}</th>
                                        <th>{{__('REG-NO.')}}</th>
                                        <th>{{__('Date')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th style="width: 100px!important;">{{__('Action')}}</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

<script src="{{ asset('js/endHealthCert.js') }}"></script>





