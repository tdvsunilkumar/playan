$(document).ready(function(){
$("#rps_person_taking_oath_id").select3({dropdownAutoWidth : false,dropdownParent: $("#oath_id")});	
$("#rps_ctc_no").select3({dropdownAutoWidth : false,dropdownParent: $("#cto_no")});
$("#rps_administer_official11").select3({dropdownAutoWidth : false,dropdownParent: $("#administer1")});
$("#rps_administer_official12").select3({dropdownAutoWidth : false,dropdownParent: $("#administer2")});
$("#rps_administer_official13").select3({dropdownAutoWidth : false,dropdownParent: $("#administer3")});
$("#rps_administer_official2_id1").select3({dropdownAutoWidth : false,dropdownParent: $("#administer21")});
$("#rps_administer_official2_id2").select3({dropdownAutoWidth : false,dropdownParent: $("#administer22")});
$("#rps_administer_official2_id3").select3({dropdownAutoWidth : false,dropdownParent: $("#administer23")});
$("#bff_representative_id").change(function(){
var id=$(this).val();
representative(id);
});
if($("#bff_representative_id").val()>0){
		representative($("#bff_representative_id").val());
}
$("#bff_representative_id2").change(function(){
var id=$(this).val();
representative(id);
});
$("#refreshClient").click(function(){
    refreshClient();
});
$("#refreshClient2").click(function(){
    refreshClient2();
});
$("#refreshClient3").click(function(){
    refreshClient3();
});
$('#rps_person_taking_oath_id').on('change', function() {
        getOrNumber($(this).val());
});
$('#rps_ctc_no').on('change', function() {
        getIssueance($(this).val());
});
$("#refreshEmployee").click(function(){
    refreshEmployee();
});
$("#refreshEmployee2").click(function(){
    refreshEmployee2();
});
$("#rps_administer_official2_type").click(function(){
    var id = $(this).val();
    // alert(id);
    var selectBox1 = $('#rps_administer_official2_id1');
    var selectBox2 = $('#rps_administer_official2_id2');
    var selectBox3 = $('#rps_administer_official2_id3');
    if (id === "1") {
      $('#div21').show();
      $('#div22').hide();
      $('#div23').hide();
      $('#div24').show();
      $('#div25').hide();
      $('#div26').hide();
      selectBox1.attr('name', 'rps_administer_official2_id');
      selectBox2.attr('name', 'rps_administer_official2_id2');
      selectBox3.attr('name', 'rps_administer_official2_id3');
    } else if (id === "2") {
      $('#div21').hide();
      $('#div22').show();
      $('#div23').hide();
      $('#div24').hide();
      $('#div25').show();
      $('#div26').hide();
      selectBox1.attr('name', 'rps_administer_official2_id1');
      selectBox2.attr('name', 'rps_administer_official2_id');
      selectBox3.attr('name', 'rps_administer_official2_id3');
    } else if (id === "3") {
      $('#div21').hide();
      $('#div22').hide();
      $('#div23').show();
      $('#div24').hide();
      $('#div25').hide();
      $('#div26').show();
      selectBox1.attr('name', 'rps_administer_official2_id1');
      selectBox2.attr('name', 'rps_administer_official2_id2');
      selectBox3.attr('name', 'rps_administer_official2_id');
    }
});
if($("#rps_administer_official2_type").val()>0){
	var id = $("#rps_administer_official2_type").val();
    // alert(id);
    var selectBox1 = $('#rps_administer_official2_id1');
    var selectBox2 = $('#rps_administer_official2_id2');
    var selectBox3 = $('#rps_administer_official2_id3');
    if (id === "1") {
      $('#div21').show();
      $('#div22').hide();
      $('#div23').hide();
      $('#div24').show();
      $('#div25').hide();
      $('#div26').hide();
      selectBox1.attr('name', 'rps_administer_official2_id');
      selectBox2.attr('name', 'rps_administer_official2_id2');
      selectBox3.attr('name', 'rps_administer_official2_id3');
    } else if (id === "2") {
      $('#div21').hide();
      $('#div22').show();
      $('#div23').hide();
      $('#div24').hide();
      $('#div25').show();
      $('#div26').hide();
      selectBox1.attr('name', 'rps_administer_official2_id1');
      selectBox2.attr('name', 'rps_administer_official2_id');
      selectBox3.attr('name', 'rps_administer_official2_id3');
    } else if (id === "3") {
      $('#div21').hide();
      $('#div22').hide();
      $('#div23').show();
      $('#div24').hide();
      $('#div25').hide();
      $('#div26').show();
      selectBox1.attr('name', 'rps_administer_official2_id1');
      selectBox2.attr('name', 'rps_administer_official2_id2');
      selectBox3.attr('name', 'rps_administer_official2_id');
    }	
}
$("#rps_administer_official1_type").click(function(){
    var id = $(this).val();
    // alert(id);
    var selectBox1 = $('#rps_administer_official11');
    var selectBox2 = $('#rps_administer_official12');
    var selectBox3 = $('#rps_administer_official13');
    if (id === "1") {
      selectBox1.attr('name', 'rps_administer_official1_id');
      selectBox2.attr('name', '');
      selectBox3.attr('name', '');
      $('#div1').show();
      $('#div2').hide();
      $('#div3').hide();
      $('#div4').show();
      $('#div5').hide();
      $('#div6').hide();
    } else if (id === "2") {
      selectBox1.attr('name', '');
      selectBox2.attr('name', 'rps_administer_official1_id');
      selectBox3.attr('name', '');
      $('#div1').hide();
      $('#div2').show();
      $('#div3').hide();
      $('#div4').hide();
      $('#div5').show();
      $('#div6').hide();
    } else if (id === "3") {
      selectBox1.attr('name', '');
      selectBox2.attr('name', '');
      selectBox3.attr('name', 'rps_administer_official1_id');
      $('#div1').hide();
      $('#div2').hide();
      $('#div3').show();
      $('#div4').hide();
      $('#div5').hide();
      $('#div6').show();
    }
});
if($("#rps_administer_official1_type").val()>0){
	var id = $("#rps_administer_official1_type").val();
    // alert(id);
    var selectBox1 = $('#rps_administer_official11');
    var selectBox2 = $('#rps_administer_official12');
    var selectBox3 = $('#rps_administer_official13');
    if (id === "1") {
      selectBox1.attr('name', 'rps_administer_official1_id');
      selectBox2.attr('name', '');
      selectBox3.attr('name', '');
      $('#div1').show();
      $('#div2').hide();
      $('#div3').hide();
      $('#div4').show();
      $('#div5').hide();
      $('#div6').hide();
    } else if (id === "2") {
      selectBox1.attr('name', '');
      selectBox2.attr('name', 'rps_administer_official1_id');
      selectBox3.attr('name', '');
      $('#div1').hide();
      $('#div2').show();
      $('#div3').hide();
      $('#div4').hide();
      $('#div5').show();
      $('#div6').hide();
    } else if (id === "3") {
      selectBox1.attr('name', '');
      selectBox2.attr('name', '');
      selectBox3.attr('name', 'rps_administer_official1_id');
      $('#div1').hide();
      $('#div2').hide();
      $('#div3').show();
      $('#div4').hide();
      $('#div5').hide();
      $('#div6').show();
    }	
}
});

function refreshClient(id){
   $.ajax({
 
        url :DIR+'getClientRptProperty', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_person_taking_oath_code").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshClient2(id){
   $.ajax({
 
        url :DIR+'getClientRptProperty', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_administer_official11").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshClient3(id){
   $.ajax({
 
        url :DIR+'getClientRptProperty', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_administer_official2_id1").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getOrNumber(id){
   $.ajax({
 
        url :DIR+'getOrNumberDetails', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_ctc_no").html(html);
          }
        }
    })
}

function getIssueance(id){
  $('.loadingGIF').show();
  var filtervars = {
      id:id
  }; 
  $.ajax({
      type: "POST",
      url: DIR+'getIssuanceDetails',
      data: filtervars,
      dataType: "json",
      success: function(html){ 
        $('.loadingGIF').hide();
        $("#rps_ctc_issued_date").val(html.cashier_or_date)
        $("#rps_ctc_issued_place").val(html.ctc_place_of_issuance)
        $("#cashier_id").val(html.id)
        $("#cashierd_id").val(html.ctodetailsId)
      }
  });
}

function refreshCitizen(){
   $.ajax({
        url :DIR+'getRefreshCitizen', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_representative_id").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshEmployee(){
   $.ajax({
 
        url :DIR+'getEmpDetails', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_administer_official12").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshEmployee2(){
   $.ajax({
 
        url :DIR+'getEmpDetails', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rps_administer_official2_id2").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshEmployeeCert(){
   $.ajax({
 
        url :DIR+'getRefreshEmployee', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_certified_by").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}




 // $(".radio").change(function() {
 //             var checked = $(this).is(':checked');
 //             $(".radio").prop('checked',false);
 //             $(".code").prop('checked',false);
 //             if(checked) {
 //             $(this).prop('checked',true);
 //             }
 //             });


