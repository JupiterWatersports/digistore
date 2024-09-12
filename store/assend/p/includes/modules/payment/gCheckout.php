<?php
/*
  gCheckout.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

class gCheckout {
  
  var $code, $title, $description, $enabled;

// class constructor
  function gCheckout() {
    global $order, $language;
    
    require_once(DIR_FS_CATALOG.'includes/languages/'. $language . '/modules/payment/gCheckout.php');
    
    $this->code = 'gCheckout';
    $this->title = GOOGLECHECKOUT_TITLE;
    $this->description = GOOGLECHECKOUT_DESCRIPTION;
    $this->enabled = ((GOOGLECHECKOUT_ENABLED == 'True') ? true : false);
			
  }

// class methods
  function update_status() {
  }

  function javascript_validation() {
    return false;
  }

  function selection() {
    return false;
  }

  function pre_confirmation_check() {
    return false;
  }

  function confirmation() {
    return false;
  }

  function process_button() {
  }

  function before_process() {
    return false;
  }

  function after_process() {
    return false;
  }

  function output_error() {
    return false;
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'GOOGLECHECKOUT_ENABLED'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    global $language;
    require_once(DIR_FS_CATALOG.'includes/languages/'. $language . '/modules/payment/gCheckout.php');
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable gCheckout Module', 'GOOGLECHECKOUT_ENABLED', 'true', 'Enable Google Checkout payment?', '6', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added              ) values ('Merchant ID', 'GOOGLECHECKOUT_MERCHANT_ID', '', 'Please fill in your merchant ID which is on the Integration page in the Settings tab of your Google Checkout account', '6', '2', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added              ) values ('Merchant Key', 'GOOGLECHECKOUT_MERCHANT_KEY', '', 'Please fill in your merchant key which is on the Integration page in the Settings tab of your Google Checkout account', '6', '3', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Select Server', 'GOOGLECHECKOUT_SUBDOMAIN', 'sandbox', 'Select the server to use: Sandbox or Production', '6', '4', 'tep_cfg_select_option(array(\'sandbox\', \'checkout\'),',now())");

    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Select commands send method', 'GOOGLECHECKOUT_SEND_METHOD', 'http', 'Select command send to google method', '6', '4', 'tep_cfg_select_option(array(\'curl\', \'http\'),',now())");
    
    //insert new status "Cancelled"
    $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Cancelled' limit 1");
      if (tep_db_num_rows($check_query) < 1) {
        $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
        $status = tep_db_fetch_array($status_query);
        $status_cancelled_id = $status['status_id']+1;
        $languages = tep_get_languages();
        foreach ($languages as $lang) {
          tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_cancelled_id . "', '" . $lang['id'] . "', 'Cancelled')");
        }
      } else {
        $check = tep_db_fetch_array($check_query);
        $status_cancelled_id = $check['orders_status_id'];
      }  

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Google Cancelled Order Status', 'GOOGLECHECKOUT_CANCELLED_ORDER_STATUS_ID', '" . $status_cancelled_id . "', 'Set the status of CANCELLED orders made with this payment module to this value', '6', '5', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    
    // get the processing status
    $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Processing' limit 1");
      if (tep_db_num_rows($check_query) > 0) {
        $check = tep_db_fetch_array($check_query);
        $status_processing_id = $check['orders_status_id'];
      }
      else $status_processing_id = DEFAULT_ORDERS_STATUS_ID;
       
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Google Charged Order Status', 'GOOGLECHECKOUT_PROCESSING_ORDER_STATUS_ID', '" . $status_processing_id . "', 'Set the status of charged orders made with this payment module to this value', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

    // get the delivered status
    $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Delivered' limit 1");
      if (tep_db_num_rows($check_query) > 0) {
        $check = tep_db_fetch_array($check_query);
        $status_delivered_id = $check['orders_status_id'];
      }
      else $status_delivered_id = DEFAULT_ORDERS_STATUS_ID;
       
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Google Delivered Order Status', 'GOOGLECHECKOUT_DELIVERED_ORDER_STATUS_ID', '" . $status_delivered_id . "', 'Set the status of shipped orders made with this payment module to this value', '6', '7', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    //add google order number
    $check_for_google_number = tep_db_query("SHOW FIELDS from ".TABLE_ORDERS); $insert_field = true;
    while ($field = tep_db_fetch_array($check_for_google_number))
    if ($field['Field'] == 'google_order_number') $insert_field = false;
    
    if ($insert_field == true) tep_db_query("ALTER TABLE `".TABLE_ORDERS."` ADD `google_order_number` VARCHAR(20) DEFAULT '0' NOT NULL");
  }

  function remove() {
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  function keys() {
    return array('GOOGLECHECKOUT_SEND_METHOD','GOOGLECHECKOUT_PROCESSING_ORDER_STATUS_ID','GOOGLECHECKOUT_DELIVERED_ORDER_STATUS_ID','GOOGLECHECKOUT_ENABLED', 'GOOGLECHECKOUT_MERCHANT_ID', 'GOOGLECHECKOUT_MERCHANT_KEY', 'GOOGLECHECKOUT_SUBDOMAIN', 'GOOGLECHECKOUT_CANCELLED_ORDER_STATUS_ID');
  }
}
?>
