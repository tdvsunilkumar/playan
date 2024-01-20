!function($) {
    "use strict";

    var obligation_requestForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _allotmentID = 0;
    var _lastSegments = '';

    obligation_requestForm.prototype.validate = function($form, $required)
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

    obligation_requestForm.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-items/' + _purchaseType,
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

    obligation_requestForm.prototype.reload_uom = function(_uom, _item = 0)
    {   
        // if (_item > 0) {
            _uom.find('option').remove(); 
            console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-uom/' + _item);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-uom/' + _item,
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

    obligation_requestForm.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        // if (_department > 0) {
            _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
            console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-divisions-employees/' + _department);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-divisions-employees/' + _department,
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
                    // if (response.divisions.length == 1) {
                    //     _division.find('option').val(3).trigger('change.select3');
                    // }
                },
                async: false
            });

            return true;
        // }
    },

    obligation_requestForm.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        // if (_employee > 0) {
            _designation.find('option').remove(); 
            console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-designation/' + _employee);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-designation/' + _employee,
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

    obligation_requestForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    obligation_requestForm.prototype.view_alob_lines = function(_id, _department, _division, year, _button)
    {   
        var _table = $('#available-alob-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/view-alob-lines/' + _id + '?department=' + _department + '&division=' + _division + '&year=' + year;
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
                    _modal.modal('show'); 
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('Add Line');
            }
        });
    },

    obligation_requestForm.prototype.fetch_alob_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-alob-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-alob-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    obligation_requestForm.prototype.fetch_alob_payee_details = function (_id, _column)
    {   
        var _address = '';
        if (_id != '') {
            console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-payee-details/' + _id + '/' + _column);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/fetch-payee-details/' + _id + '/' + _column,
                success: function(response) {
                    console.log(response);
                    _address = response.data;
                },
                async: false
            });
        }
        return _address;
    },

    obligation_requestForm.prototype.update = function (_id, _form)
    {
        var _withPR = (_form.find('input[type="checkbox"][name="with_pr2"]:checked').length > 0) ? 1 : 0;
        var _url = _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/update/' + _id + '?with_pr=' + _withPR + '&type=' + _lastSegments + '&department_id=' + _form.find('select[name="allob_department_id2"]').val() + '&division_id=' + _form.find('select[name="allob_division_id2"]').val() + '&payee_id=' + _form.find('select[name="payee_id2"]').val() + '&fund_code_id=' + _form.find('select[name="fund_code_id2"]').val() + '&address=' + encodeURIComponent(_form.find('textarea[name="address2"]').val()) + '&particulars=' + encodeURIComponent(_form.find('textarea[name="particulars2"]').val()) + '&budget_year=' + _form.find('select[name="budget_year2"]').val() + '&employee_id=' + _form.find('select[name="employee_id2"]').val() + '&designation_id=' + _form.find('select[name="designation_id2"]').val() + '&budget_category_id=' + _form.find('select[name="budget_category_id2"]').val();
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                if (_allotmentID == 0) {
                    if (response.data != '') {
                        _allotmentID = response.data.id;
                        if (_form.find('select[name="budget_year2"]').val() == '') {
                            _form.find('select[name="budget_year2"]').val(response.data.budget_year).trigger('change.select3'); 
                        }
                        _form.find('select[name="budget_year2"]').prop('disabled', true);
                        _form.find('input[name="control_no2"]').val(response.data.budget_control_no);
                    }
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    obligation_requestForm.prototype.fetch_alobID = function()
    {
        return _allotmentID;
    }

    obligation_requestForm.prototype.update_alobID = function(_id)
    {
        return _allotmentID = _id;
    }

    obligation_requestForm.prototype.reload_division = function($department)
    {   
        var $division = $('#allob_division_id2'); $division.find('option').remove(); 

        console.log(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-division-via-department/' + $department);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/reload-division-via-department/' + $department,
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

    obligation_requestForm.prototype.view_alob_lines2 = function(_id, _department, _division, year, _button, _fund, _category)
    {   
        var _table = $('#available-alob-table'); _table.find('tbody').empty();
        var _modal = $('#view-alob-modal'); //_table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/view-alob-lines2/' + _id + '?department=' + _department + '&division=' + _division + '&year=' + year + '&fund=' + _fund + '&type=' + _lastSegments + '&category=' + _category;
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
                    var d1 = $.obligation_request.validate_table(_table);
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

    obligation_requestForm.prototype.updateRow2 = function (_id, _breakdown, _gl, _allocated, _modal)
    {
        var _url = _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/update-row2/' + _id + '?breakdown=' + _breakdown + '&gl_account=' + _gl + '&allocated=' + _allocated;
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

    obligation_requestForm.prototype.init = function()
    {   
        _lastSegments = $.obligation_request.getLastSegment();
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
        | # when keywords on search
        | ---------------------------------
        */
        this.$body.on('keyup', '#keyword', function (event) {
            var input, filter, table, tr, td, td1, td2, td3, i, txtValue;
            input = document.getElementById("keyword");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-alob-table");
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
               $.obligation_request.validate_table($('#available-alob-table'));
            }
        });
        this.$body.on('search', '#view-alob-modal input[type="search"]', function (e) {
            $.obligation_request.validate_table($('#available-alob-table'));
        });

        /*
        | ---------------------------------
        | # when purchase type on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="purchase_type_id"]', function (event) {
            var _self = $(this);
            $.obligation_requestForm.reload_items($('select[name="item_id"]'), $('select[name="uom_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when item on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="item_id"]', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() == '') {
                $.requisition.updateLineID(0);
                _modal.find('textarea[name="item_remarks"]').val('');
                _modal.find('select[name="uom_id"]').val('');
                _modal.find('input[name="quantity_requested"]').val('');
                _modal.find('button.store-btn').html('Add Item');
                _modal.find('select.select3').trigger('change.select3');
            }
            $.obligation_requestForm.reload_uom($('select[name="uom_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="department_id"]', function (event) {
            var _self = $(this);
            $.obligation_requestForm.reload_divisions_employees($('select[name="division_id"]'), $('select[name="employee_id"]'), $('select[name="designation_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="employee_id"]', function (event) {
            var _self = $(this);
            $.obligation_requestForm.reload_designation($('select[name="designation_id"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when department field onchange
        | ---------------------------------
        */
        this.$body.on('change', '#allob_department_id2', function (e){
            e.preventDefault();
            var _self = $(this);
            if (_self.val() > 0) {
                $.obligation_requestForm.reload_division(_self.val());
            }
        });

        /*
        | ---------------------------------
        | # when submit btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.store-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = $('form[name="obligation_requestForm"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _lineId = $.requisition.fetchLineID();
            var _method = (_lineId > 0) ? 'PUT' : (_id > 0) ? 'PUT' : 'POST';
            var _action = (_lineId > 0) ? _form.attr('action') + '/update-line/' + _lineId + '?uom_id=' + _form.find('#uom_id').val() : (_id > 0) ? _form.attr('action') + '/update/' + _id + '?department_id=' + _form.find('#department_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val()  : _form.attr('action') + '/store?department_id=' + _form.find('#department_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val();
            var _error  = $.obligation_requestForm.validate(_form, 0);

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
                var d1 = $.obligation_requestForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        $.ajax({
                            type: _method,
                            url: _action,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    if (_id <= 0) {
                                        _form.find('input[name="control_no"]').val(response.data.control_no);
                                        $.requisition.updateID(response.data.id);
                                    }
                                    setTimeout(function () {
                                        _toast.find('.toast-body').html(response.text);
                                        _toast.show();
                                        _self.html('Add Item').prop('disabled', false);
                                        _form.find('select[name="department_id"]').prop('disabled', true);
                                        _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                                        _form.find('select[name="item_id"]').val('').trigger('change.select3'); 
                                        _form.find('select[name="uom_id"]').val('').trigger('change.select3'); 
                                        _form.find('input[name="quantity_requested"]').val('');
                                        _form.find('textarea[name="item_remarks"]').val('');
                                        var d1 = $.requisition.load_line_contents();
                                        $.when( d1 ).done(function ( v1 ) {
                                            _modal.find('table th.fs-5.text-end').text('₱' + $.requisition.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
                                        });
                                    }, 500 + 300 * (Math.random() * 5));
                                    setTimeout(function () {
                                        _toast.hide();
                                    }, 5000);
                                } else {
                                    _self.html('Add Item').prop('disabled', false);
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
                    } else {
                        _self.html('Add Item').prop('disabled', false);
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
            }
        });
        
        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#departmental-requisition-modal .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _toast  = _modal.find('#modalToast');
            var _id     = _allotmentID;  
            var _form   = _modal.find('form[name="alobForm2"]');  
            var _code   = _form.find('input[name="control_no2"]').val();
            var _url    = _baseUrl + 'finance/obligation-requests/' + _lastSegments + '/send/for-approval/' + _id;
            
            var d1 = $.obligation_request.fetch_total_allotment_amount2();
            var d2 = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {   
                if (v1 > 0) {
                    _self.prop('disabled', true).html('wait.....');
                    if (v2 == 'draft') {
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
                                            _modal.find('.add-alob-line-btn2').addClass('hidden');  
                                            setTimeout(function () {
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();                                            
                                                _form.find('input[name="budget_no"]').val(response.budget_no);
                                                _form.find('select.required, textarea.required').prop('disabled', true);
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden'); 
                                                _modal.find('button.print-btn').removeClass('hidden');    
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
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>Please add a budget allotment first.",
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
        this.$body.on('click', 'button.add-alob-line-btn', function (event) {
            var _self  = $(this);
            var _id    = $.obligation_request.fetchID();
            var _form  = _self.closest('form');
            var _error = $.obligation_requestForm.validate(_form, 0);

            _self.prop('disabled', true).html('wait.....');
            var d1 = $.obligation_requestForm.fetch_status(_id);
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
                        setTimeout(function () {
                            $.budget_allocationForm.view_alob_lines(_id, _form.find('select[name="allob_department_id"]').val(), _form.find('select[name="allob_division_id"]').val(), _form.find('select[name="budget_year"]').val(), _self);
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
            var _error = $.obligation_requestForm.validate(_form, 0);
            var _id    = _allotmentID;

            var d1 = $.obligation_requestForm.fetch_alob_status(_id);
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
                        _self.prop('disabled', true).html('wait.....');
                        setTimeout(function () {
                            $.obligation_requestForm.view_alob_lines2(_id, _form.find('select[name="allob_department_id2"]').val(), _form.find('select[name="allob_division_id2"]').val(), _form.find('select[name="budget_year2"]').val(), _self, _form.find('select[name="fund_code_id2"]').val(), _form.find('select[name="budget_category_id2"]').val());
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
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="allob_department_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            var d2    = $.obligation_requestForm.reload_divisions_employees(_form.find('select[name="division_id2"]'), _form.find('select[name="employee_id2"]'), _form.find('select[name="designation_id2"]'), _self.val());
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });
        this.$body.on('change', 'form[name="alobForm2"] select[name="budget_category_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when with pr on click
        | ---------------------------------
        */
        this.$body.on('click', 'form[name="alobForm2"] input[type="checkbox"][name="with_pr2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="employee_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            var d2    = $.obligation_requestForm.reload_designation($('select[name="designation_id2"]'), _self.val());
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when department on change
        | ---------------------------------
        */
        // this.$body.on('change', 'form[name="alobForm2"] select[name="allob_department_id2"]', function (event) {
        //     var _self = $(this);
        //     var _form = _self.closest('form');
        //     var _id   = _allotmentID;
        //     var d1    = $.obligation_requestForm.fetch_alob_status(_id);
        //     $.when( d1 ).done(function ( v1 ) 
        //     {  
        //         if (v1 == 'draft') {
        //             $.obligation_requestForm.update(_id, _form);
        //         } else {
        //             console.log('sorry cannot be processed');
        //         }
        //     });
        // });

        /*
        | ---------------------------------
        | # when division on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="allob_division_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="payee_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_payee_details(_self.val(), 'paye_full_address');
            var d2    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v2 == 'draft') {
                    _form.find('textarea[name="address2"]').val(v1);
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when budget year on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="budget_year2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when fund code on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="alobForm2"] select[name="fund_code_id2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
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
        this.$body.on('blur', 'form[name="alobForm2"] textarea[name="particulars2"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.obligation_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when amount onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#available-alob-table input[name="amount[]"]', function (event) {
            var _self = $(this);
            var _rows = _self.closest('tr');
            var _amount = _rows.attr('data-row-amount');
            var _remaining = _rows.attr('data-row-remaining');            
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
            var _id        = _allotmentID;
            var _breakdown = _rows.attr('data-row-id');
            var _glAccount = _rows.attr('data-gl-id');
            var _modal     = $(this).closest('.modal');
            $.obligation_requestForm.updateRow2(_id, _breakdown, _glAccount, _self.val(), _modal);
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#departmental-requisition-modal .print-btn', function (e){
            e.preventDefault();
            var _id   = _allotmentID;
            var d1    = $.obligation_requestForm.fetch_alob_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 !== 'draft') {
                    if ($('.alob-v1.hidden').length > 0) {
                        var _code = $('form[name="alobForm"] input[name="control_no"]').val();
                    } else {
                        var _code = $('form[name="alobForm2"] input[name="control_no2"]').val();
                    }
                    window.open(_baseUrl + 'finance/obligation-requests/' + _lastSegments + '/print/' + _code, '_blank');
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>Unable to print a draft status request.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                }
            })
        });
    }

    //init obligation_requestForm
    $.obligation_requestForm = new obligation_requestForm, $.obligation_requestForm.Constructor = obligation_requestForm

}(window.jQuery),

//initializing obligation_requestForm
function($) {
    "use strict";
    $.obligation_requestForm.init();
}(window.jQuery);
