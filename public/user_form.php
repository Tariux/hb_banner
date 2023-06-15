	<?php
	clearstatcache(true);
	global $wpdb;
	$catID = explode('catID=', $_SERVER['QUERY_STRING'])[1];
	$banner_table = $wpdb->prefix . 'hb_banners';
	$image_table = $wpdb->prefix . 'hb_banners_image';
	$font_table = $wpdb->prefix . 'hb_banners_fonts';
	$banner_info = $wpdb->get_results('SELECT `banner`.`id`, `banner`.`title`, `banner`.`description`, `banner`.`price`, `image`.`image` FROM `' . $banner_table . '` AS `banner` JOIN `' . $image_table . '` AS `image` ON `banner`.`id` = `image`.`banner_id`  WHERE `image`.`type` = "writed" AND `banner`.`cat_id` = ' . $catID, OBJECT);
	$fonts = $wpdb->get_results('SELECT * FROM ' . $font_table, OBJECT);
	$base_url = 'https://fater.local/';
	$images = [];
	foreach ($banner_info as $banner) {
		array_push(
			$images,
			$wpdb->get_results('SELECT * FROM ' . $image_table . ' WHERE banner_id = ' . $banner->id, OBJECT)
		);
		// $images = $wpdb->get_results('SELECT * FROM ' . $image_table . ' WHERE banner_id = ' . $banner->id, OBJECT);
	}
	?>

	<div class="row">

		<?php

		$i = 0;
		foreach ($banner_info as $info) {
			$order_result = $wpdb->get_results('SELECT id FROM ' . $wpdb->prefix . 'hb_banners_users_order' . ' WHERE userid = ' . get_current_user_id() . ' AND banner_id = ' . $info->id);

		?>
			<div class="col-md-3">
				<div class="card shadow_card hb_card">
					<img class="card-img-top" src="<?= HB_BANNER_LOCATION_URL . '/banners/' . $info->image ?>" alt="Card image cap">
					<div class="card-body">
						<span class="card-title yekan font14 bold"><?= $info->title; ?></span><br>
						<span class="card-text yekan font14"><?= $info->description; ?></span><br>
						<div class="row">
							<div class="col-md-7">
								<span class="card-text yekan font12">قیمت: <?= $info->price ?> تومان</span>
							</div>
							<?php
							if (empty($order_result)) {
							?>
								<div class="col-md-5">
									<button data-toggle="modal" data-target="#myModal_<?= $i ?>" class="btn btn-primary font12 yekan">انتخاب</button>
								</div>
							<?php } ?>
						</div>
						<?php
						if (!empty($order_result)) {
						?>
							<div class="row">
								<?= render_link($info->id)['main'] ?>
								<?= render_link($info->id)['story'] ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<!-- The Modal -->
			<?php
			if (is_user_logged_in()) {
			?>



				<div class="modal" id="myModal_<?= $i ?>">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<p style="width: 100%;" class="modal-title yekan p10">ویرایش اطلاعات</p>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 edit_panel" id="edit_panel" style="text-align: right; ">

										

									</div>
									<div class="col-md-8">
										<canvas style="border: solid 1px #000;" id="canvas_<?= $i ?>"></canvas>
										<canvas style="display: none;" id="renderedCanvas_<?= $i ?>"></canvas>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="#" id="accept_<?= $i ?>" class="btn btn-primary yekan accept">تایید بنر</a>
								<a style="display: none;" href="#" id="dlStory_<?= $i ?>" class="btn btn-info yekan dlStory">دانلود استوری</a>
								<a style="display: none;" href="#" id="dlPost_<?= $i ?>" class="btn btn-info yekan dlPost">دانلود پست</a>
							</div>
						</div>
					</div>
				</div>


			<?php
			} else {
			?>
				<div class="modal fade hbmodal" id="myModal_<?= $i ?>">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">

							<!-- Modal Header -->
							<div class="modal-header">
								<p style="width: 100%;" class="modal-title yekan p10">لطفا وارد شوید</p>
							</div>

							<!-- Modal body -->
							<div class="modal-body">
								<div class="row">
									<p class="yekan" style="width: 100%; text-align: center;">ابتدا باید وارد حساب کاربری خود شوید</p>
								</div>
							</div>

							<!-- Modal footer -->
							<div class="modal-footer">
								<button type="button" class="btn btn-dafualt font12 yekan" data-dismiss="modal">بستن</button>
							</div>
						</div>
					</div>
				</div>
		<?php
			}
			$i++;
		}
		?>

	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/521/fabric.min.js" integrity="sha512-nPzvcIhv7AtvjpNcnbr86eT6zGtiudLiLyVssCWLmvQHgR95VvkLX8mMpqNKWs1TG3Hnf+tvHpnGmpPS3yJIgw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		let imageURL = '';
		let canvas = '';
		let i = 0;
		let field1 = '';
		let field2 = '';
		let field3 = '';
		let field4 = '';
		let field5 = '';
		let field6 = '';
		let field7 = '';
		let sObject = '';



		$(document).ready(function() {
			$('button[data-toggle="modal"]').on('click', function() {
				imageURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[0][0]->image ?>';

				let index = $(this).attr('data-target').split('#myModal_')[1]
				switch (index) {
					case '0':
						canvas = new fabric.Canvas('canvas_0', {
							width: 500,
							height: 700,
						});
						imageURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[0][2]->image ?>';
						break;
					case '1':
						canvas = new fabric.Canvas('canvas_1', {
							width: 500,
							height: 700,
						});
						imageURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>';
						break;
					case '2':
						canvas = new fabric.Canvas('canvas_2', {
							width: 500,
							height: 700,
						});
						imageURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>';
						break;
				}
				fabric.Image.fromURL(imageURL, function(img, isError) {
					img.set({
						scaleX: 500 / img.width,
						scaleY: 700 / img.height,
						top: 0,
						left: 0,
						originX: 'left',
						originY: 'top'
					});
					canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
				});

				field_1 = add_field(canvas , 'field_text_0' ,125,200);
				field_2 = add_field(canvas , 'field_text_1' ,125,250);
				field_3 = add_field(canvas , 'field_text_2' ,125,300);
				field_4 = add_field(canvas , 'field_text_3' ,125,350);
				field_5 = add_field(canvas , 'field_text_4' ,125,400);
				field_6 = add_field(canvas , 'field_text_5' ,125,450);
				field_7 = add_field(canvas , 'field_text_6' ,125,500);


				canvas.renderAll();

				canvas.on({
					'selection:created': objSelect,
					'selection:cleared': objClear,
					'selection:updated': objUpdate,
				});

			});

		});


		function add_field(
			canvas ,
			field_name , 
			left,
			top,
			default_text = "" , 
			width = 250,
			textAlign = 'center',
			fontSize = 20

			) {



			$(document).ready(function() {





			let wrapper = 
			`
			<div id="`+field_name+`" style="border: 1px solid #000;padding: 10px;display:block;">
			<div class="from-group">
				<label for="objText">متن نوشته</label>
				<input type="text" name="objText" class="form-control objText">
			</div>
			<div class="from-group">
				<label for="objSize">اندازه نوشته</label>
				<input type="number" name="objSize" class="form-control objSize" min="0" max="200">
			</div>
			<div class="from-group">
				<label for="objColor">رنگ نوشته</label>
				<input type="color" name="objColor" class="form-control objColor">
			</div>

			<div class="from-group">
											<label for="objFont">فونت نوشته</label>
											<select name="objFont" class="form-control objFont">
												<?php
												echo '<option value="">انتخاب کنید</option>';
												$cssFiles = scandir(dirname(__FILE__) . '/../banners/fonts');
												foreach ($cssFiles as $value) {
													if ($value == '.' || $value == '..')
														continue;
													$font = explode('.', $value)[0];
													echo '<option value="' . $font . '">' . $font . '</option>';
												}
												?>
											</select>
			</div>

			</div>`;

			$(".edit_panel").append( wrapper );
			

			var field = new fabric.Textbox(default_text, {
				width: width,
				left: left,
				top: top,
				textAlign: textAlign,
				fontSize: fontSize,
				selectable : false,

				});
			canvas.add(field);

		
			let field_num = field_name.replace(/\D/g, "");

			$(`#${field_name} .objText`).on('keyup', function() {

				canvas.getObjects()[field_num].text = this.value;

				canvas.renderAll();

			});


			$(`#${field_name} .objFont`).on('change', function() {

				canvas.getObjects()[field_num].fontSize = canvas.getObjects()[field_num].fontSize - 1;
				canvas.getObjects()[field_num].fontFamily = this.value;
				canvas.getObjects()[field_num].fontSize = canvas.getObjects()[field_num].fontSize - 1;

				canvas.renderAll();

			});


			$(`#${field_name} .objSize`).on('change', function() {


				if (this.value > 0 && this.value < 200) {
					canvas.getObjects()[field_num].fontSize = this.value;
					canvas.renderAll();

				}

			});


			$(`#${field_name} .objColor`).on('change', function() {

				canvas.getObjects()[field_num].fontSize = canvas.getObjects()[field_num].fontSize - 1;
				canvas.getObjects()[field_num].fill = this.value;
				canvas.getObjects()[field_num].fontSize = canvas.getObjects()[field_num].fontSize - 1;

				canvas.renderAll();

			});





		});






			return true;
		}



		$(document).on('click', '.dlStory', function() {
			downloadStory($(this).attr('id').split('dlStory_')[1])
		});

		$(document).on('click', '.dlPost', function() {
			downloadPost($(this).attr('id').split('dlPost_')[1])
		});

		$(document).on('click', '.accept', function() {
			let accept_index = $(this).attr('id').split('accept_')[1]
			$("#dlStory_" + accept_index).trigger('click')
			$("#dlPost_" + accept_index).trigger('click')
			$("#dlStory_" + accept_index).css('display', 'block')
			$("#dlPost_" + accept_index).css('display', 'block')
		});



		function sleep(ms) {
			return new Promise(resolve => setTimeout(resolve, ms));
		}

		async function downloadPost(index) {
			let width = 1080;
			let height = 1528;
			let imgURL = '';
			switch (index) {
				case '0':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[0][2]->image ?>'
					break;
				case '1':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>'
					break;
				case '2':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>'
					break;
			}
			const renderedCanvas = new fabric.Canvas('RenderedCanvas', {
				width: width,
				height: height
			});
			await sleep(500);
			setBackground(renderedCanvas, imgURL, width, height);
			rescaleObjects(renderedCanvas, 'dlPost');
			await sleep(500)
			$(document).find('#dlPost_' + index).attr('href', renderedCanvas.toDataURL({
				width: width,
				height: height,
				format: 'png',
				quality: 0.8
			}));
			$(document).find('#dlPost_' + index).attr('download', 'post.png');
		}

		async function downloadStory(index) {
			let width = 1080;
			let height = 1920;
			let imgURL = '';
			switch (index) {
				case '0':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[0][2]->image ?>'
					break;
				case '1':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>'
					break;
				case '2':
					//ToDo
					imgURL = '<?= HB_BANNER_LOCATION_URL . "/banners/" . $images[1][2]->image ?>'
					break;
			}
			const renderedCanvas = new fabric.Canvas('RenderedCanvas', {
				width: width,
				height: height
			});
			await sleep(500);
			setBackground(renderedCanvas, imgURL, width, height)
			rescaleObjects(renderedCanvas, 'dlStory');
			await sleep(500)
			$(document).find('#dlStory_' + index).attr('href', renderedCanvas.toDataURL({
				width: width,
				height: height,
				format: 'png',
				quality: 0.8
			}));
			$(document).find('#dlStory_' + index).attr('download', 'story.png');
		}

		function setBackground(renderedCanvas, imgURL, width, height) {
			fabric.Image.fromURL(imgURL, function(image, isError) {
				image.set({
					scaleX: width / image.width,
					scaleY: height / image.height,
					top: 0,
					left: 0,
					originX: 'left',
					originY: 'top'
				});
				renderedCanvas.setBackgroundImage(image, renderedCanvas.renderAll.bind(renderedCanvas));
			});
		}

		function rescaleObjects(renderedCanvas, btn) {
			let text = '';
			var objs = canvas.getObjects().map(function(o) {
				return o.set('active', true);
			});
			let scale = 1;
			let leftScale = 0;
			if (btn == 'dlPost') {
				scale = 2.3;
				leftScale = 2;
			}
			if (btn == 'dlStory') {
				scale = 2.7;
				leftScale = 1.80;
			}
			for (var i = 0; i < objs.length; i++) {
				text = new fabric.Textbox(objs[i]['text'], {
					width: objs[i]['width'] * scale,
					left: objs[i]['left'] * leftScale,
					top: objs[i]['top'] * scale,
					textAlign: 'center',
					fontSize: objs[i]['fontSize'] * scale
				});
				text.set({
					fill: objs[i]['fill'],
					fontFamily: objs[i]['fontFamily']
				})
				renderedCanvas.add(text);
				renderedCanvas.renderAll();
			}
		}

		function objSelect(e) {
			sObject = e.selected[0];
		}

		function objClear(e) {
			sObject = '';
		}

		function objUpdate(e) {
			sObject = e.selected[0];
			document.getElementById('objFont').value = '';
			document.getElementById('objSize').value = '';
			document.getElementById('objColor').value = '#000';
		}
	</script>