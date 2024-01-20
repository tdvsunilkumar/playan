!function($) {
    "use strict";

    var general_ledgerForm = function() {
        this.$body = $("body");
    };

    var $required = 0, _glAccountId = 0, _accountGrp = '', _majorAccountGrp = '', _submajorAccountGrp = '';
    
    general_ledgerForm.prototype.validate = function($form, $required)
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

    general_ledgerForm.prototype.validateRow = function($row, $required)
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

    general_ledgerForm.prototype.reload_major_account_group = function($acct)
    {   
        var _major = $('#acctg_account_group_major_id'); _major.find('option').remove(); 
        console.log(_baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-major-account/' + $acct);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-major-account/' + $acct,
            success: function(response) {
                console.log(response.data);
                _major.append('<option value="">select a major group</option>');  
                $.each(response.data, function(i, item) {
                    _major.append('<option value="' + item.id + '">' + item.prefix + ' - ' + item.description + '</option>');  
                }); 
                // $.general_ledger.preload_select3();
            },
            async: false
        });
        return true;
    },

    general_ledgerForm.prototype.reload_submajor_account_group = function($acct, $major)
    {   
        var _submajor = $('#acctg_account_group_submajor_id'); _submajor.find('option').remove(); 
        console.log(_baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-submajor-account?account_group=' + $acct + '&major_group=' + $major);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/general-ledgers/reload-submajor-account?account_group=' + $acct + '&major_group=' + $major,
            success: function(response) {
                console.log(response.data);
                _submajor.append('<option value="">select a sub-major group</option>');  
                $.each(response.data, function(i, item) {
                    _submajor.append('<option value="' + item.id + '">' + item.prefix + ' - ' + item.description + '</option>');  
                }); 
                // $.general_ledger.preload_select3();
            },
            async: false
        });
        return true;
    },

    general_ledgerForm.prototype.fetch_group_code = function(_acct, _major, _submajor, _code, _prefix)
    { 
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/general-ledgers/fetch-group-code?account=' + _acct + '&major=' + _major + '&submajor=' + _submajor,
            success: function(response) {
                _accountGrp = response.account;
                _majorAccountGrp = response.major;
                _submajorAccountGrp = response.submajor;
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _prefix.val());
            },
            async: false
        });
        // $('.m_selectpicker').selectpicker('refresh');
    },

    general_ledgerForm.prototype.init = function()
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


        /*
        | ---------------------------------
        | # when show/hide subsidiary modal
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#general-ledger-account-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage General Ledger Account');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"]').prop('disabled', false);
            _modal.find('input[type="radio"][name="is_with_sl"][value="Yes"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_with_sl"][value="No"]').prop('checked', true);
            $.general_ledger.load_contents();
            _accountGrp = ''; _majorAccountGrp = ''; _submajorAccountGrp = '';
            $.general_ledger.updateID(0);
        });

        /*
        | ---------------------------------
        | # when submit btn is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#general-ledger-account-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="glAccountForm"]');
            var _sl     = _form.find('input[name="is_with_sl"][type="radio"]:checked').val();
            var _code   = _form.find('[name="code"]').val();
            var _id     = $.general_ledger.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?code=' + _code + '&is_with_sl=' + _sl  : _form.attr('action') + '/store?code=' + _code + '&is_with_sl=' + _sl;
            var _error  = $.general_ledgerForm.validate(_form, 0);

            if (_error != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please fill in the required fields first.",
                    type: "warning",
                    icon: "warning",
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
                                        if (_id == 0) {
                                            $.general_ledger.updateID(response.data.id);
                                        }
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
            }
        });

        /*
        | ---------------------------------
        | # fetch account code when account group onchange
        | ---------------------------------
        */
        this.$body.on('change', '#general-ledger-account-modal #acctg_account_group_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _majorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_major_id');
            var _subMajorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_submajor_id');
            var _code = $('#general-ledger-account-modal #code');
            var _prefix = $('#general-ledger-account-modal #prefix');
            if (_self.val() > 0) {
                var d1 = $.general_ledgerForm.reload_major_account_group(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                { 
                    $.general_ledgerForm.fetch_group_code(_self.val(), _majorAcctGroup.val(), _subMajorAcctGroup.val(), _code, _prefix);
                });
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _prefix.val());
            }
        });

        /*
        | ---------------------------------
        | # fetch account code when major account group onchange
        | ---------------------------------
        */
        this.$body.on('change', '#general-ledger-account-modal #acctg_account_group_major_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#general-ledger-account-modal #acctg_account_group_id');
            var _subMajorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_submajor_id');
            var _code = $('#general-ledger-account-modal #code');
            var _prefix = $('#general-ledger-account-modal #prefix');
            if (_self.val() > 0) {
                var d1 = $.general_ledgerForm.reload_submajor_account_group(_acctGroup.val(), _self.val());
                $.when( d1 ).done(function ( v1 ) 
                { 
                    $.general_ledgerForm.fetch_group_code(_acctGroup.val(), _self.val(), _subMajorAcctGroup.val(), _code, _prefix);
                });
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _prefix.val());
            }
        });     
        
        /*
        | ---------------------------------
        | # fetch account code when sub major account group onchange
        | ---------------------------------
        */
        this.$body.on('change', '#general-ledger-account-modal #acctg_account_group_submajor_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#general-ledger-account-modal #acctg_account_group_id');
            var _majorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_major_id');
            var _code = $('#general-ledger-account-modal #code');
            var _prefix = $('#general-ledger-account-modal #prefix');
            if (_self.val() > 0) {
                $.general_ledgerForm.fetch_group_code(_acctGroup.val(), _majorAcctGroup.val(), _self.val(), _code, _prefix);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _prefix.val());
            }
        }); 

        /*
        | ---------------------------------
        | # on prefix No when keyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#general-ledger-account-modal #prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#general-ledger-account-modal #acctg_account_group_id');
            var _majorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_major_id');
            var _subMajorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_submajor_id');
            var _code = $('#general-ledger-account-modal #code');
            if (_acctGroup.val() > 0 || _majorAcctGroup.val() > 0 || _subMajorAcctGroup.val() > 0) {
                $.general_ledgerForm.fetch_group_code(_acctGroup.val(), _majorAcctGroup.val(), _subMajorAcctGroup.val(), _code, _self);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _self.val());
            }
        });
        this.$body.on('blur', '#general-ledger-account-modal #prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#general-ledger-account-modal #acctg_account_group_id');
            var _majorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_major_id');
            var _subMajorAcctGroup = $('#general-ledger-account-modal #acctg_account_group_submajor_id');
            var _code = $('#general-ledger-account-modal #code');
            if (_acctGroup.val() > 0 || _majorAcctGroup.val() > 0 || _subMajorAcctGroup.val() > 0) {
                $.general_ledgerForm.fetch_group_code(_acctGroup.val(), _majorAcctGroup.val(), _subMajorAcctGroup.val(), _code, _self);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _submajorAccountGrp + '' + _self.val());
            }
        });

        /*
        | ---------------------------------
        | # when subsidiary prefix onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#subsidiary-ledger-account-modal input[name="prefix"]', function (e){
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _subCode = _modal.find('input[name="code"]');
            var _code = $('#general-ledger-account-modal input[name="code"]');
            var _parent = _modal.find('select[name="sl_parent_id"]');

            if(_parent.val() > 0) {
                _subCode.val(_parent.find('option:selected').text().replace(/ *\([^)]*\) */g, "") + '' + _self.val());
            } else {
                _subCode.val(_code.val() + '-' + _self.val());
            }
        });
        this.$body.on('blur', '#subsidiary-ledger-account-modal input[name="prefix"]', function (e){
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _subCode = _modal.find('input[name="code"]');
            var _code = $('#general-ledger-account-modal input[name="code"]');
            var _parent = _modal.find('select[name="sl_parent_id"]');

            if(_parent.val() > 0) {
                _subCode.val(_parent.find('option:selected').text().replace(/ *\([^)]*\) */g, "") + '' + _self.val());
            } else {
                _subCode.val(_code.val() + '-' + _self.val());
            }
        });

        /*
        | ---------------------------------
        | # when subsidiary parent onchange
        | ---------------------------------
        */
        this.$body.on('change', '#subsidiary-ledger-account-modal select[name="sl_parent_id"]', function (e){
            var _parent = $(this);
            var _modal = _parent.closest('.modal');
            var _subCode = _modal.find('input[name="code"]');
            var _code = $('#general-ledger-account-modal input[name="code"]');
            var _prefix = _modal.find('input[name="prefix"]');

            if(_parent.val() > 0) {
                _subCode.val(_parent.find('option:selected').text().replace(/ *\([^)]*\) */g, "") + '' + _prefix.val());
            } else {
                _subCode.val(_code.val() + '-' + _self.val());
            }
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#subsidiary-ledger-account-modal .submit-btn', function (e){
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _form      = _modal.find('form');
            var _glAccount = $.general_ledger.fetchID();
            var _id        = $.general_ledger.fetchSLID();
            var _method    = (_id > 0) ? 'PUT' : 'POST';
            var _action    = (_id > 0) ? _form.attr('action') + '/update/' + _glAccount + '/' + _id + '?code=' + _form.find('input[name="code"]').val() : _form.attr('action') + '/store/' + _glAccount + '?code=' + _form.find('input[name="code"]').val();
            var _error     = $.general_ledgerForm.validate(_form, 0);

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
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('Save Changes').prop('disabled', false);
                                $.general_ledger.load_line_contents();
                                _modal.modal('hide');
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('Save Changes').prop('disabled', false);
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
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#current-modal .submit-btn', function (e){
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _form      = _modal.find('form[name="currentForm"]');
            var _slAccount = $.general_ledger.fetchSLID();
            var _id        = $.general_ledger.fetchCurrentID();
            var _isDebit   = _form.find('input[name="is_debit"][type="radio"]:checked').val();
            var _method    = (_id > 0) ? 'PUT' : 'POST';
            var _action    = (_id > 0) ? _form.attr('action') + '/update/' + _slAccount + '/' + _id + '?is_debit=' + _isDebit : _form.attr('action') + '/store/' + _slAccount + '?is_debit=' + _isDebit;
            var _error     = $.general_ledgerForm.validate(_form, 0);

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
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('Save Changes').prop('disabled', false);
                                $.general_ledger.load_current_contents();
                                _modal.modal('hide');
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('Save Changes').prop('disabled', false);
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

    //init general_ledgerForm
    $.general_ledgerForm = new general_ledgerForm, $.general_ledgerForm.Constructor = general_ledgerForm

}(window.jQuery),

//initializing general_ledgerForm
function($) {
    "use strict";
    $.general_ledgerForm.init();
}(window.jQuery);
