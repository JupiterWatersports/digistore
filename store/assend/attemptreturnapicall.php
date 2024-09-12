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
	$products_array[]=' { "itemName": "'.$row['products_name'].'",
	 "itemPrice": '.$row['final_price'].',
	 "itemQuantity": '.$row['products_quantity'].',
	
	 "itemId": "'.$row['products_model'].'" ,
	 "shipmentId": "4JLHENj6OY56D9BTcQcR"
	  }';
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

$cardbin=substr($orderinfo['cc_number'],0,6);
$cardexpmonth= round($orderinfo['cc_exp_month']) ;
$cardexpyear= $orderinfo['cc_exp_year']  ;
$cardlast=substr($orderinfo['cc_number'],-4);
$gateway='PAYPAL';
if(!$cardbin) $cardbin='441005';
if(!$cardexpmonth) $cardexpmonth='4';
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

$apiKey = 'bHlPsiDW2SsyFRc7vwwnYUHdfa6wOKtw';
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';

$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];
echo '<h3>Attempt Return API CALL    </h3>';
// Checkout API CALL    
$url='https://api.signifyd.com/v3/orders/events/returns/attempts';

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
   "orderId": "'.$orderid.'",
   "returnId": "e4056107-6720-431f-a9f4-fe0ff9b6c92a",
   "device": {
  "clientIpAddress": "'.$_SERVER['REMOTE_ADDR'].'",
  "sessionId": "'.$sessid.'" 
   },
   "returnedItems": [
     {
       "reason": "BETTER_PRICE_AVAILABLE",
       "itemName": "Sparkly Sandals",
       "itemPrice": 25.99,
       "itemQuantity": 4,
 
       "itemId": "sparkly-sandals-xl-yellow-2020",
 "shipmentId": "4JLHENj6OY56D9BTcQcR"
      
 
     }
   ],
   "replacementItems": {
     "products": [
       {
         "itemName": "Sparkly Sandals",
         "itemPrice": 25.99,
         "itemQuantity": 4,
 
         "itemId": "sparkly-sandals-xl-yellow-2020",
 	"shipmentId": "4JLHENj6OY56D9BTcQcR"
 
       }
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
         "carrier": "UPS",
         "minDeliveryDate": "2020-12-25T13:57:40-0700",
         "maxDeliveryDate": "2020-12-28T13:57:40-0700",
         "shipmentId": "4JLHENj6OY56D9BTcQcR",
         "fulfillmentMethod": "DELIVERY"
       }
     ]
   },
   "refund": {
     "method": "STORE_CREDIT",
     "amount": 105.99,
     "currency": "USD"
   },
   "initiator": {
     "employeeEmail": "bob@example.com",
     "employeeId": "0001234"
   }
 }';
 
 echo '<h3>Datas Sent    </h3>';
 echo $casesampledata;

 
echo '<h3>Datas Received Response from Signifyd    </h3>'; 
             curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledata);
             $response = curl_exec($ch);
             $info = curl_getinfo($ch);
 $resparray_check=json_decode($response, true);
 
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


mysqli_query($dbc,"UPDATE `orders`  SET signifyd_sessionid='".$sessid."',signifydId='".$signifydId."',signifyd_checkoutID='".$checkoutId."' where  orders_id='".$orderid."'")
 
	     
?>