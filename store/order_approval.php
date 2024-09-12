<?php
/*
  $Id: checkout_shipping.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License
*/
require('includes/application_top.php');
require('includes/classes/http_client.php');

// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

require(DIR_WS_CLASSES . 'order.php');
$order = new order;
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->process();

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
$cartID = $cart->cartID;
// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed

//Check for valid approval
$product_order = "";
$address = $order->customer["street_address"]. " " . $order->customer["suburb"]. " ".$order->customer["city"] . " ". $order->customer["postcode"]. " " . $order->customer["state"] . " " . $order->customer["country"]["title"];
foreach($order->products as $productName){
    $product_order .= $productName['name'] . ",";
}
$query = tep_db_query("select * from board_checkout where customer_email = '".$order->customer['email_address']."' and expiration_date >= now() and status = 1");
$approval_count = tep_db_num_rows($query);
if ($approval_count > 0) {
    while ($result = tep_db_fetch_array($query)) {
        $shippingFee = $result['shipping_fee'];
    }
    $shipping = array('id' => 0,
    'title' => 'Approved Shipping',
    'cost' => $shippingFee);
    tep_session_register('shipping');
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
} else {
    tep_db_query("insert into board_checkout (customer_email, expiration_date, shipping_fee, status, order_details, phone, address) values ('" . str_replace("'", "\'", $order->customer['email_address']). "', now(), '0', '0','".str_replace("'", "\'", $product_order)."','".$order->customer['telephone']."','".str_replace("'", "\'", $address)."')");
}
?>
<?php echo $stylesheet; ?>
<?php require(DIR_WS_INCLUDES . 'template-top-cart.php'); ?> 
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 pt-4 mt-4">
                <div class="error-template pt-4">
                    <h2>Oversized Item</h2>

                    <div>
                        <p> At least one of your checkout items are considered “oversized”. </p>
                        <p> In order to quote you the most accurate shipping rates possible, we’ll have to run the rates separately.</p>
                        <p> Please call or text us at +1 (561) 373-4445 to request an immediate quote.</p>
                        <p> Regardless, we’ll be contacting you with the shipping rates ASAP which will be added to your order so you can complete the checkout process.</p>
                        <p> Thank you.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <svg class="svg-box" width="380px" height="500px" viewbox="0 0 837 1045" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                            <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        </g>
                    </svg>
            </div>
        </div>
    </div>
</body>
<?php
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>