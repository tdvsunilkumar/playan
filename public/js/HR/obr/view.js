$(document).ready(function(){
    // open citizen modal
    $('#empListTable').on('click', '.view-btn', function (e) {
        // _lastSegments = $.obligation_request.getLastSegment();
        var id = $(this).next('.emp-id').val();
        // console.log(_lastSegments);
            $('#secondModal').modal({backdrop: 'static', keyboard: false});
            $('#secondModal').modal('show');
            $.ajax({
                type: "get",
                url: _baseUrl+'hr-payroll-calculate/view/'+id,
                dataType: "html",
                success: function(html){ 
                    $('#2ndModal .body').html(html);
                },
                error: function(){
                }
            });
    });

    // clear modal
    $('#secondModal').on('hidden.bs.modal', function () {
        $('#secondModal .body').empty();
    });
});
