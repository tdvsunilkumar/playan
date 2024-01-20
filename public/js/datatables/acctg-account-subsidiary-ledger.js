!function($) {
    "use strict";

    var subsidiary_ledger = function() {
        this.$body = $("body");
    };

    var track_page = 1, sortBy = '', orderBy = ''; 
    var radioVal = 0;

    subsidiary_ledger.prototype.required_fields = function() {
        
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

    subsidiary_ledger.prototype.load_contents = function(_keywords = '') 
    {   
        var table = new DataTable('#subsidiaryLedgerAccountTable', {
            ajax: { 
                url : _baseUrl + 'accounting/chart-of-accounts/subsidiary-ledgers/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.subsidiary_ledger.shorten();
                }
            },
            bDestroy: true,
            pageLength: 10,
            order: [[ 1, "asc" ], [ 2, "asc" ]],
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
                { data: 'gl_account' },
                { data: 'prefix' },
                { data: 'code' },
                { data: 'description' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    subsidiary_ledger.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    subsidiary_ledger.prototype.preload_select3 = function()
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

    subsidiary_ledger.prototype.perfect_scrollbar = function()
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

    subsidiary_ledger.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.subsidiary_ledger.preload_select3();
        $.subsidiary_ledger.load_contents(1);
        $.subsidiary_ledger.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#keywords', function (e) {
            $.subsidiary_ledger.load_contents(1);
        });

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#subsidiaryLedgerAccountTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'accounting/chart-of-accounts/subsidiary-ledgers/remove/' + _id : _baseUrl + 'accounting/chart-of-accounts/subsidiary-ledgers/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the subsidiary ledger account with code (" + _code + ") will be removed." : "Are you sure? <br/>the subsidiary ledger account with code (" + _code + ") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status == 'Active') ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status == 'Active') ? "btn btn-danger" : "btn btn-primary", cancelButton: "btn btn-active-light" },
            }).then(function (t) {
                t.value
                    ? 
                    $.ajax({
                        type: 'PUT',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                    e.isConfirmed && ((t.disabled = !1));
                                    $.subsidiary_ledger.load_contents(1);
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

    //init subsidiary_ledger
    $.subsidiary_ledger = new subsidiary_ledger, $.subsidiary_ledger.Constructor = subsidiary_ledger

}(window.jQuery),

//initializing subsidiary_ledger
function($) {
    "use strict";
    $.subsidiary_ledger.required_fields();
    $.subsidiary_ledger.init();
}(window.jQuery);