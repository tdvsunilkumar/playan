!function($) {
    "use strict";

    var purchase_requestForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    purchase_requestForm.prototype.validate = function($form, $required)
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

    purchase_requestForm.prototype.reload_items = function(_item, _uom, _purchaseType = 0)
    {   
        // if (_purchaseType > 0) {
            _item.find('option').remove(); _uom.val('');
            console.log(_baseUrl + 'general-services/departmental-requisitions/reload-items/' + _purchaseType);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'general-services/departmental-requisitions/reload-items/' + _purchaseType,
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

    purchase_requestForm.prototype.reload_uom = function(_uom, _item = 0)
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

    purchase_requestForm.prototype.reload_divisions_employees = function(_division, _employee, _designation, _department = 0)
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

    purchase_requestForm.prototype.reload_designation = function(_designation, _employee = 0)
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

    purchase_requestForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        // console.log(_baseUrl + 'general-services/purchase-requests/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-requests/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    purchase_requestForm.prototype.fetch_pr_status_via_alob = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        // console.log(_baseUrl + 'general-services/purchase-requests/fetch-pr-status-via-alob/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-requests/fetch-pr-status-via-alob/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    purchase_requestForm.prototype.generate = function (_id)
    {   
        var _data = [];
        // console.log(_baseUrl + 'general-services/purchase-requests/generate/' + _id);
        $.ajax({
            type: "POST",
            url: _baseUrl + 'general-services/purchase-requests/generate/' + _id,
            success: function(response) {
                console.log(response);
                _data = response.data;
            },
            async: false
        });
        return _data;
    },

    purchase_requestForm.prototype.update = function (_id, _form)
    {
        var _url = _baseUrl + 'general-services/purchase-requests/update-pr-via-alob/' + _id + '?remarks=' + encodeURIComponent(_form.find('textarea[name="pr_remarks"]').val());
        // console.log(_url);
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

    purchase_requestForm.prototype.update_item_line = function (_id, _data, _column)
    {
        var _url = _baseUrl + 'general-services/purchase-requests/update-item-line/' + _id + '?column=' + _column + '&data=' + _data;
        // console.log(_url);
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

    purchase_requestForm.prototype.validate_pr = function (_id)
    {   
        var _error = 0;
        console.log(_baseUrl + 'general-services/purchase-requests/validate-pr/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-requests/validate-pr/' + _id,
            success: function(response) {
                console.log(response);
                _error = response.status;
            },
            async: false
        });
        return _error;
    },

    purchase_requestForm.prototype.init = function()
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
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.generate-btn', function (e) {
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _id    = $.purchase_request.fetchAlobID();   

            _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
            var d1 = $.purchase_requestForm.generate(_id);
            var d2 = $.purchase_request.load_line_contents(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {             
                $.each(v1, function (k, v) {
                    _form.find('input[type="text"][name=' + k + ']').val(v);
                    _form.find('textarea[name=' + k + ']').val(v);
                    if (k == 'remarks') {
                        _form.find('textarea[name="pr_remarks"]').val(v);
                    }
                });    
                setTimeout(function () {     
                    _form.find('.image-layer').addClass('hidden');           
                    _form.find('.detail-layer').removeClass('hidden').fadeIn();
                }, 500 + 300 * (Math.random() * 5));
            });
        });

        /*
        | ---------------------------------
        | # when fund code on change
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="prForm"] textarea[name="pr_remarks"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.purchase_request.fetchAlobID();
            var d1    = $.purchase_requestForm.fetch_pr_status_via_alob(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {            
                    $.purchase_requestForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });           
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
            var _form   = $('form[name="purchase_requestForm"]');
            var _toast  = _modal.find('#modalToast');
            var _id     = $.requisition.fetchID();
            var _lineId = $.requisition.fetchLineID();
            var _method = (_lineId > 0) ? 'PUT' : (_id > 0) ? 'PUT' : 'POST';
            var _action = (_lineId > 0) ? _form.attr('action') + '/update-line/' + _lineId + '?uom_id=' + _form.find('#uom_id').val() : (_id > 0) ? _form.attr('action') + '/update/' + _id + '?department_id=' + _form.find('#department_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val()  : _form.attr('action') + '/store?department_id=' + _form.find('#department_id').val() + '&designation_id=' + _form.find('#designation_id').val() + '&purchase_type_id=' + _form.find('#purchase_type_id').val() + '&uom_id=' + _form.find('#uom_id').val();
            var _error  = $.purchase_requestForm.validate(_form, 0);

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
                // var d1 = $.purchase_requestForm.fetch_status(_id);
                var _id = $.purchase_request.fetchAlobID();
                var d1  = $.purchase_requestForm.fetch_pr_status_via_alob(_id);
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
        | # when pr modal send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#purchase-request-modal .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="prForm"]');
            var _code   = _modal.find('.variables').text();
            var _toast  = _modal.find('#modalToast');
            // var _id     = $.purchase_request.fetchID();          
            var _id     = $.purchase_request.fetchAlobID();
            var _url    = _baseUrl + 'general-services/purchase-requests/send/for-pr-approval/' + _id;
            var _error  = $.purchase_requestForm.validate(_form, 0);
            var _error2 = $.purchase_requestForm.validate_pr(_id);
            
            if (_id > 0) {
                if (_error2 > 0) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>There are some fields that needed to fill first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null; 
                } else if (_error != 0) {
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
                    var d1 = $.purchase_requestForm.fetch_pr_status_via_alob(_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {                           
                        if (v1 == 'draft') {
                            _self.prop('disabled', true).html('wait.....');
                            Swal.fire({
                                html: "Are you sure? <br/>the request with <strong>ALOB No<br/>("+ _code +")</strong> will be sent.",
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
                                                setTimeout(function () {
                                                    _toast.find('.toast-body').html(response.text);
                                                    _toast.show();            
                                                    _form.find('input[name="approved_date"]').val(response.info[0].approved_date);                               
                                                    _form.find('input[name="purchase_request_no"]').val(response.info[0].purchase_request_no);
                                                    _form.find('select.required, textarea.required').prop('disabled', true);                                                    
                                                    $.purchase_request.load_contents();
                                                    $.purchase_request.load_line_contents();
                                                }, 500 + 300 * (Math.random() * 5));
                                                setTimeout(function () {
                                                    _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                    if (response.info[0].approved_date.length > 0) {
                                                        _modal.find('button.print-btn').removeClass('hidden');
                                                    }
                                                    _toast.hide();
                                                }, 5000);
                                            } else {
                                                _self.prop('disabled', false).html('Send Request');
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
                                    // (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Send Request') : ''
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
                }
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

        /*
        | ---------------------------------
        | # when pr2 modal send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '#purchase-request2-modal .send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="prForm"]');
            var _code   = _modal.find('.variables').text();
            var _toast  = _modal.find('#modalToast');       
            var _id     = $.purchase_request.fetchAlobID();
            var _url    = _baseUrl + 'general-services/purchase-requests/send/for-pr-approval/' + _id;
            var _error  = $.purchase_requestForm.validate(_form, 0);
            var _error2 = $.purchase_requestForm.validate_pr(_id);
            
            if (_id > 0) {
                if (_error2 > 0) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>There are some fields that needed to fill first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null; 
                } else if (_error != 0) {
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
                    var d1 = $.purchase_requestForm.fetch_pr_status_via_alob(_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {                           
                        if (v1 == 'draft') {
                            _self.prop('disabled', true).html('wait.....');
                            Swal.fire({
                                html: "Are you sure? <br/>the request with <strong>ALOB No<br/>("+ _code +")</strong> will be sent.",
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
                                                setTimeout(function () {
                                                    _toast.find('.toast-body').html(response.text);
                                                    _toast.show();            
                                                    _form.find('input[name="approved_date"]').val(response.info[0].approved_date);                               
                                                    _form.find('input[name="purchase_request_no"]').val(response.info[0].purchase_request_no);
                                                    _form.find('select.required, textarea.required').prop('disabled', true);                                                    
                                                    $.purchase_request.load_contents();
                                                    $.purchase_request.load_line_contents2();
                                                }, 500 + 300 * (Math.random() * 5));
                                                setTimeout(function () {
                                                    _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                    if (response.info[0].approved_date.length > 0) {
                                                        _modal.find('button.print-btn').removeClass('hidden');
                                                    }
                                                    _toast.hide();
                                                }, 5000);
                                            } else {
                                                _self.prop('disabled', false).html('Send Request');
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
                                    // (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Send Request') : ''
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
                }
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
        


         /*
        | ---------------------------------
        | # when quantity pr is on keyup / blur
        | ---------------------------------
        */
        this.$body.on('keyup', '#item-line-table input[name="pr_quantity[]"]', function (e) {
            var _self = $(this);
            var _rows = _self.closest('tr');
            var _id   = _rows.attr('data-row-id');
            var _qty  = _rows.attr('data-row-qty');

            if (parseFloat(_self.val()) > parseFloat(_qty)) {
                _self.val(_qty);
            }
        });
        this.$body.on('blur', '#item-line-table input[name="pr_quantity[]"]', function (e) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _id    = _rows.attr('data-row-id');
            var _qty   = _rows.attr('data-row-qty');
            var _reqID = $.purchase_request.fetchID();
            var d1     = $.purchase_requestForm.fetch_status(_reqID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {     
                    if (parseFloat(_self.val()) > parseFloat(_qty)) {
                        _self.val(_qty);
                    }       
                    $.purchase_requestForm.update_item_line(_id, _self.val(), 'quantity_pr');
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });


        this.$body.on('blur', '#item-line-table input[name="pr_remarks[]"]', function (e) {
            var _self  = $(this);
            var _rows  = _self.closest('tr');
            var _id    = _rows.attr('data-row-id');
            var _qty   = _rows.attr('data-row-qty');
            var _reqID = $.purchase_request.fetchID();
            var d1     = $.purchase_requestForm.fetch_status(_reqID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                if (v1 == 'draft') {     
                    $.purchase_requestForm.update_item_line(_id, _self.val(), 'pr_remarks');
                } else {
                    console.log('sorry cannot be processed');
                }
            }); 
        });

        /*
        | ---------------------------------
        | # when print button button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#purchase-request-modal button.print-btn', function (e) {
            e.preventDefault();
            var _code   = $('form[name="prForm"]').find('input[name="purchase_request_no"]').val();
            var _url   = _baseUrl + 'general-services/purchase-requests/print/' + _code;
            window.open(_url, '_blank');
        });

        /*
        | ---------------------------------
        | # when quantity line2 modal onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#item-line2-modal input[name="quantity"]', function (e) {
            e.preventDefault();
            var _qty  = $(this);
            var _modal = $(this).closest('.modal');
            var _unit = (parseFloat(_modal.find('input[name="unit_cost"]').val()) > 0) ? parseFloat(_modal.find('input[name="unit_cost"]').val()) : 0;
            var _total = _modal.find('input[name="total_cost"]');

            if ( parseFloat(_qty.val()) > 0 && parseFloat(_unit) > 0 ) {
                _total.val( parseFloat(_qty.val()) * parseFloat(_unit) );
            } else {
                _total.val('');
            }
        });
        this.$body.on('blur', '#item-line2-modal input[name="quantity"]', function (e) {
            e.preventDefault();
            var _qty  = $(this);
            var _modal = $(this).closest('.modal');
            var _unit = (parseFloat(_modal.find('input[name="unit_cost"]').val()) > 0) ? parseFloat(_modal.find('input[name="unit_cost"]').val()) : 0;
            var _total = _modal.find('input[name="total_cost"]');

            if ( parseFloat(_qty.val()) > 0 && parseFloat(_unit) > 0 ) {
                _total.val( parseFloat(_qty.val()) * parseFloat(_unit) );
            } else {
                _total.val('');
            }
        });

        /*
        | ---------------------------------
        | # when unit line2 modal onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#item-line2-modal input[name="unit_cost"]', function (e) {
            e.preventDefault();
            var _unit  = $(this);
            var _modal = $(this).closest('.modal');
            var _qty = (parseFloat(_modal.find('input[name="quantity"]').val()) > 0) ? parseFloat(_modal.find('input[name="quantity"]').val()) : 0;
            var _total = _modal.find('input[name="total_cost"]');

            if ( parseFloat(_unit.val()) > 0 && parseFloat(_qty) > 0 ) {
                _total.val( parseFloat(_unit.val()) * parseFloat(_qty) );
            } else {
                _total.val('');
            }
        });
        this.$body.on('blur', '#item-line2-modal input[name="unit_cost"]', function (e) {
            e.preventDefault();
            var _unit  = $(this);
            var _modal = $(this).closest('.modal');
            var _qty = (parseFloat(_modal.find('input[name="quantity"]').val()) > 0) ? parseFloat(_modal.find('input[name="quantity"]').val()) : 0;
            var _total = _modal.find('input[name="total_cost"]');

            if ( parseFloat(_unit.val()) > 0 && parseFloat(_qty) > 0 ) {
                _total.val( parseFloat(_unit.val()) * parseFloat(_qty) );
            } else {
                _total.val('');
            }
        });


        /*
        | ---------------------------------
        | # when item modal submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#item-line2-modal .submit-btn', function (e){
            e.preventDefault();
            var _self    = $(this);
            var _modal   = _self.closest('.modal');
            var _form    = $('form[name="itemForm"]');
            var _alobID  = $.purchase_request.fetchAlobID();
            var _id      = $.purchase_request.fetchPrLineID();
            var _method  = (_id > 0) ? 'PUT' : 'POST';
            var _action  = (_id > 0) ? _form.attr('action') + '/modify-pr-line/' + _id + '?remarks=' + encodeURIComponent(_modal.find('textarea[name="remarks"]').val()) : _form.attr('action') + '/add-pr-line/' + _alobID + '?remarks=' + encodeURIComponent(_modal.find('textarea[name="remarks"]').val());
            var _error   = $.purchase_requestForm.validate(_form, 0);

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
                                        $.purchase_request.load_line_contents2();
                                        $.purchase_request.fetch_pr_amount(_alobID);
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
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
            }
        });
    }

    //init purchase_requestForm
    $.purchase_requestForm = new purchase_requestForm, $.purchase_requestForm.Constructor = purchase_requestForm

}(window.jQuery),

//initializing purchase_requestForm
function($) {
    "use strict";
    $.purchase_requestForm.init();
}(window.jQuery);
