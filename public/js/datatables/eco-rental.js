!function($) {
    "use strict";

    var ecoRental = function() {
        this.$body = $("body");
    };

    var _ecoRentalID = 0; var _table; var _page = 0; var _status = 'all';

    ecoRental.prototype.required_fields = function() {
        
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

    ecoRental.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#ecoRentalTable', {
            ajax: { 
                url : _baseUrl + 'economic-and-investment/rental-application/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.ecoRental.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.ecoRental.hideTooltip();
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
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    ecoRental.prototype.fetchID = function()
    {
        return _ecoRentalID;
    }

    ecoRental.prototype.updateID = function(_id)
    {
        return _ecoRentalID = _id;
    }

    ecoRental.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    ecoRental.prototype.preload_select3 = function()
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

    ecoRental.prototype.perfect_scrollbar = function()
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

    ecoRental.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    ecoRental.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    ecoRental.prototype.getDate = function()
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

    ecoRental.prototype.reload_reception_class = function(_id, _modal, _location, _reception)
    {   
        var _lot = _modal.find('select[name="reception_class_id"]');  _lot.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/rental-application/reload-reception-class/'+ _id +'?location=' + _location + '&reception=' + _reception;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _lot.append('<option value="">select a reception class</option>');  
                $.each(response.data, function(i, item) {
                    _lot.append('<option value="' + item.id + '"> ' + item.eatd_process_type + '</option>');  
                }); 
            },
            async: false
        });
    },
    
    ecoRental.prototype.reload_reception_name = function(_modal, _location)
    {   
        var _cementery = _modal.find('select[name="reception_id"]');  _cementery.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/rental-application/reload-reception-name?location=' + _location;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _cementery.append('<option value="">select a reception</option>');  
                $.each(response.data, function(i, item) {
                    _cementery.append('<option value="' + item.id + '"> ' + item.reception_name + '</option>');  
                }); 
            },
            async: false
        });
    },

    ecoRental.prototype.fetch_multiplier_amount = function(_modal, _location, _reception, _receptionClass)
    {
        var _url = _baseUrl + 'economic-and-investment/rental-application/fetch-multiplier-amount?location=' + _location + '&reception=' + _reception + '&reception_class=' + _receptionClass;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                
                $.ecoRentalForm.updateMultiplier(response.data.multiplier);
                console.log('multiplier: ' + response.data.multiplier);

                $.ecoRentalForm.updateMultiplierAmt(response.data.multiplier_amount);
                console.log('multiplier amount: ' + response.data.multiplier_amount);

                $.ecoRentalForm.updateExcess(response.data.excess);
                console.log('excess: ' + response.data.excess);
                
                $.ecoRentalForm.updateExcessAmt(response.data.excess_amount);
                console.log('excess amount: ' + response.data.excess_amount);
            },
            async: false
        });

        return true;
    },

    ecoRental.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'economic-and-investment/rental-application/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/rental-application/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    ecoRental.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'economic-and-investment/rental-application/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/rental-application/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    ecoRental.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.ecoRental.preload_select3();
        $.ecoRental.load_contents();
        $.ecoRental.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.ecoRental.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#rental-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Rental Application');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            $.ecoRental.preload_select3();
            $.ecoRental.hideTooltip();
            $.ecoRental.load_contents(_table.page());
            _ecoRentalID = 0;
        });
        this.$body.on('shown.bs.modal', '#rental-modal', function (e) {
            $.ecoRental.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.ecoRental.load_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#rental-modal');
                _modal.find('input[name="transaction_date"]').val($.ecoRental.getDate());
                _modal.find('input[type="radio"][name="is_free"][value="0"]').prop('checked', true);
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ecoRentalTable .edit-btn, #ecoRentalTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#rental-modal');
            var _url    = _baseUrl + 'economic-and-investment/rental-application/edit/' + _id;
            console.log(_url);
            _ecoRentalID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.ecoRental.reload_reception_name(_modal, response.data.location_id);
                    var d2 = $.ecoRental.reload_reception_class(_id, _modal, response.data.location_id, response.data.reception_id);
                    var d3 = $.ecoRental.fetch_multiplier_amount(_modal, response.data.location_id, response.data.reception_id, response.data.reception_class_text);
                    var d4 = $.ecoRentalForm.fetch_discount(response.data.discount_id);
                    $.when( d1, d2, d3, d4 ).done(function (v1, v2, v3, v4) { 
                        $.each(response.data, function (k, v) {
                            _modal.find('input:not([type="radio"])[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            if (k == 'is_free') {
                                if (v > 0) {
                                    _modal.find('input[type="radio"][name="is_free"][value="1"]').prop('checked', true);
                                    _modal.find('select[name="discount_id"].select3').val('').prop('disabled', true).trigger('change.select3'); 
                                } else {
                                    _modal.find('input[type="radio"][name="is_free"][value="0"]').prop('checked', true);
                                }
                            }
                            if (k == 'discount_id') { 
                                _modal.find('select[name="discount_id"].select3').val(v).prop('disabled', false).trigger('change.select3'); 
                            }
                        });
                    });
                    if (response.data.is_free > 0) {
                        _modal.find('input[name="total_amount"]').val(0);
                    }
                    if (response.data.status != 'draft') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').addClass('hidden');
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Rental Application (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#ecoRentalTable .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'economic-and-investment/rental-application/send/for-approval/' + _id;
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
                                            $.ecoRental.load_contents();
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
        this.$body.on('click', '#ecoRentalTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.ecoRental.fetch_status(_id);
            var d2 = $.ecoRental.fetch_remarks(_id);
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
        this.$body.on('click', '#ecoRentalTable .print-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _trans = _self.closest('tr').attr('data-row-code');
            var _url = _baseUrl + 'digital-sign?url='+'economic-and-investment/rental-application/print/' + _trans;
            window.open(_url, '_blank'); 
        });
    }

    //init accountPayable
    $.ecoRental = new ecoRental, $.ecoRental.Constructor = ecoRental

}(window.jQuery),

//initializing accountPayable
function($) {
    "use strict";
    $.ecoRental.required_fields();
    $.ecoRental.init();
}(window.jQuery);