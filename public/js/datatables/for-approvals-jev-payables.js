!function($) {
    "use strict";

    var for_approval_jev_payable = function() {
        this.$body = $("body");
    };

    var _documentID = 0; var _table; var _prTable; var _projectName = ''; var _supplierStatus = ''; var _supplierID = 0; var _page = 0;
    var _voucherSegment = 'payables'; var _status = 'all';

    for_approval_jev_payable.prototype.validate = function($form, $required)
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

    for_approval_jev_payable.prototype.required_fields = function() {
        
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

    for_approval_jev_payable.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#jevPayableTable', {
            ajax: { 
                url : _baseUrl + 'for-approvals/journal-entries/payables/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.for_approval_jev_payable.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.for_approval_jev_payable.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-type', data.document.toLowerCase());
                $(row).attr('data-row-code', data.voucher_no);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="for approval">Pending</option><option value="approved">Approved</option><option value="disapproved">Disapproved</option><option value="all">All</option></select></label>');           
                $('select[name="status"]').val(_status);
            },     
            columns: [
                { data: 'checkbox' },
                { data: 'voucher_no_label' },
                { data: 'document_label' },
                { data: 'prepared_by' },
                { data: 'approved_by' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start w-25' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
            ]
        } );

        return true;
    },

    for_approval_jev_payable.prototype.reload_available_control_no = function()
    {   
        var _controlNo = $('#rfq_id'); _controlNo.find('option').remove(); 
        console.log(_baseUrl + 'general-services/purchase-orders/reload-available-control-no/' + _documentID);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-orders/reload-available-control-no/' + _documentID,
            success: function(response) {
                console.log(response.data);
                _controlNo.append('<option value="">select a control no</option>');  
                $.each(response.data, function(i, item) {
                    _controlNo.append('<option value="' + item.id + '">' + item.control_no + '</option>');  
                }); 
                // $.general_ledger.preload_select3();
            },
            async: false
        });
        return true;
    },

    for_approval_jev_payable.prototype.fetchID = function()
    {
        return _documentID;
    }

    for_approval_jev_payable.prototype.updateID = function(_id)
    {
        return _documentID = _id;
    }

    for_approval_jev_payable.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'for-approvals/journal-entries/payables/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/journal-entries/payables/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    for_approval_jev_payable.prototype.validate_approver = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'for-approvals/journal-entries/payables/validate-approver/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/journal-entries/payables/validate-approver/' + _id,
            success: function(response) {
                console.log(response);
                _status = response;
            },
            async: false
        });
        return _status;
    },

    for_approval_jev_payable.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    for_approval_jev_payable.prototype.preload_select3 = function()
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

    for_approval_jev_payable.prototype.perfect_scrollbar = function()
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

    for_approval_jev_payable.prototype.validate_table = function($table)
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
    for_approval_jev_payable.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_jev_payable.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'for-approvals/journal-entries/payables/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'for-approvals/journal-entries/payables/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    for_approval_jev_payable.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    for_approval_jev_payable.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    for_approval_jev_payable.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.for_approval_jev_payable.preload_select3();
        $.for_approval_jev_payable.load_contents();
        $.for_approval_jev_payable.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.for_approval_resolution.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Request For Quotation Approval');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('label[for="control_no"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('label[for="total_budget"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.for_approval_jev_payable.load_contents();
            $.for_approval_jev_payable.hideTooltip();
            _documentID = 0;
        });
        this.$body.on('shown.bs.modal', '#rfq-modal', function (e) {
            $.for_approval_jev_payable.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when disapprove modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#disapprove-modal', function (e) {
            var _modal = $(this);
            _modal.find('textarea').val('');
            _modal.find('button').prop('disabled', false);
            $.for_approval_jev_payable.hideTooltip();
            _documentID = 0;
        });
        this.$body.on('shown.bs.modal', '#disapprove-modal', function (e) {
            $.for_approval_jev_payable.hideTooltip();
        });
        
        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.for_approval_jev_payable.load_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#jevPayableTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $('#rfq-modal');
            var _url    = _baseUrl + 'for-approvals/journal-entries/payables/edit/' + _id;
            console.log(_url);
            _documentID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    _modal.find('label[for="control_no"].text-danger').text(response.data.control_no);
                    _projectName = response.data.project_name;
                    if (response.data.total_budget) {
                        _modal.find('label[for="total_budget"].text-danger').text('₱' + $.for_approval_jev_payable.price_separator(parseFloat(response.data.total_budget).toFixed(2)));
                    }
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    $.for_approval_jev_payable.load_pr_contents();
                    $.for_approval_jev_payable.load_supplier_contents();
                    $.for_approval_jev_payable.load_item_contents();
                    if(_status == 'draft') {
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    } else {
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('View Request For Quotation (<span class="variables">' + _code + '</span>)');
                    if (_status != 'draft') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.send-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
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
        | # when approve button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#jevPayableTable .approve-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#purchase-order-modal');
            var _controlNo = _modal.find('#rfq_id'); _controlNo.find('option').remove(); 
            var _url    = _baseUrl + 'for-approvals/journal-entries/payables/approve/' + _id;

            var d1 = $.for_approval_jev_payable.fetch_status(_id);
            var d2 = $.for_approval_jev_payable.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html: "Are you sure? <br/>the request with <strong>Voucher No<br/>("+ _code +")</strong> will be approved.",
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
                                            $.for_approval_jev_payable.load_contents();
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
        this.$body.on('click', '#jevPayableTable .disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            var d1 = $.for_approval_jev_payable.fetch_status(_id);
            var d2 = $.for_approval_jev_payable.validate_approver(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'for approval' && v2 == false) {
                    _documentID = _id;
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
        | # when disapprove submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-disapprove-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.code').text();
            var _form   = _self.closest('form');
            var _url = _form.attr('action') + '/disapprove/' + _documentID;
            var _error  = $.for_approval_jev_payable.validate(_form, 0);

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
                    html: "Are you sure? <br/>the request with <strong>Voucher No<br/>(" + _code + ")</strong> will be disapproved.",
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
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        e.isConfirmed && ((t.disabled = !1));
                                        _self.html('Submit').prop('disabled', false);
                                        _modal.modal('hide');
                                        $.for_approval_jev_payable.load_contents();
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
        | # when show disapprove remarks button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#jevPayableTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.for_approval_jev_payable.fetch_status(_id);
            var d2 = $.for_approval_jev_payable.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {   
                if (v1 == 'disapproved') {
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
        | # when supplier view button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#jevPayableTable .view-btn', function (e) {
            var _self     = $(this);
            var _type = _self.closest('tr').attr('data-row-type');
            var _voucher  = _self.closest('tr').attr('data-row-code');
            var _url      = _baseUrl + 'for-approvals/journal-entries/' + _voucherSegment + '/print/' + _voucher + '?type=' + _type;
            window.open(_url, '_blank');
        }); 
    }

    //init for_approval_jev_payable
    $.for_approval_jev_payable = new for_approval_jev_payable, $.for_approval_jev_payable.Constructor = for_approval_jev_payable

}(window.jQuery),

//initializing for_approval_jev_payable
function($) {
    "use strict";
    $.for_approval_jev_payable.required_fields();
    $.for_approval_jev_payable.init();
}(window.jQuery);