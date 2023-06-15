<?php
global $wpdb;
$fonts = fonts_table();
?>

<div id="hb_banner_container">
    
    <h1 class="yekan">افزودن فونت جدید</h1>
    <hr>
    <div class="hb_banner_row_flex">
        <div class="form-container">
            <form enctype="multipart/form-data" action="" method="post">
                <div class="hb_banner_form_group">
                    <label class="yekan" for="font_name">نام فونت</label>
                    <input type="text" name="font_name[]" id="font_name" class="form-control yekan">
                </div>
                <div class="hb_banner_form_group">
                    <label class="yekan" for="font_file">انتخاب فایل فونت</label>
                    <input type="file" name="font_file[]" id="font_file" class="form-control yekan">
                </div>
                <div class="hb_banner_form_group">
                    <?= submit_button('ارسال', 'primary', 'add_font', true, ['style'=>'float: left;']); ?>
                </div>
            </form>
        </div>
        <div class="table-container">
            <table class="hb_banner_table">
                <thead >
                <tr>
                    <th class="yekan">نام فونت</th>
                    <th class="yekan">نام فایل</th>
                    <th class="yekan">مدیریت</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($fonts as $font) {
                        $tableBody = sprintf(
                            '
                            <tr>
                                <td>%s</td>
                                <td>%s</td>
                                <td>%s</td>
                            </tr>
                            ',
                            $font->name,
                            $font->file,
                            '<form method="post" action=""><button name="remove_font" type="submit" class="banner_btn_danger">حذف</button><input type="hidden" name="id" value="'.$font->id.'" /></form>'
                        );
                        echo $tableBody;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

