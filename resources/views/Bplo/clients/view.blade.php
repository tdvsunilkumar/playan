{{ Form::hidden('client_id',$client_id, array('id' => 'client_id')) }}
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable" id="Jq_ViwDatatableList">
                        <thead>
                            <tr>
                                <th>{{__('No')}}</th>
                                <th>{{__('Business Name')}}</th>
                                <th>{{__('Last OR-No.')}}</th>
                                <th>{{__('Last OR-Date')}}</th>
                                <th>{{__('Payment Status')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/Bplo/view_bploClient.js') }}"></script>

