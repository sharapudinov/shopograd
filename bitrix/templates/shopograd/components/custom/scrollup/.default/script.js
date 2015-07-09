$(document).ready(function () {
// hide #back-top first
    $("#back-top").hide();

// fade in #back-top

    $("#content_scroller").on('scroll load resize', function() {
        if ($(this).scrollTop() > 100) {
            $('#back-top').fadeIn();
        } else {
            $('#back-top').fadeOut();
        }
    });
// scroll body to 0px on click
    $('#back-top a').on('click',function () {
        $('#content_scroller').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
});