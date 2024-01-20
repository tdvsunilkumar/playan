!function($) {
    "use strict";

    var econCemetery = function() {
        this.$body = $("body");
    };

    var _econCemeteryID = 0; var _table; var _page = 0; var _status = 'all';

    econCemetery.prototype.required_fields = function() {
        $('span.ms-1.text-danger').remove();
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                $(this).find('.m-form__help').text('');  
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    econCemetery.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#econCemeteryTable', {
            ajax: { 
                url : _baseUrl + 'economic-and-investment/cemetery-application/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.econCemetery.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.econCemetery.hideTooltip();
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
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="for approval">Pending</option><option value="requested">Requested</option><option value="partial">Partial</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option><option value="all">All</option></select></label>');           
                $('select[name="status"]').val(_status);
            },      
            columns: [
                { data: 'transaction_no_label' },
                { data: 'reference_no' },
                { data: 'requestor' },
                { data: 'address' },
                { data: 'total'},
                { data: 'total_remaining'},
                { data: 'or_no_label' },
                { data: 'modified'},
                { data: 'status_label' },
                { data: 'actions' }
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
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    econCemetery.prototype.load_payment_contents = function(_page = 0) 
    {   
        console.log(_baseUrl + 'economic-and-investment/cemetery-application/payment-lists/' + _econCemeteryID);
        _table = new DataTable('#econCemeteryPaymentTable', {
            ajax: { 
                url : _baseUrl + 'economic-and-investment/cemetery-application/payment-lists/' + _econCemeteryID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.econCemetery.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.econCemetery.hideTooltip();
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
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
            },      
            columns: [
                { data: 'id' },
                { data: 'or_date' },
                { data: 'or_no' },
                { data: 'total_amount' },
                { data: 'paid_amount'},
                { data: 'remaining_balance'},
                { data: 'status_label' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-end' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 6, className: 'text-center' }
            ]
        } );

        return true;
    },

    econCemetery.prototype.fetchID = function()
    {
        return _econCemeteryID;
    }

    econCemetery.prototype.updateID = function(_id)
    {
        return _econCemeteryID = _id;
    }

    econCemetery.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    econCemetery.prototype.preload_select3 = function()
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

    econCemetery.prototype.perfect_scrollbar = function()
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

    econCemetery.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    econCemetery.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    econCemetery.prototype.getDate = function()
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

    econCemetery.prototype.reload_cemetery_name = function(_modal, _location)
    {   
        var _cementery = _modal.find('select[name="cemetery_id"]');  _cementery.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/cemetery-application/reload-cemetery-name?location=' + _location;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _cementery.append('<option value="">select a cemetery</option>');  
                $.each(response.data, function(i, item) {
                    _cementery.append('<option value="' + item.id + '"> ' + item.cem_name + ' LOT</option>');  
                }); 
            },
            async: false
        });
    },

    econCemetery.prototype.reload_cemetery_lot = function(_id, _modal, _location, _cemetery, _style)
    {   
        var _lot = _modal.find('select[name="cemetery_lot_id"]');  _lot.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/cemetery-application/reload-cemetery-lot/'+ _id +'?location=' + _location + '&cemetery=' + _cemetery + '&style=' + _style;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _lot.append('<option value="">select a cemetery lot</option>');  
                $.each(response.data, function(i, item) {
                    _lot.append('<option value="' + item.id + '"> ' + item.ecl_lot + ' LOT</option>');  
                }); 
            },
            async: false
        });
    },

    econCemetery.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'economic-and-investment/cemetery-application/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/cemetery-application/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    econCemetery.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'economic-and-investment/cemetery-application/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/cemetery-application/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    econCemetery.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.econCemetery.preload_select3();
        $.econCemetery.load_contents();
        $.econCemetery.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.econCemetery.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#cemetery-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Cemetery Application');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            _modal.find('select[name="terms"]').removeClass('required').closest('.form-group').removeClass('required');
            _modal.find('.terms').addClass('hidden');
            _modal.find('button.term-btn').addClass('hidden');
            $.econCemetery.required_fields();
            $.econCemetery.preload_select3();
            $.econCemetery.hideTooltip();
            $.econCemetery.load_contents(_table.page());
            _econCemeteryID = 0;
        });
        this.$body.on('shown.bs.modal', '#cemetery-modal', function (e) {
            $.econCemetery.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.econCemetery.load_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#cemetery-modal');
                _modal.find('input[name="transaction_date"]').val($.econCemetery.getDate());
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#econCemeteryTable .edit-btn, #econCemeteryTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#cemetery-modal');
            var _url    = _baseUrl + 'economic-and-investment/cemetery-application/edit/' + _id;
            console.log(_url);
            _econCemeteryID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.econCemetery.reload_cemetery_name(_modal, response.data.location_id);
                    var d2 = $.econCemetery.reload_cemetery_lot(_id, _modal, response.data.location_id, response.data.cemetery_id, response.data.cemetery_style_id);
                    $.when( d1, d2 ).done(function (v1, v2) { 
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                    });
                    if (response.data.status != 'draft') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').addClass('hidden');
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                    }
                    if (response.data.downpayment != null) {
                        _modal.find('.terms').removeClass('hidden');
                        _modal.find('select[name="terms"]').addClass('required').closest('.form-group').addClass('required');
                        $.econCemetery.required_fields();
                        if (response.data.terms == null) {
                            _modal.find('select[name="terms"]').prop('disabled', false);
                            _modal.find('.term-btn').removeClass('hidden');
                        } 
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Cemetery Application (<span class="variables">' + _code + '</span>)');
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
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#econCemeteryTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'economic-and-investment/cemetery-application/send/for-approval/' + _id;
            console.log(_url);
            
            if (_status == 'draft') {
                _self.prop('disabled', true);
                Swal.fire({
                    html: "Are you sure? <br/>the request with <strong>Transaction No<br/>("+ _code +")</strong> will be sent.",
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
                                            $.econCemetery.load_contents();
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
        | # when show disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#econCemeteryTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.econCemetery.fetch_status(_id);
            var d2 = $.econCemetery.fetch_remarks(_id);
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
        | # when print btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#econCemeteryTable .print-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _trans = _self.closest('tr').attr('data-row-code');
            var _url = _baseUrl + 'digital-sign?url='+'economic-and-investment/cemetery-application/print/' + _trans;
            window.open(_url, '_blank'); 
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#econCemeteryTable .view-btn2', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#summary-modal');
            _econCemeteryID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.econCemetery.load_payment_contents();
            $.when( d1 ).done(function ( v1 ) { 
                _modal.find('.m-form__help').text('');
                _modal.find('.modal-header h5').html('Cemetery Application Payment Summary (<span class="variables">' + _code + '</span>)');
                _self.prop('disabled', false).attr('title', 'view summary').html('<i class="la la-file-text text-white"></i>');
                _modal.modal('show');
            });
        }); 
    }

    //init accountPayable
    $.econCemetery = new econCemetery, $.econCemetery.Constructor = econCemetery

}(window.jQuery),

//initializing accountPayable
function($) {
    "use strict";
    $.econCemetery.required_fields();
    $.econCemetery.init();
}(window.jQuery);