<?php
/*
  gCheckout_fedex.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

  class gCheckout_fedex {
    var $code, $title, $description, $sort_order, $icon, /*$tax_class,*/ $enabled, $meter, $intl;

    function gCheckout_fedex() {
      $this->code = 'gCheckout_fedex';
      $this->title = GOOGLECHECKOUT_FEDEX_TEXT_TITLE;
      $this->description = GOOGLECHECKOUT_FEDEX_TEXT_DESCRIPTION;
      $this->sort_order = GOOGLECHECKOUT_FEDEX_SORT_ORDER;
      $this->enabled = ((GOOGLECHECKOUT_FEDEX_STATUS == 'True') ? true : false);
      $this->meter = GOOGLECHECKOUT_FEDEX_METER;
	  
// You can comment out any methods you do not wish to quote by placing a // at the beginning of that line
// If you comment out 92 in either domestic or international, be
// sure and remove the trailing comma on the last non-commented line
      $this->domestic_types = array(
             '01' => 'Priority (by 10:30AM, later for rural)',
             '03' => '2 Day Air'
//             '05' => 'Standard Overnight (by 3PM, later for rural)',
//             '06' => 'First Overnight', 
//             '20' => 'Express Saver (3 Day)',
//             '90' => 'Home Delivery',
//             '92' => 'Ground Service'
             );

      $this->international_types = array(
             '01' => 'International Priority (1-3 Days)'
//             '03' => 'International Economy (4-5 Days)',
//             '06' => 'International First',
//             '90' => 'Home Delivery',
//             '92' => 'Ground Service'
             );
    }

// class methods
    function google_quote($address_array, $total_weight, $total_count, $total_cost)  {
          
      $shipping_weight = $total_weight;
      $shipping_num_boxes = ceil($shipping_weight/SHIPPING_MAX_WEIGHT);
      
      if (GOOGLECHECKOUT_FEDEX_ENVELOPE == 'True') {
        if ( ($shipping_weight <= .5 && GOOGLECHECKOUT_FEDEX_WEIGHT == 'LBS') ||
             ($shipping_weight <= .2 && GOOGLECHECKOUT_FEDEX_WEIGHT == 'KGS')) {
          $this->_setPackageType('06');
        } else {
          $this->_setPackageType('01');
        }
      } else {
        $this->_setPackageType('01');
      }

      if ($this->packageType == '01' && $shipping_weight < 1) {
        $this->_setWeight(1);
      } else {
        $this->_setWeight($shipping_weight);
      }
    
      $this->_setInsuranceValue($total_cost / $shipping_num_boxes);

      if (defined("SHIPPING_ORIGIN_COUNTRY")) {
        $this->country = $this->_get_country_code(SHIPPING_ORIGIN_COUNTRY);
      } 
      $fedexQuote = $this->_getQuote($address_array);

      if (is_array($fedexQuote)) {
        if (isset($fedexQuote['error'])) {
          $this->quotes = array('module' => $this->title,
                                'error' => $fedexQuote['error']);
        } else {
          $this->quotes = array('id' => $this->code,
                                'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . strtolower(GOOGLECHECKOUT_FEDEX_WEIGHT) . ')');

          $methods = array();
          foreach ($fedexQuote as $type => $cost) {
            $skip = FALSE;
            $this->surcharge = 0;
            if ($this->intl === FALSE) {
              if (strlen($type) > 2 && GOOGLECHECKOUT_FEDEX_TRANSIT == 'True') {
                $service_descr = $this->domestic_types[substr($type,0,2)] . ' (' . substr($type,2,1) . ' days)';
              } else {
                $service_descr = $this->domestic_types[substr($type,0,2)];
              }
              switch (substr($type,0,2)) {
                case 90:
                  if ($order->delivery['company'] != '') {
                    $skip = TRUE;
                  }
                  break;
                case 92:
                  if ($this->country == "CA") {
                    if ($order->delivery['company'] == '') {
                      $this->surcharge = GOOGLECHECKOUT_FEDEX_RESIDENTIAL;
                    }
                  } else {
                    if ($order->delivery['company'] == '') {
                      $skip = TRUE;
                    }
                  }
                  break;
                default:
                  if ($this->country != "CA" && substr($type,0,2) < "90" && $order->delivery['company'] == '') {
                    $this->surcharge = GOOGLECHECKOUT_FEDEX_RESIDENTIAL;
                  }
                  break;
              }
            } else {
              if (strlen($type) > 2 && GOOGLECHECKOUT_FEDEX_TRANSIT == 'True') {
                $service_descr = $this->international_types[substr($type,0,2)] . ' (' . substr($type,2,1) . ' days)';
              } else {
                $service_descr = $this->international_types[substr($type,0,2)];
              }
            }
            if ($method) {
              if (substr($type,0,2) != $method) $skip = TRUE;
            }
            if (!$skip) {
              $methods[$type] = array('id' => substr($type,0,2),
                                 'title' => $service_descr,
                                 'cost' => (SHIPPING_HANDLING + GOOGLECHECKOUT_FEDEX_SURCHARGE + $this->surcharge + $cost) * $shipping_num_boxes);
            }
          }

          $this->quotes['methods'] = $methods;

         }
      } else {
        $this->quotes = array('module' => $this->title,
                              'error' => 'An error occured with the fedex shipping calculations.<br />Fedex may not deliver to your country, or your postal code may be wrong.');
      }      
      return array('type'=>$this->intl, 'quotes'=>$this->quotes);
    }
    
    function quote($method = '') {
    	return false;
    }
    
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'GOOGLECHECKOUT_FEDEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Fedex Shipping', 'GOOGLECHECKOUT_FEDEX_STATUS', 'True', 'Do you want to offer Fedex shipping?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Transit Times', 'GOOGLECHECKOUT_FEDEX_TRANSIT', 'True', 'Do you want to show transit times for ground or home delivery rates?', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Fedex Account Number', 'GOOGLECHECKOUT_FEDEX_ACCOUNT', 'NONE', 'Enter the fedex Account Number assigned to you, required', '6', '11', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Your Fedex Meter ID', 'GOOGLECHECKOUT_FEDEX_METER', 'NONE', 'Enter the Fedex MeterID assigned to you, set to NONE to obtain a new meter number', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Path', 'GOOGLECHECKOUT_FEDEX_CURL', 'NONE', 'Enter the path to the cURL program, normally, leave this set to NONE to execute cURL using PHP', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug Mode', 'GOOGLECHECKOUT_FEDEX_DEBUG', 'False', 'Turn on Debug', '6', '19', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Weight Units', 'GOOGLECHECKOUT_FEDEX_WEIGHT', 'LBS', 'Weight Units:', '6', '19', 'tep_cfg_select_option(array(\'LBS\', \'KGS\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('First line of street address', 'GOOGLECHECKOUT_FEDEX_ADDRESS_1', 'NONE', 'Enter the first line of your ship from street address, required', '6', '13', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Second line of street address', 'GOOGLECHECKOUT_FEDEX_ADDRESS_2', 'NONE', 'Enter the second line of your ship from street address, leave set to NONE if you do not need to specify a second line', '6', '14', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('City name', 'GOOGLECHECKOUT_FEDEX_CITY', 'NONE', 'Enter the city name for the ship from street address, required', '6', '15', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('State or Province name', 'GOOGLECHECKOUT_FEDEX_STATE', 'NONE', 'Enter the 2 letter state or province name for the ship from street address, required for Canada and US', '6', '16', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Postal code', 'GOOGLECHECKOUT_FEDEX_POSTAL', 'NONE', 'Enter the postal code for the ship from street address, required', '6', '17', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Phone number', 'GOOGLECHECKOUT_FEDEX_PHONE', 'NONE', 'Enter a contact phone number for your company, required', '6', '18', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Which server to use', 'GOOGLECHECKOUT_FEDEX_SERVER', 'production', 'You must have an account with Fedex', '6', '19', 'tep_cfg_select_option(array(\'test\', \'production\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Drop off type', 'GOOGLECHECKOUT_FEDEX_DROPOFF', '1', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', '6', '20', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fedex surcharge?', 'GOOGLECHECKOUT_FEDEX_SURCHARGE', '0', 'Surcharge amount to add to shipping charge?', '6', '21', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show List Rates?', 'GOOGLECHECKOUT_FEDEX_LIST_RATES', 'False', 'Show LIST Rates?', '6', '21', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Residential surcharge?', 'GOOGLECHECKOUT_FEDEX_RESIDENTIAL', '0', 'Residential Surcharge (in addition to other surcharge) for Express packages within US, or ground packages within Canada?', '6', '21', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Insurance?', 'GOOGLECHECKOUT_FEDEX_INSURE', 'NONE', 'Insure packages over what dollar amount?', '6', '22', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Envelope Rates?', 'GOOGLECHECKOUT_FEDEX_ENVELOPE', 'False', 'Do you want to offer Fedex Envelope rates? All items under 1/2 LB (.23KG) will quote using the envelope rate if True.', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Sort rates: ', 'GOOGLECHECKOUT_FEDEX_WEIGHT_SORT', 'High to Low', 'Sort rates top to bottom: ', '6', '19', 'tep_cfg_select_option(array(\'High to Low\', \'Low to High\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Timeout in Seconds', 'GOOGLECHECKOUT_FEDEX_TIMEOUT', 'NONE', 'Enter the maximum time in seconds you would wait for a rate request from Fedex? Leave NONE for default timeout.', '6', '22', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'GOOGLECHECKOUT_FEDEX_SORT_ORDER', '0', 'Sort order of display.', '6', '24', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'GOOGLECHECKOUT_FEDEX_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())"); //zone change
	        
//Default values for shippings
      if (sizeof($this->domestic_types)>0) {
      	foreach ($this->domestic_types as $_key=>$value) {
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key)."', 'Fedex - ".$value."', 'Title of `".$value."` shipping type', '6', '0', now())");
     		
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_FEDEX_METHOD_COST_'.strtoupper($_key)."', '0', 'Cost of `".$value."` shipping type', '6', '0', now())");
      	}      	
      }

      if (sizeof($this->international_types)>0) {
      	foreach ($this->international_types as $_key=>$value) {
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_FEDEX_IMETHOD_'.strtoupper($_key)."', 'Fedex (Intl) - ".$value."', 'Title of `".$value."` shipping type', '6', '0', now())");
     		
      		tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('`".$value."` method title', '".'GOOGLECHECKOUT_FEDEX_IMETHOD_COST_'.strtoupper($_key)."', '0', 'Cost of `".$value."` shipping type', '6', '0', now())");
      	}      	
      }
      
	        
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      if (sizeof($this->domestic_types)>0) {
      	foreach ($this->domestic_types as $_key=>$value) {
      		$methods[] = 'GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key);
      		$methods[] = 'GOOGLECHECKOUT_FEDEX_METHOD_COST_'.strtoupper($_key);
      	}      	
      }

      if (sizeof($this->international_types)>0) {
      	foreach ($this->international_types as $_key=>$value) {
      		$methods[] = 'GOOGLECHECKOUT_FEDEX_IMETHOD_'.strtoupper($_key);
      		$methods[] = 'GOOGLECHECKOUT_FEDEX_IMETHOD_COST_'.strtoupper($_key);
      	}      	
      }
   	
      return array_merge(array('GOOGLECHECKOUT_FEDEX_STATUS', 'GOOGLECHECKOUT_FEDEX_ACCOUNT', 'GOOGLECHECKOUT_FEDEX_METER', 'GOOGLECHECKOUT_FEDEX_CURL', 'GOOGLECHECKOUT_FEDEX_DEBUG', 'GOOGLECHECKOUT_FEDEX_WEIGHT', 'GOOGLECHECKOUT_FEDEX_SERVER', 'GOOGLECHECKOUT_FEDEX_ADDRESS_1', 'GOOGLECHECKOUT_FEDEX_ADDRESS_2', 'GOOGLECHECKOUT_FEDEX_CITY', 'GOOGLECHECKOUT_FEDEX_STATE', 'GOOGLECHECKOUT_FEDEX_POSTAL', 'GOOGLECHECKOUT_FEDEX_PHONE', 'GOOGLECHECKOUT_FEDEX_DROPOFF', 'GOOGLECHECKOUT_FEDEX_TRANSIT', 'GOOGLECHECKOUT_FEDEX_SURCHARGE', 'GOOGLECHECKOUT_FEDEX_LIST_RATES', 'GOOGLECHECKOUT_FEDEX_INSURE', 'GOOGLECHECKOUT_FEDEX_RESIDENTIAL', 'GOOGLECHECKOUT_FEDEX_ENVELOPE', 'GOOGLECHECKOUT_FEDEX_WEIGHT_SORT', 'GOOGLECHECKOUT_FEDEX_TIMEOUT', /*'GOOGLECHECKOUT_FEDEX_TAX_CLASS',*/ 'GOOGLECHECKOUT_FEDEX_SORT_ORDER','GOOGLECHECKOUT_FEDEX_ZONE'), $methods); //zone change
    }

    function _setService($service) {
      $this->service = $service;
    }

    function _setWeight($pounds) {
      $this->pounds = sprintf("%01.1f", $pounds);
    }

    function _setPackageType($type) {
      $this->packageType = $type;
    }

    function _setInsuranceValue($order_amount) {
      if ($order_amount > GOOGLECHECKOUT_FEDEX_INSURE) {
        $this->insurance = sprintf("%01.2f",$order_amount);
      } else {
        $this->insurance = 0;
      }
    }

    function _AccessFedex($data) {

      if (GOOGLECHECKOUT_FEDEX_SERVER == 'production') {
        $this->server = 'gateway.fedex.com/GatewayDC';
      } else {
        $this->server = 'gatewaybeta.fedex.com/GatewayDC';
      }
      if (GOOGLECHECKOUT_FEDEX_CURL == "NONE") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->server);
        if (GOOGLECHECKOUT_FEDEX_TIMEOUT != 'NONE') curl_setopt($ch, CURLOPT_TIMEOUT, GOOGLECHECKOUT_FEDEX_TIMEOUT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Referer: " . STORE_NAME,
                                                   "Host: " . $this->server,
                                                   "Accept: image/gif,image/jpeg,image/pjpeg,text/plain,text/html,*/*",
                                                   "Pragma:",
                                                   "Content-Type:image/gif"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $reply = curl_exec($ch);
        curl_close ($ch);
      } else {
        $this->command_line = GOOGLECHECKOUT_FEDEX_CURL . " " . (GOOGLECHECKOUT_FEDEX_TIMEOUT == 'NONE' ? '' : '-m ' . GOOGLECHECKOUT_FEDEX_TIMEOUT) . " -s -e '" . STORE_NAME . "' --url https://" . $this->server . " -H 'Host: " . $this->server . "' -H 'Accept: image/gif,image/jpeg,image/pjpeg,text/plain,text/html,*/*' -H 'Pragma:' -H 'Content-Type:image/gif' -d '" . $data . "' 'https://" . $this->server . "'";
        exec($this->command_line, $this->reply);
        $reply = $this->reply[0];
      }
        return $reply;
    }

    function _getMeter() {
      $data = '0,"211"'; // Transaction Code, required
      $data .= '10,"' . GOOGLECHECKOUT_FEDEX_ACCOUNT . '"'; // Sender Fedex account number
      $data .= '4003,"' . STORE_OWNER . '"'; // Subscriber contact name
      $data .= '4007,"' . STORE_NAME . '"'; // Subscriber company name
      $data .= '4008,"' . GOOGLECHECKOUT_FEDEX_ADDRESS_1 . '"'; // Subscriber Address line 1
      if (GOOGLECHECKOUT_FEDEX_ADDRESS_2 != 'NONE') {
        $data .= '4009,"' . GOOGLECHECKOUT_FEDEX_ADDRESS_2 . '"'; // Subscriber Address Line 2
      }
      $data .= '4011,"' . GOOGLECHECKOUT_FEDEX_CITY . '"'; // Subscriber City Name
      if (GOOGLECHECKOUT_FEDEX_STATE != 'NONE') {
        $data .= '4012,"' . GOOGLECHECKOUT_FEDEX_STATE . '"'; // Subscriber State code
      }
      $data .= '4013,"' . GOOGLECHECKOUT_FEDEX_POSTAL . '"'; // Subscriber Postal Code
      $data .= '4014,"' . $this->country . '"'; // Subscriber Country Code
      $data .= '4015,"' . GOOGLECHECKOUT_FEDEX_PHONE . '"'; // Subscriber phone number
      $data .= '99,""'; // End of Record, required
      if (GOOGLECHECKOUT_FEDEX_DEBUG == 'True') echo "Data sent to Fedex for Meter: " . $data . "<br />";
      $fedexData = $this->_AccessFedex($data);
      if (GOOGLECHECKOUT_FEDEX_DEBUG == 'True') echo "Data returned from Fedex for Meter: " . $fedexData . "<br />";
      $meterStart = strpos($fedexData,'"498,"');

      if ($meterStart === FALSE) {
        if (strlen($fedexData) == 0) {
          $this->error_message = 'No response to CURL from Fedex server, check CURL availability, or maybe timeout was set too low, or maybe the Fedex site is down';
        } else {
          $fedexData = $this->_ParseFedex($fedexData);
          $this->error_message = 'No meter number was obtained, check configuration. Error ' . $fedexData['2'] . ' : ' . $fedexData['3'];
        }
        return false;
      }
    
      $meterStart += 6;
      $meterEnd = strpos($fedexData, '"', $meterStart);
      $this->meter = substr($fedexData, $meterStart, $meterEnd - $meterStart);
      $meter_sql = "UPDATE configuration SET configuration_value=\"" . $this->meter . "\" where configuration_key=\"GOOGLECHECKOUT_FEDEX_METER\"";
      tep_db_query($meter_sql);

      return true;
    }

    function _ParseFedex($data) {
      $current = 0;
      $length = strlen($data);
      $resultArray = array();
      while ($current < $length) {
        $endpos = strpos($data, ',', $current);
        if ($endpos === FALSE) { break; }
        $index = substr($data, $current, $endpos - $current);
        $current = $endpos + 2;
        $endpos = strpos($data, '"', $current);
        $resultArray[$index] = substr($data, $current, $endpos - $current);
        $current = $endpos + 1;
      }
    return $resultArray;
    }
     
    function _getQuote($address_array) {
      $country_id = $this->_get_country_id($address_array['country-code']);
      $postcode = $address_array['postal-code'];
      $state = $address_array['region'];
      
/*      debug($address_array, 'address_array in $this::_getQuote()', 381);
      debug($country_id, 'country_id in $this::_getQuote()', 381);
      debug($this->country, '$this->country in $this::_getQuote()', 381);
*/      
      
      if (GOOGLECHECKOUT_FEDEX_ACCOUNT == "NONE" || strlen(GOOGLECHECKOUT_FEDEX_ACCOUNT) == 0) {
        return array('error' => 'You forgot to set up your Fedex account number, this can be set up in Admin -> Modules -> Shipping');
      }
      if (GOOGLECHECKOUT_FEDEX_ADDRESS_1 == "NONE" || strlen(GOOGLECHECKOUT_FEDEX_ADDRESS_1) == 0) {
        return array('error' => 'You forgot to set up your ship from street address line 1, this can be set up in Admin -> Modules -> Shipping');
      }
      if (GOOGLECHECKOUT_FEDEX_CITY == "NONE" || strlen(GOOGLECHECKOUT_FEDEX_CITY) == 0) {
        return array('error' => 'You forgot to set up your ship from City, this can be set up in Admin -> Modules -> Shipping');
      }
      if (GOOGLECHECKOUT_FEDEX_POSTAL == "NONE" || strlen(GOOGLECHECKOUT_FEDEX_POSTAL) == 0) {
        return array('error' => 'You forgot to set up your ship from postal code, this can be set up in Admin -> Modules -> Shipping');
      }
      if (GOOGLECHECKOUT_FEDEX_PHONE == "NONE" || strlen(GOOGLECHECKOUT_FEDEX_PHONE) == 0) {
        return array('error' => 'You forgot to set up your ship from phone number, this can be set up in Admin -> Modules -> Shipping');
      }
      if (GOOGLECHECKOUT_FEDEX_METER == "NONE") { 
        if ($this->_getMeter() === false) return array('error' => $this->error_message);
      }

      $data = '0,"25"'; // TransactionCode
      $data .= '10,"' . GOOGLECHECKOUT_FEDEX_ACCOUNT . '"'; // Sender fedex account number
      $data .= '498,"' . $this->meter . '"'; // Meter number
      $data .= '8,"' . GOOGLECHECKOUT_FEDEX_STATE . '"'; // Sender state code
      $orig_zip = str_replace(array(' ', '-'), '', GOOGLECHECKOUT_FEDEX_POSTAL);
      $data .= '9,"' . $orig_zip . '"'; // Origin postal code
      $data .= '117,"' . $this->country . '"'; // Origin country
      $dest_zip = str_replace(array(' ', '-'), '', $postcode);
      $data .= '17,"' . $dest_zip . '"'; // Recipient zip code
      if ($country_id == "US" || $country_id == "CA" || $country_id == "PR") {
        //$state .= tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], ''); // Recipient state
        
        if ($state == "QC") $state = "PQ";
        $data .= '16,"' . $state . '"'; // Recipient state
      }
      $data .= '50,"' . $address_array['country-code'] . '"'; // Recipient country
      $data .= '75,"' . GOOGLECHECKOUT_FEDEX_WEIGHT . '"'; // Weight units
      if (GOOGLECHECKOUT_FEDEX_WEIGHT == "KGS") {
        $data .= '1116,"C"'; // Dimension units
      } else {
        $data .= '1116,"I"'; // Dimension units
      }
      $data .= '1401,"' . $this->pounds . '"'; // Total weight
      $data .= '1529,"1"'; // Quote discounted rates
      if ($this->insurance > 0) {
        $data .= '1415,"' . $this->insurance . '"'; // Insurance value
        $data .= '68,"USD"'; // Insurance value currency
      }
      
      $data .= '440,"Y"';
      
      $data .= '1273,"' . $this->packageType . '"'; // Package type
      $data .= '1333,"' . GOOGLECHECKOUT_FEDEX_DROPOFF . '"'; // Drop of drop off or pickup
      if (GOOGLECHECKOUT_FEDEX_LIST_RATES == 'True') {
        $data .= '1529,"2"'; // Also return list rates
      }
      $data .= '99,""'; // End of record
      if (GOOGLECHECKOUT_FEDEX_DEBUG == 'True') echo "Data sent to Fedex for Rating: " . $data . "<br />";
      $fedexData = $this->_AccessFedex($data);
      if (GOOGLECHECKOUT_FEDEX_DEBUG == 'True') echo "Data returned from Fedex for Rating: " . $fedexData . "<br />";
      if (strlen($fedexData) == 0) {
        $this->error_message = 'No data returned from Fedex, perhaps the Fedex site is down';
        return array('error' => $this->error_message);
      }
      $fedexData = $this->_ParseFedex($fedexData);
      $i = 1;
      if ($this->country == $order->delivery['country']['iso_code_2']) {
        $this->intl = FALSE;
      } else {
        $this->intl = TRUE;
      }
      $rates = NULL;
      while (isset($fedexData['1274-' . $i])) {
        if ($this->intl) {
          if (isset($this->international_types[$fedexData['1274-' . $i]])) {
            if (GOOGLECHECKOUT_FEDEX_LIST_RATES == 'False') {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1419-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1419-' . $i];
              }
            } else {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1528-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1528-' . $i];
              }
            }
          }
        } else {
          if (isset($this->domestic_types[$fedexData['1274-' . $i]])) {
            if (GOOGLECHECKOUT_FEDEX_LIST_RATES == 'False') {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1419-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1419-' . $i];
              }
            } else {
              if (isset($fedexData['3058-' . $i])) {
                $rates[$fedexData['1274-' . $i] . $fedexData['3058-' . $i]] = $fedexData['1528-' . $i];
              } else {
                $rates[$fedexData['1274-' . $i]] = $fedexData['1528-' . $i];
              }
            }
          }
        }
        $i++;
      }

      if (is_array($rates)) {
        if (GOOGLECHECKOUT_FEDEX_WEIGHT_SORT == 'Low to High') {
          asort($rates);
        } else {
          arsort($rates);
        }
      } else {
        $this->error_message = 'No Rates Returned, ' . $fedexData['2'] . ' : ' . $fedexData['3']; 
        return array('error' => $this->error_message);
      }

      return ((sizeof($rates) > 0) ? $rates : false);
    }

    function get_shipping_rates_default() {
      //$this->google_quote($address_array, $total_weight, $total_count);
      reset($this->domestic_types);
      if (sizeof($this->domestic_types)>0 && $this->enabled) {
      while (list($_key,$_value) = each($this->domestic_types)) {
        if (defined('GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key)) && defined('GOOGLECHECKOUT_FEDEX_METHOD_COST_'.strtoupper($_key))) {
        	$result[] = array('title' => constant('GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key)), 'cost' => constant('GOOGLECHECKOUT_FEDEX_METHOD_COST_'.strtoupper($_key)));
        }
      }
      }
      
      reset($this->international_types);
      if (sizeof($this->international_types)>0 && $this->enabled) {
      while (list($_key,$_value) = each($this->international_types)) {
        if (defined('GOOGLECHECKOUT_FEDEX_IMETHOD_'.strtoupper($_key)) && defined('GOOGLECHECKOUT_FEDEX_IMETHOD_COST_'.strtoupper($_key))) {
        	$result[] = array('title' => constant('GOOGLECHECKOUT_FEDEX_IMETHOD_'.strtoupper($_key)), 'cost' => constant('GOOGLECHECKOUT_FEDEX_IMETHOD_COST_'.strtoupper($_key)));
        }
      }           
      } else $result = false;
     return $result;
    } 

    function get_shipping_rates($address_array, $total_weight, $total_count, $total_cost) {    	
      $country_id = $this->_get_country_id($address_array['country-code']);
      $zone_id = $this->_get_zone_id($address_array['region']);
      
      if ( ($this->enabled == true) && ((int)GOOGLECHECKOUT_FEDEX_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . GOOGLECHECKOUT_FEDEX_ZONE . "' and zone_country_id = '" . $country_id . "' order by zone_id");
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
          	
    		$_result = $this->google_quote($address_array, $total_weight, $total_count, $total_cost);
    		    		
    		reset($this->domestic_types);
    		if (sizeof($this->domestic_types)>0) {
    			while (list($_key,$_value) = each($this->domestic_types)) {
    				if (defined('GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key))) {
              $result[] = array('title' => constant('GOOGLECHECKOUT_FEDEX_METHOD_'.strtoupper($_key)), 'cost' => number_format($_result['quotes']['methods'][$_key]['cost'],2), 'shippable' => (isset($_result['quotes']['methods'][$_key]) && $this->enabled == true  && $_result['type'])?'true':'false');
    				}
    			}
    		} else $result = false;
    		
    		reset($this->international_types);
    		if (sizeof($this->international_types)>0) {
    			while (list($_key,$_value) = each($this->international_types)) {
    				if (defined('GOOGLECHECKOUT_FEDEX_IMETHOD_'.str_replace(" ","_",strtoupper($_key)))) {
    					$result[] = array('title' => constant('GOOGLECHECKOUT_FEDEX_IMETHOD_'.str_replace(" ","_",strtoupper($_key))), 'cost' => number_format($_result['quotes']['methods'][$_key]['cost'],2), 'shippable' => (isset($_result['quotes']['methods'][$_key]) && $this->enabled == true && !$_result['type'])?'true':'false');
    				}
    			}
    		} else $result = false;   
    		
    	
        return $result; 		
    }

    function _get_country_code($country_code) {
       $country_query = tep_db_query("Select countries_iso_code_2 from ".TABLE_COUNTRIES." where countries_id = '".$country_code."'");     
       $country = tep_db_fetch_array($country_query);
       return $country['countries_iso_code_2'];
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
  
  function debug($_result, $name, $line='', $filename='zergus.txt') {
///BOF DEBUG
    		$fp = fopen(DIR_FS_CATALOG.'/zergus.txt','a');
    		ob_start();
    		print_r($_result);
    		$ret_val = ob_get_contents();
    		ob_end_clean();    		

    		if ($fp) {
    			fwrite($fp, "\n+++++++++++++++++++++++++++++++++++++ {$name} line {$line} +++++++++++++++++++++++++++++++++++++++++++++++++++\n");
    			fwrite($fp, "\n".date('Y-m-d H:m:s')."   ");
    			fwrite($fp, $ret_val);
    			//fwrite($fp, "\nEOF ".date('Y-m-d H:m:s') ."---------- {$name} line {$line} -------------------\n");
    			fwrite($fp, "\n=================================================================================================================\n");
    		}
    		fclose($fp);
///EOF DEBUG
  }
?>
