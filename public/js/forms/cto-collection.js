!function($) {
    "use strict";

    var ctoCollectionForm = function() {
        this.$body = $("body");
    };

    var $required = 0; 

    ctoCollectionForm.prototype.validate = function($form, $required)
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
                    } else if($(this).val()== null && $(this).is("select")){
                        $(this).addClass('is-invalid');
                        $required++;    
                        $(this).closest('.form-group').find('.select3-selection--single').addClass('is-invalid');       
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

    ctoCollectionForm.prototype.validate_collection = function(_collectionID)
    {
        var _validate = 0;
        if (_collectionID > 0) {
            console.log(_baseUrl + 'treasury/collections/validate-collection/' + _collectionID);
            $.ajax({
                type: "GET",
                url: _baseUrl + 'treasury/collections/validate-collection/' + _collectionID,
                success: function(response) {
                    _validate = response.validate;
                },
                async: false
            });
        }
        return _validate;
    },

    ctoCollectionForm.prototype.init = function()
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
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.send-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="collectionForm"]');
            var _id     = $.ctoCollection.fetchID();
            var _code   = _form.find('input[name="transaction_no"]').val();
            var _method = 'PUT';
            var _action = _form.attr('action') + '/send/for-approval/' + _id;
            var _error  = $.ctoCollectionForm.validate(_form, 0);
            var _total1 = $.ctoCollection.fetchTotalTrans();
            var _total2 = $.ctoCollection.fetchTotalBill();
            var _toast  = _modal.find('#modalToast');

            var d1 = $.ctoCollectionForm.validate_collection(_id);
            $.when( d1 ).done(function ( v1 ) 
            {   
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
                } else if (!(v1 > 0)) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Something went wrong!<br/>Please update the changes first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                } else if (parseFloat(_total1) > 0 && (parseFloat($.ctoCollection.money_format(parseFloat(_total1))) != parseFloat($.ctoCollection.money_format(parseFloat(_total2))))) {
                    console.log('total 1: ' + $.ctoCollection.money_format(_total1) + ' , total2: ' + $.ctoCollection.money_format(_total2));
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to use proceed!<br/>Please ensure that the total transaction amount is equal to total denomination bill.",
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
                        html: "Are you sure? <br/>the request with <strong>Transaction No<br/>("+ _code +")</strong> will be send for approval.",
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
                                type: _method,
                                url: _action,
                                success: function(response) {
                                    console.log(response);
                                    if (response.status == 'success') {
                                        _self.prop('disabled', true).html('wait.....');
                                        _modal.find('.submit-btn').addClass('hidden');
                                        setTimeout(function () {
                                            _toast.find('.toast-body').html(response.text);
                                            _toast.show();
                                        }, 500 + 300 * (Math.random() * 5));
                                        setTimeout(function () {
                                            _modal.find('.print-btn').removeClass('hidden');
                                            _self.prop('disabled', false).html('<i class="la la-send align-middle"></i> Send').addClass('hidden');
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
                            : "cancel" === t.dismiss, (t.dismiss === "cancel") ? _self.prop('disabled', false).html('<i class="la la-send align-middle"></i> Send') : ''
                    });
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
            var _form   = _modal.find('form[name="collectionForm"]');
            var _id     = $.ctoCollection.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id : _form.attr('action') + '/store';
            var _error  = $.ctoCollectionForm.validate(_form, 0);
            var _total1 = $.ctoCollection.fetchTotalTrans();
            var _total2 = $.ctoCollection.fetchTotalBill();
            var _collections = $.ctoCollection.fetchCollections();

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
            } else if (parseFloat(_total1) > 0 && ($.ctoCollection.money_format(parseFloat(_total1)) != $.ctoCollection.money_format(parseFloat(_total2)))) {
                console.log('total 1: ' + $.ctoCollection.money_format(_total1) + ' , total2: ' + $.ctoCollection.money_format(_total2));
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to use proceed!<br/>Please ensure that the total transaction amount is equal to total denomination bill.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                console.log('total 1: ' + $.ctoCollection.money_format(_total1) + ' , total2: ' + $.ctoCollection.money_format(_total2));
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _method,
                    url: _action,
                    data: _form.serialize() + '&collections=' + _collections + '&total_amount=' + _total1,
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            if (_id <= 0) {
                                _form.find('input[name="transaction_no"]').val(response.data.trans_no);
                                $.ctoCollection.updateID(response.data.id);
                            }
                            setTimeout(function () {
                                _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                    function (e) {
                                    }
                                );
                            }, 500 + 300 * (Math.random() * 5));
                        } else {
                            _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                            $.each(response.columns, function(i, column) {
                                _form.find('select[name="' + column + '"]').addClass('is-invalid').next().text(response.label[i]);
                            }); 
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

        /*
        | ---------------------------------
        | # when print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#collection-modal .print-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _transNo = _modal.find('input[name="transaction_no"]').val();
            var _url  = _baseUrl + 'treasury/collections/print/' + _transNo; 
            window.open(_url, '_blank');
        });
    }

    //init ctoCollectionForm
    $.ctoCollectionForm = new ctoCollectionForm, $.ctoCollectionForm.Constructor = ctoCollectionForm

}(window.jQuery),

//initializing ctoCollectionForm
function($) {
    "use strict";
    $.ctoCollectionForm.init();
}(window.jQuery);
