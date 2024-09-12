<?php
require_once('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
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

    tep_redirect(tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
    }
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_MOBILE_INCLUDES . 'header.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>
<div id="iphone_content">
<?php 
$headerTitle->write($product_info['products_name']);
echo ((MOBILE_BREADCRUMB_TRAIL == 'true')? '<div id="breadcrumbtrail">' . $breadcrumb->trail('') . '</div>':'');
?>
   <div class="ui-grid-a ui-responsive">
	<div class="ui-block-a">
<?php
  require(DIR_MOBILE_MODULES. "product_header.php");
?>
	</div>
	<div class="ui-block-b">
<div id="ficheProdMid"> 
<?php echo tep_draw_form('product_reviews_write', tep_mobile_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?>
<?php
  if ($messageStack->size('review') > 0) {
?>
        <?php echo str_replace('\n', '<br>', $messageStack->output('review')); ?>
<br/>
<?php
  }
?>
    <?php echo SUB_TITLE_FROM; ?>
    <?php echo tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); ?><br/><br/>
 
    <fieldset data-role="controlgroup" id="custom-fieldset" data-type="horizontal" data-mini="true">
    <legend><?php echo SUB_TITLE_RATING; ?></legend>
        <label for="bad" style="cursor:default"><?php echo strip_tags(TEXT_BAD); ?></label>
    	<?php echo tep_radio_jquery('bad',true,'b',0,'id="bad"'); ?>
        <label for="1">1</label>
    	<?php echo tep_radio_jquery('rating',false,'a',1,'id="1"'); ?>
        <label for="2">2</label>
    	<?php echo tep_radio_jquery('rating',false,'a',2,'id="2"'); ?>
        <label for="3">3</label>
    	<?php echo tep_radio_jquery('rating',false,'a',3,'id="3"'); ?>
        <label for="4">4</label>
    	<?php echo tep_radio_jquery('rating',false,'a',4,'id="4"'); ?>
        <label for="5">5</label>
    	<?php echo tep_radio_jquery('rating',false,'a',5,'id="5"'); ?>
        <label for="good" style="cursor:default"><?php echo strip_tags(TEXT_GOOD); ?></label>
    	<?php echo tep_radio_jquery('good',true,'b',0,'id="good"'); ?>
    </fieldset>
        
<br/>
      <label><?php echo SUB_TITLE_REVIEW; ?></label>

      <?php echo tep_draw_textarea_field('review', 'soft', 30, 5); ?>
      <?php echo strip_tags(TEXT_NO_HTML); ?>

      <div class="bouton">
      	<?php 
	echo tep_button_jquery(IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()), 'b' ,'button', ' data-inline="true" data-icon="back" ');
	echo tep_button_jquery( IMAGE_BUTTON_CONTINUE, '', 'b' , 'submit' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"' );
	?>  
      </div>
 </form>
</div>
</div>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php'); 
?>
