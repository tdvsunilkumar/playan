var btnType='';
$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
        uploadBulkTaxpayers();
    });
    $(".setBtntype").click(function(){
        btnType = $(this).attr('btn_type');
    })
    $('#bulkUploadFile').on('change', function () {
        $("#DivUpload").addClass("hide")
    });
});

function uploadBulkTaxpayers(){
    $("#commonError").html('');
    if (typeof $('#bulkUploadFile')[0].files[0]== "undefined") {
        $("#commonError").html("Please upload Document");
        return false;
    }
    var formData = new FormData();
    formData.append('file', $('#bulkUploadFile')[0].files[0]);
    formData.append('type', btnType);

    
    showLoader();
    $.ajax({
        url : DIR+'bploclients/uploadBulkTaxpayers',
        type : 'POST',
        data : formData,
        dataType:"JSON",
        processData: false,
        contentType: false, 
        success : function(data) {
            console.log("data",data)
            hideLoader();
            if(!data.status){
                $("#commonError").html(data.message)
                $("#DivUpload").addClass("hide")
            }else{
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
                if(btnType=='validateRecords'){
                    $("#DivUpload").removeClass("hide")
                }else{
                    $("#DivUpload").addClass("hide")
                    $("form")[0].reset(); 
                    $("#canelBtn").trigger('click');
                    location.reload();
                }
                
            }
        }
    })
}