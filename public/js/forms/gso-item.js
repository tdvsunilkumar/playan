!function($) {
    "use strict";

    var itemForm = function() {
        this.$body = $("body");
    };

    var $required = 0; 

    itemForm.prototype.validate = function($form, $required)
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

    itemForm.prototype.generate_item_code = function(_code, _itemCategory = 0)
    {   
        _code.val('');
        console.log(_baseUrl + 'general-services/setup-data/item-managements/generate-item-code/' + _itemCategory);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/setup-data/item-managements/generate-item-code/' + _itemCategory,
            success: function(response) {
                console.log(response);
                _code.val(response.item_code);
            },
            async: false
        });
    },

    itemForm.prototype.submit = function(_self, _method, _action, _form, _modal, _id)
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

    itemForm.prototype.preload_uom = function(_type, _uom)
    {   
        _uom.val('');
        console.log(_baseUrl + 'general-services/setup-data/item-managements/preload-uom/' + _type);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/setup-data/item-managements/preload-uom/' + _type,
            success: function(response) {
                console.log(response);
                _uom.val(response.uom).trigger('change.select3'); 
            },
            async: false
        });
    },

    itemForm.prototype.init = function()
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
        | # keypress numeric only
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-only', function (event) {
            var charCode = (event.which) ? event.which : event.keyCode    
    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    

                return false;             
        });

        /*
        | ---------------------------------
        | # when submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#item-modal .submit-btn', function (e){
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _form    = $('form[name="itemForm"]');
            var _id      = $.item.fetchID();
            var _method  = (_id > 0) ? 'PUT' : 'POST';
            var _itemCat = _form.find('select[name="item_category_id"]').val();
            var _glAcct  = _form.find('select[name="gl_account_id"]').val();
            var _action  = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?item_category_id=' + _itemCat + '&gl_account_id=' + _glAcct : _form.attr('action') + '/store?item_category_id=' + _itemCat + '&gl_account_id=' + _glAcct;
            var _error   = $.itemForm.validate(_form, 0);

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
                    html: '<span style="color: red;">This will save the current changes.</span>',
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
                            if (_id <= 0) {
                            Swal.fire({
                                html: "Details were no longer editable<br/>after confirmation of the changes:<br/><br/>1) Item Category<br/>2) Item Code<br/><br/>Continue by the way?",
                                icon: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonText: "Yes, continue",
                                cancelButtonText: "No, return",
                                customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
                            }).then(function (t) {
                                t.value
                                    ? 
                                    $.itemForm.submit(_self, _method, _action, _form, _modal, _id)
                                    : "cancel" === t.dismiss 
                            });
                        } else {
                            $.itemForm.submit(_self, _method, _action, _form, _modal, _id);
                        }
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                
            }
        });
        
        /*
        | ---------------------------------
        | # when item category on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="item_category_id"]', function (event) {
            var _self  = $(this);
            var _modal = _self.closest('.modal');
            var _code  = _modal.find('input[name="code"]');
            $.itemForm.generate_item_code(_code, _self.val());
            $.item.fetch_group_code(_self.val(), _modal);
        });

        /*
        | ---------------------------------
        | # when item weighted cost onkeyup/blur
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="weighted_cost"]', function (event) {
            var _self = $(this);
            var _latest = $('input[name="latest_cost"]');
            _latest.val(_self.val());
        });
        this.$body.on('blur', 'input[name="weighted_cost"]', function (event) {
            var _self = $(this);
            var _latest = $('input[name="latest_cost"]');
            _latest.val(_self.val());
        });
        /*
        | ---------------------------------
        | # when item latest cost onkeyup/blur
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="latest_cost"]', function (event) {
            var _self = $(this);
            var _weighted = $('input[name="weighted_cost"]');
            _weighted.val(_self.val());
        });
        this.$body.on('blur', 'input[name="latest_cost"]', function (event) {
            var _self = $(this);
            var _weighted = $('input[name="weighted_cost"]');
            _weighted.val(_self.val());
        });

        /*
        | ---------------------------------
        | # when add breakdown button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#item-conversion-modal .submit-btn', function (e) {
            var _self         = $(this);
            var _modal        = _self.closest('.modal');
            var _form         = _modal.find('form');
            var _error        = $.itemForm.validate(_form, 0);
            var _id           = $.item.fetchID();
            var _conversionID = $.item.fetchConversionID();
            var _method       = (_conversionID > 0) ? 'PUT' : 'POST';
            var _action       = (_conversionID > 0) ? _baseUrl + 'general-services/setup-data/item-managements/update-conversion/' + _conversionID : _form.attr('action') + '/' + _id;

            console.log(_action);
            _self.prop('disabled', true).html('Wait.....');
            if (_error != 0) {
                _self.prop('disabled', false).html('Save Changes');
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
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('Save Changes').prop('disabled', false);
                                _modal.modal('hide');
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('Save Changes').prop('disabled', false);
                            _form.find('select[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('.m-form__help').text(response.label);
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
        | # when item type onchange
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="item_type_id"]', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            $.itemForm.preload_uom(_self.val(), _modal.find('#uom_id'));
        });
    }

    //init itemForm
    $.itemForm = new itemForm, $.itemForm.Constructor = itemForm

}(window.jQuery),

//initializing itemForm
function($) {
    "use strict";
    $.itemForm.init();
}(window.jQuery);
