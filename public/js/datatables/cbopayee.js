!function($) {
    "use strict";

    var account_group_submajor = function() {
        this.$body = $("body");
    };

    var track_page = 1, sortBy = '', orderBy = '';

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

    account_group_submajor.prototype.load_contents = function(track_page, sortBy = '', orderBy = '') 
    {   
        var keywords = '?keywords=' + $('#keywords').val() + '&perPage=' + $('#perPage').val() + '&sortBy=' + sortBy + '&orderBy=' + orderBy;
        var urls = _baseUrl + 'finance/payee/lists';
        var me = $(this);
        var $portlet = $('#datatable-result');

        if ( me.data('requestRunning') ) {
            return;
        }
        
        console.log(urls + '' + keywords);
        $.ajax({
            type: 'GET',
            url:  urls + '' + keywords,
            data: { 'page': track_page },
            success: function (data) {
                if(data.trim().length == 0)
                {                    
                    return;
                }
                $portlet.html(data);
            },
            complete: function() {
                me.data('requestRunning', false);
            }
        });
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

    account_group_submajor.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.account_group_submajor.preload_select3();
        $.account_group_submajor.load_contents(1);

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#keywords', function (e) {
            $.account_group_submajor.load_contents(1);
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#submajor-account-group-modal');
            $.account_group_submajor.preload_select3();
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
            var _url    = _baseUrl + 'finance/payee/edit/' + _id;
            console.log(_url);
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
                        });
                        $.account_group_submajor.preload_select3();
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Payee (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#submajorAccountGroupTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url   = (_status > 0) ? _baseUrl + 'finance/payee/remove/' + _id : _baseUrl + 'finance/payee/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status > 0) ? "Are you sure? <br/>the payee with name ("+ _code +") will be removed." : "Are you sure? <br/>the payee with name ("+ _code +") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status > 0) ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status > 0) ? "btn btn-danger" : "btn btn-primary", cancelButton: "btn btn-active-light" },
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
                                    $.account_group_submajor.load_contents(1);
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
        | # when perPage is changed
        | ---------------------------------
        */
        this.$body.on('change', '#perPage', function (e) {
            $.account_group_submajor.load_contents(1);
        });
        
        /*
        | ---------------------------------
        | # when paginate is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.pagination li:not([class="disabled"],[class="active"])', function (e) {
            var page  = $(this).attr('p');   
            if (page > 0) {
                $.account_group_submajor.load_contents(page);
            }
        }); 
        
        /*
        | ---------------------------------
        | # when sorting is click
        | ---------------------------------
        */
        this.$body.on('click', '#submajorAccountGroupTable .sorting, #submajorAccountGroupTable .sorting_asc, #submajorAccountGroupTable .sorting_desc', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _data = _self.attr('data-row');
            if (_self.hasClass('sorting')) {
                sortBy = _data; orderBy = 'asc';
                _self.removeClass('sorting').addClass('sorting_asc');
                $.account_group_submajor.load_contents(1, sortBy, orderBy);
            } else if (_self.hasClass('sorting_asc')) {
                sortBy = _data; orderBy = 'desc';
                _self.removeClass('sorting_asc').addClass('sorting_desc');
                $.account_group_submajor.load_contents(1, sortBy, orderBy);
            } else {
                sortBy = _data; orderBy = 'asc';
                _self.removeClass('sorting_desc').addClass('sorting_asc');
                $.account_group_submajor.load_contents(1, sortBy, orderBy);
            }
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