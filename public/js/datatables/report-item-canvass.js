!function($) {
    "use strict";

    var item_canvass = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0; var _table;

    item_canvass.prototype.required_fields = function() {
        
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

    item_canvass.prototype.load_contents = function(_keywords = '') 
    {   
        _table = new DataTable('#itemCanvassTable', {
            ajax: { 
                url : _baseUrl + 'reports/general-services/item-canvass/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
                complete: function() {
                    $.item_canvass.shorten(); 
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });  
                }
            },
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
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
                { data: 'business_name' },
                { data: 'branch_name' },
                { data: 'gl_account' },
                { data: 'items' },
                { data: 'brand_model' },
                { data: 'quantity' },
                { data: 'unit_cost' },
                { data: 'total_cost' },
                { data: 'modified' }
            ],
            rowReorder: {
                dataSrc: 'order'
            },
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start sliced' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start sliced' },
                {  orderable: false, targets: 4, className: 'text-start sliced' },
                {  orderable: false, targets: 5, className: 'text-start sliced' },
                {  orderable: false, targets: 6, className: 'text-center' },
                {  orderable: false, targets: 7, className: 'text-center' },
                {  orderable: false, targets: 8, className: 'text-center' },
                {  orderable: false, targets: 9, className: 'text-center' },
            ]
        } );

        return true;
    },

    item_canvass.prototype.fetchID = function()
    {
        return _groupMenuID;
    }

    item_canvass.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }

    item_canvass.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    item_canvass.prototype.preload_select3 = function()
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

    item_canvass.prototype.updateOrder = function(data)
    {   
        console.log(data);
        $.ajax({
            type: 'POST',
            url: _baseUrl + 'components/menus/groups/update-order',
            data:{ orders: data },
            success: function(response) {
                console.log(response);
                _table.ajax.reload();
            },
            async: false
        })
    },

    item_canvass.prototype.perfect_scrollbar = function()
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

    item_canvass.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.item_canvass.preload_select3();
        $.item_canvass.load_contents();
        $.item_canvass.perfect_scrollbar();

        /*
        | ---------------------------------
        | # when export button is clicked
        | ---------------------------------
        */
        this.$body.on('click', 'a[data-bs-original-title="Download Canvass Sheet"]', function (e) {
            e.preventDefault();
            var _keywords = $('#itemCanvassTable_filter input[type="search"]').val();
            var _url    = _baseUrl + 'reports/general-services/item-canvass/export?keywords=' + _keywords;
            window.open(_url, '_blank');
        }); 
    }

    //init item_canvass
    $.item_canvass = new item_canvass, $.item_canvass.Constructor = item_canvass

}(window.jQuery),

//initializing item_canvass
function($) {
    "use strict";
    $.item_canvass.required_fields();
    $.item_canvass.init();
}(window.jQuery);