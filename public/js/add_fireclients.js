$(document).ready(function () {
  $(document).on('keyup','.phonenumber',function(){
          var x = this.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})/);
          console.log(x);
          this.value = !x[2] ? x[1] : '' +'+'+x[1] + ' ' + x[2] + x[3] ? ' ' + x[3] : '' + x[4] ? ' ' + x[4] : '';
  });
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
      url: DIR+'bploclients/getClientsDetails',
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
          $('#p_barangay_id_no').val(arr.p_barangay_id_no);
          $('#p_barangay_id_no').select3({
                allowClear: true, 
                dropdownAutoWidth : false,dropdownParent: $('.basicinfodiv')
            });

          
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

   
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


