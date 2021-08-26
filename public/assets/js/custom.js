jQuery(document).ready(function ($) {

    $(".dropdown-trigger").dropdown();

    $('.modal').modal();

    $('.modal-trigger').on('click', function () {
        $('#modal-confirm').attr('href', $(this).data('url'));
        $('.modal').modal('open');
    });

});
