$(document).ready(function () {
 
    $("#pk_code").change(function(){
    var id=$(this).val();
    getPropertyKindDetails(id);
  });

  $("#pc_class_code").change(function(){
    var id=$(this).val();
    getPropertyClassDetails(id);
  });

  $("#pau_actual_use_code").change(function(){
    var id=$(this).val();
    getPropertyActualUseDetails(id);
  });

  $("#rvy_revision_year").change(function(){
    var id=$(this).val();
    getRevisionDetails(id);
  });

  
 });
function getPropertyKindDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getPropertyKindDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
       $("#pk_code").val(html.pk_code)
      
       
      }
  });
}

function getPropertyClassDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getPropertyClassDetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
          $('.loadingGIF').hide();
         $("#pc_class_code").val(html.pc_class_code)
        
         
        }
    });
  }

  function getPropertyActualUseDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getPropertyActualUseDetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
          $('.loadingGIF').hide();
         $("#pau_actual_use_code").val(html.pau_actual_use_code)
        
         
        }
    });
  }

  function getRevisionDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getRevisionDetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
          $('.loadingGIF').hide();
         $("#rvy_revision_year").val(html.rvy_revision_year)
        
         
        }
    });
  }