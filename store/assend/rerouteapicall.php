<?php

 
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

$createdDate=date('c',strtotime($orderinfo['date_purchased'])) ;
$createdDate= $orderinfo['datep']  ;

//$sessid='ORDER_SESS_'.rand();
//$sessid='ORDER_SESS_518182823';
?>

<head>
<script
defer
type="text/javascript"
id="sig-api"
data-order-session-id="<?php echo $sessid;?>"
src="https://cdn-scripts.signifyd.com/api/script-tag.js"></script>
</head>

<?php

$apiKey = 'bHlPsiDW2SsyFRc7vwwnYUHdfa6wOKtw';
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';


$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];

 echo '<h3>Reroute API CALL    </h3>';


// ReRoute API CALL    
$url='https://api.signifyd.com/v3/orders/events/reroutes';

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

 $casesampledatat1='{
   "orderId": "'.$orderid.'",
   "device": {
 "clientIpAddress": "'.$_SERVER['REMOTE_ADDR'].'",
 "sessionId": "'.$sessid.'" 
   },
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
       "fulfillmentMethod": "DELIVERY"
     }
   ]
 }';
 
 
  echo '<h3>Datas Sent    </h3>';
  echo $casesampledatat1;
 
 echo '<h3>Datas Received Response from Signifyd    </h3>'; 
// $resparray_check=json_decode($response, true);
 
 //$checkoutId=$resparray_check['checkoutId'];
 //$signifydId=$resparray_check['signifydId'];
 
             curl_setopt($ch, CURLOPT_POSTFIELDS,  $casesampledatat1);
             $response = curl_exec($ch);
             $info = curl_getinfo($ch);
         echo "<br />Raw request: " . json_encode($info) ;
          echo  "<br /> Raw response: " . $response ;
             $error = curl_error($ch);
             echo "<br/>Curl error: " . $error ;
             $curlErrorNo = curl_error($ch);
             echo "<br/> Curl errorNo: " . $curlErrorNo ;
             curl_close($ch);


 


	     
?>