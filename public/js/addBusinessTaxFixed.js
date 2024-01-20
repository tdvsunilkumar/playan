$(document).ready(function () {
    
    $('#bbc_classification_code').on('change', function() {
        getactivitycode($(this).val());
    });

     $('#tax_type_id').on('change', function() {
        getbussinessclassbytaxtype($(this).val());
    });

    $("#bba_code").change(function(){
    var id=$(this).val();
    getbbaDetails(id);
  });

  $('#bbc_classification_code').on('change', function() {
     var id=$(this).val();
       getTaxDetails(id);
  });
  if($("#bbc_classification_code").val()>0){
        getTaxDetails($("#bbc_classification_code").val());
      }

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
        url :DIR+'getactivityCodebyid', // json datasource
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
 
        url :DIR+'getbussinessbyTaxtype', // json datasource
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

function getTaxDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'administrative/taxation-schedule/fixed-taxes-and-fees/getTaxDetails',
      data: filtervars,

      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        
        $("#tax_class_code").val(html.tax_class_code+'-'+html.tax_class_desc)
        $("#tax_class_desc").val(html.type_code+'-'+html.tax_type_description)
        $("#tax_type_id").val(html.tax_type_id)
        $("#tax_class_id").val(html.tax_class_id)
      }
  });
}


function getbbaDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getbbaDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
       $("#bba_code2").val(html.bba_code)
       $("#bba_desc").val(html.bba_desc)
       // $("#bbc_classification_code").val(html.business_classification_id)
      }
  });
}
// $(document).ready(function () {
//     getTaxTypes();
//     $('#tax_class_id').on('change', function() {
//         getTaxTypes();
//     });
//     $('#bbc_classification_code').on('change', function() {
//         getactivitycode($(this).val());
//     });
//      $('#tax_type_id').on('change', function() {
//         getbussinessclassbytaxtype($(this).val());
//     });
   

 
//   $('#bgf_fee_option').on('change', function() {
//       var currentval = $(this).val();
//       if(currentval =='2' || currentval =='3'){
//         $('#bgf_sched').prop('readonly', false);
//       }else{ $('#bgf_sched').prop('readonly', true);}
//        if(currentval =='2'){
//         $('#bgf_category_code').prop('readonly', false);
//         $('#bgf_category_description').prop('readonly', false);
//       }else{ $('#bgf_category_code').prop('readonly', true);
//         $('#bgf_category_description').prop('readonly', true);}
//        if(currentval =='3'){
//         $('#bgf_area_minimum').prop('readonly', false);
//         $('#bgf_area_maximum').prop('readonly', false);
//       }else{ $('#bgf_area_minimum').prop('readonly', true);
//         $('#bgf_area_maximum').prop('readonly', true);}
//   });
// });
// function getTaxTypes(){
//     var tax_class_id =$('#tax_class_id').val();
//     var prev_type_id =$("#prev_tax_type_id").val();
//     $.ajax({
//         url :DIR+'gettaxTypeBytaxClass', // json datasource
//         type: "POST", 
//         data: {
//           "tax_class_id": tax_class_id, 
//           "prev_type_id":prev_type_id,
//           "_token": $("#_csrf_token").val(),
//         },
//         success: function(html){
//            $("#tax_type_id").html(html);
//            if($("#id").val() <=0){
//            $("#bbc_classification_code").html('<option>Please Select</option>');
//            $("#bba_code").html('<option>Please Select</option>');
//            }
//         }
//     })
// }



// function getactivitycode(id){
//     $.ajax({
//         url :DIR+'getactivityCodebyid', // json datasource
//         type: "POST", 
//         data: {
//           "id": id, 
//           "_token": $("#_csrf_token").val(),
//         },
//         success: function(html){
//           if(html !=''){
//            $("#bba_code").html(html);
//           }
//         }
//     })
// }
// // function getactivitycode(id){
// //   $.ajax({
// //       url :DIR+'getactivityCodebyid', // json datasource
// //       type: "POST", 
// //       data: {
// //         "id": id, 
// //         "_token": $("#_csrf_token").val(),
// //       },
// //       success: function(html){
// //         if(html !=''){
// //          $("#bba_code").html(html);
         
// //         }
// //       }
// //   })
// // }


// function getcode(){
//   $.ajax({
//       url :DIR+'getcodebyid', // json datasource
//       type: "POST", 
//       data: {
//         "id": id, 
//         "_token": $("#_csrf_token").val(),
//       },
//       success: function(html){
//         if(html !=''){
//          $("#bba_code").html(html);
//          $("#bba_desc").html(html);

//         }
//       }
//   })

// }
// // function distroy(){
// //   $.ajax({
// //       url :DIR+'distroy', // json datasource
// //       type: "POST", 
// //       data: {
// //         "id": id, 
// //         "_token": $("#_csrf_token").val(),
// //       },
// //       success: function(html){
// //         if(html !=''){
// //          $("id").html(html);
         

// //         }
// //       }
// //   })

// // }
// // function getTaxTypes(id){
// //   $.ajax({
// //       url :DIR+'gettaxTypeBytaxClass', // json datasource
// //       type: "POST", 
// //       data: {
// //         "id": id, 
// //         "_token": $("#_csrf_token").val(),
// //       },
// //       success: function(html){
// //         if(html !=''){
// //          $("#tax_class_id").html(html);
// //         }
// //       }
// //   })
// // }

 
  
