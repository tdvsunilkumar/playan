!function($) {
    "use strict";

    var sms_new = function() {
        this.$body = $("body");
    };

    var $required = 0; var _receipients = []; var $taxpayers = []; var $citizens = []; var $employees = []; var $users = [];

    sms_new.prototype.validate = function($form, $required)
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

    sms_new.prototype.search_users = function(_user) {
        var _userList = $('.users-list'); _userList.empty();
        console.log(_baseUrl + 'components/sms-notifications/search-user?user=' + _user);
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/sms-notifications/search-user?user=' + _user,
            success: function(response) {
                $.each(response.data, function(i, item) {
                    var _radio = '<div class="form-check d-flex mt-2 mb-2">' +
                    '<input class="form-check-input" type="checkbox" value="' + item.mobile_no + '" id="user_' + item.id + '">' +
                    '<label class="form-check-label ms-2" for="user_' + item.id + '">' +
                    (item.name ? item.name : '') + ' [' + item.mobile_no + ']'
                    '</label>' +
                    '</div>';
                    _userList.append(_radio);
                }); 
            }, 
            complete: function() {
                $.sms_new.perfect_scrollbar();
            }
        });
    },

    sms_new.prototype.search_employees = function(_user) {
        var _userList = $('.employees-list'); _userList.empty();
        console.log(_baseUrl + 'components/sms-notifications/search-employee?employee=' + _user);
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/sms-notifications/search-employee?employee=' + _user,
            success: function(response) {
                $.each(response.data, function(i, item) {
                    var _radio = '<div class="form-check d-flex mt-2 mb-2">' +
                    '<input class="form-check-input" type="checkbox" value="' + item.mobile_no + '" id="user_' + item.id + '">' +
                    '<label class="form-check-label ms-2" for="user_' + item.id + '">' +
                    (item.fullname ? item.fullname : '') + ' [' + item.mobile_no + ']'
                    '</label>' +
                    '</div>';
                    _userList.append(_radio);
                }); 
            }, 
            complete: function() {
                $.sms_new.perfect_scrollbar();
            }
        });
    },

    sms_new.prototype.search_taxpayers = function(_user) {
        var _userList = $('.taxpayers-list'); _userList.empty();
        console.log(_baseUrl + 'components/sms-notifications/search-taxpayer?taxpayer=' + _user);
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/sms-notifications/search-taxpayer?taxpayer=' + _user,
            success: function(response) {
                $.each(response.data, function(i, item) {
                    var _radio = '<div class="form-check d-flex mt-2 mb-2">' +
                    '<input class="form-check-input" type="checkbox" value="' + item.p_mobile_no + '" id="user_' + item.id + '">' +
                    '<label class="form-check-label ms-2" for="user_' + item.id + '">' +
                    $.trim((item.rpo_first_name ? item.rpo_first_name : '') + ' ' + (item.rpo_middle_name ? item.rpo_middle_name : '') + ' ' + (item.rpo_custom_last_name ? item.rpo_custom_last_name : ''))
                    + ' [' + item.p_mobile_no + ']'
                    '</label>' +
                    '</div>';
                    _userList.append(_radio);
                }); 
            }, 
            complete: function() {
                $.sms_new.perfect_scrollbar();
            }
        });
    },

    sms_new.prototype.search_citizens = function(_user) {
        var _userList = $('.citizens-list'); _userList.empty();
        console.log(_baseUrl + 'components/sms-notifications/search-citizen?citizen=' + _user);
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/sms-notifications/search-citizen?citizen=' + _user,
            success: function(response) {
                $.each(response.data, function(i, item) {
                    var _radio = '<div class="form-check d-flex mt-2 mb-2">' +
                    '<input class="form-check-input" type="checkbox" value="' + item.cit_mobile_no + '" id="user_' + item.id + '">' +
                    '<label class="form-check-label ms-2" for="user_' + item.id + '">' +
                    $.trim((item.cit_first_name ? item.cit_first_name : '') + ' ' + (item.cit_middle_name ? item.cit_middle_name : '') + ' ' + (item.cit_last_name ? item.cit_last_name : '') + ' ' + (item.cit_suffix_name ? item.cit_suffix_name : ''))
                    + ' [' + item.cit_mobile_no + ']'
                    '</label>' +
                    '</div>';
                    _userList.append(_radio);
                }); 
            }, 
            complete: function() {
                $.sms_new.perfect_scrollbar();
            }
        });
    },

    sms_new.prototype.perfect_scrollbar = function()
    {
        if ($(".m-scrollable")) {
            $.each($('.m-scrollable'), function(_i = 0){
                _i++;
                $(this).attr('id', '_table' + _i);
                var _divID = '#' + $(this).attr('id');
                var px = new PerfectScrollbar(_divID, {
                    wheelSpeed: 0.5,
                    swipeEasing: 0,
                    wheelPropagation: 1,
                    minScrollbarLength: 100,
                });
            });
        }

    },

    sms_new.prototype.reset_messages = function()
    {

    },

    sms_new.prototype.init = function()
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
        | # when search user onkeyup
        | ---------------------------------
        */
        $.sms_new.search_users($('input[name="search-user"]').val());
        this.$body.on('keypress', 'input[name="search-user"]', function (e){
            var self = $(this);
            if (e.which == 13) {
                $.sms_new.search_users(self.val());
            } 
        });
        this.$body.on('keydown', 'input[name="search-user"]', function (e){
            var self = $(this);
            if (e.which == 8 && self.val().length == 1) {
                $.sms_new.search_users('');
            } 
        });
        this.$body.on('click', '.btn-search-user', function (e){
            var self = $('input[name="search-user"]');
            if (self.val().length > 0) {
                $.sms_new.search_users(self.val());
            } 
        });

        /*
        | ---------------------------------
        | # when search employee onkeyup
        | ---------------------------------
        */
        $.sms_new.search_employees($('input[name="search-employee"]').val());
        this.$body.on('keypress', 'input[name="search-employee"]', function (e){
            var self = $(this);
            if (e.which == 13) {
                $.sms_new.search_employees(self.val());
            } 
        });
        this.$body.on('keydown', 'input[name="search-employee"]', function (e){
            var self = $(this);
            if (e.which == 8 && self.val().length == 1) {
                $.sms_new.search_employees('');
            } 
        });
        this.$body.on('click', '.btn-search-employee', function (e){
            var self = $('input[name="search-employee"]');
            if (self.val().length > 0) {
                $.sms_new.search_employees(self.val());
            } 
        });

        /*
        | ---------------------------------
        | # when search taxpayer onkeyup
        | ---------------------------------
        */
        $.sms_new.search_taxpayers($('input[name="search-taxpayer"]').val());
        this.$body.on('keypress', 'input[name="search-taxpayer"]', function (e){
            var self = $(this);
            if (e.which == 13) {
                $.sms_new.search_taxpayers(self.val());
            } 
        });
        this.$body.on('keydown', 'input[name="search-taxpayer"]', function (e){
            var self = $(this);
            if (e.which == 8 && self.val().length == 1) {
                $.sms_new.search_taxpayers('');
            } 
        });
        this.$body.on('click', '.btn-search-taxpayer', function (e){
            var self = $('input[name="search-taxpayer"]');
            if (self.val().length > 0) {
                $.sms_new.search_taxpayers(self.val());
            } 
        });

        /*
        | ---------------------------------
        | # when search citizen onkeyup
        | ---------------------------------
        */
        $.sms_new.search_citizens($('input[name="search-citizen"]').val());
        this.$body.on('keypress', 'input[name="search-citizen"]', function (e){
            var self = $(this);
            if (e.which == 13) {
                $.sms_new.search_citizens(self.val());
            } 
        });
        this.$body.on('keydown', 'input[name="search-citizen"]', function (e){
            var self = $(this);
            if (e.which == 8 && self.val().length == 1) {
                $.sms_new.search_citizens('');
            } 
        });
        this.$body.on('click', '.btn-search-citizen', function (e){
            var self = $('input[name="search-citizen"]');
            if (self.val().length > 0) {
                $.sms_new.search_citizens(self.val());
            } 
        });

        /*
        | ---------------------------------
        | # when employee submit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.send-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _form = $('form[name="smsForm"]');
            var _msg = _form.find('textarea[name="messages"]').val().replace(/\n/g, '\\n');

            if (_form.find('textarea[name="messages"]').val() == '') {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add some messages first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                if (_receipients.length > 0) {
                    _self.prop('disabled', true).html('Wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                    $.ajax({
                        type: _form.attr('method'),
                        url: _form.attr('action'),
                        data: {'message' : _msg, 'receipients': _receipients},
                        success: function(response) {
                        }, 
                        async: true,
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                            setTimeout(function () {
                            _self.html('<i class="la la-send"></i> Send Message').prop('disabled', false);
                            Swal.fire({ title: 'Well done!', text: 'The request has been sucessfully sent.', icon: 'success', buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                    $.sms_new.reset_messages();
                                }
                            );
                            }, 500 + 300 * (Math.random() * 5));
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Something went wrong!<br/>Please add a receipient first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            }
        });

        /*
        | ---------------------------------
        | # when send later button show modal
        | ---------------------------------
        */
        this.$body.on('click', '.send-later-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _form = $('form[name="smsForm"]');
            var _modal = $('#send-later-modal');
            if (_form.find('textarea[name="messages"]').val() == '') {
                Swal.fire({
                    title: "Oops...",
                    html: "Something went wrong!<br/>Please add some messages first.",
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
                window.onkeydown = null;
                window.onfocus = null;   
            } else {
                if (_receipients.length > 0) {
                    _modal.modal('show');
                } else {
                    Swal.fire({
                        title: "Oops...",
                        html: "Something went wrong!<br/>Please add a receipient first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;   
                }
            }
        });
        
        /*
        | ---------------------------------
        | # when send later button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#send-later-modal .submit-btn', function (e){
            e.preventDefault();
            var _self = $(this);
            var _modal = _self.closest('.modal');
            var _form = $('form[name="smsForm"]');
            var _form2 = _modal.find('form');
            var _sched = _modal.find('input[name="schedule"]').val();
            var _msg = _form.find('textarea[name="messages"]').val().replace(/\n/g, '\\n');

            if (_receipients.length > 0) {
                _self.prop('disabled', true).html('Wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                $.ajax({
                    type: _form2.attr('method'),
                    url: _form2.attr('action'),
                    data: {'schedule': _sched, 'message' : _msg, 'receipients': _receipients},
                    success: function(response) {
                    }, 
                    async: true,
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                        setTimeout(function () {
                        _self.html('<i class="la la-send"></i> Send Later').prop('disabled', false);
                        Swal.fire({ title: 'Well done!', text: 'The request has been sucessfully scheduled.', icon: 'success', buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                            function (e) {
                                _modal.modal('hide');
                                $.sms_new.reset_messages();
                            }
                        );
                        }, 500 + 300 * (Math.random() * 5));
                    }
                });
            }
        });


        /*
        | ---------------------------------
        | # when messages onKeyup
        | ---------------------------------
        */
        this.$body.on('keyup', 'textarea[name="messages"]', function (e){
            var self = $(this);
            var limitholder = $('.text-length');
            var setholder = $('.text-set');
            var sets = 1;
            limitholder.text( parseFloat(480) - self.val().length);
            if (self.val().length > 320) {
                sets = 3;
            } else if (self.val().length > 160) {
                sets = 2;
            } else {
                sets = 1;
            }
            setholder.text(sets + '/3');
        });
        this.$body.on('blur', 'textarea[name="messages"]', function (e){
            var self = $(this);
            var limitholder = $('.text-length');
            var setholder = $('.text-set');
            var sets = 1;
            limitholder.text( parseFloat(480) - self.val().length);
            if (self.val().length > 320) {
                sets = 3;
            } else if (self.val().length > 160) {
                sets = 2;
            } else {
                sets = 1;
            }
            setholder.text(sets + '/3');
        });
        this.$body.on('paste', 'textarea[name="messages"]', function (e){
            var self = $(this);
            var limitholder = $('.text-length');
            var setholder = $('.text-set');
            var sets = 1;
            limitholder.text( parseFloat(480) - self.val().length);
            if (self.val().length > 320) {
                sets = 3;
            } else if (self.val().length > 160) {
                sets = 2;
            } else {
                sets = 1;
            }
            setholder.text(sets + '/3');
        });
        this.$body.on('copy', 'textarea[name="messages"]', function (e){
            var self = $(this);
            var limitholder = $('.text-length');
            var setholder = $('.text-set');
            var sets = 1;
            limitholder.text( parseFloat(480) - self.val().length);
            if (self.val().length > 320) {
                sets = 3;
            } else if (self.val().length > 160) {
                sets = 2;
            } else {
                sets = 1;
            }
            setholder.text(sets + '/3');
        });
        this.$body.on('cut', 'textarea[name="messages"]', function (e){
            var self = $(this);
            var limitholder = $('.text-length');
            var setholder = $('.text-set');
            var sets = 1;
            limitholder.text( parseFloat(480) - self.val().length);
            if (self.val().length > 320) {
                sets = 3;
            } else if (self.val().length > 160) {
                sets = 2;
            } else {
                sets = 1;
            }
            setholder.text(sets + '/3');
        });

        /*
        | ---------------------------------
        | # users checkbox
        | ---------------------------------
        */
        this.$body.on('click', '.users-list input[type="checkbox"]', function (e) { 
            var self = $(this);
            var value = self.val();

            if (self.is(":checked")) {
                var found = false;
                for (var i = 0; i < _receipients.length; i++) {
                    if (_receipients[i] == value) {
                        found == true;
                        return;
                    }
                } 
                if (found == false) {
                    _receipients.push(value);
                }
            } else {
                $.each(_receipients, function (ix) {
                    if (_receipients[ix] == value) {
                        _receipients.splice(ix, 1);
                        return false;
                    }
                });
            }
            console.log(_receipients);
        });
        /*
        | ---------------------------------
        | # employees checkbox
        | ---------------------------------
        */
        this.$body.on('click', '.employees-list input[type="checkbox"]', function (e) { 
            var self = $(this);
            var value = self.val();

            if (self.is(":checked")) {
                var found = false;
                for (var i = 0; i < _receipients.length; i++) {
                    if (_receipients[i] == value) {
                        found == true;
                        return;
                    }
                } 
                if (found == false) {
                    _receipients.push(value);
                }
            } else {
                $.each(_receipients, function (ix) {
                    if (_receipients[ix] == value) {
                        _receipients.splice(ix, 1);
                        return false;
                    }
                });
            }
            console.log(_receipients);
        });
        /*
        | ---------------------------------
        | # taxpayers checkbox
        | ---------------------------------
        */
        this.$body.on('click', '.taxpayers-list input[type="checkbox"]', function (e) { 
            var self = $(this);
            var value = self.val();

            if (self.is(":checked")) {
                var found = false;
                for (var i = 0; i < _receipients.length; i++) {
                    if (_receipients[i] == value) {
                        found == true;
                        return;
                    }
                } 
                if (found == false) {
                    _receipients.push(value);
                }
            } else {
                $.each(_receipients, function (ix) {
                    if (_receipients[ix] == value) {
                        _receipients.splice(ix, 1);
                        return false;
                    }
                });
            }
            console.log(_receipients);
        });
        /*
        | ---------------------------------
        | # citizen checkbox
        | ---------------------------------
        */
        this.$body.on('click', '.citizens-list input[type="checkbox"]', function (e) { 
            var self = $(this);
            var value = self.val();

            if (self.is(":checked")) {
                var found = false;
                for (var i = 0; i < _receipients.length; i++) {
                    if (_receipients[i] == value) {
                        found == true;
                        return;
                    }
                } 
                if (found == false) {
                    _receipients.push(value);
                }
            } else {
                $.each(_receipients, function (ix) {
                    if (_receipients[ix] == value) {
                        _receipients.splice(ix, 1);
                        return false;
                    }
                });
            }
            console.log(_receipients);
        });

        /*
        | ---------------------------------
        | # select all checkbox
        | ---------------------------------
        */
        this.$body.on('click', '.select-all', function (e){
            var self = $(this);
            if (self.attr('value') == 'user') {
                $('.users-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    var existed = jQuery.inArray( checkbox.val(), _receipients );
                    checkbox.prop('checked', true); 
                    if (existed == -1) {
                        _receipients.push(checkbox.val());
                    }
                });
                console.log(_receipients);
            } else if (self.attr('value') == 'employee') {
                $('.employees-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    var existed = jQuery.inArray( checkbox.val(), _receipients );
                    checkbox.prop('checked', true); 
                    if (existed == -1) {
                        _receipients.push(checkbox.val());
                    }
                });
                console.log(_receipients);
            } else if (self.attr('value') == 'taxpayer') {
                $('.taxpayers-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    var existed = jQuery.inArray( checkbox.val(), _receipients );
                    checkbox.prop('checked', true); 
                    if (existed == -1) {
                        _receipients.push(checkbox.val());
                    }
                });
                console.log(_receipients);
            } else {
                $('.citizens-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    var existed = jQuery.inArray( checkbox.val(), _receipients );
                    checkbox.prop('checked', true); 
                    if (existed == -1) {
                        _receipients.push(checkbox.val());
                    }
                });
                console.log(_receipients);
            }
        });
        
        this.$body.on('click', '.deselect-all', function (e){
            var self = $(this);
            if (self.attr('value') == 'user') {
                $('.users-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    checkbox.prop('checked', false);
                    $.each(_receipients, function (ix) {
                        if (_receipients[ix] == checkbox.val()) {
                            _receipients.splice(ix, 1);
                            return false;
                        }
                    });
                });                
                console.log(_receipients);
            } else if (self.attr('value') == 'employee') {
                $('.employees-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    checkbox.prop('checked', false);
                    $.each(_receipients, function (ix) {
                        if (_receipients[ix] == checkbox.val()) {
                            _receipients.splice(ix, 1);
                            return false;
                        }
                    });
                });
                console.log(_receipients);
            } else if (self.attr('value') == 'taxpayer') {
                $('.taxpayers-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    checkbox.prop('checked', false);
                    $.each(_receipients, function (ix) {
                        if (_receipients[ix] == checkbox.val()) {
                            _receipients.splice(ix, 1);
                            return false;
                        }
                    });
                });
                console.log(_receipients);
            } else {
                $('.citizens-list input[type="checkbox"]').each(function () { 
                    var checkbox = $(this);
                    checkbox.prop('checked', false);
                    $.each(_receipients, function (ix) {
                        if (_receipients[ix] == checkbox.val()) {
                            _receipients.splice(ix, 1);
                            return false;
                        }
                    });
                });
                console.log(_receipients);
            }
        });
    }

    //init sms_new
    $.sms_new = new sms_new, $.sms_new.Constructor = sms_new

}(window.jQuery),

//initializing sms_new
function($) {
    "use strict";
    $.sms_new.init();
}(window.jQuery);
