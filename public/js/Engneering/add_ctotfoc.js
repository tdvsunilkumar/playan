$(document).ready(function () {
     select3Ajax("ctype_id","chargesdiv","engtfoc/getTypeofchargesAjax");
     select3Ajax("sl_id","sliddiv","engtfoc/getChartofaccountAjax");
     select3Ajax("total_of_sl_id","totalofaccount","engtfoc/getChartofaccountAjax");
      $('#sl_id').on('change', function() {
        var agl_code =$(this).val();
        getAccoutdescription(agl_code);
     });

       if($('#tfoc_surcharge_fee').is(":checked")){
        $("#tfoc_surcharge_sl_iddiv").show();
        $("#tfoc_surcharge_sl_id").prop('required',true);
       }else{
        $("#tfoc_surcharge_sl_iddiv").hide();
        $("#tfoc_surcharge_sl_id").prop('required',false);
       }

      if($('#tfoc_interest_fee').is(":checked")){
        $("#tfoc_interest_sl_iddiv").show();
        $("#tfoc_interest_sl_id").prop('required',true);
       }else{
        $("#tfoc_interest_sl_iddiv").hide();
        $("#tfoc_interest_sl_id").prop('required',false);
       }

     if($("#sl_id").val() > 0) { 
       var agl_code = $("#sl_id option:selected").val();
        getAccoutdescription(agl_code);
     }
     if($("#tfoc_is_applicable option:selected").val() > 0 ){
          var val = $("#tfoc_is_applicable option:selected").val();
          blockshowhide(val);
          makeslect3othertaxes();
     }
     if($("#ctype_id option:selected").val() > 0 ){
          var val = $("#ctype_id option:selected").val();
          showhideessestial(val);
     }

      $('#ctype_id').on('change', function() {
        var id =$(this).val();
        checkEssestialisActive(id);
        showhideessestial(id);
   });  

     $('#tfoc_is_applicable').on('change', function() {
        var id =$(this).val();
        if(id=='1'){
          $("#bussinesspermitblock").show();
          $("#notesection").show();
          $("#bussanfengblock").show();
          $("#bussinessusage").show();
          $("#totalofaccount").show();
          $("#optionalfeediv").show();
        }else{$("#bussinesspermitblock").hide();   $("#notesection").hide();
          $("#bussinessusage").hide();  $("#totalofaccount").hide(); $("#bussanfengblock").hide(); $("#optionalfeediv").hide(); }
        if(id=='3'){
          $("#engneeringfee").show();
          $("#bussanfengblock").show(); $("#engothertaxes").show();
        }else{ $("#engneeringfee").hide(); $("#engothertaxes").hide();} 
        if(id=='4' || id=='5'){ 
          $("#bussanfengblock").show(); 
        } 
        if(id=='2'){
          $("#realproperty").show();
        }else{ $("#realproperty").hide();} 
        managetaxpayercharges(id);

        var isessetial = $("#isessential").val();
        if(id == '1' && isessetial =='1'){
                $("#is_business_tax_essential").prop('required',true);
        }else{ $("#is_business_tax_essential").prop('required',false); }
   });  

   function managetaxpayercharges(id){
    var isapp = id;
    if(isapp =='1' || isapp =='2' || isapp =='3' || isapp =='4' || isapp =='5' || isapp =='7'){
      $("#is_taxpayer_charges").prop('checked',true);
      $("#is_taxpayer_charges").attr('disabled',true);
    }else if(isapp =='6' || isapp =='8'){
       $("#is_taxpayer_charges").prop('checked',false);
       $("#is_taxpayer_charges").attr('disabled',true);
    }
    else if(isapp =='9' || isapp =='10'){
       $("#is_taxpayer_charges").prop('checked',false);
       $("#is_taxpayer_charges").attr('disabled',false);
    }
   }  

   $("#tfoc_surcharge_fee").click(function(){
       if($('#tfoc_surcharge_fee').is(":checked")){
        $("#tfoc_surcharge_sl_iddiv").show();
        $("#tfoc_surcharge_sl_id").prop('required',true);
       }else{
        $("#tfoc_surcharge_sl_iddiv").hide();
        $("#tfoc_surcharge_sl_id").prop('required',false);
       }
   }) 

    $("#tfoc_interest_fee").click(function(){
       if($('#tfoc_interest_fee').is(":checked")){
        $("#tfoc_interest_sl_iddiv").show();
        $("#tfoc_interest_sl_id").prop('required',true);
       }else{
        $("#tfoc_interest_sl_iddiv").hide();
        $("#tfoc_interest_sl_id").prop('required',false);
       }
   })
     $("#btn_addmore_othertaxes").click(function(){
        addmoreOthertaxes();
      });
      // $(".btn_cancel_othertaxes").click(function(){
      //      $(this).closest(".removeothertaxes").remove();
      // }); 

    function makeslect3othertaxes(){
      var i=0;
        $("#otherfeeDetails .otaxes_sl_id").each(function(index, field){
                        var idvalue = $(this).attr('id');
                        $("#"+idvalue).select3({dropdownAutoWidth : false,dropdownParent: $("#otherfeeDetails")}); 
                        i=i+1;
                    });
        $("#hidenOthertaxesHtml").find('.otaxes_sl_id').attr('id','otaxes_sl_id'+i); 
    }

    function addmoreOthertaxes(){
      var prevLength = $("#otherfeeDetails").find(".removeothertaxes").length;
     var html = $("#hidenOthertaxesHtml").html();
        $("#otherfeeDetails").append(html);
        $(".btn_cancel_othertaxes").click(function(){
        $(this).closest(".removeothertaxes").remove();
            var cnt = $("#otherfeeDetails").find(".removeothertaxes").length;
            $("#hidenOthertaxesHtml").find('.otaxes_sl_id').attr('id','otaxes_sl_id'+cnt);
            
        });
        var classid = $("#otherfeeDetails").find(".removeothertaxes").length;
        $("#otaxes_sl_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#otherfeeDetails")}); 
        $("#hidenOthertaxesHtml").find('.otaxes_sl_id').attr('id','otaxes_sl_id'+classid);
    } 

  function  checkEssestialisActive(id){
     var id =id;
       $.ajax({
            url :DIR+'getEssestialvalue', // json datasource
            type: "POST", 
            data: {
                    "chargeid": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
              $("#isessential").val(html);
            }
        })
   }  

  $('#is_business_tax_nonessential').click(function(){
      $("#is_business_tax_non_essential").val('1');
  })

   $('#is_business_tax_essential').click(function(){
      $("#is_business_tax_non_essential").val('0');
  })

  function  getAccoutdescription(aglcode){
     var agl_code =aglcode;
       $.ajax({
            url :DIR+'getAccountDescription', // json datasource
            type: "POST", 
            data: {
                    "agl_code": agl_code, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
              var arr = html.split('#');
               $("#gl_code").html(arr[0]);
               $("#gl_account_id").val(arr[1]);

              var descval = $("#gl_code option:selected").text();
              $("#totaldesc").text(descval);
            }
        })
   } 
   function  blockshowhide(id){
        var id =id;
        if(id=='1'){
          $("#bussanfengblock").show();
          $("#notesection").show();
          $("#bussinesspermitblock").show();
          $("#bussinessusage").show();  $("#totalofaccount").show(); $("#optionalfeediv").show();
        }else{$("#bussinesspermitblock").hide();  $("#bussanfengblock").hide();  $("#notesection").hide();
          $("#bussinessusage").hide(); $("#totalofaccount").hide(); $("#optionalfeediv").hide();}
        if(id=='3'){
          $("#engneeringfee").show(); $("#bussanfengblock").show(); $("#engothertaxes").show();
        }else{ $("#engneeringfee").hide(); $("#engothertaxes").hide();} 
        if(id=='4' || id=='5'){ 
          $("#bussanfengblock").show(); 
        } 
        if(id=='2'){
          $("#realproperty").show();
        }else{ $("#realproperty").hide();}  
   }

   function showhideessestial(id){
     if(id == '2'){
      $("#essentialdiv").removeClass('hide');
     }else{
      $("#essentialdiv").addClass('hide');
     }
   }

   $(".btn_cancel_othertaxes").click(function(){
      var id = $(this).attr('id');
      deleteOtherTaxes(id,$(this))
      
    });

  function deleteOtherTaxes(id,curr){
  var id = id; 
  const swalWithBootstrapButtons = Swal.mixin({
     customClass: {
       confirmButton: 'btn btn-success',
       cancelButton: 'btn btn-danger'
     },
     buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
     text: "Are you sure?",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonText: 'Yes',
     cancelButtonText: 'No',
     reverseButtons: true
   }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
         url :DIR+'engtfoc/deleteothertaxes', // json datasource
         type: "POST", 
         data: {
         "id": id,
         "_token": $("#_csrf_token").val(),
         },
         success: function(html){
           Swal.fire({
           position: 'center',
           icon: 'success',
           title: 'Deleted Successfully.',
           showConfirmButton: false,
           timer: 1500
           })
           curr.closest(".removeothertaxes").remove();
            calculatetotal(); 
            calculatechange();
         }
       })
     }
   })
} 
});