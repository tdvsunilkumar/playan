!function($) {
    "use strict";

    var fixed_asset = function() {
        this.$body = $("body");
    };

    var _fixedAssetID = 0; var _table, _historyTable; var _page = 0, _historyPage = 0;

    fixed_asset.prototype.required_fields = function() {
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

    fixed_asset.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#fixedAssetTable', {
            ajax: { 
                url : _baseUrl + 'accounting/fixed-assets/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.fixed_asset.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.fixed_asset.hideTooltip();
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
                $(row).attr('data-row-code', data.fa_no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-locked', data.locked);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
            },  
            columns: [
                { data: 'id' },
                { data: 'fa_no_label' },
                { data: 'par_no' },
                { data: 'category' },
                { data: 'type' },
                { data: 'gl_account' },
                { data: 'item' },
                { data: 'unit_cost' },
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
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
                {  orderable: true, targets: 7, className: 'text-end' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' },
            ]
        } );

        return true;
    },

    fixed_asset.prototype.load_history_contents = function(_historyPage = 0) 
    {   
        _historyTable = new DataTable('#fixedAssetHistoryTable', {
            ajax: { 
                url : _baseUrl + 'accounting/fixed-assets/history-lists/' + _fixedAssetID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.fixed_asset.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.fixed_asset.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.fa_no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-locked', data.locked);
            },
            initComplete: function(){
                this.api().page(_historyPage).draw( 'page' );   
            },  
            columns: [
                { data: 'id' },
                { data: 'acquired_date' },
                { data: 'acquired_by' },
                { data: 'issued_by' },
                { data: 'returned_date' },
                { data: 'returned_by' },
                { data: 'received_by' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
            ]
        } );

        return true;
    },

    fixed_asset.prototype.fetchID = function()
    {
        return _fixedAssetID;
    }

    fixed_asset.prototype.updateID = function(_id)
    {
        return _fixedAssetID = _id;
    }

    fixed_asset.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    fixed_asset.prototype.preload_select3 = function()
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

    fixed_asset.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'accounting/fixed-assets/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    fixed_asset.prototype.perfect_scrollbar = function()
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

    fixed_asset.prototype.reload_items_via_gl = function(_glAccount)
    {
        var _item = $('#item_id'); _item.find('option').remove(); 
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/fixed-assets/reload-items-via-gl/' + _glAccount,
            success: function(response) {
                _item.append('<option value="">select an item</option>');  
                $.each(response.data, function(i, item) {
                    _item.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                }); 
            },
            async: false
        });
    },

    fixed_asset.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    fixed_asset.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.fixed_asset.preload_select3();
        $.fixed_asset.load_contents();
        $.fixed_asset.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.fixed_asset.hideTooltip();
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
                var d1 = $.fixed_asset.load_contents(_self.val());
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
        this.$body.on('hidden.bs.modal', '#fixed-asset-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Fixed Asset');
            _modal.find('input, select, textarea').removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            _modal.find('input, select, textarea').closest(".form-group").find(".is-invalid").removeClass("is-invalid");
            _modal.find('input, textarea').val('');
            _modal.find('select').val('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true).closest('.form-group').removeClass('required');
            _modal.find('button.send-btn').prop('disabled', false).removeClass('hidden');
            _modal.find('input[type="checkbox"][name="is_depreciative"]').prop('checked', false);
            _modal.find('input.require:not(.disable), select.require:not(.disable)').closest('.form-group').addClass('required');
            $.fixed_asset.required_fields();
            _fixedAssetID = 0;
        });
        this.$body.on('shown.bs.modal', '#fixed-asset-modal', function (e) {
            var _modal = $(this);
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#fixed-asset-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#fixedAssetTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _lock   = _self.closest('tr').attr('data-row-locked');
            var _modal  = $('#fixed-asset-modal');
            var _url    = _baseUrl + 'accounting/fixed-assets/edit/' + _id;
            console.log(_url);
            _fixedAssetID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.fixed_asset.reload_items_via_gl(response.data[0].gl_account_id);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        $.each(response.data[0], function (k, v) {
                            _modal.find('input[name='+k+']:not([type="checkbox"])').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                    });
                    if (_code) { 
                        _modal.find('.modal-header h5').html('Edit Fixed Asset (<span class="variables">' + (_code) + '</span>)');
                    } else {
                        _modal.find('.modal-header h5').html('Edit Fixed Asset');
                    }
                    if (_lock > 0) {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('.submit-btn').addClass('hidden');
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-search text-white"></i>');
                    } else {
                        _modal.find('input, select, textarea').prop('disabled', false);
                        _modal.find('.submit-btn').removeClass('hidden');
                        _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                        if(response.data[0].is_departmental > 0) {
                            _modal.find('select[name="received_by"], select[name="issued_by"], select[name="property_category_id"], select[name="gl_account_id"], select[name="item_id"]').prop('disabled', true).closest('.form-group').removeClass('required');
                            _modal.find('input[name="received_date"], input[name="unit_cost"]').prop('disabled', true).closest('.form-group').removeClass('required');
                        } else {
                            _modal.find('input[name="received_date"], input[name="unit_cost"]').prop('disabled', false).closest('.form-group').addClass('required');
                            _modal.find('select[name="received_by"], select[name="issued_by"], select[name="property_category_id"], select[name="gl_account_id"], select[name="item_id"]').prop('disabled', false).closest('.form-group').addClass('required');
                        }
                        if (response.data[0].is_depreciative > 0) {
                            _modal.find('input[type="checkbox"][name="is_depreciative"]').prop('checked', true);
                            _modal.find('.depreciation').prop('disabled', false).closest('.form-group').addClass('required');
                        } else {
                            _modal.find('input[type="checkbox"][name="is_depreciative"]').prop('checked', false);
                            _modal.find('.depreciation').prop('disabled', true).closest('.form-group').removeClass('required');
                        }
                        _modal.find('.disable').prop('disabled', true);
                        $.fixed_asset.required_fields();
                    }
                    $.fixed_asset.load_history_contents();
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
        this.$body.on('click', '#fixedAssetTable .lock-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _url    = _baseUrl + 'accounting/fixed-assets/lock/' + _id;

            console.log(_url);
            Swal.fire({
                html: "Are you sure? <br/>the fixed asset with code ("+ _code +") will be locked.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, lock it!",
                cancelButtonText: "No, return",
                customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
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
                                    $.fixed_asset.load_contents();
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
        this.$body.on('click', '#fixedAssetTable .order-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _url    = (_self.hasClass('order-up')) ? _baseUrl + 'accounting/fixed-assets/order/up/' + _id : _baseUrl + 'accounting/fixed-assets/order/down/' + _id;
            console.log(_url);
            $.ajax({
                type: 'PUT',
                url: _url,
                success: function(response) {
                    console.log(response);
                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                    .then(
                        function (e) {
                            e.isConfirmed;
                            $.fixed_asset.load_contents();
                        }
                    );
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 
    }

    //init fixed_asset
    $.fixed_asset = new fixed_asset, $.fixed_asset.Constructor = fixed_asset

}(window.jQuery),

//initializing fixed_asset
function($) {
    "use strict";
    $.fixed_asset.required_fields();
    $.fixed_asset.init();
}(window.jQuery);