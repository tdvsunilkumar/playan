$(document).ready(function () {
    select3Ajax("rpc_owner_code","rpc_owner_code_group","getClientsNameDetailsAjax");
    select3Ajax("rpc_requestor_code","rpc_requestor_code_group","getClientsNameDetailsAjax");
    select3Ajax("rpc_city_assessor_code","rpc_city_assessor_code_group","hrEmployeesAjax");
    select3Ajax("rpc_certified_by_code","rpc_certified_by_code_group","hrEmployeesAjax");
    select3Ajaxorno("rpc_or_no","rpc_or_no_group","certOfNoLandgetOrNumberDetails");
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
    $("#refresh").click(function(){
        getClientsNameDetailsIdallData();
    });
    $("#refreshRequestor").click(function(){
        refreshRequestor();
    });
    $('#rpc_owner_code').on('change', function() {
        //getOrNumber($(this).val());
    });
    $("#rpc_requestor_code").on('change', function() {
        //select3Ajaxorno("rpc_or_no","rpc_or_no_group","certOfNoLandgetOrNumberDetails");
    });
  if($("#rpc_owner_code").val()>0){
        getCertTaxpayersname($("#rpc_owner_code").val());
        // getOrNumber($("#rpc_owner_code").val());
   }
  $('#rpc_or_no').on('change', function() {
        getIssueance($(this).val());
  });
  if($("#rpc_or_no").val()>0){
        getIssueance($("#rpc_or_no").val());
  }
 $(document).on('click','.clientdata',function() {
                var id = $('input[type=radio]:checked').val();
                if($(this).is(":checked")) {
                getClient(id);
                getClientMech(id);
                getClientBuilding(id);
                getCertTaxpayersname(id);
                //getOrNumber(id);
                // $("#myDiv").show();
             // getClientCode();
              } else {
                // $("#myDiv").hide();
              }
            });
$('input[type=radio]:checked').each(function() {
  var id = $(this).val();
  var rpc_code=$("#id").val();
  getClient(id,rpc_code);
});
// $(document).on('click','.clientdata',function() {
//               if($(this).is(":checked")) { 
//                 // alert(value);
//                 getClient($(this).val());
//             }
//                 // getClient(id);
//             });

$(document).on('click','.myCheckbox',function() {
    if($(this).is(":checked")) {
             
      // $("#myDiv").show();
   // getClientCode();
    } else {
      // $("#myDiv").hide();
    }
  }); 
  
});
function getOrNumber(id){
   $.ajax({
        url :DIR+'certOfNoLandgetOrNumberDetails', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "taxpayerid":$("#rpc_owner_code option:selected").val(),
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
function getClientsNameDetailsIdallData(){
   $.ajax({
 
        url :DIR+'getClientsNameDetailsIdAll', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rpc_owner_code").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
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
function getCertTaxpayersname(id){
   $.ajax({
 
        url :DIR+'getClientsNameDetailsId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rpc_owner_code").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
// function getCertTaxpayersname(id){
//   $('.loadingGIF').show();
//   var filtervars = {
//       id:id
//   }; 
//   $.ajax({
//       type: "GET",
//       url: DIR+'getClientsNameDetailsId',
//       data: filtervars,
//       dataType: "json",
//       success: function(html){ 
//         $('.loadingGIF').hide();
//         $("#taxpayerName").val(html.rpo_first_name+' '+html.rpo_middle_name+' '+html.rpo_custom_last_name)
      
//       }
//   });
// }


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



// function dataSearch(firstName,lastName){
//  $.ajax({
//         type: "POST",
//         url: DIR+'getClientCodeAddressnolandHolding',
//         "data": {
//                 "firstName":$("#firstName").val(),
//                 "lastName":$("#lastName").val(),
//                 "_token":$("#_csrf_token").val()
//             }, 
//        dataType: "html",
//       success: function(html){ 
//         $('.loadingGIF').hide();
//         $('.ownerdetailsData').remove();
//         $("#ownerdetails").after(html)
        
         
//         }
//     });
//   }
// 
function dataSearch(firstName, lastName) {
    $.ajax({
        type: "POST",
        url: DIR + 'getClientCodeAddressnolandHolding',
        data: {
            "firstName": $("#firstName").val(),
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


function getClient(id) {
    $.ajax({
        type: "POST",
        url: DIR + 'getClientsAppraisalsNoLand',
        data: {
            "id": id,
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




function getClientMech(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getClientsAppraisalsMechNoLand',
      data: filtervars,

      dataType: "html",
      success: function(html){ 
        $('.ClientsAppraisalsDataMech').remove();
        $('.loadingGIF').hide();
        $("#land").after(html)

        
      }
  });
}

function getClientBuilding(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getClientsAppraisalsBuildingNoLand',
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
      url: DIR+'getClientCodeAddressImprovement',
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
$("#loadStructuralCharacter2").unbind("click");
  $("#loadStructuralCharacter2").click(function(){
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
      url: DIR+'rptcertificateofnolandholding/client',
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

  
