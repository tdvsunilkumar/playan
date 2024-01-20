!function($) {
    "use strict";

    var wasteMaterial = function() {
        this.$body = $("body");
    };

    var _wasteMaterialID = 0; var _table; var _page = 0; var _status = '0';

    wasteMaterial.prototype.required_fields = function() {
        
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

    wasteMaterial.prototype.compute = function(_table) 
    {
        var _unitCost = 0; var _totalCost = 0;
        $.each(_table.find("tbody tr"), function() {
            var _self = $(this);
            _unitCost += parseFloat(_self.attr('data-row-unit-cost'));
            _totalCost += parseFloat(_self.attr('data-row-total-cost'));
        });
        _table.find('tfoot .unit-cost').text((_unitCost > 0) ? '₱' + $.wasteMaterial.price_separator($.wasteMaterial.money_format(_unitCost)) : '');
        _table.find('tfoot .total-cost').text((_totalCost > 0) ? '₱' + $.wasteMaterial.price_separator($.wasteMaterial.money_format(_totalCost)) : '');
    },

    wasteMaterial.prototype.load_contents = function(_page = 0) 
    {   
        _table = new DataTable('#wasteMaterialTable', {
            ajax: { 
                url : _baseUrl + 'general-services/waste-materials/lists?status=' + _status,
                type: "GET", 
                data: {
                    "_token": _token
                },
                complete: function(response) {
                    console.log(response.responseJSON);
                    $.wasteMaterial.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                    $.wasteMaterial.hideTooltip();
                    $.wasteMaterial.compute($('#wasteMaterialTable'));
                }
            },
            language: {
                "processing": "<div class='spinner-border table' role='status'></div>",
                "emptyTable": 'No data available in table<br/><img class="mw-100" alt="" style="max-height: 300px" src="' + _baseUrl + 'assets/images/illustrations/work.png">'
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
                $(row).attr('data-row-status', data.status);
                $(row).attr('data-row-unit-cost', data.unit_cost);
                $(row).attr('data-row-total-cost', data.total_cost);
            },
            initComplete: function(){
                this.api().page(_page).draw( 'page' );
            },      
            columns: [
                { data: 'item_no' },
                { data: 'qty' },
                { data: 'uom' },
                { data: 'description' },
                { data: 'supplier' },
                { data: 'po_no' },
                { data: 'or_no' },
                { data: 'unit_cost_label' },
                { data: 'total_cost_label' },
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start sliced ' },
                {  orderable: true, targets: 4, className: 'text-start sliced' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-end' },
                {  orderable: false, targets: 8, className: 'text-end' },
            ]
        } );

        return true;
    },

    wasteMaterial.prototype.fetchID = function()
    {
        return _wasteMaterialID;
    }

    wasteMaterial.prototype.updateID = function(_id)
    {
        return _wasteMaterialID = _id;
    }

    wasteMaterial.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    wasteMaterial.prototype.preload_select3 = function()
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

    wasteMaterial.prototype.perfect_scrollbar = function()
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

    wasteMaterial.prototype.money_format = function(_money)
    {   
        return parseFloat(Math.floor((_money * 100))/100).toFixed(2);
    },

    wasteMaterial.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    wasteMaterial.prototype.hideTooltip = function()
    {
        $('.tooltip').remove();
    },

    wasteMaterial.prototype.getDate = function()
    {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        
        if(dd<10) {
            dd = '0'+dd
        } 
        
        if(mm<10) {
            mm = '0'+mm
        } 
        
        today = yyyy + '-' + mm + '-' + dd;
        return today;
    }

    wasteMaterial.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.wasteMaterial.preload_select3();
        $.wasteMaterial.load_contents();
        $.wasteMaterial.perfect_scrollbar();
        $('[data-bs-toggle="tooltip"]').on('click', function () {
            $(this).tooltip('hide');
            $.wasteMaterial.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#account-payable-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Account Payable');
            _modal.find('input, textarea').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').find('.m-form__help').text('');
            _modal.find('input:not(.disable), select:not(.disable), textarea:not(.disable)').prop('disabled', false);
            _modal.find('input.disable, select.disable, textarea.disable').prop('disabled', true);
            _modal.find('button.submit-btn').removeClass('hidden');
            $.wasteMaterial.preload_select3();
            $.wasteMaterial.hideTooltip();
            $.wasteMaterial.load_contents(_table.page());
            _wasteMaterialID = 0;
        });
        this.$body.on('shown.bs.modal', '#account-payable-modal', function (e) {
            $.wasteMaterial.hideTooltip();
        });

        /*
        | ---------------------------------
        | # when status on change
        | ---------------------------------
        */
        this.$body.on('change', 'select[name="status"]', function (e) {
            var _self = $(this);
            _status = _self.val();
            $.wasteMaterial.load_contents();
        });

        /*
        | ---------------------------------
        | # when export button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'a[data-bs-original-title="Download Waste Materials"]', function (e) {
            e.preventDefault();
            var _keywords = $('#wasteMaterialTable_filter input[type="search"]').val();
            var _url    = _baseUrl + 'general-services/waste-materials/export?keywords=' + _keywords;
            window.open(_url, '_blank');
        }); 
    }

    //init wasteMaterial
    $.wasteMaterial = new wasteMaterial, $.wasteMaterial.Constructor = wasteMaterial

}(window.jQuery),

//initializing wasteMaterial
function($) {
    "use strict";
    $.wasteMaterial.required_fields();
    $.wasteMaterial.init();
}(window.jQuery);