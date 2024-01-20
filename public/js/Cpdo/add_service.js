$(document).ready(function () {
     if ($("#id").val() === "") {
        addmorerequirements();
        
    }
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
  
  function  getserviceName(aglcode){
     var id =aglcode;
       $.ajax({
            url :DIR+'cpdoservice/getserviceName', // json datasource
            type: "POST", 
            data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
                var arr = html.split('#');
               $("#servicefee").val(arr[0]);
               $("#cs_amount").val(arr[1]);
            }
        })
   } 
   
    function addmorerequirements(){
     var prevLength = $("#RequirementDetails").find(".removerequirementdata").length;
     var html = $("#hidenRequirementHtml").html();
        $('.RequirementDetails').append(html);
        $(".btn_cancel_requiremnt").click(function(){
        $(this).closest(".removerequirementdata").remove();
            var cnt = $("#RequirementDetails").find(".removerequirementdata").length;
            $("#hidenRequirementHtml").find('.req_id').attr('id','req_id'+cnt);
            $("#hidenRequirementHtml").find('.csr_is_required').attr('id','csr_is_required'+cnt);
        });
        var classid = $("#RequirementDetails").find(".removerequirementdata").length;
        $("#req_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#reqdropdown")}); 
        $("#req_id0").select3({dropdownAutoWidth : false,dropdownParent: $("#reqdropdown")}); 
        $("#hidenRequirementHtml").find('.req_id').attr('id','req_id'+classid);
        $("#hidenRequirementHtml").find('.csr_is_required').attr('id','csr_is_required'+classid);
    }  

});

