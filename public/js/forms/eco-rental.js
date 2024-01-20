!function($) {
    "use strict";

    var ecoRentalForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _classID = 0, _multiplier = 0, _multiplierAmt = 0, _excess = 0, _excessAmt = 0, _discount = 0;

    ecoRentalForm.prototype.validate = function($form, $required)
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

    ecoRentalForm.prototype.fetch_data = function(_key, _requestor = 0)
    {   
        var _value = '';
        console.log(_baseUrl + 'economic-and-investment/rental-application/fetch-data/' + _requestor + '?key=' + _key);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/rental-application/fetch-data/' + _requestor + '?key=' + _key,
            success: function(response) {
                _value = response.data;
                console.log($.trim(_value));
            },
            async: false
        });

        return _value;
    },

    ecoRentalForm.prototype.reload_reception_class = function(_id, _modal)
    {   
        var _lot = _modal.find('select[name="reception_class_id"]');  _lot.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/rental-application/reload-reception-class/'+ _id +'?location=' + _modal.find('select[name="location_id"]').val() + '&reception=' + _modal.find('select[name="reception_id"]').val();
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _lot.append('<option value="">select a reception class</option>');  
                $.each(response.data, function(i, item) {
                    _lot.append('<option value="' + item.id + '"> ' + item.eatd_process_type + '</option>');  
                }); 
            },
            async: false
        });
    },
    
    ecoRentalForm.prototype.reload_reception_name = function(_modal)
    {   
        var _cementery = _modal.find('select[name="reception_id"]');  _cementery.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/rental-application/reload-reception-name?location=' + _modal.find('select[name="location_id"]').val();
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _cementery.append('<option value="">select a reception</option>');  
                $.each(response.data, function(i, item) {
                    _cementery.append('<option value="' + item.id + '"> ' + item.reception_name + '</option>');  
                }); 
            },
            async: false
        });
    },

    ecoRentalForm.prototype.fetchMultiplier = function()
    {
        return _multiplier;
    }

    ecoRentalForm.prototype.updateMultiplier = function(_val)
    {
        return _multiplier = _val;
    }

    ecoRentalForm.prototype.fetchMultiplierAmt = function()
    {
        return _multiplierAmt;
    }

    ecoRentalForm.prototype.updateMultiplierAmt = function(_val)
    {
        return _multiplierAmt = _val;
    }

    ecoRentalForm.prototype.fetchExcess = function()
    {
        return _excess;
    }

    ecoRentalForm.prototype.updateExcess = function(_val)
    {
        return _excess = _val;
    }

    ecoRentalForm.prototype.fetchExcessAmt = function()
    {
        return _excessAmt;
    }

    ecoRentalForm.prototype.updateExcessAmt = function(_val)
    {
        return _excessAmt = _val;
    }

    ecoRentalForm.prototype.fetch_multiplier_amount = function(_modal)
    {
        var _url = _baseUrl + 'economic-and-investment/rental-application/fetch-multiplier-amount?location=' + _modal.find('select[name="location_id"]').val() + '&reception=' + _modal.find('select[name="reception_id"]').val() + '&reception_class=' + $.trim(_modal.find('select[name="reception_class_id"] option:selected').text());
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _multiplier = response.data.multiplier;
                console.log('multiplier: ' + _multiplier);
                _multiplierAmt = response.data.multiplier_amount;
                console.log('multiplier amount: ' + _multiplierAmt);
                _excess = response.data.excess;
                console.log('excess: ' + _excess);
                _excessAmt = response.data.excess_amount;
                console.log('excess amount: ' + _excessAmt);
            },
            async: false
        });

        return true;
    },

    ecoRentalForm.prototype.compute = function(_modal)
    {
        var _free = _modal.find('input[name="is_free"]:checked').val();
        var _value = (_modal.find('input[name="reception_class_value"]').val() != '') ? _modal.find('input[name="reception_class_value"]').val() : 0;
        var _fees = _modal.find('input[name="total_amount"]');

        if (_free > 0) {
            _fees.val(0);
        } else {
            if (parseFloat(_value) > 0) {
                if (parseFloat(_excess) > 0) {
                    if (_value > _multiplier) {
                        var _total = parseFloat(_multiplierAmt) + parseFloat(parseFloat(parseFloat(parseFloat(_value) - parseFloat(_multiplier)) / parseFloat(_excess)) * parseFloat(_excessAmt));
                        console.log(1);
                        if (_discount > 0) {                      
                            _fees.val(parseFloat(_total) - parseFloat(_total * _discount));
                        } else {
                            _fees.val(_total );
                        }
                    } else {
                        console.log(2);
                        if (_discount > 0) {
                            _fees.val(parseFloat(_multiplierAmt) - parseFloat(_multiplierAmt * _discount));
                        } else {
                            _fees.val(_multiplierAmt);
                        }
                    }
                } else {
                    var _total = parseFloat(parseFloat(_value) * parseFloat(_multiplier)) * parseFloat(_multiplierAmt);
                    console.log(3);
                    if (_discount > 0) {
                        console.log('total: ' + parseFloat(_total) + ', discount: ' +  parseFloat(_total * _discount));
                        _fees.val(parseFloat(_total) - parseFloat(_total * _discount));
                    } else {
                        _fees.val(_total);
                    }
                }
            } else {
                _fees.val('');
            }
        }
    }

    ecoRentalForm.prototype.fetch_discount = function(_modal, _discounts)
    {
        _discount = 0;
        var _url = _baseUrl + 'economic-and-investment/rental-application/fetch-discount/' + _discounts;
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                _discount = response.discount;
            },
            async: false
        });

        return true;
    },

    ecoRentalForm.prototype.init = function()
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
        | # keypress numeric only
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-only', function (event) {
            var charCode = (event.which) ? event.which : event.keyCode    

            if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
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
                    }t
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
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', '#requestor_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() != '') {
                var d1 = $.ecoRentalForm.fetch_data('full_address', _self.val());
                var d2 = $.ecoRentalForm.fetch_data('contact_no', _self.val());
                $.when( d1, d2 ).done(function (v1, v2) { 
                    _modal.find('input[name="full_address"]').val(v1);
                    _modal.find('input[name="contact_no"]').val(v2);
                });
            }   
        });

        /*
        | ---------------------------------
        | # when reception location on change
        | ---------------------------------
        */
        this.$body.on('change', '#location_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.ecoRental.fetchID();
            $.ecoRentalForm.reload_reception_name(_modal);
            $.ecoRentalForm.reload_reception_class(_id, _modal);
        });

        /*
        | ---------------------------------
        | # when reception name on change
        | ---------------------------------
        */
        this.$body.on('change', '#reception_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.ecoRental.fetchID();
            $.ecoRentalForm.reload_reception_class(_id, _modal);
        });

        /*
        | ---------------------------------
        | # when reception class on change
        | ---------------------------------
        */
        this.$body.on('change', '#reception_class_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var d1 = $.ecoRentalForm.fetch_multiplier_amount(_modal);
            $.when( d1 ).done(function (v1) { 
                $.ecoRentalForm.compute(_modal);
            });
        });

        /*
        | ---------------------------------
        | # when reception class on change
        | ---------------------------------
        */
        this.$body.on('keyup', '#reception_class_value', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            $.ecoRentalForm.compute(_modal);
            _modal.find('input[name="total_amount"]').removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            _modal.find('input[name="total_amount"]').closest(".form-group").find(".is-invalid").removeClass("is-invalid");
        });
        this.$body.on('blur', '#reception_class_value', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            $.ecoRentalForm.compute(_modal);
        });

        /*
        | ---------------------------------
        | # when free service on change
        | ---------------------------------
        */
        this.$body.on('click', 'input[name="is_free"]', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() > 0) {
                _modal.find('input[name="total_amount"]').val(0);
                _modal.find('select[name="discount_id"].select3').val('').prop('disabled', true).trigger('change.select3'); 
            } else {
                _modal.find('select[name="discount_id"].select3').val('').prop('disabled', false).trigger('change.select3'); 
                _discount = 0;
                $.ecoRentalForm.compute(_modal);
            }
        });

        /*
        | ---------------------------------
        | # when discount on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="discount_id"]', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() > 0) {
                $.ecoRentalForm.fetch_discount(_modal, _self.val());
            } else {
                _discount = 0;
            }
            $.ecoRentalForm.compute(_modal);
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
            var _form   = _modal.find('form[name="ecoRentalForm"]');
            var _id     = $.ecoRental.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?total_amount=' + _form.find('input[name="total_amount"]').val() + '&reception_class_text=' + $.trim(_form.find('select[name="reception_class_id"] option:selected').text()) : _form.attr('action') + '/store?total_amount=' + _form.find('input[name="total_amount"]').val() + '&reception_class_text=' + $.trim(_form.find('select[name="reception_class_id"] option:selected').text());
            var _error  = $.ecoRentalForm.validate(_form, 0);

            var d1 = $.ecoRental.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
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
                        _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    if (_id <= 0) {
                                        $.ecoRental.updateID(response.data.id);
                                        _modal.find('input[name="transaction_no"]').val(response.data.transaction_no);
                                    }
                                    setTimeout(function () {
                                        _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                // _modal.modal('hide');
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
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The request is already been processed.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;  
                }
            });
        });
    }

    //init ecoRentalForm
    $.ecoRentalForm = new ecoRentalForm, $.ecoRentalForm.Constructor = ecoRentalForm

}(window.jQuery),

//initializing ecoRentalForm
function($) {
    "use strict";
    $.ecoRentalForm.init();
}(window.jQuery);
