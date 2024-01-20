<div class="modal form fade" id="departmental-requisition-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="departmentalRequisitionLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="departmentalRequisitionLabel">Manage Obligation Request</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- LEFT COLUMN DETAILS START -->
                    <div class="col-md-5 border-right space-right mt-1">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- ALOB DETAILS START -->
                            @include('finance.obligation-requests.alob-details2') 
                            <!-- ALOB DETAILS END -->
                        </div>
                    </div>
                    <!-- LEFT COLUMN DETAILS END -->
                    <!-- RIGHT COLUMN DETAILS START -->
                    <div class="col-md-7 space-left">
                        <!-- ALLOTMENT DETAILS START -->
                        <h4 class="text-header mt-1 mb-3">BUDGET ALLOTMENT INFORMATION</h4>
                        <div id="datatable-3" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="allotmentBreakdownTable2" class="display dataTable table w-100 table-striped mt-4" aria-describedby="allotmentBreakdown_info">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('GL ACCOUNT CODE') }}</th>
                                                    <th>{{ __('GL ACCOUNT DETAILS') }}</th>
                                                    <th>{{ __('AMOUNT') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="5" class="dataTables_empty">Loading...</td>
                                                </tr>
                                            </tbody>
                                            <tfooter>
                                                <tr>
                                                    <th colspan="3" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                    <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                </tr>
                                            </tfooter>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ITEM DETAILS END -->
                    </div>
                    <!-- RIGHT COLUMN DETAILS END -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>