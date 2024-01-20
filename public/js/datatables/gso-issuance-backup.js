!function($) {
    "use strict";

    var menu_group = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0;

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
        var _complete = 0;
        var table = new DataTable('#issuanceRequestTable', {
            ajax: { 
                url : _baseUrl + 'general-services/issuance/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            processing: true,
            serverSide: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.issuance_id);
                $(row).attr('data-row-code', data.issuance_id);
            },
            columns: [
                { data: 'control_no' },
                { data: 'issuance_date' },
                { data: 'deptName' },
                { data: 'divName' },
                { data: 'issue_status' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
            ]
        } );

        return true;
    },

    menu_group.prototype.issuence_item_details = function(_id,_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#issuanceItemDetails', {
            ajax: { 
                url : _baseUrl + 'general-services/issuance/issuanceItemDetails',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token,
                    "issuance_id": _id,
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.control_no);
                $(row).attr('data-row-code', data.item_name);
            },
            columns: [
                { data: 'item_name' },
                { data: 'item_desc' },
                { data: 'unit_code' },
                { data: 'inv_qty' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
            ]
        } );

        return true;
    },

    menu_group.prototype.$load_contents_item = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'general-services/issuance/item_lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.itemId);
                $(row).attr('data-row-code', data.itemName);
            },
            columns: [
                { data: 'select' },
                { data: 'itemId' },
                { data: 'catCode' },
                { data: 'itemName' },
                { data: 'itemDesc' },
                { data: 'itemUOM' },
                { data: 'itemInventory' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
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

    menu_group.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.menu_group.preload_select3();
        $.menu_group.load_contents();
        $.menu_group.$load_contents_item();

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
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-3 input[type="search"]', function (e) {
            var _self = $(this);
            var _id = $('#id');
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.menu_group.issuence_item_details(_id,_self.val());
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
            modal.find('.modal-header h5').html('Manage Inssuance Request');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
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
        this.$body.on('click', '#issuanceRequestTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#edit_issuance');
            $.menu_group.issuence_item_details(_id);
            var _url    = _baseUrl + 'general-services/issuance/edit/' + _id;
            console.log(_url);
            _groupMenuID = _id;
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
                    $.menu_group.preload_select3();
                    _modal.find('.m-form__help').text('');
                    if(response.data.issue_status == 1)
                    {
                        _modal.find('.submit-btn').html('Approve');
                        // document.getElementById('apv_details').style.display = 'none';
                    }
                    else {
                        _modal.find('.submit-btn').html('Released');
                        // document.getElementById('apv_details').style.display = 'block';
                    }
                    _modal.find('.modal-header h5').html('Inssuance Approve (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#issuanceRequestTable .remove-btn, #issuanceRequestTable .restore-btn', function (e) {
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
        this.$body.on('click', '#issuanceRequestTable .order-btn', function (e) {
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