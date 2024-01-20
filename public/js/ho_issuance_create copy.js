
var search_query = "";
var category_search = "";
var select_loader = "<option>Loading...</option>";
var text_loader = "Loading...";
var issuance_type_search = "";
var submit_status = "";

$(document).ready(function () {
    $("#receiver").select3({dropdownAutoWidth : false,dropdownParent: $("#receiver-group")});
    $("#issuance").select3({dropdownAutoWidth : false,dropdownParent: $("#issuance-group")});
    $("#hp_code").select3({dropdownAutoWidth : false,dropdownParent: $("#hp_code-group")}); 
    issuance_type_search = $("#issuance_type_search").val(); 

	$("#btn_search").click(function(){
 		datatablefunction();
 	});

    $('#hp_code').change(function (e) { 
        $('#position').val(text_loader);
        $.ajax({
            type: "get",
            url: "medicine-supplies-issuance/designation/"+$(this).val(),
            success: function (response) {
                if(response.status == 200){
                    $('#position').val(response.data.description);
                    console.log(response);
                }
            },error(error){
                $('#position').val('');
            }
        });
    });

    $('#receiver').change(function (e) { 
        $('#receiver_age').val(text_loader);
        $('#receiver_brgy').val(text_loader);
        let issuance_type = $('#issuance_type_hide').val();
        let url = '';
        if(issuance_type == 1){
            url = "medicine-supplies-issuance/citizeninfo/" +$(this).val();
        }else{
            url = "medicine-supplies-issuance/employeeinfo/" +$(this).val();
        }
        $('#receiver_hide').val($(this).val());
        getReceiverDetails(url);
    });

    $('.item-form').click(function(){
        var arr = [];
        $('input.select-inv:checkbox:checked').each(function () {
            arr.push($(this).val());
        });

        arr.forEach(element => {
            let item_id = element;
            console.log(element);
            var html = $('#hidden-tr-'+element).html();
            $('#tbody').append(html);
            $('.item-qty'+element).prop('name', `items[${element}][issuance_quantity]`);
            $('.item-qty'+element).prop('required', true);
            $('.item-id'+element).prop('name', `items[${element}][ho_inv_posting_id]`);
            $('.item_id'+element).prop('name', `items[${element}][item_id]`);
            $('.issuance-uom'+element).prop('name', `items[${element}][issuance_uom]`);
        });
        $('#addItemsInventory').modal('hide');
        $('.select-inv').prop('checked', false);
    });

    $("#item-table").on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    $('#search-items').keyup(function(){
        search_query = $(this).val();
        getAllItems();
    });

    $('.category').change(function(){
        var categories = [];
        $('input.category:checkbox:checked').each(function () {
            categories.push($(this).val());
        });
        
        if(categories.length > 0){
            category_search = JSON.stringify(categories);
        }else{
            category_search = "";
        }
        
        getAllItems();
    });

    $('.issuance_type_search').change(function(){
        issuance_type_search = $(this).val();
        datatablefunction();
    });
    datatablefunction();
});

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
		"columnDefs": [{ orderable: false, targets: [0,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'medicine-supplies-issuance/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val(),
                "issuance_type_search" : issuance_type_search
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "issuance_code" },
        	{ "data": "hp_code" },
			{ "data": "receiver_name" },
			{ "data": "issuance_status" },
        	{ "data": "is_active" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var recordid = $(this).attr('id');
	            DeleteRecord(recordid);
	        });

			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 	ActiveInactiveUpdate(recordid,is_activeinactive);
			});
	    }
	});  
}

function ActiveInactiveUpdate(id,is_activeinactive){
   var msg = is_activeinactive==1?'restored':'removed';
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "This record will be "+msg,
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
		  	$.ajax({
			   url :DIR+'medicine-supplies-issuance/ActiveInactive', // json datasource
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

getReceiverDetails = (url) =>{
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if(response.status == 200){
                $('#receiver_brgy').val(response.data.brgy_name);
                $('#receiver_brgy_hide').val(response.data.barangay_id);
                $('#receiver_age').val(response.data.age);
                $('#receiver_age_hide').val(response.data.age);
            }
        }
    });
}

openModal = () =>{
    getAllItems();
    $('#addItemsInventory').modal({backdrop: 'static', keyboard: false});
    $('#addItemsInventory').modal('show');
}

updateItemQty = (key, value, balance_qty) =>{
    if(value > balance_qty){
        $('.item-qty'+key).val(parseInt(balance_qty));
    }else{
        $('.item-qty'+key).val(value);
    }
}

formSubmit = (status_key) =>{
    console.log(status, $('.cip_status').val());
    if(submit_status == ''){
       submit_status = status_key;
       $('#issuance_status').val(status_key);
    }
}

selectAllItem = (value) =>{
    if($('.select-all').prop('checked') == true){
        $('.select-inv').prop('checked', true)
    }else{
        $('.select-inv').prop('checked', false)
    }
}

getAllItems = () =>{
    $.ajax({
        type: "get",
        url: "medicine-supplies-issuance/get-items?search="+search_query+"&category_search="+category_search,
        success: function (response) {
            if(response.status == 200){
                var dateobj = new Date();
                var year = dateobj.getFullYear();
                let html = ``;
                response.data.forEach((element, key) => {
                    html+=`<tr>
                    <td>
                        <input type="checkbox" 
                            multiple class="select-inv" 
                            value="${element.id}" name="select[]" />
                    </td>
                    <td>${year}-${element.cip_control_no}</td>
                    <td>${element.cip_receiving == 1 ? 'Internal' : 'External'}</td>
                    <td>${element.cip_item_name}</td>
                    <td>${element.cip_qty_posted}</td>
                    <td>${element.cip_balance_qty}</td>
                    <td>${element.cip_expiry_date}</td>
                    <td>${element.uom_code}</td>
                </tr>`;
                });
                $('.all_items_tbody').html(html);
            }
        }
    });
}