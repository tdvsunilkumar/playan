<div class="modal form fade" id="budget-allocation-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="budgetAllocationLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="budgetAllocationLabel">Manage Budget Allocation</h5>
            </div>
            <div class="modal-body">
                <!-- V1 START -->
                <div class="alob-v1 hidden">
                    <div class="row">
                        <!-- LEFT COLUMN DETAILS START -->
                        <div class="col-md-5 border-right space-right mt-1">
                            <div class="tab-content" id="pills-tabContent">
                                <!-- ALOB DETAILS START -->
                                @include('for-approvals.budget-allocation.alob-details2') 
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
                <!-- V1 END -->
                <!-- V2 START -->
                <div class="alob-v2 hidden">
                    <div class="row">
                        <!-- LEFT COLUMN DETAILS START -->
                        <div class="col-md-5 border-right space-right">
                            <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item" role="departmental-request">
                                    <button class="nav-link" id="request-details-tab" data-bs-toggle="pill" data-bs-target="#request-details" type="button" role="tab" aria-controls="request-details" aria-selected="true">1) REQUEST </button>
                                </li>
                                <li class="nav-item" role="for-alob">
                                    <button class="nav-link active" id="alob-details-tab" data-bs-toggle="pill" data-bs-target="#alob-details" type="button" role="tab" aria-controls="alob-details" aria-selected="false">2) ALOB</button>
                                </li>
                                <li class="nav-item" role="for-pr">
                                    <button class="nav-link disabled" id="pr-details-tab" data-bs-toggle="pill" data-bs-target="#pr-details" type="button" role="tab" aria-controls="pr-details" aria-selected="false">3) PR</button>
                                </li>
                                <li class="nav-item" role="for-bidding">
                                    <button class="nav-link disabled" id="bidding-details-tab" data-bs-toggle="pill" data-bs-target="#bidding-details" type="button" role="tab" aria-controls="bidding-details" aria-selected="false">4) BIDDING</button>
                                </li>
                                <li class="nav-item" role="for-abstract">
                                    <button class="nav-link disabled" id="abstract-details-tab" data-bs-toggle="pill" data-bs-target="#abstract-details" type="button" role="tab" aria-controls="abstract-details" aria-selected="false">5) ABSTRACT</button>
                                </li>
                                <li class="nav-item" role="for-resolution">
                                    <button class="nav-link disabled" id="resolution-details-tab" data-bs-toggle="pill" data-bs-target="#resolution-details" type="button" role="tab" aria-controls="resolution-details" aria-selected="false">6) RESOLUTION</button>
                                </li>
                                <li class="nav-item" role="for-po">
                                    <button class="nav-link disabled" id="po-details-tab" data-bs-toggle="pill" data-bs-target="#po-details" type="button" role="tab" aria-controls="po-details" aria-selected="false">7) PO</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <!-- REQUEST DETAILS START -->
                                @include('for-approvals.budget-allocation.request-details') 
                                <!-- REQUEST DETAILS END -->

                                <!-- ALOB DETAILS START -->
                                @include('for-approvals.budget-allocation.alob-details') 
                                <!-- ALOB DETAILS END -->

                                <div class="tab-pane fade" id="pr-details" role="tabpanel" aria-labelledby="pr-details-tab">PR</div>
                                <div class="tab-pane fade" id="bidding-details" role="tabpanel" aria-labelledby="bidding-details-tab">BIDDING</div>
                                <div class="tab-pane fade" id="abstract-details" role="tabpanel" aria-labelledby="abstract-details-tab">ABSTRACT</div>
                                <div class="tab-pane fade" id="resolution-details" role="tabpanel" aria-labelledby="resolution-details-tab">RESOLUTION</div>
                                <div class="tab-pane fade" id="po-details" role="tabpanel" aria-labelledby="po-details-tab">PO</div>
                            </div>
                        </div>
                        <!-- LEFT COLUMN DETAILS END -->
                        <!-- RIGHT COLUMN DETAILS START -->
                        <div class="col-md-7 space-left">
                            <!-- ITEM DETAILS START -->
                            <h4 class="text-header mb-3">Item Line Information</h4>
                            <div id="datatable-3" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="itemRequisitionTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="itemRequisition_info">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('ITEM') }}</th>
                                                        <th>{{ __('ITEM DETAILS') }}</th>
                                                        <th>{{ __('UOM') }}</th>
                                                        <th>{{ __('QUANTITY') }}</th>
                                                        <th>{{ __('PREPARED') }}</th>
                                                        <th>{{ __('PURCHASED') }}</th>
                                                        <th>{{ __('POSTED') }}</th>
                                                        <th>{{ __('UNIT PRICE') }}</th>
                                                        <th>{{ __('TOTAL PRICE') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                                <tfooter>
                                                    <tr>
                                                        <th colspan="8" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                        <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                    </tr>
                                                </tfooter>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ITEM DETAILS END -->

                            <!-- ITEM DETAILS START -->
                            <h4 class="text-header mt-3 mb-3">BUDGET ALLOTMENT INFORMATION</h4>
                            <div id="datatable-3" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="allotmentBreakdownTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="allotmentBreakdown_info">
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
                                                        <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
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
                <!-- V2 END -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn send-btn bg-print align-middle d-flex justify-content-center">Send Request</button>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
</div>