$(document).ready(function () {
    $('#tax_class_id').on('change', function() {
        getTaxTypes();
    });
    $('#bbc_classification_code').on('change', function() {
        getClassificationDesc($(this).val());
    });
    if($("#bbc_classification_code").val()>0){
        getClassificationDesc($("#bbc_classification_code").val());
      }
     $('#tax_type_id').on('change', function() {
        getbussinessclassbytaxtype($(this).val());
    });
     $('#bba_code').on('change', function() {
        getActivitybbaCode($(this).val());
    });
    $("#btn_addmore_option").click(function(){
    $('html, body').stop().animate({
      scrollTop: $("#btn_addmore_option").offset().top
    }, 600);
     addmoreOptions();
    });
    $(".btn_cancel_option").click(function(){
       $(this).closest(".removeoptiondata").remove();
    });
    $('.numeric').numeric();
});
function getActivitybbaCode(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getActivitybbaCode',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $("#bsf_code").val(html.bba_code)
      }
  });
}
function addmoreOptions(){
  var html = $("#hidenoptionHtml").html();
  $(".optionDetails").append(html);
  $(".btn_cancel_option").click(function(){
    $(this).closest(".removeoptiondata").remove();
  });
  $('.numeric').numeric();
}

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
           // $("#bbc_classification_code").html('<option>Please Select</option>');
           // $("#bba_code").html('<option>Please Select</option>');
        }
    })
}

function getClassificationDesc(id){
    $.ajax({
        url :DIR+'getClasificationDesc', // json datasource
        type: "GET", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
            arr = $.parseJSON(html);
           $("#description").html(arr.bbc_classification_desc);
          }
        }
    })
}

function getbussinessclassbytaxtype(id){
   $.ajax({
        url :DIR+'getbussinessbyTaxtypenew', // json datasource
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