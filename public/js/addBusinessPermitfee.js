$(document).ready(function () {
    getTaxTypes();
    $('#tax_class_id').on('change', function() {
        getTaxTypes();
    });
    $('#bbc_classification_code').on('change', function() {
        getactivitycode($(this).val());
    });
     $('#tax_type_id').on('change', function() {
        getbussinessclassbytaxtype($(this).val());
    });
    $('#bba_code').on('change', function() {  var id =0;
        getActivitybbaCode($(this).val()); 
         var preid =0; getByCategoryDropdown(preid);
         var preamt =0; getByAreaDropdown(preamt);
    });

    if($("#id").val()>0){
      var currentid = $('input[type=radio][name="bpt_fee_option"]:checked').val();
       if(currentid =='2'){ var bpt_cagetory_code=0;
          if($("#id").val()>0){ 
           var bpt_cagetory_code = $('#bpt_cagetory_code').val();
          }
          getByCategoryDropdown(bpt_cagetory_code);
        }
        if(currentid =='3'){ var preamt=0;
          if($("#id").val()>0){ 
           var preamt = $('#areamount').val();
          }
          getByAreaDropdown(preamt);
        }
      commonhideshowdiv(currentid);
    }

    $('input[type=radio][name="bpt_fee_option"]').change(function() {
        var id = $(this).val(); 
        if(id=='2'){ var preid =0; getByCategoryDropdown(preid);}
        if(id=='3'){ var preamt =0; getByAreaDropdown(preamt);}
        commonhideshowdiv(id);

    });

});

function commonhideshowdiv(id){
        var currentval = id;
        if(currentval =='1' || currentval =='4'){
            $('#bycategorydiv').addClass('hide');
            $('#byareadiv').addClass('hide');
            $('#basicfeediv').removeClass('hide');
            $('#bpt_permit_fee_amount').prop('required', true);
            $('#annualy').prop('required', true);
          }
         if(currentval =='2'){
            $('#bycategorydiv').removeClass('hide');
            $('#basicfeediv').addClass('hide');
            $('#byareadiv').addClass('hide');
            $('#bpt_permit_fee_amount').prop('required', false);
            $('#annualy').prop('required', false);
          }
          if(currentval =='3'){
            $('#byareadiv').removeClass('hide');
            $('#bycategorydiv').addClass('hide');
            $('#basicfeediv').addClass('hide');
            $('#bpt_permit_fee_amount').prop('required', false);
            $('#annualy').prop('required', false);
          }
          if(currentval =='0'){
             $('#bycategorydiv').addClass('hide');
             $('#byareadiv').addClass('hide');
          }
}

function getActivitybbaCode(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "GET",
      url: DIR+'fees-master/business-permit-fee/getActivitybbaCode',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $("#bpf_code").val(html.bba_code)
      }
  });
}

function getByCategoryDropdown(id){
  $('.loadingGIF').show();
   var classificationid =$('#bbc_classification_code').val();
    var activityid =$("#bba_code").val();
    var precatcode = id;
  $.ajax({
      type: "POST",
      url: DIR+'fees-master/business-permit-fee/getCategoryDropdown',
      data: {
          "classificationid":classificationid, 
          "activityid":activityid,
          "precode":precatcode,
          "_token": $("#_csrf_token").val(),
        },
      success: function(html){ 
        $("#categorydynamic").html(html)
      }
  });
}



function getByAreaDropdown(amt){
  $('.loadingGIF').show();
   var classificationid =$('#bbc_classification_code').val();
    var activityid =$("#bba_code").val();
    var prefeeamount= amt;
  $.ajax({
      type: "POST",
      url: DIR+'fees-master/business-permit-fee/getAreaDropdown',
      data: {
          "classificationid":classificationid, 
          "activityid":activityid,
          "feeamount":prefeeamount,
          "_token": $("#_csrf_token").val(),
        },
      success: function(html){ 
        $("#areadynamic").html(html)
      }
  });
}


function getTaxTypes(){
    var tax_class_id =$('#tax_class_id').val();
    var prev_type_id =$("#prev_tax_type_id").val();
    $.ajax({
        url :DIR+'gettaxTypeBytaxClass', // json datasource
        type: "POST", 
        data: {
          "tax_class_id": tax_class_id, 
          "prev_type_id":prev_type_id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
           $("#tax_type_id").html(html);
           // $("#bbc_classification_code").html('<option>Please Select</option>');
           // $("#bba_code").html('<option>Please Select</option>');
        }
    })
}

function getactivitycode(id){
    $.ajax({
        url :DIR+'fees-master/business-permit-fee/getactivityCodebyid', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bba_code").html(html);
          }
        }
    })
}

function getbussinessclassbytaxtype(id){
   $.ajax({
        url :DIR+'fees-master/business-permit-fee/getbussinessbyTaxtype', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bbc_classification_code").html(html);
           $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}