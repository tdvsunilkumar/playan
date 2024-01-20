$(document).ready(function () {
  $('#ees_employee_id').on('change', function() {
     var id=$(this).val();
     getEmployeeDetails(id);
  });
});

$("#ees_employee_id").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#ees_employee_id").parent(),
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



function getEmployeeDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "Post",
      url: DIR+'getEmployeeDetails',
      data: filtervars,
       dataType: "html",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#ees_position").val(html)
        
      }
  });
}

