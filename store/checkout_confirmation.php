<?php
/*
  $Id: checkout_confirmation.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    //tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    //$payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

/* BOF Bundled Products
// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }
  EOF Bundled Products */

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
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
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=234965059596038&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
	<style>@media(min-width: 768px){.checkout-info-summary{float:left; width: 58.333333333333%;}
		.checkout-info-summary, .ordersummary{position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px;}
		.container_12 .ordersummary{width: 41.666666666667%; margin-right: 0px;}
		}
	.col-sm-3.control-label {padding: 7px; font-size:1rem;}
		.expiration select{width:30%; display:inline-block; margin-right: 10px;}
		.accepted-icons{float:left; width:13%; padding:5px;}
		.accepted-icons img{width:100%; height:auto;}
		#checkout-container{font-size:1rem;}
		.secure-checkout{margin-top: 30px; border-bottom: 1px solid #bbb; padding-bottom: 15px;}
		 a.tooltip:hover{color:#000;}
		 a.tooltip span {z-index:10;display:none; padding:14px 20px; margin-top:-30px; margin-left:28px; width:300px; line-height:1.2rem !important; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;}
		a.tooltip:hover span{display:inline;}
		a.tooltip:hover span img{width:100%;}
		@media(max-width:768px){a.tooltip:hover span{left:0px; margin-left:0px; margin-top: 20px;}
		#checkout-container .ordersummary, .checkout-info-summary{width:100%; float:left;}}
		input, select, textarea {font-size:1rem !important;}
		textarea.form-control {height: auto;}
		.date-fields .form-control {
			width: auto;
			display: inline-block;
		}

</style>
 
<script>
var submitted = false;

function check_form(form) {
var error = 0;
  var error_message = "<?php echo 'Please fill out the required boxes'; ?>";
  if(submitted){ 
    alert( "<?php echo 'Please fill out the required boxes'; ?>"); 
    return false; 
  }
   
	if (form.cc_number_nh_dns.value == 0) {
	  $('#paypal_card_num').css('border', '1px solid #FF0000');
      error = 1;
    }
    if (form.cc_expires_month.value == '') {
      error_message = error_message + "<?php echo '*You must have a MSRP\n'; ?>";
	  $('#msrp').css('border', '1px solid #FF0000');
      error = 1;
    }

    if (form.cc_expires_year.value == '') {
      error_message = error_message + "<?php echo '*You must have a Product Price\n'; ?>";
	  $('#price').css('border', '1px solid #FF0000');
      error = 1;
    }
	
	if (form.cc_cvc_nh_dns.value == 0) {
	  $('#cvv2').css('border', '1px solid #FF0000');
      error = 1;
    }
	
	
	
	

  if (error == 1) { 
    alert(error_message); 
    return false; 
  } else { 
    submitted = true; 
    return true; 
  } 
}</script> 
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>

<?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
 
     
     
     
?>
<script src="js/tooltip.js" type="text/javascript"></script>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>
<!--progressbar-->
<div id="progressbar">
	<span class="progressbar">Delivery</span>
	<span class="progressbar">Payment</span>
	<span class="progressbar-active">Confirmation</span>
	<span class="progressbar">Finished</span>	
</div>

<div class="clear"></div>  
<?php	
 if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<div class="header-error form-horizontal">
	<h4 style="color:#E61616;"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></h4>
</div>  
<?php
  }
?>	
	
<div class="secure-checkout form-group col-xs-12"><img src="images/secure-icon.jpg" style="float:left; width:40px; height:auto;"><span style="float:left; padding-left: 10px; padding-top:25px; font-weight: 700;">Secure Checkout</span></div>

<div class="grid_4 alpha ordersummary">
<div class="ordersummary-inner">
 <h1 class="checkout-heading">Order Summary</h1>
<!-- display products ordered listing  -->
		<table>
		<?php
  			if (sizeof($order->info['tax_groups']) > 1) {
		?>
			<tr>
				<td><?php echo HEADING_PRODUCTS .'<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="small">(' . TEXT_EDIT . ')</span></a>'; ?></td>
				<td><?php echo HEADING_TAX; ?></td>
				<td><?php echo HEADING_TOTAL; ?></td>
			</tr>
		<?php
 		 } else {
		?>
			<tr class="checkout">
				<th class="checkout" colspan="3"><?php echo HEADING_PRODUCTS . '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="small">(' . TEXT_EDIT . ')</span></a>'; ?></td>
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
         </div>
	


<div class="checkout-info-summary">
<div class="row">
<div class="col-xs-12 checkout-info">
	<?php
  	if ($sendto != false) {
	?>
	<!-- display delivery address and shipping method -->
	<h4>
	 <?php echo 'Shipping Address<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"> <span class="small">(' . TEXT_EDIT . ')</span></a>'; ?>
	</h4>
	<p style="margin-bottom:0">
		<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>
	</p>
</div>

<div class="col-xs-12 checkout-info">
	<!-- display billing address and cc method -->
	<h4>
			
		<?php echo  HEADING_BILLING_ADDRESS . '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">  <span class="small">(' . TEXT_EDIT . ')</span></a>'; ?>		
	</h4>
	<p style="margin-bottom:0">
		<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?>	
	</p>	
</div>
</div>

<div class="row">	
<div class="col-xs-12 checkout-info">
	<?php
    	if ($order->info['shipping_method']) {
	?>
	<h4>
		<?php echo HEADING_SHIPPING_METHOD . '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"> <span class="small">(' . TEXT_EDIT . ')</span></a>'; ?>
	</h4>
	<p style="margin-bottom:0">
	<?php
		if($order->info['shipping_method'] == 'Free Shipping (Free Shipping)'){
			echo 'Free Shipping';
		} else {
			echo $order->info['shipping_method'];} 
    	}
	  }
	?>
	</p>
</div> 


<div class="col-xs-12 checkout-info">		
		<h4>
			<?php echo  HEADING_PAYMENT_METHOD . '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"> <span class="small">(' . TEXT_EDIT . ')</span></a>'; ?>		
		</h4>	
		
		<p style="margin-bottom:0">
			<?php 
				echo tep_draw_hidden_field('payment_nonce',$_POST['payment_nonce']);
				if($_POST['payment_type'] == 'CreditCard'){
					echo tep_draw_hidden_field('payment_type','Credit Card');
					echo'Credit Card';
				} elseif ($_POST['payment_type'] == 'PayPalAccount') {
					echo tep_draw_hidden_field('payment_type','Paypal Account');
					echo 'Paypal Account';
				} elseif ($_POST['payment_type'] == 'VenmoAccount') {
					echo tep_draw_hidden_field('payment_type','Venmo Account');
					echo 'Venmo Account';
				} elseif ($_POST['payment_type'] == 'AndroidPayCard') {
					echo tep_draw_hidden_field('payment_type','Google Pay');
					echo 'Google Pay';
				} else {
					echo tep_draw_hidden_field('payment_type','Apple Pay');
					echo 'Apple Pay';
				}
			?>
		</p>
		<p style="margin-bottom:0"><?php echo $_POST['payment_detail']; ?><p>
		   
</div>
	</div>

<div class="row">
<div class="col-xs-12 checkout-info" style="display: block !important;">
		       
	<?php
		if (is_array($payment_modules->modules)) {
			if ($confirmation = $payment_modules->confirmation()) {
	?>
	<div class="checkout-heading" style="margin-top:30px;">
	<?php echo HEADING_PAYMENT_INFORMATION; ?>
	</div>
	
	<?php echo $confirmation['title']; 
				if(is_array($confirmation['fields'])){
					$n = sizeof($confirmation['fields']);
				}
				
				for ($i=0; $i<$n; $i++) {
	?>
			<tr class="orderconfirm-payment">                
				<td class="orderconfirm-payment"><?php echo $confirmation['fields'][$i]['title']; ?></td>                
				<td class="orderconfirm-payment"><?php echo $confirmation['fields'][$i]['field']; ?></td>
			</tr>
	<?php
				}
			}
		}
?>
	</div>
	</div>
<div class="row">
	<div class="col-xs-12">
	
		<h4>Comments</h4>
		<?php echo tep_draw_textarea_field('comments', 'soft','', '4',$comments, 'class="form-control"'); ?>
	</div>	
</div>

<?php
  if (is_array($payment_modules->modules)) {
    //echo $payment_modules->process_button();
  }


		
?>
 <div class="row" style="padding-top:0.5rem;">     
	<div class="col-xs-12 form-group">
		<button class="button-blue-small">Confirm Order</button>
		<label style="color:red;">ONLY CLICK ONCE</label>
	</div>
 </div>
<div class="clear"></div>
</div>
</form>

<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
