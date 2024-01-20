!function($) {
    "use strict";

    var templateForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    templateForm.prototype.validate = function($form, $required)
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

    templateForm.prototype.reload_module = function(_group, _module)
    {   
        _module.find('option').remove(); 
        console.log(_baseUrl + 'components/sms-notifications/templates/reload-module?group=' + _group);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'components/sms-notifications/templates/reload-module?group=' + _group,
            success: function(response) {
                _module.append('<option value="">select a module</option>');  
                if (response.data.length > 0) {    
                    $.each(response.data, function(i, item) {
                        _module.append('<option value="' + item.id + '">' + item.name + '</option>');  
                    }); 
                } else {
                    _module.closest('.form-group').removeClass('required');
                }
                $.template.required_fields();
            },
            async: false
        });
    },

    templateForm.prototype.reload_sub_module = function(_group, _module, _submodule)
    {   
        _submodule.find('option').remove(); 
        console.log(_baseUrl + 'components/sms-notifications/templates/reload-sub-module?group=' + _group + '&module=' + _module);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'components/sms-notifications/templates/reload-sub-module?group=' + _group + '&module=' + _module,
            success: function(response) {
                _submodule.append('<option value="">select a sub module</option>');  
                if (response.data.length > 0) {                    
                    $.each(response.data, function(i, item) {
                        _submodule.append('<option value="' + item.id + '">' + item.name + '</option>');  
                    }); 
                    _submodule.closest('.form-group').addClass('required');
                } else {
                    _submodule.closest('.form-group').removeClass('required');
                }
                $.template.required_fields();
            },
            async: false
        });
    },

    templateForm.prototype.insertAtCaret = function(areaId, text) {
        var txtarea = document.getElementById(areaId);
        if (!txtarea) {
          return;
        }
      
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
          "ff" : (document.selection ? "ie" : false));
        if (br == "ie") {
          txtarea.focus();
          var range = document.selection.createRange();
          range.moveStart('character', -txtarea.value.length);
          strPos = range.text.length;
        } else if (br == "ff") {
          strPos = txtarea.selectionStart;
        }
      
        var front = (txtarea.value).substring(0, strPos);
        var back = (txtarea.value).substring(strPos, txtarea.value.length);
        txtarea.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
          txtarea.focus();
          var ieRange = document.selection.createRange();
          ieRange.moveStart('character', -txtarea.value.length);
          ieRange.moveStart('character', strPos);
          ieRange.moveEnd('character', 0);
          ieRange.select();
        } else if (br == "ff") {
          txtarea.selectionStart = strPos;
          txtarea.selectionEnd = strPos;
          txtarea.focus();
        }
      
        txtarea.scrollTop = scrollPos;
        $('#template').closest(".form-group").find(".m-form__help").text("");
        $.templateForm.auto_textarea();
    },

    templateForm.prototype.auto_textarea = function()
    {
        var form = $('body form[name="template"]');
        form.find('textarea').attr( "height", function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    },

    templateForm.prototype.init = function()
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
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="smsTemplateForm"]');
            var _id     = $.template.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.templateForm.validate(_form, 0);

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
                                        $.template.load_groups();
                                        $.template.load_contents();
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
        | # when group has changed
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="group_id"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            $.templateForm.reload_module(_self.val(), $('#module_id'));
            $.template.required_fields();
        });

        /*
        | ---------------------------------
        | # when module has changed
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="module_id"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            $.templateForm.reload_sub_module($('#group_id').val(), _self.val(), $('#sub_module_id'));
            $.template.required_fields();
        });

        /*
        | ---------------------------------
        | # when codex btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.codex-btn', function (e) {
            var self = $(this);
            var presets = self.attr('values');
            $.templateForm.insertAtCaret('template', presets);
        });
    }

    //init templateForm
    $.templateForm = new templateForm, $.templateForm.Constructor = templateForm

}(window.jQuery),

//initializing templateForm
function($) {
    "use strict";
    $.templateForm.init();
}(window.jQuery);
