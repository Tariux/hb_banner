

$(document).on('click', function (event) {
    var _element = $(event.target);
    if (_element.attr('data-name') == 'delete_banner') {

        $.ajax({
            url: window.location.origin+'/ppress/wp-admin/admin-ajax.php',
            type: 'post',
            dataType: 'json',
            data: {
                banner_id: _element.parent().find('[name="banner_id"]').val(),
                access_token: _element.parent().find('[name="access_token"]').val(),
                action: 'delete_banner'
            },
            success: function (response) {
                if (response.result) {
                    $("#msg").addClass("alert-success");
                    $("#msg_text").html("عملیات موفقیت آمیز بود");
                    $("#msg_show").animate({opacity: "1"});
                    setTimeout(function () {
                        $("#msg_show").animate({opacity: "0"});
                    }, 2000);
                    bannersDataTable.ajax.reload();
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
});

$(document).on('click', '[name="check_uncheck"]', function () {
    if ($(this).is(':checked')) {
        $('[name="checker"]').each(function (i, e) {
            $(e).prop('checked', true);
        });
    } else {
        $('[name="checker"]').each(function (i, e) {
            $(e).prop('checked', false);
        });
    }
});

$(document).on('change', '[name="all_pro"]', function () {
    if ($(this).val() == 'hb_banners_add_discount') {
        $("#hb_banners_discount").fadeIn(700, function () {
            $("#hb_banners_discount").css('display', 'block');
        });
    }
});

$(document).on('click', '#all_work', function () {
    var _this = $(this);
    if ($('[name="hb_banners_discount"]').val().length == 0) {
        $(this).closest('div').find('input').css('border', '1px solid #F30');
        $(this).closest('div').find('label').css('color', '#F30');
        return;
    } else {
        var banners_id = [];
        $('input[type="checkbox"]').each(function (i, e) {
            if ($(e).attr('name') == 'checker' && $(e).is(':checked')) {
                banners_id.push($(e).next().val());
            }
        });
        if (banners_id.length != 0) {

            $.ajax({
                url: window.location.origin+'/ppress/wp-admin/admin-ajax.php',
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'change_discount',
                    banners_id: banners_id,
                    discount: $('[name="hb_banners_discount"]').val()
                },
                beforeSend: function () {
                    _this.css('font-size', '24px');
                    _this.html('لطفا صبر کنید');
                },
                success: function (response) {
                    if (response.result) {
                        _this.css('font-size', '12px');
                        _this.html('اعمال');
                        $("#msg").addClass("alert-success");
                        $("#msg_text").html("عملیات موفقیت آمیز بود");
                        $("#msg_show").animate({opacity: "1"});
                        setTimeout(function () {
                            $("#msg_show").animate({opacity: "0"});
                        }, 2000);
                        bannersDataTable.ajax.reload();
                    }
                },
                error: function (err) {
                    _this.css('font-size', '12px');
                    _this.html('اعمال');
                    console.log(err);
                }
            });
        } else {
            alert('حداقل یک مورد را انتخاب کنید.');
        }
    }

});