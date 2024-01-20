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
    $("#upload_type").change(function(){
        $("#DivBusiness").addClass('hide');
        $("#DivPsic").addClass('hide');
        $("#DivMeasure").addClass('hide');
        if($(this).val()==1){
            $("#DivBusiness").removeClass('hide');
        }else if($(this).val()==2){
            $("#DivPsic").removeClass('hide');
        }else if($(this).val()==3){
            $("#DivMeasure").removeClass('hide');
        }
    })
});

function uploadBulkTaxpayers(){
    $("#commonError").html('');
    var upload_type = $("#upload_type").val();
    if (typeof $('#bulkUploadFile'+upload_type)[0].files[0]== "undefined") {
        $("#commonError").html("Please upload Document");
        return false;
    }
    var formData = new FormData();
    formData.append('file', $('#bulkUploadFile'+upload_type)[0].files[0]);
    formData.append('type', btnType);
    formData.append('upload_type', upload_type);
    
    showLoader();
    $.ajax({
        url : DIR+'rptproperty/uploadBulkLandData',
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
        },
        error: function(){
            hideLoader();
        }
    })
}