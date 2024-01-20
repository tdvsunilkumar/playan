!function($) {
    "use strict";

    var by_arp_no = function() {
        this.$body = $("body");
    };

    var _groupMenuID = 0;

    by_arp_no.prototype.required_fields = function() {
        
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

    by_arp_no.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesByAprTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/lists',
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'prop_index_no' },
                { data: 'kind' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
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

    by_arp_no.prototype.load_contents_tct = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesByTctTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/listByTct',
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'tct_no' },
                { data: 'prop_index_no' },
                { data: 'kind' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },                {  orderable: true, targets: 7, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },


            ]
        } );

        return true;
    },

    by_arp_no.prototype.load_contents_cct = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesByCctTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/listByCct',
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'cct_no' },
                { data: 'unit_no' },
                { data: 'kind' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },



            ]
        } );

        return true;
    },

    by_arp_no.prototype.load_contents_own = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesByOwnTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/listByOwn',
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'prop_index_no' },
                { data: 'kind' },
                { data: 'lot_no' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },

            ]
        } );

        return true;
    },

    by_arp_no.prototype.load_contents_build_kind = function(_keywords = '',kind_id) 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesByBuildKindTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/listByBuildKind',
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token,
                    'kind_id': kind_id
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'cct_no' },
                { data: 'unit_no' },
                { data: 'kind' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
            ],
            columnDefs: [
                {  orderable: false, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
                {  orderable: true, targets: 6, className: 'text-start' },
                {  orderable: true, targets: 7, className: 'text-start' },
                {  orderable: true, targets: 8, className: 'text-start' },



            ]
        } );

        return true;
    },

    by_arp_no.prototype.load_contents_survey = function(_keywords = '') 
    {   
        var _complete = 0;
        var table = new DataTable('#inquiriesBySurveyTable', {
            ajax: { 
                url : _baseUrl + 'real-property/inquiries/listByServey',
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
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.id);
            },
            columns: [
                { data: 'sl_no' },
                { data: 'td_no' },
                { data: 'own_name' },
                { data: 'survey_no' },
                { data: 'kind' },
                { data: 'class' },
                { data: 'value' },
                { data: 'action' },
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

    by_arp_no.prototype.print_tax = function(_id) 
    {   
        $.ajax({
            url: _baseUrl + 'real-property/inquiries/printTaxDec',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
    },

    by_arp_no.prototype.print_faas = function(_id) 
    {   
        $.ajax({
            url: _baseUrl + 'real-property/inquiries/printFAAS',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
    },



    by_arp_no.prototype.fetchID = function()
    {
        return _rptpropertieID;
    }

    by_arp_no.prototype.updateID = function(_id)
    {
        return _groupMenuID = _id;
    }



    by_arp_no.prototype.shorten = function() 
    {   
        if ($('.showLess')) {
            $('.showLess').shorten({
                "showChars" : 20,
                "moreText"	: "More",
                "lessText"	: "Less"
            });
        }
    },

    by_arp_no.prototype.preload_select3 = function()
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

    by_arp_no.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.by_arp_no.preload_select3();
        $.by_arp_no.load_contents();

        /*
        | ---------------------------------
        | # when keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('keyup', '#datatable-2 input[type="search"]', function (e) {
            var _self = $(this);
            var lastVal = _self.val();
            if (e.which == 13) {
                var d1 = $.by_arp_no.load_contents(_self.val());
                $.when( d1 ).done(function ( v1 ) 
                {   
                    _self.val(lastVal).focus();
                });
            }
        });
        
        
        
        this.$body.on('change', '#filter_type', function (e){
            e.preventDefault();
            var key   = $('#q');
            var filter_type   = $('#filter_type');
            var tct_no   = $('#tct_no');
            var arp_no   = $('#arp_no');
            var cct_no   = $('#cct_no');
            var own   = $('#own');
            var survey   = $('#survey');
            var build_kind   = $('#build_kind');
            var dyn_html   = $('#dyn_html');
            var build   = $('#build');
            var search   = $('#search_q');
            var search_name   = $('#search_name');
            switch(filter_type.val()) {
                case "1":
                    arp_no.removeClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('ARP No Inquiry');
                    search_name.html('Enter APR No.');
                    build.addClass('hide');
                    search.removeClass('hide');
                    $.by_arp_no.load_contents(key.val());
                  break;
                case "2":
                    arp_no.addClass('hide');
                    tct_no.removeClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('TCT No Inquiry');
                    search_name.html('Enter TCT No.');
                    build.addClass('hide');
                    search.removeClass('hide');
                    $.by_arp_no.load_contents_tct(key.val());
                  break;
                case "3":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.removeClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('CCT No Inquiry');
                    build.addClass('hide');
                    search_name.html('Enter CCT No.');
                    search.removeClass('hide');
                    $.by_arp_no.load_contents_cct(key.val());
                  break;
                case "4":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.removeClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('Taxpayer Inquiry');
                    build.addClass('hide');
                    search.removeClass('hide');
                    search_name.html('Enter Taxpayer Name');
                    $.by_arp_no.load_contents_own(key.val());
                  break;
                case "5":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.removeClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('Survey No Inquiry');
                    build.addClass('hide');
                    search.removeClass('hide');
                    search_name.html('Enter Survey No.');
                    $.by_arp_no.load_contents_survey(key.val());
                  break;
                case "6":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.removeClass('hide');
                    var kind_id   = $('#kind_id');
                    dyn_html.html('Building Kind Inquiry');
                    build.removeClass('hide');
                    search.addClass('hide');
                    search_name.html('Select Building Kind');
                    $.by_arp_no.load_contents_build_kind(key.val(),kind_id.val());
                  break;
                default:
                    arp_no.removeClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('ARP No Inquiry');
                    build.addClass('hide');
                    search.removeClass('hide');
                    search_name.html('Enter APR No.');
                    $.by_arp_no.load_contents(key.val());
            }

            
        }); 
        
          /*
        | ---------------------------------
        | # when item line keywords onkeyup
        | ---------------------------------
        */

        this.$body.on('click', '#btn_search', function (e){
            e.preventDefault();
            var key   = $('#q');
            var filter_type   = $('#filter_type');
            var tct_no   = $('#tct_no');
            var arp_no   = $('#arp_no');
            var cct_no   = $('#cct_no');
            var own   = $('#own');
            var survey   = $('#survey');
            var build_kind   = $('#build_kind');
            var dyn_html   = $('#dyn_html');
            switch(filter_type.val()) {
                case "1":
                    arp_no.removeClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('ARP No Inquiry');
                    key.html('Enter APR No.');
                    $.by_arp_no.load_contents(key.val());
                  break;
                case "2":
                    arp_no.addClass('hide');
                    tct_no.removeClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('TCT No Inquiry');
                    key.html('Enter TCT No.');
                    $.by_arp_no.load_contents_tct(key.val());
                  break;
                case "3":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.removeClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('CCT No Inquiry');
                    $.by_arp_no.load_contents_cct(key.val());
                  break;
                case "4":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.removeClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('Taxpayer Inquiry');
                    $.by_arp_no.load_contents_own(key.val());
                  break;
                case "5":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.removeClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('Survey No Inquiry');
                    $.by_arp_no.load_contents_survey(key.val());
                  break;
                case "6":
                    arp_no.addClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.removeClass('hide');
                    var kind_id   = $('#kind_id');
                    dyn_html.html('Building Kind Inquiry');
                    $.by_arp_no.load_contents_build_kind(key.val(),kind_id.val());
                  break;
                default:
                    arp_no.removeClass('hide');
                    tct_no.addClass('hide');
                    cct_no.addClass('hide');
                    own.addClass('hide');
                    survey.addClass('hide');
                    build_kind.addClass('hide');
                    dyn_html.html('ARP No Inquiry');
                    $.by_arp_no.load_contents(key.val());
            }

            
        }); 
        
        /*
        | ---------------------------------
        | # when tax print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.print-tax', function (e) {
            e.preventDefault();
            var _id     = $(this).closest('tr').attr('data-row-id');
            $.by_arp_no.print_tax(_id);
        }); 

        /*
        | ---------------------------------
        | # when faas print button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '.print-faas', function (e) {
            e.preventDefault();
            var _id     = $(this).closest('tr').attr('data-row-id');
            $.by_arp_no.print_faas(_id);
        }); 

        
    }

    //init by_arp_no
    $.by_arp_no = new by_arp_no, $.by_arp_no.Constructor = by_arp_no

}(window.jQuery),

//initializing by_arp_no
function($) {
    "use strict";
    $.by_arp_no.init();
    $.by_arp_no.required_fields();
    $.by_arp_no.preload_select3();
}(window.jQuery);