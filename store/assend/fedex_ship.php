<?php
/**
 * fedex.com Ship Auto-Fill Contrib
 *  v 0.5
 */

	global $db;
	require('includes/application_top.php');
	$oID = (int)tep_db_prepare_input($HTTP_GET_VARS['oID']);
	include(DIR_WS_CLASSES . 'order.php');

$order->delivery['state'] = preg_match('/Florida/','Florida',$order->delivery['state']);
	$order = new order($oID);
	//Gets the return & billing address two digit state code
	$shipping_zone_query = tep_db_query("select z.zone_code from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where zone_name = '" . $order->delivery['state'] . "' AND c.countries_name = '" . $order->delivery['country'] . "' AND c.countries_id = z.zone_country_id");
	$shipping_zone = tep_db_fetch_array($shipping_zone_query);
	$shipping_zone_code = ($shipping_zone['zone_code'] == '' ? $order->delivery['state'] : $shipping_zone['zone_code']);  // if the query result was empty, then use the state name



	$contents_value = ceil(substr(strip_tags($order->totals[0]['text']),1));
	$send_value = (USPS_SEND_VALUE_OVER > $contents_value ? '' : $contents_value);

    // set residental or commericial
    $res_com = "false";
    if ($order->delivery['residence_id'] == "Residential"){
    $res_com =  "true";
    }

	?>
	<script type="text/javascript">
	function ParseIt() {
	  document.getElementById('LabelInformationAction').submit();
	}
	</script>

	<form name="Fedex_labels" target="_self" method="post" action="https://www.fedex.com/shipping/shipEntryAction.do?locale=en_US&urlparams=us&sType=F" id="LabelInformationAction">
	  <input type="hidden" name="form.submitControl" value="NewLabel">

	<input type="hidden" name="fromData.addressData.countryCode" value="US" id="fromData.countryCode.hidden">

	<input type="hidden" name="toData.addressData.contactName" value="<?php echo $order->delivery['name']; ?>" id="toData.contactName">
	<input type="hidden" name="toData.addressData.companyName" value="<?php echo $order->delivery['company']; ?>" id="toData.companyName">

	<input type="hidden" name="toData.addressData.addressLine1" value="<?php echo $order->delivery['street_address']; ?>" id="toData.addressLine1">
	<input type="hidden" name="toData.addressData.addressLine2" value="<?php echo $order->delivery['suburb']; ?>" id="toData.addressLine2">
	<input type="hidden" name="toData.addressData.city" value="<?php echo $order->delivery['city']; ?>" id="toData.city">

	<input type="hidden" name="toData.addressData.stateProvinceCode" value= "<?php echo $shipping_zone_code;?>" id="toData.stateProvinceCode">
	<input type="hidden" name="toData.addressData.zipPostalCode" value="<?php echo $order->delivery['postcode']; ?>" id="toData.zipPostalCode">

    <input type="hidden" name="toData.addressData.phoneNumber" value="<?php echo $order->customer['telephone']; ?>" id="toData.phoneNumber">
    <input type="hidden" name="toData.addressData.residential" value="<?php echo $res_com; ?>" id="toData.residential">


    <input type="hidden" name="psdData.mpsRowDataList[0].carriageValue" value="<?php echo $send_value; ?>" id="psd.mps.row.declaredValue.0">
    <input type="hidden" name="psdData.declaredValueCurrencyCode" value="USD" id="psdData.declaredValueCurrencyCode">

    <input type="hidden" name="billingData.referenceData.yourReference" value="<?php echo (int)$oID; ?>" id="billingData.yourReference">
<input type="hidden" name="notificationData.recipientNotifications.email" value="<?php echo $order->customer['email_address']; ?>" id="notificationData.recipientNotifications.email">


	<input type="submit" value="Ship a Fedex Package" />

	</form>

	<script>
	document.Fedex_labels.submit();
	</script> 
