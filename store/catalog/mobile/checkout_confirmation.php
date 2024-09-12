<?php
require_once('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_mobile_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// unregister mobile redirectCancelled for external payment service return to mobile
	tep_session_unregister('redirectCancelled');

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }

  //kgt - discount coupons
  if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
  //this needs to be set before the order object is created, but we must process it after
  $coupon = tep_db_prepare_input($HTTP_POST_VARS['coupon']);
  //end kgt - discount coupons


// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ($payment_modules->selected_module != $payment) || ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_mobile_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

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
      tep_redirect(tep_mobile_link(FILENAME_SHOPPING_CART));
    }
  }

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

	require(DIR_MOBILE_INCLUDES . 'header.php');
	$headerTitle->write();
?>
<?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_mobile_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
?>
<div id="iphone_content">
<div id="checkout_conf">
<?php
      if (is_array($payment_modules->modules)) {
      	      echo $payment_modules->process_button();
      }
      echo tep_draw_form('checkout_confirmation', $form_action_url, 'post'); 
// Cart
echo '<table id="shopping-cart-table" data-role="table" data-mode="reflow" class="ui-body-d ui-shadow table-stripe ui-responsive table-stroke">
      	<thead>
        	<tr>';
        	
      if (sizeof($order->info['tax_groups']) > 1) {
                echo '<th>' . TABLE_HEADING_QUANTITY . '</th>'; 
		echo '<th>' . HEADING_PRODUCTS . '</th>'; 
		echo '<th>' . HEADING_TAX . '</th>'; 
		echo '<th style="text-align:right">' . HEADING_TOTAL . '</th>';
  } else {
               echo '<th>' . TABLE_HEADING_QUANTITY . '</th>'; 
               echo '<th>' . HEADING_PRODUCTS . '</th>'; 
               echo '<th style="text-align:right">' . HEADING_TOTAL . '</th>'; 
  }
  
echo '         	</tr>
         </thead>
         <tbody>';

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '<tr>' .
         '<th>' . $order->products[$i]['qty'] . '</th>' .
         '<td>' . $order->products[$i]['name'] . '';

    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }

    echo '</td>';

    if (sizeof($order->info['tax_groups']) > 1) 
	echo '<td>' . tep_display_tax_value($order->products[$i]['tax']) . '%' . '</td>';
	echo '<td style="text-align:right">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td></tr>';
  }
?>
       </tbody>
   </table>
<!-- end of cart -->
<hr />
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
  	  echo '<table id="order-totals-table" data-role="table" data-mode="none" class="ui-body-d ui-shadow table-stripe table-stroke">
  	  		<thead>
         			<tr>
         			</tr>
         		</thead>
         		<tbody>' . 
	 			$order_total_modules->output() . 
	 		'</tbody>
	 		</table>';
  	}
  	
  echo tep_button_jquery(IMAGE_BUTTON_CHANGE,tep_mobile_link(FILENAME_SHOPPING_CART, '', 'SSL'),'b','button',' data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true" ');
?>
      	<hr />
<?php 
 		echo '<h1>' . HEADING_PAYMENT_METHOD . '</h1>'; 
 		echo $order->info['payment_method'];
 		
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
      	<hr />
      <h1><?php echo HEADING_PAYMENT_INFORMATION; ?></h1>
	  <div class="form_line">
	  <?php echo $confirmation['title'];
          if (isset($confirmation['fields'])) {
          	  for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
          	  	  echo $confirmation['fields'][$i]['title'];
          	  	  echo $confirmation['fields'][$i]['field'];
          	  }
          }
      ?>
      </div>
      <?php
    }
  }
  echo tep_button_jquery(IMAGE_BUTTON_CHANGE,tep_mobile_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'),'b','button',' data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true" ');
?>
	<hr />
<?php 
	echo '<h1>' . HEADING_BILLING_ADDRESS . '</h1>'; 
	echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>');
	echo tep_button_jquery(IMAGE_BUTTON_CHANGE,tep_mobile_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'),'b','button',' data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true" ');
?>
	<hr />
<?php 
	if ($sendto != false) {
		if ($order->info['shipping_method']) {
			echo '<h1>' . HEADING_SHIPPING_METHOD . '</h1>'; 
			echo $order->info['shipping_method'];
		}
	echo tep_button_jquery(IMAGE_BUTTON_CHANGE,tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'),'b','button',' data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true" ');
?>
	<hr />
<?php 
	echo '<h1>' . HEADING_DELIVERY_ADDRESS . '</h1>'; 
	echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');
	echo tep_button_jquery(IMAGE_BUTTON_CHANGE, tep_mobile_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'),'b','button',' data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true" ');
?>
	  <hr />
<?php
	} // end of if ($sendto != false) 

  if (tep_not_null($order->info['comments'])) {
  	  echo '<h1>' . HEADING_ORDER_COMMENTS . '</h1>';
  	  echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']);
	echo tep_button_jquery(IMAGE_BUTTON_CHANGE, tep_mobile_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'),'b','button','data-icon="bars" data-mini="true" data-iconpos="right" data-inline="true"');
?>
        <hr />
<?php
  }

if (is_array($payment_modules->modules)) {
	echo $payment_modules->process_button();
}
?>
      <div id="bouton">
      <?php echo  tep_button_jquery(IMAGE_BUTTON_BACK , tep_mobile_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'), 'b' , 'button' , 'data-icon="back" data-inline="true"' );
      	    echo  tep_button_jquery(IMAGE_BUTTON_CONFIRM_ORDER , '', 'b' , 'submit' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"' ); ?>		
      </div>

</form>
</div>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
