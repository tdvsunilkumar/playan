!function($) {
    "use strict";

    var cbo_ppmpForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _rowField = ''; var _itemField = []; var _budgets = [];
    cbo_ppmpForm.prototype.validate = function($form, $required)
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

    cbo_ppmpForm.prototype.fetch_total_budget = function(_table)
    {
        var _total = 0; 
        $.each(_table.find('tbody tr'), function() {
            var _row = $(this);
            var _qty = _row.find('input[name="quantity[]"]').val();
            if (parseFloat(_qty) > 0) {
                _total += parseFloat(_row.attr('data-row-total'));
            }
        });
        _table.find('tfoot td.text-danger').attr('data-row-total-budget', parseFloat(Math.floor((_total * 100))/100).toFixed(2)).text($.cbo_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
    }

    cbo_ppmpForm.prototype.fetch_item_details = function(_item, _row, _ppmpID, _division)
    {   
        var _table = _row.closest('table');
        if (_item > 0) {            
            console.log(_baseUrl + 'finance/procurement-plan/fetch-item-details/' + _item + '?division=' + _division + '&id=' + _ppmpID);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/procurement-plan/fetch-item-details/' + _item + '?division=' + _division + '&id=' + _ppmpID,
                success: function(response) {
                    console.log(response);
                    var _cost = response.data.weighted_cost;
                    if (!(response.validate > 0)) {
                        var _quantity = (parseFloat(_row.find('td:nth(3) input').val()) > 0) ? parseFloat(_row.find('td:nth(3) input').val()) : 1;
                        var _total = parseFloat(_cost) * parseFloat(_quantity);
                        _row.attr('data-row-item', _item);
                        _row.attr('data-row-uom', response.data.uom.id);
                        _row.attr('data-row-cost', response.data.weighted_cost);
                        _row.attr('data-row-quantity', _quantity);
                        _row.attr('data-row-total', _total);
                        _row.attr('data-row-gl', (response.data.gl_account ? response.data.gl_account.id : ''));
                        // _row.find('td:nth(1)').text(_row.closest('tbody').attr('data-row-gl-code'));
                        _row.find('td:nth(3) input').val(_quantity);
                        _row.find('td:nth(4)').text((response.data.uom ? response.data.uom.code : ''));
                        _row.find('td:nth(5)').text($.cbo_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: "Something went wrong!<br/>The item is already exist.",
                            icon: "warning",
                            type: "warning",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;   
                        _row.find('select.select3').val('').trigger('change.select3'); 
                        _row.attr('data-row-item', 0);
                        _row.attr('data-row-uom', 0);
                        _row.attr('data-row-quantity', 0);
                        _row.attr('data-row-total', 0);
                        _row.attr('data-row-gl', 0);
                        _row.find('td:nth(3) input').val('');
                        _row.find('td:nth(4)').text('');
                        _row.find('td:nth(5)').text('');
                    }
                }, 
                async: false
            });
        } else {
            _row.attr('data-row-item', 0);
            _row.attr('data-row-uom', 0);
            _row.attr('data-row-quantity', 0);
            _row.attr('data-row-total', 0);
            _row.attr('data-row-gl', 0);
            _row.find('td:nth(0)').text('');
            _row.find('td:nth(2) input').val('');
            _row.find('td:nth(3)').text('');
            _row.find('td:nth(4)').text('');
            _row.find('td:nth(5)').text('');
        }
        $.cbo_ppmpForm.fetch_total_budget(_table);
    },

    cbo_ppmpForm.prototype.compute_budget = function(_quantity, _row)
    {   
        var _division = $('#division_id').val();
        var _table = _row.closest('table');
        var _cost = _row.attr('data-row-cost');
        if (parseFloat(_quantity) > 0) {
            var _total = parseFloat(_cost) * parseFloat(_quantity);
            _row.attr('data-row-quantity', _quantity);
            _row.attr('data-row-total', _total);
            if (_division == 'ALL') {
                _row.find('td:nth(5)').text($.cbo_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
            } else {
                _row.find('td:nth(4)').text($.cbo_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
            }
        } else {
            _row.attr('data-row-quantity', 0);
            _row.attr('data-row-total', 0);
            if (_division == 'ALL') {
                _row.find('td:nth(5)').text('');
            } else {
                _row.find('td:nth(4)').text('');
            }
        }
        $.cbo_ppmpForm.fetch_total_budget(_table);
    }

    cbo_ppmpForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        // console.log(_baseUrl + 'finance/procurement-plan/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/fetch-status/' + _id,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    cbo_ppmpForm.prototype.fetch_division_status = function (_id, _division)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/fetch-division-status/' + _id + '?division=' + _division,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    cbo_ppmpForm.prototype.validate_division_status = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'finance/procurement-plan/validate-division-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/procurement-plan/validate-division-status/' + _id,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },


    cbo_ppmpForm.prototype.update = function(_id, _form, _field)
    {   
        var _voucher = $('#ppmp-card');
        var _url = _baseUrl + 'finance/procurement-plan/update/' + _id;
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                if (_id <= 0) {
                    $.cbo_ppmp.updateID(response.data.id);
                    _voucher.find('input[name="control_no"]').val(response.data.control_no);
                }
            },
        });
    },

    cbo_ppmpForm.prototype.fetch_budgets = function(_id)
    {   
        _budgets = [];
        var _url = _baseUrl + 'finance/procurement-plan/fetch-budgets/' + _id;
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                _budgets = response.data;
            },
            async: false
        });
        return _budgets;
    },

    cbo_ppmpForm.prototype.getItemField = function()
    {       
        var _id = $.cbo_ppmp.fetchID();
        var d1  = $.cbo_ppmpForm.fetch_budgets(_id);
        $.when( d1 ).done(function ( v1 ) 
        {   
            $.each(v1, function(i, budget) {
                var _rowField = '';
                var _select = '<select name="item_id[]" class="form-control select3" data-placeholder="">';
                $.ajax({
                    type: "GET",
                    url: _baseUrl + 'finance/procurement-plan/get-item-field/' + budget.gl_account_id,
                    success: function(response) {
                        console.log(response);
                        _rowField += '<tr data-row-id="0" data-row-cost="0" data-row-total="0">';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center fw-bold"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-left" width="30%">';
                        _rowField += '<div class="form-group m-form__group required m-0">';
                        _select += '<option value="">select an item</option>';  
                        $.each(response.items, function(i, item) {
                            _select += '<option value="' + item.id + '"> ' + item.code + ' - ' + item.name + '</option>';  
                        }); 
                        _select += '</select>'
                        _rowField += _select;
                        _rowField += '</div>';
                        _rowField += '</td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center" width="10%">';
                        _rowField += '<input type="text" class="text-center numeric-double" name="quantity[]">';
                        _rowField += '</td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center fw-bold"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center fw-bold"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center"></td>';
                        _rowField += '<td rowspan="1" colspan="1" class="text-center">';
                        _rowField += '<div class="d-flex m-auto justify-content-center">';
                        _rowField += '<a href="javascript:;" class="action-btn add-btn bg-info btn btn-sm ms-1 mt-1 mb-1 align-items-center d-flex" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="add this" aria-label="add this"><i class="ti-plus text-white"></i></a>';
                        _rowField += '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center d-flex" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="remove this" aria-label="remove this"><i class="ti-trash text-white"></i></a>';
                        _rowField += '</div>';
                        _rowField += '</td>';
                        _rowField += '</tr>';
                        _itemField[budget.gl_account_id] = _rowField;
                    },
                    async: false
                });
            }); 
        });
    },

    cbo_ppmpForm.prototype.update_lines = function(_id, _row)
    {   
        var _form = '?id=' + _row.attr('data-row-id') + '&cbo=1&division=' + _row.attr('data-row-division') + '&amount=' + _row.attr('data-row-cost') + '&total=' + _row.attr('data-row-total') + '&item=' + _row.attr('data-row-item') + '&uom=' + _row.attr('data-row-uom') + '&quantity=' + _row.attr('data-row-quantity') + '&gl_account=' + _row.closest('tbody').attr('data-row-gl');
        var _url = _baseUrl + 'finance/procurement-plan/update-lines/' + _id + '' + _form;
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                _row.attr('data-row-id', response.data.id);
            },
        });
    },

    cbo_ppmpForm.prototype.init = function()
    {   
        $.cbo_ppmpForm.getItemField();

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
                (text.substring(text.indexOf('.')).length > 5) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 5)) {
                event.preventDefault();
            }
        });

        /*
        | ---------------------------------
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _form = _self.closest('form');
            var _text = _self.val().replace('&', 'and');
            _form.find('input[name="code"]').val(_text.replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_text.replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # when item on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="item_id[]"]', function (e) {
            e.preventDefault();
            var _self    = $(this);
            var _parents = _self.closest('tr');
            var _division = _parents.attr('data-row-division');
            var _id      = $.cbo_ppmp.fetchID();
            var d1       = $.cbo_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_ppmpForm.fetch_item_details(_self.val(), _parents, _id, _division);
                    $.cbo_ppmpForm.update_lines(_id, _parents);
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
        this.$body.on('keyup', 'input[name="quantity[]"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _parents = _self.closest('tr');
            var _id      = $.cbo_ppmp.fetchID();
            var d1       = $.cbo_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_ppmpForm.compute_budget(_self.val(), _parents);
                    if (e.which == 13) {
                        _parents.next().find('input').focus();
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });
        this.$body.on('blur', 'input[name="quantity[]"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _parents = _self.closest('tr');
            var _id      = $.cbo_ppmp.fetchID();
            var d1       = $.cbo_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.cbo_ppmpForm.compute_budget(_self.val(), _parents);
                    $.cbo_ppmpForm.update_lines(_id, _parents);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when tab / enter is press
        | ---------------------------------
        */
        this.$body.on('keyup', 'tr:last-child input[name="quantity[]"]', function (e) {
            if (e.which == 13) {
                var _self = $(this);
                var _table = $(this).closest('table');
                var _gl = _table.find('tbody').attr('data-row-gl');
                var _rowCount = _table.find('tr').length + 1;
                var _row = _self.closest('tr');
                if ($('#division_id').val() != 'ALL') {
                    if (_self.val() > 0) {
                        _table.find('tbody').append(_itemField[_gl]);
                        _table.find('tbody tr:last-child select').attr('id', 'item_' + _gl + '_' + _rowCount);
                        $.cbo_ppmp.preload_select3();
                        _row.next().find('input').focus();
                    }
                }
            }
        });

        /*
        | ---------------------------------
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'tbody .action-btn.add-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _table = _self.closest('table');
            var _row = _self.closest('tr');
            var _gl = _table.find('tbody').attr('data-row-gl');
            var _div = _row.attr('data-row-division');
            var _divCode = _row.attr('data-row-division-code');
            var _glCode = _row.attr('data-row-gl-code');
            var _rowCount = _table.find('tr').length + 1;
            var _id = $.cbo_ppmp.fetchID();
            var d1  = $.cbo_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    _row.after(_itemField[_gl]);
                    _row.next().find('select').attr('id', 'item_' + _gl + '_' + _rowCount);
                    $.cbo_ppmp.preload_select3();
                    _row.next().attr('data-row-division', _div);
                    _row.next().attr('data-row-division-code', _divCode);
                    _row.next().attr('data-row-gl-code', _glCode);
                    _row.next().find('td:nth-child(1)').text(_divCode);
                    _row.next().find('td:nth-child(2)').text(_glCode);
                    _row.next().find('input').focus();
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when ppmp submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmp-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form');
            var _id     = $.cbo_ppmp.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.cbo_ppmpForm.validate(_form, 0);

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
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.cbo_ppmp.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            if (response.type == 'error2') {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                $.each(response.columns, function(i, column) {
                                    _form.find('select[name="' + column + '"]').addClass('is-invalid').next().next().text(response.label);
                                    _form.find('input[name="' + column + '"]').addClass('is-invalid').next().text(response.label);
                                }); 
                                Swal.fire({ title: response.title, text: response.text, icon: 'error', buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                    }
                                );
                            } else {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
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

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#ppmp-send-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _dep   = $('#department_id option:selected').text();
            var _year  = $('#budget_year').val();
            var _toast = $('#indexToast');
            var _id    = $.cbo_ppmp.fetchID();          
            var _url   = _baseUrl + 'finance/procurement-plan/lock/' + _id;

            var d1 = $.cbo_ppmpForm.fetch_status(_id);
            var d2 = $.cbo_ppmpForm.validate_division_status(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {   
                if (v2 == false) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>All division must be locked first.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                } else if (v1 == 'draft') {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html:  "Are you sure? <br/>the Annual Procurement Plan Year (" + _year + ")<br/>[ " + _dep + " ]</strong><br/>will be locked.",
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
                                    // if (response.status == 'success') {
                                        _self.prop('disabled', false).removeClass('active');
                                        setTimeout(function () {
                                            _toast.find('.toast-body').html(response.text);
                                            _toast.show();
                                            $.each($('.tab-pane .table'), function(){
                                                var _table = $(this);
                                                _table.find('tbody tr').addClass('active');
                                            });
                                        }, 500 + 300 * (Math.random() * 5));
                                        setTimeout(function () {
                                            _toast.hide();
                                        }, 5000);
                                    // } else {
                                    //     Swal.fire({
                                    //         title: "Oops...",
                                    //         html: "Invalid Request!",
                                    //         icon: "error",
                                    //         type: "danger",
                                    //         showCancelButton: false,
                                    //         closeOnConfirm: true,
                                    //         confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                                    //     });
                                    //     window.onkeydown = null;
                                    //     window.onfocus = null;    
                                    // }
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false) : ''
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
        });

        /*
        | ---------------------------------
        | # when copy button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#ppmp2-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form');
            var _id     = $.cbo_ppmp.fetchID();
            var _action = _form.attr('action') + '/copy/' + _id;
            var _error  = $.cbo_ppmpForm.validate(_form, 0);

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
                    type: _form.attr('method'),
                    url: _action,
                    data: _form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            setTimeout(function () {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        $.cbo_ppmp.load_contents();
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            if (response.type == 'error2') {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                $.each(response.columns, function(i, column) {
                                    _form.find('select[name="' + column + '"]').addClass('is-invalid').next().next().text(response.label);
                                    _form.find('input[name="' + column + '"]').addClass('is-invalid').next().text(response.label);
                                }); 
                                Swal.fire({ title: response.title, text: response.text, icon: 'error', buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                    }
                                );
                            } else {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
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

    //init cbo_ppmpForm
    $.cbo_ppmpForm = new cbo_ppmpForm, $.cbo_ppmpForm.Constructor = cbo_ppmpForm

}(window.jQuery),

//initializing cbo_ppmpForm
function($) {
    "use strict";
    $.cbo_ppmpForm.init();
}(window.jQuery);
