<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-nsimmerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License http://www.gnu.org/licenses/
  
    Order Editor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
  
  Much of Order Editor 5.x is based on the order editing file found within the MOECTOE Suite Public Betas written by Josh DeChant
  
  Many, many people have contributed to Order Editor in many, many ways.  Thanks go to all- it is truly a community project.  
  
*/
  $logo_name = 'https://jupiterkiteboarding.com/store/images/jkb-logo-black.png';

  require('includes/application_top.php');
  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');

 $_SESSION['disable_slow_shipping']=1;  
  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $act = $_GET['act'];
 $barcode = $_GET["ref"];
 //orders status
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name 
                                       FROM " . TABLE_ORDERS_STATUS . " 
									   WHERE language_id = '" . (int)$languages_id . "' order by orders_status_name ASC");
									   
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  
 //payment status
  $payment_statuses = array();
  $payment_status_array = array();
  $payment_status_query = tep_db_query("SELECT payment_type_id, payment_type 
                                       FROM " . TABLE_ORDERS_PAYMENT_STATUS . "");
									   
  while ($payment_status = tep_db_fetch_array($payment_status_query)) {
    $payment_statuses[] = array('id' => $payment_status['payment_type_id'],
                               'text' => $payment_status['payment_type']);
    
	$payment_status_array[$payment_status['payment_type_id']] = $payment_status['payment_type'];
  }
  
  //users array
  $user_names = array();
  $users_query = tep_db_query ("select * from admin");
  while ($users = tep_db_fetch_array ($users_query)){
   $user_names[] = array('id' => $users['admin_firstname'],
                               'text' => $users['admin_firstname']);
    
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  if (isset($action)) {
    switch ($action) {
    
    ////
		    // auto-add with barcode
	  case 'barcode_auto':
	$manual='flase';
      $oID = tep_db_prepare_input($_GET['oID']);
	  $pID = 0;

	  $bcode = $_GET['barcode'];
		// Check if the barcode exists
		$query_product_id = tep_db_query("SELECT products_id FROM " . TABLE_PRODUCTS ." WHERE products_upc ='" . addslashes($bcode) . "' ");
		if($result_product_id = tep_db_fetch_array($query_product_id))
			$pID = $result_product_id['products_id'];
		else { 
			$manual='true'; 
		}
		if(!$pID){
		$query_product_id1=tep_db_query("SELECT products_id, options_serial_no from products_attributes where options_serial_no='".addslashes($bcode)."'");
			if($result_product_id1 = tep_db_fetch_array($query_product_id1))
				$pID = $result_product_id1['products_id'];
			else { 
				$manual='true'; 
			}
		}
		if ($manual=='true')
		{
			// Redirects to manual add
			//tep_redirect(tep_href_link('edit_orders.php', "oID=" . $oID . "&action=barcode_manual&barcode='" . $bcode."'"));
		}
      

      // Check if the barcode has a value set for all options of the product
	  // Asen: this query checks if the product has attributes, I see no reason for it
    /*  $query_check_options = tep_db_query("select count(*) nb from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='".$pID."'");
      $result_check_options = tep_db_fetch_array($query_check_options);
      if ($result_check_options['nb'] > 0)
        tep_redirect(tep_href_link("edit_orders.php", "oID=" . $oID . "&action=barcode_manual&barcode=" . $bcode));
	*/
      $attributes = array();
      $query_attributes_values = tep_db_query("select options_id, options_values_id, options_serial_no, values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa  where options_serial_no='".addslashes($bcode)."'");
      while ($attribute_value =  tep_db_fetch_array($query_attributes_values))
        $attributes[$attribute_value['options_id']] = $attribute_value['values_id'];
		
		$product_query = tep_db_query("select p. products_quantity, p.products_model, p.products_upc, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$pID . "' and pd.language_id = '" . $languages_id . "'");
		//EOF Added languageid
		$product = tep_db_fetch_array($product_query);
        $order = new manualOrder($oID);

		$country_id = oe_get_country_id($order->delivery["country"]);
		$zone_id = oe_get_zone_id($country_id, $order->delivery['state']);
		$products_tax = tep_get_tax_rate($product['products_tax_class_id'], $country_id, $zone_id);		
	  
        $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                                'products_id' => tep_db_prepare_input($pID),
                                'products_model' => tep_db_prepare_input($product['products_model']),
                                'products_name' => tep_db_prepare_input($product['products_name']),
                                'products_price' => tep_db_prepare_input($product['products_price']),
                                'final_price' => tep_db_prepare_input(($product['products_price'] + $AddedOptionsPrice)),
                                'products_tax' => tep_db_prepare_input($products_tax),
                                'products_quantity' => 1);
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $new_product_id = tep_db_insert_id();
        
        if (count($attributes)) {
          foreach($attributes as $option_id => $option_value_id) {
            $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa INNER JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (po.products_options_id = pa.options_id and po.language_id = '" . $languages_id . "') INNER JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on (pov.products_options_values_id = pa.options_values_id and pov.language_id = '" . $languages_id . "') WHERE products_id = '" . $pID . "' and options_id = '" . $option_id . "' and options_values_id = '" . $option_value_id . "'");
            $row = tep_db_fetch_array($result);
			if (is_array($row)) extract($row, EXTR_PREFIX_ALL, "opt");
					if ($opt_price_prefix == '-')
					{$AddedOptionsPrice -= $opt_options_values_price;}
					else //default to positive
					{$AddedOptionsPrice += $opt_options_values_price;}
            $option_value_details[$option_id][$option_value_id] = array (
					"options_values_price" => $opt_options_values_price,
					"price_prefix" => $opt_price_prefix,
				        "options_serial_no" => $opt_options_serial_no);
            $option_names[$option_id] = $opt_products_options_name;
            $option_values_names[$option_value_id] = $opt_products_options_values_name;
			
		//add on for downloads
		if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount 
        FROM " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " 
        WHERE products_attributes_id='" . $opt_products_attributes_id . "'";
        
		$download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $filename[$option_id] = $download['products_attributes_filename'];
          $maxdays[$option_id]  = $download['products_attributes_maxdays'];
          $maxcount[$option_id] = $download['products_attributes_maxcount'];
        } //end if (tep_db_num_rows($download_query) > 0) {
		} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads 
		
          } //end foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
          foreach($attributes as $option_id => $option_value_id) {
            $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                                    'orders_products_id' => tep_db_prepare_input($new_product_id),
                                    'products_options' => tep_db_prepare_input($option_names[$option_id]),
                                    'products_options_values' => tep_db_prepare_input($option_values_names[$option_value_id]),
             'options_values_price' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['options_values_price']),
             'price_prefix' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['price_prefix']),
             'serial_no' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['options_serial_no']));
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

			
		//add on for downloads
		if (DOWNLOAD_ENABLED == 'true' && isset($filename[$option_id])) {
		
		$Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
				orders_id = '" . tep_db_prepare_input($oID) . "',
				orders_products_id = '" . tep_db_prepare_input($new_product_id) . "',
				orders_products_filename = '" . tep_db_prepare_input($filename[$option_id]) . "',
				download_maxdays = '" . tep_db_prepare_input($maxdays[$option_id]) . "',
	            download_count = '" . tep_db_prepare_input($maxcount[$option_id]) . "'";
						
					tep_db_query($Query);
					
       	} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads 
          }
        }
		tep_redirect(tep_href_link('edit_orders.php', "oID=" . $oID ));
     /* insert_product($oID, $pID, $attributes, 1);*/
	  break;
	  
	    // manual add with barcode
	  case 'barcode_manual':
      $oID = tep_db_prepare_input($_GET['oID']);
      $query_product_id = tep_db_query("SELECT products_id FROM " . TABLE_PRODUCTS ." WHERE products_upc = '" . $barcode . "'");
      if($result_product_id = tep_db_fetch_array($query_product_id))
      {
        $product_id = $result_product_id['products_id'];
      
        // Set the attributes and values for this barcode
        $query_attributes_values = tep_db_query("select products_attributes_id, products_id, options_id, options_values_id from products_attributes where options_serial_no = '" . $barcode."'");
        while ($attribute_value =  tep_db_fetch_array($query_attributes_values))
          $add_product_options[$attribute_value['options_id']] = $attribute_value['values_id'];
      }
      else
      {
        $msg_error = "The barcode that was scanned does not exist";
      }
      
      $action = 'add_product';

	  break;
    // Update Order
      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        
        // Set this Session's variables
        if (isset($_POST['billing_same_as_customer'])) $_SESSION['billing_same_as_customer'] = $_POST['billing_same_as_customer'];
        if (isset($_POST['shipping_same_as_billing'])) $_SESSION['shipping_same_as_billing'] = $_POST['shipping_same_as_billing'];
		
        // Update Order Info  
		//figure out the new currency value
		$currency_value_query = tep_db_query("SELECT value 
		                                      FROM " . TABLE_CURRENCIES . " 
											  WHERE code = '" . $_POST['update_info_payment_currency'] . "'");
		$currency_value = tep_db_fetch_array($currency_value_query);

		//figure out the country, state
		$update_customer_state = tep_get_zone_name($_POST['update_customer_country_id'], $_POST['update_customer_zone_id'], $_POST['update_customer_state']);
        $update_customer_country = tep_get_country_name($_POST['update_customer_country_id']);
        $update_billing_state = tep_get_zone_name($_POST['update_billing_country_id'], $_POST['update_billing_zone_id'], $_POST['update_billing_state']);
        $update_billing_country = tep_get_country_name($_POST['update_billing_country_id']);
        $update_delivery_state = tep_get_zone_name($_POST['update_delivery_country_id'], $_POST['update_delivery_zone_id'], $_POST['update_delivery_state']);
        $update_delivery_country = tep_get_country_name($_POST['update_delivery_country_id']);
		
        $sql_data_array = array(
		'customers_name' => tep_db_input(tep_db_prepare_input($_POST['update_customer_name'])),
        'customers_company' => tep_db_input(tep_db_prepare_input($_POST['update_customer_company'])),
        'customers_street_address' => tep_db_input(tep_db_prepare_input($_POST['update_customer_street_address'])),
        'customers_suburb' => tep_db_input(tep_db_prepare_input($_POST['update_customer_suburb'])),
        'customers_city' => tep_db_input(tep_db_prepare_input($_POST['update_customer_city'])),
        'customers_state' => tep_db_input(tep_db_prepare_input($update_customer_state)),
        'customers_postcode' => tep_db_input(tep_db_prepare_input($_POST['update_customer_postcode'])),
        'customers_country' => tep_db_input(tep_db_prepare_input($update_customer_country)),
        'customers_telephone' => tep_db_input(tep_db_prepare_input($_POST['update_customer_telephone'])),
        'customers_email_address' => tep_db_input(tep_db_prepare_input($_POST['update_customer_email_address'])),
                                
		'billing_name' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_name'] : $_POST['update_billing_name']))),
        'billing_company' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_company'] : $_POST['update_billing_company']))),
        'billing_street_address' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_street_address'] : $_POST['update_billing_street_address']))),
        'billing_suburb' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_suburb'] : $_POST['update_billing_suburb']))),
        'billing_city' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_city'] : $_POST['update_billing_city']))),
        'billing_state' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state))),
        'billing_postcode' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_postcode'] : $_POST['update_billing_postcode']))),
        'billing_country' => tep_db_input(tep_db_prepare_input(((isset($_POST['billing_same_as_customer']) && $_POST['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country))),
								
								
	'delivery_name' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_name'] : $_POST['update_billing_name']) : $_POST['update_delivery_name']))),
    'delivery_company' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_company'] : $_POST['update_billing_company']) : $_POST['update_delivery_company']))),
    'delivery_street_address' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_street_address'] : $_POST['update_billing_street_address']) : $_POST['update_delivery_street_address']))),
    'delivery_suburb' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_suburb'] : $_POST['update_billing_suburb']) : $_POST['update_delivery_suburb']))),
    'delivery_city' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_city'] : $_POST['update_billing_city']) : $_POST['update_delivery_city']))),
    'delivery_state' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state) : $update_delivery_state))),
    'delivery_postcode' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $_POST['update_customer_postcode'] : $_POST['update_billing_postcode']) : $_POST['update_delivery_postcode']))),
    'delivery_country' => tep_db_input(tep_db_prepare_input(((isset($_POST['shipping_same_as_billing']) && $_POST['shipping_same_as_billing'] == 'on') ? (($_POST['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country) : $update_delivery_country))),
                                
	'payment_method' => tep_db_input(tep_db_prepare_input($_POST['update_info_payment_method'])),
    'currency' => tep_db_input(tep_db_prepare_input($_POST['update_info_payment_currency'])),
    'currency_value' => tep_db_input(tep_db_prepare_input($currency_value['value'])),
    'cc_type' => tep_db_prepare_input($_POST['update_info_cc_type']),
    'cc_owner' => tep_db_prepare_input($_POST['update_info_cc_owner']),
	'cc_number' => tep_db_input(tep_db_prepare_input($_POST['update_info_cc_number'])),
    'cc_expires' => tep_db_prepare_input($_POST['update_info_cc_expires']),
	'cc_cvv' => tep_db_prepare_input($_POST['update_info_cc_cvv']),
    'last_modified' => 'now()');
	

        tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = \'' . tep_db_input($oID) . '\'');
        $order_updated = true;
        
	// UPDATE \ HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

    $check_status_query = tep_db_query("
	                      SELECT customers_name, customers_email_address, orders_status, date_purchased, date_paid 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check_status = tep_db_fetch_array($check_status_query); 
	          if (($status == 4 || $status == 109)) {
            tep_restock_order((int)$oID,'add');
          } 
  if (($check_status['orders_status'] != $_POST['status']) || (tep_not_null($_POST['comments']))) {

        tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($_POST['status']) . "', 
                      last_modified = now() 
                      WHERE orders_id = '" . (int)$oID . "'");
					  
					  
					  
					  
					  // UPDATE \ PAYMENT HISTORY #####

    $check_payment_status_query = tep_db_query("
	                      SELECT  payment_type, date_paid 
	                      FROM " . TABLE_ORDERS_PAYMENT_HISTORY . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
    $check_payment_status = tep_db_fetch_array($check_payment_status_query); 
	        
  if (( $check_payment_status['payment_type'] != $_POST['payment_status']) || (tep_not_null($_POST['payment_comments']))) {

        tep_db_query("UPDATE " . TABLE_ORDERS_PAYMENT_HISTORY . " SET 
					  payment_type = '" . tep_db_input($_POST['payment_status']) . "', 
                      date_paid = now() 
                      WHERE orders_id = '" . (int)$oID . "'"); }
		
		 // Notify Customer ?
      $customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
			  $notify_comments = '';
			  if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
			  }
			  $email = STORE_NAME . "\n" .
			           EMAIL_SEPARATOR . "\n" . 
					   EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . 
                       EMAIL_TEXT_INVOICE_URL;
					   echo ''. "\n" . 
					   EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE2);
			  
			  tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  
			  $customer_notified = '1';
			}			  
          		
			tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input($_POST['status']) . "', 
				now(), 
				" . tep_db_input($customer_notified) . ", 
				'" . tep_db_input(tep_db_prepare_input($_POST['comments']))  . "')");
			}

        
        // Update Products
        if (is_array($_POST['update_products'])) {
          foreach($_POST['update_products'] as $orders_products_id => $products_details) {
		  
		  	//  Update Inventory Quantity
			$order_query = tep_db_query("
			SELECT products_id, products_quantity, orders_products_id 
			FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '" . (int)$orders_products_id . "'");
			$order_products = tep_db_fetch_array($order_query);
			
			// First we do a stock check 
			
			if ($products_details['qty'] != $order_products['products_quantity']){
                $quantity_difference = ($products_details['qty'] - $order_products['products_quantity']);
				if (STOCK_LIMITED == 'true'){
				$options_query = tep_db_query("SELECT products_options_id, products_options_values_id, products_attributes_id FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " ON products_options_name = products_options LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " ON products_options_values_name = products_options_values WHERE orders_products_id = '" . (int)$order_products['orders_products_id'] . "'");
					while ($option = tep_db_fetch_array($options_query)) {
				//$option = tep_db_fetch_array($options_query);

				tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = options_quantity - " . $quantity_difference . " where products_id = '" . $order_products['products_id']. "' AND options_id = '" . (int)$option['products_options_id'] . "' AND options_values_id = '" . $option['products_options_values_id'] ."' and products_attributes_id='".$option['products_attributes_id']."'");
				}

				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity - " . $quantity_difference . ",
					products_ordered = products_ordered + " . $quantity_difference . " 
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
				}
			}

		 
		   if ( (isset($products_details['delete'])) && ($products_details['delete'] == 'on') ) {
		     //check first to see if product should be deleted
		   
		   			 //update quantities first
			       if (STOCK_LIMITED == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity + " . $products_details["qty"] . ",
					products_ordered = products_ordered - " . $products_details["qty"] . " 
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");

					$options_query = tep_db_query("SELECT products_options_id, products_options_values_id, products_attributes_id FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " ON products_options_name = products_options LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " ON products_options_values_name = products_options_values WHERE orders_products_id = '" . (int)$order_products['products_id'] . "'");
					$option = tep_db_fetch_array($options_query);

					tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = options_quantity + " . $products_details["qty"] . " where products_id = '" . (int)$order_products['products_id']. "' AND options_id = '" . (int)$option['products_options_id'] . "' AND options_values_id = '" . $option['products_options_values_id'] ."' products_attributes_id='".$option['products_attributes_id']."' ");

					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
					}
		   
                    tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS . "  
	                              WHERE orders_id = '" . (int)$oID . "'
					              AND orders_products_id = '" . (int)$orders_products_id . "'");
      
	                tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
	                              WHERE orders_id = '" . (int)$oID . "'
                                  AND orders_products_id = '" . (int)$orders_products_id . "'");
	                
					tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "
	                              WHERE orders_id = '" . (int)$oID . "'
                                  AND orders_products_id = '" . (int)$orders_products_id . "'");
           
		   } else {
		     //not deleted=> updated
		   
            // Update orders_products Table
             	$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					products_model = '" . $products_details["model"] . "',
					products_name = '" . oe_html_quotes($products_details["name"]) . "',
					products_price = '" . $products_details["price"] . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
          
              // Update Any Attributes
				// Update Any Attributes
				if(isset($products_details['attributes'])) { 
				  foreach($products_details['attributes'] as $orders_products_attributes_id => $attributes_details) {
					$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						products_options = '" . $attributes_details["option"] . "',
						products_options_values = '" . $attributes_details["value"] . "',
						options_values_price ='" . $attributes_details["price"] . "',
						price_prefix ='" . $attributes_details["prefix"] . "', 
						serial_no ='" . $attributes_details["serial_no"] . "'
						where orders_products_attributes_id = '$orders_products_attributes_id';";
						tep_db_query($Query);
					}//end of foreach($products_details["attributes"]
				}// end of if(isset($products_details[attributes]))

            } //end if/else product details delete= on
          } //end foreach post update products
        }//end if is-array update products
		
	
	  //update any downloads that may exist
      if (is_array($_POST['update_downloads'])) {
	  foreach($_POST['update_downloads'] as $orders_products_download_id => $download_details) {
		$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
					orders_products_filename = '" . $download_details["filename"] . "',
					download_maxdays = '" . $download_details["maxdays"] . "',
					download_count = '" . $download_details["maxcount"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_download_id = '$orders_products_download_id';";
					tep_db_query($Query);
			}
		}	//end downloads
		
						
				//delete or update comments
		      if (is_array($_POST['update_comments'])) {
	              foreach($_POST['update_comments'] as $orders_status_history_id => $comments_details) {
	  
	                  if (isset($comments_details['delete'])){
		
			             $Query = "DELETE FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
			                              WHERE orders_id = '" . (int)$oID . "' 
			                              AND orders_status_history_id = '$orders_status_history_id';";
				                          tep_db_query($Query);
				
				        } else {

		                 $Query = "UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " SET
					               comments = '" . $comments_details["comments"] . "'
					               WHERE orders_id = '" . (int)$oID . "'
					               AND orders_status_history_id = '$orders_status_history_id';";
					               tep_db_query($Query);
				        }
				    }	
				}//end comments update section

      $shipping = array();
      
      if (is_array($_POST['update_totals'])) {
        foreach($_POST['update_totals'] as $total_index => $total_details) {
          extract($total_details, EXTR_PREFIX_ALL, "ot");
          if ($ot_class == "ot_shipping") {
           
               $shipping['cost'] = $ot_value;
               $shipping['title'] = $ot_title;
               $shipping['id'] = $ot_id;
			
		  } // end if ($ot_class == "ot_shipping")
        } //end foreach
	  } //end if is_array

       if (tep_not_null($shipping['id'])) {
   tep_db_query("UPDATE " . TABLE_ORDERS . " SET shipping_module = '" . $shipping['id'] . "' WHERE orders_id = '" . (int)$oID . "'");
       }

        $order = new manualOrder($oID);
        $order->adjust_zones();

        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes- if we don't have shipping quotes shipping tax calculation can't happen
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
			 if (DISPLAY_PRICE_WITH_TAX == 'true') {//extract the base shipping cost or the ot_shipping module will add tax to it again
		   $module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
		   $tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		   $order->info['total'] -= ( $order->info['shipping_cost'] - ($order->info['shipping_cost'] / (1 + ($tax /100))) );
           $order->info['shipping_cost'] = ($order->info['shipping_cost'] / (1 + ($tax /100)));
		   }

		//this is where we call the order total modules
		require( 'order_editor/order_total.php');
		$order_total_modules = new order_total();
        $order_totals = $order_total_modules->process();  
		

        $current_ot_totals_array = array();
		$current_ot_titles_array = array();
        $current_ot_totals_query = tep_db_query("select class, title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
        while ($current_ot_totals = tep_db_fetch_array($current_ot_totals_query)) {
          $current_ot_totals_array[] = $current_ot_totals['class'];
		  $current_ot_titles_array[] = $current_ot_totals['title'];
        }

		tep_db_query("DELETE FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$oID . "'");

        $j=1; //giving something a sort order of 0 ain't my bag baby
		$new_order_totals = array();

	    if (is_array($_POST['update_totals'])) { //1
          foreach($_POST['update_totals'] as $total_index => $total_details) { //2
            extract($total_details, EXTR_PREFIX_ALL, "ot");
            if (!strstr($ot_class, 'ot_custom')) { //3
             for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //4

			  if ($order_totals[$i]['code'] == 'ot_tax') { //5
			  $new_ot_total = ((in_array($order_totals[$i]['title'], $current_ot_titles_array)) ? false : true);
			  } else { //within 5
			  $new_ot_total = ((in_array($order_totals[$i]['code'], $current_ot_totals_array)) ? false : true);
			  }  //end 5 if ($order_totals[$i]['code'] == 'ot_tax')
 
			  if ( ( ($order_totals[$i]['code'] == 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) && ($order_totals[$i]['title'] == $ot_title) ) || ( ($order_totals[$i]['code'] != 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) ) ) { //6
			  //only good for components that show up in the $order_totals array

				if ($ot_title != '') { //7
                  $new_order_totals[] = array('title' => $ot_title,
                                              'text' => (($ot_class != 'ot_total') ? $order_totals[$i]['text'] : '<b>' . $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</b>'),
                                              'value' => (($order_totals[$i]['code'] != 'ot_total') ? $order_totals[$i]['value'] : $order->info['total']),
                                              'code' => $order_totals[$i]['code'],
                                              'sort_order' => $j);
                $written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
				$j++;
                } else { //within 7

				  $order->info['total'] += ($ot_value*(-1)); 
				  $written_ot_totals_array[] = $ot_class;
				  $written_ot_titles_array[] = $ot_title; 

                } //end 7

			  } elseif ( ($new_ot_total) && (!in_array($order_totals[$i]['title'], $current_ot_titles_array)) ) { //within 6

                $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
                //echo $order_totals[$i]['code'] . "<br>"; for debugging- use of this results in errors

			  } elseif ($new_ot_total) { //also within 6
                $order->info['total'] += ($order_totals[$i]['value']*(-1));
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
              }//end 6
           }//end 4
         } elseif ( (tep_not_null($ot_value)) && (tep_not_null($ot_title)) ) { // this modifies if (!strstr($ot_class, 'ot_custom')) { //3
            $new_order_totals[] = array('title' => $ot_title,
                     'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                                        'value' => $ot_value,
                                        'code' => 'ot_custom_' . $j,
                                        'sort_order' => $j);
            $order->info['total'] += $ot_value;
			$written_ot_totals_array[] = $ot_class;
		    $written_ot_titles_array[] = $ot_title;
            $j++;
          } //end 3
		  
		    //save ot_skippy from certain annihilation
			 if ( (!in_array($ot_class, $written_ot_totals_array)) && (!in_array($ot_title, $written_ot_titles_array)) && (tep_not_null($ot_value)) && (tep_not_null($ot_title)) && ($ot_class != 'ot_tax') && ($ot_class != 'ot_loworderfee') ) { //7
			//this is supposed to catch the oddball components that don't show up in $order_totals
				 
				    $new_order_totals[] = array(
					        'title' => $ot_title,
                            'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                            'value' => $ot_value,
                            'code' => $ot_class,
                            'sort_order' => $j);
               //$current_ot_totals_array[] = $order_totals[$i]['code'];
				//$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
				 
				 } //end 7
        } //end 2
	  } else {//within 1
	  // $_POST['update_totals'] is not an array => write in all order total components that have been generated by the sundry modules
	   for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //8
	                  $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $j++;
				
			} //end 8
				
		} //end if (is_array($_POST['update_totals'])) { //1
	  
		for ($i=0, $n=sizeof($new_order_totals); $i<$n; $i++) {
          $sql_data_array = array('orders_id' => $oID,
                                  'title' => $new_order_totals[$i]['title'],
                                  'text' => $new_order_totals[$i]['text'],
                                  'value' => $new_order_totals[$i]['value'], 
                                  'class' => $new_order_totals[$i]['code'], 
                                  'sort_order' => $new_order_totals[$i]['sort_order']);
          tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }
		
        
        if (isset($_POST['subaction'])) {
          switch($_POST['subaction']) {
            case 'add_product':
              tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit#products'));
              break;
              
          }
        }
        
		// 1.5 SUCCESS MESSAGE #####
		
		
	// CHECK FOR NEW EMAIL CONFIRMATION

    if ( (isset($_POST['nC1'])) || (isset($_POST['nC2'])) || (isset($_POST['nC3'])) ) {
	//then the user selected the option of sending a new email
    
    tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=email')); 
	//redirect to the email case
	 
  } else  { 
     //email? email?  We don't need no stinkin email!
	 
	 if ($order_updated)	{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		
		}
		
	break;
		
	// 3. NEW ORDER EMAIL ###############################################################################################
	case 'email':
          
		$oID = tep_db_prepare_input($_GET['oID']);
		$order = new manualOrder($oID);
		
		    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	  //loop all the products in the order
			 $products_ordered_attributes = '';
	  if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
	    for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
		$products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
      }
    }
	
	   $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . $products_model . ' = ' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . $products_ordered_attributes . "\n";
			 }
		   
		//Build the email
	   	 $email_order = STORE_NAME . "\n" . 
                        EMAIL_SEPARATOR . "\n" . 
						EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" .
  EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "\n" .
                	    EMAIL_TEXT_DATE_MODIFIED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

	    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
    	                EMAIL_SEPARATOR . "\n" . 
        	            $products_ordered . 
            	        EMAIL_SEPARATOR . "\n";

	  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
        $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
      }

	  if ($order->content_type != 'virtual') {
    	$email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
        	            EMAIL_SEPARATOR . "\n" .
						$order->delivery['name'] . "\n";
						if ($order->delivery['company']) {
		                  $email_order .= $order->delivery['company'] . "\n";
	                    }
		$email_order .= $order->delivery['street_address'] . "\n";
		                if ($order->delivery['suburb']) {
		                  $email_order .= $order->delivery['suburb'] . "\n";
	                    }
		$email_order .= $order->customer['city'] . "\n";
		                if ($order->delivery['state']) {
		                  $email_order .= $order->delivery['state'] . "\n";
	                    }
		$email_order .= $order->customer['postcode'] . "\n" .
						$order->delivery['country'] . "\n";
	  }

    	$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
        	            EMAIL_SEPARATOR . "\n" .
						$order->billing['name'] . "\n";
						if ($order->billing['company']) {
		                  $email_order .= $order->billing['company'] . "\n";
	                    }
		$email_order .= $order->billing['street_address'] . "\n";
		                if ($order->billing['suburb']) {
		                  $email_order .= $order->billing['suburb'] . "\n";
	                    }
		$email_order .= $order->customer['city'] . "\n";
		                if ($order->billing['state']) {
		                  $email_order .= $order->billing['state'] . "\n";
	                    }
		$email_order .= $order->customer['postcode'] . "\n" .
						$order->billing['country'] . "\n\n";

	    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
    	                EMAIL_SEPARATOR . "\n";
	    $email_order .= $order->info['payment_method'] . "\n\n";
		
		        
			//	if ( ($order->info['payment_method'] == ORDER_EDITOR_SEND_INFO_PAYMENT_METHOD) && (EMAIL_TEXT_PAYMENT_INFO) ) { 
		      //     $email_order .= EMAIL_TEXT_PAYMENT_INFO . "\n\n";
		       //   }
			 //I'm not entirely sure what the purpose of this is so it is being shelved for now

				if (EMAIL_TEXT_FOOTER) {
					$email_order .= EMAIL_TEXT_FOOTER . "\n\n";
				  }

      //code for plain text emails which changes the � sign to EUR, otherwise the email will show ? instead of �
      $email_order = str_replace("�","EUR",$email_order);
	  $email_order = str_replace("&nbsp;"," ",$email_order);

	  //code which replaces the <br> tags within EMAIL_TEXT_PAYMENT_INFO and EMAIL_TEXT_FOOTER with the proper \n
	  $email_order = str_replace("<br>","\n",$email_order);

	  //send the email to the customer
	  tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

   // send emails to other people as necessary
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }
  
         //do the dirty
 		
		$messageStack->add_session(SUCCESS_EMAIL_SENT, 'success');
		
        tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		  
		 break;

        
    ////
    // Edit Order
      case 'edit':
        if (!isset($_GET['oID'])) {
		$messageStack->add(ERROR_NO_ORDER_SELECTED, 'error');
          break;
		  }
        $oID = tep_db_prepare_input($_GET['oID']);
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $order_exists = true;
        if (!tep_db_num_rows($orders_query)) {
        $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
          break;
        }
        
        $order = new manualOrder($oID);
        $shippingKey = $order->adjust_totals($oID);
        $order->adjust_zones();
        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
 
     
        break;
    }
  }

  // currecies drop-down array
  $currency_query = tep_db_query("select distinct title, code from " . TABLE_CURRENCIES . " order by code ASC");  
  $currency_array = array();
  while($currency = tep_db_fetch_array($currency_query)) {
    $currency_array[] = array('id' => $currency['code'],
                              'text' => $currency['code'] . ' - ' . $currency['title']);
  }

?>

  
  <?php include('order_editor/css.php');  
      //because if you haven't got your css, what have you got?
      ?>

<script language="javascript" src="includes/general.js"></script>

  <?php include('order_editor/javascript.php');  
      //because if you haven't got your javascript, what have you got?
      ?>
<script> 
function addProduct(){
	//document.edit_order.subaction.value = "add_product";
	//document.edit_order.submit();
	
	//productsTable
	jQuery.ajax({
		url: 'edit_orders_ajax.php?oID=<?php echo $_GET['oID']; ?>&action=get_products',
		success: function (data){
			jQuery('#productsTable').html(data);
		}
	});
	obtainTotals();
}

</script>
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="format-detection" content="telephone=no">
<script type="text/javascript">
// this function can be called by pic2shop pro to insert a scan (specified in callback=)
function insertCodeFormat(code,format) {
$("textarea#scanresult").text(decodeURIComponent(code));
}
</script>

<meta name="format-detection" content="telephone=no">
<script type="text/javascript">
// this function can be called by pic2shop pro to insert a scan (specified in callback=)
function insertCodeFormat(code,format) {
$("textarea#scanresult").text(decodeURIComponent(code));
}
</script>
</head>
<body>
<div id="dhtmltooltip"></div>
<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/***********************************************
* For Order Editor
* This has to stay here for the tooltips to work correctly
* I tried sticking it with the rest of the javascript, but it has to be inside the <body> tag
*
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor='white'
tipobj.style.width='200'
}
}

document.onmousemove=positiontip

window.setInterval(function(){
    if(localStorage["update"] == "1"){
        localStorage["update"] = "0";
  jQuery.ajax({
		url: 'edit_orders_ajax.php?oID=<?php echo $_GET['oID']; ?>&action=get_products',
		success: function (data){
			jQuery('#productsTable').html(data);
		}
	});
	obtainTotals();
    }
}, 500);


window.setInterval(function(){
    if(localStorage["update"] == "4"){
        localStorage["update"] = "3";
		window.location.reload();
    }
}, 500);
</script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->

<?php
  require(DIR_WS_INCLUDES . 'template-top-edit-order.php');
?>
<title><?php echo 'Edit Order&nbsp;#'.$oID; ?></title>
<style>
#do_not_delete{display: none;}
.col-xs-12{
position:relative;
min-height:1px;
padding-left:15px;
padding-right:15px
		  }
.col-xl{width:40%; float:left; padding:0px 15px;}
.col-xl:after{display:block; content:""; clear:both;}
@media(max-width:1024px){.col-xl{width:100%; float:none;} .col-xxxxs{text-align:center;}}
@media(min-width:992px){.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12{float:left}
.col-md-12{width:100%}
.col-md-11{width:91.66666666666666%}
.col-md-10{width:83.33333333333334%}
.col-md-9{width:75%}
.col-md-8{width:66.66666666666666%}
.col-md-7{width:58.333333333333336%}
.col-md-6{width:50%}
.col-md-5{width:41.66666666666667%}
.col-md-4{width:33.33333333333333%}
.col-md-3{width:25%}
.col-md-2{width:16.666666666666664%}
.col-md-1{width:8.333333333333332%}}
.btns{
   background:#428bca;
  border-radius: 5px;
  box-shadow: none;
  color: #fff !important;
  height: 22px;
  font-weight: 100 !important;
  font-family: Arial,sans-serif,verdana;
  font-size: 12px !important;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  border: 1px solid #bbb;
  border-spacing: 0;
  line-height: 22px;
  vertical-align:middle;
}
.btns:hover{ background: #009;}
.cal-TextBox{display:block;width:100%; height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s; box-sizing:border-box;}
.form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}
.pymnt-buttons .btn{line-height:0px; height:25px;}
	
@media screen and (min-width: 1024px){
#tab1, #tab2, #tab3, #tab4 {
    display: block !important;
}
}	
</style>
<!-- header_eof //-->
<?php if($order->info['delivery_location'] == '1'){
		$delivery_location = 'In Store / In Palm Beach County';
	  }
	  if($order->info['delivery_location'] == '2'){
		  $delivery_location = 'Out of PB County';
	  }
	  if($order->info['delivery_location'] == '3'){
		  $delivery_location = 'Out of State';
	  }
	  if($order->info['delivery_location'] == ''){
		  $delivery_location = 'Out of State';
	  }
?>
    <div id="heading-block" class="column-12" style="margin-top:20px;">
        <div class="row">
            <div class="column-lg-6">
                <div class="pageHeading form-group"><?php echo sprintf(HEADING_TITLE, $oID, tep_date_short($order->info['date_purchased'])); ?></div>
                <div class="column"><span style="display:inline-block;">Order Entered By:</span><?php echo'&nbsp;'. $order->info['customer_service_id']; ?> </div>
                <div class="column"><span style="display:inline-block;">Delivery Location:</span>
                    <?php echo $delivery_location; ?>
                </div>
            </div>
          <ul class="edit-orders-heading-links column-lg-6">
              <li><?php echo '<a class="btn btn-sm btn-secondary" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank"><i class="fa fa-envelope-o" style="margin-right:5px;"></i>Send Invoice</a>'; ?></li>
              <li><?php echo '<a class="btn btn-sm btn-secondary" style="width:100px;" href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $_GET['oID'] . '&action=edit') . '" TARGET="_blank"><i class="fa fa-eye" style="margin-right:5px;"></i>Details</a>'; ?></li>
              <li><?php echo '<a class="btn btn-sm btn-secondary" style="width:100px;" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank"><i class="fa fa-file-text-o" style="margin-right:5px;"></i>Invoice</a>'; ?></li>
              <li><?php echo '<a class="btn btn-sm btn-secondary" href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">Packing Slip</a>'; ?></li>
              <li><?php echo '<a class="btn btn-sm btn-secondary" style="width:100px;" href="'. tep_href_link('dropshiprequest.php?oID=' . $_GET['oID']).'  " TARGET="_blank">Drop Ship</a>'; ?></li>
              <li><?php echo '<a style="width:100px; display:inline-block; height:25px; vertical-align:middle;" href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a>'; ?></li>
              <li ><?php echo '<a style="width:100px; display:inline-block; height:25px; vertical-align:middle;" href="http://usps.com" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', 'Ship with USPS') . '</a>'; ?></li>
              <li><?php echo '<a class="btn btn-sm btn-info" style="width:100px;" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '"><i class="fa fa-chevron-left" style="margin-right:5px;"></i>Go Back</a> '; ?></li>
		  </ul>
        </div>
    </div>
      
<?php
           if (EMAIL_USE_HTML == 'true') {    
        //  send verification email //
        $oID2 = tep_db_prepare_input($_GET['oID']);
		$order2 = new manualOrder($oID2);           
$verify_body = '
<html>
<body>
<table border="0" style="width:600px;" align="center" cellspacing="0" cellpadding="2">
<tr>
<td>
<div style="float:left; width:100%; text-align:center;"><img style="width:100%; max-width:250px;" src="'. $logo_name .'" alt="logo"></div>
<tr><td>Dear '.$order2->customer['name'].',</td></tr> 
<tr><td>&nbsp;</td></tr>
<tr><td>Thank you for your order with us here at Jupiter Kiteboarding. We are happy to assist you with your purchase but first we would need to verify you as a customer. We can do this in several different ways, once we verify you we will not have to do this again in the future.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Do you have and could you send any of the below items ...<br>
1) <a href="https://www.facebook.com/jupiterkiteboarding/">Facebook</a> - either like our page or send us a message at Jupiter Kiteboarding<br>
2) Picture of drivers license (or passport) AND picture of front and back of the credit card you are using<br>
3) Reference from one of our customers</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>For international orders or non-verified domestic orders, we most likely require a scanned copy front and back of the credit card you are using as well as a corresponding photo ID. We apologize for any inconvenience, this policy has become necessary due to the high rate of fraudulent orders we have been experiencing. Please let us know if you have any questions.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Sincerely,</td></tr>
	<tr><td>Jupiter Kiteboarding</td></tr>
</table>
</body>
</html>';
 
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "X-Mailer: osCommerce Mailer" . "\r\n";        
  
        if ($action == 'verify'){
    
            
        mail($order2->customer['email_address'], 'Complete your purchase at Jupiter Kiteboarding', $verify_body, $headers);    
            
        $update_comments_verify_query = tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('".$oID2."', '" . tep_db_input('1') . "', now(), " . tep_db_input('4') . ", '" . ('Verification request sent')  . "')");
        tep_db_query("UPDATE orders SET orders_status = '1' where orders_id = '".$oID2."'");    
        ?> 
    <script>
    var oID = <?php echo $oID2; ?>;    
    location.href = "https://www.jupiterkiteboarding.com/store/assend/edit_orders.php?oID="+oID+"";
    </script>
       <?php
        }
}
    
    
	   if (($action == 'edit') && ($order_exists == true)){
     
 ?>
	   
	    <div id="ordersMessageStack">
	   	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	    </div>
	   	   
	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?>
	<!-- Begin Update Block, only for non-ajax use -->

           <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
          </div>
	
	  <br>
	  <br>
	  <!-- End of Update Block -->
	  <?php } ?>
									  		   <?php 
  //if ($action == 'edit')
 //   tep_barcode_applet(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oID . '&action=barcode_auto')); 
  //if ($action == 'add_product')
   // tep_barcode_applet(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oID . '&action=barcode_manual')); 
    
?>    	
<!-- Barcode Applet eof //-->
  
<style>
.m-dropdown .m-dropdown__wrapper{top:100%;text-align:left;display:none;position:absolute;z-index:101;padding-top:0;width:320px;-webkit-border-radius:4px;-moz-border-radius:4px;-ms-border-radius:4px;-o-border-radius:4px;border-radius:4px;-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0);-o-transform:translateZ(0);transform:translateZ(0);-webkit-transform-style:preserve-3d;-webkit-backface-visibility:hidden;backface-visibility:hidden}  
    .m-dropdown .m-dropdown__wrapper .m-dropdown__inner{-webkit-border-radius:4px;-moz-border-radius:4px;-ms-border-radius:4px;-o-border-radius:4px;border-radius:4px}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__inner .m-dropdown__content,.m-dropdown .m-dropdown__wrapper .m-dropdown__inner .m-dropdown__scrollable,.m-dropdown .m-dropdown__wrapper .m-dropdown__inner .mCSB_container,.m-dropdown .m-dropdown__wrapper .m-dropdown__inner .mCustomScrollBox{-webkit-border-radius:4px;-moz-border-radius:4px;-ms-border-radius:4px;-o-border-radius:4px;border-radius:4px}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__header{padding:20px 20px;-webkit-border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-ms-border-radius:4px 4px 0 0;-o-border-radius:4px 4px 0 0;border-radius:4px 4px 0 0}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__header .m-dropdown__header-title{display:block;padding:0 0 5px 0;font-size:1.5rem;font-weight:400}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__header .m-dropdown__header-subtitle{display:block;padding:0;font-size:1rem}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__body{padding:20px}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__body .mCSB_scrollTools{right:-10px}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__body.m-dropdown__body--paddingless{padding:0}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__body.m-dropdown__body--paddingless .mCSB_scrollTools{right:0}
    .m-dropdown .m-dropdown__wrapper .m-dropdown__footer{padding:20px}
    .m-dropdown.m-dropdown--header-bg-fil .m-dropdown__wrapper .m-dropdown__inner{-webkit-border-radius:8px 8px 4px 4px;-moz-border-radius:8px 8px 4px 4px;-ms-border-radius:8px 8px 4px 4px;-o-border-radius:8px 8px 4px 4px;border-radius:8px 8px 4px 4px}
    

    .m-dropdown.m-dropdown--arrow .m-dropdown__wrapper {
padding-top: 10px;
}
    .m-dropdown.m-dropdown--align-right .m-dropdown__wrapper {
right: 10;
}
    
.m-dropdown.m-dropdown--arrow .m-dropdown__arrow.m-dropdown__arrow--right, .m-dropdown.m-dropdown--arrow.m-dropdown--up .m-dropdown__arrow.m-dropdown__arrow--right {
right: 15px;
left: auto;
margin-left: auto;
}    

    .m-dropdown.m-dropdown--arrow .m-dropdown__arrow {
color: #fff;
}
 
    
.m-dropdown.m-dropdown--arrow .m-dropdown__arrow, .m-dropdown.m-dropdown--arrow.m-dropdown--up .m-dropdown__arrow {
position: absolute;
line-height: 0;
display: inline-block;
overflow: hidden;
height: 11px;
width: 40px;
position: relative;
left: 50%;
margin-left: -20px;
top: 0;
position: absolute;
}
    
.m-dropdown .m-dropdown__wrapper .m-dropdown__inner {
background-color: #fff;
box-shadow: 0 0 15px 1px rgba(69,65,78,.2);
} 
    
.m-dropdown .m-dropdown__wrapper .m-dropdown__body {
padding: 20px;
} 
    
.m-dropdown__arrow:before{display:inline-block;font-family:Metronic;font-style:normal;font-weight:400;font-variant:normal;line-height:0;text-decoration:inherit;text-rendering:optimizeLegibility;text-transform:none;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-smoothing:antialiased;content:""}

.m-dropdown__arrow i{position: relative;
top: 100%;
margin-top: 11px;
font-size: 40px;
} 
    #nobueno{cursor: pointer;}    
    
   
    </style>
<?php
    $customer_name = $order->customer['name'];
    $_customer_name  = tep_db_input($customer_name);       
           
    $cust_order_created_query = tep_db_query('SELECT date_purchased FROM orders WHERE customers_id = "'.$order->customer['id'].'" and orders_status = "3" LIMIT 1');
    $cust_order_created = tep_db_fetch_array($cust_order_created_query);
           
    $verified_status = 0;	   
    $verified_range = date('Y-m-d h:m:s', strtotime("-90 days"));       
           
    $get_verified_status_query = tep_db_query("select verified from customers where customers_id = '".$order->customer['id']."'");
    $get_verified_status = tep_db_fetch_array($get_verified_status_query);
    
    if($get_verified_status['verified'] == '1'){
        $verified = '<i class="fa fa-check" style="color:#0C0; font-size:16px; margin:0px 10px;"></i>';
		$verified_status = '1';
    } else {
        if ($verified_range > $cust_order_created['date_purchased'] && tep_db_num_rows($cust_order_created_query) > 0){
            $verified = '<i class="fa fa-check" style="color:#0C0; font-size:16px; margin:0px 10px;"></i>';
			$verified_status = '1';
        } else {
            $verified = '<i class="fa fa-times" style="color:#E61616; font-size:16px; margin:0px 10px;"></i>';
			$verified_status = '0';
        }
    }
           
    $check_if_delivered_query = tep_db_query("SELECT orders_status FROM orders WHERE orders_id = '".$_GET['oID']."'");
    $check_if_delivered = tep_db_fetch_array($check_if_delivered_query);
           
    if($check_if_delivered['orders_status'] == '3' || $check_if_delivered['orders_status'] == '4' || $check_if_delivered['orders_status'] == '109'){
        $disabled2 = 'disabled';
    } else {
		$disabled2 = '';
	}          
?>    
    
<div style="margin:10px 0px 25px;" class="col-xs-12">
    <div style="display:inline-block; margin-left:30px;">
        <?php if($check_if_delivered['orders_status'] !== '3'){
        echo '<a class="btn btn-primary btn-sm" style="padding:7px;" target="_blank" href="p/index.php?oID='.$_GET['oID'].'&cName='.stripslashes($order->customer['name']).'">Virtual Store</a>';
    } else {
        echo '<a class="btn btn-secondary btn-sm" style="padding:7px;">Virtual Store Closed</a>';
    }
           ?>
    
            <a class="btn btn-outline-dark btn-sm" onClick="toggleOverlay2();" style="margin:15px;">Show Agreement</a>
    </div>
<div style="display: inline-block; float:right; width:120px;" id="verified-container" class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push">
        <div id="verified" class="btns" style="width:100%; height:30px; line-height:30px; text-align:left; ">
            <?php echo $verified; ?>Verified
        </div>
        <div id="verification-steps" class="m-dropdown__wrapper" style="z-index: 101;">
            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 37px;"><i class="fa fa-caret-up" aria-hidden="true"></i></span>
            <div class="m-dropdown__inner">
                <div class="m-dropdown__body">
                <h3>Customer Has Been Verified Via:</h3>

                    <ul>
                        <li><label role="checkbox" for="email-ver"><input id="email-ver" type="checkbox" value="email" style="margin-right:10px; ">Email</label><?php echo '<a style="margin-left:35px; font-weight:bold; float:right;" href="edit_orders.php?oID='.$_GET['oID'].'&action=verify">Send Email</a>'; ?></li>
                        <li><label role="checkbox" for="phone-ver"><input id="phone-ver" type="checkbox" value="phone" style="margin-right:10px; "><span>Phone</span></label>
                        </li>
                        <li><label role="checkbox" for="fb-ver"><input id="fb-ver" type="checkbox" value="facebook" style="margin-right:10px; "><span>Facebook</span></label>
                        </li>
                        <li><label role="checkbox" for="optingout"><input id="optingout" type="checkbox" value="optout" style="margin-right:10px; "><span>Customer is legit</span></label>
                        </li>
                        <li style="margin-top:20px;"><span id="nobueno">Not Verified</span>
                        </li>
                    </ul>

                    <div id="double-check" style="display:none;">
                        <div class="message"></div>
                        <button class="button yes">Yes</button>
                        <button class="button no">No</button>
                    </div>        
                </div>
            </div>
        </div>
    </div>
    
</div>
    
    
<?php   
echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order','','id="edit_order"');
    ?>

<script type="text/javascript">
$(document).ready(function(e) {

   //Use this inside your document ready jQuery 
   $(window).on('popstate', function() {
      location.reload(true);
   });

});
    
	// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){
				$('ul.nav-tabs').each(function(){
					// For each set of tabs, we want to keep track of
					// which tab is active and it's associated content
					var $active, $active2, $content, $links = $(this).find('a');

					// If the location.hash matches one of the links, use that as the active tab.
					// If no match is found, use the first link as the initial active tab.
					$active2 = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
					$active2.addClass('active');
					$content = $($active2[0].hash);
					
					// Hide the remaining content
					$links.not($active2).each(function () {
						$(this.hash).hide();
					});
					// Bind the click event handler
					$(this).on('click', 'a', function(e){
						//Revised make the old tab inactive
						var old_tab = $('.nav-item a.active');
						old_tab.removeClass('active');
						$(old_tab[0].hash).hide();
						
						// Update the variables with the new link and content
						$active = $(this);
						$content = $(this.hash);
						// Make the tab active.
						$active.addClass('active');
						$content.show();
						
						/*
						// Make the old tab inactive.
						$active.removeClass('active');
						$content.hide();
						*/

						// Prevent the anchor's default click action
						e.preventDefault();
					});
				});
			});

		</script>
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <div id="tab-container" class="tab-container">
	 <ul class="nav nav-tabs edit-orders-nav-tabs">
		<li class="nav-item prod-details"><a href="#tab1" class="nav-link">Products</a></li>
		<li class="nav-item cust-details"><a href="#tab2" class="nav-link">Customer Details/ CC Info</a></li>
	 </ul>
	 
	 <div class="mobile-name" style="text-align: center;">
    	<h2><?php echo $order->customer['name']; ?></h2>
	</div>
		 
<div id="tab2">
    <!-- customer_info bof //-->
  <div id="customer-info-wrapper">  

             <!-- customer_info bof //-->
                   <div id="customer-info" class="col-sm-4">
              <input id="ci-1" type="checkbox">
              <label class="customer-info-heading" for="ci-1"><?php echo ENTRY_CUSTOMER; ?><i class="fa fa-caret-down" style="margin-left:5px;"></i><i class="fa fa-caret-up" style="margin-left:5px;"></i></label>
              <div class="customer-info-inner">
              <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_NAME; ?></label>
               <div class="col-sm-9"><input class="form-control" name="update_customer_name" size="37" value="<?php echo stripslashes($order->customer['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_name', encodeURIComponent(this.value))"<?php } ?>></div>
              </div>
              <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo 'Company'; ?></label>
                <div class="col-sm-9"><input class="form-control" name="update_customer_company" size="37" value="<?php echo stripslashes($order->customer['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_company', encodeURIComponent(this.value))"<?php } ?>></div>
           </div>
              <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_STREET_ADDRESS; ?></label>
                <div class="col-sm-9"><input class="form-control" name="update_customer_street_address" size="37" value="<?php echo stripslashes($order->customer['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_street_address', encodeURIComponent(this.value))"<?php } ?>></div>
             </div>
               <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_SUBURB; ?></label>
                <div class="col-sm-9"><input class="form-control" name="update_customer_suburb" size="37" value="<?php echo stripslashes($order->customer['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_suburb', encodeURIComponent(this.value))"<?php } ?>></div>
              </div>
              <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-9"><input class="form-control" name="update_customer_city" size="15" value="<?php echo stripslashes($order->customer['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_city', encodeURIComponent(this.value))"<?php } ?>></div>
                 </div>
                 <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            <label class="col-sm-3 control-label">State</label>
                <div class="col-sm-9"><span id="customerStateMenu">
				<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo tep_draw_pull_down_menu('update_customer_zone_id', tep_get_country_zones($order->customer['country_id']), $order->customer['zone_id'], 'class="col-sm-9 form-control" onChange="updateOrdersField(\'customers_state\', this.options[this.selectedIndex].text);"'); 
				} else {
				echo tep_draw_pull_down_menu('update_customer_zone_id', tep_get_country_zones($order->customer['country_id']), $order->customer['zone_id'], 'class="inputBox col-sm-9 form-control"');
				}?></span><span id="customerStateInput"><input class="form-control" name="update_customer_state" size="15" value="<?php echo stripslashes($order->customer['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_state', encodeURIComponent(this.value))"<?php } ?>></span></div></div>
              
              <div class="form-group col-xs-12 col-sm-5" style="padding:0px;">
            <label class="col-sm-5 control-label"><?php echo 'ZIP'; ?></label>
               <div class="col-sm-7"><input style="width:76px;" class="form-control" name="update_customer_postcode" size="5" value="<?php echo $order->customer['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_postcode', encodeURIComponent(this.value))"<?php } ?>></div></div>
               <div class="form-group col-xs-12 col-sm-7" style="padding:0px;">
                <label class="col-sm-4 control-label"><?php echo ENTRY_COUNTRY; ?></label>
                <div class="col-sm-8">
				<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo tep_draw_pull_down_menu('update_customer_country_id', tep_get_countries(), $order->customer['country_id'], 'class="form-control" onChange="update_zone(\'update_customer_country_id\', \'update_customer_zone_id\', \'customerStateInput\', \'customerStateMenu\'); update(\'customers_country\', this.options[this.selectedIndex].text);"'); 
				} else {
				echo tep_draw_pull_down_menu('update_customer_country_id', tep_get_countries(), $order->customer['country_id'], 'class="form-control" onChange="update_zone(\'update_customer_country_id\', \'update_customer_zone_id\', \'customerStateInput\', \'customerStateMenu\');"'); 
				} ?></label>
              </div> </div>
              
            <?php $tel_string = preg_replace("/[^a-zA-Z0-9]+/", "", $order->customer['telephone']);  ?>           
              <div class="form-group" style="clear:both;">
				  <label class="col-sm-4 control-label"><a href="tel:<?php echo $tel_string; ?>"><i class="fa fa-phone" style="margin-right:5px;"></i></a><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
				  <div class="col-sm-8"><input class="form-control" name="update_customer_telephone" size="15" value="<?php echo $order->customer['telephone']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_telephone', encodeURIComponent(this.value))"<?php } ?>></div>
              </div>
              <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
                <div class="col-sm-8"><input class="form-control" name="update_customer_email_address" size="35" value="<?php echo $order->customer['email_address']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('customers_email_address', encodeURIComponent(this.value))"<?php } ?>></div>
              <?php if(strtolower($order->customer['email_address']) == strtolower($check_admin['admin_email_address']) && $order->info['orders_status'] == '137') {
                $readonly = false;
                echo '<script>var admin_override = false;</script>';
              }?>
             </div>
              <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo 'CC Number'; ?></label>
                <div class="col-sm-8"><input class="form-control" name="cc_number" value="<?php echo $order->customer['cc_number']; ?>" onChange="updateOrdersField('cc_number', encodeURIComponent(this.value))" /></div>
              
             </div>	     
              <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo 'CC Expiry Month'; ?></label>
                <div class="col-sm-8"><input class="form-control" name="cc_exp_month" value="<?php echo $order->customer['cc_exp_month']; ?>" onChange="updateOrdersField('cc_exp_month', encodeURIComponent(this.value))" /></div>
              
             </div>	     
              <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo 'CC Expiry Year'; ?></label>
                <div class="col-sm-8"><input class="form-control" name="cc_exp_year" value="<?php echo $order->customer['cc_exp_year']; ?>" onChange="updateOrdersField('cc_exp_year', encodeURIComponent(this.value))" /></div>
              
             </div>	     
              <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo 'CC CVV'; ?></label>
                <div class="col-sm-8"><input class="form-control" name="cc_cvv" value="<?php echo $order->customer['cc_cvv']; ?>" onChange="updateOrdersField('cc_cvv', encodeURIComponent(this.value))" /></div>
              
             </div>	     
           </div>
			<!-- customer_info_eof //-->
            </div>
                        
            <div id="shipping-address" class="col-sm-4">
            <!-- shipping_address bof -->
            <input id="sa-2" type="checkbox">
            <label for="sa-2"  class="shipping-add-heading"><?php echo ENTRY_SHIPPING_ADDRESS; ?><i class="fa fa-caret-down" style="margin-left:5px;"></i><i class="fa fa-caret-up" style="margin-left:5px;"></i></label> 
				     <div class="shipping-info-inner">
                  <div class="form-group">
					<label class="col-sm-3 control-label"><?php echo ENTRY_NAME; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_delivery_name" size="37" value="<?php echo stripslashes($order->delivery['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_name', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                  <div class="form-group">
				<label class="col-sm-3 control-label"><?php echo 'Company'; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_delivery_company" size="37" value="<?php echo stripslashes($order->delivery['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_company', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                  <div class="form-group">
					<label class="col-sm-3 control-label"><?php echo ENTRY_STREET_ADDRESS; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_delivery_street_address" size="37" value="<?php echo stripslashes($order->delivery['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_street_address', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                  <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo ENTRY_SUBURB; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_delivery_suburb" size="37" value="<?php echo stripslashes($order->delivery['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_suburb', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                  <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            		<label class="col-sm-3 control-label">City</label>
                   <div class="col-sm-9"><input class="form-control" name="update_delivery_city" size="15" value="<?php echo stripslashes($order->delivery['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('delivery_city', encodeURIComponent(this.value))"<?php } ?>></div>
                   </div>
                   
                    <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            		<label class="col-sm-3 control-label">State</label>
                    <div class="col-sm-9"><span id="deliveryStateMenu">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') { 
				echo tep_draw_pull_down_menu('update_delivery_zone_id', tep_get_country_zones($order->delivery['country_id']), $order->delivery['zone_id'], 'class="col-sm-9 form-control" onChange="updateShippingZone(\'delivery_state\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_delivery_zone_id', tep_get_country_zones($order->delivery['country_id']), $order->delivery['zone_id'], 'class="col-sm-9 form-control"'); 
					} ?>
					</span><span id="deliveryStateInput"><input name="update_delivery_state" size="15" value="<?php echo stripslashes($order->delivery['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateShippingZone('delivery_state', encodeURIComponent(this.value))"<?php } ?>></span></div>
                  </div>
                  <div class="form-group col-xs-12 col-sm-5" style="padding:0px;">
            <label class="col-sm-5 control-label"><?php echo 'ZIP'; ?></label>
               <div class="col-sm-7"><input style="width:76px;" class="form-control" name="update_delivery_postcode" size="5" value="<?php echo $order->delivery['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateShippingZone('delivery_postcode', encodeURIComponent(this.value))"<?php } ?>></div>
               </div>
               <div class="form-group col-xs-12 col-sm-7" style="padding:0px;">
                <label class="col-sm-4 control-label"><?php echo ENTRY_COUNTRY; ?></label>
                <div class="col-sm-8">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_delivery_country_id', tep_get_countries(), $order->delivery['country_id'], 'class="form-control" onchange="update_zone(\'update_delivery_country_id\', \'update_delivery_zone_id\', \'deliveryStateInput\', \'deliveryStateMenu\'); updateShippingZone(\'delivery_country\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_delivery_country_id', tep_get_countries(), $order->delivery['country_id'], 'class="form-control" onchange="update_zone(\'update_delivery_country_id\', \'update_delivery_zone_id\', \'deliveryStateInput\', \'deliveryStateMenu\');"'); 
					}
					?></div>
                   </div>
             </div>
            <!-- shipping_address_eof //-->
            </div>
            
              <div id="billing-add" class="col-sm-4"> 
                 <!-- billing_address bof //-->
             <input id="ba-3" type="checkbox">    
             <label for="ba-3" class="billing-add-heading" ><?php echo 'Billing Address/ CC Info' ?><i class="fa fa-caret-down" style="margin-left:5px;"></i><i class="fa fa-caret-up" style="margin-left:5px;"></i></label>
                  <div class="billing-info-inner">
                   <div class="form-group"> 
                    <label class="col-sm-3 control-label"><?php echo ENTRY_NAME; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_billing_name" size="37" value="<?php echo stripslashes($order->billing['name']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_name', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                   <div class="form-group"> 
                    <label class="col-sm-3 control-label"><?php echo ENTRY_COMPANY; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_billing_company" size="37" value="<?php echo stripslashes($order->billing['company']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_company', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                   <div class="form-group"> 
                    <label class="col-sm-3 control-label"><?php echo ENTRY_STREET_ADDRESS; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_billing_street_address" size="37" value="<?php echo stripslashes($order->billing['street_address']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_street_address', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                   <div class="form-group"> 
                    <label class="col-sm-3 control-label"><?php echo ENTRY_SUBURB; ?></label>
                    <div class="col-sm-9"><input class="form-control" name="update_billing_suburb" size="37" value="<?php echo stripslashes($order->billing['suburb']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_suburb', encodeURIComponent(this.value))"<?php } ?>></div>
                  </div>
                  <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            		<label class="col-sm-3 control-label">City</label>
                   <div class="col-sm-9"><input class="form-control" name="update_billing_city" size="15" value="<?php echo stripslashes($order->billing['city']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_city', encodeURIComponent(this.value))"<?php } ?>></div>
                   </div>
                    <div class="form-group col-xs-12 col-sm-6" style="padding:0px;">
            		<label class="col-sm-3 control-label">State</label>
                    <div class="col-sm-9"><span id="billingStateMenu">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_billing_zone_id', tep_get_country_zones($order->billing['country_id']), $order->billing['zone_id'], 'class="col-sm-9 form-control" onChange="updateOrdersField(\'billing_state\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_billing_zone_id', tep_get_country_zones($order->billing['country_id']), $order->billing['zone_id'], 'class="col-sm-9 form-control"');
					} ?>
					</span><span id="billingStateInput"><input class="form-control" name="update_billing_state" size="15" value="<?php echo stripslashes($order->billing['state']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_state', encodeURIComponent(this.value))"<?php } ?>></span></div>
               </div>
                   <div class="form-group col-xs-12 col-sm-5" style="padding:0px;">
            <label class="col-sm-5 control-label"><?php echo 'ZIP'; ?></label>
               <div class="col-sm-7"><input style="width:76px;" class="form-control" name="update_billing_postcode" size="5" value="<?php echo $order->billing['postcode']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('billing_postcode', encodeURIComponent(this.value))"<?php } ?>></div>
               </div>
                 <div class="form-group col-xs-12 col-sm-7" style="padding:0px;">
                    <label class="col-sm-4 control-label"><?php echo ENTRY_COUNTRY; ?></label>
                <div class="col-sm-8">
					<?php if (ORDER_EDITOR_USE_AJAX == 'true') {
					echo tep_draw_pull_down_menu('update_billing_country_id', tep_get_countries(), $order->billing['country_id'], 'class="form-control" onchange="update_zone(\'update_billing_country_id\', \'update_billing_zone_id\', \'billingStateInput\', \'billingStateMenu\'); updateOrdersField(\'billing_country\', this.options[this.selectedIndex].text);"'); 
					} else {
					echo tep_draw_pull_down_menu('update_billing_country_id', tep_get_countries(), $order->billing['country_id'], 'class="form-control" onchange="update_zone(\'update_billing_country_id\', \'update_billing_zone_id\', \'billingStateInput\', \'billingStateMenu\'); updateOrdersField(\'billing_country\', this.options[this.selectedIndex].text);"'); 
					} ?></div>
                    </div>
                    
              <!-- billing_address_eof //-->
       
              <!-- payment_method bof //-->         
          
            
                  <table cellspacing="0" cellpadding="2" width="100%">
        <tr class="dataTableHeadingRow" style="background-color:#FF5E00;"> 
          <td colspan="2" class="dataTableHeadingContent" valign="bottom" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_UPDATE_TO_CC); ?>')" onMouseout="hideddrivetip()"><?php echo ENTRY_PAYMENT_METHOD; ?>
		  		
				  <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script>
			
			</td>
	      
		     <td></td>
	         <td class="dataTableHeadingContent" valign="bottom" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_UPDATE_CURRENCY); ?>')" onMouseout="hideddrivetip()"><?php echo ENTRY_CURRENCY_TYPE; ?> 
		  
		  		  <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script>
				  
             </td>
	         <td></td>
	         <td class="dataTableHeadingContent"><?php echo ENTRY_CURRENCY_VALUE; ?></td>
         </tr>
                  
	     <tr class="dataTableRow"> 
	       <td colspan="2" class="main">
	       <?php 
	        //START for payment dropdown menu use this by quick_fixer
  		      if (ORDER_EDITOR_PAYMENT_DROPDOWN == 'true') { 
		
		    // Get list of all payment modules available
            $enabled_payment = array();
            $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
            $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

             if ($dir = @dir($module_directory)) {
              while ($file = $dir->read()) {
               if (!is_dir( $module_directory . $file)) {
                if (substr($file, strrpos($file, '.')) == $file_extension) {
                   $directory_array[] = $file;
                 }
               }
             }
            sort($directory_array);
            $dir->close();
           }

          // For each available payment module, check if enabled
          for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
          $file = $directory_array[$i];

          //include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
          include($module_directory . $file);

          $class = substr($file, 0, strrpos($file, '.'));
          if (tep_class_exists($class)) {
             $module = new $class;
             if ($module->check() > 0) {
              // If module enabled create array of titles
      	       $enabled_payment[] = array('id' => $module->title, 'text' => $module->title);
		
		      //if the payment method is the same as the payment module title then don't add it to dropdown menu
		      if ($module->title == $order->info['payment_method']) {
			      $paymentMatchExists='true';	
		         }
              }
            }
          }
 		//just in case the payment method found in db is not the same as the payment module title then make it part of the dropdown array or else it cannot be the selected default value
		  if ($paymentMatchExists !='true') {
			$enabled_payment[] = array('id' => $order->info['payment_method'], 'text' => $order->info['payment_method']);	
           }
            $enabled_payment[] = array('id' => 'Other', 'text' => 'Other');	
		    //draw the dropdown menu for payment methods and default to the order value
	  		  if (ORDER_EDITOR_USE_AJAX == 'true') {
			  echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" style="width: 150px;" onChange="init(); updateOrdersField(\'payment_method\', this.options[this.selectedIndex].text)"'); 
			  } else {
			  echo tep_draw_pull_down_menu('update_info_payment_method', $enabled_payment, $order->info['payment_method'], 'id="update_info_payment_method" style="width: 150px;" onChange="init();"'); 
			  }
		    }  else { //draw the input field for payment methods and default to the order value  ?>
		  
		   <input name="update_info_payment_method" size="35" value="<?php echo $order->info['payment_method']; ?>" id="update_info_payment_method" onChange="init();<?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?> updateOrdersField('payment_method', encodeURIComponent(this.value));<?php } ?>">
		   
		   <?php } //END for payment dropdown menu use this by quick_fixer ?>
		   
		   </td>
	
	       <td width="20">
	       </td>
	
	        <td>
			 <?php
	         ///get the currency info
              reset($currencies->currencies);
              $currencies_array = array();
                while (list($key, $value) = each($currencies->currencies)) {
                      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
                 }
	
               echo tep_draw_pull_down_menu('update_info_payment_currency', $currencies_array, $order->info['currency'], 'id="update_info_payment_currency" onChange="currency(this.value)"'); 

?>
          </td>

         <td width="10">
         </td>

	     <td>
		  <input name="update_info_payment_currency_value" size="15" style="width:50px;" readonly id="update_info_payment_currency_value" value="<?php echo $order->info['currency_value']; ?>">
		 </td>
      </tr>

                  <!-- credit_card bof //-->
    <tr class="dataTableRow"> 
      <td colspan="6">
	  
	  <table id="optional"><!--  -->
	 <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	<td class="main"><input class="form-group form-control" name="update_info_cc_type" size="32" value="<?php echo $order->info['cc_type']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_type', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input class="form-group form-control" name="update_info_cc_owner" size="32" value="<?php echo $order->info['cc_owner']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_owner', encodeURIComponent(this.value))<?php } ?>"></td>
	  </tr>
	  <tr class="scramble-one">
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input class="form-group form-control" name="update_info_cc_number" size="32" value="<?php echo $order->info['cc_number']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_number', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input class="form-group form-control" style="width:75px" name="update_info_cc_expires" size="5" placeholder="MM/YY" value="<?php echo $order->info['cc_expires']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_expires', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
      <tr class="scramble-two">
	    <td class="main"><?php echo 'CVV'; ?></td>
	    <td class="main"><input class="form-group form-control" style="width:75px;" name="update_info_cc_cvv" size="4" value="<?php echo $order->info['cc_cvv']; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('cc_cvv', encodeURIComponent(this.value))"<?php } ?>></td>
	  </tr>
	</table>
	  
   </td>
  </tr>
 </table>     
		 </div>
  </div> </div>

 <div id="signature-block" style="display: none;">
			 <table class="table">
  <thead>
  <tr class="dataTableHeadingRow"> 
	<th colspan="4" class="dataTableHeadingContent" valign="top"><?php echo 'Signature'; ?></th>
  </tr>
  </thead>
  <tr class="dataTableRow"> 
	<td colspan="4" valign="top" class="dataTableContent">
		<div style="clear:both; position:relative;">
		<?php if($order->info['payment_signature']!=''){ ?>
			<div style="text-align:center;">
				<img style="width:400px;" src="<?php echo $order->info['payment_signature']; ?>"/>
			</div>
		<?php }else{ ?>
			<!--[if lt IE 9]>
			<script type="text/javascript" src="../jSignature/flashcanvas.js"></script>
			<![endif]-->
			<script src="../jSignature/jSignature.min.js"></script>
			<style>
				.controls{width:250px; display:table; margin:10px auto;}
.iagree{float: left; width: 100px; margin: 0px 20px; display: block;}
.disagree{float: left; width: 75px; margin: 0px 15px;}
				.controls form{ display:inline-block; }
				#signature{ width:100%; height:auto; border:1px solid #eee;}
				
			</style> 
			<div id="signature"></div>
			<div class="controls">
				<div class="iagree btns" onClick="submitSign()">I Agree</div><input  type="hidden" value="I Agree"/>
				<div class="disagree btns" onClick="jSig_reset()">Reset</div> <input type="hidden" value="reset"/>
				<input type="hidden" name="sigimg" id="sigimg" value=""/>
			</div>
		</div> 
		<script>
			$(document).ready(function() {
				$("#signature").jSignature()
			})
			var $sigdiv = jQuery("#signature");
			
			function jSig_reset(){
				$sigdiv.jSignature("reset");
				return false;
			}
			
			function submitSign(){	
				if($sigdiv.jSignature('getData','base30')[1].length>1){
					var datapair = $sigdiv.jSignature("getData", "image");
					updateOrdersField2('payment_signature', "data:" + datapair[0] + "," + datapair[1]);
					updateOrdersField2('signature_date', (new Date()).getTime()/1000);
					jQuery('.controls').css( 'display' , 'none' )
					jQuery('#contrcheckboxes').show();
					//jQuery('#sigimg').val( "data:" + datapair[0] + "," + datapair[1]);
				}else{
					alert('Please sign in Signature box.');
				}
			}
		</script>
		<?php } ?>
		<div id="contrcheckboxes"  <?php if($order->info['payment_signature']=='') echo ' style="display:none;" '; ?> >
		<input type="checkbox" class="mailch" <?php if( $order->info['send_contract_mail'] != 0 ) echo 'checked disabled'; ?> onClick="<?php   echo 'sendContrEmail(this);';  ?>"/> <strong>Send Contract Email &nbsp;&nbsp;&nbsp; [<a href="javascript:;" onClick="printContrEmail()">print</a>]</strong>
		<br/>
		<input type="checkbox" class="mailch" <?php if( $order->info['conditions_of_use_email'] != 0 ) echo 'checked disabled'; ?> onClick="<?php   echo 'sendConditEmail(this);';  ?>"/> <strong>Send Conditions of Use Email &nbsp;&nbsp;&nbsp; [<a href="javascript:;" onClick="printConditEmail()">print</a>]</strong><br/>
		<div style="text-align:right;">
		[<a href="javascript:;" onClick="resetMails()">reset emails</a>] [<a href="javascript:;" onClick="printBoth()">print both</a>]
		</div>
		</div>
		
		<script>
		function resetMails(){
			jQuery('.mailch').each(function(i,el){
				el.checked = false;
				el.disabled = false;
			});
		}
		function printBoth(obj){
			window.open('<?php echo  'orders.php?oID=' . $_GET['oID'] .'&print_all=1' ; ?>','','width=600,height=700');
		}
		function sendContrEmail(obj){
			if(!obj.checked) return;
			obj.checked = true;
			obj.disabled = true;
			jQuery.ajax({
				url: '<?php echo  'orders.php?status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&send_contract_mail=1' ; ?>',
				success: function(data){}
			});
		}
		function sendConditEmail(obj){
			if(!obj.checked) return;
			obj.checked = true;
			obj.disabled = true;
			jQuery.ajax({
				url: '<?php echo  'orders.php?status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&conditions_of_use_email=1' ; ?>',
				success: function(data){}
			});
		}
		
		function printContrEmail(){
			window.open('<?php echo  'orders.php?oID=' . $_GET['oID'] .'&print_contract=1' ; ?>','','width=600,height=700');
		}
		function printConditEmail(){
			window.open('<?php echo  'orders.php?oID=' . $_GET['oID'] .'&print_conditions=1' ; ?>','','width=600,height=700');
		}
		</script>
	</td>
  </tr>
  </table>
  <div class="form-group" style="width:100%; margin-top:20px;">
      <a class="btn btn-outline-info btn-sm sig-adjust" onClick="onePerson();" style="width:100px; margin:15px;">1 Person</a>
      
      <a class="btn btn-outline-info btn-sm sig-adjust" onClick="twoPerson();" style="width:100px; margin:15px;">2 People</a>
      
      <a class="btn btn-outline-info btn-sm sig-adjust" onClick="multPerson();" style="margin:15px;">Multiple People</a>
      
      <a class="btn btn-outline-danger btn-sm" onClick="jSig_reset2()" style="width:100px; margin:15px;">Start Over</a>
      
      <a class="btn btn-outline-dark btn-sm" onClick="toggleOverlay2();" style="margin:15px;">Show Agreement</a>
     </div>
  </div>    
  
 <script>
 function onePerson (){
		document.getElementById('signature-block').style.width="50%";
		$("#signature").resize();
		};
		function twoPerson(){
		document.getElementById('signature-block').style.width="70%";
		$("#signature").resize();
		};
		function multPerson (){
		document.getElementById('signature-block').style.width="100%";
		$("#signature").resize();
		};
		function jSig_reset2(){
			$("#signature-block").load('signature.php?oID='+<?php echo $_GET['oID']; ?>);
			};
</script>		     

<style>
.controls {
    width: 250px;
    display: table;
    margin: 10px auto;
}
.iagree {
    float: left;
    width: 100px;
    margin: 0px 20px;
    display: block;
}</style>
   
	
	<div id="productsMessageStack">
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
    </div>

	</div>
    <script>
$(document).ready(function() {
    $('#Popup').click(function() {
       var newwindow = window.open($(this).prop('href'), '', 'height=800,width=800');
        if (window.focus) {
            newwindow.focus();
        }
        return false;
    });

});
</script> 
    
   <div class="col-xs-12" style="display:none;">
   <?php echo '<a id="Popup" href="'. tep_href_link('order_total.php?oID='. $_GET['oID'].'') . '">Show Customer Total</a>'; ?>
   </div> 
	
<div class="show-products form-group" style="display: none;">
	<h3 class="btn btn-primary">Add Products</h3>
</div>	
	
<div id="tab1" style="overflow: auto;">
<div width="100%" style="border: 1px solid #C9C9C9;"> 
	  <a name="products"></a>
		<!-- product_listing bof //-->
         <div id="product-listing-block">
            <table id="productsTable" class="table">
			   <thead class="thead-dark">
               <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_DELETE; ?></div></th>
			    <th class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_QUANTITY; ?></div></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></th>
                 <th class="dataTableHeadingContent" id="heading-msrp"><?php echo ''; ?></th>
	 <th class="dataTableHeadingContent price-base" onMouseover="ddrivetip('Price + Attributes')"; onMouseout="hideddrivetip()"><?php  echo 'Unit Price'; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></th>
	  <th class="dataTableHeadingContent price-excl" onMouseover="ddrivetip('Unit Price x Qty')"; onMouseout="hideddrivetip()"><?php  echo 'Price w/ Qty'; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></th>
	  <th class="dataTableHeadingContent price-incl" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_PRICE_INCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE_TAXED; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></th>
	  <th class="dataTableHeadingContent total-excl" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTAL_EXCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></th>
      <th class="dataTableHeadingContent total-incl" onMouseover="ddrivetip('Price w/ Qty  x Tax')"; onMouseout="hideddrivetip()"><?php  echo 'Total'; ?> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></th>
              </tr>
              </thead>
    <?php if (($order->info['orders_status'] == '4') || $order->info['orders_status'] == '109'){
            $disabled = 'disabled'; 
          } elseif ($check_if_delivered['orders_status'] == '3'){
			$disabled = 'disabled';	   
		  } else {
            $disabled = '';
          }
  if (sizeof($order->products)) {
    for ($i=0; $i<sizeof($order->products); $i++) {
      $orders_products_id = $order->products[$i]['orders_products_id'];  ?>
			   
			   <tr class="dataTableRow" id="prodrow<?php echo $i; ?>">
                
				<td class="dataTableContent"><div align="center" style="padding:8px;"><input <?php echo $disabled; ?> style="height:20px; width:100%;" type="checkbox" name="<?php echo "update_products[" . $orders_products_id . "][delete]"; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onClick="updateProductsField('delete', '<?php echo $orders_products_id; ?>', 'delete', this.checked, this)"<?php } ?>></div></td>
                
				<td class="dataTableContent" valign="top"><div align="center"><input <?php echo $disabled; ?> type="number" style="width:55px; padding:0.5rem;" class="form-control" name="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>" size="2" onKeyUp="updatePrices('qty', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_quantity', encodeURIComponent(this.value))"<?php } ?> value="<?php echo $order->products[$i]['qty']; ?>" id="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>"></div></td>
                
				<td class="dataTableContent" valign="top"><input style="min-width:270px;" class="form-control override" name="<?php echo "update_products[" . $orders_products_id . "][name]"; ?>" size="40" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onfocus="this.oldvalue = this.value;" onChange="updateProductsField('update', '<?php echo $orders_products_id; ?>', 'products_name', encodeURIComponent(this.value),this.oldvalue)"<?php } ?> value='<?php echo oe_html_quotes($order->products[$i]['name']); ?>'>
    
	<?php
      // Has Attributes?
     if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
          $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo '<br><nobr style="line-height:35px;"><small style="font-size:11px;">&nbsp; - ' . "<span>" . oe_html_quotes($order->products[$i]['attributes'][$j]['option']) .':&nbsp;'. oe_html_quotes($order->products[$i]['attributes'][$j]['value']) . "</span>";
				echo"<input type='hidden' name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' id='p" . $orders_products_id . "_" . $orders_products_attributes_id . "_prefix' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "'>
				<input type='hidden' name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='7' value='" .@number_format($order->products[$i]['attributes'][$j]['price'], 2, '.', ''). "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\" id='p". $orders_products_id . "a" . $orders_products_attributes_id . "'>";
					if ($order->products[$i]['attributes'][$j]['serial_no'] != NULL){
					echo "<span>" . '&nbsp;('.$order->products[$i]['attributes'][$j]['serial_no'] . ")</span>";
					}
				} else {
				echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['option']) . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['value']) . "'>" . ': ' . "</i>
				<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' id='p" . $orders_products_id . "_" . $orders_products_attributes_id . "_prefix' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\">" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='7' value='" . $order->products[$i]['attributes'][$j]['price'] . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\" id='p". $orders_products_id . "a" . $orders_products_attributes_id . "'> <input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][serial_no]' size='7' value='" . $order->products[$i]['attributes'][$j]['serial_no'] . "' onKeyUp=\"updateAttributesField('simple', 'serial_no', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" id='p". $orders_products_id . "sn" . $orders_products_attributes_id . "'>";
				}
				echo '</small></nobr>';
			}  //end for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
		
			 //Has downloads?
  
    if (DOWNLOAD_ENABLED == 'true') {
   $downloads_count = 1;
   $d_index = 0;
   $download_query_raw ="SELECT orders_products_download_id, orders_products_filename, download_maxdays, download_count
                         FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "                               
						 WHERE orders_products_id='" . $orders_products_id . "'
						 AND orders_id='" . (int)$oID . "'
						 ORDER BY orders_products_download_id";
  
		$download_query = tep_db_query($download_query_raw);
		
		//
		if (isset($downloads->products)) unset($downloads->products);
		//
		
		if (tep_db_num_rows($download_query) > 0) {
        while ($download = tep_db_fetch_array($download_query)) {
		
 		$downloads->products[$d_index] = array(
		            'id' => $download['orders_products_download_id'],
		            'filename' => $download['orders_products_filename'],
                    'maxdays' => $download['download_maxdays'],
                    'maxcount' => $download['download_count']);
		
		$d_index++; 
		
		} 
       } 
        
   if (isset($downloads->products) && (sizeof($downloads->products) > 0)) {
    for ($mm=0; $mm<sizeof($downloads->products); $mm++) {  
    $id =  $downloads->products[$mm]['id'];
    echo '<br><small>';
    echo '<nobr>' . ENTRY_DOWNLOAD_COUNT . $downloads_count . "";
    echo ' </nobr><br>' . "\n";
  
      if (ORDER_EDITOR_USE_AJAX == 'true') {
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ":j<input name='update_downloads[" . $id . "][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "' onChange=\"updateDownloads('orders_products_filename', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ":<input name='update_downloads[" . $id . "][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "' onChange=\"updateDownloads('download_maxdays', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ":<input name='update_downloads[" . $id . "][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "' onChange=\"updateDownloads('download_count', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      } else {
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ":<input name='update_downloads[" . $id . "][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "'>";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ":<input name='update_downloads[" . $id . "][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "'>";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ":<input name='update_downloads[" . $id . "][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "'>";
     }
  
     echo ' </nobr>' . "\n";
     echo '<br></small>';
     $downloads_count++;
     } //end  for ($mm=0; $mm<sizeof($download_query); $mm++) {
    }
   } //end download
  } //end if (sizeof($order->products[$i]['attributes']) > 0) {

?>
                </td>
           
			<td class="dataTableContent" valign="top">
				<input class="form-control override" style="width:140px;" name="<?php echo "update_products[" . $orders_products_id . "][model]"; ?>" size="12" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('update', '<?php echo $orders_products_id; ?>', 'products_model', encodeURIComponent(this.value))"<?php } ?> value="<?php echo $order->products[$i]['model']; ?>"></td>
            
			<?php $check_if_taxable_query = tep_db_query("select products_tax_class_id from products where products_id = '".$order->products[$i]['products_id']."'");
				  $check_if_taxable = tep_db_fetch_array($check_if_taxable_query); 
			echo '<td class="dataTableContent" valign="top">';
			
			if($check_if_taxable['products_tax_class_id'] == '1'){ ?>
            <div class="input-group" style="width:100px;">
				<input <?php echo $disabled2; ?> style="display:inline-block;" class="form-control" name="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>" size="5" onKeyUp="updatePrices('tax', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_tax', encodeURIComponent(this.value))"<?php } ?> value="<?php echo tep_display_tax_value($order->products[$i]['tax']); ?>" id="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>">
				<span style="display:inline-block" class="input-group-addon">%</span>
            <?php } else { echo 'No Tax'; }?>
				   </div>
            </td>
		
		   <?php if ((!$order->products[$i]['msrp'] == NULL) && (!($order->products[$i]['msrp'] == $order->products[$i]['final_price'])) && (!($order->products[$i]['msrp'] == 0)) ) {  ?>
           	<td class="dataTableContent msrp"><span class="col-form-label" style="display:inline-block;">MSRP:&nbsp;$<?php echo @number_format($order->products[$i]['msrp'],2,'.',''); ?></span></td>
            <?php } else {echo'<td class="dataTableContent msrp"></td>';} ?> 
		    <td class="dataTableContent bprice" valign="top"><input class="form-control price-input" name="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>" size="5" onKeyUp="updatePrices('price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo @number_format($order->products[$i]['price'], 2, '.', ''); ?>" class="price-base" id="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>"></td>
            
			<td class="dataTableContent price-excl" valign="top"><input <?php echo $disabled2; ?> class="form-control price-input" name="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>" size="5" onKeyUp="updatePrices('final_price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo @number_format($order->products[$i]['final_price'], 2, '.', ''); ?>" class="price-excl" id="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>"></td>
                
			<td class="dataTableContent-price-incl" valign="top"><input <?php echo $disabled2; ?> class="form-control price-input" name="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>" size="5" value="<?php echo @number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 2, '.', ''); ?>" onKeyUp="updatePrices('price_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="price-incl" id="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>"></td>
				
			<td class="dataTableContent-total-excl" valign="top"><input <?php echo $disabled2; ?> class="form-control price-input" name="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>" size="5" value="<?php echo @number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 2, '.', ''); ?>" onKeyUp="updatePrices('total_excl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="total-excl" id="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>"></td>
				
			<td class="dataTableContent" valign="top"><input <?php echo $disabled2; ?> class="form-control price-input" name="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>" size="5" value="<?php echo @number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 2, '.', ''); ?>" onKeyUp="updatePrices('total_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="total-incl" id="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>"></td>
				
              </tr>

             			  
<?php
    }
  } else {
    //the order has no products
?>
              <tr class="dataTableRow">
                <td colspan="10" class="dataTableContent" valign="middle" align="center" style="padding: 20px 0 20px 0;"><?php echo TEXT_NO_ORDER_PRODUCTS; ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td colspan="10" style="border-bottom: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
              </tr>
<?php
  }
?>
            </table></div><!-- product_listing_eof //-->
			
		<div id="totalsBlock2">
          
               
                  <link rel="stylesheet" type="text/css" href="live.css" />  
               <?php if (!isset($_POST['search'])) {
    $product_search = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
    
    $_GET['inc_subcat'] = '1';
    if ($_GET['inc_subcat'] == '1') {
      $subcategories_array = array();
      oe_get_subcategories($subcategories_array, $add_product_categories_id);
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$add_product_categories_id . "'";
      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $product_search .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
      }
      $product_search .= ")";
    } else {
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . (int)$add_product_categories_id . "'";
    }

    $products_query = tep_db_query("select distinct p.products_id, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c " . $product_search . " order by pd.products_name");
    $not_found = ((tep_db_num_rows($products_query)) ? false : true);
  }

  $category_array = array(array('id' => '', 'text' => TEXT_SELECT_CATEGORY),
                          array('id' => '0', 'text' => TEXT_ALL_CATEGORIES));
  
  if (($step > 1) && (!$not_found)) {
    $product_array = array(array('id' => 0, 'text' => TEXT_SELECT_PRODUCT));
    while($products = tep_db_fetch_array($products_query)) {
      $product_array[] = array('id' => $products['products_id'],
                               'text' => $products['products_name'] . ' (' . $products['products_model'] . ')' . ':&nbsp;' . $currencies->format($products['products_price'], true, $order->info['currency'], $order->info['currency_value']));
    }
  }

  $has_attributes = false;
  $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
  $products_attributes = tep_db_fetch_array($products_attributes_query);
  if ($products_attributes['total'] > 0) $has_attributes = true;   
 ?>   
			<div id="add-products-block" class="form-group">
			<div style="border: 1px solid #C9C9C9; text-align:center; overflow:auto; background-color:#EFEFEF;">
          <div class="dataTableHeadingRow">
          <div class="dataTableHeadingContent form-group" style="text-align:center; padding:0.75rem;"><?php echo sprintf(ADDING_TITLE, $oID); ?></div>
         </div>
         </form>
         
<?php 
        if($check_if_delivered['orders_status'] !== '3'){  
echo '<form method="POST" id="choosecategory" class="form-inline">
         <label class="col-addprdct-2"><?php echo TEXT_STEP_1; ?></label>
            <div class="dataTableContent col-addprdct-9" valign="top">'. tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree('0', '', '0', $category_array), $add_product_categories_id,'style="" onchange="categoriesChange();" class="form-control '. $disabled.'"').'</div>
            <div class="dataTableContent col-addprdct-12" align="center">
			<input type="hidden" name="step" value="2">
			 </div>
           </form>
    
            <div class="dataTableContent col-addprdct-12" colspan="3" align="center">'.TEXT_PRODUCT_SEARCH .'</div>';
            
            $post_search = $_POST['product_search'] ? $_POST['product_search'] : '';
      echo'<form  method="POST">
            <div class="col-addprdct-12">
	               <div class="col-addprdct-2">&nbsp;</div>
	               <div class="col-addprdct-9" valign="top">
                        <input class="'.$disabled.'" type="text" id="searchbox" name="product_search" value=" '. $post_search .'" autocomplete="off" onChange="/*this.form.submit();*/" class="upcfield form-group" style="height:38px;" data-orderid="'. $_GET['oID'] .'">
                        <input type="text" id="searchbox3" name="product_search2" placeholder="Scanner Only" value=" '. $post_search .'" autocomplete="off" onChange="/*this.form.submit();*/" class="upcfield" style="text-align:center; display:none;">
                    </div>
                </div>';
        } else {
            echo '<div class="col-addprdct-12">
                        <b>Can\'t add products to orders marked as Delivered</b>
                        </br></br>
                        <b>Order must be changed back to pending or a new order must be made</b>
                        </br>
                        &nbsp;
                </div>';    
        }
            
?>                        
                       
<div id="resultsContainer"></div>

            <td class="dataTableContent" align="center"><input type="hidden" name="search" value="1"></td>
          </form>
				  </div>
                  </div>
             <?php if ($act == 'create_order'){ ?>
 <script>
 var searchbox = document.getElementById('searchbox');
searchbox.focus();
  </script>
  <?php } ?> 
    
              		<div id="totalsBlock">
              <div id="order-totals-block">
                <td align="right" rowspan="1" valign="top" nowrap class="dataTableRow" style="border: 1px solid #C9C9C9;">
                  <table border="0" cellspacing="0" cellpadding="2" width="100%" class="table">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" width="15" nowrap> <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<img src=\"images/icon_info.gif\" border= \"0\" width=\"13\" height=\"13\">");
	               //-->
                  </script></td>
                      <td class="dataTableHeadingContent" nowrap style="text-align: right;"><?php echo TABLE_HEADING_OT_TOTALS; ?></td>
                      <td class="dataTableHeadingContent" colspan="2" nowrap width="30%"><?php echo TABLE_HEADING_OT_VALUES; ?></td>
                    </tr>
<?php

  for ($i=0; $i<sizeof($order->totals); $i++) {
  
    $id = $order->totals[$i]['class'];
	
	if ($order->totals[$i]['class'] == 'ot_shipping') {
	   if (tep_not_null($order->info['shipping_id'])) {
	       $shipping_module_id = $order->info['shipping_id'];
		   } else {
		   //here we could create logic to attempt to determine the shipping module used if it's not in the database
		   $shipping_module_id = '';
		   }
	  } else {
	    $shipping_module_id = '';
	  } //end if ($order->totals[$i]['class'] == 'ot_shipping') {
	 
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ( ($order->totals[$i]['class'] == 'ot_total') || ($order->totals[$i]['class'] == 'ot_subtotal') || ($order->totals[$i]['class'] == 'ot_tax') || ($order->totals[$i]['class'] == 'ot_loworderfee') ) {
      echo '                  <tr class="' . $rowStyle . '">' . "\n";
      if ($order->totals[$i]['class'] != 'ot_total') {
        echo '                    <td class="dataTableContent" valign="middle" height="15">
		<script language="JavaScript" type="text/javascript">
		<!--
		document.write("<span id=\"update_totals['.$i.']\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');\"><img src=\"order_editor/images/plus.gif\" border=\"0\" alt=\"' . IMAGE_ADD_NEW_OT . '\" title=\"' . IMAGE_ADD_NEW_OT . '\"></a></span>");
		//-->
        </script></td>' . "\n";
      } else {
        echo '                    <td class="dataTableContent" valign="middle">&nbsp;</td>' . "\n";
      }
      
   if($order->totals[$i]['class'] =='ot_tax'){
      echo '<td align="right" class="dataTableContent">
	  <input type="hidden" style="width:200px;" name="update_totals['.$i.'][title]" value="' . trim($order->totals[$i]['title']) . '" readonly="readonly">
	  <label>'.trim($order->totals[$i]['title']).'</label></td>' . "\n";
	  } else {
	  echo '<td align="right" class="dataTableContent">
	  <input type="hidden" name="update_totals['.$i.'][title]" value="' . trim($order->totals[$i]['title']) . '" readonly="readonly">
	  <label>'.trim($order->totals[$i]['title']).'</label></td>';
	  }
	  
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td class="dataTableContent">&nbsp;</td>' . "\n";
      echo '                    <td align="left" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '<input name="update_totals['.$i.'][value]" type="hidden" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"></td>' . "\n" .
           '                  </tr>' . "\n";
    } else {
      if ($i % 2) {
        echo '                  	    <script language="JavaScript" type="text/javascript">
		<!--
		document.write("<tr class=\"' . $rowStyle . '\" id=\"update_totals['.$i.']\" style=\"visibility: hidden; display: none;\"><td class=\"dataTableContent\" valign=\"middle\" height=\"15\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i).']\', \'hidden\', \'update_totals['.($i-1).']\');\"><img src=\"order_editor/images/minus.gif\" border=\"0\" alt=\"' . IMAGE_REMOVE_NEW_OT . '\" title=\"' . IMAGE_REMOVE_NEW_OT . '\"></a></td>");
			 //-->
        </script>
			 
			 <noscript><tr class="' . $rowStyle . '" id="update_totals['.$i.']" >' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"></td></noscript>' . "\n";
      } else {
        echo '                  <tr class="' . $rowStyle . '">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15">
	    <script language="JavaScript" type="text/javascript">
		<!--
		document.write("<span id=\"update_totals['.$i.']\"><a href=\"javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');\"><img src=\"order_editor/images/plus.gif\" border=\"0\" alt=\"' . IMAGE_ADD_NEW_OT . '\" title=\"' . IMAGE_ADD_NEW_OT . '\"></a></span>");
		//-->
        </script></td>' . "\n";
      }

       if (ORDER_EDITOR_USE_AJAX == 'true') {
	  echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '" onChange="obtainTotals()" class="form-control"></td>' . "\n" .
           '                    <td align="right" class="dataTableContent">
           <input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '" size="6" onChange="obtainTotals()" class="form-control">
           <input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '">
           <input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
		   } else {
	  echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '"></td>' . "\n" .
           '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '" size="6"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"><input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
		   }
		   
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '</td>' . "\n";
      echo '                  </tr>' . "\n";
    }
  }
?>
                </table>
			  </td>
        
              <?php 
			  $check_save_query = tep_db_query("select sum(products_msrp) as msrptot, sum(final_price) as finprice, products_msrp, final_price from orders_products where orders_id= '".$oID."' and products_msrp<>final_price  and products_id<>3658");
			  $check_save= tep_db_fetch_array($check_save_query);
		      if ((!$check_save['products_msrp'] == NULL) && (!($check_save['products_msrp'] ==  $check_save['final_price']))) {
			  		$save_query = tep_db_query("select * from orders_products where orders_id= '".$oID."' and products_msrp <> final_price and products_id<>3658");
				  
				  echo '<div style="text-align:right; margin-right:10px;" id="savings" class="form-horizontal form-group"><span>You Save:  </span>$';
				  $savings = 0;
			  		while($save= tep_db_fetch_array($save_query)){
				  
				   $savings += $save['products_quantity'] * ($save['products_msrp'] - $save['final_price']);
			 			if($savings > 0 ){
			  			
			  			}
					}
					echo $savings.'</div>';
				}
			   $OT_query = tep_db_query("SELECT value FROM orders_total WHERE orders_id= '".$oID."' AND class = 'ot_total'");
$OT = tep_db_fetch_array($OT_query);
           
?>
<div class="column-12 form-group">
    <div class="row">
        <div class="column-6">
            <input class="form-control changeINPUT" placeholder="Cash Given" style="max-width:120px;">
            <div class="change" style="display:none;">
                <label class="thisLabel">Change: $</label>
            </div>
        </div>
    </div>
</div>

<script>
    var typingTimer;
    var doneTypingInterval = 10;
    var finaldoneTypingInterval = 200;

$('.changeINPUT').keydown(function () {
    clearTimeout(typingTimer);
    if ($('.changeINPUT').val) {
        typingTimer = setTimeout(function () {
            $(".thisLabel").get(0).nextSibling.remove();
            $("p.content").html('Typing...');
        }, doneTypingInterval);
    }
});

$('.changeINPUT').keyup(function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
    var data = $('.changeINPUT').val();
    var VaLue = ($(".changeINPUT").val() - <?php echo $OT['value']; ?>).toFixed(2);    
        $(".change").show();
        $(".thisLabel").after(VaLue);
    }, finaldoneTypingInterval);
});

</script> 
                
          </div>
                <!-- order_totals_eof //-->           

<div id="shipping-quote-block">
<label style="display:none;"><input type="checkbox"  /> Disable shipping modules</label>
<div class="accordion">
<div>
<input id="ac-1" name="accordion-1" <?php if(isset($_SESSION['disable_slow_shipping'])&&$_SESSION['disable_slow_shipping']) echo ' checked '; ?> type="checkbox" />
<label for="ac-1" id="turnonshipping showhideshipping" onClick="disableSlowShipping(this.checked);">Shipping Options</label>
<div class="article ac-full inner" style="display:none;">
                
<?php 
  if (sizeof($shipping_quotes) > 0) {
?>
                <!-- shipping_quote bof //-->
                <table width="100%" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_SHIPPING_QUOTES; ?></td>
                  </tr>
				  
				  				  
<?php
    $r = 0;
    for ($i=0, $n=sizeof($shipping_quotes); $i<$n; $i++) {
      for ($j=0, $n2=sizeof($shipping_quotes[$i]['methods']); $j<$n2; $j++) {
        $r++;
		if (!isset($shipping_quotes[$i]['tax'])) $shipping_quotes[$i]['tax'] = 0;
        $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        echo '                  <tr class="' . $rowClass . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')" onClick="selectRowEffect(this, ' . $r . '); setShipping(' . $r . ');">' .
             '                    <td class="dataTableContent" valign="top" align="left">
			 <script language="JavaScript" type="text/javascript">
                   <!--
                    document.write("<input type=\"radio\" name=\"shipping\" id=\"shipping_radio_' . $r . '\" value=\"' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'].'\">");
	               //-->
                  </script>
			 <input type="hidden" id="update_shipping[' . $r . '][title]" name="update_shipping[' . $r . '][title]" value="'.$shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'].'):">' . "\n" .
			 '      <input type="hidden" id="update_shipping[' . $r . '][value]" name="update_shipping[' . $r . '][value]" value="'.tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']).'">' . "\n" .
			 '      <input type="hidden" id="update_shipping[' . $r . '][id]" name="update_shipping[' . $r . '][id]" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'] . '">' . "\n" .
             '      <td class="dataTableContent" valign="top">' . $shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'] . '):</td>' . "\n" . 
             '      <td class="dataTableContent" align="right">' . $currencies->format(tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
             '                  </tr>';
      }
    }
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo sprintf(TEXT_PACKAGE_WEIGHT_COUNT, $shipping_num_boxes . ' x ' . $shipping_weight, $total_count); ?></td>
                  </tr>
                </table>
                </div></div></div></div>
                <!-- shipping_quote_eof //-->
<?php
  } else {
  echo AJAX_NO_QUOTES;
  echo '</div></div></div></div>';
  }
?>          
		  
		  </td>
				  </tr></table>
			    </td>
                       
             
			  
	  </div>
    </div>
    </div> <!-- this is end of the master div for the whole totals/shipping area -->
		      
	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?> 
    <!-- Begin Update Block, only for non-javascript browsers -->

	  <br>
            <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
           </div>
		  
	       <br>
            <div><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
	 
	 <!-- End of Update Block -->  
	 <?php } ?>
</div>   
    <div class="payment-method-block">	
<script>

function doUpdateDatePaid(){
	// https://jupiterkiteboarding.com/store/assend/edit_orders_ajax.php?action=update_order_field&oID=xxxx&field=customers_company&new_value=%D0%BB
	/*jQuery.ajax({
		url: 'edit_orders_ajax.php?action=update_order_field&oID=<?php echo (int)$_GET['oID']; ?>&field=date_paid&new_value='+jQuery('input[name="date_paid"]').val()
	});*/
	updateOrdersField('date_paid', encodeURIComponent(jQuery('input[name="date_paid"]').val()))
}
jQuery(document).ready(function(){
	jQuery('input[name="date_paid"]').change(function(){doUpdateDatePaid();});
});

function updateStatusField(id){
	jQuery('select#status').val(id);
	jQuery('input#notify').prop('checked',false);
	getNewComment();
	//updateOrdersField('orders_status', encodeURIComponent(id));
}
/*
updateDatepaid = function {
    xmlhttp=new XMLHttpRequest();
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
var PageToSendTo = "edit_orders.php?oID=<?php echo $oID; ?>&";
var MyVariable = document.getElementsByName("date_paid").value;
var VariablePlaceholder = "date_paid=";
var UrlToSend = PageToSendTo + VariablePlaceholder + VariablePlaceholder;

xmlhttp.open("GET", UrlToSend, false);
xmlhttp.send();
};*/
</script>
<?php
         /* if (isset($_GET['date_paid'])) {
	  $datepaid= $_GET['date_paid'];
          $datepaid = (date('Y-m-d') < $datepaid) ? $datepaid : 'null';
	  tep_db_query("update " . TABLE_ORDERS." set date_paid ='".$datepaid."' where orders_id ='".$oID."'"); 
	} */

    $order_date_paid_query = tep_db_query("SELECT orders_id, date_paid FROM " . TABLE_ORDERS . " WHERE orders_id = '" . (int)$oID . "'");
		   while ($order_date_paid = tep_db_fetch_array($order_date_paid_query)) {  
		if ((!is_null($order_date_paid['date_paid'])) && ($order_date_paid['date_paid']!='0000-00-00 00:00:00')) {
		 $date_paid = $order_date_paid['date_paid'];
		 } else {
		 $date_paid = '';
		}		
 }
?>
		
<link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">
<script language="JavaScript" src="js/moment.min.js"></script>		
<script language="JavaScript" src="js/bootstrap-datetimepickerv4.min.js"></script>

<div id="payment-method-block-inner" class="row">
    <div class="dataTableHeadingContent" style="background-color: #3D464E; text-align: center; padding:0.75rem; color:#fff; width:100%;">Payment History</div>
    <div id="payment-date-block" style="float:left; background-color: #F0F1F1; padding:0px;" class="col-xs-12">
        <div class="inner-pay-stuff" style="display:flex; flex-wrap:wrap;">
            <div class="column-12 column-sm-6 column-lg-7 column-xl-6 form-group">
                <div class="row">
                    <div class="col-xs-12 form-group">
						<div class="row">
                        	<label class="column-5 column-sm-4 column-lg-5 col-form-label" style="display:inline-block;">Date Paid<br/><small>(YYYY-MM-DD)</small>
							</label>
							<div class="column-7 column-sm-8 column-lg-7">
                            	<div id="datetimepicker" class="input-group">
                                	<input type="text" name="date_paid" id="datetimepicker-input" class="form-control"></input>
                                	<div class="input-group-append">
                                    	<span class="input-group-text">
											<i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar "></i>
										</span>
									</div>
								</div>
                            </div>
						</div>
    <script type="text/javascript">
		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();
		var output = d.getFullYear() + '-' +
(month<10 ? '0' : '') + month + '-' +
(day<10 ? '0' : '') + day;
		$("#datetimepicker-input").val(output);
        $('#datetimepicker-input').datetimepicker({
        	format: 'YYYY-MM-DD',
    })
    </script>
                    </div>
                    <div class="col-xs-12">
						<div class="row">
							<label class="column-6 column-sm-4 column-lg-5 col-form-label" style="display:inline-block;">Time Paid<br /><small>(HH:MM:SS)</small></label>
							<div class="column-6 column-sm-8  column-lg-7"><input id="time_paid" class="form-control" value="<?php echo $date_paid==''?date('H:i:s'):date('H:i:s',strtotime($date_paid)); ?>" style="max-width:90px;" />
							</div>
						</div>
                    </div>
				</div>
    		</div>
			<div class="column-12 column-sm-6 column-lg-5 column-xl-6">
				<div class="row">
         <?php
		    $total_paid_query = tep_db_query("select SUM(payment_value), SUM(tax_value) FROM " . TABLE_ORDERS_PAYMENT_HISTORY . " WHERE orders_id = '" . (int)$oID . "'");
			$total_paid =  tep_db_fetch_array($total_paid_query);
			$total_paid_contents .= number_format($total_paid['payment_value']);

  for ($i=0; $i<sizeof($order->totals); $i++) {
	
	if($order->info['date_purchased'] < '2017-06-25 00:00:00'){ 
	 	 $get_tax_rate_from_products_query = tep_db_query("select products_tax from orders_products where orders_id = '".$oID."'");
		 $get_tax_rate_from_products = tep_db_fetch_array($get_tax_rate_from_products_query);
		 
		 $tax_rate = $get_tax_rate_from_products['products_tax'];
		 	 
	 } else {
	 
		 $tax_query1 = tep_db_query("select tax_rate from tax_rates where tax_rates_id = '" . $order->delivery['zone_id'] . "'");
		 $tax1 = tep_db_fetch_array($tax_query1);
		 
		 /*$check_for_tax_query = tep_db_query("select * from orders_total where orders_id = ".$oID." and class = 'ot_tax'");
		 $check_for_tax = tep_db_fetch_array($check_for_tax_query); */
		 
		 if($order->info['delivery_location'] == '1'){
			$tax_rate = '7.00';
		  }
		  elseif($order->info['delivery_location'] == '2'){
			$tax_rate = $tax1['tax_rate'];
		  }
		  elseif($order->info['delivery_location'] == '3'){
			$tax_rate = '0.00';
		  }
		  elseif($order->info['delivery_location'] == ''){
			$tax_rate = '0.00';
		  }
	 }
	 
	if($total_paid['SUM(payment_value)'] > 0){
	$charged = @number_format($order->totals[$i]['value'] - $total_paid['SUM(payment_value)'], 2,'.','');
	$tax_num = @number_format(($order->totals[$i]['value'] - $total_paid['SUM(payment_value)']) - (($order->totals[$i]['value'] - $total_paid['SUM(payment_value)']) /  (1 + ($tax_rate / 100))) , 2,'.','');
	}
	else{$charged = @number_format($order->totals[$i]['value'], 2,'.','');
	$tax_num = @number_format(($order->totals[$i]['value'] - ($order->totals[$i]['value'] / (1 + ($tax_rate / 100)))) , 2,'.','');
	}	 
	 
    $id = $order->totals[$i]['class'];
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ( ($order->totals[$i]['class'] == 'ot_total') /*||  ($order->totals[$i]['class'] == 'ot_loworderfee')*/ ) {
      echo '<div class="column-12 form-group">
	  			<div class="row">
	  				<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Amount Charged</label>
	  				<div class="column-6 column-sm-5"><input style="width:90px; display:inline-block;" class="form-control" id="paytotalbox" value="'. $charged . '" onChange="refreshTax();"></input>
					</div>
				</div>
			</div>
			<div class="col-xs-12 form-group">
				<div class="row">
					<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Tax Charged</label>
					<div class="column-6 column-sm-5"><input style="width:90px; display:inline-block;" class="form-control" id="taxtotalbox" value="'. $tax_num . '"></input>
					</div>
				</div>
			</div>
	  		<div class="col-xs-12 form-group">
				<div class="row">
					<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Tax Rate</label>
					<div class="column-6 column-sm-5 column-lg-7 column-xl-5"><input style="width:60px; display:inline-block;" class="form-control" id="taxrate" value="'.$tax_rate.'"> %
					</div>
					<input name="update_totals_xx['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '">
				</div>
	  		</div>
			</div>
	  </div>
	  <div class="column-12 column-sm-6 column-md-4 column-lg-12 column-xl-12">
	  	<div class="row">	  ';	
	  echo '<div class="col-xs-12 column-xl-6 form-group"><b>Current Total Paid:</b>&nbsp;&nbsp;'.'
	  <div style="width:90px; display:inline-block;">$'.number_format( $total_paid['SUM(payment_value)'], 2,'.','').'</div>
	  </div>
	  <div class="col-xs-12 column-xl-6"><b>Current Tax Paid:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'
	  <div style="width:90px; display:inline-block;">$'.number_format( $total_paid['SUM(tax_value)'], 2,'.','').'
	  </div></div>
	  </div>';

 ?>
     <script>
	 function refreshTax(){
	if ($("#paytotalbox").val() !== <?php echo @number_format($order->totals[$i]['value'], 2,'.',''); ?>)
	var newval = $("#paytotalbox").val() - ($("#paytotalbox").val() / (1 + <?php echo ($tax1['tax_rate'] / 100) ;?>));
	$("#taxtotalbox").val(newval.toFixed(2));
	 }
	 </script>
     
     <?php
     }
  }
?>
</div>
</div>
    <script>
	 function doUpdateDatePaid(){
	
	 }
	

function updatePaymentStatusField(id){
	jQuery('select#paymentstatus').val(id);
	getNewComment();
	//updateOrdersPaymentHistoryField('payment_type_id', encodeURIComponent(id));
}

function updateOrdersPaymentHistoryField(payment_type_id){
	var data = {
		action: 'update_order_payment_history',
		orders_id: <?php echo (int)$oID; ?>,
		date_paid: jQuery('input[name="date_paid"]').val(),
		time_paid: jQuery('#time_paid').val(),
		payment_type_id: payment_type_id,
		payment_value: jQuery('#paytotalbox').val(),
		tax_rate: jQuery('#taxrate').val(),
		tax_value: jQuery('#taxtotalbox').val(),
		payment_comments: jQuery('#payment-comments').val()
	};
	
	jQuery.ajax({
		url: 'edit_orders_ajax.php',
		type:'POST',
		data: data,
		success: function(html){
			jQuery('#commentsTable_wrap').html(html);
			jQuery('#payment-comments').val('');
			$(".payment-method-block").load('payment_history_ajax.php?oID=<?php echo $_GET['oID']; ?>');
		}
	});
}

function deletePaymentHistory(id,obj){
	if(confirm('Are you sure?')){
		jQuery(obj).parent().parent().parent().remove();
		var data = {
			action: 'delete_order_payment_history',
			orders_id: <?php echo (int)$oID; ?>,
			orders_payment_history_id: id
		};
		
		jQuery.ajax({
			url: 'edit_orders_ajax.php',
			type:'POST',
			data: data,
			success: function(html){
                 $('#commentsTable_wrap').html(html);
                $(".payment-method-block").load('payment_history_ajax.php?oID=<?php echo $_GET['oID']; ?>');
            }
		});
	}else{
		obj.checked=false;
	}
}

function savePHC(id,comment){
	var data = {
		action: 'save_order_payment_history_comment',
		orders_id: <?php echo (int)$oID; ?>,
		orders_payment_history_id: id,
		comment: comment
	};
	
	jQuery.ajax({
		url: 'edit_orders_ajax.php',
		type:'POST',
		data: data,
		success: function(html){ }
	});
}

function disableSlowShipping(state){
	var data = {
		action: 'disable_slow_shipping',
		state: state
	};
	
	jQuery.ajax({
		url: 'edit_orders_ajax.php',
		type:'POST',
		data: data,
		success: function(html){ obtainTotals(); }
	});
}

</script>
	
<div class="col-xs-12">&nbsp;</div>
    <div style="text-align:center; width:100%; margin:0px auto 20px; overflow:auto;" id="payment-status" name="payment-status">
         <div class="form-group"><textarea name="payment-comments" cols="20" rows="3" placeholder="Payment Comments" class="form-control" id="payment-comments" style="width:60%; margin:0px auto;"></textarea></div>
         <div class="col-xs-12">
             <div class="row">
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(1)">Paid Credit</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(2)">Paid Debit</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(3)">Paid Cash</button></div> 
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(4)">Paid Paypal</button></div>
				 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(10)">Paid Ebay</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(7)">Paid Check</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(8)">Amazon Order</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(9)">Warranty</button></div>
             </div>
         </div>
    </div>
    
    <div id="commentsTable_wrap">
         <table style="border: 1px solid #C9C9C9; width:100%; background-color:transparent;" cellspacing="0" cellpadding="2" class="dataTableRow table" id="commentsTable">
            <thead>
                <tr class="dataTableHeadingRow">
                    <th class="dataTableHeadingContent" align="left" style="width:10%">Delete?</th>
                    <th class="dataTableHeadingContent" align="center" style="width:22%">Date Paid</th>
                    <th class="dataTableHeadingContent" align="center" style="width:20%">Payment</th>
                    <th class="dataTableHeadingContent" align="center" style="">Amount</th>
                    <th class="dataTableHeadingContent" align="center" style="width:30%">Comments</th>
                </tr>
            </thead>
    
     <?php
	 $payment_history_query = tep_db_query("SELECT  orders_payment_history_id, date_paid, payment_type_id, payment_value, payment_comments, tax_value FROM " . TABLE_ORDERS_PAYMENT_HISTORY . " WHERE orders_id = '" . (int)$oID . "' order by orders_payment_history_id desc ");
     
        if (tep_db_num_rows($payment_history_query)) {
          while ($payment_history = tep_db_fetch_array($payment_history_query)) {
          
		   $r++;
           $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        
	      if (ORDER_EDITOR_USE_AJAX == 'true') { 
		   echo '  <tr class="' . $rowClass . '" id="commentRow' . $payment_history['orders_payment_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="" align="center"><div ><input name="update_comments[' . $payment_history['orders_payment_history_id'] . '][delete]" type="checkbox" onClick="deletePaymentHistory( \'' . $payment_history['orders_payment_history_id'] . '\',  this)"></div></td>' . "\n" . 
		
         '    <td class="" align="center">' . tep_datetime_short($payment_history['date_paid']) . '</td>' . "\n";
		 } else {
		 echo '  <tr class="' . $rowClass . '" id="commentRow' . $payment_history['orders_payment_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="" align="center"><div ><input name="update_comments[' . $payment_history['orders_payment_history_id'] . '][delete]" type="checkbox"></div></td>' . "\n" . 
         '    <td class="" align="center">' . tep_datetime_short($payment_history['date_paid']) . '</td>' . "\n";
        
		 }
       
	    echo 
             '    <td class="" align="center">' . $payment_status_array[$payment_history['payment_type_id']] . '</td>' . 
			  '<td class="" align="center">'.$currencies->format($payment_history['payment_value'],'').'</td>';
        echo 
             '    <td class="" align="left"><textarea cols="25" rows="4" onchange="savePHC(' . $payment_history['orders_payment_history_id'] . ',this.value);">';
				echo htmlspecialchars($payment_history['payment_comments']);
				echo '</textarea>';
       /* if (ORDER_EDITOR_USE_AJAX == 'true') { 
		echo tep_draw_textarea_field(" update_comments[" . $payment_history['orders_payment_history_id'] . "][payment_comments]", "soft", "40", "5", 
  "" .	tep_db_output($payment_history['payment_comments']) . "", "onChange=\"updateCommentsField('update', '" . $payment_history['orders_payment_history_id'] . "', 'false', encodeURIComponent(this.value))\",''") . '' ."\n";
		 } else {
		 echo tep_draw_textarea_field("update_comments[" . $payment_history['orders_payment_history_id'] . "][payment_comments]", "soft", "40", "5", 
  "" .	tep_db_output($payment_history['payment_comments']) . "") . '' . "\n";
		 }*/
		echo  '    </td>' . "\n";
        echo '  </tr>' . "\n";
  
        }
       } else {
       echo '  <tr>' . "\n" .
            '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
            '  </tr>' . "\n";
       }
           
           $get_order_total_query = tep_db_query("SELECT value FROM orders_total WHERE class='ot_total' AND orders_id = '".$_GET['oID']."' ");
           $get_order_total = tep_db_fetch_array($get_order_total_query);
           
           $get_total_paid_query = tep_db_query("SELECT SUM(payment_value) as total FROM orders_payment_history WHERE orders_id = '".$_GET['oID']."'");
           $get_total_paid = tep_db_fetch_array($get_total_paid_query);

           //if($verified_status == '0' || $get_order_total['value'] > $get_total_paid['total'] || $check_if_delivered['orders_status'] == '' || $check_if_delivered['orders_status'] == '4' || $check_if_delivered['orders_status'] == '109' ){
           // $display = 'display:none;'; 
          // }   

           if($get_order_total['value'] > $get_total_paid['total'] || $check_if_delivered['orders_status'] == '' || $check_if_delivered['orders_status'] == '4' || $check_if_delivered['orders_status'] == '109' ){
            $display = 'display:none;'; 
           } 

    ?>
    
      

  </table> 
  </div>
         
	 
         </div></div>
	 
 <br /> <br /> 
	 
 <h3>SigniFyd API Calls for Orderid - <?php echo $oID; ?></h3> 
	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/checkoutapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Checkout API CALL</a></button>'; ?></div>	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/transactionapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Transaction API CALL</a></button>'; ?></div>	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/rerouteapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Reroute API CALL</a></button>'; ?></div>	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/repriceapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">RePrice API CALL</a></button>'; ?></div>	 

<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/fullfillmentapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">FullFillMent API CALL</a></button>'; ?></div>	 
	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/chargebackapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Chargeback API CALL</a></button>'; ?></div>	 
	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/representmentoutcomeapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Representment Outcome API CALL</a></button>'; ?></div>	 

<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/chargebackstageapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Chargeback Stage API CALL</a></button>'; ?></div>	 
	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/attemptreturnapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Attempt Return API CALL</a></button>'; ?></div>	 
	 
<div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" target="_NEW" href="https://jupiterkiteboarding.com/store/assend/executereturnapicall.php?oID=' . $_GET['oID'].'" onClick="return confirm(\'Are You Sure?\');">Execute Return API CALL</a></button>'; ?></div>	 
	 
	 
 </div> 
    
     
  		<div class="newstatus-comments col-xs-6">
	   		<div class="row">
     			<div style="border: 1px solid #C9C9C9; float:right; width:100%;" class="dataTableRow" >
					<div class="dataTableHeadingRow">
   						<div class="dataTableHeadingContent" style="background-color: #3D464E;text-align: center; padding:0.75rem; color:#fff;"><?php echo 'New Status/ Comments' ?></div>
					</div>
		  			<div class="newstatus-inner column-12 column-xl-7">
           				<div class="form-group">
			   				<textarea name="comments" cols="40" rows="5" placeholder="Status Comments" class="form-control" id="comments"></textarea>
		   				</div>

           			<div class="new-status-options-submit" >
                    	<div class="form-horizontal">
							<div class="form-group">
								<input class="form-control" id="tracking-input1" name="update_fedex_track_num" size="24" placeholder="FedEx Tracking #" value="<?php echo stripslashes($order->info['fedex_track_num']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('fedex_track_num', encodeURIComponent(this.value))"<?php } ?>>
<?php 
if ($order->info['fedex_track_num'] !== NULL){
	echo '<a onclick="return !window.open(this.href);" href="https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber='.$order->info['fedex_track_num'].'&cntry_code=us"><b>Track FedEx</b></a>';
}  ?>      					</div>
							<div class="form-group">
								<input class="form-control" id="tracking-input2" name="update_usps_track_num" size="24" placeholder="USPS Tracking #" value="<?php echo stripslashes($order->info['usps_track_num']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('usps_track_num', encodeURIComponent(this.value))"<?php } ?>>
<?php if ($order->info['usps_track_num'] !== NULL){
	$carrier = '<a onclick="return !window.open(this.href);" href="https://tools.usps.com/go/TrackConfirmAction?tLabels='.$order->info['usps_track_num'].'"><b>Track USPS</b></a>';
    } ?>   
							</div>    
							<div class="form-group">
								<input class="form-control" id="tracking-input3" name="update_ups_track_num" size="24" placeholder="UPS Tracking #" value="<?php echo stripslashes($order->info['ups_track_num']); ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateOrdersField('ups_track_num', encodeURIComponent(this.value))"<?php } ?>>
<?php if ($order->info['ups_track_num'] !== NULL){
	$carrier = '<a onclick="return !window.open(this.href);" href="https://wwwapps.ups.com/WebTracking/track?trackNums='.$order->info['ups_track_num'].'&track.x=Track"><b>Track UPS</b></a>';
} ?>   
							</div>
						</div>
<style>
.disabled{pointer-events:none; background:#ddd !important; color:#ddd !important; opacity:0.4;}
</style>

<script>
$('#tracking-input1, #tracking-input2, #tracking-input3').focus(
function(){
$("#send-tracking").addClass('disabled');
}
);
$('#tracking-input1, #tracking-input2, #tracking-input3').blur(
function(){
$("#send-tracking").removeClass('disabled');
}
);
</script>
  		<div class="form-inline form-group">
                     <div class="column-5"><b><?php echo ENTRY_STATUS; ?></b></div>
                     <div class="main column-7"><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status'], 'id="status" class="form-control" style="width:100%;"'); ?></div>
                </div>

                <div class="form-inline form-group">
                    <div class="col-xs-6"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b></div>
                    <div class="main col-xs-5" style="text-align:center;"><?php echo oe_draw_checkbox_field('notify', '', false, '', 'id="notify"'); ?></div>
               </div>

                <div class="form-inline form-group">
                    <div class="col-xs-6"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></div>
                    <div class="main col-xs-5" style="text-align:center;"><?php echo oe_draw_checkbox_field('notify_comments', '', false, '', 'id="notify_comments"'); ?></div>
                </div>
               <div class="col-xs-12">
                   <div class="main form-group" ><?php echo '<a class="btn btn-sm btn-info"  href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank"><i class="fa fa-envelope-o" style="margin-right:5px;"></i>Send Invoice</a>'; ?></div>
                   
                   <!-- <div class="main form-group" ><?php echo '<button class="btn btn-sm btn-info" type="button" onClick="sendQuote(122)"><a style="color:#FFF;" href="' . tep_href_link('quote.php', 'oID=' . $_GET['oID']. '') . '" TARGET="_blank"><i class="fa fa-envelope-o" style="margin-right:5px;"></i>Send Quote</a></button>'; ?></div> -->
                   
                   <div class="main form-group shipping-button"  style="<?php echo $display; ?>"><?php echo '<button id="send-tracking"  class="btn btn-sm btn-info"  type="button" onClick="sendTracking(112)"><a style="color:#FFF;" href="' . tep_href_link('tracking.php', 'oID=' . $_GET['oID']. '') . '" TARGET="_blank"><i class="fa fa-envelope-o" style="margin-right:5px;"></i>Send Tracking Info</a></button>'; ?></div>
                   
                   <div class="main form-group" ><?php echo '<button class="btn btn-sm btn-info"  type="button"><a style="color:#FFF;" href="' . tep_href_link('gift_certificate.php', 'oID=' . $_GET['oID']. '') . '" TARGET="_blank"><i class="fa fa-envelope-o" style="margin-right:5px;"></i>Gift Certificate</a></button>'; ?></div>
                   
                   <div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" onClick="toggleOverlay();">Consignment Agreement</a></button>'; ?></div>
                   <div class="main form-group"><?php echo '<button class="btn btn-sm btn-info" type="button"><a style="color:#FFF;" onClick="toggleOverlayKite();">Kite Lesson</a></button>'; ?></div>
                   
			   </div>
   
     </div>
    
	
    <div style="width:100%; text-align:center;">
        <button class="btn btn-sm btn-outline-info form-group" type="button" name="comments_button" onClick="getNewComment();">Submit New Comments/Status</button>
        
        <button class="btn btn-sm btn-outline-info form-group delivered-button" style="margin-left:20px; <?php echo $display; ?>" type="button" onClick="updateStatusField(3)">Delivered</button>
    <div><div class="form-group">&nbsp;</div>
    
    <div style="display:none;"><button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:20px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(123)">Awaiting Kite Lesson</button>
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:40px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(118)">Demo Gear Out</button>
    
    <button class="btn form-group" style="float:left; width:160px; display:inline-block; margin-left:0px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="happyEnding();">Awaiting Happy Ending</button>
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:40px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(116)">On the Water</button>	
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:20px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(125)">Awaiting Paddle Tour</button>
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:40px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(127)">Rental Reservation</button>
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:20px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(115)">Awaiting Repair</button>
    
    <button class="btn form-group" style="float:left; width:140px; display:inline-block; margin-left:40px; margin-bottom:15px !important; background:#ccc !important; color:#000 !important;" type="button" onClick="JupdateStatus(129)">Special Order</button>
		</div>
    
    </div>	
    </div>				  
</div></div>
	<div style="width:99%; float:right;" id="commentsBlock">
	<table class="dataTableRow table table-striped" id="commentsTable" width="100%">
		<thead>
			<tr class="dataTableHeadingRow">
      			<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DELETE; ?></td>
      			<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
      			<td class="dataTableHeadingContent" align="center">Customer Notified</td>
      			<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
      			<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
    		</tr>
		</thead>
    
    <script>
	function JupdateStatus(statusid){
		var data = {
			action: 'update_order_status_history',
			orders_id: <?php echo (int)$oID; ?>,
			orders_status_id: statusid,
			comments: jQuery('#comments').val()
		};

		jQuery.ajax({
			url: 'edit_orders_ajax.php',
			type:'POST',
			data: data,
			success: function(data){
				$('#commentsBlock').html(data);
			}
		});
	}	
		
	</Script>
    <tbody>
    <?php
      $orders_history_query = tep_db_query("SELECT orders_status_history_id, orders_status_id, date_added, customer_notified, comments 
                                            FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
									        WHERE orders_id = '" . (int)$oID . "' 
									        ORDER BY date_added");
        if (tep_db_num_rows($orders_history_query)) {
          while ($orders_history = tep_db_fetch_array($orders_history_query)) {
		   echo '  <tr class="' . $rowClass . '" id="commentRow' . $orders_history['orders_status_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="align-middle" align="center"><div id="do_not_delete"><input name="update_comments[' . $orders_history['orders_status_history_id'] . '][delete]" type="checkbox" onClick="updateCommentsField(\'delete\', \'' . $orders_history['orders_status_history_id'] . '\', \''.$oID.'\', \'\', this)"></div></td>' . "\n" . 
		
         '    <td class="align-middle" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
    
         '    <td class="align-middle" align="center">';
      
	  if ($orders_history['customer_notified'] == '4') {
      echo '<i class="fa fa-quote-right" style="margin-right:5px;"></i><i class="fa fa-envelope-o"  style="margin-right:5px;"></i></td>'."\n";
    }	 
    elseif ($orders_history['customer_notified'] == '3') {
      echo '<i class="fa fa-envelope-o" style="margin-right:5px;"></i></td>'."\n";
    } 
	 elseif ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    }else {
      echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
    }
      if($orders_history['orders_status_id'] == 1){
        $braintreeId = $order->info['payment_nonce'] ? ' <br><p style="font-size:9;"> Braintree ID:#' . $order->info['payment_nonce'] : '</p>' ;
      }else {
        $braintreeId = '';
      }
      
	    echo '<td class="align-middle" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . $braintreeId.'</td>' . "\n";
      echo '    <td class="align-middle" align="left">';
  
        if (ORDER_EDITOR_USE_AJAX == 'true') {
		if ($orders_history['customer_notified'] == '4') {echo  '<div style="display:table; padding:10px;">'.nl2br($orders_history['comments']).'</div>'; }
		elseif ($orders_history['comments'] =='Tracking Sent' ){echo  '<div style="display:table; padding:25px; width:100%;">Tracking Sent</div>'; }
		 else{	 
		echo tep_draw_textarea_field("update_comments[" . $orders_history['orders_status_history_id'] . "][comments]", "soft", "40", "5", 
  "" .	tep_db_output($orders_history['comments']) . "", "onChange=\"updateCommentsField('update', '" . $orders_history['orders_status_history_id'] . "', 'false', encodeURIComponent(this.value))\"") . '' . "\n" .
		 '    </td>' . "\n";
		 }} else {
		 echo tep_draw_textarea_field("update_comments[" . $orders_history['orders_status_history_id'] . "][comments]", "soft", "40", "5", 
  "" .	tep_db_output($orders_history['comments']) . "") . '' . "\n" .
		 '    </td>' . "\n";
		 }
 
        echo '  </tr>' . "\n";
  
        }
       } else {
       echo '  <tr>' . "\n" .
            '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
            '  </tr>' . "\n";
       }

    ?>
  </table> 
  </div>
	   		</div>
		</div>
	  
				  
      <div>
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?>
	  </div>
      

	
		
     <div style="margin-bottom:20px;">
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	</div>
    
	<!-- End of Status Block -->

	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?> 
	<!-- Begin Update Block, only for non-javascript browsers -->
	       <div class="updateBlock">
              <div class="update1"><?php echo HINT_PRESS_UPDATE; ?></div>
              <div class="update2">&nbsp;</div>
              <div class="update3">&nbsp;</div>
              <div class="update4" align="center"><?php echo ENTRY_SEND_NEW_ORDER_CONFIRMATION; ?>&nbsp;<?php echo tep_draw_checkbox_field('nC1', '', false); ?></div>
              <div class="update5" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
          </div>
		  
	       <br>
           <div style="margin-bottom:20px;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
	
	<!-- End of Update Block -->
	<?php   }  //end if (ORDER_EDITOR_USE_AJAX != 'true') {
          echo '</form>';
        }
    ?>
  <!-- body_text_eof //-->
      </td>
    </tr>
  </table>
  
  </div>

  
<script src="ext/jquery/ui/controller_order.js"></script>
  <!-- body_eof //-->
  <!-- footer //-->
  <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
  <!-- footer_eof //-->
 </div>
<style>
    #verification-steps li{list-style: none; margin-bottom: 10px;}
    #double-check{
        display: none;
        background-color: #eee;
        border-radius: 5px;
        border: 1px solid #aaa;
        position: absolute;
        top:30%;
        width: 300px;
        left:3%;
        padding: 6px 8px 8px;
        box-sizing: border-box;
        text-align: center;
    }
    
    #double-check .button{margin:10px; display: inline-block;}
    
    .show-overlay #kiteless-container{
      position: fixed;
    width: 80%;
    border: 1px solid;
    left: 10%;
    top: 5%;
    background: #fff;
    padding: 30px;
    z-index: 1000000;
    
</style>
 <div id="consignment-container">
 </div> 

<div id="rental-container">
</div>

<div id="kiteLesson-container">
</div>
 
<?php $current_date = new DateTime();
	$current_date->format('Y-m-d');
	$check_date = new DateTime($order->info['date_purchased']);
	  $date->modify('+1 week');
echo $check_date1 = $date->format('Y-m-d') . "\n";

if( $order->info['orders_status'] == '3' || $order->info['orders_status'] == '4' || $order->info['orders_status'] == '112'){ ?>
	<script>
		$("#optional").find("tr").remove();
		
	</script>	
<?php 	
}
?>

  <script>
     $('#verified').click(function(){
         $('#verification-steps').toggle();
     })
    $('#verification-steps li').click(function(){
                
     var step = $(this).find('input').val();
     
     if (step === 'email'){
         msg = 'Did you receive confirmation?';
     } 
     if(step === 'phone'){
         msg = 'Did you talk to the person and they approved of this order?'
     }
    if(step === 'facebook'){
       msg = 'Did the person add us on Facebook? ';
    }
    if (step === 'yup'){
        $msg = 'good job';
    }    
    if(step === 'optout'){
        msg = 'You sure the customer is legit?';
    }    
        
    var confirmBox = $('#double-check');
    confirmBox.find(".yes,.no").unbind().click(function () {
            confirmBox.hide();
    });
    $(".no").click(function(e){
        e.preventDefault();
        $('#verification-steps input:checkbox').removeAttr('checked');
        confirmBox.hide();
    })
    $(".yes").click(function(e){
        e.preventDefault();
        var data = {
			action: 'update_customer_verify',
			orders_id: <?php echo (int)$oID; ?>,
			verify: '1'
		};

		jQuery.ajax({
			url: 'edit_orders_ajax.php',
			type:'POST',
			data: data,
			success: function(data){
                $('#verified-container').html(data);
			}
		});
    })    
    confirmBox.find(".message").text(msg);
    confirmBox.show();   
        
    }); 
     
    $("#nobueno").click(function(e){
     var data = {
			action: 'update_customer_verify',
			orders_id: <?php echo (int)$oID; ?>,
			verify: '0'
		};

		jQuery.ajax({
			url: 'edit_orders_ajax.php',
			type:'POST',
			data: data,
			success: function(data){
               $('#verified-container').html(data);
			}
        });
    }); 
	function toggleOverlay () {
		var data = $("#edit_order").serialize();
	$.ajax({
  type : 'POST',
  url  : 'consign-agreement.php?oID=<?php echo $oID; ?>',
  data : data,
  success :  function(data) {
	 $("#consignment-container").html(data);
	  }  
  });	
		
		
    var overlay = document.querySelector("body");
    overlay.classList.toggle('show-overlay');
}
	
    function toggleOverlay2() {
        var data = $("#edit_order").serialize();
        $.ajax({
            type : 'POST',
            url  : 'rental-agreement.php?oID=<?php echo $oID; ?>',
            data : data,
            success :  function(data) {
                $("#rental-container").html(data);
            }  
    });	


    var overlay = document.querySelector("body");
    overlay.classList.toggle('show-overlay');
    }
    
    function toggleOverlayKite () {
        var data = $("#edit_order").serialize();
        $.ajax({
            type : 'POST',
            url  : 'kite-lessons-info.php?oID=<?php echo $oID; ?>',
            data : data,
            success :  function(data) {
                $("#kiteLesson-container ").html(data);
            }
        });

        var overlay = document.querySelector("body");
        overlay.classList.toggle('show-overlay');
    }
	
	 var body = document.body,
        overlay = document.querySelector('.overlay'),
        overlayBtts = document.querySelectorAll('button[class$="overlay"]');
        
    [].forEach.call(overlayBtts, function(btt) {

      btt.addEventListener('click', function() { 
         
         /* Detect the button class name */
         var overlayOpen = this.className === 'open-overlay';
         
         /* Toggle the aria-hidden state on the overlay and the 
            no-scroll class on the body */
         overlay.setAttribute('aria-hidden', !overlayOpen);
         body.classList.toggle('noscroll', overlayOpen);
         
         /* On some mobile browser when the overlay was previously
            opened and scrolled, if you open it again it doesn't 
            reset its scrollTop property after the fadeout */
         setTimeout(function() {
             overlay.scrollTop = 0;              }, 1000)

      }, false);

    });
$("#showhideshipping").click(function(){
         $("#showhideshipping").toggleClass('show');
     })
	  
$('.cust-details').on("click", function(){	 
	$('.show-products').show();
});

$('.prod-details').on("click", function(){	 
	$('.show-products').hide();
});	  
	 
$('.show-products').on("click", function(){
	$('.show-products').hide();
	$('.nav-item a').removeClass("active");
	$('.prod-details a').addClass("active");
	$('#tab1').show();
	$('#tab2').hide();
	
	$('html, body').animate({
		scrollTop: $("#tab1").offset().top-100
	})
})	 	  
	  
	</script>
  </body>
  </html>
  <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
