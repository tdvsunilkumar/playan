!function($) {
    "use strict";

    var gso_ppmpForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _rowField = ''; var _itemField = []; var _budgets = [];
    gso_ppmpForm.prototype.validate = function($form, $required)
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

    gso_ppmpForm.prototype.fetch_total_budget = function(_table)
    {
        var _total = 0; 
        $.each(_table.find('tbody tr'), function() {
            var _row = $(this);
            var _qty = _row.find('input[name="quantity[]"]').val();
            if (parseFloat(_qty) > 0) {
                _total += parseFloat(_row.attr('data-row-total'));
            }
        });
        _table.find('tfoot td.text-danger').attr('data-row-total-budget', parseFloat(Math.floor((_total * 100))/100).toFixed(2)).text($.gso_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
    }

    gso_ppmpForm.prototype.fetch_item_details = function(_item, _row, _ppmpID, _division)
    {   
        var _table = _row.closest('table');
        if (_item > 0) {
            console.log(_baseUrl + 'general-services/project-procurement-management-plan/fetch-item-details/' + _item + '?division=' + _division + '&id=' + _ppmpID);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'general-services/project-procurement-management-plan/fetch-item-details/' + _item + '?division=' + _division + '&id=' + _ppmpID,
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
                        _row.find('td:nth(1)').text(_row.closest('tbody').attr('data-row-gl-code'));
                        _row.find('td:nth(3) input').val(_quantity);
                        _row.find('td:nth(4)').text((response.data.uom ? response.data.uom.code : ''));
                        _row.find('td:nth(5)').text($.gso_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
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
            _row.find('td:nth(1)').text('');
            _row.find('td:nth(3) input').val('');
            _row.find('td:nth(4)').text('');
            _row.find('td:nth(5)').text('');
        }
        $.gso_ppmpForm.fetch_total_budget(_table);
    },

    gso_ppmpForm.prototype.compute_budget = function(_quantity, _row)
    {   
        var _division = $('#division_id').val();
        var _table = _row.closest('table');
        var _cost = _row.attr('data-row-cost');
        if (parseFloat(_quantity) > 0) {
            var _total = parseFloat(_cost) * parseFloat(_quantity);
            _row.attr('data-row-quantity', _quantity);
            _row.attr('data-row-total', _total);
            if (_division == 'ALL') {
                _row.find('td:nth(5)').text($.gso_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
            } else {
                _row.find('td:nth(5)').text($.gso_ppmp.price_separator(parseFloat(Math.floor((_total * 100))/100).toFixed(2)));
            }
        } else {
            _row.attr('data-row-quantity', 0);
            _row.attr('data-row-total', 0);
            if (_division == 'ALL') {
                _row.find('td:nth(5)').text('');
            } else {
                _row.find('td:nth(5)').text('');
            }
        }
        $.gso_ppmpForm.fetch_total_budget(_table);
    }

    gso_ppmpForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        // console.log(_baseUrl + 'general-services/project-procurement-management-plan/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/project-procurement-management-plan/fetch-status/' + _id,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    gso_ppmpForm.prototype.fetch_division_status = function (_id, _division)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/project-procurement-management-plan/fetch-division-status/' + _id + '?division=' + _division,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    gso_ppmpForm.prototype.validate_division_status = function (_id)
    {   
        var _status = false;
        console.log(_baseUrl + 'general-services/project-procurement-management-plan/validate-division-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/project-procurement-management-plan/validate-division-status/' + _id,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },


    gso_ppmpForm.prototype.update = function(_id, _form, _field)
    {   
        var _voucher = $('#ppmp-card');
        var _url = _baseUrl + 'general-services/project-procurement-management-plan/update/' + _id;
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                if (_id <= 0) {
                    $.gso_ppmp.updateID(response.data.id);
                    _voucher.find('input[name="control_no"]').val(response.data.control_no);
                }
            },
        });
    },

    gso_ppmpForm.prototype.fetch_budgets = function(_id)
    {   
        _budgets = [];
        var _url = _baseUrl + 'general-services/project-procurement-management-plan/fetch-budgets/' + _id;
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

    gso_ppmpForm.prototype.getItemField = function()
    {       
        var _id = $.gso_ppmp.fetchID();
        var d1  = $.gso_ppmpForm.fetch_budgets(_id);
        $.when( d1 ).done(function ( v1 ) 
        {   
            $.each(v1, function(i, budget) {
                var _rowField = '';
                var _select = '<select name="item_id[]" class="form-control select3" data-placeholder="">';
                $.ajax({
                    type: "GET",
                    url: _baseUrl + 'general-services/project-procurement-management-plan/get-item-field/' + budget.gl_account_id,
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
                        _rowField += '</tr>';
                        _itemField[budget.gl_account_id] = _rowField;
                    },
                    async: false
                });
            }); 
        });
    },

    gso_ppmpForm.prototype.update_lines = function(_id, _row)
    {   
        var _form = '?id=' + _row.attr('data-row-id') + '&division=' + $('#division_id').val() + '&amount=' + _row.attr('data-row-cost') + '&total=' + _row.attr('data-row-total') + '&item=' + _row.attr('data-row-item') + '&uom=' + _row.attr('data-row-uom') + '&quantity=' + _row.attr('data-row-quantity') + '&gl_account=' + _row.closest('tbody').attr('data-row-gl');
        var _url = _baseUrl + 'general-services/project-procurement-management-plan/update-lines/' + _id + '' + _form;
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

    gso_ppmpForm.prototype.fetch_budget_lists = function(_modal, _fund = '', _department = '', _category = '', _year = '')
    {   
        var _table = _modal.find('table'); _table.find('thead').empty(); _table.find('tbody').empty();
        console.log('fund: ' + _fund + ', department: ' + _department + ', category: ' + _category + ', year: ' + _year);
        var _url = _baseUrl + 'general-services/project-procurement-management-plan/fetch-budget-lists?fund=' + _fund + '&department=' + _department + '&category=' + _category + '&year=' + _year;
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                if (response.data.budgets.length > 0) {
                    console.log(response.data.budgets);
                    var _head = ''; var _body = '';
                    _head += '<tr>';
                    _head += '<th rowspan="2" colspan="1" class="text-center"> </th>';
                    _head += '<th rowspan="2" colspan="1" class="text-center">GL ACCOUNT</th>';
                    _head += '<th rowspan="2" colspan="1" class="text-center">BUDGET</th>';
                    _head += '<th rowspan="1" colspan="' + response.data.divisions.length + '" class="text-center">ALL ' + response.data.divisions[0].department.name + ' ' + (_year != '' ? _year : '') + ' BUDGET</th>';
                    _head += '</tr><tr>';
                    $.each(response.data.divisions, function(i, division) {
                        _head += '<th rowspan="1" colspan="1" class="text-center" title="' + division.name + '">' + division.code + '</th>';
                    });
                    _head += '</tr>'; 
                    $.each(response.data.budgets, function(i, budget) {
                        _body += '<tr data-row-budget="' + (budget.final_budget != null ? budget.final_budget : budget.annual_budget) + '">';
                        _body += '<td class="text-center"><i class="fa fa-close text-danger"></i></td>';
                        _body += '<td class="text-center" title="' + budget.gl_account.description + '">' + budget.gl_account.code + '<input class="hidden" value="' + budget.gl_account.id + '" name="gl_account[]"/></td>';
                        _body += '<td class="text-center">&#8369; ' + (budget.final_budget != null ? budget.final_budget.toLocaleString('en-US', {minimumFractionDigits: 2}) : budget.annual_budget.toLocaleString('en-US', {minimumFractionDigits: 2})) + '</td>';
                        $.each(response.data.divisions, function(i, division) {
                            _body += '<td class="text-center"><input class="form-control form-control-solid text-center" name="division[' + budget.gl_account.id + '][' + division.id + ']"></td>';
                        });
                        _body += '</tr>'
                    });
                    _table.find('thead').append(_head);
                    _table.find('tbody').append(_body);
                }
            },
        });
    },

    gso_ppmpForm.prototype.compute_list = function (_modal, _row, _column)
    {
        var _remainingTD = _row.find('td:nth-child(3)');
        var _remaining = _row.attr('data-row-budget') ? _row.attr('data-row-budget') : 0;     
        var _total = 0; 
        $.each(_row.find('input:not(.hidden)'), function() {
            var _self = $(this);
                _total += (_self.val() > 0) ? parseFloat(_self.val()) : 0;
        });
        console.log(_total);
        if (_total > 0) {
            _remaining = _remaining - _total;
        }
        if (_remaining == 0) {
            _row.find('i.fa').removeClass('fa-close text-danger').addClass('fa-check text-success');
        } else {
            _row.find('i.fa').removeClass('fa-check text-success').addClass('fa-close text-danger');
        }
        _remainingTD.text(_remaining);
    },

    gso_ppmpForm.prototype.validate_computations = function (_modal) 
    {   
        var _validation = 0;
        var _lists = _modal.find('table tbody tr');
        $.each(_lists, function() {
            var _list = $(this);
            var _budget = _list.attr('data-row-budget'); 
            var _total = 0;           
            $.each(_list.find(':not(.hidden)'), function() {
                var _self = $(this);
                    _total += (_self.val() > 0) ? parseFloat(_self.val()) : 0;
            });
            if (_total != _budget) {
                _validation++;
            } 
        });
        return _validation;
    },

    gso_ppmpForm.prototype.init = function()
    {   
        $.gso_ppmpForm.getItemField();

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
            var _division = ($('#ppmp-card #division_id').val() > 0) ? $('#ppmp-card #division_id').val() : _parents.attr('data-row-division');
            var _id      = $.gso_ppmp.fetchID();
            var d1       = $.gso_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_ppmpForm.fetch_item_details(_self.val(), _parents, _id, _division);
                    $.gso_ppmpForm.update_lines(_id, _parents);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when ppmp modal select on change
        | ---------------------------------
        */
        this.$body.on('change', '#ppmp-modal select', function (e) {
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            $.gso_ppmpForm.fetch_budget_lists(
                _modal,
                _modal.find('select[name="fund_code_id"]').val(), 
                _modal.find('select[name="department_id"]').val(), 
                _modal.find('select[name="budget_category_id"]').val(), 
                _modal.find('input[name="budget_year"]').val()
            );
        });
        this.$body.on('blur', '#ppmp-modal input[name="budget_year"]', function (e) {
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            $.gso_ppmpForm.fetch_budget_lists(
                _modal,
                _modal.find('select[name="fund_code_id"]').val(), 
                _modal.find('select[name="department_id"]').val(), 
                _modal.find('select[name="budget_category_id"]').val(), 
                _modal.find('input[name="budget_year"]').val()
            );
        });

        this.$body.on('keyup', '#ppmp-modal table input', function (e) {
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _row   = _self.closest('tr');
            $.gso_ppmpForm.compute_list(
                _modal,
                _row,
                _self
            );
        });
        this.$body.on('blur', '#ppmp-modal table input', function (e) {
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _row   = _self.closest('tr');
            $.gso_ppmpForm.compute_list(
                _modal,
                _row,
                _self
            );
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
            var _id      = $.gso_ppmp.fetchID();
            var d1       = $.gso_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_ppmpForm.compute_budget(_self.val(), _parents);
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
            var _id      = $.gso_ppmp.fetchID();
            var d1       = $.gso_ppmpForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_ppmpForm.compute_budget(_self.val(), _parents);
                    $.gso_ppmpForm.update_lines(_id, _parents);
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
                        var _count = _table.find('tbody tr').length + 1;
                        _table.find('tbody').append(_itemField[_gl]);
                        _table.find('tbody tr:last-child select').attr('id', 'item_' + _gl + '_' + _rowCount);
                        $.gso_ppmp.preload_select3();
                        _row.next().find('td:first-child').text(_count);
                        _row.next().find('input').focus();
                    }
                }
            }
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
            var _id     = $.gso_ppmp.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.gso_ppmpForm.validate(_form, 0);
            var _error2 = $.gso_ppmpForm.validate_computations(_modal);

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
            } else if (!($('.budget-layer table tbody tr').length > 0)) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>The budget is not aligned.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else if (_error2 != 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>The budget are not properly distributed.",
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
                                        $.gso_ppmp.load_contents();
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
            var _id    = $.gso_ppmp.fetchID();          
            var _url   = _baseUrl + 'general-services/project-procurement-management-plan/send/for-approval/' + _id;

            var d1 = $.gso_ppmpForm.fetch_status(_id);
            var d2 = $.gso_ppmpForm.validate_division_status(_id);
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
                        html:  "Are you sure? <br/>the PPMP budget year (" + _year + ")<br/>[ " + _dep + " ]</strong><br/>will be send for approval.",
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
            var _id     = $.gso_ppmp.fetchID();
            var _action = _form.attr('action') + '/copy/' + _id;
            var _error  = $.gso_ppmpForm.validate(_form, 0);

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
                                        $.gso_ppmp.load_contents();
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

    //init gso_ppmpForm
    $.gso_ppmpForm = new gso_ppmpForm, $.gso_ppmpForm.Constructor = gso_ppmpForm

}(window.jQuery),

//initializing gso_ppmpForm
function($) {
    "use strict";
    $.gso_ppmpForm.init();
}(window.jQuery);
