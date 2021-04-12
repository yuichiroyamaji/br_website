$(function(){
    scrolltop();
    modal();
    setTimeout('msg_float()'); //アニメーションを実行    
});

function scrolltop() {
    $('a[href^="#"]').click(function(){
        var speed = 500;
        var href= $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top;
        $("html, body").animate({scrollTop:position}, speed, "swing");
        $('.navbar-collapse.in').collapse('hide');
        return false;
    });
    // $('a[href^=#]').click(function(){
    //show screen width
    // $('body').append('<p id=value>ブラウザの横幅</p>');
    // $('#value').css({
    //                 position:'fixed',
    //                 right:'20px',
    //                 bottom:'20px',
    //                 fontSize: '150%',
    //             });
    // var windowWidth = $(window).width();
    // $('#value').text(windowWidth);

    // $(window).resize(function(){
    //     var windowWidth = $(window).width();
    //     $('#value').text(windowWidth);
    // });
    // $('.ios-click').click(function(){
    //     //alert('ios-click');
    //     $('.navbar-collapse.in').collapse('hide');
    // });
}

function modal() {
    $('.js-modal').fadeIn();
    $('.js-modal-close').on('click',function(){
        // $('.js-modal').fadeOut();
        $('.js-modal').hide();
        return false;
    });
}

function msg_float() {
    $('.modal__content').animate({
        marginTop: '-=10px'
    }, 600).animate({
        marginTop: '+=10px'
    }, 600);
    setTimeout('msg_float()', 1400); //アニメーションを繰り返す間隔
}