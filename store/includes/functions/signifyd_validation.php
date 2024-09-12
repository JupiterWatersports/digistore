<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

    $app = new OSCOM_PayPal();
    $app->loadLanguageFile('modules/EC/EC.php');
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
    
    $amount=$app->formatCurrencyRaw($order->info['total']);
    $shipamount=$app->formatCurrencyRaw($order->info['shipping_cost']);
    $custname=$order->customer['firstname'] . ' ' . $order->customer['lastname'];
    $del_custname=trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']);
    $custphone=str_replace("-","",$order->customer['telephone']);
    $custemail=$order->customer['email_address'];
    
    $currency=$order->info['currency'];
    $gateway='PAYPAL_EXPRESS';
    $accountNumber=$customer_id ;
    $scarrier=$order->info['shipping_method'];
    $t=time();
    $createdDate=date('c',$t) ;
    $apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';
    $headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];
    if($_SESSION['tempsessid']){
        $sessid=$_SESSION['tempsessid'] ;
    }else{
        $sessid=$randomNum1."-".$randomNum2."-".$randomNum3."-".$randomNum4."-".$randomNum5;
        $_SESSION['tempsessid']=$sessid;
    }
    $ordinfo=tep_db_fetch_array(tep_db_query("SELECT MAX(orders_id) as ordid  FROM `orders`"));
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
    $ot=$ordinfo['ordid']+1;
    $_SESSION['tempordid']=$ot;
    $casesampledata='
    {
        "checkoutId": "'.rand().'",
        "device": {
        "clientIpAddress": "'.$_SERVER['REMOTE_ADDR'].'",
        "sessionId": "'.$sessid.'" 
    },
    "transactions": 
    [
        {
            "paymentMethod": "PAYPAL",
            "checkoutPaymentDetails": {
                "billingAddress": {
                    "streetAddress": "'.$bill_street_address.'",
                    "postalCode": "'.$bill_postalCode.'",
                    "city": "'.$bill_city.'",
                    "provinceCode": "'.$bill_province.'",
                    "countryCode": "'.$bill_country.'"
                },
                "accountHolderName": "'.$custname.'" 
            },
            "amount": '.$amount.',
            "currency": "'.$currency.'",
            "gateway": "PAYPAL_EXPRESS"
        }
    ],
    "orderId": "'.$ot.'",
    "purchase": {
        "createdAt": "'.$createdDate.'",
        "orderChannel": "WEB",
        "totalPrice": '.$amount.',
        "currency": "'.$currency.'",
        "confirmationEmail": "'.$custemail.'",
        "products": ['.$products_str.'],
        "shipments": 
        [
            {
                "destination": 
                {
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
                    "address": 
                    {
                        "streetAddress": "1500 N US HWY 1",
                        "postalCode": "33469",
                        "city": "Jupiter",
                        "provinceCode": "FL",
                        "countryCode": "US"
                    }
                },
                "carrier": "USPS",
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
    },
    "merchantCategoryCode": "5000"
    }';
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledata);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $resparray_check=json_decode($response, true);
    $_SESSION['checkoutId']=$resparray_check['checkoutId'];
    $_SESSION['signifydId']=$resparray_check['signifydId'];
    if($resparray_check['decision']['checkpointActionReason']!='SIGNIFYD_APPROVED'){
        tep_redirect(tep_href_link('checkout_confirmation.php', 'error_message=There was a problem processing your payment. Please make sure all details are correct', 'SSL'));
    }
?>