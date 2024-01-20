$(document).ready(function () {
    calWeidthX();
    calHeightY();
    $('#pos_x').on('change', function () {
        calWeidthX();
    });
    $('#pos_x_end').on('change', function () {
        calWeidthX();
    });
    $('#pos_y').on('change', function () {
        calHeightY();
    });
    $('#pos_y_end').on('change', function () {
        calHeightY();
    });
    $('#menu_module_id').on('change', function () {
        var selectedMenuModule = $(this).val();

        // Use AJAX to fetch menu_sub_id options based on the selected menu_module_id.
        $.ajax({
            url: DIR+'get_sub_module', // Replace with your server-side endpoint
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
                "menu_module_id" : selectedMenuModule,
              },
            success: function (data) {
                // Clear existing options and populate the menu_sub_id dropdown with new options.
                var menuSubIdDropdown = $('#menu_sub_id');
                menuSubIdDropdown.empty();
                menuSubIdDropdown.append('<option value="" data-placeholder="select">select</option>');

                for (var i = 0; i < data.length; i++) {
                    menuSubIdDropdown.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                }
            }
        });
    });
    
});
function calWeidthX(){
    var pos_x = $('#pos_x').val();
    var pos_x_end = $('#pos_x_end').val();
    var width_x = pos_x - pos_x_end;
    $('#width_x').val(width_x);
}
function calHeightY(){
    var pos_y = $('#pos_y').val();
    var pos_y_end = $('#pos_y_end').val();
    var height_y = pos_y - pos_y_end;
    $('#height_y').val(height_y);
}