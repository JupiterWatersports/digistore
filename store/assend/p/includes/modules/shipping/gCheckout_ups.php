<?php
/*
  gCheckout_ups.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

  class gCheckout_ups {
    var $code, $title, $descrption, $icon, $enabled, $types;

// class constructor
    function gCheckout_ups() {
      $this->code = 'gCheckout_ups';
      $this->title = GOOGLECHECKOUT_UPS_TEXT_TITLE;
      $this->description = GOOGLECHECKOUT_UPS_TEXT_DESCRIPTION;
      $this->sort_order = GOOGLECHECKOUT_UPS_SORT_ORDER;
      $this->enabled = ((GOOGLECHECKOUT_UPS_STATUS == 'True') ? true : false);
      $this->types = array(
//                           '1DM' => 'Next Day Air Early AM',
//                           '1DML' => 'Next Day Air Early AM Letter',
                           '1DA' => 'Next Day Air',
//                           '1DAL' => 'Next Day Air Letter',
//                           '1DAPI' => 'Next Day Air Intra (Puerto Rico)',
//                           '1DP' => 'Next Day Air Saver',
//                           '1DPL' => 'Next Day Air Saver Letter',
//                           '2DM' => '2nd Day Air AM',
//                           '2DML' => '2nd Day Air AM Letter',
//                           '2DA' => '2nd Day Air',
//                           '2DAL' => '2nd Day Air Letter',
//                           '3DS' => '3 Day Select',
                           'GND' => 'Ground',
//                           'GNDCOM' => 'Ground Commercial',
//                           'GNDRES' => 'Ground Residential',
//                           'STD' => 'Canada Standard',
//                           'XPR' => 'Worldwide Express',
//                           'XPRL' => 'worldwide Express Letter',
//                           'XDM' => 'Worldwide Express Plus',
//                           'XDML' => 'Worldwide Express Plus Letter',
//                           'XPD' => 'Worldwide Expedited'
                          );
    }

// class methods
    function google_quote($address_array, $total_weight, $total_count) {    
      $shipping_weight = $total_weight;
      $shipping_num_boxes = ceil($shipping_weight/SHIPPING_MAX_WEIGHT);
      $prod = 'GNDRES';
      $this->_upsProduct($prod);
      $country_name = $this->_get_country_code(SHIPPING_ORIGIN_COUNTRY);
      $this->_upsOrigin(SHIPPING_ORIGIN_ZIP, $country_name['countries_iso_code_2']);
      $this->_upsDest($address_array['postal-code'], $address_array['country-code']);
      $this->_upsRate(GOOGLECHECKOUT_UPS_PICKUP);
      $this->_upsContainer(GOOGLECHECKOUT_UPS_PACKAGE);
      $this->_upsWeight($shipping_weight);
      $this->_upsRescom(GOOGLECHECKOUT_UPS_RES);
      $upsQuote = $this->_upsGetQuote();

      if ( (is_array($upsQuote)) && (sizeof($upsQuote) > 0) ) {
        $this->quotes = array('id' => $this->code,
                              'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'lbs)');

        $methods = array();
        $qsize = sizeof($upsQuote);
        for ($i=0; $i<$qsize; $i++) {
          list($type, $cost) = each($upsQuote[$i]);
          $methods[$type] = array('id' => $type,
                             'title' => $this->types[$type],
                             'cost' => ($cost + GOOGLECHECKOUT_UPS_HANDLING) * $shipping_num_boxes);
        }

        $this->quotes['methods'] = $methods;
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => 'An error occured with the UPS shipping calculations.<br />' . $upsQuote . '<br />If you prefer to use UPS as your shipping method, please contact the store owner.');
      }
      return $this->quotes;
    }

    function quote($method = '') {
    	//void
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'GOOGLECHECKOUT_UPS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Checkout UPS Shipping', 'GOOGLECHECKOUT_UPS_STATUS', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Pickup Method', 'GOOGLECHECKOUT_UPS_PICKUP', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Packaging?', 'GOOGLECHECKOUT_UPS_PACKAGE', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Residential Delivery?', 'GOOGLECHECKOUT_UPS_RES', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'GOOGLECHECKOUT_UPS_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'GOOGLECHECKOUT_UPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'GOOGLECHECKOUT_UPS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      
//Default values for shippings
      if (sizeof($this->types)>0) {
      	foreach ($this->types as $_key=>$value) {
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key)."', 'UPS - ".$value."', 'Title of `".$value."` shipping type', '6', '0', now())");
     		
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_UPS_METHOD_COST_'.strtoupper($_key)."', '0', 'Cost of `".$value."` shipping type', '6', '0', now())");
      	}      	
      }

      
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");     
    }

    function keys() {
      if (sizeof($this->types)>0) {
      	foreach ($this->types as $_key=>$value) {
      		$methods[] = 'GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key);
      		$methods[] = 'GOOGLECHECKOUT_UPS_METHOD_COST_'.strtoupper($_key);
      	}      	
      }
      return array_merge(array('GOOGLECHECKOUT_UPS_STATUS', 'GOOGLECHECKOUT_UPS_PICKUP', 'GOOGLECHECKOUT_UPS_PACKAGE', 'GOOGLECHECKOUT_UPS_RES', 'GOOGLECHECKOUT_UPS_HANDLING', 'GOOGLECHECKOUT_UPS_ZONE', 'GOOGLECHECKOUT_UPS_SORT_ORDER'), $methods);
    }

    function _upsProduct($prod){
      $this->_upsProductCode = $prod;
    }

    function _upsOrigin($postal, $country){
      $this->_upsOriginPostalCode = $postal;
      $this->_upsOriginCountryCode = $country;
    }

    function _upsDest($postal, $country){
      $postal = str_replace(' ', '', $postal);

      if ($country == 'US') {
        $this->_upsDestPostalCode = substr($postal, 0, 5);
      } else {
        $this->_upsDestPostalCode = $postal;
      }

      $this->_upsDestCountryCode = $country;
    }

    function _upsRate($foo) {
      switch ($foo) {
        case 'RDP':
          $this->_upsRateCode = 'Regular+Daily+Pickup';
          break;
        case 'OCA':
          $this->_upsRateCode = 'On+Call+Air';
          break;
        case 'OTP':
          $this->_upsRateCode = 'One+Time+Pickup';
          break;
        case 'LC':
          $this->_upsRateCode = 'Letter+Center';
          break;
        case 'CC':
          $this->_upsRateCode = 'Customer+Counter';
          break;
      }
    }

    function _upsContainer($foo) {
      switch ($foo) {
        case 'CP': // Customer Packaging
          $this->_upsContainerCode = '00';
          break;
        case 'ULE': // UPS Letter Envelope
          $this->_upsContainerCode = '01';
          break;
        case 'UT': // UPS Tube
          $this->_upsContainerCode = '03';
          break;
        case 'UEB': // UPS Express Box
          $this->_upsContainerCode = '21';
          break;
        case 'UW25': // UPS Worldwide 25 kilo
          $this->_upsContainerCode = '24';
          break;
        case 'UW10': // UPS Worldwide 10 kilo
          $this->_upsContainerCode = '25';
          break;
      }
    }

    function _upsWeight($foo) {
      $this->_upsPackageWeight = $foo;
    }

    function _upsRescom($foo) {
      switch ($foo) {
        case 'RES': // Residential Address
          $this->_upsResComCode = '1';
          break;
        case 'COM': // Commercial Address
          $this->_upsResComCode = '2';
          break;
      }
    }

    function _upsAction($action) {
      /* 3 - Single Quote
         4 - All Available Quotes */

      $this->_upsActionCode = $action;
    }

    function _upsGetQuote() {
      if (!isset($this->_upsActionCode)) $this->_upsActionCode = '4';

      $request = join('&', array('accept_UPS_license_agreement=yes',
                                 '10_action=' . $this->_upsActionCode,
                                 '13_product=' . $this->_upsProductCode,
                                 '14_origCountry=' . $this->_upsOriginCountryCode,
                                 '15_origPostal=' . $this->_upsOriginPostalCode,
                                 '19_destPostal=' . $this->_upsDestPostalCode,
                                 '22_destCountry=' . $this->_upsDestCountryCode,
                                 '23_weight=' . $this->_upsPackageWeight,
                                 '47_rate_chart=' . $this->_upsRateCode,
                                 '48_container=' . $this->_upsContainerCode,
                                 '49_residential=' . $this->_upsResComCode));
                                                   
// BOF Replaced standard osC http_client connection code     
      if (!$socket = fsockopen('www.ups.com', '80', $reply, $replyString)) {
        return 'error';
      }
      $headers = 
       'Host'. ': ' . 'www.ups.com' . "\r\n".
       'User-Agent'. ': ' . 'osCommerce' . "\r\n".
       'Connection'. ': ' . 'Close' . "\r\n";
      //echo $request;
      fputs($socket, 'GET /using/services/rave/qcostcgi.cgi?' . $request.' HTTP/1.0\r\n'.$headers . "\r\n"); 
      
      $replyString = trim(fgets($socket, 1024));

      if (preg_match('|^HTTP/\S+ (\d+) |i', $replyString, $a )) {
        $this->reply = $a[1];
      } else {
        return 'error';
        //'Bad Response'
      }
      
      $data = '';
      $counter = 0;

      do {
        $status = socket_get_status($socket);
        if ($status['eof'] == 1) {
          break;
        }

        if ($status['unread_bytes'] > 0) {
          $buffer = fread($socket, $status['unread_bytes']);
          $counter = 0;
        } else {
          $buffer = fread($socket, 128);
          $counter++;
          usleep(2);
        }

        $body .= $buffer;
      } while ( ($status['unread_bytes'] > 0) || ($counter++ < 10) );  
          
      $body_array = explode("\n", $body);

      $returnval = array();
      $errorret = 'error'; // only return error if NO rates returned
// BOF Replaced standard osC http_client connection code     

      $n = sizeof($body_array);
      for ($i=0; $i<$n; $i++) {
        $result = explode('%', $body_array[$i]);
        $errcode = substr($result[0], -1);
        switch ($errcode) {
          case 3:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 4:
            if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
            break;
          case 5:
            $errorret = $result[1];
            break;
          case 6:
            if (is_array($returnval)) $returnval[] = array($result[3] => $result[10]);
            break;
        }
      }
      if (empty($returnval)) $returnval = $errorret;     
      return $returnval;
    }
    
    function get_shipping_rates_default() {
      reset($this->types);
      if (sizeof($this->types)>0 && $this->enabled) {
      while (list($_key,$_value) = each($this->types)) {
        if (defined('GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key)) && defined('GOOGLECHECKOUT_UPS_METHOD_COST_'.strtoupper($_key))) {
        	$result[] = array('title' => constant('GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key)), 'cost' => constant('GOOGLECHECKOUT_UPS_METHOD_COST_'.strtoupper($_key)));
        }
      }
      return $result;
      } else return false;
     
    }

    function get_shipping_rates($address_array, $total_weight, $total_count) {    	
      $country_id = $this->_get_country_id($address_array['country-code']);
      $zone_id = $this->_get_zone_id($address_array['region']);
      
      if ( ($this->enabled == true) && ((int)GOOGLECHECKOUT_UPS_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . GOOGLECHECKOUT_UPS_ZONE . "' and zone_country_id = '" . $country_id . "' order by zone_id");
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
    		$_result = $this->google_quote($address_array, $total_weight, $total_count);
    		
    		
    		reset($this->types);
    		if (sizeof($this->types)>0) {
    			while (list($_key,$_value) = each($this->types)) {
    				if (defined('GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key))) {
    					$result[] = array('title' => constant('GOOGLECHECKOUT_UPS_METHOD_'.strtoupper($_key)), 'cost' => number_format($_result['methods'][$_key]['cost'],2), 'shippable' => (isset($_result['methods'][$_key]) && $this->enabled == true)?'true':'false');
    				}
    			}
    			return $result;
    		} else return false;
    }

    function _get_country_code($country_code) {
       $country_query = tep_db_query("Select countries_iso_code_2 from ".TABLE_COUNTRIES." where countries_id = '".$country_code."'");     
       $country = tep_db_fetch_array($country_query);
       return $country['countries_id'];
    }

    function _get_country_id($country_code) {
       $country_query = tep_db_query("Select countries_id from ".TABLE_COUNTRIES." where countries_iso_code_2 = '".$country_code."'");     
       $country = tep_db_fetch_array($country_query);
       return $country['countries_id'];
    }
    
    function _get_zone_id($zone_code) {
       $state_query = tep_db_query("Select zone_id from ".TABLE_ZONES." where zone_code = '".$zone_code."'");     
       $state = tep_db_fetch_array($state_query);
       return $state['zone_id'];
    }
    
  }
  
?>
