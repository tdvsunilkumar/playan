$(document).ready(function () {
    $('.getPosition').on('change', function() {
        getPosition($(this));
    });
    $('#reg_no').on('change', function() {
        getprofileRegioncode($(this).val());
    });
    if($('#mun_display_for_bplo').val() > 0){
      $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_1")});
    }
   $("#bfp_inspection_order").click(function() {
        var is_print = 0;
        if($(this).is(':checked')) { 
             is_print=1;
          }
         bfpInspectionOrderUpdate(is_print);
    });
    $('#mun_display_for_bplo').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_1").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_1")});
        }else{
          $("#divDetaprtment_1").addClass("hide");
        }

    });
    if($('#mun_display_for_rpt').val() > 0){
      $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_2")});
    }

    $('#mun_display_for_rpt').on('change', function() {
        if($(this).val()==1){
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_2")});
          $("#divDetaprtment_2").removeClass("hide");
        }else{
          $("#divDetaprtment_2").addClass("hide");
        }
        $('html, body').stop().animate({
          scrollTop: $(".modal-footer").offset().top
        }, 600);
    });

    if($('#mun_display_for_welfare').val() > 0){
      $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_3")});
    } 

    $('#mun_display_for_welfare').on('change', function() {
        if($(this).val()==1){
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_3")});
          $("#divDetaprtment_3").removeClass("hide");
        }else{
          $("#divDetaprtment_3").addClass("hide");
        }
        $('html, body').stop().animate({
          scrollTop: $(".modal-footer").offset().top
        }, 600);
    });

    $('#mun_display_for_accounting').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_4").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_4")});
        }else{
          $("#divDetaprtment_4").addClass("hide");
        }

    });
    $('#mun_display_for_economic').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_5").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_5")});
        }else{
          $("#divDetaprtment_5").addClass("hide");
        }
    });
    $('#mun_display_for_cpdo').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_6").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_6")});
        }else{
          $("#divDetaprtment_6").addClass("hide");
        }
    });
    $('#mun_display_for_eng').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_7").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_7")});
        }else{
          $("#divDetaprtment_7").addClass("hide");
        }
    });
    $('#mun_display_for_occupancy').on('change', function() {
        if($(this).val()==1){
          $("#divDetaprtment_8").removeClass("hide");
          $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_8")});
        }else{
          $("#divDetaprtment_8").addClass("hide");
        }
    });
    if($('#mun_display_for_rpt').val() > 0){
      $(".forSelect3").select3({dropdownAutoWidth : false,dropdownParent: $("#divDetaprtment_2")});
    }

    $('.uacs_change').on('change', function() {
      prov_no = $("#prov_no").val();
      reg_no = $("#reg_no").val();
      mun_code = $("#uacs_code").val();
      $.ajax({
          url :DIR+'getUACScode', // json datasource
          type: "POST", 
          data: {
            "reg_id": reg_no, 
            "prov_id": prov_no, 
            "_token": $("#_csrf_token").val(),
          },
          success: function(html){
            console.log(html);
            $("#loc_local_code3").val(html+mun_code);
          }
      })
    });
 });


function getprofileRegioncode(id){
    $.ajax({
        url :DIR+'getprofileRegioncodeId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#prov_no").html(html);
          }
        }
    })
}

function getPosition(val){
  var name = val.attr('pname');
  $('.loadingGIF').show();
  var filtervars = {
      id:val.val(),
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getOfficerposition',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $('input[name="'+name+'"]').val(html.description);
      }
  });
}
function bfpInspectionOrderUpdate(is_print){
   // alert(is_print);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You want to update Inspection Order?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed)
        {
           $.ajax({
            url :DIR+'profilemunicipalitie/updateDataMenuPermission', // json datasource
            type: "POST", 
            data: {
              "is_active": is_print,  
              "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Update Successfully.',
              showConfirmButton: false,
              timer: 1500
            })
               // location.reload();
            }
        })
        }
    })
}
