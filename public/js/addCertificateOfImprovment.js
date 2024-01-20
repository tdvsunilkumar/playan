$(document).ready(function () {
  select3Ajax("rpc_owner_code","rpc_owner_code_group","getClientsNameDetailsAjax");
    select3Ajax("rpc_requestor_code","rpc_requestor_code_group","getClientsNameDetailsAjax");
    select3Ajax("rpc_city_assessor_code","rpc_city_assessor_code_group","hrEmployeesAjax");
    select3Ajax("rpc_certified_by_code","rpc_certified_by_code_group","hrEmployeesAjax");
    select3Ajaxorno("rpc_or_no","rpc_or_no_group","certOfNimprovementgetOrNumberajax");
     $("#rpc_certified_by_code").change(function(){
    var id=$(this).val();
    getCertPosition(id);
    });
    $(document).on('click','table tr',function(event) {
    // $("table tr").click(function(event) {
      if (event.target.type !== 'checkbox') {
        $(':checkbox', this).trigger('click');
      }
    });
    $("#search").click(function(){
        dataSearch();
      
    }); 
    $("#refreshRequestor").click(function(){
        refreshRequestor();
      
    });
  $('#rpc_or_no').on('change', function() {
        getIssueance($(this).val());
  });
  if($("#rpc_or_no").val()>0){
        getIssueance($("#rpc_or_no").val());

  }
 $(document).on('click','.clientdata',function() {
                var id = $('input[type=radio]:checked').val();
                // alert(value);
                getClient(id);
                getClientMech(id);
                getClientBuilding(id);
                getOrNumber(id);
  }); 
 $('input[type=radio]:checked').each(function() {
    var id = $(this).val();
    var rpc_code=$("#id").val();
    getClient(id,rpc_code);
});
$(document).on('click','.myCheckbox',function() {
    if($(this).is(":checked")) {
     var inputValue = $('#rvy_revision_year_id').val();
     $('#rvy_revision_year').val(inputValue);         
      // $("#myDiv").show();
   // getClientCode();
    } else {
      // $("#myDiv").hide();
      var recordid = $(this).attr('id');
      // DeleteRecord(recordid);
    }
  }); 
  
});
function getOrNumber(id){
   $.ajax({
 
        url :DIR+'getctoCashierIdPropertyImprovment', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rpc_or_no").html(html);
          }
        }
    })
}
function getIssueance(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'certOfNoLandgetIssuanceDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#rpc_or_date").val(html.cashier_or_date)
        $("#rpc_or_amount").val(html.tfc_amount)
        $("#cashier_id").val(html.id)
        $("#cashierd_id").val(html.ctodetailsId)
      }
  });
}
function getCertPosition(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getAppraisersPositionDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
       $("#rpc_certified_by_position").val(html.description)
      
      }
  });
}
function dataSearch(lastName){
 $.ajax({
        type: "POST",
        url: DIR+'getClientCodeImprovement',
         data: {
            "lastName": $("#lastName").val(),
            "_token": $("#_csrf_token").val()
        },
        dataType: "json",
        success: function (data) {
            $('.loadingGIF').hide();
            var table = $('#new_taxpayer_details').DataTable();
            if (table) {
                table.destroy();
            }

            // Create the DataTable with options
            var dataTable = $('#new_taxpayer_details').DataTable({
                "data": data.data,
                "paging": true,
                "pageLength": 5, // Default page length
                "lengthMenu": [5, 10, 25, 50, 100], // Options for the select box
                "ordering": true,
                "columns": [
                    { "data": "selectdata" },
                    { "data": "full_name" },
                    { "data": "address" },
                    { "data": "phone" },
                    { "data": "email" }
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data.class) {
                        $(row).addClass(data.class);
                    }
                }
            });

            // Set the default page length to 5
            dataTable.page.len(5).draw();
        }
    });
}
  
function getClient(id,rpc_code){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getClientsAppraisalsImprovement',
      data: {
            "id": id,
            "rpc_code": $("#id").val(),
            "_token": $("#_csrf_token").val()
        },
        dataType: "json",
        success: function (data) {
            $('.loadingGIF').hide();
            var table = $('#new_Property_details').DataTable();
            if (table) {
                table.destroy();
            }
            // Initialize DataTables
            var dataTable = $('#new_Property_details').DataTable({
                "data": data.data,
                "paging": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 100],
                "ordering": true,
                "columns": [
                    { "data": "srno" },
                    { "data": "full_name" },
                    { "data": "rp_tax_declaration_no" },
                    { "data": "pk_code" },
                    { "data": "pc_class_code" },
                    { "data": "rp_cadastral_lot_no" },
                    { "data": "brgy_name" },
                    { "data": "rpa_base_market_value" },
                    { "data": "rpa_assessed_value" },
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data.class) {
                        $(row).addClass(data.class);
                    }
                }
            });

            // Set the default page length to 5
            dataTable.page.len(5).draw();
        }
    });
}


function refreshRequestor(){
   $.ajax({
 
        url :DIR+'getClientsNameDetailsIdAllRequest', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rpc_requestor_code").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getClientMech(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getClientsAppraisalsMechImprovement',
      data: filtervars,

      dataType: "html",
      success: function(html){ 
        $('.ClientsAppraisalsDataMech').remove();
        $('.loadingGIF').hide();
        $("#land").after(html)

        
      }
  });
}
function DeleteRecord(id){
  // alert(id);
        // const swalWithBootstrapButtons = Swal.mixin({
        //     customClass: {
        //         confirmButton: 'btn btn-success',
        //         cancelButton: 'btn btn-danger'
        //     },
        //     buttonsStyling: false
        // })
        // swalWithBootstrapButtons.fire({
        //     title: 'Are you sure?',
        //     text: "This action can not be undone. Do you want to continue?",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonText: 'Yes',
        //     cancelButtonText: 'No',
        //     reverseButtons: true
        // }).then((result) => {
        //     if(result.isConfirmed
        //     )
        //     {
               $.ajax({
              url :DIR+'rptpropertycertofpropertyholding/delete', // json datasource
              type: "POST", 
              data: {
                "id": id, 
                "_token": $("#_csrf_token").val(),
              },
              success: function(html){
                // Swal.fire({
                // position: 'center',
                // icon: 'success',
                // title: 'Unchecked Successfully.',
                // showConfirmButton: false,
                // timer: 1500
              // })
                 // location.reload();
                 // datatablefunction();
                 // setInterval(function(){
                
                 //  });
              }
          })
            }
//         })
// }
function getClientBuilding(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getClientsAppraisalsBuildingImprovement',
      data: filtervars,

      dataType: "html",
      success: function(html){ 
        $('.ClientsAppraisalsDataBuilding').remove();
        $('.loadingGIF').hide();
        $("#mach").after(html)

        
      }
  });
}

function getClientCode(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getClientCodeAddresss',
      data: filtervars,

      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#clientcode").val(html.id)
        $("#clientaddress").val(html.rpo_address_house_lot_no+','+html.rpo_address_street_name+''+html.rpo_address_subdivision)
      }
  });
}
$("#loadStructuralCharacter").unbind("click");
  $("#loadStructuralCharacter").click(function(){
    $('#addStructuralCharacterModal').modal('show');
    loadBuildingStructureForm(id = '');
    
    $(document).on('click','.addStructuralCharacterModal',function(){
      $('#addStructuralCharacterModal').modal('hide');
    });
}); 


$(document).off('submitClient','#storeBuildingStructural').on('submitClient','#storeBuildingStructural',function(e){
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
      type: "post",
      url: url,
      data: data,
      dataType: "json",
      success: function(html){ 
        if(html.status == 'validation_error'){
          $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
        }if(html.status == 'success'){
          $('.validate-err').html('');
          $('#addStructuralCharacterModal').modal('hide');
          /*loadLandAppraisal($('input[name=id]').val());
          loadAssessementSumary($('input[name=id]').val());*/
        }
      }
       });
    });

function loadBuildingStructureForm() {
  $('.loadingGIF').show();
  var filtervars = {
      
  }; 
  $.ajax({
      type: "post",
      url: DIR+'rptcertificateofnoImprovement/client',
      data: filtervars,
      dataType: "html",
      success: function(html){ 
        $('#structuralCharacterForm').html('');
        $('#structuralCharacterForm').html(html);
        var revisionYearId = $('.rvy_revision_year_id').val();
        var revisionYear   = $('input[name=rvy_revision_year]').val();
        setTimeout(function(){ 
          $('#addStructuralCharacterModal').find(".country").select3({});
         
              }, 500);
      }
  });
}

  
