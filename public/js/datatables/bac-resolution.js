!function($) {
    "use strict";

    var bac_resolution = function() {
        this.$body = $("body");
    };

    var _rfqID = 0; var _table; var _prTable; var _projectName = ''; var _supplierStatus = ''; var _supplierID = 0;

    bac_resolution.prototype.required_fields = function() {
        
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

    bac_resolution.prototype.load_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/resolution/lists'); 
        _table = new DataTable('#rfqTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/resolution/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.bac_resolution.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
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
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-rfq', data.rfq);
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'id' },
                { data: 'control_no_label' },
                { data: 'project_name' },
                { data: 'agencies' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_resolution.prototype.load_pr_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/pr-lists/' + _rfqID); 
        _prTable = new DataTable('#prTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/pr-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_resolution.shorten();
                }
            },     
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.pr_no);
            },
            columns: [
                { data: 'pr_no' },
                { data: 'department' },
                { data: 'rfq_no' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
            ]
        } );

        return true;
    },

    bac_resolution.prototype.load_supplier_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/abstract-of-canvass/supplier-lists/' + _rfqID); 
        _prTable = new DataTable('#supplierTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/abstract-of-canvass/supplier-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_resolution.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.branch);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'supplier' },
                { data: 'total_canvass' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_resolution.prototype.load_item_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/item-lists/' + _rfqID); 
        _prTable = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/item-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_resolution.shorten();
                }
            }, 
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 10,
            // lengthMenu: [
            //     [5, 10, 25, 50, -1],
            //     [5, 10, 25, 50, 'All'],
            // ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.pr_no);
            },
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'description' },
                { data: 'quantity' },
                { data: 'uom' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' }
            ]
        } );

        return true;
    },

    bac_resolution.prototype.load_committee_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/resolution/committee-lists/' + _rfqID); 
        _prTable = new DataTable('#committeeTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/resolution/committee-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_resolution.shorten();
                }
            }, 
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-committee">ADD COMMITTEE</button>');           
            },  
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
            },
            columns: [
                { data: 'name' },
                { data: 'department' },
                { data: 'division' },
                { data: 'designation' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_resolution.prototype.fetchID = function()
    {
        return _rfqID;
    }

    bac_resolution.prototype.updateID = function(_id)
    {
        return _rfqID = _id;
    }

    bac_resolution.prototype.fetchProjectName = function()
    {
        return _projectName;
    }

    bac_resolution.prototype.updateProjectName = function(_proj)
    {
        return _projectName = _proj;
    }

    bac_resolution.prototype.fetchSupplierStatus = function()
    {
        return _supplierStatus;
    }

    bac_resolution.prototype.fetchSupplierID = function()
    {
        return _supplierID;
    }

    bac_resolution.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    bac_resolution.prototype.preload_select3 = function()
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

    bac_resolution.prototype.perfect_scrollbar = function()
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

    bac_resolution.prototype.validate_table = function($table)
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

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    bac_resolution.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    bac_resolution.prototype.edit_supplier = function(_supplier, _url, _modal, _code, _button, _status)
    {   
        var _table = _modal.find('table'); _table.find('tbody').empty();
        var _rows  = ''; _modal.find('h5.modal-title').text('Item Canvass ('+_code+')');
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                var _totalCost = 0;
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-supplier="' + _supplier + '" data-row-item="' + row.item_id + '" data-row-quantity="' + row.item_quantity + '">';
                        _rows += '<td>' + (row.item_code ? row.item_code : '') + '</td>';
                        _rows += '<td>' + (row.item_description ? row.item_description : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_quantity ? row.item_quantity : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_uom ? row.item_uom : '') + '</td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid" name="brand" type="text" value="' + row.brand + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="unit_cost" type="text" value="' + row.unit_cost + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="total_cost" type="text" value="' + row.total_cost + '" disabled="disabled"><span class="m-form__help text-danger"></span></td>';
                        _rows += '</tr>';
                        if (parseFloat(row.total_cost) > 0) { 
                            _totalCost += parseFloat(row.total_cost);
                        }
                    });
                    _button.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _table.find('tbody').append(_rows);
                    _table.find('tfoot th:last-child').text($.bac_resolution.price_separator(parseFloat(_totalCost).toFixed(2)));
                    if (_status !== 'pending') {
                        _modal.find('button.submit-btn').addClass('hidden');
                        _modal.find('input').prop('disabled', true);
                    }
                    $.each(response.canvass[0], function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                    });
                    _modal.modal('show'); 
                }
            },
            async: false,
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
            }
        });
    },

    bac_resolution.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    bac_resolution.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.bac_resolution.preload_select3();
        $.bac_resolution.load_contents();
        $.bac_resolution.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide')
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Abstract Of Canvass');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('label[for="control_no"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('label[for="total_budget"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            // _modal.find('input, select, textarea').prop('disabled', false);
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.bac_resolution.load_contents();
            $.bac_resolution.hideTooltip();
            _rfqID = 0;
        });
        this.$body.on('shown.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            $.bac_resolution.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when add committee modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-committee-modal', function (e) {
            $.bac_resolution.load_committee_contents();
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-rfq');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $('#rfq-modal');
            var _url    = _baseUrl + 'general-services/bac/resolution/edit/' + _id;
            console.log(_url);
            _rfqID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    _modal.find('label[for="control_no"].text-danger').text(response.data.control_no);
                    _projectName = response.data.project_name;
                    if (response.data.total_budget) {
                        _modal.find('label[for="total_budget"].text-danger').text('₱' + $.bac_resolution.price_separator(parseFloat(response.data.total_budget).toFixed(2)));
                    }
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    $.each(response.abstract, function (k, v) {
                        _modal.find('textarea[name='+k+']').val(v);
                    });
                    _modal.find('input[name="agencies"]').val(response.agencies);

                    var _supplierContent = $('#supplier-content');
                    var _supplierData = '';
                    var _found = response.suppliers.find(element => element.is_selected == 1);
                    _supplierContent.empty();
                    $.each(response.suppliers, function (k, v) {
                        console.log(_found);
                        if (v.is_selected > 0) {
                            _supplierData += '<div class="accordion-item">';
                            _supplierData += '<h2 class="accordion-header" id="flush-heading' + v.supplier_id + '">';
                            _supplierData += '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' + v.supplier_id + '" aria-expanded="false" aria-controls="flush-collapse3">';
                            _supplierData += '<input value="' + v.supplier_id + '" class="form-check-input me-2" type="radio" name="is_selected" checked="checked" ' + ((_found != undefined) ? 'disabled="disabled"' : '') + '/>' + v.supplier + ' -&nbsp; <strong class="text-danger">' + '₱' + $.bac_resolution.price_separator(parseFloat(v.canvass).toFixed(2)) + '</strong>';
                            _supplierData += '</button>';
                            _supplierData += '</h2>';
                            _supplierData += '<div id="flush-collapse' + v.supplier_id + '" class="accordion-collapse collapse show p-0" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample">';
                            _supplierData += '<div class="accordion-body p-0">';
                            _supplierData += '<table class="table mb-0">';
                            _supplierData += '<thead>';
                            _supplierData += '<tr>';
                            _supplierData += '<th style="width:5%">#</th>';
                            _supplierData += '<th>Item Description</th>';
                            _supplierData += '<th>Model</th>';
                            _supplierData += '<th class="text-center">Quantity</th>';
                            _supplierData += '<th class="text-center">UOM</th>';
                            _supplierData += '<th class="text-center">Unit Cost</th>';
                            _supplierData += '<th class="text-end">Total Cost</th>';
                            _supplierData += '</tr>';
                            _supplierData += '</thead>';
                            _supplierData += '<tbody>'; 
                            var _total = 0; var _increment = 0;
                                $.each(v.items, function (k1, v2){
                                    _increment++;
                                    _supplierData += '<tr>';
                                    _supplierData += '<td style="width:5%">' + _increment + '</td>';
                                    _supplierData += '<td><code class="text-primary">' + (v2.name ? v2.name : '') + '</code></td>';
                                    _supplierData += '<td>' + (v2.model ? v2.model : '') + '</td>';
                                    _supplierData += '<td class="text-center">' + (v2.quantity ? v2.quantity : '') + '</td>';
                                    _supplierData += '<td class="text-center">' + (v2.uom ? v2.uom : '') + '</td>';
                                    _supplierData += '<td class="text-center">' + $.bac_resolution.price_separator(parseFloat(v2.unit_cost).toFixed(2)) + '</td>';
                                    _supplierData += '<td class="text-end text-danger">' + $.bac_resolution.price_separator(parseFloat(v2.total_cost).toFixed(2)) + '</td>';
                                    _supplierData += '</tr>';
                                    _total += parseFloat(v2.total_cost);
                                });
                            _supplierData += '</tbody>';
                            _supplierData += '<tfoot>';
                            _supplierData += '<tr>';
                            _supplierData += '<td class="text-end" colspan="6"><strong>TOTAL AMOUNT</strong></td>';
                            _supplierData += '<td class="text-end text-danger" colspan="1"><strong>' + '₱' + $.bac_resolution.price_separator(parseFloat(_total).toFixed(2)) + '</strong></td>';
                            _supplierData += '</tr>';
                            _supplierData += '</tfoot>';
                            _supplierData += '</tr>';
                            _supplierData += '</table>';
                            _supplierData += '</div>';
                            _supplierData += '</div>';
                            _supplierData += '</div>';
                        } 
                    });
                    _supplierContent.append(_supplierData);

                    $.bac_resolution.load_committee_contents();
                    if (_status != 'draft') {
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                    } else {
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Resolution (<span class="variables">' + _code + '</span>)');
                    if (_status != 'draft' && _status != 'processed') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.award-btn').addClass('hidden');
                        _modal.find('button.send-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
                    }
                    if (_status == 'processed') {
                        _modal.find('button.award-btn').addClass('hidden');
                    }
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 

        /*
        | ---------------------------------
        | # when rfq print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .print-btn', function (e) {
            var _self   = $(this);
            var _rfqNo   = $(this).closest('tr').attr('data-row-code');
            var _url      = _baseUrl +'digital-sign?url='+'general-services/bac/resolution/print/' + _rfqNo;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when committee remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#committeeTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _urlz   = _baseUrl + 'general-services/bac/resolution/remove-committee/' +_rfqID + '/' + _id;

            var d1     = $.bac_resolutionForm.fetch_status(_rfqID);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    Swal.fire({
                        html: "Are you sure? <br/>the committee with name <br/>("+ _code +")<br/>will be removed.",
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
                                url: _urlz,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.bac_resolution.load_committee_contents();
                                        }
                                    );
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

        /*
        | ---------------------------------
        | # when rfq print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfq-modal .print-btn', function (e) {
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _rfqNo  = _modal.find('label[for="control_no"].text-danger').text();
            var _url    = _baseUrl +'digital-sign?url='+'general-services/bac/resolution/print/' + _rfqNo;
            window.open(_url, '_blank');
        }); 
    }

    //init bac_resolution
    $.bac_resolution = new bac_resolution, $.bac_resolution.Constructor = bac_resolution

}(window.jQuery),

//initializing bac_resolution
function($) {
    "use strict";
    $.bac_resolution.required_fields();
    $.bac_resolution.init();
}(window.jQuery);