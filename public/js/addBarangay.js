$(document).ready(function () {
   
    $('#reg_no').on('change', function() {
        getprofileRegioncode($(this).val());
    });
    $('#prov_no').on('change', function() {
        getprofileProvcode($(this).val());
    });
    $('#mun_no').on('change', function() {
        getprofileDist($(this).val());
    });
    
 });




function getprofileRegioncode(id){
    $.ajax({
        url :DIR+'getprofileRegioncodeId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#prov_no").html(html);
           $("#mun_no").html('<option>Please Select</option>');
           $("#dist_code").html('<option>Please Select</option>');
          }
        }
    })
}

function getprofileProvcode(id){
    $.ajax({
        url :DIR+'getprofileProvcodeId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#mun_no").html(html);
           $("#dist_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getprofileDist(id){
    $.ajax({
        url :DIR+'getDistrictCodes', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#dist_code").html(html);
          }
        }
    })
}

