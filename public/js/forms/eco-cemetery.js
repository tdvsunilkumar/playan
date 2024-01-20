!function($) {
    "use strict";

    var econCemeteryForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    econCemeteryForm.prototype.validate = function($form, $required)
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

    econCemeteryForm.prototype.fetch_data = function(_key, _requestor = 0)
    {   
        var _value = '';
        console.log(_baseUrl + 'economic-and-investment/cemetery-application/fetch-data/' + _requestor + '?key=' + _key);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'economic-and-investment/cemetery-application/fetch-data/' + _requestor + '?key=' + _key,
            success: function(response) {
                _value = response.data;
                console.log($.trim(_value));
            },
            async: false
        });

        return _value;
    },

    econCemeteryForm.prototype.reload_cemetery_lot = function(_id, _modal)
    {   
        var _lot = _modal.find('select[name="cemetery_lot_id"]');  _lot.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/cemetery-application/reload-cemetery-lot/'+ _id +'?location=' + _modal.find('select[name="location_id"]').val() + '&cemetery=' + _modal.find('select[name="cemetery_id"]').val() + '&style=' + _modal.find('select[name="cemetery_style_id"]').val();
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _lot.append('<option value="">select a cemetery lot</option>');  
                $.each(response.data, function(i, item) {
                    _lot.append('<option value="' + item.id + '"> ' + item.ecl_lot + ' LOT</option>');  
                }); 
            },
            async: false
        });
    },
    
    econCemeteryForm.prototype.reload_cemetery_name = function(_modal)
    {   
        var _cementery = _modal.find('select[name="cemetery_id"]');  _cementery.find('option').remove(); 
        var _url = _baseUrl + 'economic-and-investment/cemetery-application/reload-cemetery-name?location=' + _modal.find('select[name="location_id"]').val();
        console.log(_url);
        $.ajax({
            type: "GET",
            url: _url,
            success: function(response) {
                console.log(response.data);
                _cementery.append('<option value="">select a cemetery</option>');  
                $.each(response.data, function(i, item) {
                    _cementery.append('<option value="' + item.id + '"> ' + item.cem_name + ' LOT</option>');  
                }); 
            },
            async: false
        });
    },

    econCemeteryForm.prototype.init = function()
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
        | # when requestor on change
        | ---------------------------------
        */
        this.$body.on('change', '#requestor_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            if (_self.val() != '') {
                var d1 = $.econCemeteryForm.fetch_data('full_address', _self.val());
                var d2 = $.econCemeteryForm.fetch_data('contact_no', _self.val());
                $.when( d1, d2 ).done(function (v1, v2) { 
                    _modal.find('input[name="full_address"]').val(v1);
                    _modal.find('input[name="contact_no"]').val(v2);
                });
            }   
        });

        /*
        | ---------------------------------
        | # when cemetery location on change
        | ---------------------------------
        */
        this.$body.on('change', '#location_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.econCemetery.fetchID();
            $.econCemeteryForm.reload_cemetery_name(_modal);
            $.econCemeteryForm.reload_cemetery_lot(_id, _modal);
        });

        /*
        | ---------------------------------
        | # when cemetery name on change
        | ---------------------------------
        */
        this.$body.on('change', '#cemetery_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.econCemetery.fetchID();
            $.econCemeteryForm.reload_cemetery_lot(_id, _modal);
        });

        /*
        | ---------------------------------
        | # when cemetery style on change
        | ---------------------------------
        */
        this.$body.on('change', '#cemetery_style_id', function (event) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _id = $.econCemetery.fetchID();
            $.econCemeteryForm.reload_cemetery_lot(_id, _modal);
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
            var _form   = _modal.find('form[name="econCemeteryForm"]');
            var _id     = $.econCemetery.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.econCemeteryForm.validate(_form, 0);

            var d1 = $.econCemetery.fetch_status(_id);
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
                                                if (_id <= 0) {
                                                    $.econCemetery.updateID(response.data.id);
                                                    _form.find('input[name="transaction_no"]').val(response.data.transaction_no);
                                                }
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
        this.$body.on('click', '.term-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _code   = _modal.find('input[name="transaction_no"]').val();
            var _form   = _modal.find('form[name="econCemeteryForm"]');
            var _id     = $.econCemetery.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update-terms/' + _id : _form.attr('action') + '/store';
            var _error  = $.econCemeteryForm.validate(_form, 0);

            var d1 = $.econCemetery.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'partial') {
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
                            html: "Are you sure? <br/>the cemetery terms with <strong>Transaction No<br/>("+ _code +")</strong> will be updated.",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, update it!",
                            cancelButtonText: "No, return",
                            customClass: { confirmButton: "btn bg-info text-white", cancelButton: "btn btn-active-light" },
                        }).then(function (t) {
                            t.value
                                ? 
                                $.ajax({
                                    type: _method,
                                    url: _action,
                                    data: _form.serialize(),
                                    success: function(response) {
                                        console.log(response);
                                        if (response.status == 'success') {
                                            _self.html('<i class="la la-save"></i> Update Terms').prop('disabled', false).addClass('hidden');
                                            _modal.find('select[name="terms"]').prop('disabled', true);
                                            setTimeout(function () {
                                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                                    function (e) {
                                                    }
                                                );
                                            }, 500 + 300 * (Math.random() * 5));
                                        } else {
                                            _self.html('<i class="la la-save"></i> Update Terms').prop('disabled', false);
                                            _form.find('input[name="' + response.column + '"]').addClass('is-invalid').next().text(response.label);
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                                function (e) {
                                                }
                                            );
                                            window.onkeydown = null;
                                            window.onfocus = null;    
                                        }
                                    },
                                    complete: function() {
                                        window.onkeydown = null;
                                        window.onfocus = null;
                                    }
                                })
                                : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false).html('Update Terms') : ''
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
    }

    //init econCemeteryForm
    $.econCemeteryForm = new econCemeteryForm, $.econCemeteryForm.Constructor = econCemeteryForm

}(window.jQuery),

//initializing econCemeteryForm
function($) {
    "use strict";
    $.econCemeteryForm.init();
}(window.jQuery);
