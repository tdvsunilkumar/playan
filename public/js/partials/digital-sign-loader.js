$(document).ready(function(){
    $('body').on('click', '.digital-sign-btn', function (e) {
        btn = $(this)
        e.preventDefault();
        window.open(DIR + 'digital-sign?url='+btn.attr('href'), '_blank')
        return false;
    });
});