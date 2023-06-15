
$(document).ready(function () {
    $(document).on('click', '.preview_banner', function () {
        console.log($("#group_name"));
    });

    $(document).on('click', function (event) {
        var _element = $(event.target);
        if (_element.attr('name') == 'add_singer') {
            if (_element.hasClass('dashicons-plus-alt')) {
                var sname2 = _element.closest('.col-md-6').find('[name="singer_name2"]');
                sname2.fadeIn(1000, function () {
                    sname2.css('display', 'block');
                });
                _element.fadeIn(700, function () {
                    _element.removeClass('dashicons-plus-alt').addClass('dashicons-minus');
                    _element.css('color', '#F33333');
                });
            } else {
                var sname2 = _element.closest('.col-md-6').find('[name="singer_name2"]');
                sname2.fadeIn(500, function () {
                    sname2.css('display', 'none');
                });
                _element.fadeIn(700, function () {
                    _element.removeClass('dashicons-minus').addClass('dashicons-plus-alt');
                    _element.css('color', '#007bff');
                });
            }
        }

    });

    $(document).on('click', '[data-name="hb_banner_preview_display"]', function () {
        $('.preview_content').empty();
        $('.preview_image_modal').hide(500);
    });

    $('.color-selector').each(function (i, e) {
        $(e).on('change', function () {
            var color = $(this).val();
            console.log(color);
            $(this).css('backgroundColor', color);
        });
    });
});
