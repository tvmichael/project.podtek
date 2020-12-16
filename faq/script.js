BX.ready(function () {
    $('.faq-page .faq-menu .faq-menu-item').click(function () {
        $('.faq-page .faq-menu .faq-menu-item').each(function () {
            $(this).removeClass('selected');
        });
        $('.faq-page .faq-contain .faq-contain-item').each(function () {
            $(this).removeClass('selected');
        });
        $(this).addClass('selected');
        $(".faq-page .faq-contain .faq-contain-item[data-id='" + $(this).attr('data-id') + "']").addClass('selected');
    });
});