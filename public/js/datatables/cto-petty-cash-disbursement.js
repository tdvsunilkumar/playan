!function($) {
    "use strict";

    var cto_petty_cash = function() {
        this.$body = $("body");
    };

    var _pettyCashID = 0; var _breakdownID = 0; var _table; var _tableLine; 
    var _tablePage = 0, _linePage = 0;

    cto_petty_cash.prototype.required_fields = function() {
        
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

    cto_petty_cash.prototype.load_contents = function(_tablePage = 0) 
    {   
        console.log(_baseUrl + 'treasury/petty-cash/disbursement/lists'); 
        _table = new DataTable('#pettyCashTable', {
            ajax: { 
                url : _baseUrl + 'treasury/petty-cash/disbursement/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.cto_petty_cash.shorten();
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
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-voucher', data.voucher);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-total', data.total);
            },
            columns: [
                { data: 'id' },
                { data: 'control_no_label' },
                { data: 'voucher_label' },
                { data: 'payee' },
                { data: 'department' },
                { data: 'particulars' },
                { data: 'total_label' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-left sliced' },
                {  orderable: true, targets: 6, className: 'text-end' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    cto_petty_cash.prototype.load_line_contents = function(_linePage = 0) 
    {   
        console.log(_baseUrl + 'treasury/petty-cash/disbursement/line-lists/' + _pettyCashID); 
        _tableLine = new DataTable('#pettyCashLineTable', {
            ajax: { 
                url : _baseUrl + 'treasury/petty-cash/disbursement/line-lists/' + _pettyCashID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.cto_petty_cash.shorten();
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
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.alob_no);
                // $(row).attr('data-row-status', data.status);
            },
            dom: 'l<"toolbar-2">frtip',
            initComplete: function(){
                $("div.toolbar-2").html('<button type="button" class="btn btn-small bg-info" id="add-petty-cash-line">ADD LINE</button>');   
                if(_tableLine.rows().data().length > 0) {
                    $('select[name="voucher_id"]').prop('disabled', true);
                    $('select[name="department_id"]').prop('disabled', true);
                } else {
                    $('select[name="voucher_id"]').prop('disabled', false);
                    $('select[name="department_id"]').prop('disabled', false);
                }       
            }, 
            columns: [
                { data: 'id' },
                { data: 'alob_no_label' },
                { data: 'total' },
                { data: 'modified' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-end' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' }
            ]
        } );

        return true;
    },

    cto_petty_cash.prototype.fetchID = function()
    {
        return _pettyCashID;
    }

    cto_petty_cash.prototype.updateID = function(_id)
    {
        return _pettyCashID = _id;
    }

    cto_petty_cash.prototype.fetchTableLine = function()
    {
        return _tableLine;
    }

    cto_petty_cash.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    cto_petty_cash.prototype.preload_select3 = function()
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

    cto_petty_cash.prototype.perfect_scrollbar = function()
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

    cto_petty_cash.prototype.validate_table = function($table)
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
    cto_petty_cash.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    cto_petty_cash.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.cto_petty_cash.preload_select3();
        $.cto_petty_cash.load_contents();
        $.cto_petty_cash.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#petty-cash-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Petty Cash');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('tfoot th.text-end.text-danger').text('0.00');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            _modal.find('input, select, textarea').prop('disabled', false);
            _modal.find('input[name="payee_id"]').prop('disabled', true);
            $.cto_petty_cash.load_contents();
            _pettyCashID = 0;
        });
        this.$body.on('shown.bs.modal', '#petty-cash-modal', function (e) {
            var _modal = $(this);
            _modal.find('input[name="payee_id"]').prop('disabled', true);
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#petty-cash-modal');
            var d1 = $.cto_petty_cash.load_line_contents();
            $.when( d1 ).done(function (v1) { 
                _modal.modal('show');
            });
        });

        /*
        | ---------------------------------
        | # when breakdown modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#budget-breakdown-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Breakdown');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"][name="is_ppmp"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_ppmp"][value="0"]').prop('checked', true);
            $.cto_petty_cash.preload_select3();
            $.cto_petty_cash.load_line_contents();
            // $('#budget_year').prop('disabled', false);
            _breakdownID = 0;
        });

        /*
        | ---------------------------------
        | # when breakdown modal is shown
        | ---------------------------------
        */
        this.$body.on('shown.bs.modal', '#budget-breakdown-modal', function (e) {
            var _modal = $(this);
                $('#add-breakdown').prop('disabled', false).html('ADD LINE');
                $.cto_petty_cash.preload_select4();
        });
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#pettyCashTable .edit-btn, #pettyCashTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'treasury/petty-cash/disbursement/edit/' + _id;
            var _modal  = $('#petty-cash-modal');
            _pettyCashID = _id;
            console.log(_url);
            $('#budget_year').prop('disabled', true);
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[type="text"][name='+k+']:not([type="radio"])').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v).trigger('change.select3');
                        if (k == 'payee') {
                            if (v != null) {
                                _modal.find('input[type="text"][name="payee_id"]').val(v.paye_name);
                            }
                        }
                    });
                    $.cto_petty_cash.load_line_contents();
                    if (_status != 'draft') {
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                        _modal.find('input[type="text"], select.select3, textarea').prop('disabled', true);
                        _modal.find('button.send-btn').addClass('hidden');
                    } else {
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>'); 
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Petty Cash (<span class="variables">' + _code + '</span>)');
                    _modal.find('label[for="total-amount"] span').text('₱' + $.cto_petty_cash.price_separator(parseFloat(Math.floor((response.total * 100))/100).toFixed(2)));
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
        this.$body.on('click', '#pettyCashLineTable .remove-btn', function (e) {
            var _id   = $(this).closest('tr').attr('data-row-id');
            var _code = $(this).closest('tr').attr('data-row-code');
            var _url  = _baseUrl + 'treasury/petty-cash/disbursement/remove-line/' + _id;
            var _modal = $(this).closest('.modal');

            var _id = $.cto_petty_cash.fetchID();
            var d1 = $.cto_petty_cashForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {
                    Swal.fire({
                        html: "Are you sure? <br/>the petty cash detail with code ("+ _code +") will be removed.",
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
                                            _modal.find('label[for="total-amount"] span').text('₱' + $.cto_petty_cash.price_separator(parseFloat(Math.floor((response.total * 100))/100).toFixed(2)));
                                            $.cto_petty_cash.load_line_contents();
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
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#pettyCashTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _total  = $(this).closest('tr').attr('data-row-total');
            var _url    = _baseUrl + 'treasury/petty-cash/disbursement/send/for-approval/' + _id;
            
            if (_status == 'draft' && parseFloat(_total) > 0) {
                _self.prop('disabled', true);
                Swal.fire({
                    html: "Are you sure? <br/>the petty cash with <strong>Control No<br/>("+ _code +")</strong> will be sent.",
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
                                            $.cto_petty_cash.load_contents();
                                            // $.requisition.notify(_id);
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
                if (parseFloat(_total) <= 0) { 
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
        | # when print btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#pettyCashTable .print-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _voucher = _self.closest('tr').attr('data-row-voucher');
            var _control = _self.closest('tr').attr('data-row-code');
            var _url = _baseUrl + 'treasury/petty-cash/disbursement/print/' + _voucher + '?type=cash&reference_no=' + _control;
            window.open(_url, '_blank'); 
        });
    }

    //init cto_petty_cash
    $.cto_petty_cash = new cto_petty_cash, $.cto_petty_cash.Constructor = cto_petty_cash

}(window.jQuery),

//initializing cto_petty_cash
function($) {
    "use strict";
    $.cto_petty_cash.required_fields();
    $.cto_petty_cash.init();
}(window.jQuery);