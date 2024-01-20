!function($) {
    "use strict";

    var user_accountForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    user_accountForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each(this.$body.find("input[type='date'], input[type='text'], input[type='password'], input[type='email'], select, textarea"), function(){
               
            if (!($(this).attr("name") === undefined || $(this).attr("name") === null)) {
                if($(this).hasClass("required")){
                    if($(this).is("[multiple]")){
                        if( !$(this).val() || $(this).find('option:selected').length <= 0 ){
                            $(this).addClass('is-invalid');
                            $required++;
                        }
                    } else if($(this).val()==""){
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


    user_accountForm.prototype.isValidEmailAddress = function () {
        var emailError = 0;
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

        $.each(this.$body.find("input[type='email']"), function(){
            var $self = $(this);
            if ($self.val() != '') {
                var validEmail = pattern.test($self.val());

                if (!validEmail) {
                    emailError++;
                    $self.next().text('this is not a valid email');
                }
            }
        });

        return emailError;
    },

    user_accountForm.prototype.pretype_password = function() {
        $("#password, #confirm_password").attr("type", "password");
    },

    user_accountForm.prototype.init = function()
    {   
        $.user_accountForm.pretype_password();

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
            _self.closest(".form-group").find(".is-invalid").removeClass("is-invalid");
        });

        /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _form = _self.closest('form');
            var _text = _self.val();
            _form.find('input[name="code"]').val(_text.replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # when role on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="employee_id"]', function (e) {
            var _self   = $(this);
            var _modal  = $('#user-account-modal');
            var _userID = $.user_account.fetchID();

            if (_self.val() > 0) {
                _modal.find('input[name="name"]').val(_self.find('option:selected').text());
            } else {
                _self.val('').trigger('change.select3');  
                _modal.find('input[name="name"]').val('');
            }
        });

        /*
        | ---------------------------------
        | # when role on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="role_id"]', function (e) {
            var _self   = $(this);
            var _modal  = $('#user-account-modal');
            var _userID = $.user_account.fetchID();

            if (_self.val() > 0) {
                var d1 = $.user_account.load_menus(_self.val(), _userID, _modal);
                // $.when( d1 ).done(function ( v1 ) 
                // {   
                //     _modal.modal('show');
                // });
            } else {
                _self.val('').trigger('change.select3');  
                _modal.find('#result-layer').addClass('hidden');
            }
        });

        /*
        | ---------------------------------
        | # when permission on click
        | ---------------------------------
        */
        this.$body.on('click', '#user-account-modal ul.modules .permissions input[type="checkbox"]', function (e) {
            var _self = $(this);
            var _parent = _self.closest('li');
            var _accordion = _self.closest('.accordion-item');
            if (_parent.find('.permissions input[type="checkbox"]:checked').length > 0) {
                _parent.find('input[name="module[]"').prop('checked', true);
            } else {
                _parent.find('input[name="module[]"').prop('checked', false);
            }
            if(_self.closest('.accordion-collapse input[type="checkbox"]:checked').length > 0) {
                _accordion.find('input[name="group[]"]').prop('checked', true);
            } else {
                _accordion.find('input[name="group[]"]').prop('checked', false);
            }
        });
        this.$body.on('click', '#user-account-modal ul.sub-modules .permissions input[type="checkbox"]', function (e) {
            var _self = $(this);
            var _parent = _self.closest('li');
            var _accordion = _self.closest('.accordion-item');
            if (_parent.find('.permissions input[type="checkbox"]:checked').length > 0) {
                _parent.find('input[name="sub_module[]"]').prop('checked', true);
            } else {
                _parent.find('input[name="sub_module[]"]').prop('checked', false);
            }
            if(_self.closest('.accordion-collapse input[type="checkbox"]:checked').length > 0) {
                _accordion.find('input[name="group[]"]').prop('checked', true);
            } else {
                _accordion.find('input[name="group[]"]').prop('checked', false);
            }
        });
        this.$body.on('click', '#user-account-modal .accordion-body .mb-2 input[type="checkbox"]', function (e) {
            var _self = $(this);
            var _accordion = _self.closest('.accordion-item');
            if(_accordion.find('.accordion-collapse input[type="checkbox"]:checked').length > 0) {
                _accordion.find('input[name="group[]"]').prop('checked', true);
            } else {
                _accordion.find('input[name="group[]"]').prop('checked', false);
            }
        });
        this.$body.on('click', '#user-account-modal input[name="module[]"], #user-account-modal input[name="sub_module[]"]', function (e) {
            var _self = $(this);
            var _accordion = _self.closest('.accordion-item');
            if(_accordion.find('.accordion-collapse input[type="checkbox"]:checked').length > 0) {
                _accordion.find('input[name="group[]"]').prop('checked', true);
            } else {
                _accordion.find('input[name="group[]"]').prop('checked', false);
            }
        });


        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $.user_account.fetchID();
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.variables').text();
            var _form   = _modal.find('form[name="userAccountForm"]');
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?name=' + _form.find('input[name="name"]').val() : _form.attr('action') + '/store?name=' + _form.find('input[name="name"]').val();
            var _error  = $.user_accountForm.validate(_form, 0);
            var _emailError = $.user_accountForm.isValidEmailAddress();

            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if (_emailError > 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please use a valid email.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if ( _form.find('#password').val() !== _form.find('#confirm_password').val() ) {
                _form.find('#confirm_password').closest(".form-group").find(".m-form__help").text("confirm password does not match.");
                Swal.fire({
                    title: "Oops...",
                    html: "Password mismatch.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;
            } else {
                _self.html('wait.....').prop('disabled', true).addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                Swal.fire({
                    html: "Are you sure? <br/>the User Account with email ("+ _code +")<br/>will be saved.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, save it!" ,
                    cancelButtonText: "No, return",
                    allowOutsideClick: false,
                    customClass: { confirmButton: "btn btn-primary text-white", cancelButton: "btn btn-active-light" },
                }).then(function (t) {
                    if (t.value) {
                        _self.html('wait.....').prop('disabled', true).addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('Save Changes').prop('disabled', false);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                $.user_account.load_contents();
                                                _modal.modal('hide');
                                            }
                                        );
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Save Changes').prop('disabled', false);
                                    _form.find('select[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
                                    _form.find('input[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
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
                        })
                    } else if ("cancel" === t.dismiss) {
                        _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false); 
                    }
                });

                // _self.html('wait.....').prop('disabled', true).addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                // $.ajax({
                //     type: _method,
                //     url: _action,
                //     data: _form.serialize(),
                //     success: function(response) {
                //         console.log(response);
                //         if (response.type == 'success') {
                //             setTimeout(function () {
                //                 _self.html('Save Changes').prop('disabled', false);
                //                 Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                //                     function (e) {
                //                         $.user_account.load_contents();
                //                         _modal.modal('hide');
                //                     }
                //                 );
                //             }, 500 + 300 * (Math.random() * 5));
                //         } else {
                //             _self.html('Save Changes').prop('disabled', false);
                //             _form.find('select[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
                //             _form.find('input[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
                //             Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                //                 function (e) {
                //                 }
                //             );
                //         }
                //     }, 
                //     complete: function() {
                //         window.onkeydown = null;
                //         window.onfocus = null;
                //     }
                // });
            }
        });

         /*
        | ---------------------------------
        | # when dash permission submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn-dash', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _id     = $.user_account.fetchID();
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="userAccountDashForm"]');
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/updateDash/' + _id : _form.attr('action') + '/storeDash';
            // var _error  = $.user_accountForm.validate(_form, 0);
                _self.html('wait.....').prop('disabled', true).addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.user_account.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('Save Changes').prop('disabled', false);
                            _form.find('select[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
                            _form.find('input[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('span.m-form__help').text(response.text);
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
            
        });
    }

    //init user_accountForm
    $.user_accountForm = new user_accountForm, $.user_accountForm.Constructor = user_accountForm

}(window.jQuery),

//initializing user_accountForm
function($) {
    "use strict";
    $.user_accountForm.init();
}(window.jQuery);
