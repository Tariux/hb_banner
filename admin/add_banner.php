


<div class="wrap">
    <h1 class="wp-heading-inline">افزودن بنر جدید</h1>
    
    
    <table class="form-table" role="presentation">
    	<tbody>
    	    
    	    <tr class="form-field form-required">
    		    <th scope="row"><label for="title">عنوان</label></th>
    		    <td><input style="width: 25em;" name="title" type="text" id="title" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
    	    </tr>
    	    
    	    <tr class="form-field form-required">
    		    <th scope="row"><label for="price">قیمت</label></th>
    		    <td><input style="width: 25em;" name="price" type="text" id="price" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
    	    </tr>
    	    
    	    <tr class="form-field form-required">
    		    <th scope="row"><label for="cats">دسته بندی</label></th>
    		    <td>
    		        <select style="width: 21rem;" name="category" id="category">
                        <option value="">انتخاب نشده</option>
						<?php
						foreach ($categories as $cts) {
							echo '<option value="'.$cts['value'].'">'.$cts['title'].'</option>';
						}
						?>
                    </select>
    		    </td>
    	    </tr>
    	    
    	    <tr class="form-field form-required">
    		    <th scope="row"><label for="discount">تخفیف</label></th>
    		    <td><input style="width: 25em;" name="discount" type="number" id="discount" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
    	    </tr>
    	    
    	    
    	     <tr class="form-field form-required">
    		    <th scope="row"><label for="description">توضیحات</label></th>
    		    <td><textarea name="description" id="description" rows="10"></textarea></td>
    	    </tr>
    	    
    	    <tr class="form-field form-required">
    		    <th scope="row"></th>
    		    <td>
    		        <?= submit_button('ذخیره', 'primary', 'submit', true, []) ?>
    		    </td>
    	    </tr>
        </tbody>
    </table>
</div>