!function($) {
    "use strict";

    var ewt = function() {
        this.$body = $("body");
    };

    var _ewtID = 0; var _table; var _page = 0;

    ewt.prototype.required_fields = function() {
        
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

    ewt.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#ewtTable', {
            ajax: { 
                url : _baseUrl + 'accounting/setup-data/expanded-withholding-taxes/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.ewt.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.ewt.hideTooltip();
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
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );        
            }, 
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'name' },
                { data: 'description' },
                { data: 'percentage' },
                { data: 'gl' },
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

    ewt.prototype.fetchID = function()
    {
        return _ewtID;
    }

    ewt.prototype.updateID = function(_id)
    {
        return _ewtID = _id;
    }

    ewt.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    ewt.prototype.preload_select3 = function()
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

    ewt.prototype.perfect_scrollbar = function()
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

    ewt.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    ewt.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.ewt.preload_select3();
        $.ewt.load_contents();
        $.ewt.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.ewt.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#ewt-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Expanded Withholding Tax');
            modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            $.ewt.preload_select3();
            $.ewt.hideTooltip();
            $.ewt.load_contents(_table.page());
            _ewtID = 0;
        });
        this.$body.on('shown.bs.modal', '#ewt-modal', function (e) {
            $.ewt.hideTooltip();
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#ewt-modal');
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ewtTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#ewt-modal');
            var _url    = _baseUrl + 'accounting/setup-data/expanded-withholding-taxes/edit/' + _id;
            console.log(_url);
            _ewtID = _id;
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
                    $.ewt.preload_select3();
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Expanded Withholding Tax (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#ewtTable .remove-btn, #ewtTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/setup-data/expanded-withholding-taxes/remove/' + _id : _baseUrl + 'accounting/setup-data/expanded-withholding-taxes/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the ewt with code ("+ _code +") will be removed." : "Are you sure? <br/>the ewt with code ("+ _code +") will be restored.",
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
                                    $.ewt.load_contents(_table.page());
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

    //init ewt
    $.ewt = new ewt, $.ewt.Constructor = ewt

}(window.jQuery),

//initializing ewt
function($) {
    "use strict";
    $.ewt.required_fields();
    $.ewt.init();
}(window.jQuery);