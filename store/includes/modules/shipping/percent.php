<?php

/*

  $Id: percent.php,v 1.0 2003/09/04 22:41:52 hpdl Exp $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  class percent {

    var $code, $title, $description, $icon, $enabled;



// class constructor

    function percent() {

      global $order;

      $this->code = 'percent';

      $this->title = MODULE_SHIPPING_PERCENT_TEXT_TITLE;

      $this->description = MODULE_SHIPPING_PERCENT_TEXT_DESCRIPTION;

      $this->sort_order = MODULE_SHIPPING_PERCENT_SORT_ORDER;

      $this->icon = '';

      $this->tax_class = MODULE_SHIPPING_PERCENT_TAX_CLASS;

      $this->enabled = ((MODULE_SHIPPING_PERCENT_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_PERCENT_ZONE > 0) ) {

        $check_flag = false;

        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_PERCENT_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");

        while ($check = tep_db_fetch_array($check_query)) {

          if ($check['zone_id'] < 1) {

            $check_flag = true;

            break;

          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {

            $check_flag = true;

            break;

          }

        }



        if ($check_flag == false) {

          $this->enabled = false;

        }

      }

    }


    function quote($method = '') {

      global $order, $cart, $shipping_weight, $shipping_num_boxes;


      if (MODULE_SHIPPING_PERCENT_MODE == 'price') {

        $order_total = $cart->show_total();

      } else {

        $order_total = $shipping_weight;

      }



      $percent_cost = split("[:,]" , MODULE_SHIPPING_PERCENT_COST);

      $size = sizeof($percent_cost);

      for ($i=0, $n=$size; $i<$n; $i+=2) {

        if ($order_total <= $percent_cost[$i]) {

          $shipping = ($order_total * ($percent_cost[$i+1] / 100));

          break;

        }

      }



      if (MODULE_SHIPPING_PERCENT_MODE == 'weight') {

        $shipping = $shipping * $shipping_num_boxes;

      }



      $this->quotes = array('id' => $this->code,

                            'module' => MODULE_SHIPPING_PERCENT_TEXT_TITLE,

                            'methods' => array(array('id' => $this->code,

                                                     'title' => MODULE_SHIPPING_PERCENT_TEXT_WAY,

                                                     'cost' => $shipping + MODULE_SHIPPING_PERCENT_HANDLING)));



      if ($this->tax_class > 0) {

        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);

      }



      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
// disable the module if  free shipping
if (defined('MODULE_SHIPPING_FREEAMOUNT_AMOUNT')) {
      if ($this->enabled == true) {
        global $shipping_weight;
		if (($cart->show_total() >= MODULE_SHIPPING_FREEAMOUNT_AMOUNT) && ($shipping_weight <= MODULE_SHIPPING_FREEAMOUNT_WEIGHT_MAX)) {
		  return false;
		} else {
              return $this->quotes;
                }
        }
} 
}



    function check() {

      if (!isset($this->_check)) {

        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_PERCENT_STATUS'");

        $this->_check = tep_db_num_rows($check_query);

      }

      return $this->_check;

    }



    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Percent Method', 'MODULE_SHIPPING_PERCENT_STATUS', 'True', 'Do you want to offer percent rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Percent', 'MODULE_SHIPPING_PERCENT_COST', '24.99:20,49.99:15,99.99:10,10000:0', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Percent Method', 'MODULE_SHIPPING_PERCENT_MODE', 'weight', 'The shipping cost is based on the order total or the total weight of the items ordered.', '6', '0', 'tep_cfg_select_option(array(\'weight\', \'price\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_PERCENT_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_PERCENT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_PERCENT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

    }



    function remove() {

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");

    }



    function keys() {

      return array('MODULE_SHIPPING_PERCENT_STATUS', 'MODULE_SHIPPING_PERCENT_COST', 'MODULE_SHIPPING_PERCENT_MODE', 'MODULE_SHIPPING_PERCENT_HANDLING', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', 'MODULE_SHIPPING_PERCENT_ZONE', 'MODULE_SHIPPING_PERCENT_SORT_ORDER');

    }

  }

?>
