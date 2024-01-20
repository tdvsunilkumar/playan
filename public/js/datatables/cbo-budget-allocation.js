!function($) {
    "use strict";

    var budget_allocation = function() {
        this.$body = $("body");
    };

    var sortBy = '', orderBy = '', _requisitionID = 0, _lineID = 0, _allotmentID = 0; var _allotTable1 = '', _allotTable2 = '', _obType = 0;

    budget_allocation.prototype.required_fields = function() {
        
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

    budget_allocation.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    budget_allocation.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    budget_allocation.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    }

    budget_allocation.prototype.fetchLineID = function()
    {
        return _lineID;
    }
    
    budget_allocation.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#budgetAllocationTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-allocations/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    if (_complete == 0 && _keywords != '') {
                        $('#datatable-2 #budgetAllocationTable_wrapper input[type="search"]').val(_keywords).focus();
                        _complete = 1;
                    }
                    $.budget_allocation.shorten();
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
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control);
                $(row).attr('data-row-department', data.departmental_request);
                $(row).attr('data-row-total-pr', data.total_pr);
                $(row).attr('data-row-total-alob', data.total_alob);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-type', data.type_id);
            },
            columns: [
                { data: 'budget_control', orderable: true },
                { data: 'type', orderable: true },
                { data: 'control_no', orderable: true },
                { data: 'department', orderable: true },
                { data: 'particulars', orderable: true },
                { data: 'total', orderable: true },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-center' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    budget_allocation.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    budget_allocation.prototype.load_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-allocations/item-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    if (_complete == 0 && _keywords != '') {
                        $('#datatable-3 #itemRequisitionTable_wrapper input[type="search"]').val(_keywords).focus();
                        _complete = 1;
                    }
                    $.budget_allocation.shorten();
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
            columns: [
                { data: 'id' },
                { data: 'item' },
                { data: 'item_details' },
                { data: 'uom' },
                { data: 'req_quantity' },
                { data: 'pr_quantity' },
                { data: 'po_quantity' },
                { data: 'posted_quantity' },
                { data: 'unit_price' },
                { data: 'total_price' },
                { data: 'status' },
                { data: 'status_label' },
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.item);
                $(row).attr('data-row-status', data.status);
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: false, visible: false,  targets: 1},
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-end' },
                {  orderable: false, targets: 9, className: 'text-end' },
                {  orderable: false, visible: false,  targets: 10},
                {  orderable: false, targets: 11, className: 'text-center' },
            ]
        } );

        return true;
    },

    budget_allocation.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        _allotTable1 = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-allocations/alob-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.budget_allocation.shorten();
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
            initComplete: function(){
                if(_allotTable1.rows().data().length > 0 || _obType == 1) {
                    $('form[name="alobForm"] select[name="fund_code_id"]').prop('disabled', true);
                    $('form[name="alobForm"] select[name="budget_category_id"]').prop('disabled', true);
                } else {
                    $('form[name="alobForm"] select[name="fund_code_id"]').prop('disabled', false);
                    $('form[name="alobForm"] select[name="budget_category_id"]').prop('disabled', false);
                }          
            }, 
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-end' },
                {  orderable: false, targets: 4, className: 'text-center' },
            ]
        } );

        return true;
    },

    budget_allocation.prototype.load_alob_line_contents2 = function(_keywords = '') 
    {   
        _allotTable2 = new DataTable('#allotmentBreakdownTable2', {
            ajax: { 
                url : _baseUrl + 'finance/budget-allocations/alob-lists2/' + _allotmentID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
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
            initComplete: function(){
                if(_allotTable2.rows().data().length > 0) {
                    $('form[name="alobForm2"] select[name="fund_code_id2"]').prop('disabled', true);
                    $('form[name="alobForm2"] select[name="budget_category_id2"]').prop('disabled', true);
                } else {
                    $('form[name="alobForm2"] select[name="fund_code_id2"]').prop('disabled', false);
                    $('form[name="alobForm2"] select[name="budget_category_id2"]').prop('disabled', false);
                }          
            }, 
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-end' },
                {  orderable: false, targets: 4, className: 'text-center' },
            ]
        } );

        return true;
    },

    budget_allocation.prototype.preload_select3 = function()
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

    budget_allocation.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'finance/budget-allocations/fetch-allotment-via-pr/' + _requisitionID + '?column=total_amount'; 
        console.log(_url);
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

    budget_allocation.prototype.fetch_total_allotment_amount2 = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'finance/budget-allocations/fetch-allotment-via-pr2/' + _allotmentID + '?column=total_amount'; 
        console.log(_url);
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

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    budget_allocation.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    budget_allocation.prototype.perfect_scrollbar = function()
    {
        if ($(".table-responsive")) {
            $.each($('.table-responsive'), function(_i = 0){
                _i++;
                $(this).attr('id', '_table' + _i);
                var _divID = '#' + $(this).attr('id');
                var px = new PerfectScrollbar(_divID, {
                    wheelSpeed: 0.5,
                    swipeEasing: 0,
                    wheelPropagation: 1,
                    minScrollbarLength: 40,
                });
            });
        }

    },

    budget_allocation.prototype.fetch_alobID = function()
    {
        return _allotmentID;
    }

    budget_allocation.prototype.update_alobID = function(_id)
    {
        return _allotmentID = _id;
    }

    budget_allocation.prototype.validate_table = function($table)
    {   
        $('.pager').remove();
        $table.each(function() {
            var currentPage = 0;
            var numPerPage = 8;
            var $table = $(this);
            $table.bind('repaginate', function() {
                $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
            });
            $table.trigger('repaginate');
            var numRows = $table.find('tbody tr').length;
            var numPages = Math.ceil(numRows / numPerPage);
            var $pager = $('<div class="pager d-flex pagination justify-content-center"></div>');
            for (var page = 0; page < Math.min(numPages,5); page++) {
                $('<span class="page-number page-item"></span>').text(page + 1).bind('click', {
                    newPage: page
                }, function(event) {
                    currentPage = event.data['newPage'];
                    $table.trigger('repaginate');
                    $(this).addClass('active').siblings().removeClass('active');
                }).appendTo($pager).addClass('clickable');
            }
            $pager.insertAfter($table).find('span.page-number:first').addClass('active');
        });
    },

    budget_allocation.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },


    budget_allocation.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.budget_allocation.preload_select3();
        $.budget_allocation.load_contents();
        $.budget_allocation.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.budget_allocation.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 #budgetAllocationTable_wrapper input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.budget_allocation.load_contents(_self.val());
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
                var d1 = $.budget_allocation.load_line_contents(_self.val());
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
                var d1 = $.budget_allocation.load_alob_line_contents(_self.val());
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
        this.$body.on('hidden.bs.modal', '#budget-allocation-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Departmental Request');
            _modal.find('input[type="text"], textarea').val('').removeClass('is-invalid');
            _modal.find('select[name="department_id"]').prop('disabled', false);
            _modal.find('select[name="purchase_type_id"]').prop('disabled', false);
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('button.store-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('li button.nav-link').addClass('disabled').removeClass('active');
            _modal.find('li[role="departmental-request"] button.nav-link').removeClass('disabled');
            _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled').addClass('active');
            _modal.find('.tab-content .tab-pane').removeClass('show active');
            _modal.find('.tab-content .tab-pane[id="alob-details"]').addClass('show active');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('form[name="alobForm"] select.required, form[name="alobForm"] textarea.required').prop('disabled', false);
            _modal.find('form[name="alobForm2"] select.required, form[name="alobForm2"] textarea.required').prop('disabled', false);
            _modal.find('.alob-v2, .alob-v1').addClass('hidden');
            _modal.find('button.add-alob-line-btn').removeClass('hidden');
            _modal.find('button.add-alob-line-btn2').removeClass('hidden');
            $.budget_allocation.load_contents();
            _requisitionID = 0; _allotmentID = 0; _obType = 0;
        });
        this.$body.on('shown.bs.modal', '#budget-allocation-modal', function (e) {
            $.budget_allocation.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when view alob modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#view-alob-modal', function (e) {
            var _modal = $(this);
            if (_requisitionID > 0) {
                var d1 = $.budget_allocation.fetch_total_allotment_amount();
            } else {
                var d1 = $.budget_allocation.fetch_total_allotment_amount2();
            }
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (_requisitionID > 0) {
                    if (v1 == 0) {
                        $('#allotmentBreakdownTable_wrapper table#allotmentBreakdownTable th.fs-5.text-end').text(v1);
                    }
                    $('#allotmentBreakdownTable_wrapper table#allotmentBreakdownTable th.fs-5.text-end').text('₱' + $.budget_allocation.price_separator(parseFloat(v1).toFixed(2)) );
                    $.budget_allocation.load_alob_line_contents();
                } else {
                    if (v1 == 0) {
                        $('table#allotmentBreakdownTable2 th:first-child.fs-5').text(v1);
                    }
                    $('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.budget_allocation.price_separator(parseFloat(v1).toFixed(2)) );
                    $.budget_allocation.load_alob_line_contents2();
                }
            });
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _total  = _self.closest('tr').attr('data-row-total-pr');
            var _status = _self.closest('tr').attr('data-row-status');
            _obType     = _self.closest('tr').attr('data-row-type');
            var _modal  = $('#budget-allocation-modal');
            var _dep    = _self.closest('tr').attr('data-row-department');
            var _form   = _modal.find('form[name="requisitionForm"]');
            var _alobForm = _modal.find('form[name="alobForm"]');
            var _alobForm2 = _modal.find('form[name="alobForm2"]');
            
            if (_dep > 0) {
                _modal.find('.alob-v2').removeClass('hidden');
                _requisitionID = _dep; _allotmentID = _id;
                var _url    = _baseUrl + 'finance/budget-allocations/edit/' + _dep;
                _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d5 = $.budget_allocationForm.reload_divisions_employees(_form.find('select[name="division_id"]'), _form.find('select[name="employee_id"]'), _form.find('select[name="designation_id"]'), response.data.department_id);
                        var d2 = $.budget_allocationForm.reload_designation(_form.find('select[name="designation_id"]'), response.data.employee_id);
                        var d3 = $.budget_allocationForm.reload_items(_form.find('select[name="item_id"]'), _form.find('select[name="uom_id"]'), response.data.purchase_type_id);
                        var d4 = $.budget_allocation.load_line_contents();
                        var d7 = $.budget_allocation.load_alob_line_contents();
                        var d1 = response.data;
                        var d6 = response.alob;
                        var d8 = $.budget_allocation.fetch_total_allotment_amount();
                        console.log(d4);
                        $.when( d1, d2, d3, d4, d5, d6, d7, d8 ).done(function ( v1, v2, v3, v4, v5, v6, v7, v8 ) 
                        { 
                            if (_total != '₱0.00') {
                                _form.find('select[name="department_id"]').prop('disabled', true);
                                _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                                _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                            }
                            if (_status != 'draft') {
                                _alobForm.find('textarea.required, select.required').prop('disabled', true);
                                _modal.find('button.add-alob-line-btn').addClass('hidden');
                                _modal.find('button.send-btn').addClass('hidden');
                            }
                            _modal.find('.m-form__help').text('');
                            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text('₱' + $.budget_allocation.price_separator(parseFloat(_total).toFixed(2)));
                            _modal.find('.modal-header h5').html('Edit Budget Allocation (<span class="variables">' + _code + '</span>)');
                            
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
                            _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
                            _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text('₱' + $.budget_allocation.price_separator(parseFloat(v8).toFixed(2)));
                            
                            _modal.modal('show');
                        });
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            } else {
                _modal.find('.alob-v1').removeClass('hidden');
                _allotmentID = _id;
                var _url    = _baseUrl + 'finance/budget-allocations/edits/' + _id;
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d1 = response.alob;
                        var d2 = $.budget_allocationForm.reload_division(response.alob[0].allob_department_id2);
                        var d3 = $.budget_allocation.load_alob_line_contents2();
                        var d4 = $.budget_allocation.fetch_total_allotment_amount2();
                        $.when( d1, d2, d3, d4 ).done(function ( v1, v2, v3, v4 ) 
                        { 
                            _alobForm2.find('#budget_year2').prop('disabled', true);
                            $.each(v1[0], function (k, v) {
                                _alobForm2.find('input[name='+k+']').val(v);
                                _alobForm2.find('textarea[name='+k+']').val(v);
                                _alobForm2.find('select[name='+k+']').val(v);
                                _alobForm2.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                                if (k == 'with_pr2') {
                                    if (v == 1) {
                                        _alobForm2.find('input[type="checkbox"][name="with_pr2"]').prop('checked', true);
                                    } else {
                                        _alobForm2.find('input[type="checkbox"][name="with_pr2"]').prop('checked', false);
                                    }
                                } 
                            });

                            if (v4 == 0) {
                                _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text(v4);
                            } else {
                                _alobForm2.find('#allob_department_id2, #allob_division_id2').prop('disabled', true);
                            }
                            if (_status !== 'draft') {
                                _modal.find('.add-alob-line-btn2').addClass('hidden');
                                _modal.find('.send-btn').addClass('hidden');
                                _modal.find('.print-btn').removeClass('hidden');
                                _alobForm2.find('input.required, select.required, textarea.required').prop('disabled', true);
                            }
                            _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.budget_allocation.price_separator(parseFloat(v4).toFixed(2)) );
                        });
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                        _modal.modal('show');
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
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _rows   = _self.closest('tr');
            var _id     = _rows.attr('data-row-id');
            var _status = _rows.attr('data-row-status');
            var _dep    = (_rows.attr('data-row-department') > 0) ? _rows.attr('data-row-department') : 0;
            var _code   = _rows.attr('data-row-code');
            var _total1 = _rows.attr('data-row-total-pr')
            var _total2 = _rows.attr('data-row-total-alob')
            var _url    = _baseUrl + 'finance/budget-allocations/send/for-alob-approval/' + _id + '?departmental=' + _dep;
            
            console.log(_url);
            if (_dep > 0 ) {
                if(parseFloat(_total2) >= parseFloat(_total1)) { 
                    if (_status == 'draft') {
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
                                                $.budget_allocation.load_contents();
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
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The alob amount should be higher or equal to item amount.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
            } else {
                if (parseFloat(_total2) > 0) {
                    if (_status == 'draft') {
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
                                                $.budget_allocation.load_contents();
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
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The alob amount should be higher than zero.",
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

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#allotmentBreakdownTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $(this).closest('.modal');
            var _url    = _baseUrl + 'finance/budget-allocations/remove-line/' + _id;

            var d1 = $.budget_allocationForm.fetch_status(_requisitionID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'requested') {
                    Swal.fire({
                        html: "Are you sure? <br/>the alob line with <strong>GL Code ("+ _code +")</strong><br/>will be removed.",
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
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            var d1 = $.budget_allocation.load_alob_line_contents();
                                            $.when( d1 ).done(function ( v1 ) {
                                                if (response.totalAmt == 0) {
                                                    _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text('₱0.00');
                                                }
                                                _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text('₱' + $.budget_allocation.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
                                            });
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
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            });
        });  
        /*
        | ---------------------------------
        | # when remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#allotmentBreakdownTable2 .delete-btn', function (e) {
            var _id          = $(this).closest('tr').attr('data-row-id');
            var _code        = $(this).closest('tr').attr('data-row-code');
            var _modal       = $(this).closest('.modal');
            var _url         = _baseUrl + 'finance/budget-allocations/remove-line/' + _id;
            var d1           = $.budget_allocationForm.fetch_alob_status(_allotmentID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending') {
                    Swal.fire({
                        html: "Are you sure? <br/>the alob line with <strong>GL Code ("+ _code +")</strong><br/>will be removed.",
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
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            var d1 = $.budget_allocation.load_alob_line_contents2();
                                            $.when( d1 ).done(function ( v1 ) {
                                                if (response.totalAmt == 0) {
                                                    _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('0.00');
                                                }
                                                _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.budget_allocation.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
                                            });
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
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            });
        });  


        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .approve-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _total1 = $(this).closest('tr').attr('data-row-total').replace('₱', '');
            var _total2 = $(this).closest('tr').attr('data-row-total-alob').replace('₱', '');
            var _modal  = $(this).closest('.modal');
            var _url    = _baseUrl + 'finance/budget-allocations/approve/' + _id;

            var d1 = $.budget_allocationForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'for alob approval') {
                    if (parseFloat(_total2.replace(',', '')) >= parseFloat(_total1.replace(',', ''))) {
                        Swal.fire({
                            html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code +")</strong> will be sent.",
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
                                                $.budget_allocation.load_contents();
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
                            html: "Unable to proceed!<br/>The alob amount should be higher or equal to item amount.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    }
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
    }

    //init budget allocation
    $.budget_allocation = new budget_allocation, $.budget_allocation.Constructor = budget_allocation

}(window.jQuery),

//initializing budget allocation
function($) {
    "use strict";
    $.budget_allocation.required_fields();
    $.budget_allocation.init();
}(window.jQuery);