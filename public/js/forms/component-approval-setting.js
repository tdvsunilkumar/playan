!function($) {
    "use strict";

    var approval_settingForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    approval_settingForm.prototype.validate = function($form, $required)
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
    
    approval_settingForm.prototype.reload_sub_module = function(_module)
    {   
        var _subModule = $('#sub_module_id'); _subModule.find('option').remove(); 
        console.log(_baseUrl + 'components/approval-settings/reload-sub-module/' + _module);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'components/approval-settings/reload-sub-module/' + _module,
            success: function(response) {
                console.log(response.data);
                if (response.data.length > 0) {
                    _subModule.append('<option value="">select a sub module</option>');  
                    $.each(response.data, function(i, item) {
                        _subModule.append('<option value="' + item.id + '">' + item.name + '</option>');  
                    }); 
                    _subModule.closest('.form-group').addClass('required');
                    $.approval_setting.required_fields();
                } else {
                    _subModule.closest('.form-group').removeClass('required');
                    $.approval_setting.required_fields();
                }
            },
            async: false
        });
        // $.approval_setting.preload_select3();
        // $('.m_selectpicker').selectpicker('refresh');
    },

    approval_settingForm.prototype.init = function()
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
            var _text = _self.val().replace('&', 'and');
            _form.find('input[name="code"]').val(_text.replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_text.replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # when module is change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="module_id"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            $.approval_settingForm.reload_sub_module(_self.val());
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
            var _form   = $('form[name="approvalSettingForm"]');
            var _id     = $.approval_setting.fetchID();
            var _method = 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id  : _form.attr('action') + '/store';
            var _error  = $.approval_settingForm.validate(_form, 0);

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
                    html: "Are you sure? <br/>the Approval Setting ("+ _code +")<br/>will be saved.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, save it!" ,
                    cancelButtonText: "No, return",
                    allowOutsideClick: false,
                    customClass: { confirmButton: "btn btn-primary text-white", cancelButton: "btn btn-active-light" },
                }).then(function (t) {
                    if (t.value) {
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
                                                $.approval_setting.load_contents();
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
                        });
                    } else if ("cancel" === t.dismiss) {
                        _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                    }
                });
            }
        });
    }

    //init approval_settingForm
    $.approval_settingForm = new approval_settingForm, $.approval_settingForm.Constructor = approval_settingForm

}(window.jQuery),

//initializing approval_settingForm
function($) {
    "use strict";
    $.approval_settingForm.init();
}(window.jQuery);
