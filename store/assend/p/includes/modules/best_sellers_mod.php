<?php
/*
  Id: best_sellers.php,v 1.0 2002/11/22

  Written by Tony Alanis tmalanis@swbell.net

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- best_sellers //-->
<?php


  if ($cPath) {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_image, pd.products_description, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_ALSO_PURCHASED);
	//revised to take into account special prices- original code below
	//$best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_image, p.products_tax_class_id, p.products_price, p.products_ordered from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and (c.categories_id = '" . $current_category_id . "' OR c.parent_id = '" . $current_category_id . "') order by p.products_ordered DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_image, pd.products_description, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_ALSO_PURCHASED);
    //revised to take into account special prices- original code below
	//$best_sellers_query = tep_db_query("select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_ordered from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by p.products_ordered DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } ?>
  
  <div class="also-purchased-products-container">
<div class="also-purchased-carousel">
<?php
   $info_box_contents = array();
      new plcontentBoxHeading($info_box_contents);
  
  $info_box_contents = array();
  $row = 0;
  $col = 0;
  while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
    $best_sellers['products_name'] = tep_get_products_name($best_sellers['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="also-purchased-products"',
                                           'text' => '<div><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $best_sellers['products_image'], $best_sellers['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a><br>' . $currencies->display_price($best_sellers['products_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])).'</div>');
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
<!-- best_sellers_eof //-->