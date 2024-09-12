<?php
/*
 $Id: ups_print_worldship_xml.php,v 1.6 2010/03/22 17:32:13 am Exp $
 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com
 Released under GPL and may be modified.
 written by: Thuan V. Nguyen, http://www.a8le.com
 Modified by: Chance McClurkin, Http://alcohol-injection.com
 Modified by gewuerznelke v. 1.6
 */

  require('includes/application_top.php');

$ups_account_num = MODULE_SHIPPING_LABEL_UPSWS_UPS_ACCOUNT_NUMBER;  // ups account number
$country_origin = MODULE_SHIPPING_LABEL_UPSWS_COUNTRY_ORIGIN;  // country
$description_goods = MODULE_SHIPPING_LABEL_UPSWS_GOODS_DESCRIPTION; // description of goods
$qvn_subject_line = MODULE_SHIPPING_LABEL_UPSWS_QVN_SUBJECT_LINE;  // quantum view notification email subject line
$qvn_memo = MODULE_SHIPPING_LABEL_UPSWS_QVN_MEMO; // text note that will be sent to the users
$qvn_refe2 = MODULE_SHIPPING_LABEL_UPSWS_QVN_LABEL_REF; //Reference that will show on the label
$max_weight = MODULE_SHIPPING_LABEL_UPSWS_MAX_WEIGHT_PER_PACKAGE;

// get order ID
$oID = tep_db_prepare_input($_GET['oID']);

// get service type from orders page
$service_type = $_GET['ups_method'];

function replaceBadCharacters($str,$replStr) {
	$badChars = array();
	$goodChars = array();
	
	$locArr = explode(',', $replStr);
	foreach ($locArr as $key => $value) {
		$locArr2 = explode('=>', trim($value));
		foreach ($locArr2 as $key2 => $value2) {
			$badChars[] = $key2;
			$goodChars[] = $value2;
		}
	}
	return trim(str_replace($badChars, $goodChars, $str));
}

// query db for order info
$ups_export_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
$ups_export = tep_db_fetch_array($ups_export_query);

$customers_id = $ups_export['customers_id'];
$country = $ups_export['delivery_country'];
$customers_telephone = replaceBadCharacters($ups_export['customers_telephone'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT_TELEPHONE);
$customers_fax = replaceBadCharacters($ups_export['customers_fax'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT_TELEPHONE);
$customers_email_address = $ups_export['customers_email_address'];

// ** editable variables **
$qvn_refe1 = "Order:". $oID;              //Reference that will show on the Order Number on label
$qvn_refe2 = "Customer: ". $customers_id; //Reference that will show on the Customer Number on label
// ** editable variables **

// if delivery_company is empty, use delivery_name
if ($ups_export['delivery_company'] == "") {
	$delivery_company = replaceBadCharacters($ups_export['delivery_name'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
	$delivery_name = "";
} else {
	$delivery_company = replaceBadCharacters($ups_export['delivery_company'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
	$delivery_name = replaceBadCharacters($ups_export['delivery_name'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
}

$delivery_street_address = replaceBadCharacters($ups_export['delivery_street_address'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
$delivery_address_2 = replaceBadCharacters($ups_export['delivery_suburb'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
$delivery_city = replaceBadCharacters($ups_export['delivery_city'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
$delivery_postcode = replaceBadCharacters($ups_export['delivery_postcode'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);

if (strlen($ups_export['delivery_state']) > 2) {
// Get the 2 letter state
$state_list = array(
                'AL'=>"Alabama",
                'AK'=>"Alaska", 
                'AZ'=>"Arizona", 
                'AR'=>"Arkansas", 
                'CA'=>"California", 
                'CO'=>"Colorado", 
                'CT'=>"Connecticut", 
                'DE'=>"Delaware", 
                'DC'=>"District Of Columbia", 
                'FL'=>"Florida", 
                'GA'=>"Georgia", 
                'HI'=>"Hawaii", 
                'ID'=>"Idaho", 
                'IL'=>"Illinois", 
                'IN'=>"Indiana", 
                'IA'=>"Iowa", 
                'KS'=>"Kansas", 
                'KY'=>"Kentucky", 
                'LA'=>"Louisiana", 
                'ME'=>"Maine", 
                'MD'=>"Maryland", 
                'MA'=>"Massachusetts", 
                'MI'=>"Michigan", 
                'MN'=>"Minnesota", 
                'MS'=>"Mississippi", 
                'MO'=>"Missouri", 
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio", 
                'OK'=>"Oklahoma", 
                'OR'=>"Oregon", 
                'PA'=>"Pennsylvania", 
                'RI'=>"Rhode Island", 
                'SC'=>"South Carolina", 
                'SD'=>"South Dakota",
                'TN'=>"Tennessee", 
                'TX'=>"Texas", 
                'UT'=>"Utah", 
                'VT'=>"Vermont", 
                'VA'=>"Virginia", 
                'WA'=>"Washington", 
                'WV'=>"West Virginia", 
                'WI'=>"Wisconsin", 
                'WY'=>"Wyoming");                
	foreach ($state_list as $key => $value) {
		if ($ups_export['delivery_state'] == $value)
			$delivery_state = $key;
		}
	} else {
		// Google Checkout/PayPal uses 2 letter state
		$delivery_state = replaceBadCharacters($ups_export['delivery_state'], MODULE_SHIPPING_LABEL_UPSWS_CHAR_REPLACEMENT);
	}

// Weight Calculations
$weight_query = tep_db_query("select sum(op.products_quantity * p.products_weight) as weight from " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS_PRODUCTS . " op where op.products_id = p.products_id AND op.orders_id = '" . (int)$oID . "'");
$total_weight = tep_db_fetch_array($weight_query);
$shipping_weight =  $total_weight['weight'] ;  // + SHIPPING_BOX_WEIGHT  adds the "Package Tare weight" configuration value to the package value
$shipping_weight = ($shipping_weight < 0.0625 ? 0.0625 : $shipping_weight); // if shipping weight is less than one ounce then make it one ounce
$shipping_weight = ceil($shipping_weight*16)/16;  // rounds up to the next ounce, 4.6 oz becomes 5 oz, 15.7 oz becomes 1 lb
$shipping_pounds = ceil ($shipping_weight);
//$shipping_ounces = (16 * ($shipping_weight - floor($shipping_weight)));

$total_pounds = $shipping_pounds;

// Package count Calculations
$number_packages = 1;
if (MODULE_SHIPPING_LABEL_UPSWS_MULTIPLE_PACKAGES == "true") {
$number_packages = 0; // Setting a base number
while ( $shipping_pounds >= $max_weight ) {
$number_packages += 1;
$shipping_pounds -= $max_weight ;// + SHIPPING_BOX_WEIGHT
}
if ($shipping_pounds >= 1) {
$number_packages += 1;
}
}


// create the customer delivery information
$ups  ="<OpenShipments xmlns=\"x-schema: OpenShipments.xdr\">\r\n";
$ups .="<OpenShipment ProcessStatus=\"\" ShipmentOption=\"SC\">\r\n";

//shipto data
$ups.=" <ShipTo>\r\n";
$ups.="  <CustomerID>" . $customers_id . "</CustomerID>\r\n";
$ups.="  <CompanyOrName>" . $delivery_company . "</CompanyOrName>\r\n";
$ups.="  <Attention>" . $delivery_name . "</Attention>\r\n";
$ups.="  <Address1>" . $delivery_street_address . "</Address1>\r\n";
$ups.="  <Address2>" . $delivery_address_2 . "</Address2>\r\n";
$ups.="  <Address3></Address3>\r\n";
$ups.="  <CountryTerritory>" . $country . "</CountryTerritory>\r\n";
$ups.="  <PostalCode>" . $delivery_postcode . "</PostalCode>\r\n";
$ups.="  <CityOrTown>" . $delivery_city . "</CityOrTown>\r\n";
$ups.="  <StateProvinceCounty>" . $delivery_state . "</StateProvinceCounty>\r\n";
$ups.="  <Telephone>" . $customers_telephone . "</Telephone>\r\n";
$ups.="  <FaxNumber>" . $customers_fax . "</FaxNumber>\r\n";
$ups.="  <EmailAddress>" . $customers_email_address . "</EmailAddress>\r\n";
$ups.="  <LocationID></LocationID>\r\n";
$ups.="  <ResidentialIndicator>1</ResidentialIndicator>\r\n";
$ups.=" </ShipTo>\r\n";

//shipment info
$ups.=" <ShipmentInformation>\r\n";
$ups.="  <ServiceType>" . $service_type . "</ServiceType>\r\n";
$ups.="  <NumberOfPackages>" . $number_packages . "</NumberOfPackages>\r\n";
$ups.="  <DescriptionOfGoods>" . $description_goods . "</DescriptionOfGoods>\r\n";
$ups.="  <ShipperNumber>" . $ups_account_num . "</ShipperNumber>\r\n";
$ups.="  <BillingOption>PP</BillingOption>\r\n";
// Quantum View Notify
$ups.="  <QVNOption>\r\n";
$ups.="  	<QVNRecipientAndNotificationTypes>\r\n";
$ups.="  	 <EMailAddress>" . $customers_email_address . "</EMailAddress>\r\n";
$ups.="  	 <Ship>1</Ship>\r\n";
$ups.="  	</QVNRecipientAndNotificationTypes>\r\n";
$ups.="  	<SubjectLine>" . $qvn_subject_line . "</SubjectLine>\r\n";
$ups.="  	<Memo>" . $qvn_memo . "</Memo>\r\n";
$ups.="  </QVNOption>\r\n";
$ups.=" </ShipmentInformation>\r\n";

// goods info
$ups.="  <Goods>\r\n";
$ups.="   <DescriptionOfGood>" . $description_goods . "</DescriptionOfGood>\r\n";
$ups.="   <Inv-NAFTA-CO-CountryTerritoryOfOrigin>" . $country_origin . "</Inv-NAFTA-CO-CountryTerritoryOfOrigin>\r\n";
$ups.="  </Goods>\r\n";

// Begining of package loop
while ( $number_packages > 0 ) {
// Multable packages
if ($total_pounds >= $max_weight) {
$shipping_pounds2 = $max_weight;
$total_pounds = (($total_pounds) - $max_weight) ;
//  Single package
} else {
$shipping_pounds2 = $total_pounds;
}

// package info
$ups.=" <Package>\r\n";
$ups.="  <PackageType>CP</PackageType>\r\n";
$ups.="  <Weight>" . $shipping_pounds2 . "</Weight>\r\n";
$ups.="  <TrackingNumber></TrackingNumber>\r\n";
$ups.="  <LargePackageIndicator></LargePackageIndicator>\r\n";
$ups.="  <Reference1>" . $qvn_refe1 . "</Reference1>\r\n";
$ups.="  <Reference2>" . $qvn_refe2 . "</Reference2>\r\n";
$ups.=" </Package>\r\n";

$number_packages--;
$shipping_pounds2 = ($total_pounds - $max_weight); // + SHIPPING_BOX_WEIGHT
}
//**  end of loop



// close
$ups.="</OpenShipment>";
$ups.="</OpenShipments>";
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body onload="SetFocus();">
<!-- body //-->
<table width="1000px"  border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">ups labels</td>
          </tr>
        </table></td>
      </tr>
      <tr><td>
<?php
$post_url = "https://wwwcie.ups.com/ups.app/xml/ShipConfirm";

$ch = curl_init($post_url);

curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, "xmldata=".$ups);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);


$data = curl_exec($ch); 
// to get information on the curl resultset
$info = curl_getinfo($ch);

if(curl_errno($ch)){
    print curl_error($ch);
}

curl_close($ch);

/*
$URL = "https://wwwcie.ups.com/ups.app/xml/ShipConfirm";
 
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$ups");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch); */
echo '<br><br>'.$ups; 

 
?>

          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

