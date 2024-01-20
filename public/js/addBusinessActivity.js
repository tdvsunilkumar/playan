$(document).ready(function () {
    getTaxTypes();
    getClassifications();
    $('#tax_class_id').on('change', function() {
        getTaxTypes();
    });
    $('.getclassification').on('change', function() {
        getClassifications();
    });
});


function getClassifications(){
    var tax_class_id =$('#tax_class_id').val();
    var tax_type_id =$("#tax_type_id").val();
    var prev_classification_id =$("#prev_classification_id").val();
    $.ajax({
        url :DIR+'administrative/bus.-classificication/activities/getClassificationBytaxClassType', // json datasource
        type: "POST", 
        data: {
          "tax_class_id": tax_class_id, 
          "tax_type_id":tax_type_id,
          "prev_classification_id":prev_classification_id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
           $("#business_classification_id").html(html);
        }
    })
}


function getTaxTypes(){
    var tax_class_id =$('#tax_class_id').val();
    var prev_type_id =$("#prev_tax_type_id").val();
    $.ajax({
        url :DIR+'administrative/bus.-classificication/activities/gettaxTypeBytaxClassActivity', // json datasource
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