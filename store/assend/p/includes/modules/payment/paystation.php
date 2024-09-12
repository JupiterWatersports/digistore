<?php
/*
  paystation.php, v2.2 MS2 2004/06/28   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com
   http://www.digistore.co.nz   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/


  class paystation {
    var $code, $title, $description, $enabled;

// class constructor
    function paystation() {
      global $order;

      $this->code = 'paystation';
      $this->title = MODULE_PAYMENT_PAYSTATION_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYSTATION_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYSTATION_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYSTATION_STATUS == 'True') ? true : false);
      if ((int)MODULE_PAYMENT_PAYSTATION_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYSTATION_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

	  $this->form_action_url =  "https://www.paystation.co.nz/dart/darthttp.dll";
    }

// class methods
    function update_status() {
      global $order;

// disable the module if  free downloads
      if ($this->enabled == true) {
        global $cart;
        if ($cart->show_total() == 0.00) {
          $this->enabled = false;
        }
      }
    }

// this method returns the javascript that will validate the form entry
    function javascript_validation() {
      return false;
    }

 // this method returns the html that creates the input form
    function selection() {
		if (MODULE_PAYMENT_PAYSTATION_ALLOW_EPS == 'True') {
			if (MODULE_PAYMENT_PAYSTATION_ACCEPT_VISA == 'True') {
				$creditCardTypes[] = array('id' => 'visa', 'text' => 'Visa');
			}
			if (MODULE_PAYMENT_PAYSTATION_ACCEPT_MASTERCARD == 'True') {
				$creditCardTypes[] = array('id' => 'mastercard', 'text' => 'MasterCard');
			}
			if (MODULE_PAYMENT_PAYSTATION_ACCEPT_AMEX == 'True') {
				$creditCardTypes[] = array('id' => 'amex', 'text' => 'American Express');
			}
			if (MODULE_PAYMENT_PAYSTATION_ACCEPT_DINERS == 'True') {
				$creditCardTypes[] = array('id' => 'diners', 'text' => 'Diners Club');
			}
			$selection=array('id' => $this->code,
			         'module' => $this->title,
			         'fields' => array(array('title' => MODULE_PAYMENT_PAYSTATON_TEXT_CT,
				                             'field' => tep_draw_pull_down_menu('pstn_ct', $creditCardTypes))));
		} else {
			$selection=array('id' => $this->code,
			         'module' => $this->title);
		}
		return $selection;
    }

// this method is called before the data is sent to the credit card processor
// here you can do any field validation that you need to do
// we also set the global variables here from the form values
    function pre_confirmation_check() {
      return false;
    }

// this method returns the data for the confirmation page
    function confirmation() {
		$paystation_return = urlencode($HTTP_POST_VARS['payment'] . '|' . $HTTP_POST_VARS['sendto'] . '|' . $shipping_cost . '|' . urlencode($shipping_method) . '|' . urlencode($comments) . '&' . SID);
		$checkout_form_action = $paystation;
		return false;
    }

// this method performs the authorization by sending the data to the processor, and getting the result
    function process_button() {
	 global $HTTP_POST_VARS, $HTTP_GET_VARS, $order;
		//================================================================================
		function makePaystationSessionID($min=8,$max=8){
		  # seed the random number generator - straight from PHP manual, dunno what it does
		  $seed = (double)microtime()*getrandmax();
		  srand($seed);
		  # make a string of $max characters with ASCII values of 40-122
		  $p=0; while ($p < $max):
			$r=123-(rand()%75);
			$pass.=chr($r);
		  $p++; endwhile;
		  # get rid of all non-alphanumeric characters
		  $pass=ereg_replace("[^a-zA-NP-Z1-9]+","",$pass);
		  # if string is too short, remake it
		  if (strlen($pass)<$min):
			$pass=makePaystationSessionID($min,$max);
		  endif;

			return $pass;
		};
		//================================================================================
		$pass=makePaystationSessionID(8,8);
		$found_good_ms = false;

		while(!$found_good_ms){
		//do ms prefix
			$tempSession = ((MODULE_PAYMENT_PAYSTATION_MS_PREFIX=='')?'':MODULE_PAYMENT_PAYSTATION_MS_PREFIX.'-').$pass;

			# if it's alread in the database, remake it
			$check_pstn_ms_is_unique = tep_db_query("select pstn_ms from " . TABLE_ORDERS . " where pstn_ms='$tempSession'");
			if (tep_db_num_rows($check_pstn_ms_is_unique) > 0){
				$pass=makePaystationSessionID($min,$max);
			}else{
				$found_good_ms = true;
			}
		}

		$psamount=(number_format($order->info['total'], 2, '.', '')*100);

		echo '<input type="hidden" name="paystation" value="">';
		echo '<input type="hidden" name="am" value="'.$psamount.'">';
		echo '<input type="hidden" name="pi" value="'.MODULE_PAYMENT_PAYSTATION_ID.'">';
		echo '<input type="hidden" name="ms" value="'.$tempSession.'">';
		echo '<input type="hidden" name="merchant_ref" value="'.$order->customer['email_address'].'">';
		echo tep_hide_session_id();


		if (MODULE_PAYMENT_PAYSTATION_ALLOW_EPS == 'True') echo '<input type="hidden" name="ct" value="'.urlencode($HTTP_POST_VARS['pstn_ct']).'">';
		if (MODULE_PAYMENT_PAYSTATION_TESTMODE == 'Test') echo '<input type="hidden" name="tm" value="T">';
		return false;
    }

// this method gets called after the processing is done but before the app server
// accepts the result. It is used to check for errors.
    function before_process() {
		global $HTTP_POST_VARS, $HTTP_GET_VARS, $order;
		if ($HTTP_GET_VARS['ec']!='0'){
			#Error in the Transaction
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message='.urlencode($HTTP_GET_VARS['em']), 'SSL', true, false));
		}
		return false;
    }

    function after_process() {
		global $HTTP_POST_VARS, $HTTP_GET_VARS, $insert_id;
		tep_db_query("update " . TABLE_ORDERS . " set pstn_ms = '".$HTTP_GET_VARS['ms']."', pstn_ti = '".$HTTP_GET_VARS['ti']."', pstn_ec = ".$HTTP_GET_VARS['ec'].", pstn_em = '".$HTTP_GET_VARS['em']."' where orders_id = ".$insert_id );
		if ((defined('MODULE_PAYMENT_PAYSTATION_SEND_PAYMENT_EMAIL')) && (tep_validate_email(MODULE_PAYMENT_PAYSTATION_SEND_PAYMENT_EMAIL)) ) {
			$message = "osCommerce order number: ".$insert_id."\n\nMerchant session (ms): ".$HTTP_GET_VARS['ms']."\nPaystation order number (ti): ".$HTTP_GET_VARS['ti']."\nError code (ec): ".$HTTP_GET_VARS['ec']."\nError message (em): ".$HTTP_GET_VARS['em']."\n\nThank you for choosing Paystation!";
			tep_mail('', MODULE_PAYMENT_PAYSTATION_SEND_PAYMENT_EMAIL, 'Paystation information for order ' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		}
		return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYSTATION_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Paystation Module', 'MODULE_PAYMENT_PAYSTATION_STATUS', 'True', 'Allows customers to select Paystation as a payment method', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Paystation ID', 'MODULE_PAYMENT_PAYSTATION_ID', 'paystationid', 'Your Paystation ID (pi) as supplied - usually a six digit number', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_PAYSTATION_TESTMODE', 'Test', 'Transaction mode (tm) used for processing orders.  Used for testing after the initial \'go live\'.  You need to coordinate with Paystation during your initial launch.', '6', '3', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Send email on payment', 'MODULE_PAYMENT_PAYSTATION_SEND_PAYMENT_EMAIL', 'Disabled', 'When an email address is entered here an email is sent to it with the Paystation variables when a payment is completed', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable External Payment Selection (EPS)', 'MODULE_PAYMENT_PAYSTATION_ALLOW_EPS', 'False', 'When \'True\' the card type can be selected prior to being passed to Paystation.  This removes a screen from the Paystation process, but requires EPS to be enabled in your Paystation account.  If EPS is set to \'False\' the card types below are ignored.', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('EPS only - accept VISA', 'MODULE_PAYMENT_PAYSTATION_ACCEPT_VISA', 'False', 'If you are using EPS, selecting \'True\' here will add VISA to the Card Type selection. Requires a VISA merchant account', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('EPS only - accept MasterCard', 'MODULE_PAYMENT_PAYSTATION_ACCEPT_MASTERCARD', 'False', 'Requires a MasterCard merchant account', '6', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('EPS only - accept American Express', 'MODULE_PAYMENT_PAYSTATION_ACCEPT_AMEX', 'False', 'Requires an American Express merchant account', '6', '8', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('EPS only - accept Diners Club', 'MODULE_PAYMENT_PAYSTATION_ACCEPT_DINERS', 'False', 'Requires a Diners Club merchant account', '6', '9', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('EPS only - accept BankCard', 'MODULE_PAYMENT_PAYSTATION_ACCEPT_BANKCARD', 'False', 'Requires a BankCard merchant account', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Transaction Prefix (ms)', 'MODULE_PAYMENT_PAYSTATION_MS_PREFIX', '', '(Optional) Transaction / site prefix - this will be prefixed to the ms value. ', '6', '2', now())");

	  // Check to see if the Paystation feilds are in the TABLE_ORDERS table, if not then add them.
	  // This stores the results from Paystation with the orders, which you could access from elsewhere
	  // if required (this module does nothing more with them other than store the valuse in them).
	  $check_pstn_ms_query = tep_db_query("show columns from " . TABLE_ORDERS . " LIKE 'pstn_ms'");
	  if (tep_db_num_rows($check_pstn_ms_query) < 1) {
		  tep_db_query("alter table " . TABLE_ORDERS . " add column pstn_ms varchar(8) after payment_method");
	  }
	  $check_pstn_ti_query = tep_db_query("show columns from " . TABLE_ORDERS . " LIKE 'pstn_ti'");
	  if (tep_db_num_rows($check_pstn_ti_query) < 1) {
		  tep_db_query("alter table " . TABLE_ORDERS . " add column pstn_ti varchar(16) after pstn_ms");
	  }
	  $check_pstn_ec_query = tep_db_query("show columns from " . TABLE_ORDERS . " LIKE 'pstn_ec'");
	  if (tep_db_num_rows($check_pstn_ec_query) < 1) {
		  tep_db_query("alter table " . TABLE_ORDERS . " add column pstn_ec int after pstn_ti");
	  }
	  $check_pstn_em_query = tep_db_query("show columns from " . TABLE_ORDERS . " LIKE 'pstn_em'");
	  if (tep_db_num_rows($check_pstn_em_query) < 1) {
		  tep_db_query("alter table " . TABLE_ORDERS . " add column pstn_em varchar(64) after pstn_ec");
	  }
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYSTATION_STATUS', 'MODULE_PAYMENT_PAYSTATION_TESTMODE', 'MODULE_PAYMENT_PAYSTATION_ID', 'MODULE_PAYMENT_PAYSTATION_SEND_PAYMENT_EMAIL','MODULE_PAYMENT_PAYSTATION_ALLOW_EPS','MODULE_PAYMENT_PAYSTATION_ACCEPT_VISA','MODULE_PAYMENT_PAYSTATION_ACCEPT_MASTERCARD','MODULE_PAYMENT_PAYSTATION_ACCEPT_AMEX','MODULE_PAYMENT_PAYSTATION_ACCEPT_DINERS','MODULE_PAYMENT_PAYSTATION_MS_PREFIX');
    }
  }
?>