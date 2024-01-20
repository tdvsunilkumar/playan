{{ Form::open(array('url' => 'legal-housing-application','class'=>'formDtls','id'=>'main-form')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
    .progress{
        height:30px;
    }
    .progress-bar{
        color:#000;
    }
    .accordion-button::after{
        content: "+"!important;
        background-image: none;
    }
    body.theme-2 .progress-bar:not([class*="bg-"]):disabled, body.theme-2 .progress-bar:not([class*="bg-"]).disabled, body.theme-2 .btn-primary:disabled, body.theme-2 .btn-primary.disabled{
        background-color:#20B7CC;
    }
    
</style>
<div class="modal-body">
    <div class="progress mb-4">
        <div id="form-progress-1" class="progress-bar bg-info" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"><span>1. Personal Details</span></div>
        
        <div id="form-progress-2" class="progress-bar bg-light" role="progressbar" style="width: 35%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">2. Contact Details</div>
        <div id="form-progress-3" class="progress-bar bg-light" role="progressbar" style="width: 35%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">3. Other/Details</div>
    </div>
    <div id="1-form" class="citizen_group step-contain show" data-step="1">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('reference_id', __('Reference Id'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('reference_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('reference_id',
                            $reference_id, 
                            array(
                                'class' => 'form-control required',
                                'id'=>'reference_id',
                                'readonly'=>true
                            )) }}
                    </div>
                    <span class="validate-err" id="err_reference_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" id="div_type_of_transaction_id">
                    {{ Form::label('type_of_transaction_id', __('Type of Transaction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('type_of_transaction_id') }}</span>
                    <div class="form-icon-user" id="type_of_transaction_id_contain">
                        {{ Form::select('type_of_transaction_id',
                            $arrTypeTrans,
                            $data->type_of_transaction_id,
                            array(
                                'class'=>'form-control select3 required',
                                'data-contain' => 'type_of_transaction_id_contain',
                                'id'=>'type_of_transaction_id',
                                ($data->id) ? 'readonly' : '',
                                'required'=>'required'
                                )) }}
                    </div>
                    <span class="validate-err" id="err_type_of_transaction_id"></span>
                </div>
            </div>            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('app_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('app_date') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('app_date',
                            $app_date,
                            array(
                                'class' => 'form-control required',
                                'id'=>'app_date',
                                'readonly'=>true
                                )) }}
                    </div>
                    <span class="validate-err" id="err_app_date"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group ">
                    {{ Form::label('client_id', "Name of Applicant", ['class' => '']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('client_id') }}</span>
                        <div class="form-icon-user" id="contain_client_id">    
                            {{ 
                                Form::select('client_id', 
                                    isset($data->client) ? [ $data->client_id => $data->client->cit_fullname] : [],
                                    $data->client_id, 
                                    $attributes = array(
                                    'id' => 'client_id',
                                    'data-url' => 'citizens/getCitizens',
                                    'data-placeholder' => 'Search Citizen',
                                    'class' => 'form-control ajax-select get-citizen select_id required',
                                    ($data->id) ? 'readonly' : '',
                                )) 
                            }}           
                        </div>
                    <span class="validate-err" id="err_client_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('contact_no', __('Contact No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('contact_no') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('contact_no',
                            $data->contact_no, 
                            array(
                                'class' => 'form-control required',
                            ($data->id) ? 'readonly' : '',
                                'id'=>'contact_no'
                                )) }}
                    </div>
                    <span class="validate-err" id="err_contact_no"></span>
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('gender', __('Gender'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('gender') }}</span>
                    <div class="form-icon-user" id="gender_contain">
                        {{ Form::select('gender',
                            $gender,
                            $data->gender,
                            array(
                                'class'=>'form-control select3 select_cit_gender required',
                                    'data-contain' => 'gender_contain',
                            ($data->id) ? 'readonly' : '',
                                'id'=>'gender'
                                )) }}
                    </div>
                    <span class="validate-err" id="err_gender"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('civil_status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('civil_status') }}</span>
                    <div class="form-icon-user" id="civil_status_contain">
                        {{ Form::select('civil_status',
                            $civil_status,
                            $data->civil_status,
                            array(
                                'class'=>'form-control select3 select_ccs_id required',
                                'data-contain' => 'civil_status_contain',
                            ($data->id) ? 'readonly' : '',
                                'id'=>'civil_status'
                                )) }}
                    </div>
                    <span class="validate-err" id="err_status"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('email_address', __('Email Address'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('email_address') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('email_address',
                            $data->email_address, 
                            array(
                                'class' => 'form-control',
                            ($data->id) ? 'readonly' : '',
                                'id'=>'email_address'
                                )) }}
                    </div>
                    <span class="validate-err" id="err_email_address"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('current_address', __('House No./Blk. No/Phase No./Street'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('current_address') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('current_address',
                            $data->current_address, 
                            array(
                                'class' => 'form-control select_cit_full_address required',
                            ($data->id) ? 'readonly' : '',
                                'id'=>'current_address'
                            )) }}
                    </div>
                    <span class="validate-err" id="err_current_address"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('barangay_id', __('Barangay / City Municipality / Province'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('barangay_id') }}</span>
                    <div class="form-icon-user" id="contain_barangay_id">
                        {{ 
                                Form::select('barangay_id', 
                                    [],
                                    $data->barangay_id, 
                                    $attributes = array(
                                    'id' => 'barangay_id',
                                    'data-url' => 'getBarngayList',
                                    'data-placeholder' => 'Search Barangay',
                                    'data-contain' => 'select-contain-citizen',
                                    'data-value' =>isset($data->client->barangay_name) ? $data->client->barangay_name : '',
                                    'data-value_id' =>$data->barangay_id,
                                    'class' => 'form-control ajax-select select_brgy_id required',
                                    ($data->id) ? 'readonly' : '',
                                )) 
                            }}      
                    </div>
                    <span class="validate-err" id="err_curr_address"></span>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="button" value="{{__('Next')}}" class="btn btn-primary next-btn">
            
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
    <div id="2-form" class="step-contain" data-step="2">
        <div class="accordion accordion-flush">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingone">
                    <button class="accordion-button collapsed btn-primary add-housing-btn " {{($data->id) ? 'disabled' : ''}} type="button">
                        Contract Housing Address
                    </button>
                </h6>
                <div id="flush-collapseone" class="accordion-collapse collapse show">
                    <table id="house-group" class="table">
                        <thead>
                            <tr>
                                <td>
                                    Subdivision/Village Name<span class="text-danger">*</span>
                                </td>
                                <td width="30%">
                                    Phase<span class="text-danger">*</span>
                                </td>
                                <td width="30%">
                                    Blk/Lot (with Street)<span class="text-danger">*</span>
                                </td>
                                <td width="10%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data->houses))
                                @foreach($data->houses as $house)
                                <tr id="addHousing{{$house->id}}">
                                    <td>
                                        <div class="">
                                            {{ Form::select('housing['.$house->id.'][residential_name_id]',
                                                $residence,
                                                $house->residential_name_id,
                                                array(
                                                    'class'=>'form-control select3 select-residence required',
                                                    'data-contain' => 'addHousing'.$house->id,
                                                ($data->status >= 3) ? 'readonly' : '',
                                                    'id'=>'residential_name_id_'.$house->id,
                                                    'required'
                                                    )) }}
                                        </div>
                                        <span class="validate-err" id="err_residential_name_id_{{$house->id}}"></span>
                                    </td>
                                    <td>
                                        <div class="">
                                        {{ Form::select('housing['.$house->id.'][residential_location_id]',
                                            [],
                                            '',
                                            array(
                                                'class'=>'form-control ajax-select select-phase',
                                                'data-url' => 'legal-housing-application/getPhase/'.$house->residential_name_id,
                                                'data-contain' => 'addHousing'.$house->id,
                                                'data-value' => $house->phase->phase,
                                                'data-value_id' => $house->residential_location_id,
                                                ($data->status >= 3) ? 'readonly' : '',
                                                'id'=>'residential_location_id_'.$house->id,
                                                'required'
                                                )) }}
                                        </div>
                                        <span class="validate-err" id="err_residential_location_id_{{$house->id}}"></span>
                                    </td>
                                    <td>
                                        <div class="">
                                        {{ Form::select('housing['.$house->id.'][blk_lot_id]',
                                            [],
                                            '',
                                            array(
                                                'class'=>'form-control ajax-select select-blk',
                                                'data-url' => 'legal-housing-application/getBlk/'.$house->residential_location_id,
                                                'data-contain' => 'addHousing'.$house->id,
                                                'data-value' => $house->blk->lot_number,
                                                'data-value_id' => $house->blk_lot_id,
                                                'id'=>'blk_lot_id_'.$house->id,
                                                'required',
                                                ($data->status >= 3) ? 'readonly' : '',
                                                )) }}
                                        </div>
                                        <span class="validate-err" id="err_blk_lot_id_{{$house->id}}"></span>
                                    </td>
                                    <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr id="addHousing00">
                                <td>
                                    <div class="">
                                        {{ Form::select('housing[00][residential_name_id]',
                                            $residence,
                                            '',
                                            array(
                                                'class'=>'form-control select3 select-residence required',
                                                'data-contain' => 'addHousing00',
                                                'id'=>'residential_name_id_00',
                                                'required',
                                                ($data->status >= 3) ? 'readonly' : ''
                                                )) }}
                                    </div>
                                    <span class="validate-err" id="err_residential_name_id_00"></span>
                                </td>
                                <td>
                                    <div class="">
                                    {{ Form::select('housing[00][residential_location_id]',
                                        [],
                                        '',
                                        array(
                                            'class'=>'form-control select3 select-phase required',
                                            'data-contain' => 'addHousing00',
                                            'id'=>'residential_location_id_00',
                                            'required',
                                            ($data->status >= 3) ? 'readonly' : ''
                                            )) }}
                                    </div>
                                    <span class="validate-err" id="err_residential_location_id_00"></span>
                                </td>
                                <td>
                                    <div class="">
                                    {{ Form::select('housing[00][blk_lot_id]',
                                        [],
                                        '',
                                        array(
                                            'class'=>'form-control select3 select-blk required',
                                            'data-contain' => 'addHousing00',
                                            'id'=>'blk_lot_id_00',
                                            'required',
                                            ($data->status >= 3) ? 'readonly' : ''
                                            )) }}
                                    </div>
                                    <span class="validate-err" id="err_blk_lot_id_00"></span>
                                </td>
                                <td>
                                <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <div class="">
                    {{ Form::label('month_terms', __('Terms (months)'),['class'=>'form-label']) }}
                    {{ Form::number('month_terms',
                        $data->month_terms, 
                        array(
                            'class' => 'form-control select-end-date',
                            'id'=>'month_terms',
                            ($data->status >= 3) ? 'readonly' : ''
                        )) }}
                </div>
                <span class="validate-err" id="err_month_terms"></span>
            </div>
            <div class="col-md-4 form-group">
                <div class="">
                    {{ Form::label('terms_date_from', __('Date Start'),['class'=>'form-label']) }}
                    {{ Form::date('terms_date_from',
                        $data->terms_date_from, 
                        array(
                            'class' => 'form-control select-end-date',
                            'id'=>'terms_date_from',
                            ($data->status >= 3) ? 'readonly' : ''
                        )) }}
                </div>
                <span class="validate-err" id="err_terms_date_from"></span>
            </div>
            <div class="col-md-4 form-group">
                <div class="">
                    {{ Form::label('terms_date_to', __('Date End'),['class'=>'form-label']) }}
                    {{ Form::date('terms_date_to',
                        $data->terms_date_to, 
                        array(
                            'class' => 'form-control',
                            'id'=>'terms_date_to',
                            'readonly'
                        )) }}
                </div>
                <span class="validate-err" id="err_terms_date_to"></span>
            </div>
            <div class="col-md-4 form-group">
                <div class="">
                    {{ Form::label('total_amount', __('Total Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    {{ Form::text('total_amount',
                        $data->total_amount, 
                        array(
                            'class' => 'form-control required',
                            'id'=>'total_amount',
                            ($data->id) ? 'readonly' : '',
                        )) }}
                </div>
                <span class="validate-err" id="err_total_amount"></span>
            </div>
            <div class="col-md-4 form-group">
                <div class="">
                    {{ Form::label('initial_monthly', __('Downpayment'),['class'=>'form-label']) }}
                    {{ Form::text('initial_monthly',
                        $data->initial_monthly, 
                        array(
                            'class' => 'form-control',
                            'id'=>'initial_monthly',
                            'readonly'
                        )) }}
                </div>
                <span class="validate-err" id="err_initial_monthly"></span>
            </div>
            <div class="col-md-4 form-group">
                <div class="" id="penalty_contain">
                    {{ Form::label('penalty', __('Penalty'),['class'=>'form-label']) }}
                    {{ Form::select('penalty',
                        $penalties,
                        $data->penalty, 
                        array(
                            'class' => 'form-control select3',
                            'data-contain' => 'penalty_contain',
                            'id'=>'penalty'
                        )) }}
                </div>
                <span class="validate-err" id="err_penalty"></span>
            </div>
        </div>
        @if($data->initial_monthly)
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="">
                        <button class="accordion-button collapsed btn-primary" disabled>
                            Summary Of Payment
                        </button>
                    </h6>
                    <div id="" class="accordion-collapse collapse show">
                        <div class="table-responsive">
                            
                        <table id="payment-group"  class="table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td width="10%">
                                        NO
                                    </td>
                                    <td >
                                        Date
                                    </td>
                                    <td >
                                        Or No
                                    </td>
                                    <td>
                                        Amount
                                    </td>
                                    <td>
                                        Paid
                                    </td>
                                    <td>
                                        Balance
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->breakdown->isNotEmpty())
                                    @foreach($data->breakdown as $payment)
                                    <tr id="payment_{{$payment->id}}">
                                        <td>
                                        {{$loop->iteration}}
                                            {{ Form::hidden('breakdown['.$payment->id.'][id]',
                                                $payment->id,
                                                array(
                                                    'class'=>'form-control',
                                                    )) }}
                                        </td>
                                        <td>
                                            {{ Form::text('breakdown['.$payment->id.'][due_date]',
                                                $payment->due_date,
                                                array(
                                                    'class'=>'form-control',
                                                    'readonly'
                                                    )) }}
                                        </td>
                                        <td>
                                            {{ Form::text('breakdown['.$payment->id.'][or_no]',
                                                $payment->or_no,
                                                array(
                                                    'class'=>'form-control',
                                                    'readonly'
                                                    )) }}
                                        </td>
                                        <td>
                                            {{ Form::text('breakdown['.$payment->id.'][amount_due]',
                                                $payment->amount_due,
                                                array(
                                                    'class'=>'form-control',
                                                    'readonly'
                                                    )) }}
                                        </td>
                                        <td>
                                            {{ Form::text('breakdown['.$payment->id.'][amount_pay]',
                                                $payment->amount_pay,
                                                array(
                                                    'class'=>'form-control',
                                                    'readonly'
                                                    )) }}
                                        </td>
                                        <td>
                                            {{ Form::text('breakdown['.$payment->id.'][remaining_amount]',
                                                $payment->remaining_amount,
                                                array(
                                                    'class'=>'form-control',
                                                    'readonly'
                                                    )) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-3">
                    <p class="form-label text-sm-end">TOTAL BALANCE</p>
                </div>
                <div class="col-md-3">
                    {{ Form::text('remaining_amount',
                    $data->remaining_amount,
                    array(
                        'class'=>'form-control',
                        'disabled'
                        )) }}
                </div>
            </div>
        @endif
        <div class="modal-footer">
            <input type="button" value="{{__('Back')}}" class="btn  btn-light back-btn">
            <input type="button" value="{{__('Next')}}" class="btn btn-primary next-btn">
            
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
    <div id="3-form" class="step-contain" data-step="3">
        <div class="row">
            <div class="col-md-12 form-group">
                {{ Form::label('terms_condition', __('Terms and Conditions'),['class'=>'form-label']) }}
                {{ Form::textarea('terms_condition',
                    $data->terms_condition, 
                    array(
                        'class' => 'form-control',
                        'id'=>'terms_condition'
                    )) }}
                    {{Form::close()}}

            </div>
            <div class="col-md-12 form-group">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button">
                                Upload Details
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show">
                            <div class="row">
                                <div class="col-sm-12">
                                    {{ Form::open(array('url' => '#', 'class'=> (isset($data->files) && $data->files->isNotEmpty()) ? 'dropzone dz-clickable dz-started' : 'dropzone dz-clickable', 'name' => 'uploadFile', 'id' => 'upload-file-form')) }}
                                    @if(isset($data->files) && $data->files->isNotEmpty())
                                        <div class="dz-default dz-message"><span wfd-invisible="true">Drop files here to upload</span></div>
                                        @foreach($data->files as $file)
                                            <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
                                                <div class="dz-image">
                                                    <img data-dz-thumbnail wfd-invisible="true" alt="{{$file->name}}" src="{{url('uploads/housing-application/'.$file->name)}}" width="100px"/>
                                                </div>   

                                                <div class="dz-details">
                                                    <div class="dz-size" >
                                                        <span data-dz-size>{{$file->size}}</span>
                                                    </div>
                                                    <div class="dz-filename">
                                                        <span data-dz-name>{{$file->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="dz-progress" wfd-invisible="true"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="datatable-2" class="dataTables_wrapper mt-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="supplierUploadTable" class="display dataTable table w-100 table-striped" aria-describedby="supplierUploadInfo">
                                                        <thead>
                                                            <tr>
                                                                <th class="sliced">{{ __('FILENAME') }}</th>
                                                                <th>{{ __('TYPE') }}</th>
                                                                <th>{{ __('SIZE') }}</th>
                                                                <th>{{ __('ACTIONS') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                @if(isset($data->files) && $data->files->isNotEmpty())
                                                                    @foreach($data->files as $file)
                                                                    <tr>
                                                                        <td>
                                                                        {{$file->name}}
                                                                            <input type="hidden" name="upload_file[{{$file->id}}][name]" value="{{$file->name}}">
                                                                        </td>
                                                                        <td>
                                                                            {{$file->type}}
                                                                            <input type="hidden" name="upload_file[{{$file->id}}][type]" value="{{$file->type}}">
                                                                        </td>
                                                                        <td>
                                                                            {{$file->size}}
                                                                            <input type="hidden" name="upload_file[{{$file->id}}][size]" value="{{$file->size}}">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
                                                                            <input type="hidden" name="upload_file[{{$file->id}}][is_active]" value="{{$file->is_active}}">
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr class="odd loading" >
                                                                        <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                                    </tr>
                                                                @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <span class="validate-err text-sm-end" id="main_error_msg"></span>

            </div>
        </div>
        <div class="modal-footer">
            @if($data->id && $data->breakdown->isNotEmpty())
                <a href="{{route('LegalHousingApplication.printBreakdown',['data'=>$data->id])}}" target="_blank" class="btn btn-primary">
                    <i class="fa fa-print icon"></i>   
                    Print
                </a>
            @endif
                <input type="button" value="{{__('Back')}}" class="btn  btn-light back-btn">
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                    <i class="fa fa-save icon"></i>
                    <input type="submit" name="next" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                </div>

            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
    {{ Form::close() }}
    
    <div class="mb-5" style="height: 50px;">
        &nbsp;
    </div>
</div>    
<table class="hidden">
    <tbody id="addHousing">
        <tr id="addHousing_0">
            <td>
                <div id="contain_residential_name_id_0">
                    {{ Form::select('housing[0][residential_name_id]',
                        $residence,
                        '',
                        array(
                            'class'=>'form-control select3 select-residence required',
                            'data-contain' => 'addHousing0',
                            'id'=>'residential_name_id_0',
                            'required'
                            )) }}
                </div>
                <span class="validate-err" id="err_residential_name_id_0"></span>
            </td>
            <td>
                <div class="">
                {{ Form::select('housing[0][residential_location_id]',
                    [],
                    '',
                    array(
                        'class'=>'form-control select3 select-phase required',
                        'data-contain' => 'addHousing0',
                        'id'=>'residential_location_id_0',
                        'required'
                        )) }}
                </div>
                <span class="validate-err" id="err_residential_location_id_0"></span>
            </td>
            <td>
                <div class="">
                {{ Form::select('housing[0][blk_lot_id]',
                    [],
                    '',
                    array(
                        'class'=>'form-control select3 select-blk required',
                        'data-contain' => 'addHousing0',
                        'id'=>'blk_lot_id_0',
                        'required'
                        )) }}
                </div>
                <span class="validate-err" id="err_blk_lot_id_0"></span>
            </td>
            <td>
            <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
            </td>
        </tr>
    </tbody>
</table>
<table class="hidden">
    <tbody id="addPayment">
        <tr id="payment_0">
            <td>
            srNo
                {{ Form::hidden('breakdown[0][id]',
                    '0',
                    array(
                        'class'=>'form-control',
                        )) }}
            </td>
            <td>
                {{ Form::text('breakdown[0][due_date]',
                    'dueDate',
                    array(
                        'class'=>'form-control',
                        'readonly'
                        )) }}
            </td>
            <td>
                {{ Form::text('breakdown[0][or_no]',
                    '',
                    array(
                        'class'=>'form-control',
                        'readonly'
                        )) }}
            </td>
            <td>
                {{ Form::text('breakdown[0][amount_due]',
                    'amountDue',
                    array(
                        'class'=>'form-control',
                        'readonly'
                        )) }}
            </td>
            <td>
                {{ Form::text('breakdown[0][amount_pay]',
                    '',
                    array(
                        'class'=>'form-control',
                        'readonly'
                        )) }}
            </td>
            <td>
                {{ Form::text('breakdown[0][remaining_amount]',
                    '',
                    array(
                        'class'=>'form-control',
                        'readonly'
                        )) }}
            </td>
        </tr>
    </tbody>
</table>
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
 <script src="{{ asset('js/accounting/addLegalHousingApplication.js?v='.filemtime(getcwd().'/js/accounting/addLegalHousingApplication.js').'') }}"></script>
<script src="{{ asset('assets/vendors/dropzone/dropzone.js?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.js').'') }}"></script>
<script>
@if(isset($data->breakdown) && $data->breakdown->isEmpty())
    getBreakdown()
@endIF
FormNormal();
</script>
  
 
           