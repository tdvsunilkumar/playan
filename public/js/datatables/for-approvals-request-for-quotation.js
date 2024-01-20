!function($) {
    "use strict";

    var for_approval_rfq = function() {
        this.$body = $("body");
    };

    var _rfqID = 0; var _table; var _prTable; var _projectName = ''; var _supplierStatus = ''; var _supplierID = 0; var _page = 0;

    for_approval_rfq.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $required++;
                        }
                    } else if($(this).val()==""){
                        if(!$(this).is("select")) {
                            $(this).addClass('is-invalid');
                            $required++;
                        } else {
                            $(this).addClass('is-invalid');
                            $required++;                                          
                        }
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },

    for_approval_rfq.prototype.required_fields = function() {
        
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

    for_approval_rfq.prototype.load_contents = function(_page = 0) 
    {   
        console.log(_baseUrl + 'for-approvals/request-for-quotation/lists'); 
        _table = new DataTable('#rfqTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_rfq.shorten();
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
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );        
                $.for_approval_rfq.hideTooltip();
            }, 
            columns: [
                { data: 'id' },
                { data: 'control_no_label' },
                { data: 'project_name' },
                { data: 'agencies' },
                { data: 'approved_by' },
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

    for_approval_rfq.prototype.load_pr_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'for-approvals/request-for-quotation/pr-lists/' + _rfqID); 
        _prTable = new DataTable('#prTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/pr-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.for_approval_rfq.shorten();
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

    for_approval_rfq.prototype.load_supplier_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'for-approvals/request-for-quotation/supplier-lists/' + _rfqID); 
        _prTable = new DataTable('#supplierTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/supplier-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.for_approval_rfq.shorten();
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
                $(row).attr('data-row-supplier', data.supplier_id);
            },
            columns: [
                { data: 'supplier' },
                { data: 'total_canvass' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-end' },
                {  orderable: false, targets: 2, className: 'text-center' },
            ]
        } );

        return true;
    },

    for_approval_rfq.prototype.load_item_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'for-approvals/request-for-quotation/item-lists/' + _rfqID); 
        _prTable = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/item-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.for_approval_rfq.shorten();
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

    for_approval_rfq.prototype.reload_available_control_no = function()
    {   
        var _controlNo = $('#rfq_id'); _controlNo.find('option').remove(); 
        console.log(_baseUrl + 'general-services/purchase-orders/reload-available-control-no/' + _rfqID);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-orders/reload-available-control-no/' + _rfqID,
            success: function(response) {
                console.log(response.data);
                _controlNo.append('<option value="">select a control no</option>');  
                $.each(response.data, function(i, item) {
                    _controlNo.append('<option value="' + item.id + '">' + item.control_no + '</option>');  
                }); 
                // $.general_ledger.preload_select3();
            },
            async: false
        });
        return true;
    },

    for_approval_rfq.prototype.fetchID = function()
    {
        return _rfqID;
    }

    for_approval_rfq.prototype.updateID = function(_id)
    {
        return _rfqID = _id;
    }

    for_approval_rfq.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/request-for-quotation/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/request-for-quotation/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_rfq.prototype.validate_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/request-for-quotation/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/request-for-quotation/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_rfq.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_rfq.prototype.preload_select3 = function()
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

    for_approval_rfq.prototype.perfect_scrollbar = function()
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

    for_approval_rfq.prototype.validate_table = function($table)
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
    for_approval_rfq.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_rfq.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/request-for-quotation/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/request-for-quotation/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    for_approval_rfq.prototype.load_line_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/item-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_rfq.shorten();
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
                { data: 'status_label' }
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
            ]
        } );

        return true;
    },

    for_approval_rfq.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/request-for-quotation/alob-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_rfq.shorten();
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

    for_approval_rfq.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        // if (_department > 0) {
            _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
            console.log(_baseUrl + 'for-approvals/request-for-quotation/reload-divisions-employees/' + _department);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/request-for-quotation/reload-divisions-employees/' + _department,
                success: function(response) {
                    console.log(response.employees);
                    _employee.append('<option value="">select a requestor</option>');  
                    $.each(response.employees, function(i, item) {
                        _employee.append('<option value="' + item.id + '">' + item.fullname + '</option>');  
                    }); 
                    console.log(response.divisions);
                    _division.append('<option value="">select a division</option>');  
                    $.each(response.divisions, function(i, item) {
                        _division.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    for_approval_rfq.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        // if (_employee > 0) {
            _designation.find('option').remove(); 
            console.log(_baseUrl + 'for-approvals/request-for-quotation/reload-designation/' + _employee);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/request-for-quotation/reload-designation/' + _employee,
                success: function(response) {
                    console.log(response.data);
                    _designation.append('<option value="">select a designation</option>');  
                    _designation.append('<option value="' + response.data.id + '">' + response.data.description + '</option>');  
                    _designation.val(response.data.id);
                },
                async: false
            });

            return true;
        // }
    },

    for_approval_rfq.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'for-approvals/request-for-quotation/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/request-for-quotation/reload-items/' + _purchaseType,
                success: function(response) {
                    console.log(response.data);
                    _item.append('<option value="">select an item</option>');  
                    $.each(response.data, function(i, item) {
                        _item.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    for_approval_rfq.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'for-approvals/request-for-quotation/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/request-for-quotation/reload-items/' + _purchaseType,
                success: function(response) {
                    console.log(response.data);
                    _item.append('<option value="">select an item</option>');  
                    $.each(response.data, function(i, item) {
                        _item.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    for_approval_rfq.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'for-approvals/request-for-quotation/fetch-allotment-via-pr/' + _rfqID + '?column=total_amount'; 
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

    for_approval_rfq.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_rfq.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    for_approval_rfq.prototype.edit_supplier = function(_supplier, _url, _modal, _code, _button, _status)
    {   
        var _table = _modal.find('table'); _table.find('tbody').empty();
        var _rows  = ''; _modal.find('h5.modal-title').text('Item Quotation ('+_code+')');
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
                        _rows += '<td class="sliced">' + (row.item_description ? row.item_description : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_quantity ? row.item_quantity : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_uom ? row.item_uom : '') + '</td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid" name="brand" type="text" value="' + row.brand + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="unit_cost" type="text" value="' + row.unit_cost + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="total_cost" type="text" value="' + row.total_cost + '" disabled="disabled"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid" name="remarks" type="text" value="' + row.remarks + '""><span class="m-form__help text-danger"></span></td>';
                        _rows += '</tr>';
                        if (parseFloat(row.total_cost) > 0) { 
                            _totalCost += parseFloat(row.total_cost);
                        }
                    });                    
                    _table.find('tbody').append(_rows);
                    
                    _table.find('tfoot th.text-danger').text($.for_approval_rfq.price_separator(parseFloat(_totalCost).toFixed(2)));
                    if (_status !== 'pending') {
                        _button.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                        _modal.find('button.submit-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
                        _modal.find('input').prop('disabled', true);
                    } else {
                        _button.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    }
                    $.each(response.canvass[0], function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                    });
                    $.for_approval_rfq.shorten();
                    _modal.modal('show'); 
                }
            },
            async: false,
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    for_approval_rfq.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_rfq.preload_select3();
        $.for_approval_rfq.load_contents();
        $.for_approval_rfq.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.for_approval_resolution.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Request For Quotation Approval');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('label[for="control_no"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('label[for="total_budget"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.for_approval_rfq.load_contents();
            $.for_approval_rfq.hideTooltip();
            _rfqID = 0;
        });
        this.$body.on('shown.bs.modal', '#rfq-modal', function (e) {
            $.for_approval_rfq.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when disapprove modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#disapprove-modal', function (e) {
            var _modal = $(this);
            _modal.find('textarea').val('');
            _modal.find('button').prop('disabled', false);
            $.for_approval_rfq.hideTooltip();
            _rfqID = 0;
        });
        this.$body.on('shown.bs.modal', '#disapprove-modal', function (e) {
            $.for_approval_rfq.hideTooltip();
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $('#rfq-modal');
            var _url    = _baseUrl + 'for-approvals/request-for-quotation/edit/' + _id;
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
                        _modal.find('label[for="total_budget"].text-danger').text('₱' + $.for_approval_rfq.price_separator(parseFloat(response.data.total_budget).toFixed(2)));
                    }
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    $.for_approval_rfq.load_pr_contents();
                    $.for_approval_rfq.load_supplier_contents();
                    $.for_approval_rfq.load_item_contents();
                    if(_status == 'draft') {
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    } else {
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('View Request For Quotation (<span class="variables">' + _code + '</span>)');
                    if (_status != 'draft') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.send-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#purchase-order-modal');
            var _controlNo = _modal.find('#rfq_id'); _controlNo.find('option').remove(); 
            var _url    = _baseUrl + 'for-approvals/request-for-quotation/approve/' + _id;

            var d1 = $.for_approval_rfq.fetch_status(_id);
            var d2 = $.for_approval_rfq.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
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
                                            _self.prop('disabled', false);
                                            $.for_approval_rfq.load_contents();
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss,_self.prop('disabled', false) 
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_rfq.fetch_status(_id);
            var d2 = $.for_approval_rfq.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _rfqID = _id;
                    _modal.find('span.code').text(_code);
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
                }
            });
        });

        /*
        | ---------------------------------
        | # when disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.code').text();
            var _form   = _self.closest('form');
            var _url = _form.attr('action') + '/disapprove/' + _rfqID;
            var _error  = $.for_approval_rfq.validate(_form, 0);

            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                Swal.fire({
                    html: "Are you sure? <br/>the request with <strong>Control No<br/>(" + _code + ")</strong> will be disapproved.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, disapprove it!",
                    cancelButtonText: "No, return",
                    customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
                }).then(function (t) {
                    t.value
                        ? 
                        $.ajax({
                            type: 'POST',
                            url: _url,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        _self.html('Submit').prop('disabled', false);
                                        _modal.modal('hide');
                                        $.for_approval_rfq.load_contents();
                                    }
                                );
                            },
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        })
                        : "cancel" === t.dismiss,_self.html('Submit').prop('disabled', false) 
                });
            }
        });

        /*
        | ---------------------------------
        | # when show disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_rfq.fetch_status(_id);
            var d2 = $.for_approval_rfq.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
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
        | # when supplier view button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierTable .view-btn', function (e) {
            var _self     = $(this);
            var _supplier = _self.closest('tr').attr('data-row-supplier');
            var _code     = _self.closest('tr').attr('data-row-code');
            var _status   = _self.closest('tr').attr('data-row-status');
            var _url      = _baseUrl + 'for-approvals/request-for-quotation/edit-supplier/' + _rfqID + '?supplier=' + _supplier;
            var _modal    = $('#canvass-modal');
            
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            setTimeout(function () {
                $.for_approval_rfq.edit_supplier(_supplier, _url, _modal, _code, _self, _status);
            }, 500 + 300 * (Math.random() * 5));
        }); 
    }

    //init for_approval_rfq
    $.for_approval_rfq = new for_approval_rfq, $.for_approval_rfq.Constructor = for_approval_rfq

}(window.jQuery),

//initializing for_approval_rfq
function($) {
    "use strict";
    $.for_approval_rfq.required_fields();
    $.for_approval_rfq.init();
}(window.jQuery);