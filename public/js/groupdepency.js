$(document).ready(function () {
      $('#section_id').on('change', function() {
        var section_id =$(this).val();
       $.ajax({
            url :DIR+'getdivisionbysection', // json datasource
            type: "POST", 
            data: {
                    "section_id": section_id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#division_id").html(html);
            }
        })
   });
});