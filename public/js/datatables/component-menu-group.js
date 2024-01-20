!function($) {
    "use strict";

    var menu_group = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0; var _table;

    menu_group.prototype.required_fields = function() {
        
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

    menu_group.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#groupMenuTable', {
            ajax: { 
                url : _baseUrl + 'components/menus/groups/lists',
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
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-position', data.order);
            },
            columns: [
                { data: 'order' },
                { data: 'code' },
                { data: 'name' },
                { data: 'description' },
                { data: 'icon' },
                { data: 'slug' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-start' },
                {  orderable: false, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    menu_group.prototype.fetchID = function()
    {
        return _groupMenuID;
    }

    menu_group.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }

    menu_group.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    menu_group.prototype.preload_select3 = function()
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

    menu_group.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/menus/groups/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    menu_group.prototype.perfect_scrollbar = function()
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

    menu_group.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.menu_group.preload_select3();
        $.menu_group.load_contents();
        $.menu_group.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when re order table
        | ---------------------------------
        */
        $("#groupMenuTable tbody").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('#groupMenuTable tbody tr').each(function() {
                    selectedData.push($(this).attr("data-row-id"));
                });
                $.menu_group.updateOrder(selectedData);
            }
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
                var d1 = $.menu_group.load_contents(_self.val());
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
        this.$body.on('hidden.bs.modal', '#group-menu-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Group Menu');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('button.submit-btn').html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
            $.menu_group.preload_select3();
            _groupMenuID = 0;
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
        this.$body.on('click', '#groupMenuTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#group-menu-modal');
            var _url    = _baseUrl + 'components/menus/groups/edit/' + _id;
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
                        if (k == 'is_dashboard') {
                            if (v > 0) {
                                _modal.find('input[type="checkbox"]').prop('checked', true);
                            } else {
                                _modal.find('input[type="checkbox"]').prop('checked', false);
                            }
                        }
                    });
                    $.menu_group.preload_select3();
                    _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Group Menu (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#groupMenuTable .remove-btn, #groupMenuTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/menus/groups/remove/' + _id : _baseUrl + 'components/menus/groups/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the group menu with code ("+ _code +") will be removed." : "Are you sure? <br/>the group menu with code ("+ _code +") will be restored.",
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
                                    $.menu_group.load_contents();
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
        this.$body.on('click', '#groupMenuTable .order-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _url    = (_self.hasClass('order-up')) ? _baseUrl + 'components/menus/groups/order/up/' + _id : _baseUrl + 'components/menus/groups/order/down/' + _id;
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
                            $.menu_group.load_contents();
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

    //init menu_group
    $.menu_group = new menu_group, $.menu_group.Constructor = menu_group

}(window.jQuery),

//initializing menu_group
function($) {
    "use strict";
    $.menu_group.required_fields();
    $.menu_group.init();
}(window.jQuery);