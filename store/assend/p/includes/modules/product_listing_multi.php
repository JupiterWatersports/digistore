<?php
/*
  $Id: product_listing.php,v 1.39 2002/10/26 22:43:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $listing_numrows_sql = $listing_sql;
// fix counted products
  $listing_numrows = tep_db_query($listing_numrows_sql);
  $listing_numrows = tep_db_num_rows($listing_numrows);

  if ($listing_numrows > 0 ) {
?>
  <tr>
    <td>
<?php
    $listing = tep_db_query($listing_sql);
    $list_box_contents = array();
    $number_of_products = '0';
?>
    <form name="cart_multi" method="post" action="<?php echo tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params(array('action')) . 'action=add_multi', 'NONSSL'); ?>">
<?php
    $list_box_contents[] = array('params' => 'class="productListing-heading"');

    $cur_row = sizeof($list_box_contents) - 1;

    $lc_text = TEXT_PRODUCT;
    $lc_align = 'left';
    $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-heading"',
                                            'text'  => $lc_text);
    $lc_text = TEXT_MODEL;
    $lc_align = 'center';
    $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);
    $lc_text = TEXT_OPTIONS;
    $lc_align = 'center';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);

    $lc_text = TEXT_PRICE;
    $lc_align = 'right';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);
    $lc_text = TEXT_AMOUNT_BUY;
    $lc_align = 'center';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);

    while ($listing_values = tep_db_fetch_array($listing)) {
      $number_of_products++;
      $lc_align='';
      if ( ($number_of_products/2) == floor($number_of_products/2) ) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }
      $cur_row = sizeof($list_box_contents) - 1;
      $products_attributes = tep_db_query("select popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $listing_values['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
      if (tep_db_num_rows($products_attributes)) {
        $products_attributes = '1';
      } else {
        $products_attributes = '0';
      }

// product_name
     $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing_values['products_id']) . '">' . $listing_values['products_name'] . '</a>&nbsp;';
    $lc_align = 'left';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);

     $lc_text = '&nbsp;'.$listing_values['products_model'].'&nbsp;';
     $lc_align = 'center';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);
      if ($products_attributes) {



      $products_options_name = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $listing_values['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
//        $products_options_name = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $listing_values['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "' order by popt.products_options_id");
      $lc_text = '<table border="0" cellpading="0" cellspacing"0">';
      $lc_align = 'center';
//      while ($products_options_name_values = tep_db_fetch_array($products_options_name)) { 
//        $selected = 0;
//        $products_options_array = array();
//        $lc_text.= '<tr><td class="main">' . $products_options_name_values['products_options_name'] . ':</td><td>' . "\n"; 
//        $products_options = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $listing_values['products_id'] . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'");
//        while ($products_options_values = tep_db_fetch_array($products_options)) {
//          $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
//          if ($products_options_values['options_values_price'] != '0') {
//            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->display_price($products_options_values['options_values_price'], tep_get_tax_rate($product_info_values['products_tax_class_id'])) .') ';
//          }
//        }
//        $lc_text .= tep_draw_pull_down_menu('id['.$number_of_products.'][' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name_values['products_options_id']]);
//        $lc_text .= '</td></tr>';
//      }



      while ($products_options_name_values = tep_db_fetch_array($products_options_name)) { 
        $selected = 0;
        $products_options_array = array();
        $lc_text.= '<tr><td class="main">' . $products_options_name_values['products_options_name'] . ':</td><td>' . "\n"; 
        $products_options = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $listing_values['products_id'] . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'");
        while ($products_options_values = tep_db_fetch_array($products_options)) {
          $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
          if ($products_options_values['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->display_price($products_options_values['options_values_price'], tep_get_tax_rate($product_info_values['products_tax_class_id'])) .') ';
          }
        }
        $lc_text .= tep_draw_pull_down_menu('id['.$number_of_products.'][' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name_values['products_options_id']]);
        $lc_text .= '</td></tr>';
      }

      $lc_text .= '</table>';

     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);

      } else {
      $lc_text = '&nbsp;';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);
      }
            if ($listing_values['specials_new_products_price']) {
              $lc_text = '&nbsp;<s>' .  $currencies->display_price($listing_values['products_price'], tep_get_tax_rate($listing_values['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing_values['specials_new_products_price'], tep_get_tax_rate($listing_values['products_tax_class_id'])) . '</span>&nbsp;';
             } else {
               $lc_text = '&nbsp;' . $currencies->display_price($listing_values['products_price'], tep_get_tax_rate($listing_values['products_tax_class_id'])) . '&nbsp;';
             }
     $lc_align = 'right';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);

     $lc_align = 'center';
     $lc_text = '<input type="text" name="add_id['.$number_of_products.']" value="0" size="4">';
     $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                            'params' => 'class="productListing-data"',
                                            'text'  => $lc_text);
?>
<input type="hidden" name="products_id[<?php echo $number_of_products; ?>]" value="<?php echo $listing_values['products_id']; ?>"> 
<?php
    }
    new tableBox($list_box_contents, true);

    echo '    </td>' . "\n";
    echo '  </tr>' . "\n";
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" class="main"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><?php echo tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT); ?></a></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></td>
          </tr>
        </table></td>
      </tr></form>
<?php





  } else {
?>
  <tr class="productListing-odd">
    <td class="smallText">&nbsp;<?php echo ($HTTP_GET_VARS['manufacturers_id'] ? TEXT_NO_PRODUCTS2 : TEXT_NO_PRODUCTS); ?>&nbsp;</td>
  </tr>
<?php
  }
?>
</table>