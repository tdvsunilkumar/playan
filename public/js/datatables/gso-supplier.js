!function($) {
    "use strict";

    var supplier = function() {
        this.$body = $("body");
    };

    var _supplierID = 0, _contactID = 0, _table = '', _uploadTable = '', _contactTable = '', _supplierDropzone = '', _trackPage1 = 0, _trackPage2 = 0, _trackPage3 = 0;

    supplier.prototype.required_fields = function() {
        
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

    supplier.prototype.load_contents = function(_trackPage1 = 0) 
    {   
        _table = new DataTable('#supplierTable', {
            ajax: { 
                url : _baseUrl + 'general-services/setup-data/suppliers/lists',
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.supplier.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.supplier.hideTooltip();
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
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },
            initComplete: function(){
                this.api().page(_trackPage1).draw( 'page' );        
            }, 
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'branch_name' },
                { data: 'business_name' },
                { data: 'product_lines' },
                { data: 'contact_no' },
                { data: 'address' },
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
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-start sliced' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' }
            ]
        } );

        return true;
    },

    supplier.prototype.load_contact_contents = function(_trackPage2 = 0) 
    {   
        _contactTable = new DataTable('#supplierContactTable', {
            ajax: { 
                url : _baseUrl + 'general-services/setup-data/suppliers/contact-persons/lists/' + _supplierID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.supplier.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-contact-person">ADD CONTACT</button>');     
                this.api().page(_trackPage2).draw( 'page' );         
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                });    
            },      
            bDestroy: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 5,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.contact);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'contact_person' },
                { data: 'contact_tel_no' },
                { data: 'contact_mobile_no' },
                { data: 'contactemail' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-center' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' }
            ]
        } );

        return true;
    },

    supplier.prototype.load_file_contents = function(_trackPage3 = 0) 
    {   
        _uploadTable = new DataTable('#supplierUploadTable', {
            ajax: { 
                url : _baseUrl + 'general-services/setup-data/suppliers/upload-lists/' + _supplierID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.supplier.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });   
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 5,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-file', data.file);
            },
            initComplete: function(){
                this.api().page(_trackPage3).draw( 'page' );  
            }, 
            columns: [
                { data: 'filename' },
                { data: 'type' },
                { data: 'size' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start sliced' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' }
            ]
        } );

        return true;
    },

    supplier.prototype.generate_code = function()
    {   
        var _code = ''; 
        console.log(_baseUrl + 'general-services/setup-data/suppliers/generate-code');
        $.ajax({
            type: "GET",
            url: _baseUrl + 'general-services/setup-data/suppliers/generate-code',
            success: function(response) {
                console.log(_code = response.code);
            },
            async: false
        });
        return _code;
    },
    
    supplier.prototype.fetchID = function()
    {
        return _supplierID;
    }

    supplier.prototype.updateID = function(_id)
    {
        return _supplierID = _id;
    }

    supplier.prototype.fetchContactID = function()
    {
        return _contactID;
    }

    supplier.prototype.updateContactID = function(_id)
    {
        return _contactID = _id;
    }

    supplier.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    supplier.prototype.preload_select3 = function()
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

    supplier.prototype.preload_selectpicker = function()
    {
        if ( $('.selectpicker') ) {
            $('.selectpicker').selectpicker();
        }
    }

    supplier.prototype.preload_dropzone = function()
    {
        Dropzone.autoDiscover = false;
        var accept = "jpeg,.jpg,.png,.gif,.doc,.docx,.pdf";

        _supplierDropzone = new Dropzone('#import-supplier-dropzone', { 
            acceptedFiles: accept,
            maxFilesize: 10,
            timeout: 0,
            headers: {
                'X-CSRF-TOKEN': _token
            },
            init: function () {
            this.on("processing", function(file) {
                if (_supplierID == 0) {
                    var _form = $('form[name="supplierForm"]');
                    $.supplierForm.validate(_form, 0);
                    Swal.fire({
                        title: "Oops...",
                        html: "Something went wrong!<br/>Please fill in the required fields first.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                } else {
                    this.options.url = _baseUrl + 'general-services/setup-data/suppliers/upload/' + _supplierID + '?category=suppliers';
                    console.log(this.options.url);
                }
            }).on("queuecomplete", function (file, response) {
                // console.log(response);
            }).on("success", function (file, response) {
                console.log(response);
                var data = $.parseJSON(response);
                if (data.message == 'success') {
                    $.supplier.load_file_contents();
                }
            }).on("totaluploadprogress", function (progress) {
                var progressElement = $("[data-dz-uploadprogress]");
                progressElement.width(progress + '%');
                progressElement.find('.progress-text').text(progress + '%');
            });
            this.on('resetFiles', function() {
                this.removeAllFiles();
            });
            this.on("error", function(file){if (!file.accepted) this.removeFile(file);});            
            }
        });
    }

    supplier.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    supplier.prototype.updateFirst = function(_modal, _form, _method, _action, _button)
    {
        _button.prop('disabled', true).html('WAIT.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
        $.ajax({
            type: _method,
            url: _action,
            data: _form.serialize(),
            success: function(response) {
                console.log(response);
                if (response.type == 'success') {
                    setTimeout(function () {
                        _button.html('ADD CONTACT').prop('disabled', false);
                        _modal.find('input[name="code"]').val(response.data.code);
                        $.supplier.updateID(response.data.id);
                        $.supplier.load_contact_contents();
                        $.supplier.load_file_contents();
                    }, 500 + 300 * (Math.random() * 5));
                } else {
                    _button.html('ADD CONTACT').prop('disabled', false);
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

        return true;
    },

    supplier.prototype.select4Ajax = function (id, parentId, Url, length = 0) 
    {
        $("#"+id).select3({
            allowClear: true,
            dropdownAutoWidth : false,
            dropdownParent: $("#"+parentId),
            minimumInputLength: length,
            ajax: {
                url: _baseUrl + Url,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params,val) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 20) < data.data_cnt
                        }
                    };
                },
                cache: true
            }
        }).val($("#"+id).val()).trigger('change');
    }, 

    supplier.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.supplier.preload_select3();
        $.supplier.preload_selectpicker();
        $.supplier.preload_dropzone();
        $.supplier.load_contents();
        $.supplier.select4Ajax("barangay_id","divBarngay","getBarngayList");

        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide')
        });
        var _modalx = new bootstrap.Modal($('#supplier-modal'), {
            backdrop: 'static',
            keyboard: false
        });
        
        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#supplier-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Supplier');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('#contactTable tbody').empty().append('<tr class="empty"><td colspan="7" class="text-center">There\'s no contact details available.</td></tr>');
            _modal.find('select.selectpicker').selectpicker('deselectAll');
            _supplierDropzone.removeAllFiles( true );
            $.supplier.load_contents(_table.page());
            $.supplier.hideTooltip();
            _supplierID = 0;
        });
        this.$body.on('shown.bs.modal', '#supplier-modal', function (e) {
            $.supplier.hideTooltip();
            $('.add-btn').prop('disabled', false);
        });
        
        /*
        | ---------------------------------
        | # when supplier contact person modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#supplier-contact-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Contact Person');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.supplier.load_contact_contents();
            _contactID = 0;
        });

        /*
        | ---------------------------------
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            e.preventDefault();
            var _self = $(this);
            var _modals = $('#supplier-modal');

            _self.prop('disabled', true);
            var d1 = $.supplier.generate_code();
            var d2 = $.supplier.load_contact_contents();
            var d3 = $.supplier.load_file_contents();
            $.when( d1, d2, d3 ).done(function (v1, v2, v3) { 
                _modals.find('input[name="code"]').val(v1);
                _modalx.show();
            });
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-contact-person', function (e) {
            e.preventDefault();
            var _self   = $(this);
            var _fModal = $('#supplier-contact-modal');
            var _modal  = _self.closest('.modal');
            var _form   = _modal.find('form[name="supplierForm"]');
            var _id      = $.supplier.fetchID();
            var _method = (_id > 0) ? 'PUT' : 'POST';
            var _action = (_id > 0) ? _form.attr('action') + '/update/' + _id + '?address=' + _form.find("#barangay_id option:selected").text() : _form.attr('action') + '/store?address=' + _form.find("#barangay_id option:selected").text();
            var _error  = $.supplierForm.validate(_form, 0);

            if (_id == 0) {
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
                    var d1 = $.supplier.updateFirst(_modal, _form, _method, _action, _self);
                    $.when( d1 ).done(function (v1) { 
                        _fModal.modal('show');
                    });
                }
            } else {
                _self.prop('disabled', true).html('WAIT.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right');
                setTimeout(function () {
                    _self.html('ADD CONTACT').prop('disabled', false);
                    _fModal.modal('show');
                }, 500 + 300 * (Math.random() * 5));                
            }
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#supplier-modal');
            var _url    = _baseUrl + 'general-services/setup-data/suppliers/edit/' + _id;
            console.log(_url);
            _supplierID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);

                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        if(k=='barangay_id'){
                            $("#"+k).html(v)
                            $.supplier.select4Ajax("p_barangay_id_no","accordionFlushExample","getBarngayList");
                        }
                    });
                    if (response.data.vat_type == 'Vatable') {
                        _modal.find('select[name="evat_id"].select3').val(2).trigger('change.select3'); 
                    } else {
                        _modal.find('select[name="evat_id"].select3').val(1).trigger('change.select3'); 
                    }
                    _modal.find('select.selectpicker').val(response.lines).selectpicker('refresh');
                    $.supplier.load_contact_contents();
                    $.supplier.load_file_contents();
                    _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Supplier (<span class="variables">' + _code + '</span>)');
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
        this.$body.on('click', '#supplierTable .remove-btn, #supplierTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'general-services/setup-data/suppliers/remove/' + _id : _baseUrl + 'general-services/setup-data/suppliers/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the supplier with code ("+ _code +") will be removed." : "Are you sure? <br/>the supplier with code ("+ _code +") will be restored.",
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
                                    $.supplier.load_contents(_table.page());
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
        | # when download button is click
        | ---------------------------------
        */
        this.$body.on('click', '#supplierUploadTable .download-btn', function (e) {
            e.preventDefault();
            var _self     = $(this);
            var _row      = _self.closest('tr');
            var _file     = _row.attr('data-row-file');
            var _url = _baseUrl + 'general-services/setup-data/suppliers/download/' + _supplierID + '?category=suppliers&file=' + _file;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when delete button is click
        | ---------------------------------
        */
        this.$body.on('click', '#supplierUploadTable .remove-btn', function (e) {
            e.preventDefault();
            var _self     = $(this);
            var _row      = _self.closest('tr');
            var _id       = _row.attr('data-row-id');
            var _file     = _row.attr('data-row-file');
            var _url      = _baseUrl + 'general-services/setup-data/suppliers/delete/' + _supplierID + '?category=suppliers&id=' + _id + '&file=' + _file;
   
            console.log(_url);
            Swal.fire({
                html: "Are you sure? <br/>the file ("+ _file +") will be deleted.",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, return",
                customClass: { confirmButton: "btn btn-danger", 
                cancelButton: "btn btn-active-light" },
            }).then(function (t) {
                t.value
                    ? 
                    $.ajax({
                        type: 'DELETE',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                function (e) {
                                    e.isConfirmed && ((t.disabled = !1));
                                    $.supplier.load_file_contents();
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierContactTable .remove-btn, #supplierContactTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'general-services/setup-data/suppliers/contact-persons/remove/' + _id : _baseUrl + 'general-services/setup-data/suppliers/contact-persons/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the contact person ("+ _code +") will be removed." : "Are you sure? <br/>the contact person ("+ _code +") will be restored.",
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
                                    $.supplier.load_contact_contents(_contactTable.page());
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
        | # when contact person edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierContactTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = _self.closest('tr').attr('data-row-id');
            var _code   = _self.closest('tr').attr('data-row-code');
            var _modal  = $('#supplier-contact-modal');
            var _url    = _baseUrl + 'general-services/setup-data/suppliers/contact-persons/edit/' + _id;
            console.log(_url);
            _contactID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    _self.prop('disabled', false).attr('title', 'edit this').html('<i class="ti-pencil text-white"></i>');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Contact Person (<span class="variables">' + _code + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 
    }

    //init supplier
    $.supplier = new supplier, $.supplier.Constructor = supplier

}(window.jQuery),

//initializing supplier
function($) {
    "use strict";
    $.supplier.required_fields();
    $.supplier.init();
}(window.jQuery);