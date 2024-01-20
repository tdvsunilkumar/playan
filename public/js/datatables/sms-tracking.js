!function($) {
    "use strict";

    var sms_tracking = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0; var _table; var _page = 0;

    sms_tracking.prototype.required_fields = function() {
        
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

    sms_tracking.prototype.load_contents = function(_page = 0) 
    {   
        var _url = _baseUrl + 'components/sms-notifications/tracking-lists?status=' + $('select[name="status"]').val() + '&date_from=' + $('input[name="date_from"]').val() + '&date_to=' + $('input[name="date_to"]').val();
        console.log(_url);
        _table = new DataTable('#trackingTable', {
            ajax: { 
                url : _url,
                type: "GET", 
                data: {
                    "_token": _token
                    
                },
                complete: function(response) {
                    $.each(response.responseJSON.sms, function(_sms, _val){
                        $('#sms-' + _sms).find('span').text(_val)
                    });
                    $.sms_tracking.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $('.search-btn').prop('disabled', false).html('<i class="la la-search align-middle"></i> SEARCH');
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
                var _resentStatus = (data.delivered > 0 || data.undelivered > 0 || data.failed > 0 || data.expired > 0) ? 1 : 0;
                $(row).attr('data-row-status', _resentStatus);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
            },  
            columns: [
                { data: 'id' },
                { data: 'message' },
                { data: 'contacts' },
                { data: 'sent_at' },
                { data: 'successful' },
                { data: 'failed' },
                { data: 'delivered' },
                { data: 'undelivered' },
                { data: 'expired' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: false, targets: 2, className: 'text-center' },
                {  orderable: false, targets: 3, className: 'text-center' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    sms_tracking.prototype.fetchID = function()
    {
        return _groupMenuID;
    }

    sms_tracking.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }

    sms_tracking.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    sms_tracking.prototype.preload_select3 = function()
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
                    dropdownParent: $('.noflow'),
                });
            });
        }
    },

    sms_tracking.prototype.updateOrder = function(data)
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

    sms_tracking.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    sms_tracking.prototype.perfect_scrollbar = function()
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

    sms_tracking.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.sms_tracking.preload_select3();
        $.sms_tracking.load_contents();
        $.sms_tracking.perfect_scrollbar();

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
                $.sms_tracking.updateOrder(selectedData);
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
                var d1 = $.sms_tracking.load_contents(_self.val());
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
            $.sms_tracking.preload_select3();
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
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#group-menu-modal');
            var _url    = _baseUrl + 'components/menus/groups/edit/' + _id;
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
                        if (k == 'is_dashboard') {
                            if (v > 0) {
                                _modal.find('input[type="checkbox"]').prop('checked', true);
                            } else {
                                _modal.find('input[type="checkbox"]').prop('checked', false);
                            }
                        }
                    });
                    $.sms_tracking.preload_select3();
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
        this.$body.on('click', '#trackingTable .resend-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = _baseUrl + 'components/sms-notifications/resend/' + _id;

            console.log(_url);
            if (_status > 0) {
                Swal.fire({
                    html: "Are you sure? <br/>the sms notifcation with tracking id ("+ _id +") will be resent.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, resend it!",
                    cancelButtonText: "No, return",
                    customClass: { confirmButton: "btn btn-secondary", cancelButton: "btn btn-active-light" },
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
                                        $.sms_tracking.load_contents();
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
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>All SMS are already sent.",
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

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.search-btn', function (e) {
            var _self   = $(this);
            _self.prop('disabled', true).html('SEARCHING...');
            setTimeout(function () {
                $.sms_tracking.load_contents();
            }, 500 + 300 * (Math.random() * 5));
        }); 
    }

    //init sms_tracking
    $.sms_tracking = new sms_tracking, $.sms_tracking.Constructor = sms_tracking

}(window.jQuery),

//initializing sms_tracking
function($) {
    "use strict";
    $.sms_tracking.required_fields();
    $.sms_tracking.init();
}(window.jQuery);