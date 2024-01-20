$(document).ready(function () {

     if($("#tfoc_id option:selected").val() > 0 ){
          var val = $("#tfoc_id option:selected").val();
          getserviceName(val);
     }

     $('#tfoc_id').on('change', function() {
        var id =$(this).val();
         getserviceName(id);
    });
     $("#btn_addmore_requirement").click(function(){
            addmorerequirements();
    });
    $(".btn_cancel_requiremnt").click(function(){
         $(this).closest(".removerequirementdata").remove();
    });

    $('#is_main_service').click(function() {
            if ($(this).is(':checked')) {
                 $("#eat_id").prop('required',true);
                 $("#emf_id").prop('required',true);
                 $("#top_transaction_type_id").prop('required',true);
            }else{
                $("#eat_id").prop('required',false);
                $("#emf_id").prop('required',false);
                $("#top_transaction_type_id").prop('required',false);
            }
        });   

  function  getserviceName(aglcode){
     var id =aglcode;
       $.ajax({
            url :DIR+'engservice/getserviceName', // json datasource
            type: "POST", 
            data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#servicefee").val(html);
            }
        })
   } 
    function addmorerequirements(){
     var prevLength = $("#RequirementDetails").find(".removerequirementdata").length;
     var html = $("#hidenRequirementHtml").html();
        $("#RequirementDetails").append(html);
        $(".btn_cancel_requiremnt").click(function(){
        $(this).closest(".removerequirementdata").remove();
            var cnt = $("#RequirementDetails").find(".removerequirementdata").length;
            $("#hidenRequirementHtml").find('.req_id').attr('id','req_id'+cnt);
        });
        var classid = $("#RequirementDetails").find(".removerequirementdata").length;
        $("#req_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#reqdropdown")}); 
        $("#hidenRequirementHtml").find('.req_id').attr('id','req_id'+classid);
    }   
});