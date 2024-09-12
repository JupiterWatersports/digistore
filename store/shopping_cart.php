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
 require(DIR_WS_LANGUAGES . $language . '/shopping_cart.php');
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
echo $doctype;
?>
<html lang="en-US">
<head>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '234965059596038');
fbq('track', 'AddToCart');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=234965059596038&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta name="description" content="Add your items here to check out and pay." />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>
<?php  
//echo TITLE; 
?>Shopping Cart</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-cart.php'); ?> 
<form name="cart_quantity" id="cart_quantity">
<div class="clear spacer-tall"></div> 
<style>
#cart:hover #shoppingcart-contents{display:none !important;}
@media only screen and (max-width :990px) {.container_12{width:100%; padding-left:15px; padding-right:15px;}}
</style>
<h1>Shopping Cart</h1>
<?php
  if ($cart->count_contents() > 0) {
?>
<div id="shopping-cart-table">
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
          $attributes = tep_db_query("select popt.products_options_name, pa.options_values_id, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id, pa.options_values_msrp, pa.products_id, pa.attribute_special_order
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
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
		  $products[$i][$option]['options_values_msrp'] = $attributes_values['options_values_msrp'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
		  $products[$i][$option]['products_attributes_id'] = $attributes_values['products_attributes_id'];
		  $products[$i][$option]['attribute_special_order'] =  $attributes_values['attribute_special_order'];
        }
        //$options = strstr($products[$i]['id'], '{');    
        $options = '&opt='.substr($products[$i]['id'], strrpos($products[$i]['id'], '}')+1);  
      } else {
          $options = '';
      }  

	echo'<div class="productListing">';
	
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
 
	$str = $products[$i]['id'];
	$str2 = trim(preg_replace('/\s*\{[^)]*\}/', '', $str));
	$cur_row = sizeof($info_box_contents) - 1; 

 
$check_att_query = tep_db_query("select * from products p, products_attributes pa where p.products_id = '".$products[$i]['id']."' and pa.products_id = p.products_id");	  
$stock_check_products_query = tep_db_query("select products_quantity from products  where products_id = '".$products[$i]['id']."'");
while ($stock_check_product = tep_db_fetch_array($stock_check_products_query)){	 
	if (tep_db_num_rows($check_att_query) < 1) {
 echo    '<div id="stockcheck'.$str2.'" class="stock-warning" style="width:100%; display:none; margin-bottom:15px; color: #ff0000; font-size: 18px; padding: 15px; text-align:center;"><i class="fa fa-exclamation-triangle"></i>&nbsp;We are very sorry there are only&nbsp;'.$stock_check_product['products_quantity'].'&nbsp;of these available at this time</div>';  
 		if ($products[$i]['special_order'] !== '1'){
    ?>
      
     <script type="text/javascript">
		$(document).ready(function() {
		$('#select<?php echo $str2; ?>').on('change',function(){
   
		if ($(this).val() > <?php echo $stock_check_product['products_quantity'];?> ){ 
		
		$(this).val("<?php echo $stock_check_product['products_quantity'];?>");
		var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	    $("#shopping-cart-table").html(data);
	    $('#stockcheck<?php echo $str2; ?>').show();
		  }
		    });
 		
		} else {
		var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	  $("#shopping-cart-table").html(data);
	  $('#stockcheck<?php echo $str2; ?>').hide();
		  }
		  
		  });
		  }
		}); 
		});
		</script> 
	  
	 <?php }else {?>
     <script>
	 $(document).ready(function() {
	 $('#select<?php echo $str2; ?>').on('change',function(){
     var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	  $("#shopping-cart-table").html(data);
	  $('#stockcheck<?php echo $str2; ?>').hide();
		  }
		  
		  });
		 
		}); 
		});
        </script>
     <?php  }
	 }
} if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
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
		echo    '<div id="stockcheck'.$str2.'" class="stock-warning" style="width:100%; display:none; margin-bottom:15px; color: #ff0000; font-size: 18px; padding: 15px; text-align:center;"><i class="fa fa-exclamation-triangle"></i>&nbsp;We are very sorry there are only&nbsp;'.$products[$i][$option]['counts'].'&nbsp;of these available at this time</div>';  
	  				 
	}
    
}

    $check_for_variant_image_query = tep_db_query("SELECT variants_image_xl_1 from variants_images where parent_id = '".$products_id."' and options_values_id = '".$attributes_values['options_values_id']."'");
    $check_for_variant_image = tep_db_fetch_array($check_for_variant_image_query);
    
    if($check_for_variant_image['variants_image_xl_1'] <> ''){
        $product_image = $check_for_variant_image['variants_image_xl_1'];
    } else {
        $product_image = $products[$i]['image'];
    }
        
      
  echo  '<div class="cart-item-image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id.$options) . '">' . tep_image(DIR_WS_IMAGES . $product_image, $products[$i]['name'], 150, 150). '</a></div>' .
                       '<div class="cart-details-container">'.
					   '<div class="cart-item-details">'.'<a class="cart-item-name" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$products_id.$options) . '">' . $products[$i]['name'] . '</a>' 
					   . $products[$i]['qproduct'] . ''; //' added qproduct for Get 1 Free mod

     
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo  '<br /><small class="cart-item-attributes"> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
		
 
		
		 $stock_check_attributes = tep_db_query("select sum(options_quantity) as count, attribute_special_order
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id ");
          $stock_check_attributes_values = tep_db_fetch_array($stock_check_attributes);
		$products[$i][$option]['count'] =  $stock_check_attributes_values['count'];
		
		 if ($products[$i][$option]['attribute_special_order'] !== '1'){
		?> 
        <script type="text/javascript">
		$(document).ready(function() {
		$('#select<?php echo $str2; ?>').on('change',function(){
   
		if ($(this).val() > <?php echo $products[$i][$option]['count'];?> ){ 
		
		$(this).val("<?php echo $products[$i][$option]['count']; ?>");
		var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	    $("#shopping-cart-table").html(data);
	    $('#stockcheck<?php echo $str2; ?>').show();
		  }
		    });
 		
		} else {
		var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	  $("#shopping-cart-table").html(data);
	  $('#stockcheck<?php echo $str2; ?>').hide();
		  }
		  
		  });
		  }
		}); 
		});
		</script>
		
	<?php } else { ?>
    	<script type="text/javascript">
		$(document).ready(function() {
			$('#select<?php echo $str2; ?>').on('change',function(){
   
		var data = $("#cart_quantity").serialize();
  		$.ajax({
 		type : 'POST',
  		url  : 'ajax-cart.php?action=update_product',
 		data : data,
		success :  function(data) {
	  $("#shopping-cart-table").html(data);
	  $('#stockcheck<?php echo $str2; ?>').hide();
		  }
		  });
		}); 
		});
		</script>
<?php }
			if($products[$i][$option]['options_values_msrp'] > 0){
			$products_price = $products[$i][$option]['options_values_price'];
			} else {
			$products_price = $products[$i]['price'] + $products[$i][$option]['options_values_price']; }

if ($products[$i][$option]['attribute_special_order'] == '1'){
	echo '</br>**<b>SPECIAL ORDER</b>: actual ETA may vary**'; } 
	
     } echo '</br><div class="" valign="top">' . $currencies->display_price($products_price, tep_get_tax_rate($products[$i]['tax_class_id'])) .'</div>';
}
if (tep_db_num_rows($check_att_query) < 1) {
	$products_price = $products[$i]['price'];
	echo '</br><div class="" valign="top">' . $currencies->display_price($products_price, tep_get_tax_rate($products[$i]['tax_class_id'])) .'</div>';
	if ($products[$i]['special_order'] == '1'){
		echo '</br>**<b>SPECIAL ORDER</b>: actual ETA may vary**';
	 } 
 }
        $quantity_array = array();    
    for($z=1; $z<11; $z++){
       $quantity_array[] = array('id'=> $z, 'text'=> $z); 
    }    


$str = $products[$i]['id'];
$str2 = trim(preg_replace('/\s*\{[^)]*\}/', '', $str));     
   

			
			echo'</div><div class="cart-item-quantity" valign="top">'. tep_draw_pull_down_menu('cart_quantity[]', $quantity_array, $products[$i]['quantity'], 'style="text-align:center; margin-bottom:10px;" id="select'.$str2.'" class="cart-qty-select"') . tep_draw_hidden_field('products_id[] ', $products[$i]['id'])
			.'<a class="remove" style="display:block;" href="' . tep_href_link(FILENAME_SHOPPING_CART,"action=remove_product&products_id=" . tep_get_uprid($products[$i]['id'], $products[$i]['attributes'] )) .'">Remove</a></div>'.
			' <div class="cart-item-price" valign="top">'.'<b>' . $currencies->display_price($products_price, tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) .'</b></div>'.
			'</div>';									 		
	  echo'</div>';	
	  }

	
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


 <?php //shopping cart total
    echo '<div class="shoppingcart-total col-xs-12">'.SUB_TITLE_SUB_TOTAL.$currencies->format($cart->show_total()).'</div>'; ?>
 </div>  
 </div>
<!--buttons-->
<div class="cart-buttons col-xs-12">
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
				<div class="shopping-cart-btns continue-shop"><?php echo '<a class="cssButton" href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $continueButtonId) . '">Continue Shopping</a>'; ?></div>
<?php
// if (isset($navigation->path[$back])) {  
	} elseif (isset($navigation->path[$back])) {
// EOF FWR Mod category based continue button
?>
				<div class="shopping-cart-btns continue-shop"><?php echo '<a class="cssButton" href="' . tep_href_link(FILENAME_DEFAULT) . '">Continue Shopping</a>'; ?></div>
<?php
	}
?>

<?php
echo '<div class="shopping-cart-btns checkout-btn"><a class="cssButton" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">Checkout</a></div>'; 

    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();
    if (!empty($initialize_checkout_methods)) {

?>
</div>
      		<p><?php //echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></p>
<?php

      reset($initialize_checkout_methods);

      while (list(, $value) = each($initialize_checkout_methods)) {

?>
      <p><?php// echo $value; ?></p>
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

