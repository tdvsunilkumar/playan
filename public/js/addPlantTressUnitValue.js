$(document).ready(function () {
  $('#ps_subclass_code').select3({dropdownAutoWidth : false,dropdownParent : '#ps_subclass_code_div'});
  $('form').submit(function () {
        $("#mun_no").attr("disabled", false);
        $("#rvy_revision_year").attr("disabled", false);
        $("#pt_ptrees_code").attr("disabled", false);
        $("#ps_subclass_code").attr("disabled", false);
    });
  $('#ps_subclass_code').on('change', function() {
     var id=$(this).val();
       getSubClassDetails(id);
  });
   $("#ps_subclass_code").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $("#ps_subclass_code").parent(),
    ajax: {
        url: DIR+'class-subclass-ajax-request',
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
  $("#pt_ptrees_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#pt_ptrees_code").parent(),
    ajax: {
        url: DIR+'planttree-ajax-request',
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
  if($("#ps_subclass_code").val()>0){
        getSubClassDetails($("#ps_subclass_code").val());
      }
  $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
    });    

    $('#revisionyear').select3('destroy');
    $('#revisionyear').select3();
});
function getSubClassDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getPlantTreesSubClassDetailss',
      data: filtervars,

      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#pc_class_code").val(html.pc_class_id)
        $("#ps_subclass_desc").val(html.ps_subclass_code+'-'+html.ps_subclass_desc)
        $("#pc_class_desc").val(html.pc_class_code+'-'+html.pc_class_description)
        
       
      }
  });
}

function getSubClass(id){
  $.ajax({

       url :DIR+'getsubclass', // json datasource
       type: "POST", 
       data: {
         "id": id, 
         "_token": $("#_csrf_token").val(),
       },
       success: function(html){
         if(html !=''){
          $("#ps_subclass_code").html(html);
          // $("#ps_subclass_code").html('<option>Please Select</option>');
         }
       }
   })
}





 
