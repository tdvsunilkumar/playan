!function($) {
    "use strict";

    var gso_issuanceForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _issuances = [];

    gso_issuanceForm.prototype.validate = function($form, $required)
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

    gso_issuanceForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'general-services/issuance/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/issuance/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    gso_issuanceForm.prototype.validate_issuance = function (_id)
    {   
        var _validated = 0;
        if (_id == 0) {
            return _validated = 1;
        }
        console.log(_baseUrl + 'general-services/issuance/validate-issuance/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/issuance/validate-issuance/' + _id,
            success: function(response) {
                console.log(response);
                _validated = response.validated;
            },
            async: false
        });
        return _validated;
    },

    gso_issuanceForm.prototype.update = function(_id, _form, _field)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/issuance/update/' + _id;
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
                if (_id <= 0) {
                    $.gso_issuance.updateID(response.data.id);
                    _modal.find('input[name="control_no"]').val(response.data.control_no);
                }
                if (_field == 'requested_by') {
                    _modal.find('input[name="department"]').val(response.data.requestor.department.code + ' - ' + response.data.requestor.department.name);
                    _modal.find('input[name="designation"]').val(response.data.requestor.department.designation.description);
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    gso_issuanceForm.prototype.view_available_items = function(_id, _button)
    {   
        var _table = $('#available-items-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _inventory = $('input[type="checkbox"][name="inventory"]:checked');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/issuance/view-available-items/' + _id + '?inventory=' + _inventory.length + '&po_no=' + $('#purchase_order_id').val();
        console.log(_url);
        _issuances = [];
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        if (row.available > 0) {
                            _rows += '<tr data-row-amt="' + row.amt + '" data-row-po="' + row.po + '" data-row-id="' + row.id + '" data-row-uom="' + row.uom.toLowerCase() + '" data-row-uom-id="' + row.uom_id + '" data-row-available="' + row.available + '">';
                            _rows += '<td class="text-center">' + (row.no ? row.no : '') + '</td>';
                            _rows += '<td>' + (row.ref ? row.ref : '') + '</td>';
                            _rows += '<td>' + (row.code ? row.code : '') + '</td>';
                            _rows += '<td class="sliced">' + (row.description ? row.description : '') + '</td>';
                            _rows += '<td class="text-center">' + (row.uom ? row.uom : '') + '</td>';
                            _rows += '<td class="text-center">' + (row.available ? row.available : 0) + '</td>';
                            _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="quantity[]" type="text" value="' + (row.withdrawn ? row.withdrawn : '') + '"></td>';
                            _rows += '</tr>';
                            
                            if (row.withdrawn) {
                                var found = false;
                                for (var i = 0; i < _issuances.length; i++) {
                                    if (_issuances[i].id == row.id && _issuances[i].po == row.po) {
                                        found == true;
                                        _issuances[i].qty = row.withdrawn;
                                        _issuances[i].amt = row.amt;
                                        return;
                                    }
                                } 
                                if (found == false) {
                                    _issuances.push({ id: row.id, po: String(row.po), qty: row.withdrawn, amt: row.amt, uom: row.uom_id });
                                } 
                            }
                        }
                    });
                    console.log(_issuances);
                    _button.prop('disabled', false).html('Add Item');
                    _table.find('tbody').append(_rows);
                    var d1 = $.gso_issuance.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        $.gso_issuance.shorten();
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('Add Item');
            }
        });
    },

    gso_issuanceForm.prototype.init = function()
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
        | # when select on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="issuanceForm"] select:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_issuance.fetchID();
            var d1    = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_issuanceForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when input text on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="issuanceForm"] input[type="text"]:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_issuance.fetchID();
            var d1    = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_issuanceForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when input date on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="issuanceForm"] input[type="date"]:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_issuance.fetchID();
            var d1    = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_issuanceForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('blur', 'form[name="issuanceForm"] textarea:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_issuance.fetchID();
            var d1    = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_issuanceForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('click', 'form[name="issuanceForm"] button.send-btn', function (e) {
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _form      = _self.closest('form');
            var _code      = _form.find('input[name="control_no"]').val();
            var _itemTable = $('#itemTable');
            var _id        = $.gso_issuance.fetchID();
            var d1         = $.gso_issuanceForm.fetch_status(_id);
            var d2         = $.gso_issuanceForm.validate_issuance(_id);
            var _error     = $.gso_issuanceForm.validate(_form, 0);
            var _url       = _baseUrl + 'general-services/issuance/send/for-approval/' + _id;
            var _toast     = $('#indexToast');
            
            $.when( d1, d2 ).done(function ( v1, v2 ) 
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
                    } else if (v2 > 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>(PAR & ICS) multiplier should not be greater than one.",
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
                            html: "Are you sure? <br/>the request with <strong>Control No<br/>(" + _code + ")</strong> will be sent.",
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
                                                _modal.find('button.print-btn').removeClass('hidden');
                                                _modal.modal('hide');                                       
                                                $.gso_issuance.load_contents();
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
        | # when add item is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-item-btn', function (e) {
            var _self   = $(this);
            var _form   = _self.closest('form');
            var _id     = $.gso_issuance.fetchID();
            var _length    = _form.find("select[name='purchase_order_id[]'] option:selected").length;
            var _inventory = _form.find('input[type="checkbox"][name="inventory"]:checked').length;
            var d1      = $.gso_issuanceForm.fetch_status(_id);

            _self.prop('disabled', true).html('Wait.....');
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    if (parseFloat(_inventory) > 0) {
                        setTimeout(function () {
                            $.gso_issuanceForm.view_available_items(_id, _self);
                        }, 500 + 300 * (Math.random() * 5));  
                    } else {
                        if (_length > 0) {
                            setTimeout(function () {
                                $.gso_issuanceForm.view_available_items(_id, _self);
                            }, 500 + 300 * (Math.random() * 5));  
                        } else {
                            _self.prop('disabled', false).html('Add Item');
                            Swal.fire({
                                title: "Oops...",
                                html: "Unable to add item!<br/>Please select a PO No. / PR No. first.",
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
                } else {
                    _self.prop('disabled', false).html('Add Item');
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
        | # when unit cost onblur
        | ---------------------------------
        */
        this.$body.on('keyup', '#available-items-table input[name="quantity[]"]', function (event) {
            var _self      = $(this);
            var _rows      = _self.closest('tr');
            var _availQty  = _rows.attr('data-row-available');
            var _id        = $.gso_issuance.fetchID();
            var d1         = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    if (parseFloat(_self.val()) > parseFloat(_availQty)) {
                        _self.val(_availQty);
                    }
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });
        this.$body.on('blur', '#available-items-table input[name="quantity[]"]', function (event) {
            var _self      = $(this);
            var _rows      = _self.closest('tr');
            var _availQty  = _rows.attr('data-row-available');
            var _rowID     = _rows.attr('data-row-id');
            var _rowPO     = _rows.attr('data-row-po');
            var _rowAmt    = _rows.attr('data-row-amt');
            var _rowUom    = _rows.attr('data-row-uom-id');
            var _id        = $.gso_issuance.fetchID();
            var d1         = $.gso_issuanceForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {
                    if (parseFloat(_self.val()) > parseFloat(_availQty)) {
                        _self.val(_availQty);
                    }
                    var found = false;
                    for (var i = 0; i < _issuances.length; i++) {
                        if (_issuances[i].id == _rowID && _issuances[i].po == _rowPO) {
                            found == true;
                            _issuances[i].qty = _self.val();
                            _issuances[i].amt = _rowAmt;
                            return;
                        }
                    } 
                    if (found == false) {
                        _issuances.push({ id: _rowID, po: _rowPO, qty: _self.val(), amt: _rowAmt, uom: _rowUom });
                    } 
                } else {
                    console.log('sorry cannot be processed');
                }
            });
            console.log(_issuances);
        });

        /*
        | ---------------------------------
        | # when post buttoon is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-item-modal button.post-btn', function (e) {
            e.preventDefault();
            var _self      = $(this);
            var _modal     = _self.closest('.modal');
            var _id        = $.gso_issuance.fetchID();
            var _inventory = $('#issuance-modal input[type="checkbox"][name="inventory"]:checked');
            var _url       = _baseUrl + 'general-services/issuance/post/' + _id + '?inventory=' + _inventory.length;
            var d1         = $.gso_issuanceForm.fetch_status(_id);

            console.log(_url);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    if (_issuances.length <= 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Something went wrong!<br/>Please add some quantity first.",
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
                            html: "Are you sure? <br/>the item requested will be posted.",
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
                                    type: 'POST',
                                    url: _url,
                                    data: {'issuances' : _issuances},
                                    success: function(response) {
                                        console.log(response);
                                        if (response.type == 'success') {
                                            _issuances = [];
                                            _self.prop('disabled', true).html('wait.....');
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Post Now')
                                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                                    function (e) {
                                                        $('#issuance-modal').find('tfoot th.text-danger').text('₱' + $.gso_issuance.price_separator(parseFloat(response.total).toFixed(2)));
                                                        $.gso_issuance.load_item_contents();
                                                        _modal.modal('hide');
                                                    }
                                                );
                                            }, 500 + 300 * (Math.random() * 5));
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Post Now')
                        });
                    }
                } else {
                    _self.prop('disabled', false).html('Post Now');
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
        | # when print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#issuance-modal .print-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _controlNo = _modal.find('input[name="control_no"]').val();
            var _category = '?type='; var _cats = [];
            _modal.find('input[type="checkbox"][name="slip[]"]:checked').each(function () {
                _cats.push($(this).val());
            });
            _category += _cats.join(',');
            if (_cats.length > 0) {
                var _url = _baseUrl +'digital-sign?url='+'general-services/issuance/print/' + _controlNo + '' + _category;
                window.open(_url, _controlNo);
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please select an issuance slip first.",
                    icon: "error",
                    type: "danger",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;    
            }
        })

        /*
        | ---------------------------------
        | # when keywords on search
        | ---------------------------------
        */
        this.$body.on('keyup', '#keyword1', function (event) {
            var input, filter, table, tr, td, td1, td2, td3, i, txtValue;
            input = document.getElementById("keyword1");
            filter = input.value.toUpperCase();
            table = document.getElementById("available-items-table");
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
               $.gso_issuance.validate_table($('#available-items-table'));
            }
        });

        /*
        | ---------------------------------
        | # when clear keywords on search
        | ---------------------------------
        */
        $('input[type=search][id="keyword1"]').on('search', function () {
            var _self = $(this);
            if (_self.val() == '') {
               $.gso_issuance.validate_table($('#available-items-table'));
            }
        });
    }

    //init gso_issuanceForm
    $.gso_issuanceForm = new gso_issuanceForm, $.gso_issuanceForm.Constructor = gso_issuanceForm

}(window.jQuery),

//initializing gso_issuanceForm
function($) {
    "use strict";
    $.gso_issuanceForm.init();
}(window.jQuery);

