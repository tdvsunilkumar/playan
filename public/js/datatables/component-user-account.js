!function($) {
    "use strict";

    var user_account = function() {
        this.$body = $("body");
    };

    var _useraccountID = 0; var _table; var _page = 0;

    user_account.prototype.required_fields = function() {
        this.$body.find(".form-group").find('label span').remove();
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='password'], input[type='text'], input[type='email'], select, textarea");
                if ($input.val() == '') {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='text'], select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    user_account.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#userAccountTable', {
            ajax: { 
                url : _baseUrl + 'components/users/accounts/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.user_account.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.user_account.hideTooltip();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.username);
                $(row).attr('data-row-status', data.status);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
            },      
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'username' },
                { data: 'role' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
            ]
        } );

        return true;
    },

    user_account.prototype.fetchID = function()
    {
        return _useraccountID;
    }

    user_account.prototype.updateID = function(_id)
    {
        return _useraccountID = _id;
    }

    user_account.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    user_account.prototype.preload_select3 = function()
    {
        if ( $('.select3') ) {
            $.each($('.select3'), function(){
                var _self = $(this);
                var _selfID = $(this).attr('id');
                var _parentID = 'parent_' + _selfID;
                _self.closest('.form-group').attr('id', _parentID);

                _self.select3({
                    allowClear: true,
                    dropdownAutoWidth : false,
                    dropdownParent: $('#' + _parentID),
                });
            });
        }
    },

    user_account.prototype.load_menus = function(_id = 0, _user = 0, _modal)
    {   
        console.log(_baseUrl + 'components/users/roles/load-menus/' + _id + '/' + _user);
        var _menus = []; var checked = 'checked="checked"';
        _modal.find('#result').empty();
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/users/roles/load-menus/' + _id + '/' + _user,
            success: function(response) {
                console.log(response.data);
                _menus = response.data;
                var _append  = '<div class="accordion accordion-flush" id="accountPanel">';
                    _append += '<div class="row mb-2 mt-3">';
                    _append += '<div class="col-sm-6 text-end">';
                    _append += '<a href="javascript:;" class="check-accounts" value="checkall">[ <span>Check All</span> ]</a>';
                    _append += '</div>';
                    _append += '<div class="col-sm-6 text-start">';
                    _append += '<a href="javascript:;" class="check-accounts" value="uncheckall">[ <span>Uncheck All</span> ]</a>';
                    _append += '</div>';
                    _append += '</div>';                    
                    _append += '<div class="row">';
                $.each(_menus.group, function(i, _group) {
                    _append += '<div class="col-sm-6">';
                    _append += '<div class="accordion-item">';
                    _append += '<h4 class="accordion-header">';
                    _append += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-' + _group.id + '" aria-expanded="false" aria-controls="flush-collapseOne">';
                    if (_group.is_selected > 0) {
                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="group[]" value="' + _group.id + '" ' + checked + '>' + _group.name;
                    } else {
                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="group[]" value="' + _group.id + '">' + _group.name;
                    }
                    _append += '</button>';
                    _append += '</h4>';
                    _append += '<div id="group-' + _group.id + '" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accountPanel">';
                    _append += '<div class="accordion-body">';
                    _append += '<div class="mb-2">';
                    var _grpPermission = _group.permissions.split(',');
                    $.each(response.permissions, function(i, _permission) {
                        _append += '<div class="form-check form-check-inline">';
                        if (_grpPermission.indexOf(_permission.code) > -1) {
                            _append += '<input class="form-check-input" type="checkbox" attr="' + _permission.code + '" name="group_permission[' + _group.id + '][]" value="' + _permission.code + '" ' + checked + '>';
                        } else {
                            _append += '<input class="form-check-input" type="checkbox" attr="' + _permission.code + '" name="group_permission[' + _group.id + '][]" value="' + _permission.code + '">';
                        }    
                        _append += '<label class="form-check-label">';
                        _append += _permission.name;
                        _append += '</label>';
                        _append += '</div>';
                    });
                    _append += '</div>';
                    if (_menus.modules[_group.id] !== 'undefined') {
                        _append += '<ul class="modules">';
                        $.each(_menus.modules[_group.id], function(i, _module) {
                            _append += '<li>';
                            _append += '<h6>';
                            if (_module.is_selected > 0) {
                                _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="module[]" value="' + _module.id + '" ' + checked + '>';
                            } else {
                                _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="module[]" value="' + _module.id + '">';
                            }
                            _append += _module.name;
                            _append += '</h6>';
                            _append += '<div class="mb-2 mt-2 permission-layer d-flex flex-wrap">';
                            _append += '<div class="module-' + _module.id + ' permissions">';
                            var _modPermission = _module.permissions.split(',');
                            $.each(response.permissions, function(i, _permission) {
                                _append += '<div class="form-check form-check-inline">';
                                if (_modPermission.indexOf(_permission.code) > -1) {
                                    _append += '<input class="form-check-input" type="checkbox" code="' + _permission.code + '" name="module_permission[' + _module.id + '][]" value="' + _permission.code + '" ' + checked + '>';
                                } else {
                                    _append += '<input class="form-check-input" type="checkbox" code="' + _permission.code + '" name="module_permission[' + _module.id + '][]" value="' + _permission.code + '">';
                                }
                                _append += '<label class="form-check-label">';
                                _append += _permission.name;
                                _append += '</label>';
                                _append += '</div>';
                            });
                            _append += '</div>';
                            _append += '</div>';
                            _append += '</li>';

                            if (_menus.sub_modules[_module.id] !== 'undefined') {
                                _append += '<ul class="module-' + _module.id + ' sub-modules">';
                                $.each(_menus.sub_modules[_module.id], function(i, _sub_module) {
                                    _append += '<li>';
                                    _append += '<h6>';
                                    if (_sub_module.is_selected > 0) {
                                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="sub_module[]" value="' + _sub_module.id + '" ' + checked + '>';
                                    } else {
                                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="sub_module[]" value="' + _sub_module.id + '">';
                                    }
                                    _append += _sub_module.name;
                                    _append += '</h6>';
                                    _append += '<div class="mb-2 mt-2 permission-layer d-flex flex-wrap">';
                                    _append += '<div class="sub-module-' + _sub_module.id + ' permissions">';
                                    var _subPermission = _sub_module.permissions.split(',');
                                    $.each(response.permissions, function(i, _permission) {
                                        _append += '<div class="form-check form-check-inline">';
                                        if (_subPermission.indexOf(_permission.code) > -1) {
                                            _append += '<input class="form-check-input" type="checkbox" name="sub_module_permission[' + _sub_module.id + '][]" value="' + _permission.code + '" ' + checked + '>';
                                        } else {
                                            _append += '<input class="form-check-input" type="checkbox" name="sub_module_permission[' + _sub_module.id + '][]" value="' + _permission.code + '">';
                                        }
                                        _append += '<label class="form-check-label">';
                                        _append += _permission.name;
                                        _append += '</label>';
                                        _append += '</div>';
                                    });
                                    _append += '</div>';
                                    _append += '</div>';
                                    _append += '</li>';
                                });
                                _append += '</ul>';
                            }
                        });
                        _append += '</ul>';
                    }
                    _append += '</div>';
                    _append += '</div>';
                    _append += '</div>';
                    _append += '</div>';
                }); 
                _append += '</div>';
                _append += '</div>';

                _modal.find('#result').html(_append);
                _modal.find('#result-layer').removeClass('hidden');
            },
            async: false
        });
        return _menus;
    },

    user_account.prototype.load_dash_menus = function(_id = 0, _user = 0, _modal)
    {   
        console.log(_baseUrl + 'components/users/roles/load-menus/' + _id + '/' + _user);
        var _menus = []; var checked = 'checked="checked"';
        _modal.find('#resultDash').empty();
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/users/roles/load-menus-dash/' + _id + '/' + _user,
            success: function(response) {
                console.log(response.data);
                _menus = response.data;
                var _append  = '<div class="accordion accordion-flush" id="accountPanel">';
                    _append += '<div class="row mb-2 mt-3">';
                    _append += '<div class="col-sm-6 text-end">';
                    _append += '<a href="javascript:;" class="check-accounts" value="checkall">[ <span>Check All</span> ]</a>';
                    _append += '</div>';
                    _append += '<div class="col-sm-6 text-start">';
                    _append += '<a href="javascript:;" class="check-accounts" value="uncheckall">[ <span>Uncheck All</span> ]</a>';
                    _append += '</div>';
                    _append += '</div>';                    
                    _append += '<div class="row">';
                $.each(_menus.group, function(i, _group) {
                    _append += '<div class="col-sm-6">';
                    _append += '<div class="accordion-item">';
                    _append += '<h4 class="accordion-header">';
                    _append += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#group-' + _group.id + '" aria-expanded="false" aria-controls="flush-collapseOne">';
                    if (_group.is_selected > 0) {
                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="group[]" value="' + _group.id + '" ' + checked + '>' + _group.name;
                    } else {
                        _append += '<input class="form-check-input me-2" type="checkbox" category="all" name="group[]" value="' + _group.id + '">' + _group.name;
                    }
                    _append += '</button>';
                    _append += '</h4>';
                    _append += '<div id="group-' + _group.id + '" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accountPanel">';
                    _append += '<div class="accordion-body">';
                    _append += '<div class="mb-2">';
                    var _grpPermission = _group.is_per_selected.split(',').map(item => item.trim());

                    $.each(_group.permissions, function(i, _permission) {
                      console.log("Permission ID:", _permission.id);
                      console.log("GRP Permissions:", _grpPermission);
                    
                      _append += '<div class="form-check form-check-inline">';
                      
                      if (_grpPermission.includes(String(_permission.id))) { // Convert _permission.id to a string
                        _append += '<input class="form-check-input" type="checkbox" attr="' + _permission.id + '" name="group_permission[' + _group.id + '][]" value="' + _permission.id + '" checked>';
                      } else {
                        _append += '<input class="form-check-input" type="checkbox" attr="' + _permission.id + '" name="group_permission[' + _group.id + '][]" value="' + _permission.id + '">';
                      }    
                      
                      _append += '<label class="form-check-label">';
                      _append += _permission.menu_name;
                      _append += '</label>';
                      _append += '</div>';
                    });
                    
                    _append += '</div>';
                   
                    _append += '</div>';
                    _append += '</div>';
                    _append += '</div>';
                    _append += '</div>';
                }); 
                _append += '</div>';
                _append += '</div>';

                _modal.find('#resultDash').html(_append);
                _modal.find('#result-layer-dash').removeClass('hidden');
            },
            async: false
        });
        return _menus;
    },

    user_account.prototype.perfect_scrollbar = function()
    {
        if ($(".table-responsive")) {
            $.each($('.table-responsive'), function(_i = 0){
                _i++;
                $(this).attr('id', '_table' + _i);
                var _divID = '#' + $(this).attr('id');
                var px = new PerfectScrollbar(_divID, {
                    wheelSpeed: 0.5,
                    swipeEasing: 0,
                    wheelPropagation: 1,
                    minScrollbarLength: 40,
                });
            });
        }

    },

    user_account.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    user_account.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.user_account.preload_select3();
        $.user_account.load_contents();
        $.user_account.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.user_account.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#user-account-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage User Account');
            _modal.find('input[type="text"], input[type="password"], input[type="email"], textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select:not(.select3)').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('#result').empty().parents('#result-layer').addClass('hidden');
            _modal.find('#result').empty().parents('#result-layer-dash').addClass('hidden');
            _modal.find('input[name="password"], input[name="confirm_password"]').addClass('required').closest('.form-group').addClass('required');
            _modal.find('button.submit-btn').html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
            $.user_account.required_fields();
            _useraccountID = 0;
        });
        this.$body.on('shown.bs.modal', '#user-account-modal', function (e) {
            $.user_account.hideTooltip();
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#user-account-modal'); 
                _modal.modal('show');
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#userAccountTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#user-account-modal');
            var _url    = _baseUrl + 'components/users/accounts/edit/' + _id;
            console.log(_url);
            _useraccountID = _id;          

            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    _modal.find('input[name="password"], input[name="confirm_password"]').removeClass('required').closest('.form-group').removeClass('required');
                    $.user_account.required_fields();
                    var d1 = $.user_account.load_menus(response.data.user_role.role_id, response.data.id, _modal);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']:not(.select3)').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                           
                        });
                        if (response.data.hr_employee) {
                            _modal.find('select[name="employee_id"]').val(response.data.hr_employee.id).trigger('change.select3');
                        }
                        _modal.find('select[name="role_id"]').val(response.data.user_role.role_id).trigger('change.select3'); 
                    });
                    _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Account (<span class="variables">' + _code + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#userAccountTable .edit-dash-per-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#user-account-modal-dash');
            var _url    = _baseUrl + 'components/users/accounts/editDeshPermission/' + _id;
            console.log(_url);
            _useraccountID = _id;          

            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    _modal.find('input[name="password"], input[name="confirm_password"]').removeClass('required').closest('.form-group').removeClass('required');
                    $.user_account.required_fields();
                    var d1 = $.user_account.load_dash_menus(response.data.user_role.role_id, response.data.id, _modal);
                    $.when( d1 ).done(function ( v1 ) 
                    {   
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']:not(.select3)').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                           
                        });
                        if (response.data.hr_employee) {
                            _modal.find('select[name="employee_id"]').val(response.data.hr_employee.id).trigger('change.select3');
                        }
                        _modal.find('select[name="role_id"]').val(response.data.user_role.role_id).trigger('change.select3'); 
                    });
                    _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Dashboard Permissions');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        });


        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#userAccountTable .remove-btn, #userAccountTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/users/accounts/remove/' + _id : _baseUrl + 'components/users/accounts/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the account with code ("+ _code +") will be removed." : "Are you sure? <br/>the account with code ("+ _code +") will be restored.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: (_status == 'Active') ? "Yes, remove it!" : "Yes, restore it",
                cancelButtonText: "No, return",
                customClass: { confirmButton: (_status == 'Active') ? "btn btn-danger" : "btn btn-info", cancelButton: "btn btn-active-light" },
            }).then(function (t) {
                t.value
                    ? 
                    $.ajax({
                        type: 'PUT',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } })
                            .then(
                                function (e) {
                                    e.isConfirmed && ((t.disabled = !1));
                                    $.user_account.load_contents();
                                }
                            );
                        },
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                        }
                    })
                    : "cancel" === t.dismiss 
            });
            
        }); 


        /*
        | ---------------------------------
        | # when check all / uncheck all is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.check-accounts', function (e){
            var _self  = $(this);
            var _modal = _self.closest('.modal');

            if (_self.attr('value') == 'checkall') {
                _modal.find('.accordion input[type="checkbox"]').prop('checked', true);
            } else {
                _modal.find('.accordion input[type="checkbox"]').prop('checked', false);
            }
        });

        /*
        | ---------------------------------
        | # when group check all is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'input[type="checkbox"][name="group[]"][category="all"]', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('.accordion-item');

            if (_self.is(':checked')) {
                _parent.find('.accordion-body input[type="checkbox"]').prop('checked', true);
            } else {
                _parent.find('.accordion-body input[type="checkbox"]').prop('checked', false);
            }
        });

        /*
        | ---------------------------------
        | # when module check all is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'input[type="checkbox"][name="module[]"][category="all"]', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('ul.modules');

            if (_self.is(':checked')) {
                _parent.find('div.permissions.module-' + _self.val() + ' input[type="checkbox"]').prop('checked', true);
                _parent.find('ul.sub-modules.module-' + _self.val() + ' input[type="checkbox"]').prop('checked', true);
            } else {
                _parent.find('div.permissions.module-' + _self.val() + ' input[type="checkbox"]').prop('checked', false);
                _parent.find('ul.sub-modules.module-' + _self.val() + ' input[type="checkbox"]').prop('checked', false);
            }
        });

        /*
        | ---------------------------------
        | # when module check all is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'input[type="checkbox"][name="sub_module[]"][category="all"]', function (e) {
            var _self   = $(this);
            var _parent = _self.closest('ul.sub-modules');
            
            if (_self.is(':checked')) {
                _parent.find('div.permissions.sub-module-' + _self.val() + ' input[type="checkbox"]').prop('checked', true);
            } else {
                _parent.find('div.permissions.sub-module-' + _self.val() + ' input[type="checkbox"]').prop('checked', false);
            }
        });
    }

    //init user_account
    $.user_account = new user_account, $.user_account.Constructor = user_account

}(window.jQuery),

//initializing user_account
function($) {
    "use strict";
    $.user_account.required_fields();
    $.user_account.init();
}(window.jQuery);