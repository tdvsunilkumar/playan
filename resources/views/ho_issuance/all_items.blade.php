<style>
    .mr-2{
        margin-right: 12px;
    }
</style>
<div class="modal form-inner" id="addItemsInventory" data-backdrop="static" style="z-index:9999999;">
    <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
        <div class="modal-content" id="BuildingPermit">
            <div class="modal-header">
                <h5 class="modal-title">Inventory List</h5>
              </div>
            <div class="modal-body">

                <div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
                    <div id="flush-collapseone" class="accordion-collapse collapse show">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="receiver-group">
                                        {{ Form::label('filter_by', __('Filter By'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            @foreach ($categories as $key => $val)
                                                <input type="checkbox" name="category" class="category" value="{{ $val->inv_category }}" class="category" />
                                                {{ Form::label('filter_by',$val->inv_category ,['class'=>'form-label mr-2']) }}
                                            @endforeach
                                        </div>
                                        <span class="validate-err" id="err_receiver_name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Search', __('Search'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('search') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('search',  
                                                null, ['class' => 'form-control', 'id' => 'search-items', 'placeholder' => 'Search By Product Name']) !!}
                                        </div>
                                        <span class="validate-err" id="err_search"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" onchange="selectAllItem(this.value)" class="select-all" />
                                            </th>
                                            <th>Control No</th>
                                            <th>Product Name & Description</th>
                                            <th>Received</th>
                                            <th>Issued</th>
                                            <th>Adjusted</th>
                                            <th>Balance</th>
                                            <th>Unit</th>
                                            <th>Expiration Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="all_items_tbody"></tbody>
                                </table>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination"></ul>
                                  </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="button" value="{{__('Cancel')}}" class="btn cancel-form btn-light" data-bs-dismiss="modal">
                    <input type="button" value="Submit" class="btn item-form btn-primary">
                </div>
            </div>
        </div>
    </div>
</div>