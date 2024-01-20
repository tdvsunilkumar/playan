!function($) {
    "use strict";

    var cbo_budgetForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _issuances = [];  var _isValid = false;

    cbo_budgetForm.prototype.validate = function($form, $required)
    {   
        $required = 0;

        $.each($form.find("input[type='date'], input[type='text'], select, textarea"), function() {
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

    cbo_budgetForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/budget-proposal/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-proposal/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    cbo_budgetForm.prototype.fetch_breakdown_status = function (_id)
    {   
        var _adjusted = '';
        if (_id == 0) {
            return _adjusted = 'draft';
        }
        console.log(_baseUrl + 'finance/budget-proposal/fetch-breakdown-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-proposal/fetch-breakdown-status/' + _id,
            success: function(response) {
                console.log(response);
                _adjusted = response.adjusted;
            },
            async: false
        });
        return _adjusted;
    },

    cbo_budgetForm.prototype.validate_budget = function (_id)
    {   
        var _validate = 1;
        if (_id == 0) {
            return _validate = 1;
        }
        console.log(_baseUrl + 'finance/budget-proposal/validate-budget/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-proposal/validate-budget/' + _id,
            success: function(response) {
                console.log(response);
                _validate = response.validate;
            },
            async: false
        });
        return _validate;
    },

    cbo_budgetForm.prototype.validate_amount = function (_id)
    {   
        var _amount = 1;
        console.log(_baseUrl + 'finance/budget-proposal/validate-amount/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-proposal/validate-amount/' + _id,
            success: function(response) {
                console.log(response);
                _amount = response.amount;
            },
            async: false
        });
        return _amount;
    },

    cbo_budgetForm.prototype.update = function(_id, _form, _field)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'finance/budget-proposal/update/' + _id + '?fund=' + _form.find('select[name="fund_code_id"]').val() + '&budget_year=' + _form.find('input[name="budget_year"]').val() + '&department_id=' + _form.find('select[name="department_id"]').val() + '&remarks=' + encodeURIComponent(_form.find('textarea[name="remarks"]').val());
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            // data: _form.serialize(),
            success: function(response) {
                console.log(response);
                if (response.type == 'success') {
                    if (_id <= 0) {
                        $.cbo_budget.updateID(response.data.id);
                    }
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    cbo_budgetForm.prototype.reload_division = function($department)
    {   
        var $division = $('#division_id'); $division.find('option').remove(); 

        console.log(_baseUrl + 'finance/budget-proposal/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-proposal/reload-division-via-department/' + $department,
            success: function(response) {
                console.log(response.data);
                $division.append('<option value="">select a division</option>');  
                $.each(response.data, function(i, item) {
                    $division.append('<option value="' + item.id + '"> ' + item.code + ' - ' + item.name + '</option>');  
                }); 
            },
            async: false
        });
        
        // $('.m_selectpicker').selectpicker('refresh');
    },

    cbo_budgetForm.prototype.forceNumeric = function(_self)
    {   
        var re = /^[-+]?[0-9]+(\.\d{0,2})?$/;
        // var re = /^[-+]?[0-9]\d*(\.\d+)?$/;
        var text = _self.val();
        _isValid = (text.match(re) !== null);
        if (!_isValid) {
            _self.next().text('this is not a valid value.');
        } else {
            _self.next().text('');
        }
    },

    cbo_budgetForm.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # year picker
        | ---------------------------------
        */
        $('#budget_year').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            autoclose: true,
            clearBtn: false
        }).on('changeDate', function(e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.cbo_budget.fetchID();
            var d1    = $.cbo_budgetForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_budget.updateBudgetYear(_self.val());
                    $.cbo_budgetForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
                _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            });
        });
        $('#budget_year2').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            autoclose: true,
            clearBtn: false
        }).on('changeDate', function(e) {
        });


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


        this.$body.on('keyup', 'input[name="alignment"]', function (event) {
            var _self = $(this);
            $.cbo_budgetForm.forceNumeric(_self);
        });
        this.$body.on('blur', 'input[name="alignment"]', function (event) {
            var _self = $(this);
            $.cbo_budgetForm.forceNumeric(_self);
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
        | # when select on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="budgetForm"] select:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.cbo_budget.fetchID();
            var d1    = $.cbo_budgetForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_budgetForm.update(_id, _form, _self.attr('name'));
                    if (_self.attr('name') == 'department_id') {
                        $.cbo_budgetForm.reload_division(_self.val());
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when textarea on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="budgetForm"] textarea:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.cbo_budget.fetchID();
            var d1    = $.cbo_budgetForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_budgetForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when send request is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'form[name="budgetForm"] button.send-btn', function (e) {
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _form      = _self.closest('form');
            var _code      = _form.find('input[name="budget_year"]').val();
            var _dep       = _form.find('select[name="department_id"] option:selected').text();
            var _breakdownTable = $('#breakdownTable');
            var _id        = $.cbo_budget.fetchID();
            var d1         = $.cbo_budgetForm.fetch_status(_id);
            var d2         = $.cbo_budgetForm.validate_budget(_id);
            var d3         = $.cbo_budgetForm.validate_amount(_id);
            var _error     = $.cbo_budgetForm.validate(_form, 0);
            var _url       = _baseUrl + 'finance/budget-proposal/send/for-approval/' + _id;
            var _toast     = $('#indexToast');
            
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
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
                    } else if (parseFloat(v2) > 0) { 
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The department is already exist.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    } else if (parseFloat(v3) <= 0) { 
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some breakdown line first.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null; 
                    } else {
                        _self.prop('disabled', true).html('wait.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the request with <strong>Budget Year (" + _code + ")<br/>[ " + _dep + " ]</strong> will be locked.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, lock it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _url,
                                    success: function(response) {
                                        console.log(response);
                                        if (response.status == 'success') {
                                            _self.prop('disabled', true).html('wait.....');
                                            _modal.find('input, select, textarea').prop('disabled', true);  
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Lock Budget').addClass('hidden');
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();       
                                                _modal.find('button.print-btn').removeClass('hidden');
                                                _modal.modal('hide');                                       
                                                $.cbo_budget.load_contents();
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _toast.hide();
                                            }, 5000);
                                        } else {
                                            Swal.fire({
                                                title: response.title,
                                                html: response.text,
                                                icon: response.type,
                                                type: response.type,
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Lock Budget')
                        });
                    }
                } else {
                    console.log('sorry cannot be processed');
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

        /*
        | ---------------------------------
        | # when add breakdown button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budget-breakdown-modal .submit-btn', function (e) {
            var _self        = $(this);
            var _form        = _self.closest('form');
            var _modal       = _self.closest('.modal');
            var _error       = $.cbo_budgetForm.validate(_form, 0);
            var _id          = $.cbo_budget.fetchID();
            var _breakdownID = $.cbo_budget.fetchBreakdownID();
            var d1           = $.cbo_budgetForm.fetch_status(_id);

            _self.prop('disabled', true).html('Wait.....');
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
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
                            type: (_breakdownID > 0) ? 'PUT' : _form.attr('method'),
                            url: (_breakdownID > 0) ? _baseUrl + 'finance/budget-proposal/update-breakdown/' + _breakdownID + '?budget_id=' + _id : _form.attr('action') + '/' + _id,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('Save Changes').prop('disabled', false);
                                        $.cbo_budget.load_line_contents();
                                        $('#budget-proposal-modal').find('tfoot th.text-danger').text('₱' + $.cbo_budget.price_separator(parseFloat(response.total).toFixed(2)));
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
                } else {
                    _self.prop('disabled', false).html('Save Changes');
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
        this.$body.on('click', '#budget-breakdown2-modal .submit-btn', function (e) {
            var _self        = $(this);
            var _form        = _self.closest('form');
            var _modal       = _self.closest('.modal');
            var _error       = $.cbo_budgetForm.validate(_form, 0);
            var _id          = $.cbo_budget.fetchID();
            var _breakdownID = $.cbo_budget.fetchBreakdownID();
            var d1           = $.cbo_budgetForm.fetch_status(_id);

            _self.prop('disabled', true).html('Wait.....');
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'locked') {
                    if (!_isValid) {
                        _self.prop('disabled', false).html('Save Changes');
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please insert a valid value.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    } else if (_error != 0) {
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
                            type: (_breakdownID > 0) ? 'PUT' : _form.attr('method'),
                            url: (_breakdownID > 0) ? _baseUrl + 'finance/budget-proposal/update-breakdown/' + _breakdownID + '?budget_id=' + _id : _form.attr('action') + '/' + _id,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('Save Changes').prop('disabled', false);
                                        $.cbo_budget.load_line_contents();
                                        $('#budget-proposal-modal').find('tfoot th.text-danger').text('₱' + $.cbo_budget.price_separator(parseFloat(response.total).toFixed(2)));
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
                } else {
                    _self.prop('disabled', false).html('Save Changes');
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

        /*
        | ---------------------------------
        | # quarterly budget on keyup
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="quarterly_budget"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _annual = $('input[name="annual_budget"]');
            
            if (_self.val() != '') {
                _annual.val(parseFloat(parseFloat(_self.val()) * parseFloat(4)));
            } else {
                _annual.val('');
            }
        });
        this.$body.on('blur', 'input[name="quarterly_budget"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _annual = $('input[name="annual_budget"]');
            
            if (_self.val() != '') {
                _annual.val(parseFloat(parseFloat(_self.val()) * parseFloat(4)));
            } else {
                _annual.val('');
            }
        });

        /*
        | ---------------------------------
        | # annual budget on keyup
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="annual_budget"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _quarter = $('input[name="quarterly_budget"]');
            
            if (_self.val() != '') {
                _quarter.val(parseFloat(parseFloat(_self.val()) / parseFloat(4)));
            } else {
                _quarter.val('');
            }
        });
        this.$body.on('blur', 'input[name="annual_budget"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _quarter = $('input[name="quarterly_budget"]');
            
            if (_self.val() != '') {
                _quarter.val(parseFloat(parseFloat(_self.val()) / parseFloat(4)));
            } else {
                _quarter.val('');
            }
        });

        /*
        | ---------------------------------
        | # when copy btn onClick
        | ---------------------------------
        */
        this.$body.on('click', '#copy-modal .submit-btn', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _modal = _self.closest('.modal');
            var _error = $.cbo_budgetForm.validate(_form, 0);
            var _lists = $.cbo_budget.fetchLists();

            if (_error != 0) {
            } else {
                _form.find('input[name="budget_year2"]').prop('disabled', true);
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _form.attr('method'),
                    url: _form.attr('action') + '?budget_year=' + _form.find('input[name="budget_year2"]').val(),
                    data: {'lists' : _lists},
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('<i class="la la-save align-middle"></i> Copy Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.cbo_budget.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            if (response.type == 'error2') {
                                _form.find('input[name="budget_year2"]').prop('disabled', false);
                                _self.html('<i class="la la-save align-middle"></i> Copy Changes').prop('disabled', false);
                                $.each(response.columns, function(i, column) {
                                    _form.find('select[name="' + column + '"]').addClass('is-invalid').next().next().text(response.label);
                                    _form.find('input[name="' + column + '"]').addClass('is-invalid').next().text(response.label);
                                }); 
                                Swal.fire({ title: response.title, text: response.text, icon: 'error', buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                    }
                                );
                            } else {
                                _form.find('input[name="budget_year2"]').prop('disabled', false);
                                _self.html('<i class="la la-save align-middle"></i> Copy Changes').prop('disabled', false);
                                _form.find('select[name="' + response.column + '"]').addClass('is-invalid').next().next().text(response.label);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                    }
                                );
                            }
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

    //init cbo_budgetForm
    $.cbo_budgetForm = new cbo_budgetForm, $.cbo_budgetForm.Constructor = cbo_budgetForm

}(window.jQuery),

//initializing cbo_budgetForm
function($) {
    "use strict";
    $.cbo_budgetForm.init();
}(window.jQuery);
