$(document).ready(function () {
    $('#cm_id').select3({dropdownAutoWidth : false,dropdownParent: $("#cm_id_group")});
    let row_count = $('.table-body').find('tr').length;
    $('.add-more').click(function(){
        row_count++;
        let table_row = `<tr>
                            <td>${row_count}</td>
                            <td>
                                <input type="number" name="data[${row_count}][czccl_below]" class="form-control" placeholder="Below" />
                            </td>
                            <td>
                                <input type="number" name="data[${row_count}][czccl_over]" class="form-control over-${row_count}" placeholder="Over" />
                            </td>
                            <td>
                                <input type="checkbox" name="data[${row_count}][czccl_over_by_amount]" onclick="checkOverByAmount(${row_count})" class="overbyamo-${row_count}" placeholder="Over By Amount" />
                            </td>
                            <td>
                                <input type="text" name="data[${row_count}][czccl_amount]" class="form-control" placeholder="Amount" />
                            </td>
                            <td>
                                <a href="javascript::void(0)"
                                    class="btn btn-sm btn-danger remove">
                                    <i class="ti-trash"></i>
                                </a>
                            </td>
                        </tr>`;
        $('.table-body').append(table_row);
    });

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
    
    checkOverByAmount = (row) =>{
        console.log(row, row_count);
        if(row == row_count){
            let checked = $(`.overbyamo-${row}`).is(':checked');
            if(checked){
                $(`.over-${row}`).prop('disabled', true);
                $(`.add-more`).addClass('disabled');
            }else{
                $(`.over-${row}`).prop('disabled', false);
                $(`.add-more`).removeClass('disabled');
            }
        }
    }
});