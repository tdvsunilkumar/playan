!function($) {
    "use strict";

    var for_approval_rental_application = function() {
        this.$body = $("body");
    };

    var _ecoRentalID = 0; var _table; var _page = 0; var _status = 'all';

    for_approval_rental_application.prototype.validate = function($form, $required)
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

    for_approval_rental_application.prototype.required_fields = function() {
        
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

    for_approval_rental_application.prototype.load_contents = function(_page = 0) 
    {   
        console.log(_baseUrl + 'for-approvals/economic-and-investment/rental-application/lists'); 
        _table = new DataTable('#ecoRentalTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/economic-and-investment/rental-application/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.for_approval_rental_application.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_rental_application.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="ap_status" aria-controls="ap_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="for approval">Pending</option><option value="posted">Posted</option><option value="cancelled">Cancelled</option><option value="all">All</option></select></label>');           
                $('select[name="ap_status"]').val(_status);
            },  
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.transaction_no);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'transaction_no_label' },
                { data: 'reference_no' },
                { data: 'requestor' },
                { data: 'address' },
                { data: 'total'},
                { data: 'or_no_label' },
                { data: 'approved_by'},
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' }
            ]
        } );

        return true;
    },

    for_approval_rental_application.prototype.validate_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/economic-and-investment/rental-application/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/economic-and-investment/rental-application/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_rental_application.prototype.fetchID = function()
    {
        return _ecoRentalID;
    }

    for_approval_rental_application.prototype.updateID = function(_id)
    {
        return _ecoRentalID = _id;
    }

    for_approval_rental_application.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/economic-and-investment/rental-application/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/economic-and-investment/rental-application/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_rental_application.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/economic-and-investment/rental-application/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/economic-and-investment/rental-application/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    for_approval_rental_application.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_rental_application.prototype.preload_select3 = function()
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

    for_approval_rental_application.prototype.perfect_scrollbar = function()
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
    for_approval_rental_application.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_rental_application.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    for_approval_rental_application.prototype.reload_reception_class = function(_id, _modal, _location, _reception)
    {   
        var _lot = _modal.find('select[name="reception_class_id"]');  _lot.find('option').remove(); 
        var _url = _baseUrl + 'for-approvals/economic-and-investment/rental-application/reload-reception-class/'+ _id +'?location=' + _location + '&reception=' + _reception;
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
    
    for_approval_rental_application.prototype.reload_reception_name = function(_modal, _location)
    {   
        var _cementery = _modal.find('select[name="reception_id"]');  _cementery.find('option').remove(); 
        var _url = _baseUrl + 'for-approvals/economic-and-investment/rental-application/reload-reception-name?location=' + _location;
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

    for_approval_rental_application.prototype.fetch_multiplier_amount = function(_modal, _location, _reception, _receptionClass)
    {
        var _url = _baseUrl + 'for-approvals/economic-and-investment/rental-application/fetch-multiplier-amount?location=' + _location + '&reception=' + _reception + '&reception_class=' + _receptionClass;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                console.log('multiplier: ' + response.data.multiplier);
                console.log('multiplier amount: ' + response.data.multiplier_amount);
                console.log('excess: ' + response.data.excess);                
                console.log('excess amount: ' + response.data.excess_amount);
            },
            async: false
        });

        return true;
    },

    for_approval_rental_application.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_rental_application.preload_select3();
        $.for_approval_rental_application.load_contents();
        $.for_approval_rental_application.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.for_approval_rental_application.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#cemetery-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Rental Application');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.for_approval_rental_application.load_contents();
            _ecoRentalID = 0;
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
            _ecoRentalID = 0;
        });

        /*
        | ---------------------------------
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ecoRentalTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#cemetery-modal');
            var _controlNo = _modal.find('#rfq_id'); _controlNo.find('option').remove(); 
            var _url    = _baseUrl + 'for-approvals/economic-and-investment/rental-application/approve/' + _id;

            var d1 = $.for_approval_rental_application.fetch_status(_id);
            var d2 = $.for_approval_rental_application.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the request with <strong>Transaction No.<br/>("+ _code +")</strong> will be approved.",
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
                                            $.for_approval_rental_application.load_contents();
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
            var _code   = _modal.find('span.code').text();
            var _form   = _self.closest('form');
            var _url = _form.attr('action') + '/disapprove/' + _ecoRentalID;
            var _error  = $.for_approval_rental_application.validate(_form, 0);

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
                    html: "Are you sure? <br/>the request with <strong>Transaction No.<br/>(" + _code + ")</strong> will be disapproved.",
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
                                        $.for_approval_rental_application.load_contents();
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
        this.$body.on('click', '#ecoRentalTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#rental-modal');
            var _url    = _baseUrl + 'for-approvals/economic-and-investment/rental-application/view/' + _id;
            console.log(_url);
            _ecoRentalID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.for_approval_rental_application.reload_reception_name(_modal, response.data.location_id);
                    var d2 = $.for_approval_rental_application.reload_reception_class(_id, _modal, response.data.location_id, response.data.reception_id);
                    var d3 = $.for_approval_rental_application.fetch_multiplier_amount(_modal, response.data.location_id, response.data.reception_id, response.data.reception_class_text);
                    $.when( d1, d2 ).done(function (v1, v2) { 
                        $.each(response.data, function (k, v) {
                            _modal.find('input:not([type="radio"])[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            if (k == 'is_free') {
                                if (v > 0) {
                                    _modal.find('input[type="radio"][name="is_free"][value="1"]').prop('checked', true);
                                } else {
                                    _modal.find('input[type="radio"][name="is_free"][value="0"]').prop('checked', true);
                                }
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ecoRentalTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_rental_application.fetch_status(_id);
            var d2 = $.for_approval_rental_application.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _ecoRentalID = _id;
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
        this.$body.on('click', '#ecoRentalTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_rental_application.fetch_status(_id);
            var d2 = $.for_approval_rental_application.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _ecoRentalID = _id;
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
    }

    //init for_approval_rental_application
    $.for_approval_rental_application = new for_approval_rental_application, $.for_approval_rental_application.Constructor = for_approval_rental_application

}(window.jQuery),

//initializing for_approval_rental_application
function($) {
    "use strict";
    $.for_approval_rental_application.required_fields();
    $.for_approval_rental_application.init();
}(window.jQuery);