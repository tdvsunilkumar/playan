$(document).ready(function () {
  
  var serviceid =  $("#es_id option:selected").val(); 
  if(serviceid =='1'){  openbuildingpopup(); }
  if(serviceid =='2'){  opendemolitionpopup(); }
  if(serviceid =='3'){  opensanitarypopup(); }
  if(serviceid =='4'){  openfencingpopup(); }
  if(serviceid =='5'){  openexcavationpopup(); }
  if(serviceid =='6'){  openelecticpopup(); }
  if(serviceid =='8'){  opensignpopup(); }
  if(serviceid =='11'){  opencivilpopup(); }
  if(serviceid =='9'){  openelectronicpopup(); }
  if(serviceid =='10'){  openmechanicalpopup(); }
  if(serviceid =='13'){  openarchitecturalpopup(); }
  var shouldSubmitForm = false;
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var myform = $('form');
        var disabled = myform.find(':input:disabled').removeAttr('disabled');
        var data = myform.serialize().split("&");
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
      if (!shouldSubmitForm) {
         Swal.fire({
            title: "Are you sure?",
            html: '<span style="color: red;">It will not change details after the confirmation?</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
              shouldSubmitForm = true;
              $.ajax({
                  url :$(this).attr("action")+'/formValidation', // json datasource
                  type: "POST", 
                  data: obj,
                  dataType: 'json',
                  success: function(html){
                      if(html.ESTATUS){
                          $("#err_"+html.field_name).html(html.error)
                          $('.'+html.field_name).focus();
                      }else{
                            if($('#id').val()==""){
                            var data   = $('#storeJobService').serialize();
                                $.ajax({ 
                            type: "post",
                            url: 'engjobrequest/savejobreuest',
                            data: data,
                            dataType: "json",
                            success: function(html){ 
                                hideLoader();
                               if(html.status == 'success'){
                                   $("#id").val(html.lastinsertid);
                                   $("#appnumber").val(html.appid2);
                                   $("#appnumber").addClass(html.class);
                                   $("#ejr_jobrequest_no").val(html.jobreqno);
                                   $("#application_id").val(html.appid);
                                   $("#es_id").attr('disabled','disabled'); 
                                   if(html.class =='buildingpermit'){openbuildingpopup();}
                                   if(html.class =='sanitarypermit'){opensanitarypopup();}
                                   if(html.class =='electicpermit'){openelecticpopup();}
                                   if(html.class =='civilpermit'){opencivilpopup();}
                                   if(html.class =='electronicpermit'){openelectronicpopup();}
                                   if(html.class =='mechanicalpermit'){openmechanicalpopup();}
                                   if(html.class =='excavationpermit'){openexcavationpopup();}
                                   if(html.class =='architecturalpermit'){openarchitecturalpopup();}
                                   if(html.class =='fencingpermit'){openfencingpopup();}
                                   if(html.class =='signpermit'){opensignpopup();}
                                   if(html.class =='demolitionpermit'){opendemolitionpopup();}
                                   if(html.surchatgeenable ==1){$("#ejr_surcharge_fee").removeClass('disabled-field');}
                                  Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Application Number Created Successfully.',
                                        showConfirmButton: false,
                                        timer: 1500
                                      })

                                }if(html.status == 'error'){
                                    Swal.fire({
                                          position: 'center',
                                          icon: 'error',
                                          title: html.msg,
                                          showConfirmButton: true,
                                          timer: false
                                        })
                                }
                                if(html.status == 'update'){
                                  location.reload();
                                }
                            },error:function(){
                                hideLoader();
                            }
                          });
                         }else{
                           $('form').unbind('submit');
                           $("form input[name='submit']").trigger("click");
                         }
                      }
                  }
              })
            } else {
                        console.log("Form submission canceled");
                    }
            }); 
        }else{
           $.ajax({
                  url :$(this).attr("action")+'/formValidation', // json datasource
                  type: "POST", 
                  data: obj,
                  dataType: 'json',
                  success: function(html){
                      if(html.ESTATUS){
                          $("#err_"+html.field_name).html(html.error)
                          $('.'+html.field_name).focus();
                      }else{
                            if($('#id').val()==""){
                            var data   = $('#storeJobService').serialize();
                                $.ajax({ 
                            type: "post",
                            url: 'engjobrequest/savejobreuest',
                            data: data,
                            dataType: "json",
                            success: function(html){ 
                                hideLoader();
                               if(html.status == 'success'){
                                   $("#id").val(html.lastinsertid);
                                   $("#appnumber").val(html.appid2);
                                   $("#appnumber").addClass(html.class);
                                   $("#ejr_jobrequest_no").val(html.jobreqno);
                                   $("#application_id").val(html.appid);
                                   $("#es_id").attr('disabled','disabled'); 
                                   if(html.class =='buildingpermit'){openbuildingpopup();}
                                   if(html.class =='sanitarypermit'){opensanitarypopup();}
                                   if(html.class =='electicpermit'){openelecticpopup();}
                                   if(html.class =='civilpermit'){opencivilpopup();}
                                   if(html.class =='electronicpermit'){openelectronicpopup();}
                                   if(html.class =='mechanicalpermit'){openmechanicalpopup();}
                                   if(html.class =='excavationpermit'){openexcavationpopup();}
                                   if(html.class =='architecturalpermit'){openarchitecturalpopup();}
                                   if(html.class =='fencingpermit'){openfencingpopup();}
                                   if(html.class =='signpermit'){opensignpopup();}
                                   if(html.class =='demolitionpermit'){opendemolitionpopup();}
                                   if(html.surchatgeenable ==1){$("#ejr_surcharge_fee").removeClass('disabled-field');}
                                  Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Application Number Created Successfully.',
                                        showConfirmButton: false,
                                        timer: 1500
                                      })

                                }if(html.status == 'error'){
                                    Swal.fire({
                                          position: 'center',
                                          icon: 'error',
                                          title: html.msg,
                                          showConfirmButton: true,
                                          timer: false
                                        })
                                }
                                if(html.status == 'update'){
                                  location.reload();
                                }
                            },error:function(){
                                hideLoader();
                            }
                          });
                         }else{
                           $('form').unbind('submit');
                           $("form input[name='submit']").trigger("click");
                         }
                      }
                  }
              })
        } 
    });
    $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });

   
    function showhideotherremark(id){
      if(id == 15){ $("#ebpa_scope_remarks").removeClass('disabled-field');}
      else{ $("#ebpa_scope_remarks").addClass('disabled-field');}
    }
    function showhideremarksubocc(text){
      if(text == 'Others'){ $("#otherOccupancy").removeClass('disabled-field');}
      else{ $("#otherOccupancy").addClass('disabled-field');}
    }
   function openbuildingpopup(){
      $(".buildingpermit").unbind("click");
      $(".buildingpermit").click(function(){
        $('#addBuildingPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addBuildingPermitmodal').modal('show');
        loadBuildingPermitForm(id = '');
        $(document).on('click','.closeServiceModal',function(){
          $('#addBuildingPermitmodal').modal('hide');
        });
      });
   }

    function opensanitarypopup(){
      $(".sanitarypermit").unbind("click");
      $(".sanitarypermit").click(function(){
        $('#addSanitaryPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addSanitaryPermitmodal').modal('show');
        loadSanitaryPermitForm(id = '');
        $(document).on('click','.closeSanitaryModal',function(){
          $('#addSanitaryPermitmodal').modal('hide');
        });
      });
   }

   function openelecticpopup(){
     $(".electicpermit").unbind("click");
      $(".electicpermit").click(function(){
         $('#addSanitaryPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addElecticPermitmodal').modal('show');
        loadElecticPermitForm(id = '');
        $(document).on('click','.closeElecticModal',function(){
          $('#addElecticPermitmodal').modal('hide');
        });
      });
   }

   function openelectronicpopup(){
    $(".electronicpermit").unbind("click");
      $(".electronicpermit").click(function(){
         $('#addElectronicPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addElectronicPermitmodal').modal('show');
        loadElectronicPermitForm(id = '');
        $(document).on('click','.closeElectronicModal',function(){
          $('#addElectronicPermitmodal').modal('hide');
        });
      });
   }

   function opensignpopup(){
       $(".signpermit").unbind("click");
       $(".signpermit").click(function(){
         $('#addSignPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addSignPermitmodal').modal('show');
        loadSignPermitForm(id = '');
        $(document).on('click','.closeSignModal',function(){
          $('#addSignPermitmodal').modal('hide');
        });
      });
   }


   function openmechanicalpopup(){
       $(".mechanicalpermit").unbind("click");
      $(".mechanicalpermit").click(function(){
        $('#addMechanicalPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addMechanicalPermitmodal').modal('show');
        loadMechanicalPermitForm(id = '');
        $(document).on('click','.closeMechanicalModal',function(){
          $('#addMechanicalPermitmodal').modal('hide');
        });
      });
   }

    function opendemolitionpopup(){
       $(".demolitionpermit").unbind("click");
      $(".demolitionpermit").click(function(){
        $('#addDemolitionPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addDemolitionPermitmodal').modal('show');
        loadDemolitionpermitPermitForm(id = '');
        $(document).on('click','.closeDemolitionModal',function(){
          $('#addDemolitionPermitmodal').modal('hide');
        });
      });
   }


   function openexcavationpopup(){
      $(".excavationpermit").unbind("click");
      $(".excavationpermit").click(function(){
        $('#addExcavationPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addExcavationPermitmodal').modal('show');
        loadExcavationPermitForm(id = '');
        $(document).on('click','.closeExcavationModal',function(){
          $('#addExcavationPermitmodal').modal('hide');
        });
      });
   }

   function openarchitecturalpopup(){
       $(".architecturalpermit").unbind("click");
       $(".architecturalpermit").click(function(){
        $('#addArchitecturalPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addArchitecturalPermitmodal').modal('show');
        loadArchitecturalPermitForm(id = '');
        $(document).on('click','.closeArchitecturalModal',function(){
          $('#addArchitecturalPermitmodal').modal('hide');
        });
      });
   }

   function openfencingpopup(){
       $(".fencingpermit").unbind("click");
       $(".fencingpermit").click(function(){
        $('#addFencingPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addFencingPermitmodal').modal('show');
        loadFencingPermitForm(id = '');
        $(document).on('click','.closeFencingModal',function(){
          $('#addFencingPermitmodal').modal('hide');
        });
      });
   }

   function opencivilpopup(){
     $(".civilpermit").unbind("click");
      $(".civilpermit").click(function(){
        $('#addCivilPermitmodal').modal({backdrop: 'static', keyboard: false});
        $('#addCivilPermitmodal').modal('show');
        loadCivilPermitForm(id = '');
        $(document).on('click','.closeCivilModal',function(){
          $('#addCivilPermitmodal').modal('hide');
        });
      });
   }

   function loadBuildingPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showbuildingappfrom',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#BuildingPermit').html(html);
            setTimeout(function(){ 
             // $("#ebpa_bldg_official_name").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
              // select3Ajax("ebpa_bldg_official_name","BuildingPermit","engjobrequest/getbildOfficialAjax");
             select3Ajax("ebpa_bldg_official_id","BuildingPermit","engjobrequest/getbildOfficialAjax");
             $("#ebfd_sign_category").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
             
             $("#ebfd_incharge_category").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
             // $("#ebfd_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
             select3Ajax("ebfd_applicant_consultant_id","BuildingPermit","engjobrequest/getRptOwnersAjax");
             select3Ajax("ebfd_consent_id","BuildingPermit","engjobrequest/getRptOwnersAjax");
             // $("#ebfd_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
             // $("#ebfd_consent_id").select3({dropdownAutoWidth : false,dropdownParent: $("#BuildingPermit")});
          select3Ajax("ebaf_zoning_assessed_by","ebaf_zoning_assessed_byparrent","getClientsBfpAjax"); 
          select3Ajax("ebaf_bldg_assessed_by","ebaf_bldg_assessed_byparrent","getClientsBfpAjax");  
          select3Ajax("ebaf_plum_assessed_by","ebaf_plum_assessed_byparrent","getClientsBfpAjax"); 
          select3Ajax("ebaf_elec_assessed_by","ebaf_elec_assessed_byparrent","getClientsBfpAjax");

          select3Ajax("ebaf_linegrade_assessed_by","ebaf_linegrade_assessed_byparrent","getClientsBfpAjax"); 
          select3Ajax("ebaf_mech_assessed_by","ebaf_mech_assessed_byparrent","getClientsBfpAjax");  
          select3Ajax("ebaf_others_assessed_by","ebaf_others_assessed_byparrent","getClientsBfpAjax"); 
          select3Ajax("ebaf_total_assessed_by","ebaf_total_assessed_byparrent","getClientsBfpAjax"); 
			 // $("#ebaf_zoning_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_zoning_assessed_byparrent")});
			 // $("#ebaf_bldg_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_bldg_assessed_byparrent")});
			 // $("#ebaf_linegrade_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_linegrade_assessed_byparrent")});
			 // $("#ebaf_plum_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_plum_assessed_byparrent")});
			 // $("#ebaf_elec_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_elec_assessed_byparrent")});
			 // $("#ebaf_mech_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_mech_assessed_byparrent")});
			 // $("#ebaf_others_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_others_assessed_byparrent")});
			 // $("#ebaf_total_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebaf_total_assessed_byparrent")});
            }, 1000);
            commonfunctionforpermit();
            $('#ebpa_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            //$('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            /* $('#appbrgy_code>option:eq('++')').prop('selected', true); */
            $('#jobrequest_id').val($('#id').val());

             var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
               $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
               //$('#>option[text=]').prop('selected', true);
             }
             $('#eba_id>option:eq(1)').prop('selected', true);
              $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
           },
        error: function(){
          hideLoader();
        }
    });
   } 

   function loadSanitaryPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showsanitarypermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#SanitaryPermit').html(html);
          setTimeout(function(){ 
            select3Ajax("espa_preparedby", "SanitaryPermit", "getClientsBfpAjax");
			 // $("#espa_preparedby").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
             select3Ajax("espa_building_official", "SanitaryPermit", "engjobrequest/getbildOfficialAjax");
             // $("#espa_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
             // $("#espa_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
             // $("#espa_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
             select3Ajax("espa_applicant_consultant_id", "SanitaryPermit", "engjobrequest/getRptOwnersAjax");
             select3Ajax("espa_assessed_by", "SanitaryPermit", "engjobrequest/getRptOwnersAjax");
             // $("#espa_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
             // $("#espa_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#SanitaryPermit")});
            }, 1000);
            commonfunctionforsanitarypermit();
            $('#espa_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            // $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            // $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            // $('#ebpa_address_subdivision').val($('#rpo_address_subdivision').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
            });
            var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
            }
             $('#ebot_id').on('change', function() {
                var option =$("#ebot_id option:selected").text();
                showhideremarksubocc(option);
            });
            var suboccuval =  $("#ebot_id option:selected").text(); 
                 if(suboccuval !=""){
                  showhideremarksubocc(suboccuval);
            }
            select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax"); 

           },
        error: function(){
          hideLoader();
        }
    });
   }

    function loadElecticPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showelectricpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#ElecticPermit').html(html);
            commonfunctionforelecticpermit();
             setTimeout(function(){ 
              select3Ajax("eea_building_official", "ElecticPermit", "engjobrequest/getbildOfficialAjax");
             // $("#eea_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             // $("#eea_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             // $("#eea_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             $("#eea_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             $("#eea_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             $("#eea_prepared_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
             select3Ajax("eea_assessed_by", "ElecticPermit", "getClientsBfpAjax");
             // $("#eea_assessed_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ElecticPermit")});
            }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
              $('#ebot_id').on('change', function() {
                var option =$("#ebot_id option:selected").text();
                showhideremarksubocc(option);
              });
             var suboccuval =  $("#ebot_id option:selected").text(); 
                 if(suboccuval !=""){
                showhideremarksubocc(suboccuval);
            }
            select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax"); 
           },
        error: function(){
          hideLoader();
        }
    });
   } 

   function loadSignPermitForm(id, sessionId){
        showLoader();
        $('.loadingGIF').show();
        var filtervars = {
            id:id,
            sessionId:sessionId,
            request_id:$('input[name=id]').val()
        }; 
        $.ajax({
            type: "get",
            url: DIR+'engjobrequest/showsignpermitform',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
              hideLoader();
              $('#Signpermit').html(html);
                commonfunctionforsignpermit();
               setTimeout(function(){ 
                select3Ajax("esa_building_official", "Signpermit", "engjobrequest/getbildOfficialAjax");
               // $("#esa_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#Signpermit")});
               // $("#esa_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Signpermit")});
               // $("#esa_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Signpermit")});
               select3Ajax("esa_applicant_consultant_id", "Signpermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("esa_owner_id", "Signpermit", "engjobrequest/getRptOwnersAjax");
               // $("#esa_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Signpermit")});
               // $("#esa_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Signpermit")});
              }, 1000);
                $('#eea_application_no').val($('#appnumber').val());
                var ownername =  $("#client_id option:selected").text(); 
                $('#ebfd_applicant_consultant_id').val(ownername);
                var ownerarr = ownername.split(' ');
                $('#ebpa_owner_last_name').val(ownerarr[2]);
                $('#ebpa_owner_first_name').val(ownerarr[0]);
                $('#ebpa_owner_mid_name').val(ownerarr[1]);
                $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
                $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
                $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
                var barangaycode = $('#brgy_code').find(":selected").text();
                $('#appbrgy_code').val(barangaycode);
                $('#jobrequest_id').val($('#id').val());
                var muncipal =  $("#ebpa_mun_no option:selected").val(); 
                 if(muncipal ==""){
                 $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
                 }
                 $('#ebs_id').on('change', function() {
                    var id =$(this).val();
                    showhideotherremark(id);
                  });
                  var scopeval =  $("#ebs_id option:selected").val(); 
                     if(scopeval !=""){
                      showhideotherremark(scopeval);
                  }
                  select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
               },
            error: function(){
              hideLoader();
            }
        });
   }

   function loadDemolitionpermitPermitForm(id, sessionId){
        showLoader();
        $('.loadingGIF').show();
        var filtervars = {
            id:id,
            sessionId:sessionId,
            request_id:$('input[name=id]').val()
        }; 
        $.ajax({
            type: "get",
            url: DIR+'engjobrequest/showdemolitionpermitform',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
              hideLoader();
              $('#Demolitionpermit').html(html);
                commonfunctionforDemolitionpermit();
                 setTimeout(function(){ 

               //$("#eda_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#Demolitionpermit")});
               select3Ajax("eda_building_official","Demolitionpermit","engjobrequest/getbildOfficialAjax");
               // $("#eda_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Demolitionpermit")});
               select3Ajax("eda_applicant_consultant_id", "Demolitionpermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("eda_owner_id", "Demolitionpermit", "engjobrequest/getRptOwnersAjax");
               // $("#eda_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#Demolitionpermit")});
              }, 1000);
                $('#eea_application_no').val($('#appnumber').val());
                var ownername =  $("#client_id option:selected").text(); 
                $('#ebfd_applicant_consultant_id').val(ownername);
                var ownerarr = ownername.split(' ');
                // $('#ebpa_owner_last_name').val(ownerarr[2]);
                // $('#ebpa_owner_first_name').val(ownerarr[0]);
                // $('#ebpa_owner_mid_name').val(ownerarr[1]);
                $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
                $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
                $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
                var barangaycode = $('#brgy_code').find(":selected").text();
                $('#appbrgy_code').val(barangaycode);
                $('#jobrequest_id').val($('#id').val());
                 var muncipal =  $("#ebpa_mun_no option:selected").val(); 
                 if(muncipal ==""){
                 $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
                 }
                
                $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
                });
                var scopeval =  $("#ebs_id option:selected").val(); 
                   if(scopeval !=""){
                    showhideotherremark(scopeval);
                }
                 select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
               },
            error: function(){
              hideLoader();
            }
        });
   }
  

  function loadElectronicPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showelectronicspermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#ElectronicPermit').html(html);
            commonfunctionforelectronicpermit();
            setTimeout(function(){ 
              select3Ajax("eeta_building_official","ElectronicPermit","engjobrequest/getbildOfficialAjax");
               // $("#eeta_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#ElectronicPermit")});
               $("#eeta_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElectronicPermit")});
               $("#eeta_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElectronicPermit")});
               select3Ajax("eeta_applicant_consultant_id", "ElectronicPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("eeta_owner_id", "ElectronicPermit", "engjobrequest/getRptOwnersAjax");
               // $("#eeta_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElectronicPermit")});
               // $("#eeta_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ElectronicPermit")});
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
               select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
    });
   }  

   function loadMechanicalPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showmechanicalpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#MechanicalPermit').html(html);
            commonfunctionformechanicalpermit();
            setTimeout(function(){ 
              select3Ajax("ema_building_official", "MechanicalPermit", "engjobrequest/getbildOfficialAjax");
               // $("#ema_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#MechanicalPermit")});
               // $("#ema_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#MechanicalPermit")});
               // $("#ema_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#MechanicalPermit")});
               select3Ajax("ema_applicant_consultant_id", "MechanicalPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("ema_owner_id", "MechanicalPermit", "engjobrequest/getRptOwnersAjax");
               // $("#ema_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#MechanicalPermit")});
               // $("#ema_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#MechanicalPermit")});
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
             var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
              select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
    });
   } 

  function loadExcavationPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showexcavationpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#ExcavationPermit').html(html);
            commonfunctionforexcavationpermit();
             setTimeout(function(){ 
              select3Ajax("eega_building_official", "ExcavationPermit", "engjobrequest/getbildOfficialAjax");
               // $("#eega_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#ExcavationPermit")});
               // $("#eega_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ExcavationPermit")});
               $("#eega_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ExcavationPermit")});
               // $("#eega_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ExcavationPermit")});
               select3Ajax("eega_applicant_consultant_id", "ExcavationPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("eega_owner_id", "ExcavationPermit", "engjobrequest/getRptOwnersAjax");
               // $("#eega_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ExcavationPermit")});
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
              $('#ebot_id').on('change', function() {
                var option =$("#ebot_id option:selected").text();
                showhideremarksubocc(option);
              });
              var suboccuval =  $("#ebot_id option:selected").text(); 
                 if(suboccuval !=""){
                  showhideremarksubocc(suboccuval);
              } 
               select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
      });
     }


    function loadFencingPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showfencingpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#FencingPermit').html(html);
            commonfunctionforFencingpermit();
            setTimeout(function(){ 
              select3Ajax("efa_building_official", "FencingPermit", "engjobrequest/getbildOfficialAjax");
               // $("#efa_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#FencingPermit")});
               // $("#efa_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#FencingPermit")});
               // $("#efa_inspector_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#FencingPermit")});
               // $("#efa_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#FencingPermit")});
               // $("#efa_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#FencingPermit")});
               select3Ajax("efa_applicant_consultant_id", "FencingPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("efa_owner_id", "FencingPermit", "engjobrequest/getRptOwnersAjax");
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
              $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
               select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
      });
     }

        
    function loadArchitecturalPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showarchitecturalpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#ArchitecturalPermit').html(html);
            commonfunctionforarchitecturalpermit();
             setTimeout(function(){ 
              select3Ajax("eea_building_official", "ArchitecturalPermit", "engjobrequest/getbildOfficialAjax");
               // $("#eea_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#ArchitecturalPermit")});
               $("#eea_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ArchitecturalPermit")});
               // $("#eea_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ArchitecturalPermit")});
               // $("#eea_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ArchitecturalPermit")});
               select3Ajax("eea_applicant_consultant_id", "ArchitecturalPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("eea_owner_id", "ArchitecturalPermit", "engjobrequest/getRptOwnersAjax");
               // $("#eea_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ArchitecturalPermit")});
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
             if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
             }
             $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
               select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
      });
     }

    function loadCivilPermitForm(id, sessionId){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
    }; 
    $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showcivilpermitform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#CivilPermit').html(html);
            commonfunctionforcivilpermit();
             setTimeout(function(){ 
              select3Ajax("eca_building_official", "CivilPermit", "engjobrequest/getbildOfficialAjax");
               // $("#eca_building_official").select3({dropdownAutoWidth : false,dropdownParent: $("#CivilPermit")});
               select3Ajax("eca_applicant_consultant_id", "CivilPermit", "engjobrequest/getRptOwnersAjax");
               select3Ajax("eca_owner_id", "CivilPermit", "engjobrequest/getRptOwnersAjax");
               // $("#eca_sign_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#CivilPermit")});

               // $("#eca_incharge_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#CivilPermit")});
               // $("#eca_applicant_consultant_id").select3({dropdownAutoWidth : false,dropdownParent: $("#CivilPermit")});
               // $("#eca_owner_id").select3({dropdownAutoWidth : false,dropdownParent: $("#CivilPermit")});
              }, 1000);
            $('#eea_application_no').val($('#appnumber').val());
            var ownername =  $("#client_id option:selected").text(); 
            $('#ebfd_applicant_consultant_id').val(ownername);
            var ownerarr = ownername.split(' ');
            // $('#ebpa_owner_last_name').val(ownerarr[2]);
            // $('#ebpa_owner_first_name').val(ownerarr[0]);
            // $('#ebpa_owner_mid_name').val(ownerarr[1]);
            $('#ebpa_address_house_lot_no').val($('#rpo_address_house_lot_no').val());
            $('#ebpa_address_street_name').val($('#rpo_address_street_name').val());
            $('#ebpa_address_subdivision').val($('#rpo_address_subdivision1').val());
            var barangaycode = $('#brgy_code').find(":selected").text();
            $('#appbrgy_code').val(barangaycode);
            $('#jobrequest_id').val($('#id').val());
            var muncipal =  $("#ebpa_mun_no option:selected").val(); 
            if(muncipal ==""){
             $("#ebpa_mun_no option:contains(Palayan City)").attr('selected', true);
            }
            $('#ebs_id').on('change', function() {
                var id =$(this).val();
                showhideotherremark(id);
              });
              var scopeval =  $("#ebs_id option:selected").val(); 
                 if(scopeval !=""){
                  showhideotherremark(scopeval);
              }
               select3AjaxPermitno("ebpa_permit_no","permitnodiv","engjobrequest/getPermitnoAjax");
           },
        error: function(){
          hideLoader();
        }
    });
   }

   function commonnextprevious(){
       $(".previouspageModal").click(function(){
             $('#page1').show();
             $('#page2').hide();
        });
   }

   function commonfunctionforpermit(){
       $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationBuilding',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
       $("#btn_buildingrevision").click(function(){
        $('#addBuildingrevisionmodal').modal({backdrop: 'static', keyboard: false});
        $('#addBuildingrevisionmodal').modal('show');
          var filtervars = {
            id:$("#application_id").val(),
            request_id:$('input[name=id]').val(),
            "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
            type: "post",
            url: DIR+'engjobrequest/showbuildingrevisionform',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
              hideLoader();
              $('#Buildingrevision').html(html);
              Loadbuildingrevisionfunctions();
               $(document).on('click','.closebuildingrevisionModal',function(){
                $('#addBuildingrevisionmodal').modal('hide');
              });
            }
          })
       })
      
    $('#ebfd_sign_category').on('change', function() {
        var signcatid = $(this).val();

        $.ajax({
            url: DIR + 'engjobrequest/getConslutant',
            type: "get",
            dataType: "html",
            data: {
                "signcatid": signcatid,
                "_token": $("#_csrf_token").val(),
            },
            success: function(html) {
              $("#ebfd_sign_consultant_id").html(html);
                if (signcatid === '1') {
                  $("#btn_electricalrevision2").hide();
                select3Ajax("ebfd_sign_consultant_id", "BuildingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                   $("#ebfd_sign_consultant_id").html(html);
                   $("#btn_electricalrevision2").show();
                select3Ajax("ebfd_sign_consultant_id", "BuildingPermit", "engjobrequest/getExteranlsAjax");
                }
                else{
                  $("#btn_electricalrevision2").hide();
                }
            }
        });
    });

        $('#ebfd_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#ebfd_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("ebfd_incharge_consultant_id", "BuildingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("ebfd_incharge_consultant_id", "BuildingPermit", "engjobrequest/getExteranlsAjax");
               }
               else{
                $("#btn_electricalrevision").hide();
              }
            }
           })
        });

         $('#ebfd_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#ebfd_sign_category').val();
          signindetailelectric(signcatid,signcategory);
         });
         var signconsultid =  $("#ebfd_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory = $("#ebfd_sign_category option:selected").val();  
          //signindetailelectronic(signcatid,signcategory);
           if (signcategory === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("ebfd_sign_consultant_id", "BuildingPermit", "getClientsBfpAjax");
          } else if (signcategory === '2') {
            $("#btn_electricalrevision2").show();
          select3Ajax("ebfd_sign_consultant_id", "BuildingPermit", "engjobrequest/getExteranlsAjax");
          }else{
            $("#btn_electricalrevision2").hide();
          }
        } 

        $('#ebfd_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#ebfd_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#ebfd_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $("#ebfd_incharge_category option:selected").val();  
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcategory === '1') {
             $("#btn_electricalrevision").hide();
                select3Ajax("ebfd_incharge_consultant_id", "BuildingPermit", "getClientsBfpAjax");
          } else if (signcategory === '2') {
            $("#btn_electricalrevision").show();
         select3Ajax("ebfd_incharge_consultant_id", "BuildingPermit", "engjobrequest/getExteranlsAjax");
          }
          else{
            $("#btn_electricalrevision").hide();
          }
        } 
        $('#ebfd_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#ebfd_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#ebfd_consent_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#ebfd_consent_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        } 

        $('#ebfd_bldg_est_cost,#ebfd_elec_est_cost,#ebfd_plum_est_cost,#ebfd_mech_est_cost,#ebfd_other_est_cost').on('change keyup',function(){
             claculateTotalEstimate();
        })
        $('#ebaf_zoning_amount,#ebaf_linegrade_amount,#ebaf_bldg_amount,#ebaf_plum_amount,#ebaf_elec_amount,#ebaf_others_amount,#ebaf_mech_amount').on('change keyup',function(){
             claculateTotalAssessFee();
        })
        $('.occupancyclass').on('change', function() {
            var id =$(this).val(); var subid ="";
            getsuboccupancy(id,subid);
         });
       var typeoccupancy = $('input[name="ebot_id"]:checked').val();
       if(typeoccupancy > 0){  var subid =$("#ebost_id").val();
          getsuboccupancy(typeoccupancy,subid);
       }

   }

   function getsuboccupancy(id,subid){
          var id = id; var subid = subid;
            $.ajax({
            url :DIR+'engjobrequest/getsuboccupancytype', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "occupancyid": id,"subid":subid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $(".suboccupancytype").html('');
              $("#subtypeoccu"+id).html(html);
              $(".suboccupancydrop").on('change', function() {
                var id1 = $(this).text();
                 var subval =  $("select[name=ebost_id] option:selected").text(); 
                 
                if(subval=='Others'){ $("#ebpa_occ_other_remarks").removeClass('disabled-field'); }
                else{ $("#ebpa_occ_other_remarks").addClass('disabled-field');}
               });
              var subval =  $("select[name=ebost_id] option:selected").text(); 
              $(".suboccupancydrop").select3({ dropdownAutoWidth: false });
                if(subval=='Others'){ $("#ebpa_occ_other_remarks").removeClass('disabled-field'); }
                else{ $("#ebpa_occ_other_remarks").addClass('disabled-field');}
            }
           })
   }

   function showsuboccupancyother(){

   }

   function commonfunctionforsanitarypermit(){
        $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationSanitary',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
        
       commonnextprevious();
        // $('#espa_sign_category').on('change', function() {
        //   var signcatid =$(this).val();
        //   $.ajax({
        //     url :DIR+'engjobrequest/getConslutant', // json datasource
        //     type: "get",
        //     dataType: "html", 
        //     data: {
        //       "signcatid": signcatid, "_token": $("#_csrf_token").val(),
        //     },
        //     success: function(html){
        //       $("#espa_sign_consultant_id").html(html);
        //       if (signcategory === '1') {
        //             select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
        //       } else if (signcategory === '2') {
        //          select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
        //       }
        //     }
        //    })
        // });
        $('#espa_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#espa_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });
        $('#espa_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#espa_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });
        // $('#espa_incharge_category').on('change', function() {
        //   var signcatid =$(this).val();
        //   $.ajax({
        //     url :DIR+'engjobrequest/getConslutant', // json datasource
        //     type: "get",
        //     dataType: "html", 
        //     data: {
        //       "signcatid": signcatid, "_token": $("#_csrf_token").val(),
        //     },
        //     success: function(html){
        //       $("#espa_incharge_consultant_id").html(html);
        //       if (signcategory === '1') {
        //             select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
        //       } else if (signcategory === '2') {
        //          select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
        //       }
        //     }
        //    })
        // });
        $('#espa_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#espa_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#espa_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory = $("#espa_sign_category option:selected").val();  
          //signindetailelectronic(signcatid,signcategory);
          if (signcategory === '1') {
            $("#btn_electricalrevision2").hide();
                    select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
              } else if (signcategory === '2') {
                $("#btn_electricalrevision2").show();
                 select3Ajax("espa_sign_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
              }else{
                $("#btn_electricalrevision2").hide();
              }
        } 

        $('#espa_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#espa_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#espa_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $("#espa_incharge_category option:selected").val();  
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcategory === '1') {
            $("#btn_electricalrevision").hide();
                    select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "getClientsBfpAjax");
              } else if (signcategory === '2') {
                $("#btn_electricalrevision").show();
                 select3Ajax("espa_incharge_consultant_id", "SanitaryPermit", "engjobrequest/getExteranlsAjax");
              }else{
                $("#btn_electricalrevision").hide();
              }
        } 
          $('#espa_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#espa_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        }

        $('#espa_water_closet_qty,#espa_bidette_qty,#espa_floor_drain_qty,#espa_laundry_trays_qty,#espa_lavatories_qty,#espa_dental_cuspidor_qty,#espa_kitchen_sink_qty,#espa_gas_heater_qty,#espa_faucet_qty,#espa_electric_heater_qty,#espa_shower_head_qty,#espa_water_boiler_qty,#espa_water_meter_qty,#espa_drinking_fountain_qty,#espa_grease_trap_qty,#espa_bar_sink_qty,#espa_bath_tubs_qty,#espa_soda_fountain_qty,#espa_slop_sink_qty,#espa_laboratory_qty,#espa_urinal_qty,#espa_sterilizer_qty,#espa_airconditioning_unit_qty,#espa_swimmingpool_qty,#espa_water_tank_qty,#espa_others_qty').on('change keyup',function(){
             var filtervars = {
            id:$(this).val(),
            espa_water_closet_qty:$('#espa_water_closet_qty').val(),
            espa_bidette_qty:$('#espa_bidette_qty').val(),
            espa_floor_drain_qty:$('#espa_floor_drain_qty').val(),
            espa_laundry_trays_qty:$('#espa_laundry_trays_qty').val(),
            espa_lavatories_qty:$('#espa_lavatories_qty').val(),
            espa_dental_cuspidor_qty:$('#espa_dental_cuspidor_qty').val(),
            espa_kitchen_sink_qty:$('#espa_kitchen_sink_qty').val(),
            espa_gas_heater_qty:$('#espa_gas_heater_qty').val(),
            espa_faucet_qty:$('#espa_faucet_qty').val(),
            espa_shower_head_qty:$('#espa_shower_head_qty').val(),
            espa_electric_heater_qty:$('#espa_electric_heater_qty').val(),
            espa_water_boiler_qty:$('#espa_water_boiler_qty').val(),
            espa_water_meter_qty:$('#espa_water_meter_qty').val(),
            espa_drinking_fountain_qty:$('#espa_drinking_fountain_qty').val(),
            espa_grease_trap_qty:$('#espa_grease_trap_qty').val(),
            espa_bar_sink_qty:$('#espa_bar_sink_qty').val(),
            espa_bath_tubs_qty:$('#espa_bath_tubs_qty').val(),
            espa_soda_fountain_qty:$('#espa_soda_fountain_qty').val(),
            espa_slop_sink_qty:$('#espa_slop_sink_qty').val(),
            espa_laboratory_qty:$('#espa_laboratory_qty').val(),
            espa_urinal_qty:$('#espa_urinal_qty').val(),
            espa_sterilizer_qty:$('#espa_sterilizer_qty').val(),
            espa_airconditioning_unit_qty:$('#espa_airconditioning_unit_qty').val(),
            espa_swimmingpool_qty:$('#espa_swimmingpool_qty').val(),
            espa_water_tank_qty:$('#espa_water_tank_qty').val(),
            espa_others_qty:$('#espa_others_qty').val(),
            "_token": $("#_csrf_token").val()
            };
           // showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/calculatesanitaryfee',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  //hideLoader();
                  $('#espa_amount_due').val(html);
                  
                }
              })
        })
   }

      function commonfunctionforelecticpermit(){
        $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationElectric',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
       $("#btn_electricalrevisionid").click(function(){
        $('#addElectricalrevisionmodal').modal({backdrop: 'static', keyboard: false});
        $('#addElectricalrevisionmodal').modal('show');
       
          var filtervars = {
            id:$("#application_id").val(),
            request_id:$('input[name=id]').val(),
            "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
            type: "post",
            url: DIR+'engjobrequest/showelectricrevisionform',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
              hideLoader();
              $('#Electricalrevision').html(html);
              Loadrevisionfunctions();
               $(document).on('click','.closeelectricalrevisionModal',function(){
                $('#addElectricalrevisionmodal').modal('hide');
              });
            }
          })
       })

        $('#eea_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eea_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eea_sign_consultant_id", "ElecticPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eea_sign_consultant_id", "ElecticPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });
        $('#eea_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eea_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("eea_incharge_consultant_id", "ElecticPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eea_incharge_consultant_id", "ElecticPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });

         $('#eea_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eea_sign_category').val();
          signindetailelectric(signcatid,signcategory);
        });
        var signconsultid =  $("#eea_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#eea_sign_category').val();
          //signindetailelectric(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("eea_sign_consultant_id", "ElecticPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eea_sign_consultant_id", "ElecticPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 

        $('#eea_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eea_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eea_incharge_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory = $('#eea_incharge_category').val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("eea_incharge_consultant_id", "ElecticPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eea_incharge_consultant_id", "ElecticPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }

        } 

         $('#eea_owner_id').on('change', function() {
          var clientid =$(this).val();
          GetOwnerInformation(clientid);
        });
          $('#eea_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#eea_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
         // getApplicantDetails(applicantid);
        }
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        } 
   }

   function Loadbuildingrevisionfunctions(){
        $("#ebpfd_id").on('change',function(){
            var filtervars = {
            id:$(this).val(),
            request_id:$('input[name=id]').val(),
            floorarea:$('#ebpf_total_sqm').val(),
            "_token": $("#_csrf_token").val()
            };
           // showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/calculatebuildingfee',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  //hideLoader();
                  $('#ebpf_total_fees').val(html);
                  $('#ebaf_bldg_amount').val(html);
                  claculateTotalAssessFee();
                }
              })
        })
   }

   function Loadrevisionfunctions(){
    $("#eef_total_load_kva").on('change',function(){
            var filtervars = {
            load:$(this).val(),
            "_token": $("#_csrf_token").val()
            };
            showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/checkloadrange',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  hideLoader();
                  $('#eef_total_load_total_fees').val(html);
                  calculateTotalElectricalfees();
                }
              })
        })

        $("#eef_total_ups").on('change',function(){
            var filtervars = {
            upsval:$(this).val(),
            "_token": $("#_csrf_token").val()
            };
            showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/checkupsrange',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  hideLoader();
                  $('#eef_total_ups_total_fees').val(html);
                  calculateTotalElectricalfees();
                }
              })
        })
        $("#eef_pole_location_qty").on('change',function(){
            var filtervars = {
            qty:$(this).val(),
            id:"1",
            "_token": $("#_csrf_token").val()
            };
            showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/getpoleamount',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  hideLoader();
                  $('#eef_pole_location_total_fees').val(html);
                  calculateTotalElectricalfees();
                }
              })
        })
        $("#eef_guying_attachment_qty").on('change',function(){
            var filtervars = {
            qty:$(this).val(),
            id:"2",
            "_token": $("#_csrf_token").val()
            };
            showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/getpoleamount',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  hideLoader();
                  $('#eef_guying_attachment_fees').val(html);
                  calculateTotalElectricalfees();
                }
              })
        })

        $("#eefm_id").on('change',function(){
            var filtervars = {
            id:$(this).val(),
            "_token": $("#_csrf_token").val()
            };
            showLoader(); 
            $.ajax({
                type: "post",
                url: DIR+'engjobrequest/getmiscellaneousamount',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                  hideLoader();
                  var arr = html.split('#');
                  $('#eef_electric_meter_fees').val(arr[0]);
                  $('#eef_wiring_permit_fees').val(arr[1]);
                  $('#eef_miscellaneous_tota_fees').val(arr[2]);
                  calculateTotalElectricalfees();
                }
              })
        })
   }

   function GetOwnerInformation(clientid){
      $.ajax({
            url :DIR+'engjobrequest/getRptClientDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "clientid": clientid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#ownersuffix").val(arr.suffix);
              $("#ownerebpa_address_house_lot_no").val(arr.rpo_address_house_lot_no);
              $("#ownerebpa_address_street_name").val(arr.rpo_address_street_name);
              $("#ownerebpa_address_subdivision").val(arr.rpo_address_subdivision);
              $("#ownertelephoneno").val(arr.p_telephone_no);
            }
           })
   }

   function getApplicantDetails(applicantid){
       $.ajax({
            url :DIR+'engjobrequest/getApplicant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "id": applicantid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#applicant_comtaxcert").val('');
              $("#applicant_date_issued").val('');
              $("#applicant_place_issued").val(''); 
              $("#applicantaddress").val(''); 
              $("#applicant_comtaxcert").val(arr.or_no);
              $("#applicant_date_issued").val(arr.created_at);
              $("#applicant_place_issued").val(arr.ctc_place_of_issuance);
			        $("#applicantaddress").val(arr.address); 
            }
           })
   }
   function getApplicantDetailsfencing(applicantid){
       $.ajax({
            url :DIR+'engjobrequest/getApplicant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "id": applicantid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#applicant_comtaxcert").val('');
              $("#applicant_date_issued").val('');
              $("#applicant_place_issued").val(''); 
              $("#applicantaddress").val(''); 
              $("#applicant_comtaxcert").val(arr.or_no);
              $("#applicant_date_issued").val(arr.created_at);
              $("#applicant_place_issued").val(arr.ctc_place_of_issuance);
              $("#applicantaddress").val(arr.address); 
              $("#applicantaddressnew").val('');
              $("#applicantdateissued").val('');
              $("#applicantplaceissued").val(''); 
              $("#appctcno").val(''); 
              $("#applicantname").val('');
              var applicantnamenew =  $("#efa_applicant_consultant_id option:selected").text(); 
              $("#applicantname").val(applicantnamenew);
              $("#applicantaddressnew").val(arr.address);
              $("#applicantdateissued").val(arr.created_at);
              $("#applicantplaceissued").val(arr.ctc_place_of_issuance); 
              $("#appctcno").val(arr.or_no); 
            }
           })
   }
   function getApplicantDetailsDemolition(applicantid){
       $.ajax({
            url :DIR+'engjobrequest/getApplicant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "id": applicantid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
			        $("#applicantaddressnew").val('');
			        $("#applicantaddress").val('');
              $("#applicant_comtaxcert").val(arr.or_no);
              $("#applicant_date_issued").val(arr.created_at);
              $("#applicant_place_issued").val(arr.ctc_place_of_issuance);
			        $("#applicantaddress").val(arr.address); 
              $("#appctcno").val(arr.or_no);
              var name = $("#eda_applicant_consultant_id option:selected").text();
              $("#applicantname").val(name);
              $("#applicantdateissued").val(arr.created_at);
              $("#applicantplaceissued").val(arr.ctc_place_of_issuance);
			        $("#applicantaddressnew").val(arr.address); 
            }
           })
   }

   function getOwnerDetails(ownerid){
       $.ajax({
            url :DIR+'engjobrequest/getApplicant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "id": ownerid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#owner_comtaxcert").val('');
              $("#owner_date_issued").val('');
              $("#ctcoctno").val('');
              $("#owneraddress").val('');
              $("#ownerplaceissued").val('');
              $("#owner_comtaxcert").val(arr.or_no);
              $("#owner_date_issued").val(arr.created_at);
              $("#ctcoctno").val(arr.cashier_batch_no);
			  $("#owneraddress").val(arr.address); 
              $("#ownerplaceissued").val(arr.ctc_place_of_issuance);
            }
           })
   }

   function signindetailelectric(signcatid,signcategory){
        $.ajax({
            url :DIR+'engjobrequest/getSignDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid,"categoryid":signcategory,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#signaddress").val(arr.address);
              $("#signebfd_sign_ptr_no").val(arr.ptrno);
              $("#signdateissued").val(arr.issueddate);
              $("#signplaceissued").val(arr.issuedplace);
              $("#signtin").val(arr.tinno);
              $("#signprcregno").val(arr.prcno);
            }
           })
   }

   function inchargedetailelectric(signcatid,signcategory){
        $.ajax({
            url :DIR+'engjobrequest/getSignDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid,"categoryid":signcategory,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#inchargenaddress").val(arr.address);
              $("#inchargeebfd_sign_ptr_no").val(arr.ptrno);
              $("#inchargedateissued").val(arr.issueddate);
              $("#inchargeplaceissued").val(arr.issuedplace);
              $("#inchargetin").val(arr.tinno);
              $("#inchargeprcregno").val(arr.prcno);
            }
           })
   }

  function signindetailelectronic(signcatid,signcategory){
        $.ajax({
            url :DIR+'engjobrequest/getSignDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid,"categoryid":signcategory,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#signaddress").val(arr.address);
              $("#signprcno").val(arr.prcno);
              $('#signvalidity').val(arr.validity);
              $("#signdateissued").val(arr.issueddate);
              $("#signplaceissued").val(arr.issuedplace);
              $("#signtin").val(arr.tinno);
              $("#signebfd_sign_ptr_no").val(arr.ptrno);
            }
           })
   }

   function inchargedetailelectronic(signcatid,signcategory){
        $.ajax({
            url :DIR+'engjobrequest/getSignDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid,"categoryid":signcategory,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#inchargenaddress").val(arr.address);
              $("#inchargeebfd_sign_ptr_no").val(arr.ptrno);
              $('#inchargevalidity').val(arr.validity);
              $("#inchargedateissued").val(arr.issueddate);
              $("#inchargeplaceissued").val(arr.issuedplace);
              $("#inchargetin").val(arr.tinno);
              $("#inchargeprcregno").val(arr.prcno);
            }
           })
        }
   function inchargedetaileldemolition(signcatid,signcategory){
       $.ajax({
            url :DIR+'engjobrequest/getSignDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid,"categoryid":signcategory,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#inchargenaddress").val(arr.address);
              $("#inchargeebfd_sign_ptr_no").val(arr.ptrno);
              $('#inchargevalidity').val(arr.validity);
              $("#inchargedateissued").val(arr.issueddate);
              $("#inchargeplaceissued").val(arr.issuedplace);
              $("#inchargetin").val(arr.tinno);
              $("#inchargeprcregno").val(arr.prcno);
              var name = $("#eda_incharge_consultant_id option:selected").text();
              $("#aplicantname").val(name);
              $("#archaddress").val(arr.address);
              $("#archidateissued").val(arr.issueddate);
              $("#archiplaceissued").val(arr.issuedplace);
             }
           })
   }

    function commonfunctionforcivilpermit(){
        $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationCivil',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#eca_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eca_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eca_sign_consultant_id", "CivilPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eca_sign_consultant_id", "CivilPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });
        $('#eca_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eca_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("eca_incharge_consultant_id", "CivilPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eca_incharge_consultant_id", "CivilPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });

        $('#eca_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eca_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
        });
        var signconsultid =  $("#eca_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#eca_sign_category').val();
         // signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("eca_sign_consultant_id", "CivilPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eca_sign_consultant_id", "CivilPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }

        } 

        $('#eca_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eca_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eca_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#eca_incharge_category').val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("eca_incharge_consultant_id", "CivilPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eca_incharge_consultant_id", "CivilPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        $('#eca_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#eca_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#eca_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#eca_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        } 
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        } 
   }

   function commonfunctionforelectronicpermit(){
         $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationElectronic',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#eeta_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eeta_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("eeta_sign_consultant_id", "ElectronicPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eeta_sign_consultant_id", "ElectronicPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });
        $('#eeta_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eeta_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eeta_incharge_consultant_id", "ElectronicPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eeta_incharge_consultant_id", "ElectronicPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });

         $('#eeta_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eeta_applicant_consultant_id").html(html);
            }
           })
        });

         $('#eeta_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eeta_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#eeta_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#eeta_sign_category').val();
          //signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("eeta_sign_consultant_id", "ElectronicPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eeta_sign_consultant_id", "ElectronicPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        $('#eeta_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eeta_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eeta_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#eeta_incharge_category').val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
               $("#btn_electricalrevision").hide();
                select3Ajax("eeta_incharge_consultant_id", "ElectronicPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eeta_incharge_consultant_id", "ElectronicPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 
        
         $('#eeta_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#eeta_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#eeta_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#eeta_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        } 
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        } 
   }

  function commonfunctionformechanicalpermit(){
         $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationMechanical',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#ema_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#ema_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("ema_sign_consultant_id", "MechanicalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("ema_sign_consultant_id", "MechanicalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });
        $('#ema_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#ema_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("ema_incharge_consultant_id", "MechanicalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("ema_incharge_consultant_id", "MechanicalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });

         $('#ema_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#ema_applicant_consultant_id").html(html);
            }
           })
        });

         $('#ema_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#ema_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#ema_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#ema_sign_category').val();
          //signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("ema_sign_consultant_id", "MechanicalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("ema_sign_consultant_id", "MechanicalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        $('#ema_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#ema_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#ema_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#ema_incharge_category').val(); 
         // inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("ema_incharge_consultant_id", "MechanicalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("ema_incharge_consultant_id", "MechanicalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 
        $('#ema_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#ema_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
         // getApplicantDetails(applicantid);
        } 
        $('#ema_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#ema_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        } 
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        } 
   }

  function commonfunctionforexcavationpermit(){
         $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationExcavation',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#eega_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eega_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eega_sign_consultant_id", "ExcavationPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eega_sign_consultant_id", "ExcavationPermit", "engjobrequest/getExteranlsAjax");
               }
               else{
                $("#btn_electricalrevision").hide();
              }
            }
           })
        });
        $('#eega_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eega_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("eega_incharge_consultant_id", "ExcavationPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eega_incharge_consultant_id", "ExcavationPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
               
            }
           })
        });

         $('#eega_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eega_applicant_consultant_id").html(html);
            }
           })
        });

         $('#eega_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eega_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#eega_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory = $("#eega_sign_category option:selected").val(); 
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("eega_sign_consultant_id", "ExcavationPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eega_sign_consultant_id", "ExcavationPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 

        $('#eega_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eega_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eega_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory =  $("#eega_incharge_category option:selected").val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
            select3Ajax("eega_incharge_consultant_id", "ExcavationPermit", "getClientsBfpAjax");
            } else if (signcatid === '2') {
              $("#btn_electricalrevision2").show();
              select3Ajax("eega_incharge_consultant_id", "ExcavationPermit", "engjobrequest/getExteranlsAjax");
           }else{
                $("#btn_electricalrevision2").hide();
               }

        } 

        $('#eega_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#eega_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
         // getApplicantDetails(applicantid);
        } 
        $('#eega_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#eega_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        }  
         $('#ebs_id').on('change', function() {
          var id =$(this).val();
          if(id =='7'){ $("#ebpa_scope_reegarks").removeClass("disabled-field");}
          else{ $("#ebpa_scope_reegarks").addClass("disabled-field");}
         });
          $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        }

   }

   function commonfunctionforFencingpermit(){
       $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationFencing',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#efa_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#efa_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("efa_sign_consultant_id", "FencingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("efa_sign_consultant_id", "FencingPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });
        $('#efa_inspector_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#efa_inspector_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("efa_inspector_consultant_id", "FencingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("efa_inspector_consultant_id", "FencingPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });

         $('#efa_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#efa_applicant_consultant_id").html(html);
            }
           })
        });

         $('#efa_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#efa_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#efa_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =  $("#efa_sign_category option:selected").val();  
          //signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("efa_sign_consultant_id", "FencingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("efa_sign_consultant_id", "FencingPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        $('#efa_inspector_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#efa_inspector_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#efa_inspector_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory =  $("#efa_inspector_category option:selected").val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("efa_inspector_consultant_id", "FencingPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("efa_inspector_consultant_id", "FencingPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 

        //  $('#efa_applicant_consultant_id').on('change', function() {
        //   var clientid =$(this).val();
        //   GetApplicantInformation(clientid);
        // });
        $('#efa_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetailsfencing(applicantid);
         });

        var applicantid =  $("#efa_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#efa_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#efa_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        }
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        } 
        $('#efa_linegrade_amount,#efa_fencing_amount,#efa_electrical_amount,#efa_others_amount').on('change keyup',function(){
             claculateTotalFecingfees();
        })
   }

   function commonfunctionforarchitecturalpermit(){
        $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationArchitectural',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#eea_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eea_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("eea_sign_consultant_id", "ArchitecturalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eea_sign_consultant_id", "ArchitecturalPermit", "engjobrequest/getExteranlsAjax");
               }
               else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });
        $('#eea_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eea_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eea_incharge_consultant_id", "ArchitecturalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eea_incharge_consultant_id", "ArchitecturalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });

         $('#eea_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eea_applicant_consultant_id").html(html);
            }
           })
        });

         $('#eea_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eea_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#eea_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#eea_sign_category').val();
          //signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("eea_sign_consultant_id", "ArchitecturalPermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("eea_sign_consultant_id", "ArchitecturalPermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        $('#eea_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eea_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eea_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#eea_incharge_category').val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
            select3Ajax("eea_incharge_consultant_id", "ArchitecturalPermit", "getClientsBfpAjax");
            } else if (signcatid === '2') {
              $("#btn_electricalrevision").show();
              select3Ajax("eea_incharge_consultant_id", "ArchitecturalPermit", "engjobrequest/getExteranlsAjax");
           }else{
                $("#btn_electricalrevision").hide();
               }
        } 

        //  $('#eea_applicant_consultant_id').on('change', function() {
        //   var clientid =$(this).val();
        //   GetApplicantInformation(clientid);
        // });
         $('#eea_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#eea_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#eea_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#eea_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =ownerid;
          //getOwnerDetails(ownerid);
        }
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        }  
   }

   function commonfunctionforsignpermit(){
          $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationSign',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
        $('#esa_sign_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#esa_sign_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("esa_sign_consultant_id", "Signpermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("esa_sign_consultant_id", "Signpermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
            }
           })
        });
        $('#esa_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#esa_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision2").hide();
                select3Ajax("esa_incharge_consultant_id", "Signpermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("esa_incharge_consultant_id", "Signpermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
            }
           })
        });

         $('#esa_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#esa_applicant_consultant_id").html(html);
            }
           })
        });

         $('#esa_sign_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#esa_sign_category').val();
          signindetailelectronic(signcatid,signcategory);
         });
         var signconsultid =  $("#esa_sign_consultant_id option:selected").val(); 
        if(signconsultid > 0){
          var signcatid =signconsultid;
          var signcategory =$('#esa_sign_category').val();
          //signindetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("esa_sign_consultant_id", "Signpermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("esa_sign_consultant_id", "Signpermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision").hide();
               }
        } 

        $('#esa_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#esa_incharge_category').val();
          inchargedetailelectronic(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#esa_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#esa_incharge_category').val(); 
          //inchargedetailelectronic(signcatid,signcategory);
          if (signcatid === '1') {
            $("#btn_electricalrevision2").hide();
                select3Ajax("esa_incharge_consultant_id", "Signpermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision2").show();
                  select3Ajax("esa_incharge_consultant_id", "Signpermit", "engjobrequest/getExteranlsAjax");
               }else{
                $("#btn_electricalrevision2").hide();
               }
        } 

        //  $('#esa_owner_id').on('change', function() {
        //   var clientid =$(this).val();
        //   GetApplicantInformation(clientid);
        // });
         $('#esa_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetails(applicantid);
         });

        var applicantid =  $("#esa_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetails(applicantid);
        } 
        $('#esa_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#esa_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =applicantid;
          //getOwnerDetails(ownerid);
        }  
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        }
   }

   function commonfunctionforDemolitionpermit(){
          $(".nextpageModal").click(function(){
            var method = $(this).attr('method');
            var data   = $('#page1 :input').serialize();
            $.ajax({
              type: "post",
              url: DIR+'engjobrequest/permitvalidationDemolition',
              data: data,
              dataType: "json",
              success: function(html){ 
                hideLoader();
                if(html.status == 'validation_error'){
                  $('.validate-err').html('');

                  $('#err_'+html.field_name).html(html.error);
                  $('.'+html.field_name).focus();
                }if(html.status == 'success'){
                  $('.validate-err').html('');
                  $('#page1').hide();
                  $('#page2').show();
                }if(html.status == 'error'){
                  Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: html.msg,
                    showConfirmButton: true,
                    timer: false
                  })
                }
              },error:function(){
                hideLoader();
              }
            });   
       })
       commonnextprevious();
         $('#eda_applicant_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eda_applicant_consultant_id").html(html);
            }
           })
        });

         $('#eda_incharge_category').on('change', function() {
          var signcatid =$(this).val();
          $.ajax({
            url :DIR+'engjobrequest/getConslutant', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "signcatid": signcatid, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#eda_incharge_consultant_id").html(html);
              if (signcatid === '1') {
                $("#btn_electricalrevision").hide();
                select3Ajax("eda_incharge_consultant_id", "Demolitionpermit", "getClientsBfpAjax");
                } else if (signcatid === '2') {
                  $("#btn_electricalrevision").show();
                  select3Ajax("eda_incharge_consultant_id", "Demolitionpermit", "engjobrequest/getExteranlsAjax");
               }
               else{
                $("#btn_electricalrevision").hide();
              }
            }
           })
        });
        $('#eda_incharge_consultant_id').on('change', function() {
          var signcatid =$(this).val();
          var signcategory =$('#eda_incharge_category').val();
          inchargedetaileldemolition(signcatid,signcategory);
        });
        var inchargeconsultid =  $("#eda_incharge_consultant_id option:selected").val(); 
        if(inchargeconsultid > 0){
          var signcatid =inchargeconsultid;
          var signcategory = $('#eda_incharge_category').val(); 
          inchargedetaileldemolition(signcatid,signcategory);
          if (signcategory === '1') {
            $("#btn_electricalrevision").hide();
                select3Ajax("eda_incharge_consultant_id", "Demolitionpermit", "getClientsBfpAjax");
          } else if (signcategory === '2') {
            $("#btn_electricalrevision").show();
         select3Ajax("eda_incharge_consultant_id", "Demolitionpermit", "engjobrequest/getExteranlsAjax");
          }
          else{
                $("#btn_electricalrevision").hide();
              }
        } 
        
        $('#eda_owner_id').on('change', function() {
          var ownerid =$(this).val();
          getOwnerDetails(ownerid);
         });

        var ownerid =  $("#eda_owner_id option:selected").val(); 
        if(ownerid > 0){
          var ownerid =ownerid;
          //getOwnerDetails(ownerid);
        }
        $('#eda_applicant_consultant_id').on('change', function() {
          var applicantid =$(this).val();
          getApplicantDetailsDemolition(applicantid);
         });

        var applicantid =  $("#eda_applicant_consultant_id option:selected").val(); 
        if(applicantid > 0){
          var applicantid =applicantid;
          //getApplicantDetailsDemolition(applicantid);
        } 
         $('#ebpa_permit_no').on('change', function() {
          var permitid =$(this).val();
          getbuildingpermitdetails(permitid);
         });

        var permitid =  $("#ebpa_permit_no option:selected").val(); 
        if(permitid > 0){
          var permitidid =permitid;
          getbuildingpermitdetails(permitidid);
        } else{
          $("#permit_owner_name").val('');
          $("#owner_complete_address").val('');
          $("#building_permit_location").val('');
          $("#job_re_reference").val('');
        }
   }

   function getbuildingpermitdetails(id){
       $.ajax({
            url :DIR+'engjobrequest/getbuildingpermitdetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "permitno": id,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              if(isNaN(arr)){
              $("#permit_owner_name").val(arr.full_name);
              $("#owner_complete_address").val(arr.address);
              $("#building_permit_location").val(arr.location);
              $("#job_re_reference").val(arr.ejr_jobrequest_no);
              }else{
                $("#permit_owner_name").val('');
                $("#owner_complete_address").val('');
                $("#building_permit_location").val('');
                $("#job_re_reference").val('');
              }
            }
           })
   }

    function GetApplicantInformation(clientid){
      $.ajax({
            url :DIR+'engjobrequest/getRptClientDetails', // json datasource
            type: "get",
            dataType: "html", 
            data: {
              "clientid": clientid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
               arr = $.parseJSON(html);
              $("#ownersuffix").val(arr.suffix);
              $("#ownerebpa_address_house_lot_no").val(arr.rpo_address_house_lot_no);
              $("#ownerebpa_address_street_name").val(arr.rpo_address_street_name);
              $("#ownerebpa_address_subdivision").val(arr.rpo_address_subdivision);
              $("#ownertelephoneno").val(arr.p_telephone_no);
            }
           })
   }

   function claculateTotalFecingfees(){
             var linegrade = +$("#efa_linegrade_amount").val();
             var fecingamt = +$("#efa_fencing_amount").val();
             var electricamt = +$("#efa_electrical_amount").val();
             var otherfee = +$("#efa_others_amount").val();

             if(isNaN(linegrade)){ linegrade =0;} if(isNaN(fecingamt)){ fecingamt =0;} if(isNaN(electricamt)){ electricamt =0;}  if(isNaN(otherfee)){ otherfee =0;}
              
             var total = parseFloat(linegrade) + parseFloat(fecingamt) + parseFloat(electricamt) +parseFloat(otherfee);
              
             $("#efa_total_amount").val(total);
   }

   function calculateTotalElectricalfees(){
             var totalload = +$("#eef_total_load_total_fees").val();
             var totalups = +$("#eef_total_ups_total_fees").val();
             var totalpolefee = +$("#eef_pole_location_total_fees").val();
             var totalattachment = +$("#eef_guying_attachment_fees").val();
             var totalmiscellaneous = +$("#eef_miscellaneous_tota_fees").val();

             if(isNaN(totalload)){ totalload =0;} if(isNaN(totalups)){ totalups =0;} if(isNaN(totalpolefee)){ totalpolefee =0;}  if(isNaN(totalattachment)){ totalattachment =0;} if(isNaN(totalmiscellaneous)){ totalmiscellaneous =0;}
              
             var total = parseFloat(totalload) + parseFloat(totalups) + parseFloat(totalpolefee) +parseFloat(totalattachment) + parseFloat(totalmiscellaneous);
             $("#eef_total_fees").val(total); $("#eea_amount_due").val(total);
   }


   function claculateTotalEstimate(){
             var bildfee = +$("#ebfd_bldg_est_cost").val();
             var elefee = +$("#ebfd_elec_est_cost").val();
             var plumfee = +$("#ebfd_plum_est_cost").val();
             var mechanicalfee = +$("#ebfd_mech_est_cost").val();
             var otherfee = +$("#ebfd_other_est_cost").val();

             if(isNaN(bildfee)){ bildfee =0;} if(isNaN(elefee)){ elefee =0;} if(isNaN(plumfee)){ plumfee =0;} if(isNaN(mechanicalfee)){ mechanicalfee =0;} if(isNaN(otherfee)){ otherfee =0;}
             var total = parseFloat(bildfee) + parseFloat(elefee) + parseFloat(plumfee) +parseFloat(mechanicalfee) +parseFloat(otherfee);
             $("#ebfd_total_est_cost").val(total);
   }

   function claculateTotalAssessFee(){
             var zoningfee = +$("#ebaf_zoning_amount").val();
             var linegradefee = +$("#ebaf_linegrade_amount").val();
             var buildfee = +$("#ebaf_bldg_amount").val();
             var plumbingfee = +$("#ebaf_plum_amount").val();
             var electricfee = +$("#ebaf_elec_amount").val();
             var mechanicalfee = +$("#ebaf_mech_amount").val();
             var otherfee = +$("#ebaf_others_amount").val();
              
             var total = parseFloat(zoningfee) + parseFloat(linegradefee) + parseFloat(buildfee) +parseFloat(plumbingfee) +parseFloat(electricfee) +parseFloat(mechanicalfee) +parseFloat(otherfee);
             $("#ebaf_total_amount").val(total);  $("#totalssessdfeeamt").val(total);
             if($('#assessedfeeid').val()==""){
               $('#ejr_lineandgrade').val($("#ebaf_linegrade_amount").val());
               $('#ejr_building').val($("#ebaf_bldg_amount").val());
               $('#ejr_electrical').val($("#ebaf_elec_amount").val());
               $('#ejr_plumbing').val($("#ebaf_plum_amount").val());
               $('#ejr_mechanical').val($("#ebaf_mech_amount").val());
            }
     }
});


