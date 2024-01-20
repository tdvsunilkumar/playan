!function($) {
    "use strict";

    var outbox = function() {
        this.$body = $("body");
    };

    var _outboxID = 0; var _table; var _page = 0, _grpList = '', _grp = 'all', _typeList = '', _type = 'all';

    outbox.prototype.required_fields = function() 
    {
        $('label span.ms-1.text-danger').remove();
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

    outbox.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#outboxTable', {
            ajax: { 
                url : _baseUrl + 'components/sms-notifications/outbox/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.outbox.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.outbox.hideTooltip();
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
                $(row).attr('data-row-code', data.application);
                $(row).attr('data-row-status', data.status);
            },
            // dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                // $("div.toolbar-2").html('<div class="dataTables_wrapper"><label class="d-inline-flex line-30">Group: <div class="form-group ms-2">' + _grpList + '</div></label></div> <div class="dataTables_wrapper"><label class="d-inline-flex line-30">Type: <div class="form-group ms-2">' + _typeList + '</div></label></div>');           
                // $('select[name="types"]').val(_type);
                // $('select[name="groups"]').val(_grp);
                // $.outbox.preload_select3();
            },  
            columns: [
                { data: 'id' },
                { data: 'transid' },
                { data: 'messages' },
                { data: 'type' },
                { data: 'msisdn' },
                { data: 'smsc' },
                { data: 'status' },
                { data: 'modified' }
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
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    outbox.prototype.fetchID = function()
    {
        return _outboxID;
    }

    outbox.prototype.updateID = function(_id)
    {
        return _outboxID = _id;
    }

    outbox.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    outbox.prototype.preload_select3 = function()
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

    outbox.prototype.perfect_scrollbar = function()
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

    outbox.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    outbox.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.outbox.preload_select3();
        $.outbox.load_contents();
        $.outbox.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.outbox.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#outbox-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Bank');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            $.outbox.preload_select3();
            $.outbox.hideTooltip();
            $.outbox.load_contents(_table.page());
            _outboxID = 0;
        });
        this.$body.on('shown.bs.modal', '#outbox-modal', function (e) {
            $.outbox.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when filter on change
        | ---------------------------------
        */
        this.$body.on('change', '#groups_id', function (e) {
            _grp = $(this).val();
            $.outbox.load_contents(_table.page());
        });
        this.$body.on('change', '#types_id', function (e) {
            _type = $(this).val();
            $.outbox.load_contents(_table.page());
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#outbox-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#outboxTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#outbox-modal');
            var _url    = _baseUrl + 'components/sms-notifications/outboxs/edit/' + _id;
            console.log(_url);
            _outboxID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.outboxForm.reload_module(response.data.group_id, _modal.find('#module_id'));
                    var d2 = $.outboxForm.reload_sub_module(response.data.group_id, response.data.module_id, _modal.find('#sub_module_id'));
                    $.when( d1, d2 ).done(function (v1, v2) {
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                    });
                    _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit outbox (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#outboxTable .remove-btn, #outboxTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/sms-notifications/outboxs/remove/' + _id : _baseUrl + 'components/sms-notifications/outboxs/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the outbox with code ("+ _code +") will be removed." : "Are you sure? <br/>the outbox with code ("+ _code +") will be restored.",
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
                                    $.outbox.load_contents(_table.page());
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

    //init outbox
    $.outbox = new outbox, $.outbox.Constructor = outbox

}(window.jQuery),

//initializing outbox
function($) {
    "use strict";
    $.outbox.required_fields();
    $.outbox.init();
}(window.jQuery);