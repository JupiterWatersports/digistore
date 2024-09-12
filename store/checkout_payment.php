<?php
/*
  $Id: checkout_payment.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
if (tep_check_stock($products[$i]['id'],$products[$i][$option]['products_attributes_id'], $products[$i]['quantity'])) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        break;
      }
if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
while (list($option, $value) = each($products[$i]['attributes'])) {
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

if (tep_check_stock_attribute($products[$i]['id'],$products[$i][$option]['products_attributes_id'], $products[$i]['quantity'])) {
tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
break;
}
}
}

}
}


// if no billing destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  } else {
// verify the selected billing address
    if ( (is_array($billto) && empty($billto)) || is_numeric($billto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] != '1') {
        $billto = $customer_default_address_id;
        if (tep_session_is_registered('payment')) tep_session_unregister('payment');
      }
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (isset($HTTP_POST_VARS['comments']) && tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }

 //if(isset($_SESSION['comments'])) $comments=$_SESSION['comments'];

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
// BrainTree
  require_once 'includes/modules/payment/lib/Braintree.php';

  $config = new Braintree\Configuration([
      'environment' => 'production',
      'merchantId' => 'mdgfgmv4dpy62jjx',
      'publicKey' => '9c428q9h5zwdcpgr',
      'privateKey' => 'a296f6e0b4b9d8aa5da877cbe5f1b65c'
  ]); 
 /* $config = new Braintree\Configuration([
    'environment' => 'sandbox',
    'merchantId' => 'nbqd6b2z7qtnjr9g',
    'publicKey' => '599t62ybhvx64pj3',
    'privateKey' => '6cdf9ac40553dd9b69f3623adcd1146a'
  ]);*/
  $gateway = new Braintree\Gateway($config);

  //echo($clientToken = $gateway->clientToken()->generate());

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
      require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
echo $doctype;
?>
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
fbq('track', 'AddPaymentInfo');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=234965059596038&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<script src="https://js.braintreegateway.com/web/dropin/1.37.0/js/dropin.min.js"></script>
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
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>

<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="" id="payment-form"'); ?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>

<div id="progressbar">
	<span class="progressbar">Delivery</span>
	<span class="progressbar-active">Payment</span>
	<span class="progressbar">Confirmation</span>
	<span class="progressbar">Finished</span>	
</div>

<div class="clear"></div>

<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
      <p class="messageStackError">
      <?php echo tep_output_string_protected($error['title']).':<br/> '.tep_output_string_protected($error['error']); ?>
      </p>
   <?php //-----   BEGINNING OF ADDITION: MATC   -----// 

if($HTTP_GET_VARS['matcerror'] == 'true'){

?>

<p><?php 

$matc_error_box_contents = array();

$matc_error_box_contents[] = array('text' => MATC_ERROR);

new errorBox($matc_error_box_contents);



?></p>

<?php } //-----   END OF ADDITION: MATC   -----// ?>
<?php
  }
?>
<?php if ($_GET['error_message']) { ?>
  <div class="alert alert-danger" role="alert" style="padding: 1rem;color: red;background-color: #ff00005e;margin-bottom: 1rem;border-radius: 15px;text-align: center;">
    <?php echo $_GET['error_message']; ?>
  </div>
<?php } ?>

<div class="checkout-payment-info">
<div class="grid_4 alpha billing-add">
 	<div class="checkout-heading"><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></div>
	<p><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?></p>
	<div class="leftfloat"><?php echo TITLE_BILLING_ADDRESS.tep_draw_separator('pixel_trans.gif', '20', '10'); ?>
	<div class="address"><?php echo tep_address_label($customer_id, $billto, true, ' ', '<br />'); ?></div></div>
	<div class="clear spacer-tall"></div> 
	<div class="right-align" style="float: right; margin-top: -77px;"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a></div>'; ?>   
	<div class="clear spacer-tall"></div>   
</div>
<!-- <div class="grid_4 alpha checkout">	
	<div class="checkout-heading">Put Something Here</div>
	<div class="spacer-tall"></div>
	<p class="info">put something here. It's about line 225 on checkout_shipping.php. This CSS class="info"</p>
	<p class="notes">put something here. It's about line 226 on checkout_shipping.php. This CSS class="notes"</p>

</div> -->

<div class="clear spacer"></div>         
      
<div class="grid_4 alpha payment-method" id="has-sig">

	<div class="checkout-heading">
		<?php echo TABLE_HEADING_PAYMENT_METHOD; ?>
	</div>
          
	<?php
  		$selection = $payment_modules->selection();
  // *** BEGIN GOOGLE CHECKOUT ***
  // Skips Google Checkout as a payment option on the payments page since that option
  // is provided in the checkout page.
  for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
    if ($selection[$i]['id'] == 'googlecheckout') {
      array_splice($selection, $i, 1);
      break;
    }
  }
  // *** END GOOGLE CHECKOUT ***



  		if (sizeof($selection) > 1) {
			} else {
		?>

	<div class="checkout-heading">
	
		<?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?>
	</div>
	<div>
  </div>
	<?php
  	}
    echo tep_draw_hidden_field('payment_type');
    echo tep_draw_hidden_field('payment_nonce');
    echo tep_draw_hidden_field('payment_detail');
	?>
<?php
  // Discount Code 2.9 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
?>
    <div id="dropin-container"></div>
    <div class="main form-group"><input class="form-control" type="text" name="discount_code" value="<?php echo $sess_discount_code; ?>" placeholder="Enter Discount Code" style="width:200px; display:inline-block; border-radius: 5px 0px 0px 5px; height:35px;"><button class="button-blue-small" style="display:inline-block; border-radius: 0px 5px 5px 0px">Apply</button></div>

<?php
  }
  // Discount Code 2.9 - end
?>
</div>
<div class="grid_4 alpha checkout" style="display:none;">
<div class="checkout-heading"><?php echo TABLE_HEADING_COMMENTS; ?></div>
<?php echo tep_draw_textarea_field('comments', 'soft', '37', '8',$comments); ?>
</div>
<div class="clear spacer"></div> 
 		

</div>

<div class="grid_4 alpha ordersummary">
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
				
<div class="clear"></div>

</div>
<div class="clear"></div>
<div class="grid_4 continue-check checkout">

	<h1 class="checkout-heading">
	<?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE;?>
	</h1>
			<div class="alpha" ><button id= "submit-payment" class="button-blue-small">Continue</button></div> </form>     </div> 
      <script>
        var form = document.querySelector('#payment-form');
        braintree.dropin.create({
          authorization:  "<?php echo $gateway->clientToken()->generate() ?>",
          container: '#dropin-container',
          paymentOptionPriority: ['card', 'paypal', 'paypalCredit'],
          paypal: {
            flow: 'checkout',
            amount: '<?php echo $order->info['total']; ?>',
            currency: 'USD'
          }
        }, function (err, dropinInstance) {
          if (err) {
            // Handle any errors that might've occurred when creating Drop-in
            console.error(err);
            return;
          }
          form.addEventListener('submit', function (event) {
            event.preventDefault();

            dropinInstance.requestPaymentMethod(function (err, payload) {
              if (err) {
                // Handle errors in requesting payment method
                return;
              }
              console.log(payload)
              // Send payload.nonce to your server
              $('[name="payment_nonce"').val(payload.nonce);
              $('[name="payment_type"').val(payload.type);
              if(payload.details.type == 'PayPalAccount'){
                $('[name="payment_detail"').val(payload.details.email);
              }else if(payload.details.type == 'VenmoAccount') {
                $('[name="payment_detail"').val(payload.details.username);
              }else if(payload.details.type == 'AndroidPayCard') {
                $('[name="payment_detail"').val(payload.details.cardType);
              }else {
                $('[name="payment_detail"').val(payload.details.cardType);
              }
              form.submit();
            });
          });
        });
      </script>  
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
