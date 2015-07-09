$(document).ready(function () {
// fade in #back-top
    $("#content_scroller").on('scroll load resize', function () {
        $('#nav_up,#nav_down').fadeIn('slow');
    });
    $(window).on('scrollstart', function () {
        $('#nav_up,#nav_down').stop().animate({'opacity': '0.2'});
    });
    $(window).on('scrollstop', function () {
        $('#nav_up,#nav_down').stop().animate({'opacity': '1'});
    });
// scroll body to 0px on click
    $('#back-top a').on('click', function () {
        $('#content_scroller').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
    $('#nav_down').on('click',function (e) {
            $('#content_scroller').animate({scrollTop: $('#content_scroller').height()}, 800);
        }
    );
    $('#nav_up').on('click',function (e) {
            $('#content_scroller').animate({scrollTop: '0px'}, 800);
        }
    );
});