!function($) {
    "use strict";

    var user_role = function() {
        this.$body = $("body");
    };

    var _userRoleID = 0;

    user_role.prototype.required_fields = function() {
        
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'], input[type='text'], select, textarea");
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

    user_role.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#userRoleTable', {
            ajax: { 
                url : _baseUrl + 'components/users/roles/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    if (_complete == 0 && _keywords != '') {
                        $('#datatable-2 input[type="search"]').val(_keywords).focus();
                        _complete = 1;
                    }
                    $.user_role.shorten();
                }
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'name' },
                { data: 'description' },
                { data: 'modified' },
                { data: 'status' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, visible: false, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
            ]
        } );

        return true;
    },

    user_role.prototype.fetchID = function()
    {
        return _userRoleID;
    }

    user_role.prototype.updateID = function(_id)
    {
        return _userRoleID = _id;
    }

    user_role.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    user_role.prototype.preload_select3 = function()
    {
        if ( $('.select3') ) {
            $('.select3').select3({
                allowClear: true,
                dropdownAutoWidth : false,dropdownParent: $('.modal.form .modal-body')
            });
        }
    },

    user_role.prototype.load_menus = function(_id = 0, _modal)
    {   
        var _menus = []; var checked = 'checked="checked"';
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'components/users/roles/load-menus/' + _id + '/0',
            success: function(response) {
                console.log(response.data);
                _menus = response.data;
                var _append  = '<div class="accordion accordion-flush" id="rolePanel">';
                    _append += '<div class="row mb-2 mt-3">';
                    _append += '<div class="col-sm-6 text-end">';
                    _append += '<a href="javascript:;" class="check-roles" value="checkall">[ <span>Check All</span> ]</a>';
                    _append += '</div>';
                    _append += '<div class="col-sm-6 text-start">';
                    _append += '<a href="javascript:;" class="check-roles" value="uncheckall">[ <span>Uncheck All</span> ]</a>';
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
                    _append += '<div id="group-' + _group.id + '" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#rolePanel">';
                    _append += '<div class="accordion-body">';
                    _append += '<div class="mb-2">';
                    $.each(response.permissions, function(i, _permission) {
                        _append += '<div class="form-check form-check-inline">';
                        if (_group.permissions.includes(_permission.code) > 0) {
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
                            $.each(response.permissions, function(i, _permission) {
                                _append += '<div class="form-check form-check-inline">';
                                if (_module.permissions.includes(_permission.code) > 0) {
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
                                    $.each(response.permissions, function(i, _permission) {
                                        _append += '<div class="form-check form-check-inline">';
                                        if (_sub_module.permissions.includes(_permission.code) > 0) {
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
            },
            async: false
        });
        return _menus;
    },

    user_role.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.user_role.preload_select3();
        $.user_role.load_contents();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.user_role.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#user-role-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Role');
            _modal.find('input[type="text"], textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change');
            _modal.find('#result').empty();
            _modal.find('button.submit-btn').html('<i class="la la-save"></i> Save Changes').prop('disabled', false);
            _userRoleID = 0;
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#user-role-modal');

            var d1 = $.user_role.load_menus(0, _modal);
            $.when( d1 ).done(function ( v1 ) 
            {   
                _modal.modal('show');
            });
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#userRoleTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#user-role-modal');
            var _url    = _baseUrl + 'components/users/roles/edit/' + _id;
            console.log(_url);
            _userRoleID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.user_role.load_menus(_userRoleID, _modal);
            $.when( d1 ).done(function ( v1 ) 
            {   
                $.ajax({
                    type: 'GET',
                    url: _url,
                    success: function(response) {
                        console.log(response);
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                        });
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                        _modal.find('.m-form__help').text('');
                        _modal.find('.modal-header h5').html('Edit Role (<span class="variables">' + _code + '</span>)');
                        _modal.modal('show');
                    },
                    complete: function() {
                        window.onkeydown = null;
                        window.onfocus = null;
                    }
                });
            });
        }); 

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#userRoleTable .remove-btn, #userRoleTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/users/roles/remove/' + _id : _baseUrl + 'components/users/roles/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the role with code ("+ _code +") will be removed." : "Are you sure? <br/>the role with code ("+ _code +") will be restored.",
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
                                    $.user_role.load_contents();
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
        this.$body.on('click', '.check-roles', function (e){
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

    //init user_role
    $.user_role = new user_role, $.user_role.Constructor = user_role

}(window.jQuery),

//initializing user_role
function($) {
    "use strict";
    $.user_role.required_fields();
    $.user_role.init();
}(window.jQuery);