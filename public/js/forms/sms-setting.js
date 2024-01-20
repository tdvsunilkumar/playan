!function($) {
    "use strict";

    var settingForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var $taxpayers = []; var $citizens = []; var $employees = []; var $users = [];

    settingForm.prototype.required_fields = function() {
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
                $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                $(this).find('.m-form__help').text('');      
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    settingForm.prototype.validate = function($form, $required)
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

    settingForm.prototype.perfect_scrollbar = function()
    {
        if ($(".m-scrollable")) {
            $.each($('.m-scrollable'), function(_i = 0){
                _i++;
                $(this).attr('id', '_table' + _i);
                var _divID = '#' + $(this).attr('id');
                var px = new PerfectScrollbar(_divID, {
                    wheelSpeed: 0.5,
                    swipeEasing: 0,
                    wheelPropagation: 1,
                    minScrollbarLength: 100,
                });
            });
        }

    },

    settingForm.prototype.update = function(_form)
    {   
        var _check = (_form.find('input[name="is_enabled"]:checked').length > 0) ? 1 : 0
        $.ajax({
            type: 'PUT',
            url: _form.attr('action') + '?enabled=' + _check,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
            },
        });
    },

    settingForm.prototype.init = function()
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
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'form[name="smsSettingForm"] .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form');
            var _id     = $.setting.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.settingForm.validate(_form, 0);

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
				Swal.fire({
					title: "Are you sure?",
					html: '<span>Some Details may not be editable after saving</span>',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText: 'No',
					reverseButtons: true,
					customClass: {
						confirmButton: 'btn btn-success',
						cancelButton: 'btn btn-danger'
					},
					buttonsStyling: false
				}).then((result) => {
                if (result.isConfirmed) {
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
                                        $.setting.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
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
					} else {
						console.log("Form submission canceled");
					}
				});
            }            
        });

        /*
        | ---------------------------------
        | # when sms setting is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'form[name="smsServerSettingForm"] input', function (e){
            var _form = $(this).closest('form');
            $.settingForm.update(_form);
        });
    }

    //init settingForm
    $.settingForm = new settingForm, $.settingForm.Constructor = settingForm

}(window.jQuery),

//initializing settingForm
function($) {
    "use strict";
    $.settingForm.init();
}(window.jQuery);
