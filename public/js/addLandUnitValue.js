$(document).ready(function () {
    $('#pc_class_code').on('change', function() {
        $('#ps_subclass_code').val('').trigger('change');
        $('#pau_actual_use_code').val('').trigger('change');
        getSubClass($(this).val());
        getactualdata($(this).val());
    });

    $("#pc_class_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#pc_class_code").parent(),
    ajax: {
        url: DIR+'rptlandunitvalue/class-ajax-request',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
   
    $('form').submit(function () {
        $("#loc_local_code").attr("disabled", false);
        $("#rvy_revision_year").attr("disabled", false);
        $("#pau_actual_use_code").attr("disabled", false);
        $("#pc_class_code").attr("disabled", false);
        $("#ps_subclass_code").attr("disabled", false);
        $("#lav_unit_measure").attr("disabled", false);

        
    });
    $('#revisionyear').select3('destroy');
    $('#revisionyear').select3();
    
});
function getSubClass(id){
   $("#ps_subclass_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#ps_subclass_code").parent(),
    ajax: {
        url: DIR+'getsubclass',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
function getactualdata(id){
   $("#pau_actual_use_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#pau_actual_use_code").parent(),
    ajax: {
        url: DIR+'getActualdata',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}





  
