$(document).ready(function () {
    select3Ajax("division_id","group_Division","sectioncodeList");
      
      $('#division_id').on('change', function() {
        var division_id =$(this).val();
        sectionId(division_id);
        groupId(division_id);
       $.ajax({
            url :DIR+'groupAllData', // json datasource
            type: "POST", 
            data: {
                    "division_id": division_id,
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#group_id").html(html);         
           }
        })
   });
      
  if($("#division_id").val()>0){
        sectionId($("#division_id").val());
        groupId($("#division_id").val());
    }
 
    
});
function groupId(division_id){
   $("#group_id").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'groupAjaxList',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": division_id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
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