!function($) {
    "use strict";

    var bac_abstractForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _suppliers = []; var _purchases = []; var _committees = [];

    bac_abstractForm.prototype.validate = function($form, $required)
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

    bac_abstractForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'general-services/bac/abstract-of-canvass/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/bac/abstract-of-canvass/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    bac_abstractForm.prototype.view_available_suppliers = function(_id, _button)
    {   
        var _table = $('#available-supplier-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/view-available-suppliers/' + _id;
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
                        _rows += '<td>' + (row.business_name ? row.business_name : '') + '</td>';
                        _rows += '<td>' + (row.branch_name ? row.branch_name : '') + '</td>';
                        _rows += '<td>' + (row.mobile_no ? row.mobile_no : '') + '</td>';
                        _rows += '<td>' + (row.email_address ? row.email_address : '') + '</td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('ADD LINE');
                    _table.find('tbody').append(_rows);
                    var d1 = $.bac_abstract.validate_table(_table);
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

    bac_abstractForm.prototype.view_available_purchase_requests = function(_id, _button)
    {   
        var _table = $('#available-purchase-request-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/view-available-purchase-requests/' + _id;
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
                        _rows += '<td>' + (row.pr_no ? row.pr_no : '') + '</td>';
                        _rows += '<td>' + (row.department ? row.department : '') + '</td>';
                        _rows += '<td>' + (row.division ? row.division : '') + '</td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('ADD LINE');
                    _table.find('tbody').append(_rows);
                    var d1 = $.bac_abstract.validate_table(_table);
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

    bac_abstractForm.prototype.update = function(_id, _form)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/bac/abstract-of-canvass/update/' + _id;
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_abstractForm.prototype.update_row = function(_id, _rows)
    {   
        var _modal = _rows.closest('.modal');
        var _table = _rows.closest('table');
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/update-row/' + _id + '?item=' + _rows.attr('data-row-item') + '&supplier=' + _rows.attr('data-row-supplier') + '&quantity=' + _rows.attr('data-row-quantity') + '&unit_cost=' + _rows.find('input[name="unit_cost"]').val() + '&total_cost=' + _rows.find('input[name="total_cost"]').val() + '&description=' + _rows.find('input[name="brand"]').val() + '&remarks=' + _rows.find('input[name="remarks"]').val();
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                _table.find('tfoot th.text-danger').text($.bac_abstract.price_separator(parseFloat(response.total).toFixed(2)));
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_abstractForm.prototype.update_canvass = function(_id, _supplier, _form)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/bac/abstract-of-canvass/update-canvass/' + _id + '/' + _supplier;
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_abstractForm.prototype.submit_canvass = function(_id, _supplier, _self)
    {   
        var _modal = _self.closest('.modal');
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/submit-canvass/' + _id + '/' + _supplier;
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                _self.addClass('hidden');
                _modal.modal('hide');
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_abstractForm.prototype.validate_supplier = function(_id, _required)
    {
        _required = 0;
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'general-services/bac/request-for-quotations/validate-supplier/' + _id,
            success: function(response) {
                console.log(response);
                _required = response.data;
            },
            async: false
        });
        return _required;
    }   

    bac_abstractForm.prototype.view_available_committees = function(_id, _button)
    {   
        var _table = $('#available-committee-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal'); _committees = [];
        var _rows  = '';
        var _url = _baseUrl + 'general-services/bac/abstract-of-canvass/view-available-committees/' + _id;
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
                        _rows += '<td>' + (row.name ? row.name : '') + '</td>';
                        _rows += '<td>' + (row.department ? row.department : '') + '</td>';
                        _rows += '<td>' + (row.division ? row.division : '') + '</td>';
                        _rows += '<td>' + (row.designation ? row.designation : '') + '</td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('ADD COMMITTEE');
                    _table.find('tbody').append(_rows);
                    var d1 = $.bac_abstract.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('ADD COMMITTEE');
            }
        });
    },

    bac_abstractForm.prototype.init = function()
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
        | # keypress numeric double
        | ---------------------------------
        */
        this.$body.on('keyup', 'input[name="name"]', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _form = _self.closest('form');
            var _text = _self.val();
            _form.find('input[name="code"]').val(_text.replace(/\s+/g, '-').toLowerCase());
            // _form.find('input[name="slug"]').val(_text.replace(/\s+/g, '-').toLowerCase());
        });

        /*
        | ---------------------------------
        | # when add supplier button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-supplier', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id = $.bac_abstract.fetchID();

            _self.prop('disabled', true).html('WAIT.....');
            var d1 = $.bac_abstractForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    setTimeout(function () {
                        $.bac_abstractForm.view_available_suppliers(_id, _self);
                    }, 500 + 300 * (Math.random() * 5));
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
        this.$body.on('keyup', '#keyword2', function (event) {
            var input, filter, table, tr, td, td1, td2, td3, i, txtValue;
            input = document.getElementById("keyword2");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-supplier-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                $('.pager').remove();
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    td1  = tr[i].getElementsByTagName("td")[2];
                    td2  = tr[i].getElementsByTagName("td")[3];
                    td3  = tr[i].getElementsByTagName("td")[4];
                    if (td || td1 || td2 || td3) {
                        txtValue = td.textContent + '' + td1.textContent + '' + td2.textContent + '' + td3.textContent || td.innerText + td1.innerText + td2.innerText + td3.innerText; 
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            } else {
               $.bac_abstract.validate_table($('#available-supplier-table'));
            }
        });

        /*
        | ---------------------------------
        | # when supplier modal button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-supplier-modal button', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = $('#add-supplier-modal');
            var _id = $.bac_abstract.fetchID();

            if (_suppliers.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.bac_abstractForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.ajax({
                            type: 'POST',
                            url: _baseUrl + 'general-services/bac/request-for-quotations/add-suppliers/' + _id,
                            data: {'suppliers' : _suppliers},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('Close').prop('disabled', false);
                                        _modal.modal('hide');
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Close').prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        _self.prop('disabled', false).html('Close');
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
        | # when supplier modal checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#add-supplier-modal input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.is(':checked')) {
                _modal.find('tr input[type="checkbox"]').prop('checked', true);
                $.each(_modal.find('input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _suppliers.length; i++) {
                            if (_suppliers[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _suppliers.push(checkbox.val());
                        }
                    } 
                });
                console.log(_suppliers);
            } else {
                _modal.find('tr input[type="checkbox"]').prop('checked', false);
                $.each(_modal.find("input[type='checkbox'][value!='all']"), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _suppliers.length; i++) {
                        if (_suppliers[i] == checkbox.val()) {
                            _suppliers.splice(i, 1);
                        }
                    }
                });
                console.log(_suppliers);
            }
        });
        /*
        | ---------------------------------
        | # when supplier modal checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#add-supplier-modal input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _suppliers.push(_self.val());
            } else {
                for (var i = 0; i < _suppliers.length; i++) {
                    if (_suppliers[i] == _self.val()) {
                        _suppliers.splice(i, 1);
                    }
                }
            }
            console.log(_suppliers);
        });

        /*
        | ---------------------------------
        | # when add pr button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-pr', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id = $.bac_abstract.fetchID();

            _self.prop('disabled', true).html('WAIT.....');
            var d1 = $.bac_abstractForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    setTimeout(function () {
                        $.bac_abstractForm.view_available_purchase_requests(_id, _self);
                    }, 500 + 300 * (Math.random() * 5));
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
        this.$body.on('keyup', '#keyword1', function (event) {
            var input, filter, table, tr, td, td1, td2, i, txtValue;
            input = document.getElementById("keyword1");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-purchase-request-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                $('.pager').remove();
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    td1  = tr[i].getElementsByTagName("td")[2];
                    td2  = tr[i].getElementsByTagName("td")[3];
                    if (td || td1 || td2) {
                        txtValue = td.textContent + '' + td1.textContent + '' + td2.textContent || td.innerText + td1.innerText + td2.innerText; 
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            } else {
               $.bac_abstract.validate_table($('#available-supplier-table'));
            }
        });

        /*
        | ---------------------------------
        | # when pr modal button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-purchase-request-modal button', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.bac_abstract.fetchID();

            if (_purchases.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.bac_abstractForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.ajax({
                            type: 'POST',
                            url: _baseUrl + 'general-services/bac/request-for-quotations/add-purchase-request/' + _id,
                            data: {'purchases' : _purchases},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('Close').prop('disabled', false);
                                        $('#rfq-modal').find('label[for="total_budget"].text-danger').text('₱' + $.bac_abstract.price_separator(parseFloat(response.total).toFixed(2)));
                                        _modal.modal('hide');
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Close').prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        _self.prop('disabled', false).html('Close');
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
        | # when pr modal checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#add-purchase-request-modal input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.is(':checked')) {
                _modal.find('tr input[type="checkbox"]').prop('checked', true);
                $.each(_modal.find('input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _purchases.length; i++) {
                            if (_purchases[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _purchases.push(checkbox.val());
                        }
                    } 
                });
            } else {
                _modal.find('tr input[type="checkbox"]').prop('checked', false);
                $.each(_modal.find("input[type='checkbox'][value!='all']"), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _purchases.length; i++) {
                        if (_purchases[i] == checkbox.val()) {
                            _purchases.splice(i, 1);
                        }
                    }
                });
            }
            console.log(_purchases);
        });
        /*
        | ---------------------------------
        | # when pr modal checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#add-purchase-request-modal input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _purchases.push(_self.val());
            } else {
                for (var i = 0; i < _purchases.length; i++) {
                    if (_purchases[i] == _self.val()) {
                        _purchases.splice(i, 1);
                    }
                }
            }
            console.log(_purchases);
        });

        /*
        | ---------------------------------
        | # when unit cost onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#available-canvass-table input[name="unit_cost"]', function (event) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _qty   = _rows.attr('data-row-quantity');
            var _total = _rows.find('td input[disabled]');

            if (_self.val() > 0) {
                _total.val(parseFloat(_qty) * parseFloat(_self.val()));
            } else {
                _total.val('');
            }
        });

        /*
        | ---------------------------------
        | # when unit cost onblur
        | ---------------------------------
        */
        this.$body.on('blur', '#available-canvass-table input[name="unit_cost"]', function (event) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _qty   = _rows.attr('data-row-quantity');
            var _total = _rows.find('td input[disabled]');
            var _id   = $.bac_abstract.fetchID();
            var d1    = $.bac_abstractForm.fetch_status(_id);
            var d2    = $.bac_abstract.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    if (_self.val() > 0) {
                        _total.val(parseFloat(_qty) * parseFloat(_self.val()));
                    } else {
                        _total.val('');
                    }
                    $.bac_abstractForm.update_row(_id, _rows);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when brand model onblur
        | ---------------------------------
        */
        this.$body.on('blur', '#available-canvass-table input[name="brand"]', function (event) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _id    = $.bac_abstract.fetchID();
            var d1     = $.bac_abstractForm.fetch_status(_id);
            var d2     = $.bac_abstract.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_abstractForm.update_row(_id, _rows);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when brand model onblur
        | ---------------------------------
        */
        this.$body.on('blur', '#available-canvass-table input[name="remarks"]', function (event) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _id    = $.bac_abstract.fetchID();
            var d1     = $.bac_abstractForm.fetch_status(_id);
            var d2     = $.bac_abstract.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_abstractForm.update_row(_id, _rows);
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
        this.$body.on('click', 'form[name="rfqForm"] button.send-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _code   = _form.find('label[for="control_no"].text-danger').text();
            var _committeeTable = $('#committeeTable');
            var _id     = $.bac_abstract.fetchID();
            var d1      = $.bac_abstractForm.fetch_status(_id);
            var _error  = $.bac_abstractForm.validate(_form, 0);
            var _url    = _baseUrl + 'general-services/bac/abstract-of-canvass/send/for-abstract-approval/' + _id;
            var _toast  = $('#indexToast');
            
            console.log(_url);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'processed') {
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
                    } else if (_committeeTable.find('tbody td.dataTables_empty').length > 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some committee member first.",
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
                        Swal.fire({
                            html: "Are you sure? <br/>the request with <strong>Control No<br/>("+ _code +")</strong> will be sent.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, send it!",
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
                                            _modal.find('button.print-btn').removeClass('hidden');  
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();       
                                                _modal.modal('hide');                                       
                                                $.bac_abstract.load_contents();
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Send Request')
                        });
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when canvass 
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="canvassForm"] input', function (event) {
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _id    = $.bac_abstract.fetchID();
            var d1     = $.bac_abstractForm.fetch_status(_id);
            var d2     = $.bac_abstract.fetchSupplierStatus();
            var d3     = $.bac_abstract.fetchSupplierID();
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_abstractForm.update_canvass(_id, v3, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when canvass 
        | ---------------------------------
        */
        this.$body.on('click', '#canvass-modal .submit-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _modal = _self.closest('.modal');
            var _error = $.bac_abstractForm.validate(_form, 0);
            var _id    = $.bac_abstract.fetchID();
            var d1     = $.bac_abstractForm.fetch_status(_id);
            var d2     = $.bac_abstract.fetchSupplierStatus();
            var d3     = $.bac_abstract.fetchSupplierID();
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
                $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
                {  
                    if (v1 == 'draft' && v2 == 'pending') {                    
                        _self.prop('disabled', true).html('wait.....');
                        Swal.fire({
                            html: "Are you sure? <br/>the canvass will be submitted.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, submit it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _baseUrl + 'general-services/bac/abstract-of-canvass/submit-canvass/' + _id + '/' + v3,
                                    success: function(response) {
                                        console.log(response);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                e.isConfirmed && ((t.disabled = !1));                                                
                                                _self.addClass('hidden');
                                                _modal.modal('hide');
                                            }
                                        );
                                    },
                                    complete: function() {
                                        window.onkeydown = null;
                                        window.onfocus = null;
                                    }
                                })
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Submit') 
                        });
                    } else {
                        console.log('sorry cannot be processed');
                    }
                });
            }
        });

        /*
        | ---------------------------------
        | # when rfqForm on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="rfqForm"] textarea', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.bac_abstract.fetchID();
            var d1    = $.bac_abstractForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft' || v1 == 'processed') {
                    $.bac_abstractForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when add committee button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-committee', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id = $.bac_abstract.fetchID();

            var d1 = $.bac_abstractForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    _self.prop('disabled', true).html('WAIT.....');
                    setTimeout(function () {
                        $.bac_abstractForm.view_available_committees(_id, _self);
                    }, 500 + 300 * (Math.random() * 5));
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
        | # when add committee modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-committee-modal', function (e) {
            $.bac_abstract.load_committee_contents();
            _committees = [];
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
            table = document.getElementById("available-committee-table");
            tr = table.getElementsByTagName("tr");
            
            if (input.value.length > 0) {
                $('.pager').remove();
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    td1  = tr[i].getElementsByTagName("td")[2];
                    td2  = tr[i].getElementsByTagName("td")[3];
                    td3  = tr[i].getElementsByTagName("td")[4];
                    if (td || td1 || td2 || td3) {
                        txtValue = td.textContent + '' + td1.textContent + '' + td2.textContent + '' + td3.textContent || td.innerText + td1.innerText + td2.innerText + td3.innerText; 
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            } else {
               $.bac_abstract.validate_table($('#available-committee-table'));
            }
        });

        /*
        | ---------------------------------
        | # when committee modal button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-committee-modal button', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.bac_abstract.fetchID();

            if (_committees.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.bac_abstractForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.ajax({
                            type: 'POST',
                            url: _baseUrl + 'general-services/bac/abstract-of-canvass/add-committees/' + _id,
                            data: {'committees' : _committees},
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
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
        | # when committee modal checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#add-committee-modal input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.is(':checked')) {
                _modal.find('tr input[type="checkbox"]').prop('checked', true);
                $.each(_modal.find('input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _committees.length; i++) {
                            if (_committees[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _committees.push(checkbox.val());
                        }
                    } 
                });
                console.log(_committees);
            } else {
                _modal.find('tr input[type="checkbox"]').prop('checked', false);
                $.each(_modal.find("input[type='checkbox'][value!='all']"), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _committees.length; i++) {
                        if (_committees[i] == checkbox.val()) {
                            _committees.splice(i, 1);
                        }
                    }
                });
                console.log(_committees);
            }
        });
        /*
        | ---------------------------------
        | # when committee modal checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#add-committee-modal input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _committees.push(_self.val());
            } else {
                for (var i = 0; i < _committees.length; i++) {
                    if (_committees[i] == _self.val()) {
                        _committees.splice(i, 1);
                    }
                }
            }
            console.log(_committees);
        });

        /*
        | ---------------------------------
        | # when award button is clicked 
        | ---------------------------------
        */
        this.$body.on('click', '#rfq-modal .award-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _modal = _self.closest('.modal');
            var _code  = _modal.find('label[for="control_no"].text-danger').text();
            var _id    = $.bac_abstract.fetchID();
            var _url   = _baseUrl + 'general-services/bac/abstract-of-canvass/award/' + _id + '/' + _modal.find('input[name="is_selected"]:checked').val();
            var d1     = $.bac_abstractForm.fetch_status(_id);
            var _committeeTable = $('#committeeTable');
            
            if (!($('input[name="is_selected"]').is(':checked'))) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select a supplier to award first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null; 
            } else if (_committeeTable.find('tbody td.dataTables_empty').length > 0) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please add some committee member first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;  
            } else {
                $.when( d1 ).done(function ( v1) 
                {  
                    if (v1 == 'draft') {   
                        _self.prop('disabled', true).html('wait.....');   
                        Swal.fire({
                            html: "Are you sure? <br/>The Abstract with Control No. ("+ _code +")<br/>will be updated.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, update it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn bg-print", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _url,
                                    success: function(response) {
                                        console.log(response);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                e.isConfirmed && ((t.disabled = !1));
                                                _modal.find('input[name="is_selected"]').prop('disabled', true);
                                                _modal.find('button.send-btn').removeClass('hidden');
                                                _self.prop('disabled', false).html('Award').addClass('hidden');
                                            }
                                        );
                                    }
                                })
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Award')
                        });  
                    } else {
                        console.log('sorry cannot be processed');
                    }
                });
            }
            
        });

        /*
        | ---------------------------------
        | # when modal print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfq-modal .print-btn', function (e) {
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _rfqNo  = _modal.find('label.text-danger[for="control_no"]').text();
            var _url    = _baseUrl +'digital-sign?url='+'general-services/bac/abstract-of-canvass/print/' + _rfqNo;
            window.open(_url, '_blank');
        }); 

    }

    //init bac_abstractForm
    $.bac_abstractForm = new bac_abstractForm, $.bac_abstractForm.Constructor = bac_abstractForm

}(window.jQuery),

//initializing bac_abstractForm
function($) {
    "use strict";
    $.bac_abstractForm.init();
}(window.jQuery);
