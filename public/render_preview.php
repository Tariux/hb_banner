<?php


require_once ('../../../../wp-config.php');
include('../includes/persian_txt2pic.php');
global $wpdb;
$font_path = HB_BANNER_LOCATION.'/banners/fonts/';
$thumb_path = HB_BANNER_LOCATION.'/banners/thums/';

if (isset($_POST['action']) && $_POST['action'] == 'render_preview') {
	if (md5(sha1(get_current_user_id().$_POST['banner_id'])) == $_POST['preview_access_token']) {
		$_data = $_POST;
		unset($_data['action']);
		unset($_data['render_preview']);
		$banner_id = $_data['banner_id'];
		unset($_data['banner_id']);
		unset($_data['preview_access_token']);
		unset($_data['csrf']);
		unset($_data['access_token']);

		$banner_image = $wpdb->get_results('SELECT image FROM ' . $wpdb->prefix . 'hb_banners_image WHERE banner_id = ' . $_POST['banner_id'] . ' AND type="thumbnail"' , OBJECT)[0]->image;

		$image  = imagecreatefromjpeg($thumb_path.$banner_image);
		$color = imagecolorallocate($image, 255, 255, 255);
		$y = 0;

		list($r, $g, $b) = sscanf($_data['day_color'], "#%02x%02x%02x");
		$day_color = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['talking_color'], "#%02x%02x%02x");
		$talking_color = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['singer_color'], "#%02x%02x%02x");
		$singer_color = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['singer_color_2'], "#%02x%02x%02x");
		$singer_color_2 = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['pr_time_color'], "#%02x%02x%02x");
		$pr_time_color = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['pr_location_color'], "#%02x%02x%02x");
		$pr_location_color = imagecolorallocate($image, $r, $g, $b);

		list($r, $g, $b) = sscanf($_data['group_color'], "#%02x%02x%02x");
		$group_color = imagecolorallocate($image, $r, $g, $b);

		$data = [
			'dayInfo' => [
				'name'        => $_data['day_name'],
				'size'        => p2e($_data['day_size']),
				'font'        => $_data['day_font'],
				'color'       => $day_color,
			],

			'talkingInfo' => [
				'name'      => $_data['talking_name'],
				'size'      => p2e($_data['talking_size']),
				'font'      => $_data['talking_font'],
				'color'     => $talking_color,
			],
			'singerInfo' => [
				'name'       => $_data['singer_name'],
				'size'       => p2e($_data['singer_size']),
				'font'       => $_data['singer_font'],
				'color'      => $singer_color,
			],
			'singer2Info' => [
				'name'     => $_data['singer_name_2'],
				'size'     => p2e($_data['singer_size_2']),
				'font'     => $_data['singer_font_2'],
				'color'    => $singer_color_2,
			],
			'timeInfo' => [
				'name'      => $_data['pr_time'],
				'size'      => p2e($_data['pr_time_size']),
				'font'      => $_data['pr_time_font'],
				'color'      => $pr_time_color,
			],
			'locationInfo' => [
				'name'      => $_data['pr_location'],
				'size'      => p2e($_data['pr_location_size']),
				'font'      => $_data['pr_location_font'],
				'color'     => $pr_location_color,
			],
			'groupInfo' => [
				'name'        => $_data['group_name'],
				'size'        => p2e($_data['group_size']),
				'font'        => $_data['group_font'],
				'color'       => $group_color,
			],

		];

		$y=140;
		for ($i=0; $i <= 90; $i+=30) {
			imagettftext(
				$image,
				15,
				0,
				$i+5,
				$i+50,
				-$color,
				$font_path . 'TEMPOK.TTF',
				'codingland.ir'
			);
		}

		foreach ($data as $key => $dt) {
			$dt['size'] = $dt['size'] / 4;
//            $x = imagesx($image)/2-(mb_strlen($dt['name'])/2)*((int)$dt['size']/2);
            $fontwidth = imagefontwidth(imageloadfont($font_path . $dt['font'] . '.ttf'));
            $x  = (imagesx($image)/2 - ((mb_strlen($dt['name'])*2.35)));
            if ($x < (imagesx($image)/4)) {
                $x = $x + (mb_strlen($dt['name`'])*2);
            } else {
                $x = $x - (mb_strlen($dt['name`'])*2);
            }

            if (empty($dt['name']))
                continue;

//            if ($key == 'singerInfo')
//                $x += 5;

            $y += 22;

            imagettftext(
                $image,
                $dt['size'],
                0,
                $x,
                $y,
                $dt['color'],
                $font_path . $dt['font'] . '.ttf',
//				persian_log2vis($dt['name'])
                $dt['name']
            );
		}
		$user_id = wp_get_current_user()->data->ID;
		imagepng($image, HB_BANNER_LOCATION . '/tmps/' . 'preview_'.$user_id . '_' . $_data['banner_id'] . '_' . date('Y-m-d') . '.png');
		imagedestroy($image);

		echo json_encode([
			'url'   => HB_BANNER_LOCATION_URL . '/tmps/preview_'.$user_id . '_' . $_data['banner_id'] . '_' . date('Y-m-d') . '.png'
		]);
	}
}