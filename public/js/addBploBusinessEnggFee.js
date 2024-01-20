$(document).ready(function () {
    getTaxTypes();
    $('#tax_class_id').on('change', function() {
        getTaxTypes();
    });
});

function getTaxTypes(){
    var tax_class_id =$('#tax_class_id').val();
    var prev_type_id =$("#prev_tax_type_id").val();
    $.ajax({
        url :DIR+'gettaxTypeBytaxClass', // json datasource
        type: "POST", 
        data: {
          "tax_class_id": tax_class_id, 
          "prev_type_id":prev_type_id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
           $("#tax_type_id").html(html);
        }
    })
   
}