<?php
/*
  $Id: checkout_shipping.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require('includes/classes/http_client.php');
// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
// if no shipping destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('sendto')) {
    tep_session_register('sendto');
    $sendto = $customer_default_address_id;
  } else {
// verify the selected shipping address
    if ( (is_array($sendto) && empty($sendto)) || is_numeric($sendto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
      $check_address = tep_db_fetch_array($check_address_query);
      if ($check_address['total'] != '1') {
        $sendto = $customer_default_address_id;
        if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
      }
    }
  }
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;
// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if ($order->content_type == 'virtual') {
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    $shipping = false;
    $sendto = false;
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
// check if customer's basket has been added to database
 if (is_array($cart->contents)) {
        reset($cart->contents);
        while (list($products_id, ) = each($cart->contents)) {
          $qty = $cart->contents[$products_id]['qty'];
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . tep_db_input($qty) . "', '" . date('Ymd') . "')");
            if (isset($cart->contents[$products_id]['attributes'])) {
              reset($cart->contents[$products_id]['attributes']);
              while (list($option, $value) = each($cart->contents[$products_id]['attributes'])) {
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')");
              }
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . tep_db_input($qty) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          }
        }
      }
    
$needApproval = false;
foreach($cart->contents as $content) {
  if(array_keys($content['attributes'])[0] == 28) {
    $needApproval = true;
  }
}

if($needApproval) {
  //tep_redirect(tep_href_link('order_approval.php'));
}
  
// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
        
       
            
      case 'both':
        $pass = true;
        break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
/*
if ($order->delivery['zone_id'] == 'Alaska' || 
    $order->delivery['zone_id'] == 'American Samoa' || 
    $order->delivery['zone_id'] == '96' || 
    $order->delivery['zone_id'] == '86' || 
    $order->delivery['zone_id'] == '87' || 
    $order->delivery['zone_id'] == '70' || 
    $order->delivery['zone_id'] == '71' || 
    $order->delivery['zone_id'] == '74' || 
    $order->delivery['zone_id'] == '75' || 
    $order->delivery['zone_id'] == '76' || 
    $order->delivery['zone_id'] == '77' || 
    $order->delivery['zone_id'] == '78' || 
    $order->delivery['zone_id'] == '79'){
            $free_shipping = false;
        } 
*/
  
  if ($free_shipping == false) {
  $check_free_shipping_basket_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
  while ($check_free_shipping_basket = tep_db_fetch_array($check_free_shipping_basket_query)) {
    $check_free_shipping_query = tep_db_query("select products_free_shipping from " . TABLE_PRODUCTS . " where products_id = '" . (int)$check_free_shipping_basket['products_id'] . "'");
	$check_free_shipping = tep_db_fetch_array($check_free_shipping_query);
	$check_free_shipping_array[] = $check_free_shipping['products_free_shipping'];
  }
  if (in_array("1", $check_free_shipping_array) && !in_array("0", $check_free_shipping_array)) {
    $free_shipping = true;
    include_once(DIR_WS_LANGUAGES . $language . '/checkout_shipping.php');
  }
}
// process the selected shipping method
  if ( isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (!tep_session_is_registered('comments')) tep_session_register('comments');
    if (tep_not_null($_POST['comments'])) {
      $comments = tep_db_prepare_input($_POST['comments']);
    }
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        $shipping = $_POST['shipping'];
        list($module, $method) = explode('_', $shipping);
        if ( is_object($$module) || ($shipping == 'free_free') ) {
          if ($shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else { // not free shipping
            // check if the cart and send to address haven't changed in the mean time
             if (isset($_SESSION['shipping_quotes']) && $_SESSION['shipping_quotes']['cartID'] == $cartID && $_SESSION['shipping_quotes']['sendto'] == $sendto) {
              foreach ($_SESSION['shipping_quotes']['quotes'] as $array_key => $shipping_quote) {
                if ($shipping_quote['id'] == $module) {
                  foreach ($shipping_quote['methods'] as $subarray_key => $method_array) {
                    if ($method_array['id'] == $method) {
                       $quote[] = array('id' => $module, 'module' => $shipping_quote['module'], 'methods' => array($method_array));
                       break;
                    } // end if ($method_array['id'] == $method)
                  } // end foreach ($shipping_quote['methods'] as $subarray_key => $method_array)
                  break; // found shipping quote in session
                } // end if ($shipping_quote['id'] == $module)
              } // end foreach ($_SESSION['shipping_quotes']['quotes']...
// if for some reason the quote is not found in the session so $quote is not set here we get our quotes again
              if (!isset($quote)) {
                $quote = $shipping_modules->quote($method, $module);
              } // end if (!isset($quote))
            } else { 
            $quote = $shipping_modules->quote($method, $module);
             }
          }
          if (isset($quote['error'])) {
            tep_session_unregister('shipping');
          } else {
            if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $shipping = array('id' => $shipping,
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);
              tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
            }
          }
        } else {
          tep_session_unregister('shipping');
        }
      }
    } else {
      $shipping = false;
                
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }    
  }
// get all available shipping quotes
// get all available shipping quotes
  $quotes = $shipping_modules->quote();
  if (!tep_session_is_registered('shipping_quotes')) tep_session_register('shipping_quotes');
  $shipping_quotes = array('cartID' => $cartID, 'sendto' => $sendto, 'quotes' => $quotes);
// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);
      require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
echo $doctype;
?><html <?php echo HTML_PARAMS; ?>>
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
fbq('track', 'InitiateCheckout');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=234965059596038&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<script language="javascript"><!--
var selected;
function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }
  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;
// one button is not an array
  if (document.checkout_address.shipping[0]) {
    document.checkout_address.shipping[buttonSelect].checked=true;
  } else {
    document.checkout_address.shipping.checked=true;
  }
}
function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}
function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>

<?php  $check_if_sup_part1_query = tep_db_query ("select products_id from customers_basket where customers_id = '".$customer_id."' ");
	$check_products_id = '';
	
	while($check_if_sup_part1 = tep_db_fetch_array($check_if_sup_part1_query)){
		
		if(strpos($check_if_sup_part1['products_id'], '{') !== false){
		$check_product_id = $check_if_sup_part1['products_id'];
		$check_product_id = substr($check_product_id, 0, strrpos($check_product_id, "{"));
		
		$check_products_id = $check_product_id;
		}
        $result = rtrim($check_products_id,"','");
        $check_if_oversized_query = tep_db_query ("select products_shipping_label from products where products_id = '".$result."'");
        
        $check_if_oversized = tep_db_fetch_array($check_if_oversized_query);
        if(($check_if_oversized['products_shipping_label'] == 'oversized') || ($check_if_oversized['products_shipping_label']== 'oversized2')){
            $check_oversized = '1';
            break;
        }
	}

	if($check_oversized > 0){ ?>
	<style>.shipping-options{display: none;}
		.sup-shipping{width:100%; border:solid 1px #DDD; padding:10px;}
		.sup-shipping p{font-size:1.2em; line-height:1.44;}
        .show{display:block !important;}
		</stlye>
	<?php  } ?>
		<style>
		body{font-size:100%; line-height: 1.4;}
	
	</style>
    
    <style>
        .edit{color:#000;}
    </style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>
<!--progressbar-->
<div id="progressbar">
	<span class="progressbar-active">Delivery</span>
	<span class="progressbar">Payment</span>
	<span class="progressbar">Confirmation</span>
	<span class="progressbar">Finished</span>	
</div>
<div class="clear"></div>
<div class="checkout-shipping-info col-sm-7">
<div class="grid_4 shipping-add">
 <div class="checkout-heading"><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></div>
	<p ><?php echo TEXT_CHOOSE_SHIPPING_DESTINATION; ?><p>
	
	<div class="leftfloat"><?php echo TITLE_SHIPPING_ADDRESS . tep_draw_separator('pixel_trans.gif', '20', '10'); ?>
	<div class="address"><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br />'); ?></div></div>
	
	<div class="clear spacer-tall"></div>
	
	<p class="right-align" style=""><?php echo '<a style="color:#fff;" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></p>
</div>
 
<div class="clear spacer"></div>                  
<?php 
    
/* if($order->delivery['country_id'] <> '223'){ 
        $free_shipping = false;
} else if (
    $order->delivery['zone_id'] == '70' || 
    $order->delivery['zone_id'] == '71' || 
    $order->delivery['zone_id'] == '96' || 
    $order->delivery['zone_id'] == '86' || 
    $order->delivery['zone_id'] == '87' || 
    $order->delivery['zone_id'] == '70' || 
    $order->delivery['zone_id'] == '71' || 
    $order->delivery['zone_id'] == '74' || 
    $order->delivery['zone_id'] == '75' || 
    $order->delivery['zone_id'] == '76' || 
    $order->delivery['zone_id'] == '77' || 
    $order->delivery['zone_id'] == '78' || 
    $order->delivery['zone_id'] == '79' ||
    $order->delivery['zone_id'] == '118') {
        $free_shipping = false;
} else {     
        $free_shipping = true;
}   */

//Check free shipping in module
$config = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREEAMOUNT_STATUS'");
$check_config = tep_db_fetch_array($config);
if($check_config['configuration_value'] == 'True'){
  $config_amount = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREEAMOUNT_AMOUNT'");
  $check_config_amount = tep_db_fetch_array($config_amount);

  //total amount check
  if($check_config_amount['configuration_value'] < $order->info['total']){
    $free_shipping = true;
  }else{
    $free_shipping = false;
  }

  //Check if true to disable for special
  $config_disable_special = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREEAMOUNT_HIDE_SPECIALS'");
  $check_config_disable_special = tep_db_fetch_array($config_disable_special);
  if($check_config_disable_special['configuration_value'] == 'True' && $free_shipping == true){
    if ($cart->count_contents() > 0) {
      $products_cart = $cart->get_products();

      for ($i=0, $n=sizeof($products_cart); $i<$n; $i++) {
        $check_option_id = array_keys($products_cart[$i]['attributes'])[0] ?? null;
        $check_options_values_id = array_values($products_cart[$i]['attributes'])[0] ?? null;
        //If product has attribute
        if(!empty($check_option_id) && !empty($check_options_values_id)){
          $id = explode("{", $products_cart[$i]['id'], 2)[0];
          $check_product_attribute = tep_db_query('select options_values_price,options_values_msrp,products_id from `products_attributes` WHERE `options_id` ='.$check_option_id.' AND `options_values_id` ='.$check_options_values_id.' and `products_id` ='. $id);
          $get_check_product_attribute = tep_db_fetch_array($check_product_attribute);
          $final_product_price = $get_check_product_attribute['options_values_price'];
          $final_product_msrp = $get_check_product_attribute['options_values_msrp'];
          //If price is 0 get product price
          if($get_check_product_attribute['options_values_price'] <= 0){
            $check_product = tep_db_query('select products_price from `products` WHERE `products_id` ='.$get_check_product_attribute['products_id']);
            $get_check_product = tep_db_fetch_array($check_product);
            $final_product_price = $get_check_product['products_price'];
          }

          //If MSRP is 0 get product MSRP
          if($get_check_product_attribute['options_values_msrp'] <= 0 ){
            $check_product = tep_db_query('select products_msrp from `products` WHERE `products_id` ='.$get_check_product_attribute['products_id']);
            $get_check_product = tep_db_fetch_array($check_product);
            $final_product_msrp = $get_check_product['products_msrp'];
          }

          if($final_product_price < $final_product_msrp){
            $free_shipping = false;
          }
        //If product has no attribute
        }else{
          $check_product_attribute = tep_db_query('select products_price,products_msrp from `products` WHERE `products_id` ='.$products_cart[$i]['id']);
          $get_check_product_attribute = tep_db_fetch_array($check_product_attribute);
          if($get_check_product_attribute['products_price'] < $get_check_product_attribute['products_msrp']){
            $free_shipping = false;
          }
        }
      }
    }
  }
}

  if (tep_count_shipping_modules() > 0) {
?>
<div class="alpha shipping-method">
<div class="checkout-heading" style="text-transform:uppercase; font-size:1.3rem; ">Shipping Method</div>
<?php
	 
	if($check_if_oversized['products_shipping_label'] == 'oversized'){ 
		echo '<div class="sup-shipping form-group">
		<div style="text-align: center; border-bottom: 1px dashed #ccc; padding-bottom: 5px; width: 50%; margin: 0px auto 15px; font-size: 1.2rem;">NOTICE</div>
		<p>We offer free shipping <strong>up to $99</strong> on most shipments within the mainland United States.  <strong>If the shipment is oversized there will be a shipping surcharge.</strong> We will always confirm with our clients to make sure they understand and accept the surcharge</p>
		<p>All shipments must be inspected by the buyer upon delivery, check over all contents before signing off on the shipment. Do not sign for your shipment if there is any damage to the contents of the shipment.</p>
    <p>If the damage is unacceptable please refuse the shipment and we will resolve the issue in a timely manner. Once you sign for the shipment the client assumes all responsibility for any damage</p></div>';
        
        echo '<div class="form-group"><input type="checkbox" id="freeeeeshipping"><span style="margin-left:10px;">I have read and agree to the above conditions</span></div>';
		 
		 echo '<div style="width:100%; border:solid 1px #DDD;display:none; padding:10px;" id="forfree" class="form-group">
   		<div style="width:30%; float:left;"><input name="shipping" value="freeamount_freeamount" type="radio" style="margin-right:5px;" id="shipping0">Free Shipping</div>
   		<div style="width:70%; float:left;">For orders of $99 or more with a maximum package weight of 50 lbs</div>
             
</div>';
	 
	}
	  
  /*  if ($free_shipping == true) {
       
?>
<div class="checkout-heading"><?php echo FREE_SHIPPING_TITLE; ?>&nbsp;<?php echo $quotes[$i]['icon']; ?></div>
<div id="defaultSelected" class="moduleRowSelected" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="selectRowEffect(this, 0)">
<?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?>
</div>
<?php
    } else { */
      $radio_buttons = 0;

      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
<div style="width:100%;" class="shipping-options">
 
 <?php  if (isset($quotes[$i]['error'])) {
        

	 	 echo $quotes[$i]['error']; 
        } else { 
          
 for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
  
    if($free_shipping == false){
      
     if (($quotes[$i]['methods'][$j]['title'] <> 'Free Shipping')){
     
	  $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
	 echo '<div class="shipping-choice form-group row">';
      
	     if ( ($n > 1) || ($n2 > 1) ) {
 
 			echo '<div class="col-xs-12 col-sm-9"><span style="float:left;">'.tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked, 'style="margin-right:8px;" id="shipping'.$j.'"').'</span>';
 			echo '<div style="float:left; width:90%;">'.$quotes[$i]['methods'][$j]['title'] . '</div>
				 </div>';
			echo '<div class="col-xs-12 col-sm-3">';
			if ( ($n > 1) || ($n2 > 1) ) {
			
			 echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)));
                   
            } else {
			 echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']);
			  
		   } echo '</div>';
	  
	  	  }  echo'</div>';
 	}
     } else {
        
         $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
	 echo '<div class="shipping-choice form-group row">';

	     if ( ($n >= 1) || ($n2 >= 1) ) {
        
 			echo '<div class="col-xs-9"><span style="float:left;">'.tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked, 'style="margin-right:8px;" id="shipping'.$j.'"').'</span>';
 			echo '<div style="float:left; width:90%;">'.$quotes[$i]['methods'][$j]['title'] . '</div>
				 </div>';
			echo '<div class="col-xs-3">';
			if ( ($n > 1) || ($n2 > 1) ) {
			
			 echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)));
                   
            } else {
			 echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']);
			  
		   } echo '</div>';
	  
	  	  }  echo'</div>';
     }
 } 
 
 		
    
  		}
			echo '</div>';									  }
/*	} */
  }
      
?>
</div>
<div class="grid_4 alpha checkout">
<div class="checkout-heading" style="display: none;"><?php echo TABLE_HEADING_COMMENTS; ?></div>

</div>
</div>
<div class="col-sm-5 ordersummary" style="float:right;">
<div class="ordersummary-inner">
 <h1 class="checkout-heading">Order Summary</h1>
 <!-- display products ordered listing  -->
		<table>
		<?php
  		if (sizeof($order->info['tax_groups']) > 1) {
		?>
			<tr>
				<td><?php echo HEADING_PRODUCTS .'<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="small edit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
				<td><?php echo HEADING_TAX; ?></td>
				<td><?php echo HEADING_TOTAL; ?></td>
			</tr>
		<?php
 		 } else {
		?>
			<tr class="checkout">
				<th class="checkout" colspan="3"><?php echo HEADING_PRODUCTS . '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="small edit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
			</tr>
		<?php
 		 }
  		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
     echo '<tr class="checkout">' . "\n" .
              
              '<td class="checkout">' . $order->products[$i]['name'];
   		 if (STOCK_CHECK == 'true') {
    		  echo tep_check_stock($order->products[$i]['id'],$products[$i][$option]['products_attributes_id'],$order->products[$i]['qty']);
   		 }
  		  if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
   		   for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
     		   echo '<br><span class="small"><em> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</em></span>';
   		   }
   		 }
    	echo '</td>' . "\n" .
    	'<td class="checkout">(' . $order->products[$i]['qty'] . ')</td>' . "\n" ;
    if (sizeof($order->info['tax_groups']) > 1) echo '<td valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
        echo '<td class="checkout subtotal">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
             '</tr>' . "\n";
  }
    
?>
		</table>
 <!-- display order totals under products ordered listing  -->
		<table class="orderconfirm-total" >              
<?php // inserted here again  
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    echo $order_total_modules->output();
  }
 
//stop inserted here again  
?>            
		</table>                        
	</div>
<div class="help" style="margin-top:5px;"><b>Need Help? Call us at (561) 427-0240</b></div>    
</div>           
<div class="clear"></div>        
<div class="grid_8 alpha checkout">
	<div class="checkout-heading"><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE; ?> </div>
<button class="button-blue-small">Continue</button>

  </form>
</div>
<div class="clear"></div>
    <script>
    $("#freeeeeshipping").click(function(e) {       
      $("#forfree").toggleClass('show');  
    })
    </script>    
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>