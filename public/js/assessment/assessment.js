$(document).ready(function(){
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    datatablefunction();
    $("#rptPropertySearchBystatus").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#rptPropertySearchByBarangy").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#taxdeclairdetail").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
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

    $('#taxdeclairdetail').on('change',function(e){
        datatablefunction();
    });
    $('#rptPropertySearchBystatus').on('change',function(e){
        datatablefunction();
    });

})
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
    "columnDefs": [{ orderable: false, targets: [0,4,14] }],
    "pageLength": 10,
    "ajax":{ 
      url :DIR+'rpt-assessment-list/getList', // json datasource
      type: "GET", 
      "data": {
        "q":$("#rptPropertySearchByText").val(),
        'taxdeclairdetail':$('#taxdeclairdetail').val(),
        'barangay':$('#rptPropertySearchByBarangy').val(),
        'pk_is_active':$('#rptPropertySearchBystatus').val(),
        "_token":$("#_csrf_token").val()
        }, 
      error: function(html){
      }
    },
        "columns": [
            { "data": "no" },
            { "data": "td_no" },
            { "data": "taxpayer_name" },
            { "data": "brgy_name" },
            { "data": "class" },
            { "data": "pin" },
            { "data": "rp_cadastral_lot_no" },
            { "data": "market_value" },
            { "data": "assessed_value" },
            { "data": "uc_code" },
            { "data": "effectivity" },
            { "data": "reg_emp_name" },
            { "data": "created_date" },
            { "data": "pk_is_active" },
            { "data": "action" },
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
            api.$(".showLess2").shorten({
                "showChars" : 2,
                "moreText"    : "More",
                "lessText"    : "Less",
            });
      }
  });  
}

$(document).on('click','.realpropertyaction',function(){

        //var selected = $(this).find("option:selected");
        var actionName = $(this).data('actionname');
        var propertyId   = $(this).data('propertyid');
        var taxDeclaretion   = $(this).data('tax');
        var count   = $(this).data('count');
         // alert(count);
         if(actionName == 'print'){
            printTaxDeclaration(propertyId);
        }else if(actionName == 'printfaas'){
            printTaxDeclarationFaas(propertyId);
        }else if(actionName == 'rptop'){
            printRPTOP(propertyId);
        }else if(actionName == 'bill'){
            var propertyId   = selected.data('propertyid');
            $('#selectBillingType').modal('show');
            $('input[name=selected_property_id_for_billing]').val(propertyId);

            $('.closeUpdateCodeNodal').on('click',function(){
                 $('#selectBillingType').modal('hide');
            });
        }
    });

function printTaxDeclaration(id) {
      var _id = id;
      $.ajax({
            url: _baseUrl + 'real-property/inquiries/printTaxDec',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
  }

  function printTaxDeclarationFaas(id) {
      var _id = id;
      $.ajax({
            url: _baseUrl + 'real-property/inquiries/printFAAS',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
  } 
  function printRPTOP(id) {
      var _id = id;
      $.ajax({
            url: _baseUrl + 'rptop/printbill',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
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
                     title: 'Update Successfully.',
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

