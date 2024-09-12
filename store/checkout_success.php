<?php
/*
  $Id: checkout_success.php 1749 2007-12-21 04:23:36Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
    $notify_string = '';

    if (isset($HTTP_POST_VARS['notify']) && !empty($HTTP_POST_VARS['notify'])) {
      $notify = $HTTP_POST_VARS['notify'];

      if (!is_array($notify)) {
        $notify = array($notify);
      }

      for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
        if (is_numeric($notify[$i])) {
          $notify_string .= 'notify[]=' . $notify[$i] . '&';
        }
      }

      if (!empty($notify_string)) {
        $notify_string = 'action=notify&' . substr($notify_string, 0, -1);
      }
    }

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>




<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?>

<?php


$orderid= $_SESSION['tempordid'];
	
 // "signifydId":3308333153,"checkoutId":"592690955"
 
	$orderinfo=tep_db_fetch_array(tep_db_query("SELECT *,DATE_FORMAT(date_purchased, '%Y-%m-%dT%TZ') as datep FROM `orders` where orders_id='".$orderid."'"));
	$ordertotalinfo=tep_db_fetch_array(tep_db_query("SELECT * FROM `orders_total` where class='ot_total' and orders_id='".$orderid."'"));
	$ordertotalSinfo=tep_db_fetch_array(tep_db_query("SELECT * FROM `orders_total` where class='ot_shipping' and orders_id='".$orderid."'"));
	$res= tep_db_query("SELECT * FROM `orders_products` where orders_id='".$orderid."'");
	
	 
	  while($row = tep_db_fetch_array($res, MYSQLI_ASSOC)){
	$products_array[]=' { "itemName": "'.$row['products_name'].'",
	 "itemPrice": '.$row['final_price'].',
	 "itemQuantity": '.$row['products_quantity'].',
	
	 "itemId": "'.$row['products_model'].'"  }';
	}
	$products_str=implode(",",$products_array);
// if paypal get transaction ID
		$orderstatusinfo=tep_db_fetch_array(tep_db_query("SELECT * FROM `orders_status_history`  where orders_status_id=131 and orders_id='".$orderid."'"));
		$transinfo=preg_split("/\R/",$orderstatusinfo['comments']);
		if($transinfo[0]){
		$trsidarray=explode(":",$transinfo[0]);
		$transaction_ID=trim($trsidarray[1]);
		
		}
		if($transinfo[6]){
		$trsidarray1=explode(":",$transinfo[6]);
		$avs_ID=trim($trsidarray1[1]);
		
		}
		if($transinfo[7]){
		$trsidarray2=explode(":",$transinfo[7]);
		$cvv_ID=trim($trsidarray2[1]);
		
		}
	//	echo $transaction_ID;
		//print_r($transinfo);
//	exit;	
//print_r($orderinfo);
$checkoutId=$orderinfo['signifyd_checkoutID'];
$sessid=$orderinfo['signifyd_sessionid'];
$street_address=$orderinfo['customers_street_address'];
$postalCode=$orderinfo['customers_postcode'];
$city=$orderinfo['customers_city'];
$del_street_address=$orderinfo['delivery_street_address'];
$del_postalCode=$orderinfo['delivery_postcode'];
$del_city=$orderinfo['delivery_city'];
$del_company=$orderinfo['delivery_company'];
$bill_street_address=$orderinfo['billing_street_address'];
$bill_postalCode=$orderinfo['billing_postcode'];
$bill_city=$orderinfo['billing_city'];
$amount=$ordertotalinfo['value'];
$shipamount=$ordertotalSinfo['value'];
$custname=$orderinfo['customers_name'];
$del_custname=$orderinfo['delivery_name'];
$custphone='+1'.str_replace("-","",$orderinfo['customers_telephone']);
$custemail=$orderinfo['customers_email_address'];
$cardBrand=$orderinfo['cc_type'];
$currency=$orderinfo['currency'];
$gateway=$orderinfo['payment_method'];
$accountNumber=$orderinfo['customers_id'] ;

$cardbin=$_SESSION['ccbin'];
$cardexpmonth= $_SESSION['ccmonth'];
$cardexpyear= $_SESSION['ccyear'];
$cardlast=$_SESSION['cclast'];
$gateway='PAYPAL_PRO';
if(!$cardbin) $cardbin='441005';
if(!$cardexpmonth) $cardexpmonth='4';
if(!$cardexpyear) $cardexpyear='2024';
if(!$cardlast) $cardlast='0367';

$createdDate=date('c',strtotime($orderinfo['date_purchased'])) ;
$createdDate= $orderinfo['datep']  ;

//$sessid='ORDER_SESS_'.rand();

 
 
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';

$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];
 
 
	      
//echo '<h3>Transaction API CALL    </h3>';

// Transaction API CALL    
$url='https://api.signifyd.com/v3/orders/events/transactions';

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

 $casesampledatat='{
   "checkoutId": "'.$checkoutId.'",
   "orderId": "'.$orderid.'",
   "transactions": [
     {
       "transactionId": "'.$transaction_ID.'",
       "gatewayStatusCode": "SUCCESS",
       "paymentMethod": "CREDIT_CARD",
       "checkoutPaymentDetails": {
 "billingAddress": {
 "streetAddress": "'.$bill_street_address.'",
 "postalCode": "'.$bill_postalCode.'",
 "city": "'.$bill_city.'",
 "provinceCode": "FL",
 "countryCode": "US"
         },
	 
         "accountHolderName": "'.$custname.'",


 "cardBin": "'.$cardbin.'",
 "cardExpiryMonth": '.$cardexpmonth.',
 "cardExpiryYear": '.$cardexpyear.',
 "cardLast4": "'.$cardlast.'", 
 "cardBrand": "'.$cardBrand.'"	 

       },
 "amount": '.$amount.',
 "currency": "'.$currency.'",
 "gateway": "'.$gateway.'",

       "verifications": {
         "cvvResponseCode": "'.$cvv_ID.'",
         "avsResponseCode": "'.$avs_ID.'"
       } 
 
     }
   ]
 }';
 
 //echo $casesampledatat;
 // echo '<h3>Datas Sent    </h3>';
  //echo $casesampledatat;
 
 //echo '<h3>Datas Received Response from Signifyd    </h3>'; 
 
// $resparray_check=json_decode($response, true);
 
 //$checkoutId=$resparray_check['checkoutId'];
 //$signifydId=$resparray_check['signifydId'];
 
             curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledatat);
             $response = curl_exec($ch);
             $info = curl_getinfo($ch);
        // echo "<br />Raw request: " . json_encode($info) ;
          //echo  "<br /> Raw response: " . $response ;
             $error = curl_error($ch);
             //echo "<br/>Curl error: " . $error ;
             $curlErrorNo = curl_error($ch);
            // echo "<br/> Curl errorNo: " . $curlErrorNo ;
             curl_close($ch);
	     
	     
/*

==========================================================

	Include Google Analystics module for osCommerce

  	Added by  Clement Nicolaescu (http://www.osCoders.biz) 

	v. 1.0.0 - 2005/11/15

  

  Google Analytics Beta modification v 2.1.0 2008/01/15 by:

  Tomas Hesseling (www.boxershortz.nl) &

  Mathieu Burgerhout (www.seo-for-osc.com)

  

  Google Analytics universal modification v 3.0.0 2014/05/12 by:

  @raiwa (www.sarplataygemas.com)

  

==========================================================	

--------------------------------------------------

  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

--------------------------------------------------



  Released under the GNU General Public License

*/



// ############## Google Analytics - start ###############



// Get order id

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");

    $orders = tep_db_fetch_array($orders_query);

    $order_id = $orders['orders_id'];



// Set value for  "affiliation"

    $analytics_affiliation = str_replace('http://', '', str_replace('www.', '', HTTP_SERVER));



    $totals_query = tep_db_query("select value, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");

// Set values for "total", "tax" and "shipping"

    $analytics_total = '';

    $analytics_tax = '';

    $analytics_shipping = '';

    

     while ($totals = tep_db_fetch_array($totals_query)) {



        if ($totals['class'] == 'ot_total') {

$analytics_total = number_format($totals['value'], 2, '.', '');

$total_flag = 'true';

} else if ($totals['class'] == 'ot_tax') {

$analytics_tax = number_format($totals['value'], 2, '.', '');

$tax_flag = 'true';

} else if ($totals['class'] == 'ot_shipping') {

$analytics_shipping = number_format($totals['value'], 2, '.', '');

$shipping_flag = 'true';

}



     }


// Get products info for Analytics "Item lines"



    //$item_string = '';

    $items_query = tep_db_query("select products_id, products_model, products_name, final_price, products_tax, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id . "' order by products_name");

    $gatag_string = 'gtag(\'event\', \'purchase\', {
        "transaction_id": "'.$order_id.'",
        "affiliation": "Google online store",
        "value": "'.$analytics_total.'",
        "currency": "USD",
        "tax": "'.$analytics_tax.'",
        "shipping": "'.$analytics_shipping.'",
        "items": [';
    
    $gatag_string_items = "";
    while ($items = tep_db_fetch_array($items_query)) {

		$category_query = tep_db_query("select p2c.categories_id, cd.categories_name from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p2c.products_id = '" . $items['products_id'] . "' AND cd.categories_id = p2c.categories_id AND cd.language_id = '" . (int)$languages_id . "'");
		$category = tep_db_fetch_array($category_query);

		/*$item_string .= "ga('ecommerce:addItem', { ";
		$item_string .=  "'id': '" . $order_id . "', 'name': '" . $items['products_name'] . "', 'sku': '" . $items['products_id'] . "', 'category': '" . $category['categories_name'] . "', 'price': '" . number_format(tep_add_tax($items['final_price'], $items['products_tax']), 2) . "', 'quantity': '" . $items['products_quantity'] . "'";
		$item_string .= "}); \n";*/
                
                $gatag_string_items .= '{
                    "id": "'.$items['products_id'].'",
                    "name": "'.$items['products_name'].'",
                    "list_name": "Order Item",
                    "brand": "",
                    "category": "'.$category['categories_name'].'",
                    "variant": "",
                    "list_position": 1,
                    "quantity": '.$items['products_quantity'].',
                    "price": "'.number_format(tep_add_tax($items['final_price'], $items['products_tax']), 2).'"
                },';

    }
    
    $gatag_string_items = rtrim($gatag_string_items, ",");
    $gatag_string = $gatag_string . $gatag_string_items . "]  });";


// ############## Google Analytics - end ###############



?>

<script>

//ga('require', 'ecommerce', 'ecommerce.js');

<?php echo $transaction_string ?>

<?php //echo $item_string; ?>
//ga('ecommerce:send');

<?php echo $gatag_string; ?>
</script>



<!-- Google Code for Shopping Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 964026900;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "_T7bCInyt1YQlMTXywM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript"  
src="//www.googleadservices.com/pagead/conversion.js">
</script>

<!-- Facebook Conversion Code for Conformation - Checkout Page -->
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6037755001353', {'value':'0.00','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6037755001353&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
<noscript>
<div style="display:none;">
<img height="1" width="1" style="border-style:none;" alt=""  
src="//www.googleadservices.com/pagead/conversion/964026900/?label=_T7bCInyt1YQlMTXywM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>

<div id="progressbar">
	<span class="progressbar">Delivery</span>
	<span class="progressbar">Payment</span>
	<span class="progressbar">Confirmation</span>
	<span class="progressbar-active">Finished</span>	
</div>

<div class="clear"></div>
<div class="alpha">
 		<p><?php echo TEXT_SUCCESS; ?></p>

  		<p><?php echo TEXT_NOTIFY_PRODUCTS; ?> </p>
  			<div class="grid_4"><div class="productsNotifications">
  		<?php

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
        echo tep_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br />';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }
	?>
    	</div></div>
        <div class="clear"></div>
    <?php echo '<p>'.TEXT_SEE_ORDERS . '</p><p>' . TEXT_CONTACT_STORE_OWNER.'</p>'  		
	?>
</div>

<div class="clear"></div>
<h3><?php echo TEXT_THANKS_FOR_SHOPPING; ?></h3>

<div class="grid_8 alpha">
		<p>
		<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
		</p>
</div> 

<div class="clear"></div>

<div class="alpha" style="height:80px;">
    
    	<button class="button-blue-small">Continue</button>
</div>
 <div class="clear"></div>

    </form>
    
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
