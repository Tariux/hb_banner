<?php

global $wpdb;

$cats = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories', OBJECT);

foreach ($cats as $cat) {
    $categories[$cat->id] = [
        'value' => $cat->id,
        'title' => $cat->title
    ];
}
$banners = banners_table();
?>
<div id="hb_banner_container">
    <h1 class="yekan">افزودن بنر جدید</h1>
    <hr>
   
        <div class="hb_banner_row_flex">
            <div class="form-container">
                 <form id="admin_form" method="post" enctype="multipart/form-data">
                    <div class="hb_banner_form_group">
                        <label for="title" class="yekan">عنوان</label>
                        <input required type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="hb_banner_form_group">
                        <label for="price" class="yekan">قیمت</label>
                        <input type="text" name="price" id="price" class="form-control">
                    </div>
                    <div class="hb_banner_form_group">
                        <label for="cats" class="yekan">انتخاب دسته بندی</label>
                        <select class="selectpicker form-control yekan" title="انتخاب نشده" name="category" id="category">
                            <option value="">انتخاب نشده</option>
							<?php
							foreach ($categories as $cts) {
								echo '<option value="'.$cts['value'].'">'.$cts['title'].'</option>';
							}
							?>
                        </select>
                    </div>
                    <div class="hb_banner_form_group">
                        <label for="discount">میران تخفیف</label>
                        <input type="number" name="discount" id="discount" class="form-control">
                    </div>
                
                    <div class="hb_banner_form_group">
                        <label for="descripttion" class="yekan">توضیحات</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10"></textarea>
                    </div>
            </div>
            <div style="max-width: 300px;" class="form-container">
                <div class="hb_banner_form_group">
                    <label for="image_904" class="yekan">انتخاب تصویر نوشته دار</label>
                    <input type="file" name="writed" id="writed" class="form-control">
                </div>
                <div class="hb_banner_form_group">
                    <label for="image_720" class="yekan">انتخاب تصویر اصلی</label>
                    <input type="file" name="main" id="main" class="form-control">
                </div>
                <div class="hb_banner_form_group">
                    <label for="image_720" class="yekan">انتخاب تصویر استوری</label>
                    <input type="file" name="story" id="story" class="form-control">
                </div>
            
            
                <div class="hb_banner_form_group" >
					<?= submit_button('ذخیره', 'primary', 'hb_admin_submit', true, [] ) ?>
                </div>
            </div>
            </form>
            <div class="table-container">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="all_pro" class="yekan">اعمال دسته جمعی</label>
                            <select name="all_pro" id="all_pro" class="yekan selectpicker" >
                                <option value="">انتخاب نشده</option>
                                <option value="hb_banners_add_discount">افزودن تخفیف</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row align-items-center">
                                <div id="hb_banners_discount">
                                    <label for="hb_banners_discount" class="yekan">درصد تخفیف وارد شود</label><br>
                                    <input style="float: right" type="number" name="hb_banners_discount">
                                    <button id="all_work" type="button" class="btn btn-info yekan" style="float: left; font-size: 12px;">اعمال</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class="hb_banner_table">
                        <thead>
                        <tr>
                            <th class="yekan">
                                <input style="padding-top: 10px;" type="checkbox" name="check_uncheck" class="form-control">
                            </th>
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>دسته بندی</th>
                            <th>مدیریت</th> 
                        </tr>
                        </thead>
                        <tbody>
                          <?php 
                          foreach ($banners as $banner) {
                              $tableBody = sprintf(
                                  '
                                  <tr>
                                    <td><input style="padding-top: 10px;" type="checkbox" name="check_uncheck" class="form-control"></td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td style="display: flex;">%s %s</td>
                                  </tr>
                                  ',
                                  '<img style="width: 50px;" src="'.get_site_url().'/wp-content/plugins/hb_banner/banners/thums/'.$banner->image.'" />',
                                  $banner->title,
                                  $banner->price,
                                  $banner->discount,
                                  $categories[$banner->cat_id]['title'],
                                  '
                                  <form method="post" action="">
                                    <input type="hidden" name="action" value="delete_banner" />
                                    <input type="hidden" value="'.$banner->id.'" name="banner_id" />
                                    <input type="submit" value="حذف" style="cursor: pointer; color: #fff; background-color: #F30; padding:5px; border: none; border-radius: 5px;" />
                                  </form>
                                  ',
                                  '
                                  <form method="post" action="">
                                    <input type="hidden" name="action" value="edit_banner" />
                                    <input type="hidden" value="'.$banner->id.'" name="banner_id" />
                                    <input type="submit" value="ویرایش" style="cursor: pointer; color: #fff; background-color: #1c66a6; padding:5px; border: none; border-radius: 5px;" />
                                  </form>
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


<script>

   
</script>




