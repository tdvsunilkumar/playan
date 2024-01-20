$(document).ready(function () {


$('form').submit(function () {
        $("#mun_no").attr("disabled", false);
        $("#rvy_revision_year").attr("disabled", false);
    });
  $('#pk_code').on('change', function() {
     var id=$(this).val();
     $("#pau_actual_use_code").val('').trigger('change');
    $("#classcode").val('').trigger('change');
    if(id==3){
        getKindDetailss(id);
        $("#myDiv").hide();
        $("#myDivactual").hide();
        $("#myDivClass").show();
       $('#pau_actual_use_codelabel').text("Class Description");
      }else{
        getKindDetailssActualUs(id);
        $("#myDivLebel").show();
        $("#myDivactual").show();
        $("#myDivClass").hide();
        $('#pau_actual_use_codelabel').text("Class-Actual Use Description");
        $("#myDiv").show();
     }
    });
  
 
$('#pau_actual_use_code').on('change', function() {
 var id=$(this).val();
   getSubClassDetails(id);
   
});
$('#classcode').on('change', function() {
 var id=$(this).val();
  getClassDetails(id)
});
if($("#pk_code").val()>0){
    id=$("#pk_code").val();
    if(id==3){ 
       getKindDetailss(id);
       if($("#classcode").val()>0){
       getClassDetails($("#classcode").val());
       }
    }
    else{
        getKindDetailssActualUs(id);
        if($("#pau_actual_use_code").val()>0){
         getSubClassDetails($("#pau_actual_use_code").val());
       
        }  
    }
}
$("#btn_addmore_nature").unbind('click');
$("#btn_addmore_nature").click(function(){
    // alert("sok");
        $('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
        addmoreNature1();
    });
    $('.numeric').numeric();
    $(".btn_cancel_nature").click(function(){
         var id = $(this).attr('id');
        // alert(id);
         
         if(!id)
         {
          $(this).closest(".removenaturedata").remove();  
         }
        else{
            DeleteRecord(id);
        }
       
    });
$(".btn_cancel_nature").click(function(){
    var id = this.getAttribute('data-id');
    DeleteAssessmentRequirment(id);
      
    });
});
function DeleteAssessmentRequirment(id){
  $.ajax({
        url :DIR+'DeleteRelation', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
         
        }
    })
}

function addmoreNature1(){
    var prevLength = $(".natureDetails").find(".removenaturedata").length;
    $("#hidennatureHtml").find('.validate-err.minimum_unit_value').attr('id','err_minimum_unit_value'+prevLength);
    $("#hidennatureHtml").find('.validate-err.maximum_unit_value').attr('id','err_maximum_unit_value'+prevLength);
    $("#hidennatureHtml").find('.validate-err.assessment_level').attr('id','err_assessment_level'+prevLength);
    var html = $("#hidennatureHtml").html();
    
    
    $(".natureDetails").append(html);
    // alert("ok");
    $(".btn_cancel_nature").click(function(){
        $(this).closest(".removenaturedata").remove();
        var cnt = $(".natureDetails").find(".removenaturedata").length;
        $("#hidennatureHtml").find('select').attr('id','assessment_id'+cnt);
    });
    var classid = $(".natureDetails").find(".removenaturedata").length;
    
}
function getClassDetails(id){
   $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getClassDetailss',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#pc_class_code").val(html.id)
        $("#pc_class_desc").val(html.pc_class_code+'-'+html.pc_class_description)
      }
  });
  }

function getSubClassDetails(id){
   $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'getSubClassDetailss',
      data: filtervars,

      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#pc_class_code").val(html.pc_class_id)
        $("#ps_subclass_desc").val(html.pau_actual_use_code+'-'+html.pau_actual_use_desc)
        $("#pc_class_desc").val(html.pc_class_code+'-'+html.pc_class_description)
        
       
      }
  });
  }


function getKindDetailss(id){
  $("#classcode").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#classcode").parent(),
    ajax: {
        url: DIR+'getKindDetailss',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                'id' : id,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
function getKindDetailssActualUs(id){

     $("#pau_actual_use_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#pau_actual_use_code").parent(),
    ajax: {
        url: DIR+'getKindDetailssActualUs',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                'id' : id,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
function DeleteRecord(id){
    // alert(id);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed)
        {
           $.ajax({
                url :DIR+'bplorequirements/delete', // json datasource
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
                   location.reload();
                }
            })
        }
    })
}
