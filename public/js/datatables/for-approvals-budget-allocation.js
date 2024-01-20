!function($) {
    "use strict";

    var for_approval_budget_allocation = function() {
        this.$body = $("body");
    };

    var _requisitionID = 0 , _lineID = 0, _allotmentID = 0; 
    var _table; var _prTable;

    for_approval_budget_allocation.prototype.validate = function($form, $required)
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

    for_approval_budget_allocation.prototype.required_fields = function() {
        
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

    for_approval_budget_allocation.prototype.load_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'for-approvals/budget-allocation/lists'); 
        _table = new DataTable('#budgetAllocationTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/budget-allocation/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.for_approval_budget_allocation.shorten();
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
                $(row).attr('data-row-total-pr', data.total_pr);
                $(row).attr('data-row-total-alob', data.total_alob);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-sequence', data.sequence);
            },
            columns: [
                { data: 'budget_control', orderable: true },
                { data: 'type', orderable: true },
                { data: 'control_no', orderable: true },
                { data: 'department', orderable: true },
                { data: 'particulars', orderable: true },
                { data: 'total', orderable: true },
                { data: 'approved_by' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-center' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center sliced' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    for_approval_budget_allocation.prototype.reload_division = function($department)
    {   
        var $division = $('#allob_division_id2'); $division.find('option').remove(); 

        console.log(_baseUrl + 'for-approvals/budget-allocation/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/budget-allocation/reload-division-via-department/' + $department,
            success: function(response) {
                console.log(response.data);
                $division.append('<option value="">select a division</option>');  
                $.each(response.data, function(i, item) {
                    $division.append('<option value="' + item.id + '"> ' + item.code + ' - ' + item.name + '</option>');  
                }); 
            },
            async: false
        });
        
        // $('.m_selectpicker').selectpicker('refresh');
    },

    for_approval_budget_allocation.prototype.validate_approver = function (_id, _sequence)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/budget-allocation/validate-approver/' + _id + '/' + _sequence);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/budget-allocation/validate-approver/' + _id + '/' + _sequence,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_budget_allocation.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    for_approval_budget_allocation.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    for_approval_budget_allocation.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/budget-allocation/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/budget-allocation/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_budget_allocation.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/budget-allocation/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/budget-allocation/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    // for_approval_budget_allocation.prototype.validate_approver = function (_id)
    // {   
    //     var _status = false;
    //     console.log(_baseUrl + 'for-approvals/budget-allocation/validate-approver/' + _id);
    //     $.ajax({
    //         type: "GET",
    //         url: _baseUrl + 'for-approvals/budget-allocation/validate-approver/' + _id,
    //         success: function(response) {
    //             console.log(response);
    //             _status = response;
    //         },
    //         async: false
    //     });
    //     return _status;
    // },

    for_approval_budget_allocation.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        // if (_department > 0) {
            _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
            console.log(_baseUrl + 'for-approvals/budget-allocation/reload-divisions-employees/' + _department);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/budget-allocation/reload-divisions-employees/' + _department,
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

    for_approval_budget_allocation.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        // if (_employee > 0) {
            _designation.find('option').remove(); 
            console.log(_baseUrl + 'for-approvals/budget-allocation/reload-designation/' + _employee);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/budget-allocation/reload-designation/' + _employee,
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

    for_approval_budget_allocation.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'for-approvals/budget-allocation/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'for-approvals/budget-allocation/reload-items/' + _purchaseType,
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

    for_approval_budget_allocation.prototype.load_line_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#itemRequisitionTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/budget-allocation/item-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.for_approval_budget_allocation.shorten();
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
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
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
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.item);
                $(row).attr('data-row-status', data.status);
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: false, visible: false,  targets: 1},
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-end' },
                {  orderable: false, targets: 9, className: 'text-end' },
            ]
        } );

        return true;
    },

    for_approval_budget_allocation.prototype.load_alob_line_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#allotmentBreakdownTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/budget-allocation/alob-lists/' + _requisitionID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.for_approval_budget_allocation.shorten();
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
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            columns: [
                { data: 'id' },
                { data: 'gl_code' },
                { data: 'gl_desc' },
                { data: 'amount' },
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

    for_approval_budget_allocation.prototype.fetch_total_allotment_amount = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'for-approvals/budget-allocation/fetch-allotment-via-pr/' + _requisitionID + '?column=total_amount'; 
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

    for_approval_budget_allocation.prototype.fetch_total_allotment_amount2 = function()
    {   
        var _total = 0;
        var _url   = _baseUrl + 'for-approvals/budget-allocation/fetch-allotment-via-pr2/' + _allotmentID + '?column=total_amount'; 
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

    for_approval_budget_allocation.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_budget_allocation.prototype.preload_select3 = function()
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

    for_approval_budget_allocation.prototype.perfect_scrollbar = function()
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

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    for_approval_budget_allocation.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_budget_allocation.prototype.load_alob_line_contents2 = function(_keywords = '') 
    {   
        var table = new DataTable('#allotmentBreakdownTable2', {
            ajax: { 
                url : _baseUrl + 'for-approvals/budget-allocation/alob-lists2/' + _allotmentID,
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

    for_approval_budget_allocation.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_budget_allocation.preload_select3();
        $.for_approval_budget_allocation.load_contents();
        $.for_approval_budget_allocation.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#departmental-requisition-modal', function (e) {
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
            $.for_approval_budget_allocation.load_contents();
            _requisitionID = 0;
        });

        /*
        | ---------------------------------
        | # when disapprove modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#disapprove-modal', function (e) {
            var _modal = $(this);
            _modal.find('textarea').val('');
            _modal.find('button').removeClass('hidden').prop('disabled', false);
            _requisitionID = 0;
        });
        
        /*
        | ---------------------------------
        | # when view button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _total  = _self.closest('tr').attr('data-row-total-pr');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#budget-allocation-modal');
            var _dep    = _self.closest('tr').attr('data-row-department');
            var _form   = _modal.find('form[name="requisitionForm"]');
            var _alobForm = _modal.find('form[name="alobForm"]');
            var _alobForm = _modal.find('form[name="alobForm"]');
            var _alobForm2 = _modal.find('form[name="alobForm2"]');
            
            if (_dep > 0) {
                _modal.find('.alob-v1').addClass('hidden');
                _modal.find('.alob-v2').removeClass('hidden');
                _requisitionID = _dep; _allotmentID = _id;
                var _url    = _baseUrl + 'for-approvals/budget-allocation/view/' + _dep;
                _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d5 = $.for_approval_budget_allocation.reload_divisions_employees(_form.find('select[name="division_id"]'), _form.find('select[name="employee_id"]'), _form.find('select[name="designation_id"]'), response.data.department_id);
                        var d2 = $.for_approval_budget_allocation.reload_designation(_form.find('select[name="designation_id"]'), response.data.employee_id);
                        var d3 = $.for_approval_budget_allocation.reload_items(_form.find('select[name="item_id"]'), _form.find('select[name="uom_id"]'), response.data.purchase_type_id);
                        var d4 = $.for_approval_budget_allocation.load_line_contents();
                        var d7 = $.for_approval_budget_allocation.load_alob_line_contents();
                        var d1 = response.data;
                        var d6 = response.alob;
                        var d8 = $.for_approval_budget_allocation.fetch_total_allotment_amount();
                        console.log(d4);
                        $.when( d1, d2, d3, d4, d5, d6, d7, d8 ).done(function ( v1, v2, v3, v4, v5, v6, v7, v8 ) 
                        { 
                            if (_total != '₱0.00') {
                                _form.find('select[name="department_id"]').prop('disabled', true);
                                _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                            }
                            if (_status != 'pending') {
                                _alobForm.find('textarea.required, select.required').prop('disabled', true);
                                _modal.find('button.add-alob-line-btn').addClass('hidden');
                                _modal.find('button.send-btn').addClass('hidden');
                            }
                            _modal.find('.m-form__help').text('');
                            _modal.find('table#itemRequisitionTable th.text-danger').text('₱' + $.for_approval_budget_allocation.price_separator(parseFloat(_total).toFixed(2)));
                            _modal.find('.modal-header h5').html('View Budget Allocation (<span class="variables">' + _code + '</span>)');
                            
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
                                _modal.find('table#allotmentBreakdownTable th.text-danger').text(v8);
                            }
                            _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                            _modal.find('table#allotmentBreakdownTable th.text-danger').text('₱' + $.for_approval_budget_allocation.price_separator(parseFloat(v8).toFixed(2)));
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
                _modal.find('.alob-v2').addClass('hidden');
                _allotmentID = _id;
                var _url    = _baseUrl + 'for-approvals/budget-allocation/views/' + _id;
                _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        var d1 = response.alob;
                        var d2 = $.for_approval_budget_allocation.reload_division(response.alob[0].allob_department_id2);
                        var d3 = $.for_approval_budget_allocation.load_alob_line_contents2();
                        var d4 = $.for_approval_budget_allocation.fetch_total_allotment_amount2();
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
                                _modal.find('table#allotmentBreakdownTable2 th.text-danger').text(v4);
                            } else {
                                _alobForm2.find('#allob_department_id2, #allob_division_id2').prop('disabled', true);
                            }
                            if (_status !== 'pending') {
                                _modal.find('.add-alob-line-btn2').addClass('hidden');
                                _modal.find('.send-btn').addClass('hidden');
                                _modal.find('.print-btn').removeClass('hidden');
                                _alobForm2.find('input.required, select.required, textarea.required').prop('disabled', true);
                            }
                            _modal.find('table#allotmentBreakdownTable2 th.text-danger').text('₱' + $.for_approval_budget_allocation.price_separator(parseFloat(v4).toFixed(2)) );
                        });
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _sequence = _self.closest('tr').attr('data-row-sequence');
            var _modal  = $('#departmental-requisition-modal');
            var _controlNo = _modal.find('#rfq_id'); _controlNo.find('option').remove(); 
            var _url    = _baseUrl + 'for-approvals/budget-allocation/approve/' + _id;

            var d1 = $.for_approval_budget_allocation.fetch_status(_id);
            var d2 = $.for_approval_budget_allocation.validate_approver(_id, _sequence);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 != false) {
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
                                            $.for_approval_budget_allocation.load_contents();
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
        this.$body.on('click', '#budgetAllocationTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _sequence = $(this).closest('tr').attr('data-row-sequence');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_budget_allocation.fetch_status(_id);
            var d2 = $.for_approval_budget_allocation.validate_approver(_id, _sequence);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 != false) {
                    _allotmentID = _id;
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
        | # when show disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetAllocationTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _dep    = $(this).closest('tr').attr('data-row-department');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_budget_allocation.fetch_status(_id);
            var d2 = $.for_approval_budget_allocation.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _allotmentID = _id;
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
        | # when disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.code').text();
            var _form   = _self.closest('form');
            var _url = _form.attr('action') + '/disapprove/' + _allotmentID;
            var _error  = $.for_approval_budget_allocation.validate(_form, 0);

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
                    html: "Are you sure? <br/>the request with <strong>PO No<br/>(" + _code + ")</strong> will be disapproved.",
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
                                        $.for_approval_budget_allocation.load_contents();
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
    }

    //init for_approval_budget_allocation
    $.for_approval_budget_allocation = new for_approval_budget_allocation, $.for_approval_budget_allocation.Constructor = for_approval_budget_allocation

}(window.jQuery),

//initializing for_approval_budget_allocation
function($) {
    "use strict";
    $.for_approval_budget_allocation.required_fields();
    $.for_approval_budget_allocation.init();
}(window.jQuery);