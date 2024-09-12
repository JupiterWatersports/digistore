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
             $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.options_values_id, pa.price_prefix, pa.products_attributes_id, pa.options_values_msrp, pa.products_id, pa.attribute_special_order
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . $products[$i]['id'] . "'
                                       and pa.options_id = '" . $option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
		  
		  $products[$i][$option]['products_id'] = $attributes_values['products_id'];
          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
		  $products[$i][$option]['options_values_msrp'] = $attributes_values['options_values_msrp'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
		  $products[$i][$option]['attribute_special_order'] =  $attributes_values['attribute_special_order'];
        }
      }
    
    $check_for_variant_image_query = tep_db_query("SELECT variants_image_xl_1 from variants_images where parent_id = '".$products_id."' and options_values_id = '".$attributes_values['options_values_id']."'");
    $check_for_variant_image = tep_db_fetch_array($check_for_variant_image_query);
    
    if($check_for_variant_image['variants_image_xl_1'] <> ''){
        $product_image = $check_for_variant_image['variants_image_xl_1'];
    } else {
        $product_image = $products[$i]['image'];
    }
    

echo'<div class="productListing">';
    
  $check_att_query = tep_db_query("select * from products p, products_attributes pa where p.products_id = '".$products[$i]['id']."' and pa.products_id = p.products_id");	 
 
      $cur_row = sizeof($info_box_contents) - 1;

 	if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
	reset($products[$i]['attributes']);
		while (list($option, $value) = each($products[$i]['attributes'])) {
	$products_id = $products[$i][$option]['products_id'];
	
	$stock_check_attributes2 = tep_db_query("select sum(options_quantity) as counts
                                      from  " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_values_id = '" . (int)$value . "'");
          $stock_check_attributes_values2 = tep_db_fetch_array($stock_check_attributes2);
		$products[$i][$option]['counts'] =  $stock_check_attributes_values2['counts'];
	
	
		}
	}
	else {  $products_id = $products[$i]['id']; }
	
      echo'  <div class="minicart-image">
      <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id) . '">' . tep_image(DIR_WS_IMAGES . $product_image, $products[$i]['name'], 75, 75). '</a></div>' .
                    '<div class="mini-cart-item-full-details">' .
					  '<div style="width:100%; float:left; padding-bottom:10px;">' .
                       ' <div class="mini-cart-item-details">'.'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id) . '">' . $products[$i]['name'] . '</a>' 
					   . $products[$i]['qproduct'] . ''; //' added qproduct for Get 1 Free mod

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
		  
		  if($products[$i][$option]['options_values_msrp'] > 0){
			$products_price = $products[$i][$option]['options_values_price'];
		  } else {
			$products_price = $products[$i]['price'] + $products[$i][$option]['options_values_price'];
		  }
			
		  if ($products[$i][$option]['attribute_special_order'] == '1'){
		  echo '</br>**<b>SPECIAL ORDER</b>**';
		  } 
       }
	  }
	if (tep_db_num_rows($check_att_query) < 1) {
	$products_price = $products[$i]['price'];
	echo '</br><div class="" valign="top">' . $currencies->display_price($products_price, tep_get_tax_rate($products[$i]['tax_class_id'])) .'</div>';
	if ($products[$i]['special_order'] == '1'){
		echo '</br>**<b>SPECIAL ORDER</b>: actual ETA may vary**';
	 } 

 }
 $special_order_check = tep_db_query("select products_special_order from products where products_id = '".$products[$i]['id']."'");	
$special_order = tep_db_fetch_array($special_order_check);
 if ($special_order['products_special_order'] == '1'){
		echo '</br>**<b>SPECIAL ORDER</b>**';
	 }
	 
      echo '</div><div class="mini-cart-item-quantity" valign="top">'. tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']).'</div>'.

    '<div class="mini-cart-item-price" valign="top">'.'<b>' . $currencies->display_price($products_price, tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b></div>'.
						'</div>'.
      					'<a class="mini-cart-remove" href="' . tep_href_link(FILENAME_SHOPPING_CART,"action=remove_product&products_id=" . tep_get_uprid($products[$i]['id'], $products[$i]['attributes'])) .'">Remove</a>' .
      					'</div>'.					
						'</div>';		
    }

   
?>  
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
        
           </form>
           <a class="button-blue-small" href="shopping_cart" style="height: 35px; width: 120px; color: #fff; text-decoration: none; padding: 5px; border-radius: 5px; float:right; margin:15px 10px; line-height:1.3rem; ">View Cart</a>
       
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
