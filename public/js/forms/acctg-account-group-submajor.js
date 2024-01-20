!function($) {
    "use strict";

    var account_group_submajorForm = function() {
        this.$body = $("body");
    };

    var $required = 0, _accountGrp = '', _majorAccountGrp = '';
    
    account_group_submajorForm.prototype.validate = function($form, $required)
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

    account_group_submajorForm.prototype.reload_major_account_group = function($acct)
    {   
        var _major = $('#acctg_account_group_major_id'); _major.find('option').remove(); 
        console.log(_baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/reload-major-account/' + $acct);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/reload-major-account/' + $acct,
            success: function(response) {
                console.log(response.data);
                _major.append('<option value="">select a major account group</option>');  
                $.each(response.data, function(i, item) {
                    _major.append('<option value="' + item.id + '">' + item.prefix + ' - ' + item.description + '</option>');  
                }); 
            },
            async: false
        });
        
        // $('.m_selectpicker').selectpicker('refresh');
    },

    account_group_submajorForm.prototype.fetch_group_code = function(_acct, _major, _code, _prefix)
    { 
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/chart-of-accounts/submajor-account-groups/fetch-group-code?account=' + _acct + '&major=' + _major,
            success: function(response) {
                _accountGrp = response.account;
                _majorAccountGrp = response.major;
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _prefix.val());
            },
            async: false
        });
        // $('.m_selectpicker').selectpicker('refresh');
    },

    account_group_submajorForm.prototype.init = function()
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
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#submajor-account-group-modal', function (e) {
            var modal = $(this);
            modal.find('.modal-header h5').html('Manage Sub-Major Group');
            modal.find('input, textarea').val('').removeClass('is-invalid');            
            modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            $.account_group_submajor.preload_select3();
            _accountGrp = ''; _majorAccountGrp = '';
        });

        /*
        | ---------------------------------
        | # when submit btn is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="submajorAccountGroupForm"]');
            var _id     = _form.find('[name="id"]').val();
            var _code   = _form.find('[name="code"]').val();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?code=' + _code : _form.attr('action') + '/store?code=' + _code;
            var _error  = $.account_group_submajorForm.validate(_form, 0);

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
                                        $.account_group_submajor.load_contents(1);
                                        _modal.modal('hide');
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
        this.$body.on('change', '#acctg_account_group_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _majorAcctGroup = $('#acctg_account_group_major_id');
            var _code = $('#code');
            var _prefix = $('#prefix');
            if (_self.val() > 0) {
                var d1 = $.account_group_submajorForm.reload_major_account_group(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                { 
                    $.account_group_submajorForm.fetch_group_code(_self.val(), _majorAcctGroup.val(), _code, _prefix);
                });
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _prefix.val());
            }
        });

        /*
        | ---------------------------------
        | # fetch account code when major account group onchange
        | ---------------------------------
        */
        this.$body.on('change', '#acctg_account_group_major_id', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#acctg_account_group_id');
            var _code = $('#code');
            var _prefix = $('#prefix');
            if (_self.val() > 0) {
                $.account_group_submajorForm.fetch_group_code(_acctGroup.val(), _self.val(), _code, _prefix);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _prefix.val());
            }
        });        

        /*
        | ---------------------------------
        | # on prefix No when keyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#acctg_account_group_id');
            var _majorAcctGroup = $('#acctg_account_group_major_id');
            var _code = $('#code');
            if (_acctGroup.val() > 0 || (_majorAcctGroup.val() > 0) ) {
                $.account_group_submajorForm.fetch_group_code(_acctGroup.val(), _majorAcctGroup.val(), _code, _self);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _self.val());
            }
        });
        this.$body.on('blur', '#prefix', function (e){
            e.preventDefault();
            var _self = $(this);
            var _acctGroup = $('#acctg_account_group_id');
            var _majorAcctGroup = $('#acctg_account_group_major_id');
            var _code = $('#code');
            if (_acctGroup.val() > 0 || (_majorAcctGroup.val() > 0) ) {
                $.account_group_submajorForm.fetch_group_code(_acctGroup.val(), _majorAcctGroup.val(), _code, _self);
            } else {
                _code.val(_accountGrp + '' + _majorAccountGrp + '' + _self.val());
            }
        });

    }

    //init account_group_submajorForm
    $.account_group_submajorForm = new account_group_submajorForm, $.account_group_submajorForm.Constructor = account_group_submajorForm

}(window.jQuery),

//initializing account_group_submajorForm
function($) {
    "use strict";
    $.account_group_submajorForm.init();
}(window.jQuery);
