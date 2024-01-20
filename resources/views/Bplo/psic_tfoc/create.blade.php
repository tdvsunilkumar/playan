{{ Form::open(array('url' => 'PsicTfoc','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('ptfoc_access_type',$data->ptfoc_access_type, array('id' => 'ptfoc_access_type')) }}
    {{ Form::hidden('section_id',$data->section_id, array('id' => 'section_id')) }}
    {{ Form::hidden('subclass_id',$data->subclass_id, array('id' => 'subclass_id')) }}
    {{ Form::hidden('ptfoc_gl_id',$data->ptfoc_gl_id, array('id' => 'ptfoc_gl_id')) }}
    <style type="text/css">
        accordion-button:not(.collapsed)::after, .accordion-button::after {
            background-image: unset !important;
        }
        .accordion-button {
            background-color: #f0f4f3 !important;display: block;padding: 8px;height: 40px !important;
        }
        .sub-title{
            float: left;
        }
        .qurtr_is_higher, .month_is_formula, .month_is_higher, .qurtr_is_formula{
            margin-left: auto !important;margin-right: auto !important;
        }
        .ti-plus, .ti-minus{
            color: #20B7CC;font-size: 20px;cursor: pointer;padding-right: 5px;
        }
        .ti-trash{
            color: red;font-size: 20px;cursor: pointer;
        }
        .action-dtls{
            padding-top: 8px;
        }
        .pt10 b{ font-size: 12px; }
        .JqDivComputationMonth, .JqDivComputationQuartarly{
            background: #885d2d1c;
            margin-bottom: 20px;
        }
        .divComputation{
            background: #885d2d1c;
            padding: 10px;
            margin-left: 0px;
            margin-right: 0px;
        }
        
    </style>

    <div class="modal-body" style="overflow-x: hidden;">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group" id="group_tfoc_id">
                    {{ Form::label('tfoc_id', __('Chart Of Account[Tax, Fee & Other Charges]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tfoc_id',$arrAccount,$data->tfoc_id, array('class' => 'form-control ','id'=>'tfoc_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div> 
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('ctype_id', __('Type Of Charges'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::hidden('hidden_ctype_id',$data->ctype_id, array('id' => 'hidden_ctype_id')) }}
                        {{ Form::select('ctype_id',$arrChargesType,$data->ctype_id, array('class' => 'form-control','id'=>'ctype_id','readonly'=>'readonly','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ctype_id"></span>
                </div>
            </div> 

            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('ptfoc_sl_id', __('Description'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('ptfoc_sl_id',$arrGl,$data->ptfoc_sl_id, array('class' => 'form-control','id'=>'ptfoc_sl_id','readonly')) }}
                    </div>
                </div>
            </div> 

            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('app_code', __('Type Of Applicaiton'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('app_code',$arrType,$data->app_code, array('class' => 'form-control select3','id'=>'app_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_app_code"></span>
                </div>
            </div> 
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('ptfoc_effectivity_date', __('Effectivity Date'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::date('ptfoc_effectivity_date', $data->ptfoc_effectivity_date, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                </div>
            </div>   
            <div class="col-md-2"><br><br>
               <div class="d-flex radio-check">
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('ptfoc_is_no_of_units', '1', ($data->ptfoc_is_no_of_units)?true:false, array('id'=>'ptfoc_is_no_of_units','class'=>'form-check-input')) }}
                        {{ Form::label('ptfoc_is_no_of_units', __('No Of Units'),['class'=>'form-label']) }}
                    </div>
                </div>
            </div>
            <div class="col-md-3"><br><br>
                <div class="d-flex radio-check">
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('ptfoc_is_distribute_per_barangay', '1', ($data->ptfoc_is_distribute_per_barangay)?true:false, array('id'=>'ptfoc_is_distribute_per_barangay','class'=>'form-check-input')) }}
                        {{ Form::label('ptfoc_is_distribute_per_barangay', __('Distribute Per Barangay'),['class'=>'form-label']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group" id="group_cctype_id">
                    {{ Form::label('cctype_id', __('Type of Computation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('cctype_id',$arrTypeComputation,$data->cctype_id, array('class' => 'form-control select3','id'=>'cctype_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_app_code"></span>
                </div>
            </div> 
            <div class="col-md-3 divBasis hide">
                <div class="form-group">
                    {{ Form::label('ptfoc_basis_id', __('Basis[X]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ptfoc_basis_id',$arrBasis,$data->ptfoc_basis_id, array('class' => 'form-control select3','id'=>'ptfoc_basis_id')) }}
                    </div>
                    <span class="validate-err" id="err_app_code"></span>
                </div>
            </div> 

            <div class="col-md-3 divConstant hide">
                <div class="form-group">
                    {{ Form::label('ptfoc_constant_amount', __('Constant Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('ptfoc_constant_amount', $data->ptfoc_constant_amount, array('class' => 'form-control numeric','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_ptfoc_constant_amount"></span>
                </div>
            </div>  
        </div> 
        
        <!--- Start Set Formula --->
        <div class="row JqDivFormulaSection hide" >
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <div class="accordion-header" id="flush-headingtwo">
                            <div class="accordion-button collapsed">
                                <h6 class="sub-title">Set Formula</h6>
                            </div>
                        </div>
                        <div class="accordion-collapse">
                            <div class="row">
                                <!-- <div class="col-md-3">
                                    <div class="form-group currency">
                                        {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                             {{ Form::text('amount',isset($formulaData['amount'])?$formulaData['amount']:'', array('class' => 'form-control numeric','maxlength'=>'150')) }}
                                             <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_amount"></span>
                                    </div>
                                </div>    -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('formula', __('Formula'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                             {{ Form::text('formula',isset($formulaData['formula'])?$formulaData['formula']:'', array('class' => 'form-control','maxlength'=>'150')) }}
                                        </div>
                                    </div>
                                </div>   

                                <div class="col-md-2"><br>
                                   <div class="d-flex radio-check"><?php 
                                        $is_higher =false;
                                        if(isset($formulaData['is_higher'])){
                                            $is_higher = ($formulaData['is_higher'])?true:false;
                                        }
                                        ?>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::checkbox('is_higher', '1',$is_higher, array('id'=>'is_higher','class'=>'form-check-input')) }}
                                            {{ Form::label('is_higher', __('Which ever is higher'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group currency">
                                        {{ Form::label('higher_amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                             {{ Form::text('higher_amount',isset($formulaData['higher_amount'])?$formulaData['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150')) }}
                                             <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_higher_amount"></span>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                    <input type="button" id="btn_addmore" class="btn btn-success" value="Add Variable" style="padding: 0.4rem 0.76rem !important;"><br>
                                </div>
                            </div>
                            <div class="row">
                                <span class="addmoreDetails nature-details" id="addmoreDetails">
                                    <br>
                                    <div class="row pt10">
                                        <div class="col-lg-4 col-md-4 col-sm-4"><b>Formula</b></div>
                                        <div class="col-sm-1"><b>Action</b></div>
                                    </div>
                                    <br>
                                    @php $i=0; @endphp
                                    @foreach($arrVariable as $key=>$val)
                                        <div class="row removeDataFormula pt10">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('charges_id[]',$arrFormulaCharges,$val, array('class' => 'form-control charges_id','id'=>'charges_id'.$i,'required'=>'required')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-1">
                                                <input type="button" name="btnCancelDetails" class="btn btn-success btnCancelDetails delete" value="Delete" style="padding: 0.4rem 1rem !important;">
                                            </div>
                                            
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#charges_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreDetails")});
                                            });
                                        </script>

                                        @php $i++; @endphp
                                    @endforeach 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Set Formula --->


        <!--- Start Measure and Pax --->
        <div class="row JqDivMeasureSection" >
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <div class="accordion-header" id="flush-headingtwo">
                            <div class="accordion-button collapsed">
                                <h6 class="sub-title">Set Measure & Pax</h6>
                            </div>
                        </div>
                        <div class="accordion-collapse">
                            <div class="row">
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                    <input type="button" id="btn_addmoreMeasure" class="btn btn-success" value="Add Measure & Pax" style="padding: 0.4rem 0.76rem !important;"><br>
                                </div>
                            </div>
                            <div class="row">
                                <span class="addmoreMeasureDetails" id="addmoreMeasureDetails">
                                    <br>
                                    <div class="row pt10">
                                        <div class="col-lg-4 col-md-4 col-sm-4"><b>Measure and Pax</b></div>
                                        <div class="col-md-3"><b>Amount</b></div>
                                        <div class="col-md-2"><b>Per Unit</b></div>
                                        <div class="col-sm-1"><b>Action</b></div>
                                    </div>
                                    <br>
                                    @php $i=0; @endphp
                                    @foreach($arrMeasure as $key=>$val)
                                        <div class="row removeMeasureData pt10">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('measure_charges_id[]',$arrMeasureCharges,$val['charge_id'], array('class' => 'form-control measure_charges_id','id'=>'measure_charges_id'.$i,'required'=>'required')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group currency">
                                                    <div class="form-icon-user">
                                                         {{ Form::text('measure_amount[]',$val['measure_amount'], array('class' => 'form-control numeric','maxlength'=>'150')) }}
                                                         <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                    <span class="validate-err" id="err_measure_amount"></span>
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('is_per_unit[]',array('0'=>'No','1'=>'Yes'),$val['is_per_unit'], array('class' => 'form-control','id'=>'is_per_unit'.$i)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="button" name="btnCancelMeasureDetails" class="btn btn-success btnCancelMeasureDetails delete" value="Delete" style="padding: 0.4rem 1rem !important;">
                                            </div>
                                            
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#measure_charges_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreMeasureDetails")});
                                            });
                                        </script>

                                        @php $i++; @endphp
                                    @endforeach 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Measure and Pax --->

        <!--- Start Monthly --->
        <div class="row JqDivMonthlySection" >
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <div class="accordion-button collapsed">
                                <h6 class="sub-title">Set Monthly</h6>
                            </div>
                        </div>
                        <div class="accordion-collapse">
                            <br>
                            <div class="row pt10">
                                <div class="col-md-1"><b>Month</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                                <div class="col-md-1"><b>Formula</b></div>
                                <div class="col-md-2"><b>Formula Details</b></div>
                                <div class="col-md-2"><b>Which ever is higher</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                            </div>
                            <br>

                            @php $i=0; @endphp
                            @for ($m=1; $m<=12; $m++) 
                                @php
                                    $month_name = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                @endphp
                                <?php
                                    $readonlyFormula = "readonly";
                                    $readonlyHigheramt = "readonly";
                                    if(count($arrMonthDetails)>0){
                                        $m_index = array_search($m, array_column($arrMonthDetails, 'month_id'));
                                        if($m_index>=0){
                                            $val = $arrMonthDetails[$m_index];
                                            $readonlyFormula = ($val['is_formula'])?"":'readonly';
                                            $readonlyHigheramt = ($val['is_higher'])?"":'readonly';
                                        }
                                    }

                                ?>
                                {{ Form::hidden('month_id[]',$m) }}
                                <div class="row">
                                    <div class="col-md-1">{{ Form::label('month_name_'.$m, __($month_name),['class'=>'form-label']) }}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group currency">
                                            <div class="form-icon-user">
                                                 {{ Form::text('month_amount_'.$m,isset($val['amount'])?$val['amount']:'', array('class' => 'form-control numeric month_amount','maxlength'=>'150','mid'=>$m,'id'=>'month_amount_'.$m)) }}
                                                 <div class="currency-sign"><span>Php</span></div>
                                            </div>
                                        </div>
                                    </div>   

                                    <div class="col-md-1">
                                       <div class="d-flex radio-check"><?php 
                                            $is_formula =false;
                                            if(isset($val['is_formula'])){
                                                $is_formula = ($val['is_formula'])?true:false;
                                            }?>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('month_is_formula_'.$m, '1',$is_formula, array('id'=>'month_is_formula_'.$m,'class'=>'form-check-input month_is_formula','mid'=>$m)) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 jqMonthFormulaSet{{$m}}">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::text('month_formula_'.$m,isset($val['formula'])?$val['formula']:'', array('class' => 'form-control month_formula','maxlength'=>'150','mid'=>$m,'id'=>'month_formula_'.$m,$readonlyFormula)) }}
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-md-2">
                                       <div class="d-flex radio-check"><?php 
                                            $is_higher =false;
                                            if(isset($val['is_higher'])){
                                                $is_higher = ($val['is_higher'])?true:false;
                                            }
                                            ?>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('month_is_higher_'.$m, '1',$is_higher, array('id'=>'month_is_higher_'.$m,'class'=>'form-check-input month_is_higher','mid'=>$m)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 jqIshigerSet{{$m}}">
                                        <div class="form-group currency">
                                            <div class="form-icon-user">
                                                {{ Form::text('month_higher_amount_'.$m,isset($val['higher_amount'])?$val['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150',$readonlyHigheramt)) }}
                                                <div class="currency-sign"><span>Php</span></div>
                                            </div>
                                        </div>
                                    </div> 

                                </div>
                             @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Monthly --->


        <!--- Start Quarterly --->
        <div class="row JqDivQuarterlySection" >
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <div class="accordion-button collapsed">
                                <h6 class="sub-title">Set Quarterly</h6>
                            </div>
                        </div>
                        <div class="accordion-collapse">
                            <br>
                            <div class="row pt10">
                                <div class="col-md-2"><b>Quarterly</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                                <div class="col-md-1"><b>Formula</b></div>
                                <div class="col-md-2"><b>Formula Details</b></div>
                                <div class="col-md-2"><b>Which ever is higher</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                            </div>
                            <br>

                            @php $i=0; @endphp
                            @foreach($arrQuarterly as $key=>$qutrname)
                                <?php
                                    $m=$key;
                                    $readonlyFormula = "readonly";
                                    $readonlyHigheramt = "readonly";
                                    if(count($arrQuartarlyDetails)>0){
                                        $m_index = array_search($m, array_column($arrQuartarlyDetails, 'quarter_id'));
                                        if($m_index>=0){
                                            $val = $arrQuartarlyDetails[$m_index];
                                            $readonlyFormula = ($val['is_formula'])?"":'readonly';
                                            $readonlyHigheramt = ($val['is_higher'])?"":'readonly';
                                        }
                                    }
                                ?>
                                {{ Form::hidden('quarter_id[]',$m) }}
                                <div class="row">
                                    <div class="col-md-2">{{ Form::label('qurtr_name_'.$m, __($qutrname),['class'=>'form-label']) }}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group currency">
                                            <div class="form-icon-user">
                                                 {{ Form::text('qurtr_amount_'.$m,isset($val['amount'])?$val['amount']:'', array('class' => 'form-control numeric qurtr_amount','maxlength'=>'150','mid'=>$m,'id'=>'qurtr_amount_'.$m)) }}
                                                 <div class="currency-sign"><span>Php</span></div>
                                            </div>
                                        </div>
                                    </div>   

                                    <div class="col-md-1">
                                       <div class="d-flex radio-check"><?php 
                                            $is_formula =false;
                                            if(isset($val['is_formula'])){
                                                $is_formula = ($val['is_formula'])?true:false;
                                            }?>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('qurtr_is_formula_'.$m, '1',$is_formula, array('id'=>'qurtr_is_formula_'.$m,'class'=>'form-check-input qurtr_is_formula','mid'=>$m)) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 jqQurtrFormulaSet{{$m}}">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::text('qurtr_formula_'.$m,isset($val['formula'])?$val['formula']:'', array('class' => 'form-control qurtr_formula','maxlength'=>'150','mid'=>$m,'id'=>'qurtr_formula_'.$m,$readonlyFormula)) }}
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="col-md-2">
                                       <div class="d-flex radio-check"><?php 
                                            $is_higher =false;
                                            if(isset($val['is_higher'])){
                                                $is_higher = ($val['is_higher'])?true:false;
                                            }
                                            ?>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('qurtr_is_higher_'.$m, '1',$is_higher, array('id'=>'qurtr_is_higher_'.$m,'class'=>'form-check-input qurtr_is_higher','mid'=>$m)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 jqIsQurtrhigerSet{{$m}}">
                                        <div class="form-group currency">
                                            <div class="form-icon-user">
                                                {{ Form::text('qurtr_higher_amount_'.$m,isset($val['higher_amount'])?$val['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150',$readonlyHigheramt)) }}
                                                <div class="currency-sign"><span>Php</span></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                             @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Quarterly --->

        <!--- Start Range Details --->
        <div class="row JqDivQRangeSection" >
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <div class="accordion-button collapsed">
                                <h6 class="sub-title">Set Range</h6>
                            </div>
                        </div>
                        <div class="accordion-collapse">
                            <div class="row">
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                    <input type="button" id="btn_addmoreRange" class="btn btn-success" value="Add More Range" style="padding: 0.4rem 0.76rem !important;"><br>
                                </div>
                            </div><br>
                            <div class="row pt10">
                                <div class="col-md-1"><b>No.</b></div>
                                <div class="col-md-1"><b>Lower Limit</b></div>
                                <div class="col-md-1"><b>Upper Limit</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                                <div class="col-md-1"><b>Formula</b></div>
                                <div class="col-md-2"><b>Formula Details</b></div>
                                <div class="col-md-1"><b>Which ever is higher</b></div>
                                <div class="col-md-2"><b>Amount</b></div>
                                <div class="col-md-1"><b>Action</b></div>
                            </div>
                            <br>
                            <span class="validate-err" id="err_LimitAmount"></span>
                            <span class="addmoreRangeDetails" id="addmoreRangeDetails">
                                @php $i=0; $j=0; @endphp
                                @foreach($arrRange as $key=>$val)
                                @php $j=$j+1; @endphp
                                @php 
                                    $readonlyFormula = ($val['is_formula'])?"":'readonly';
                                    $readonlyHigheramt = ($val['is_higher'])?"":'readonly';

                                @endphp

                                    <div class="removeRangeData pt10">
                                        <div class="row divRangeDetails">
                                            <div class="col-lg-1 col-md-1 col-sm-1">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{$j}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                         {{ Form::text('lower_amount[]',isset($val['lower_amount'])?$val['lower_amount']:'', array('class' => 'form-control numeric lower_amount','maxlength'=>'150')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                         {{ Form::text('upper_amount[]',isset($val['upper_amount'])?$val['upper_amount']:'', array('class' => 'form-control numeric upper_amount','maxlength'=>'150')) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 jqRangeFormulaSetAmount">
                                                <div class="form-group currency">
                                                    <div class="form-icon-user">
                                                         {{ Form::text('range_amount[]',isset($val['range_amount'])?$val['range_amount']:'', array('class' => 'form-control numeric range_amount','maxlength'=>'150')) }}
                                                         <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                </div>
                                            </div>   

                                            <div class="col-md-1">
                                               <div class="d-flex radio-check"><?php 
                                                    $is_formula =false;
                                                    if(isset($val['is_formula'])){
                                                        $is_formula = ($val['is_formula'])?true:false;
                                                    }?>
                                                    <div class="form-check form-check-inline form-group">
                                                        {{ Form::checkbox('range_is_formula_'.$i, '1',$is_formula, array('class'=>'form-check-input range_is_formula')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2 jqRangeFormulaSet">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                         {{ Form::text('range_formula[]',isset($val['formula'])?$val['formula']:'', array('class' => 'form-control range_formula','maxlength'=>'150','mid'=>$i,$readonlyFormula)) }}
                                                    </div>
                                                    <span class="validate-err err_range_formula" id="err_range_formula"></span>
                                                </div>
                                            </div>  

                                            <div class="col-md-1">
                                               <div class="d-flex radio-check"><?php 
                                                    $is_higher =false;
                                                    if(isset($val['is_higher'])){
                                                        $is_higher = ($val['is_higher'])?true:false;
                                                    }
                                                    ?>
                                                    <div class="form-check form-check-inline form-group">
                                                        {{ Form::checkbox('range_is_higher_'.$i, '1',$is_higher, array('id'=>'range_is_higher_'.$i,'class'=>'form-check-input range_is_higher')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 jqIsRangehigerSet">
                                                <div class="form-group currency">
                                                    <div class="form-icon-user">
                                                        {{ Form::text('range_higher_amount[]',isset($val['higher_amount'])?$val['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150',$readonlyHigheramt)) }}
                                                        <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-sm-1 action-dtls">
                                                <i class="ti-plus text-blue JqOpenSubRange" alt="Sub Range" title="Sub Range" mid="{{$i}}"></i>
                                                <i class="ti-trash text-blue btnCancelRangeDetails" alt="Delete" title="Delete" mid="{{$i}}"></i>
                                            </div>
                                        </div>

                                        <div class="row divComputation hide">
                                            <div class="col-md-2">
                                                {{ Form::label('computation_type', __('Type of Computation'),['class'=>'form-label']) }}
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('computation_type_'.$i,array(''=>'Please Select','Monthly'=>'Monthly','Quarterly'=>'Quarterly'),isset($val['computation_type'])?$val['computation_type']:'', array('class' => 'form-control computation_type','id'=>'computation_type_'.$i)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="JqDivComputationMonth hide">
                                            <br>
                                            <div class="row pt10">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-1"><b>Month</b></div>
                                                <div class="col-md-2"><b>Amount</b></div>
                                                <div class="col-md-1"><b>Formula</b></div>
                                                <div class="col-md-2"><b>Formula Details</b></div>
                                                <div class="col-md-2"><b>Which ever is higher</b></div>
                                                <div class="col-md-2"><b>Amount</b></div><br><br>
                                            </div>
                                            @for ($m=1; $m<=12; $m++) 
                                                @php
                                                    $month_name = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                                @endphp
                                                <?php
                                                    $readonlyFormula = "readonly";
                                                    $readonlyHigheramt = "readonly";
                                                    if(count($val['month_details'])>0){
                                                        $m_index = array_search($m, array_column($val['month_details'], 'month_id'));
                                                        if($m_index>=0){
                                                            $mval = $val['month_details'][$m_index];
                                                            $readonlyFormula = ($mval['is_formula'])?"":'readonly';
                                                            $readonlyHigheramt = ($mval['is_higher'])?"":'readonly';
                                                        }
                                                    }

                                                ?>
                                                {{ Form::hidden('range_month_id_'.$i.'[]',$m, array('mid'=>$m,'fname'=>'range_month_id','id'=>'range_month_id'.$m.'_'.$i)) }}

                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1">{{ Form::label('range_month_name_'.$m.'_'.$i, __($month_name),['class'=>'form-label']) }}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group currency">
                                                            <div class="form-icon-user">
                                                                 {{ Form::text('range_month_amount_'.$m.'_'.$i,isset($mval['amount'])?$mval['amount']:'', array('class' => 'form-control numeric range_month_amount','maxlength'=>'150','mid'=>$m,'fname'=>'range_month_amount','id'=>'range_month_amount_'.$m.'_'.$i)) }}
                                                                 <div class="currency-sign"><span>Php</span></div>
                                                            </div>
                                                        </div>
                                                    </div>   

                                                    <div class="col-md-1">
                                                       <div class="d-flex radio-check"><?php 
                                                            $is_formula =false;
                                                            if(isset($mval['is_formula'])){
                                                                $is_formula = ($mval['is_formula'])?true:false;
                                                            }?>
                                                            <div class="form-check form-check-inline form-group">
                                                                {{ Form::checkbox('range_month_is_formula_'.$m.'_'.$i, '1',$is_formula, array('id'=>'range_month_is_formula_'.$m.'_'.$i,'class'=>'form-check-input range_month_is_formula','mid'=>$m,'iid'=>$i,'fname'=>'range_month_is_formula')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-2 jqMonthFormulaSet{{$m}}_{{$i}}">
                                                        <div class="form-group">
                                                            <div class="form-icon-user">
                                                                 {{ Form::text('range_month_formula_'.$m.'_'.$i,isset($mval['formula'])?$mval['formula']:'', array('class' => 'form-control range_month_formula','maxlength'=>'150','mid'=>$m,'fname'=>'range_month_formula','id'=>'range_month_formula_'.$m.'_'.$i,$readonlyFormula)) }}
                                                            </div>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-2">
                                                       <div class="d-flex radio-check"><?php 
                                                            $is_higher =false;
                                                            if(isset($mval['is_higher'])){
                                                                $is_higher = ($mval['is_higher'])?true:false;
                                                            }
                                                            ?>
                                                            <div class="form-check form-check-inline form-group">
                                                                {{ Form::checkbox('range_month_is_higher_'.$m.'_'.$i, '1',$is_higher, array('id'=>'range_month_is_higher_'.$m.'_'.$i,'class'=>'form-check-input range_month_is_higher','mid'=>$m,'iid'=>$i,'fname'=>'range_month_formula')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 jqIshigerSet{{$m}}_{{$i}}">
                                                        <div class="form-group currency">
                                                            <div class="form-icon-user">
                                                                {{ Form::text('range_month_higher_amount_'.$m.'_'.$i,isset($mval['higher_amount'])?$mval['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150','fname'=>'range_month_formula',$readonlyHigheramt)) }}
                                                                <div class="currency-sign"><span>Php</span></div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="JqDivComputationQuartarly hide">
                                            <br>
                                            <div class="row pt10">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-1"><b>Quarterly</b></div>
                                                <div class="col-md-2"><b>Amount</b></div>
                                                <div class="col-md-1"><b>Formula</b></div>
                                                <div class="col-md-2"><b>Formula Details</b></div>
                                                <div class="col-md-2"><b>Which ever is higher</b></div>
                                                <div class="col-md-2"><b>Amount</b></div>
                                            </div>
                                            <br>
                                            @foreach($arrQuarterly as $key=>$qutrname)
                                                <?php
                                                    $m=$key;
                                                    $readonlyFormula = "readonly";
                                                    $readonlyHigheramt = "readonly";
                                                    if(count($val['qurtarly_details'])>0){
                                                        $m_index = array_search($m, array_column($val['qurtarly_details'], 'quarter_id'));
                                                        if($m_index>=0){
                                                            $qval = $val['qurtarly_details'][$m_index];
                                                            $readonlyFormula = ($qval['is_formula'])?"":'readonly';
                                                            $readonlyHigheramt = ($qval['is_higher'])?"":'readonly';
                                                        }
                                                    }
                                                ?>
                                                {{ Form::hidden('range_quarter_id_'.$i.'[]',$m, array('mid'=>$m,'fname'=>'range_quarter_id','id'=>'range_quarter_id_'.$m.'_'.$i)) }}
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1">{{ Form::label('qurtr_name_'.$m.'_'.$i, __($qutrname),['class'=>'form-label']) }}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group currency">
                                                            <div class="form-icon-user">
                                                                 {{ Form::text('qurtr_amount_'.$m.'_'.$i,isset($qval['amount'])?$qval['amount']:'', array('class' => 'form-control numeric qurtr_amount','maxlength'=>'150','mid'=>$m,'fname'=>'qurtr_amount','id'=>'qurtr_amount_'.$m.'_'.$i)) }}
                                                                 <div class="currency-sign"><span>Php</span></div>
                                                            </div>
                                                        </div>
                                                    </div>   

                                                    <div class="col-md-1">
                                                       <div class="d-flex radio-check"><?php 
                                                            $is_formula =false;
                                                            if(isset($qval['is_formula'])){
                                                                $is_formula = ($qval['is_formula'])?true:false;
                                                            }?>
                                                            <div class="form-check form-check-inline form-group">
                                                                {{ Form::checkbox('qurtr_is_formula_'.$m.'_'.$i, '1',$is_formula, array('id'=>'qurtr_is_formula_'.$m.'_'.$i,'class'=>'form-check-input range_qurtr_is_formula','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_is_formula')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-2 jqQurtrFormulaSet{{$m}}_{{$i}}">
                                                        <div class="form-group">
                                                            <div class="form-icon-user">
                                                                 {{ Form::text('qurtr_formula_'.$m.'_'.$i,isset($qval['formula'])?$qval['formula']:'', array('class' => 'form-control range_qurtr_formula','maxlength'=>'150','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_formula','id'=>'qurtr_formula_'.$m.'_'.$i,$readonlyFormula)) }}
                                                            </div>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-2">
                                                       <div class="d-flex radio-check"><?php 
                                                            $is_higher =false;
                                                            if(isset($qval['is_higher'])){
                                                                $is_higher = ($qval['is_higher'])?true:false;
                                                            }
                                                            ?>
                                                            <div class="form-check form-check-inline form-group">
                                                                {{ Form::checkbox('qurtr_is_higher_'.$m.'_'.$i, '1',$is_higher, array('id'=>'qurtr_is_higher_'.$m.'_'.$i,'class'=>'form-check-input range_qurtr_is_higher','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_is_higher')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 jqIsQurtrhigerSet{{$m}}">
                                                        <div class="form-group currency">
                                                            <div class="form-icon-user">
                                                                {{ Form::text('qurtr_higher_amount_'.$m.'_'.$i,isset($qval['higher_amount'])?$qval['higher_amount']:'', array('class' => 'form-control numeric','maxlength'=>'150',$readonlyHigheramt,'fname'=>'qurtr_higher_amount','mid'=>$m)) }}
                                                                <div class="currency-sign"><span>Php</span></div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                             @endforeach
                                        </div>
                                    </div>
                                    @php $i++; @endphp
                                 @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- End Range Details --->


        <br> <br> <br> <br> <br> <br> <br> 
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="saveChanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>

        </div>
    </div>
{{Form::close()}}
<!--- Start Hidden Formula Deatails  --->
<div id="hiddenFormulaDtls" class="hide">
    <div class="removeDataFormula row pt10">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <div class="form-icon-user">
                    @php 
                        $i=(count($arrVariable)>0)?count($arrVariable):0;
                    @endphp
                    {{ Form::select('charges_id[]',$arrFormulaCharges,'', array('class' => 'form-control charges_id','id'=>'charges_id'.$i,'required'=>'required')) }}
               </div>
           </div>
       </div>
        <div class="col-sm-1">
            <input type="button" name="btn_cancel"  class="btn btn-success btnCancelDetails delete" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div>   
<!--- End Hidden Formula Deatails  --->

<!--- Start Hidden Measuer and Pax Deatails  --->
<div id="hiddenMeasureDtls" class="hide">
    <div class="row removeMeasureData pt10">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <div class="form-icon-user">
                     @php 
                        $i=(count($arrMeasure)>0)?count($arrMeasure):0;
                    @endphp

                    {{ Form::select('measure_charges_id[]',$arrMeasureCharges,'', array('class' => 'form-control measure_charges_id','id'=>'measure_charges_id'.$i,'required'=>'required')) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group currency">
                <div class="form-icon-user">
                     {{ Form::text('measure_amount[]',0, array('class' => 'form-control numeric','maxlength'=>'150')) }}
                     <div class="currency-sign"><span>Php</span></div>
                </div>
                <span class="validate-err" id="err_measure_amount"></span>
            </div>
        </div> 
        <div class="col-md-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::select('is_per_unit[]',array('0'=>'No','1'=>'Yes'),'', array('class' => 'form-control','id'=>'is_per_unit'.$i)) }}
                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="button" name="btnCancelMeasureDetails" class="btn btn-success btnCancelMeasureDetails delete" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div> 
</div>
<!--- Start Hidden Measuer and Pax Deatails  --->

<!--- Start Hidden Range Deatails  --->
<div id="hiddenRangeDtls" class="hide">
    
    @php 
        $i=(count($arrRange)>0)?count($arrRange):0;
    @endphp
    <div class="removeRangeData pt10">
        <div class="row divRangeDetails">
            <div class="col-lg-1 col-md-1 col-sm-1">
                <div class="form-group">
                    <div class="form-icon-user">
                       <div id="increment"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <div class="form-icon-user">
                         {{ Form::text('lower_amount[]','0.00', array('class' => 'form-control numeric lower_amount','maxlength'=>'150')) }}
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <div class="form-icon-user">
                         {{ Form::text('upper_amount[]','0.00', array('class' => 'form-control numeric upper_amount','maxlength'=>'150')) }}
                    </div>
                </div>
            </div>

            <div class="col-md-2 jqRangeFormulaSetAmount">
                <div class="form-group currency">
                    <div class="form-icon-user">
                         {{ Form::text('range_amount[]','0', array('class' => 'form-control numeric range_amount','maxlength'=>'150')) }}
                         <div class="currency-sign"><span>Php</span></div>
                    </div>
                </div>
            </div>   

            <div class="col-md-1">
               <div class="d-flex radio-check">
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('range_is_formula_'.$i, '1','', array('class'=>'form-check-input range_is_formula')) }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 jqRangeFormulaSet">
                <div class="form-group">
                    <div class="form-icon-user">
                         {{ Form::text('range_formula[]','', array('class' => 'form-control range_formula','maxlength'=>'150','readonly'=>'readonly')) }}
                    </div>
                </div>
            </div>  

            <div class="col-md-1">
               <div class="d-flex radio-check">
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('range_is_higher_'.$i, '1','', array('id'=>'range_is_higher_'.$i,'class'=>'form-check-input range_is_higher')) }}
                    </div>
                </div>
            </div>
            <div class="col-md-2 jqIsRangehigerSet">
                <div class="form-group currency">
                    <div class="form-icon-user">
                        {{ Form::text('range_higher_amount[]','0', array('class' => 'form-control numeric','maxlength'=>'150','readonly'=>'readonly')) }}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                </div>
            </div> 
            <div class="col-sm-1 action-dtls">
                <i class="ti-plus text-blue JqOpenSubRange" alt="Sub Range" title="Sub Range" mid="{{$i}}"></i>
                <i class="ti-trash text-blue btnCancelRangeDetails" alt="Delete" title="Delete" mid="{{$i}}"></i>
            </div>
        </div>
    
        <div class="row divComputation hide">
            <div class="col-md-2">
                {{ Form::label('computation_type', __('Type of Computation'),['class'=>'form-label']) }}
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{ Form::select('computation_type_'.$i,array(''=>'Please Select','Monthly'=>'Monthly','Quarterly'=>'Quarterly'),'', array('class' => 'form-control computation_type','id'=>'computation_type_'.$i)) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="JqDivComputationMonth hide">
            <br>
            <div class="row pt10">
                <div class="col-md-2"></div>
                <div class="col-md-1"><b>Month</b></div>
                <div class="col-md-2"><b>Amount</b></div>
                <div class="col-md-1"><b>Formula</b></div>
                <div class="col-md-2"><b>Formula Details</b></div>
                <div class="col-md-2"><b>Which ever is higher</b></div>
                <div class="col-md-2"><b>Amount</b></div><br><br>
            </div>
            @for ($m=1; $m<=12; $m++) 
                @php
                    $month_name = date('F', mktime(0,0,0,$m, 1, date('Y')));
                @endphp
                <?php
                    $readonlyFormula = "readonly";
                    $readonlyHigheramt = "readonly";
                ?>
                {{ Form::hidden('range_month_id_'.$i.'[]',$m, array('mid'=>$m,'fname'=>'range_month_id','id'=>'range_month_id'.$m.'_'.$i)) }}
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-1">{{ Form::label('range_month_name_'.$m.'_'.$i, __($month_name),['class'=>'form-label']) }}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group currency">
                            <div class="form-icon-user">
                                 {{ Form::text('range_month_amount_'.$m.'_'.$i,'', array('class' => 'form-control numeric range_month_amount','maxlength'=>'150','mid'=>$m,'fname'=>'range_month_amount','id'=>'range_month_amount_'.$m.'_'.$i)) }}
                                 <div class="currency-sign"><span>Php</span></div>
                            </div>
                        </div>
                    </div>   

                    <div class="col-md-1">
                       <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group">
                                {{ Form::checkbox('range_month_is_formula_'.$m.'_'.$i, '1','', array('id'=>'range_month_is_formula_'.$m.'_'.$i,'class'=>'form-check-input range_month_is_formula','mid'=>$m,'iid'=>$i,'fname'=>'range_month_is_formula')) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 jqMonthFormulaSet{{$m}}_{{$i}}">
                        <div class="form-group">
                            <div class="form-icon-user">
                                 {{ Form::text('range_month_formula_'.$m.'_'.$i,'', array('class' => 'form-control range_month_formula','maxlength'=>'150','mid'=>$m,'fname'=>'range_month_formula','id'=>'range_month_formula_'.$m.'_'.$i,$readonlyFormula)) }}
                            </div>
                        </div>
                    </div>  

                    <div class="col-md-2">
                       <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group">
                                {{ Form::checkbox('range_month_is_higher_'.$m.'_'.$i, '1','', array('id'=>'range_month_is_higher_'.$m.'_'.$i,'class'=>'form-check-input range_month_is_higher','mid'=>$m,'fname'=>'range_month_is_higher','iid'=>$i)) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 jqIshigerSet{{$m}}_{{$i}}">
                        <div class="form-group currency">
                            <div class="form-icon-user">
                                {{ Form::text('range_month_higher_amount_'.$m.'_'.$i,'', array('class' => 'form-control numeric range_month_higher_amount','maxlength'=>'150','mid'=>$m,$readonlyHigheramt,'fname'=>'range_month_higher_amount')) }}
                                <div class="currency-sign"><span>Php</span></div>
                            </div>
                        </div>
                    </div> 
                </div>
            @endfor
        </div>
        <div class="JqDivComputationQuartarly hide">
            <br>
            <div class="row pt10">
                <div class="col-md-2"></div>
                <div class="col-md-1"><b>Quarterly</b></div>
                <div class="col-md-2"><b>Amount</b></div>
                <div class="col-md-1"><b>Formula</b></div>
                <div class="col-md-2"><b>Formula Details</b></div>
                <div class="col-md-2"><b>Which ever is higher</b></div>
                <div class="col-md-2"><b>Amount</b></div>
            </div>
            <br>
            @foreach($arrQuarterly as $key=>$qutrname)
                <?php
                    $m=$key;
                    $readonlyFormula = "readonly";
                    $readonlyHigheramt = "readonly";
                ?>
                {{ Form::hidden('range_quarter_id_'.$i.'[]',$m, array('mid'=>$m,'fname'=>'range_quarter_id','id'=>'range_quarter_id_'.$m.'_'.$i)) }}
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-1">{{ Form::label('qurtr_name_'.$m.'_'.$i, __($qutrname),['class'=>'form-label']) }}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group currency">
                            <div class="form-icon-user">
                                 {{ Form::text('qurtr_amount_'.$m.'_'.$i,'', array('class' => 'form-control numeric qurtr_amount','maxlength'=>'150','mid'=>$m,'fname'=>'qurtr_amount','id'=>'qurtr_amount_'.$m.'_'.$i)) }}
                                 <div class="currency-sign"><span>Php</span></div>
                            </div>
                        </div>
                    </div>   

                    <div class="col-md-1">
                       <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group">
                                {{ Form::checkbox('qurtr_is_formula_'.$m.'_'.$i, '1','', array('id'=>'qurtr_is_formula_'.$m.'_'.$i,'class'=>'form-check-input range_qurtr_is_formula','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_is_formula')) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 jqQurtrFormulaSet{{$m}}_{{$i}}">
                        <div class="form-group">
                            <div class="form-icon-user">
                                 {{ Form::text('qurtr_formula_'.$m.'_'.$i,'', array('class' => 'form-control range_qurtr_formula','maxlength'=>'150','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_formula','id'=>'qurtr_formula_'.$m.'_'.$i,$readonlyFormula)) }}
                            </div>
                        </div>
                    </div>  

                    <div class="col-md-2">
                       <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group">
                                {{ Form::checkbox('qurtr_is_higher_'.$m.'_'.$i, '1','', array('id'=>'qurtr_is_higher_'.$m.'_'.$i,'class'=>'form-check-input range_qurtr_is_higher','mid'=>$m,'iid'=>$i,'fname'=>'qurtr_is_higher')) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 jqIsQurtrhigerSet{{$m}}_{{$i}}">
                        <div class="form-group currency">
                            <div class="form-icon-user">
                                {{ Form::text('qurtr_higher_amount_'.$m.'_'.$i,'', array('class' => 'form-control numeric qurtr_higher_amount','maxlength'=>'150','mid'=>$m,'fname'=>'qurtr_higher_amount',$readonlyHigheramt)) }}
                                <div class="currency-sign"><span>Php</span></div>
                            </div>
                        </div>
                    </div> 
                </div>
             @endforeach
        </div>
    </div>

</div>
<!--- Start Hidden Range Deatails  --->

<script src="{{ asset('js/Bplo/add_PsicTfoc.js?ver='.time()) }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>
     
  