<?php

/*

  $Id: product_reviews_info.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License

*/
  require('includes/application_top.php');
  if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
    $review_check = tep_db_fetch_array($review_check_query);
    if ($review_check['total'] < 1) {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }
  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'");
  $review_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_image_med, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
  $review = tep_db_fetch_array($review_query);

  if ($new_price = tep_get_products_special_price($review['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
  }
  
  if (tep_not_null($review['products_model'])) {
    $products_name = $review['products_name'];
    $products_model = $review['products_model'];
  } else {

    $products_name = $review['products_name'];

  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="robots" content="noindex">
<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
  <title><?php echo TITLE; ?></title>
<?php
}
/*** End Header Tags SEO ***/
?>

<meta name="Keywords" content="<?php echo $keywordtag; ?>" />

<meta name="Description" content="<?php echo $description; ?>" />

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto({
				animationSpeed: 'normal', 
				padding: 30, 
				opacity: 0.5, 
				showTitle: true, 
				allowresize: true, 
				counter_separator_label: '/', 
				theme: 'light_rounded', 
				hideflash: false, 
				wmode: 'opaque',
				autoplay: true,
				modal: false, 
				changepicturecallback: function(){}, 
				callback: function(){}
			});
		});
	</script>
<?php require(DIR_WS_INCLUDES . 'template-top2.php'); ?>
 <div class="clear"></div>
<div id="review-info-container">
<div id="review-productinfo">
 <h1><?php echo $review['products_name']; ?></h1>
 <div class="review-productimage">
 <?php
  	if (tep_not_null($review['products_image_med'])) {
 echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $review['products_image_med']) . '" target="_blank" rel="prettyPhoto[gallery1]">' . tep_image(DIR_WS_IMAGES . $review['products_image'], $review['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '</a>'; 
	  }
?></div>
 <div class="grid_4 alpha"><span class="productprice"><?php echo $products_price; ?></span></div>
 <div class="review-product-rating"><label style="float:left;">Rating:</label><?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $review['reviews_rating'] . '.gif'). '<p style="float:left;">'. sprintf(TEXT_OF_5_STARS, $review['reviews_rating']).'</p>'; ?></div>
 </div>
 
 <div class="clear spacer-tall"></div>
 
 <!--reviews author, text--> 
 <div class="grid_6 alpha">    
 		<?php echo sprintf(TEXT_REVIEW_BY.' ', tep_output_string_protected($review['customers_name'])); ?>
 	<p><?php echo tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])), 60, '-<br />'); ?></p>
</div> 
 <!--javascript popup-->
<div class="grid_2 alpha right-align">

</div>

 <div class="clear"></div>
 
  <!--date added-->
<div class="grid_8 alpha">
 
	<?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review['date_added'])); ?>
</div>
<div class="clear spacer-tall"></div>
 <!--buttons-->
<div class="grid_4 alpha" style="width:120px; margin-top:10px;">
  	<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))) . '">'.'<button class="button-blue-small">Back</button>'.'</a>'; ?>
</div>
<div class="alpha right-align" style="float:left; margin-top:10px;">
<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '">'.'<button class="button-blue-small">Write Review</button>'.'</a>'; ?>
</div>
 
<div class="clear"></div>  

<span><?php echo tep_draw_separator('pixel_trans.gif', SMALL_IMAGE_WIDTH + 10, '1'); ?></span>
 

<div class="clear"></div>     
</div>          
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
