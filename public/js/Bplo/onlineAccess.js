$(document).ready(function () {
    getBusinessThroughAjax('busn_id','busn_group','bploclients/getbusinssForOnlineAccess');
    dataSearch();

    $("#saveData").click(function () {
        $("#validate-err").html("");
        var selectedValue = $("#busn_id").val();
        var clientid = $("#id").val();
        if (!selectedValue) {
            $("#validate-err").text("Please Select a business.");
            return false;
        }

        $.ajax({
            type: "POST",
            url: DIR + 'bploclients/checkBusinessExist',
            dataType:'JSON',
            data: {
                "busn_id": $("#busn_id").val(),
                "client_id": $("#id").val(),
                "_token": $("#_csrf_token").val()
            },
            success: function (response) {
                console.log("response",response)
                if (response.Exist) {
                    $("#validate-err").text("This business already assigned.");
                } else {
                    saveDeta();
                }
            }
        });
        dataSearch();
    });
    $(document).on('click', '.deleterow', function() {
        var recordid = $(this).attr('id');
        DeleteRecord(recordid)
    });
    $("#busn_id").change(function(){
        var id=$(this).val();
        getBusinessDtls(id);
    });
    $("#taxpayerReference").click(function(){
        $("#busn_id").val("");
        getBusinessThroughAjax('busn_id','busn_group','bploclients/getbusinssForOnlineAccess');
       /* $.ajax({
            type: "POST",
            url: DIR + 'bploclients/getbusinssForOnlineAccess',
            data: {
                "is_checked":($(this).is(":checked"))?1:0,
                "_token": $("#_csrf_token").val()
            },
            dataType: "html",
            success: function(data) {
               $("#busn_id").html(data);
            }
        });*/
    })
    
});
function DeleteRecord(id){
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
        if(result.isConfirmed){
           $.ajax({
                url :DIR+'bploclients/deleteOnlineAccess', // json datasource
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
                    dataSearch();
                    updateRemoteServer(id,'delete');
                }
            })
        }
    })
}


function saveDeta(){
    const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-primary',
           cancelButton: 'btn btn-dark'
       },
       buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
       text: "Are sure want to continue?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
   }).then((result) => {
        if(result.isConfirmed){
            showLoader();
            $.ajax({
                url :DIR+'bploclients/AddBusnOnlineAccess', // json datasource
                type: "POST", 
                dataType: "json",
                data: {
                    "client_id":$("#id").val(),
                    "busn_id":$("#busn_id").val(),
                    "_token": $("#_csrf_token").val(),
               },
               success: function(html){
                    hideLoader();
                    if(html.ESTATUS){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Submit Successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        dataSearch();
                        if(html.lastId>0){
                            updateRemoteServer(html.lastId,'save');
                        }
                   }
               }
           })
       }
   })
}

function updateRemoteServer(id,type){
     $.ajax({
        type: "POST",
        url: DIR + 'bploclients/updateOnlineAccessRemoteServer',
        data: {
            "id": id,
            "type":type,
            "_token": $("#_csrf_token").val()
        },
        dataType: "json",
        success: function(data) {
            console.log("remote Server",data)
        }
    });
}
function dataSearch() {
    $.ajax({
        type: "POST",
        url: DIR + 'bploclients/getBusniessOnlineAccess',
        data: {
            "client_id": $("#id").val(),
            "_token": $("#_csrf_token").val()
        },
        dataType: "json",
        success: function(data) {
            $('.loadingGIF').hide();
            var table = $('#jqOnlineAccessDtatable').DataTable();
            if (table) {
                table.destroy();
            }
            $('#jqOnlineAccessDtatable').DataTable({
                "data": data.data,
                "paging": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 100],
                "ordering": true,
                "columns": [
                    { "data": "srno" },
                    { "data": "busn_name" },
                    { "data": "full_name" },
                    { "data": "busn_trade_name" },
                    { "data": "busns_id_no" },
                    { "data": "brgy_name" },
                    { "data": "action" }
                ],
                "createdRow": function (row, data, dataIndex) {
                    console.log('createdRow called');
                    if (data.class) {
                        $(row).addClass(data.class);
                    }
                }

            });
        }
    });
}


function getBusinessDtls(id){
    showLoader();
    var id = $('#busn_id').val();
    $.ajax({
        url :DIR+'bploclients/getBusinessDtls', // json datasource
        type: "POST", 
        data: {
             "id": id, 
             "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            hideLoader();
            $('input[name=busn_trade_name]').val(html.busn_trade_name);
            $('input[name=busn_tin_no]').val(html.busn_tin_no);
            $('input[name=busn_registration_no]').val(html.busn_registration_no);
            $('input[name=busns_id_no]').val(html.busns_id_no);
            $('input[name=brgy_name]').val(html.brgy_name);
            $('input[name=full_name]').val(html.full_name);
            $('input[name=btype_desc]').val(html.btype_desc);
            $('input[name=payment_status]').val(html.payment_status);
        },
        error: function() {
            hideLoader();   
        }
   });
}

function getBusinessThroughAjax(id,parentId,Url,length=0){
    $("#"+id).select3({
        allowClear: true,
        dropdownAutoWidth : false,
        dropdownParent: $("#"+parentId),
        minimumInputLength: length,
        ajax: {
            url: DIR+Url,
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params,val) {
                return {
                    is_checked:($('#taxpayerReference').is(":checked"))?1:0,
                    client_id:$('#id').val(),
                    search: params.term,
                    page: params.page || 1,
                };
            },
            processResults: function (data,params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 20) < data.data_cnt
                    }
                };
            },
            cache: true
        }
    }).val($("#"+id).val()).trigger('change');
}
