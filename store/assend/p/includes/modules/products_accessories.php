<?php
/*
  $Id: products_accessories.php, v1.1  20110904 kymation $
  $From: xsell_products.php, v1  2002/09/11 $
  $Loc: catalog/includes/modules/ $

  osCommerce, Open Source E-Commerce Solutions
  <http://www.oscommerce.com>

  Copyright (c) 2011 osCommerce

  Released under the GNU General Public License
*/


  if (SPECIFICATIONS_ACCESSORIES_TAB == 'True' && isset ($_GET['products_id']) && $_GET['products_id'] > 0) {
    $xsell_query_raw = "
      select distinct
        p.products_id,
        pd.products_name,
        p.products_tax_class_id,
        p.products_price,
        p.products_image
      from
        " . TABLE_PRODUCTS_XSELL . " xp
        join " . TABLE_PRODUCTS . " p
          on (p.products_id = xp.xsell_id)
        join " . TABLE_PRODUCTS_DESCRIPTION . " pd
          on (pd.products_id = p.products_id)
      where
        xp.products_id = '" . (int) $_GET['products_id'] . "'
        and pd.language_id = '" . $languages_id . "'
        and p.products_status = '1'
      order by
        xp.sort_order asc
      limit
        " . MAX_DISPLAY_ALSO_PURCHASED . "
    ";
    $xsell_query = tep_db_query( $xsell_query_raw );
    $num_products_xsell = tep_db_num_rows( $xsell_query );
    if( $num_products_xsell >= MIN_DISPLAY_ALSO_PURCHASED ) {
?>
<!-- products_accessories //-->
<!-- sphider_noindex -->
 <?php
      $info_box_text = '';
      $box_number = 1;
      $space_above = false;
      while ($xsell_products = tep_db_fetch_array ($xsell_query) ) {

        $cPath_new = tep_get_product_path( $xsell_products['products_id'] );

        echo '<a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'cPath=' . $cPath_new . '&products_id=' . $xsell_products['products_id'] ) . '">';

        // Draw a box around the product information
        echo '<span class=imageBox id="box_' . $box_number . '"';
        if (SPECIFICATIONS_ACCESSORIES_MOUSEOVER == 'True') {

          // Change the colors in the next line to change the mousover color of the border
          echo ' onmouseover="set_CSS(\'box_' . $box_number . '\',\'borderColor\',\'#aabbdd\')" onmouseout="set_CSS(\'box_' . $box_number . '\',\'borderColor\',\'#182d5c\')" ';
        }
        echo '>';

        // Show the products image if selected in Admin
        if (SPECIFICATIONS_ACCESSORIES_IMAGE == 'True') {
          echo tep_image (DIR_WS_IMAGES . $xsell_products['products_image'], $xsell_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="2" vspace="3"');
          $space_above = true;
        } //if (SPECIFICATIONS_ACCESSORIES_IMAGE

        // Show the products name if selected in Admin
        if (SPECIFICATIONS_ACCESSORIES_NAME == 'True') {
          if ($space_above == true) {
            echo "<br>\n";
          }

          echo '<b>' . $xsell_products['products_name'] . '</b>';
          $space_above = true;
        } //if (SPECIFICATIONS_ACCESSORIES_NAME

        echo '</span>' . "\n";
        echo '</a>';

        $box_number++;
      } // while ($xsell_products

?>
<!-- /sphider_noindex -->
<!-- products_accessories_eof //-->
<?php
    }
  }
?>
