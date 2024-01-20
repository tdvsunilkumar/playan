!function($) {
    "use strict";

    var requisitionForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    requisitionForm.prototype.validate = function($form, $required)
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
                            if($(this).hasClass('selectpicker')) {
                                $(this).addClass('is-invalid');
                                $required++;    
                            } else {
                                $(this).addClass('is-invalid');
                                $required++;    
                            }                                      
                        }
                        $(this).closest('.form-group').find('.bootstrap-select').addClass('is-invalid');
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');
                    } 
                }
            }
        });

        return $required;
    },

    requisitionForm.prototype.reload_items = function(_item, _uom, _fund, _department, _division, _requestDate, _category)
    {   
        // if (_purchaseType > 0) {
            // _item.find('option').remove(); _uom.val('');
            // console.log(_baseUrl + 'general-services/departmental-requisitions/reload-items/' + _purchaseType);
            // $.ajax({
            //     type: "GET",
            //     url: _baseUrl + 'general-services/departmental-requisitions/reload-items/' + _purchaseType,
            //     success: function(response) {
            //         console.log(response.data);
            //         _item.append('<option value="">select an item</option>');  
            //         $.each(response.data, function(i, item) {
            //             _item.append('<option value="' + item.id + '">' + item.code + ' - ' + item.name + '</option>');  
            //         }); 
            //     },
            //     async: false
            // });

            // return true;
        // }

        _item.find('option').remove(); _uom.val('');
        console.log(_baseUrl + 'general-services/departmental-requisitions/reload-itemx/' + (_fund ? _fund : 0) + '/' + (_department ? _department : 0) + '/' + (_division ? _division : 0) + '/' + (_requestDate ? _requestDate : 0) + '/' + (_category ? _category : 0));
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/reload-itemx/' + (_fund ? _fund : 0) + '/' + (_department ? _department : 0) + '/' + (_division ? _division : 0) + '/' + (_requestDate ? _requestDate : 0) + '/' + (_category ? _category : 0),
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
    },

    requisitionForm.prototype.reload_uom = function(_uom, _item = 0)
    {   
        // if (_item > 0) {
            _uom.find('option').remove(); 
            console.log(_baseUrl + 'general-services/departmental-requisitions/reload-uom/' + _item);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'general-services/departmental-requisitions/reload-uom/' + _item,
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

    requisitionForm.prototype.reload_unit_cost = function(_unit_cost, _item = 0)
    {   
        console.log(_baseUrl + 'general-services/departmental-requisitions/reload-unit-cost/' + _item);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/reload-unit-cost/' + _item,
            success: function(response) {
                _unit_cost.val(response.unit_cost);
            },
            async: false
        });

        return true;
    },

    requisitionForm.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
    {   
        // if (_department > 0) {
            _employee.find('option').remove(); _division.find('option').remove(); _designation.val('');
            console.log(_baseUrl + 'general-services/departmental-requisitions/reload-divisions-employees/' + _department);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'general-services/departmental-requisitions/reload-divisions-employees/' + _department,
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

    requisitionForm.prototype.reload_designation = function(_designation, _employee = 0)
    {   
        // if (_employee > 0) {
            _designation.find('option').remove(); 
            console.log(_baseUrl + 'general-services/departmental-requisitions/reload-designation/' + _employee);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'general-services/departmental-requisitions/reload-designation/' + _employee,
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

    requisitionForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'general-services/departmental-requisitions/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    requisitionForm.prototype.validate_item_request = function(_lineId, _fund, _year, _division, _category, _item, _quantity)
    {
        var _validate = false;
        console.log(_baseUrl + 'general-services/departmental-requisitions/validate-item-request?line=' + _lineId + '&fund=' + _fund + '&year=' + _year + '&division=' + _division + '&category=' + _category + '&item=' + _item + '&quantity=' + _quantity);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/departmental-requisitions/validate-item-request?line=' + _lineId + '&fund=' + _fund + '&year=' + _year + '&division=' + _division + '&category=' + _category + '&item=' + _item + '&quantity=' + _quantity,
            success: function(response) {
                console.log(response);
                _validate = response.validate;
            },
            async: false
        });
        return _validate;
    },

    requisitionForm.prototype.init = function()
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
        | # when requested date on change
        | ---------------------------------
        */
        this.$body.on('change', 'input[name="requested_date"]', function (event) {
            var _self = $(this);
            var _form = $(this).closest('form');
            var _item = _form.find('select[name="item_id"]');
            var _uom = _form.find('select[name="uom_id"]');
            var _department = _form.find('select[name="department_id"]');
            var _division = _form.find('input[name="division_id"]');
            var _fund = _form.find('select[name="fund_code_id"]');
            var _category = _form.find('select[name="budget_category_id"]');
            $.requisitionForm.reload_items(_item, _uom, _fund.val(), _department.val(), _division.val(), _self.val(), _category.val());
        });

        /*
        | ---------------------------------
        | # when fund code on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="fund_code_id"]', function (event) {
            var _self = $(this);
            var _form = $(this).closest('form');
            var _item = _form.find('select[name="item_id"]');
            var _uom = _form.find('select[name="uom_id"]');
            var _department = _form.find('select[name="department_id"]');
            var _requestDate = _form.find('input[name="requested_date"]');
            var _division = _form.find('input[name="division_id"]');
            var _category = _form.find('select[name="budget_category_id"]');
            $.requisitionForm.reload_items(_item, _uom, _self.val(), _department.val(), _division.val(), _requestDate.val(), _category.val());
        });

        /*
        | ---------------------------------
        | # when division type on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="division_id"]', function (event) {
            var _self = $(this);
            var _form = $(this).closest('form');
            var _item = _form.find('select[name="item_id"]');
            var _uom = _form.find('select[name="uom_id"]');
            var _department = _form.find('select[name="department_id"]');
            var _requestDate = _form.find('input[name="requested_date"]');
            var _fund = _form.find('select[name="fund_code_id"]');
            var _category = _form.find('select[name="budget_category_id"]');
            $.requisitionForm.reload_items(_item, _uom, _fund.val(), _department.val(), _self.val(), _requestDate.val(), _category.val());
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
            $.requisitionForm.reload_uom($('select[name="uom_id"]'), _self.val());
            $.requisitionForm.reload_unit_cost($('input[name="request_unit_price"]'), _self.val());
        });

        /*
        | ---------------------------------
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="department_id"]', function (event) {
            var _self = $(this);
            var _form = $(this).closest('form');
            var _item = _form.find('select[name="item_id"]');
            var _uom = _form.find('select[name="uom_id"]');
            var _division = _form.find('select[name="division_id"]');
            var _requestDate = _form.find('input[name="requested_date"]');
            var _employee = _form.find('select[name="employee_id"]');
            var _designation = _form.find('select[name="designation_id"]');
            var _fund = _form.find('select[name="fund_code_id"]');
            var _category = _form.find('select[name="budget_category_id"]');

            $.requisitionForm.reload_divisions_employees(_division, _employee, _designation, _self.val());
            $.requisitionForm.reload_items(_item, _uom, _fund.val(), _self.val(),  _division.val(), _requestDate.val(), _category.val());
        });
        /*
        | ---------------------------------
        | # when department on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="budget_category_id"]', function (event) {
            var _self = $(this);
            var _form = $(this).closest('form');
            var _item = _form.find('select[name="item_id"]');
            var _uom = _form.find('select[name="uom_id"]');
            var _division = _form.find('select[name="division_id"]');
            var _requestDate = _form.find('input[name="requested_date"]');
            var _fund = _form.find('select[name="fund_code_id"]');
            var _department = _form.find('select[name="department_id"]');
            $.requisitionForm.reload_items(_item, _uom, _fund.val(), _department.val(),  _division.val(), _requestDate.val(), _self.val());
        });

        /*
        | ---------------------------------
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="employee_id"]', function (event) {
            var _self = $(this);
            $.requisitionForm.reload_designation($('select[name="designation_id"]'), _self.val());
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
            var _form   = $('form[name="requisitionForm"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _lineId = $.requisition.fetchLineID();
            var _method = (_lineId > 0) ? 'PUT' : (_id > 0) ? 'PUT' : 'POST';
            var _action = (_lineId > 0) ? _form.attr('action') + '/update-line/' + _lineId + '?uom_id=' + _form.find('#uom_id').val() : 
                (_id > 0) ? _form.attr('action') + '/update/' + _id + '?budget_category_id=' + _form.find('#budget_category_id').val() +'&department_id=' + _form.find('#department_id').val() + '&division_id=' + _form.find('#division_id').val() + '&employee_id=' + _form.find('#employee_id').val() + '&fund_code_id=' + _form.find('[name="fund_code_id"]').val() + '&request_type_id=' + _form.find('#request_type_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val() + '&remarks=' + encodeURIComponent(_form.find('#remarks').val()) : 
                _form.attr('action') + '/store?budget_category_id=' + _form.find('#budget_category_id').val() +'&department_id=' + _form.find('#department_id').val() + '&division_id=' + _form.find('#division_id').val() + '&employee_id=' + _form.find('#employee_id').val() + '&fund_code_id=' + _form.find('[name="fund_code_id"]').val() + '&request_type_id=' + _form.find('#request_type_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val() + '&remarks=' + encodeURIComponent(_form.find('#remarks').val());
            var _error  = $.requisitionForm.validate(_form, 0);

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
                var d1 = $.requisitionForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft') {
                        var _itemValidation  = $.requisitionForm.validate_item_request(
                            _lineId,
                            _form.find('[name="fund_code_id"]').val(), 
                            _form.find('#requested_date').val(), 
                            _form.find('#division_id').val(),
                            _form.find('#budget_category_id').val(),
                            _form.find('#item_id').val(),
                            _form.find('#quantity_requested').val()
                        );
                        if (_itemValidation == true) { 
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
                                            _form.find('select[name="department_id"]').prop('disabled', true);
                                            _form.find('select[name="purchase_type_id"]').prop('disabled', true);
                                            _form.find('select[name="budget_category_id"]').prop('disabled', true);
                                            _form.find('select[name="item_id"]').val('').trigger('change.select3'); 
                                            _form.find('select[name="uom_id"]').val('').trigger('change.select3'); 
                                            _form.find('input[name="request_unit_price"]').val('');
                                            _form.find('input[name="quantity_requested"]').val('');
                                            _form.find('textarea[name="item_remarks"]').val('');
                                            var d1 = $.requisition.load_line_contents();
                                            $.when( d1 ).done(function ( v1 ) {
                                                _modal.find('table th.fs-5.text-end').text('₱' + $.requisition.price_separator(parseFloat(response.totalAmt).toFixed(2)) );
                                            });
                                        }, 500 + 300 * (Math.random() * 5));
                                        setTimeout(function () {
                                            _self.html('Add Item').prop('disabled', false);
                                            _toast.hide();
                                        }, 3000);
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
                            if (_lineId > 0) {
                                _self.html('Update Item').prop('disabled', false);
                            } else {
                                _self.html('Add Item').prop('disabled', false);
                            }
                            Swal.fire({
                                title: "Oops...",
                                html: "Unable to proceed!<br/>The quantity requested is beyond the quantity left.",
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
            var _self  = $(this);
            var _modal = _self.closest('.modal');
            var _code  = _modal.find('form[name="requisitionForm"] input[name="control_no"]').val();
            var _total = _modal.find('table th.fs-5.text-end').text();
            var _toast = _modal.find('#modalToast');
            var _id    = $.requisition.fetchID();            
            var _url   = _baseUrl + 'general-services/departmental-requisitions/send/for-approval/' + _id;
            
            if (_id > 0) {
                var d1 = $.requisitionForm.fetch_status(_id);
                $.when( d1 ).done(function ( v1 ) 
                {   
                    if (v1 == 'draft' && _total != '₱0.00') {
                        _self.prop('disabled', true).html('wait.....');
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
                                            _modal.find('.store-btn').addClass('hidden');
                                            setTimeout(function () {
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();
                                                $.requisition.load_line_contents();
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                _toast.hide();
                                                $.requisition.notify(_id);
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
                                : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Send Request') : ''
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

    //init requisitionForm
    $.requisitionForm = new requisitionForm, $.requisitionForm.Constructor = requisitionForm

}(window.jQuery),

//initializing requisitionForm
function($) {
    "use strict";
    $.requisitionForm.init();
}(window.jQuery);