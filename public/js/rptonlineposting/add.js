$(document).ready(function () {
    select3Ajax("taxDeclaration","taxDeclaration_group","getTaxDecration");
    dataSearch();
    $("#saveData").click(function () {
    $("#validate-err").html("");
    var selectedValue = $("#taxDeclaration").val();
    var clientid = $("#id").val();

    if (!selectedValue) {
        $("#validate-err").text("Please Select a Tax Declaration.");
        return false;
    }

    $.ajax({
        type: "POST",
        url: DIR + 'checkExit',
        data: {
            "rp_code": $("#taxDeclaration").val(),
            "clients_id": $("#id").val(),
            "_token": $("#_csrf_token").val()
        },
        success: function (response) {
            if (response.message === "Tax Declaration already exists.") {
                $("#validate-err").text("Tax Declaration already exists.");
            } else {
                saveDeta();
            }
        },
        error: function (xhr) {
        if (xhr.status === 422) {
            // Handle the 422 error here and display the error message
            var responseJSON = xhr.responseJSON;
            if (responseJSON && responseJSON.message) {
                $("#validate-err").text(responseJSON.message);
            } else {
                // Default error message for unprocessable entity (422)
                $("#validate-err").text("Unprocessable Entity");
            }
        } else {
            // Handle other errors (e.g., network errors)
            console.error('AJAX error:', xhr.responseText);
        }
    }
       
    });
    dataSearch();
});

    
    $("#uploadAttachmentonly").click(function(){
        uploadAttachmentonly();
    });
    $(".deleteAttachment").click(function(){
        deleteAttachment($(this));
    })
    $(document).on('click', '.deleterow', function() {
        
        var recordid = $(this).attr('id');
        DeleteRecord(recordid)
    });
    $("#taxDeclaration").change(function(){
    var id=$(this).val();
    fetchTdDetails(id);
    });
    // $('#commonModal').off('change','#taxDeclaration').on('change','#taxDeclaration',function(){
    //      fetchTdDetails();
    // });
    $('#commonModal').off('click','.myCheckbox').on('click','.myCheckbox',function() {
        if($(this).is(":checked")) {
            var id=$("#id").val();
            getTaxDe(id);
            $("#taxDeclaration").select3({dropdownAutoWidth : false,dropdownParent: $("#representative")});
        } else {
           getTaxDeAll();
           select3Ajax("taxDeclaration","taxDeclaration_group","getTaxDecration");
        }
    }); 
    
});
function DeleteRecord(id){
  // alert(id);
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This action can not be undone. Do you want to continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed
            )
            {
               $.ajax({
              url :DIR+'deleteOnlineAccess', // json datasource
              type: "POST", 
              data: {
                "id": id, 
                "_token": $("#_csrf_token").val(),
              },
              success: function(html){
                Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Deleted Successfully.',
                showConfirmButton: false,
                timer: 1500
              })
               dataSearch();
               datatablefunction();
              }
          })
            }
        })
}


function saveDeta(){
    const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-primary',
           cancelButton: 'btn btn-dark'
       },
       buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
       text: "Are sure want to continue?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
   }).then((result) => {
        if(result.isConfirmed){
            showLoader();
            $.ajax({
                url :DIR+'AddOnlineAccess', // json datasource
                type: "POST", 
                dataType: "json",
                data: {
                    "clients_id":$("#id").val(),
                    "rp_code":$("#taxDeclaration").val(),
                    "rp_property_code":$("#rp_property_code").val(),
                    "_token": $("#_csrf_token").val(),
               },
               success: function(html){
                    hideLoader();
                   if(html.ESTATUS){
                        Swal.fire({
                         position: 'center',
                         icon: 'success',
                         title: 'Submit Successfully.',
                         showConfirmButton: false,
                         timer: 1500
                       })
                        dataSearch();
                        datatablefunction();
                   }
               }
           })
       }
   })
}

function dataSearch() {
    $.ajax({
        type: "POST",
        url: DIR + 'getClientOnlineAccess',
        data: {
            "clients_id": $("#id").val(),
            "_token": $("#_csrf_token").val()
        },
        dataType: "json",
        success: function(data) {
            $('.loadingGIF').hide();
            var table = $('#new_added_land_apraisal').DataTable();
            if (table) {
                table.destroy();
            }
            $('#new_added_land_apraisal').DataTable({
                "data": data.data,
                "paging": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 100],
                "ordering": true,
                "columns": [
                    { "data": "srno" },
                    { "data": "rp_tax_declaration_no" },
                    { "data": "full_name" },
                    { "data": "rp_pin_declaration_no" },
                    { "data": "propertyClass" },
                    { "data": "brgy_name" },
                    { "data": "cctUnitNo" },
                    { "data": "assessedValue" },
                    { "data": "action" }
                ],
                "createdRow": function (row, data, dataIndex) {
                    console.log('createdRow called');
                    if (data.class) {
                        $(row).addClass(data.class);
                    }
                }

            });
           dataTable.on('draw', function () {
                var recordsCount = dataTable.rows().count();
                $('select[name="new_added_land_apraisal_length"]').val(recordsCount);
            });
        }
    });
}
function getTaxDe(id){
   $.ajax({
        url :DIR+'getTaxDeclaresionOnlineDetails', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#taxDeclaration").html(html);
          }
        }
    })
}
function getTaxDeAll(){
   $.ajax({
        url :DIR+'getTaxDeclaresionOnlineDetailsAll', // json datasource
        type: "POST", 
        data: {
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#taxDeclaration").html(html);
           select3Ajax("taxDeclaration","taxDeclaration_group","getTaxDecration");
          }

        }
    })
}
function fetchTdDetails(id){
    showLoader();
     var id = $('#commonModal').find('#taxDeclaration').val();
    $.ajax({
                   url :DIR+'rptpropertyonlineaccess/gettddetails', // json datasource
                   type: "POST", 
                   data: {
                         "id": id, 
                         "_token": $("#_csrf_token").val(),
                   },
                   success: function(html){
                    hideLoader();
                    $('input[name=assess]').val(html.customername);
                    $('input[name=location]').val(html.brgy_name);
                    $('input[name=pin]').val(html.rp_pin_declaration_no);
                    $('input[name=cct_unit_no]').val(html.cctUnitNo);
                    $('input[name=class]').val(html.propertyClass);
                    $('input[name=assessed_value]').val(html.assessedValue);
                    $('input[name=last_or_no]').val(html.or_no);
                    $('input[name=last_or_date]').val(html.cashier_or_date);
                    $('input[name=rpo_code]').val(html.rpo_code);
                    $('input[name=rp_property_code]').val(html.rp_property_code);

                   },
                   error: function() {
                    hideLoader();   
                   }
               });
}

function uploadAttachmentonly(){
    $(".validate-err").html("");
    if (typeof $('#ora_document')[0].files[0]== "undefined") {
        $("#err_documents").html("Please upload Document");
        return false;
    }
    var formData = new FormData();
    formData.append('file', $('#ora_document')[0].files[0]);
    formData.append('healthCertId', $("#id").val());
    showLoader();
    $.ajax({
       url : DIR+'rptpropertyowner-uploadDocument',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
            hideLoader();
            var data = JSON.parse(data);
            if(data.ESTATUS==1){
                $("#err_end_requirement_id").html(data.message);
            }else{
                $("#end_requirement_id").val(0);
                $("#ora_document").val(null);
                if(data!=""){
                    $("#DocumentDtlsss").html(data.documentList);
                }
                Swal.fire({
                     position: 'center',
                     icon: 'success',
                     title: 'Document uploaded successfully.',
                     showConfirmButton: false,
                     timer: 1500
                })
                $(".deleteEndrosment").unbind("click");
                $(".deleteEndrosment").click(function(){
                    deleteEndrosment($(this));
                })
            }
       }
    });
}
 function deleteAttachment(thisval){
        var healthCertid = thisval.attr('healthCertid');
        var doc_id = thisval.attr('doc_id');
        const swalWithBootstrapButtons = Swal.mixin({
           customClass: {
               confirmButton: 'btn btn-success',
               cancelButton: 'btn btn-danger'
           },
           buttonsStyling: false
       })
       swalWithBootstrapButtons.fire({
           text: "Are you sure?",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes',
           cancelButtonText: 'No',
           reverseButtons: true
       }).then((result) => {
            if(result.isConfirmed){
                showLoader();
                $.ajax({
                   url :DIR+'rptpropertyowner-deleteAttachment', // json datasource
                   type: "POST", 
                   data: {
                        "healthCertid": healthCertid,
                        "doc_id": doc_id,  
                   },
                   success: function(html){
                    hideLoader();
                    thisval.closest("tr").remove();
                       Swal.fire({
                         position: 'center',
                         icon: 'success',
                         title: 'Update Successfully.',
                         showConfirmButton: false,
                         timer: 1500
                       })
                   }
               })
           }
       })
    }
    
    function datatablefunction()
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
        "columnDefs": [{ orderable: false, targets: [0] }],
        "pageLength": 10,
        "ajax":{ 
            url :DIR+'rptpropertyonlineaccess/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
       "columns": [
            { "data": "srno" },
            { "data": "taxpayername" },
            { "data": "address" },
            { "data": "mobile" },
            { "data": "email" },
            { "data": "access" },
            { "data": "view" , "className": "align-center" },
            { "data": "count" },
            { "data": "updatedby" },
            { "data": "date" },
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
            api.$('.defaultupdate').click(function() {
                var recordid = $(this).attr('id');
                var is_print = 0;
                if($(this).is(':checked')) { 
                   is_print=1;
                }
                 isDefaultUpdate(recordid,is_print);

            });
        }
    });  
}