$(document).ready(function(){
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    datatablefunction();
    
    $("#btn_search").click(function(){
        datatablefunction();
    });

   $('#commonModal').on('hidden.bs.modal', function () {
       datatablefunction();
    })

    $('#rptPropertySearchByBarangy').on('change',function(e){
        datatablefunction();
    });

    $('#rptPropertySearchByKind').on('change',function(e){
        datatablefunction();
    });

    $('.addNewProperty').on('click',function(){
                var url = $(this).data('url');
                var title1 = 'Single Property Billing';
                var title2 = 'Single Property Billing';
                var title = (title1 != undefined) ? title1 : title2;
                var size = 'xxll';
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
        "columnDefs": [{ orderable: false, targets: [0,8] }],
        
        "pageLength": 100,
        "ajax":{ 
            url :DIR+'generalrevision/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#rptPropertySearchByText").val(),
                'year':$('#rptPropertySearchByRevisionYear').val(),
                /*'approve':$('#aproved').val(),
                'crdate':$('#datecreated1').val(),*/
                'status':2,
                'property_kind':$('#rptPropertySearchByKind').val(),
                'barangay':$('#rptPropertySearchByBarangy').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "checkbox" },
            { "data": "no" },
            { "data": "td_no" },
            { "data": "taxpayer_name" },
            { "data": "pin" },
            { "data": "market_value" },
            { "data": "assessed_value" },
            { "data": "pk_is_active" },
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
        }
    }); 

    $('#selectALlrecords').prop('checked', false); 
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