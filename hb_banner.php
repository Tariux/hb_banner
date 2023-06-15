<?php
/**
 * Plugin Name: فروش بنرهای سفارشی
 * Plugin URI: http://codingland.ir/
 * Description: بنرهای طرحی شده توسط کاربران شخصی سازی می شوند و قابلیت فروش دارند
 * Version: 1.0
 * Author: Vahid Habibzadeh
 * Author URI: http://codingland.ir/
 */

if (!defined('ABSPATH')) {
    exit();
}

define('HB_BANNER_LOCATION', dirname(__FILE__));
define('HB_BANNER_LOCATION_URL', plugins_url('', __FILE__));
include HB_BANNER_LOCATION . '/functions.php';

add_action('admin_menu', 'banners_menu');

register_activation_hook(__FILE__, 'banner_database');
function banner_database()
{
    global $wpdb;
    $hb_banner_db_version = '1.0';

    $query = '
        CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'hb_banners (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR (255) NOT NULL,
            `price` VARCHAR (255) NOT NULL,
            `discount` VARCHAR (100) NOT NULL,
            `cat_id` INT (11) NOT NULL,
            `description` TEXT,
            `create_date` DATETIME,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
    ';

    $query2 = '
        CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'hb_banners_image (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `banner_id` VARCHAR (255) NOT NULL,
            `image` VARCHAR (255) NOT NULL,
            `type`  VARCHAR (255) NOT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
    ';

    $query3 = '
        CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'hb_banners_fonts (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `file` VARCHAR (255) NOT NULL,
            `name` VARCHAR (255) NOT NULL,
            `slug` VARCHAR (255) NOT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
    ';

    $query4 = '
        CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'hb_banners_users_order (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `userid` VARCHAR (255) NOT NULL,
            `banner_id` VARCHAR (255) NOT NULL,
            `order_date` DATETIME NOT NULL,
            
            `day_name` VARCHAR (255) NOT NULL,
            `day_size` INT (11) NOT NULL,
            `day_font` VARCHAR (255) NOT NULL,
            `day_color` VARCHAR (255) NOT NULL,
            
            `group_name` VARCHAR (255) NOT NULL,
            `group_size` INT (11) NOT NULL,
            `group_font` VARCHAR (255) NOT NULL,
            `group_color` VARCHAR (255) NOT NULL,
            
            `talking_name` VARCHAR (255) NOT NULL,
            `talking_size` INT (11) NOT NULL,
            `talking_font` VARCHAR (255) NOT NULL,
            `talking_color` VARCHAR (255) NOT NULL,
            
            `singer_name` VARCHAR (255) NOT NULL,
            `singer_size` INT (11) NOT NULL,
            `singer_font` VARCHAR (255) NOT NULL,
            `singer_color` VARCHAR (255) NOT NULL,
            
            `singer_name_2` VARCHAR (255) NOT NULL,
            `singer_size_2` INT (11) NOT NULL,
            `singer_font_2` VARCHAR (255) NOT NULL,
            `singer_color_2` VARCHAR (255) NOT NULL,
            
            `pr_time` VARCHAR (255) NOT NULL,
            `pr_time_size` INT (11) NOT NULL,
            `pr_time_font` VARCHAR (255) NOT NULL,
            `pr_time_color` VARCHAR (255) NOT NULL,
            
            `pr_location` VARCHAR (255) NOT NULL,
            `pr_location_size` INT (11) NOT NULL,
            `pr_location_font` VARCHAR (255) NOT NULL,
            `pr_location_color` VARCHAR (255) NOT NULL,
            
            `pay_result` VARCHAR (255) NOT NULL,
            `tracking_code` INT (11) NOT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
    ';

    $query5 = '
        CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'hb_banners_categories (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `banner_id` INT (12) NOT NULL,
            `title` VARCHAR (255) NOT NULL,
            `image` VARCHAR (255) NOT NULL,
            `description` TEXT NOT NULL,
            `dateadded` DATETIME NOT NULL,
            `addedfrom` INT (12) NOT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci;
    ';

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($query);
    dbDelta($query2);
    dbDelta($query3);
    dbDelta($query4);
    dbDelta($query5);
    add_option('hb_banner_db_version', $hb_banner_db_version);
}

add_shortcode('banner_cats_inside', function () {
    handle_user_form();
    include __DIR__ . '/public/user_form.php';
});

add_shortcode('banner_cats_view', function () {
    include __DIR__ . '/public/main_view.php';
});

add_action('admin_print_styles', function () {
    wp_enqueue_style( 'custom', plugins_url( 'admin/css/custom.css', __FILE__ ) );
});
add_action('admin_print_scripts', function () {
    //wp_enqueue_script( 'custom', plugins_url( 'admin/js/custom.js', __FILE__ ) );
});
add_action( 'wp_enqueue_scripts', 'client_script_css' );

add_action('wp_enqueue_scripts', 'add_custom_style', 1000);










