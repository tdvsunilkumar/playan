!function($) {
    "use strict";

    var cbo_ppmp = function() {
        this.$body = $("body");
    };

    var _ppmpID = 0; var _table; var _page = 0; var _status = 'all', _YrList = '', _Yr = 'all';

    cbo_ppmp.prototype.required_fields = function() {
        
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

    cbo_ppmp.prototype.load_years = function()
    {   
        _YrList = '<select name="ppmp_year" class="form-select form-select-sm ms-1">';
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'finance/procurement-plan/year-lists',
            success: function(response) {
                console.log(response.data);
                $.each(response.data, function(i, item) {
                    _YrList += '<option value="' + item.budget_year + '"> ' + item.budget_year + '</option>';  
                }); 
                _YrList += '<option value="all">All</option>';  
                _YrList += '</select>';
            },
            async: false
        });
    },

    cbo_ppmp.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#ppmpTable', {
            ajax: { 
                url : _baseUrl + 'finance/procurement-plan/lists?status=' + encodeURIComponent(_status) + '&year=' + _Yr,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.cbo_ppmp.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.cbo_ppmp.hideTooltip();
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
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-position', data.order);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Year: ' + _YrList + '</label><label class="d-inline-flex ms-3 line-30">Status:<select name="ppmp_status" aria-controls="ppmp_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="locked">Locked</option><option value="all">All</option></label>');           
                $('select[name="ppmp_status"]').val(_status);
                $('select[name="ppmp_year"]').val(_Yr);
            }, 
            columns: [
                { data: 'id' },
                { data: 'control_no_label' },
                { data: 'budget_year' },
                { data: 'department' },
                { data: 'funds' },
                // { data: 'remarks' },
                { data: 'total' },
                { data: 'modified' },
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
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                // {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    cbo_ppmp.prototype.fetchID = function()
    {
        return _ppmpID;
    }

    cbo_ppmp.prototype.updateID = function(_id)
    {
        return _ppmpID = _id;
    }

    cbo_ppmp.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    cbo_ppmp.prototype.preload_select3 = function()
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

    cbo_ppmp.prototype.perfect_scrollbar = function()
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
    cbo_ppmp.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    cbo_ppmp.prototype.preload_yearpicker = function()
    {
        if ( $('#budget_year') ) {
            $('#budget_year').datepicker({
                minViewMode: 2,
                format: 'yyyy',
                autoclose: true,
                clearBtn: false
            }).on('changeDate', function(e) {
                var _self = $(this);
            });
        }
        if ( $('#budget_year2') ) {
            $('#budget_year2').datepicker({
                minViewMode: 2,
                format: 'yyyy',
                autoclose: true,
                clearBtn: false
            }).on('changeDate', function(e) {
                var _self = $(this);
            });
        }
    },

    cbo_ppmp.prototype.getLastSegment = function()
    {
        const parts = window.location.href.split('/');
        if (parts.slice(-2)[0] == 'project-procurement-management-plan') {
            return parts.slice(-1)[0];
        }
        return parts.slice(-2)[0];
    }

    cbo_ppmp.prototype.getIdentity = function ()
    {   
        _ppmpID = 0;
        var _card = $('#ppmp-card');
        console.log(_baseUrl + 'finance/procurement-plan/get-identity');
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/get-identity',
            success: function(response) {
                console.log(response);
                _ppmpID = response.identity;
            },
            async: false
        });
        return _ppmpID;
    },

    cbo_ppmp.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    cbo_ppmp.prototype.validate_ppmp = function()
    {   
        var _validate = 0;
        $.each(this.$body.find("#ppmp-details-card .tab-pane"), function() {
            var _layer = $(this);
            var _budget1 = _layer.find('div.total-gl-budget').attr('data-row-total-gl-budget');
            var _budget2 = _layer.find('tfoot td.total-budget').attr('data-row-total-budget');

            if (parseFloat(_budget1) < parseFloat(_budget2)) {
                _validate = 1;
                return false; return false;
            }
        });ppmp_status

        return _validate;
    },

    cbo_ppmp.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/procurement-plan/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    cbo_ppmp.prototype.fetch_remarks = function (_id)
    {   
        var _remarks = '';
        console.log(_baseUrl + 'finance/procurement-plan/fetch-remarks/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/fetch-remarks/' + _id,
            success: function(response) {
                console.log(response);
                _remarks = response.remarks;
            },
            async: false
        });
        return _remarks;
    },

    cbo_ppmp.prototype.validate_item_removal = function (_id) 
    {
        var _validate = false;
        console.log(_baseUrl + 'finance/procurement-plan/validate-item-removal/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/validate-item-removal/' + _id,
            success: function(response) {
                console.log(response);
                _validate = response.validate;
            },
            async: false
        });
        return _validate;
    }

    cbo_ppmp.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        if($.cbo_ppmp.getLastSegment() == 'manage' || $.cbo_ppmp.getLastSegment() == 'add' || $.cbo_ppmp.getLastSegment() == 'edit') {
            $.cbo_ppmp.getIdentity();
        }
        $.cbo_ppmp.preload_select3();
        $.cbo_ppmp.load_contents();
        $.cbo_ppmp.perfect_scrollbar();
        $.cbo_ppmp.preload_yearpicker();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.cbo_ppmp.hideTooltip();
        });
        $('[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.cbo_ppmp.preload_select3();
            $.cbo_ppmp.perfect_scrollbar();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal = $('#ppmp-modal');
                _modal.modal('show');
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="ppmp_status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.cbo_ppmp.load_contents();
        });
        this.$body.on('change', 'select[name="ppmp_year"]', function (e) {
            var _self = $(this);
            _Yr = _self.val();
            $.cbo_ppmp.load_contents();
        });

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.cbo_ppmp.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#ppmp-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Project Procurement Management Plan');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.cbo_ppmp.load_contents();
            $.cbo_ppmp.hideTooltip();
            _ppmpID = 0;
        });

        /*
        | ---------------------------------
        | # when copy modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#ppmp2-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Copy Project Procurement Management Plan');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.cbo_ppmp.load_contents();
            $.cbo_ppmp.hideTooltip();
            _ppmpID = 0;
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#group-menu-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmp-lock-btn', function (e) {
            var _self    = $(this);
            var _dep     = $('#division_id option:selected').text();
            var _year    = $('#budget_year').val();
            var _id      = $.cbo_ppmp.fetchID();
            var _url     = _baseUrl + 'finance/procurement-plan/lock-division/' + _id + '?division=' + $('#division_id').val();
            var d1       = $.cbo_ppmpForm.fetch_status(_id);
            var d2       = $.cbo_ppmpForm.fetch_division_status(_id, $('#division_id').val());
            var d3       = $.cbo_ppmp.validate_ppmp();
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            {  
                if (v1 != 'draft' || v2 != 'draft') {
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
                } else if (v3 > 0) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>There are some GL that is over the budget proposal.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;     
                } else {
                    console.log(_url);
                    _self.prop('disabled', true);
                    Swal.fire({
                        html:  "Are you sure? <br/>the PPMP budget year (" + _year + ")<br/>[ " + _dep + " ]</strong><br/>will be locked.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, lock it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn bg-secondary text-white", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: _url,
                                success: function(response) {
                                    console.log(response);
                                    _self.prop('disabled', false);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn bg-secondary text-white" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            _self.removeClass('active');
                                            $.each($('.tab-pane .table'), function(){
                                                var _table = $(this);
                                                _table.find('tbody tr').addClass('active');
                                            });
                                            $('#ppmp-details-card input, #ppmp-details-card select').prop('disabled', true);                                            
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss, _self.prop('disabled', false) 
                    });
                }
            });
        }); 

        /*
        | ---------------------------------
        | # when remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmp-table .remove-btn', function (e) {
            var _table  = $(this).closest('table');
            var _parent = $(this).closest('tr');
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').find('select option:selected').text();
            var _url    = _baseUrl + 'finance/procurement-plan/remove-lines/' + _id;
            var d1      = $.cbo_ppmpForm.fetch_status(_ppmpID);
            var d2      = $.cbo_ppmp.validate_item_removal(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft') {
                    if (v2 == true) {
                        console.log(_url);
                        Swal.fire({
                            html: "Are you sure? <br/>the item ("+ _code +") will be removed.",
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
                                                _parent.remove();
                                                $.cbo_ppmpForm.fetch_total_budget(_table);
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
                            html: "Unable to proceed!<br/>The item is already been processed.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;   
                    }
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
        this.$body.on('click', '#ppmpTable .view-disapprove-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#disapprove-modal');

            _self.prop('disabled', true);
            var d1 = $.cbo_ppmp.fetch_status(_id);
            var d2 = $.cbo_ppmp.fetch_remarks(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'cancelled') {
                    _ppmpID = _id;
                    _self.prop('disabled', false);
                    _modal.find('h5').text('PPMP Disapproval (' + $.trim(_code) + ')');
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
        | # when copy button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.copy-btn', function (e) {
            var _self  = $(this);
            var _modal = $('#ppmp2-modal');
            var _id    = _self.closest('tr').attr('data-row-id');
            var _code  = _self.closest('tr').attr('data-row-code');
            var _url   = _baseUrl + 'finance/procurement-plan/find/' + _id;
            _ppmpID = _id;
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
                    _self.prop('disabled', false).html('<i class="flaticon-layers text-white"></i>');
                    _modal.find('h5').text('Copy Project Procurement Management Plan (' + $.trim(_code) + ')');
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
        | # when unlock button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmpTable .unlock-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'finance/procurement-plan/unlock/' + _id;

            console.log(_url);
            Swal.fire({
                html: "Are you sure? <br/>the APP with control no ("+ _code +") will be unlocked.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, unlock it!" ,
                cancelButtonText: "No, return",
                customClass: { confirmButton: "btn draft-bg text-white", cancelButton: "btn btn-active-light" },
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
                                    $.cbo_ppmp.load_contents();
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
        | # when unlock button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmpTable .lock-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'finance/procurement-plan/lock/' + _id;

            console.log(_url);
            Swal.fire({
                html: "Are you sure? <br/>the APP with control no ("+ _code +") will be locked.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, lock it!" ,
                cancelButtonText: "No, return",
                customClass: { confirmButton: "btn bg-info text-white", cancelButton: "btn btn-active-light" },
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
                                    $.cbo_ppmp.load_contents();
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
        | # when unlock button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmpTable .edit-btn, #ppmpTable .view-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'finance/procurement-plan/manage/' + _id;
            window.location.href = _url;
        });

        /*
        | ---------------------------------
        | # when budget onChange
        | ---------------------------------
        */
        this.$body.on('change', '#budget_gl_account_id', function (e) {
            e.preventDefault();
            var _self = $(this);
            $('.tab-content .tab-pane').removeClass('show active');
            $('#tab-' + _self.val()).addClass('show active');
        });
    }

    //init cbo_ppmp
    $.cbo_ppmp = new cbo_ppmp, $.cbo_ppmp.Constructor = cbo_ppmp

}(window.jQuery),

//initializing cbo_ppmp
function($) {
    "use strict";
    $.cbo_ppmp.load_years();
    $.cbo_ppmp.required_fields();
    $.cbo_ppmp.init();
}(window.jQuery);