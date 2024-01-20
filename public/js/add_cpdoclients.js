$(document).ready(function () {
   select3Ajax("p_barangay_id_no","accordionFlushExample","getBarngayList");
   $('#commonModal').find("#country").select3({dropdownParent : '#natonality'});
    $("#clientsregistered").change(function(){
    var id=$(this).val();
    if(id){ getprofiledata(id); }
  })

 function getprofiledata(id){
  $('.loadingGIF').show();
  var filtervars = {
      pid:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'cpdoclients/getClientsDetails',
      data: filtervars,
      dataType: "html",
      success: function(html){ 
        $('.loadingGIF').hide();
        arr = $.parseJSON(html);
        if(arr){
          console.log(arr);
          $("#rpo_custom_last_name").text(arr.rpo_custom_last_name);
          $("#rpo_first_name").val(arr.rpo_first_name);
          $("#rpo_middle_name").val(arr.rpo_middle_name);
          $("#suffix").val(arr.suffix); 
          $("#rpo_address_house_lot_no").val(arr.rpo_address_house_lot_no);
          $("#rpo_address_street_name").val(arr.rpo_address_street_name);
          $("#rpo_address_subdivision").val(arr.rpo_address_subdivision);
          $("#p_telephone_no").val(arr.p_telephone_no);
          $('#p_barangay_id_no').html(arr.p_barangay_id_no);
          select3Ajax("p_barangay_id_no","accordionFlushExample","getBarngayList");
          //$('#p_barangay_id_no>option[value='+arr.p_barangay_id_no+']').prop('selected', true);
          $("#p_mobile_no").val(arr.p_mobile_no);
          $("#p_fax_no").val(arr.p_fax_no);
          $("#p_email_address").val(arr.p_email_address);
          $("#p_tin_no").val(arr.p_tin_no);
          $('#country>option:eq('+arr.country+')').prop('selected', true);
          $('#gender>option:eq('+arr.gender+')').prop('selected', true);
          $("#dateofbirth").val(arr.dateofbirth);
        }
      }
  });
}   

    $(document).off('click','#submittaxdeclarationform').on('click','#submittaxdeclarationform',function(e) {
        e.preventDefault();
         $("#err_natureofbussiness").text('');
         var ids='';
             $('.natureofbussiness').each(function () {
                    if($(this).val()!='')
                        ids +=$(this).val()+',';
                });
               ids = ids.replace(/,\s*$/, '');
              var id = ids;
              var natureArray = id.split(',');
              let len1 = natureArray.length; //get the length of your array
                let len2 = $.unique(natureArray).length; //the length of array removing the duplicates

                if (len1 > len2) {
                var currid =$(this).attr('id');
                 //  $("#err_natureofbussiness").text(');
                 // $('html, modal-body').animate({scrollTop: '+=200px'}, 800);
                  Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Nature Of Bussiness Should not be dublicate.',
                      showConfirmButton: false,
                      timer: 3000
                    })
                 return false;
                }
              $('.codeabbrevation').each(function () {
                    if($(this).val()!='')
                        ids +=$(this).val()+',';
                });
               ids = ids.replace(/,\s*$/, '');
              var id = ids;
              var requireArray = id.split(',');
              let len3 = requireArray.length; //get the length of your array
                let len4 = $.unique(requireArray).length; //the length of array removing the duplicates

                if (len4 > len4) {
                   var currid =$(this).attr('id');
                 //  $("#err_requirements").text('Requirements Should not be dublicate');
                 // $('html, modal-body').animate({scrollTop: '+=200px'}, 800);
                    Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Requirements Should not be dublicate.',
                      showConfirmButton: false,
                      timer: 3000
                    })
                 return false;
                }

        $(".validate-err").html('');
        var data = $("form").serialize().split("&");
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'rptproperty/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    $('#propertyTaxDeclarationForm').unbind('submit');
                    $('#propertyTaxDeclarationForm').submit();
                }
            }
        })
      });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


