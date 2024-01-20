!function($) {
    "use strict";

    var generalJournal = function() {
        this.$body = $("body");
    };

    var _generalJournalID = 0, _entryID = 0; var _table, _entryTable; var _page = 0, _linePage = 0; var _status = 'all';

    generalJournal.prototype.required_fields = function() {
        
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

    generalJournal.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#generalJournalTable', {
            ajax: { 
                url : _baseUrl + 'accounting/general-journals/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.generalJournal.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.generalJournal.hideTooltip();
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
                $(row).attr('data-row-code', data.journal);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-3 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-3").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="ap_status" aria-controls="ap_status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="completed">Completed</option><option value="all">All</option></select></label>');           
                $('select[name="ap_status"]').val(_status);
                $.generalJournal.shorten(); 
            },      
            columns: [
                { data: 'id' },
                { data: 'journal_label' },
                { data: 'transaction_date' },
                { data: 'fixed_asset_label' },
                { data: 'particulars' },
                { data: 'total_debit' },
                { data: 'total_credit' },
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
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 6, className: 'text-end' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    generalJournal.prototype.load_line_contents = function(_linePage = 0) 
    {   
        console.log(_baseUrl + 'accounting/general-journals/line-lists/' + _generalJournalID); 
        _entryTable = new DataTable('#journalEntriesTable', {
            ajax: { 
                url : _baseUrl + 'accounting/general-journals/line-lists/' + _generalJournalID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                omplete: function() {
                    $.generalJournal.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.generalJournal.hideTooltip();
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
            order: [[3, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'l<"toolbar-2">frtip',
            initComplete: function(){
                $("div.toolbar-2").html('<button type="button" class="btn btn-small bg-info mb-3" id="add-entry">ADD LINE</button>');   
                if(_entryTable.rows().data().length > 0) {
                    $('select[name="fund_code_id"], select[name="fixed_asset_id"], select[name="payee_id"], select[name="division_id"]').prop('disabled', true);
                } else {
                    $('select[name="fund_code_id"], select[name="fixed_asset_id"], select[name="payee_id"], select[name="division_id"]').prop('disabled', false);
                }       
                $.generalJournal.shorten(); 
            }, 
            columns: [
                { data: 'gl_account' },
                { data: 'debit' },
                { data: 'credit' },
                { data: 'actions' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' },
            ]
        } );

        return true;
    },

    generalJournal.prototype.fetchID = function()
    {
        return _generalJournalID;
    }

    generalJournal.prototype.updateID = function(_id)
    {
        return _generalJournalID = _id;
    }

    generalJournal.prototype.fetchEntryID = function()
    {
        return _entryID;
    }

    generalJournal.prototype.updateEntryID = function(_id)
    {
        return _entryID = _id;
    }

    generalJournal.prototype.shorten = function() 
    {   
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    generalJournal.prototype.preload_select3 = function()
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

    generalJournal.prototype.reload_fixed_assets = function (_id)
    {   
        var _fixedAsset = $('#fixed_asset_id'); _fixedAsset.find('option').remove(); 
        console.log(_baseUrl + 'accounting/general-journals/reload-fixed-asset/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/general-journals/reload-fixed-asset/' + _id,
            success: function(response) {
                console.log(response);
                _fixedAsset.append('<option value="">select a fixed asset no</option>');  
                $.each(response.data, function(i, item) {
                    _fixedAsset.append('<option value="' + item.id + '">' + item.fixed_asset_no + '</option>');  
                }); 
            },
            async: false
        });
        return _status;
    },


    generalJournal.prototype.perfect_scrollbar = function()
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

    generalJournal.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    generalJournal.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    generalJournal.prototype.getDate = function()
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

    generalJournal.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.generalJournal.preload_select3();
        $.generalJournal.load_contents();
        $.generalJournal.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.generalJournal.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#general-journal-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Account Payable');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            $.generalJournal.preload_select3();
            $.generalJournal.hideTooltip();
            $.generalJournal.load_contents(_table.page());
            _generalJournalID = 0;
        });
        this.$body.on('shown.bs.modal', '#general-journal-modal', function (e) {
            $.generalJournal.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="ap_status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.generalJournal.load_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#general-journal-modal');
             var d1    = $.generalJournal.reload_fixed_assets(_generalJournalID);
             var d2    = $.generalJournal.load_line_contents();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {   
                _modal.find('input[name="due_date"]').val($.generalJournal.getDate());
                _modal.modal('show');
            });
        });
        
        /*
        | ---------------------------------
        | # when add breakdown button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-entry', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _error = $.generalJournalForm.validate(_form, 0);
            var _modal = $('#journal-entry-modal');
            var d1     = $.generalJournalForm.fetch_status(_generalJournalID);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
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
                        _self.prop('disabled', true).html('WAIT.....');
                        _modal.modal('show');
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
        this.$body.on('hidden.bs.modal', '#journal-entry-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Journal Entry');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            $.generalJournal.load_line_contents(_entryTable.page());
            _entryID = 0;
        });
        this.$body.on('shown.bs.modal', '#journal-entry-modal', function (e) {
            $('#add-entry').prop('disabled', false).html('ADD LINE');
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#generalJournalTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#general-journal-modal');
            var _url    = _baseUrl + 'accounting/general-journals/edit/' + _id;
            console.log(_url);
            _generalJournalID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1    = $.generalJournal.reload_fixed_assets(_generalJournalID);
            var d2    = $.generalJournal.load_line_contents();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {
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
                        if (response.data.status != 'draft') {
                            _modal.find('input, select, textarea').prop('disabled', true);
                            _modal.find('button.submit-btn').addClass('hidden');
                        }
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit General Journal (<span class="variables">' + _code + '</span>)');
                        _modal.modal('show');
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            });
        }); 

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#journalEntriesTable .remove-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $(this).closest('.modal');
            var _url    = _baseUrl + 'accounting/general-journals/remove-entry/' + _id;

            var d1     = $.generalJournalForm.fetch_status(_generalJournalID);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    console.log(_url);
                    Swal.fire({
                        html: "Are you sure? <br/>the journal entry with code ("+ _code +")<br/>will be removed.",
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
                                            _modal.find('tfoot th.total-debit').text('₱' + $.generalJournal.price_separator(parseFloat(response.total_debit).toFixed(2)));
                                            _modal.find('tfoot th.total-credit').text('₱' + $.generalJournal.price_separator(parseFloat(response.total_credit).toFixed(2)));
                                            $.generalJournal.load_line_contents(_entryTable.page());
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
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#journalEntriesTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _modal  = $('#journal-entry-modal');
            var _url    = _baseUrl + 'accounting/general-journals/edit-entry/' + _id;
            console.log(_url);
            _entryID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.generalJournalForm.fetch_status(_generalJournalID);
            $.when( d1 ).done(function ( v1 ) 
            {
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
                        if (v1 != 'draft') {
                            _modal.find('input, select, textarea').prop('disabled', true);
                            _modal.find('button.submit-btn').addClass('hidden');
                        }
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Journal Entry (<span class="variables">' + _id + '</span>)');
                        _modal.modal('show');
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            });
        }); 


        /*
        | ---------------------------------
        | # when complete button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#generalJournalTable .complete-btn', function (e) {
            var _id   = $(this).closest('tr').attr('data-row-id');
            var _code = $(this).closest('tr').attr('data-row-code');
            var _url  = _baseUrl + 'accounting/general-journals/complete/' + _id;
            var d1    = $.generalJournalForm.fetch_status(_id);
            var d2    = $.generalJournalForm.validate_journal(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (!(v2 > 0 )) {
                    // _self.prop('disabled', false).html('Post Changes');
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The debit and credit amount doesn't matched or don't have value.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                } else if (v1 == 'draft') {
                    // _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                    Swal.fire({
                        html: "Are you sure? <br/>the journal entry with code ("+ _code +")<br/>will be completed.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, complete it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn completed-bg", cancelButton: "btn btn-active-light" },
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
                                            $.generalJournal.load_contents(_table.page());
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
                    _self.prop('disabled', false).html('Post Changes');
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

    }

    //init generalJournal
    $.generalJournal = new generalJournal, $.generalJournal.Constructor = generalJournal

}(window.jQuery),

//initializing generalJournal
function($) {
    "use strict";
    $.generalJournal.required_fields();
    $.generalJournal.init();
}(window.jQuery);