!function($) {
    "use strict";

    var gso_inspectionForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _posted = []; 

    gso_inspectionForm.prototype.validate = function($form, $required)
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

    gso_inspectionForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'general-services/inspection-and-acceptance/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/inspection-and-acceptance/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    gso_inspectionForm.prototype.update = function(_id, _form, _field)
    {   
        var _modal = _form.closest('.modal');
        var _url = _baseUrl + 'general-services/purchase-orders/update/' + _id;
        console.log(_id);
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
                if (_id <= 0) {
                    $.gso_inspection.updateID(response.data.id);
                    _modal.find('input[name="purchase_order_no"]').val(response.data.purchase_order_no);
                }
                if (_field == 'rfq_id') {
                    _modal.find('input[name="supplier"]').val(response.data.supplier.business_name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    }) + ' - ' + response.data.supplier.branch_name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    }));
                    _modal.find('input[name="project_name"]').val(response.data.rfq.project_name);
                    _modal.find('input[name="address"]').val(response.data.supplier.address);
                    _modal.find('tfoot th:last-child').text('₱' + $.gso_inspection.price_separator(parseFloat(response.data.total_amount).toFixed(2)));
                    $.gso_inspection.load_pr_contents();
                    $.gso_inspection.load_item_contents();
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    gso_inspectionForm.prototype.view_available_posting = function(_id, _button)
    {   
        var _table = $('#available-posting-table'); _table.find('tbody').empty();
        var _modal = _table.closest('.modal');
        var _rows  = '';
        var _url = _baseUrl + 'general-services/inspection-and-acceptance/view-available-posting/' + _id;
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-id="' + row.id + '" data-row-uom="' + row.uom.toLowerCase() + '" data-row-uom-id="' + row.uom_id + '" data-row-available="' + row.available + '">';
                        _rows += '<td class="text-center">' + (row.no ? row.no : '') + '</td>';
                        _rows += '<td>' + (row.code ? row.code : '') + '</td>';
                        _rows += '<td>' + (row.description ? row.description : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.uom ? row.uom : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.available ? row.available : '') + '</td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="quantity[]" type="text" value=""></td>';
                        _rows += '</tr>';
                    });
                    _button.prop('disabled', false).html('ADD POSTING');
                    _table.find('tbody').append(_rows);
                    var d1 = $.gso_inspection.validate_table(_table);
                    $.when( d1 ).done(function ( v1 ) 
                    { 
                        _modal.modal('show'); 
                    });
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
                _button.prop('disabled', false).html('ADD POSTING');
            }
        });
    },

    gso_inspectionForm.prototype.init = function()
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
        | # when select on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="purchaseOrderForm"] select:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_inspection.fetchID();
            var d1    = $.gso_inspectionForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_inspectionForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('blur', 'form[name="purchaseOrderForm"] input[type="text"]:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_inspection.fetchID();
            var d1    = $.gso_inspectionForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_inspectionForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('blur', 'form[name="purchaseOrderForm"] input[type="date"]:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_inspection.fetchID();
            var d1    = $.gso_inspectionForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_inspectionForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('blur', 'form[name="purchaseOrderForm"] textarea:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.gso_inspection.fetchID();
            var d1    = $.gso_inspectionForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_inspectionForm.update(_id, _form, _self.attr('name'));
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when add pr button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-posting', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _id = $.gso_inspection.fetchID();

            _self.prop('disabled', true).html('WAIT.....');
            var d1 = $.gso_inspectionForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'pending') {
                    setTimeout(function () {
                        $.gso_inspectionForm.view_available_posting(_id, _self);
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
        | # when posting button si clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-posting-modal .post-btn', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form');
            var _method = 'POST';
            var _error  = $.gso_inspectionForm.validate(_form, 0);
            var _id     = $.gso_inspection.fetchID();
            var _inventory = (_form.find('input[name="inventory_posting"]:checked').length > 0) ? 1 : 0;
            var _action = _form.attr('action') + '/posting/' + _id + '?inventory=' + _inventory + '&inspected_by=' + _form.find('select[name="inspected_by"]').val() + '&inspected_date=' + _form.find('input[name="inspected_date"]').val() + '&received_by=' + _form.find('select[name="received_by"]').val() + '&received_date=' + _form.find('input[name="received_date"]').val() + '&remarks=' + encodeURIComponent(_form.find('textarea[name="remarks"]').val()) + '&reference_no=' + encodeURIComponent(_form.find('input[name="reference_no"]').val()) + '&reference_date=' + _form.find('input[name="reference_date"]').val();

            var d1 = $.gso_inspectionForm.fetch_status(_id);
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
                    } else if (_posted.length <= 0) {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some posting quantity first.",
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
                            data: { 'posted' : _posted },
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    _posted = []; 
                                    setTimeout(function () {
                                        _self.html('Post Now').prop('disabled', false);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                $.gso_inspection.load_item_contents();
                                                $.gso_inspection.load_posting_contents();
                                                _modal.modal('hide');
                                            }
                                        );
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Post Now').prop('disabled', false);
                                    _form.find('input[name="' + response.column + '"]').addClass('is-invalid').next().text(response.label);
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
        | # when input quantity[] onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#add-posting-modal input[name="quantity[]"]', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('tr');
            var _qty    = _parent.attr('data-row-available');

            if (parseFloat(_self.val()) >= parseFloat(_qty)) {
                _self.val(_qty);
            }
        });
        /*
        | ---------------------------------
        | # when input quantity[] blur
        | ---------------------------------
        */
        this.$body.on('blur', '#add-posting-modal input[name="quantity[]"]', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('tr');
            var _qty    = _parent.attr('data-row-available');
            var _uom    = _parent.attr('data-row-uom');
            var _uomID  = _parent.attr('data-row-uom-id');
            var _id     = _parent.attr('data-row-id');
            var _quantity  = 0;

            if (_self.val() != '') {
                if (parseFloat(_self.val()) >= parseFloat(_qty)) {
                    _self.val(_qty);
                    _quantity = _qty;
                } else {
                    _quantity = _self.val();
                }
                var found = false;
                for (var i = 0; i < _posted.length; i++) {
                    if (_posted[i].id == _id) {
                        found == true;
                        _posted[i].qty = _quantity;
                        return;
                    }
                } 
                if (found == false) {
                    _posted.push({ id: _id, qty: _quantity, uom: _uom, uom_id: _uomID });
                } 
            }
            console.log(_posted);
        });
    }

    //init gso_inspectionForm
    $.gso_inspectionForm = new gso_inspectionForm, $.gso_inspectionForm.Constructor = gso_inspectionForm

}(window.jQuery),

//initializing gso_inspectionForm
function($) {
    "use strict";
    $.gso_inspectionForm.init();
}(window.jQuery);
