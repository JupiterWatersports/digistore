<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);
  new infoBoxHeading($info_box_contents, true, true, tep_href_link(FILENAME_SHOPPING_CART));

  if ($cart->count_contents() > 0) {
    $info_box_contents = array();
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . $products[$i]['id'] . "'
                                       and pa.options_id = '" . $option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

//Build the infobox...
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      $cur_row = sizeof($info_box_contents);

     if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
       $style_text = '<span class="newItemInCart">';
     } else {
       $style_text ='<span class="infoBoxContents">';
     }

//Product Details First...
      $products_name = '  <tr>' .
  // uncomment this line to get micro thumbnails!                     '     <td><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], '30') . '&nbsp;</a></td>' .
                       '    <td colspan="4" class="infoBoxContents" valign="top"> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">'  . $style_text . $products[$i]['name'] . '</span></a>';

//--Add any attributes...
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {

          $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '    </td>' .
                        '  </tr>';
                        

//Now build the Quantity form...
      //--Make sure we have a form array...one element is not an array and
      //--javascript doesn't like elements with [] so add some extra fields if there is only one product in the cart!
      if (sizeof($products)==1){
         $j =$i+1;
         $extra_elements = tep_draw_hidden_field('cart_quantity[]', $products[$i]['quantity']) . tep_draw_hidden_field('products_id[]', $products[$i]['id']);
      } else {
         $j =$i;
         $extra_elements = '';
      }

      $products_form ='    <tr>' .
                     '      <td>Qty&nbsp;</td>' .
                     '      <td> ' . $extra_elements . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'class="quantitybox"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . '</td>' .
                     '      <td><a href="javascript:void(increment('. $j . '));" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage(\'document.up' . $i . '\',\'document.up' . $i . '\',\'images/btn-up-ov.gif\')" ><img name="up' . $i . '" src="images/btn-up.gif" border="0" width=14 height=10></a><br><a href="javascript:void(decrement('. $j . '));" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage(\'document.dn' . $i . '\',\'document.dn' . $i . '\',\'images/btn-dn-ov.gif\')" ><img name="dn' . $i . '" src="images/btn-dn.gif" border="0" width=14 height=10></td>' .
                     '      <td width="100%" align="right"><b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b></td>' .
                     '    </tr>'.
                     '    <tr><td colspan="5" align="right" width="100%" class="infoBoxContents"><a href="javascript:void(mark(\'' . $products[$i]['id'] . '\'));"><span class="removeProduct">' . TABLE_HEADING_REMOVE . '&nbsp;<img src="images/remove.gif" border="0" height="8" width="8">&nbsp;</span></a></td></tr>' .
                     '    <tr><td colspan="5" width="100%">' . tep_draw_separator('pixel_silver.gif') . '</td></tr>';

      $cart_contents .= $products_name . $products_form;
    }

//Finally, add the total...
//--If we are already at the checkout, don't show the checkout button!
   if (preg_match("/checkout/", $PHP_SELF)) {
     $co_link = '';
   } else {
     $co_link = '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' .  IMAGE_BUTTON_CHECKOUT . '</a>';
   }

      $total = '  <tr height="20">' .
               '    <td colspan="3" align"left">' . $co_link . '</td>' .
               '    <td colspan="1" align="right" width="100%" class="infoBoxContents"><b>' . $currencies->format($cart->show_total()) . '</b></td>' .
               '  </tr>';

      $cart_contents .= $total;
               
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents);

  new cartBox($info_box_contents);

  } else {
  new infoBox(array(array('text' => BOX_SHOPPING_CART_EMPTY)));
  }
?>
</form>
 
