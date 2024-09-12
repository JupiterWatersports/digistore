<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2017 osCommerce

  Released under the GNU General Public License
*/
chdir('../../../../');
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

// initialize variables if the customer is not logged in
if ( !tep_session_is_registered('customer_id') ) {
$customer_id = 0;
$customer_default_address_id = 0;
}

require_once 'includes/modules/payment/lib/Braintree.php';

$config = new Braintree\Configuration([
    'environment' => 'production',
    'merchantId' => 'mdgfgmv4dpy62jjx',
    'publicKey' => '9c428q9h5zwdcpgr',
    'privateKey' => 'a296f6e0b4b9d8aa5da877cbe5f1b65c'
]);

$gateway = new Braintree\Gateway($config);
echo $cart->total;die;
// Then, create a transaction:

/* $result = $gateway->transaction()->sale([
    'amount' => '10.00',
    'paymentMethodNonce' => $nonceFromTheClient,
    'deviceData' => $deviceDataFromTheClient,
    'options' => [ 'submitForSettlement' => True ]
]);

if ($result->success) {
    print_r("success!: " . $result->transaction->id);
} else if ($result->transaction) {
    print_r("Error processing transaction:");
    print_r("\n  code: " . $result->transaction->processorResponseCode);
    print_r("\n  text: " . $result->transaction->processorResponseText);
} else {
    foreach($result->errors->deepAll() AS $error) {
      print_r($error->code . ": " . $error->message . "\n");
    }
} */

require(DIR_WS_INCLUDES . 'application_bottom.php');