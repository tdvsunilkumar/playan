
var search_query = "";
var category_search = "";
var select_loader = "<option>Loading...</option>";
var text_loader = "Loading...";
var submit_status = "";
var page = 1;
var pagination_numbers = 0;
var selectedData = JSON.parse(localStorage.getItem('selectedData')) || [];
var row_count;

$(document).ready(function () {
    $("#receiver").select3({dropdownAutoWidth : false,dropdownParent: $("#receiver-group")});
    $("#issuance").select3({dropdownAutoWidth : false,dropdownParent: $("#issuance-group")});
    $("#hp_code").select3({dropdownAutoWidth : false,dropdownParent: $("#hp_code-group")});  
	
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
        console.log(selectedData);
        selectedData.forEach(element => {
            let item_id = element;
            var html = $('#hidden-tr-'+element).html();
            $('#tbody').append(html);
            $('.item-qty'+element).prop('name', `items[${element}][issuance_quantity]`);
            $('.item-qty'+element).prop('required', true);
            $('.item-id'+element).prop('name', `items[${element}][ho_inv_posting_id]`);
            $('.item_id'+element).prop('name', `items[${element}][item_id]`);
            $('.is-parent'+element).prop('name', `items[${element}][is_parent]`);
            $('.issuance-uom'+element).prop('name', `items[${element}][issuance_uom]`);
            $('.base-uom'+element).prop('name', `items[${element}][base_uom]`);
            $('#conv-uom'+element).prop('name', `items[${element}][conversion_uom]`);
            $('#conv-uom-qty'+element).prop('name', `items[${element}][conversion_uom_qty]`);
        }); 
        $('#addItemsInventory').modal('hide');
        selectedData = [];
        localStorage.removeItem('selectedData');
    });

    $('.cancel-form').click(function(){
        selectedData = [];
        localStorage.removeItem('selectedData');
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
});

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
    }else if(value == 0){
        $('.item-qty'+key).val(1);
    }else{
        $('.item-qty'+key).val(value);
    }
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

removeTR = (key) =>{
    $('.table-row-'+key).remove();
}

formSubmit = (status_key) =>{
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

    let id = ((page -1) * 10);
    let length = row_count;
    for (let index = 0; index < length; index++) {
        selectCheckbox((id + index));
    }
}

getAllItems = () =>{
    $.ajax({
        type: "get",
        url: "medicine-supplies-issuance/get-items?page="+ page +"&search="+search_query+"&category_search="+category_search+"&is_issuance=1",
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
                    if(selectedData.indexOf((element.sl_no)) !== -1){
                        checked = "checked";
                    }
                    if(element.hrb_expiry_date !== null){
                        expiry = element.hrb_expiry_date;
                    }else if(element.cip_expiry_date !== null){
                        expiry = element.cip_expiry_date;
                    }
                    html+=`<tr>
                    <td><input type="checkbox" multiple class="select-inv" id="checkbox-${(element.sl_no)}" value="${(element.sl_no)}" name="select[]" ${checked} onclick="selectCheckbox(${(element.sl_no)})" /></td>
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

