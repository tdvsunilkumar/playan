<div class="modal form fade" id="summary-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="cemeteryModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="cemeteryModal">
                        Cemetery Application Payment Summary
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="econCemeteryPaymentTable" class="display dataTable table w-100 table-striped" style="margin-top: 0 !important;" aria-describedby="econCemeteryPaymentInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('NO.') }}</th>
                                                <th>{{ __('OR DATE') }}</th>
                                                <th>{{ __('OR NO') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('PAYMENT') }}</th>
                                                <th>{{ __('BALANCE') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>