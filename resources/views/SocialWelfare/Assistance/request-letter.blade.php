<div class="container-fluid card pt-4">
    {{ Form::open(array('url' => 'social-welfare/assistance/request-letter/'.$data->wswa_id,'class'=>'formDtls')) }}
        {{ Form::hidden('wswa_id',$data->wswa_id, array('id' => 'id')) }}
        <div class="row">
            <div class="col-sm-12">
                {{ Form::label('wswart_body', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wswart_body', 
                    $data->wswart_body, 
                    $attributes = array(
                        'id' => 'wswart_body',
                        'class' => 'form-control form-control-solid'
                    )) 
                }}
            </div>
            <span class="validate-err"  id="err_wswart_body"></span>
        </div>
        <div class="modal-footer">
            <a href="{{route('assistance.printRequestLetter',['id'=>$data->wswa_id])}}" class="btn btn-primary" target="_blank">
                <i class="ti-printer text-white"></i>
                {{__('Print')}}
            </a>
            <button class="btn btn-primary" type="submit" value="save">
                {{__('Submit')}}
            </button>
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal" >
        </div>
    {{Form::close()}}
</div>


</div>
<script src="{{ asset('/js/HealthandSafety/LabForms.js?v='.filemtime(getcwd().'/js/HealthandSafety/LabForms.js').'') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        FormAjax()
   });
</script>
