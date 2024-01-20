<div class="container-fluid">
    {{ Form::open(array('url' => 'hr-payroll-calculate/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        {{ Form::hidden('btn',0, array('id' => 'btn_send')) }}
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('hrcp_id', __('Payroll Period'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('hrcp_id') }}</span>
                        <div class="form-icon-user" id="select-contain-cutoff">
                            {{-- Form::text('hrcp_id',$data->hrcp_id, array('class' => 'form-control','id'=>'hrcp_id','required'=>'required')) --}}
                            {{ 
                                Form::select('hrcp_id', 
                                    [],
                                    $data->hrcp_id, 
                                    $attributes = array(
                                    'id' => ($data->id) ? 'hrcp_id_name' : 'hrcp_id',
                                    'data-url' => 'hr-payroll-calculate/selectCutoff',
                                    'data-contain' => 'select-contain-cutoff',
                                    'data-value' => isset($data->payroll_desc) ? $data->payroll_desc : '',
                                    'data-value_id' => $data->hrcp_id,
                                    'class' => 'form-control ajax-select',
                                    ($data->id) ? 'readonly' : ''
                                )) 
                            }}
                            @if($data->id)
                                {{ Form::hidden('hrcp_id',$data->hrcp_id, array('id' => 'hrcp_id')) }}
                            @endif
                        </div>
                        <span class="validate-err" id="err_hrcp_id"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('hrpr_payroll_no', __('Payroll No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('hrpr_payroll_no') }}</span>
                        <div class="form-icon-user">
                            {{ Form::text('hrpr_payroll_no',
                                $data->hrpr_payroll_no, 
                                array(
                                    'class' => 'form-control',
                                    'id'=>'hrpr_payroll_no',
                                    'required'=>'required',
                                    'readonly'
                                    )) }}
                        </div>
                        <span class="validate-err" id="err_hrpr_payroll_no"></span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group" id="hra_department_id_div">
                        {{ Form::label('hra_department_id', __('Department'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('hrpr_department_id') }}</span>
                        <div class="form-icon-user">
                            {{ Form::select('hrpr_department_id',
                                $department,
                                $data->hrpr_department_id, 
                                array(
                                    'class' => 'form-control',
                                    'id'=>'hra_department_id',
                                    'required'=>'required',
                                    ($data->id) ? 'readonly' : ''
                                    )) }}
                        </div>
                        <span class="validate-err" id="err_hra_department_id"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('hra_division_id', __('Division'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('hrpr_division_id') }}</span>
                        <div class="form-icon-user" id="hra_division_id_div">
                            @if($data->id)
                                {{ Form::text('hrpr_division_id_name',
                                    $data->appointment->employee->division->name, 
                                    array(
                                        'class' => 'form-control',
                                        'id'=>'hrpr_division_id_name',
                                        'required'=>'required',
                                        'disabled'
                                        )) }}
                                {{ Form::hidden('hrpr_division_id',
                                    $data->hrpr_division_id) }}
                            @else
                            {{ Form::select('hrpr_division_id',
                                ['Please Select'],
                                $data->hrpr_division_id, 
                                array(
                                    'class' => 'form-control employee-filter',
                                    'id'=>'hra_division_id',
                                    'required'=>'required',
                                    )) }}
                            @endif
                        </div>
                        <span class="validate-err" id="err_hrpr_division_id"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('hrpr_appointment_type', __('Appointment Type'),['class'=>'form-label employee-filter']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('hrpr_appointment_type') }}</span>
                        <div class="form-icon-user" id="hrpr_appointment_type_div">
                            {{ Form::select(
                                'hrpr_appointment_type',
                                $employee_appointment_status,
                                $data->hrpr_appointment_type, 
                                array(
                                    'class' => 'form-control select3 employee-filter',
                                    'id'=>'hrpr_appointment_type',
                                    'data-contain' => 'hrpr_appointment_type_div',
                                    'required'=>'required',
                                    ($data->id) ? 'readonly' : ''
                                    
                                    )
                                ) }}
                        </div>
                        <span class="validate-err" id="err_hrpr_appointment_type"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div  class="accordion accordion-flush" id="req-sec">
                        <div class="accordion-item">
                            <div id="flush-employee" class="accordion-collapse collapse show">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('employee_name', __('Employee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('employee_name') }}</span>
                                            <div class="form-icon-user">
                                            {{ Form::text(
                                                'employee_name',
                                                $data->appointment->employee->fullname, 
                                                array(
                                                    'class' => 'form-control',
                                                    'id'=>'employee_name',
                                                    'readonly'=>'readonly'
                                                    )
                                                ) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_employees_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('hr_designation', __('Designation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hr_designation') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('hr_designation',
                                                    $data->appointment->employee->designation->description, 
                                                    array(
                                                        'class' => 'form-control',
                                                        'id'=>'hr_designation',
                                                        'disabled'
                                                        )) }}
                                            </div>
                                            <span class="validate-err" id="err_hr_designation"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- left -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('hrpr_monthly_rate', __('Monthly Salary'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_monthly_rate') }}</span>
                                            <div class="form-icon-user">
                                            {{ Form::text('hrpr_monthly_rate',
                                                currency_format($data->hrpr_monthly_rate), 
                                                array(
                                                    'class' => 'form-control',
                                                    'id'=>'hrpr_monthly_rate',
                                                    'disabled'
                                                    )) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_monthly_rate"></span>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('hrpr_aut', __('AUT'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_aut') }}</span>
                                            <div class="row">
                                                <div class="form-icon-user col-md-6">
                                                    {{ Form::text('hrpr_aut',
                                                        $data->hrpr_aut, 
                                                        array(
                                                            'class' => 'form-control',
                                                            'id'=>'hrpr_aut',
                                                            'disabled'
                                                            )) }}
                                                </div>
                                                <div class="form-icon-user col-md-6">
                                                    {{ Form::text('hrpr_aut_compute',
                                                        '', 
                                                        array(
                                                            'class' => 'form-control',
                                                            'id'=>'hrpr_aut_compute',
                                                            'disabled'
                                                            )) }}
                                                </div>
                                            </div>
                                            <span class="validate-err" id="err_hrpr_aut"></span>
                                        </div>
                                        <div id="ot-sec">

                                        </div>
                                        <div  class="accordion accordion-flush">
                                            <div class="accordion-item">
                                                <h6 class="accordion-header" id="flush-head-income">
                                                    <button class="accordion-button collapsed btn-primary" type="button">
                                                        <h6 class="sub-title accordiantitle">{{__("Incomes")}}</h6>
                                                    </button>
                                                </h6>
                                                <div id="flush-income" class="accordion-collapse collapse show">
                                                    <div id="income-sec">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- right -->
                                    <div class="col-md-6">
                                        <div  class="accordion accordion-flush" >
                                            <div class="accordion-item">
                                                <h6 class="accordion-header" id="flush-head-deduction">
                                                    <button class="accordion-button collapsed btn-primary" type="button">
                                                        <h6 class="sub-title accordiantitle">{{__("Deductions")}}</h6>
                                                    </button>
                                                </h6>
                                                <div id="flush-deduction" class="accordion-collapse collapse show">
                                                    <div id="deduction-sec">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            {{ Form::label('hrpr_total_salary', __('Total Salary'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_total_salary') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('hrpr_total_salary','', array('class' => 'form-control','id'=>'hrpr_total_salary','disabled')) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_total_salary"></span>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('hrpr_earnings', __('Additional Earnings'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_earnings') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('hrpr_earnings','', array('class' => 'form-control','id'=>'hrpr_earnings','disabled')) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_earnings"></span>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('hrpr_deductions', __('Total Deductions'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_deductions') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('hrpr_deductions','', array('class' => 'form-control','id'=>'hrpr_deductions','disabled')) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_deductions"></span>
                                        </div>
                                        </br>
                                        <div class="form-group">
                                            {{ Form::label('hrpr_net_salary', __('NET SALARY'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('hrpr_net_salary') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('hrpr_net_salary','', array('class' => 'form-control','id'=>'hrpr_net_salary','disabled')) }}
                                            </div>
                                            <span class="validate-err" id="err_hrpr_net_salary"></span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            
        </div>
        <div class="modal-footer">
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal" >
            </div>
    {{Form::close()}}
</div>
<!-- for select dept -->
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/HR/add_appointment.js') }}"></script>   
<script src="{{ asset('js/HR/add_payroll.js?v='.filemtime(getcwd().'/js/HR/add_payroll.js').'') }}"></script>
<div id="addField" class="hidden">
    <div class="form-group">
        {{ Form::label('changeType_changeName', __('changeName'),['class'=>'form-label']) }}
        <span class="validate-err">{{ $errors->first('changeType[changeID]') }}</span>
        <div class="form-icon-user">
            {{ Form::text('changeType[changeID]','changeValue', array('class' => 'form-control compute-changeType','id'=>'changeType_changeName','readonly'=>'readonly')) }}
        </div>
        <span class="validate-err" id="err_changeType_changeName"></span>
    </div>
</div>

<div id="addOT" class="hidden">
    <div class="form-group">
        {{ Form::label('changeType_changeName', __('changeName'),['class'=>'form-label']) }}
        <div class="row">
            <div class="form-icon-user col-md-6">
                {{ Form::text('changeType[changeID][hours]','changeHours', array('class' => 'form-control ','id'=>'changeType_changeName','readonly'=>'readonly')) }}
            </div>
            <div class="form-icon-user col-md-6">
                {{ Form::text('changeType[changeID][earnings]','changeEarn', array('class' => 'form-control compute-ot','id'=>'changeType_changeName','readonly'=>'readonly')) }}
            </div>
        </div>
        <span class="validate-err" id="err_changeType_changeName"></span>
    </div>
</div>
<script>
    getPayroll({{$data->hrpr_employees_id}})
</script>