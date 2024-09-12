<?php
/*
  $Id: invoice.php,v 1.6 2005/11/01 00:37:30 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce
   http://www.oscommerce.com   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
	  
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);	 
$date = date('M d, Y');
$action='';
$action = $_GET['action']; 


	  
$usps_text = 'USPS Tracking Number: ';
$usps_track_num_noblanks = str_replace(' ', '', $order->info['usps_track_num']);
$usps_link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num_noblanks;
$usps_track = '<a target="_blank" style="font-family:Verdana, Arial, sans-serif; font-size:14px; text-decoration:underline; color:#006699;" href="' . $usps_link . '">' . $order->info['usps_track_num'] . '</a>' . "\n";

$ups_text = 'UPS Tracking Number: ';
$ups_track_num_noblanks = str_replace(' ', '', $order->info['ups_track_num']);
$ups_link = 'http://wwwapps.ups.com/etracking/tracking.cgi?TypeOfInquiryNumber=T&InquiryNumber1=' . $ups_track_num_noblanks . ' ';
$ups_track = '<a target="_blank" style="font-family:Verdana, Arial, sans-serif; font-size:14px; text-decoration:underline; color:#006699;" href="' . $ups_link . '">' . $order->info['ups_track_num'] . '</a>' . "\n";

$fedex_text = 'FedEx Tracking Number: ';
$fedex_track_num_noblanks = str_replace(' ', '', $order->info['fedex_track_num']);
$fedex_link = 'http://www.fedex.com/Tracking?tracknumbers=' . $order->info['fedex_track_num'] . '&action=track&language=english&cntry_code=us';
$fedex_track = '<a target="_blank" style="font-family:Verdana, Arial, sans-serif; font-size:14px; text-decoration:underline; color:#006699;" href="' . $fedex_link . '">' . $order->info['fedex_track_num'] . '</a>' . "\n";

if ($action=='email') { 
echo "<script type='text/javascript'>alert('Tracking Info has been emailed');</script>";
////

// Return a formatted address

// TABLES: customers, address_book

  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {

    if (is_array($address_id) && !empty($address_id)) {

      return tep_address_format($address_id['address_format_id'], $address_id, $html, $boln, $eoln);

    }



    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");

    $address = tep_db_fetch_array($address_query);



    $format_id = tep_get_address_format_id($address['country_id']);



    return tep_address_format($format_id, $address, $html, $boln, $eoln);

  }
  
require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . 'email_tracking.php'); 
 tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input('112') . "', 
				now(), 
				" . tep_db_input('3') . ", 
				'" .'Tracking Sent'. "')");
				
tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '112', last_modified = now() WHERE orders_id = '" . $_GET['oID'] . "'"); ?>
  <script src="ext/jquery/jquery.js" type="text/javascript"></script>
    <script>
localStorage.setItem("update", "3");

$(document).ready(function() {
localStorage.setItem("update", "4");
window.location.href="tracking.php?oID=<?php echo $oID;  ?>";
    })
	</script> <?php
	
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>Sent Tracking #<?php echo $oID; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff; font-size:14px;" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style>.dataTableContent, .main{font-size:14px;}
</style>
<?php echo tep_draw_form('send_quote','tracking.php', 'oID=' . $oID. '&action=email'); ?>
    <div style="text-align:center;">Ready to Send?</br>
        <span id="ready"><h3>Please confirm that the correct products are being shipped out.</h3></span>
        <button id="readyToSend" style="width: 150px; height: 50px; font-size: 20px; display: none;">Yes</button> <span></span>
    </div></form>
<!-- body_text //-->
<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
<td>
      <div style="float:left; width:40%;"><img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></div>
      <div style="float:right; width:40%; text-align:right; padding-right:15px;"><h2 style=" margin:10px 0px;">Shipping Confirmation</h2><strong>Order No. <a target="_new" style="font-family:Verdana, Arial, sans-serif; font-size:14px; text-decoration:underline; color:#006699;" href="https://www.jupiterkiteboarding.com/store/account_history_info.php?order_id=<?php echo $oID; ?>"><?php echo $oID; ?></a></strong></div>
</td>
</tr>     

           <tr>
      <td width="100%"><hr size="2"></td>
                  </tr>
 
 
<tr>
<td>Thank you for shopping with us,<br/><br/>You will find your tracking number below and please note that it may take 24 hours for any updates to be available.</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<?php 
if(!empty($order->info['fedex_track_num'])) { 
echo $fedex_text . $fedex_track; }
if(!empty($order->info['usps_track_num'])) {
echo $usps_text . $usps_track;}
if(!empty($order->info['ups_track_num']))
{echo $ups_text . $ups_track;}
elseif (empty($order->info['fedex_track_num'])& empty($order->info['usps_track_num'])& empty($order->info['ups_track_num']) ){echo'Sorry we forgot your tracking number.';}?>
</tr>
<tr><td>&nbsp;</td></tr>

  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><hr></td>
      </tr>
      <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="main"><b><?php echo 'Shipped To'; ?></b></td>
              </tr>
              <tr> 
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="main"><b><?php echo 'Billed To'; ?></b></td>
              </tr>
              <tr> 
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr> 
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr> 
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr> 
                <td class="main"><?php echo $order->customer['email_address']; ?></td>
              </tr>
		
            </table></td>
       
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
   
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
     <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow" width="100%" style="height:30px;"> 
          <td></td>
            <td width="100%" style="background-color: #39F; color: FFF; padding-left:15px;">Order Summary</td>
        </tr>
    <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) { 
        if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
            for ($j = 0; $j < $k; $j++) {
                if($order->products[$i]['attributes'][$j]['serial_no'] <> ' '){
                    $serial_check = 'serialCheck';
                } else {
                    $serial_check ='';
                }
            }
        }
      echo '      <tr><td><input type="checkbox" class="abc '.$serial_check.'"></td>
      <td style="border-bottom:1px solid #ddd; padding:13px 0px;"><table><tr>';
 
 
    echo       '        <td class="dataTableContent" valign="top" width="80%"><span style="font-size:14px; display:block; margin-bottom:5px;">' . $order->products[$i]['name'].'</span>';

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          
      echo ' '. ($order->products[$i]['attributes'][$j]['serial_no']?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }


      echo 
	 '        <td class="dataTableContent" valign="top" align="center"  width="15%">' . '&nbsp;x'.$order->products[$i]['qty'] . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top" width="15%"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '   </tr></table>  </td></tr>' . "\n";
    }
?>
        <tr>
          <td align="right" colspan="8"></td>
        </tr>
        <tr > 
          <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
              <?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr >' . "\n" .
         '            <td align="right" >' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" >' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '       </tr>' . "\n";
  }
?>
            </table>
            <p>&nbsp;</p><?php echo tep_draw_separator(); ?></td>
        </tr>
        
      </table></td>
  </tr><tr>
          <td  style="color:#000;" align="center"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
          </tr>
</table>
<script src="ext/jquery/jquery.js" type="text/javascript"></script>   
<script>
   /* $(".serialCheck").change(function() {
        var check = confirm('Did you confirm the serial number?');
            if (check == true){
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
    }); */
  $("input[type='checkbox'].abc").change(function(){
    var a = $("input[type='checkbox'].abc");
    if(a.length == a.filter(":checked").length){
        alert("You are ready to send Tracking info.");
        $("#readyToSend").show();
        $("#ready").hide();
    }
});
    </script>
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

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
 
	$orderinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT *,DATE_FORMAT(date_purchased, '%Y-%m-%dT%TZ') as datep FROM `orders` where orders_id='".$orderid."' and signifyd_checkoutID!=''"));
	
	if(!$orderinfo) exit;
	
	$ordertotalinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `orders_total` where class='ot_total' and orders_id='".$orderid."'"));
	$ordertotalSinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `orders_total` where class='ot_shipping' and orders_id='".$orderid."'"));
	$res=mysqli_query($dbc,"SELECT * FROM `orders_products` where orders_id='".$orderid."'");
	
	 
	  while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
	$products_array[]=' { "itemName": "'.$row['products_name'].'",
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

$track_number='string';
$track_link='string';
if($orderinfo['fedex_track_num']){
$carrier='FEDEX';
$track_number=$orderinfo['fedex_track_num'];
$track_link='http://www.fedex.com/Tracking?tracknumbers='.$track_number.'&action=track&language=english&cntry_code=us';
}
if($orderinfo['ups_track_num']){
$carrier='UPS';
$track_number=$orderinfo['ups_track_num'];
$track_link='http://wwwapps.ups.com/etracking/tracking.cgi?TypeOfInquiryNumber=T&InquiryNumber1='.$track_number;
}
if($orderinfo['usps_track_num']){
$carrier='USPS';
$track_number=$orderinfo['usps_track_num'];
$track_link='http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum='.$track_number;
}
//http://www.fedex.com/Tracking?tracknumbers=9999999999999&action=track&language=english&cntry_code=us
//http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=324234324234324
//http://wwwapps.ups.com/etracking/tracking.cgi?TypeOfInquiryNumber=T&InquiryNumber1=21321344222
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

 
$apiKey='NGMhsnwFuim3bonXg0pZhEFqjtCY6SNH';

$headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];

 echo '<h3>FullFIllment API CALL    </h3>';


// ReRoute API CALL    
$url='https://api.signifyd.com/v3/orders/events/fulfillments';

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
$fullfillstatusarr=array("PARTIAL", "COMPLETE", "REPLACEMENT", "CANCELED");
$shipmentstatusarr=array("WAITING_FOR_PICKUP", "IN_TRANSIT", "OUT_FOR_DELIVERY", "FAILED_ATTEMPT", "DELIVERED" ,"EXCEPTION");

	$orderstatus_shipinfo=mysqli_fetch_array(mysqli_query($dbc,"SELECT *,DATE_FORMAT(date_added, '%Y-%m-%dT%TZ') as datep FROM `orders_status_history`  where orders_status_id=112 and orders_id='".$orderid."'"));
	
	$randomNum1=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 8);
	$randomNum2=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);
	$randomNum3=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);
	$randomNum4=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);
	$randomNum5=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 12);
	
	$randomNum1=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
	$randomNum2=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum3=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum4=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
	$randomNum5=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12);
	
	$shipmentID=$randomNum1."-".$randomNum2."-".$randomNum3."-".$randomNum4."-".$randomNum5;
	 //$shipmentID=$orderinfo['signifyd_sessionid'];
 $casesampledatat1='{
   "orderId": "'.$orderid.'",
   "fulfillmentStatus": "'.$fullfillstatusarr[1].'",
   "fulfillments": [
     {
       "shipmentId": "'.$shipmentID.'",
       "shippedAt": "'.$orderstatus_shipinfo['datep'].'",
       "products": [
'.	$products_str.'
       ],
       "shipmentStatus": "'.$shipmentstatusarr[2].'",
       "trackingUrls": [
         "'.$track_link.'"
       ],
       "trackingNumbers": [
         "'.$track_number.'"
       ],
       "destination": {
 "fullName": "'.$del_custname.'",
 "organization": "'.$del_company.'",
 "address": {
 "streetAddress": "'.$del_street_address.'",
 "postalCode": "'.$del_postalCode.'",
 "city": "'.$del_city.'",
 "provinceCode": "FL",
 "countryCode": "US"
 }	 
	 ,
         "confirmationPhone": "'.$custphone.'" 	  
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
       "carrier": "'.$carrier.'",
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

