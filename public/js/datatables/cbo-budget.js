!function($) {
    "use strict";

    var cbo_budget = function() {
        this.$body = $("body");
    };

    var _budgetID = 0; var _breakdownID = 0; var _budgetYear = ''; var _table; 
    var _breakdownTable; var _page = 0; var _YrList = '', _Yr = 'all'; var _list = [];
    var _categoryList = '', _category = 'all';

    cbo_budget.prototype.required_fields = function() {
        
        $('label').find('span.text-danger').remove();
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

    cbo_budget.prototype.load_years = function()
    {   
        _YrList = '<select name="year" class="form-select form-select-sm ms-1">';
        _categoryList = '<select name="category" class="form-select form-select-sm ms-1 select3">';
        $.ajax({
            type: 'GET',
            url: _baseUrl + 'finance/budget-proposal/year-lists',
            success: function(response) {
                console.log(response.data);
                $.each(response.data, function(i, item) {
                    _YrList += '<option value="' + item.budget_year + '"> ' + item.budget_year + '</option>';  
                }); 
                _YrList += '<option value="all">All</option>';  
                _YrList += '</select>';
                $.each(response.category, function(i, item) {
                    _categoryList += '<option value="' + item.id + '"> ' + item.code + '</option>';  
                }); 
                _categoryList += '<option value="all">ALL</option>';  
                _categoryList += '</select>';
            },
            async: false
        });
    },

    cbo_budget.prototype.load_contents = function(_page = 0) 
    {   
        console.log(_baseUrl + 'finance/budget-proposal/lists?year=' + _Yr);
        _table = new DataTable('#budgetTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-proposal/lists?year=' + _Yr,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function (data) {  
                    $.cbo_budget.shorten();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.cbo_budget.hideTooltip();
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
                $(row).attr('data-row-code', data.year);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-total', data.total_buget);
                $(row).attr('data-row-dep', data.dep);
            },
            dom: 'lf<"toolbar-2 float-end d-flex flex-row">rtip',
            initComplete: function(){
                this.api().page(_page).draw( 'page' );   
                $("div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Year: ' + _YrList + '</label>');           
                $('select[name="year"]').val(_Yr);
            },   
            columns: [
                { data: 'checkbox' },
                { data: 'year' },
                { data: 'department' },
                // { data: 'division' },
                { data: 'fundcode' },
                { data: 'total_buget' },
                { data: 'total_used' },
                { data: 'modified' },
                { data: 'status_label' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                // {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-left sliced' },
                {  orderable: true, targets: 4, className: 'text-end' },
                {  orderable: false, targets: 5, className: 'text-end' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
            ]
        } );

        return true;
    },

    cbo_budget.prototype.compute = function($table) {

        var _quarterly = 0, _annual = 0, _alignment = 0, _final = 0, _used = 0, _balance = 0;
        $.each($table.find('tbody tr'), function(){
            var _row = $(this);
            _quarterly += parseFloat(_row.attr('data-row-total-quarterly'));
            _annual += parseFloat(_row.attr('data-row-total-annual'));
            _alignment += parseFloat(_row.attr('data-row-total-alignment'));
            _final += parseFloat(_row.attr('data-row-total-final'));
            _used += parseFloat(_row.attr('data-row-total-used'));
            _balance += parseFloat(_row.attr('data-row-total-balance'));
        });
        $table.find('tfoot .total-quarterly').html('₱' + ((_quarterly > 0) ? $.cbo_budget.price_separator(parseFloat(Math.floor((_quarterly * 100))/100).toFixed(2)) : ''));
        $table.find('tfoot .total-annual').html('₱' + ((_annual > 0) ? $.cbo_budget.price_separator(parseFloat(Math.floor((_annual * 100))/100).toFixed(2)) : ''));
        $table.find('tfoot .total-alignment').html('₱' + $.cbo_budget.price_separator(parseFloat(Math.floor((_alignment * 100))/100).toFixed(2)));
        $table.find('tfoot .total-final').html('₱' + ((_final > 0) ? $.cbo_budget.price_separator(parseFloat(Math.floor((_final * 100))/100).toFixed(2)) : ''));
        $table.find('tfoot .total-used').html('₱' + ((_used > 0) ? $.cbo_budget.price_separator(parseFloat(Math.floor((_used * 100))/100).toFixed(2)) : '0.00'));
        $table.find('tfoot .total-balance').html('₱' + ((_balance > 0) ? $.cbo_budget.price_separator(parseFloat(Math.floor((_balance * 100))/100).toFixed(2)) : ''));
    },

    cbo_budget.prototype.load_line_contents = function(_keywords = '') 
    {   
        console.log(_baseUrl + 'finance/budget-proposal/line-lists/' + _budgetID + '?category=' + _category); 
        _breakdownTable = new DataTable('#breakdownTable', {
            ajax: { 
                url : _baseUrl + 'finance/budget-proposal/line-lists/' + _budgetID + '?category=' + _category,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function (data) {  
                    $.cbo_budget.shorten();
                }
            },     
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
            },
            bDestroy: true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'All'],
            ],
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.no);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-total-quarterly', data.quarterly2);
                $(row).find('td:nth-child(4)').addClass('text-end');
                $(row).attr('data-row-total-annual', data.annual2);
                $(row).find('td:nth-child(5)').addClass('text-end');
                $(row).attr('data-row-total-alignment', data.alignment2);
                $(row).find('td:nth-child(6)').addClass('text-end');
                $(row).attr('data-row-total-final', data.final_budget2);
                $(row).find('td:nth-child(7)').addClass('text-end text-primary');
                $(row).attr('data-row-total-used', data.amount_used2);
                $(row).find('td:nth-child(8)').addClass('text-end');
                $(row).attr('data-row-total-balance', data.balance_budget2);
                $(row).find('td:nth-child(9)').addClass('text-end text-danger');
                if (data.is_ppmp_data > 0) {
                    $(row).addClass('active');
                }
            },
            dom: 'l<"toolbar-2">frtip',
            initComplete: function(){
                $(".modal div.toolbar-2").html('<label class="d-inline-flex ms-2 line-30">Category: ' + _categoryList + '</label><button type="button" class="btn btn-small bg-info ms-2" id="add-breakdown">ADD LINE</button>');   
                if(_breakdownTable.rows().data().length > 0) {
                    $('select[name="fund_code_id"]').prop('disabled', true);
                    // $('select[name="department_id"]').prop('disabled', true);
                    $('input[name="budget_year"]').prop('disabled', true);
                } else {
                    $('select[name="fund_code_id"]').prop('disabled', false);
                    // $('select[name="department_id"]').prop('disabled', false);
                    $('input[name="budget_year"]').prop('disabled', false);
                }  
                $('select[name="category"]').val(_category);
                setTimeout(function() { 
                    $.cbo_budget.compute($('#breakdownTable'));
                }, 100);
            }, 
            columns: [
                { data: 'id' },
                { data: 'gl_account' },
                { data: 'category' },
                { data: 'quarterly' },
                { data: 'annual' },
                { data: 'alignment' },
                { data: 'final_budget' },
                { data: 'amount_used' },
                { data: 'balance_budget' },
                { data: 'is_ppmp' },
                { data: 'status_label' },
                { data: 'actions' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: false, targets: 3, className: '' },
                {  orderable: false, targets: 4, className: '' },
                {  orderable: false, targets: 5, className: '' },
                {  orderable: false, targets: 6, className: '' },
                {  orderable: false, targets: 7, className: '' },
                {  orderable: false, targets: 8, className: '' },
                {  orderable: false, targets: 9, className: 'text-center' },
                {  orderable: false, targets: 10, className: 'text-center' },
                {  orderable: false, targets: 11, className: 'text-center' },
            ]
        } );

        return true;
    },  

    cbo_budget.prototype.fetchLists = function()
    {
        return _list;
    }

    cbo_budget.prototype.fetchID = function()
    {
        return _budgetID;
    }

    cbo_budget.prototype.updateID = function(_id)
    {
        return _budgetID = _id;
    }

    cbo_budget.prototype.fetchBreakdownID = function()
    {
        return _breakdownID;
    }

    cbo_budget.prototype.updateBreakdownID = function(_id)
    {
        return _breakdownID = _id;
    }

    cbo_budget.prototype.fetchBudgetYear = function()
    {
        return _budgetYear;
    }

    cbo_budget.prototype.updateBudgetYear = function(_year)
    {
        return _budgetYear = _year;
    }

    cbo_budget.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    cbo_budget.prototype.preload_select3 = function()
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

    cbo_budget.prototype.preload_select4 = function()
    {
        if ( $('.select3') ) {
            $('.select3').select3({
                allowClear: true,
                dropdownAutoWidth : false,dropdownParent: $('.modal.form-inner')
            });
        }
    },

    cbo_budget.prototype.perfect_scrollbar = function()
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

    cbo_budget.prototype.validate_table = function($table)
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
    cbo_budget.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    cbo_budget.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    cbo_budget.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.cbo_budget.preload_select3();
        $.cbo_budget.load_contents();
        $.cbo_budget.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.cbo_budget.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#budget-proposal-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Budget Proposal');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('label[for="control_no"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('label[for="total_budget"].text-danger').text('_ _ _ _ _ _ _ _ _ _ _ _ _ _');
            _modal.find('tfoot th.text-end.text-danger').text('0.00');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('button.print-btn').addClass('hidden');
            _modal.find('input, select, textarea').prop('disabled', false);
            _modal.find('input[name="control_no"], input[name="department"], input[name="designation"]').prop('disabled', true);
            $.cbo_budget.load_contents();
            $.cbo_budget.hideTooltip();
            _budgetID = 0; _budgetYear = '';
        });
        this.$body.on('shown.bs.modal', '#budget-proposal-modal', function (e) {
            $.cbo_budget.hideTooltip();
        });

        this.$body.on('hidden.bs.modal', '#copy-modal', function (e) {
            var _self = $(this);
            _self.find('input[name="budget_year2"]').val('').prop('disabled', false);
            $.cbo_budget.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="year"]', function (e) {
            var _self = $(this);
            _Yr = _self.val();
            $.cbo_budget.load_contents();
        });
        /*
        | ---------------------------------
        | # when breakdown category onChange
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="category"]', function (e) {
            var _self = $(this);
            _category = _self.val();
            $.cbo_budget.load_line_contents();
        });   

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            var _modal  = $('#budget-proposal-modal');
            var d1 = $.cbo_budget.load_line_contents();
            $.when( d1 ).done(function (v1) { 
                $.cbo_budget.perfect_scrollbar();
                _modal.modal('show');
            });
        });
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#budgetTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _total  = $(this).closest('tr').attr('data-row-total');
            var _modal  = $('#budget-proposal-modal');
            var _url    = _baseUrl + 'finance/budget-proposal/edit/' + _id;
            console.log(_url);
            _budgetID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                    console.log(response);
                    var d1 = $.cbo_budgetForm.reload_division(response.data.department_id);
                    $.when( d1 ).done(function ( v1 ) 
                    {  
                        $.each(response.data, function (k, v) {
                            _modal.find('input[name='+k+']').val(v);
                            _modal.find('textarea[name='+k+']').val(v);
                            _modal.find('select[name='+k+']').val(v);
                            _modal.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                        });
                        $.cbo_budget.load_line_contents();
                        if (_status != 'draft') {
                            _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                            _modal.find('input.form-control:not(.form-control-sm), select.form-control:not(.form-control-sm), textarea.form-control:not(.form-control-sm)').prop('disabled', true);
                            _modal.find('button.send-btn').addClass('hidden');
                        } else {
                            _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                        }
                        _modal.find('.m-form__help').text('');
                        // _modal.find('tfoot th.text-danger').text(_total);
                        _modal.find('.modal-header h5').html('Edit Budget Proposal (<span class="variables">' + _code + '</span>)');
                        _modal.modal('show');
                    });
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 

        /*
        | ---------------------------------
        | # when breakdown modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#budget-breakdown-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Breakdown');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"][name="is_ppmp"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_ppmp"][value="0"]').prop('checked', true);
            $.cbo_budget.preload_select3();
            $.cbo_budget.load_line_contents();
            // $('#budget_year').prop('disabled', false);
            _breakdownID = 0;
        });
        this.$body.on('hidden.bs.modal', '#budget-breakdown2-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Breakdown');
            _modal.find('input:not([type="radio"]), textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.select3-selection').removeClass('is-invalid');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('input[type="radio"][name="is_ppmp"][value="1"]').prop('checked', false);
            _modal.find('input[type="radio"][name="is_ppmp"][value="0"]').prop('checked', true);
            $.cbo_budget.load_line_contents();
            _breakdownID = 0;
        });
        /*
        | ---------------------------------
        | # when breakdown modal is shown
        | ---------------------------------
        */
        this.$body.on('shown.bs.modal', '#budget-breakdown-modal', function (e) {
            var _modal = $(this);
                $('#add-breakdown').prop('disabled', false).html('ADD LINE');
                // $.cbo_budget.preload_select4();
        });             

        /*
        | ---------------------------------
        | # when add breakdown button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#add-breakdown', function (e) {
            e.preventDefault();
            var _self  = $(this);
            var _form  = _self.closest('form');
            var _error = $.cbo_budgetForm.validate(_form, 0);
            var _year  = _form.find('input[name="budget_year"]');
            var _modal = $('#budget-breakdown-modal');
            var d1     = $.cbo_budgetForm.fetch_status(_budgetID);
            var d2     = $.cbo_budgetForm.validate_budget(_budgetID);
            $.when( d1, d2 ).done(function ( v1, v2 ) 
            {  
                if (v1 == 'draft') {
                    // $('#budget_year').prop('disabled', true);
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
                    } else if (_year.val() == '') { 
                        _year.addClass('is-invalid');
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
                    } else if (parseFloat(v2) > 0) { 
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>The department and division is already exist.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null;    
                    } else {
                        _self.prop('disabled', true).html('WAIT.....');
                        _modal.modal('show');
                        $('#budget_year').prop('disabled', true);
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
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#breakdownTable .edit-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#budget-breakdown-modal');
            var _url    = _baseUrl + 'finance/budget-proposal/edit-breakdown/' + _id;

            var d1     = $.cbo_budgetForm.fetch_status(_budgetID);
            $.when( d1 ).done(function ( v1 ) {
                console.log(_url);
                if (v1 == 'draft') {
                    _breakdownID = _id;
                    $('#budget_year').prop('disabled', true);
                    _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                    $.ajax({
                        type: 'GET',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            $.each(response.data, function (k, v) {
                                _modal.find('input[type="text"][name='+k+']:not([type="radio"])').val(v);
                                _modal.find('textarea[name='+k+']').val(v);
                                _modal.find('select[name='+k+']').val(v).trigger('change.select3');
                                if (k == 'is_ppmp' && v == 1) {
                                    _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', true);
                                    _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', false);
                                } else {
                                    _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', true);
                                    _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', false);
                                }
                                if (k == 'finals') {
                                    if (v != null) {
                                        _modal.find('input[name="quarterly_budget"], input[name="annual_budget"]').prop('disabled', true);
                                    }
                                }
                            });
                            _self.prop('disabled', false).html('<i class="ti-pencil text-white"></i>');
                            _modal.find('.m-form__help').text('');
                            _modal.find('.modal-header h5').html('Edit Budget Breakdown (<span class="variables">' + _code + '</span>)');
                            _modal.modal('show');
                        },
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                        }
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
        this.$body.on('click', '#breakdownTable .view-btn', function (e) {
            var _self   = $(this);
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#budget-breakdown2-modal');
            var _url    = _baseUrl + 'finance/budget-proposal/edit-breakdown/' + _id;

            var d1     = $.cbo_budgetForm.fetch_breakdown_status(_id);
            $.when( d1 ).done(function ( v1 ) {
                if (v1 == 'locked') {
                    _breakdownID = _id;
                    $('#budget_year').prop('disabled', true);
                    _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
                    $.ajax({
                        type: 'GET',
                        url: _url,
                        success: function(response) {
                            console.log(response);
                            _modal.find('input, select').removeClass('required').attr('disabled', true).closest('.form-group').removeClass('required');
                            $.each(response.data, function (k, v) {
                                _modal.find('input[type="text"][name='+k+']:not([type="radio"])').val(v);
                                _modal.find('textarea[name='+k+']').val(v);
                                _modal.find('select[name='+k+']').val(v).trigger('change.select3');
                                if (k == 'is_ppmp' && v == 1) {
                                    _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', true);
                                    _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', false);
                                } else {
                                    _modal.find('input[type="radio"][name="'+k+'"][value="0"]').prop('checked', true);
                                    _modal.find('input[type="radio"][name="'+k+'"][value="1"]').prop('checked', false);
                                }
                            });
                            var _hiddens = ['balance', 'alignment', 'final_budget'];
                            $.each(_hiddens, function (h, j) {
                                _modal.find('input[name="' + j + '"], select[name="' + j + '"]').closest('.form-group').removeClass('hidden');
                                if (j == 'alignment') {
                                    _modal.find('input[name="' + j + '"], select[name="' + j + '"]').attr('disabled', false).addClass('required').closest('.form-group').addClass('required');
                                }
                            });
                            $.cbo_budget.required_fields();                            
                            _self.prop('disabled', false).html('<i class="ti-search text-white"></i>');
                            _modal.find('.m-form__help').text('');
                            _modal.find('.modal-header h5').html('Edit Budget Breakdown (<span class="variables">' + _code + '</span>)');
                            _modal.modal('show');
                        },
                        complete: function() {
                            window.onkeydown = null;
                            window.onfocus = null;
                        }
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
        | # when restore/remove button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#breakdownTable .remove-btn, #breakdownTable .restore-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _status = $(this).closest('tr').attr('data-row-status');
            var _modal  = $(this).closest('.modal');
            var _url    = (_status == 'Active') ? _baseUrl + 'finance/budget-proposal/remove/' + _id : _baseUrl + 'finance/budget-proposal/restore/' + _id;

            var d1     = $.cbo_budgetForm.fetch_status(_budgetID);
            $.when( d1 ).done(function ( v1 ) {
                console.log(_url);
                if (v1 == 'draft') {
                    $('#budget_year').prop('disabled', true);
                    Swal.fire({
                        html: (_status == 'Active') ? "Are you sure? <br/>the budget breakdown with series ("+ _code +") will be removed." : "Are you sure? <br/>the budget breakdown with series ("+ _code +") will be restored.",
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
                                            _modal.find('tfoot th.text-danger').text('₱' + $.cbo_budget.price_separator(parseFloat(response.total).toFixed(2)));
                                            $.cbo_budget.load_line_contents();
                                            if (_budgetYear !== '') {
                                                $('#budget_year').prop('disabled', false).val(_budgetYear);
                                            } else {
                                                $('#budget_year').prop('disabled', false);
                                            }
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss,$('#budget_year').prop('disabled', false)
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
        | # when unlock button is click
        | ---------------------------------
        */
        this.$body.on('click', '#budgetTable .unlock-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _rows   = _self.closest('tr');
            var _id     = _rows.attr('data-row-id');
            var _status = _rows.attr('data-row-status');
            var _code   = _rows.attr('data-row-code');
            var _dep    = _rows.attr('data-row-dep');
            var _total  = _rows.attr('data-row-total-total');
            var _url    = _baseUrl + 'finance/budget-proposal/send/unlock/' + _id;
            
            console.log(_url);
            if (parseFloat(_total) != '₱0.00') {
                if (_status == 'locked') {
                    _self.prop('disabled', true);
                    Swal.fire({
                        html:  "Are you sure? <br/>the request with <strong>Budget Year (" + _code + ")<br/>[ " + _dep + " ]</strong><br/>will be unlocked.",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, unlock it!",
                        cancelButtonText: "No, return",
                        customClass: { confirmButton: "btn draft-bg text-white", cancelButton: "btn btn-active-light" },
                    }).then(function (t) {
                        t.value
                            ? 
                            $.ajax({
                                type: 'PUT',
                                url: _url,
                                success: function(response) {
                                    console.log(response);
                                    _self.prop('disabled', false);
                                    Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-blue" } }).then(
                                        function (e) {
                                            e.isConfirmed && ((t.disabled = !1));
                                            $.cbo_budget.load_contents();
                                        }
                                    );
                                },
                                complete: function() {
                                    window.onkeydown = null;
                                    window.onfocus = null;
                                }
                            })
                            : "cancel" === t.dismiss, _self.prop('disabled', false) 
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
            } else {
                Swal.fire({
                    title: "Oops...",
                    html: "Unable to proceed!<br/>Please add some breakdown line first.",
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

        /*
        | ---------------------------------
        | # when lock button is click
        | ---------------------------------
        */
        this.$body.on('click', '#budgetTable .lock-btn', function (e){
            e.preventDefault();
            var _self   = $(this);
            var _rows   = _self.closest('tr');
            var _id     = _rows.attr('data-row-id');
            var _status = _rows.attr('data-row-status');
            var _code   = _rows.attr('data-row-code');
            var _dep    = _rows.attr('data-row-dep');
            var _total  = _rows.attr('data-row-total-total');
            var _url    = _baseUrl + 'finance/budget-proposal/send/for-approval/' + _id;
            var d1      = $.cbo_budgetForm.validate_budget(_id);
            
            $.when( d1 ).done(function ( v1 ) 
            {  
                if (parseFloat(v1) > 0) {
                    Swal.fire({
                        title: "Oops...",
                        html: "Unable to process!<br/>The department and division is already exist.",
                        icon: "error",
                        type: "danger",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;    
                } else {
                    console.log(_url);
                    if (parseFloat(_total) != '₱0.00') {
                        if (_status == 'draft') {
                            _self.prop('disabled', true);
                            Swal.fire({
                                html:  "Are you sure? <br/>the request with <strong>Budget Year (" + _code + ")<br/>[ " + _dep + " ]</strong><br/>will be sent to locked.",
                                icon: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonText: "Yes, send it!",
                                cancelButtonText: "No, return",
                                customClass: { confirmButton: "btn bg-info text-white", cancelButton: "btn btn-active-light" },
                            }).then(function (t) {
                                t.value
                                    ? 
                                    $.ajax({
                                        type: 'PUT',
                                        url: _url,
                                        success: function(response) {
                                            console.log(response);
                                            _self.prop('disabled', false);
                                            Swal.fire({ title: response.title, text: response.text, icon: response.type, buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-blue" } }).then(
                                                function (e) {
                                                    e.isConfirmed && ((t.disabled = !1));
                                                    $.cbo_budget.load_contents();
                                                }
                                            );
                                        },
                                        complete: function() {
                                            window.onkeydown = null;
                                            window.onfocus = null;
                                        }
                                    })
                                    : "cancel" === t.dismiss, _self.prop('disabled', false) 
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
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            html: "Unable to proceed!<br/>Please add some breakdown line first.",
                            icon: "error",
                            type: "danger",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                        window.onkeydown = null;
                        window.onfocus = null; 
                    }
                }
            });
        });

         /*
        | ---------------------------------
        | # when payables checkbox all is tick
        | ---------------------------------
        */
        this.$body.on('click', '#budgetTable input[type="checkbox"][value="all"]', function (e) {
            var _self = $(this);
            var _table = _self.closest('table');
            if (_self.is(':checked')) {
                _table.find('tr[data-row-status="locked"] input[type="checkbox"]').prop('checked', true);
                $.each(_table.find('tr[data-row-status="locked"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    if (checkbox.is(":checked")) {
                        var found = false;
                        for (var i = 0; i < _list.length; i++) {
                            if (_list[i] == checkbox.val()) {
                                found == true;
                                return;
                            }
                        } 
                        if (found == false) {
                            _list.push(checkbox.val());
                        }
                    } 
                });
                console.log(_list);
            } else {
                _table.find('tr[data-row-status="locked"] input[type="checkbox"]').prop('checked', false);
                $.each(_table.find('tr[data-row-status="locked"] input[type="checkbox"][value!="all"]'), function(){
                    var checkbox = $(this);
                    for (var i = 0; i < _list.length; i++) {
                        if (_list[i] == checkbox.val()) {
                            _list.splice(i, 1);
                        }
                    }
                });
                console.log(_list);
            }
            if (_list.length > 0) {
                $('#copy-budget-btn').prop('disabled', false).addClass('active');
            } else {
                $('#copy-budget-btn').prop('disabled', true).removeClass('active');
            }
        });
        /*
        | ---------------------------------
        | # when payables checkbox singular is tick 
        | ---------------------------------
        */
        this.$body.on('click', '#budgetTable input[type="checkbox"][value!="all"]', function (e) {
            var _self = $(this);
            if (_self.is(':checked')) {
                _list.push(_self.val());
            } else {
                for (var i = 0; i < _list.length; i++) {
                    if (_list[i] == _self.val()) {
                        _list.splice(i, 1);
                    }
                }
            }
            if (_list.length > 0) {
                $('#copy-budget-btn').prop('disabled', false).addClass('active');
            } else {
                $('#copy-budget-btn').prop('disabled', true).removeClass('active');
            }
            console.log(_list);
        });

        /*
        | ---------------------------------
        | # when copy button is click
        | ---------------------------------
        */
        this.$body.on('click', '#copy-budget-btn', function (e){
            e.preventDefault();
            var _modal  = $('#copy-modal');
                _modal.modal('show');
        });
    }

    //init cbo_budget
    $.cbo_budget = new cbo_budget, $.cbo_budget.Constructor = cbo_budget

}(window.jQuery),

//initializing cbo_budget
function($) {
    "use strict";
    $.cbo_budget.load_years();
    $.cbo_budget.required_fields();
    $.cbo_budget.init();
}(window.jQuery);