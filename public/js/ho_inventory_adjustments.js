var status = "";
var search_query = "";
var category_search = "";
var category_filter = "";
var page = 1;
var pagination_numbers = 0;
var selectedData = JSON.parse(localStorage.getItem('selectedData')) || [];

$(document).ready(function () {

    datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 
     $('.item-form').click(function(){
        selectedData.forEach(element => {
            let item_id = element;
            var html = $('#hidden-tr-'+element).html();
            $('#tbody').append(html);
            $('.item-qty'+element).prop('name', `items[${element}][issuance_quantity]`);
            $('.item-qty'+element).prop('required', true);
            $('.item-id'+element).prop('name', `items[${element}][ho_inv_posting_id]`);
            $('.item_id'+element).prop('name', `items[${element}][item_id]`);
            $('.category'+element).prop('name', `items[${element}][inv_cat_id]`);
            $('.remarks'+element).prop('name', `items[${element}][hiad_remarks]`);
            $('.remarks'+element).prop('required', true);
            $('.issuance-uom'+element).prop('name', `items[${element}][issuance_uom]`);
            $('.base-uom'+element).prop('name', `items[${element}][base_uom]`);
            $('.parent-id'+element).prop('name', `items[${element}][parent_id]`);
            $('.is-parent'+element).prop('name', `items[${element}][is_parent]`);
            $('#conv-uom'+element).prop('name', `items[${element}][conversion_uom]`);
            $('#conv-uom-qty'+element).prop('name', `items[${element}][conversion_uom_qty]`);
        });
        $('#addItemsInventory').modal('hide');
        selectedData = [];
        localStorage.removeItem('selectedData');
    }); 

    $("#item-table").on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
    });

    removeTR = (key) =>{
        $('.table-row-'+key).remove();
    }

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

    $('.inv_cat_search').change(function(){
        category_filter = $(this).val();
        datatablefunction();
    });
    
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
		"columnDefs": [{ orderable: false, targets: [0,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'medicine-supplies-sdjustment/getList', // json datasource
			type: "GET",
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val(),
                "category_filter" : category_filter
		    },
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "hia_id" },
        	{ "data": "item_id" },
			{ "data": "hiad_uom" },
			{ "data": "hiad_qty" },
        	{ "data": "hiad_remarks"},
        	{ "data": "hiad_status"},
        	{ "data": "created_by"},
        	{ "data": "created_at"},
            { "data": "is_active"},
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
			   url :DIR+'medicine-supplies-sdjustment/ActiveInactive', // json datasource
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

openModal = () =>{
    getAllItems();
    $('#addItemsInventory').modal({backdrop: 'static', keyboard: false});
    $('#addItemsInventory').modal('show');
}

updateItemQty = (key, balance_qty) =>{
    let value = $('.item-qty'+key).val();
    var sanitizedValue = value.replace(/[^+0-9-]/g, '');
    $('.item-qty'+key).val(sanitizedValue);
    value = $('.item-qty'+key).val();
    let symbol = value[0];
    let adj_qty = value.replace(/^./, "");

    if(symbol == "-"){
        $('.item-qty-symbol'+key).hide();

        if(adj_qty > balance_qty){
            $('.item-qty'+key).val(symbol + balance_qty);
        }else{
            $('.item-qty'+key).val(value);
        }

    }else if(symbol == "+"){
        $('.item-qty-symbol'+key).hide();
        $('.item-qty'+key).val(value);
    }else{
        $('.item-qty-symbol'+key).show();
        $('.item-qty'+key).val('');
    }
}

updateRemarks = (key, value) =>{
    $('.remarks'+key).val(value);
}

formSubmit = (status_key) =>{
    if(status == ''){
       status = status_key;
       $('#hia_status').val(status_key);
    }
}

selectAllItem = (value) =>{
    if($('.select-all').prop('checked') == true){
        $('.select-inv').prop('checked', true)
    }else{
        $('.select-inv').prop('checked', false)
    }

    let id = ((page -1) * 10);
    let length = row_count;
    for (let index = 0; index < length; index++) {
        selectCheckbox((id + index));
    }
}

// getAllItems = () =>{
//     $.ajax({
//         type: "get",
//         url: "medicine-supplies-issuance/get-items?search="+search_query+"&category_search="+category_search,
//         success: function (response) {
//             if(response.status == 200){
//                 var dateobj = new Date();
//                 var year = dateobj.getFullYear();
//                 let html = ``;
//                 response.data.forEach((element, key) => {
//                     html+=`<tr>
//                     <td>
//                         <input type="checkbox"
//                             multiple class="select-inv"
//                             value="${element.id}" name="select[]" />
//                     </td>
//                     <td>${year}-${element.cip_control_no}</td>
//                     <td>${element.cip_receiving == 1 ? 'Internal' : 'External'}</td>
//                     <td>${element.cip_item_name}</td>
//                     <td>${element.cip_qty_posted}</td>
//                     <td>${element.cip_balance_qty}</td>
//                     <td>${element.uom_code}</td>
//                     <td>${element.cip_expiry_date == null ? '' : element.cip_expiry_date}</td>
//                 </tr>`;
//                 });
//                 $('.all_items_tbody').html(html);
//             }
//         }
//     });
// }

// getAllItems = () =>{
//     $.ajax({
//         type: "get",
//         url: "medicine-supplies-issuance/get-items?page="+ page +"&search="+search_query+"&category_search="+category_search,
//         success: function (response) {
//             if(response.status == 200){
//                 var dateobj = new Date();
//                 var year = dateobj.getFullYear();
//                 let html = ``;
//                 let pagination = ``;
                
//                 // This section is for table data
//                 response.data.data.forEach((element, key) => {
//                     let checked = "";
//                     if(selectedData.indexOf(element.id) !== -1){
//                         checked = "checked";
//                     }
//                     html+=`<tr>
//                     <td><input type="checkbox" multiple class="select-inv" id="checkbox-${element.id}" value="${element.id}" name="select[]" ${checked} onclick="selectCheckbox(${element.id})" /></td>
//                     <td>${year}-${element.cip_control_no}</td>
//                     <td>${element.cip_receiving == 1 ? 'Internal' : 'External'}</td>
//                     <td>${element.cip_item_name}</td>
//                     <td>${element.cip_qty_posted}</td>
//                     <td>${element.cip_balance_qty}</td>
//                     <td>${element.uom_code}</td>
//                     <td>${element.cip_expiry_date == null ? '' : element.cip_expiry_date}</td>
//                     </tr>`;
//                 });
//                 $('.all_items_tbody').html(html);

//                 // This Section for Pagination
//                 pagination_numbers = Math.ceil(response.data.total / response.data.per_page);
//                 pagination+= `<li class="page-item">
//                     <a class="page-link" onclick="previousPage()" href="javascript::void(0)">
//                         Previous
//                     </a>
//                 </li>`;
//                 for (let page_no = 0; page_no < pagination_numbers; page_no++) {
//                     pagination+=`
//                         <li class="page-item ${ (page_no + 1) == page ? 'active' : '' }">
//                             <a class="page-link" onclick="jumpToPage(${(page_no + 1)})" href="javascript::void(0)">
//                             ${(page_no + 1)}
//                             </a>
//                         </li>`
//                 }
                
//                 pagination+=`<li class="page-item">
//                     <a class="page-link" onclick="nextPage()" href="javascript::void(0)">
//                         Next
//                     </a>
//                 </li>`;
//                 $('.pagination').html(pagination);
//             }
//         }
//     });
// }

getAllItems = () =>{
    $.ajax({
        type: "get",
        url: "medicine-supplies-sdjustment/get-items?page="+ page +"&search="+search_query+"&category_search="+category_search,
        success: function (response) {
            console.log(response);
            if(response.status == 200){
                var dateobj = new Date();
                var year = dateobj.getFullYear();
                let html = ``;
                let pagination = ``;
                // This section is for table data
                row_count = response.data.data.length;
                response.data.data.forEach((element, key) => {
                    let checked = "";
                    let expiry = "";
                    if(selectedData.indexOf((key+1)) !== -1){
                        checked = "checked";
                    }
                    if(element.hrb_expiry_date !== null){
                        expiry = element.hrb_expiry_date;
                    }else if(element.cip_expiry_date !== null){
                        expiry = element.cip_expiry_date;
                    }
                    html+=`<tr>
                    <td><input type="checkbox" multiple class="select-inv" id="checkbox-${(key+1)}" value="${(key+1)}" name="select[]" ${checked} onclick="selectCheckbox(${(key+1)})" /></td>
                    <td>${year}-${element.cip_control_no}</td>
                    <td>${element.cip_item_name}</td>
                    <td>${element.hrb_qty_posted != null ? element.hrb_qty_posted : element.cip_qty_posted}</td>
                    <td>${element.hrb_issued_qty != null ? element.hrb_issued_qty : element.cip_issued_qty}</td>
                    <td>${element.hrb_adjust_qty != null ? element.hrb_adjust_qty : element.cip_adjust_qty}</td>
                    <td>${element.hrb_balance_qty != null ? element.hrb_balance_qty : element.cip_balance_qty}</td>
                    <td>${element.uom_code}</td>
                    <td>${expiry}</td>
                    </tr>`;
                });
                $('.all_items_tbody').html(html);

                // This Section for Pagination
                pagination_numbers = Math.ceil(response.data.total / response.data.per_page);
                pagination+= `<li class="page-item">
                    <a class="page-link" onclick="previousPage()" href="javascript::void(0)">
                        Previous
                    </a>
                </li>`;
                for (let page_no = 0; page_no < pagination_numbers; page_no++) {
                    pagination+=`
                        <li class="page-item ${ (page_no + 1) == page ? 'active' : '' }">
                            <a class="page-link" onclick="jumpToPage(${(page_no + 1)})" href="javascript::void(0)">
                            ${(page_no + 1)}
                            </a>
                        </li>`
                }
                
                pagination+=`<li class="page-item">
                    <a class="page-link" onclick="nextPage()" href="javascript::void(0)">
                        Next
                    </a>
                </li>`;
                $('.pagination').html(pagination);
            }
        }
    });
}

updateConvertedUOM = (selections, current_uom, balance_qty, key) =>{
    var myArray = selections.split(',');
    var item_id = myArray[0] ? myArray[0] : 0;
    var selected_uom = myArray[1] ? myArray[1] : 1;
    var current_uom = current_uom;
    if(selected_uom == current_uom){
        return $('#conv-uom-qty'+key).val(balance_qty);
    }
    $.ajax({
        type: "get",
        url: "medicine-supplies-issuance/calculate-conversion",
        data : {item_id : item_id, selected_uom:selected_uom, current_uom:current_uom, balance_qty},
        success: function (response) {
            if(response.status == 200){
                $('#conv-uom-qty'+key).val(response.data);
            }
        }
    });
}

previousPage = () => {
    if(page != 1){
        page--;
        getAllItems();
    }
}

nextPage = () => {
    if(pagination_numbers == page){
        return false;
    }
    page++;
    getAllItems();
}

jumpToPage = (page_no) =>{
    
    page = page_no;
    getAllItems();
}

selectCheckbox = (id) => {
    if($('#checkbox-' + id).prop('checked') == true){
        selectedData.push(id);
    }else{
        const index = selectedData.indexOf(id);
        if (index > -1) {
            selectedData.splice(index, 1);
        }
    }
    localStorage.setItem('selectedData', JSON.stringify(selectedData));
}