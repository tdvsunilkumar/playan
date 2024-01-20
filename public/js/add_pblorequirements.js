$(document).ready(function () {
    $('#section_id').on('change', function() {
        getDevision();
    });
    $("#btn_addmore_nature").click(function(){
        $('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
        addmoreNature();
    });
    $("#requirement_id0").change(function(){
        var current_id = $(this).val();
        // alert(current_id);
        var checkboxFieldName = 'is_required_' + current_id;
        $("#is_required0").attr('name', checkboxFieldName);

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

    $('#division_id').on('change', function() {
        var division_id =$(this).val();
       $.ajax({
            url :DIR+'getgroupbydivision', // json datasource
            type: "POST", 
            data: {
                "division_id": division_id, 
                "section_id": $('#section_id').val(),
                "_token": $("#_csrf_token").val(),
            },
            success: function(html){
               $("#group_id").html(html);
               //$("#class_id").html('<option>Please Select</option>');
               //$("#subclass_id").html('<option>Please Select</option>');
               $($(".select12")).each(function (index, element) {
                  var id = $(element).attr('id');
                  var multipleCancelButton = new Choices(
                      '#' + id, {
                          removeItemButton: true,
                          matcher: function(term, text, option) {
                  return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                }
                      }
                  );
              });
            }
        })
   });  
    $('#group_id').on('change', function() {
        var group_id =$(this).val();
       $.ajax({
            url :DIR+'getclassbygroup', // json datasource
            type: "POST", 
            data: {
                "group_id": group_id, 
                "division_id": $('#division_id').val(),
                "section_id": $('#section_id').val(),
                "_token": $("#_csrf_token").val(),
            },
            success: function(html){
               $("#class_id").html(html);
               //$("#subclass_id").html('<option>Please Select</option>');
                $($(".select13")).each(function (index, element) {
                  var id = $(element).attr('id');
                  var multipleCancelButton = new Choices(
                      '#' + id, {
                          removeItemButton: true,
                          matcher: function(term, text, option) {
                  return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                }
                      }
                  );
              });
            }
        })
   });
    $('#class_id').on('change', function() {
        var group_id =$(this).val();
       $.ajax({
            url :DIR+'getsubclassbyclass', // json datasource
            type: "POST", 
            data: {
                    "class_id": $('#class_id').val(),
                    "group_id": $('#group_id').val(),
                    "division_id": $('#division_id').val(),
                    "section_id": $('#section_id').val(),
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#subclass_id").html(html);
                $($(".select14")).each(function (index, element) {
                  var id = $(element).attr('id');
                  var multipleCancelButton = new Choices(
                      '#' + id, {
                          removeItemButton: true,
                          matcher: function(term, text, option) {
                  return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                }
                      }
                  );
              });
            }
        })
   });    
});

function getDevision(){
    var section_id =$('#section_id').val();
    $.ajax({
        url :DIR+'getdivisionbysection', // json datasource
        type: "POST", 
        data: {
            "section_id": section_id, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
           $("#division_id").html(html);
           //$("#group_id").html('<option>Please Select</option>');
           //("#class_id").html('<option>Please Select</option>');
           //$("#subclass_id").html('<option>Please Select</option>');
            $($(".select11")).each(function (index, element) {
              var id = $(element).attr('id');
              var multipleCancelButton = new Choices(
                  '#' + id, {
                  removeItemButton: true,
                  matcher: function(term, text, option) {
                      return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                    }
                  }
              );
            });
        }
    })
   
}
function commonFunction(){
    $(".requirement_id").change(function(){
        var current_id = $(this).val();
        // var checkboxFieldName = 'is_required_' + current_id;
        // $('.checkedBox').attr('name', checkboxFieldName);
        var thisVar = $(this);
        var attrid = $(this).attr('id');
        isDuplicate=0;
        thisVar.closest(".removenaturedata").find('#err_requirement').html('');
        $('#natureDetails').find('.requirement_id').each(function(i, obj) {
           if(attrid!=$(this).attr('id')){
               if(current_id==$(this).val()){
                    isDuplicate=1;
                    return false;
               }
           }
        });
        if(isDuplicate){
            thisVar.val('').trigger('change');
            thisVar.closest(".removenaturedata").find('#err_requirement').html('Don\'t select duplicate requirement');
            return false;
        }
    })
}
function addmoreNature(){
    var html = $("#hidennatureHtml").html();
    var prevLength = $(".natureDetails").find(".removenaturedata").length;
    $(".natureDetails").append(html);
    $(".btn_cancel_nature").click(function(){
        $(this).closest(".removenaturedata").remove();
        var cnt = $(".natureDetails").find(".removenaturedata").length;
        $("#hidennatureHtml").find('select').attr('id','requirement_id'+cnt);
        $("#hidennatureHtml").find('.checkboxis_required').attr('id','is_required'+cnt);
    });
    
    var classid = $(".natureDetails").find(".removenaturedata").length;
    
    $("#requirement_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});
    $("#requirement_id"+prevLength).change(function(){
        var current_id = $(this).val();
        // alert(current_id);
        var checkboxFieldName = 'is_required_' + current_id;
        $("#is_required"+prevLength).attr('name', checkboxFieldName);

    });
    $("#hidennatureHtml").find('select').attr('id','requirement_id'+classid);
    $("#hidennatureHtml").find('.checkboxis_required').attr('id','is_required'+classid);
    commonFunction();
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


  
