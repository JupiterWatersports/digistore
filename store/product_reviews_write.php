<?php

/*

  $Id: product_reviews_write.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License

*/
  require('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_image_med, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }
  $customer_query = tep_db_query("select customers_firstname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $customer = tep_db_fetch_array($customer_query);
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
    $review = tep_db_prepare_input($HTTP_POST_VARS['review']);
    $error = false;
    if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
      $error = true;
      $messageStack->add('review', JS_REVIEW_TEXT);
    }
    if (($rating < 1) || ($rating > 5)) {
      $error = true;
      $messageStack->add('review', JS_REVIEW_RATING);

    }
    if ($error == false) {

      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();
      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));}}
  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '</span>';
  } else {
    $products_name = $product_info['products_name'];}
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

?>

<!DOCTYPE html>

<html<?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title>Write A Review</title>
<?php
}
/*** End Header Tags SEO ***/
?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<script language="javascript"><!--

function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";
  var review = document.product_reviews_write.review.value;
  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }
  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
    error = 1;
  }
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
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
<div class="center490">
<?php echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?>
<div id="product-review-info-rating">
<h1><?php echo $product_info['products_name']; ?></h1>
 <div class="writereview-image">                 
<?php
  if (tep_not_null($product_info['products_image_med'])) {
?>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']) . '" target="_blank" rel="prettyPhoto[gallery1]">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') .'</a>'; ?>
<?php
  }
 ?>
</div>

<div id="rating"> <tr>
        <label style="float:left;">Rating: </label>
        <td class="fieldValue">
          <?php echo ' <span class="star-rating">
          ' . tep_draw_radio_field('rating', '1') . '<i></i>
          ' . tep_draw_radio_field('rating', '2') . '<i></i>
          ' . tep_draw_radio_field('rating', '3') . '<i></i>
          ' . tep_draw_radio_field('rating', '4') . '<i></i>
          ' . tep_draw_radio_field('rating', '5') . '<i></i></span><div class="choice"></div> '; ?>
          </td>
      </tr></div>
      </div>
<?php

  if ($messageStack->size('review') > 0) {

?>
      <?php echo $messageStack->output('review'); ?>
<?php }   ?>
<!--name and review text area-->
<div class="grid_6 alpha" style="">
<textarea placeholder="Write your review here..." name="review" cols="60" rows="15" wrap="soft" style="font-size:14px; padding:10px;"></textarea>
</div>

<div class="clear spacer-tall"></div>
 
 <!--buttons-->  

<button class="button-blue-small required-continue">Continue</button>

<script>
$(document).ready(function() {
$(':radio').change(
  function(){
    $('.choice').text( this.value + ' stars' );
  }
)
});
</script>
</form>

<?php echo '<a href="' . tep_href_link('product_info.php?products_id='. $product_info['products_id']) .'">'.'<button class="button-blue-small required-back">Back</button>'.'</a>'; ?>


<div class="divider"></div>
  <div class="buttons-bottom">  </div>                  

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
