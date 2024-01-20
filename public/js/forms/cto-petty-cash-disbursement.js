!function($) {
    "use strict";

    var cto_petty_cashForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _obrs = []; 

    cto_petty_cashForm.prototype.validate = function($form, $required)
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
                    } else if($(this).val()=="" || $(this).val() == null){
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

    cto_petty_cashForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'treasury/petty-cash/disbursement/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'treasury/petty-cash/disbursement/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    cto_petty_cashForm.prototype.update = function(_id, _form, _field)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'treasury/petty-cash/disbursement/update/' + _id + '?particulars=' + encodeURIComponent(_form.find('textarea[name="particulars"]').val());
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
                if (response.type == 'success') {
                    if (_id <= 0) {
                        $.cto_petty_cash.updateID(response.data.id);
                    }
                    _modal.find('input[name="payee_id"]').val( (response.data.payee ? response.data.payee.paye_name : '') );
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    cto_petty_cashForm.prototype.view_available_obr = function(_id, _button, _department)
    {   
        var _table = $('#available-obligation-request-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'treasury/petty-cash/disbursement/view-available-obligation-requests/' + _id + '?department=' + _department;
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '">';
                        _rows += '<td><div class="form-check"><input class="form-check-input" type="checkbox" value="' + row.id + '"></div></td>';
                        _rows += '<td>' + (row.alob_no ? row.alob_no : '') + '</td>';
                        _rows += '<td>' + (row.department ? row.department : '') + '</td>';
                        _rows += '<td>' + (row.division ? row.division : '') + '</td>';
                        _rows += '<td>' + (row.total_amount ? $.cto_petty_cash.price_separator(parseFloat(Math.floor((row.total_amount * 100))/100).toFixed(2)) : '') + '</td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('ADD LINE');
                    _table.find('tbody').append(_rows);
                    var d1 = $.cto_petty_cash.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('ADD LINE');
            }
        });
    },

    cto_petty_cashForm.prototype.init = function()
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
            var _id   = $.cto_petty_cash.fetchID();
            var d1    = $.cto_petty_cashForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cto_petty_cash.updateBudgetYear(_self.val());
                    $.cto_petty_cashForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
                _self.removeClass('is-invalid').closest(".form-group").find(".m-form__help").text("");
            });
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
        this.$body.on('hidden.bs.modal', '#add-obligation-request-modal', function (e) {
            var _modal = $(this); _obrs = [];
            _modal.find('input[type="checkbox"][value="all"]').prop('checked', false);
            $.cto_petty_cash.load_line_contents();
        });

        /*
        | ---------------------------------
        | # when keywords on search
        | ---------------------------------
        */
        this.$body.on('keyup', '#keyword1', function (event) {
            var input, filter, table, tr, td, td1, td2, td3, i, txtValue;
            input = document.getElementById("keyword1");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-obligation-request-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                $('.pager').remove();
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    td1  = tr[i].getElementsByTagName("td")[2];
                    td2  = tr[i].getElementsByTagName("td")[3];
                    td3  = tr[i].getElementsByTagName("td")[4];
                    if (td || td1 || td2) {
                        txtValue = td.textContent + '' + td1.textContent + '' + td2.textContent + '' + td3.textContent || td.innerText + td1.innerText + td2.innerText + td3.innerText;  
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            } else {
               $.cto_petty_cash.validate_table($('#available-obligation-request-table'));
            }
        });
        this.$body.on('search', '#add-obligation-request-modal input[type="search"]', function (e) {
            $.cto_petty_cash.validate_table($('#available-obligation-request-table'));
        });

        /*
        | ---------------------------------
        | # when pr modal checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#add-obligation-request-modal input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.is(':checked')) {
                _modal.find('tr input[type="checkbox"]').prop('checked', true);
                $.each(_modal.find('input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _obrs.length; i++) {
                            if (_obrs[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _obrs.push(checkbox.val());
                        }
                    } 
                });
            } else {
                _modal.find('tr input[type="checkbox"]').prop('checked', false);
                $.each(_modal.find("input[type='checkbox'][value!='all']"), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _obrs.length; i++) {
                        if (_obrs[i] == checkbox.val()) {
                            _obrs.splice(i, 1);
                        }
                    }
                });
            }
            console.log(_obrs);
        });
        /*
        | ---------------------------------
        | # when pr modal checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#add-obligation-request-modal input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _obrs.push(_self.val());
            } else {
                for (var i = 0; i < _obrs.length; i++) {
                    if (_obrs[i] == _self.val()) {
                        _obrs.splice(i, 1);
                    }
                }
            }
            console.log(_obrs);
        });

        /*
        | ---------------------------------
        | # when pr modal button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-obligation-request-modal button', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.cto_petty_cash.fetchID();

            if (_obrs.length > 0) {
                console.log(_obrs);
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.cto_petty_cashForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.ajax({
                            type: 'POST',
                            url: _baseUrl + 'treasury/petty-cash/disbursement/add-line/' + _id,
                            data: {'obrs' : _obrs},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    if (_id <= 0) {
                                        $.cto_petty_cash.updateID(response.data.id);
                                    }
                                    setTimeout(function () {
                                        $('#petty-cash-modal').find('label[for="total-amount"] span').text('₱' + $.cto_petty_cash.price_separator(parseFloat(Math.floor((response.total * 100))/100).toFixed(2)));
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
        | # when select on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="pettyCashForm"] select:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.cto_petty_cash.fetchID();
            var d1    = $.cto_petty_cashForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cto_petty_cashForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('blur', 'form[name="pettyCashForm"] textarea:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.cto_petty_cash.fetchID();
            var d1    = $.cto_petty_cashForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cto_petty_cashForm.update(_id, _form, _self.attr('name'));
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
            var _id        = $.cto_petty_cash.fetchID();
            var d1         = $.cto_petty_cashForm.fetch_status(_id);
            var d2         = $.cto_petty_cashForm.validate_budget(_id);
            var d3         = $.cto_petty_cashForm.validate_amount(_id);
            var _error     = $.cto_petty_cashForm.validate(_form, 0);
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
                                                $.cto_petty_cash.load_contents();
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
            var _error       = $.cto_petty_cashForm.validate(_form, 0);
            var _id          = $.cto_petty_cash.fetchID();
            var _breakdownID = $.cto_petty_cash.fetchBreakdownID();
            var d1           = $.cto_petty_cashForm.fetch_status(_id);

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
                                        $.cto_petty_cash.load_line_contents();
                                        $('#budget-proposal-modal').find('tfoot th.text-danger').text('₱' + $.cto_petty_cash.price_separator(parseFloat(response.total).toFixed(2)));
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
        | # when add pr button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-petty-cash-line', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  =  _self.closest('form');
            var _error = $.cto_petty_cashForm.validate(_form, 0);
            var _id    = $.cto_petty_cash.fetchID();
            

            _self.prop('disabled', true).html('WAIT.....');
            var d1 = $.cto_petty_cashForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {
                    if (_error != 0) {
                        _self.prop('disabled', false).html('ADD LINE');
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
                        setTimeout(function () {
                            $.cto_petty_cashForm.view_available_obr(_id, _self, _form.find('select[name="department_id"]').val());
                        }, 500 + 300 * (Math.random() * 5));
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
                    _self.prop('disabled', false).html('ADD LINE');
                }
            });
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#petty-cash-modal .send-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _modal = _self.closest('.modal');
            var _toast = $('#modalToast');
            var _id    = $.cto_petty_cash.fetchID();            
            var _url   = _baseUrl + 'treasury/petty-cash/disbursement/send/for-approval/' + _id;
            
            if (_id > 0) {
                var d1 = $.cto_petty_cashForm.fetch_status(_id);
                var d2 = $.cto_petty_cash.fetchTableLine();
                $.when( d1, d2 ).done(function ( v1, v2 ) 
                {   
                    if(v2.rows().data().length <= 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some line first.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    } else if (v1 == 'draft') {
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
                                    type: 'PUT',
                                    url: _url,
                                    success: function(response) {
                                        console.log(response);
                                        if (response.status == 'success') {
                                            _modal.find('input.form-control-solid, select.select3, textarea.form-control-solid').prop('disabled', true);
                                            _self.prop('disabled', true).html('wait.....');
                                            setTimeout(function () {
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();
                                                $.cto_petty_cash.load_line_contents();
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                _toast.hide();
                                                // $.requisition.notify(_id);
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Send Request')
                                // : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Send Request') : ''
                        });
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
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please add a line item first.",
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
    }

    //init cto_petty_cashForm
    $.cto_petty_cashForm = new cto_petty_cashForm, $.cto_petty_cashForm.Constructor = cto_petty_cashForm

}(window.jQuery),

//initializing cto_petty_cashForm
function($) {
    "use strict";
    $.cto_petty_cashForm.init();
}(window.jQuery);
