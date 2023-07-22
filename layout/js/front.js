$(function(){

    'use strict'

    $('.login-page h1 span').click(function (){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });

    $("select").selectBoxIt({
        autoWidth: false
    });

    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function(){

        $(this).attr('placeholder', $(this).attr('data-text'));

    });

    $('input').each(function () {
        if ($(this).attr('required') === "required") {
            $(this).after("<span class='asterisk'>*</span>");
        }
    });

    $('.confirm').click(function () {
        return confirm('Are you sure?');
    });

    $('.live').keyup(function(){
        $($(this).data('class')).text($(this).val());
    });

    $('.live-avatar').blur(function(){
        $($(this).data('class')).attr('src',URL.createObjectURL(event.target.files[0]));
    });

    $('.caption').hover(function(){
        $(this).toggleClass('caption-hovered',{duration:200});
    });

    var userError  = true;
    var emailError = true;
    var msgError   = true;


    $('.username').blur(function(){
        if($(this).val().length <= 3){
            $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(300).end().find('.asterisx').fadeIn(100);
            userError = true;
        }else{
            $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(300).end().find('.asterisx').fadeOut(100);
            userError = false;
        }

    });

    $('.email').blur(function(){
        if($(this).val() === ''){
            $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(300).end().find('.asterisx').fadeIn(100);
            emailError = true;
        }else{
            $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(300).end().find('.asterisx').fadeOut(100);
            emailError = false;
        }

    });

    $('.message').blur(function(){
        if($(this).val().length <= 10){
            $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(300).end().find('.asterisx').fadeIn(100);
            msgError = true;
        }else{
            $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(300).end().find('.asterisx').fadeOut(100);
            msgError = false;
        }

    });

    $('.contact-form').submit(function(e){
        if(userError === true || emailError === true || msgError === true){
            e.preventDefault();
            $('.username, .email, .message').blur();
        }
    });
});