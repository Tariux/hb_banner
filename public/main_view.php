<?php

global $wpdb;
$cats = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hb_banners_categories', OBJECT);


?>



<div class="row">
	<!--<div style="text-align: center" class="col-md-12">-->
	<!--	<p style="width: 100%;" class="yekan">طرح ها و محصولات</p>-->
	<!--</div>-->
</div>
<div class="row">
    <?php
    foreach ($cats as $cat) {
    ?>
    <div class="col-md-3">
        <a href="<?= get_page_by_path('catinside')->guid . '?_catID='.$cat->id ?>">
			<div class="hb_card">
				<div class="hb_card_image">
					<img src="<?= site_url('wp-content/plugins/hb_banner/admin/images/' . $cat->image) ?>" alt="">
				</div>
				<div class="hb_card_content">
					<div class="hb_card_max_text yekan">
						<?= $cat->title ?>
					</div>
					<div class="hb_card_min_text yekan">
                        <?= $cat->description ?>
					</div>
				</div>
			</div>
        </a>
    </div>
    <?php } ?>
</div>
</div>