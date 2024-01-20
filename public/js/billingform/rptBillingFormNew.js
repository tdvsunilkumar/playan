$(document).ready(function(){
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    setTimeout(function(){ 
        $('input[name=fromdate]').val(today);
        $('input[name=todate]').val(today);
        datatablefunction();
     }, 2000);

    $("#rptPropertySearchByBarangy").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#rptPropertySearchByRevisionYear").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#btn_search").click(function(){
        datatablefunction();
    });

     $('#rptPropertySearchByText').on('keyup',function(){
        datatablefunction();
    });

    $('#rptPropertySearchByBarangy').on('change',function(e){
        if($(this).val() != ''){
            $('.addNewProperty').attr('hidden',false);
        }else{
            $('.addNewProperty').attr('hidden',true);
        }
        datatablefunction();
    });

    $('#rptPropertySearchByRevisionYear').on('change',function(e){
        datatablefunction();
    });
  

    $('.addNewProperty').on('click',function(){
                var url = $(this).data('url');
                var title1 = 'Single Property Billing';
                var title2 = 'Single Property Billing';
                var title = (title1 != undefined) ? title1 : title2;
                var size = 'xll';
                loadMainForm(url, title, size,'commonModal');
     });

    $("#brgy_code_id").change(function(){
        var id=$(this).val();
        var updateCode = $('input[name=update_code]').val();
        //alert(id+' '+updateCode);
        if(id != '' && updateCode == 'DC'){ 
            getbarangayaDetails(id); 
            }

    });

    $(document).on('hidden.bs.modal', '#commonModal', function () {
    datatablefunction();
});

     $(document).off('submit','#subitFormForRevision').on('submit','#subitFormForRevision',function(e){
        showLoader();
        e.preventDefault();
        var url =  $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
                datatablefunction();
            }
        },error:function(){
            hideLoader();
        }
    });

    });

     $(document).off('click','.showBillingDetails').on('click','.showBillingDetails',function(){
        showLoader();
        $("#commonUpDateCodeIntermediateModal1").unbind("click");
        $("#commonUpDateCodeIntermediateModal1 .modal-title").html('Billing Details [Item No.'+$(this).data('sr')+']');
        $("#commonUpDateCodeIntermediateModal1 .modal-dialog").addClass('modal-xll');
        var url =  $(this).data('url');
        var id  = $(this).data('id');
        var data   = {
            id:id
        }
        $.ajax({
        type: "get",
        url: url,
        data: data,
        dataType: "html",
        success: function(html){ 
            hideLoader();
                $('#commonUpDateCodeIntermediateModal1 .body').html('');
                $('#commonUpDateCodeIntermediateModal1 .body').html(html);
                $("#commonUpDateCodeIntermediateModal1").modal('show');
        },error:function(){
            hideLoader();
        }
    });

     });


});

function reviseOrRollbackSelectedTds(action) {
    var selectedLandAppraisals = $("#Jq_datatablelist tbody tr .selectpropertyforfinalrevision:checkbox:checked");
        var length                 = selectedLandAppraisals.length;
        if(length == 0){
            Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select at least one Record for Revision/Rollback',
                      showConfirmButton: true,
                      timer: false
                    })
        }else{
            $('input[name=actionForSelectedTds]').val(action);
            $('#subitFormForRevision').submit();
        }
}


function datatablefunction()
{

    var dropdown_html=get_page_number('1'); 
    var table = $('#Jq_datatablelist').DataTable({ 
        "language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
            oLanguage: {
                sLengthMenu: dropdown_html
        },
        "bProcessing": true,
        "serverSide": true,
        "bDestroy": true,
        "searching": false,
        "order": [],
        "columnDefs": [{ orderable: false, targets: [0,13] }],
        
        "pageLength": 100,
        "ajax":{ 
            url :DIR+'billingform/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#rptPropertySearchByText").val(),
                'year':$('#rptPropertySearchByRevisionYear').val(),
                'fromdate':$('#fromdate').val(),
                'todate':$('#todate').val(),
                'status':2,
                'property_kind':$('#rptPropertySearchByKind').val(),
                'barangay':$('#rptPropertySearchByBarangy').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "controlno" },
            { "data": "arp_no" },
            { "data": "taxpayer_name" },
            { "data": "barangay" },
            { "data": "type" },
            { "data": "billing_date" },
            { "data": "paeriod_covered" },
            { "data": "assessed_value" },
            { "data": "topno" },
            { "data": "orno" },
            { "data": "amount_due" },
            { "data": "is_paid" },
            { "data": "action" }
            /*{ "data": "other"}*/
        ],
        drawCallback: function(s){ 
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
             api.$('.print').click(function() {
                var rowid = $(this).attr('id');
                grosssaleReceipt(rowid);
            });
             api.$('.activeinactive').click(function() {
                var recordid = $(this).attr('id');
                var is_activeinactive = $(this).attr('value');
                 ActiveInactiveUpdate(recordid,is_activeinactive);
    
            });
        }
    }); 

    $('#selectALlrecords').prop('checked', false); 
}

function ActiveInactiveUpdate(id,is_activeinactive){
    // alert(id);
    // alert(is_activeinactive);
   const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-success',
           cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
       title: 'Are you sure?',
       text: "You want to Delete?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
   }).then((result) => {
       if(result.isConfirmed)
       {
          $.ajax({
               url :DIR+'billingform/ActiveInactive', // json datasource
               type: "POST", 
               data: {
                 "id": id,
                 "is_activeinactive": is_activeinactive,  
                 "_token": $("#_csrf_token").val(),
               },
               success: function(html){
                   Swal.fire({
                     position: 'center',
                     icon: 'success',
                     title: 'Billing deleted successfully!',
                     showConfirmButton: false,
                     timer: 1500
                   })
                   datatablefunction();
                   setInterval(function(){
                  
                      });
                   //location.reload();
               }
           })
       }
   })
}

function loadMainForm(url, title, size,modalId) {
    showLoader();
    $("#"+modalId).unbind("click");
    $("#"+modalId+" .modal-title").html(title);
    $("#"+modalId+" .modal-dialog").addClass('modal-' + size);
    
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            if(typeof data.status !== 'undefined' && data.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: data.msg,
                      showConfirmButton: true,
                      timer: 3000
                    })

            }else{
                $('#'+modalId+' .body').html('');
                $('#'+modalId+' .body').html(data);
                $("#"+modalId).modal('show');
                taskCheckbox();
                common_bind("#"+modalId);
                commonLoader();
            }
            
        },
        error: function (data) {
            hideLoader();
            $('#'+modalId).modal('hide');
                /*Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: false,
                      timer: 3000
                    })*/
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function getbarangayaDetails(id){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'rptproperty/getbarangycodedetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            var rpTdNo = $('input[name=rp_td_no]').val();
            $('.loadingGIF').hide();
            $('input[name=loc_group_brgy_no]').val(html.brgy_name);
            $('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
            $('input[name=brgy_code]').val(html.brgy_code);
            $('input[name=dist_code]').val(html.dist_code);
            $('input[name=dist_code_name]').val(html.dist_code+'-'+html.dist_name);
            $('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
            $('input[name=loc_local_code_name]').val(html.loc_local_code+'-'+html.loc_local_name);
            $('input[name=loc_local_code]').val(html.loc_local_code_id);
            
        },error:function(){
            hideLoader();
        }
    });
}