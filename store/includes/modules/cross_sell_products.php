<?php
/*
$Id: cross_sell_products.php, v1  2002/09/11

Digistore v4.0,  Open Source E-Commerce Solutions
<http://www.digistore.co.nz>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['products_id']) {
  $cross_sell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_CROSS_SELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and xp.cross_sell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by pd.products_name asc limit " . MAX_DISPLAY_ALSO_PURCHASED);
  $num_products_cross_sell = tep_db_num_rows($cross_sell_query);

  if ($num_products_cross_sell >= 1) {
?>
<!-- cross_sell_products //-->
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_CROSS_SELL_PRODUCTS);
    new contentBoxHeading($info_box_contents);

    $row = 0;
    $col = 0;
    $info_box_contents = array();

    while ($cross_sell = tep_db_fetch_array($cross_sell_query)) {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                              'params' => 'class="smallText" width="33%" valign="top"',
                                              'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $cross_sell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $cross_sell['products_image'], $cross_sell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $cross_sell['products_id']) . '">' . $cross_sell['products_name'] .'</a>');
      $col ++;

      if ($col > 2) {
        $col = 0;
        $row ++;
      }
    }

    new contentBox($info_box_contents);
?>
<!-- cross_sell_products_eof //-->
<?php
  }
}
?>