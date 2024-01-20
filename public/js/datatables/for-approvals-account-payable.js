!function($) {
    "use strict";

    var for_approval_account_payable = function() {
        this.$body = $("body");
    };

    var _payableID = 0; var _payable; var _payables = []; var _payableStatus = 'all'; var _payablePage = 0; var _codex = '';

    for_approval_account_payable.prototype.validate = function($form, $required)
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

    for_approval_account_payable.prototype.required_fields = function() {
        
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

    for_approval_account_payable.prototype.load_contents = function(_payablePage = 0) 
    {   
        console.log(_baseUrl + 'for-approvals/account-payables/lists?status=' + _payableStatus);
        _payable = new DataTable('#payablesTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/account-payables/lists?status=' + _payableStatus,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_account_payable.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_account_payable.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
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
                this.api().page(_payablePage).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="ap_status" aria-controls="ap_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="all">All</option><option value="pending">Pending</option><option value="posted">Approved</option><option value="cancelled">Disapproved</option></select></label>');           
                $('select[name="ap_status"]').val(_payableStatus);
                if(_payable.rows().data().length > 0) {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', true);
                } else {
                    $('#voucher-card select[name="fund_code_id"]').prop('disabled', false);
                }
            },      
            columns: [
                { data: 'checkbox' },
                { data: 'voucher_label' },
                { data: 'transaction_label' },
                { data: 'vat' },
                { data: 'gl_account_label' },
                { data: 'items' },
                { data: 'total' },
                { data: 'approved_by' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-center w-25' },
                {  orderable: true, targets: 1, className: 'text-center' },
                {  orderable: false, targets: 2, className: 'text-center' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
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

    for_approval_account_payable.prototype.load_line_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'for-approvals/account-payables/line-lists/' + _payableID); 
        _breakdownTable = new DataTable('#breakdownTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/account-payables/line-lists/' + _payableID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.for_approval_account_payable.shorten();
                }
            },     
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
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
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.no);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'no' },
                { data: 'gl_account' },
                { data: 'quarterly' },
                { data: 'annual' },
                { data: 'status_label' },
                { data: 'actions' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-center' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: false, targets: 2, className: 'text-end' },
                {  orderable: false, targets: 3, className: 'text-end' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
            ]
        } );

        return true;
    },

    for_approval_account_payable.prototype.validate_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/account-payables/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/account-payables/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_account_payable.prototype.fetchID = function()
    {
        return _payableID;
    }

    for_approval_account_payable.prototype.updateID = function(_id)
    {
        return _payableID = _id;
    }

    for_approval_account_payable.prototype.reload_division = function($department)
    {   
        var $division = $('#division_id'); $division.find('option').remove(); 

        console.log(_baseUrl + 'for-approvals/account-payables/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/account-payables/reload-division-via-department/' + $department,
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

    for_approval_account_payable.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/account-payables/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/account-payables/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_account_payable.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/account-payables/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/account-payables/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    for_approval_account_payable.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_account_payable.prototype.preload_select3 = function()
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

    for_approval_account_payable.prototype.perfect_scrollbar = function()
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
    for_approval_account_payable.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_account_payable.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    for_approval_account_payable.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        console.log(_baseUrl + 'for-approvals/account-payables/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/account-payables/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_account_payable.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_account_payable.preload_select3();
        $.for_approval_account_payable.load_contents();
        $.for_approval_account_payable.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#account-payable-modal', function (e) {
            var _modal = $(this);
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.for_approval_account_payable.load_contents();
            _payableID = 0;
            _payables = [];
        });

        /*
        | ---------------------------------
        | # when stats on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="ap_status"]', function (e) {
            var _self = $(this);
            _payableStatus = _self.val();
            $.for_approval_account_payable.load_contents();
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
            _payableID = 0;
            _payables = [];
            _codex = '';
        });

        /*
        | ---------------------------------
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#departmental-requisition-modal');
            var _controlNo = _modal.find('#rfq_id'); _controlNo.find('option').remove(); 
            var _url    = _baseUrl + 'for-approvals/account-payables/approve/' + _id;

            var d1 = $.for_approval_account_payable.fetch_status(_id);
            var d2 = $.for_approval_account_payable.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the payable with <strong>GL Code<br/>("+ _code +")</strong> will be approved.",
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
                                            $.for_approval_account_payable.load_contents();
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
        | # when disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _url    = (_payableID > 0) ? _form.attr('action') + '/disapprove/' + _payableID + '?remarks=' + _form.find('textarea[name="disapproved_remarks"]').val() : _baseUrl + 'for-approvals/account-payables/disapprove-all?remarks=' + _form.find('textarea[name="disapproved_remarks"]').val();
            var _error  = $.for_approval_account_payable.validate(_form, 0);

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
                    html: (_payableID > 0) ? "Are you sure? <br/>the payable with <strong>GL Code<br/>(" + _codex + ")</strong> will be disapproved." : "Are you sure? the ("+ _payables.length +") selected payables will be disapproved.",
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
                                        $('.btn-circle').removeClass('active');
                                        $.for_approval_account_payable.load_contents();
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
        | # when view button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _total  = $(this).closest('tr').attr('data-row-total');
            var _modal  = $('#account-payable-modal');
            var _url    = _baseUrl + 'for-approvals/account-payables/view/' + _id;
            console.log(_url);
            _payableID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
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
                    _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('input, select, textarea').prop('disabled', true);
                    _modal.find('.modal-header h5').html('View Account Payable (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#payablesTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_account_payable.fetch_status(_id);
            var d2 = $.for_approval_account_payable.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _payableID = _id;
                    _codex = _code;
                    _modal.find('h5').text('Account Payable Disapproval (' + $.trim(_code) + ')');
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
        this.$body.on('click', '#payablesTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_account_payable.fetch_status(_id);
            var d2 = $.for_approval_account_payable.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _payableID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text('Account Payable Disapproval (' + $.trim(_code) + ')');
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
        | # when payables checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#payablesTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="pending"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="pending"] input[type="checkbox"][value!="all"]'), function(){
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
            } else {
                _table.find('tr[data-row-status="pending"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="pending"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _payables.length; i++) {
                        if (_payables[i] == checkbox.val()) {
                            _payables.splice(i, 1);
                        }
                    }
                });
            }
            console.log(_payables);
            if (_payables.length > 0) {
                $('#approved-btn, #disapproved-btn').addClass('active');
            } else {
                $('#approved-btn, #disapproved-btn').removeClass('active');
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
            console.log(_payables);
            if (_payables.length > 0) {
                $('#approved-btn, #disapproved-btn').addClass('active');
            } else {
                $('#approved-btn, #disapproved-btn').removeClass('active');
            }
        });

        /*
        | ---------------------------------
        | # when batch approve payable is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#approved-btn', function (e) {
            var _self   = $(this);
            var _url    = _baseUrl + 'for-approvals/account-payables/approve-all';

            if (_payables.length > 0) {
                console.log(_url);
                _self.prop('disabled', true);
                Swal.fire({
                    html: "Are you sure? <br/>the (" + _payables.length + ") selected payables <br/>will be approved.",
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
                            data: {'payables' : _payables},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    _payables = [];
                                    _self.prop('disabled', true);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                    .then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));                                                    
                                            _self.prop('disabled', false);
                                            $('.btn-circle').removeClass('active');
                                            $.for_approval_account_payable.load_contents(_payable.page());
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
                console.log('sorry cannot be processed');
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an item to approved.",
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
        | # when voucher no is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.voucher-link', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _title = _self.find('strong').text();
            var _url = _self.attr('link');      

            var dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
            var dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

            var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var systemZoom = width / window.screen.availWidth;
            var left = (width - 500) / 2 / systemZoom + dualScreenLeft
            var top = (height - 500) / 2 / systemZoom + dualScreenTop
            var newWindow = window.open(_url, _title, 'scrollbars=yes,width='+(width / systemZoom)+',height='+(height / systemZoom)+',top='+top+',left='+left);

            if (window.focus) newWindow.focus(); 
        });
        
        /*
        | ---------------------------------
        | # when batch disapprove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#disapproved-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _modal  = $('#disapprove-modal');

            if (_payables.length > 0) {
                _modal.find('h5').text('Account Payable Batch Disapproval');
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
    }

    //init for_approval_account_payable
    $.for_approval_account_payable = new for_approval_account_payable, $.for_approval_account_payable.Constructor = for_approval_account_payable

}(window.jQuery),

//initializing for_approval_account_payable
function($) {
    "use strict";
    $.for_approval_account_payable.required_fields();
    $.for_approval_account_payable.init();
}(window.jQuery);