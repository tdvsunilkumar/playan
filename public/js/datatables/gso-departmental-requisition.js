!function($) {
    "use strict";

    var requisition = function() {
        this.$body = $("body");
    };

    var sortBy = '', orderBy = '', _requisitionID = 0, _lineID = 0; var _status = 'all'; var _itemTable = ''; var _tracking = [];

    requisition.prototype.required_fields = function() {
        
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                if ($input.val() == '') {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                if ($input.hasClass('selectpicker')) {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    requisition.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    requisition.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    requisition.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    }

    requisition.prototype.fetchLineID = function()
    {
        return _lineID;
    }

    requisition.prototype.load_contents = function(_page = 0) 
    {   
        var table = new DataTable('#departmentalRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'general-services/departmental-requisitions/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                }, 
                complete: function() {
                    $.requisition.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.requisition.hideTooltip();
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
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control);
                $(row).attr('data-row-total', data.total);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-obr', data.obr_no);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex">' +
                '<option value="draft">Draft</option>' +
                '<option value="for approval">For Approval</option>' +
                '<option value="requested">Requested</option>' +    
                '<option value="for alob approval">For ALOB Approval</option>' +
                '<option value="allocated">Allocated</option>' +      
                '<option value="for pr approval">For PR Approval</option>' +
                '<option value="prepared">Prepared</option>' + 
                '<option value="for rfq approval">For RFQ Approval</option>' +
                '<option value="quoted">Quoted</option>' +  
                '<option value="for abstract approval">For Abstract Approval</option>' +
                '<option value="estimated">Estimated</option>' +   
                '<option value="for resolution approval">For Resolution Approval</option>' +
                '<option value="awarded">Awarded</option>' +  
                '<option value="for po approval">For PO Approval</option>' +      
                '<option value="purchased">Purchased</option>' +
                '<option value="partial">Partial</option>' +
                '<option value="completed">Completed</option>' +
                '<option value="cancelled">Cancelled</option>' +
                '<option value="all">All</option>' +
                '</select></label>');           
                $('select[name="status"]').val(_status);
            }, 
            columns: [
                { data: 'id', orderable: true },
                { data: 'control_no', orderable: true },
                { data: 'request_type', orderable: true },
                { data: 'department', orderable: true },
                { data: 'requestor', orderable: true },
                // { data: 'remarks', orderable: true },
                { data: 'total', orderable: true },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start' },
                // {  orderable: false, targets: 5, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    requisition.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    requisition.prototype.load_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        _itemTable = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'general-services/departmental-requisitions/item-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.requisition.shorten();
                }
            },
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
                { data: 'status_label' },
                { data: 'actions' }
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.item);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                if(_itemTable.rows().data().length > 0) {
                    $('form[name="requisitionForm"] select[name="request_type_id"]').prop('disabled', true);
                    $('form[name="requisitionForm"] select[name="fund_code_id"]').prop('disabled', true);
                    $('form[name="requisitionForm"] select[name="department_id"]').prop('disabled', true);
                    $('form[name="requisitionForm"] select[name="division_id"]').prop('disabled', true);
                    $('form[name="requisitionForm"] select[name="employee_id"]').prop('disabled', true);
                } else {
                    $('form[name="requisitionForm"] select[name="request_type_id"]').prop('disabled', false);
                    $('form[name="requisitionForm"] select[name="fund_code_id"]').prop('disabled', false);
                    $('form[name="requisitionForm"] select[name="department_id"]').prop('disabled', false);
                    $('form[name="requisitionForm"] select[name="division_id"]').prop('disabled', false);
                    $('form[name="requisitionForm"] select[name="employee_id"]').prop('disabled', false);
                }       
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: false, targets: 1, className: 'text-start hidden'},
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-end' },
                {  orderable: false, targets: 9, className: 'text-end' },
                {  orderable: false, targets: 10, className: 'text-start hidden'},
                {  orderable: false, targets: 11, className: 'text-center' },
                {  orderable: false, targets: 12, className: 'text-center' }
            ]
        } );

        return true;
    },

    requisition.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        var _complete = 0; _lineID = 0;
        var table = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'general-services/departmental-requisitions/alob-lists/' + _requisitionID,
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

    requisition.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'general-services/departmental-requisitions/fetch-allotment-via-pr/' + _requisitionID + '?column=total_amount'; 
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

    requisition.prototype.preload_select3 = function()
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
    requisition.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    requisition.prototype.perfect_scrollbar = function()
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

    requisition.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'general-services/departmental-requisitions/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    requisition.prototype.preload_selectpicker = function()
    {
        if ( $('.selectpicker') ) {
            $('.selectpicker').selectpicker();
        }
    }

    requisition.prototype.notify = function(_id)
    {
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'send-departmental-request/' + _id,
            success: function(response) {
            },
        });
    }

    requisition.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    requisition.prototype.track_request = function (_id)
    {   
        var _tracking = [];
        console.log(_baseUrl + 'general-services/departmental-requisitions/track-request/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/track-request/' + _id,
            success: function(response) {
                console.log(response);
                _tracking = response.data;
            },
            async: false
        });
        return _tracking;
    },

    requisition.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.requisition.preload_select3();
        $.requisition.load_contents();
        $.requisition.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.requisition.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when requisition keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#departmentalRequisitionTable_wrapper input[type="search"]', function (e) {
            $.requisition.load_contents('');
        });

        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.requisition.load_contents();
        });

        /*
        | ---------------------------------
        | # when item line keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#itemRequisitionTable_wrapper input[type="search"]', function (e) {
            $.requisition.load_line_contents();
        });

        /*
        | ---------------------------------
        | # when alob line keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#allotmentBreakdownTable_wrapper input[type="search"]', function (e) {
            $.requisition.load_alob_line_contents();
        });

        /*
        | ---------------------------------
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _self  = $(this);
            var _modal = $('#departmental-requisition-modal');

            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.requisition.load_line_contents();
            // var d2 = $.requisitionForm.reload_items(_modal.find('form[name="requisitionForm"] select[name="item_id"]'), _modal.find('form[name="requisitionForm"] select[name="uom_id"]'), 1);
            $.when( d1 ).done(function ( v1 ) 
            {   
                _self.prop('disabled', false).html('<i class="ti-plus text-white"></i>');
                _modal.find('form[name="requisitionForm"] input[name="requested_date"]').val(new Date().toISOString().substring(0, 10));
                _modal.find('form[name="requisitionForm"] select[name="request_type_id"]').val(2).trigger('change.select3'); 
                _modal.find('form[name="requisitionForm"] select[name="purchase_type_id"]').val(1).trigger('change.select3');
                _modal.modal('show');
            });
            
            
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#departmental-requisition-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Departmental Request');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select[name="department_id"]').prop('disabled', false);
            _modal.find('select[name="purchase_type_id"]').prop('disabled', false);
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('button.store-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('li button.nav-link').addClass('disabled').removeClass('active');
            _modal.find('li[role="departmental-request"] button.nav-link').removeClass('disabled').addClass('active');
            _modal.find('.tab-content .tab-pane').removeClass('show active');
            _modal.find('.tab-content .tab-pane[id="request-details"]').addClass('show active');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('.item-layer').removeClass('hidden');
            _modal.find('form[name="requisitionForm"] input.required, form[name="requisitionForm"] select.required, form[name="requisitionForm"] textarea.required').prop('disabled', false);
            _modal.find('textarea[name="remarks"]').prop('disabled', false);
            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text('₱0.00');
            $.requisition.load_contents();
            _requisitionID = 0;
        });
        this.$body.on('shown.bs.modal', '#departmental-requisition-modal', function (e) {
            $.requisition.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#departmentalRequisitionTable .edit-btn', function (e) {
            var _self     = $(this);
            var _id       = _self.closest('tr').attr('data-row-id');
            var _code     = _self.closest('tr').attr('data-row-code');
            var _total    = _self.closest('tr').attr('data-row-total');
            var _status   = _self.closest('tr').attr('data-row-status');
            var _modal    = $('#departmental-requisition-modal');
            var _form     = _modal.find('form[name="requisitionForm"]');
            var _alobForm = _modal.find('form[name="alobForm"]');
            var _prForm   = _modal.find('form[name="prForm"]');
            var _url      = _baseUrl + 'general-services/departmental-requisitions/edit/' + _id;
            console.log(_url);
            _requisitionID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d5 = $.requisitionForm.reload_divisions_employees(_form.find('select[name="division_id"]'), _form.find('select[name="employee_id"]'), _form.find('select[name="designation_id"]'), response.data.department_id);
                    var d2 = $.requisitionForm.reload_designation(_form.find('select[name="designation_id"]'), response.data.employee_id);
                    var d3 = $.requisitionForm.reload_items(_form.find('select[name="item_id"]'),_form.find('select[name="uom_id"]'), response.data.fund_code_id, response.data.department_id, response.data.division_id, response.data.requested_date, response.data.budget_category_id);
                    var d4 = $.requisition.load_line_contents();
                    var d1 = response.data;
                    var d6 = response.alob;
                    var d7 = $.requisition.load_alob_line_contents();
                    var d8 = $.requisition.fetch_total_allotment_amount();
                    var d9 = response.pr;
                    $.when( d1, d2, d3, d4, d5, d6, d7, d8, d9 ).done(function ( v1, v2, v3, v4, v5, v6, v7, v8, v9 ) 
                    { 
                        if (_total != '₱0.00') {
                            _modal.find('select[name="department_id"]').prop('disabled', true);
                            _modal.find('select[name="purchase_type_id"]').prop('disabled', true);
                            _modal.find('select[name="budget_category_id"]').prop('disabled', true);
                        }
                        if (_status != 'draft') {
                            _modal.find('.item-layer').addClass('hidden');
                            _modal.find('button.store-btn').addClass('hidden');
                            _modal.find('button.send-btn').addClass('hidden');
                            _form.find('input, select, textarea').prop('disabled', true);
                        }
                        if (_status == 'requested') {
                            _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled');
                        }
                        if (_status == 'allocated') {
                            _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled');
                            _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled');
                        }
                        if (_status == 'prepared') {
                            _modal.find('li[role="for-alob"] button.nav-link').removeClass('disabled');
                            _modal.find('li[role="for-pr"] button.nav-link').removeClass('disabled');
                            _modal.find('li[role="for-bidding"] button.nav-link').removeClass('disabled');
                        }
                        _modal.find('.m-form__help').text('');
                        _modal.find('table#itemRequisitionTable th.fs-5.text-end').text(_total);
                        _modal.find('.modal-header h5').html('Edit Departmental Request (<span class="variables">' + _code + '</span>)');
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
                        if (v9.length > 0) {
                            $.each(v9[0], function (k, v) {
                                _prForm.find('input[name='+k+']').val(v);
                                _prForm.find('textarea[name='+k+']').val(v);
                            });
                        }
                        _modal.find('table#allotmentBreakdownTable th:last-child.fs-5').text('₱' + $.requisition.price_separator(parseFloat(v8).toFixed(2)) );
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                        _modal.modal('show');
                    });
                },
                complete: function() {
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
        this.$body.on('click', '#itemRequisitionTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $(this).closest('.modal');
            var _url    = _baseUrl + 'general-services/departmental-requisitions/remove-line/' + _id;

            if (_status == 'draft') {
                Swal.fire({
                    html: "Are you sure? <br/>the item with code ("+ _code +") will be removed.",
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
                            type: 'DELETE',
                            url: _url,
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        var d1 = $.requisition.load_line_contents();
                                        $.when( d1 ).done(function ( v1 ) {
                                            if (response.totalAmt == 0) {
                                                _modal.find('table#itemRequisitionTable th.fs-5.text-end').text('₱0.00');
                                                _modal.find('select[name="purchase_type_id"]').prop('disabled', false);
                                                _modal.find('select[name="budget_category_id"]').prop('disabled', false);
                                                _modal.find('select.select3').trigger('change.select3');
                                            }
                                            _modal.find('table#itemRequisitionTable th.fs-5.text-end').text('₱' + $.requisition.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
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
            
        });   

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemRequisitionTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $(this).closest('.modal');
            var _url    = _baseUrl + 'general-services/departmental-requisitions/edit-line/' + _id;

            if (_status == 'draft') {
                _lineID = _id;
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d1 = $.requisitionForm.reload_uom(_modal.find('select[name="uom_id"]'), response.data.item_id);
                        $.when( d1 ).done(function ( v1 ) 
                        { 
                            $.each(response.data, function (k, v) {
                                _modal.find('input[name='+k+']').val(v);
                                _modal.find('select[name='+k+']').val(v);
                            });
                            _modal.find('textarea[name="item_remarks"]').val(response.data.remarks);
                            _modal.find('button.store-btn').html('Update Item');
                            _modal.find('select.select3').trigger('change.select3');
                        });
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
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
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#departmentalRequisitionTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _total  = $(this).closest('tr').attr('data-row-total');
            var _url    = _baseUrl + 'general-services/departmental-requisitions/send/for-approval/' + _id;
            console.log(_url);
            
            if (_status == 'draft' && _total != '₱0.00') {
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
                                if (response.status == 'success') {
                                    _self.prop('disabled', false);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-blue" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.requisition.load_contents();
                                            $.requisition.notify(_id);
                                        }
                                    );
                                } else {
                                    Swal.fire({
                                        title: "Oops...",
                                        html: "Invalid Request!",
                                        icon: "error",
                                        type: "danger",
                                        showCancelButton: false,
                                        closeOnConfirm: true,
                                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                                    });
                                    window.onkeydown = null;
                                    window.onfocus = null;    
                                }
                            },
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        })
                        : "cancel" === t.dismiss, _self.prop('disabled', false) 
                });
            } else {
                if (_total == '₱0.00') { 
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>Please add a line item first.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
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

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#departmentalRequisitionTable .print-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _rows = _self.closest('tr');
            var _code = _rows.attr('data-row-obr');
            window.open(_baseUrl + 'general-services/departmental-requisitions/print/' + _code, '_blank');
        });

        /*
        | ---------------------------------
        | # when show disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#departmentalRequisitionTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.requisitionForm.fetch_status(_id);
            var d2 = $.requisition.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _requisitionID = _id;
                    _self.prop('disabled', false);
                    _modal.find('span.code').text(_code);
                    _modal.find('textarea').val(v2).prop('disabled', true);
                    _modal.find('button.submit-disapprove-btn').addClass('hidden');
                    _modal.modal('show');
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
                    _self.prop('disabled', false);    
                }
            });
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#departmentalRequisitionTable .view-track-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _id = _self.parents('tr').attr('data-row-id');
            var _code = _self.parents('tr').attr('data-row-code');
            var _status = _self.parents('tr').attr('data-row-status');
            var _modal = $('#tracking-modal');
            var d1 = $.requisition.track_request(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                console.log(v1);
                _modal.modal('show');
                _modal.find('ul li[data-row-status="' + _status + '"]').addClass('text-danger fw-bold');
                _modal.find('ul li[data-row-status="' + _status + '"]').prevAll().addClass('text-secondary');
                _modal.find('.code').text(' (' + _code + ')');
                $.each(v1, function(y, z){
                    console.log(z);
                    _modal.find('ul li[data-row-status="' + z.status + '"] span.dates').text(z.date);
                });
            });
        });
        this.$body.on('hidden.bs.modal', '#tracking-modal', function (e) {
            var _modal = $(this);
            _modal.find('ul li').removeClass('text-secondary text-danger fw-bold');
            _modal.find('ul span.dates').text('');
        });
    }

    //init requisition
    $.requisition = new requisition, $.requisition.Constructor = requisition

}(window.jQuery),

//initializing requisition
function($) {
    "use strict";
    $.requisition.required_fields();
    $.requisition.init();
}(window.jQuery);