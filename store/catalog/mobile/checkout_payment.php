<?php
require_once('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_mobile_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        tep_redirect(tep_mobile_link(FILENAME_SHOPPING_CART));
        break;
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

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (isset($HTTP_POST_VARS['comments']) && tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_mobile_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<?php echo $payment_modules->javascript_validation(); ?>
<div id="iphone_content">
<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"', true); ?>
<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
<span class="messageStackWarning">
<?php echo tep_output_string_protected($error['title']); ?>
<?php echo tep_output_string_protected($error['error']); ?>
</span>
<?php
  }
?>
<div id="checkout_payment">
<h1><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h1>
<br/>
<?php
  $selection = $payment_modules->selection();

  if (sizeof($selection) > 1) {
?>

  <div class="bill_add">
    <?php echo TEXT_SELECT_PAYMENT_METHOD; ?>
<br/>
<br/>

<?php
    } elseif ($free_shipping == false) {
?>

  <div class="bill_add">
    <?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?>
<br/>
<br/>

<?php
    }
?>


<fieldset data-role="controlgroup" data-theme="a" id ="custom-fieldset">
<?php
  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
	if (sizeof($selection) > 0) {
		if ( ($selection[$i]['id'] == $payment)  ) 
       		$checked = true;
		else 
      		$checked = false;	
      		echo tep_radio_jquery('payment',$checked,'a',$selection[$i]['id'],'id="'.$selection[$i]['id'].'"') ;
      	} else {
      		echo tep_draw_hidden_field('payment', $selection[$i]['id']);
    	}
?>
<label class="<?php echo $selection[$i][id]?>" for="<?php echo $selection[$i]['id'];?>"><?php echo $selection[$i]['module']; ?></label>
<?php
    if (isset($selection[$i]['error'])) {
        echo $selection[$i]['error'];
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
    	    echo '<br />';
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
            echo $selection[$i]['fields'][$j]['title'];
            echo $selection[$i]['fields'][$j]['field'];
      }
          echo '<br /><br />';
    }

    $radio_buttons++;
  }
?>
</fieldset>
  </div>
      <hr />
	<h1><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></h1>
	<div class="bill_add">
	  <?php echo tep_address_label($customer_id, $billto, true, ' ', '<br/>');
	        echo tep_button_jquery(IMAGE_BUTTON_CHANGE_ADDRESS , tep_mobile_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'b' , 'button' , 'data-icon="check" data-inline="false"' ); ?>
     </div>
      <hr />
      <h1><?php echo TABLE_HEADING_COMMENTS; ?></h1>
      <div class="bill_add">
	  <?php echo tep_draw_textarea_field('comments', 'soft', '40', '6', '', 'data-theme="a"'); ?>
      </div>
      <div id="bouton">
      <?php echo  tep_button_jquery(IMAGE_BUTTON_BACK , tep_mobile_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'), 'b' , 'button' , 'data-icon="back" data-inline="true"' );
      	    echo  tep_button_jquery(IMAGE_BUTTON_CONTINUE , '', 'b' , 'submit' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"' ); ?>		
      </div>
</div>
</form>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
