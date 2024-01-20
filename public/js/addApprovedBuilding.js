$(document).ready(function(){
    $("#uc_code1").select3({dropdownAutoWidth : false,dropdownParent: $("#uc_codediv")});
   function toggleDropdown() {
        var value = $('input[name="pk_is_active"]:checked').val();
        if (value === '0') {
             $("#rp_app_cancel_is_direct").prop('disabled', false);
             $("#rp_app_cancel_type").prop('disabled', false);
             $("#rp_app_cancel_date").prop('disabled', false);
             $("#rp_app_cancel_by_td_no").prop('disabled', false);
             $("#rp_app_cancel_remarks").prop('disabled', false);
             $("#rp_app_cancel_by_td_id").prop('disabled', false);
        } else {
            $("#rp_app_cancel_is_direct").prop('disabled', true);
            $("#rp_app_cancel_type").prop('disabled', true);
            $("#rp_app_cancel_date").prop('disabled', true);
            $("#rp_app_cancel_by_td_no").prop('disabled', true);
            $("#rp_app_cancel_remarks").prop('disabled', true);
            $("#rp_app_cancel_by_td_id").prop('disabled', true);
        }
    }
    
    toggleDropdown();
    // Attach the function to the click event of the radio buttons
    $(document).on('click', 'input[name="pk_is_active"]', function() {
        toggleDropdown();
    });
});
 
