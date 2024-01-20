!function($) {
    "use strict";

    var citizen_code = function() {
        this.$body = $("body");
    };

    var track_page = 1, sortBy = '', orderBy = '';

    citizen_code.prototype.required_fields = function() {
        
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

    citizen_code.prototype.load_contents = function(track_page, sortBy = '', orderBy = '') 
    {   
        var keywords = '?keywords=' + $('#keywords').val() + '&perPage=' + $('#perPage').val() + '&sortBy=' + sortBy + '&orderBy=' + orderBy;
        var urls = _baseUrl + 'accounting/fund-codes/lists';
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

    citizen_code.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.citizen_code.load_contents(1);

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#keywords', function (e) {
            $.citizen_code.load_contents(1);
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#citizen-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Citizen Code');
            modal.find('input, textarea').val('').removeClass('is-invalid');
            modal.find('select').val('').removeClass('is-invalid');
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#citizen-modal');
            _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#citizenTable .edit-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#citizen-modal');
            var _url    = _baseUrl + 'accounting/fund-codes/edit/' + _id;
            console.log(_url);
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                    });
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Citizen Code (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#citizenTable .delete-btn', function (e) {
            var id     = $(this).closest('tr').attr('data-row-id');
            var code   = $(this).closest('tr').attr('data-row-code');
            var status = $(this).closest('tr').attr('data-row-status');
            var urlz   = (status > 0) ? _baseUrl + 'accounting/fund-codes/remove/' + id : _baseUrl + 'accounting/fund-codes/restore/' + id;

            console.log(urlz);
            Swal.fire({
                html: (status > 0) ? "Are you sure? <br/>the citizen code with code ("+ code +") will be removed." : "Are you sure? <br/>the citizen code with code ("+ code +") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (status > 0) ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
            }).then(function (t) {
                t.value
                    ? 
                    $.ajax({
                        type: 'PUT',
                        url: urlz,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                    e.isConfirmed && ((t.disabled = !1));
                                    $.citizen_code.load_contents(1);
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
            $.citizen_code.load_contents(1);
        });
        
        /*
        | ---------------------------------
        | # when paginate is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.pagination li:not([class="disabled"],[class="active"])', function (e) {
            var page  = $(this).attr('p');   
            if (page > 0) {
                $.citizen_code.load_contents(page);
            }
        }); 
        
        /*
        | ---------------------------------
        | # when sorting is click
        | ---------------------------------
        */
        this.$body.on('click', '#citizenTable .sorting, #citizenTable .sorting_asc, #citizenTable .sorting_desc', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _data = _self.attr('data-row');
            if (_self.hasClass('sorting')) {
                sortBy = _data; orderBy = 'asc';
                _self.removeClass('sorting').addClass('sorting_asc');
                $.citizen_code.load_contents(1, sortBy, orderBy);
            } else if (_self.hasClass('sorting_asc')) {
                sortBy = _data; orderBy = 'desc';
                _self.removeClass('sorting_asc').addClass('sorting_desc');
                $.citizen_code.load_contents(1, sortBy, orderBy);
            } else {
                sortBy = _data; orderBy = 'asc';
                _self.removeClass('sorting_desc').addClass('sorting_asc');
                $.citizen_code.load_contents(1, sortBy, orderBy);
            }
        });
    }

    //init citizen_code
    $.citizen_code = new citizen_code, $.citizen_code.Constructor = citizen_code

}(window.jQuery),

//initializing citizen_code
function($) {
    "use strict";
    $.citizen_code.required_fields();
    $.citizen_code.init();
}(window.jQuery);