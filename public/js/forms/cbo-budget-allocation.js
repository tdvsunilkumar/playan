!function($) {
    "use strict";

    var budget_allocationForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    budget_allocationForm.prototype.validate = function($form, $required)
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

    budget_allocationForm.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'finance/budget-allocations/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/budget-allocations/reload-items/' + _purchaseType,
                success: function(response) {
                    console.log(response.data);
                    _item.append('<option value="">select an item</option>');  
                    $.each(response.data, function(i, item) {
                        _item.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    budget_allocationForm.prototype.reload_uom = function(_uom, _item = 0)
    {   
        // if (_item > 0) {
            _uom.find('option').remove(); 
            console.log(_baseUrl + 'finance/budget-allocations/reload-uom/' + _item);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/budget-allocations/reload-uom/' + _item,
                success: function(response) {
                    console.log(response.data);
                    _uom.append('<option value="">select a uom</option>');  
                    _uom.append('<option value="' + response.data.id + '">' + response.data.code + '</option>');  
                    _uom.val(response.data.id);
                },
                async: false
            });

            return true;
        // }
    },

    budget_allocationForm.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        // if (_department > 0) {
            _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
            console.log(_baseUrl + 'finance/budget-allocations/reload-divisions-employees/' + _department);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/budget-allocations/reload-divisions-employees/' + _department,
                success: function(response) {
                    console.log(response.employees);
                    _employee.append('<option value="">select a requestor</option>');  
                    $.each(response.employees, function(i, item) {
                        _employee.append('<option value="' + item.id + '">' + item.fullname + '</option>');  
                    }); 
                    console.log(response.divisions);
                    _division.append('<option value="">select a division</option>');  
                    $.each(response.divisions, function(i, item) {
                        _division.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
                    }); 
                },
                async: false
            });

            return true;
        // }
    },

    budget_allocationForm.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        // if (_employee > 0) {
            _designation.find('option').remove(); 
            console.log(_baseUrl + 'finance/budget-allocations/reload-designation/' + _employee);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/budget-allocations/reload-designation/' + _employee,
                success: function(response) {
                    console.log(response.data);
                    _designation.append('<option value="">select a designation</option>');  
                    _designation.append('<option value="' + response.data.id + '">' + response.data.description + '</option>');  
                    _designation.val(response.data.id);
                },
                async: false
            });

            return true;
        // }
    },

    budget_allocationForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/budget-allocations/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-allocations/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    budget_allocationForm.prototype.fetch_payee_details = function (_id, _column)
    {   
        var _address = '';
        if (_id != '') {
            console.log(_baseUrl + 'finance/budget-allocations/fetch-payee-details/' + _id + '/' + _column);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/budget-allocations/fetch-payee-details/' + _id + '/' + _column,
                success: function(response) {
                    console.log(response);
                    _address = response.data;
                },
                async: false
            });
        }
        return _address;
    },

    budget_allocationForm.prototype.update2 = function (_id, _form)
    {   
        var _url = _baseUrl + 'finance/budget-allocations/update/' + _id + '?payee_id=' + _form.find('select[name="payee_id2"]').val() + '&fund_code_id=' + _form.find('select[name="fund_code_id2"]').val() + '&address=' + _form.find('textarea[name="address2"]').val() + '&particulars=' + _form.find('textarea[name="particulars2"]').val() + '&budget_year=' + _form.find('select[name="budget_year2"]').val() + '&funding_by=' + _form.find('select[name="funding_byx"]').val() + '&approval_by=' + _form.find('select[name="approval_byx"]').val();
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    budget_allocationForm.prototype.update = function (_id, _form)
    {   
        var _url = _baseUrl + 'finance/budget-allocations/update/' + _id + '?payee_id=' + _form.find('select[name="payee_id"]').val() + '&fund_code_id=' + _form.find('select[name="fund_code_id"]').val() + '&address=' + _form.find('textarea[name="address"]').val() + '&particulars=' + _form.find('textarea[name="particulars"]').val() + '&budget_year=' + _form.find('select[name="budget_year"]').val() + '&funding_by=' + _form.find('select[name="funding_byz"]').val() + '&approval_by=' + _form.find('select[name="approval_byz"]').val();
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    budget_allocationForm.prototype.updateRow = function (_id, _breakdown, _gl, _allocated, _modal)
    {
        var _url = _baseUrl + 'finance/budget-allocations/update-row/' + _id + '?breakdown=' + _breakdown + '&gl_account=' + _gl + '&allocated=' + _allocated;
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                console.log(response.remaining);
                console.log(response.amount);
                _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-remaining', response.remaining);
                _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-amount', response.amount);
                if (response.type == 'failed') {
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-amount', 0);
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"] td.remaining').text(response.remaining);
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"] td:last-child input').val(response.amount);
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The allocated amount is not enough.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            },
            async: false
        });
    },

    budget_allocationForm.prototype.updateRow2 = function (_id, _breakdown, _gl, _allocated, _modal)
    {
        var _url = _baseUrl + 'finance/budget-allocations/update-row2/' + _id + '?breakdown=' + _breakdown + '&gl_account=' + _gl + '&allocated=' + _allocated;
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                console.log(response.remaining);
                console.log(response.amount);
                _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-remaining', response.remaining);
                _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-amount', response.amount);
                if (response.type == 'failed') {
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"]').attr('data-row-amount', 0);
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"] td.remaining').text(response.remaining);
                    _modal.find('table tbody tr[data-row-id="' + _breakdown + '"] td:last-child input').val(response.amount);
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The allocated amount is not enough.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            },
            async: false
        });
    },

    budget_allocationForm.prototype.reload_division = function($department)
    {   
        var $division = $('#allob_division_id2'); $division.find('option').remove(); 

        console.log(_baseUrl + 'finance/budget-allocations/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-allocations/reload-division-via-department/' + $department,
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

    budget_allocationForm.prototype.view_alob_lines = function(_id, _department, _division, year, _button, _fund, _category)
    {   
        var _table = $('#available-alob-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'finance/budget-allocations/view-alob-lines/' + _id + '?department=' + _department + '&division=' + _division + '&year=' + year + '&fund=' + _fund + '&category=' + _category;
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '" data-gl-id="' + row.gl_id + '" data-row-total="' + row.total + '" data-row-remaining="' + row.remaining + '" data-row-amount="' + row.amount + '">';
                        _rows += '<td>' + (i + 1) + '</td>';
                        _rows += '<td>' + row.gl_code + '</td>';
                        _rows += '<td>' + row.gl_desc + '</td>';
                        _rows += '<td class="text-center total">' + row.total + '</td>';
                        _rows += '<td class="text-center remaining">' + row.remaining + '</td>';
                        _rows += '<td class="text-center"><input type="text" name="amount[]" class="numeric-double form-control text-center" value="' + ((row.amount > 0) ? row.amount : '') + '"/></td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('Add Line');
                    _table.find('tbody').append(_rows);
                    var d1 = $.budget_allocation.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('Add Line');
            }
        });
    },

    budget_allocationForm.prototype.fetch_alob_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/budget-allocations/fetch-alob-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/budget-allocations/fetch-alob-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    budget_allocationForm.prototype.view_alob_lines2 = function(_id, _department, _division, year, _button, _fund, _category)
    {   
        var _table = $('#available-alob-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'finance/budget-allocations/view-alob-lines2/' + _id + '?department=' + _department + '&division=' + _division + '&year=' + year + '&fund=' + _fund + '&category=' + _category;
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '" data-gl-id="' + row.gl_id + '" data-row-total="' + row.total + '" data-row-remaining="' + row.remaining + '" data-row-amount="' + row.amount + '">';
                        _rows += '<td>' + (i + 1) + '</td>';
                        _rows += '<td>' + row.gl_code + '</td>';
                        _rows += '<td>' + row.gl_desc + '</td>';
                        _rows += '<td class="text-center total">' + row.total + '</td>';
                        _rows += '<td class="text-center remaining">' + row.remaining + '</td>';
                        _rows += '<td class="text-center"><input type="text" name="amount[]" class="obligation numeric-double form-control text-center" value="' + ((row.amount > 0) ? row.amount : '') + '"/></td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('Add Line');
                    _table.find('tbody').append(_rows);
                    var d1 = $.budget_allocation.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('Add Line');
            }
        });
    },

    budget_allocationForm.prototype.init = function()
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
        | # when payee on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm"] select[name="payee_id"], form[name="alobForm2"] select[name="payee_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_payee_details(_self.val(), 'paye_full_address');
            var d2    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'pending' || v1 == 'draft') {    
                    _form.find('textarea[name="address"]').val(v1);
                    $.budget_allocationForm.update(_id, _form);
                } else {
                    $.budget_allocationForm.update2(_id, _form);
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when fund code on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm"] select[name="fund_code_id"], form[name="alobForm2"] select[name="fund_code_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending' || v1 == 'draft') {    
                    if (_self.attr('name') == 'fund_code_id') {           
                        $.budget_allocationForm.update(_id, _form);
                    } else {
                        $.budget_allocationForm.update2(_id, _form);
                    }           
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when particulars on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="alobForm"] textarea[name="particulars"], form[name="alobForm2"] textarea[name="particulars2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending' || v1 == 'draft') {       
                    if (_self.attr('name') == 'particulars') {           
                        $.budget_allocationForm.update(_id, _form);
                    } else {
                        $.budget_allocationForm.update2(_id, _form);
                    }      
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when year on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm"] select[name="budget_year"], form[name="alobForm2"] select[name="budget_year2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending' || v1 == 'draft') {      
                    if (_self.attr('name') == 'budget_year') {           
                        $.budget_allocationForm.update(_id, _form);
                    } else {
                        $.budget_allocationForm.update2(_id, _form);
                    }            
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when funding by on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm"] select[name="funding_byz"], form[name="alobForm2"] select[name="funding_byx"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending' || v1 == 'draft') {    
                    if (_self.attr('name') == 'funding_byz') {           
                        $.budget_allocationForm.update(_id, _form);
                    } else {
                        $.budget_allocationForm.update2(_id, _form);
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when approval by on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm"] select[name="approval_byz"], form[name="alobForm2"] select[name="approval_byx"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.budget_allocation.fetch_alobID();
            var d1    = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending' || v1 == 'draft') {       
                    if (_self.attr('name') == 'approval_byz') {   
                        $.budget_allocationForm.update(_id, _form);
                    } else {
                        $.budget_allocationForm.update2(_id, _form);
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when add line button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'button.add-alob-line-btn', function (event) {
            var _self  = $(this);
            var _id    = $.budget_allocation.fetchID();
            var _form  = _self.closest('form');
            var _error = $.budget_allocationForm.validate(_form, 0);

            var d1 = $.budget_allocationForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'requested') {
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
                        _self.prop('disabled', true).html('wait.....');
                        setTimeout(function () {
                            $.budget_allocationForm.view_alob_lines(_id, _form.find('select[name="allob_department_id"]').val(), _form.find('select[name="allob_division_id"]').val(), _form.find('select[name="budget_year"]').val(), _self, _form.find('select[name="fund_code_id"]').val(), _form.find('select[name="budget_category_id"]').val());
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
                }
            });
        });

        /*
        | ---------------------------------
        | # when add line button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'button.add-alob-line-btn2', function (event) {
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _error = $.budget_allocationForm.validate(_form, 0);
            var _id    = $.budget_allocation.fetch_alobID();
            var d1 = $.budget_allocationForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'pending') {
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
                        _self.prop('disabled', true).html('wait.....');
                        setTimeout(function () {
                            $.budget_allocationForm.view_alob_lines2(_id, _form.find('select[name="allob_department_id2"]').val(), _form.find('select[name="allob_division_id2"]').val(), _form.find('select[name="budget_year2"]').val(), _self, _form.find('select[name="fund_code_id2"]').val(), _form.find('select[name="budget_category_id2"]').val());
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
                }
            });
        });

        /*
        | ---------------------------------
        | # when keywords on search
        | ---------------------------------
        */
        this.$body.on('keyup', '#keyword', function (event) {
            var input, filter, table, tr, td, td1, i, txtValue;
            input = document.getElementById("keyword");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-alob-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    td1  = tr[i].getElementsByTagName("td")[2];
                    if (td || td1) {
                        txtValue = td.textContent + '' + td1.textContent || td.innerText + td1.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            } else {
                $.budget_allocation.validate_table($('#available-alob-table'));
            }
        });
        this.$body.on('search', '#keyword', function (event) {
            $.budget_allocation.validate_table($('#available-alob-table'));
        });

        /*
        | ---------------------------------
        | # when amount onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#available-alob-table input[name="amount[]"]', function (event) {
            var _self = $(this);
            var _rows = _self.closest('tr');
            var _amount = _rows.attr('data-row-amount') ? _rows.attr('data-row-amount') : 0;
            var _remaining = _rows.attr('data-row-remaining') ? _rows.attr('data-row-remaining') : 0;            
            var _remainingTD = _rows.find('td.remaining');
            var _totalAmt = parseFloat(_remaining) + parseFloat(_amount);
            if (_self.val() > 0) {
                if (parseFloat(_self.val()) > parseFloat(_totalAmt)) {
                    _self.val(_totalAmt);
                    var _totalLeft = parseFloat(_totalAmt) - parseFloat(_self.val());
                    _remainingTD.text(_totalLeft);
                } else {
                    var _totalLeft = parseFloat(_totalAmt) - parseFloat(_self.val());
                    _remainingTD.text(_totalLeft);
                }
            } else {
                _remainingTD.text(_totalAmt);
            }
        });

        /*
        | ---------------------------------
        | # when amount onblur
        | ---------------------------------
        */
        this.$body.on('blur', '#available-alob-table input[name="amount[]"]', function (event) {
            var _self      = $(this);
            var _rows      = _self.closest('tr');
            var _breakdown = _rows.attr('data-row-id');
            var _glAccount = _rows.attr('data-gl-id');
            var _modal     = $(this).closest('.modal');
            if (_self.hasClass('obligation')) {
                var _id = $.budget_allocation.fetch_alobID();
                $.budget_allocationForm.updateRow2(_id, _breakdown, _glAccount, _self.val(), _modal);
            } else {
                var _id = $.budget_allocation.fetchID();
                $.budget_allocationForm.updateRow(_id, _breakdown, _glAccount, _self.val(), _modal);
            }
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#budget-allocation-modal .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _total1 = _modal.find('table#itemRequisitionTable th.fs-5.text-end').text().replace('₱', '');
            var _total2 = _modal.find('table#allotmentBreakdownTable th.fs-5.text-end').text().replace('₱', '');
            var _total1 = _total1.replace(',', '');
            var _total2 = _total2.replace(',', '');
            var _toast  = _modal.find('#modalToast');
            var _dep    = $.budget_allocation.fetchID(); 
            var _id     = $.budget_allocation.fetch_alobID();  
            var _form   = _modal.find('form[name="alobForm"]');  
            var _form2  = _modal.find('form[name="alobForm2"]');  
            var _total3 = _modal.find('table#allotmentBreakdownTable2 th:first-child.text-end').text();
            var _code   = _form.find('input[name="control_no"]').val();
            var _code2  = _form2.find('input[name="control_no2"]').val();
            var _url    = _baseUrl + 'finance/budget-allocations/send/for-alob-approval/' + _id + '?departmental=' + _dep;
            
            if (_dep > 0) {
                if (parseFloat(_total2) >= parseFloat(_total1)) {
                    var d1 = $.budget_allocationForm.fetch_status(_dep);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        _self.prop('disabled', true).html('wait.....');
                        if (v1 == 'requested') {
                            Swal.fire({
                                html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code +")</strong> will be sent.",
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
                                                _self.prop('disabled', true).html('wait.....');
                                                _modal.find('.add-alob-line-btn').addClass('hidden');  
                                                setTimeout(function () {
                                                    _toast.find('.toast-body').html(response.text);
                                                    _toast.show();                                              
                                                    _form.find('input[name="budget_no"]').val(response.budget_no);
                                                    _form.find('select.required, textarea.required').prop('disabled', true);
                                                    $.budget_allocation.load_contents();
                                                }, 500 + 300 * (Math.random() * 5));
                                                setTimeout(function () {
                                                    _self.prop('disabled', false).html('Send Request').addClass('hidden');
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
                                    : "cancel" === t.dismiss, _self.prop('disabled', false).html('Send Request')
                            });
                        } else {
                            _self.prop('disabled', false).html('Send Request');
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
                        html: "Unable to proceed!<br/>The alob amount should be higher or equal to item amount.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
            } else {
                if (_total3 !== '₱0.00' && _total3 !== '0.00') {
                    var d1 = $.budget_allocationForm.fetch_alob_status(_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        _self.prop('disabled', true).html('wait.....');
                        if (v1 == 'pending') {
                            Swal.fire({
                                html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code2 +")</strong> will be sent.",
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
                                                _self.prop('disabled', true).html('wait.....');
                                                _modal.find('.add-alob-line-btn2').addClass('hidden');  
                                                setTimeout(function () {
                                                    _toast.find('.toast-body').html(response.text);
                                                    _toast.show();                                              
                                                    _form2.find('input[name="budget_no2"]').val(response.budget_no);
                                                    _form2.find('select.required, textarea.required').prop('disabled', true);
                                                    $.budget_allocation.load_contents();
                                                }, 500 + 300 * (Math.random() * 5));
                                                setTimeout(function () {
                                                    _self.prop('disabled', false).html('Send Request').addClass('hidden');
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
                                    : "cancel" === t.dismiss, _self.prop('disabled', false).html('Send Request')
                            });
                        } else {
                            _self.prop('disabled', false).html('Send Request');
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
                        html: "Unable to proceed!<br/>The alob amount should be higher than zero.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                }
            }
        });
    }

    //init budget_allocationForm
    $.budget_allocationForm = new budget_allocationForm, $.budget_allocationForm.Constructor = budget_allocationForm

}(window.jQuery),

//initializing budget_allocationForm
function($) {
    "use strict";
    $.budget_allocationForm.init();
}(window.jQuery);
