<?php

$cat_list = categories_table();
?>
<div id="hb_banner_container">
    <div class="col-md-12">
        <h1 class="yekan">افزودن دسته بندی جدید</h1>
        <hr>
        <div class="hb_banner_row_flex">
            <div style="min-width: 500px;" class="form-container">
                <form action="" method="post" name="cat_form" enctype="multipart/form-data">
                    <div class="hb_banner_form_group">
                        <label for="title" class="yekan">نام دسته بندی</label>
                        <input type="text" name="title" id="title" class="form-control">
                    <div class="hb_banner_form_group">
                    </div>
                        <label for="image" class="yekan">انتخاب تصویر</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="hb_banner_form_group">
                        <label for="description" class="yekan">توضیحات</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="10"></textarea>
                    </div>
                    <div class="hb_banner_form_group" >
                        <input type="hidden" name="action" value="add_cat" />
						<?= submit_button('ذخیره', 'primary', 'hb_admin_submit', true, ['class' => 'pull_right']) ?>
                    </div>
                </form>
            </div>
            <div class="table-container">
                <table class="hb_banner_table">
                    <thead class="table-dark">
                        <th class="yekan">دسته بندی</th>
                        <th class="yekan">تصویر</th>
                        <th class="yekan">مدیریت</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cat_list as $list) {
                            $tableBody = sprintf(
                                '
                                <tr>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td style="flex">%s</td>
                                </tr>
                                ',
                                $list->title,
                                '<img style="width: 50px;" src="'.get_site_url().'/wp-content/plugins/hb_banner/admin/images/'.$list->image.'" alt="" />',
                                '
                                <form method="post" action="">
                                    <input type="hidden" value="delete_category" name="action" />
                                    <input type="hidden" value="'.$list->id.'" name="cat_id" />
                                    <input type="submit" value="حذف" class="banner_btn_danger" />
                                </form>
                                <div class="banner_cat_edit_section"></div>
                                '
                            );
                            echo $tableBody;
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>