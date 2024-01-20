!function($) {
    "use strict";

    var approval_setting = function() {
        this.$body = $("body");
    };

    var _settingID = 0; var _table;

    approval_setting.prototype.required_fields = function() {
        
        $('span.ms-1.text-danger').remove();
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

    approval_setting.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#approvalSettingTable', {
            ajax: { 
                url : _baseUrl + 'components/approval-settings/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[ 1, "asc" ], [ 2, "asc" ], [ 3, "asc" ]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.module + ' - Level ' + data.levels);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-position', data.order);
            },
            columns: [
                { data: 'id' },
                { data: 'group' },
                { data: 'module' },
                { data: 'sub_module' },
                { data: 'levels' },
                { data: 'remarks' },
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
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    approval_setting.prototype.fetchID = function()
    {
        return _settingID;
    }

    approval_setting.prototype.updateID = function(_id)
    {
        return _settingID = _id;
    }

    approval_setting.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    approval_setting.prototype.preload_select3 = function()
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

    approval_setting.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/approval-settings/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    approval_setting.prototype.perfect_scrollbar = function()
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

    approval_setting.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.approval_setting.preload_select3();
        $.approval_setting.load_contents();
        $.approval_setting.perfect_scrollbar();

        var _modalx = new bootstrap.Modal($('#approval-setting-modal'), {
            // backdrop: 'static',
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
                var d1 = $.approval_setting.load_contents(_self.val());
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
        this.$body.on('hidden.bs.modal', '#approval-setting-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Approval Setting');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('button.submit-btn').html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
            _settingID = 0;
        });
        this.$body.on('shown.bs.modal', '#approval-setting-modal', function (e) {
            $('.add-btn').prop('disabled', false);
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _self = $(this);
            _self.prop('disabled', true);
            _modalx.show();
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#approvalSettingTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#approval-setting-modal');
            var _url    = _baseUrl + 'components/approval-settings/edit/' + _id;
            console.log(_url);
            _settingID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.approval_settingForm.reload_sub_module(response.data.module_id);
                    $.when( d1 ).done(function ( v1 ) {
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        if (response.data.primary.length > 0) {
                            $.each(response.data.primary, function (k, v) {
                                $.each(v, function (x, y) {
                                    _modal.find('select[id='+x+'].select3').val(y).trigger('change.select3'); 
                                });
                            });
                        }
                        if (response.data.secondary.length > 0) {
                            $.each(response.data.secondary, function (k, v) {
                                $.each(v, function (x, y) {
                                    _modal.find('select[id='+x+'].select3').val(y).trigger('change.select3'); 
                                });
                            });
                        }
                        if (response.data.tertiary.length > 0) {
                            $.each(response.data.tertiary, function (k, v) {
                                $.each(v, function (x, y) {
                                    _modal.find('select[id='+x+'].select3').val(y).trigger('change.select3'); 
                                });
                            });
                        }
                        if (response.data.quaternary.length > 0) {
                            $.each(response.data.quaternary, function (k, v) {
                                $.each(v, function (x, y) {
                                    _modal.find('select[id='+x+'].select3').val(y).trigger('change.select3'); 
                                });
                            });
                        }
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Approval Setting (<span class="variables">' + _code + '</span>)');
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#approvalSettingTable .remove-btn, #approvalSettingTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/approval-settings/remove/' + _id : _baseUrl + 'components/approval-settings/restore/' + _id;

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
                                    $.approval_setting.load_contents();
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

    //init approval_setting
    $.approval_setting = new approval_setting, $.approval_setting.Constructor = approval_setting

}(window.jQuery),

//initializing approval_setting
function($) {
    "use strict";
    $.approval_setting.required_fields();
    $.approval_setting.init();
}(window.jQuery);