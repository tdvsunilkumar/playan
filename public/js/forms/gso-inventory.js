!function($) {
    "use strict";

    var inventoryForm = function() {
        this.$body = $("body");
    };

    var $required = 0; 

    inventoryForm.prototype.validate = function($form, $required)
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

    inventoryForm.prototype.generate_item_code = function(_code, _itemCategory = 0)
    {   
        _code.val('');
        console.log(_baseUrl + 'general-services/inventory/generate-item-code/' + _itemCategory);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/inventory/generate-item-code/' + _itemCategory,
            success: function(response) {
                console.log(response);
                _code.val(response.item_code);
            },
            async: false
        });
    },

    inventoryForm.prototype.submit = function(_self, _method, _action, _form, _modal, _id)
    {
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
                                if (_id <= 0) {
                                    $.item.updateID(response.data.id);
                                    _form.find('[name="item_category_id"]').prop('disabled', true);
                                }
                                $.item.load_contents(1);
                                $.item.load_file_contents();
                                _modal.modal('hide');
                                _modal.find('.upload-row').removeClass('hidden')
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
                window.onkeydown = null
                window.onfocus = null
            }
        })

        return true;
    },

    inventoryForm.prototype.init = function()
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
        | # when submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#item-adjustment-modal .send-btn', function (e){
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _form    = _modal.find('form');
            var _toast   = $('#indexToast');
            var _id      = $.inventory.fetchID();
            var _action  = _baseUrl + 'general-services/inventory/send/' + _id;
            var _error   = $.inventoryForm.validate(_form, 0);

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
                _self.prop('disabled', true).html('wait.....');
                Swal.fire({
                    html: "Are you sure? <br/>the request will be sent.",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, send it!",
                    cancelButtonText: "No, return",
                    customClass: { confirmButton: "btn btn-blue", cancelButton: "btn btn-active-light" },
                }).then(function (t) {
                    t.value
                        ? 
                        $.ajax({
                            type: 'POST',
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.status == 'success') {
                                    setTimeout(function () {
                                        _toast.find('.toast-body').html(response.text);
                                        _toast.show();
                                        _modal.modal('hide');
                                    }, 500 + 300 * (Math.random() * 5));
                                    setTimeout(function () {
                                        _self.prop('disabled', false).html('Send Adjustment');
                                        _toast.hide();
                                    }, 3000);
                                } else {
                                    Swal.fire({
                                        title: "Oops...",
                                        html: "Invalid Request!",
                                        icon: "error",
                                        type: "danger",
                                        showCancelButton: false,
                                        closeOnConfirm: true,
                                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                                    });
                                    window.onkeydown = null;
                                    window.onfocus = null;    
                                }
                            },
                            complete: function() {
                                window.onkeydown = null;
                                window.onfocus = null;
                            }
                        })
                        : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Send Adjustment') : ''
                });
            }
        });
    }

    //init inventoryForm
    $.inventoryForm = new inventoryForm, $.inventoryForm.Constructor = inventoryForm

}(window.jQuery),

//initializing inventoryForm
function($) {
    "use strict";
    $.inventoryForm.init();
}(window.jQuery);
