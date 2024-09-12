<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  if ( !class_exists('OSCOM_PayPal') ) {
    include(DIR_FS_CATALOG . 'includes/apps/paypal/OSCOM_PayPal.php');
  }

  class paypal_pro_dp {

    const REQUIRES = [
      'firstname',
      'lastname',
      'street_address',
      'city',
      'postcode',
      'country',
      'telephone',
      'email_address',
    ];

    public $code = 'paypal_pro_dp';
    public $title, $description, $enable, $_app;

    function __construct() {
      global $order;

      $this->_app = new OSCOM_PayPal();
      $this->_app->loadLanguageFile('modules/DP/DP.php');

      $this->signature = 'paypal|paypal_pro_dp|' . $this->_app->getVersion() . '|2.3';
      $this->api_version = $this->_app->getApiVersion();

      $this->code;
      $this->title = $this->_app->getDef('module_dp_title');
      $this->public_title = $this->_app->getDef('module_dp_public_title');
      $this->description = '<div align="center">' . $this->_app->drawButton($this->_app->getDef('module_dp_legacy_admin_app_button'), tep_href_link('paypal.php', 'action=configure&module=DP'), 'primary', null, true) . '</div>';
      $this->sort_order = defined('OSCOM_APP_PAYPAL_DP_SORT_ORDER') ? OSCOM_APP_PAYPAL_DP_SORT_ORDER : 0;
      $this->enabled = defined('OSCOM_APP_PAYPAL_DP_STATUS') && in_array(OSCOM_APP_PAYPAL_DP_STATUS, ['1', '0']);
      $this->order_status = defined('OSCOM_APP_PAYPAL_DP_ORDER_STATUS_ID') && ((int)OSCOM_APP_PAYPAL_DP_ORDER_STATUS_ID > 0) ? (int)OSCOM_APP_PAYPAL_DP_ORDER_STATUS_ID : 0;

      if ( !defined('MODULE_PAYMENT_INSTALLED') || !tep_not_null(MODULE_PAYMENT_INSTALLED) || !in_array('paypal_express.php', explode(';', MODULE_PAYMENT_INSTALLED)) || !defined('OSCOM_APP_PAYPAL_EC_STATUS') || !in_array(OSCOM_APP_PAYPAL_EC_STATUS, ['1', '0']) ) {
        $this->description .= '<div class="alert alert-warning">' . $this->_app->getDef('module_dp_error_express_module') . '</div>';

        $this->enabled = false;
      }

      if ( defined('OSCOM_APP_PAYPAL_DP_STATUS') ) {
        if ( OSCOM_APP_PAYPAL_DP_STATUS == '0' ) {
          $this->title .= ' [Sandbox]';
          $this->public_title .= ' (' . $this->code . '; Sandbox)';
        }
      }

      if ( !function_exists('curl_init') ) {
        $this->description .= '<div class="alert alert-warning">' . $this->_app->getDef('module_dp_error_curl') . '</div>';

        $this->enabled = false;
      }

      if ( $this->enabled === true ) {
        if ( OSCOM_APP_PAYPAL_GATEWAY == '1' ) { // PayPal
          if ( !$this->_app->hasCredentials('DP') ) {
            $this->description .= '<div class="alert alert-warning">' . $this->_app->getDef('module_dp_error_credentials') . '</div>';

            $this->enabled = false;
          }
        } else { // Payflow
          if ( !$this->_app->hasCredentials('DP', 'payflow') ) {
            $this->description .= '<div class="alert alert-warning">' . $this->_app->getDef('module_dp_error_credentials_payflow') . '</div>';

            $this->enabled = false;
          }
        }
      }

      if ( $this->enabled === true ) {
        if ( isset($order) && is_object($order) ) {
          $this->update_status();
        }
      }

      $this->cc_types = [
        'VISA' => 'Visa',
        'MASTERCARD' => 'MasterCard',
        'DISCOVER' => 'Discover Card',
        'AMEX' => 'American Express',
        'MAESTRO' => 'Maestro',
      ];
    }

    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)OSCOM_APP_PAYPAL_DP_ZONE > 0) ) {
        $check_query = tep_db_query("SELECT zone_id FROM zones_to_geo_zones WHERE geo_zone_id = '" . OSCOM_APP_PAYPAL_DP_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if (($check['zone_id'] < 1) || ($check['zone_id'] == $order->delivery['zone_id'])) {
            return;
          }
        }

        $this->enabled = false;
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return [
        'id' => $this->code,
        'module' => $this->public_title,
      ];
    }

    function pre_confirmation_check() {
      if ( $this->templateClassExists() ) {
        $GLOBALS['oscTemplate']->addBlock('<style>.date-fields .form-control {width:auto;display:inline-block}</style>', 'header_tags');
        $GLOBALS['oscTemplate']->addBlock($this->getSubmitCardDetailsJavascript(), 'footer_scripts');
      }
    }

    function confirmation() {
      global $order;

      $types_array = [];
      foreach ( $this->cc_types as $key => $value ) {
        if ($this->isCardAccepted($key)) {
          $types_array[] = [
            'id' => $key,
            'text' => $value,
          ];
        }
      }

      $today = getdate();

      $months_array = [];
      for ($i = 1; $i <= 12; $i++) {
        $months_array[] = ['id' => sprintf('%2d', $i), 'text' => sprintf('%02d', $i)];
      }

      $year_valid_from_array = [];
      for ($i = $today['year'] - 10; $i <= $today['year']; $i++) {
        $year_valid_from_array[] = ['id' => strftime('%Y',mktime(0, 0, 0, 1, 1, $i)), 'text' => strftime('%Y',mktime(0, 0, 0, 1, 1, $i))];
      }

      $year_expires_array = [];
      for ($i = $today['year']; $i < $today['year']+10; $i++) {
        $year_expires_array[] = ['id' => strftime('%Y',mktime(0, 0, 0, 1, 1, $i)), 'text' => strftime('%Y',mktime(0, 0, 0, 1, 1, $i))];
      }

      $content = '<table id="paypal_table_new_card" border="0" width="100%" cellspacing="0" cellpadding="2">'
               . '  <tr>'
               . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_type') . '</td>'
               . '    <td>' . tep_draw_pull_down_menu('cc_type', $types_array, '', 'id="paypal_card_type" class="form-control"') . '</td>'
               . '  </tr>'
               . '  <tr>'
               . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_owner') . '</td>'
               . '    <td>' . tep_draw_input_field('cc_owner', $order->billing['name'], 'class="form-control"') . '</td>'
               . '  </tr>'
               . '  <tr>'
               . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_number') . '</td>'
               . '    <td>' . tep_draw_input_field('cc_number_nh-dns', '', 'id="paypal_card_num" class="form-control"') . '</td>'
               . '  </tr>'
               . '  <tr>'
               . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_expires') . '</td>'
               . '    <td class="date-fields">' . tep_draw_pull_down_menu('cc_expires_month', $months_array, '', 'class="form-control"') . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $year_expires_array, '', 'class="form-control"') . '</td>'
               . '  </tr>'
               . '  <tr>'
               . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_cvc') . '</td>'
               . '    <td class="date-fields">' . tep_draw_input_field('cc_cvc_nh-dns', '', 'size="5" maxlength="4" class="form-control"') . '<a class="tooltip" style="color: #084482; text-decoration: none; border-bottom: 1px dashed #084482; cursor: pointer;"> <span id="cardSecurityCodeInfo">' . tep_output_string($this->_app->getDef('module_dp_field_card_cvc_info')) . '</span>' . $this->_app->getDef('module_dp_field_card_cvc_info_link') . '</a></td>'
               . '  </tr>';

      if ( $this->isCardAccepted('MAESTRO') ) {
        $content .= '  <tr>'
                  . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_valid_from') . '</td>'
                  . '    <td class="date-fields">' . tep_draw_pull_down_menu('cc_starts_month', $months_array, '', 'id="paypal_card_date_start"') . '&nbsp;' . tep_draw_pull_down_menu('cc_starts_year', $year_valid_from_array) . '&nbsp;' . $this->_app->getDef('module_dp_field_card_valid_from_info') . '</td>'
                  . '  </tr>'
                  . '  <tr>'
                  . '    <td width="30%">' . $this->_app->getDef('module_dp_field_card_issue_number') . '</td>'
                  . '    <td>' . tep_draw_input_field('cc_issue_nh-dns', '', 'id="paypal_card_issue" size="3" maxlength="2"') . '&nbsp;' . $this->_app->getDef('module_dp_field_card_issue_number_info') . '</td>'
                  . '  </tr>';
      }

      $content .= '</table>';

      if ( !$this->templateClassExists() ) {
        $content .= $this->getSubmitCardDetailsJavascript();
      }

      $confirmation = ['title' => $content];

      return $confirmation;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      if ( OSCOM_APP_PAYPAL_GATEWAY == '1' ) {
        $this->before_process_paypal();
      } else {
        $this->before_process_payflow();
      }
    }

    function before_process_paypal() {
      global $order, $response_array,$customer_id,$_SESSION;
// Array ( [cc_type] => VISA [cc_owner] => asdasd [cc_number_nh-dns] => 2123333333333333 [cc_expires_month] => 01 [cc_expires_year] => 2022 [cc_cvc_nh-dns] => 222 [cc_starts_month] => 01 [cc_starts_year] => 2012 [cc_issue_nh-dns] => [comments] => )

      if ( isset($_POST['cc_owner']) && !empty($_POST['cc_owner']) && isset($_POST['cc_type']) && $this->isCardAccepted($_POST['cc_type']) && isset($_POST['cc_number_nh-dns']) && !empty($_POST['cc_number_nh-dns']) ) {
      //print_r($_POST);
     // exit;
              //  'AMT' => $this->_app->formatCurrencyRaw($order->info['total']),

	  for ($j=0, $n=sizeof($order->products); $j<$n; $j++) {
	  $order->products[$j]['name']=str_replace("'","",$order->products[$j]['name']);
	  $order->products[$j]['name']=str_replace('"','',$order->products[$j]['name']);	  
	$products_array[]=' { "itemName": "'. $order->products[$j]['name']  .'",
	 "itemPrice": '.$order->products[$j]['final_price'].',
	 "itemQuantity": '.$order->products[$j]['qty'].',
	
	 "itemId": "'.$order->products[$j]['model'].'"  }';
	}
	$products_str=implode(",",$products_array);
	
	$ordinfo=tep_db_fetch_array(tep_db_query("SELECT MAX(orders_id) as ordid  FROM `orders`"));
	
	//$billcountryinfo=tep_db_fetch_array(tep_db_query("SELECT *  FROM `countries` where countries_name='".$order->billing['country']."'"));
	//$dellcountryinfo=tep_db_fetch_array(tep_db_query("SELECT *  FROM `countries` where countries_name='".$order->delivery['country']."'"));
	
	//$billzoneinfo=tep_db_fetch_array(tep_db_query("SELECT *  FROM `countries` where countries_name='".$order->billing['zone_id']."'"));
	//$dellzoneinfo=tep_db_fetch_array(tep_db_query("SELECT *  FROM `countries` where countries_name='".$order->delivery['zone_id']."'"));
	
		
$street_address=$order->customer['street_address'];
$postalCode=$order->customer['postcode'];
$city=$order->customer['city'];
$del_street_address=$order->delivery['street_address'];
$del_postalCode=$order->delivery['postcode'];
$del_city=$order->delivery['city'];
$del_province=tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
$del_country=$order->delivery['country']['iso_code_2'];
$del_company=$order->delivery['company'];
$bill_street_address=$order->billing['street_address'];
$bill_postalCode=$order->billing['postcode'];
$bill_city=$order->billing['city'];
$bill_province=tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], $order->billing['state']);

$bill_country=$order->billing['country']['iso_code_2'];
$amount=$this->_app->formatCurrencyRaw($order->info['total']);
$shipamount=$this->_app->formatCurrencyRaw($order->info['shipping_cost']);
$custname=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
$del_custname=trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']);
$custphone=str_replace("-","",$order->customer['telephone']);
$custphone=str_replace("(","",$custphone);
$custphone=str_replace(")","",$custphone);
$custphone=str_replace(" ","",$custphone);
$custemail=$order->customer['email_address'];
$cardBrand=$_POST['cc_type'];
if($cardBrand)$cardBrand='VISA';

$currency=$order->info['currency'];
$gateway='PAYPAL_PRO';
$accountNumber=$customer_id ;
$scarrier=$order->info['shipping_method'];
$t=time();
$createdDate=date('c',$t) ;
		
	$randomNum1=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
	$randomNum2=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum3=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum4=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum5=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12);
	
	//$sessid=$randomNum1."-".$randomNum2."-".$randomNum3."-".$randomNum4."-".$randomNum5;
	//$_SESSION['tempsessid']=$sessid;
	$sessid=$_SESSION['tempsessid'];
	$trcinfo=str_replace(' ','',$_POST['cc_number_nh-dns']);
	$trcinfo=str_replace('(','',$trcinfo);
	$trcinfo=str_replace(')','',$trcinfo);
	$trcinfo=str_replace('-','',$trcinfo);
	$trcinfo=str_replace('/','',$trcinfo);
	
$cardbin=substr($trcinfo,0,6);
$cardlast=substr($trcinfo,-4);
$cardexpmonth=$_POST['cc_expires_month'];
$cardexpyear=$_POST['cc_expires_year']; 

$_SESSION['ccbin']=$cardbin;
$_SESSION['cclast']=$cardlast;
$_SESSION['ccmonth']=$cardexpmonth;
$_SESSION['ccyear']=$cardexpyear;
if(!$shipamount) $shipamount=0;
			
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';
$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];
   
$url='https://api.signifyd.com/v3/orders/events/checkouts';

$recby='"receivedBy": "string"';
 
 //echo '<script defer type="text/javascript" id="sig-api" data-order-session-id="'.$sessid.'" src="https://cdn-scripts.signifyd.com/api/script-tag.js"></script>';
 
 
$ch = curl_init();
        $options = [
            CURLOPT_PORT => 443,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $apiKey,
            CURLOPT_USERAGENT => 'Signifyd PHP SDK',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $url
        ];
       

 
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
     

     
            $options[CURLOPT_POST] = true;
 

        curl_setopt_array($ch, $options);
//$ot=rand();
$ot=$ordinfo['ordid']+1;

$_SESSION['tempordid']=$ot;
 $casesampledata='{
  "checkoutId": "'.rand().'",
  "device": {
  "clientIpAddress": "'.$_SERVER['REMOTE_ADDR'].'",
  "sessionId": "'.$sessid.'" 
  },
   "transactions": [
  {
    "paymentMethod": "CREDIT CARD",
  "checkoutPaymentDetails": {
  "billingAddress": {
  "streetAddress": "'.$bill_street_address.'",
   "postalCode": "'.$bill_postalCode.'",
  "city": "'.$bill_city.'",
  "provinceCode": "'.$bill_province.'",
  "countryCode": "'.$bill_country.'"
  },
  "accountHolderName": "'.$custname.'",
  
   "cardBin": "'.$cardbin.'",
   "cardExpiryMonth": '.$cardexpmonth.',
   "cardExpiryYear": "'.$cardexpyear.'",
   "cardLast4": "'.$cardlast.'",
   "cardBrand": "'.$cardBrand.'"
  },
  "amount": '.$amount.',
  "currency": "'.$currency.'",
  "gateway": "PAYPAL_PRO"
  }
  ],
  "orderId": "'.$ot.'",
  "purchase": {
  "createdAt": "'.$createdDate.'",
  "orderChannel": "WEB",
  "totalPrice": '.$amount.',
  "currency": "'.$currency.'",
  "confirmationEmail": "'.$custemail.'",
  "products": [
  
 '.$products_str.'
 
  
  
  ],
  "shipments": [
  {
  "destination": {
  "fullName": "'.$del_custname.'",
  "organization": "'.$del_company.'",
  "email": null,
  "address": {
  "streetAddress": "'.$del_street_address.'",
  "postalCode": "'.$del_postalCode.'",
  "city": "'.$del_city.'",
  "provinceCode": "'.$del_province.'",
  "countryCode": "'.$del_country.'"
  }
  },
  "origin": {
           "locationId": "Jupiter-Store",
           "address": {
             "streetAddress": "1500 N US HWY 1",
              "postalCode": "33469",
             "city": "Jupiter",
             "provinceCode": "FL",
             "countryCode": "US"
           }
  } ,
  "carrier": "'.$scarrier.'",
  "fulfillmentMethod": "DELIVERY"
  }
  ],
  "confirmationPhone": "'.$custphone.'",
  "totalShippingCost": '.$shipamount.',
  '.$recby.'
  },
  "userAccount": {
  "username": "NULL",
  "createdDate": "'.$createdDate.'",
  "accountNumber": "'.$accountNumber.'",
  "aggregateOrderCount": 1,
  "aggregateOrderDollars": '.$amount.',
  "email": "'.$custemail.'",
  "phone": "'.$custphone.'"
  } ,
  "merchantCategoryCode": "5000"
 
  
  
  }';
 
 //echo '<h3>Datas Sent    </h3>';
 //echo $casesampledata;
// Array ( [signifydId] => 3365438099 [checkoutId] => 33569412 [orderId] => string [decision] => Array ( [createdAt] => 2022-12-20T00:39:37.384721Z [checkpointAction] => ACCEPT [checkpointActionReason] => SIGNIFYD_APPROVED [checkpointActionPolicy] => SIGNIFYD_DECISION [policies] => Array ( [overriding] => Array ( ) [default] => Array ( [name] => SIGNIFYD_DECISION [status] => EVALUATED_TRUE [action] => ACCEPT [reason] => SIGNIFYD_APPROVED ) ) [score] => 976.85304024732 ) [coverage] => Array ( [fraudChargebacks] => Array ( [amount] => 1569.64 [currency] => USD ) [inrChargebacks] => [allChargebacks] => ) )
 
 //echo $casesampledata;
             curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledata);
             $response = curl_exec($ch);
             $info = curl_getinfo($ch);
 $resparray_check=json_decode($response, true);
  // print_r($resparray_check);
 $_SESSION['checkoutId']=$resparray_check['checkoutId'];
  $_SESSION['signifydId']=$resparray_check['signifydId'];
  
  // NO NEED Feb 15 2023 if($resparray_check['decision']['checkpointActionReason']!='SIGNIFYD_APPROVED'){
   //tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=There was a problem processing your credit card details. Please make sure your credit card information is correct.', 'SSL'));
  //}
 //$checkoutId=223894584;
 //print_r($resparray_check);	     
   
   if($resparray_check['decision']['checkpointActionReason']!='SIGNIFYD_APPROVED'){
       tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=There was a problem processing your credit card details or email or address. Please make sure your information is correct.', 'SSL'));
      } // back added on March 8 2023
	  
     //echo "<br />Raw request: " . json_encode($info) ;
         // echo  "<br /> Raw response: " . $response ;
             $error = curl_error($ch);
            // echo "<br/>Curl error: " . $error ;
             $curlErrorNo = curl_error($ch);
             //echo "<br/> Curl errorNo: " . $curlErrorNo ;
             curl_close($ch);

//exit;
		
        $params = [
    'AMT' => $this->_app->formatCurrencyRaw($order->info['total']),
          'CREDITCARDTYPE' => $_POST['cc_type'],
          'ACCT' => $_POST['cc_number_nh-dns'],
          'EXPDATE' => $_POST['cc_expires_month'] . $_POST['cc_expires_year'],
          'CVV2' => $_POST['cc_cvc_nh-dns'],
          'FIRSTNAME' => substr($_POST['cc_owner'], 0, strpos($_POST['cc_owner'], ' ')),
          'LASTNAME' => substr($_POST['cc_owner'], strpos($_POST['cc_owner'], ' ')+1),
          'STREET' => $order->billing['street_address'],
          'STREET2' => $order->billing['suburb'],
          'CITY' => $order->billing['city'],
          'STATE' => tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], $order->billing['state']),
          'COUNTRYCODE' => $order->billing['country']['iso_code_2'],
          'ZIP' => $order->billing['postcode'],
          'EMAIL' => $order->customer['email_address'],
          'SHIPTOPHONENUM' => $order->customer['telephone'],
          'CURRENCYCODE' => $order->info['currency'],
        ];

        if ( $_POST['cc_type'] == 'MAESTRO' ) {
          $params['STARTDATE'] = $_POST['cc_starts_month'] . $_POST['cc_starts_year'];
          $params['ISSUENUMBER'] = $_POST['cc_issue_nh-dns'];
        }

        if ( is_numeric($_SESSION['sendto']) && ($_SESSION['sendto'] > 0) ) {
          $params['SHIPTONAME'] = trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']);
          $params['SHIPTOSTREET'] = $order->delivery['street_address'];
          $params['SHIPTOSTREET2'] = $order->delivery['suburb'];
          $params['SHIPTOCITY'] = $order->delivery['city'];
          $params['SHIPTOSTATE'] = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
          $params['SHIPTOCOUNTRYCODE'] = $order->delivery['country']['iso_code_2'];
          $params['SHIPTOZIP'] = $order->delivery['postcode'];
        }

        $item_params = [];

        $line_item_no = 0;

        foreach ( $order->products as $product ) {
          $item_params['L_NAME' . $line_item_no] = $product['name'];
          $item_params['L_AMT' . $line_item_no] = $this->_app->formatCurrencyRaw($product['final_price']);
          $item_params['L_NUMBER' . $line_item_no] = $product['id'];
          $item_params['L_QTY' . $line_item_no] = $product['qty'];

          $line_item_no++;
        }

        $items_total = $this->_app->formatCurrencyRaw($order->info['subtotal']);

        foreach ( $order->totals as $ot ) {
          if ( !in_array($ot['code'], ['ot_subtotal', 'ot_shipping', 'ot_tax', 'ot_total']) ) {
            $item_params['L_NAME' . $line_item_no] = $ot['title'];
            $item_params['L_AMT' . $line_item_no] = $this->_app->formatCurrencyRaw($ot['value']);

            $items_total += $this->_app->formatCurrencyRaw($ot['value']);

            $line_item_no++;
          }
        }

        $item_params['ITEMAMT'] = $items_total;
        $item_params['TAXAMT'] = $this->_app->formatCurrencyRaw($order->info['tax']);
        $item_params['SHIPPINGAMT'] = $this->_app->formatCurrencyRaw($order->info['shipping_cost']);

        if ( $this->_app->formatCurrencyRaw($item_params['ITEMAMT'] + $item_params['TAXAMT'] + $item_params['SHIPPINGAMT']) == $params['AMT'] ) {
          $params = array_merge($params, $item_params);
        }

        $response_array = $this->_app->getApiResult('DP', 'DoDirectPayment', $params);
		  
        if ( !in_array($response_array['ACK'], ['Success', 'SuccessWithWarning']) ) {
          tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=There was an error processing your credit card details. Please make sure everything was entered correctly.', 'SSL'));
        }
      } else {
        tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=' . $this->_app->getDef('module_dp_error_all_fields_required'), 'SSL'));
      }
    }

    function before_process_payflow() {
      global $order, $response_array;

      if ( !empty($_POST['cc_owner']) && !empty($_POST['cc_number_nh-dns']) && isset($_POST['cc_type']) && $this->isCardAccepted($_POST['cc_type']) ) {
      // 'AMT' => $this->_app->formatCurrencyRaw($order->info['total']),
        $params = [
        'AMT' => $this->_app->formatCurrencyRaw($order->info['total']),
          'CURRENCY' => $order->info['currency'],
          'BILLTOFIRSTNAME' => substr($_POST['cc_owner'], 0, strpos($_POST['cc_owner'], ' ')),
          'BILLTOLASTNAME' => substr($_POST['cc_owner'], strpos($_POST['cc_owner'], ' ')+1),
          'BILLTOSTREET' => $order->billing['street_address'],
          'BILLTOSTREET2' => $order->billing['suburb'],
          'BILLTOCITY' => $order->billing['city'],
          'BILLTOSTATE' => tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], $order->billing['state']),
          'BILLTOCOUNTRY' => $order->billing['country']['iso_code_2'],
          'BILLTOZIP' => $order->billing['postcode'],
          'EMAIL' => $order->customer['email_address'],
          'ACCT' => $_POST['cc_number_nh-dns'],
          'EXPDATE' => $_POST['cc_expires_month'] . $_POST['cc_expires_year'],
          'CVV2' => $_POST['cc_cvc_nh-dns'],
        ];

        if ( is_numeric($_SESSION['sendto']) && ($_SESSION['sendto'] > 0) ) {
          $params['SHIPTOFIRSTNAME'] = $order->delivery['firstname'];
          $params['SHIPTOLASTNAME'] = $order->delivery['lastname'];
          $params['SHIPTOSTREET'] = $order->delivery['street_address'];
          $params['SHIPTOSTREET2'] = $order->delivery['suburb'];
          $params['SHIPTOCITY'] = $order->delivery['city'];
          $params['SHIPTOSTATE'] = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
          $params['SHIPTOCOUNTRY'] = $order->delivery['country']['iso_code_2'];
          $params['SHIPTOZIP'] = $order->delivery['postcode'];
        }

        $item_params = [];

        $line_item_no = 0;

        foreach ($order->products as $product) {
          $item_params['L_NAME' . $line_item_no] = $product['name'];
          $item_params['L_COST' . $line_item_no] = $this->_app->formatCurrencyRaw($product['final_price']);
          $item_params['L_QTY' . $line_item_no] = $product['qty'];

          $line_item_no++;
        }

        $items_total = $this->_app->formatCurrencyRaw($order->info['subtotal']);

        foreach ($order->totals as $ot) {
          if ( !in_array($ot['code'], ['ot_subtotal', 'ot_shipping', 'ot_tax', 'ot_total']) ) {
            $item_params['L_NAME' . $line_item_no] = $ot['title'];
            $item_params['L_COST' . $line_item_no] = $this->_app->formatCurrencyRaw($ot['value']);
            $item_params['L_QTY' . $line_item_no] = 1;

            $items_total += $this->_app->formatCurrencyRaw($ot['value']);

            $line_item_no++;
          }
        }

        $item_params['ITEMAMT'] = $items_total;
        $item_params['TAXAMT'] = $this->_app->formatCurrencyRaw($order->info['tax']);
        $item_params['FREIGHTAMT'] = $this->_app->formatCurrencyRaw($order->info['shipping_cost']);

        if ( $this->_app->formatCurrencyRaw($item_params['ITEMAMT'] + $item_params['TAXAMT'] + $item_params['FREIGHTAMT']) == $params['AMT'] ) {
          $params = array_merge($params, $item_params);
        }

        $params['_headers'] = [
          'X-VPS-REQUEST-ID: ' . md5($_SESSION['cartID'] . session_id() . $this->_app->formatCurrencyRaw($order->info['total'])),
          'X-VPS-CLIENT-TIMEOUT: 45',
          'X-VPS-VIT-INTEGRATION-PRODUCT: OSCOM',
          'X-VPS-VIT-INTEGRATION-VERSION: 2.3',
        ];

        $response_array = $this->_app->getApiResult('DP', 'PayflowPayment', $params);

        if ( $response_array['RESULT'] != '0' ) {
          switch ( $response_array['RESULT'] ) {
            case '1':
            case '26':
              $error_message = $this->_app->getDef('module_dp_error_configuration');
              break;

            case '7':
              $error_message = $this->_app->getDef('module_dp_error_address');
              break;

            case '12':
              $error_message = $this->_app->getDef('module_dp_error_declined');
              break;

            case '23':
            case '24':
              $error_message = $this->_app->getDef('module_dp_error_invalid_card');
              break;

            default:
              $error_message = $this->_app->getDef('module_dp_error_general');
              break;
          }

          tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=' . $error_message, 'SSL'));
        }
      } else {
        tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=' . $this->_app->getDef('module_dp_error_all_fields_required'), 'SSL'));
      }
    }

    function after_process() {
      if ( OSCOM_APP_PAYPAL_GATEWAY == '1' ) {
        $this->after_process_paypal();
      } else {
        $this->after_process_payflow();
      }
    }

    function after_process_paypal() {
      global $response_array, $order_id,$_SESSION;

      $details = $this->_app->getApiResult('APP', 'GetTransactionDetails', ['TRANSACTIONID' => $response_array['TRANSACTIONID']], (OSCOM_APP_PAYPAL_DP_STATUS == '1') ? 'live' : 'sandbox');
	if($_SESSION['checkoutId'])
      $chkid=$_SESSION['checkoutId'];
      if($_SESSION['signifydId'])
      $sgid=$_SESSION['signifydId'];
      
      $result = 'Transaction ID: ' . tep_output_string_protected($response_array['TRANSACTIONID']) . "\n";

      if ( in_array($details['ACK'], ['Success', 'SuccessWithWarning']) ) {
        $result .= 'Payer Status: ' . tep_output_string_protected($details['PAYERSTATUS']) . "\n"
                 . 'Address Status: ' . tep_output_string_protected($details['ADDRESSSTATUS']) . "\n"
                 . 'Payment Status: ' . tep_output_string_protected($details['PAYMENTSTATUS']) . "\n"
                 . 'Payment Type: ' . tep_output_string_protected($details['PAYMENTTYPE']) . "\n"
                 . 'Pending Reason: ' . tep_output_string_protected($details['PENDINGREASON']) . "\n";
    		 
      }

      $result .='TempOrderID: ' . tep_output_string_protected($_SESSION['tempordid']) . "\n" .'CheckoutID: ' . tep_output_string_protected($chkid) . "\n" . 'SignifydID: ' . tep_output_string_protected($sgid) . "\n" .' AVS Code: ' . tep_output_string_protected($response_array['AVSCODE']) . "\n". 'CVV2 Match: ' . tep_output_string_protected($response_array['CVV2MATCH']);
//	$detsstr=implode(",!!!!!",$details);
//	$detsstr1=implode(",!!!!!",$response_array);
//	$result .=$detsstr.$detsstr1;
	 
	
      $sql_data = [
        'orders_id' => $order_id,
        'orders_status_id' => OSCOM_APP_PAYPAL_TRANSACTIONS_ORDER_STATUS_ID,
        'date_added' => 'NOW()',
        'customer_notified' => '0',
        'comments' => $result,
      ];

      tep_db_perform('orders_status_history', $sql_data);
      

      
    }

    function after_process_payflow() {
      global $order_id, $response_array,$_SESSION;

      $details = $this->_app->getApiResult('APP', 'PayflowInquiry', ['ORIGID' => $response_array['PNREF']], (OSCOM_APP_PAYPAL_DP_STATUS == '1') ? 'live' : 'sandbox');

	if($_SESSION['checkoutId'])
      $chkid=$_SESSION['checkoutId'];
      if($_SESSION['signifydId'])
      $sgid=$_SESSION['signifydId'];
      
           
      $result = 'Transaction ID: ' . tep_output_string_protected($response_array['PNREF']) . "\n"
              . 'Gateway: Payflow' . "\n"
              . 'PayPal ID: ' . tep_output_string_protected($response_array['PPREF']) . "\n"
              . 'Response: ' . tep_output_string_protected($response_array['RESPMSG']) . "\n" .'TempOrderID: ' . tep_output_string_protected($_SESSION['tempordid']) . "\n" .'CheckoutID: ' . tep_output_string_protected($chkid) . "\n" . 'SignifydID: ' . tep_output_string_protected($sgid) . "\n";	      

      if ( isset($details['RESULT']) && ($details['RESULT'] == '0') ) {
        $pending_reason = $details['TRANSSTATE'];
        $payment_status = null;

        switch ( $details['TRANSSTATE'] ) {
          case '3':
            $pending_reason = 'authorization';
            $payment_status = 'Pending';
            break;

          case '4':
            $pending_reason = 'other';
            $payment_status = 'In-Progress';
            break;

          case '6':
            $pending_reason = 'scheduled';
            $payment_status = 'Pending';
            break;

          case '8':
          case '9':
            $pending_reason = 'None';
            $payment_status = 'Completed';
            break;
        }

        if ( isset($payment_status) ) {
          $result .= 'Payment Status: ' . tep_output_string_protected($payment_status) . "\n";
        }

        $result .= 'Pending Reason: ' . tep_output_string_protected($pending_reason) . "\n";
      }

      switch ( $response_array['AVSADDR'] ) {
        case 'Y':
          $result .= 'AVS Address: Match' . "\n";
          break;

        case 'N':
          $result .= 'AVS Address: No Match' . "\n";
          break;
      }

      switch ( $response_array['AVSZIP'] ) {
        case 'Y':
          $result .= 'AVS ZIP: Match' . "\n";
          break;

        case 'N':
          $result .= 'AVS ZIP: No Match' . "\n";
          break;
      }

      switch ( $response_array['IAVS'] ) {
        case 'Y':
          $result .= 'IAVS: International' . "\n";
          break;

        case 'N':
          $result .= 'IAVS: USA' . "\n";
          break;
      }

      switch ( $response_array['CVV2MATCH'] ) {
        case 'Y':
          $result .= 'CVV2: Match' . "\n";
          break;

        case 'N':
          $result .= 'CVV2: No Match' . "\n";
          break;
      }

     //	$detsstr=implode(",!!!!!",$details);
      //	$detsstr1=implode(",!!!!!",$response_array);
      //	$result .=$detsstr.$detsstr1;
	      
	       
      $sql_data = [
        'orders_id' => $order_id,
        'orders_status_id' => OSCOM_APP_PAYPAL_TRANSACTIONS_ORDER_STATUS_ID,
        'date_added' => 'NOW()',
        'customer_notified' => '0',
        'comments' => $result,
      ];

      tep_db_perform('orders_status_history', $sql_data);
    }

    function get_error() {
      return false;
    }

    function check() {
      $check_query = tep_db_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'OSCOM_APP_PAYPAL_DP_STATUS'");
      if ( tep_db_num_rows($check_query) ) {
        $check = tep_db_fetch_array($check_query);

        return tep_not_null($check['configuration_value']);
      }

      return false;
    }

    function install() {
      tep_redirect(tep_href_link('paypal.php', 'action=configure&subaction=install&module=DP'));
    }

    function remove() {
      tep_redirect(tep_href_link('paypal.php', 'action=configure&subaction=uninstall&module=DP'));
    }

    function keys() {
      return ['OSCOM_APP_PAYPAL_DP_SORT_ORDER'];
    }

    function isCardAccepted($card) {
      static $cards;

      if ( !isset($cards) ) {
        $cards = explode(';', OSCOM_APP_PAYPAL_DP_CARDS);
      }

      return isset($this->cc_types[$card]) && in_array(strtolower($card), $cards);
    }

    function templateClassExists() {
      return class_exists('oscTemplate') && isset($GLOBALS['oscTemplate']) && is_object($GLOBALS['oscTemplate']) && (get_class($GLOBALS['oscTemplate']) == 'oscTemplate');
    }

    function getSubmitCardDetailsJavascript() {
      $js = <<<EOD
<script>
if ( typeof jQuery == 'undefined' ) {
  document.write('<scr' + 'ipt src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></scr' + 'ipt>');
}
</script>

<script>
$(function() {
  if ( typeof($('#paypal_table_new_card').parent().closest('table').attr('width')) == 'undefined' ) {
    $('#paypal_table_new_card').parent().closest('table').attr('width', '100%');
  }

  paypalShowNewCardFields();

  $('#paypal_card_type').change(function() {
    var selected = $(this).val();

    if ( $('#paypal_card_date_start').length > 0 ) {
      if ( selected == 'MAESTRO' ) {
        $('#paypal_card_date_start').parent().parent().show();
      } else {
        $('#paypal_card_date_start').parent().parent().hide();
      }
    }

    if ( $('#paypal_card_issue').length > 0 ) {
      if ( selected == 'MAESTRO' ) {
        $('#paypal_card_issue').parent().parent().show();
      } else {
        $('#paypal_card_issue').parent().parent().hide();
      }
    }
  });

  $('#cardSecurityCodeInfo').tooltip();
});

function paypalShowNewCardFields() {
  var selected = $('#paypal_card_type').val();

  if ( $('#paypal_card_date_start').length > 0 ) {
    if ( selected != 'MAESTRO' ) {
      $('#paypal_card_date_start').parent().parent().hide();
    }
  }

  if ( $('#paypal_card_issue').length > 0 ) {
    if ( selected != 'MAESTRO' ) {
      $('#paypal_card_issue').parent().parent().hide();
    }
  }
}
</script>
EOD;

      return $js;
    }
  }
