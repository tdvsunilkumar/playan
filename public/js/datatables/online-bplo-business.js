!function($) {
    "use strict";

    var requisition = function() {
        this.$body = $("body");
    };
    
    $(".JqSaveTestingData").click(function(){
        changeBusinessDate();
    })
    $("#app_code").change(function(){
        if($(this).val()==1){
            $("#pm_id").val(1);
            $("#pm_id").addClass("disabled-field")
        }else{
            $("#pm_id").removeClass("disabled-field")
        }
    })
    $("#test_app_code").change(function(){
        if($(this).val()==1){
            $("#test_pm_id").val(1);
            $("#test_pm_id").addClass("disabled-field")
        }else{
            $("#test_pm_id").removeClass("disabled-field")
        }
    })

    var sortBy = '', orderBy = '', _requisitionID = 0, _lineID = 0; var _status = 0;
    requisition.prototype.required_fields = function() {
        $.each(this.$body.find(".form-group"), function(){
            if ($(this).hasClass('required')) {       
                var $input = $(this).find("input[type='date'],input[type='text'],input[type='number'],input[type='file'],radio, select, textarea");
                if ($input.val() == '') {
                    $(this).find('label').append('<span class="ms-1 text-danger">*</span>'); 
                    $(this).find('.m-form__help').text('');  
                }
                $input.addClass('required');
            } else {
                $(this).find("input[type='date'],input[type='text'],input[type='number'],input[type='file'],radio, select, textarea").removeClass('required is-invalid');
            } 
        });

    },

    requisition.prototype.fetchID = function()
    {
        return _requisitionID;
    }

    requisition.prototype.updateID = function(_id)
    {
        return _requisitionID = _id;
    }

    requisition.prototype.updateLineID = function(_id)
    {
        return _lineID = _id;
    }

    requisition.prototype.fetchLineID = function()
    {
        return _lineID;
    }

    requisition.prototype.load_data = function(_keywords = '') 
    {   
            var dropdown_html=get_page_number('1'); 
            var table = $('#Jq_datatablelist').DataTable({ 
                "language": {
                    "infoFiltered":"",
                    "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
                },
                dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
                    oLanguage: {
                        sLengthMenu: dropdown_html
                },
                "bProcessing": true,
                "serverSide": true,
                "bDestroy": true,
                "searching": false,
                "order": [],
                "columnDefs": [{ orderable: false, targets: [0,8,9] }],
                "pageLength": 10,
                "ajax":{ 
                    url :DIR+'business-online-application/lists', // json datasource
                    type: "GET", 
                    "data": {
                        "q":$("#q").val(),
                        "from_date":$("#from_date").val(),
				        "to_date":$("#to_date").val(),
                        "brgy":$("#flt_busn_office_barangay").val(),
                        "flt_Status" :$("#flt_Status").val(),
                        "_token":$("#_csrf_token").val()
                    }, 
                    error: function(html){
                    }
                },
            "columns": [
                    { "data": "srno" },
                    { "data": "owner" },
                    { "data": "busn_name" },
                    { "data": "barangay" },
                    { "data": "app_type" },
                    { "data": "app_date" },
                    { "data": "busn_app_status" },
                    { "data": "app_method" },
                    { "data": "duration" },
                    { "data": "action" }
                ],
                drawCallback: function(s){ 
                    var api = this.api();
                    var info=table.page.info();
                    var dropdown_html=get_page_number(info.recordsTotal,info.length);
                    $("#common_pagesize").html(dropdown_html);
                    api.$('.deleterow').click(function() {
                        var recordid = $(this).attr('id');
                        DeleteRecord(recordid);
                    });

                    api.$('.activeinactive').click(function() {
                        var recordid = $(this).attr('id');
                        var is_activeinactive = $(this).attr('value');
                        ActiveInactiveUpdate(recordid,is_activeinactive);
            
                    });

                    api.$('.newRenew').click(function() {
                        var recordid = $(this).attr('id');
                        var is_newRenew = $(this).attr('value');
                        newRenewUpdate(recordid,is_newRenew);
            
                    });
                }
            });  
    },

    requisition.prototype.load_contents = function(_keywords = '') 
    {   
        var _complete = 0;
        var bus_id     = $.requisition.fetchID();
        var table = new DataTable('#subClassTable', {
            ajax: { 
                url : _baseUrl + 'business-online-application/busn_psic_lists/'+ bus_id,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.requisition.shorten();
                }
               
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
            },
            initComplete: function(){
                if (_status == 3) {
                    $('#subClassTable td:last-child, #subClassTable th:last-child').addClass('hidden');
                } else {
                    $('#subClassTable td:last-child, #subClassTable th:last-child').removeClass('hidden');
                }
            },    
            columns: [
                { data: 'code', orderable: true },
                { data: 'desc', orderable: true },
                { data: 'busp_no_units', orderable: true },
                { data: 'busp_capital_investment', orderable: true },
                { data: 'busp_essential', orderable: true },
                { data: 'busp_non_essential', orderable: true },
            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
                {  orderable: true, targets: 4, className: 'text-start' },
                {  orderable: true, targets: 5, className: 'text-start' },
            ]
        } );

        return true;
    },

    requisition.prototype.load_requirment_doc = function(_keywords = '') 
    {   
        var _complete = 0;
        var bus_id     = $.requisition.fetchID();
        var table = new DataTable('#reqDocTable', {
            ajax: { 
                url : _baseUrl + 'business-online-application/requirment_doc_list/'+ bus_id,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.code);
            },
            initComplete: function(){
                if (_status == 3) {
                    $('#reqDocTable td:last-child, #reqDocTable th:last-child').addClass('hidden');
                } else {
                    $('#reqDocTable td:last-child, #reqDocTable th:last-child').removeClass('hidden');
                }
            },   
            columns: [
                { data: 'line_business', orderable: true },   
                { data: 'desc', orderable: true },
                { data: 'attachment', orderable: true },
                { data: 'action', orderable: false },         ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: false, targets: 3, className: 'text-start' },
            ]
        } );

        return true;
    },

    requisition.prototype.load_measure_pax = function(_keywords = '') 
    {   
        var _complete = 0;
        var bus_id     = $.requisition.fetchID();
        if(bus_id == null)
        {
            bus_id=0;  
        }
        var table = new DataTable('#measurePaxTable', {
            ajax: { 
                url : _baseUrl + 'business-online-application/busn_measure_lists/'+ bus_id,
                type: "GET", 
                data: {
                    "query": _keywords,
                    "_token": _token
                }, 
                complete: function (data) {  
                    $.requisition.shorten();
                }
            },
            // bProcessing: true,
            bDestroy: true,
            pageLength: 10,
            order: [[0, "desc"]],
            serverSide: true,
            processing: true,
            pageLength: 10,
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('data-row-id', data.id);
                $(row).attr('data-row-code', data.charge_desc);
            },
            initComplete: function(){
                if (_status == 3) {
                    $('#measurePaxTable td:last-child, #measurePaxTable th:last-child').addClass('hidden');
                } else {
                    $('#measurePaxTable td:last-child, #measurePaxTable th:last-child').removeClass('hidden');
                }
            },   
            columns: [
                { data: 'buspx_no_units', orderable: true },
                { data: 'buspx_capacity', orderable: true },
                { data: 'charge_desc', orderable: true },
                { data: 'subclass_description', orderable: true },            ],
            columnDefs: [
                {  orderable: true, targets: 0, className: 'text-start' },
                {  orderable: true, targets: 1, className: 'text-start' },
                {  orderable: true, targets: 2, className: 'text-start' },
                {  orderable: true, targets: 3, className: 'text-start' },
            ]
        } );

        return true;
    },


    requisition.prototype.shorten = function() 
    {
        this.$body.find('.showLess').shorten({
            "showChars" : 20,
            "moreText"	: "More",
            "lessText"	: "Less"
        });
    },

    requisition.prototype.reload_address = function(busn_office_barangay_id,busn_office_building_no,busn_office_building_name,busn_office_add_block_no,busn_office_add_lot_no,busn_office_add_street_name,busn_office_add_subdivision, busn_office_is_same_as_main)
    {   
        var busn_id= $.requisition.fetchID();
        // $.requisition.preload_select3();
        if(busn_id != 0)
        {
            busn_office_barangay_id.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-online-application/reload-address/' + busn_id,
                success: function(response) {
                    console.log(response.data);
                        busn_office_barangay_id.append('<option value="' + response.data.busn_office_main_barangay_id + '">' + response.data.brgy_name + ', ' + response.data.mun_desc + ', ' + response.data.prov_desc + ', ' + response.data.reg_region +  '</option>');  
                        busn_office_barangay_id.val(response.data.busn_office_main_barangay_id); 
                        busn_office_building_no.val(response.data.busn_office_main_building_no);
                        busn_office_building_name.val(response.data.busn_office_main_building_name);
                        busn_office_add_block_no.val(response.data.busn_office_main_add_block_no);
                        busn_office_add_lot_no.val(response.data.busn_office_main_add_lot_no);
                        busn_office_add_street_name.val(response.data.busn_office_main_add_street_name);
                        busn_office_add_subdivision.val(response.data.busn_office_main_add_subdivision);
                },
                async: false
            });
        }
        else{
            
        }    

            return true;
    },

    requisition.prototype.reload_barangay = function(busn_office_barangay_id,busn_office_is_same_as_main)
    {  
            // $.requisition.preload_select3();
            busn_office_barangay_id.find('option').remove();
            $.ajax({
                type: "GET",
                url: _baseUrl + 'business-online-application/reload-barangay',
                success: function(response) {
                    console.log(response.data);
                    busn_office_barangay_id.append('<option value="">select a Barangay</option>');  
                    $.each(response.data, function(i, item) {
                        busn_office_barangay_id.append('<option value="' + item.id + '">' + item.brgy_name + ', ' + item.mun_desc + ', ' + item.prov_desc + ', ' + item.reg_region + '</option>');  
                    }); 
                },
                async: false
            });    

            return true;
    },
   
    

    requisition.prototype.preload_select3 = function()
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
    requisition.prototype.price_separator = function(input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
    },

    requisition.prototype.perfect_scrollbar = function()
    {
        if (document.querySelector(".table-responsive")) {
            var px = new PerfectScrollbar(".table-responsive", {
              wheelSpeed: 0.5,
              swipeEasing: 0,
              wheelPropagation: 1,
              minScrollbarLength: 40,
            });
        }

    },

    requisition.prototype.init = function()
    {   
        /*
        | ---------------------------------
        | # load initial content
        | ---------------------------------
        */
        $.requisition.preload_select3();
        // $.requisition.load_contents();
        // $.requisition.load_measure_pax();
        // $.requisition.load_requirment_doc();
        $.requisition.perfect_scrollbar();
        select3Ajax("flt_busn_office_barangay","this_is_filter","getBarngayLisByRptFlt");


        /*
        | ---------------------------------
        | # when requisition keywords onkeyup
        | ---------------------------------
        */
        this.$body.on('search', '#departmentalRequisitionTable_wrapper input[type="search"]', function (e) {
            $.requisition.load_contents('');
        });

       

       
    

        // this.$body.on('click', '#change-status-btn', function (e) {
        //     var _id   = $(this).data('id');
        //     var _url  = _baseUrl + 'business-online-application/verify-applications/'+ _id;
        //     location.href = _url;
            
        // });

        /*
        | ---------------------------------
        | # when modal is reset
        | ---------------------------------
        */
        this.$body.on('hidden.bs.modal', '#departmental-requisition-modal', function (e) {
            var _modal = $(this);
            _modal.find('.modal-header h5').html('Manage Application');
             _modal.find('input, textarea').val('').removeClass('is-invalid');
            _modal.find('select').val('').removeClass('is-invalid').closest('.form-group').removeClass('is-invalid');
            _modal.find('button.store-btn').removeClass('hidden');
            _modal.find('button.send-btn').removeClass('hidden');
            _modal.find('li button.nav-link').removeClass('active');
            _modal.find('li[role="departmental-request"] button.nav-link').removeClass('disabled').addClass('active');
            _modal.find('.tab-content .tab-pane').removeClass('show active');
            _modal.find('.tab-content .tab-pane[id="request-details"]').addClass('show active');
            _modal.find('select.select3').val('').trigger('change.select3'); 
            _modal.find('.item-layer').removeClass('hidden');
            _modal.find('#app_code').val(''); 
            $("#pr-details-tab").addClass('disabled');
            $("#bidding-details-tab").addClass('disabled');
             //_modal.find('form[name="requisitionForm"] input.required, form[name="requisitionForm"] select.required, form[name="requisitionForm"] textarea.required').prop('disabled', false);
            //  $.requisition.load_contents();
            $.requisition.load_data();
            // $.requisition.load_measure_pax();
            // $.requisition.load_requirment_doc();
            _requisitionID = 0;
        });

        /*
        | ---------------------------------
        | # when edit button is clicked
        | ---------------------------------
        */
        this.$body.on('click', '#Jq_datatablelist .edit-btn', function (e) {
            var _self     = $(this);
            var _id   = _self.data('id');
            var _modal    = $('#departmental-requisition-modal');
            var _formAppDet     = _modal.find('form[name="appDetailsForm"]');
            var _formBusnOpt     = _modal.find('form[name="busnOptForm"]');
            var _formBusnInfo     = _modal.find('form[name="requisitionForm"]');
            var _url      = _baseUrl + 'business-online-application/edit/' + _id;
            $("#pr-details-tab").removeClass('disabled');
            $("#bidding-details-tab").removeClass('disabled');
            $("#busn_tax_year").attr("readonly", true);
            // $(".save-btn").removeClass('hide');
            // $(".submit-btn").removeClass('hide');
            $("#tab").val(1);
            //console.log(_url);
            _requisitionID = _id;
            _self.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"><span class="visually-hidden">Loading...</span></div>');
            $.ajax({
                type: 'GET',
                url: _url,
                success: function(response) {
                        console.log("response",response)
                        //For Testing Data Don't remove this
                        $("#test_busn_id").val(response.data.id)
                        if(response.data.busn_app_status<=1 && response.isTest==1){
                            $("#testingDetails").removeClass("hide");
                        }else{
                            $("#testingDetails").addClass("hide");
                        }
                        $("#app_code").addClass("disabled-field");
                        // if(response.data.busn_app_status > 1){
                        //     $("#app_code").addClass("disabled-field");
                        // }else{
                        //     $("#app_code").removeClass("disabled-field");
                        // }
                        var dropdown = $("#app_code");
                        // Get the last option (using :last-child selector)
                        var lastOption = dropdown.find("option:last-child");
                        _status = response.data.app_code;
                        if(response.data.app_code == 3){
                            // Hide the last option
                           lastOption.show();
                        //    $(".save-btn").hide();
                        //    $(".submit-btn").hide();
                           $(".retire-btn").hide();
                       }else{
                           // Hide the last option
                           lastOption.hide();
                        //    $(".save-btn").show();
                        //    $(".submit-btn").show();
                           $(".retire-btn").show();
                       }
                        if (response.data.busn_bldg_is_owned == 1) {
                            _formAppDet.find('#yes_radio').prop('checked', true);
                            _formAppDet.find('#no_radio').prop('checked', false);
                        } else {
                            _formAppDet.find('#yes_radio').prop('checked', false);
                            _formAppDet.find('#no_radio').prop('checked', true);
                        }

                        if (response.data.busn_tax_incentive_enjoy == 1) {
                            _formAppDet.find('#yes_radio_en').prop('checked', true);
                            _formAppDet.find('#no_radio_en').prop('checked', false);
                        } else {
                            _formAppDet.find('#yes_radio_en').prop('checked', false);
                            _formAppDet.find('#no_radio_en').prop('checked', true);
                        }
                        
                        // End Testing Data
                    // var d5 =  $.requisition.load_contents();
                    // alert(response.data.busn_office_barangay_id);
                    // var d2 = $.requisition.load_measure_pax();
                    // var d3 = $.requisition.preload_select3();
                    var d4 = $.requisition.perfect_scrollbar();
                    // var d6 = $.requisition.load_requirment_doc();
                    var d1 = response.data;
                    if(response.data.client_id != null)
                    {
                        $.requisitionForm.reload_client_det(response.data.client_id);
                    }
                    $.when( d1,d4).done(function ( v1,v4) 
                    {
                        _modal.find('.modal-header h5').html('Application Details');
                        $.each(v1, function (k, v) {
                            if(k!='busn_office_main_barangay_id' && k!='busn_office_barangay_id'){
                                _formBusnInfo.find('input[name='+k+']').val(v);
                                _formBusnInfo.find('textarea[name='+k+']').val(v);
                                _formBusnInfo.find('select[name='+k+']').val(v);
                                _formBusnInfo.find('select[name='+k+'].select3').val(v).trigger('change.select3'); 
                            }
                        });
                        
                        $.each(v1, function (k, v) {
                            if(k!='busn_office_barangay_id' && k!='busn_office_main_barangay_id'){
                                _formAppDet.find('input[name='+k+']').val(v);
                                _formAppDet.find('select[name='+k+']').val(v);
                                _formAppDet.find('select[name='+k+']').val(v);
                                _formAppDet.find('select[name='+k+'].select3').val(v).trigger('change.select3');
                                if (k == 'busn_office_is_same_as_main' && v == 1) {
                                    _formAppDet.find('input[type="checkbox"][name='+k+']').prop('checked', true);
                                } else {
                                    _formAppDet.find('input[type="checkbox"][name='+k+']').prop('checked', false);
                                }
                               
                            }
                        });
                        $("#busn_office_main_barangay_id").html(d1.busn_office_main_barangay_id)
                        // select3Ajax("busn_office_main_barangay_id","div_office_barangay","getBarngayList");

                        //for the multi select
                        if(response.data.rp_code != ""){
                            var floor_val_id = $('#floor_val_id');
                            // $.requisitionForm.reload_rpt_info(response.data.rp_code);
                            // $.requisitionForm.loadFloorVal(response.data.rp_code,floor_val_id);
                            _formAppDet.find('select[name="floor_val_id[]"]').val(response.floor_val).trigger('change.select3');
                        }
                       


                        $("#busn_office_barangay_id").html(d1.busn_office_barangay_id);
                        $("#app_code").html(d1.app_code);
                        $("#pm_id").html(d1.pm_id);
                        $("#btype_id").html(d1.btype_id);
                        $("#busloc_id").html(d1.busloc_id);
                        $("#client_id").html(d1.client_id_html);
                        select3Ajax("busn_office_main_barangay_id","div_office_barangay","getBarngayList");
                        select3Ajax("busn_office_barangay_id","parent_busn_office_barangay_id","getBarngayLisByRpt");
                        select3Ajax("subclass_id","div_psic_subclass","getPsicSubclass");
                        _self.prop('disabled', false).html('<i class="ti-eye text-white"></i>');
                        _modal.modal('show');

                        applicationCodeDetails();
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
        | # Retire business Re-activate
        | ---------------------------------
        */
        this.$body.on('click', '#Jq_datatablelist .jqUnlock', function (e) {
            confirmUnlockAlert($(this))
        });
    }

    //init requisition
    $.requisition = new requisition, $.requisition.Constructor = requisition

}(window.jQuery),

//initializing requisition
function($) {
    "use strict";
    $.requisition.required_fields();
    // $.requisition.preload_select3();
    $.requisition.init();
}(window.jQuery);

function applicationCodeDetails(){
    if($("#app_code").val()==1){
        $("#pm_id").addClass("disabled-field")
    }
}
function changeBusinessDate(){
    if($("#test_tax_date").val()!=""){
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Are you sure want to update?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed){   
               updateBusinessDateForTest();
            }
        })
    }
}
function confirmUnlockAlert(_this){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Re-Activate the business application?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){   
           unclockBusiness(_this);
        }
    })
    
}
function unclockBusiness(_this){
    $.ajax({
        url :DIR+'business-online-application/unclockBusiness', // json datasource
        type: "POST", 
        dataType: "json",
        data: {
          "busn_id":_this.data('id'),
         "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            if(html.ESTATUS){
                Swal.fire({
                    title: "Oops...",
                    html:html.message ,
                    icon: "error",
                    type: "danger",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-danger btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
            }else{
                $.requisition.load_data();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Update Successfully.',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    })
}
function updateBusinessDateForTest(){
    $.ajax({
        url :DIR+'business-online-application/updateBusinessDateForTest', // json datasource
        type: "POST", 
        data: {
          "busn_id":$("#test_busn_id").val(),
          "test_app_code":$("#test_app_code").val(),
          "test_pm_id":$("#test_pm_id").val(),
          "test_tax_date": $("#test_tax_date").val(), 
         "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Update Successfully.',
                showConfirmButton: false,
                timer: 1500
            })
           location.reload(true);
        }
    })
}