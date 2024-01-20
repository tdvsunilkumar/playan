!function($) {
    "use strict";

    var bac_rfq = function() {
        this.$body = $("body");
    };

    var _rfqID = 0; var _table; var _prTable;  var _supplierTable; var _itemTable; var _projectName = ''; var _supplierStatus = ''; var _supplierID = 0;

    bac_rfq.prototype.required_fields = function() {
        
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

    bac_rfq.prototype.load_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/lists'); 
        _table = new DataTable('#rfqTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token,
                },
                complete: function() {
                    $.bac_rfq.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
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
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.control_no);
                $(row).attr('data-row-status', data.status);
            },
            columns: [
                { data: 'id' },
                { data: 'control_no_label' },
                { data: 'fund_code' },
                { data: 'purchase_type' },
                { data: 'project_name' },
                { data: 'agencies' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_rfq.prototype.load_pr_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/pr-lists/' + _rfqID); 
        _prTable = new DataTable('#prTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/pr-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_rfq.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            dom: 'l<"toolbar-1">frtip',
            initComplete: function(){
                if(_prTable.rows().data().length > 0) {
                    $('form[name="rfqForm"] select[name="fund_code_id"]').prop('disabled', true);
                } else {
                    $('form[name="rfqForm"] select[name="fund_code_id"]').prop('disabled', false);
                }
                $("div.toolbar-1").html('<button type="button" class="btn btn-small bg-info" id="add-pr">ADD LINE</button>');           
            },      
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.pr_no);
            },
            columns: [
                { data: 'pr_no' },
                { data: 'department' },
                { data: 'rfq_no' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_rfq.prototype.load_supplier_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/supplier-lists/' + _rfqID); 
        _supplierTable = new DataTable('#supplierTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/supplier-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_rfq.shorten();
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            dom: 'l<"toolbar-2">frtip',
            initComplete: function(){
                $("div.toolbar-2").html('<button type="button" class="btn btn-small bg-info" id="add-supplier">ADD LINE</button>');           
            }, 
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.branch);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-supplier', data.supplier_id);
            },
            columns: [
                { data: 'supplier' },
                { data: 'total_canvass' },
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
                {  orderable: false, targets: 3, className: 'text-center' },
            ]
        } );

        return true;
    },

    bac_rfq.prototype.load_item_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'general-services/bac/request-for-quotations/item-lists/' + _rfqID); 
        _itemTable = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'general-services/bac/request-for-quotations/item-lists/' + _rfqID,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.bac_rfq.shorten();
                }
            }, 
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>"
            },
            bDestroy: true,
            pageLength: 10,
            // lengthMenu: [
            //     [5, 10, 25, 50, -1],
            //     [5, 10, 25, 50, 'All'],
            // ],
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            dom: 'l<"toolbar-3 pull-right">frtip',
            initComplete: function(){
                $("div.toolbar-3").html('<button type="button" class="btn btn-small bg-info" id="preview-btn">PREVIEW</button>');   
            }, 
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.pr_no);
            },
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'description' },
                { data: 'quantity' },
                { data: 'uom' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' }
            ]
        } );

        return true;
    },

    bac_rfq.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    bac_rfq.prototype.fetchID = function()
    {
        return _rfqID;
    }

    bac_rfq.prototype.updateID = function(_id)
    {
        return _rfqID = _id;
    }

    bac_rfq.prototype.fetchProjectName = function()
    {
        return _projectName;
    }

    bac_rfq.prototype.updateProjectName = function(_proj)
    {
        return _projectName = _proj;
    }

    bac_rfq.prototype.fetchSupplierStatus = function()
    {
        return _supplierStatus;
    }

    bac_rfq.prototype.fetchSupplierID = function()
    {
        return _supplierID;
    }

    bac_rfq.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    bac_rfq.prototype.preload_select3 = function()
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

    bac_rfq.prototype.perfect_scrollbar = function()
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

    bac_rfq.prototype.validate_table = function($table)
    {   
        $('.pager').remove();
        $table.each(function() {
            var currentPage = 0;
            var numPerPage = 8;
            var $table = $(this);
            $table.bind('repaginate', function() {
                $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
            });
            $table.trigger('repaginate');
            var numRows = $table.find('tbody tr').length;
            var numPages = Math.ceil(numRows / numPerPage);
            var $pager = $('<div class="pager d-flex pagination justify-content-center"></div>');
            for (var page = 0; page < Math.min(numPages,5); page++) {
                $('<span class="page-number page-item"></span>').text(page + 1).bind('click', {
                    newPage: page
                }, function(event) {
                    currentPage = event.data['newPage'];
                    $table.trigger('repaginate');
                    $(this).addClass('active').siblings().removeClass('active');
                }).appendTo($pager).addClass('clickable');
            }
            $pager.insertAfter($table).find('span.page-number:first').addClass('active');
        });
    },

    /*
    | ---------------------------------
    | # price separator
    | ---------------------------------
    */
    bac_rfq.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    bac_rfq.prototype.edit_supplier = function(_supplier, _url, _modal, _code, _button, _status)
    {   
        var _table = _modal.find('table'); _table.find('tbody').empty();
        var _rows  = ''; _modal.find('h5.modal-title').text('Item Quotation ('+_code+')');
        console.log(_url);
        $.ajax({
            type: 'GET',
            url: _url,
            success: function(response) {
                console.log(response);
                var _totalCost = 0;
                if (response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        _rows += '<tr data-row-supplier="' + _supplier + '" data-row-item="' + row.item_id + '" data-row-quantity="' + row.item_quantity + '">';
                        _rows += '<td>' + (row.item_code ? row.item_code : '') + '</td>';
                        _rows += '<td class="sliced">' + (row.item_description ? row.item_description : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_quantity ? row.item_quantity : '') + '</td>';
                        _rows += '<td class="text-center">' + (row.item_uom ? row.item_uom : '') + '</td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid" name="brand" type="text" value="' + row.brand + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="unit_cost" type="text" value="' + row.unit_cost + '"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid numeric-double text-center" name="total_cost" type="text" value="' + row.total_cost + '" disabled="disabled"><span class="m-form__help text-danger"></span></td>';
                        _rows += '<td class="text-center"><input class="form-control form-control-solid" name="remarks" type="text" value="' + row.remarks + '""><span class="m-form__help text-danger"></span></td>';
                        _rows += '</tr>';
                        if (parseFloat(row.total_cost) > 0) { 
                            _totalCost += parseFloat(row.total_cost);
                        }
                    });                    
                    _table.find('tbody').append(_rows);
                    
                    _table.find('tfoot th.text-danger').text($.bac_rfq.price_separator(parseFloat(_totalCost).toFixed(2)));
                    if (_status !== 'pending') {
                        _button.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                        _modal.find('button.submit-btn').addClass('hidden');
                        _modal.find('button.save-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
                        _modal.find('input').prop('disabled', true);
                    } else {
                        _button.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    }
                    $.each(response.canvass[0], function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                    });
                    $.bac_rfq.shorten();
                    _modal.modal('show'); 
                }
            },
            async: false,
            complete: function() {
                window.onkeydown = null;
                window.onfocus = null;
            }
        });
    },

    bac_rfq.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.bac_rfq.preload_select3();
        $.bac_rfq.load_contents();
        $.bac_rfq.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide')
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Request For Quotation');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('label[for="control_no"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('label[for="total_budget"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('input, select, textarea').prop('disabled', false);
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            $.bac_rfq.load_contents();
            $.bac_rfq.hideTooltip();
            _rfqID = 0;
        });
        this.$body.on('shown.bs.modal', '#rfq-modal', function (e) {
            var _modal = $(this);
            $.bac_rfq.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when canvass modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#canvass-modal', function (e) {
            var _modal = $(this);
            _supplierStatus = ''; _supplierID = 0;
            _modal.find('input').prop('disabled', false);
            _modal.find('button.print-btn').addClass('hidden');
            _modal.find('button.submit-btn, button.save-btn').removeClass('hidden');
            $.bac_rfq.load_supplier_contents();
        });


        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#rfq-modal');
            var d1 = $.bac_rfq.load_pr_contents();
            var d2 = $.bac_rfq.load_supplier_contents();
            var d3 = $.bac_rfq.load_item_contents();
            $.when( d1, d2, d3 ).done(function ( v1, v2, v3 ) 
            { 
                _modal.modal('show');
            });
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $('#rfq-modal');
            var _url    = _baseUrl + 'general-services/bac/request-for-quotations/edit/' + _id;
            console.log(_url);
            _rfqID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    _modal.find('label[for="control_no"].text-danger').text(response.data.control_no);
                    _projectName = response.data.project_name;
                    if (response.data.total_budget) {
                        _modal.find('label[for="total_budget"].text-danger').text('₱' + $.bac_rfq.price_separator(parseFloat(response.data.total_budget).toFixed(2)));
                    }
                    $.each(response.data, function (k, v) {
                        _modal.find('input[name='+k+']').val(v);
                        _modal.find('textarea[name='+k+']').val(v);
                        _modal.find('select[name='+k+']').val(v);
                        _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                    });
                    $.bac_rfq.load_pr_contents();
                    $.bac_rfq.load_supplier_contents();
                    $.bac_rfq.load_item_contents();
                    if(_status == 'draft') {
                        _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                    } else {
                        _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                    }
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Edit Request For Quotation (<span class="variables">' + _code + '</span>)');
                    if (_status != 'draft') {
                        _modal.find('input, select, textarea').prop('disabled', true);
                        _modal.find('button.send-btn').addClass('hidden');
                        _modal.find('button.print-btn').removeClass('hidden');
                    }
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
        | # when rfq print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .print-btn', function (e) {
            var _self   = $(this);
            var _rfqNo   = $(this).closest('tr').attr('data-row-code');
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/print/' + _rfqNo;
            window.open(_url, '_blank');
        }); 

        /*
        | ---------------------------------
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfqTable .remove-btn, #rfqTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _url    = (_status == 'Active') ? _baseUrl + 'components/menus/groups/remove/' + _id : _baseUrl + 'components/menus/groups/restore/' + _id;

            console.log(_url);
            Swal.fire({
                html: (_status == 'Active') ? "Are you sure? <br/>the group menu with code ("+ _code +") will be removed." : "Are you sure? <br/>the group menu with code ("+ _code +") will be restored.",
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
                                    $.bac_rfq.load_contents();
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
        | # when pr remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#prTable .delete-btn', function (e) {
            var id     = $(this).closest('tr').attr('data-row-id');
            var code   = $(this).closest('tr').attr('data-row-code');
            var urlz   = _baseUrl + 'general-services/bac/request-for-quotations/remove-pr/' + id + '?rfq=' + _rfqID;
            var d1 = $.bac_rfqForm.fetch_status(_rfqID);
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (v1 == 'draft') {
                    console.log(urlz);
                    Swal.fire({
                        html: "Are you sure? <br/>the purchase request with PR No. ("+ code +") will be removed.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, remove it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: urlz,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.bac_rfq.load_pr_contents();
                                            $.bac_rfq.load_item_contents();
                                            $('#rfq-modal').find('label[for="total_budget"].text-danger').text('₱' + $.bac_rfq.price_separator(parseFloat(response.total).toFixed(2)));
                                        }
                                    );
                                }
                            })
                            : "cancel" === t.dismiss 
                    });     
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
        | # when supplier remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierTable .delete-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _urlz   = _baseUrl + 'general-services/bac/request-for-quotations/remove-supplier/' + _id;

            var d1 = $.bac_rfqForm.fetch_status(_rfqID);
            $.when( d1 ).done(function ( v1 ) 
            {  
                console.log(_urlz);
                if (v1 == 'draft') {
                    Swal.fire({
                        html: "Are you sure? <br/>the supplier with branch name ("+ _code +") will be removed.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, remove it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: _urlz,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.bac_rfq.load_supplier_contents();
                                        }
                                    );
                                }
                            })
                            : "cancel" === t.dismiss 
                    });  
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
        | # when supplier edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierTable .edit-btn', function (e) {
            var _self     = $(this);
            var _supplier = _self.closest('tr').attr('data-row-supplier');
            var _code     = _self.closest('tr').attr('data-row-code');
            var _status   = _self.closest('tr').attr('data-row-status');
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/edit-supplier/' + _rfqID + '?supplier=' + _supplier;
            var _modal    = $('#canvass-modal');
            
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            var d1 = $.bac_rfqForm.fetch_status(_rfqID);
            _supplierStatus = _status;
            _supplierID = _supplier;
            $.when( d1 ).done(function ( v1 ) 
            {  
                // if (v1 == 'draft || ') {
                    setTimeout(function () {
                        $.bac_rfq.edit_supplier(_supplier, _url, _modal, _code, _self, _status);
                    }, 500 + 300 * (Math.random() * 5));
                // } else {
                //     Swal.fire({
                //         title: "Oops...",
                //         html: "Unable to proceed!<br/>The request is already been processed.",
                //         icon: "error",
                //         type: "danger",
                //         showCancelButton: false,
                //         closeOnConfirm: true,
                //         confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                //     });
                //     window.onkeydown = null;
                //     window.onfocus = null;    
                // }
            });
        }); 

        /*
        | ---------------------------------
        | # when supplier print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#supplierTable .print-btn', function (e) {
            var _self     = $(this);
            var _modal    = _self.closest('.modal');
            var _rfqNo    = _modal.find('label[for="control_no"].text-danger').text();
            var _supplier = _self.closest('tr').attr('data-row-supplier');
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/print/' + _rfqNo + '?supplier=' + _supplier;
            window.open(_url, '_blank');
        });

        /*
        | ---------------------------------
        | # when canvass print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#canvass-modal .print-btn', function (e) {
            var _self     = $(this);
            var _modal    = $('#rfq-modal');
            var _rfqNo    = _modal.find('label[for="control_no"].text-danger').text();
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/print/' + _rfqNo + '?supplier=' + _supplierID;
            window.open(_url, '_blank');
        });

        /*
        | ---------------------------------
        | # when rfq print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#rfq-modal button.print-btn', function (e) {
            var _self     = $(this);
            var _modal    = _self.closest('.modal');
            var _rfqNo    = _modal.find('label[for="control_no"].text-danger').text();
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/print/' + _rfqNo;
            window.open(_url, '_blank');
        });

        /*
        | ---------------------------------
        | # when preview button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#preview-btn', function (e) {
            var _self     = $(this);
            var _modal    = _self.closest('.modal');
            var _rfqNo    = _modal.find('label[for="control_no"].text-danger').text();
            var _url      = _baseUrl + 'general-services/bac/request-for-quotations/preview/' + _rfqNo;
            if(_itemTable.rows().data().length > 0) {
                window.open(_url, '_blank');
            }
        });
    }

    //init bac_rfq
    $.bac_rfq = new bac_rfq, $.bac_rfq.Constructor = bac_rfq

}(window.jQuery),

//initializing bac_rfq
function($) {
    "use strict";
    $.bac_rfq.required_fields();
    $.bac_rfq.init();
}(window.jQuery);