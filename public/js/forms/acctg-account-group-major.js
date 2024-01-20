!function($) {
    "use strict";

    var account_group_majorForm = function() {
        this.$body = $("body");
    };

    var $required = 0;
    
    account_group_majorForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $required++;
                        }
                    } else if($(this).val()=="" || $(this).val()=="0"){
                        if(!$(this).is("select")) {
                            $(this).addClass('is-invalid');
                            $required++;
                        } else {
                            $(this).addClass('is-invalid');
                            $required++;                                          
                        }
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },

    account_group_majorForm.prototype.fetch_account_group_code = function(_id, _code, _prefix)
    {
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/account-groups/edit/' + _id,
            success: function(response) {
                console.log(response.data);
                _code.val(response.data.code + _prefix.val());

            },
            async: false
        });
        
        // $('.m_selectpicker').selectpicker('refresh');
    },

    account_group_majorForm.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # select, input, and textarea on change or keyup remove error
        | ---------------------------------
        */
        this.$body.on('keyup', 'input, textarea', function (e) {
            e.preventDefault();
            var _self = $(this);
            _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
        });
        this.$body.on('change', 'select, input', function (e) {
            e.preventDefault();
            var _self = $(this);
            _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
        });

        /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-double', function (event) {
            var $this = $(this);
            if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
                ((event.which < 48 || event.which > 57) &&
                    (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }
    
            var text = $(this).val();
            if ((event.which == 46) && (text.indexOf('.') == -1)) {
                setTimeout(function () {
                    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                        $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    }
                }, 1);
            }
    
            if ((text.indexOf('.') != -1) &&
                (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
            }
        });

        /*
        | ---------------------------------
        | # when submit btn is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="majorAccountGroupForm"]');
            var _id     = _form.find('[name="id"]').val();
            var _code   = _form.find('[name="code"]').val();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?code=' + _code : _form.attr('action') + '/store?code=' + _code;
            var _error  = $.account_group_majorForm.validate(_form, 0);

            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    type: "warning",
                    icon: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.account_group_major.load_contents(1);
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                            _form.find('input[name="code"]').addClass('is-invalid').next().text('This is an existing code.');
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                }
                            );
                        }
                    }, 
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            }
        });

        /*
        | ---------------------------------
        | # fetch account code when account group onchange
        | ---------------------------------
        */
        this.$body.on('change', '#acctg_account_group_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _code = $('#code');
            var _prefix = $('#prefix');
            if (_self.val() > 0) {
                $.account_group_majorForm.fetch_account_group_code(_self.val(), _code, _prefix);
            } else {
                _code.val(_prefix.val());
            }
        });

        /*
        | ---------------------------------
        | # on prefix No when keyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#acctg_account_group_id');
            var _code = $('#code');
            if (_acctGroup.val() > 0) {
                $.account_group_majorForm.fetch_account_group_code(_acctGroup.val(), _code, _self);
            } else {
                _code.val(_self.val());
            }
        });
        this.$body.on('blur', '#prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#acctg_account_group_id');
            var _code = $('#code');
            if (_acctGroup.val() > 0) {
                $.account_group_majorForm.fetch_account_group_code(_acctGroup.val(), _code, _self);
            } else {
                _code.val(_self.val());
            }
        });
    }

    //init account_group_majorForm
    $.account_group_majorForm = new account_group_majorForm, $.account_group_majorForm.Constructor = account_group_majorForm

}(window.jQuery),

//initializing account_group_majorForm
function($) {
    "use strict";
    $.account_group_majorForm.init();
}(window.jQuery);
