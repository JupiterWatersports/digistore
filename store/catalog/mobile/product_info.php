<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
require(DIR_MOBILE_INCLUDES . 'header.php');

$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_check = tep_db_fetch_array($product_check_query);
?>
<div id="iphone_content">
	<?php
	echo tep_draw_form('cart_quantity', tep_mobile_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
	<?php
	if ($product_check['total'] < 1) {
		$headerTitle->write(TEXT_PRODUCT_NOT_FOUND);  ?>
		<div id="cms">
			<?php echo TEXT_PRODUCT_NOT_FOUND; ?>
			<div id="bouton">
				<?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_mobile_link(FILENAME_DEFAULT), 'primary'); ?>  
			</div>
		</div>
		<?php
	} else {
		$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
		$product_info = tep_db_fetch_array($product_info_query);	 
		if (tep_not_null($product_info['products_model'])) {
			$products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
		} else {
			$products_name = $product_info['products_name'];
		}
		if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
			$products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
		} else {
			$products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
		}	
		$headerTitle->write($product_info['products_name']);
		?>
		<div class="ui-grid-a ui-responsive">
			<div class="ui-block-a">
			<div id="ficheProdTop">
			<h1><?php echo $products_name; ?></h1>
			<div class="visuel">
				<?php
				if (tep_not_null($product_info['products_image'])) {
					echo '<a class="fancyLink" data-fancybox-group="group" href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'images/' . $product_info['products_image'] . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), MOBILE_PRODUCT_IMAGE_WIDTH, MOBILE_PRODUCT_IMAGE_HEIGHT, 'style="max-width:100%; max-height:100%"') . '</a>';
				}
			?>

<script>
$(document).ready(function() {
$(".fancyLink").fancybox({
openEffect : 'fade',
prevEffect : 'fade',
nextEffect : 'fade'
});
}); // ready
/* ]]> */
</script>

				</div>
				<div class="prodPrice">
					<?php echo $products_price; ?><br />
				</div>
				<?php	
				$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
				$products_attributes = tep_db_fetch_array($products_attributes_query);
				if ($products_attributes['total'] > 0) {			
					$products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$HTTP_GET_VARS['products_id']) ? $HTTP_GET_VARS['products_id'] : (int)$HTTP_GET_VARS['products_id']); 
					?>
					<div class="options">
						<strong><?php echo TEXT_PRODUCT_OPTIONS; ?></strong>	
						<br />
						<?php
						$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
						while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
							$products_options_array = array();
							$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
							while ($products_options = tep_db_fetch_array($products_options_query)) {
								$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
								if ($products_options['options_values_price'] != '0') {
									$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
								}
							}
							if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
								$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
							} else {
								$selected_attribute = false;
							}
							?>
					<div class="optName">
						<div data-role="fieldcontain">
							<?php echo '<span style="position:relative; top:-14px">' . $products_options_name['products_options_name'] . ':   </span>' . tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute, 'data-theme="a" '); ?>
						</div>
					</div>
					<?php
				}
				?></div>
				<div class="bouton">
				<?php
			} else {
				?>
				<div class="bouton">
				<?php
			}
					 echo tep_draw_hidden_field('products_id', $product_info['products_id']); 
					 echo '<input value="'.IMAGE_BUTTON_IN_CART.'" type="submit" data-inline="true" name="cart" data-role="submit"  data-icon="plus" data-theme="b">';?>
				</div>
			</div>
		</div>
		<div class="ui-block-b">
			<div id="ficheProdMid">
				<?php
				tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
				echo '<div class="description">' . stripslashes($product_info['products_description']) . '</div>'; ?>
				<?php

				if (tep_not_null($product_info['products_url'])) {
					echo '<div class="description">' . strip_tags(TEXT_MORE_INFORMATION) . tep_button_jquery(IMAGE_BUTTON_WEBPAGE, tep_mobile_link(FILENAME_REDIRECT, 'action=url&goto=' . $product_info['products_url'], 'NONSSL', true, false), 'b', 'button', 'data-inline="true" data-ajax="false" data-icon="info" data-mini="true"') . '</div>';
				}

				if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
					echo '<div class="description">' . sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])) . '</div>';
				} else {
					echo '<div class="description">' . sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])) . '</div>';
				}
				$reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
				$reviews = tep_db_fetch_array($reviews_query);
				?>
			</div>
		</div>
	</div>
				<div class="cms">
					<br>
					<div class="bouton">
						<?php echo  tep_button_jquery(IMAGE_BUTTON_BACK,'#','b','button','data-rel="back" data-inline="true" data-icon="back" ') . tep_button_jquery( IMAGE_BUTTON_REVIEWS . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : '') , tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) , 'b' , 'button' , 'data-icon="edit" data-iconpos="right" data-inline="true" ' ); ?>
					</div>
				</div>
			<?php

			if ((USE_CACHE == 'true') && empty($SID)) {
				if (tep_not_null(tep_cache_mobile_also_purchased(3600))) {
					echo tep_cache_mobile_also_purchased(3600);
				}
			} else {
				include(DIR_MOBILE_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
			}
	}
?>
</form>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
