$(document).ready(function () {
  //alert();
    $("#clientsregistered").select3({dropdownAutoWidth : false,dropdownParent: $("#clientsregistered-group")});
    // $("#p_barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $(".p_barangay_id_group")});
    // $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
    select3Ajax("p_barangay_id","p_barangay_id_group","getBarngayList");
    $('#commonModal').find("#country").select3({dropdownParent : '#commonModal'});
	
    $("#p_code").change(function(){
		var id=$(this).val();
		getProfileDetails(id);
	});
	$("#clientsregistered").change(function(){
		var id=$(this).val();
		if(id){ getprofiledata(id); }
	}) 
  $("select[name=clientsregistered]").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $("select[name=clientsregistered]").parent(),
    ajax: {
        url: DIR+'real-property/property-owners/taxpayer-ajax-request',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

    $(document).on('keyup','.phonenumber',function(){
      var x = this.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})/);
      console.log(x);
      this.value = !x[2] ? x[1] : '' +'+'+x[1] + ' (' + x[2] + (x[3] ? ') ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
    });
 });
function getProfileDetails(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'rptpropertyowner/getProfileDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
       $('.loadingGIF').hide();
       $("#brgy_code").val(html.brgy_code)
       $("#p_telephone_no").val(html.p_telephone_no)
       $("#p_mobile_no").val(html.p_mobile_no)
       $("#p_fax_no").val(html.p_fax_no)
       $("#p_tin_no").val(html.p_tin_no)
       $("#p_email_address").val(html.p_email_address)
       $('#p_barangay_id').html(html.p_barangay_id_no);
       select3Ajax("p_barangay_id","p_barangay_id_group","getBarngayList");
       //$("#p_barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $(".p_barangay_id_group")});
       //$('#p_barangay_id_no>option:eq('+arr.p_barangay_id_no+')').prop('selected', true);
       
      }
  });
}

 function getprofiledata(id){
  $('.loadingGIF').show();
  var filtervars = {
      pid:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'rptpropertyowner/getClientsDetails',
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
          $('#p_barangay_id').html(arr.p_barangay_id_no);
          select3Ajax("p_barangay_id","p_barangay_id_group","getBarngayList");
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

 
  
