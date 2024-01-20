!function($) {
    "use strict";

    var bac_rfqForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _suppliers = []; var _purchases = [];

    bac_rfqForm.prototype.validate = function($form, $required)
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

    bac_rfqForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        // console.log(_baseUrl + 'general-services/bac/request-for-quotations/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/bac/request-for-quotations/fetch-status/' + _id,
            success: function(response) {
                // console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    bac_rfqForm.prototype.view_available_suppliers = function(_id, _button)
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
                    var d1 = $.bac_rfq.validate_table(_table);
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

    bac_rfqForm.prototype.view_available_purchase_requests = function(_id, _button, _fund)
    {   
        var _table = $('#available-purchase-request-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/view-available-purchase-requests/' + _id + '?fund_code=' + _fund;
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
                    var d1 = $.bac_rfq.validate_table(_table);
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

    bac_rfqForm.prototype.update = function(_id, _form)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/update/' + _id + '?fund_code_id=' + _form.find('select[name="fund_code_id"]').val() + '&project_name=' + encodeURIComponent(_form.find('textarea[name="project_name"]').val());
        console.log(_url);
        // '&deadline_date=' + _form.find('input[name="deadline_date"]').val() + '&quotation_date=' + _form.find('input[name="quotation_date"]').val() + '&delivery_period=' + _form.find('input[name="delivery_period"]').val() + '&warranty_exp_id=' + _form.find('select[name="warranty_exp_id"]').val() + '&warranty_non_exp_id=' + _form.find('select[name="warranty_non_exp_id"]').val() + '&price_validaty_id=' + _form.find('select[name="price_validaty_id"]').val();
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                // console.log(response);
                if (_id <= 0) {
                    $.bac_rfq.updateID(response.data.id);
                    _modal.find('label[for="control_no"].text-danger').text(response.data.control_no);
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_rfqForm.prototype.update_row = function(_id, _rows)
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
                _table.find('tfoot th.text-danger').text($.bac_rfq.price_separator(parseFloat(response.total).toFixed(2)));
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_rfqForm.prototype.update_canvass = function(_id, _supplier, _form)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/bac/request-for-quotations/update-canvass/' + _id + '/' + _supplier;
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

    bac_rfqForm.prototype.submit_canvass = function(_id, _supplier, _self)
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

    bac_rfqForm.prototype.validate_supplier = function(_id, _required)
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

    bac_rfqForm.prototype.init = function()
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
        | # when add supplier modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-supplier-modal', function (e) {
            $.bac_rfq.load_supplier_contents();
            _suppliers = [];
        });
        /*
        | ---------------------------------
        | # when add pr modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#add-purchase-request-modal', function (e) {
            $.bac_rfq.load_pr_contents();
            $.bac_rfq.load_item_contents();
            _purchases = [];
        });

        /*
        | ---------------------------------
        | # when add supplier button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-supplier', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id = $.bac_rfq.fetchID();

            _self.prop('disabled', true).html('WAIT.....');
            var d1 = $.bac_rfqForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    setTimeout(function () {
                        $.bac_rfqForm.view_available_suppliers(_id, _self);
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
               $.bac_rfq.validate_table($('#available-supplier-table'));
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
            var _id = $.bac_rfq.fetchID();

            if (_suppliers.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.bac_rfqForm.fetch_status(_id);
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
                                    if (_id <= 0) {
                                        $.bac_rfq.updateID(response.data.id);
                                        $('#rfq-modal').find('label[for="control_no"].text-danger').text(response.data.control_no);
                                    }
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
            var _fund = $('form[name="rfqForm"] select[name="fund_code_id"]');
            var _id = $.bac_rfq.fetchID();

            if (_fund.val() > 0) {
                _self.prop('disabled', true).html('WAIT.....');
                var d1 = $.bac_rfqForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        setTimeout(function () {
                            $.bac_rfqForm.view_available_purchase_requests(_id, _self, _fund.val());
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
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select a fund code first.",
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
               $.bac_rfq.validate_table($('#available-supplier-table'));
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
            var _id = $.bac_rfq.fetchID();

            if (_purchases.length > 0) {
                _self.prop('disabled', true).html('Wait.....');
                var d1 = $.bac_rfqForm.fetch_status(_id);
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
                                    if (_id <= 0) {
                                        $.bac_rfq.updateID(response.data.id);
                                        $('#rfq-modal').find('label[for="control_no"].text-danger').text(response.data.control_no);
                                    }
                                    setTimeout(function () {
                                        _self.html('Save & Close').prop('disabled', false);
                                        $('#rfq-modal').find('label[for="total_budget"].text-danger').text('₱' + $.bac_rfq.price_separator(parseFloat(response.total).toFixed(2)));
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
        | # when rfqForm on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="rfqForm"] textarea', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.bac_rfq.fetchID();
            var _proj = $.bac_rfq.fetchProjectName();
            if (_self.val() != _proj) {
                var d1    = $.bac_rfqForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {  
                    if (v1 == 'draft') {
                        $.bac_rfq.updateProjectName(_self.val());
                        $.bac_rfqForm.update(_id, _form);
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
        this.$body.on('blur', 'form[name="rfqForm"] input:not([type="search"])', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.bac_rfq.fetchID();
            var d1    = $.bac_rfqForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.bac_rfqForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when rfqForm on blur
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="rfqForm"] select', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.bac_rfq.fetchID();
            var d1    = $.bac_rfqForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.bac_rfqForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
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
            var _id   = $.bac_rfq.fetchID();
            var d1    = $.bac_rfqForm.fetch_status(_id);
            var d2    = $.bac_rfq.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    if (_self.val() > 0) {
                        _total.val(parseFloat(_qty) * parseFloat(_self.val()));
                    } else {
                        _total.val('');
                    }
                    $.bac_rfqForm.update_row(_id, _rows);
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
            var _id    = $.bac_rfq.fetchID();
            var d1     = $.bac_rfqForm.fetch_status(_id);
            var d2     = $.bac_rfq.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_rfqForm.update_row(_id, _rows);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when remarks onblur
        | ---------------------------------
        */
        this.$body.on('blur', '#available-canvass-table input[name="remarks"]', function (event) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _id    = $.bac_rfq.fetchID();
            var d1     = $.bac_rfqForm.fetch_status(_id);
            var d2     = $.bac_rfq.fetchSupplierStatus();
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_rfqForm.update_row(_id, _rows);
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
            var _prTable = $('#prTable');
            var _supplierTable = $('#supplierTable');
            var _id     = $.bac_rfq.fetchID();
            var d1      = $.bac_rfqForm.fetch_status(_id);
            var _error  = $.bac_rfqForm.validate(_form, 0);
            var _error2 = $.bac_rfqForm.validate_supplier(_id, 0);
            var _url    = _baseUrl + 'general-services/bac/request-for-quotations/send/for-rfq-approval/' + _id;
            var _toast  = $('#indexToast');
            
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
                    } else if (_prTable.find('tbody td.dataTables_empty').length > 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some PR first.",
                            icon: "warning",
                            type: "warning",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;  
                    } else if (_error2 != 0 || _supplierTable.find('tbody tr').length <= 2) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Make sure that you have added atleast 3 suppliers with completed status.",
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
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();       
                                                _modal.modal('hide');                                       
                                                $.bac_rfq.load_contents();
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
            var _id    = $.bac_rfq.fetchID();
            var d1     = $.bac_rfqForm.fetch_status(_id);
            var d2     = $.bac_rfq.fetchSupplierStatus();
            var d3     = $.bac_rfq.fetchSupplierID();
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            {  
                if (v1 == 'draft' && v2 == 'pending') {
                    $.bac_rfqForm.update_canvass(_id, v3, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when canvas post
        | ---------------------------------
        */
        this.$body.on('click', '#canvass-modal .submit-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _modal = _self.closest('.modal');
            var _total = parseFloat(_modal.find('tfoot th.text-danger').text());
            var _error = $.bac_rfqForm.validate(_form, 0);
            var _id    = $.bac_rfq.fetchID();
            var d1     = $.bac_rfqForm.fetch_status(_id);
            var d2     = $.bac_rfq.fetchSupplierStatus();
            var d3     = $.bac_rfq.fetchSupplierID();

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
            } else if (!(parseFloat(_total) > 0)) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add some quotation first.",
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
                            confirmButtonText: "Yes, post it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn btn-info", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _baseUrl + 'general-services/bac/request-for-quotations/submit-canvass/' + _id + '/' + v3,
                                    success: function(response) {
                                        console.log(response);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                e.isConfirmed && ((t.disabled = !1));                                                
                                                _self.addClass('hidden');
                                                _modal.find('button.save-btn').addClass('hidden');
                                                _modal.find('input, select').prop('disabled', true);
                                                _modal.find('button.print-btn').removeClass('hidden');
                                                // _modal.modal('hide');
                                            }
                                        );
                                    },
                                    complete: function() {
                                        window.onkeydown = null;
                                        window.onfocus = null;
                                    }
                                })
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Post') 
                        });
                    } else {
                        console.log('sorry cannot be processed');
                    }
                });
            }
        });

        /*
        | ---------------------------------
        | # when canvass save
        | ---------------------------------
        */
        this.$body.on('click', '#canvass-modal .save-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _modal = _self.closest('.modal');
            var _total = parseFloat(_modal.find('tfoot th.text-danger').text());
            var _error = $.bac_rfqForm.validate(_form, 0);
            var _id    = $.bac_rfq.fetchID();
            var d1     = $.bac_rfqForm.fetch_status(_id);
            var d2     = $.bac_rfq.fetchSupplierStatus();
            var d3     = $.bac_rfq.fetchSupplierID();

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
            } else if (!(parseFloat(_total) > 0)) {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add some quotation first.",
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
                            html: "Are you sure? <br/>the canvass will be saved as draft.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, save it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn btn-secondary", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: 'PUT',
                                    url: _baseUrl + 'general-services/bac/request-for-quotations/save-canvass/' + _id + '/' + v3,
                                    success: function(response) {
                                        console.log(response);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                e.isConfirmed && ((t.disabled = !1));                                                
                                                // _self.addClass('hidden');
                                                // _modal.find('input, select').prop('disabled', true);
                                                // _modal.find('button.print-btn').removeClass('hidden');
                                                // _modal.modal('hide');
                                            }
                                        );
                                    },
                                    complete: function() {
                                        window.onkeydown = null;
                                        window.onfocus = null;
                                    }
                                })
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Draft') 
                        });
                    } else {
                        console.log('sorry cannot be processed');
                    }
                });
            }
        });
    }

    //init bac_rfqForm
    $.bac_rfqForm = new bac_rfqForm, $.bac_rfqForm.Constructor = bac_rfqForm

}(window.jQuery),

//initializing bac_rfqForm
function($) {
    "use strict";
    $.bac_rfqForm.init();
}(window.jQuery);
