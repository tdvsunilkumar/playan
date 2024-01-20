!function($) {
    "use strict";

    var user_roleForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    user_roleForm.prototype.validate = function($form, $required)
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

    user_roleForm.prototype.init = function()
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
        | # when permission on click
        | ---------------------------------
        */
        this.$body.on('click', '#user-role-modal ul.modules .permissions input[type="checkbox"]', function (e) {
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
        this.$body.on('click', '#user-role-modal ul.sub-modules .permissions input[type="checkbox"]', function (e) {
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
        this.$body.on('click', '#user-role-modal .accordion-body .mb-2 input[type="checkbox"]', function (e) {
            var _self = $(this);
            var _accordion = _self.closest('.accordion-item');
            if(_accordion.find('.accordion-collapse input[type="checkbox"]:checked').length > 0) {
                _accordion.find('input[name="group[]"]').prop('checked', true);
            } else {
                _accordion.find('input[name="group[]"]').prop('checked', false);
            }
        });
        this.$body.on('click', '#user-role-modal input[name="module[]"], #user-role-modal input[name="sub_module[]"]', function (e) {
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
            var _id     = $.user_role.fetchID();
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.variables').text();
            var _form   = _modal.find('form[name="userRoleForm"]');
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.user_roleForm.validate(_form, 0);

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
            } else {
                _self.html('wait.....').prop('disabled', true).addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                Swal.fire({
                    html: "Are you sure? <br/>the user role with code ("+ _code +")<br/>will be saved.",
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
                                                $.user_role.load_contents();
                                                _modal.modal('hide');
                                            }
                                        );
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Save Changes').prop('disabled', false);
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
                //                         $.user_role.load_contents();
                //                         _modal.modal('hide');
                //                     }
                //                 );
                //             }, 500 + 300 * (Math.random() * 5));
                //         } else {
                //             _self.html('Save Changes').prop('disabled', false);
                //             _form.find('input[name="code"]').addClass('is-invalid').next().text('This is an existing code.');
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
    }

    //init user_roleForm
    $.user_roleForm = new user_roleForm, $.user_roleForm.Constructor = user_roleForm

}(window.jQuery),

//initializing user_roleForm
function($) {
    "use strict";
    $.user_roleForm.init();
}(window.jQuery);
