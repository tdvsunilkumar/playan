!function($) {
    "use strict";

    var ctoCollection = function() {
        this.$body = $("body");
    };

    var _ctoCollectionID = 0; var _table, _transTable, _receiptTable; var _page = 0, _transPage = 0, _receiptPage = 0; var _status = 'all';
    var _totalBill = 0, _totalTrans = 0; var _collections = [];

    ctoCollection.prototype.required_fields = function() {
        
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

    ctoCollection.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#collectionTable', {
            ajax: { 
                url : _baseUrl + 'treasury/collections/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.ctoCollection.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.ctoCollection.hideTooltip();
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
                $(row).attr('data-row-code', data.transaction_no);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="for approval">Pending</option><option value="posted">Posted</option><option value="cancelled">Cancelled</option><option value="all">All</option></select></label>');           
                $('select[name="status"]').val(_status);
            },      
            columns: [
                { data: 'fund' },
                { data: 'transaction_label' },
                { data: 'transaction_date' },
                { data: 'officer' },
                { data: 'total_amount' },
                { data: 'modified' },
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
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    ctoCollection.prototype.load_trans_contents = function(_transPage = 0) 
    {   
        _transTable = new DataTable('#transTable', {
            ajax: { 
                url : _baseUrl + 'treasury/collections/transaction-lists/' + _ctoCollectionID + '?fund=' + $('select[name="fund_code_id"]').val() + '&officer=' + $('select[name="officer_id"]').val() + '&transaction_date=' + $('input[name="transaction_date"]').val(),
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    $.ctoCollection.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.ctoCollection.hideTooltip();
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
                {  orderable: true, targets: 4, className: 'text-end' }
            ]
        } );

        return true;
    },

    ctoCollection.prototype.load_receipt_contents = function(_receiptPage = 0) 
    {   
        _receiptTable = new DataTable('#receiptTable', {
            ajax: { 
                url : _baseUrl + 'treasury/collections/receipt-lists/' + _ctoCollectionID + '?fund=' + $('select[name="fund_code_id"]').val() + '&officer=' + $('select[name="officer_id"]').val() + '&transaction_date=' + $('input[name="transaction_date"]').val(),
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    $.ctoCollection.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.ctoCollection.hideTooltip();
                    console.log('discount: ' + response.responseJSON.total_discount);
                    console.log('total: ' + response.responseJSON.total_amount);
                    if (response.responseJSON.total_amount > 0) {
                        _totalTrans = parseFloat(response.responseJSON.total_amount) - parseFloat(response.responseJSON.total_discount);
                        $('.total-transaction').text($.ctoCollection.price_separator($.ctoCollection.money_format(_totalTrans)));
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
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: false, targets: 4, className: 'text-end' },
            ]
        } );

        return true;
    },

    ctoCollection.prototype.fetchCollections = function()
    {
        return _collections;
    },

    ctoCollection.prototype.fetchTotalTrans = function()
    {
        return _totalTrans;
    }

    ctoCollection.prototype.updateTotalTrans = function(_total)
    {
        return _totalTrans = _total;
    }

    ctoCollection.prototype.fetchTotalBill = function()
    {
        return _totalBill;
    }

    ctoCollection.prototype.updateTotalBill = function(_total)
    {
        return _totalBill = _total;
    }

    ctoCollection.prototype.fetchID = function()
    {
        return _ctoCollectionID;
    }

    ctoCollection.prototype.updateID = function(_id)
    {
        return _ctoCollectionID = _id;
    }

    ctoCollection.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    ctoCollection.prototype.preload_select3 = function()
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

    ctoCollection.prototype.perfect_scrollbar = function()
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

    ctoCollection.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    ctoCollection.prototype.money_format = function(_money)
    {   
        // return parseFloat(Math.floor((_money * 100))/100).toFixed(2);
        return parseFloat(_money).toFixed(2);
    },

    ctoCollection.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    ctoCollection.prototype.getDate = function()
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
    },

    ctoCollection.prototype.get_denominations = function(_table)
    {
        _table.find('tbody').empty(); var _datas = '';
        console.log(_baseUrl + 'treasury/collections/get-denominations/' + _ctoCollectionID);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'treasury/collections/get-denominations/' + _ctoCollectionID,
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

    ctoCollection.prototype.compute_totalAmount = function(_table)
    {   
        var _totalAmt = 0;
        $.each(_table.find("tr td:last-child input"), function(){
            var _self = $(this);
            if (parseFloat(_self.val()) > 0) {
                _totalAmt += parseFloat(_self.val());
            }
        });
        _totalBill = parseFloat(_totalAmt);
        $('.total-amount').text($.ctoCollection.price_separator(parseFloat(_totalAmt).toFixed(2)));
    },

    ctoCollection.prototype.unset = function()
    {   
        if (_ctoCollectionID > 0) {
            $.ajax({
                type: "PUT",
                url: _baseUrl + 'treasury/collections/unset/' + _ctoCollectionID,
                success: function(response) {
                },
                async: false
            });
        }
        return true;
    },

    ctoCollection.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.ctoCollection.preload_select3();
        $.ctoCollection.load_contents();
        $.ctoCollection.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.ctoCollection.hideTooltip();
        });

        /*
        | ---------------------------------
        | # keypress numeric only
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-only', function (event) {
            var charCode = (event.which) ? event.which : event.keyCode    
    
            if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                return false;             
            }
        });

        /*
        | ---------------------------------
        | # when no. of pieces onkeyup/blur
        | ---------------------------------
        */
        this.$body.on('keyup', '#denomination .numeric-only', function (event) {
            var _self = $(this);
            var _parent = _self.closest('table');
            var _multiplier = _self.attr('multiplier');
            var _amount = _self.closest('td').next().find('input');

            if (_self.val() != '') {
                var _total = parseFloat(_self.val()) * parseFloat(_multiplier);
                _amount.val($.ctoCollection.money_format(_total));
            } else {
                _amount.val('');
            }
            $.ctoCollection.compute_totalAmount(_parent);
        });
        this.$body.on('blur', '#denomination .numeric-only', function (event) {
            var _self = $(this);
            var _parent = _self.closest('table');
            var _multiplier = _self.attr('multiplier');
            var _amount = _self.closest('td').next().find('input');

            if (_self.val() != '') {
                var _total = parseFloat(_self.val()) * parseFloat(_multiplier);
                _amount.val($.ctoCollection.money_format(_total));
            } else {
                _amount.val('');
            }
            $.ctoCollection.compute_totalAmount(_parent);
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#collection-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Collections');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.ctoCollection.preload_select3();
            $.ctoCollection.hideTooltip();
            $.ctoCollection.load_contents(_table.page());
            _ctoCollectionID = 0;
        });
        this.$body.on('shown.bs.modal', '#collection-modal', function (e) {
            $.ctoCollection.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when officer onChange
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="officer_id"]', function (e) {
            var _self = $(this);
            var d1 = $.ctoCollection.unset();
            $.when( d1 ).done(function ( v1 ) 
            {   
                $.ctoCollection.load_trans_contents();
                $.ctoCollection.load_receipt_contents();
            });
        });

        /*
        | ---------------------------------
        | # when transaction date onBlur
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="fund_code_id"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.ctoCollection.load_trans_contents();
            $.ctoCollection.load_receipt_contents();
        });

        /*
        | ---------------------------------
        | # when transaction date onBlur
        | ---------------------------------
        */
        this.$body.on('change', 'input[name="transaction_date"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.ctoCollection.load_trans_contents();
            $.ctoCollection.load_receipt_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#collection-modal');
            var d1 = $.ctoCollection.get_denominations(_modal.find('table#denomination'));
            var d2 = $.ctoCollection.load_trans_contents();
            var d3 = $.ctoCollection.load_receipt_contents();
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            {   
                _modal.modal('show');
            });
                // _modal.find('input[name="due_date"]').val($.ctoCollection.getDate());
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable .edit-btn, #collectionTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#collection-modal');
            var _url    = _baseUrl + 'treasury/collections/edit/' + _id;
            console.log(_url);
            _ctoCollectionID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.ctoCollection.get_denominations(_modal.find('table#denomination'));
                    var d2 = $.ctoCollection.load_trans_contents();
                    var d3 = $.ctoCollection.load_receipt_contents();
                    $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
                    {  
                        $.each(response.data[0], function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        $.ctoCollection.compute_totalAmount($('#denomination'));
                        if (response.data[0].status != 'draft') {
                            _modal.find('input.form-control:not([type="search"]), select.form-control, textarea.form-control').prop('disabled', true);
                            _modal.find('button.submit-btn').addClass('hidden');
                            _modal.find('button.send-btn').addClass('hidden');
                            _modal.find('button.print-btn').removeClass('hidden');
                        }
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Collection (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#collectionTable .remove-btn, #collectionTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/account-payables/remove/' + _id : _baseUrl + 'accounting/account-payables/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the payment type with code ("+ _code +") will be removed." : "Are you sure? <br/>the payment type with code ("+ _code +") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status == 'Active') ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status == 'Active') ? "btn btn-danger" : "btn btn-info", cancelButton: "btn btn-active-light" },
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
                                    $.ctoCollection.load_contents(_table.page());
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
            
        }); 

        /*
        | ---------------------------------
        | # when print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collectionTable .print-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _transNo = _self.closest('tr').attr('data-row-code');
            var _url  = _baseUrl + 'treasury/collections/print/' + _transNo; 
            window.open(_url, '_blank');
        });
    }

    //init ctoCollection
    $.ctoCollection = new ctoCollection, $.ctoCollection.Constructor = ctoCollection

}(window.jQuery),

//initializing ctoCollection
function($) {
    "use strict";
    $.ctoCollection.required_fields();
    $.ctoCollection.init();
}(window.jQuery);