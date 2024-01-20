<div class="modal form-inner fade" id="add-item-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="addItemLabel" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/issuance', 'class' => '', 'name' => 'postingForm')) }}
            @csrf
            <div class="modal-header p-0">
                <div class="input-group search-group">
                    <input class="form-control border-end-0 border" type="search" id="keyword1" placeholder="search for keywords">
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row" style="margin-top: -1px">
                    <div class="col-sm-12">
                        <div id="datatable-result" class="table-responsive">
                            <table id="available-items-table" class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>REFERENCE</th>
                                        <th>ITEM CODE</th>
                                        <th class="sliced">ITEM DESCRIPTION</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">AVAILABLE QUANTITY</th>
                                        <th class="text-center">WITHDRAWN QUANTITY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn post-btn btn-primary aling-middle justify-content-center">Post Now</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>