!function($) {
    "use strict";

    var item_category = function() {
        this.$body = $("body");
    };

    var _table; var _itemCategoryID = 0, _page = 0; var _tooltip;

    item_category.prototype.required_fields = function() {
        
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

    item_category.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#itemCategoryTable', {
            ajax: { 
                url : _baseUrl + 'general-services/setup-data/item-categories/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.item_category.shorten();
                    // $('[data-bs-toggle="tooltip"]').tooltip({
                    //     trigger : 'hover'
                    // });  
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
                // $(row).find('.gl_account').attr({'title': data.gl_account, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                // $(row).find('.description').attr({'title': data.description, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
                // $(row).find('.remarks').attr({'title': data.remarks, 'data-bs-toggle': 'tooltip', 'data-bs-placement': 'top'});
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );     
                // _tooltip.dispose();
                // $('[data-bs-toggle="tooltip"]').tooltip({dispose: true});
                
            }, 
            columns: [
                { data: 'id' },
                { data: 'gl_account_label' },
                { data: 'code' },
                { data: 'description_label' },
                { data: 'remarks_label' },
                { data: 'health_safety' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced gl_account' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced description' },
                {  orderable: true, targets: 4, className: 'text-start sliced remarks' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    item_category.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    item_category.prototype.preload_select3 = function()
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

    item_category.prototype.perfect_scrollbar = function()
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

    item_category.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.item_category.preload_select3();
        $.item_category.load_contents();
        $.item_category.perfect_scrollbar();

        // $('[data-bs-toggle="tooltip"]').on('click', function () {
        //     $(this).tooltip('hide')
        // });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-category-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Item Category');
            _modal.find('input[type="text"], textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="checkbox"]').prop('checked', false);
            _modal.find('.submit-btn').removeClass('hidden');
            _itemCategoryID = 0;
            $.item_category.load_contents(_table.page());
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#item-category-modal');
            _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemCategoryTable .edit-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#item-category-modal');
            var _url    = _baseUrl + 'general-services/setup-data/item-categories/edit/' + _id;
            console.log(_url);
            _itemCategoryID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[type="text"][name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        if (k == 'is_health_safety') {
                            if (v == 1) {
                                _modal.find('input[type="checkbox"]').prop('checked', true);
                            }
                        }
                    });
                    if (response.validate > 0) {
                        _modal.find('.submit-btn').addClass('hidden');
                    }
                    _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>').blur();
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Item Category (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#itemCategoryTable .remove-btn, #itemCategoryTable .restore-btn', function (e) {
            e.preventDefault();
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'general-services/setup-data/item-categories/remove/' + _id : _baseUrl + 'general-services/setup-data/item-categories/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the item category with code ("+ _code +") will be removed." : "Are you sure? <br/>the item category with code ("+ _code +") will be restored.",
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
                                    $.item_category.load_contents(_table.page());
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
    }

    //init item_category
    $.item_category = new item_category, $.item_category.Constructor = item_category

}(window.jQuery),

//initializing item_category
function($) {
    "use strict";
    $.item_category.required_fields();
    $.item_category.init();
}(window.jQuery);