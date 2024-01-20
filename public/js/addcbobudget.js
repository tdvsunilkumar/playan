$(document).ready(function () {
    
      $('#dept_id').on('change', function() {
         var id=$(this).val();
           getdivclass(id);
      });
    });
   
  //   $("#selectYear").flatpickr({
  //     // altInput: true,
  //     dateFormat: "Y",
  //     altFormat: "Y",
  //     ariaDateFormat: "Y"
  // });
  $(document).off('keyup','.getannual').on('keyup','.getannual', function() {
    	  getannualdata($(this));
     });



 

    $(document).on('click','.updatecodefunctionality',function(){

      var actionName = $(this).data("actionname");
      var propertyId   = $(this).data("propertyid");
      if(actionName == 'edit'){
        var url = DIR+'cbobudget/store?id='+propertyId;
        var title1 = 'Edit Budget';
        var title2 = 'Edit Budget';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
  
        loadMainForm(url, title, size);
      }else if(actionName == 'submit'){
        
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
          title: 'Are you sure?',
          text: "You wont to Submit?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          reverseButtons: true
        }).then((result) => {
          if(result.isConfirmed)
          {
             $.ajax({
              url :DIR+'cbobudget/DraftSubmit', // json datasource
              type: "POST", 
              data: {
                "id": propertyId,
                "save_draft": 1,  
                "_token": $("#_csrf_token").val(),
              },
              success: function(html){
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Submit Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
                 location.reload();
              }
            })
          }
        });
        // alert('Submitted Sucessfully');actionName
      }else if(actionName == 'approve'){
        var propertyId   = $(this).data("propertyid");
        $.ajax({
          url :DIR+'cbobudget/Approve', // json datasource
          type: "POST", 
          data: {
            "id": propertyId,
            "_token": $("#_csrf_token").val(),
          },
          success: function(html){
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Approve Successfully.',
              showConfirmButton: false,
              timer: 1500
            })
             location.reload();
          }
        })
        
        
      }
      else{
        var propertyId   = $(this).data("propertyid");
        $.ajax({
          url :DIR+'cbobudget/Unlock', // json datasource
          type: "POST", 
          data: {
            "id": propertyId,
            "_token": $("#_csrf_token").val(),
          },
          success: function(html){
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Unlock Successfully.',
              showConfirmButton: false,
              timer: 1500
            })
             location.reload();
          }
        })
      }
    });

    

    $("#btn_addmore_nature").unbind('click');
    $("#btn_addmore_nature").click(function(){
        
        $('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
        addmoreNature1();
    });
    // $('.numeric').numeric();
    $(".btn_cancel_nature").click(function(){
         var id = $(this).attr('id');
        // alert(id);
         
         if(!id)
         {
          $(this).closest(".removenaturedata").remove();  
         }
        else{
            DeleteRecord(id);
        }
       
    });

    function addmoreNature1(){
      var html = $("#hidennatureHtml").html();
      var prevLength = $(".natureDetails").find(".removenaturedata").length;
    
      $("#natureDetails").append(html);
      $(".btn_cancel_nature").click(function(){
          $(this).closest(".removenaturedata").remove();
          var cnt = $(".natureDetails").find(".removenaturedata").length;
          $("#hidennatureHtml").find('select').attr('id','agl_id'+cnt);
      });
      var classid = $(".natureDetails").find(".removenaturedata").length;
      $("#agl_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});

      $("#hidennatureHtml").find('select').attr('id','agl_id'+classid);
      //$('#agl_id'+prevLength).select3({});
    // $(".natureDetails").find('select').select3({});
  }
    function getdivclass(id){
        $.ajax({
      
             url :DIR+'getdivclasss', // json datasource
             type: "GET", 
             data: {
               "id": id, 
               "_token": $("#_csrf_token").val(),
             },
             success: function(html){
               if(html !=''){
                $("#ddiv_id").html(html);
               }
             }
         })
     }
    
     
     function getannualdata(selectedElement){
      var ownVal = selectedElement.val();
      selectedElement.closest('.removenaturedata').find('.bud_budget_annual').val(ownVal*4);
      selectedElement.closest('.removenaturedata').find('.bud_budget_total').val(ownVal*4);
      setTimeout(function(){ calculateTotalBudget(); }, 500);
    
		
	  }
    function calculateTotalBudget() {
      var totalMarketValue = 0.00;
      $('.removenaturedata').find(".bud_budget_total").each(function(total){
        var marketValue = +$(this).val();
        totalMarketValue = parseFloat(marketValue) + parseFloat(totalMarketValue);
      });
      $(".budgetmain").find("#ceiling").val(parseFloat(totalMarketValue));
    }

    