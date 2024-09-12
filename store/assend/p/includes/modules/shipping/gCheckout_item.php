<?php
/*
  gCheckout_item.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

  class gCheckout_item {
    var $code, $title, $description, $enabled;

// class constructor
    function gCheckout_item() {

      $this->code = 'gCheckout_item';
      $this->title = GOOGLECHECKOUT_ITEM_TEXT_TITLE;
      $this->description = GOOGLECHECKOUT_ITEM_TEXT_DESCRIPTION;
      $this->sort_order = GOOGLECHECKOUT_ITEM_SORT_ORDER;
      $this->enabled = ((GOOGLECHECKOUT_ITEM_STATUS == 'true') ? true : false);

    }

// class methods
    function quote($method = '')
    {
      //skip oscommerce shipping
    }

    function get_shipping_rates_default() {
      
      if ($this->enabled == true) {
      $result = array('title' => GOOGLECHECKOUT_ITEM_TEXT_DISPLAY, 'cost' => GOOGLECHECKOUT_ITEM_DEFAULT_COST + GOOGLECHECKOUT_ITEM_HANDLING);
      return $result;
      }
      else return false;
      
    }


    function get_shipping_rates($address_array, $total_weight, $total_count) {
      
      $country_id = $this->_get_country_id($address_array['country-code']);
      $zone_id = $this->_get_zone_id($address_array['region']);
      
      if ( ($this->enabled == true) && ((int)GOOGLECHECKOUT_ITEM_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . GOOGLECHECKOUT_ITEM_ZONE . "' and zone_country_id = '" . $country_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $zone_id) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
      
      if ($this->enabled == true) {
      $result = array('title' => GOOGLECHECKOUT_ITEM_TEXT_DISPLAY, 'cost' => (GOOGLECHECKOUT_ITEM_COST * $total_count) + GOOGLECHECKOUT_ITEM_HANDLING,'shippable' => 'true');
      return $result;
      }
      else
      { 
      $result = array('title' => GOOGLECHECKOUT_ITEM_TEXT_DISPLAY, 'cost' => (GOOGLECHECKOUT_ITEM_COST * $total_count) + GOOGLECHECKOUT_ITEM_HANDLING,'shippable' => 'false');
      return $result;
      }
      
    }


    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'GOOGLECHECKOUT_ITEM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Google Checkout Item Shipping Title to Display', 'GOOGLECHECKOUT_ITEM_TEXT_DISPLAY', 'Per Item Shipping', 'The shipping method title that will appear on checkout page.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Checkout Item Shipping', 'GOOGLECHECKOUT_ITEM_STATUS', 'true', 'Do you want to offer per item rate shipping for Google Checkout?', '6', '0', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'GOOGLECHECKOUT_ITEM_COST', '2.50', 'The shipping cost will be multiplied by the number of items in an order that uses this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Default Shipping Cost', 'GOOGLECHECKOUT_ITEM_DEFAULT_COST', '12.50', 'This is the default shipping cost for this method. If your server will not respond in 3 seconds, google will use this default cost for this method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'GOOGLECHECKOUT_ITEM_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'GOOGLECHECKOUT_ITEM_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'GOOGLECHECKOUT_ITEM_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('GOOGLECHECKOUT_ITEM_DEFAULT_COST', 'GOOGLECHECKOUT_ITEM_TEXT_DISPLAY','GOOGLECHECKOUT_ITEM_STATUS', 'GOOGLECHECKOUT_ITEM_COST', 'GOOGLECHECKOUT_ITEM_HANDLING', 'GOOGLECHECKOUT_ITEM_ZONE', 'GOOGLECHECKOUT_ITEM_SORT_ORDER');
    }

    function _get_country_id($country_code)
    {
       $country_query = tep_db_query("Select countries_id from ".TABLE_COUNTRIES." where countries_iso_code_2 = '".$country_code."'");     
       $country = tep_db_fetch_array($country_query);
       return $country['countries_id'];
    }

    function _get_zone_id($zone_code)
    {
       $state_query = tep_db_query("Select zone_id from ".TABLE_ZONES." where zone_code = '".$zone_code."'");     
       $state = tep_db_fetch_array($state_query);
       return $state['zone_id'];
    }

  }
?>
