!function($) {
    "use strict";

    var obligation_request = function() {
        this.$body = $("body");
    };

    var sortBy = '', orderBy = '', _requisitionID = 0, _lineID = 0;

    obligation_request.prototype.required_fields = function() {
        
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

    obligation_request.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    obligation_request.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    obligation_request.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    }

    obligation_request.prototype.fetchLineID = function()
    {
        return _lineID;
    }

    obligation_request.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#obligationRequestTable', {
            ajax: { 
                url : _baseUrl + 'finance/obligation-requests/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.obligation_request.shorten();
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
                $(row).attr('data-row-code', data.control);
                $(row).attr('data-row-department', data.departmental_request);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'budget_control', orderable: true },
                { data: 'control_no', orderable: true },
                { data: 'department', orderable: true },
                { data: 'payee', orderable: true },
                { data: 'particulars', orderable: true },
                { data: 'total', orderable: true },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-center' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: false, targets: 4, className: 'text-start sliced' },
                {  orderable: false, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    obligation_request.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    obligation_request.prototype.load_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'finance/obligation-requests/item-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
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
                { data: 'status_label' }
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
                {  orderable: false, targets: 11, className: 'text-center' }
            ]
        } );

        return true;
    },

    obligation_request.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'finance/obligation-requests/alob-lists/' + _requisitionID,
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

    obligation_request.prototype.load_alob_line_contents2 = function(_keywords = '') 
    {   
        var _id = $.obligation_requestForm.fetch_alobID();
        var table = new DataTable('#allotmentBreakdownTable2', {
            ajax: { 
                url : _baseUrl + 'finance/obligation-requests/alob-lists2/' + _id,
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

    obligation_request.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'finance/obligation-requests/fetch-allotment-via-pr/' + _requisitionID + '?column=total_amount'; 
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

    obligation_request.prototype.preload_select3 = function()
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

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    obligation_request.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    obligation_request.prototype.perfect_scrollbar = function()
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

    obligation_request.prototype.fetch_total_allotment_amount2 = function()
    {   
        var _allotmentID = $.obligation_requestForm.fetch_alobID();
        var _total = 0;
        var _url   = _baseUrl + 'finance/obligation-requests/fetch-allotment-via-pr2/' + _allotmentID + '?column=total_amount'; 
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

    obligation_request.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.obligation_request.preload_select3();
        $.obligation_request.load_contents();
        $.obligation_request.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when requisition keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#obligationRequestTable_wrapper input[type="search"]', function (e) {
            $.obligation_request.load_contents('');
        });

        /*
        | ---------------------------------
        | # when item line keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#itemRequisitionTable_wrapper input[type="search"]', function (e) {
            $.obligation_request.load_line_contents();
        });

        /*
        | ---------------------------------
        | # when alob line keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#allotmentBreakdownTable_wrapper input[type="search"]', function (e) {
            $.obligation_request.load_alob_line_contents();
        });

        /*
        | ---------------------------------
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#departmental-requisition-modal');
            $.obligation_request.load_alob_line_contents2();
            _modal.find('.alob-v1').removeClass('hidden');
            _modal.find('form[name="alobForm2"] input[name="allob_requested_date2"]').val(new Date().toJSON().slice(0, 10));
            _modal.modal('show');
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#departmental-requisition-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Obligation Request');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select[name="department_id"]').prop('disabled', false);
            _modal.find('select[name="purchase_type_id"]').prop('disabled', false);
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('button.store-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            _modal.find('li button.nav-link').addClass('disabled').removeClass('active');
            _modal.find('li[role="departmental-request"] button.nav-link').removeClass('disabled').addClass('active');
            _modal.find('.tab-content .tab-pane').removeClass('show active');
            _modal.find('.tab-content .tab-pane[id="request-details"]').addClass('show active');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('.alob-v1, .alob-v2').addClass('hidden');
            _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('0.00');
            _modal.find('form[name="alobForm2"] input.required, form[name="alobForm2"] select.required, form[name="alobForm2"] textarea.required').prop('disabled', false);
            $.obligation_request.load_contents();
            $.obligation_requestForm.update_alobID(0);
            _requisitionID = 0;
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#obligationRequestTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _total  = _self.closest('tr').attr('data-row-total');
            var _dep    = _self.closest('tr').attr('data-row-department');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#departmental-requisition-modal');
            var _form   = _modal.find('form[name="requisitionForm"]');
            var _alobForm = _modal.find('form[name="alobForm"]');
            var _alobForm2 = _modal.find('form[name="alobForm2"]');
            if (_dep > 0) {
                var _url    = _baseUrl + 'finance/obligation-requests/edit/' + _dep;
                _requisitionID = _dep;
                _modal.find('.alob-v2').removeClass('hidden');
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d5 = $.obligation_requestForm.reload_divisions_employees(_form.find('select[name="division_id"]'), _form.find('select[name="employee_id"]'), _form.find('select[name="designation_id"]'), response.data.department_id);
                        var d2 = $.obligation_requestForm.reload_designation(_form.find('select[name="designation_id"]'), response.data.employee_id);
                        var d3 = $.obligation_requestForm.reload_items(_form.find('select[name="item_id"]'), _form.find('select[name="uom_id"]'), response.data.purchase_type_id);
                        var d4 = $.obligation_request.load_line_contents();
                        var d1 = response.data;
                        var d6 = response.alob;
                        var d7 = $.obligation_request.load_alob_line_contents();
                        var d8 = $.obligation_request.fetch_total_allotment_amount();
                        console.log(d4);
                        $.when( d1, d2, d3, d4, d5, d6, d7, d8 ).done(function ( v1, v2, v3, v4, v5, v6, v7, v8 ) 
                        { 
                            if (_total != '₱0.00') {
                                _modal.find('select[name="department_id"]').prop('disabled', true);
                                _modal.find('select[name="purchase_type_id"]').prop('disabled', true);
                            }
                            if (_status != 'draft' && _status != 'for-approval') {
                                _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled');
                            }
                            if (_status == 'allocated') {
                                _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled');
                            }
                            _modal.find('.m-form__help').text('');
                            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text(_total);
                            _modal.find('.modal-header h5').html('View Obligation Request (<span class="variables">' + _code + '</span>)');
                            $.each(v1, function (k, v) {
                                _form.find('input[name='+k+']').val(v);
                                _form.find('textarea[name='+k+']').val(v);
                                _form.find('select[name='+k+']').val(v);
                                _form.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            $.each(v6[0], function (k, v) {
                                _alobForm.find('input[name='+k+']').val(v);
                                _alobForm.find('textarea[name='+k+']').val(v);
                                _alobForm.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            });
                            if (v8 == 0) {
                                _modal.find('table#allotmentBreakdownTable th:last-child.fs-5').text(v8);
                            }
                            if (_status !== 'draft') {
                                _modal.find('.send-btn').addClass('hidden');
                                _modal.find('.print-btn').removeClass('hidden');
                            }
                            _modal.find('table#allotmentBreakdownTable th:last-child.fs-5').text('₱' + $.obligation_request.price_separator(parseFloat(v8).toFixed(2)) );
                            _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
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
                $.obligation_requestForm.update_alobID(_id);
                var _url    = _baseUrl + 'finance/obligation-requests/edits/' + _id;
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d1 = response.alob;
                        var d2 = $.obligation_requestForm.reload_division(response.alob[0].allob_department_id2);
                        var d3 = $.obligation_request.load_alob_line_contents2();
                        var d4 = $.obligation_request.fetch_total_allotment_amount2();
                        $.when( d1, d2, d3, d4 ).done(function ( v1, v2, v3, v4 ) 
                        { 
                            _alobForm2.find('#budget_year2').prop('disabled', true);
                            $.each(v1[0], function (k, v) {
                                _alobForm2.find('input[name='+k+']').val(v);
                                _alobForm2.find('textarea[name='+k+']').val(v);
                                _alobForm2.find('select[name='+k+']').val(v);
                                _alobForm2.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
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
                            _modal.find('.modal-header h5').html('View Obligation Request (<span class="variables">' + _code + '</span>)');
                            _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.obligation_request.price_separator(parseFloat(v4).toFixed(2)) );
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
        | # when view alob modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#view-alob-modal', function (e) {
            var _modal = $(this);
            var _alobForm2 = $('form[name="alobForm2"]');
            var d1 = $.obligation_request.fetch_total_allotment_amount2();
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 0) {
                    $('table#allotmentBreakdownTable2 th:first-child.fs-5').text(v1);
                } else {
                    _alobForm2.find('#allob_department_id2, #allob_division_id2, #budget_year2').prop('disabled', true);
                }
                $('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.obligation_request.price_separator(parseFloat(v1).toFixed(2)) );
                $.obligation_request.load_alob_line_contents2();
            });
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#obligationRequestTable .print-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _rows = _self.closest('tr');
            var _code = _rows.attr('data-row-code');
            window.open(_baseUrl + 'finance/obligation-requests/print/' + _code, '_blank');
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
            var _url         = _baseUrl + 'finance/obligation-requests/remove-line/' + _id;
            var _allotmentID = $.obligation_requestForm.fetch_alobID();
            var d1           = $.obligation_requestForm.fetch_alob_status(_allotmentID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {
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
                                            var d1 = $.obligation_request.load_alob_line_contents2();
                                            $.when( d1 ).done(function ( v1 ) {
                                                if (response.totalAmt == 0) {
                                                    _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('0.00');
                                                }
                                                _modal.find('table#allotmentBreakdownTable2 th:first-child.fs-5').text('₱' + $.obligation_request.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
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
    }

    //init obligation_request
    $.obligation_request = new obligation_request, $.obligation_request.Constructor = obligation_request

}(window.jQuery),

//initializing obligation_request
function($) {
    "use strict";
    $.obligation_request.required_fields();
    $.obligation_request.init();
}(window.jQuery);