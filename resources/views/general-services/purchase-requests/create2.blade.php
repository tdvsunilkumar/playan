<div class="modal form fade" id="purchase-request2-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="purchaseRequestLabel" tabindex="-1">
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
                            <li class="nav-item" role="for-alob">
                                <button class="nav-link" id="alob-details-tab" data-bs-toggle="pill" data-bs-target="#alob-details2" type="button" role="tab" aria-controls="alob-details2" aria-selected="false">1) ALOB</button>
                            </li>
                            <li class="nav-item" role="for-pr">
                                <button class="nav-link active" id="pr-details-tab" data-bs-toggle="pill" data-bs-target="#pr-details2" type="button" role="tab" aria-controls="pr-details2" aria-selected="false">2) PR</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- ALOB DETAILS START -->
                            @include('general-services.purchase-requests.alob-details2') 
                            <!-- ALOB DETAILS END -->

                            <!-- ALOB DETAILS START -->
                            @include('general-services.purchase-requests.pr-details2') 
                            <!-- ALOB DETAILS END -->
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
                                        <table id="itemRequisitionTable2" class="display dataTable table w-100 table-striped mt-4" aria-describedby="itemRequisition_info">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th class="sliced">{{ __('ITEM DETAILS') }}</th>
                                                    <th>{{ __('UOM') }}</th>
                                                    <th>{{ __('QUANTITY') }}</th>
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
                                                    <th colspan="6" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                    <th colspan="2" class="text-start fs-5">{{ __('TOTAL AMOUNT') }}</th>
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
                                            <table id="allotmentBreakdownTable2" class="display dataTable table w-100 table-striped mt-4" aria-describedby="allotmentBreakdown_info">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('GL ACCOUNT CODE') }}</th>
                                                        <th>{{ __('GL ACCOUNT DETAILS') }}</th>
                                                        <th>{{ __('AMOUNT') }}</th>
                                                        <th class="text-center">{{ __('ACTIONS') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                                <tfooter>
                                                    <tr>
                                                        <th colspan="4" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                        <th colspan="1" class="text-start fs-5">{{ __('TOTAL AMOUNT') }}</th>
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