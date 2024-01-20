$(document).ready(function(){
    // open Second modal
    // add this at index.php
    // @include('common.secondModal')
    $('body').on('click', '.second-modal-show', function (e) {
        var url = $(this).data('url');
            $('#secondModal').modal({backdrop: 'static', keyboard: false});
            $('#secondModal').modal('show');
            $.ajax({
                type: "get",
                url: url,
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