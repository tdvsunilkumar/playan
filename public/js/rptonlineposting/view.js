$(document).ready(function () {
    select3Ajax("taxDeclaration","taxDeclaration_group","getTaxDecration");
    dataSearchDetails();
   
});

function dataSearchDetails(){
 $.ajax({
        type: "POST",
        url: DIR+'getClientOnlineAccessView',
        "data": {
                "clients_id":$("#id").val(),
                "_token":$("#_csrf_token").val()
            }, 
       dataType: "html",
      success: function(html){ 
        $('.loadingGIF').hide();
        $('.ownerdetailsData').remove();
        $("#ownerdetailsView").after(html)
        
         
        }
    });
}
