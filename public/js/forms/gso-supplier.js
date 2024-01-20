!function($) {
    "use strict";
    var supplierForm = function() {
        this.$body = $("body");
    };

    var $required = 0;  var commaCounter = 2; var x, y, z;

    supplierForm.prototype.validate = function($form, $required)
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

    supplierForm.prototype.validateRow = function($row, $required)
    {   
        $required = 0;

        $.each($row.find("input[type='date'], input[type='text'], select, textarea"), function(){
               
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
                    } 
                }
            }
        });

        return $required;
    },

    supplierForm.prototype.numberSeparator = function(Number) {
        Number += '';

        for (var i = 0; i < commaCounter; i++) {
            Number = Number.replace(',', '');
        }

        x = Number.split('.');
        y = x[0];
        z = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(y)) {
            y = y.replace(rgx, '$1' + ',' + '$2');
        }
        commaCounter++;
        return y + z;
    }
    
    supplierForm.prototype.init = function()
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

        $(document).on('keypress , paste', '.number-separator', function (e) {
            if (/^-?\d*[,.]?(\d{0,3},)*(\d{3},)?\d{0,3}$/.test(e.key)) {
                $('.number-separator').on('input', function () {
                    e.target.value = $.supplierForm.numberSeparator(e.target.value);
                });
            } else {
                e.preventDefault();
                return false;
            }
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
        | # when vat type on change
        | ---------------------------------
        */
        this.$body.on('change', '#vat_type', function (event) {
            var _self = $(this);
            var _ewt  = $('#ewt_id');
            var _evat = $('#evat_id');

            if (_self.val() == 'Vatable') {
                _evat.val(2).trigger('change.select3'); 
            } else {
                _evat.val(1).trigger('change.select3'); 
            }
        });

        /*
        | ---------------------------------
        | # when submit btn is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplier-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="supplierForm"]');
            var _id      = $.supplier.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?address=' + _form.find("#barangay_id option:selected").text() + '&evat_id=' + _form.find('#evat_id').val() : _form.attr('action') + '/store?address=' + _form.find("#barangay_id option:selected").text() + '&evat_id=' + _form.find('#evat_id').val();
            var _error  = $.supplierForm.validate(_form, 0);

            console.log(_action);
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
                                                if (_id == 0) {
                                                    _modal.find('input[name="code"]').val(response.data.code);
                                                    $.supplier.updateID(response.data.id);
                                                }
                                                $.supplier.load_contact_contents();
                                                $.supplier.load_file_contents();
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
                    } else {
                        console.log("Form submission canceled");
                    }
                });
               
            }
        });

        /*
        | ---------------------------------
        | # when submit btn is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplier-contact-modal .submit-btn', function (e){
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _form      = _modal.find('form[name="contactPersonForm"]');
            var _id        = $.supplier.fetchID();
            var _contactID = $.supplier.fetchContactID();
            var _method = (_contactID > 0) ? 'PUT' : 'POST';
            var _action = (_contactID > 0) ? _form.attr('action') + '/update/' + _contactID : _form.attr('action') + '/store/' + _id;
            var _error  = $.supplierForm.validate(_form, 0);

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
                _self.prop('disabled', true).html('Wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
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
                            _form.find('input[name="contact_person"]').addClass('is-invalid').next().text('This is an existing contact.');
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
    }

    //init supplierForm
    $.supplierForm = new supplierForm, $.supplierForm.Constructor = supplierForm

}(window.jQuery),

//initializing supplierForm
function($) {
    "use strict";
    $.supplierForm.init();
}(window.jQuery);