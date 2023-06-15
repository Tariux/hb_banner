
<?php

global $wpdb;
$cats = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories', OBJECT);
$categories = [];
foreach ($cats as $cat) {
    $categories[$cat->id] = [
        'value' => $cat->id,
        'title' => $cat->title
    ];
}
$banners = banners_table();

?>



<div class="wrap">
    
    <h1 class="wp-heading-inline">بنرها</h1>
    <a class="page-title-action" href="">افزودن بنر جدید</a>
    
    
    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <td>عنوان</td>
                <td>تصویر</td>
                <td>قیمت</td>
                <td>دسته بندی</td>
                <td>مدیریت</td>
            </tr>
        </thead>
        
        <tbody>
            <?php 
            $html = '';
            foreach ($banners as $banner) {
                $html .= sprintf(
                        '
                        <tr>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                        </tr>
                        ',
                        $banner->title,
                        '<img src="'.get_site_url('wp-content/plugins/hb_banner/banners/thums/' . $banner->image).'" />',
                        $banner->price,
                        $categories[$banner->cat_id]['title'],
                        '
                        <form method="post" action="">
                            <input type="hidden" name="action" value="delete_banner" />
                            <input type="hidden" value="'.$banner->id.'" name="banner_id" />
                            <input type="submit" value="حذف" style="cursor: pointer; color: #fff; background-color: #F30; padding:5px; border: none; border-radius: 5px;" />
                         </form>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="edit_banner" />
                            <input type="hidden" value="'.$banner->id.'" name="banner_id" />
                            <input type="submit" value="ویرایش" style="cursor: pointer; color: #fff; background-color: #1c66a6; padding:5px; border: none; border-radius: 5px;" />
                         </form>
                        '
                    );
            }
            
            ?>
        </tbody>
    </table>
</div>