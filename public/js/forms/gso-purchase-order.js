!function($) {
    "use strict";

    var gso_purchaseOrderForm = function() {
        this.$body = $("body");
    };

    var $required = 0; var _committees = []; var _purchases = [];

    gso_purchaseOrderForm.prototype.validate = function($form, $required)
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

    gso_purchaseOrderForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'general-services/purchase-orders/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/purchase-orders/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    gso_purchaseOrderForm.prototype.update = function(_id, _form, _field)
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
                    $.gso_purchaseOrder.updateID(response.data.id);
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
                    _modal.find('tfoot th:last-child').text('₱' + $.gso_purchaseOrder.price_separator(parseFloat(response.data.total_amount).toFixed(2)));
                    $.gso_purchaseOrder.load_pr_contents();
                    $.gso_purchaseOrder.load_item_contents();
                }
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    gso_purchaseOrderForm.prototype.init = function()
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
                (text.substring(text.indexOf('.')).length > 5) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 5)) {
                event.preventDefault();
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
            var _id   = $.gso_purchaseOrder.fetchID();
            var d1    = $.gso_purchaseOrderForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_purchaseOrderForm.update(_id, _form, _self.attr('name'));
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
            var _id   = $.gso_purchaseOrder.fetchID();
            var d1    = $.gso_purchaseOrderForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_purchaseOrderForm.update(_id, _form, _self.attr('name'));
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
            var _id   = $.gso_purchaseOrder.fetchID();
            var d1    = $.gso_purchaseOrderForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_purchaseOrderForm.update(_id, _form, _self.attr('name'));
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
            var _id   = $.gso_purchaseOrder.fetchID();
            var d1    = $.gso_purchaseOrderForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.gso_purchaseOrderForm.update(_id, _form, _self.attr('name'));
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
        this.$body.on('click', 'form[name="purchaseOrderForm"] button.send-btn', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _self.closest('form');
            var _code   = _form.find('select[name="rfq_id"] option:selected').text();
            var _id     = $.gso_purchaseOrder.fetchID();
            var d1      = $.gso_purchaseOrderForm.fetch_status(_id);
            var _error  = $.gso_purchaseOrderForm.validate(_form, 0);
            var _url    = _baseUrl + 'general-services/purchase-orders/send/for-po-approval/' + _id;
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
                                                $.gso_purchaseOrder.load_contents();
                                            }, 500 + 300 * (Math.random() * 5));
                                            setTimeout(function () {
                                                _toast.hide();
                                            }, 5000);
                                        } else {
                                            _self.prop('disabled', false).html('Send Request')
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

    }

    //init gso_purchaseOrderForm
    $.gso_purchaseOrderForm = new gso_purchaseOrderForm, $.gso_purchaseOrderForm.Constructor = gso_purchaseOrderForm

}(window.jQuery),

//initializing gso_purchaseOrderForm
function($) {
    "use strict";
    $.gso_purchaseOrderForm.init();
}(window.jQuery);
