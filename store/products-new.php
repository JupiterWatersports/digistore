<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_NEW));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>

<h1><?php echo HEADING_TITLE; ?></h1>
   

<?php
  $products_new_array = array();
  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?> 
<!--products count-->
<div class="grid_4 smalltext alpha"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></div>
<!--page count/links-->
<div class="grid_4 right-align smalltext omega"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
<div class="clear"></div>        
<?php
  }
?>
<div class="clear"></div>
      
<?php
  if ($products_new_split->number_of_rows > 0) {
  
    $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($products_new = tep_db_fetch_array($products_new_query)) {
      if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
      }
?>
<!--new products open container--> 
<div class="pl products-new">
<!--<div class="pl products-new">-->
	<!--image-->
	<div class="pl-image">
	<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $products_new['products_image'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?>
	</div>
	<!--name-->
	<div class="pl-name">	
	<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . $products_new['products_name'] . '</a>'; ?>
	</div>
	<!--date added-->
	<div class="pl-dateadded">
	<?php // echo TEXT_DATE_ADDED . ' ' . tep_date_short($products_new['products_date_added']);?>
	<?php echo '<span class="smalltext">Added ' . tep_date_short($products_new['products_date_added']).'</span>';?>
	</div>
	<!--manufacturer-->
	<div class="pl-manufacturer">
	<?php // echo TEXT_MANUFACTURER . ' ' . $products_new['manufacturers_name'];?>
	<?php echo 'from ' . $products_new['manufacturers_name'];?>
	</div>
	<!--price-->
	<div class="pl-price">
	 <?php echo TEXT_PRICE . ' ' . $products_price; ?>	
	</div>
	<!--button--> 
	<div class="pl-button">	
	<?php echo '<form name="buy_now_' . $products_new['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '"><input type="hidden" name="products_id" value="' . $products_new['products_id'] . '" />' . tep_image_add('', '') .'</form>'; ?>
 	</div>
 	
<!--close container--> 	
 </div> 
 
<?php
    }
    
//if no products
  } else {
?>
	<p><?php echo TEXT_NO_NEW_PRODUCTS; ?></p>
<?php
  }

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<div class="clear"></div>

<!--products count-->
<div class="grid_4 smalltext alpha"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></div>
<!--page count/links-->
<div class="grid_4 right-align smalltext omega"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>

<div class="clear"></div> 
         
<?php
  }

require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
