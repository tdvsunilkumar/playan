!function($) {
    "use strict";

    var item = function() {
        this.$body = $("body");
    };

    var _itemID = 0, _conversionID = 0, _table = '', _fileTable = '', _trackPageUpload = 1, sortBy = '', orderBy = ''; 
    var _conversionTable; var _conversionPage = 0;

    item.prototype.required_fields = function() {
        
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

    item.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'health-and-safety/setup-data/item-managements/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.item.shorten();
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
                $(row).attr('data-row-position', data.order);
            },
            columns: [
                { data: 'id' },
                { data: 'gl_account' },
                { data: 'type' },
                { data: 'category' },
                { data: 'code' },
                { data: 'name' },
                { data: 'quantity' },
                { data: 'uom' },
                { data: 'unit_cost' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' },
                {  orderable: false, targets: 11, className: 'text-center' },
            ]
        } );

        return true;
    },

    item.prototype.load_file_contents = function() 
    {   
        _fileTable = new DataTable('#itemUploadTable', {
            ajax: { 
                url : _baseUrl + 'health-and-safety/setup-data/item-managements/upload-lists/' + _itemID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.item.shorten();
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

    item.prototype.conversion_contents = function(_conversionPage = 0) 
    {   
        console.log(_baseUrl + 'health-and-safety/setup-data/item-managements/conversion-lists/' + _itemID);
        _conversionTable = new DataTable('#itemConversionTable', {
            ajax: { 
                url : _baseUrl + 'health-and-safety/setup-data/item-managements/conversion-lists/' + _itemID,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function() {
                    $.item.shorten(); 
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                this.api().page(_conversionPage).draw( 'page' );   
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-conversion-btn">ADD CONVERSION</button>');  
                if(_conversionTable.rows().data().length > 0) {
                    $('#item-modal select[name="uom_id"]').prop('disabled', true);
                } else {
                    $('#item-modal select[name="uom_id"]').prop('disabled', false);
                }
            }, 
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
            },     
            columns: [
                { data: 'id' },
                { data: 'based_qty' },
                { data: 'based_uom' },
                { data: 'conversion_qty'},
                { data: 'conversion_uom'},
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
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: false, targets: 5, className: 'text-center' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' }
            ]
        } );

        return true;
    },

    item.prototype.fetchID = function()
    {
        return _itemID;
    }

    item.prototype.updateID = function(_id)
    {
        return _itemID = _id;
    }

    item.prototype.fetchConversionID = function()
    {
        return _conversionID;
    }

    item.prototype.updateConversionID = function(_id)
    {
        return _conversionID = _id;
    }

    item.prototype.preload_select3 = function()
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

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    item.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    item.prototype.perfect_scrollbar = function()
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

    item.prototype.fetch_group_code = function(_id, _modal)
    {
        $.ajax({
            type: "GET",
            url: _baseUrl + 'health-and-safety/setup-data/item-managements/fetch-gl-via-item-category/' + _id,
            success: function(response) {
                console.log(response.data);
                _modal.find('select[name="gl_account_id"]').val(response.data);
                $.item.preload_select3();
            },
            async: false
        });
    },

    item.prototype.fetch_based_uom = function(_id, _modal)
    {   
        var _basedUom = 0;
        $.ajax({
            type: "GET",
            url: _baseUrl + 'health-and-safety/setup-data/item-managements/fetch-based-uom/' + _id,
            success: function(response) {
                console.log(response.data);
                _basedUom = response.data.uom_id;
            },
            async: false
        });
        return _basedUom;
    },

    item.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    item.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.item.preload_select3();
        $.item.load_contents();
        $.item.perfect_scrollbar();
        
        Dropzone.autoDiscover = false;
        var accept = "jpeg,.jpg,.png,.gif,.doc,.docx,.pdf";

        var itemDropzone = new Dropzone('#import-item-dropzone', { 
            acceptedFiles: accept,
            maxFilesize: 10,
            timeout: 0,
            headers: {
                'X-CSRF-TOKEN': _token
            },
            init: function () {
            this.on("processing", function(file) {
                this.options.url = _baseUrl + 'health-and-safety/setup-data/item-managements/upload/' + _itemID + '?category=items';
                console.log(this.options.url);
            }).on("queuecomplete", function (file, response) {
                // console.log(response);
            }).on("success", function (file, response) {
                console.log(response);
                var data = $.parseJSON(response);
                if (data.message == 'success') {
                    $.item.load_file_contents();
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

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Item');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('.upload-row').addClass('hidden');
            _modal.find('select[name="item_category_id"]').prop('disabled', false);
            _modal.find('.submit-btn').prop('disabled', false).removeClass('hidden');
            _modal.find('input:not([name="code"]), select:not([name="gl_account_id"]), textarea').prop('disabled', false);
            _modal.find('input[type="checkbox"]').prop('checked', false);
            itemDropzone.removeAllFiles( true );
            $.item.preload_select3();
            _itemID = 0;
        });
        /*
        | ---------------------------------
        | # when item conversion modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#item-conversion-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Item Conversion');
            _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            $.item.conversion_contents();
            _conversionID = 0;
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#item-modal');
            _modal.modal('show');
        });

        /*
        | ---------------------------------
        | # when add button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-conversion-btn', function (e) {
            var _self = $(this);
            var _modal = $('#item-conversion-modal');
            _self.prop('disabled', true).html('WAIT...');
            var d1 = $.item.fetch_based_uom(_itemID);
            $.when( d1 ).done(function ( v1 ) 
            {   
                _modal.find('input[name="based_quantity"]').val(1);
                _modal.find('select[name="based_uom"]').val(v1).trigger('change.select3'); 
                setTimeout(function () {    
                    _self.attr('disabled', false).html('ADD CONVERSION');
                    _modal.modal('show'); 
                }, 500 + 300 * (Math.random() * 5));
            });
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#item-modal');
            var _url    = _baseUrl + 'health-and-safety/setup-data/item-managements/edit/' + _id;
            console.log(_url);
            _itemID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[type="text"][name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        if (k == 'is_expirable') {
                            if (v > 0) {
                                _modal.find('input[type="checkbox"][name='+k+']').prop('checked', true);
                            } else {
                                _modal.find('input[type="checkbox"][name='+k+']').prop('checked', false);
                            }
                        }
                    });
                    _modal.find('select[name="item_category_id"]').prop('disabled', true);
                    if (!(response.data.category.is_health_safety > 0)) {
                        _modal.find('.submit-btn').prop('disabled', true).addClass('hidden');
                        _modal.find('input, select, textarea').prop('disabled', true);
                    }
                    $.item.load_file_contents();
                    $.item.conversion_contents();
                    _modal.find('.upload-row').removeClass('hidden');
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Item (<span class="variables">' + _code + '</span>)');
                    _self.prop('disabled', false).attr('title', 'Edit').html('<i class="ti-pencil text-white"></i>');
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
        this.$body.on('click', '#itemTable .remove-btn, #itemTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'health-and-safety/setup-data/item-managements/remove/' + _id : _baseUrl + 'health-and-safety/setup-data/item-managements/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the item with code ("+ _code +") will be removed." : "Are you sure? <br/>the item with code ("+ _code +") will be restored.",
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
                                    $.item.load_contents();
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
        | # when item category is changed
        | ---------------------------------
        */
        this.$body.on('change', '#item_category_id', function (e) {
            var _self = $(this);
            var _modal = _self.closest('.modal');
            $.item.fetch_group_code(_self.val(), _modal);
        });

        /*
        | ---------------------------------
        | # when download button is click
        | ---------------------------------
        */
        this.$body.on('click', '#itemUploadTable .download-btn', function (e) {
            e.preventDefault();
            var _self     = $(this);
            var _row      = _self.closest('tr');
            var _file     = _row.attr('data-row-file');
            var _url = _baseUrl + 'health-and-safety/setup-data/item-managements/download/' + _itemID + '?category=items&file=' + _file;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when delete button is click
        | ---------------------------------
        */
        this.$body.on('click', '#itemUploadTable .remove-btn', function (e) {
            e.preventDefault();
            var _self     = $(this);
            var _row      = _self.closest('tr');
            var _id       = _row.attr('data-row-id');
            var _file     = _row.attr('data-row-file');
            var _url      = _baseUrl + 'health-and-safety/setup-data/item-managements/delete/' + _itemID + '?category=items&id=' + _id + '&file=' + _file;
   
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
                                    $.item.load_file_contents();
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
        | # when conversion restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemConversionTable .remove-btn, #itemConversionTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'health-and-safety/setup-data/item-managements/remove-conversion/' + _id : _baseUrl + 'health-and-safety/setup-data/item-managements/restore-conversion/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the conversion with id ("+ _id +") will be removed." : "Are you sure? <br/>the conversion with id ("+ _id +") will be restored.",
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
                                    $.item.conversion_contents();
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
        | # when conversion edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#itemConversionTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $('#item-modal').find('input[name="code"]').val();
            var _modal  = $('#item-conversion-modal');
            var _url    = _baseUrl + 'health-and-safety/setup-data/item-managements/edit-conversion/' + _id;
            console.log(_url);
            _conversionID = _id;
            _self.prop('disabled', true).attr('title', 'Loading...').html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function (k, v) {
                        _modal.find('input[type="text"][name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Item Conversion (<span class="variables">' + _code + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 
    }

    //init item
    $.item = new item, $.item.Constructor = item

}(window.jQuery),

//initializing item
function($) {
    "use strict";
    $.item.required_fields();
    $.item.init();
}(window.jQuery);