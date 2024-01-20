!function($) {
    "use strict";

    var voucher = function() {
        this.$body = $("body");
    };

    var _payableID = 0; var _deductionID = 0; var _paymentID = 0; var _payables = []; var _payments = [], _deductions = [];
    var _voucherID = 0; var _table; var _payable, _payment;  var _payablePage = 0, _paymentPage = 0; var _payableStatus = 'all'; var _paymentStatus = 'all';
    var _payableCodex = ''; var _deductionCodex = ''; var _paymentCodex = '';
    var _voucherStatus = 'all';

    var _deduction, _deductionPage = 0, _deductionStatus = 'all';
    var _payableShow = 5; var _deductionShow = 5; var _paymentShow = 5;

    const parts = window.location.href.split('/');
    var voucherSegment = (parts.slice(-2)[0] == 'edit' || parts.slice(-2)[0] == 'view') ? parts.slice(-3)[0] : (parts.slice(-1)[0] == 'add') ? parts.slice(-2)[0] : parts.slice(-1)[0];
   
    voucher.prototype.required_fields = function() {

        $('label:not(.custom-file-label) span.ms-1.text-danger').remove(); 
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], input[type='file'], select, textarea");
                // if ($input.val() == '') {
                    $(this).find('label:not(.custom-file-label)').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                // }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], input[type='file'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    voucher.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#voucherTable', {
            ajax: { 
                url : _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/lists?status=' + _voucherStatus,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.voucher.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.voucher.hideTooltip();
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
                $(row).attr('data-row-code', data.voucher);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="voucher_status" aria-controls="voucher_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="completed">Completed</option><option value="all">All</option></select></label>');           
                $('select[name="voucher_status"]').val(_voucherStatus);
            },      
            columns:             
                (voucherSegment == 'payables') ?             
                [
                    { data: 'voucher_label' },
                    { data: 'payee' },
                    // { data: 'remarks' },
                    { data: 'total_payables' },
                    { data: 'total_ewt' },
                    { data: 'total_evat' },
                    { data: 'total_disbursement' },
                    { data: 'modified' },
                    { data: 'status_label' },
                    { data: 'actions' }
                ]
                :
                [
                    { data: 'voucher_label' },
                    { data: 'payee' },
                    // { data: 'remarks' },
                    { data: 'total_payables' },
                    { data: 'total_deduction' },
                    // { data: 'total_evat' },
                    { data: 'total_disbursement' },
                    { data: 'modified' },
                    { data: 'status_label' },
                    { data: 'actions' }
                ]
            ,
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: 
            (voucherSegment == 'payables') ?  
            [
                {  orderable: true, targets: 0, className: 'text-start w-25' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                // {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-end' },
                {  orderable: true, targets: 3, className: 'text-end' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
            :
            [
                {  orderable: true, targets: 0, className: 'text-start w-25' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                // {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-end' },
                {  orderable: true, targets: 3, className: 'text-end' },
                // {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    voucher.prototype.load_payable_contents = function(_payablePage = 0) 
    {   
        _payable = new DataTable('#payablesTable', {
            ajax: { 
                url : _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables-lists/' + _voucherID + '?status=' + _payableStatus,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.voucher.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.voucher.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_account);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                _payable.page.len( _payableShow ).draw();
                this.api().page(_payablePage).draw( 'page' );   
                $("#payable-card div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="ap_status" aria-controls="ap_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="all">All</option><option value="draft">Draft</option><option value="for approval">Pending</option><option value="posted">Posted</option><option value="cancelled">Cancelled</option></select></label>');           
                $('#payable-card select[name="ap_status"]').val(_payableStatus);
                if(_payable.rows().data().length > 0 || _payment.rows().data().length > 0) {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', true);
                } else {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', false);
                }
                if (_payable.rows().data().length > 0) {
                    $('#voucher-card select[name="payee_id"]').prop('disabled', true);
                } else {
                    $('#voucher-card select[name="payee_id"]').prop('disabled', false);
                }
            },      
            columns: [
                { data: 'checkbox' },
                { data: 'gl_account_label' },
                { data: 'total' },
                // { data: 'credit' },
                { data: 'responsibility_center' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-center w-25' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-center' },
                {  orderable: true, targets: 3, className: 'text-center' },
                {  orderable: true, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                // {  orderable: true, targets: 6, className: 'text-end' },
            ]
        } );

        _payable.on( 'draw', function () {
            $('#payablesTable th input[type="checkbox"]').prop('checked', false);
            for (var i = 0; i < _payables.length; i++) {
                $('#payablesTable').find('tr[data-row-id="' +  _payables[i] + '"][data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
            }
        } );

        return true;
    },

    voucher.prototype.load_deduction_contents = function(_deductionPage = 0) 
    {   
        _deduction = new DataTable('#deductionTable', {
            ajax: { 
                url : _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions-lists/' + _voucherID + '?status=' + _deductionStatus,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.voucher.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.voucher.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_account);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                _deduction.page.len( _deductionShow ).draw();
                this.api().page(_deductionPage).draw( 'page' );   
                $("#deduction-card div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="deduction_status" aria-controls="deduction_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="all">All</option><option value="draft">Draft</option><option value="for approval">Pending</option><option value="posted">Posted</option><option value="cancelled">Cancelled</option></select></label>');           
                $('#deduction-card select[name="deduction_status"]').val(_deductionStatus);
            },      
            columns: [
                { data: 'checkbox' },
                { data: 'gl_account_label' },
                { data: 'total' },
                // { data: 'credit' },
                { data: 'responsibility_center' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-center w-25' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-center' },
                {  orderable: true, targets: 3, className: 'text-center' },
                {  orderable: true, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                // {  orderable: true, targets: 6, className: 'text-end' },
            ]
        } );

        _deduction.on( 'draw', function () {
            $('#deductionTable th input[type="checkbox"]').prop('checked', false);
            for (var i = 0; i < _deductions.length; i++) {
                $('#deductionTable').find('tr[data-row-id="' +  _deductions[i] + '"][data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
            }
        } );

        return true;
    },

    voucher.prototype.load_payment_contents = function(_paymentPage = 0) 
    {   
        _payment = new DataTable('#paymentsTable', {
            ajax: { 
                url : _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments-lists/' + _voucherID + '?status=' + _paymentStatus,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.voucher.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.voucher.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_account);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-type', data.type);
            },
            dom: 'lf<"toolbar-3 float-end d-flex flex-row">rtip',
            initComplete: function(){
                _payment.page.len( _paymentShow ).draw();
                this.api().page(_paymentPage).draw( 'page' );   
                $("#payment-card div.toolbar-3").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="pay_status" aria-controls="pay_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="all">All</option><option value="draft">Draft</option><option value="for approval">Pending</option><option value="posted">Posted</option><option value="cancelled">Cancelled</option></label>');           
                $('#payment-card select[name="pay_status"]').val(_paymentStatus);
                if(_payment.rows().data().length > 0 || _payable.rows().data().length > 0) {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', true);
                } else {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', false);
                }
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-pr">ADD LINE</button>');   
            },      
            columns: [
                { data: 'checkbox' },
                { data: 'gl_account_label' },
                { data: 'disburse' },
                { data: 'type' },
                { data: 'cheque_details' },
                { data: 'bank_name' },
                { data: 'account_details' },
                { data: 'payment_date' },
                { data: 'total' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-center' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: false, targets: 2, className: 'text-center' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: true, targets: 7, className: 'text-end' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        _payment.on( 'draw', function () {
            $('#paymentsTable th input[type="checkbox"]').prop('checked', false);
            for (var i = 0; i < _payments.length; i++) {
                $('#paymentsTable').find('tr[data-row-id="' +  _payments[i] + '"][data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
            }
        } );

        return true;
    },

    voucher.prototype.fetchPayableID = function()
    {
        return _payableID;
    }

    voucher.prototype.updatePayableID = function(_id)
    {
        return _payableID = _id;
    }

    voucher.prototype.fetchID = function()
    {
        return _voucherID;
    }

    voucher.prototype.updateID = function(_id)
    {
        return _voucherID = _id;
    }

    voucher.prototype.fetchPaymentID = function()
    {
        return _paymentID;
    }

    voucher.prototype.updatePaymentID = function(_id)
    {
        return _paymentID = _id;
    }

    voucher.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    voucher.prototype.validate_table = function($table)
    {   
        $('.pager').remove();
        $table.each(function() {
            var currentPage = 0;
            var numPerPage = 8;
            var $table = $(this);
            $table.bind('repaginate', function() {
                $table.find('tbody tr').addClass("hidden").slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).removeClass('hidden');
            });
            $table.trigger('repaginate');
            if ($('#keyword2').val() != '') {
                var numRows = $table.find('tbody tr:not(.hidden)').length;
            } else {
                var numRows = $table.find('tbody tr').length;
            }
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

    voucher.prototype.view_available_payables = function(_id, _button)
    {   
        var _table = $('#available-payable-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/view-available-payables/' + _id + '?fund_code=' + $('#fund_code_id').val() + '&payee=' + $('#payee_id').val();
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '">';
                        _rows += '<td><div class="form-check"><input class="form-check-input" type="checkbox" value="' + row.id + '"></div></td>';
                        _rows += '<td class="text-center">' + (row.trans_no ? '<strong class="text-primary">' + row.trans_type + '</strong><br/>' + row.trans_no: '') + '</td>';
                        _rows += '<td class="sliced">' + (row.gl_account ? row.gl_account : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.vat ? row.vat: '') + '</td>';
                        _rows += '<td class="text-center">' + (row.ewt ? row.ewt: '') + '</td>';
                        _rows += '<td class="text-center">' + (row.evat ? row.evat : '') + '</td>';
                        _rows += '<td class="sliced text-start">' + (row.items ? row.items : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.quantity ? row.quantity : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.uom ? row.uom : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.amount ? row.amount : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.total ? row.total : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.due_date ? row.due_date : '') + '</td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('<i class="ti-plus align-middle me-2"></i> ADD');
                    _table.find('tbody').append(_rows);
                    var d1 = $.voucher.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        $.voucher.shorten();
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('<i class="ti-plus align-middle me-2"></i> ADD');
            }
        });
    },

    voucher.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.getVoucher = function ()
    {   
        _voucherID = 0;
        var _payablesCard = $('#payable-card'), _paymentsCard = $('#payment-card'), _deductionsCard = $('#deduction-card');
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/get-voucher');
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/get-voucher',
            success: function(response) {
                console.log(response);
                _voucherID = response.voucher;
                if (_voucherID > 0) {
                    $('select[name="payee_id"]').val(response.data.payee_id).trigger('change.select3'); 
                    $('select[name="fund_code_id"]').val(response.data.fund_code_id).trigger('change.select3'); 
                    $('input[name="voucher_no"]').val(response.data.voucher_no); 
                    $('textarea[name="remarks"]').val(response.data.remarks);
                    if (parseFloat(response.data.is_replenish) > 0) {
                        $('input[name="is_replenish"]').prop('checked', true);
                    }
                    var _payable = $.voucher.money_format(response.data.total_payables);
                    var _ewt = $.voucher.money_format(response.data.total_ewt);
                    var _evat = $.voucher.money_format(response.data.total_evat);
                    var _gross = $.voucher.money_format(response.payroll.gross_salary);
                    var _net = $.voucher.money_format(response.payroll.net_salary);
                    var _deduct = $.voucher.money_format(response.payroll.deductions);
                    var _salaries = $.voucher.money_format(response.payroll.salaries);
                    var _pagibig = $.voucher.money_format(response.payroll.pagibig);
                    var _philhealth = $.voucher.money_format(response.payroll.philhealth);
                    var _bir = $.voucher.money_format(response.payroll.bir);
                    var _gsis = $.voucher.money_format(response.payroll.gsis);
                    var _pagibig_pay = $.voucher.money_format(response.payroll.pagibig_pay);
                    var _philhealth_pay = $.voucher.money_format(response.payroll.philhealth_pay);
                    var _bir_pay = $.voucher.money_format(response.payroll.bir_pay);
                    var _gsis_pay = $.voucher.money_format(response.payroll.gsis_pay);
                    _payablesCard.find('th.total-payables').text($.voucher.price_separator(_payable));
                    _payablesCard.find('th.total-ewt').text($.voucher.price_separator(_ewt));
                    _payablesCard.find('th.total-evat').text($.voucher.price_separator(_evat));
                    _payablesCard.find('th.total-gross').text($.voucher.price_separator(_gross));
                    _payablesCard.find('th.total-net').text($.voucher.price_separator(_net));
                    _payablesCard.find('th.total-deduct').text($.voucher.price_separator(_deduct));
                    _payablesCard.find('th.total-salaries').text($.voucher.price_separator(_salaries));

                    _deductionsCard.find('th.total-pagibig').text($.voucher.price_separator(_pagibig));
                    _deductionsCard.find('th.total-philhealth').text($.voucher.price_separator(_philhealth));
                    _deductionsCard.find('th.total-bir').text($.voucher.price_separator(_bir));
                    _deductionsCard.find('th.total-gsis').text($.voucher.price_separator(_gsis));

                    _deductionsCard.find('th.pay-pagibig').text($.voucher.price_separator(_pagibig_pay));
                    _deductionsCard.find('th.pay-philhealth').text($.voucher.price_separator(_philhealth_pay));
                    _deductionsCard.find('th.pay-bir').text($.voucher.price_separator(_bir_pay));
                    _deductionsCard.find('th.pay-gsis').text($.voucher.price_separator(_gsis_pay));

                    var _totalAmt = parseFloat(_payable) - parseFloat( parseFloat(_ewt) + parseFloat(_evat) );
                    _payablesCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(_totalAmt)));
                    _paymentsCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(response.data.total_disbursement)));
                    _payablesCard.find('th.total-deductions').text($.voucher.price_separator($.voucher.money_format(response.payroll.total_deductions)));
                    _deductionsCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(response.payroll.total_deductions)));
                }
                if (response.data.status == 'completed') {
                    $('#voucher-card input, #voucher-card select, #voucher-card textarea').prop('disabled', true);
                    $('#payable-card #send-payable-btn, #payable-card #del-payable-btn, #payable-card #add-payable-btn').addClass('hidden');
                    $('#payment-card #send-payment-btn, #payment-card #del-payment-btn, #payment-card #add-payment-btn').addClass('hidden');
                } 
            },
            async: false
        });
        return _voucherID;
    },

    voucher.prototype.preload_select3 = function()
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
                }).on("select3-open", function() { 
                    $(this).select2('positionDropdown', true);
                });
            });
        }
    },

    voucher.prototype.perfect_scrollbar = function()
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

    voucher.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    voucher.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    voucher.prototype.getDate = function()
    {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        
        if(dd<10) {
            dd = '0'+dd
        } 
        
        if(mm<10) {
            mm = '0'+mm
        } 
        
        today = yyyy + '-' + mm + '-' + dd;
        return today;
    }

    voucher.prototype.getLastSegment = function()
    {
        const parts = window.location.href.split('/');
        if (parts.slice(-2)[0] == 'journal-entries') {
            return parts.slice(-1)[0];
        }
        return parts.slice(-2)[0];
    }

    voucher.prototype.fetch_deduction_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.validate_deductions_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.fetch_deduction_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    voucher.prototype.fetch_payable_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.validate_payables_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.fetch_payment_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },
    
    voucher.prototype.validate_payments_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    voucher.prototype.fetch_payable_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    voucher.prototype.fetch_payment_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    voucher.prototype.validate_voucher = function (_id)
    {   
        var _validate = 2;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/validate-voucher/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/validate-voucher/' + _id,
            success: function(response) {
                console.log(response);
                _validate = response.validate;
            },
            async: false
        });
        return _validate;
    },

    voucher.prototype.fetch_disbursement_type = function (_id)
    {   
        var _type = 0;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-disbursement-type/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-disbursement-type/' + _id,
            success: function(response) {
                console.log(response);
                _type = response.type;
            },
            async: false
        });
        return _type;
    },

    voucher.prototype.fetch_disbursement_reference = function (_id)
    {
        var _reference = '';
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-disbursement-reference/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-disbursement-reference/' + _id,
            success: function(response) {
                console.log(response);
                _reference = response.reference;
            },
            async: false
        });
        return _reference;
    },

    voucher.prototype.money_format = function(_money)
    {   
        return parseFloat(Math.floor((_money * 100))/100).toFixed(2);
    },

    voucher.prototype.fetch_voucher_print = function (_id, _type)
    {
        var _voucherPrint = 0;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-voucher-print?id=' + _id + '&type=' + _type);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-voucher-print?id=' + _id + '&type=' + _type,
            success: function(response) {
                console.log(response);
                _voucherPrint = response.data;
            },
            async: false
        });
        return _voucherPrint;
    },  
    
    voucher.prototype.fetch_document_status = function (_id, _type)
    {
        var _voucherPrint = 0;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-document-status?id=' + _id + '&type=' + _type);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-document-status?id=' + _id + '&type=' + _type,
            success: function(response) {
                console.log(response);
                _voucherPrint = response.data;
            },
            async: false
        });
        return _voucherPrint;
    },  

    voucher.prototype.fetch_document_remarks = function (_id, _type)
    {
        var _voucherPrint = 0;
        console.log(_baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-document-remarks?id=' + _id + '&type=' + _type);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/fetch-document-remarks?id=' + _id + '&type=' + _type,
            success: function(response) {
                console.log(response);
                _voucherPrint = response.data;
            },
            async: false
        });
        return _voucherPrint;
    },  

    voucher.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        if($.voucher.getLastSegment() == 'add' || $.voucher.getLastSegment() == 'edit' || $.voucher.getLastSegment() == 'view') {
            $.voucher.getVoucher();
        }
        $.voucher.preload_select3();
        $.voucher.load_contents();
        $.voucher.load_payable_contents();
        $.voucher.load_deduction_contents();
        $.voucher.load_payment_contents();
        $.voucher.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.voucher.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="voucher_status"]', function (e) {
            var _self = $(this);
            _voucherStatus = _self.val();
            $.voucher.load_contents();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#account-payable-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Account Payable');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            $.voucher.preload_select3();
            $.voucher.hideTooltip();
            $.voucher.load_payable_contents(_payable.page());
            _payableID = 0;
        });
        this.$body.on('shown.bs.modal', '#account-payable-modal', function (e) {
            $.voucher.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when add payable modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-payable-modal', function (e) {
            var _modal = $(this);
            $.voucher.load_payable_contents(_payable.page());
            _modal.find('input[type="text"], textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('input[type="checkbox"]').prop("checked", false);
        });

        /*
        | ---------------------------------
        | # when add payment modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-payment-modal', function (e) {
            var _modal = $(this);
            $.voucher.load_payment_contents(_payment.page());
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[name="cheque_date"], input[name="cheque_no"]').removeClass('required').closest('.form-group').removeClass('required');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('input[type="checkbox"]').prop("checked", false);
            _modal.find('.custom-file label').text('').removeClass('selected');
            $.voucher.required_fields();
            _paymentID = 0;
        });
        this.$body.on('hidden.bs.modal', '#add-bank-payment-modal', function (e) {
            var _modal = $(this);
            $.voucher.load_payment_contents(_payment.page());
            $.voucher.required_fields();
            _paymentID = 0;
        });
        
        /*
        | ---------------------------------
        | # when datatable length onChange
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="payablesTable_length"]', function (e) {
            var _self = $(this);
            _payableShow = _self.val();
        });
        this.$body.on('change', 'select[name="deductionTable_length"]', function (e) {
            var _self = $(this);
            _deductionShow = _self.val();
        });
        this.$body.on('change', 'select[name="paymentsTable_length"]', function (e) {
            var _self = $(this);
            _paymentShow = _self.val();
        });
        
        /*
        | ---------------------------------
        | # when payable status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="ap_status"]', function (e) {
            var _self = $(this);
            _payableStatus = _self.val();
            $.voucher.load_payable_contents();
        });

        /*
        | ---------------------------------
        | # when payment status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="pay_status"]', function (e) {
            var _self = $(this);
            _paymentStatus = _self.val();
            $.voucher.load_payment_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#voucherTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _status = _self.closest('tr').attr('data-row-status');
            var _url    = (_status == 'completed') ? _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/view/' + _id : _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/edit/' + _id;
            window.location.href =_url;
        }); 

        /*
        | ---------------------------------
        | # when edit payables button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#account-payable-modal');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/edit/' + _id;
            console.log(_url);
            _payableID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    if (response.data.trans_type == 'Purchase Order') {
                        _modal.find('input.require, select.require, textarea.require').prop('disabled', true);
                    } else {
                        _modal.find('input.require, select.required, textarea.require').prop('disabled', false);
                        _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
                    }
                    if (response.data.status != 'draft') {
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View Collection (<span class="variables">' + _id + '</span>)');
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').prop('disabled', true).addClass('hidden');
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Collection (<span class="variables">' + _id + '</span>)');
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .remove-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _status = _self.closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/remove/' + _id;
            var _payablesCard = $('#payable-card');
            var _label = (voucherSegment == 'payables') ? 'payables' : 'collections';

            if (_status == 'draft') {
                console.log(_url);
                Swal.fire({
                    html: "Are you sure? <br/>the " + _label + " with gl account<br/>(" + _code + ")<br/>will be removed.",
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
                                        _payablesCard.find('th.total-payables').text($.voucher.price_separator($.voucher.money_format(response.data.total_payables)));
                                        _payablesCard.find('th.total-ewt').text($.voucher.price_separator($.voucher.money_format(response.data.total_ewt)));
                                        _payablesCard.find('th.total-evat').text($.voucher.price_separator($.voucher.money_format(response.data.total_evat)));
                                        _payablesCard.find('th.total-gross').text($.voucher.price_separator($.voucher.money_format(response.payroll.gross_salary)));
                                        _payablesCard.find('th.total-net').text($.voucher.price_separator($.voucher.money_format(response.payroll.net_salary)));
                                        _payablesCard.find('th.total-deduct').text($.voucher.price_separator($.voucher.money_format(response.payroll.total_deductions)));
                                        _payablesCard.find('th.total-salaries').text($.voucher.price_separator($.voucher.money_format(response.payroll.salaries)));
                                        _payablesCard.find('th.total-pagibig').text($.voucher.price_separator($.voucher.money_format(response.payroll.pagibig)));
                                        _payablesCard.find('th.total-philhealth').text($.voucher.price_separator($.voucher.money_format(response.payroll.philhealth)));
                                        _payablesCard.find('th.total-bir').text($.voucher.price_separator($.voucher.money_format(response.payroll.bir)));
                                        _payablesCard.find('th.total-gsis').text($.voucher.price_separator($.voucher.money_format(response.payroll.gsis)));
                                        var _totalAmt =  $.voucher.money_format(response.data.total_payables) - parseFloat(  $.voucher.money_format(response.data.total_ewt) +  $.voucher.money_format(response.data.total_evat) );
                                        _payablesCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(_totalAmt)));
                                        $.voucher.load_payable_contents(_payable.page());
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

        /*
        | ---------------------------------
        | # when add payable button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-payable-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id   = _voucherID
            var _fund = $('#voucher-card select[name="fund_code_id"]');

            if (_fund.val() > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.voucher.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        setTimeout(function () {
                            $.voucher.view_available_payables(_id, _self);
                        }, 500 + 300 * (Math.random() * 5));
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
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select a fund code first.",
                    icon: "error",
                    type: "danger",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                _fund.addClass('is-invalid').closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                window.onkeydown = null;
                window.onfocus = null;    
            }
        });   

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-payment-btn', function (e) {
            var _modal = $('#add-payment-modal');
            var _fund = $('#voucher-card select[name="fund_code_id"]');

            if (_fund.val() > 0) {
                _modal.find('input[name="payment_date"]').val($.voucher.getDate());
                _modal.modal('show');
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select a fund code first.",
                    icon: "error",
                    type: "danger",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                _fund.addClass('is-invalid').closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                window.onkeydown = null;
                window.onfocus = null;    
            }
        });

        /*
        | ---------------------------------
        | # when edit payments button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#add-payment-modal');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/edit/' + _id;
            console.log(_url);
            _paymentID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input:not([type="file"])[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        if (k == 'attachment') {
                            _modal.find('.custom-file label').text('C:\\fakepath\\' + v).addClass('selected');
                        }
                    });
                    if (response.data.payment_type_id == 2) {
                        _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', false).closest('.form-group').addClass('required');
                    } else {
                        _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', true).closest('.form-group').removeClass('required');
                    }
                    $.voucher.required_fields();
                    if (response.data.status == 'draft') {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Payment (<span class="variables">' + _id + '</span>)');
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View Payment (<span class="variables">' + _id + '</span>)');
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').prop('disabled', true).addClass('hidden');
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
        | # when deposit payments button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .deposit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _type   = _self.closest('tr').attr('data-row-type');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#add-bank-payment-modal');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/edit/' + _id;
            console.log(_url);
            _paymentID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            if (_type == 'Cash' && _status == 'posted') {
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        $.each(response.data, function (k, v) {
                            _modal.find('input:not([type="file"])[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            if (k == 'attachment') {
                                _modal.find('.custom-file label').text('C:\\fakepath\\' + v).addClass('selected');
                            }
                        });
                        _modal.find('select[name="payment_type_id"]').val(5).trigger('change.select3'); 
                        if (response.data.payment_type_id == 2) {
                            _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', false).closest('.form-group').addClass('required');
                        } else {
                            _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', true).closest('.form-group').removeClass('required');
                        }
                        $.voucher.required_fields();
                        if (response.data.status == 'posted') {
                            _self.prop('disabled', false).attr('title', 'edit this').html('<i class="la la-share-square-o text-white"></i>');
                            _modal.find('.m-form__help').text('');
                            _modal.find('.modal-header h5').html('Edit Payment (<span class="variables">' + _id + '</span>)');
                        } else {
                            _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-search text-white"></i>');
                            _modal.find('.m-form__help').text('');
                            _modal.find('.modal-header h5').html('View Payment (<span class="variables">' + _id + '</span>)');
                            _modal.find('input, select, textarea').prop('disabled', true);
                            _modal.find('button.submit-btn').prop('disabled', true).addClass('hidden');
                        }
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .remove-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _status = _self.closest('tr').attr('data-row-status');
            var _type   = _self.closest('tr').attr('data-row-type');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/remove/' + _id;
            var _paymentsCard = $('#payment-card');
            var _label = (voucherSegment == 'payables') ? 'payment' : 'deposit';

            var d1 = $.voucher.fetch_disbursement_type(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if ((_type != 'Procurement') || (v1 > 1)) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>Only Procurement can be cancelled.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;  
                } else if (_status == 'draft') {
                    console.log(_url);
                    Swal.fire({
                        html: "Are you sure? <br/>the " + _label + " with gl account<br/>(" + _code + ")<br/>will be removed.",
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
                                            _paymentsCard.find('th.total-amount').text($.voucher.price_separator( $.voucher.money_format(response.data.total_disbursement)));
                                            $.voucher.load_payment_contents(_payment.page());
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

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.print-voucher-btn', function (e) {
            var _self = $(this);
            var _type = _self.attr('data');
            var _voucher = $('#voucher-card input[name="voucher_no"]').val();
            var _url  = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/print/' + _voucher + '?type=' + _type; 
            var _id   = $.voucher.fetchID();
            var d1    = $.voucher.fetch_voucher_print(_id, _type);
            var d2    = $.voucher.fetch_document_status(_id, _type);
            var d3    = $.voucher.fetch_document_remarks(_id, _type);
            var _modal = $('#add-date-modal');
            var _modal2 = $('#disapprove-document-modal');
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            {  
                if (v2 == 1) {
                    if (v1.length > 0) {
                        Swal.fire({
                            html: "The " + _type + " voucher is dated at <strong>" + v1 + "</strong>,<br/>do you like to keep this date?",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, keep it!",
                            cancelButtonText: "No, select other date",
                            customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {    
                            if (t.value) {
                                window.open(_url, '_blank');
                            } else if (t.dismiss == "cancel") {
                                _modal.find('.variables').text(_type), 
                                _modal.find('span.text').text("Manage " + _type.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase(); }) + " Voucher's Date"), 
                                _modal.modal('show');
                            }                                
                        });
                    } else {
                        _modal.find('.variables').text(_type);
                        _modal.find('span.text').text("Manage " + _type.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase(); }) + " Voucher's Date");
                        _modal.modal('show');
                    }
                } else if (v2 == 2) {
                    window.open(_url, '_blank');
                } else if (v2 == 3) {
                    Swal.fire({
                        html: "Oops your voucher document is disapproved",
                        icon: "error",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "View the reason why?",
                        cancelButtonText: "Okay, select other date",
                        customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        if (t.value) { 
                            if (v2 == 3) {
                                _modal2.find('span.code').text(_voucher);
                                _modal2.find('textarea').val(v3).prop('disabled', true);
                                _modal2.find('button.submit-disapprove-btn').addClass('hidden');
                                _modal2.modal('show');
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
                        } else if (t.dismiss == "cancel") {
                            _modal.find('.variables').text(_type), 
                            _modal.find('span.text').text("Manage " + _type.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase(); }) + " Voucher's Date"), 
                            _modal.modal('show');
                        }                             
                    });
                } else {
                    _modal.find('.variables').text(_type);
                    _modal.find('span.text').text("Manage " + _type.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase(); }) + " Voucher's Date");
                    _modal.modal('show');
                }
            });
        });
        this.$body.on('click', '.preview-voucher-btn', function (e) {
            var _self = $(this);
            var _type = _self.attr('data');
            var _voucher = $('#voucher-card input[name="voucher_no"]').val();
            var _url  = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/preview/' + _voucher + '?type=' + _type; 
            window.open(_url, '_blank');
        });

        /*
        | ---------------------------------
        | # when payables checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _payables.length; i++) {
                            if (_payables[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _payables.push(checkbox.val());
                        }
                    } 
                });
                console.log(_payables);
            } else {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _payables.length; i++) {
                        if (_payables[i] == checkbox.val()) {
                            _payables.splice(i, 1);
                        }
                    }
                });
                console.log(_payables);
            }
            if (_payables.length > 0) {
                if (voucherSegment == 'payables') { 
                    $('#del-payable-btn, #send-payable-btn').prop('disabled', false);
                } else {
                    $('#send-payable-btn').prop('disabled', false);
                }
            } else {
                if (voucherSegment == 'payables') { 
                    $('#del-payable-btn, #send-payable-btn').prop('disabled', true);
                } else {
                    $('#send-payable-btn').prop('disabled', true);
                }
            }
        });
        /*
        | ---------------------------------
        | # when payables checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _payables.push(_self.val());
            } else {
                for (var i = 0; i < _payables.length; i++) {
                    if (_payables[i] == _self.val()) {
                        _payables.splice(i, 1);
                    }
                }
            }
            if (_payables.length > 0) {
                if (voucherSegment == 'payables') { 
                    $('#del-payable-btn, #send-payable-btn').prop('disabled', false);
                } else {
                    $('#send-payable-btn').prop('disabled', false);
                }
            } else {
                if (voucherSegment == 'payables') { 
                    $('#del-payable-btn, #send-payable-btn').prop('disabled', true);
                } else {
                    $('#send-payable-btn').prop('disabled', true);
                }
            }
            console.log(_payables);
        });

        /*
        | ---------------------------------
        | # when payments checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _payments.length; i++) {
                            if (_payments[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _payments.push(checkbox.val());
                        }
                    } 
                });
                console.log(_payments);
            } else {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _payments.length; i++) {
                        if (_payments[i] == checkbox.val()) {
                            _payments.splice(i, 1);
                        }
                    }
                });
                console.log(_payments);
            }
            if (_payments.length > 0) {
                $('#del-payment-btn, #send-payment-btn').prop('disabled', false);
            } else {
                $('#del-payment-btn, #send-payment-btn').prop('disabled', true);
            }
        });
        /*
        | ---------------------------------
        | # when payments checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _payments.push(_self.val());
            } else {
                for (var i = 0; i < _payments.length; i++) {
                    if (_payments[i] == _self.val()) {
                        _payments.splice(i, 1);
                    }
                }
            }
            if (_payments.length > 0) {
                $('#del-payment-btn, #send-payment-btn').prop('disabled', false);
            } else {
                $('#del-payment-btn, #send-payment-btn').prop('disabled', true);
            }
            console.log(_payments);
        });

        /*
        | ---------------------------------
        | # when batch remove payable is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#del-payable-btn', function (e) {
            var _self   = $(this);
            var _code = $('input[name="voucher_no"]').val();
            var _id   = $.voucher.fetchID();
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/remove-all/' + _id;
            var _payablesCard = $('#payable-card');
            var _label = (voucherSegment == 'payables') ? 'payables' : 'collections';

            if (_payables.length > 0) {
                var d1    = $.voucherForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {
                        console.log(_url);
                        _self.prop('disabled', true).html('WAIT.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the (" + _payables.length + ") selected " + _label + " on voucher<br/>(" + _code + ")<br/>will be removed.",
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
                                    data: {'payables' : _payables},
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _payables = [];
                                            _self.prop('disabled', true).html('WAIT.....');
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                            .then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    _payablesCard.find('th.total-payables').text($.voucher.price_separator($.voucher.money_format(response.data.total_payables)));
                                                    _payablesCard.find('th.total-ewt').text($.voucher.price_separator($.voucher.money_format(response.data.total_ewt)));
                                                    _payablesCard.find('th.total-evat').text($.voucher.price_separator($.voucher.money_format(response.data.total_evat)));
                                                    _payablesCard.find('th.total-gross').text($.voucher.price_separator($.voucher.money_format(response.payroll.gross_salary)));
                                                    _payablesCard.find('th.total-net').text($.voucher.price_separator($.voucher.money_format(response.payroll.net_salary)));
                                                    _payablesCard.find('th.total-deduct').text($.voucher.price_separator($.voucher.money_format(response.payroll.total_deductions)));
                                                    _payablesCard.find('th.total-salaries').text($.voucher.price_separator($.voucher.money_format(response.payroll.salaries)));
                                                    _payablesCard.find('th.total-pagibig').text($.voucher.price_separator($.voucher.money_format(response.payroll.pagibig)));
                                                    _payablesCard.find('th.total-philhealth').text($.voucher.price_separator($.voucher.money_format(response.payroll.philhealth)));
                                                    _payablesCard.find('th.total-bir').text($.voucher.price_separator($.voucher.money_format(response.payroll.bir)));
                                                    _payablesCard.find('th.total-gsis').text($.voucher.price_separator($.voucher.money_format(response.payroll.gsis)));
                                                    var _totalAmt =  $.voucher.money_format(response.data.total_payables) -  $.voucher.money_format( parseFloat(response.data.total_ewt) +  $.voucher.money_format(response.data.total_evat) );
                                                    _payablesCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(_totalAmt)));
                                                    $.voucher.load_payable_contents(_payable.page());
                                                    _self.prop('disabled', true).html('<i class="ti-trash align-middle me-1"></i> REMOVE');
                                                    _payablesCard.find('#send-payable-btn').prop('disabled', true);
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('<i class="ti-trash align-middle me-1"></i> REMOVE')
                        });
                    } else {
                        console.log('sorry cannot be processed');
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
            } else {
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to removed.",
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
        | # when batch send payable is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#send-payable-btn', function (e) {
            var _self   = $(this);
            var _code = $('input[name="voucher_no"]').val();
            var _id   = $.voucher.fetchID();
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/send-all/' + _id;
            var _payablesCard = $('#payable-card');
            var _label = (voucherSegment == 'payables') ? 'payables' : 'collections';

            if (_payables.length > 0) {
                var d1    = $.voucherForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {
                        console.log(_url);
                        _self.prop('disabled', true).html('WAIT.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the (" + _payables.length + ") selected " + _label + " on voucher<br/>(" + _code + ")<br/>will be send for approval.",
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
                                    data: {
                                        'payables' : _payables,
                                        'voucher_id' : _id
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _payables = [];
                                            _self.prop('disabled', true).html('WAIT.....');
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                            .then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    $.voucher.load_payable_contents(_payable.page());
                                                    _self.prop('disabled', true).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND');
                                                    _payablesCard.find('#del-payable-btn').prop('disabled', true);
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND')
                        });
                    } else {
                        console.log('sorry cannot be processed');
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
            } else {
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to removed.",
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
        | # when batch remove payment is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#del-payment-btn', function (e) {
            var _self   = $(this);
            var _code = $('input[name="voucher_no"]').val();
            var _id   = $.voucher.fetchID();
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/remove-all/' + _id;
            var _paymentsCard = $('#payment-card');
            var _label = (voucherSegment == 'payables') ? 'payments' : 'deposits';

            if (_payments.length > 0) {
                var d1    = $.voucherForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {
                        console.log(_url);
                        _self.prop('disabled', true).html('WAIT.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the (" + _payments.length + ") selected " + _label + " on voucher<br/>(" + _code + ")<br/>will be removed.",
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
                                    data: {'payments' : _payments},
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _payments = [];
                                            _self.prop('disabled', true).html('WAIT.....');
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                            .then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    $.voucher.load_payment_contents(_payment.page());
                                                    _self.prop('disabled', true).html('<i class="ti-trash align-middle me-1"></i> REMOVE');
                                                    _paymentsCard.find('#send-payment-btn').prop('disabled', true);
                                                    _paymentsCard.find('th.total-amount').text($.voucher.price_separator( $.voucher.money_format(response.data.total_disbursement)));
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('<i class="ti-trash align-middle me-1"></i> REMOVE')
                        });
                    } else {
                        console.log('sorry cannot be processed');
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
            } else {
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to removed.",
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
        | # when batch send payment is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#send-payment-btn', function (e) {
            var _self   = $(this);
            var _code = $('input[name="voucher_no"]').val();
            var _id   = $.voucher.fetchID();
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/send-all/' + _id;
            var _paymentsCard = $('#payment-card');
            var _label = (voucherSegment == 'payables') ? 'payments' : 'deposits';

            if (_payments.length > 0) {
                var d1    = $.voucherForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {
                        console.log(_url);
                        _self.prop('disabled', true).html('WAIT.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the (" + _payments.length + ") selected " + _label + " on voucher<br/>(" + _code + ")<br/>will be send for approval.",
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
                                    data: {'payments' : _payments},
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _payments = [];
                                            _self.prop('disabled', true).html('WAIT.....');
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                            .then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    $.voucher.load_payment_contents(_payment.page());
                                                    _self.prop('disabled', true).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND');
                                                    _paymentsCard.find('#del-payment-btn').prop('disabled', true);
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND')
                        });
                    } else {
                        console.log('sorry cannot be processed');
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
            } else {
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to removed.",
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
        | # when print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .print-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id   = $(this).closest('tr').attr('data-row-id');
            var _url  = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/print-cheque/' + _id;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when payables approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/approve/' + _id;
            var _label = (voucherSegment == 'payables') ? 'payable' : 'collection';

            var d1 = $.voucher.fetch_payable_status(_id);
            var d2 = $.voucher.validate_payables_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>("+ _code +")</strong> will be approved.",
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
                                            $.voucher.load_payable_contents(_payable.page());
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
        | # when payables disapprove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-payable-modal');
            var _label = (voucherSegment == 'payables') ? 'Payable' : 'Collection';

            var d1 = $.voucher.fetch_payable_status(_id);
            var d2 = $.voucher.validate_payables_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _payableID = _id;
                    _payableCodex = _code;
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
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
        | # when payables disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-payable-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _url    = _form.attr('action') + '/disapprove/' + _payableID + '?remarks=' + encodeURIComponent(_form.find('textarea[name="disapproved_payable_remarks"]').val());
            var _error  = $.voucherForm.validate(_form, 0);
            var _label = (voucherSegment == 'payables') ? 'payable' : 'collection';

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
                    html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>(" + _payableCodex + ")</strong> will be disapproved.",
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
                            type: 'PUT',
                            url: _url,
                            data: {'payables' : _payables},
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        _self.html('Submit').prop('disabled', false);
                                        _modal.modal('hide');
                                        $.voucher.load_payable_contents(_payable.page());
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
        | # when show payable disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-payable-modal');
            var _label = (voucherSegment == 'payables') ? 'Payable' : 'Collection';

            _self.prop('disabled', true);
            var d1 = $.voucher.fetch_payable_status(_id);
            var d2 = $.voucher.fetch_payable_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _payableID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
                    _modal.find('textarea').val(v2).prop('disabled', true);
                    _modal.find('button.submit-disapprove-payable-btn').addClass('hidden');
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
        | # when payments approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payments/approve/' + _id;
            var _label = (voucherSegment == 'payables') ? 'payment' : 'deposit';

            var d1 = $.voucher.fetch_payment_status(_id);
            var d2 = $.voucher.validate_payments_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>("+ _code +")</strong> will be approved.",
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
                                            $.voucher.load_payment_contents(_payment.page());
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
        | # when payments disapprove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-payment-modal');
            var _label = (voucherSegment == 'payables') ? 'Payment' : 'Deposit';

            var d1 = $.voucher.fetch_payment_status(_id);
            var d2 = $.voucher.validate_payments_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _paymentID = _id;
                    _paymentCodex = _code;
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
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
        | # when payments disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-payment-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _url    = _form.attr('action') + '/disapprove/' + _paymentID + '?remarks=' + encodeURIComponent(_form.find('textarea[name="disapproved_payment_remarks"]').val());
            var _error  = $.voucherForm.validate(_form, 0);
            var _label = (voucherSegment == 'payables') ? 'payment' : 'deposit';

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
                    html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>(" + _paymentCodex + ")</strong> will be disapproved.",
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
                            type: 'PUT',
                            url: _url,
                            data: {'payables' : _payables},
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        _self.html('Submit').prop('disabled', false);
                                        _modal.modal('hide');
                                        $.voucher.load_payment_contents(_payment.page());
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
        | # when show payment disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#paymentsTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-payment-modal');
            var _label = (voucherSegment == 'payables') ? 'Payment' : 'Deposit';

            _self.prop('disabled', true);
            var d1 = $.voucher.fetch_payment_status(_id);
            var d2 = $.voucher.fetch_payment_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _paymentID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
                    _modal.find('textarea').val(v2).prop('disabled', true);
                    _modal.find('button.submit-disapprove-payment-btn').addClass('hidden');
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
        | # when payments approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#voucherTable .complete-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/complete/' + _id;
            var _label = (voucherSegment == 'payables') ? 'payables' : 'collections';

            var d1 = $.voucher.fetch_status(_id);
            var d2 = $.voucher.validate_voucher(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {   
                if (parseFloat(v2) > 0) {
                    if (v2 > 1) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The amount should be higher than zero.",
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
                            html: "Unable to proceed!<br/>The " + _label + " and disbursement doesn't match.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;   
                    }
                } else if (v1 == 'draft') {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the voucher with <strong>Voucher No<br/>("+ _code +")</strong> will be complete.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, complete it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn completed-bg text-white", cancelButton: "btn btn-active-light" },
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
                                            $.voucher.load_contents();
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
        | # when print disbursement
        | ---------------------------------
        */
        this.$body.on('click', '.print-disbursement-btn', function (e) {
            var _self = $(this);
            var _id   = $(this).closest('tr').attr('data-row-id');
            var _type = _self.attr('data');
            var _voucher = $('#voucher-card input[name="voucher_no"]').val();

            var d1 = $.voucher.fetch_disbursement_reference(_id);
            $.when( d1 ).done(function ( v1 ) 
            {
                var _url  = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/print/' + _voucher + '?type=' + _type + '&reference_no=' + v1;
                window.open(_url, '_blank');
            });
        });

        /*
        | ---------------------------------
        | # when deductions checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _payables.length; i++) {
                            if (_deductions[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _deductions.push(checkbox.val());
                        }
                    } 
                });
                console.log(_deductions);
            } else {
                _table.find('tr[data-row-status="draft"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="draft"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _deductions.length; i++) {
                        if (_deductions[i] == checkbox.val()) {
                            _deductions.splice(i, 1);
                        }
                    }
                });
                console.log(_deductions);
            }
            if (_deductions.length > 0) {
                $('#send-deduction-btn').prop('disabled', false);
            } else {
                $('#send-deduction-btn').prop('disabled', true);
            }
        });
        /*
        | ---------------------------------
        | # when deductions checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _deductions.push(_self.val());
            } else {
                for (var i = 0; i < _deductions.length; i++) {
                    if (_deductions[i] == _self.val()) {
                        _deductions.splice(i, 1);
                    }
                }
            }
            if (_deductions.length > 0) {
                $('#send-deduction-btn').prop('disabled', false);
            } else {
                $('#send-deduction-btn').prop('disabled', true);
            }
            console.log(_deductions);
        });

        /*
        | ---------------------------------
        | # when edit payables button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .view-btn, #payablesTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#account-payable-modal');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/payables/edit/' + _id;
            var _label = (voucherSegment == 'payables') ? 'Account Payable' : 'Collection';
            console.log(_url);
            _payableID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    if (response.data.trans_type == 'Purchase Order') {
                        _modal.find('input.require, select.require, textarea.require').prop('disabled', true);
                    } else {
                        _modal.find('input.require, select.required, textarea.require').prop('disabled', false);
                        _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
                    }
                    if (response.data.status != 'draft') {
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View ' + _label + ' (<span class="variables">' + _id + '</span>)');
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').prop('disabled', true).addClass('hidden');
                    } else {
                        _modal.find('.m-form__help').text('');
                        if (voucherSegment == 'payables') {
                            _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                            _modal.find('.modal-header h5').html('Edit ' + _label + ' (<span class="variables">' + _id + '</span>)');
                        } else {
                            _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                            _modal.find('.modal-header h5').html('View ' + _label + ' (<span class="variables">' + _id + '</span>)');
                            _modal.find('input, select, textarea').prop('disabled', true);
                        }
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
        | # when view deduction button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable .view-btn, #deductionTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _status = _self.closest('tr').attr('data-row-status');
            var _modal  = $('#account-payable-modal');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/view/' + _id;
            console.log(_url);
            _payableID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    if (response.data.trans_type == 'Purchase Order') {
                        _modal.find('input.require, select.require, textarea.require').prop('disabled', true);
                    } else {
                        _modal.find('input.require, select.required, textarea.require').prop('disabled', false);
                        _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
                    }
                    if (response.data.status != 'draft') {
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View Deduction (<span class="variables">' + _id + '</span>)');
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').prop('disabled', true).addClass('hidden');
                    } else {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View Deduction (<span class="variables">' + _id + '</span>)');
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
        | # when batch send deduction is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#send-deduction-btn', function (e) {
            var _self   = $(this);
            var _code = $('input[name="voucher_no"]').val();
            var _id   = $.voucher.fetchID();
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/send-all/' + _id;
            var _label = 'deductions';

            if (_deductions.length > 0) {
                var d1    = $.voucherForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {
                        console.log(_url);
                        _self.prop('disabled', true).html('WAIT.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the (" + _deductions.length + ") selected " + _label + " on voucher<br/>(" + _code + ")<br/>will be send for approval.",
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
                                    data: {
                                        'deductions' : _deductions,
                                        'voucher_id' : _id
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _deductions = [];
                                            _self.prop('disabled', true).html('WAIT.....');
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                            .then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    $.voucher.load_deduction_contents(_deduction.page());
                                                    _self.prop('disabled', true).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND');
                                                    // _deductionCard.find('#del-payable-btn').prop('disabled', true);
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('<i class="la la-angle-double-right align-middle me-1"></i> SEND')
                        });
                    } else {
                        console.log('sorry cannot be processed');
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
            } else {
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to removed.",
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
        | # when deductions approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'accounting/journal-entries/' + voucherSegment + '/deductions/approve/' + _id;
            var _label  = 'deduction';

            var d1 = $.voucher.fetch_deduction_status(_id);
            var d2 = $.voucher.validate_deductions_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>("+ _code +")</strong> will be approved.",
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
                                            $.voucher.load_deduction_contents(_deduction.page());
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
        | # when deductions disapprove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-deduction-modal');
            var _label  = 'Deduction';

            var d1 = $.voucher.fetch_deduction_status(_id);
            var d2 = $.voucher.validate_deductions_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _deductionID = _id;
                    _deductionCodex = _code;
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
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
        | # when deductions disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-deduction-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _url    = _form.attr('action') + '/disapprove/' + _deductionID + '?remarks=' + encodeURIComponent(_form.find('textarea[name="disapproved_deduction_remarks"]').val());
            var _error  = $.voucherForm.validate(_form, 0);
            var _label  = 'deduction';

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
                    html: "Are you sure? <br/>the " + _label + " with <strong>GL Code<br/>(" + _deductionCodex + ")</strong> will be disapproved.",
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
                            type: 'PUT',
                            url: _url,
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        _self.html('Submit').prop('disabled', false);
                                        _modal.modal('hide');
                                        $.voucher.load_deduction_contents(_deduction.page());
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
        | # when show deduction disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#deductionTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-deduction-modal');
            var _label = 'Deduction';

            _self.prop('disabled', true);
            var d1 = $.voucher.fetch_deduction_status(_id);
            var d2 = $.voucher.fetch_deduction_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _deductionID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text(_label + ' Disapproval (' + $.trim(_code) + ')');
                    _modal.find('textarea').val(v2).prop('disabled', true);
                    _modal.find('button.submit-disapprove-deduction-btn').addClass('hidden');
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
    }    

    //init voucher
    $.voucher = new voucher, $.voucher.Constructor = voucher

}(window.jQuery),

//initializing voucher
function($) {
    "use strict";
    $.voucher.required_fields();
    $.voucher.init();
}(window.jQuery);