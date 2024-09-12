<?php

/*

  $Id: shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
  Released under the GNU General Public License
*/



  require("includes/application_top.php");



  if ($cart->count_contents() > 0) {

    include(DIR_WS_CLASSES . 'payment.php');

    $payment_modules = new payment;

  }
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
echo $doctype;
?>
<html lang="en-US">
<head>

<meta name="description" content="Add you items here to check out and pay." />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>
<?php  
//echo TITLE; 
?>Shopping Cart</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-cart.php'); ?> 
<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
<div class="clear spacer-tall"></div> 
<h1>Shopping Cart</h1>
<?php

  if ($cart->count_contents() > 0) {
?>

<?php
 echo '<div class="shoppingcart-table">';
 echo '<div class="shoppingcart-headings">'
	 
	 .'<div class="shoppingcart-heading products">Products</div>'
	 .'<div class="shoppingcart-heading quantity">Quantity</div>'
	 .'<div class="shoppingcart-heading total">Total</div>'
	 
	 .'</div>';

   $info_box_contents = array('');
    $any_out_of_stock = 0;

    $products = $cart->get_products();

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

// Push all attributes information in an array

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {

        while (list($option, $value) = each($products[$i]['attributes'])) {

          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
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
    // BOF Products Bundles

    if (STOCK_CHECK == 'true') {

      $runningsums = array();
      // Temporarily sort the cart so that bundles are first to
      // prevent marking a bundle out of stock, if the user tries to order
      // a bundle's worth separately.
      // Make a copy of the cart.

      $workprods = $products;

      for ($i = 0, $n = sizeof($workprods); $i < $n; $i++) {

        // Remember the original sort order.

        $workprods[$i]['origpos'] = $i;
      }
      // Sort.

      $inorder = false;

      while (!$inorder) {

        $inorder = true;

        for ($i = 0, $n = sizeof($workprods); $i < $n-1; $i++) {

          if ($workprods[$i]['bundle'] != "yes" && $workprods[$i+1]['bundle'] == "yes") {
            $workprod = $workprods[$i+1];
            $workprods[$i+1] = $workprods[$i];
            $workprods[$i] = $workprod;
            $inorder = false;

            break;
          }
        }
      }
      for ($i = 0, $n = sizeof($workprods); $i < $n; $i++) {
        if ($workprods[$i]['bundle'] == "yes") {
          $stock_checks[0][$i] = tep_check_stock($workprods[$i]['id'], $workprods[$i]['quantity']);
          if (!tep_not_null($stock_checks[0][$i])) {

            // The bundle is in stock, so count this against the total.

            $bundle_query = tep_db_query("SELECT subproduct_id, subproduct_qty FROM " . TABLE_PRODUCTS_BUNDLES . " WHERE bundle_id = '" . $workprods[$i]['id'] . "'");
            while ($bundle_data = tep_db_fetch_array($bundle_query)) {
              $work = $bundle_data['subproduct_id'];
              $runningsums[$work] += $bundle_data['subproduct_qty'];
            }
          }
        } else {

          $work = $workprods[$i]['id'];
          $runningsums[$work] += $workprods[$i]['quantity'];
          $stock_checks[0][$i] = tep_check_stock($workprods[$i]['id'], $runningsums[$work]);
        }
      }

      // Now go back to the original sort order.

      for ($i = 0, $n = sizeof($workprods); $i < $n; $i++) {
        $work = $workprods[$i]['origpos'];
        $stock_checks[1][$i] = $stock_checks[0][$work];
      }
    }

    // EOF Product Bundles
for ($i=0, $n=sizeof($products); $i<$n; $i++) {
  

      $cur_row = sizeof($info_box_contents) - 1;

      $products_name = '<div class="productListing">'.          
                       '<div class="cart-item-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], 150, 150). '</a></div>' .
                       '<div class="cart-item-details">'.'<a class="cart-item-name" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a>' 
					   . $products[$i]['qproduct'] . ''; //' added qproduct for Get 1 Free mod

      if (STOCK_CHECK == 'true') {

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {

	$stock_check = tep_check_stock_attribute($products[$i]['id'], $products[$i][$option]['products_attributes_id'], $products[$i]['quantity']);
		if (tep_not_null($stock_check)) {
		$any_out_of_stock = 1;
		$products_name .=  $stock_check. '</small>';
		}

	}
	} else {
	$stock_check = tep_check_stock($products[$i]['id'], $products[$i][$option]['products_attributes_id'], $products[$i]['quantity']);
		if (tep_not_null($stock_check)) {
		$any_out_of_stock = 1;
		$products_name .= $stock_check. '</small>';
		}
	}
}

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']) && $any_out_of_stock == 0 ) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br /><small class="cart-item-attributes"> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }
   
      $info_box_contents[$cur_row][] = array('params' => '',
                                             'text' => $products_name);

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="cart-item-quantity" valign="top"',
                                             'text' =>''. tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" style="text-align:center;"') . tep_draw_hidden_field('products_id[]', $products[$i]['id'])
											 .'<a class="remove" style="display:block;" href="' . tep_href_link(FILENAME_SHOPPING_CART,"action=remove_product&products_id=" . tep_get_uprid($products[$i]['id'], $products[$i]['attributes'] )) .'">' . Remove . '</a>');

      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="cart-item-price" valign="top"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>' .
      					'</div>'.
						'</div>');
    }

    new shoppingcartTable($info_box_contents);
	
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {

?>
        <p class="stockwarning" align="center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>
        

<?php

      } else {

?>
        <p class="stockWarning" align="center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></p>

<?php
      }
    }
?>
<style>
.container_12{padding-bottom:100px;}
</style>
</div>

 <?php //shopping cart total
    echo '<div class="shoppingcart-total" style="font-size: 20px; font-weight: 600; margin-top: 20px; margin-right:15px;">'.SUB_TITLE_SUB_TOTAL.$currencies->format($cart->show_total()).'</div>'; ?>
<div class="clear spacer-tall"></div>      
<!--buttons-->
<div class="cart-buttons">
      <div style="display:none;"><?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></div>

<?php
	$back = sizeof($navigation->path)-2;
// BOF FWR Mod category based continue button
	$count = count($products);
	if( isset($products[$count-1]['id']) ) {
	  $continueButtonId = tep_get_product_path(str_replace(strstr($products[$count-1]['id'], '{'), '', $products[$count-1]['id']));
	}
	if( isset($continueButtonId) ) {
?>
				<div class="shopping-cart-btns continue-shop"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $continueButtonId) . '">' . tep_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; ?></div>
<?php
// if (isset($navigation->path[$back])) {  
	} elseif (isset($navigation->path[$back])) {
// EOF FWR Mod category based continue button
?>
				<div class="shopping-cart-btns continue-shop"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; ?></div>
<?php
	}
?>

<?php
echo '<div class="shopping-cart-btns checkout"><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a></div>'; 

    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();
    if (!empty($initialize_checkout_methods)) {

?>
</div>
      		<p><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></p>
<?php

      reset($initialize_checkout_methods);

      while (list(, $value) = each($initialize_checkout_methods)) {

?>
      <p><?php echo $value; ?></p>
<?php
  	    }
  	  }
  } else {
?>
<style>
@media only screen and (min-width : 768px) and (max-width : 959px) {.container_12{width:95%;}}
</style>

<p><?php echo TEXT_CART_EMPTY; ?></p>

 <div class="clear spacer"></div>        
<div class="grid_8">
      <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>     		
</div>       
<?php

  }

?>
 </form> 
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

