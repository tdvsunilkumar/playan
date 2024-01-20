$(document).ready(function () {
      select3Ajax("division_id","group_Division","sectioncodeList");
      $('#division_id').on('change', function() {
        var division_id =$(this).val();
        sectionId(division_id);
       
       $.ajax({
            url :DIR+'getSubclassgroupbyClass', // json datasource
            type: "POST", 
            data: {
                    "division_id": division_id,
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#class_id").html(html);         }
        })
   });
      
      if($("#division_id").val()>0){
        sectionId($("#division_id").val());
    }
});
function sectionId(division_id){
    $.ajax({
            url :DIR+'getPsicSectionsId', // json datasource
            type: "POST", 
            data: {
                    "division_id": division_id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#section_id").val(html);        }
        })
}