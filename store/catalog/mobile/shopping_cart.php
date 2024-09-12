<?php
require_once('includes/application_top.php');

  if ($cart->count_contents() > 0) {
    include(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_SHOPPING_CART));
    
  require(DIR_MOBILE_INCLUDES . 'header.php');
  $headerTitle->write();
  
?>
<div id="iphone_content">
<?php
  if ($cart->count_contents() > 0) {
  echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));

    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
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

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

?>
<div id="cms">
    
<table id="shopping-cart-table" data-role="table" data-mode="reflow" class="ui-body-d ui-shadow table-stripe ui-responsive table-stroke">
  <thead>
    <tr>
      <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
      <th><?php echo ''; ?></th>
      <th style="text-align:center"><?php echo TABLE_HEADING_QUANTITY; ?></th>
      <th><?php echo ''; ?></th>
      <th style="text-align:right"><?php echo TABLE_HEADING_PRICE; ?></th>
    </tr>
  </thead>
  <tbody>

<?php
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      $products_image = '<a href="' . tep_mobile_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH/2, SMALL_IMAGE_HEIGHT/2) . '</a>';
      $products_name = '<a href="' . tep_mobile_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';

      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_qty = tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="2" data-theme="a" data-mini="true"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']);
      $products_price = '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
      $products_buttons = '<input value="'.IMAGE_BUTTON_UPDATE.'" type="submit" name="refresh" data-mini="true" data-inline="true" data-role="submit"  data-icon="check" data-theme="b">' . tep_button_jquery( IMAGE_BUTTON_REMOVE,tep_mobile_link(FILENAME_SHOPPING_CART, 'products_id=' . $products[$i]['id'] . '&action=remove_product'), 'b' , 'button' , 'data-icon="delete" data-mini="true" data-inline="true"' );

?>          
      <tr>
        <th><?php echo $products_image; ?></th>
        <td><?php echo $products_name; ?></td>
        <td style="text-align:center"><?php echo $products_qty; ?></td>
        <td><?php echo $products_buttons; ?></td>
        <td style="text-align:right"><?php echo $products_price; ?></td>
      </tr>
      <?php
    }
    ?>

  </tbody>
</table>    
    
<?php
	if ($any_out_of_stock == 1) {
		?><span class="messageStackWarning"><?php
		if (STOCK_ALLOW_CHECKOUT == 'true') {
			echo OUT_OF_STOCK_CAN_CHECKOUT;
		} else {
			echo OUT_OF_STOCK_CANT_CHECKOUT; 
		}
		?></span><?php
	}

    echo '<br><div style="padding-right:4px; float:right;"><strong>' . SUB_TITLE_TOTAL . '&nbsp;&nbsp;' . $currencies->format($cart->show_total()) . '</strong></div><br><br>';
?>
<div id="bouton">
	<div class="ui-grid-a ui-responsive">
    
<?php 
	echo '<div class="ui-block-a">' . 
	      tep_button_jquery(IMAGE_BUTTON_BACK,'#','b','button','data-rel="back" data-icon="back" ') . 
	     '</div>
	     <div class="ui-block-b">' . 
	     tep_button_jquery(IMAGE_BUTTON_CHECKOUT,tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'), 'b' , 'button' , 'rel="external" data-iconpos="right" data-icon="arrow-r"' );
	     ?>
<?php

    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();

    if (!empty($initialize_checkout_methods)) {
?>
        
        	<br /><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?><br /><br />
<?php
      reset($initialize_checkout_methods);
      while (list(, $value) = each($initialize_checkout_methods)) {
		echo $value;
      }
    }
?>
</div>
</div>
</div>
</div>
</form>
<?php 
  } else {
?>
	<div id="cms" style="text-align:center;">
        <?php echo TEXT_CART_EMPTY; ?>
        	<div id="bouton">
        		<?php echo  tep_button_jquery(IMAGE_BUTTON_CONTINUE,tep_mobile_link(FILENAME_CATALOG_MB),'b','button','data-icon="check"'); ?>
        	</div>
        </div>
<?php
  }
?>          

<?php
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
