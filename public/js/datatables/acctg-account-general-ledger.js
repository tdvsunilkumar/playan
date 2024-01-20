!function($) {
    "use strict";

    var general_ledger = function() {
        this.$body = $("body");
    };

    var _glAccountId = 0; var _slID = 0; var _currentID = 0; var _parentCode = ''; var _table; var _lineTable; var _lineTableLength = 0;
    var _currentPage = 0;

    general_ledger.prototype.required_fields = function() {
        
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

    general_ledger.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#generalLedgerAccountTable', {
            ajax: { 
                url : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.general_ledger.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'id' },
                { data: 'group' },
                { data: 'major' },
                { data: 'sub_major' },
                { data: 'code' },
                { data: 'description' },
                { data: 'normal_balance' },
                { data: 'with_sl' },
                // { data: 'mother_code' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
                {  orderable: false, targets: 7, className: 'text-center' },
                // {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' },
            ]
        } );

        return true;
    },

    general_ledger.prototype.load_line_contents = function(_keywords = '') 
    {   
        _lineTable = new DataTable('#subsidiaryTable', {
            ajax: { 
                url : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/subsidiary-lists/' + _glAccountId,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.general_ledger.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {  
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-hidden', data.hidden);
            },
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                // if(_lineTable.rows().data().length > 0) {
                //     $('#general-ledger-account-modal input[type="radio"]').prop('disabled', true);
                // } else {
                //     $('#general-ledger-account-modal input[type="radio"]').prop('disabled', false);
                // }
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-sl">ADD SL</button>');           
            }, 
            columns: [
                { data: 'prefix' },
                { data: 'code' },
                { data: 'description' },
                { data: 'is_parent' },
                { data: 'visibility' },
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
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    general_ledger.prototype.load_current_contents = function(_currentPage = 0) 
    {   
        _lineTable = new DataTable('#currentTable', {
            ajax: { 
                url : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/current-lists/' + _slID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.general_ledger.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {  
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.gl_account + ' - ' + data.sl_account);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'l<"toolbar-3">frtip',
            initComplete: function() {
                $("div.toolbar-3").html('<button type="button" class="btn btn-small bg-info" id="add-current">ADD CURRENT</button>');           
            }, 
            columns: [
                { data: 'funds' },
                { data: 'gl_account_label' },
                { data: 'sl_account_label' },
                { data: 'is_debit' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
            ]
        } );

        return true;
    },

    general_ledger.prototype.fetchCurrentID = function()
    {
        return _currentID;
    }

    general_ledger.prototype.updateCurrentID = function(_id)
    {
        return _currentID = _id;
    }

    general_ledger.prototype.fetchID = function()
    {
        return _glAccountId;
    }

    general_ledger.prototype.updateID = function(_id)
    {
        return _glAccountId = _id;
    }

    general_ledger.prototype.fetchParentCode = function()
    {
        return _parentCode;
    }

    general_ledger.prototype.updateParentCode = function(_code)
    {
        return _parentCode = _code;
    }

    general_ledger.prototype.fetchSLID = function()
    {
        return _slID;
    }

    general_ledger.prototype.updateSLID = function(_id)
    {
        return _slID = _id;
    }

    general_ledger.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    general_ledger.prototype.preload_select3 = function()
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

    // general_ledger.prototype.preload_select4 = function()
    // {
    //     if ( $('.select3') ) {
    //         $.each($('.select3'), function(){
    //             var _self = $(this);
    //             var _selfID = $(this).attr('id');
    //             var _parentID = 'parent_' + _selfID;
    //             _self.closest('.form-group').attr('id', _parentID);

    //             _self.select3({
    //                 allowClear: true,
    //                 dropdownAutoWidth : false,
    //                 dropdownParent: $('#' + _parentID),
    //             });
    //         });
    //     }
    // },

    // general_ledger.prototype.preload_select5 = function()
    // {
    //     if ( $('.select4') ) {
    //         $.each($('.select4'), function(){
    //             var _self = $(this);
    //             var _selfID = $(this).attr('id');
    //             var _parentID = 'parent_' + _selfID;
    //             _self.closest('.form-group').attr('id', _parentID);

    //             _self.select3({
    //                 allowClear: true,
    //                 dropdownAutoWidth : false,
    //                 dropdownParent: $('#' + _parentID),
    //             });
    //         });
    //     }
    // },

    general_ledger.prototype.perfect_scrollbar = function()
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


    general_ledger.prototype.reload_parent = function(_glAccountId, _slID, _modal)
    {   
        var _parent = _modal.find('#sl_parent_id'); _parent.find('option').remove(); 

        console.log(_baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-parent/' + _glAccountId + '/' + _slID);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-parent/' + _glAccountId + '/' + _slID,
            success: function(response) {
                console.log(response.data);
                _parent.append('<option value="">select a parent</option>');  
                $.each(response.data, function(i, item) {
                    _parent.append('<option value="' + item.id + '"> ' + item.code + ' (' + item.description + ')</option>');  
                }); 
            },
            async: false
        });
    },

    general_ledger.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.general_ledger.preload_select3();
        $.general_ledger.load_contents(1);
        $.general_ledger.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#keywords', function (e) {
            $.general_ledger.load_contents(1);
        });

        /*
        | ---------------------------------
        | # when add general ledger button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#general-ledger-account-modal');
            var d1 = $.general_ledger.load_line_contents();
            $.when( d1 ).done(function ( v1 ) 
            {   
                _modal.modal('show');
            });
        });

        /*
        | ---------------------------------
        | # when show/hide subsidiary modal
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#subsidiary-ledger-account-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Subsidiary Ledger Account');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"][name="is_parent"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_parent"][value="0"]').prop('checked', true);
            _modal.find('input[type="radio"][name="is_rpt_tax_cy"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_rpt_tax_cy"][value="0"]').prop('checked', true);
            _modal.find('.current-row').addClass('hidden');
            $.general_ledger.preload_select3();
            $.general_ledger.load_line_contents();
            _slID = 0;
        });
        this.$body.on('shown.bs.modal', '#subsidiary-ledger-account-modal', function (e) {
            $('#add-sl').prop('disabled', false).html('ADD SL');
            // $.general_ledger.preload_select4();
            $.general_ledger.load_current_contents();
        });

        /*
        | ---------------------------------
        | # when show/hide current modal
        | ---------------------------------
        */
        this.$body.on('shown.bs.modal', '#current-modal', function (e) {
            // $.general_ledger.preload_select5();
        });
        this.$body.on('hidden.bs.modal', '#current-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Receivable / Contra');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select4').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"][name="is_debit"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_debit"][value="0"]').prop('checked', true);
            $.general_ledger.load_current_contents();
            _currentID = 0;
        });

        /*
        | ---------------------------------
        | # when add subsidiary button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-sl', function (e) {
            var _self = $(this);
            var _modal  = $('#subsidiary-ledger-account-modal');
            
            if ($('input[type="radio"][name="is_with_sl"]:checked').val() == 'Yes') {
                _self.prop('disabled', true).html('WAIT...');
                var d1 = $.general_ledger.reload_parent(_glAccountId, _slID, _modal);
                $.when( d1 ).done(function ( v1 ) 
                {                 
                    _modal.modal('show');
                });
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Invalid SL Code Selection.",
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
        | # when gl account edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#generalLedgerAccountTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#general-ledger-account-modal');
            var _url    = _baseUrl + 'accounting/chart-of-accounts/general-ledgers/edit/' + _id;
            _glAccountId = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            console.log(_url);
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.general_ledgerForm.reload_major_account_group(response.data.acctg_account_group_id);
                    var d2 = $.general_ledgerForm.reload_submajor_account_group(response.data.acctg_account_group_id, response.data.acctg_account_group_major_id);
                    var d3 = $.general_ledger.load_line_contents();
                    $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
                    {   
                        $.each(response.data, function (k, v) {
                            _modal.find('input[type="text"][name='+k+']:not([type="radio"])').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            if (k == 'is_with_sl' && v == 1) {
                                _modal.find('input[type="radio"][name="'+k+'"][value="Yes"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="No"]').prop('checked', false);
                                _modal.find('.subsidiaryLayer').removeClass('hidden');
                            } else {
                                _modal.find('input[type="radio"][name="'+k+'"][value="No"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="Yes"]').prop('checked', false);
                            }
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit General Ledger Account (<span class="variables">' + _code + '</span>)');
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
        | # when gl account restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#generalLedgerAccountTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/chart-of-accounts/general-ledgers/remove/' + _id : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the general ledger account with code (" + _code + ") will be removed." : "Are you sure? <br/>the general ledger account with code (" + _code + ") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status == 'Active') ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status == 'Active') ? "btn btn-danger" : "btn btn-primary", cancelButton: "btn btn-active-light" },
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
                                    $.general_ledger.load_contents(1);
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
        | # when gl account restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'input[type="radio"][name="is_rpt_tax_cy"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() > 0) {
                _modal.find('.current-row').removeClass('hidden');
            } else {
                _modal.find('.current-row').addClass('hidden');
            }
        });

        /*
        | ---------------------------------
        | # when add current button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-current', function (e) {
            var _modal  = $('#current-modal');
            if (_slID > 0) {
                _modal.modal('show');
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add SL data first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
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
        this.$body.on('click', '#subsidiaryTable .edit-btn', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('tr');
            var _id     = _parent.attr('data-row-id');
            var _code   = _parent.attr('data-row-code');
            var _modal  = $('#subsidiary-ledger-account-modal');
            var _url    = _baseUrl + 'accounting/chart-of-accounts/general-ledgers/edit-sl/' + _id;
            _slID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            console.log(_url);
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.general_ledger.reload_parent(_glAccountId, _slID, _modal);
                    $.when( d1).done(function ( v1 ) 
                    {  
                        $.each(response.data, function (k, v) {
                            _modal.find('input[type="text"][name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            if (k == 'is_parent' && v == 1) {
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', false);
                                _modal.find('.subsidiaryLayer').removeClass('hidden');
                            } else {
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', false);
                            }
                            if (k == 'is_rpt_tax_cy' && v == 1) {
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', false);
                            } else {
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', false);
                            }
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        if (response.data.is_rpt_tax_cy) {
                            _modal.find('.current-row').removeClass('hidden');
                        } else {
                            _modal.find('.current-row').addClass('hidden');
                        }
                    });
                    _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Subsidiary Ledger Account (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#subsidiaryTable .remove-btn, #subsidiaryTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/chart-of-accounts/general-ledgers/remove-sl/' + _id : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/restore-sl/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the subsidiary with code ("+ _code +") will be removed." : "Are you sure? <br/>the subsidiary with code ("+ _code +") will be restored.",
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
                                    $.general_ledger.load_line_contents();
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#subsidiaryTable .hide-btn, #subsidiaryTable .unhide-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _hidden = $(this).closest('tr').attr('data-row-hidden');
            var _url    = (_hidden <= 0) ? _baseUrl + 'accounting/chart-of-accounts/general-ledgers/hide-sl/' + _id : _baseUrl + 'accounting/chart-of-accounts/general-ledgers/show-sl/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_hidden <= 0) ? "Are you sure? <br/>the subsidiary with code ("+ _code +") will be hidden." : "Are you sure? <br/>the subsidiary with code ("+ _code +") will be visible.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_hidden <= 0) ? "Yes, hide it!" : "Yes, show it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_hidden <= 0) ? "btn btn-secondary" : "btn btn-info", cancelButton: "btn btn-active-light" },
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
                                    $.general_ledger.load_line_contents();
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
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#currentTable .edit-btn', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('tr');
            var _id     = _parent.attr('data-row-id');
            var _code   = _parent.attr('data-row-code');
            var _modal  = $('#current-modal');
            var _url    = _baseUrl + 'accounting/chart-of-accounts/general-ledgers/current/edit/' + _id;
            _currentID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            console.log(_url);
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.general_ledger.reload_parent(_glAccountId, _slID, _modal);
                    $.when( d1).done(function ( v1 ) 
                    {  
                        $.each(response.data, function (k, v) {
                            _modal.find('input[type="text"][name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            if (k == 'is_debit' && v == 1) {
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', false);
                            } else {
                                _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', true);
                                _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', false);
                            }
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                    });
                    _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Receivable / Contra (<span class="variables">' + _code + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        });
    }   

    //init general_ledger
    $.general_ledger = new general_ledger, $.general_ledger.Constructor = general_ledger

}(window.jQuery),

//initializing general_ledger
function($) {
    "use strict";
    $.general_ledger.required_fields();
    $.general_ledger.init();
}(window.jQuery);