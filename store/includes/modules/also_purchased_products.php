<?php
/*
  $Id: also_purchased_products.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if ((USE_CACHE == 'true') && empty($SID)) {
	// include currencies class and create an instance
	require_once(DIR_WS_CLASSES . 'currencies.php');
	$also_currencies = new currencies();
}

  if (isset($HTTP_GET_VARS['products_id'])) {
    $also_purchased_query = tep_db_query("select p.products_id, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($also_purchased_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products v2-->
<div class="clear spacer-tall"></div>
<h3 style="margin-bottom:10px; text-transform:uppercase; text-align:center;"><?php echo TEXT_ALSO_PURCHASED_PRODUCTS ?></h3><hr />
<div class="also-purchased-products-container">
<div class="also-purchased-carousel">
<?php

      $info_box_contents = array();
      new plcontentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($also_purchased = tep_db_fetch_array($also_purchased_query)) {
	// BEGIN  Discount 
     $specialprice = true;
     // END Discount 
	   if ($new_price = tep_get_products_special_price($also_purchased['products_id'])) {
        $also_purchased_products_price = '<s>' . $also_currencies->display_price($also_purchased['products_price'], tep_get_tax_rate($also_purchased['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($also_purchased['products_tax_class_id'])) . '</span>';
       } else {
        $also_purchased_products_price = $also_currencies->display_price($also_purchased['products_price'], tep_get_tax_rate($also_purchased['products_tax_class_id']));
       }

       $also_purchased_box_contents .= '<span class="alsop-product-price">' . $also_purchased_products_price . '</span></div>';
        $also_purchased['products_name'] = tep_get_products_name($also_purchased['products_id']);
         $info_box_contents[$row][$col] = array('params' => 'class="also-purchased-products"',
                                               'text' => '<div><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $also_purchased['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $also_purchased['products_image'], $also_purchased['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><div class="xtra-products-info"><a href="' . tep_href_link(       											FILENAME_PRODUCT_INFO, 'products_id=' . $also_purchased['products_id']) . '">' . $also_purchased['products_name'] . '</a>'.'<span class="alsop-product-price">'. $also_purchased_products_price.'</span></div>');

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      new plcontentBox($info_box_contents);
?>
</div>
<button id="also-left" class="leftArrow" value="left"></button>
<button id="also-right" class="rightArrow" value="right"></button>
</div>
<div class="clear spacer-tall"></div>
<!--end also_purchased_products-->



<?php
    }
  }
?>
