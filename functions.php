<?php

// Banners Menu
function banners_menu()
{
    add_menu_page(
        'بنرها',
        'بنرها',
        'manage_options',
        'banner_menu',
        'banner_content',
        'dashicons-images-alt2',
        10
    );
    add_submenu_page(
        'banner_menu',
        'دسته بندی ها',
        'دسته بندی ها',
        'manage_options',
        'banner_categories',
        'banner_categories'
    );
    add_submenu_page(
        'banner_menu',
        'فونت ها',
        'فونت ها',
        'manage_options',
        'banner_fonts',
        'banner_fonts'
    );
    add_submenu_page(
        'banner_menu',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'banner_settings',
        'banner_settings'
    );
}

//Banners Menu Functions
function banner_categories()
{
    if (is_admin()) {
        global $wpdb;
        $message = '';
        if (isset($_POST['action']) && $_POST['action'] == 'add_cat') {
            list($name, $type) = explode('.', $_FILES['image']['name']);

            if ($type == 'png' || $type == 'jpg' || $type == 'jpeg') {
                // upload
                if (move_uploaded_file($_FILES['image']['tmp_name'], HB_BANNER_LOCATION . '/admin/images/' . md5($name) . '.' . $type)) {
                    // insert data
                    $_data = [
                        'title' => htmlspecialchars(addslashes($_POST['title'])),
                        'description' => htmlspecialchars(addslashes($_POST['description'])),
                        'image' => md5($name) . '.' . $type,
                        'dateadded' => date('Y-m-d H:i:s'),
                        'addedfrom' => get_current_user_id()
                    ];
                    $wpdb->insert($wpdb->prefix . 'hb_banners_categories', $_data);
                    if ($wpdb->insert_id) {
                        $message = sprintf('<div class="updated notice"><p>%s</p></div>', 'عملیات موفقیت آمیز بود');
                    } else {
                        $message = sprintf('<div class="error notice"><p>%s</p></div>', 'خطایی در ورود اطلاعات رخ داده');
                    }
                } else {
                    // redError
                    $message = sprintf('<div class="error notice"><p>%s</p></div>', $_FILES['image']['error']);
                }
            } else {
                // redError
                $message = sprintf('<div class="error notice"><p>%s</p></div>', 'فرمت اشتباه است');
            }
        }
        if (isset($_POST['action']) && $_POST['action'] == 'delete_category') {
            $cat_id = $_POST['cat_id'];
            $category = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories WHERE id = ' . $cat_id, OBJECT)[0];
            $query_result = $wpdb->delete($wpdb->prefix.'hb_banners_categories', ['id' => $cat_id]);
            $message = '';
            if ($query_result) {
                if (is_file(HB_BANNER_LOCATION . '/admin/images/' . $category->image))
                    unlink(HB_BANNER_LOCATION . '/admin/images/' . $category->image);
                $message = sprintf('<div class="updated notice"><p>%s</p></div>', 'حذف موفقیت آمیز بود');
            } else {
                $message = sprintf('<div class="error notice"><p>%s</p></div>', 'لطفا دوباره تلاش کنید');
            }

        }
        echo $message;

        require_once(HB_BANNER_LOCATION . '/admin/categories.php');
    }
}

function banner_fonts()
{
    if (is_admin()) {
        global $wpdb;
        $message = '';
        if (isset($_POST['add_font'])) {
            $files = $_FILES;
            $sorted_array_file = [];
            foreach ($files as $file) {
                foreach ($file['name'] as $key => $name) {
                    $sorted_array_file[$key]['name'] = $name;
                }
                foreach ($file['type'] as $key => $type) {
                    $sorted_array_file[$key]['type'] = $type;
                }
                foreach ($file['tmp_name'] as $key => $tmp_name) {
                    $sorted_array_file[$key]['tmp_name'] = $tmp_name;
                }
                foreach ($file['error'] as $key => $error) {
                    $sorted_array_file[$key]['error'] = $error;
                }
                foreach ($file['size'] as $key => $size) {
                    $sorted_array_file[$key]['size'] = $size;
                }
            }
            $data = $sorted_array_file;
            foreach ($_POST['font_name'] as $key => $font_name) {
                $data[$key]['font_name'] = $font_name;
            }

            $upload_insert_result = [];
            foreach ($data as $value) {
                if ($value['error'] == 0 && strtolower(explode('.', $value['name'])[1]) == 'ttf') {
                    if (move_uploaded_file($value['tmp_name'], __DIR__.'/banners/fonts/' . $value['name'])) {
                        $insert_result = $wpdb->insert($wpdb->prefix. 'hb_banners_fonts', ['file' => $value['name'], 'name' => $value['font_name']]);
                        if ($insert_result) {
                            $upload_insert_result['result'] = true;
                            $upload_insert_result['message'] = 'آپلود انجام شد';
                        } else {
                            $upload_insert_result['result'] = false;
                            $upload_insert_result['message'] = 'خطایی در ورود اطلاعات رخ داده';
                        }
                    } else {
                        $upload_insert_result['result'] = false;
                        $upload_insert_result['message'] = 'خطایی در آپلود رخ داده است';
                    }
                } else {
                    $upload_insert_result['result'] = false;
                    $upload_insert_result['message'] = 'از پسوندهای مجاز استفاده کنید';
                }
            }

            if ($upload_insert_result['result']) {
                $message = sprintf(
                    '
                <div class="updated notice">
                    <p>%s</p>
                </div>
                ',
                    $upload_insert_result['message']
                );
            } else {
                $message = sprintf(
                    '
                <div class="error notice">
                    <p>%s</p>
                </div>
                ',
                    $upload_insert_result['message']
                );
            }
        }
        if (isset($_POST['remove_font'])) {
            $remove_result = remove_font($_POST['id']);
            if ($remove_result) {
                $message = sprintf(
                    '
                <div class="error notice">
                    <p>%s</p>
                </div>
                ',
                'حذف موفقیت آمیز بود'
                );
            } else {
                $message = sprintf(
                    '
                <div class="error notice">
                    <p>%s</p>
                </div>
                ',
                'خطایی رخ داده'
                );
            }
        }
        echo $message;
        require_once(HB_BANNER_LOCATION . '/admin/add_font.php');
    }
}

function banner_content()
{
    if (is_admin()) {
        handle_admin_form();
        delete_banner();
        require_once(HB_BANNER_LOCATION . '/admin/admin_content.php');
    }
}

//EditBanner

function edit_banner()
{
	global $wpdb;
	
	if (isset($_POST['action']) && $_POST['action'] == 'edit_banner') {
	    $update_result = $wpdb->update($wpdb->prefix.'hb_banners', [
	            'title' => sanitize_text_field($_POST['title']),
	            'price' => sanitize_text_field($_POST['price']),
	            'discount' => sanitize_text_field($_POST['discount']),
	            'cat_id' => sanitize_text_field($_POST['category']),
	            'description' => sanitize_text_field($_POST['description'], ['id' => $_POST['id']])
	        ]);
        $message = '';
        if ($update_result) 
            $message = sprintf('<div class="updated notice"><p>%s</p></div>', 'عملیات موفقیت آمیز بود');
        else
            $message = sprintf('<div class="error notice"><p>%s</p></div>', 'دوباره تلاش کنید');
        echo $message;
	        
	}
	
	$result = $wpdb->get_results(
		'SELECT 
       				banners.id, 
       				banners.title, 
       				banners.price, 
       				banners.discount, 
       				banners.description,
       				categories.id as cat_id ,
       				images.image 
				FROM '.$wpdb->prefix.'hb_banners as banners 
				JOIN '.$wpdb->prefix.'hb_banners_categories as categories
				ON banners.cat_id = categories.id
				JOIN '.$wpdb->prefix.'hb_banners_image as images
				ON images.banner_id = banners.id
				WHERE banners.id = ' . htmlspecialchars(addslashes($_POST['banner_id'])),
		OBJECT
	);
	$generated_info = [];
	foreach ($result as $key => $value) {
		$generated_info[$value->id] = [
			'banner_id'                 => $value->id,
			'banner_title'              => $value->title,
			'banner_price'              => $value->price,
			'banner_discount'           => $value->discount,
			'banner_description'        => $value->description,
			'banner_cat_id'             => $value->cat_id,
		];
		$generated_info[$value->id]['banner_images'][] = $value->image;
	}
	echo '<hr /><pre>';
	print_r($generated_info);
	echo '</pre>';
	exit();
}

//RenderDataTable

function banners_table()
{
	global $wpdb;
	$banners = $wpdb->get_results(
		'SELECT bns.id, bns.discount, bns.price, bns.title, bns.cat_id, img.image, cts.title as cstitle FROM '
		. $wpdb->prefix . 'hb_banners as bns JOIN '
		. $wpdb->prefix . 'hb_banners_image as img ON bns.id = img.banner_id JOIN '
		. $wpdb->prefix . 'hb_banners_categories as cts ON bns.cat_id = cts.id WHERE img.type = "thumbnail"',
		OBJECT
	);

	return $banners;
}

function categories_table()
{
	global $wpdb;
	$categories = $wpdb->get_results(
		'SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories',
		OBJECT
	);
	return $categories;
}

function fonts_table()
{
	global $wpdb;
	$fonts = $wpdb->get_results(
		'SELECT * FROM ' . $wpdb->prefix . 'hb_banners_fonts',
		OBJECT
	);
	return $fonts;
}

//ChangeDiscount
add_action('wp_ajax_change_discount', 'change_discount');
add_action('wp_ajax_nopriv_change_discount', 'change_discount');
function change_discount()
{
	global $wpdb;

	foreach ($_POST['banners_id'] as $id) {
		$res = $wpdb->update(
			$wpdb->prefix . 'hb_banners',
			[ 'discount' => $_POST['discount'] ],
			[ 'id' => $id ]
		);
	}

	echo json_encode([
		'result'    => 'true',
		'msg'       => 'success'
	]);
	exit();
}

//DeleteBanner

function delete_banner()
{
	if (
		isset($_POST['action']) &&
		$_POST['action'] == 'delete_banner'
	) {
		global $wpdb;
		$message = '';
		try {
			$res = $wpdb->get_results('SELECT image FROM ' . $wpdb->prefix . 'hb_banners_image WHERE banner_id = ' . html_entity_decode( addslashes( $_POST['banner_id'] ) ), OBJECT);

			$wpdb->delete(
				$wpdb->prefix . 'hb_banners',
				[ 'id' => htmlspecialchars( addslashes( $_POST['banner_id'] ) ) ]
			);
			$wpdb->delete(
				$wpdb->prefix . 'hb_banners_image',
				[ 'banner_id' => htmlspecialchars( addslashes( $_POST['banner_id'] ) ) ]
			);
			if (is_file(HB_BANNER_LOCATION . '/banners/' . $res[0]->image))
			    unlink(HB_BANNER_LOCATION . '/banners/' . $res[0]->image);
		    if (HB_BANNER_LOCATION . '/banners/' . $res[1]->image)
			    unlink(HB_BANNER_LOCATION . '/banners/' . $res[1]->image);
			$message = sprintf('<div class="updated notice"><p>%s</p></div>', 'حذف موفقیت آمیز بود');
			
		} catch(Exception $e) {
		    $message = sprintf('<div class="error notic"><p>%s</p></div>', 'لطفا دوباره تلاش کنید');
		}
		
		echo $message;
	}
}

/*********************UsefulAdmin************************/

//AlertAdminMessage
function alert_message($message, $type)
{
	echo '<script>
	            $(document).ready(function() {
	                $("#msg").addClass("alert-'.$type.'");
	                $("#msg_text").html("'.$message.'");
	                $("#msg_show").animate({opacity: "1"});
	                setTimeout(function () {
	                    $("#msg_show").animate({opacity: "0"});    
	                }, 2000);
	            });    
	        </script>';
}


function render_link($banner_id)
{
	global $wpdb;
	$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_users_order WHERE banner_id = ' . $banner_id, OBJECT);
	$links = [];
	foreach ($result as $res) {
		$links['main'] = '<a target="_blank" class="btn btn-primary yekan font12" style="margin: 3px; padding: 3px;" href="'.HB_BANNER_LOCATION_URL . '/render_banner.php/main/' . $banner_id . '/' . sha1(md5($res->order_date.$res->userid)) .'">دانلود بنر اصلی</a>';
		$links['story'] = '<a target="_blank" class="btn btn-primary yekan font12" style="margin: 3px; padding: 3px;" href="'.HB_BANNER_LOCATION_URL . '/render_banner.php/story/' . $banner_id . '/' . sha1(md5($res->order_date.$res->userid)) .'">دانلود بنر استوری</a>';
	}
	return $links;
}

function hb_banners_get_cats()
{
	global $wpdb;
	$cat_list = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories', OBJECT);
	return $cat_list;
}

function p2e($string) {
	$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
	$english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

	$output= str_replace($persian, $english, $string);
	return $output;
}

function remove_font($id)
{
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'hb_banners_fonts', ['id' => $id]);
}


add_action('wp_ajax_user_form', 'handle_user_form');
function handle_user_form()
{
    global $wpdb;
    if (isset($_POST['user_form'])) {
        //Enter data without pay
        $_data = $_POST;
        unset($_data['csrf']);
        unset($_data['access_token']);
        unset($_data['preview_access_token']);
        unset($_data['action']);
        unset($_data['user_form']);
        $_data['userid'] = get_current_user_id();
        $date = date('Y-m-d H:i:s');
        $_data['order_date'] = $date;
        $_data['pay_result'] = 1;
        $_data['tracking_code'] = 0;

        $insert_result = $wpdb->insert(
            $wpdb->prefix . 'hb_banners_users_order',
            $_data
        );
    }
}


function handle_admin_form()
{
    if (is_admin()) {

        global $wpdb;
        if (isset($_POST['hb_admin_submit'])) {
            $wpdb->insert(
                $wpdb->prefix . 'hb_banners',
                [
                    'title' => $_POST['title'],
                    'price' => $_POST['price'],
                    'cat_id' => $_POST['category'],
                    'description' => $_POST['description'],
                    'create_date' => date('Y-m-d H:i:s')
                ]
            );
            $insert_id = $wpdb->insert_id;
            $upload_result = true;
            foreach ($_FILES as $key => $value) {
                $name = explode('.', $_FILES[$key]['name']);
                $type = $name[1];
                $name = md5(sha1($name[0]) . strtotime(date('Y-m-d H:i:s'))) . '.' . $type;
                if (move_uploaded_file($_FILES[$key]['tmp_name'], HB_BANNER_LOCATION.'/banners/' . $name)) {
                    $wpdb->insert(
                        $wpdb->prefix . 'hb_banners_image',
                        [
                            'banner_id' => $insert_id,
                            'image'     => $name,
                            'type'      => $key
                        ]
                    );
                } else {
                    $upload_result = false;
                    break;
                }

            }
            $message = '';
            if ($upload_result) {
                // render thubmnail
                $image = $wpdb->get_results('SELECT image FROM ' . $wpdb->prefix . 'hb_banners_image WHERE banner_id = ' . $insert_id . ' AND type="story"', OBJECT);
                $sourceImage = imagecreatefromjpeg(HB_BANNER_LOCATION . '/banners/' . $image[0]->image);
                $orgWidth = imagesx($sourceImage);
                $orgHeight = imagesy($sourceImage);
                $thumbHeight = floor($orgHeight * (200 / $orgWidth));
                $destImage = imagecreatetruecolor(200, $thumbHeight);
                imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, 200, $thumbHeight, $orgWidth, $orgHeight);
                imagejpeg($destImage, HB_BANNER_LOCATION . '/banners/thums/' . $insert_id . '.' . $type);
                imagedestroy($sourceImage);
                imagedestroy($destImage);
                $insert_result = $wpdb->insert(
                    $wpdb->prefix . 'hb_banners_image',
                    [
                        'banner_id' => $insert_id,
                        'image'     => $insert_id . '.' . $type,
                        'type'      => 'thumbnail'
                    ]
                );

                if ($insert_result) {
                    $message = sprintf(
                        '
                        <div class="updated notice">
                            <p>%s</p>
                        </div>
                        ',
                        'عملیات موفقیت آمیز بود'
                    );
                } else {
                    $message = sprintf(
                        '
                        <div class="error notice">
                            <p>%s</p>
                        </div>
                        ',
                        'خطایی در ورود اطلاعات رخ داده است'
                    );
                }


            } else {
                $message = sprintf(
                    '
                        <div class="error notice">
                            <p>%s</p>
                        </div>
                        ',
                    'خطایی در آپلود رخ داده'
                );
            }

            echo $message;

        }
    } else
        die('access_denied');
}



function client_script_css()
{
    echo '
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js" integrity="sha512-XZEy8UQ9rngkxQVugAdOuBRDmJ5N4vCuNXCh8KlniZgDKTvf7zl75QBtaVG1lEhMFe2a2DuA22nZYY+qsI2/xA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
           ';

    wp_enqueue_script( 'custom', plugins_url( 'public/js/custom.js', __FILE__ ) );


    echo '
            <script src="'.get_site_url().'/wp-content/plugins/hb_banner/public/js/coloris.min.js"></script>
            <script>
            Coloris({
              el: ".coloris",
              swatches: [
                "#264653",
                "#2a9d8f",
                "#e9c46a",
                "#f4a261",
                "#e76f51",
                "#d62828",
                "#023e8a",
                "#0077b6",
                "#0096c7",
                "#00b4d8",
                "#48cae4"
              ]
            });
            </script>
    ';

    echo '<link rel="stylesheet" href="'.get_site_url().'/wp-content/plugins/hb_banner/public/css/coloris.min.css" />';
    echo '<style>
    
    
    .input-coloris {
      width: 150px;
      height: 32px;
      padding: 0 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: inherit;
      font-size: inherit;
      font-weight: inherit;
      box-sizing: border-box;
    }
    
    .square .clr-field button,
    .circle .clr-field button {
      width: 22px;
      height: 22px;
      left: 5px;
      right: auto;
      border-radius: 5px;
    }

    .square .clr-field input,
    .circle .clr-field input {
      padding-left: 36px;
    }

    .circle .clr-field button {
      border-radius: 50%;
    }

    .full .clr-field button {
      width: 100%;
      height: 100%;
      border-radius: 5px;
    }

    </style>';

    wp_enqueue_style( 'custom', plugins_url( 'public/css/custom.css', __FILE__ ));
    wp_enqueue_style( 'jquery-form');
}
// add css
function add_custom_style()
{
    $cssFiles = scandir(dirname(__FILE__).'/banners/fonts');
    echo '<style>';
    foreach ($cssFiles as $key => $value) {
        if ($value == '.' || $value == '..')
            continue;
        echo "
        @font-face {
            font-family: ".explode('.', $value)[0].";
            src: url('".HB_BANNER_LOCATION_URL."/banners/fonts/".$value."');
        }
        ";
    }

    echo '</style>';
}