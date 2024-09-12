<?php
/*
  $Id: checkout_process.php 1750 2007-12-21 05:20:28Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');
  
  $rip = $_SERVER["REMOTE_ADDR"];
  $rclient = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
  $str = preg_split("/\./", $rclient);
  $i = count($str);
  $x = $i - 1;
  $n = $i - 2;
  $risp = $str[$n] . "." . $str[$x];


// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping') || !tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    //tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

/* One Page Checkout - BEGIN */
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      require('includes/classes/onepage_checkout.php');
      $onePageCheckout = new osC_onePageCheckout();
  }
/* One Page Checkout - END */

// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

 //print_r($_POST); 
// print_r($GLOBALS); 
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

/* One Page Checkout - BEGIN */
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $onePageCheckout->loadSessionVars();
      $onePageCheckout->fixTaxes();

      /*
       * This error report is due to the fact that we cannot duplicate some errors.
       * please forward this email always if you recieve it
       */
      if ($order->customer['email_address'] == '' || $order->customer['firstname'] == '' || $order->billing['firstname'] == '' || $order->delivery['firstname'] == ''){
      	ob_start();
      	echo 'ONEPAGE::' . serialize($onepage);
      	echo 'SESSION::' . serialize($_SESSION);
      	echo 'SERVER::' . serialize($_SERVER);
      	echo 'ORDER::' . serialize($order);
      	$content = ob_get_contents();
      	mail(ONEPAGE_DEBUG_EMAIL_ADDRESS, 'Order Error: Please forward to juipter kiteboarding', $content);
      	unset($content);
      	ob_end_clean();
      }
  }
/* One Page Checkout - END */
  

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['attr'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }


  //$payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    //tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

 if(isset($_SESSION['comments']) && !$order->info['comments']) $order->info['comments']=$_SESSION['comments'];
 
 	$mystring = $order->delivery['state'];
	$word = 'Florida';

	if($order->delivery['state'] == 'Florida (Palm Beach County)'){
		$delivery_location = 1;
	} elseif(strpos($mystring, $word) !== false) {
		$delivery_location = 2;
	} else {
		$delivery_location = 3;
	}

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();
  // signifyd verification
  include('includes/functions/signifyd_validation.php');
  //$payment_modules->before_process();
  //date_purchase will be considered tomorrow if after office hours
  date_default_timezone_set('America/New_York');
  $date_purchased = Date('Y-m-d H:i:s');
  if((int)date("N") == 2 ) {
    if((int)date("H") > 15 ) {
      $date_purchased = Date('Y-m-d H:i:s', strtotime('+1 days'));
    }
  }else{
    if((int)date("H") > 16 ) {
      $date_purchased = Date('Y-m-d H:i:s', strtotime('+1 days'));
    }
  }
  $total_payment_amount = 0;
  $total_payment_tax = 0;
  $total_payment_subtotal = "";
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    if($order_totals[$i]['code'] == 'ot_total') {
      $total_payment_amount = $order_totals[$i]['value'];
    }
    if($order_totals[$i]['code'] == 'ot_tax') {
      $total_payment_tax = $order_totals[$i]['value'];
    }
    if($order_totals[$i]['code'] == 'ot_subtotal') {
      $total_payment_subtotal = $order_totals[$i]['value'];
    }
  }
  //payment history insert
  $tax_rate = ($total_payment_tax/$total_payment_subtotal)*100;
  // BrainTree
  require_once 'includes/modules/payment/lib/Braintree.php';

  $config = new Braintree\Configuration([
    'environment' => 'production',
    'merchantId' => 'mdgfgmv4dpy62jjx',
    'publicKey' => '9c428q9h5zwdcpgr',
    'privateKey' => 'a296f6e0b4b9d8aa5da877cbe5f1b65c'
  ]); 

  $gateway = new Braintree\Gateway($config); 

  $result = $gateway->transaction()->sale([
    'amount' => (string)round($total_payment_amount,2),
    'paymentMethodNonce' => $_POST['payment_nonce'],
  ]);
  //var_dump($result);
  if (!$result->success) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode('Error with the chosen payment method, please try another payment method'), 'SSL'));
  } else {
    $sql_order_data_array = array('customers_id' => $customer_id,
      'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
      'customers_company' => $order->customer['company'],
      'customers_street_address' => $order->customer['street_address'],
      'customers_suburb' => $order->customer['suburb'],
      'customers_city' => $order->customer['city'],
      'customers_postcode' => $order->customer['postcode'], 
      'customers_state' => $order->customer['state'], 
      'customers_country' => $order->customer['country']['title'], 
      'customers_telephone' => $order->customer['telephone'], 
      'customers_email_address' => $order->customer['email_address'],
      'customers_address_format_id' => $order->customer['format_id'], 
  // PWA BOF
      'customers_dummy_account' => '0', 
  // PWA EOF
      'delivery_name' => trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
      'delivery_company' => $order->delivery['company'],
      'delivery_street_address' => $order->delivery['street_address'], 
      'delivery_suburb' => $order->delivery['suburb'], 
      'delivery_city' => $order->delivery['city'], 
      'delivery_postcode' => $order->delivery['postcode'], 
      'delivery_state' => $order->delivery['state'], 
      'delivery_country' => $order->delivery['country']['title'], 
      'delivery_address_format_id' => $order->delivery['format_id'], 
      'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
      'billing_company' => $order->billing['company'],
      'billing_street_address' => $order->billing['street_address'], 
      'billing_suburb' => $order->billing['suburb'], 
      'billing_city' => $order->billing['city'], 
      'billing_postcode' => $order->billing['postcode'], 
      'billing_state' => $order->billing['state'], 
      'billing_country' => $order->billing['country']['title'], 
      'billing_address_format_id' => $order->billing['format_id'], 
      'payment_method' => $_POST['payment_type'], 
      'shipping_module' => $shipping['id'],
      'cc_type' => $order->info['cc_type'],
      'cc_owner' => $order->info['cc_owner'], 
    //  'cc_number' => $order->info['cc_number'], 
  //   'cc_exp_month' => $order->info['cc_exp_month'], 
    //  'cc_exp_year' => $order->info['cc_exp_year'], 
      'cc_expires' => $order->info['cc_expires'], 
      'cc_cvv' => $order->info['cc_cvv'], 
      'date_purchased' => $date_purchased, 
      'orders_status' => 6, 
      'currency' => $order->info['currency'], 
      'currency_value' => $order->info['currency_value'] ?? 1,
      'ipaddy' => $rip,
      'ipisp' => $risp,
      'signifyd_checkoutID'=>$_SESSION['checkoutId'],
      'signifyd_sessionid' =>$_SESSION['tempsessid'],
      'signifydId' =>$_SESSION['signifydId'],
      'payment_signature' => '',
      'payment_nonce' => $result->transaction->id ?? $_POST['payment_nonce'],
      'total_payment_authorized' => $total_payment_amount,
      'total_tax_rate_authorized' => $tax_rate,
      'total_tax_value_authorized' => $total_payment_tax,
      'payment_comments' => utf8_decode($_POST['comments']) ?? '',
      'delivery_location' => $delivery_location);
    tep_db_perform(TABLE_ORDERS, $sql_order_data_array);
    $insert_id = tep_db_insert_id();

    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $sql_data_array = array('orders_id' => $insert_id,
                              'title' => $order_totals[$i]['title'],
                              'text' => $order_totals[$i]['text'],
                              'value' => $order_totals[$i]['value'], 
                              'class' => $order_totals[$i]['code'], 
                              'sort_order' => $order_totals[$i]['sort_order']);
      tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
    }

    
    $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
    $sql_data_array = array('orders_id' => $insert_id, 
                            'orders_status_id' => $order->info['order_status'], 
                            'date_added' => 'now()', 
                            'customer_notified' => $customer_notification,
                            'comments' => $_POST['comments']);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

  //  $sql_data_array = array(
     // 'orders_id' => $insert_id, 
     // 'date_paid' => 'now()', 
     // 'payment_type_id' => 6,
      //'payment_value' => $total_payment_amount,
      //'tax_rate' => $tax_rate,
     // 'tax_value' => $total_payment_tax,
     // 'payment_comments' => $_POST['comments']
    //);
    //tep_db_perform('orders_payment_history', $sql_data_array);
  }
// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

// begin product bundles
  function reduce_bundle_stock($bundle_id, $qty_sold) {
    $bundle_query = tep_db_query('select pb.subproduct_id, pb.subproduct_qty, p.products_bundle, p.products_quantity from ' . TABLE_PRODUCTS_BUNDLES . ' pb, ' . TABLE_PRODUCTS . ' p where p.products_id = pb.subproduct_id and bundle_id = ' . (int)tep_get_prid($bundle_id));
    while ($bundle_info = tep_db_fetch_array($bundle_query)) {
      if ($bundle_info['products_bundle'] == 'yes') {
        reduce_bundle_stock($bundle_info['subproduct_id'], ($qty_sold * $bundle_info['subproduct_qty']));
        // update quantity of nested bundle sold
        tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', ($qty_sold * $bundle_info['subproduct_qty'])) . " where products_id = " . (int)$bundle_info['subproduct_id']); 
      } else {
        $bundle_stock_left = $bundle_info['products_quantity'] - ($qty_sold * $bundle_info['subproduct_qty']);
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = " . (int)$bundle_stock_left . ", products_ordered = products_ordered + " . (int)($qty_sold * $bundle_info['subproduct_qty']) . " where products_id = " . (int)$bundle_info['subproduct_id']);
		tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = '" . $stock_attr_left . "' where products_attributes_id = '" . $stock_values['products_attributes_id'] . "'");
        if ( ($bundle_stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = " . (int)$bundle_info['subproduct_id']);
        }
      } //end if products bundle yes
    }
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
$stock_query_raw = "SELECT products_quantity, pa.products_attributes_id, pa.options_quantity, pad.products_attributes_filename
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
		  $stock_query = tep_db_query("SELECT products_quantity FROM products WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
/*$stock_query = tep_db_query("SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id = pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "' and");*/
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
        if ($stock_values['products_bundle'] == 'yes') {
        // order item is a bundle and must be separated
          $report_text .= "Bundle found in order : " . tep_get_prid($order->products[$i]['id']) . "<br />\n";
          $bundle_query = tep_db_query("select pb.subproduct_id, pb.subproduct_qty, p.products_model, p.products_quantity, p.products_bundle 
          from " . TABLE_PRODUCTS_BUNDLES . " pb 
          LEFT JOIN " . TABLE_PRODUCTS . " p 
          ON p.products_id=pb.subproduct_id 
          where pb.bundle_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

          while ($bundle_data = tep_db_fetch_array($bundle_query)) {
            if ($bundle_data['products_bundle'] == "yes") {
              $report_text .= "<br />level 2 bundle found in order : " . $bundle_data['products_model'] . "<br />";
              $bundle_query_nested = tep_db_query("select pb.subproduct_id, pb.subproduct_qty, p.products_model, p.products_quantity, p.products_bundle 
              from " . TABLE_PRODUCTS_BUNDLES . " pb 
              LEFT JOIN " . TABLE_PRODUCTS . " p 
              ON p.products_id=pb.subproduct_id 
              where pb.bundle_id = '" . $bundle_data['subproduct_id'] . "'");
              while ($bundle_data_nested = tep_db_fetch_array($bundle_query_nested)) {
                $stock_left = $bundle_data_nested['products_quantity'] - $bundle_data_nested['subproduct_qty'] * $order->products[$i]['qty'];
                $report_text .= "updating level 2 item " . $bundle_data_nested['products_model'] . " : was " . $bundle_data_nested['products_quantity'] . " and number ordered is " . ($bundle_data_nested['subproduct_qty'] * $order->products[$i]['qty']) . " <br />\n";
                tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . $bundle_data_nested['subproduct_id'] . "'");
              }
            } else {
              $stock_left = $bundle_data['products_quantity'] - $bundle_data['subproduct_qty'] * $order->products[$i]['qty'];
              $report_text .= "updating level 1 item " . $bundle_data['products_model'] . " : was " . $bundle_data['products_quantity'] . " and number ordered is " . ($bundle_data['subproduct_qty'] * $order->products[$i]['qty']) . " <br />\n";
              tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . $bundle_data['subproduct_id'] . "'");
            }
          }
        } else {
          // order item is normal and should be treated as such
          $report_text .= "Normal product found in order : " . tep_get_prid($order->products[$i]['id']) . "\n";
          // do not decrement quantities if products_attributes_filename exists
          if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
			  $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
			  $stock_attr_left = $stock_values['options_quantity'] - $order->products[$i]['qty'];
          } else {
			  $stock_left = $stock_values['products_quantity'];
          }
			tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
			
			if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
				tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        } 
      }
    }
    //EOF Bundled Products
// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
							'products_msrp' => $order->products[$i]['msrp'],
                            'products_price' => $order->products[$i]['price'], 
                            'final_price' => $order->products[$i]['final_price'], 
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty']);
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_quantity, pa.options_serial_no, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix,  pa.options_serial_no, pa.options_quantity, pa.products_attributes_id from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "' ORDER BY pa.options_quantity DESC");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'], 
                                'options_values_price' => $attributes_values['options_values_price'],
								'products_attributes_id' => '',
                                'price_prefix' => $attributes_values['price_prefix'],
								'serial_no' => $attributes_values['options_serial_no']);
		  
		  $new_quantity = $attributes_values['options_quantity'] - $order->products[$i]['qty']; 
		  
		  tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = '" . $new_quantity . "' where products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'");
		  tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;
    //BEGIN SEND HTML MAIL//
    $orders_picture_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$order->products[$i]['id'] . "'");
    $orders_picture=tep_db_fetch_array($orders_picture_query);
    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n"."<br />";
    $products_quantity .= nl2br($order->products[$i]['qty'] . "\n");
	$products_name .= nl2br("" . $order->products[$i]['name'] . $products_ordered_attributes ."\n");
	
	if (!tep_not_null($order->products[$i]['model'])) {
       $products_model .= ''.EMAIL_NO_MODEL.'' ;
         }
    else{
       $products_model .= nl2br($order->products[$i]['model'] . "\n");
        }; 
            
	 $orderarray[$i] = array("Image" => "<img src=".HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $orders_picture['products_image']." width='90px' border='0'>",
					     "Model" => nl2br("" . $order->products[$i]['name'] . $products_ordered_attributes . "\n" ),
					     "Modelnr"=> nl2br($order->products[$i]['model'] . "\n"),
					     "Qty" => nl2br($order->products[$i]['qty'] . "\n"),
					     "Unitprice" => nl2br($currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], 1)  . "\n"),
					     "Tax" => nl2br($order->products[$i]['tax'] ."\n"),
					     "Price" => nl2br($currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty'])  . "\n"));
							 
}
 
  // Discount Code 2.9 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
    if (!empty($discount)) {
      $discount_codes_query = tep_db_query("select discount_codes_id from " . TABLE_DISCOUNT_CODES . " where discount_codes = '" . tep_db_input($sess_discount_code) . "'");
      $discount_codes = tep_db_fetch_array($discount_codes_query);
      if(!empty($discount_codes)){
        tep_db_perform(TABLE_CUSTOMERS_TO_DISCOUNT_CODES, array('customers_id' => $customer_id, 'discount_codes_id' => $discount_codes['discount_codes_id']));
        tep_db_query("update " . TABLE_DISCOUNT_CODES . " set number_of_orders = number_of_orders + 1 where discount_codes_id = '" . (int)$discount_codes['discount_codes_id'] . "'");
  
        tep_session_unregister('sess_discount_code');
      }
    }
  }
  // Discount Code 2.9 - end
// Get 1 free
    // If this product qualifies for free product(s) add the free products
    if (is_array ($free_product = $cart->get1free ($products_id))) {
      // Update products_ordered (for bestsellers list)
      //   comment out the next line if you don't want free products in the bestseller list
      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $free_product['quantity']) . " where products_id = '" . tep_get_prid($free_product['id']) . "'");

      $sql_data_array = array('orders_id' => $insert_id,
                              'products_id' => $free_product['id'],
                              'products_model' => $free_product['model'],
                              'products_name' => $free_product['name'],
                              'products_price' => 0,
                              'final_price' => 0,
                              'products_tax' => '',
                              'products_quantity' => $free_product['quantity']
                             );
      tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

      $total_weight += ($free_product['quantity'] * $free_product['weight']);
    }
// end Get 1 free


for ($i=0; $i<sizeof($order_totals); $i++) {
    $Vartaxe .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }


if ($order->content_type != 'virtual') {
    $Varaddress .= tep_address_label($customer_id, $sendto, 0, '', "\n") ;
  }

    if (is_object($$payment)) {
    $Varmodepay .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    $Varmodpay .= $_POST['payment_type'] . "\n";
  }

$Varlogo = ''.VARLOGO.'' ;
$Vartable1 = ''.VARTABLE1.'' ;
$Vartable2 = ''.VARTABLE2.'' ;

$Vartext1 = ' <b>' . EMAIL_TEXT_DEAR . ' ' . $order->customer['firstname'] . ' ' . $order->customer['lastname'] .' </b><br />' . EMAIL_MESSAGE_GREETING ;
$Vartext2 = '    ' . EMAIL_TEXT_ORDER_NUMBER . ' <STRONG> ' . $insert_id . '</STRONG><br />' . EMAIL_TEXT_DATE_ORDERED . ': <strong>' . strftime(DATE_FORMAT_LONG) . '</strong><br /><a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'account_history_info.php?order_id=' . $insert_id .'">' . EMAIL_TEXT_INVOICE_URL . '</a>' ; 

$Varmailfooter = ''.VARMAILFOOTER.'' ;
$VarArticles= ''.EMAIL_TEXT_PRODUCTS_ARTICLES.'' ;
$VarModele= ''.EMAIL_TEXT_PRODUCTS_MODELE.'' ;
$VarQte= ''.EMAIL_TEXT_PRODUCTS_QTY .'' ;
$VarTotal= ''.EMAIL_TEXT_TOTAL.'' ;
$VarAddresship = ''.EMAIL_TEXT_DELIVERY_ADDRESS.'' ;
$VarAddressbill = ''.EMAIL_TEXT_BILLING_ADDRESS.'' ;
$Varmetodpaye = ''.EMAIL_TEXT_PAYMENT_METHOD.'' ;
$Vardetail = ''.DETAIL .'' ;
$Varhttp = ''.VARHTTP.'';
$Varstyle = ''.VARSTYLE.'';
$Varshipaddress =''.tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />').'';
$Varadpay =''.tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />').'';



require(DIR_WS_MODULES . 'email/html_checkout_process.php');
$email_order = $html_email_order ;


// lets start with the email confirmation
if (EMAIL_USE_HTML == 'true') {
	$email_order;
} else {
	$email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
	
	if (isset($order->info['comments'])) {
		$email_order .= tep_db_output($order->info['comments']) . "\n\n";
	}
	
	$email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

/* One Page Checkout - BEGIN */
  $sendToFormatted = tep_address_label($customer_id, $sendto, 0, '', "\n");
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $sendToFormatted = $onePageCheckout->getAddressFormatted('sendto');
  }

  $billToFormatted = tep_address_format($order->billing['format_id'], $order->billing, 1, '', "\n");
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $billToFormatted = $onePageCheckout->getAddressFormatted('billto');
  }
/* One Page Checkout - END */
  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    EMAIL_SEPARATOR . "\n" .
                    $sendToFormatted . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .

                  EMAIL_SEPARATOR . "\n" .
                  $billToFormatted . "\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    $email_order .= $_POST['payment_type'] . "\n\n\n\n\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_FOOTER . "\n" .
                        HTTP_SERVER . DIR_WS_CATALOG . "\n" . 
                        EMAIL_TEXT_FOOTER . "\n" ;

 }

//*******start mail manager****************//
if (file_exists(DIR_WS_MODULES.'mail_manager/order_confirm.php')){
include(DIR_WS_MODULES.'mail_manager/order_confirm.php');
}else{
 tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
}
//*******end mail manager*****************//
  //$email_order=ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $email_order);

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
$email_order = $html_email_order ;
if (EMAIL_USE_HTML == 'true') {

$email_order;

} else {

$email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";

  for ($i=0; $i<sizeof($order_totals); $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

/* One Page Checkout - BEGIN */
  $sendToFormatted = tep_address_label($customer_id, $sendto, 0, '', "\n");
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $sendToFormatted = $onePageCheckout->getAddressFormatted('sendto');
  }

  $billToFormatted = tep_address_format($order->billing['format_id'], $order->billing, 1, '', "\n");
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $billToFormatted = $onePageCheckout->getAddressFormatted('billto');
  }
/* One Page Checkout - END */
  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    EMAIL_SEPARATOR . "\n" .
                     $sendToFormatted . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .

                  EMAIL_SEPARATOR . "\n" .
                    $billToFormatted . "\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    $email_order .= $_POST['payment_type'] . "\n\n\n\n\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_FOOTER . "\n" .
                        HTTP_SERVER . DIR_WS_CATALOG . "\n" . 
                        EMAIL_TEXT_FOOTERR . "\n" ;

}
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
    $email_order=ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $email_order);
  } 

//END SEND HTML MAIL//

  // Include OSC-AFFILIATE
  //require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');

$order_id = $insert_id;
// load the after_process function from the payment modules
  //$payment_modules->after_process();

  $cart->reset(true);

/* One Page Checkout - BEGIN */
  if (ONEPAGE_CHECKOUT_ENABLED == 'True'){
      $onepage['info']['order_id'] = $insert_id;
  }
/* One Page Checkout - END */

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('shipping_quotes'); // add this line
  tep_session_unregister('payment');
  tep_session_unregister('comments');
 
  // PWA BOF 2b
  if (tep_session_is_registered('customer_is_guest')){
    //delete the temporary account
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  }
  // PWA EOF 2b

	// coupons addon start
  	if (isset($ot_coupon) && is_object($ot_coupon) && $ot_coupon->redeem==true) {
   	tep_db_query("insert into " . TABLE_COUPONS_SALES . " (coupons_code, customers_id, orders_id, date_purchased) values ('" . tep_db_input($ot_coupon->coupons_code) . "', '" . (int)$customer_id . "', '" . (int)$insert_id . "', now())");
    	tep_session_unregister('coupon_code_code');
    	tep_session_unregister('coupon_code_value');
  	}
	// coupons addon end
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
