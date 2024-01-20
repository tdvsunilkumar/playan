$(document).ready(function () {
    select3Ajax("division_id","group_Division","sectioncodeList");
      $('#division_id').on('change', function() {
        var division_id =$(this).val();
        sectionId(division_id);
        divisionId(division_id)
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
        divisionId($("#division_id").val());
    }
   $('#class_id').on('change', function() {
        var id =$(this).val();
         classdataId(id); 
   });
});


function classdataId(id){
    $.ajax({
            url :DIR+'getPsicGroupId', // json datasource
            type: "POST", 
            data: {
                    "classId": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#group_id").val(html);        }
        })
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
function divisionId(division_id){
   $("#class_id").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#class_id").parent(),
    ajax: {
        url: DIR+'classcodeList',
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