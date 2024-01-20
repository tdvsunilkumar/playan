!function($) {
    "use strict";

    var voucherForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _payables = [];

    const parts = window.location.href.split('/');
    var voucherSegment = (parts.slice(-2)[0] == 'edit' || parts.slice(-2)[0] == 'view') ? parts.slice(-3)[0] : (parts.slice(-1)[0] == 'add') ? parts.slice(-2)[0] : parts.slice(-1)[0];
   
    voucherForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], input[type='file'], select, textarea"), function(){
               
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
                        $(this).closest('.form-group').find('.custom-file-label').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },

    voucherForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'treasury/journal-entries/' + voucherSegment + '/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'treasury/journal-entries/' + voucherSegment + '/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    voucherForm.prototype.update = function(_id, _form)
    {   
        var _card = $('#voucher-card');
        var _url = _baseUrl + 'treasury/journal-entries/' + voucherSegment + '/update/' + _id + '?fund_code=' + _form.find('select[name="fund_code_id"]').val() + '&payee_id=' + _form.find('select[name="payee_id"]').val() + '&remarks=' + _form.find('textarea[name="remarks"]').val();
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                if (_id <= 0) {
                    $.voucher.updateID(response.data.id);
                }
                console.log(response.data.voucher_no);
                _card.find('input[name="voucher_no"]').val(response.data.voucher_no);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    voucherForm.prototype.validateFile = function (_file)
    {   
        var _error = 0;
        var ext = _file.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif", "pdf"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
            _error = 1;
        }

        return _error;
    },

    voucherForm.prototype.init = function()
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
                (text.substring(text.indexOf('.')).length > 5) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 5)) {
                event.preventDefault();
            }
        });

        /*
        | ---------------------------------
        | # when payee on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="payee_id"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.voucher.fetchID();
            var d1    = $.voucherForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    $.voucherForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when payee on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="fund_code_id"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.voucher.fetchID();
            var d1    = $.voucherForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    $.voucherForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when vat type on change
        | ---------------------------------
        */
        this.$body.on('blur', 'textarea[name="remarks"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.voucher.fetchID();
            var d1    = $.voucherForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    $.voucherForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
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
                _total.val($.voucher.price_separator($.voucher.money_format(_totalAmt)));
            } else {
                _total.val($.voucher.price_separator($.voucher.money_format(0)));
            }
        });
        this.$body.on('blur', '#quantity', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _amount = _modal.find('input[name="amount"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_amount.val()) > 0) {
                var _totalAmt = parseFloat(_amount.val()) * parseFloat(_self.val());
                _total.val($.voucher.price_separator($.voucher.money_format(_totalAmt)));
            } else {
                _total.val($.voucher.price_separator($.voucher.money_format(0)));
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
                _total.val($.voucher.price_separator($.voucher.money_format(_totalAmt)));
            } else {
                _total.val($.voucher.price_separator($.voucher.money_format(0)));
            }
        });
        this.$body.on('blur', '#amount', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _qty = _modal.find('input[name="quantity"]');
            var _total = _modal.find('input[name="total_amount"]');
            if (parseFloat(_self.val()) > 0 && parseFloat(_qty.val()) > 0) {
                var _totalAmt = parseFloat(_qty.val()) * parseFloat(_self.val());
                _total.val($.voucher.price_separator($.voucher.money_format(_totalAmt)));
            } else {
                _total.val($.voucher.price_separator($.voucher.money_format(0)));
            }
        });

        /*
        | ---------------------------------
        | # when vat type on change
        | ---------------------------------
        */
        this.$body.on('change', '#account-payable-modal #vat_type', function (event) {
            var _self = $(this);
            var _ewt  = $('#account-payable-modal #ewt_id');
            var _evat = $('#account-payable-modal #evat_id');

            if (_self.val() == 'Vatable') {
                _evat.val(2).trigger('change.select3'); 
            } else {
                _evat.val(1).trigger('change.select3'); 
            }
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#account-payable-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="accountPayableForm"]');
            var _id     = $.voucher.fetchPayableID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _params = '?trans_no=' + _form.find('input[name="trans_no"]').val() + '&trans_type=' + _form.find('select[name="trans_type"]').val() + '&vat_type=' + _form.find('select[name="vat_type"]').val() + '&due_date=' + _form.find('input[name="due_date"]').val() + '&ewt_id=' + _form.find('select[name="ewt_id"]').val() + '&evat_id=' + _form.find('select[name="evat_id"]').val() + '&items=' + _form.find('input[name="items"]').val() + '&gl_account_id=' + _form.find('select[name="gl_account_id"]').val() + '&quantity=' + _form.find('input[name="quantity"]').val() + '&uom_id=' + _form.find('select[name="uom_id"]').val() + '&amount=' + _form.find('input[name="amount"]').val() + '&remarks=' + _form.find('textarea[name="remarks"]').val();
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '' + _params : _form.attr('action') + '/store' + _params;
            var _error  = $.voucherForm.validate(_form, 0);
            var _payablesCard = $('#payable-card');
            
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
                                _payablesCard.find('th.total-payables').text($.voucher.price_separator($.voucher.money_format(response.data.total_payables)));
                                _payablesCard.find('th.total-ewt').text($.voucher.price_separator($.voucher.money_format(response.data.total_ewt)));
                                _payablesCard.find('th.total-evat').text($.voucher.price_separator($.voucher.money_format(response.data.total_evat)));
                                var _totalAmt = $.voucher.money_format(response.data.total_payables) - $.voucher.money_format( $.voucher.money_format(response.data.total_ewt) + $.voucher.money_format(response.data.total_evat) );
                                _payablesCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(_totalAmt)));
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

        /*
        | ---------------------------------
        | # when keywords on search
        | ---------------------------------
        */
        this.$body.on('search', '#add-payable-modal #keyword2', function (event) {
            $.voucher.validate_table($('#available-payable-table'));
        });
        this.$body.on('keyup', '#add-payable-modal #keyword2', function (event) {
            var input, filter, table, tr, td1, td2, td3, td4, td5, td6, td7, td8, td9, td10, i, txtValue;
            input = document.getElementById("keyword2");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-payable-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                $('.pager').remove();
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td1  = tr[i].getElementsByTagName("td")[1];
                    td2  = tr[i].getElementsByTagName("td")[2];
                    td3  = tr[i].getElementsByTagName("td")[3];
                    td4  = tr[i].getElementsByTagName("td")[4];
                    td5  = tr[i].getElementsByTagName("td")[5];
                    td6  = tr[i].getElementsByTagName("td")[6];
                    td7  = tr[i].getElementsByTagName("td")[7];
                    td8  = tr[i].getElementsByTagName("td")[8];
                    td9  = tr[i].getElementsByTagName("td")[9];
                    td10 = tr[i].getElementsByTagName("td")[10];
                    if (td1 || td2 || td3 || td4 || td5 || td6 || td7|| td8 || td9 || td10) {
                        txtValue = td1.textContent + '' + td2.textContent + '' + td3.textContent + '' + td4.textContent + '' + td5.textContent + '' + td6.textContent + '' + td7.textContent + '' + td8.textContent + '' + td9.textContent + '' + td10.textContent  
                        || td1.innerText + td2.innerText + td3.innerText + td4.innerText + td5.innerText + td6.innerText + td7.innerText + td8.innerText + td9.innerText + td10.innerText; 
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {                            
                            tr[i].classList.remove("hidden");
                        } else {                                                     
                            tr[i].classList.add("hidden");
                            // .style.display = "none";
                        }
                    }
                }
            } else {
               $.voucher.validate_table($('#available-payable-table'));
            }
        });

        /*
        | ---------------------------------
        | # when supplier modal button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-payable-modal button', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _voucherCard = $('#voucher-card');
            var _payablesCard = $('#payable-card');
            var _modal = $('#add-payable-modal');
            var _id = $.voucher.fetchID();

            if (_payables.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.voucher.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.ajax({
                            type: 'POST',
                            url: _baseUrl + 'treasury/journal-entries/' + voucherSegment + '/add-payables/' + _id,
                            data: {'payables' : _payables},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    _payables = [];
                                    if (_id <= 0) {
                                        $.voucher.updateID(response.data.id);
                                        _voucherCard.find('input[name="voucher_no"]').val(response.data.voucher_no);
                                    }
                                    _payablesCard.find('th.total-payables').text($.voucher.price_separator($.voucher.money_format(response.data.total_payables)));
                                    _payablesCard.find('th.total-ewt').text($.voucher.price_separator($.voucher.money_format(response.data.total_ewt)));
                                    _payablesCard.find('th.total-evat').text($.voucher.price_separator($.voucher.money_format(response.data.total_evat)));
                                    var _totalAmt = $.voucher.money_format(response.data.total_payables) - $.voucher.money_format( $.voucher.money_format(response.data.total_ewt) + $.voucher.money_format(response.data.total_evat) );
                                    _payablesCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(_totalAmt)));
                                    setTimeout(function () {
                                        _self.html('Save & Close').prop('disabled', false);
                                        _modal.modal('hide');
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Save & Close').prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        _self.prop('disabled', false).html('Save & Close');
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
            } else {
                _modal.modal('hide');
            }
        });

        /*
        | ---------------------------------
        | # when payables modal checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#add-payable-modal input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.is(':checked')) {
                _modal.find('tr:not(.hidden) input[type="checkbox"]').prop('checked', true);
                $.each(_modal.find('tr:not(.hidden) input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _payables.length; i++) {
                            if (_payables[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _payables.push(checkbox.val());
                        }
                    } 
                });
                console.log(_payables);
            } else {
                _modal.find('tr:not(.hidden) input[type="checkbox"]').prop('checked', false);
                $.each(_modal.find("tr:not(.hidden) input[type='checkbox'][value!='all']"), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _payables.length; i++) {
                        if (_payables[i] == checkbox.val()) {
                            _payables.splice(i, 1);
                        }
                    }
                });
                console.log(_payables);
            }
        });
        /*
        | ---------------------------------
        | # when supplier modal checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#add-payable-modal input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _payables.push(_self.val());
            } else {
                for (var i = 0; i < _payables.length; i++) {
                    if (_payables[i] == _self.val()) {
                        _payables.splice(i, 1);
                    }
                }
            }
            console.log(_payables);
        });

        /*
        | ---------------------------------
        | # when SL on change
        | ---------------------------------
        */
        this.$body.on('change', '#add-payment-modal #sl_account_id, #add-bank-payment-modal #sl_account_id2', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() > 0) {
                $.ajax({
                    type: 'GET',
                    url: _baseUrl + 'treasury/journal-entries/' + voucherSegment + '/find-sl-bank/' + _self.val(),
                    success: function(response) {
                        console.log(response.data.bank);
                        $.each(response.data.bank, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                        });
                    }
                });
            } else {
                _modal.find('input[name="bank_name"]').val('');
                _modal.find('input[name="bank_account_no"]').val('');
                _modal.find('input[name="bank_account_name"]').val('');
            }
        });

        /*
        | ---------------------------------
        | # when SL on change
        | ---------------------------------
        */
        this.$body.on('change', '#add-payment-modal #payment_type_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() == 2) {
                _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', false).closest('.form-group').addClass('required');
            } else {
                _modal.find('input[name="cheque_date"], input[name="cheque_no"]').prop('disabled', true).closest('.form-group').removeClass('required');
            }
            $.voucher.required_fields();
        });
        
        /*
        | ---------------------------------
        | # when input file on change
        | ---------------------------------
        */
        this.$body.on('change', '#add-payment-modal .custom-file-input', function (event) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').removeClass('is-invalid').addClass("selected").html(fileName);
        }); 

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-payment-modal .submit-btn', function (e){
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _form    = _modal.find('form[name="paymentsForm"]');
            var _voucher = $.voucher.fetchID();
            var _id      = $.voucher.fetchPaymentID();
            var _file = _modal.find('input[name="attachment"]').val();
            var _attachment = _file.substring(_file.lastIndexOf("\\") + 1, _file.length);
            var _method  = (_id > 0) ? 'PUT' : 'POST';
            var _params  = 'cheque_date=' + _form.find('input[name="cheque_date"]').val() + '&cheque_no=' + encodeURIComponent(_form.find('input[name="cheque_no"]').val()) + '&bank_name=' + _form.find('input[name="bank_name"]').val() + '&bank_account_no=' + _form.find('input[name="bank_account_no"]').val() + '&bank_account_name=' + _form.find('input[name="bank_account_name"]').val() + '&attachment=' + _attachment + '&reference_no=' + encodeURIComponent(_form.find('textarea[name="reference_no"]').val());
            var _action  = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?' + _params : _form.attr('action') + '/store/' + _voucher + '?' + _params;
            var _error   = $.voucherForm.validate(_form, 0);
            var _paymentsCard = $('#payment-card');
            var _fError  = $.voucherForm.validateFile(_modal.find('input[name="attachment"]').val());
            var _validExtensions = ["jpg","pdf","jpeg","gif","png","bmp"];

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
            } else if (_fError > 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use this file format!<br/>Only formats are allowed: " + _validExtensions.join(', ') + ".",
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
                                _paymentsCard.find('th.total-amount').text($.voucher.price_separator($.voucher.money_format(response.data.total_disbursement)));
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

        /*
        | ---------------------------------
        | # when add cash in bank submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-bank-payment-modal .submit-btn', function (e){
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _form    = _modal.find('form[name="payments2Form"]');
            var _voucher = $.voucher.fetchID();
            var _id      = $.voucher.fetchPaymentID();
            var _file = _modal.find('input[name="attachment"]').val();
            var _attachment = _file.substring(_file.lastIndexOf("\\") + 1, _file.length);
            var _method  = (_id > 0) ? 'PUT' : 'POST';
            var _params  = 'cheque_date=' + _form.find('input[name="cheque_date"]').val() + '&cheque_no=' + encodeURIComponent(_form.find('input[name="cheque_no"]').val()) + '&bank_name=' + _form.find('input[name="bank_name"]').val() + '&bank_account_no=' + _form.find('input[name="bank_account_no"]').val() + '&bank_account_name=' + _form.find('input[name="bank_account_name"]').val() + '&attachment=' + _attachment + '&reference_no=' + encodeURIComponent(_form.find('textarea[name="reference_no"]').val());
            var _action  = (_id > 0) ? _form.attr('action') + '/update-payment/' + _id + '?' + _params : _form.attr('action') + '/store-payment/' + _voucher + '?' + _params;
            var _error   = $.voucherForm.validate(_form, 0);
            var _paymentsCard = $('#payment-card');
            var _fError  = $.voucherForm.validateFile(_modal.find('input[name="attachment"]').val());
            var _validExtensions = ["jpg","pdf","jpeg","gif","png","bmp"];

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
            } else if (_fError > 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use this file format!<br/>Only formats are allowed: " + _validExtensions.join(', ') + ".",
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
                                _paymentsCard.find('th.total-amount').text($.voucher.price_separator( $.voucher.money_format(response.data.total_disbursement)));
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

        /*
        | ---------------------------------
        | # when vouchers date submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-date-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="voucherDateForm"]');
            var _id     = $.voucher.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = _form.attr('action') + '/' + _id + '?type=' + _modal.find('.variables').text();
            var _error  = $.voucherForm.validate(_form, 0);          
            var _voucher = $('#voucher-card input[name="voucher_no"]').val();  
            var _urlPrint  = _baseUrl + 'treasury/journal-entries/' + voucherSegment + '/print/' + _voucher + '?type=' + _modal.find('.variables').text(); 

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
            } else if (!(_id > 0)) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add voucher entry first.",
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
                            _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                            _modal.modal('hide');
                            setTimeout(function () {
                                window.open(_urlPrint, '_blank')
                            }, 500 + 300 * (Math.random() * 5));
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

    //init voucherForm
    $.voucherForm = new voucherForm, $.voucherForm.Constructor = voucherForm

}(window.jQuery),

//initializing voucherForm
function($) {
    "use strict";
    $.voucherForm.init();
}(window.jQuery);
