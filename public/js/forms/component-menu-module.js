!function($) {
    "use strict";

    var menu_moduleForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    menu_moduleForm.prototype.validate = function($form, $required)
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

    menu_moduleForm.prototype.init = function()
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
        | # input name on keyup
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _group = _form.find('select[name="menu_group_id"]');
            var _text  = (_group.val() > 0) ? _form.find('select[name="menu_group_id"] option:selected').text().replace('&', 'and') + '-' : '';
                _text += _self.val().replace('&', 'and');

            _form.find('input[name="code"]').val(_text  .replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_self.val().replace(/\s+/g, '-').toLowerCase());
        });
        this.$body.on('blur', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _group = _form.find('select[name="menu_group_id"]');
            var _text  = (_group.val() > 0) ? _form.find('select[name="menu_group_id"] option:selected').text().replace('&', 'and') + '-' : '';
                _text += _self.val().replace('&', 'and');

            _form.find('input[name="code"]').val(_text  .replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_self.val().replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # select group on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="menu_group_id"]', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _text  = (_self.val() > 0) ? _self.find('option:selected').text().replace('&', 'and') + '-' : '';
                _text += _form.find('input[name="name"]').val().replace('&', 'and');

            _form.find('input[name="code"]').val(_text  .replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_form.find('input[name="name"]').val().replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('span.variables').text();
            var _form   = $('form[name="moduleMenuForm"]');
            var _id     = $.menu_module.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?code=' + _form.find('input[name="code"]').val() : _form.attr('action') + '/store?code=' + _form.find('input[name="code"]').val();
            var _error  = $.menu_moduleForm.validate(_form, 0);

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
                    html: "Are you sure? <br/>the Module Menu with code ("+ _code +")<br/>will be saved.",
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
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                $.menu_module.load_contents();
                                                _modal.modal('hide');
                                            }
                                        );
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _form.find('input[name="' + response.column + '"]').addClass('is-invalid').next().text(response.label);
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
            }
        });
    }

    //init menu_moduleForm
    $.menu_moduleForm = new menu_moduleForm, $.menu_moduleForm.Constructor = menu_moduleForm

}(window.jQuery),

//initializing menu_moduleForm
function($) {
    "use strict";
    $.menu_moduleForm.init();
}(window.jQuery);
