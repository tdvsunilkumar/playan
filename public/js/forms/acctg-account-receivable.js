!function($) {
    "use strict";

    var accountReceivableForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    accountReceivableForm.prototype.validate = function($form, $required)
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

    accountReceivableForm.prototype.init = function()
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
        this.$body.on('keypress', '.numeric-doubles', function (event) {
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
                (text.substring(text.indexOf('.')).length > 5) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 5)) {
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
        | # when quantity onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#quantity', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _amount = _modal.find('input[name="amount"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_amount.val()) > 0) {
                var _totalAmt = parseFloat(_amount.val()) * parseFloat(_self.val());
                _total.val($.accountPayable.price_separator(parseFloat(_totalAmt).toFixed(2)));
            } else {
                _total.val($.accountPayable.price_separator(parseFloat(0).toFixed(2)));
            }
        });
        this.$body.on('blur', '#quantity', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _amount = _modal.find('input[name="amount"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_amount.val()) > 0) {
                var _totalAmt = parseFloat(_amount.val()) * parseFloat(_self.val());
                _total.val($.accountPayable.price_separator(parseFloat(_totalAmt).toFixed(2)));
            } else {
                _total.val($.accountPayable.price_separator(parseFloat(0).toFixed(2)));
            }
        });

        /*
        | ---------------------------------
        | # when amount onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#amount', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _qty = _modal.find('input[name="quantity"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_qty.val()) > 0) {
                var _totalAmt = parseFloat(_qty.val()) * parseFloat(_self.val());
                _total.val($.accountPayable.price_separator(parseFloat(_totalAmt).toFixed(2)));
            } else {
                _total.val($.accountPayable.price_separator(parseFloat(0).toFixed(2)));
            }
        });
        this.$body.on('blur', '#amount', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _qty = _modal.find('input[name="quantity"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_qty.val()) > 0) {
                var _totalAmt = parseFloat(_qty.val()) * parseFloat(_self.val());
                _total.val($.accountPayable.price_separator(parseFloat(_totalAmt).toFixed(2)));
            } else {
                _total.val($.accountPayable.price_separator(parseFloat(0).toFixed(2)));
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
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="accountReceivableForm"]');
            var _id     = $.accountPayable.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _params = '?trans_no=' + _form.find('input[name="trans_no"]').val() + '&trans_type=' + _form.find('select[name="trans_type"]').val() + '&vat_type=' + _form.find('select[name="vat_type"]').val() + '&due_date=' + _form.find('input[name="due_date"]').val() + '&ewt_id=' + _form.find('select[name="ewt_id"]').val() + '&evat_id=' + _form.find('select[name="evat_id"]').val() + '&items=' + _form.find('input[name="items"]').val() + '&gl_account_id=' + _form.find('select[name="gl_account_id"]').val() + '&quantity=' + _form.find('input[name="quantity"]').val() + '&uom_id=' + _form.find('select[name="uom_id"]').val() + '&amount=' + _form.find('input[name="amount"]').val() + '&fund_code_id=' + _form.find('select[name="fund_code_id"]').val() + '&remarks=' + _form.find('textarea[name="remarks"]').val();
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '' + _params : _form.attr('action') + '/store' + _params;
            var _error  = $.accountReceivableForm.validate(_form, 0);

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
            } else if ( !(_form.find('select[name="trans_type"]').prop('disabled') > 0) && _form.find('select[name="trans_type"]').val() == 'Purchase Order' && _method == 'POST' ) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use this trasanction!<br/>Please use/select other transaction type.",
                    icon: "warning",
                    type: "warning",
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
            }
        });
    }

    //init accountReceivableForm
    $.accountReceivableForm = new accountReceivableForm, $.accountReceivableForm.Constructor = accountReceivableForm

}(window.jQuery),

//initializing accountReceivableForm
function($) {
    "use strict";
    $.accountReceivableForm.init();
}(window.jQuery);
