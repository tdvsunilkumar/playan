$(document).ready(function () {
    getTaxTypes();
    $('#tax_class_id').on('change', function() {
        getTaxTypes();
    });
    $('#bbc_classification_code').on('change', function() {
        getactivitycode($(this).val());
    });
     $('#tax_type_id').on('change', function() {
        getbussinessclassbytaxtype($(this).val());
    });
    $('#bbef_fee_option').on('change', function() {
        var currentval = $(this).val();
        if(currentval =='2' || currentval =='3'){
          $('#bbef_sched').prop('readonly', false);
        }else{ $('#bbef_sched').prop('readonly', true);}
         if(currentval =='2'){
          $('#bbef_category_code').prop('readonly', false);
          $('#bbef_category_description').prop('readonly', false);
        }else{ $('#bgf_category_code').prop('readonly', true);
          $('#bbef_category_description').prop('readonly', true);}
         if(currentval =='3'){
          $('#bbef_area_minimum').prop('readonly', false);
          $('#bbef_area_maximum').prop('readonly', false);
        }else{ $('#bbef_area_minimum').prop('readonly', true);
          $('#bbef_area_maximum').prop('readonly', true);}
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
           if($("#id").val() <=0){
           $("#bbc_classification_code").html('<option>Please Select</option>');
           $("#bba_code").html('<option>Please Select</option>');
           }
        }
    })
}

function getactivitycode(id){
    $.ajax({
        url :DIR+'fees-master/business-permit-fee/getactivityCodebyid', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bba_code").html(html);
          }
        }
    })
}


function getbussinessclassbytaxtype(id){
   $.ajax({
        url :DIR+'fees-master/business-permit-fee/getbussinessbyTaxtype', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bbc_classification_code").html(html);
           $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}