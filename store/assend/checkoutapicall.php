<?php

 // orderChannel  required  string (OrderChannel) Enum: "WEB" "PHONE" "MOBILE_APP" "SOCIAL" "MARKETPLACE" "IN_STORE_KIOSK" "SCAN_AND_GO" "SMART_TV" "MIT"
	$host='localhost';
	$db_username='live';
	$db_password='9Y2tVNYxP22LNp5G';
	$dbname='live';
	global $dbc;
	$dbc=mysqli_connect($host,$db_username,$db_password,$dbname) or die('Unable to connect to the database...');
	
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
 
	 mysqli_select_db($dbc,$dbname) or die('Unable to select the database...');
	$orderid= $_REQUEST['oID'];
	
 // "signifydId":3308333153,"checkoutId":"592690955"
 
	$orderinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT *,DATE_FORMAT(date_purchased, '%Y-%m-%dT%TZ') as datep FROM `orders` where orders_id='".$orderid."'"));
	$ordertotalinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `orders_total` where class='ot_total' and orders_id='".$orderid."'"));
	$ordertotalSinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `orders_total` where class='ot_shipping' and orders_id='".$orderid."'"));
	$res=mysqli_query($dbc,"SELECT * FROM `orders_products` where orders_id='".$orderid."'");
	
	 
	  while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
	  $row['products_name']=str_replace("'","",$row['products_name']);
	  $row['products_name']=str_replace('"','',$row['products_name']);	  
	$products_array[]=' { "itemName": "'. $row['products_name'] .'",
	 "itemPrice": '.$row['final_price'].',
	 "itemQuantity": '.$row['products_quantity'].',
	
			 	 		 	 "itemId": "'.$row['products_model'].'"  }';
	}
	$products_str=implode(",",$products_array);
// if paypal get transaction ID
		$orderstatusinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `orders_status_history`  where orders_status_id=131 and orders_id='".$orderid."'"));
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

$ccnumarr=explode("-",$orderinfo['cc_number']);

//$cardbin=substr($orderinfo['cc_number'],0,6);
$cardbin=$ccnumarr[0];
$cardexpmonth= round($orderinfo['cc_exp_month']) ;
$cardexpyear= $orderinfo['cc_exp_year']  ;
//$cardlast=substr($orderinfo['cc_number'],-4);
$cardlast=$ccnumarr[1];
$gateway='PAYPAL_PRO';
if(!$cardbin) $cardbin='467895';
if(!$cardexpmonth) $cardexpmonth='04';
if(!$cardexpyear) $cardexpyear='2024';
if(!$cardlast) $cardlast='0367';

$createdDate=date('c',strtotime($orderinfo['date_purchased'])) ;
$createdDate= $orderinfo['datep']  ;
if($orderinfo['signifyd_sessionid']){
$sessid=$orderinfo['signifyd_sessionid'];
}else{
	$randomNum1=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
	$randomNum2=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum3=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum4=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum5=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12);
	
	$sessid=$randomNum1."-".$randomNum2."-".$randomNum3."-".$randomNum4."-".$randomNum5;
}	
	
//$sessid='ORDER_sess-'.rand();
if(!$shipamount) $shipamount=0;


?>

<head>
<script defer type="text/javascript" id="sig-api" data-order-session-id="<?php echo $sessid;?>" src="https://cdn-scripts.signifyd.com/api/script-tag.js"></script>

<script>
alert('Signifyd GLOAB CALL is '+ SIGNIFYD_GLOBAL.scriptTagHasLoaded());
</script>
</head>
<?php
if($_REQUEST['action']!='proceed'){ 
?>

<form action="" method="post">
<input type="hidden" name="action" value="proceed" />
<input type="hidden" name="oID" value="<?php echo $orderid; ?>" />

<h3>Select API Calls DATA for CHECKOUT</h3>
<table width="500" cellpadding=10 cellspacing=5>
<tr><td>
Payment Method
</td><td>
<select name="pmethod">
<option value="CREDIT_CARD">CREDIT CARD</option>
<option value="PAYPAL">PAYPAL</option>
</select>
</td></tr>

<tr><td>
Order Channel
</td><td>
<select name="ochannel">
<option value="WEB">WEB</option>
<option value="PHONE">PHONE</option>
</select>
</td></tr>

<tr><td>
Shipment Carrier
</td><td>
<select name="scarrier">
<option value="FEDEX">FEDEX</option>
<option value="USPS">USPS</option>
</select>
</td></tr>

<tr><td>
Payment Gateway
</td><td>
<select name="pgateway">
<option value="PAYPAL">PAYPAL</option>
<option value="PAYPAL_PRO">PAYPAL PRO</option>
<option value="PAYPAL_EXPRESS">PAYPAL EXPRESS</option>

</select>
</td></tr>


<tr><td colspan="2" align="center">
 <input type="Submit" value="Proceed With Selection" />
  
</td></tr>

</form>
<?php
}


if($_REQUEST['action']=='proceed'){ 

$pmethod=$_REQUEST['pmethod'];
$ochannel=$_REQUEST['ochannel'];
$scarrier=$_REQUEST['scarrier'];
$pgateway=$_REQUEST['pgateway'];


$apiKey = 'bHlPsiDW2SsyFRc7vwwnYUHdfa6wOKtw';
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';
$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];
echo '<h3>Checkout API CALL    </h3>';
// Checkout API CALL    
$url='https://api.signifyd.com/v3/orders/events/checkouts';

$recby='"receivedBy": "string"';
if($ochannel=='PHONE') $recby='"receivedBy": "ADMIN"';
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

 $casesampledata='{
 "checkoutId": "'.rand().'",
 "device": {
 "clientIpAddress": "'.$_SERVER['REMOTE_ADDR'].'",
 "sessionId": "'.$sessid.'" 
 },
  "transactions": [
 {
   "paymentMethod": "'.$pmethod.'",
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
  "cardExpiryMonth": "'.$cardexpmonth.'",
  "cardExpiryYear": '.$cardexpyear.',
  "cardLast4": "'.$cardlast.'",
  "cardBrand": "'.$cardBrand.'"
 },
 "amount": '.$amount.',
 "currency": "'.$currency.'",
 "gateway": "'.$pgateway.'"
 }
 ],
 "orderId": "'.$orderid.'",
 "purchase": {
 "createdAt": "'.$createdDate.'",
 "orderChannel": "'.$ochannel.'",
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
 "provinceCode": "FL",
 "countryCode": "US"
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
 },
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
 
 echo '<h3>Datas Sent    </h3>';
 echo $casesampledata;
// Array ( [signifydId] => 3365438099 [checkoutId] => 33569412 [orderId] => string [decision] => Array ( [createdAt] => 2022-12-20T00:39:37.384721Z [checkpointAction] => ACCEPT [checkpointActionReason] => SIGNIFYD_APPROVED [checkpointActionPolicy] => SIGNIFYD_DECISION [policies] => Array ( [overriding] => Array ( ) [default] => Array ( [name] => SIGNIFYD_DECISION [status] => EVALUATED_TRUE [action] => ACCEPT [reason] => SIGNIFYD_APPROVED ) ) [score] => 976.85304024732 ) [coverage] => Array ( [fraudChargebacks] => Array ( [amount] => 1569.64 [currency] => USD ) [inrChargebacks] => [allChargebacks] => ) )
 
echo '<h3>Datas Received Response from Signifyd    </h3>'; 
             curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledata);
             $response = curl_exec($ch);
             $info = curl_getinfo($ch);
 $resparray_check=json_decode($response, true);
 print_r($resparray_check);
 $checkoutId=$resparray_check['checkoutId'];
 $signifydId=$resparray_check['signifydId'];
 //$checkoutId=223894584;
 //print_r($resparray_check);	     
         echo "<br />Raw request: " . json_encode($info) ;
          echo  "<br /> Raw response: " . $response ;
             $error = curl_error($ch);
             echo "<br/>Curl error: " . $error ;
             $curlErrorNo = curl_error($ch);
             echo "<br/> Curl errorNo: " . $curlErrorNo ;
             curl_close($ch);
	     
echo '<hr>';

}

mysqli_query($dbc,"UPDATE `orders`  SET signifyd_sessionid='".$sessid."',signifydId='".$signifydId."',signifyd_checkoutID='".$checkoutId."' where  orders_id='".$orderid."'")
 
	     
?>