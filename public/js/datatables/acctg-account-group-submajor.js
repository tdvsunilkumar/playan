!function($) {
    "use strict";

    var account_group_submajor = function() {
        this.$body = $("body");
    };

    var _accountSubMajorGroupID = 0; var _table;

    account_group_submajor.prototype.required_fields = function() {
        
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

    account_group_submajor.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#submajorAccountGroupTable', {
            ajax: { 
                url : _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.account_group_submajor.shorten();
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
            },
            columns: [
                { data: 'id' },
                { data: 'group' },
                { data: 'major' },
                { data: 'code' },
                { data: 'description' },
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
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    account_group_submajor.prototype.preload_select3 = function()
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

    account_group_submajor.prototype.perfect_scrollbar = function()
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

    account_group_submajor.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    account_group_submajor.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.account_group_submajor.preload_select3();
        $.account_group_submajor.load_contents();
        $.account_group_submajor.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#submajor-account-group-modal');
            _modal.modal('show');
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#submajorAccountGroupTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#submajor-account-group-modal');
            var _url    = _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/edit/' + _id;
            console.log(_url);
            _accountSubMajorGroupID = _id;
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.account_group_submajorForm.reload_major_account_group(response.data.acctg_account_group_id);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                    });
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Sub-Major Group (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#submajorAccountGroupTable .remove-btn, #submajorAccountGroupTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/remove/' + _id : _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/restore/' + _id;

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
                                    $.account_group_submajor.load_contents();
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

    //init account_group_submajor
    $.account_group_submajor = new account_group_submajor, $.account_group_submajor.Constructor = account_group_submajor

}(window.jQuery),

//initializing account_group_submajor
function($) {
    "use strict";
    $.account_group_submajor.required_fields();
    $.account_group_submajor.init();
}(window.jQuery);