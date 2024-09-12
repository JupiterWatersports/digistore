<?php
/* 
$Id: xsell_products.php, v1  2002/09/11

osCommerce, Open Source E-Commerce Solutions 
<http://www.oscommerce.com> 

Copyright (c) 2002 osCommerce 

Released under the GNU General Public License 
*/ 

/* if product given */

if ((USE_CACHE == 'true') && empty($SID)) {
	// include currencies class and create an instance
	require_once(DIR_WS_CLASSES . 'currencies.php');
	$xsell_currencies = new currencies();
}


if ($HTTP_GET_VARS['products_id']) { 

/* obtain the xsell product */

$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and  xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and  p.products_status = '1' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED); 

$num_products_xsell = tep_db_num_rows($xsell_query); 

/* if we equal or exceed the minimal amount of products */

if ($num_products_xsell >= MIN_DISPLAY_ALSO_PURCHASED) { 

      /* put them in the box */
?>

<div class="clear spacer-tall"></div>
<h3 style="margin-bottom:10px; text-transform:uppercase; text-align:center;"><?php echo TEXT_XSELL_PRODUCTS ?></h3><hr />
<div class="xsell-products-container row">
<div class="xsell-carousel">
<?php   
        $info_box_contents = array();
		 new plcontentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($xsell = tep_db_fetch_array($xsell_query)) {
       // BEGIN  Discount 
     $specialprice = true;
     // END Discount 
	   if ($new_price = tep_get_products_special_price($xsell['products_id'])) {
        $xsell_products_price = '<s>' . $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
       } else {
        $xsell_products_price = $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id']));
       }
         $xsell['products_name'] = tep_get_products_name($xsell['products_id']);
         $info_box_contents[$row][$col] = array('align' => 'center',
                                                'params' => 'class="xsell-products"',
                                                'text' => '<div class="xsell-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><div class="xtra-products-info"><a href="' . tep_href_link(        										FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a>'.'<span class="alsop-product-price">'. $xsell_products_price.'</span></div>');
        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      /* if we have not enough products to fill the box */

      if ($num_products_xsell < MAX_DISPLAY_ALSO_PURCHASED) {

        /* add some random products from the same category to fill the box */

        $mtm= rand();

        $xsell_cat_query = tep_db_query("select categories_id
                                   from " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                   where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
        $xsell_cat_array = tep_db_fetch_array($xsell_cat_query);
        $xsell_category = $xsell_cat_array['categories_id'];
        $new_limit = MAX_DISPLAY_ALSO_PURCHASED - $num_products_xsell;
        $xsell_prod_query = tep_db_query("select distinct p.products_id, 
                                             p.products_image, 
                                             pd.products_name, p.products_price, p.products_tax_class_id 
                             from " . TABLE_PRODUCTS . " p,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " pc,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                             where pc.categories_id = '" . $xsell_category . "' and
                                   p.products_id != '" . $HTTP_GET_VARS['products_id'] . "' and 
                                   pc.products_id = p.products_id and 
                                   p.products_id = pd.products_id and 
                                   pd.language_id = '" . $languages_id . "' and 
                                   p.products_status = '1' 
                             order by rand($mtm) desc 
                             limit " . $new_limit);

       while ($xsell = tep_db_fetch_array($xsell_prod_query)) {
       // BEGIN  Discount 
     $specialprice = true;
     // END Discount 
	   if ($new_price = tep_get_products_special_price($xsell['products_id'])) {
        $xsell_products_price = '<s>' . $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
       } else {
        $xsell_products_price = $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id']));
       }
         $xsell['products_name'] = tep_get_products_name($xsell['products_id']);
         $info_box_contents[$row][$col] = array('align' => 'center',
                                                'params' => 'class="xsell-products"',
                                                'text' => '<div class="xsell-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><div class="xtra-products-info"><a href="' . tep_href_link(        										FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a>'.'<span class="alsop-product-price">'. $xsell_products_price.'</span></div>');
         $col ++;
         if ($col > 2) {
           $col = 0;
           $row ++;
         }
       }
      }
     new plcontentBox($info_box_contents);
    }
else {

      /* there are no xsell products registered at all for this product */
	  ?>

<div class="clear spacer-tall"></div>
<h3 style="margin-bottom:10px; text-transform:uppercase; text-align:center;"><?php echo TEXT_XSELL_PRODUCTS ?></h3><hr />
<div class="xsell-products-container row">
<div class="xsell-carousel">
<?php  $info_box_contents = array();
       new plcontentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();

      /* fill the box with all random products from the same category */

        $mtm= rand();
        $xsell_cat_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . "  where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");

        $xsell_cat_array = tep_db_fetch_array($xsell_cat_query);
        $xsell_category = $xsell_cat_array['categories_id'];
        $new_limit = MAX_DISPLAY_ALSO_PURCHASED - $num_products_xsell;
        $xsell_prod_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc, " . TABLE_PRODUCTS_DESCRIPTION . " pd  where pc.categories_id = '" . $xsell_category . "' and  pc.products_id = p.products_id and  p.products_id != '" . $HTTP_GET_VARS['products_id'] . "' and  p.products_id = pd.products_id and  pd.language_id = '" . $languages_id . "' and  p.products_status = '1'  order by rand($mtm) desc limit " . MAX_DISPLAY_ALSO_PURCHASED);

       while ($xsell = tep_db_fetch_array($xsell_prod_query)) {
	 // BEGIN  Discount 
     $specialprice = true;
     // END Discount 
	   if ($new_price = tep_get_products_special_price($xsell['products_id'])) {
        $xsell_products_price = '<s>' . $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
       } else {
        $xsell_products_price = $xsell_currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id']));
       }
         $xsell['products_name'] = tep_get_products_name($xsell['products_id']);
         $info_box_contents[$row][$col] = array('align' => 'center',
                                                'params' => 'class="xsell-products"',
                                                'text' => '<div class="xsell-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><div class="xtra-products-info"><a href="' . tep_href_link(        										FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a>'.'<span class="alsop-product-price">'. $xsell_products_price.'</span></div>');
         $col ++;
         if ($col > 2) {
           $col = 0;
           $row ++;
         }
       }
      new plcontentBox($info_box_contents);
}
}
?>
</div>
<button id="xsell-left" class="leftArrow" value="left"></button>
<button id="xsell-right" class="rightArrow" value="right"></button>
</div>