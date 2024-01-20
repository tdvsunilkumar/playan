{{ Form::open(array('url' => 'treasury/journal-entries/check-disbursement/store','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

<div class="modal-body">
<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('transaction_no', __('Transaction No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('transaction_no',
                        $data->transaction_no, 
                        array(
                            'class' => 'form-control',
                            'id'=>'transaction_no',
                            'readonly'
                            )) }}
                </div>
                <span class="validate-err" id="err_transaction_no"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('payment_type', __('Payment Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('payment_type',
                        $payment_type,
                        $data->payment_type, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'payment_type',
                            )) }}
                </div>
                <span class="validate-err" id="err_payment_type"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('fund_code_id', __('Fund Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('fund_code_id',
                        $fund_code,
                        $data->fund_code_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'fund_code_id',
                            )) }}
                </div>
                <span class="validate-err" id="err_fund_code_id"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('payee', __('Payee'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('payee',
                        $data->payee, 
                        array(
                            'class' => 'form-control',
                            'id'=>'payee'
                            )) }}
                </div>
                <span class="validate-err" id="err_payee"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('disbursement_no', __('Disbursement No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('disbursement_no',
                        $data->disbursement_no, 
                        array(
                            'class' => 'form-control',
                            'id'=>'disbursement_no'
                            )) }}
                </div>
                <span class="validate-err" id="err_disbursement_no"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::date('date',
                        $data->date, 
                        array(
                            'class' => 'form-control',
                            'id'=>'date'
                            )) }}
                </div>
                <span class="validate-err" id="err_date"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('voucher_no', __('Voucher No.'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('voucher_no',
                        $data->voucher_no, 
                        array(
                            'class' => 'form-control',
                            'id'=>'voucher_no'
                            )) }}
                </div>
                <span class="validate-err" id="err_voucher_no"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('amount',
                        $data->amount, 
                        array(
                            'class' => 'form-control',
                            'id'=>'amount'
                            )) }}
                </div>
                <span class="validate-err" id="err_amount"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('particulars', __('Particulars'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::textarea('particulars',
                        $data->particulars, 
                        array(
                            'class' => 'form-control',
                            'id'=>'particulars'
                            )) }}
                </div>
                <span class="validate-err" id="err_particulars"></span>
            </div>
        </div>
        
    </div>
    <div class="modal-footer">
        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
    </div>
</div>    
{{Form::close()}}

<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<script>
    FormNormal()
</script>