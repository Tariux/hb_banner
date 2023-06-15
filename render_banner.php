<?php
require_once ('../../../wp-config.php');
global $wpdb;


$datas = explode('/', $_SERVER['PATH_INFO']);
$tr_data = [
	'size'              => $datas[1],
	'banner_id'         => $datas[2],
	'hash'              => $datas[3],
];


$order_info = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_users_order WHERE banner_id = ' . $tr_data['banner_id'], OBJECT)[0];
$img = $wpdb->get_results('SELECT image FROM ' . $wpdb->prefix . 'hb_banners_image WHERE banner_id = ' . $tr_data['banner_id'] . ' AND type = "'.$tr_data['size'].'"', OBJECT)[0]->image;

$hash = $order_info->order_date . $order_info->userid;
$image      = imagecreatefromjpeg( HB_BANNER_LOCATION . '/banners/' . $img );
list($r, $g, $b) = sscanf($order_info->day_color, "#%02x%02x%02x");
$day_color = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->talking_color, "#%02x%02x%02x");
$talking_color = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->singer_color, "#%02x%02x%02x");
$singer_color = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->singer_color_2, "#%02x%02x%02x");
$singer_color_2 = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->pr_time_color, "#%02x%02x%02x");
$pr_time_color = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->pr_location_color, "#%02x%02x%02x");
$pr_location_color = imagecolorallocate($image, $r, $g, $b);

list($r, $g, $b) = sscanf($order_info->group_color, "#%02x%02x%02x");
$group_color = imagecolorallocate($image, $r, $g, $b);

$mainData = [
	'dayInfo' => [
		'name'        => $order_info->day_name,
		'size'        => $order_info->day_size,
		'font'        => $order_info->day_font,
        'color'       => $day_color
	],
	'talkingInfo' => [
		'name'        => $order_info->talking_name,
		'size'        => $order_info->talking_size,
		'font'        => $order_info->talking_font,
        'color'       => $talking_color
	],
	'singerInfo' => [
		'name'      => $order_info->singer_name,
		'size'      => $order_info->singer_size,
		'font'      => $order_info->singer_font,
        'color'     => $singer_color
	],
	'singer2Info' => [
		'name'       => $order_info->singer_name_2,
		'size'       => $order_info->singer_size_2,
		'font'       => $order_info->singer_font_2,
        'color'      => $singer_color_2
	],
	'timeInfo' => [
		'name'      => $order_info->pr_time,
		'size'      => $order_info->pr_time_size,
		'font'      => $order_info->pr_time_font,
        'color'     => $pr_time_color
	],
	'locationInfo' => [
		'name'      => $order_info->pr_location,
		'size'      => $order_info->pr_location_size,
		'font'      => $order_info->pr_location_font,
        'color'     => $pr_location_color
	],
	'groupInfo' => [
		'name'      => $order_info->group_name,
		'size'      => $order_info->group_size,
		'font'      => $order_info->group_font,
        'color'     => $group_color
	],

];

if (sha1(md5($hash))) {
	include( __DIR__ . '/includes/persian_txt2pic.php' );
	header( 'Content-Type: image/png' );


	$text_color = imagecolorallocate( $image, 255, 255, 255 );
	$y = $datas[1] == 'main' ? 680 : 780;
	foreach ($mainData as $key => $data) {
		if ($key == 'singer2Info' && empty($data['name']))
			continue;

		$y += 100;
//		$text = persian_log2vis($data['name']);
        $text = $data['name'];
		$ttfBox = imagettfbbox(
			$data['size'],
			0,
			__DIR__ . '/banners/fonts/'.$data['font'].'.ttf',
			$data['name']
		);
		// $x = $key == 'timeInfo' ? imagesx($image)/2.7 : imagesx($image)/2-strlen($data['name'])*((int)$data['size']/10);
		$x = $key == 'timeInfo' ? imagesx($image)/2-strlen($data['name'])*((int)$data['size']/7) : imagesx($image)/2-strlen($data['name'])*((int)$data['size']/10);
		// $x = imagesx($image)/2-strlen($data['name'])*((int)$data['size']/10);
		imagettftext(
			$image,
			$data['size'],
			0,
			$x,
			$y,
			$data['color'],
			__DIR__ . '/banners/fonts/'.$data['font'].'.ttf',
			$text
		);
	}

	imagepng( $image );
	imagedestroy( $image );

}