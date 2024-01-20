!function($) {
    "use strict";

    var menu_subModule = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0; var _table;

    menu_subModule.prototype.required_fields = function() {
        
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

    menu_subModule.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#subModuleMenuTable', {
            ajax: { 
                url : _baseUrl + 'components/menus/sub-modules/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'order' },
                { data: 'module' },
                { data: 'code' },
                { data: 'name' },
                { data: 'description' },
                { data: 'icon' },
                { data: 'slug' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    menu_subModule.prototype.fetchID = function()
    {
        return _groupMenuID;
    }

    menu_subModule.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }

    menu_subModule.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    menu_subModule.prototype.preload_select3 = function()
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

    menu_subModule.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/menus/sub-modules/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    menu_subModule.prototype.perfect_scrollbar = function()
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

    menu_subModule.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.menu_subModule.preload_select3();
        $.menu_subModule.load_contents();        
        $.menu_subModule.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when re order table
        | ---------------------------------
        */
        $("#subModuleMenuTable tbody").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('#subModuleMenuTable tbody tr').each(function() {
                    selectedData.push($(this).attr("data-row-id"));
                });
                $.menu_subModule.updateOrder(selectedData);
            }
        });
        
        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#sub-module-menu-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Sub Module Menu');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('button.submit-btn').html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
            $.menu_subModule.preload_select3();
            _groupMenuID = 0;
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#sub-module-menu-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#subModuleMenuTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#sub-module-menu-modal');
            var _url    = _baseUrl + 'components/menus/sub-modules/edit/' + _id;
            console.log(_url);
            _groupMenuID = _id;
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
                    });
                    $.menu_subModule.preload_select3();
                    _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Sub Module Menu (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#subModuleMenuTable .remove-btn, #subModuleMenuTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/menus/sub-modules/remove/' + _id : _baseUrl + 'components/menus/sub-modules/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the Module Menu with code ("+ _code +") will be removed." : "Are you sure? <br/>the Module Menu with code ("+ _code +") will be restored.",
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
                                    $.menu_subModule.load_contents();
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
        this.$body.on('click', '#subModuleMenuTable .order-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _url    = (_self.hasClass('order-up')) ? _baseUrl + 'components/menus/sub-modules/order/up/' + _id : _baseUrl + 'components/menus/sub-modules/order/down/' + _id;
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
                            $.menu_subModule.load_contents();
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

    //init menu_subModule
    $.menu_subModule = new menu_subModule, $.menu_subModule.Constructor = menu_subModule

}(window.jQuery),

//initializing menu_subModule
function($) {
    "use strict";
    $.menu_subModule.required_fields();
    $.menu_subModule.init();
}(window.jQuery);