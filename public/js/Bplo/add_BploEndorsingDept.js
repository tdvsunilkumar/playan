var isDuplicate=0;
$(document).ready(function () {
    $("#btn_addmore").click(function(){
        if(!isDuplicate){
            $('html, body').stop().animate({
              scrollTop: $("#btn_addmore").offset().top
            }, 600);
            addMore();
        }
        
    });
    $("#requirement_id0").change(function(){
        var current_id = $(this).val();
        // alert(current_id);
        var checkboxFieldName = 'is_required_' + current_id;
        $("#is_required0").attr('name', checkboxFieldName);

    });
    $(".btnCancel").click(function(){
        isDuplicate=0;
        var id = $(this).attr('id');
        if(id>0){
            DeleteRecord(id);
        }else{
            $(this).closest(".removedata").remove();  
        }
    });
});
function commonFunction(){
    $(".requirement_id").change(function(){
        var current_id = $(this).val();
        // var checkboxFieldName = 'is_required_' + current_id;
        // $('.checkedBox').attr('name', checkboxFieldName);
        var thisVar = $(this);
        var attrid = $(this).attr('id');
        isDuplicate=0;
        thisVar.closest(".removedata").find('#err_requirement').html('');
        $('#reqDetails').find('.requirement_id').each(function(i, obj) {
           if(attrid!=$(this).attr('id')){
               if(current_id==$(this).val()){
                    isDuplicate=1;
                    return false;
               }
           }
        });
        if(isDuplicate){
            thisVar.val('').trigger('change');
            thisVar.closest(".removedata").find('#err_requirement').html('Don\'t select duplicate requirement');
            return false;
        }
    })
}

function addMore(){
    var html = $("#hidenrequirementHtml").html();
    var prevLength = $(".reqDetails").find(".removedata").length;
    $(".reqDetails").append(html);
    $(".btnCancel").click(function(){
        $(this).closest(".removedata").remove();
        var cnt = $(".reqDetails").find(".removedata").length;
        $("#hidenrequirementHtml").find('select').attr('id','requirement_id'+cnt);
        $("#hidenrequirementHtml").find('.checkboxis_required').attr('id','is_required'+cnt);
    });
    var classid = $(".reqDetails").find(".removedata").length;
    $("#requirement_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#reqDetails")});
    $("#requirement_id"+prevLength).change(function(){
        var current_id = $(this).val();
        // alert(current_id);
        var checkboxFieldName = 'is_required_' + current_id;
        $("#is_required"+prevLength).attr('name', checkboxFieldName);

    });

    $("#hidenrequirementHtml").find('select').attr('id','requirement_id'+classid);
    $("#hidenrequirementHtml").find('.checkboxis_required').attr('id','is_required'+classid);
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
                url :DIR+'deleteBploEndorsingDept', // json datasource
                type: "POST", 
                data: {
                  "id":$("#id").val(),
                  "rid": id,
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


  
