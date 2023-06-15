<?php
clearstatcache(true);
global $wpdb;
$catID = explode('catID=', $_SERVER['QUERY_STRING'])[1];
$banner_table = $wpdb->prefix . 'hb_banners';
$image_table = $wpdb->prefix . 'hb_banners_image';
$font_table = $wpdb->prefix . 'hb_banners_fonts';
$banner_info = $wpdb->get_results('SELECT `banner`.`id`, `banner`.`title`, `banner`.`description`, `banner`.`price`, `image`.`image` FROM `' . $banner_table . '` AS `banner` JOIN `' . $image_table . '` AS `image` ON `banner`.`id` = `image`.`banner_id`  WHERE `image`.`type` = "writed" AND `banner`.`cat_id` = ' . $catID, OBJECT);
$fonts = $wpdb->get_results('SELECT * FROM ' . $font_table, OBJECT);


?>