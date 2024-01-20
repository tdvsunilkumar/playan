$(document).ready(function () {
    let row_count = $('.table-body').find('tr').length;
    console.log(row_count);
    for (let index = 0; index < row_count; index++) {
        console.log(index);
        $(`.imperial-code-${(index + 1)}`).select3({dropdownAutoWidth : false,dropdownParent: $(`.imperial-code-group-${(index + 1)}`)});
    }
    
    $('#cm_id').select3({dropdownAutoWidth : false,dropdownParent: $("#cm_id_group")});
    
    $('.add-more').click(function(){
        $.ajax({
            type: "get",
            url :DIR+'development-permit-computation/get-imperials', // json datasource
            success: function (response) {
                if(response.status == 200){
                    row_count = $('.table-body').find('tr').length;
                    row_count++;
                    let table_row = `<tr>
                        <td>${row_count}</td>
                        <td>
                            <input type="text" name="data[${row_count}][cdpcl_description]" class="form-control" placeholder="description" />
                        </td>
                        <td>
                            <input type="number" name="data[${row_count}][cdpcl_amount]" class="form-control" placeholder="Amount" step="any"/>
                        </td>
                        <td class="imperial-code-group-${row_count}">
                            <select name="data[${row_count}][cis_id]" class="form-control imperial-code-${row_count}">
                                <option value="">Select Types</option>`;
                                response.data.forEach((element, index) => {
                                    table_row+=`<option value="${element.id}">${element.cis_code}</option>`;
                                });
                            table_row+=`</select>
                        </td>
                        <td>
                            <a href="javascript::void(0)"
                                class="btn btn-sm btn-danger remove">
                                <i class="ti-trash"></i>
                            </a>
                        </td>
                    </tr>`;
                    $('.table-body').append(table_row);
                }
                $(`.imperial-code-${row_count}`).select3({dropdownAutoWidth : false,dropdownParent: $(`.imperial-code-group-${row_count}`)});
            }
        });
    });

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    $(".input-amount").on("input", function() {
        // Get the current input value
        var inputValue = $(this).val();
    
        // Remove any non-numeric or non-dot characters
        var sanitizedValue = inputValue.replace(/[^0-9.]/g, '');
    
        // Ensure that there's only one dot in the value
        if (sanitizedValue.indexOf('.') !== sanitizedValue.lastIndexOf('.')) {
          sanitizedValue = sanitizedValue.substring(0, sanitizedValue.lastIndexOf('.'));
        }
    
        // Update the input value with sanitized value
        $(this).val(sanitizedValue);
      });
});