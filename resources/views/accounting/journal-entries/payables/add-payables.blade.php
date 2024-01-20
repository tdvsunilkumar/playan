<div class="modal form-inner fade" id="add-payable-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="addSupplierLabel" tabindex="-1">
    <div class="modal-dialog modal-xl modal-xxl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-0">
                <div class="input-group search-group">
                    <input class="form-control border-end-0 border" type="search" id="keyword2" placeholder="search for keywords">
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row" style="margin-top: -1px">
                    <div class="col-sm-12">
                        <div class="table-responsive datatable-result">
                            <table id="available-payable-table" class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="all">
                                            </div>
                                        </th>
                                        <th class="text-center">Transaction</th>
                                        <th class="sliced text-start">GL Account</th>
                                        <th class="text-center">VAT Type</th>
                                        <th class="text-center">EWT</th>
                                        <th class="text-center">EVAT</th>
                                        <th class="sliced text-start">ITEMS</th>
                                        <th class="text-center">QUANTITY</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">AMOUNT</th>
                                        <th class="text-center">TOTAL AMOUNT</th>
                                        <th class="text-center">DUE DATE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary mw-200"><span>Save &amp; Close</span></button>
            </div>
        </div>
    </div>
</div>