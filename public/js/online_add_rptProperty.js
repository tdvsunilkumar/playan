$(document).ready(function () {
    //alert();
      $("#clientsregistered").select3({dropdownAutoWidth : false,dropdownParent: $("#clientsregistered-group")});
      // $("#p_barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $(".p_barangay_id_group")});
      $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
      $("#search").select3({dropdownAutoWidth : false,dropdownParent: $("#p_search")});
      $("#country_ext").select3({dropdownAutoWidth : false,dropdownParent: $("#country_div_ext")});
      $('#commonModal').find("#country").select3({dropdownParent : '#commonModal'});
      if($("#ref_client_id").val() != 0){
        var client_id=$("#ref_client_id").val();
        getClientDetaildById(client_id);
      }
    //   select3Ajax("p_barangay_id_no_ext","p_barangay_id_group_ext","getBarngayList");
      $("#p_code").change(function(){
          var id=$(this).val();
          getProfileDetails(id);
      });
      $("#clientsregistered").change(function(){
          var id=$(this).val();
          if(id){ getprofiledata(id); }
      }) 
      $("#c_mobile").change(function(){
            if($(this).is(':checked') == true){ 
                $('#p_mobile_no_ext').val( $('#p_mobile_no').val());
            } 
            if($(this).is(':checked') == false){
                $('#p_mobile_no_ext').val( $('#p_mobile_no_old').val());
            }
        }) 
      $("#c_email").change(function(){
       
            if($(this).is(':checked') == true){ 
                $('#p_email_address_ext').val( $('#p_email_address').val());
             }
             if($(this).is(':checked') == false){
                $('#p_email_address_ext').val( $('#p_email_address_old').val());
             }
        })  
    /*$(document).on('keyup','.phonenumber',function(){
        var x = this.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
              this.value = !x[2] ? x[1] : '' + x[1] + ' ' + x[2] + (x[3] ? ' ' + x[3] : '');
      });*/
  
      $(document).on('keyup','.phonenumber',function(){
        var x = this.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})/);
        console.log(x);
        this.value = !x[2] ? x[1] : '' +'+'+x[1] + ' (' + x[2] + (x[3] ? ') ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
      });
  
      $('#search').change(function(e) {
        
        e.preventDefault();
        var client_id =  $('#search').val();
        if(client_id > 0){
            getClientDetaildById(client_id);        
        }
    });
    $('#clear_data').click(function(e) {
        e.preventDefault();
        $('#search_data').val(1);
        $('#rpo_custom_last_name_ext').val("");
        $('#rpo_first_name_ext').val("");
        $('#rpo_middle_name_ext').val("");
        $('#suffix_ext').val("");
        $('#rpo_address_house_lot_no_ext').val("");
        $('#rpo_address_street_name_ext').val("");
        $('#rpo_address_subdivision_ext').val("");
        // $('#p_barangay_id_no_ext').val(response.data.p_barangay_id_no);
        $('#p_telephone_no_ext').val("");
        $('#p_mobile_no_ext').val("");
        $('#p_fax_no_ext').val("");
        $('#p_email_address_ext').val("");
        $('#p_tin_no_ext').val("");
        $('#gender_ext').val("");
        $('#dateofbirth_ext').val("");
        $('#p_mobile_no_old').val("");
        $('#p_email_address_old').val("");
        // Find the option element with the matching value
        var optionToSelect = $('#country_ext').find('option[value=""]');
        if (optionToSelect.length) {
            // Trigger the 'change' event to select the option
            optionToSelect.prop('selected', true);
            $('#country_ext').trigger('change');
        } 
        var optionToSel = $('#search').find('option[value=""]');
        if (optionToSel.length) {
            // Trigger the 'change' event to select the option
            optionToSel.prop('selected', true);
            $('#search').trigger('change');
        } 
        
        $("#p_barangay_id_no_ext").val("");
        // select3Ajax("p_barangay_id_no_ext","p_barangay_id_group_ext","getBarngayList");
        $('#c_mobile').prop('checked', false);
        $('#c_email').prop('checked', false);
        $('#online').css('display', 'none');
        $('#offline').css('display', 'none'); 
        var client_id =  $('#search').val();    
        BploDatatableFunction(client_id);
        EngDatatablefunction(client_id);
        PlanningDatatablefunction(client_id);
        OccupancyDatatablefunction(client_id);
        RealPropertyDatatablefunction(client_id);   
    });
  
    $('#approve').click(function(e) {
        //e.preventDefault();
        $(".validate-err").html('');
        //$("form input[name='submit']").unbind("click");
        var myform = $('#storePropertyOwnerForm');
        var id =  $('#id').val();
        var _self = $('#approve');
        var disabled = myform.find(':input:disabled').removeAttr('disabled');
        var data = myform.serialize().split("&");
        var government_id_attachment=$('#government_id_attachment').val();
        console.log(government_id_attachment);
       
        disabled.attr('disabled','disabled');
        _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right disabled');
        var obj={};
        for(var key in data){
          obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        })
        if (government_id_attachment == 0) {
            Swal.fire({
                title: "Oops...",
                html: "Proof of identity documents attachment must be completed first before the palayan user can accept the taxpayers online registration.",
                icon: "warning",
                type: "warning",
                showCancelButton: false,
                closeOnConfirm: true,
                confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
            });
        } 
        swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Are you sure want to approve?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed){
                showLoader();
                $.ajax({
                  url :$('#storePropertyOwnerForm').attr("action")+'/approve/'+id, // json datasource
                  type: "POST", 
                  data: obj,
                  dataType: 'json',
                  success: function(response){
                    hideLoader();
                    if(response.success == false){
                      $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
                        Swal.fire({
                            title: "Oops...",
                            html: response.msg,
                            icon: "warning",
                            type: "warning",
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                        });
                    }else{
                      window.location.href = $('#storePropertyOwnerForm').attr("action");
                    }
                  }
              })
            }else{
                $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
            }
        });
    });
  
    $('#decline').click(function(e) {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Are you sure want to decline?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed){
                //e.preventDefault();
                $(".validate-err").html('');
                //$("form input[name='submit']").unbind("click");
                var myform = $('#storePropertyOwnerForm');
                var id =  $('#id').val();
                var _self = $('#decline');
                var disabled = myform.find(':input:disabled').removeAttr('disabled');
                var data = myform.serialize().split("&");
                disabled.attr('disabled','disabled');
                _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right disabled');
                var obj={};
                for(var key in data){
                    obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
                }
                $.ajax({
                    url :$('#storePropertyOwnerForm').attr("action")+'/decline/'+id, // json datasource
                    type: "POST", 
                    data: obj,
                    dataType: 'json',
                    success: function(response){
                        $('#decline').html('Decline').prop('disabled', false).removeClass('disabled');
                        if(response.success == false){
                              Swal.fire({
                                  title: "Oops...",
                                  html: response.msg,
                                  icon: "warning",
                                  type: "warning",
                                  showCancelButton: false,
                                  closeOnConfirm: true,
                                  confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                              });
                        }else{
                            window.location.href = $('#storePropertyOwnerForm').attr("action");
                        }
                    }
                })
            }
        });
    });
});

   function BploDatatableFunction(client_id)
    {
        var dropdown_html=get_page_number('1'); 
        var table = $('#bplo_datatable').DataTable({ 
            "language": {
                "infoFiltered":"",
                "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
            },
            // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
    
            //     oLanguage: {
            //         sLengthMenu: dropdown_html
            // },
            dom: "rtip",
            "bProcessing": true,
            "serverSide": true,
            "bDestroy": true,
            "searching": false,
            "order": [],
            "columnDefs": [{ orderable: false, targets: [0] }],
            "pageLength": 3,
            "ajax":{ 
                url :DIR+'taxpayer-online-registration/getBploBusinessList', // json datasource
                type: "POST", 
                "data": {
                    "q":$("#q").val(),
                    'client_id':client_id,
                    "_token":$("#_csrf_token").val()
                }, 
                error: function(html){
                }
            },
        "columns": [
                { "data": "srno" },
                { "data": "busn_id_no" },
                { "data": "busn_name" },
                { "data": "barangay" },
                { "data": "app_type" },
                { "data": "app_date" },
                { "data": "last_pay_date" }
            ],
            drawCallback: function(s){ 
                var api = this.api();
                var info=table.page.info();
                var dropdown_html=get_page_number(info.recordsTotal,info.length);
                $("#common_pagesize").html(dropdown_html);
                // $('#common_pagesize').css('display', 'none');
                api.$('.deleterow').click(function() {
                    var recordid = $(this).attr('id');
                    DeleteRecord(recordid);
                });
                api.$('.activeinactive').click(function() {
                    var recordid = $(this).attr('id');
                    var is_activeinactive = $(this).attr('value');
                    ActiveInactiveUpdate(recordid,is_activeinactive);
        
                });
            }
        });  
    }
    function EngDatatablefunction(client_id)
    {
        var dropdown_html=get_page_number('1'); 
        var table = $('#eng_datatable').DataTable({ 
            "language": {
                "infoFiltered":"",
                "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
            },
            // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
    
            //     oLanguage: {
            //         sLengthMenu: dropdown_html
            // },
            dom: "rtip",
            "bProcessing": true,
            "serverSide": true,
            "bDestroy": true,
            "searching": false,
            "order": [],
            "columnDefs": [{ orderable: false, targets: [0,4,5,6] }],
            "pageLength": 3,
            "ajax":{ 
                url :DIR+'taxpayer-online-registration/engjobrequestList', // json datasource
                type: "POST", 
                "data": {
                    "q":$("#q").val(),
                    'client_id':client_id,
                    "_token":$("#_csrf_token").val()
                }, 
                error: function(html){
                }
            },
        "columns": [
                { "data": "srno" },
                { "data": "jobreqno" },
                { "data": "ownername" },
                { "data": "services" },

                { "data": "permtno" },
              
                { "data": "ornumber" },
                { "data": "ordate" },
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
                api.$('.print').click(function() {
                    var id = $(this).attr('id');
                    var serviceid   = $(this).attr('serviceid');
                    printpermit(id,serviceid);
                });
            }
        });  
    }  
    function PlanningDatatablefunction(client_id)
    {
        var dropdown_html=get_page_number('1'); 
        var table = $('#planning_datatable').DataTable({ 
            "language": {
                "infoFiltered":"",
                "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
            },
            // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
    
            //     oLanguage: {
            //         sLengthMenu: dropdown_html
            // },
            dom: "rtip",
            "bProcessing": true,
            "serverSide": true,
            "bDestroy": true,
            "searching": false,
            "order": [],
            "columnDefs": [{ orderable: false, targets: [0,5] },{ orderable: false, targets: [0,6] },{ orderable: false, targets: [0,7] }],
            "pageLength": 3,
            "ajax":{ 
                url :DIR+'taxpayer-online-registration/cpdodevelopmentappList', // json datasource
                type: "POST", 
                "data": {
                    "q":$("#q").val(),
                    'client_id':client_id,
                    "_token":$("#_csrf_token").val()
                }, 
                error: function(html){
                }
            },
        "columns": [
                { "data": "srno" },
                { "data": "caf_control_no" },
                { "data": "ownername" },
                { "data": "projectname" },
                { "data": "address" },
                { "data": "sdetail" },
                { "data": "orno" },
                { "data": "ordate" }
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
                api.$('.viewreq').click(function() {
                    var recordid = $(this).attr('id');
                    viewrequirements(recordid);
        
                });
            }
        });  
    }
    function OccupancyDatatablefunction(client_id)
    {
        var dropdown_html=get_page_number('1'); 
        var table = $('#occupation_datatable').DataTable({ 
            "language": {
                "infoFiltered":"",
                "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
            },
            // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
    
            //     oLanguage: {
            //         sLengthMenu: dropdown_html
            // },
            dom: "rtip",
            "bProcessing": true,
            "serverSide": true,
            "bDestroy": true,
            "searching": false,
            "order": [],
            "columnDefs": [{ orderable: false, targets: [0,7] }],
            "pageLength": 3,
            "ajax":{ 
                url :DIR+'taxpayer-online-registration/engoccupancyappList', // json datasource
                type: "POST", 
                "data": {
                    "q":$("#q").val(),
                    'client_id':client_id,
                    "_token":$("#_csrf_token").val()
                }, 
                error: function(html){
                }
            },
        "columns": [
                { "data": "srno" },
                { "data": "ebpa_id" },
                { "data": "ownername" },
                { "data": "eoa_application_type" },
                { "data": "appno" },
                { "data": "topno" },
       
                { "data": "ornumber" },
                { "data": "ordate" },
               
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
                api.$('.print').click(function() {
                    var appid = $(this).attr('id');
                    printoccupancy(appid);
                });
            }
        });  
    }
    function RealPropertyDatatablefunction(client_id)
    {
        var dropdown_html=get_page_number('1'); 
        var table = $('#realproperty_datatable').DataTable({ 
            "language": {
                "infoFiltered":"",
                "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
            },
            // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
    
            //     oLanguage: {
            //         sLengthMenu: dropdown_html
            // },
            dom: "rtip",
            "bProcessing": true,
            "serverSide": true,
            "bDestroy": true,
            "searching": false,
            "order": [],
            "columnDefs": [{ orderable: false, targets: [0] }],
            "pageLength": 3,
            "ajax":{ 
                url :DIR+'taxpayer-online-registration/realPropertyList', // json datasource
                type: "POST", 
                "data": {
                    "q":$("#q").val(),
                    'client_id':client_id,
                    "_token":$("#_csrf_token").val()
                }, 
                error: function(html){
                }
            },
        "columns": [
                { "data": "srno" },
                { "data": "td_no" },
                { "data": "taxpayer_name" },
                { "data": "barangay" },
                { "data": "pin" },
                { "data": "lot" },
                { "data": "class" },
                { "data": "assessedValue" }
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
                api.$('.print').click(function() {
                    var appid = $(this).attr('id');
                    printoccupancy(appid);
                });
            }
        });  
    }
    
  function getProfileDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'rptpropertyowner/getProfileDetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
         $('.loadingGIF').hide();
         $("#brgy_code").val(html.brgy_code)
         $("#p_telephone_no").val(html.p_telephone_no)
         $("#p_mobile_no").val(html.p_mobile_no)
         $("#p_fax_no").val(html.p_fax_no)
         $("#p_tin_no").val(html.p_tin_no)
         $("#p_email_address").val(html.p_email_address)
         $('#p_barangay_id').html(html.p_barangay_id_no);
         select3Ajax("p_barangay_id","p_barangay_id_group","getBarngayList");
         //$("#p_barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $(".p_barangay_id_group")});
         //$('#p_barangay_id_no>option:eq('+arr.p_barangay_id_no+')').prop('selected', true);
         
        }
    });
  }
  
   function getprofiledata(id){
    $('.loadingGIF').show();
    var filtervars = {
        pid:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'rptpropertyowner/getClientsDetails',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
          if(arr){
            console.log(arr);
            $("#rpo_custom_last_name").text(arr.rpo_custom_last_name);
            $("#rpo_first_name").val(arr.rpo_first_name);
            $("#rpo_middle_name").val(arr.rpo_middle_name);
            $("#suffix").val(arr.suffix); 
            $("#rpo_address_house_lot_no").val(arr.rpo_address_house_lot_no);
            $("#rpo_address_street_name").val(arr.rpo_address_street_name);
            $("#rpo_address_subdivision").val(arr.rpo_address_subdivision);
            $("#p_telephone_no").val(arr.p_telephone_no);
            $('#p_barangay_id').html(arr.p_barangay_id_no);
            select3Ajax("p_barangay_id","p_barangay_id_group","getBarngayList");
            $("#p_mobile_no").val(arr.p_mobile_no);
            $("#p_fax_no").val(arr.p_fax_no);
            $("#p_email_address").val(arr.p_email_address);
            $("#p_tin_no").val(arr.p_tin_no);
            $('#country>option:eq('+arr.country+')').prop('selected', true);
            $('#gender>option:eq('+arr.gender+')').prop('selected', true);
            $("#dateofbirth").val(arr.dateofbirth);
          }
        }
    });
  } 
  function getClientDetaildById(client_id)
  {
    showLoader();
    $.ajax({
        url :$('#storePropertyOwnerForm').attr("action")+'/get-client-details/'+client_id, // json datasource
        type: "GET",
        success: function (response) {
            hideLoader();
            console.log(client_id);
            $('#search_data').val(1);
            $('#rpo_custom_last_name_ext').val(response.data.rpo_custom_last_name);
            $('#rpo_first_name_ext').val(response.data.rpo_first_name);
            $('#rpo_middle_name_ext').val(response.data.rpo_middle_name);
            $('#suffix_ext').val(response.data.suffix);
            $('#rpo_address_house_lot_no_ext').val(response.data.rpo_address_house_lot_no);
            $('#rpo_address_street_name_ext').val(response.data.rpo_address_street_name);
            $('#rpo_address_subdivision_ext').val(response.data.rpo_address_subdivision);
            // $('#p_barangay_id_no_ext').val(response.data.p_barangay_id_no);

            $('#p_telephone_no_ext').val(response.data.p_telephone_no);
            $('#p_mobile_no_ext').val(response.data.p_mobile_no);
            $('#p_fax_no_ext').val(response.data.p_fax_no);
            $('#p_email_address_ext').val(response.data.p_email_address);
            $('#p_tin_no_ext').val(response.data.p_tin_no);
            // $('#country_ext').val(response.data.country);
            $('#gender_ext').val(response.data.gender);
            $('#dateofbirth_ext').val(response.data.dateofbirth);

            $('#p_mobile_no_old').val(response.data.p_mobile_no);
            $('#p_email_address_old').val(response.data.p_email_address);

            // Find the option element with the matching value
            var optionToSelect = $('#country_ext').find('option[value="' + response.data.country + '"]');
            if (optionToSelect.length) {
                // Trigger the 'change' event to select the option
                optionToSelect.prop('selected', true);
                $('#country_ext').trigger('change');
            } 
            $("#p_barangay_id_no_ext").val(response.data.p_barangay_desc);
            $('#c_mobile').prop('checked', false);
            $('#c_email').prop('checked', false);
            if(response.data.is_online == 1){
                $('#online').css('display', 'block');
                $('#offline').css('display', 'none');
            }else{
                $('#offline').css('display', 'block');
                $('#online').css('display', 'none');
            }
            BploDatatableFunction(client_id);
            EngDatatablefunction(client_id);
            PlanningDatatablefunction(client_id);
            OccupancyDatatablefunction(client_id);
            RealPropertyDatatablefunction(client_id);
           
        }
    })
  }
  
  
   
    
  