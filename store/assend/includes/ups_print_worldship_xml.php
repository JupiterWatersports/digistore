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
$qvn_refe1 = $oID;              //Reference that will show on the Order Number on label
$qvn_refe2 = "Customer ". $customers_id; //Reference that will show on the Customer Number on label
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
$delivery_postcode = $ups_export['delivery_postcode'];

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

$ups_header = "CustomerID,CompanyOrName,Attention,Address1,Address2,CountryTerritory,PostalCode,CityOrTown,StateProvinceCounty,Telephone,FaxNumber,EmailAddress,";
$ups_header .="ResidentialIndicator,ServiceType,NumberOfPackages,DescriptionOfGoods,ShipperNumber,BillingOption,";
$ups_header .="EMailAddress1,ShipSubjectLine,Memo,DescriptionOfGood,InvNAFTACOCountryTerritoryOfOrigin,";


//shipto data
$ups=$customers_id . ",";
$ups.=$delivery_company . ",";
$ups.=$delivery_name . ",";
$ups.=$delivery_street_address . ",";
$ups.=$delivery_address_2 . ",";
$ups.=$country . ",";
$ups.=$delivery_postcode . ",";
$ups.=$delivery_city . ",";
$ups.=$delivery_state . ",";
$ups.=$customers_telephone . ",";
$ups.=$customers_fax . ",";
$ups.=$customers_email_address . ",";
//$ups.="  <LocationID></LocationID>\r\n";
$ups.="1,";
 
//shipment info
$ups.=$service_type . ",";
$ups.=$number_packages . ",";
$ups.=$description_goods . ",";
$ups.=$ups_account_num . ",";
$ups.="PP,";
// Quantum View Notify
$ups.=$customers_email_address . ",";
$ups.="1,";
$ups.=$qvn_subject_line . ",";
$ups.=$qvn_memo . ",";

// goods info
$ups.=$description_goods;
$ups.=$country_origin . ",";


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
$ups_header .='PackageType,Weight,TrackingNumber,LargePackageIndicator,Reference1,Reference2,';
$ups.="CP,";
$ups.=$shipping_pounds2 . ",";
$ups.=",";
$ups.=",";
$ups.= $qvn_refe1 . ",";
$ups.=$qvn_refe2 . ",";

$number_packages--;
$shipping_pounds2 = ($total_pounds - $max_weight); // + SHIPPING_BOX_WEIGHT
}//**  end of loop

// close


// output
    // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-csv");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=upsexport.csv");
echo $ups_header."\r\n".$ups."\r\n";

exit;

// end
?>
