jQuery(document).ready(function() {
    "use strict";
    jQuery('.mega-menu-category .nav > li').hover(function() {
        jQuery(this).addClass("active");
        jQuery(this).find('.popup').stop(true, true).fadeIn('slow');
    }, function() {
        jQuery(this).removeClass("active");
        jQuery(this).find('.popup').stop(true, true).fadeOut('slow');
    });


    jQuery('.mega-menu-category .nav > li.view-more').on('click', function(e) {
        if (jQuery('.mega-menu-category .nav > li.more-menu').is(':visible')) {
            jQuery('.mega-menu-category .nav > li.more-menu').stop().slideUp();
            jQuery(this).find('a').text('More category');
        } else {
            jQuery('.mega-menu-category .nav > li.more-menu').stop().slideDown();
            jQuery(this).find('a').text('Close menu');
        }
        e.preventDefault();
    });

    $(document).on('click', '.captcha_reload', function(e){
        var captcha = $(this).parents('.captcha-row');
        e.preventDefault();
        $.ajax({
            url: '/ajax/captcha.php'
        }).done(function(text){
            if(captcha.find('input[name=captcha_sid]').length){
                captcha.find('input[name=captcha_sid]').val(text);
                captcha.find('img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
            }
            else if(captcha.find('input[name=captcha_code]').length){
                captcha.find('input[name=captcha_code]').val(text);
                captcha.find('img').attr('src', '/bitrix/tools/captcha.php?captcha_code=' + text);
            }
            captcha.find('input[name=captcha_word]').val('').removeClass('error');
            captcha.find('.captcha_input').removeClass('error').find('.error').remove();
        });
    });

})
