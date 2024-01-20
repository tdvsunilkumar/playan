$("#bk_building_kind_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#bk_building_kind_code").parent(),
    ajax: {
        url: DIR+'rptbuildingunitvalue/kind-ajax-request',
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

$("#bt_building_type_code").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#bt_building_type_code").parent(),
    ajax: {
        url: DIR+'rptbuildingunitvalue/type-ajax-request',
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