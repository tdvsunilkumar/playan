!function($) {
    "use strict";

    var preRepair = function() {
        this.$body = $("body");
    };

    var _preRepairID = 0, _lineID = 0; var _table, _historyTable, _itemTable; var _page = 0, _historyPage = 0, _itemPage = 0; var _status = 'all';

    preRepair.prototype.required_fields = function() {
        
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

    preRepair.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#repairTable', {
            ajax: { 
                url : _baseUrl + 'general-services/repairs-and-inspections/inspection/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.preRepair.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.preRepair.hideTooltip();
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
                $(row).attr('data-row-code', data.repair_no);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Status:<select name="status" aria-controls="status" class="ms-2 form-select form-select-sm d-inline-flex"><option value="draft">Draft</option><option value="for approval">Pending</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option><option value="all">All</option></select></label>');           
                $('select[name="status"]').val(_status);
            },      
            columns: [
                { data: 'id' },
                { data: 'repair_no_label' },
                { data: 'fa_no_label' },
                { data: 'requested_by' },
                { data: 'requested_date' },
                { data: 'issues' },
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
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    preRepair.prototype.history_contents = function(_historyPage = 0) 
    {   
        _historyTable = new DataTable('#repairHistoryTable', {
            ajax: { 
                url : _baseUrl + 'general-services/repairs-and-inspections/inspection/history-lists/' + _preRepairID + '?fixed_asset=' + $('#property_id').val(),
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.preRepair.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.preRepair.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_historyPage).draw( 'page' );   
            },      
            columns: [
                { data: 'id' },
                { data: 'date_requested' },
                { data: 'concerns' },
                { data: 'remarks' },
                { data: 'date_accomplished' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' }
            ]
        } );

        return true;
    },

    preRepair.prototype.item_contents = function(_itemPage = 0) 
    {   
        _itemTable = new DataTable('#repairItemsTable', {
            ajax: { 
                url : _baseUrl + 'general-services/repairs-and-inspections/inspection/item-lists/' + _preRepairID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.preRepair.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.preRepair.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            dom: 'l<"toolbar-1 pad-end">frtip',
            initComplete: function(){
                this.api().page(_historyPage).draw( 'page' );   
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-item">Add Item</button>');  
            }, 
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },     
            columns: [
                { data: 'id' },
                { data: 'item' },
                { data: 'remarks' },
                { data: 'quantity'},
                { data: 'uom'},
                { data: 'amount'},
                { data: 'total'},
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-end' },
                {  orderable: true, targets: 6, className: 'text-end' },
                {  orderable: false, targets: 7, className: 'text-center' }
            ]
        } );

        return true;
    },

    preRepair.prototype.fetchID = function()
    {
        return _preRepairID;
    },

    preRepair.prototype.updateID = function(_id)
    {
        return _preRepairID = _id;
    },

    preRepair.prototype.fetchLineID = function()
    {
        return _lineID;
    },

    preRepair.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    },

    preRepair.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    preRepair.prototype.preload_select3 = function()
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

    preRepair.prototype.perfect_scrollbar = function()
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

    preRepair.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    preRepair.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    preRepair.prototype.getDate = function()
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

    preRepair.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.preRepair.preload_select3();
        $.preRepair.load_contents();
        $.preRepair.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.preRepair.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#repair-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Pre-Repair Inspection Request');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.send-btn').prop('disabled', false).removeClass('hidden');
            $.preRepair.hideTooltip();
            $.preRepair.load_contents(_table.page());
            _preRepairID = 0;
        });
        this.$body.on('shown.bs.modal', '#repair-modal', function (e) {
            $.preRepair.hideTooltip();
        });
        /*
        | ---------------------------------
        | # when item modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Pre-Repair Item');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.preRepair.item_contents();
            _lineID = 0;
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.preRepair.load_contents();
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#repair-modal');
            var d1 = $.preRepair.history_contents();
            $.when( d1 ).done(function ( v1 ) 
            {   
                _modal.find('input[name="requested_date"]').val($.preRepair.getDate());
                _modal.modal('show');
            });
        });
        /*
        | ---------------------------------
        | # when add item button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-item', function (e) {
            var _self  = $(this);
            var _modal = $('#item-modal');
            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'requested') {
                    _self.prop('disabled', true).html('Wait...');
                    setTimeout(function () {
                        _modal.modal('show');
                    }, 500 + 300 * (Math.random() * 5));
                } else {
                    console.log('sorry cannot be processed');
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

        this.$body.on('shown.bs.modal', '#item-modal', function (e) {
            $('#add-item').prop('disabled', false).html('Add Item');
        });

        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#repairTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#repair-modal');
            var _url    = _baseUrl + 'general-services/repairs-and-inspections/inspection/edit/' + _id;
            console.log(_url);
            _preRepairID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.preRepairForm.preload_fixedAsset(_modal, response.data.property_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {  
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        $.preRepair.history_contents();
                        $.preRepair.item_contents();
                    });
                    if (response.data.status != 'requested') {
                        _modal.find('button.send-btn').addClass('hidden');
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('input.form-control-solid, select.form-control-solid, textarea.form-control-solid').prop('disabled', true);
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Pre-Repair Inspection Request (<span class="variables">' + _code + '</span>)');
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
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#repairItemsTable .edit-btn, #repairItemsTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#item-modal');
            var _url    = _baseUrl + 'general-services/repairs-and-inspections/inspection/edit-item/' + _id;
            console.log(_url);
            _lineID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data[0], function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    if (response.data[0].request.status != 'requested') {
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.submit-btn').addClass('hidden');
                        _modal.find('.modal-header h5').html('View Pre-Repair Item (<span class="variables">' + _code + '</span>)');
                    } else {
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.modal-header h5').html('Edit Pre-Repair Item (<span class="variables">' + _code + '</span>)');
                    }
                    $.preRepair.hideTooltip();
                    _modal.find('.m-form__help').text('');
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
        this.$body.on('click', '#repairItemsTable .remove-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'general-services/repairs-and-inspections/inspection/remove-item/' + _id;

            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'requested') {
                    console.log(_url);
                    Swal.fire({
                        html: "Are you sure? <br/>the pre-repair item with code ("+ _code +") will be removed.",
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
                                            $.preRepair.item_contents(_itemTable.page());
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
                    console.log('sorry cannot be processed');
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

    //init preRepair
    $.preRepair = new preRepair, $.preRepair.Constructor = preRepair

}(window.jQuery),

//initializing preRepair
function($) {
    "use strict";
    $.preRepair.required_fields();
    $.preRepair.init();
}(window.jQuery);