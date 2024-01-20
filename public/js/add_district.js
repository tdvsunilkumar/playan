$(document).ready(function () {
 
    $("#loc_local_code").change(function(){
    var id=$(this).val();
    getLocalIdDetails(id);
  });

  
 });
function getLocalIdDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getLocalIdDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
       $("#loc_local_code").val(html.loc_local_code)
      
       
      }
  });
}