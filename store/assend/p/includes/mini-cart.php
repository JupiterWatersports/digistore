<div id="shoppingcart-contents-inner">
<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
	
<?php
  if ($cart->count_contents() > 0) {
?>
 
<?php
  echo '<div class="shoppingcart-table">';
 
 
   $info_box_contents = array('');
    $any_out_of_stock = 0;

    $products = $cart->get_products();

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
/*
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
*/
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id
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
		  $products[$i][$option]['products_attributes_id'] = $attributes_values['products_attributes_id'];
        }
      }
    }



    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
   
      $cur_row = sizeof($info_box_contents) - 1;

      $products_name = '<div class="productListing">' .
                       '  <div class="minicart-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], 75, 75). '</a></div>' .
                    '<div class="mini-cart-item-full-details">' .
					  '<div style="width:100%; float:left; padding-bottom:10px;">' .
                       ' <div class="mini-cart-item-details">'.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a>' 
					   . $products[$i]['qproduct'] . ''; //' added qproduct for Get 1 Free mod

      if (STOCK_CHECK == 'true') {

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {

	$stock_check = tep_check_stock_attribute($products[$i]['id'], $products[$i][$option]['products_attributes_id'], $products[$i]['quantity']);
		if (tep_not_null($stock_check)) {
		$any_out_of_stock = 1;
		$products_name .=  $stock_check. '</i></small>';
		}

	}
	} else {
	$stock_check = tep_check_stock($products[$i]['id'], $products[$i][$option]['products_attributes_id'], $products[$i]['quantity']);
		if (tep_not_null($stock_check)) {
		$any_out_of_stock = 1;
		$products_name .= $stock_check. '</i></small>';
		}
	}

}

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']) && $any_out_of_stock == 0 ) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }
   
      $info_box_contents[$cur_row][] = array('params' => '',
                                             'text' => $products_name);

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="mini-cart-item-quantity" valign="top"',
                                             'text' =>''. tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']).'');

      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="mini-cart-item-price" valign="top"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>' .
      					'</div>'.
						'</div>'.
      					'	<a class="mini-cart-remove" href="' . tep_href_link(FILENAME_SHOPPING_CART,"action=remove_product&products_id=" . tep_get_uprid($products[$i]['id'], $products[$i]['attributes'])) .'">' . Remove . '</a>' .
      					'</div>'.
						'</div>');		
    }

    new minicartTable($info_box_contents);
?> </div>  
       <b style="float:right; margin:10px 10px 0px 0px;"><?php echo 'Sub Total'; ?> <?php echo $currencies->format($cart->show_total()); ?></b>
<?php
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
      <tr>
        <td class="stockWarning" align="center"><br /><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td class="stockWarning" align="center"><br /><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
<?php
      }
    }
?>

  <div style="clear:both; height:45px;">
        
       <?php echo '<span class="mini-cart-btns">'.tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART).'</span>'; ?>
           </form><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')).'<span class="mini-cart-btns">'.tep_image_submit(IMAGE_BUTTON_CHECKOUT, 'checkout').'</span>' . '</form>'; ?>
       
      </div>



<?php
      } else {
?>
<style>
#shoppingcart-contents{width:310px;}
</style>
        <div class="mini-cart-empty" style="margin: 10px auto;"><h3>Your Cart is Empty</h3></div>

<?php 
}
?>

</form>
</div>
