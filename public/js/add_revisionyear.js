$(document).ready(function () {
 
    $("#rvy_city_assessor_assistant_code").change(function(){
    var id=$(this).val();
    getProfileDetails(id);
  });

    $("#rvy_city_assessor_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#rvy_city_assessor_code").parent(),
    ajax: {
        url: DIR+'real-property/revision-setup/assessor-ajax-request',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

    $("#rvy_city_assessor_assistant_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#rvy_city_assessor_assistant_code").parent(),
    ajax: {
        url: DIR+'real-property/revision-setup/assessor-ajax-request',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

  
 });
function getProfileDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getProfileDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
       $("#rvy_city_assessor_assistant_code").val(html.rvy_city_assessor_assistant_code)
       
      }
  });
}


 
  
