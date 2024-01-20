<div class="modal form fade" id="purchase-request-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="purchaseRequestLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="purchaseRequestLabel">Manage Purchase Request</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- LEFT COLUMN DETAILS START -->
                    <div class="col-md-5 border-right space-right">
                        <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                            <li class="nav-item" role="departmental-request">
                                <button class="nav-link" id="request-details-tab" data-bs-toggle="pill" data-bs-target="#request-details" type="button" role="tab" aria-controls="request-details" aria-selected="true">1) REQUEST </button>
                            </li>
                            <li class="nav-item" role="for-alob">
                                <button class="nav-link" id="alob-details-tab" data-bs-toggle="pill" data-bs-target="#alob-details" type="button" role="tab" aria-controls="alob-details" aria-selected="false">2) ALOB</button>
                            </li>
                            <li class="nav-item" role="for-pr">
                                <button class="nav-link active" id="pr-details-tab" data-bs-toggle="pill" data-bs-target="#pr-details" type="button" role="tab" aria-controls="pr-details" aria-selected="false">3) PR</button>
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
                            @include('general-services.purchase-requests.request-details') 
                            <!-- REQUEST DETAILS END -->

                            <!-- ALOB DETAILS START -->
                            @include('general-services.purchase-requests.alob-details') 
                            <!-- ALOB DETAILS END -->

                            <!-- ALOB DETAILS START -->
                            @include('general-services.purchase-requests.pr-details') 
                            <!-- ALOB DETAILS END -->
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
                                                    <th class="sliced">{{ __('ITEM DETAILS') }}</th>
                                                    <th>{{ __('UOM') }}</th>
                                                    <th>{{ __('QUANTITY') }}</th>
                                                    <th>{{ __('PREPARED') }}</th>
                                                    <th>{{ __('PURCHASED') }}</th>
                                                    <th>{{ __('POSTED') }}</th>
                                                    <th>{{ __('UNIT PRICE') }}</th>
                                                    <th>{{ __('TOTAL PRICE') }}</th>
                                                    <th>{{ __('STATUS') }}</th>
                                                    <th>{{ __('ACTIONS') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                </tr>
                                            </tbody>
                                            <tfooter>
                                                <tr>
                                                    <th colspan="9" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                    <th colspan="2" class="text-start fs-5">{{ __('TOTAL AMOUNT') }}</th>
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
                <button type="button" class="btn send-btn bg-print align-middle d-flex justify-content-center">Send Request</button>
                <button type="button" class="btn print-btn bg-print align-middle d-flex justify-content-center hidden">Print</button>
            </div>
        </div>
    </div>
</div>