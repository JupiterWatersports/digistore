<?php

/*
  $Id: paypal_express.php 2011-12-13 20:00:00 webprojectsol $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2011 Web Project Solutions LLC www.webprojectsol.com

  Released under the GNU General Public License
 */

class paypal_express {

    var $code, $title, $description, $enabled;

// class constructor
    function paypal_express() {
        global $order;

        $this->signature = 'paypal|paypal_express|2.0|2.3';

        $this->code = 'paypal_express';
        $this->title = MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_TITLE;
        $this->public_title = MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_PUBLIC_TITLE;
        $this->description = MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER;
        $this->enabled = ((MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS == 'True') ? true : false);

        if ((int) MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID > 0) {
            $this->order_status = MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID;
        }

        if (is_object($order))
            $this->update_status();
    }

// class methods
    function update_status() {
        global $order;

        if (($this->enabled == true) && ((int) MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

// Discount Code - start
/*
    function checkout_initialization_method() {
      global $language;

      if (file_exists(DIR_FS_CATALOG . 'ext/modules/payment/paypal/images/btn_express_' . basename($language) . '.gif')) {
        $image = 'ext/modules/payment/paypal/images/btn_express_' . basename($language) . '.gif';
      } else {
        $image = 'ext/modules/payment/paypal/images/btn_express.gif';
      }

      $string = '<a href="' . tep_href_link('ext/modules/payment/paypal/express.php', '', 'SSL') . '"><img src="' . $image . '" border="0" alt="" title="' . tep_output_string_protected(MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_BUTTON) . '" /></a>';

      return $string;
    }
*/
// Discount Code - end
    function javascript_validation() {
        return false;
    }

    function selection() {
        return array('id' => $this->code,
            'module' => $this->public_title);
    }

    function pre_confirmation_check() {
        if (!tep_session_is_registered('ppe_token')) {
            tep_redirect(tep_href_link('ext/modules/payment/paypal/express.php', '', 'SSL'));
        }
    }

    function confirmation() {
        global $comments;

        if (!isset($comments)) {
            $comments = null;
        }

        $confirmation = false;

        if (empty($comments)) {
            $confirmation = array('fields' => array(array('title' => MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_COMMENTS,
                        'field' => tep_draw_textarea_field('ppecomments', 'soft', '60', '5', $comments))));
        }

        return $confirmation;
    }

    function process_button() {
        return false;
    }

    function before_process() {
        global $order, $sendto, $ppe_token, $ppe_payerid, $_POST, $comments, $order_totals;

        if (empty($comments)) {
            if (isset($_POST['ppecomments']) && tep_not_null($_POST['ppecomments'])) {
                $comments = tep_db_prepare_input($_POST['ppecomments']);

                $order->info['comments'] = $comments;
            }
        }

        if (MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER == 'Live') {
            $api_url = 'https://api-3t.paypal.com/nvp';
        } else {
            $api_url = 'https://api-3t.sandbox.paypal.com/nvp';
        }

        $params = array('USER' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME,
            'PWD' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD,
            'VERSION' => '85.0',
            'SIGNATURE' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE,
            'METHOD' => 'DoExpressCheckoutPayment',
            'TOKEN' => $ppe_token,
            'PAYMENTREQUEST_0_PAYMENTACTION' => ((MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization'),
            'PAYERID' => $ppe_payerid,
            'PAYMENTREQUEST_0_CURRENCYCODE' => $order->info['currency'],
            'BUTTONSOURCE' => 'osCommerce22_Default_EC');

        if (is_numeric($sendto) && ($sendto > 0)) {
            $params['PAYMENTREQUEST_0_SHIPTONAME'] = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
            $params['PAYMENTREQUEST_0_SHIPTOSTREET'] = $order->delivery['street_address'];
            $params['PAYMENTREQUEST_0_SHIPTOCITY'] = $order->delivery['city'];
            $params['PAYMENTREQUEST_0_SHIPTOSTATE'] = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
            $params['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $order->delivery['country']['iso_code_2'];
            $params['PAYMENTREQUEST_0_SHIPTOZIP'] = $order->delivery['postcode'];
            $params['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $order->delivery['telephone'];
        }

        $nProd = sizeof($order->products);
        $subtotal = 0;
        for ($i = 0; $i < $nProd; ++$i) {
            $subtotal += $this->format_raw($order->products[$i]['final_price']) * $order->products[$i]['qty'];
        }
        $difst = 0;
        if ($subtotal != $this->format_raw($order->info['subtotal'])) {
            $difst = $this->format_raw($order->info['subtotal']) - $subtotal;
        }

        $order->products[$nProd - 1]['final_price'] += $difst;
        for ($i = 0; $i < $nProd; ++$i) {
            $params['L_PAYMENTREQUEST_0_NAME' . $i] = $order->products[$i]['name'];
            $params['L_PAYMENTREQUEST_0_NUMBER' . $i] = $order->products[$i]['model'];
            #$params['L_PAYMENTREQUEST_0_DESC' . $i] = $order->products[$i]['description'];
            $params['L_PAYMENTREQUEST_0_AMT' . $i] = $this->format_raw($order->products[$i]['final_price']);
            $params['L_PAYMENTREQUEST_0_QTY' . $i] = $order->products[$i]['qty'];
        }

        if (!is_array($order_totals)) {
            require_once(DIR_WS_CLASSES . 'order_total.php');
            $order_total_modules = new order_total;
            $order_totals = $order_total_modules->process();
        }

        $order_details = array();
        $order_details['subtotal'] = 0;
        $order_details['shippingcost'] = 0;
        $order_details['tax'] = 0;
        $order_details['discount'] = 0;
        $order_details['handling'] = 0;
        $order_details['total'] = 0;

        foreach ($order_totals as $order_total) {
            if ($order_total['code'] == 'ot_subtotal') {
                $order_details['subtotal'] += $order_total['value'];
            } elseif ($order_total['code'] == 'ot_shipping') {
                $order_details['shippingcost'] += $order_total['value'];
            } elseif ($order_total['code'] == 'ot_tax') {
                $order_details['tax'] += $order_total['value'];
            } elseif ($order_total['code'] == 'ot_total') {
                $order_details['total'] += $order_total['value'];
            } elseif ($order_total['code'] == 'ot_redemptions' || $order_total['code'] == 'ot_gv' || $order_total['code'] == 'ot_coupon') {
                $order_details['discount'] += $order_total['value'];
            } elseif ($order_total['code'] == 'ot_insurance') {
                $order_details['handling'] += $order_total['value'];
            } else {
                if ($order_total['value'] > 0) {
                    $order_details['handling'] += $order_total['value'];
                } else {
                    $order_details['discount'] += $order_total['value'];
                }
            }
        }

        $params['PAYMENTREQUEST_0_ITEMAMT'] = $this->format_raw($order_details['subtotal']);
        $params['PAYMENTREQUEST_0_TAXAMT'] = $this->format_raw($order_details['tax']);
        $params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $this->format_raw($order_details['shippingcost']);
        $params['PAYMENTREQUEST_0_SHIPDISCAMT'] = $this->format_raw($order_details['discount']);
        $params['PAYMENTREQUEST_0_HANDLINGAMT'] = $this->format_raw($order_details['handling']);
        $params['PAYMENTREQUEST_0_AMT'] = $this->format_raw($order_details['total']);

        $post_string = '';

        foreach ($params as $key => $value) {
            $post_string .= $key . '=' . urlencode(trim($value)) . '&';
        }

        $post_string = substr($post_string, 0, -1);

        $response = $this->sendTransactionToGateway($api_url, $post_string);
        $response_array = array();
        parse_str($response, $response_array);

        if (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) {
            tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0']), 'SSL'));
        }
    }

    function after_process() {
        tep_session_unregister('ppe_token');
        tep_session_unregister('ppe_payerid');
    }

    function get_error() {
        return false;
    }

    function check() {
        if (!isset($this->_check)) {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function install() {
        global $languages_id;

        if (isset($languages_id) && !empty($languages_id)) {
            $lang_query = tep_db_query("SELECT code FROM " . TABLE_LANGUAGES . " WHERE languages_id = " . $languages_id);
            $lang = tep_db_fetch_array($lang_query);

            switch ($lang['code']) {
                case 'it':
                    $mylanguage = 'it';
                    break;
                default:
                    $mylanguage = 'en';
            }
        } else {
            $mylanguage = 'en';
        }
        if ($mylanguage === 'en') {
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1', 'Enable PayPal Express Checkout');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1_TEXT', 'Do you want to accept PayPal Express Checkout payments?');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2', 'API Username');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2_TEXT', 'The username to use for the PayPal API service');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3', 'API Password');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3_TEXT', 'The password to use for the PayPal API service');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4', 'API Signature');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4_TEXT', 'The signature to use for the PayPal API service');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5', 'Transaction Server');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5_TEXT', 'Use the live or testing (sandbox) gateway server to process transactions?');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6', 'Transaction Method');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6_TEXT', 'The processing method to use for each transaction');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7', 'Payment Zone');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7_TEXT', 'If a zone is selected, only enable this payment method for that zone');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8', 'Sort order of display');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8_TEXT', 'Sort order of display. Lowest is displayed first');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9', 'Set Order Status');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9_TEXT', 'Set the status of orders made with this payment module to this value');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10', 'cURL Program Location');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10_TEXT', 'The location to the cURL program application');
        } elseif ($mylanguage === 'it') {
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1', 'Abilita il Modulo PayPal Express Checkout');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1_TEXT', 'Attivare il sistema di pagamento tramite la Cassa Veloce di PayPal?');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2', 'Nome Utente API');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2_TEXT', 'Il Nome Utente usato per il servizio API PayPal');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3', 'Password API');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3_TEXT', 'La Password usata per il servizio API PayPal');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4', 'Firma');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4_TEXT', 'La Firma usata per il servizio API PayPal');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5', 'Transazione Server');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5_TEXT', "Usa il server gateway per i test (Sandbox) o per completare veramente l\'acquisto (Live)");
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6', 'Metodo di Transazione');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6_TEXT', 'Il metodo di processo usato per ogni transazione');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7', 'Zone di pagamento');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7_TEXT', 'Se &egrave; selezionata una zona, il modulo sar&agrave; abilitato solo per quella zona');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8', 'Ordine');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8_TEXT', 'Ordine di visualizzazione');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9', 'Stato ordine dopo pagamento');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9_TEXT', "Definisce quale stato ordine attribuire all\'ordine una volta completato il pagamento");
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10', 'cURL Localizzazione Programma');
            define('MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10_TEXT', 'La localizzazione del Programma cURL');
        }

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS', 'False', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG1_TEXT . "', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME', '', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG2_TEXT . "', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD', '', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG3_TEXT . "', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE', '', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG4_TEXT . "', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER', 'Live', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG5_TEXT . "', '6', '0', 'tep_cfg_select_option(array(\'Live\', \'Sandbox\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD', 'Sale', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG6_TEXT . "', '6', '0', 'tep_cfg_select_option(array(\'Authorization\', \'Sale\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE', '0', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG7_TEXT . "', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER', '0', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG8_TEXT . "', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID', '0', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG9_TEXT . "', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10 . "', 'MODULE_PAYMENT_PAYPAL_EXPRESS_CURL', '/usr/bin/curl', '" . MODULE_PAYMENT_PAYPAL_EXPRESS_CONFIG10_TEXT . "', '6', '0' , now())");
    }

    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
        return array('MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD', 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_SERVER', 'MODULE_PAYMENT_PAYPAL_EXPRESS_TRANSACTION_METHOD', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE', 'MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_EXPRESS_CURL');
    }

    function sendTransactionToGateway($url, $parameters) {
        $server = parse_url($url);

        if (!isset($server['port'])) {
            $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
        }

        if (!isset($server['path'])) {
            $server['path'] = '/';
        }

        if (isset($server['user']) && isset($server['pass'])) {
            $header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
        }

        if (function_exists('curl_init')) {
            $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
            curl_setopt($curl, CURLOPT_PORT, $server['port']);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

            $result = curl_exec($curl);

            curl_close($curl);
        } else {
            exec(escapeshellarg(MODULE_PAYMENT_PAYPAL_EXPRESS_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k', $result);
            $result = implode("\n", $result);
        }

        return $result;
    }

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') {
        global $currencies, $currency;

        if (empty($currency_code) || !$this->is_set($currency_code)) {
            $currency_code = $currency;
        }

        if (empty($currency_value) || !is_numeric($currency_value)) {
            $currency_value = $currencies->currencies[$currency_code]['value'];
        }

        return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

}

?>
