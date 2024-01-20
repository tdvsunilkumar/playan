!function($) {
    "use strict";

    var accountReceivable = function() {
        this.$body = $("body");
    };

    var _accountReceivableID = 0; var _table; var _page = 0; var _status = '0';

    accountReceivable.prototype.required_fields = function() {
        
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

    accountReceivable.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#accountReceivableTable', {
            ajax: { 
                url : _baseUrl + 'treasury/account-receivables/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    console.log(response.responseJSON);
                    $.accountReceivable.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.accountReceivable.hideTooltip();
                    $('.total-amount-due').text((response.responseJSON.total_due > 0) ? '₱' + $.accountReceivable.price_separator($.accountReceivable.money_format(response.responseJSON.total_due)) : '');
                    $('.total-amount-paid').text((response.responseJSON.total_pay > 0) ? '₱' + $.accountReceivable.price_separator($.accountReceivable.money_format(response.responseJSON.total_pay)) : '');
                    $('.total-amount-balance').text((response.responseJSON.total_balance > 0) ? '₱' + $.accountReceivable.price_separator($.accountReceivable.money_format(response.responseJSON.total_balance)) : '');
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[6, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="0">Unpaid</option><option value="2">Partial</option><option value="1">Paid</option><option value="all">All</option></select></label>');           
                $('select[name="status"]').val(_status);
            },      
            columns: [
                { data: 'checkbox' },
                { data: 'gl_account' },
                { data: 'items' },
                { data: 'amount_due' },
                { data: 'amount_paid' },
                { data: 'balance' },
                { data: 'due_date' },
                { data: 'status_label' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start w-25' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-end' },
                {  orderable: true, targets: 4, className: 'text-end ' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: false, targets: 7, className: 'text-center' }
            ]
        } );

        return true;
    },

    accountReceivable.prototype.fetchID = function()
    {
        return _accountReceivableID;
    }

    accountReceivable.prototype.updateID = function(_id)
    {
        return _accountReceivableID = _id;
    }

    accountReceivable.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    accountReceivable.prototype.preload_select3 = function()
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

    accountReceivable.prototype.perfect_scrollbar = function()
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

    accountReceivable.prototype.money_format = function(_money)
    {   
        return parseFloat(Math.floor((_money * 100))/100).toFixed(2);
    },

    accountReceivable.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    accountReceivable.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    accountReceivable.prototype.getDate = function()
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

    accountReceivable.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.accountReceivable.preload_select3();
        $.accountReceivable.load_contents();
        $.accountReceivable.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.accountReceivable.hideTooltip();
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
            _modal.find('button.submit-btn').removeClass('hidden');
            $.accountReceivable.preload_select3();
            $.accountReceivable.hideTooltip();
            $.accountReceivable.load_contents(_table.page());
            _accountReceivableID = 0;
        });
        this.$body.on('shown.bs.modal', '#account-payable-modal', function (e) {
            $.accountReceivable.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.accountReceivable.load_contents();
        });

        /*
        | ---------------------------------
        | # when export button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'a[data-bs-original-title="Download Receivables"]', function (e) {
            e.preventDefault();
            var _keywords = $('#accountReceivableTable_filter input[type="search"]').val();
            var _status = $('#accountReceivableTable_wrapper select[name="status"]').val();
            var _url    = _baseUrl + 'treasury/account-receivables/export?keywords=' + _keywords + '&status=' + _status;
            window.open(_url, '_blank');
        }); 
    }

    //init accountReceivable
    $.accountReceivable = new accountReceivable, $.accountReceivable.Constructor = accountReceivable

}(window.jQuery),

//initializing accountReceivable
function($) {
    "use strict";
    $.accountReceivable.required_fields();
    $.accountReceivable.init();
}(window.jQuery);