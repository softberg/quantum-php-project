jQuery(document).ready(function ($) {

    $(".dropdown-trigger").dropdown();

    $('textarea#content').characterCounter();

    $('.modal').modal();

    $('.modal-trigger').on('click', function () {
        $('#modal-confirm').attr('href', $(this).data('url'));
        $('.modal').modal('open');
    });

    $('.visibility-icon').on('click', function () {
        if($(this).hasClass('on')) {
            $('.off').removeClass('hide');
            $(this).parent('.input-field').find('input[type=text]').attr('type','password');

        } else {
            $('.on').removeClass('hide');
            $(this).parent('.input-field').find('input[type=password]').attr('type','text');
        }

        $(this).addClass('hide');
    })
});


