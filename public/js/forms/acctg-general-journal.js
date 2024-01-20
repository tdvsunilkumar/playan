!function($) {
    "use strict";

    var generalJournalForm = function() {
        this.$body = $("body");
    };

    var $required = 0;

    generalJournalForm.prototype.validate = function($form, $required)
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

    generalJournalForm.prototype.fetch_status = function (_id)
    {   
        var _status = '';
        if (_id == 0) {
            return _status = 'draft';
        }
        console.log(_baseUrl + 'accounting/general-journals/fetch-status/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/general-journals/fetch-status/' + _id,
            success: function(response) {
                console.log(response);
                _status = response.status;
            },
            async: false
        });
        return _status;
    },

    generalJournalForm.prototype.validate_journal = function (_id)
    {   
        var _validate = 0;
        console.log(_baseUrl + 'accounting/general-journals/validate/' + _id);
        $.ajax({
            type: "GET",
            url: _baseUrl + 'accounting/general-journals/validate/' + _id,
            success: function(response) {
                console.log(response);
                _validate = response.validate;
            },
            async: false
        });
        return _validate;
    },

    generalJournalForm.prototype.update = function(_id, _form)
    {   
        var _url = _baseUrl + 'accounting/general-journals/update/' + _id + '?fixed_asset=' + _form.find('select[name="fixed_asset_id"]').val() + '&fund_code=' + _form.find('select[name="fund_code_id"]').val() + '&payee=' + _form.find('select[name="payee_id"]').val() + '&particulars=' + _form.find('textarea[name="particulars"]').val() + '&division=' + _form.find('select[name="division_id"]').val() + '&trans_date=' + _form.find('input[name="transaction_date"]').val();
        console.log(_url);
        $.ajax({
            type: 'PUT',
            url: _url,
            success: function(response) {
                console.log(response);
                if (_id <= 0) {
                    $.generalJournal.updateID(response.data.id);
                }
                console.log(response.data.general_journal_no);
                _form.find('input[name="general_journal_no"]').val(response.data.general_journal_no);
            },
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    generalJournalForm.prototype.init = function()
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
        | # when payee on change
        | ---------------------------------
        */
        this.$body.on('change', 'form[name="generalJournalForm"] select', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.generalJournal.fetchID();
            var d1    = $.generalJournalForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    $.generalJournalForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });
        /*
        | ---------------------------------
        | # when particular on blur
        | ---------------------------------
        */
        this.$body.on('blur', 'form[name="generalJournalForm"] textarea', function (event) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.generalJournal.fetchID();
            var d1    = $.generalJournalForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1) 
            {  
                if (v1 == 'draft') {
                    $.generalJournalForm.update(_id, _form);
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
        this.$body.on('blur', 'form[name="generalJournalForm"] input[type="date"]:not(:disabled)', function (e) {
            var _self = $(this);
            var _form = _self.closest('form');
            var _id   = $.generalJournal.fetchID();
            var d1    = $.generalJournalForm.fetch_status(_id);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    $.generalJournalForm.update(_id, _form);
                } else {
                    console.log('sorry cannot be processed');
                }
            });
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#general-journal-modal .submit-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="generalJournalForm"]');
            var _id     = $.generalJournal.fetchID();
            var _method = 'PUT';  
            var _action = _form.attr('action') + '/complete/' + _id;
            var _error  = $.generalJournalForm.validate(_form, 0);
            var _id   = $.generalJournal.fetchID();
            var d1    = $.generalJournalForm.fetch_status(_id);
            var d2    = $.generalJournalForm.validate_journal(_id);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (!(v2 > 0 )) {
                    _self.prop('disabled', false).html('Post Changes');
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to proceed!<br/>The debit and credit amount doesn't matched or don't have value.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                } else if (v1 == 'draft') {
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
                                        _self.html('Post Changes').prop('disabled', false);
                                        Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                            function (e) {
                                                _modal.modal('hide');
                                            }
                                        );
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('Post Changes').prop('disabled', false);
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
                    _self.prop('disabled', false).html('Post Changes');
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
        | # when add breakdown button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#journal-entry-modal .submit-btn', function (e) {
            var _self     = $(this);
            var _form     = _self.closest('form');
            var _modal    = _self.closest('.modal');
            var _error    = $.generalJournalForm.validate(_form, 0);
            var _id       = $.generalJournal.fetchID();
            var _entryID  = $.generalJournal.fetchEntryID();
            var d1        = $.generalJournalForm.fetch_status(_id);

            _self.prop('disabled', true).html('Wait.....');
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    if (_error != 0) {
                        _self.prop('disabled', false).html('Save Changes');
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
                        $.ajax({
                            type: (_entryID > 0) ? 'PUT' : _form.attr('method'),
                            url: (_entryID > 0) ? _form.attr('action') + '/modify-entry/' + _entryID : _form.attr('action') + '/store-entry/' + _id,
                            data: _form.serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.type == 'success') {
                                    setTimeout(function () {
                                        _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                        $.generalJournal.load_line_contents();
                                        $('#general-journal-modal').find('tfoot th.total-debit').text('₱' + $.generalJournal.price_separator(parseFloat(response.total_debit).toFixed(2)));
                                        $('#general-journal-modal').find('tfoot th.total-credit').text('₱' + $.generalJournal.price_separator(parseFloat(response.total_credit).toFixed(2)));
                                        _modal.modal('hide');
                                    }, 500 + 300 * (Math.random() * 5));
                                } else {
                                    _self.html('<i class="la la-save align-middle"></i> Save Changes').prop('disabled', false);
                                    _form.find('select[name="' + response.column + '"]').addClass('is-invalid').closest('.form-group').find('.m-form__help').text(response.label);
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
                    _self.prop('disabled', false).html('<i class="la la-save align-middle"></i> Save Changes');
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

    //init generalJournalForm
    $.generalJournalForm = new generalJournalForm, $.generalJournalForm.Constructor = generalJournalForm

}(window.jQuery),

//initializing generalJournalForm
function($) {
    "use strict";
    $.generalJournalForm.init();
}(window.jQuery);
