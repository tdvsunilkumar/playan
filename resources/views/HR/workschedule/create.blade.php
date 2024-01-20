<div class="col-md-12">
    <ul class="nav nav-pills mt-3 d-flex justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item" role="by-employee">
            <button class="nav-link active" id="employee-tab" data-bs-toggle="pill" data-bs-target="#employee" type="button" role="tab" aria-controls="employee" aria-selected="true">By Employee</button>
        </li>
        <li class="nav-item" role="by-sched">
            <button class="nav-link" id="sched-tab" data-bs-toggle="pill" data-bs-target="#sched" type="button" role="tab" aria-controls="sched" aria-selected="false" {{($data->id)? 'hidden':''}}>By Schedule</button>
        </li>
    </ul>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" id="hra_department_id_div">
                    {{ Form::label('hra_department_id', __('Department'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hra_department_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('hra_department_id',$department,'', array('class' => 'form-control ','id'=>'hra_department_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hra_department_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" id="hra_division_id_div">
                    {{ Form::label('hra_division_id', __('Division'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hra_division_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('hra_division_id',$division,'', array('class' => 'form-control ','id'=>'hra_division_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hra_division_id"></span>
                </div>
            </div>
        </div>
    </div>
<div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade active show" id="employee" role="tabpanel" aria-labelledby="employee-tab">
            @include('HR.workschedule.create-employee')
        </div>
        <div class="tab-pane fade " id="sched" role="tabpanel" aria-labelledby="sched-tab">
            @include('HR.workschedule.create-schedule')
        </div>
    </div>
</div>

 <script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>  
 <script src="{{ asset('js/HR/add_workschedule.js?v='.filemtime(getcwd().'/js/HR/add_workschedule.js').'') }}"></script>  

  
 
           