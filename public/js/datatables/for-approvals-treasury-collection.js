!function($) {
    "use strict";

    var for_approval_treasury_collection = function() {
        this.$body = $("body");
    };

    var _ctoCollectionID = 0; var _table, _codex, _transTable, _receiptTable; var _page = 0, _transPage = 0, _receiptPage = 0; var _status = 'all';
    var _totalBill = 0, _totalTrans = 0; var _collections = [];

    for_approval_treasury_collection.prototype.validate = function($form, $required)
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

    for_approval_treasury_collection.prototype.required_fields = function() {
        
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

    for_approval_treasury_collection.prototype.load_contents = function(_tablePage = 0) 
    {   
        console.log(_baseUrl + 'for-approvals/collections/lists?status=' + _status);
        _table = new DataTable('#collectionTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/collections/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_treasury_collection.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_treasury_collection.hideTooltip();
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
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.transaction_no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-sequence', data.sequence);
            },
            // dom: 'lf<"toolbar-3 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_tablePage).draw( 'page' );   
            },      
            columns: [
                { data: 'transaction_label' },
                { data: 'transaction_date' },
                { data: 'officer' },
                { data: 'total_amount' },
                { data: 'approved_by' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-end' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
            ]
        } );

        // _table.on( 'draw', function () {
        //     $('#collectionTable th input[type="checkbox"]').prop('checked', false);
        //     for (var i = 0; i < _tables.length; i++) {
        //         $('#collectionTable').find('tr[data-row-id="' +  _tables[i] + '"][data-row-status="draft"] input[type="checkbox"]').prop('checked', true);
        //     }
        // } );

        return true;
    },

    for_approval_treasury_collection.prototype.load_trans_contents = function(_transPage = 0) 
    {   
        _transTable = new DataTable('#transTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/collections/transaction-lists/' + _ctoCollectionID + '?officer=' + $('select[name="officer_id"]').val() + '&transaction_date=' + $('input[name="transaction_date"]').val(),
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    $.for_approval_treasury_collection.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_treasury_collection.hideTooltip();
                    _collections = response.responseJSON.collections;
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 3,
            lengthMenu: [
                [3, 10, 25, 50, -1],
                [3, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_transPage).draw( 'page' );   
            },      
            columns: [
                { data: 'trans_date' },
                { data: 'or_label' },
                { data: 'taxpayer' },
                { data: 'form_code' },
                { data: 'credit' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-end' },
            ]
        } );

        return true;
    },

    for_approval_treasury_collection.prototype.load_receipt_contents = function(_receiptPage = 0) 
    {   
        _receiptTable = new DataTable('#receiptTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/collections/receipt-lists/' + _ctoCollectionID + '?officer=' + $('select[name="officer_id"]').val() + '&transaction_date=' + $('input[name="transaction_date"]').val(),
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    $.for_approval_treasury_collection.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_treasury_collection.hideTooltip();
                    if (response.responseJSON.total_amount > 0) {
                        _totalTrans = parseFloat(response.responseJSON.total_amount);
                        $('.total-transaction').text($.for_approval_treasury_collection.price_separator(response.responseJSON.total_amount));
                    } else {
                        _totalTrans = 0;
                        $('.total-transaction').text('0.00');
                    }
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 3,
            lengthMenu: [
                [3, 10, 25, 50, -1],
                [3, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(response){
                this.api().page(_receiptPage).draw( 'page' );   
                console.log(response);
            },      
            columns: [
                { data: 'form_no' },
                { data: 'or_dept' },
                { data: 'or_from' },
                { data: 'or_to' },
                { data: 'total_amount' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: false, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: false, targets: 4, className: 'text-end' },
            ]
        } );

        return true;
    },

    for_approval_treasury_collection.prototype.validate_approver = function (_id, _sequence)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/collections/validate-approver/' + _id + '/' + _sequence);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/collections/validate-approver/' + _id + '/' + _sequence,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_treasury_collection.prototype.fetchID = function()
    {
        return _ctoCollectionID;
    }

    for_approval_treasury_collection.prototype.updateID = function(_id)
    {
        return _ctoCollectionID = _id;
    }

    for_approval_treasury_collection.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/collections/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/collections/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_treasury_collection.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/collections/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/collections/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    for_approval_treasury_collection.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_treasury_collection.prototype.preload_select3 = function()
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

    for_approval_treasury_collection.prototype.perfect_scrollbar = function()
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
    for_approval_treasury_collection.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_treasury_collection.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },  

    for_approval_treasury_collection.prototype.get_denominations = function(_table)
    {
        _table.find('tbody').empty(); var _datas = '';
        console.log(_baseUrl + 'for-approvals/collections/get-denominations/' + _ctoCollectionID);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/collections/get-denominations/' + _ctoCollectionID,
            success: function(response) {
                $.each(response.data, function(i, item) {
                    _datas += '<tr>';
                    _datas += '<td class="text-center">' + item.name + '</td>';
                    _datas += '<td class="text-center">';
                    _datas += '<input class="form-control form-control-solid text-center numeric-only" multiplier="' + item.multiplier + '" name="counter[' + item.id + ']" type="text" value="' + item.counter + '"/>';
                    _datas += '</td>';
                    _datas += '<td class="text-center pt-1 pb-1">';
                    _datas += '<input class="form-control form-control-solid text-center strong" disabled="disabled" name="amount[' + item.id + ']" type="text" value="' + item.amount + '"/>';
                    _datas += '</td>';
                    _datas += '</tr>';
                }); 
                _table.append(_datas);
            },
            async: false
        });

        return true;
    },

    for_approval_treasury_collection.prototype.compute_totalAmount = function(_table)
    {   
        var _totalAmt = 0;
        $.each(_table.find("tr td:last-child input"), function(){
            var _self = $(this);
            if (parseFloat(_self.val()) > 0) {
                _totalAmt += parseFloat(_self.val());
            }
        });
        _totalBill = parseFloat(_totalAmt);
        $('.total-amount').text($.for_approval_treasury_collection.price_separator(parseFloat(_totalAmt).toFixed(2)));
    },

    for_approval_treasury_collection.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_treasury_collection.preload_select3();
        $.for_approval_treasury_collection.load_contents();
        $.for_approval_treasury_collection.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-payment-modal', function (e) {
            var _modal = $(this);
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.for_approval_treasury_collection.load_contents();
            _ctoCollectionID = 0;
            _tables = [];
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
            _ctoCollectionID = 0;
            _codex = '';
        });

        /*
        | ---------------------------------
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _sequence = $(this).closest('tr').attr('data-row-sequence');
            var _url    = _baseUrl + 'for-approvals/collections/approve/' + _id;

            var d1 = $.for_approval_treasury_collection.validate_approver(_id, _sequence);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (_status == 'pending' && v1 != false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>collections with <br/><strong>transaction no. ("+ _code +")</strong><br/>will be approved.",
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
                                            $.for_approval_treasury_collection.load_contents();
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
            var _url    = _form.attr('action') + '/disapprove/' + _ctoCollectionID + '?remarks=' + encodeURIComponent(_form.find('textarea[name="disapproved_remarks"]').val());
            var _error  = $.for_approval_treasury_collection.validate(_form, 0);

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
                    html: "Are you sure? <br/>the treasury collection with <br/><strong>transaction no. (" + _codex + ")</strong><br/>will be disapproved.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, disapprove it!",
                    cancelButtonText: "No, return",
                    customClass: { confirmButton: "btn bg-danger text-white", cancelButton: "btn btn-active-light" },
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
                                        $.for_approval_treasury_collection.load_contents();
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _sequence = $(this).closest('tr').attr('data-row-sequence');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_treasury_collection.validate_approver(_id, _sequence);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (_status == 'pending' && v1 != false) {
                    _ctoCollectionID = _id;
                    _codex = _code;
                    _modal.find('h5').text('Collection Disapproval (' + $.trim(_code) + ')');
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
        this.$body.on('click', '#collectionTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_treasury_collection.fetch_status(_id);
            var d2 = $.for_approval_treasury_collection.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled'){
                    _ctoCollectionID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text('Replenishment Disapproval (' + $.trim(_code) + ')');
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
        | # when payments checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="pending"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="pending"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _tables.length; i++) {
                            if (_tables[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _tables.push(checkbox.val());
                        }
                    } 
                });
            } else {
                _table.find('tr[data-row-status="pending"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="pending"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _tables.length; i++) {
                        if (_tables[i] == checkbox.val()) {
                            _tables.splice(i, 1);
                        }
                    }
                });
            }
            console.log(_tables);
            if (_tables.length > 0) {
                $('#approved-btn, #disapproved-btn').addClass('active');
            } else {
                $('#approved-btn, #disapproved-btn').removeClass('active');
            }
        });
        /*
        | ---------------------------------
        | # when payments checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _tables.push(_self.val());
            } else {
                for (var i = 0; i < _tables.length; i++) {
                    if (_tables[i] == _self.val()) {
                        _tables.splice(i, 1);
                    }
                }
            }
            console.log(_tables);
            if (_tables.length > 0) {
                $('#approved-btn, #disapproved-btn').addClass('active');
            } else {
                $('#approved-btn, #disapproved-btn').removeClass('active');
            }
        });

        /*
        | ---------------------------------
        | # when batch approve payment is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#approved-btn', function (e) {
            var _self   = $(this);
            var _url    = _baseUrl + 'for-approvals/collections/approve-all';

            if (_tables.length > 0) {
                console.log(_url);
                _self.prop('disabled', true);
                Swal.fire({
                    html: "Are you sure? <br/>the (" + _tables.length + ") selected payments <br/>will be approved.",
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
                            data: {'payments' : _tables},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    _tables = [];
                                    _self.prop('disabled', true);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                                    .then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));                                                    
                                            _self.prop('disabled', false);
                                            $('.btn-circle').removeClass('active');
                                            $.for_approval_treasury_collection.load_contents(_table.page());
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

            if (_tables.length > 0) {
                _modal.find('h5').text('Disbursement Batch Disapproval');
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

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable .edit-btn, #collectionTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'for-approvals/collections/view/' + _id;
            var _modal  = $('#collection-modal');
            _ctoCollectionID = _id;
            console.log(_url);
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.for_approval_treasury_collection.get_denominations(_modal.find('table#denomination'));
                    var d2 = $.for_approval_treasury_collection.load_trans_contents();
                    var d3 = $.for_approval_treasury_collection.load_receipt_contents();
                    $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
                    {  
                        $.each(response.data[0], function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        $.for_approval_treasury_collection.compute_totalAmount($('#denomination'));
                        if (response.data[0].status != 'draft') {
                            _modal.find('input.form-control:not([type="search"]), select.form-control, textarea.form-control').prop('disabled', true);
                            _modal.find('button.submit-btn').addClass('hidden');
                            _modal.find('button.send-btn').addClass('hidden');
                            _modal.find('button.print-btn').removeClass('hidden');
                        }
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('View Collection (<span class="variables">' + _code + '</span>)');
                        _modal.modal('show');
                    });
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        });
    }

    //init for_approval_treasury_collection
    $.for_approval_treasury_collection = new for_approval_treasury_collection, $.for_approval_treasury_collection.Constructor = for_approval_treasury_collection

}(window.jQuery),

//initializing for_approval_treasury_collection
function($) {
    "use strict";
    $.for_approval_treasury_collection.required_fields();
    $.for_approval_treasury_collection.init();
}(window.jQuery);