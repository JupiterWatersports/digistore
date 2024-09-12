<?php
/*
  ups_worldship.php
  by Alejandro Arbiza - www.alejandro-arbiza.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ups_print_worldship_xml {
    var $title, $output;

    function ups_print_worldship_xml() {
      $this->code = 'ups_print_worldship_xml';
      $this->title = MODULE_SHIPPING_LABEL_UPSWS_TITLE;
      $this->description = MODULE_SHIPPING_LABEL_UPSWS_DESCRIPTION;
      $this->enabled = ((MODULE_SHIPPING_LABEL_UPSWS_STATUS == 'true') ? true : false);

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        if ($value > 0) {
          $this->output[] = array('title' => $key . ':',
                                  'text' => $currencies->format($value, true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => $value);
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_LABEL_UPSWS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {      
      return array(
        'MODULE_SHIPPING_LABEL_UPSWS_STATUS',
        'MODULE_SHIPPING_LABEL_UPSWS_UPS_ACCOUNT_NUMBER',
        'MODULE_SHIPPING_LABEL_UPSWS_COUNTRY_ORIGIN',
        'MODULE_SHIPPING_LABEL_UPSWS_GOODS_DESCRIPTION',
        'MODULE_SHIPPING_LABEL_UPSWS_QVN_SUBJECT_LINE',
        'MODULE_SHIPPING_LABEL_UPSWS_QVN_MEMO',
        'MODULE_SHIPPING_LABEL_UPSWS_QVN_LABEL_REF',
        'MODULE_SHIPPING_LABEL_UPSWS_MULTIPLE_PACKAGES',
        'MODULE_SHIPPING_LABEL_UPSWS_MAX_WEIGHT_PER_PACKAGE',
      	'MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT',
      	'MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT_TELEPHONE',
      );
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('UPS Worldship XML auto import', 'MODULE_SHIPPING_LABEL_UPSWS_STATUS', 'true', 'Do you want to enable UPS Worldship label generator?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Account number', 'MODULE_SHIPPING_LABEL_UPSWS_UPS_ACCOUNT_NUMBER', '', 'UPS Account number', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Country of origin', 'MODULE_SHIPPING_LABEL_UPSWS_COUNTRY_ORIGIN', 'US', 'Country your goods are sent from', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Goods description', 'MODULE_SHIPPING_LABEL_UPSWS_GOODS_DESCRIPTION', '', 'General description of the goods you use to ship', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mail subject', 'MODULE_SHIPPING_LABEL_UPSWS_QVN_SUBJECT_LINE', '', 'Notification mail subject', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mail text', 'MODULE_SHIPPING_LABEL_UPSWS_QVN_MEMO', 'Your order has been shipped', 'This will be sent to you customer in the notification e-mail', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Label ref.', 'MODULE_SHIPPING_LABEL_UPSWS_QVN_LABEL_REF', '', 'Reference that will show on the label', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Multiple packages', 'MODULE_SHIPPING_LABEL_UPSWS_MULTIPLE_PACKAGES', 'false', 'Do you want the number of packages calculates based on weight?', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max weight/package (pounds)', 'MODULE_SHIPPING_LABEL_UPSWS_MAX_WEIGHT_PER_PACKAGE', '1', 'When dividing a package into many smaller ones, which must be the maximum weight of each package?', '6', '9', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Array of special chars for replacement', 'MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT', '\"Ä\"=>\"Ae\",\"Ü\"=>\"Ue\",\"Ö\"=>\"Oe\",\"ä\"=>\"ae\",\"ü\"=>\"ue\",\"ö\"=>\"oe\",\"ß\"=>\"ss\",\"&\"=>\"&amp;\",\"<\"=>\"&lt;\",\">\"=>\"&gt;\"', 'comma separated list of char(s) => replacement to replace banned chars (q.v. TechRefDoc)', '6', '10', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Array of special chars for telephone number replacement', 'MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT_TELEPHONE', '\" \"=>\"\",\"-\"=>\"\",\"(\"=>\"\",\")\"=>\"\"', 'comma separated list of char(s) => replacement to replace banned chars in the telephone number(q.v. TechRefDoc)', '6', '12', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
