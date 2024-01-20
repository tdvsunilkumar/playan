
<style>
.select3-container{
    z-index:1000 !important;
}</style>
{{ Form::open(array('url' => 'taxrevenue/store','id'=>'taxRevenueForm')) }}

    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .select3-container{z-index:unset !important;}
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                    {{ Form::label('', __('Property Kind'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pk_id',$propKinds,$data->pk_id, array('class' => 'form-control 
                        ','id'=>'pk_id','readonly'=>'true','onmousedown'=>'return false;')) }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                    {{ Form::label('', __('Tax Revenue Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('',$data->trev_name, array('class' => 'form-control 
                        ','id'=>'trev_name','readonly')) }}
                    </div>
                </div>
            </div> 
            <div class="col-md-3">
              <div class="form-group">
                    {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('',$data->trev_description, array('class' => 'form-control 
                        ','id'=>'trev_description','readonly')) }}
                    </div>
                </div>
            </div>
           
           
            <div class="col-md-3">
              <div class="form-group">
                    {{ Form::label('', __('Revenue Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('',$data->tax_what_year.'-'.config('constants.taxRevenueYears')[$data->tax_what_year], array('class' => 'form-control
                        ','id'=>'tax_what_year','readonly')) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone3" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("Basic Tax")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone3" class="accordion-collapse collapse show" aria-labelledby="flush-headingone3" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-md-8" id="basic_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_tfoc_id', __('Basic'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_tfoc_id',$taxesAnddFee,$data->basic_tfoc_id, array('class' => 'form-control basic_tfoc_id','id'=>'basic_tfoc_id','placeholder' => 'Select Basic')) }}
                                        </div>
                                        <span class="validate-err" id="err_basic_tfoc_id"></span>
                                    </div>
                                </div> 
                                    
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_tfoc_id]))?$taxesFeeDesc[$data->basic_tfoc_id]:'',array('class' => 'form-control basic_tfoc_id_desc','readonly'=>'true')) }}
                                           <input type="hidden" value="{{ json_encode($taxesFeeDesc)}}" id="taxFeesJsonData" />
                                        </div>
                                    </div>
                                </div>
                                 @if($data->tax_what_year == 1) 
                                <div class="col-md-8" id="basic_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_discount_tfoc_id',$taxesAnddFee,$data->basic_discount_tfoc_id, array('class' => 'form-control basic_discount_tfoc_id 
                                            ','id'=>'basic_discount_tfoc_id','placeholder' => 'Select Basic Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_discount_tfoc_id]))?$taxesFeeDesc[$data->basic_discount_tfoc_id]:'',array('class' => 'form-control basic_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-8" id="basic_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('basic_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_penalty_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control basic_penalty_tfoc_id 
                                            ','placeholder' => 'Select Basic Penalty' ,'readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                 <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_penalty_tfoc_id]))?$taxesFeeDesc[$data->basic_penalty_tfoc_id]:'',array('class' => 'form-control basic_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 2)
                                <div class="col-md-8" id="basic_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_discount_tfoc_id',$taxesAnddFee,$data->basic_discount_tfoc_id, array('class' => 'form-control basic_discount_tfoc_id 
                                            ','id'=>'basic_discount_tfoc_id','placeholder' => 'Select Basic Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_discount_tfoc_id]))?$taxesFeeDesc[$data->basic_discount_tfoc_id]:'',array('class' => 'form-control basic_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-8" id="basic_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('basic_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_penalty_tfoc_id',$taxesAnddFee,$data->basic_penalty_tfoc_id, array('class' => 'form-control basic_penalty_tfoc_id 
                                            ','id'=>'basic_penalty_tfoc_id','placeholder' => 'Select Basic Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_penalty_tfoc_id]))?$taxesFeeDesc[$data->basic_penalty_tfoc_id]:'',array('class' => 'form-control basic_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>      
                                 @elseif($data->tax_what_year == 3)
                                <div class="col-md-8" id="basic_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_discount_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control basic_discount_tfoc_id 
                                            ','placeholder' => 'Select Basic Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_discount_tfoc_id]))?$taxesFeeDesc[$data->basic_discount_tfoc_id]:'',array('class' => 'form-control basic_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-8" id="basic_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('basic_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_penalty_tfoc_id',$taxesAnddFee,$data->basic_penalty_tfoc_id, array('class' => 'form-control basic_penalty_tfoc_id 
                                            ','id'=>'basic_penalty_tfoc_id','placeholder' => 'Select Basic Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_penalty_tfoc_id]))?$taxesFeeDesc[$data->basic_penalty_tfoc_id]:'',array('class' => 'form-control basic_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                @elseif($data->tax_what_year == 4)
                                <div class="col-md-8" id="basic_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_discount_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control basic_discount_tfoc_id 
                                            ','placeholder' => 'Select Basic Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_discount_tfoc_id]))?$taxesFeeDesc[$data->basic_discount_tfoc_id]:'',array('class' => 'form-control basic_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-8" id="basic_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('basic_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('basic_penalty_tfoc_id',$taxesAnddFee,$data->basic_penalty_tfoc_id, array('class' => 'form-control basic_penalty_tfoc_id 
                                            ','id'=>'basic_penalty_tfoc_id','placeholder' => 'Select Basic Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->basic_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->basic_penalty_tfoc_id]))?$taxesFeeDesc[$data->basic_penalty_tfoc_id]:'',array('class' => 'form-control basic_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("SEF[Special Education Fund]")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-md-8" id="basic_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('basic_tfoc_id', __('SEF'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_tfoc_id',$taxesAnddFee,$data->sef_tfoc_id, array('class' => 'form-control sef_tfoc_id 
                                            ','id'=>'sef_tfoc_id','placeholder' => 'Select SEF')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_tfoc_id]))?$taxesFeeDesc[$data->sef_tfoc_id]:'',array('class' => 'form-control sef_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                @if($data->tax_what_year == 1)
                                <div class="col-md-8" id="sef_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sef_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_discount_tfoc_id',$taxesAnddFee,$data->sef_discount_tfoc_id, array('class' => 'form-control sef_discount_tfoc_id 
                                            ','id'=>'sef_discount_tfoc_id','placeholder' => 'Select SEF Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_discount_tfoc_id]))?$taxesFeeDesc[$data->sef_discount_tfoc_id]:'',array('class' => 'form-control sef_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sef_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sef_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_penalty_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control sef_penalty_tfoc_id 
                                            ','placeholder' => 'Select SHT Penalty','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_penalty_tfoc_id]))?$taxesFeeDesc[$data->sef_penalty_tfoc_id]:'',array('class' => 'form-control sef_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 2)
                                <div class="col-md-8" id="sef_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sef_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_discount_tfoc_id',$taxesAnddFee,$data->sef_discount_tfoc_id, array('class' => 'form-control sef_discount_tfoc_id 
                                            ','id'=>'sef_discount_tfoc_id','placeholder' => 'Select SEF Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_discount_tfoc_id]))?$taxesFeeDesc[$data->sef_discount_tfoc_id]:'',array('class' => 'form-control sef_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sef_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sef_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_penalty_tfoc_id',$taxesAnddFee,$data->sef_penalty_tfoc_id, array('class' => 'form-control sef_penalty_tfoc_id 
                                            ','id'=>'sef_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_penalty_tfoc_id]))?$taxesFeeDesc[$data->sef_penalty_tfoc_id]:'',array('class' => 'form-control sef_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 3)
                                <div class="col-md-8" id="sef_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sef_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_discount_tfoc_id',$taxesAnddFee,$data->sef_discount_tfoc_id, array('class' => 'form-control sef_discount_tfoc_id 
                                            ','placeholder' => 'Select SEF Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_discount_tfoc_id]))?$taxesFeeDesc[$data->sef_discount_tfoc_id]:'',array('class' => 'form-control sef_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sef_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sef_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_penalty_tfoc_id',$taxesAnddFee,$data->sef_penalty_tfoc_id, array('class' => 'form-control sef_penalty_tfoc_id 
                                            ','id'=>'sef_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_penalty_tfoc_id]))?$taxesFeeDesc[$data->sef_penalty_tfoc_id]:'',array('class' => 'form-control sef_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 4)
                                <div class="col-md-8" id="sef_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sef_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_discount_tfoc_id',$taxesAnddFee,$data->sef_discount_tfoc_id, array('class' => 'form-control sef_discount_tfoc_id 
                                            ','placeholder' => 'Select SEF Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_discount_tfoc_id]))?$taxesFeeDesc[$data->sef_discount_tfoc_id]:'',array('class' => 'form-control sef_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sef_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sef_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sef_penalty_tfoc_id',$taxesAnddFee,$data->sef_penalty_tfoc_id, array('class' => 'form-control sef_penalty_tfoc_id 
                                            ','id'=>'sef_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sef_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sef_penalty_tfoc_id]))?$taxesFeeDesc[$data->sef_penalty_tfoc_id]:'',array('class' => 'form-control sef_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("SHT[Socialize Housing Tax]")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone2" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-md-8" id="sh_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sh_tfoc_id', __('Socialize Housing'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_tfoc_id',$taxesAnddFee,$data->sh_tfoc_id, array('class' => 'form-control sh_tfoc_id 
                                            ','id'=>'sh_tfoc_id','placeholder' => 'Select SHT')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_tfoc_id]))?$taxesFeeDesc[$data->sh_tfoc_id]:'',array('class' => 'form-control sh_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @if($data->tax_what_year == 1)
                                <div class="col-md-8" id="sh_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sh_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_discount_tfoc_id',$taxesAnddFee,$data->sh_discount_tfoc_id, array('class' => 'form-control sh_discount_tfoc_id 
                                            ','id'=>'sh_discount_tfoc_id','placeholder' => 'Select SHT Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_discount_tfoc_id]))?$taxesFeeDesc[$data->sh_discount_tfoc_id]:'',array('class' => 'form-control sh_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sh_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sh_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_penalty_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control sh_penalty_tfoc_id 
                                            ','placeholder' => 'Select SHT Penalty','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_penalty_tfoc_id]))?$taxesFeeDesc[$data->sh_penalty_tfoc_id]:'',array('class' => 'form-control sh_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 2)
                                <div class="col-md-8" id="sh_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sh_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_discount_tfoc_id',$taxesAnddFee,$data->sh_discount_tfoc_id, array('class' => 'form-control sh_discount_tfoc_id 
                                            ','id'=>'sh_discount_tfoc_id','placeholder' => 'Select SHT Discount')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_discount_tfoc_id]))?$taxesFeeDesc[$data->sh_discount_tfoc_id]:'',array('class' => 'form-control sh_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sh_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sh_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_penalty_tfoc_id',$taxesAnddFee,$data->sh_penalty_tfoc_id, array('class' => 'form-control sh_penalty_tfoc_id 
                                            ','id'=>'sh_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_penalty_tfoc_id]))?$taxesFeeDesc[$data->sh_penalty_tfoc_id]:'',array('class' => 'form-control sh_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                
                                @elseif($data->tax_what_year == 4)
                                <div class="col-md-8" id="sh_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sh_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_discount_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control sh_discount_tfoc_id 
                                            ','placeholder' => 'Select SHT Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_discount_tfoc_id]))?$taxesFeeDesc[$data->sh_discount_tfoc_id]:'',array('class' => 'form-control sh_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sh_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sh_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_penalty_tfoc_id',$taxesAnddFee,$data->sh_penalty_tfoc_id, array('class' => 'form-control sh_penalty_tfoc_id 
                                            ','id'=>'sh_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_penalty_tfoc_id]))?$taxesFeeDesc[$data->sh_penalty_tfoc_id]:'',array('class' => 'form-control sh_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @elseif($data->tax_what_year == 3)
                                <div class="col-md-8" id="sh_discount_tfoc_id_div">
                                  <div class="form-group">
                                        {{ Form::label('sh_discount_tfoc_id', __('Discount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_discount_tfoc_id',$taxesAnddFee,'', array('class' => 'form-control sh_discount_tfoc_id 
                                            ','placeholder' => 'Select SHT Discount','readonly'=>'true','disabled' => 'disabled')) }}
                                        </div>
                                        <span class="validate-err" id="err_sef_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_discount_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_discount_tfoc_id]))?$taxesFeeDesc[$data->sh_discount_tfoc_id]:'',array('class' => 'form-control sh_discount_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div> 
                                

                                <div class="col-md-8" id="sh_penalty_tfoc_id_div">

                                  <div class="form-group">
                                        {{ Form::label('sh_penalty_tfoc_id', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('sh_penalty_tfoc_id',$taxesAnddFee,$data->sh_penalty_tfoc_id, array('class' => 'form-control sh_penalty_tfoc_id 
                                            ','id'=>'sh_penalty_tfoc_id','placeholder' => 'Select SHT Penalty')) }}
                                        </div>
                                        <span class="validate-err" id="err_sh_tfoc_id"></span>
                                    </div>
                                </div> 
                                      
                               <div class="col-md-4">
                                  <div class="form-group">
                                        {{ Form::label('', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                           {{ Form::text('',($data->sh_penalty_tfoc_id != '' && isset($taxesFeeDesc[$data->sh_penalty_tfoc_id]))?$taxesFeeDesc[$data->sh_penalty_tfoc_id]:'',array('class' => 'form-control sh_penalty_tfoc_id_desc','readonly'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <button class="btn  btn-primary" id="submitPlantTreeUnitValueButton">{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button>
        </div>
    </div>
{{Form::close()}}
<script type="text/javascript">
    $(document).ready(function(){
        $('#basic_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sh_tfoc_id_div'});
        $('#sef_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sh_tfoc_id_div'});
        $('#sh_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sh_tfoc_id_div'});
        $('#basic_discount_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#basic_discount_tfoc_id_div'});
        $('#basic_penalty_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#basic_penalty_tfoc_id_div'});
        $('#sh_discount_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sh_discount_tfoc_id_div'});
        $('#sh_penalty_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sh_penalty_tfoc_id_div'});
        $('#sef_discount_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sef_discount_tfoc_id_div'});
        $('#sef_penalty_tfoc_id').select3({dropdownAutoWidth : false,dropdownParent : '#sef_penalty_tfoc_id_div'});

    });

</script>
<!-- <script src="{{ asset('js/ajax_rptPlantTreeUnitValue.js') }}"></script>
<script src="{{ asset('js/addPlantTressUnitValue.js') }}"></script> -->




