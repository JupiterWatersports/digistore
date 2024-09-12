<?php
require_once('includes/application_top.php');
require(DIR_MOBILE_INCLUDES . 'header.php');

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

  if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
    $review_check = tep_db_fetch_array($review_check_query);

    if ($review_check['total'] < 1) {
      tep_redirect(tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }

  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'");

  $review_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
  $review = tep_db_fetch_array($review_query);

  if ($new_price = tep_get_products_special_price($review['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
  }

  if (tep_not_null($review['products_model'])) {
    $products_name = $review['products_name'] . '<br><span class="smallText">[' . $review['products_model'] . ']</span>';
  } else {
    $products_name = $review['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
  $headerTitle->write($product_info['products_name']);  
?>
<div id="iphone_content">
   <div class="ui-grid-a ui-responsive">
	<div class="ui-block-a">
	<?php
	require(DIR_MOBILE_MODULES. "product_header.php");
	?>
	</div>
	<div class="ui-block-b">
<div id="ficheProdMid"> 
    <div class="main">
      <?php echo '<b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($review['customers_name'])) . '</b><br>'; ?>
    </div>
    <div class="smallText">
      <?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review['date_added'])) . '<br><br>'; ?>
    </div>
    <div class="review">
      <?php echo tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])), 60, '-<br>') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $review['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])) . '</i>'; ?>
    </div>

    <div class="bouton">
	<?php 
	echo tep_button_jquery(IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))),'b','button','data-icon="back" data-inline="true"');
	echo tep_button_jquery( IMAGE_BUTTON_WRITE_REVIEW , tep_mobile_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()), 'b' , 'button' , 'data-icon="edit" data-iconpos="right" data-inline="true"' );
	?>  
      </div>
</div>
</div>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php'); 
?>
