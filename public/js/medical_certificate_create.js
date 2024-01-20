var text_loader = "Loading...";
    var submit_status = ""; 

    $(document).ready(function () {
        $("#officer").select3({dropdownAutoWidth : false,dropdownParent: $("#officer-group")});
        $("#or_no").select3({dropdownAutoWidth : false,dropdownParent: $("#or_no-group")});

        datatablefunction();
        $("#btn_search").click(function(){
            datatablefunction();
        });	

        $('#officer').change(function (e) { 
            $('#licence_number_show').val(text_loader);
            $('#position').val(text_loader);
            $.ajax({
                type: "get",
                url: "medicine-supplies-issuance/designation/"+$(this).val(),
                success: function (response) {
                    if(response.status == 200){
                        $('#position').val(response.data.description);
                        $('#licence_number_show').val(response.data.licence_no);
                    }
                },error(error){
                    $('#postion_show').val('');
                    $('#licence_number_show').val('');
                }
            });
        });
        
    $('#select-contain-citizen').on('change', '#cit_id', function (e) {
        var id = $(this).val();
        select3Ajax('or_no','or_no-group','medical-certificate/get-or-list/'+id);
    });

    $('#cert-type').on('change', '#med_cert_type', function (e) {
        var id = $(this).val();
        $('.type-form').hide();
        if (id == 1) {
            $('#findings-form').show();
        } else if(id == 2){
            $('#medicol-form').show();
        } else if(id == 3){
            $('#findings-form').show();
        }
    });

        $('#or_no').change(function (e) {
            $('#amount_show').val(text_loader);
            
            $.ajax({
                type: "get",
                url: "medical-certificate/get-or-no/"+$(this).val(),
                success: function (response) {
                    if(response.status == 200){
                        if(response.data != null){
                            $('#amount_show').val(response.data.tfc_amount);
                            $('#or_amount').val(response.data.tfc_amount);
                            $('#or_date').val(response.data.cashier_or_date);
                            $('#or_date_show').val(response.data.cashier_or_date);
                            $('#cashierd_id').val(response.data.cashierd_id);
                            $('#cashier_id').val(response.data.cashier_id);
                        }else{
                            $('#amount_show').val(0);
                        }
                    }
                },error(error){
                    $('#postion_show').val('');
                    $('#or_date').val('');
                }
            });
        });

        $('#med_cert_is_free').change(function (e) {
            if($('#med_cert_is_free').not(':checked').length){
                // $('.or_no').prop('required', true);
                $('.or_no_star').show();

                $('#select3-or_no-container').text('Select OR No')

                $('#or_no').prop('disabled', false);
            }else{
                // $('.or_no').prop('required', false);
                $('.or_no_star').hide();

                $('#or_no').find('option').removeAttr("selected");
                $('#or_no').prop('disabled', true);
                $('#or_date_show').val('');
                $('#amount_show').val('0.00');
                $('#select3-or_no-container').text('Free')
            }
        });
        
    });

    formSubmit = (status_key) =>{
        if(submit_status == ''){
        submit_status = status_key;
        $('#is_posted').val(status_key);
        }
    }