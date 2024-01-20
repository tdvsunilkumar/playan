!function($) {
    "use strict";

    var purchase_request = function() {
        this.$body = $("body");
    };

    var sortBy = '', orderBy = '', _requisitionID = 0, _lineID = 0, _allotmentID = 0;
    var _itemPage = 0, _itemPage2 = 0, _itemTable1 = '', _itemTable2 = '', _prLineID = 0;

    purchase_request.prototype.required_fields = function() {
        
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                if ($input.val() == '') {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },
    
    purchase_request.prototype.fetchAlobID = function()
    {
        return _allotmentID;
    }

    purchase_request.prototype.updateAlobID = function(_id)
    {
        return _allotmentID = _id;
    }

    purchase_request.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    purchase_request.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    purchase_request.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    }

    purchase_request.prototype.fetchLineID = function()
    {
        return _lineID;
    }

    purchase_request.prototype.updatePrLineID = function(_id)
    {
        return _prLineID = _id;
    }

    purchase_request.prototype.fetchPrLineID = function()
    {
        return _prLineID;
    }
    
    purchase_request.prototype.load_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#purchaseRequestTable', {
            ajax: { 
                url : _baseUrl + 'general-services/purchase-requests/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.purchase_request.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-total', data.total);
                $(row).attr('data-row-pr', data.pr_no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-departmental', data.departmental);
            },
            columns: [
                { data: 'id', orderable: true },
                { data: 'control_no_label', orderable: true },
                { data: 'pr_no_label', orderable: true },
                { data: 'request_type', orderable: true },
                { data: 'department', orderable: true },
                { data: 'requestor', orderable: true },
                { data: 'total'},
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: false, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-end' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    purchase_request.prototype.load_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'general-services/purchase-requests/item-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.purchase_request.shorten();
                }
            },
            bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            columns: [
                { data: 'id' },
                { data: 'item_details' },
                { data: 'uom' },
                { data: 'req_quantity' },
                { data: 'pr_quantity' },
                { data: 'po_quantity' },
                { data: 'posted_quantity' },
                { data: 'unit_price' },
                { data: 'total_price' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.item);
                $(row).attr('data-row-status', data.status);
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: false, targets: 2, className: 'text-center' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-end' },
                {  orderable: false, targets: 8, className: 'text-end' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' }
            ]
        } );

        return true;
    },

    purchase_request.prototype.load_line_contents2 = function(_itemPage2 = 0) 
    {   
        _itemTable2 = new DataTable('#itemRequisitionTable2', {
            ajax: { 
                url : _baseUrl + 'general-services/purchase-requests/item-lists2/' + _allotmentID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.purchase_request.shorten();
                }
            },
            bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            columns: [
                { data: 'id' },
                { data: 'item_details' },
                { data: 'uom' },
                { data: 'pr_quantity' },
                { data: 'unit_price' },
                { data: 'total_price' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.item);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                this.api().page(_itemPage2).draw( 'page' );   
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-item">ADD ITEM</button>');           
            }, 
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    purchase_request.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-allocations/alob-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.purchase_request.shorten();
                }
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            columns: [
                { data: 'id' },
                { data: 'gl_code' },
                { data: 'gl_desc' },
                { data: 'amount' }
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_code);
                $(row).attr('data-row-desc', data.desc);
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-end' }
            ]
        } );

        return true;
    },

    purchase_request.prototype.load_alob_line_contents2 = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#allotmentBreakdownTable2', {
            ajax: { 
                url : _baseUrl + 'general-services/purchase-requests/alob-lists/' + _allotmentID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.purchase_request.shorten();
                }
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            columns: [
                { data: 'id' },
                { data: 'gl_code' },
                { data: 'gl_desc' },
                { data: 'amount' },
                { data: 'actions' }
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_code);
                $(row).attr('data-row-desc', data.desc);
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' }
            ]
        } );

        return true;
    },

    purchase_request.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    purchase_request.prototype.preload_select3 = function()
    {
        if ( $('.select3') ) {
            $.each($('.select3'), function(){
                var _self = $(this);
                var _selfID = $(this).attr('id');
                var _parentID = 'parent_' + _selfID;
                _self.closest('.form-group').attr('id', _parentID);

                _self.select3({
                    allowClear: true,
                    dropdownAutoWidth : false,
                    dropdownParent: $('#' + _parentID),
                });
            });
        }
    },

    purchase_request.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'finance/budget-allocations/fetch-allotment-via-pr/' + _requisitionID + '?column=total_amount'; 
        // console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                _total = response.data;
            },
            async: false
        });
        return _total;
    },

    purchase_request.prototype.fetch_pr_line_status = function(_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        var _url   = _baseUrl + 'general-services/purchase-requests/fetch-pr-line-status/' + _id; 
        // console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    purchase_request.prototype.fetch_pr_amount = function(_id)
    {
        var _url   = _baseUrl + 'general-services/purchase-requests/fetch-pr-amount/' + _id; 
        // console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.departmental > 0) {

                } else {
                    $('#purchase-request2-modal table#itemRequisitionTable2 th.text-danger').text('₱' + $.purchase_request.price_separator(parseFloat(Math.floor((response.item_amount * 100))/100).toFixed(2)));
                    $('#purchase-request2-modal table#allotmentBreakdownTable2 th.text-danger').text('₱' + $.purchase_request.price_separator(parseFloat(Math.floor((response.budget_amount * 100))/100).toFixed(2)));
                }
            },
            async: false
        });
        return true;
    }

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    purchase_request.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    purchase_request.prototype.perfect_scrollbar = function()
    {
        if ($("body .table-responsive")) {
            var px = new PerfectScrollbar("body .table-responsive", {
              wheelSpeed: 0.5,
              swipeEasing: 0,
              wheelPropagation: 1,
              minScrollbarLength: 40,
            });
        }

    },

    purchase_request.prototype.view_item_lines = function(_id, _button)
    {   
        var _table = $('#item-line-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/purchase-requests/view-item-lines/' + _id;
        // console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '" data-row-item-id="' + row.item_id + '" data-row-qty="' + row.req_qty + '">';
                        _rows += '<td>' + (i + 1) + '</td>';
                        _rows += '<td>' + row.item_code + '</td>';
                        _rows += '<td>' + row.item_desc + '</td>';
                        _rows += '<td class="text-center total">' + row.req_qty + '</td>';
                        _rows += '<td class="text-center remaining"><input type="text" name="pr_quantity[]" class="numeric-double form-control text-center" value="' + row.pr_qty + '"/></td>';
                        _rows += '<td class="text-center remarks"><input type="text" name="pr_remarks[]" class="form-control text-center" value="' + row.pr_remarks + '"/></td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _table.find('tbody').append(_rows);
                    _modal.modal('show'); 
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    purchase_request.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.purchase_request.preload_select3();
        $.purchase_request.load_contents();
        $.purchase_request.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 #budgetAllocationTable_wrapper input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.purchase_request.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });
        this.$body.on('keyup', '#datatable-3 #itemRequisitionTable_wrapper input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.purchase_request.load_line_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });
        this.$body.on('keyup', '#datatable-3 #allotmentBreakdownTable_wrapper input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.purchase_request.load_alob_line_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#purchase-request-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Purchase Request');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select[name="department_id"]').prop('disabled', false);
            _modal.find('select[name="purchase_type_id"]').prop('disabled', false);
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('button.store-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            _modal.find('li button.nav-link').addClass('disabled').removeClass('active');
            _modal.find('li[role="departmental-request"] button.nav-link').removeClass('disabled');
            _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled');
            _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled').addClass('active');
            _modal.find('.tab-content .tab-pane').removeClass('show active');
            _modal.find('.tab-content .tab-pane[id="pr-details"]').addClass('show active');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('form[name="alobForm"] select.required, form[name="alobForm"] textarea.required').prop('disabled', true);
            _modal.find('form[name="prForm"] .image-layer').removeClass('hidden').fadeIn();          
            _modal.find('form[name="prForm"] .detail-layer').addClass('hidden');
            _modal.find('form[name="prForm"] textarea').prop('disabled', false);
            _modal.find('button.generate-btn').html('UNLOCK PR').prop('disabled', false);
            $.purchase_request.load_contents();
            _requisitionID = 0; _allotmentID = 0;
        });
        this.$body.on('hidden.bs.modal', '#purchase-request2-modal', function (e) {
            var _modal = $(this);
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select.select3').removeClass('is-invalid').val('').trigger('change.select3'); 
            $.purchase_request.load_contents();
            _requisitionID = 0; _allotmentID = 0;
        });

        /*
        | ---------------------------------
        | # when item line modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-line-modal', function (e) {
            var _modal = $(this);
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select.select3').removeClass('is-invalid').val('').trigger('change.select3'); 
            $.purchase_request.load_line_contents();
        });
        this.$body.on('hidden.bs.modal', '#item-line2-modal', function (e) {
            var _modal = $(this);
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select.select3').removeClass('is-invalid').val('').trigger('change.select3'); 
            $.purchase_request.load_line_contents2();
            $('#add-item').prop('disabled', false).html('ADD ITEM');
            _prLineID = 0;
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#purchaseRequestTable .edit-btn, #purchaseRequestTable .view-btn', function (e) {
            var _self     = $(this);
            var _id       = (_self.closest('tr').attr('data-row-departmental') > 0) ? _self.closest('tr').attr('data-row-departmental') : _self.closest('tr').attr('data-row-id');
            var _code     = _self.closest('tr').attr('data-row-code');
            var _total    = _self.closest('tr').attr('data-row-total');
            var _status   = _self.closest('tr').attr('data-row-status');
            var _departmental = _self.closest('tr').attr('data-row-departmental');
            var _modal    = (_self.closest('tr').attr('data-row-departmental') > 0) ? $('#purchase-request-modal') : $('#purchase-request2-modal');
            var _form     = _modal.find('form[name="requisitionForm"]');
            var _alobForm = (_self.closest('tr').attr('data-row-departmental') > 0) ? _modal.find('form[name="alobForm"]') : _modal.find('form[name="alobForm2"]');
            var _prForm   = _modal.find('form[name="prForm"]');
            var _url      = (_self.closest('tr').attr('data-row-departmental') > 0) ? _baseUrl + 'general-services/purchase-requests/edit/' + _id : _baseUrl + 'general-services/purchase-requests/edits/' + _id;
            console.log(_url);

            if (_departmental > 0) {
                _requisitionID = _self.closest('tr').attr('data-row-departmental');
                _allotmentID = _self.closest('tr').attr('data-row-id');
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d5 = $.purchase_requestForm.reload_divisions_employees(_form.find('select[name="division_id"]'), _form.find('select[name="employee_id"]'), _form.find('select[name="designation_id"]'), response.data.department_id);
                        var d2 = $.purchase_requestForm.reload_designation(_form.find('select[name="designation_id"]'), response.data.employee_id);
                        var d3 = $.purchase_requestForm.reload_items(_form.find('select[name="item_id"]'), _form.find('select[name="uom_id"]'), response.data.purchase_type_id);
                        var d4 = $.purchase_request.load_line_contents();
                        var d7 = $.purchase_request.load_alob_line_contents();
                        var d1 = response.data;
                        var d6 = response.alob;
                        var d8 = $.purchase_request.fetch_total_allotment_amount();
                        var d9 = response.pr;
                        var d10 = $.purchase_request.fetch_pr_amount(_allotmentID);
                        $.when( d1, d2, d3, d4, d5, d6, d7, d8, d9, d10 ).done(function ( v1, v2, v3, v4, v5, v6, v7, v8, v9, v10 ) 
                        { 
                            if (_total != '₱0.00') {
                                _form.find('select[name="department_id"]').prop('disabled', true);
                                _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                            }
                            if (_status == 'draft') {
                                _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled');
                            }
                            if (_status != 'draft') {
                                _alobForm.find('textarea.required, select.required').prop('disabled', true);
                                _prForm.find('textarea[name="pr_remarks"]').prop('disabled', true);
                                _modal.find('button.add-alob-line-btn').addClass('hidden');
                                _modal.find('button.send-btn').addClass('hidden');
                                if (_status == 'prepared') {
                                    _modal.find('button.print-btn').removeClass('hidden');
                                }
                            }                            
                            $.each(v1, function (k, v) {
                                _form.find('input[name='+k+']').val(v);
                                _form.find('textarea[name='+k+']').val(v);
                                _form.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            $.each(v6[0], function (k, v) {
                                _alobForm.find('input[name='+k+']').val(v);
                                _alobForm.find('textarea[name='+k+']').val(v);
                                _alobForm.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            if (v8 == 0) {
                                _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text(v8);
                            }
                            if (v9.length > 0) {
                                _prForm.find('.image-layer').addClass('hidden');           
                                _prForm.find('.detail-layer').removeClass('hidden');
                                $.each(v9[0], function (k, v) {
                                    _prForm.find('input[name='+k+']').val(v);
                                    _prForm.find('textarea[name='+k+']').val(v);
                                });
                            }
                            _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text('₱' + $.purchase_request.price_separator(parseFloat(v8).toFixed(2)) );
                             _modal.find('.m-form__help').text('');
                            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text(_total);
                            _modal.find('.modal-header h5').html('Edit Purchase Request (<span class="variables">' + _code + '</span>)');
                            $.purchase_request.perfect_scrollbar();
                            _modal.modal('show');
                        });
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            } else {
                _allotmentID = _id;
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        // alert(response.alob.employee_id2);
                        var d5 = $.purchase_requestForm.reload_divisions_employees(_form.find('select[name="division_id2"]'), _form.find('select[name="employee_id2"]'), _form.find('select[name="designation_id2"]'), response.alob[0].allob_department_id2);
                        var d2 = $.purchase_requestForm.reload_designation(_form.find('select[name="designation_id"]'), response.alob[0].employee_id2);
                        // var d3 = $.purchase_requestForm.reload_items(_form.find('select[name="item_id"]'), _form.find('select[name="uom_id"]'), response.data.purchase_type_id);
                        var d4 = $.purchase_request.load_line_contents2();
                        var d7 = $.purchase_request.load_alob_line_contents2();
                        var d1 = response.data;
                        var d6 = response.alob;
                        var d8 = $.purchase_request.fetch_total_allotment_amount();
                        var d9 = response.pr;
                        var d10 = $.purchase_request.fetch_pr_amount(_allotmentID);
                        $.when( d1, d2, d4, d5, d6, d7, d8, d9, d10 ).done(function ( v1, v2, v4, v5, v6, v7, v8, v9, v10 ) 
                        { 
                            if (_total != '₱0.00') {
                                _form.find('select[name="department_id"]').prop('disabled', true);
                                _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                            }
                            if (_status == 'draft') {
                                _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled');
                                _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                            } else {
                                _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                            }
                            if (_status != 'draft') {
                                _alobForm.find('textarea.required, select.required').prop('disabled', true);
                                _prForm.find('textarea[name="pr_remarks"]').prop('disabled', true);
                                _modal.find('button.add-alob-line-btn').addClass('hidden');
                                _modal.find('button.send-btn').addClass('hidden');
                                if (_status == 'completed') {
                                    _modal.find('button.print-btn').removeClass('hidden');
                                }
                            }
                            _modal.find('.m-form__help').text('');
                            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text(_total);
                            _modal.find('.modal-header h5').html('Edit Purchase Request (<span class="variables">' + _code + '</span>)');
                            
                            $.each(v1, function (k, v) {
                                _form.find('input[name='+k+']').val(v);
                                _form.find('textarea[name='+k+']').val(v);
                                _form.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            $.each(v6[0], function (k, v) {
                                _alobForm.find('input[name='+k+']').val(v);
                                _alobForm.find('textarea[name='+k+']').val(v);
                                _alobForm.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                                if (k == 'with_pr2') {
                                    if (v == 1) {
                                        _alobForm.find('input[type="checkbox"][name="with_pr2"]').prop('checked', true);
                                    } else {
                                        _alobForm.find('input[type="checkbox"][name="with_pr2"]').prop('checked', false);
                                    }
                                } 
                            });
                            if (v8 == 0) {
                                _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text(v8);
                            }
                            if (v9.length > 0) {
                                _prForm.find('.image-layer').addClass('hidden');           
                                _prForm.find('.detail-layer').removeClass('hidden');
                                $.each(v9[0], function (k, v) {
                                    _prForm.find('input[name='+k+']').val(v);
                                    _prForm.find('textarea[name='+k+']').val(v);
                                });
                            }
                            _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text('₱' + $.purchase_request.price_separator(parseFloat(v8).toFixed(2)) );
                            $.purchase_request.perfect_scrollbar();
                            _modal.modal('show');
                        });
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            }
        }); 

        /*
        | ---------------------------------
        | # when rfq print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#purchaseRequestTable .print-btn', function (e) {
            var _self   = $(this);
            var _prNo   = $(this).closest('tr').attr('data-row-pr');
            var _url      = _baseUrl +'digital-sign?url='+'general-services/purchase-requests/print/' + _prNo;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#purchaseRequestTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _rows   = _self.closest('tr');
            var _id     = _rows.attr('data-row-id');
            var _status = _rows.attr('data-row-status');
            var _code   = _rows.attr('data-row-code');
            var _url    = _baseUrl + 'general-services/purchase-requests/send/for-pr-approval/' + _id;
            
            var d1      = $.purchase_requestForm.validate_pr(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 > 0) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>There are some fields that needed to fill first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null; 
                } else {
                    if (_status == 'allocated') {
                        _self.prop('disabled', true);
                        Swal.fire({
                            html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code +")</strong> will be sent.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, send it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn btn-blue", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _url,
                                    success: function(response) {
                                        console.log(response);
                                        _self.prop('disabled', false);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-blue" } }).then(
                                            function (e) {
                                                e.isConfirmed && ((t.disabled = !1));
                                                $.purchase_request.load_contents();
                                            }
                                        );
                                    },
                                    complete: function() {
                                        window.onkeydown = null;
                                        window.onfocus = null;
                                    }
                                })
                                : "cancel" === t.dismiss, _self.prop('disabled', false) 
                        });
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                }
            });
        });

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#purchaseRequestTable .approve-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'general-services/purchase-requests/approve/' + _id;

            var d1 = $.purchase_requestForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'for pr approval') {
                    Swal.fire({
                        html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code +")</strong> will be approved.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, approve it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn btn-success", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: _url,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.purchase_request.load_contents();
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss 
                    });
                } else {
                    if (v1 != 'requested') {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The request is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;   
                    }
                }
            });
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemRequisitionTable .edit-btn', function (e) {
                _lineID = $(this).closest('tr').attr('data-row-id');
            var _self   = $(this);

            var d1 = $.purchase_requestForm.fetch_status(_requisitionID);            
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (!($('.image-layer.hidden').length > 0)) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>Please unlock the PR form first.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;  
                } else {
                    if (v1 == 'draft') {
                        _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                        setTimeout(function () {
                            $.purchase_request.view_item_lines(_requisitionID, _self);
                        }, 500 + 300 * (Math.random() * 5));
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The item is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
                }
            });
        });

        /*
        | ---------------------------------
        | # when print button button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#purchaseRequestTable .print-btn', function (e) {
            e.preventDefault();
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-pr');
            var _url   = _baseUrl + 'general-services/purchase-requests/print/' + _code;

            var d1     = $.purchase_requestForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 != 'draft' && v1 != 'requested' && v1 != 'allocated') {
                    window.open(_url, '_blank');
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to print!<br/>Only complete PO can be print.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }        
            }); 
        }); 

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-item', function (e) {
            var _self  = $(this);
            var _modal = $('#item-line2-modal');
            var _id = $.purchase_request.fetchAlobID();
            var d1  = $.purchase_requestForm.fetch_pr_status_via_alob(_id);

            _self.prop('disabled', true).html('WAIT.....');            
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    setTimeout(function () {
                        _modal.modal('show');
                    }, 500 + 300 * (Math.random() * 5));
                } else {
                    _self.prop('disabled', false).html('ADD ITEM');
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The request is already been processed.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
            });            
        });

        /*
        | ---------------------------------
        | # when pr line edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemRequisitionTable2 .edit-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _modal  = $('#item-line2-modal');
            var _url    = _baseUrl + 'general-services/purchase-requests/edit-pr-line/' + _id;
            console.log(_url);
            var d1  = $.purchase_request.fetch_pr_line_status(_id);     
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'draft') {
                    _prLineID = _id;
                    _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                    $.ajax({
                        type: 'GET',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            $.each(response.data[0], function (k, v) {
                                _modal.find('input[name='+k+']').val(v);
                                _modal.find('textarea[name='+k+']').val(v);
                                _modal.find('select[name='+k+']').val(v);
                                _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                            _modal.find('.m-form__help').text('');
                            _modal.find('.modal-header h5').html('Edit Item Line (<span class="variables">' + _id + '</span>)');
                            _modal.modal('show');
                        },
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The request is already been processed.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
            });
        }); 

        /*
        | ---------------------------------
        | # when pr line remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemRequisitionTable2 .delete-btn', function (e) {
            var _self = $(this);
            var _id   = $(this).closest('tr').attr('data-row-id');
            var _url  = _baseUrl + 'general-services/purchase-requests/remove-pr-line/' + _id;

            var d1  = $.purchase_request.fetch_pr_line_status(_id);     
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {
                    console.log(_url);
                    Swal.fire({
                        html: "Are you sure? <br/>the item line ("+ _id +") will be removed.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, remove it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: _url,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                    .then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.purchase_request.load_line_contents2();
                                            $.purchase_request.fetch_pr_amount(_allotmentID);
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss 
                    });
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The request is already been processed.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
                
            });
            
        }); 
    }

    //init budget allocation
    $.purchase_request = new purchase_request, $.purchase_request.Constructor = purchase_request

}(window.jQuery),

//initializing budget allocation
function($) {
    "use strict";
    $.purchase_request.required_fields();
    $.purchase_request.init();
}(window.jQuery);