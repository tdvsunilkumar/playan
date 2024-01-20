!function($) {
    "use strict";

    var inventory = function() {
        this.$body = $("body");
    };

    var _itemID = 0, _table = '', _historyTable = '', _page = 0, _linePage = 0;

    inventory.prototype.required_fields = function() {
        
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

    inventory.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'general-services/inventory/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.inventory.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
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
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
                $(row).find('.quantity').attr('title', 'Inventory / Reserved');
                $(row).find('.quantity').attr('data-bs-toggle', 'tooltip');
                $(row).find('.quantity').attr('data-bs-placement', 'top');
                $(row).find('.unit_cost').attr('title', 'Weighted Cost / Latest Cost');
                $(row).find('.unit_cost').attr('data-bs-toggle', 'tooltip');
                $(row).find('.unit_cost').attr('data-bs-placement', 'top');
                $(row).find('.gl_account').attr({'title': data.gl_account, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                $(row).find('.type').attr({'title': data.type, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                $(row).find('.category').attr({'title': data.category, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                $(row).find('.named').attr({'title': data.name, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );        
            }, 
            columns: [
                { data: 'id' },
                { data: 'gl_account_label' },
                { data: 'type_label' },
                { data: 'category_label' },
                { data: 'code_label' },
                { data: 'name_label' },
                { data: 'quantity' },
                { data: 'uom' },
                { data: 'unit_cost' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced gl_account' },
                {  orderable: true, targets: 2, className: 'text-start sliced type' },
                {  orderable: true, targets: 3, className: 'text-start sliced category' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start sliced named' },
                {  orderable: false, targets: 6, className: 'text-center quantity' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center unit_cost' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' },
            ]
        } );

        return true;
    },

    inventory.prototype.load_line_contents = function(_linePage = 0) 
    {   

        _historyTable = new DataTable('#itemHistoryTable', {
            ajax: { 
                url : _baseUrl + 'general-services/inventory/history-lists/' + _itemID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.inventory.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.inventory.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[1, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 5,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).find('.issued_by').attr({'title': data.issued_by, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                $(row).find('.received_by').attr({'title': data.received_by, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
            },
            initComplete: function(){
                this.api().page(_linePage).draw( 'page' );        
            }, 
            columns: [
                { data: 'transaction' },
                { data: 'datetime' },
                { data: 'issued_by_label' },
                { data: 'received_by_label' },
                { data: 'based_from' },
                { data: 'based_qty' },
                { data: 'posted_qty' },
                { data: 'balanced_qty' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-center' },
                {  orderable: true, targets: 2, className: 'text-start sliced issued_by' },
                {  orderable: true, targets: 3, className: 'text-start sliced received_by' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' }
            ]
        } );

        return true;
    },

    inventory.prototype.fetchID = function()
    {
        return _itemID;
    }

    inventory.prototype.updateID = function(_id)
    {
        return _itemID = _id;
    }

    inventory.prototype.preload_select3 = function()
    {
        if ( $('.select3') ) {
            $('.select3').select3({
                allowClear: true,
                dropdownAutoWidth : false,dropdownParent: $('.modal.form .modal-body'),
            });
        }
    },

    inventory.prototype.preload_select4 = function()
    {
        if ( $('.select3') ) {
            $('.select3').select3({
                allowClear: true,
                dropdownAutoWidth : false,dropdownParent: $('.modal.form-inner .modal-body'),
            });
        }
    },

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    inventory.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    inventory.prototype.perfect_scrollbar = function()
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

    inventory.prototype.fetch_group_code = function(_id, _modal)
    {
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/inventory/fetch-gl-via-item-category/' + _id,
            success: function(response) {
                console.log(response.data);
                _modal.find('select[name="gl_account_id"]').val(response.data);
                $.inventory.preload_select3();
            },
            async: false
        });
    },

    inventory.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    inventory.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    inventory.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.inventory.preload_select3();
        $.inventory.load_contents();
        $.inventory.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.inventory.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Item Inventory');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('.upload-row').addClass('hidden');
            _modal.find('select[name="item_category_id"]').prop('disabled', false);
            $.inventory.preload_select3();
            $.inventory.hideTooltip();
            $.inventory.load_contents(_table.page());
            _itemID = 0;
        });

        this.$body.on('shown.bs.modal', '#item-adjustment-modal', function (e) {
            var _modal = $(this);
            $.inventory.preload_select4();
            $.inventory.hideTooltip();
        });
        this.$body.on('hidden.bs.modal', '#item-adjustment-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Adjustment');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('.upload-row').addClass('hidden');
            _modal.find('select[name="item_category_id"]').prop('disabled', false);
            $.inventory.preload_select3();
            $.inventory.hideTooltip();
            $.inventory.load_contents(_table.page());
            _itemID = 0;
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemTable .view-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#item-modal');
            var _url    = _baseUrl + 'general-services/inventory/edit/' + _id;
            console.log(_url); _itemID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.inventory.load_line_contents();
            $.when( d1 ).done(function ( v1 ) 
            { 
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        $.each(response.data, function (k, v) {
                            _modal.find('label[for='+k+']').next().html(v);
                        });
                        _self.prop('disabled', false).attr('title', 'view this').html('<i class="ti-comment text-white"></i>');
                        _modal.find('.modal-header h5').html('Manage Item Inventory (<span class="variables">' + _code + '</span>)');
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
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemTable .adjust-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#item-adjustment-modal');
            _itemID = _id;
            _modal.find('.modal-header h5').html('Item Adjustment (<span class="variables">' + _code + '</span>)');
            _modal.modal('show');
        }); 
    }

    //init inventory
    $.inventory = new inventory, $.inventory.Constructor = inventory

}(window.jQuery),

//initializing inventory
function($) {
    "use strict";
    $.inventory.required_fields();
    $.inventory.init();
}(window.jQuery);