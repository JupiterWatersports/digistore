<?php
/*
  gCheckout_flat.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

  class gCheckout_flat {
    var $code, $title, $description, $enabled;

// class constructor
    function gCheckout_flat() {

      $this->code = 'gCheckout_flat';
      $this->title = GOOGLECHECKOUT_FLAT_TEXT_TITLE;
      $this->description = GOOGLECHECKOUT_FLAT_TEXT_DESCRIPTION;
      $this->sort_order = GOOGLECHECKOUT_FLAT_SORT_ORDER;
      $this->enabled = ((GOOGLECHECKOUT_FLAT_STATUS == 'true') ? true : false);
      
    }

// class methods
    function quote($method = '')
    {
      //skip oscommerce shipping
    }
    
    function get_shipping_rates_default() {
      
      if ($this->enabled == true) {
      $result = array('title' => GOOGLECHECKOUT_FLAT_TEXT_DISPLAY, 'cost' => GOOGLECHECKOUT_FLAT_COST);
      return $result;
      }
      else return false;
      
    }

    function get_shipping_rates($address_array, $total_weight, $total_count) {
      
      $country_id = $this->_get_country_id($address_array['country-code']);
      $zone_id = $this->_get_zone_id($address_array['region']);
      
      //echo "Yo".($this->enabled == 'true' ? '1' : '0').GOOGLECHECKOUT_FLAT_STATUS;
      
      if ( ($this->enabled == true) && ((int)GOOGLECHECKOUT_FLAT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . GOOGLECHECKOUT_FLAT_ZONE . "' and zone_country_id = '" . $country_id . "' order by zone_id");
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
      $result = array('title' => GOOGLECHECKOUT_FLAT_TEXT_DISPLAY, 'cost' => GOOGLECHECKOUT_FLAT_COST,'shippable' => 'true');
      return $result;
      }
      else 
      {
      $result = array('title' => GOOGLECHECKOUT_FLAT_TEXT_DISPLAY, 'cost' => GOOGLECHECKOUT_FLAT_COST,'shippable' => 'false');
      return $result;
      }
      
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'GOOGLECHECKOUT_FLAT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Google Checkout Flat Shipping Title to Display', 'GOOGLECHECKOUT_FLAT_TEXT_DISPLAY', 'Flat Rate Shipping', 'The shipping method title that will appear on checkout page.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Checkout Flat Shipping', 'GOOGLECHECKOUT_FLAT_STATUS', 'true', 'Do you want to offer flat rate shipping for Google Checkout?', '6', '0', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Google Checkout Flat Shipping Cost', 'GOOGLECHECKOUT_FLAT_COST', '5.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'GOOGLECHECKOUT_FLAT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'GOOGLECHECKOUT_FLAT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('GOOGLECHECKOUT_FLAT_TEXT_DISPLAY','GOOGLECHECKOUT_FLAT_STATUS', 'GOOGLECHECKOUT_FLAT_COST', 'GOOGLECHECKOUT_FLAT_ZONE', 'GOOGLECHECKOUT_FLAT_SORT_ORDER');
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
