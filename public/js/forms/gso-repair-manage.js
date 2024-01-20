!function($) {
    "use strict";

    var preRepairForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    preRepairForm.prototype.validate = function($form, $required)
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

    preRepairForm.prototype.preload_fixedAsset = function(_modal, _fixedAsset)
    {  
        console.log(_baseUrl + 'general-services/repairs-and-inspections/manage/preload-fixed-asset/' + _fixedAsset);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/repairs-and-inspections/manage/preload-fixed-asset/' + _fixedAsset,
            success: function(response) {
                console.log(response);
                $.each(response.data[0], function (k, v) {
                    _modal.find('input[name='+k+']').val(v);
                    _modal.find('textarea[name='+k+']').val(v);
                    _modal.find('select[name='+k+']').val(v);
                    _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                });
            },
            async: false
        });
        $.preRepair.history_contents();
    },

    preRepairForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/repairs-and-inspections/manage/fetch-status/' + _id,
            success: function(response) {
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    preRepairForm.prototype.update = function(_id, _form)
    {   
        var _url = _baseUrl + 'general-services/repairs-and-inspections/manage/update/' + _id;
        $.ajax({
            type: 'PUT',
            url: _url,
            data: _form.serialize(),
            success: function(response) {
                if (_id <= 0) {
                    $.preRepair.updateID(response.data.id);
                }
            },
        });
    },

    preRepairForm.prototype.init = function()
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
        this.$body.on('keypress', '.numeric-doubles', function (event) {
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
        | # when fixed asset no on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="property_id"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.preload_fixedAsset(_self.closest('.modal'), _self.val());
            var d2 = $.preRepair.history_contents();
            var d3 = $.preRepairForm.fetch_status(_id);
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            { 
                if (v3 == 'draft') {
                    $.preRepairForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when requested by on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="requested_by"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'draft') {
                    $.preRepairForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when requested date on change
        | ---------------------------------
        */
        this.$body.on('change', 'input[name="requested_date"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'draft') {
                    $.preRepairForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when issues / concerns on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'textarea[name="issues"]', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id = $.preRepair.fetchID();
            var d1 = $.preRepairForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            { 
                if (v1 == 'draft') {
                    $.preRepairForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when send btn is click
        | ---------------------------------
        */
        this.$body.on('click', '.send-btn', function (e){
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _modal = _self.closest('.modal');
            var _dep   = $('#department_id option:selected').text();
            var _year  = $('#budget_year').val();
            var _toast = $('#modalToast');
            var _id    = $.preRepair.fetchID();       
            var _error = $.preRepairForm.validate(_form, 0);   
            var _url   = _baseUrl + 'general-services/repairs-and-inspections/manage/send/for-approval/' + _id;

            var d1 = $.preRepairForm.fetch_status(_id);
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
                        Swal.fire({
                            html:  "Are you sure? <br/>the pre-repair inspection request<br/>will be send for approval.",
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
                                            _modal.find('input, select, textarea').prop('disabled', true);
                                            _modal.find('.store-btn').addClass('hidden');
                                            setTimeout(function () {
                                                _toast.find('.toast-body').html(response.text);
                                                _toast.show();
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _self.prop('disabled', false).html('Send Request').addClass('hidden');
                                                _toast.hide();
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
                                : "cancel" === t.dismiss, _self.prop('disabled', false).html('Send Request')
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
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="preRepairForm"]');
            var _id     = $.accountPayable.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _params = '?trans_no=' + _form.find('input[name="trans_no"]').val() + '&trans_type=' + _form.find('select[name="trans_type"]').val() + '&vat_type=' + _form.find('select[name="vat_type"]').val() + '&due_date=' + _form.find('input[name="due_date"]').val() + '&ewt_id=' + _form.find('select[name="ewt_id"]').val() + '&evat_id=' + _form.find('select[name="evat_id"]').val() + '&items=' + _form.find('input[name="items"]').val() + '&gl_account_id=' + _form.find('select[name="gl_account_id"]').val() + '&quantity=' + _form.find('input[name="quantity"]').val() + '&uom_id=' + _form.find('select[name="uom_id"]').val() + '&amount=' + _form.find('input[name="amount"]').val() + '&fund_code_id=' + _form.find('select[name="fund_code_id"]').val() + '&remarks=' + _form.find('textarea[name="remarks"]').val();
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '' + _params : _form.attr('action') + '/store' + _params;
            var _error  = $.preRepairForm.validate(_form, 0);

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
            } else if ( !(_form.find('select[name="trans_type"]').prop('disabled') > 0) && _form.find('select[name="trans_type"]').val() == 'Purchase Order' && _method == 'POST' ) {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use this trasanction!<br/>Please use/select other transaction type.",
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
                                _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                        _modal.modal('hide');
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
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
        });
    }

    //init preRepairForm
    $.preRepairForm = new preRepairForm, $.preRepairForm.Constructor = preRepairForm

}(window.jQuery),

//initializing preRepairForm
function($) {
    "use strict";
    $.preRepairForm.init();
}(window.jQuery);
