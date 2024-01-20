!function($) {
    "use strict";

    var inventory = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0;

    inventory.prototype.required_fields = function() {
        
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

    inventory.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inventoryTable', {
            ajax: { 
                url : _baseUrl + 'general-services/inventory/lists',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                },
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.itemId);
                $(row).attr('data-row-code', data.itemName);
            },
            columns: [
                { data: 'select' },
                { data: 'itemId' },
                { data: 'catCode' },
                { data: 'itemCode' },
                { data: 'itemName' },
                { data: 'itemInventory' },
                { data: 'itemUOM' },
                { data: 'actions' }
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start sliced' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },
            ]
        } );

        return true;
    },
    inventory.prototype.$load_contents_item_history = function(_id,_filter_type,_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#itemTable', {
            ajax: { 
                url : _baseUrl + 'general-services/inventory/item_history_lists/' + _id +'/' + _filter_type,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.control_no);
                $(row).attr('data-row-code', data.control_no);
            },
            columns: [
                { data: 'trans_type' },
                { data: 'trans_date' },
                { data: 'trans_by' },
                { data: 'rcv_by' },
                { data: 'based_qty' },
                { data: 'posted_qty' },
                { data: 'balance_qty' },
                { data: 'reserved_qty' },
    
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },
               
            ]
        } );

        return true;
    },

    inventory.prototype.$load_issue_item = function(_checkedValues,_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#itemIssueTable', {
            ajax: { 
                url : _baseUrl + 'general-services/inventory/issue_checked_item',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token,
                    "checkedValues": _checkedValues,
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "asc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-select-id', data.itemId);
                $(row).attr('data-select-code', data.itemName);
            },
            columns: [
                { data: 'select' },
                { data: 'itemId' },
                { data: 'accCode' },
                { data: 'itemType' },
                { data: 'itemCode' },
                { data: 'itemName' },
                { data: 'itemDesc' },
                { data: 'itemInventory' },
                { data: 'itemUOM' },
                { data: 'estLifeSpan' },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start sliced' },
                {  orderable: true, targets: 7, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },
            ]
        } );

        return true;
    },

    inventory.prototype.fetchID = function()
    {
        return _groupMenuID;
    }

    inventory.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }



    inventory.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    inventory.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.inventory.load_contents();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.inventory.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });
        
        /*
        | ---------------------------------
        | # when check all / uncheck all is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#selectAll', function (e){
            var _self  = $(this);
            var checkbox = document.getElementById('selectAll');
            var _modal  = $('.select_item');
            var issue_add  = $('#issue_add');
            if(checkbox.checked)
            {
                issue_add.removeClass('disabled');
                _modal.prop('checked', true);
            }
            else{
                issue_add.addClass('disabled');
                _modal.prop('checked', false);   
            }
        });

         /*
        | ---------------------------------
        | # when select item
        | ---------------------------------
        */
        this.$body.on('click', '.select_item', function (e){
            var _self  = $(this);
            var issue_add  = $('#issue_add');
            var checkboxes = document.querySelectorAll('.select_item');
                var checkedCount = 0;
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                    checkedCount++;
                    }
                }
                if(checkedCount > 0)
                {
                    issue_add.removeClass('disabled');
                }
                else{
                    issue_add.addClass('disabled');
                }    
        });


        


        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-3 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            var _filter_type = $('#filter_type');
            var _id   = $('#id');
            if (e.which == 13) {
                var d1 =   $.inventory.$load_contents_item_history(_id.val(),_filter_type.val(),_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

         /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-1 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            var selected_items = $('.selected_items');
            if (e.which == 13) {
                var d1 =   $.inventory.$load_issue_item(selected_items.val(),_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });

          /*
        | ---------------------------------
        | # when item line keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#itemRequisitionTable_wrapper input[type="search"]', function (e) {
            $.requisition.load_line_contents();
        });

         /*
        | ---------------------------------
        | # fetch item history according to filter
        | ---------------------------------
        */
        this.$body.on('change', '#filter_type', function (e){
            e.preventDefault();
            var _self = $(this);
            var _id   = $('#id');
            $.inventory.$load_contents_item_history(_id.val(),_self.val());
        });  
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.add-btn', function (e) {
            // Get all elements that match the selector '.select_item'
            const selectItems = document.querySelectorAll('.select_item');
            // Loop through all the elements and get their values if they are checked
            const checkedValues = [];
            for (let i = 0; i < selectItems.length; i++) {
                const item = selectItems[i];
                if (item.checked) {
                    checkedValues.push(item.value);
                }
            }
            $.inventory.$load_issue_item(checkedValues);
            var _modal  = $('#issuanceRequestModal');
                _modal.modal('show');
        });

        /*
        | ---------------------------------
        | # when issue button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#inventoryTable .issue-btn', function (e) {
            var _id     = $(this).closest('tr').attr('data-row-id');
            const checkedValues = [];
            checkedValues.push(_id);
            $.inventory.$load_issue_item(checkedValues);
            var _modal  = $('#issuanceRequestModal');
            _modal.modal('show');
        }); 
        
        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#inventoryTable .edit-btn', function (e) {

            var _id     = $(this).closest('tr').attr('data-row-id');
            var _code   = $(this).closest('tr').attr('data-row-code');
            var _modal  = $('#inventory-modal');
            var _filter_type  = 1;
            $.inventory.$load_contents_item_history(_id,_filter_type);
            var _url    = _baseUrl + 'general-services/inventory/edit/' + _id;
            console.log(_url);
            _groupMenuID = _id;
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
                    _modal.find('.m-form__help').text('');
                    _modal.find('.modal-header h5').html('Item Inventory (<span class="variables">' + _code + '</span>)');
                    _modal.modal('show');
                },
                complete: function() {
                    window.onkeydown = null;
                    window.onfocus = null;
                }
            });
        }); 
    }

    //init inventory
    $.inventory = new inventory, $.inventory.Constructor = inventory

}(window.jQuery),

//initializing inventory
function($) {
    "use strict";
    $.inventory.init();
    $.inventory.required_fields();
}(window.jQuery);