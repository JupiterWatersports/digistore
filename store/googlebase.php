<?php
/*
  $Id: googlebase.php 1739 2010-03-28 00:52:16Z gw $
  $Loc: catalog/ $
  $Mod: 20100328 Google Base Auto Feeder 1.3 Geoffrey Walton $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// ########## BEGIN CONFIGURATION ###################

// START Main Configuration

// Change to 'true' to enable the use of SEO
  define('SEO_ENABLED','true');
// Change this to the id of your language.  By default 1 is english
  $languages_id = 1;   
// Change this to your unit of wt
  $WeightUnit = " Gms.";    
  $Separator="\t";						// Set to "\t" or "|" or "~" depending on your feed settings
//End Main Configuration

// START Optional Advance Configuration
// Monetary Adjustments for Products in Feed
$taxRate = 0;							// default = 0 (e.g. for 17.5% tax use "$taxRate = 17.5;")
$convertCur = false;					//  true
$curType = "USD";						// Converts Currency to any defined currency (eg. USD, EUR, GBP)
//END Optional Advance Configuration

// START Optional Advance Feed Attributes
$optional_sec = true;					// (optional_sec must be enabled to use any of the following options)
$manufacturer = true;

$payment_accepted = true;
$default_payment_methods = "PayPal,Visa,MasterCard,AMEX"; // Acceptable values: cash, check, GoogleCheckout, Visa, MasterCard, AMEX, Discover, wiretransfer

$product_type = true;
$currency = true;
$default_currency = "USD";				//this is not binary. Change this to Google Base Currency e.g. - USD

$feed_quantity = true;
$feed_quantity_method = 2;				// 1 - Report true levels, 
										// 2 - Show report true values if > 0 else set to default_feed_quantity 
										// 3 - Set all stock levels to default_feed_quantity
$default_feed_quantity = 1;
$advertise_inactive_with_stock = false;	// how will they be able to buy if it is inactive?

$output_dummy_desc = true;
$dummy_desc = "";						// If this is left blank use product title.

// Mandatory fields since 31 jan 2007 - As there as no values held for each product, set defaults as required
$default_condition = "New";				// Could be old
$default_age_range = "20-90";			// Change this to Age Range who could view your feeds - 30-60
$default_made_in = "USA";				// Change this to country of manufacure
// End of Manatory fields

$location = true;
$default_location = "1500 N US HWY 1, Jupiter, Fl 33469";		// Your store address

$tax = true;							// This attribute is no longer supported leave this value as false
$default_tax_code = "::0:n";			//Check for valid values in google http://www.google.com/support/merchants/bin/answer.py?answer=160085&hl=en#tax

$shipping = false;
$lowestShipping = "";					//this is not binary. Custom Code is required to provide the shipping cost per product.  ###needs to be an array for per product.

$upc = false;							// UPC or EAN requires new field on the product table, you will need to change the sql on line 151/2
										// will display model no if sql not changed to new field name.
$mpn = false;							//manu part number, NOT your internal store sku same requirements as for upc
$isbn = false;
// END Optional Advance Feed Attributes

// ########## END CONFIGURATION ###################
//Please avoid editing the codes below

$taxCalc = ($taxRate/100) + 1;  

$feed_exp_date = date('Y-m-d', time() + 2592000 );

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');

  require_once('includes/filenames.php');
  require_once('includes/database_tables.php');

  $home = DB_SERVER;
  $user=DB_SERVER_USERNAME;
  $pass=DB_SERVER_PASSWORD;
  $base=DB_DATABASE;
  $catalogURL = HTTP_SERVER.DIR_WS_HTTP_CATALOG;
  $imageURL = HTTP_SERVER.DIR_WS_HTTP_CATALOG.DIR_WS_IMAGES;

if(SEO_ENABLED=='true'){
  include_once('includes/classes/seo.class.php');
  $seo_urls = new SEO_URL($languages_id);

  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
     global $seo_urls;
     return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  }
}

if(SEO_ENABLED=='true'){
   $productURL = 'product_info.php'; 
   $productParam = "products_id=";   
}else{
   $productURL = $catalogURL.'product_info.php?products_id=';
}

$already_sent = array();

if($convertCur)
{
   if(SEO_ENABLED=='true'){
       $productParam="" . $curType . "&products_id=";
   }else{
       $productURL = $catalogURL."/product_info.php?currency=" . $curType . "&products_id=";  //where CURTYPE is your currency type (eg. USD, EUR, GBP)
   }
}


if (!($link=mysql_connect($home,$user,$pass)))
{
echo "Error when connecting itself to the data base";
exit();
}
if (!mysql_select_db( $base , $link ))
{
echo "Error the data base does not exist";
exit();
}

// SUBSTRING_INDEX(products_description.products_name,' ',1) as mpn,
//products.products_upc AS upc,
//products.products_isbn AS isbn,

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model AS prodModel, 
products_weight AS weight, 
manufacturers.manufacturers_name AS mfgName,
manufacturers.manufacturers_id,
products.products_id AS id,
products.products_model AS upc,
products.products_model AS isbn,
products.products_model AS mpn,
products_description.products_name AS name,
products_description.products_description AS description,
products.products_quantity AS quantity,
products.products_status AS prodStatus,
FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) * " . $taxCalc . ",2) AS price,
CONCAT( '" . $imageURL . "' , products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories)
left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )
WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id
ORDER BY
products.products_id ASC
";

/*  Ready for multi image contributions

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model AS prodModel, 
products_weight AS weight, 
manufacturers.manufacturers_name AS mfgName,
manufacturers.manufacturers_id,
products.products_id AS id,
products.products_model AS upc,
products.products_model AS isbn,
products.products_model AS mpn,
products_description.products_name AS name,
products_description.products_description AS description,
products.products_quantity AS quantity,
products.products_status AS prodStatus,
FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) * " . $taxCalc . ",2) AS price,
CONCAT( '" . $imageURL . "' , products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories)
left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )
WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id
ORDER BY
products.products_id ASC
";

*/

$catInfo = "
SELECT
categories.categories_id AS curCatID,
categories.parent_id AS parentCatID,
categories_description.categories_name AS catName
FROM
categories,
categories_description
WHERE categories.categories_id = categories_description.categories_id
";

function findCat($curID, $catTempPar, $catTempDes, $catIndex)
{
	if( (isset($catTempPar[$curID])) && ($catTempPar[$curID] != 0) )
	{
		if(isset($catIndex[$catTempPar[$curID]]))
		{
			$temp=$catIndex[$catTempPar[$curID]];
		}
		else
		{
			$catIndex = findCat($catTempPar[$curID], $catTempPar, $catTempDes, $catIndex);
			$temp = $catIndex[$catTempPar[$curID]];
		}
	}
	if( (isset($catTempPar[$curID])) && (isset($catTempDes[$curID])) && ($catTempPar[$curID] == 0) )
	{
		$catIndex[$curID] = $catTempDes[$curID];
	}
	else
	{
		$catIndex[$curID] = $temp . ", " . $catTempDes[$curID];
	}
	return $catIndex;

}

$catIndex = array();
$catTempDes = array();
$catTempPar = array();
$processCat = mysql_query( $catInfo )or die( $FunctionName . ": SQL error " . mysql_error() . "| catInfo = " . htmlentities($catInfo) );
while ( $catRow = mysql_fetch_object( $processCat ) )
{
	$catKey = $catRow->curCatID;
	$catName = $catRow->catName;
	$catParID = $catRow->parentCatID;
	if($catName != "")
	{
		$catTempDes[$catKey]=$catName;
		$catTempPar[$catKey]=$catParID;
	}
}

foreach($catTempDes as $curID=>$des)  //don't need the $des
{
	$catIndex = findCat($curID, $catTempPar, $catTempDes, $catIndex);
}

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
' ');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "Æ" => "", "&trade;" => "", "ô" => "", "\t" => "", "	" => "");

$output = "link".$Separator."title".$Separator."description".$Separator."expiration_date".$Separator."price".$Separator."image_link".$Separator."genre".$Separator."id".$Separator."weight";
//create optional section
if($optional_sec =='true')
{
	if($shipping =='true')
		$output .= $Separator."shipping";
	if($manufacturer =='true')
		$output .= $Separator."manufacturer";
	if($upc =='true')
		$output .= $Separator."upc";
	if($mpn =='true')
		$output .= $Separator."mpn";
	if($isbn =='true')
		$output .= $Separator."isbn";
	if($payment_accepted =='true')
		$output .= $Separator."payment_accepted";
	if($product_type =='true')
		$output .= $Separator."product_type";
	if($currency =='true')
		$output .= $Separator."currency";
    if($location =='true')
        $output .= $Separator."location";
	if($tax =='true')
		$output .= $Separator."tax";
	if($feed_quantity =='true')
		$output .= $Separator."quantity";
  
	$output .= $Separator."brand".$Separator."condition".$Separator."age_range".$Separator."made_in"; 
}
$output .= "\n";
$result=mysql_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );
//Currency Information
if($convertCur)
{
	$sql3 = "
	SELECT
	currencies.value AS curUSD
	FROM
	currencies
	WHERE currencies.code = '$curType'
	";

	$result3=mysql_query( $sql3 )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql3 = " . htmlentities($sql3) );
	$row3 = mysql_fetch_object( $result3 );
}

while( $row = mysql_fetch_object( $result ) )
{
	if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop

	if( $row->prodStatus == 1 || ($advertise_inactive_with_stock =='true' && $quantity == 1) )
	{

		if($convertCur)
		{
			$row->price = ereg_replace("[^.0-9]", "", $row->price);
			$row->price = $row->price *  $row3->curUSD;
			$row->price = number_format($row->price, 2, '.', ',');
		}

        if(SEO_ENABLED=='true'){
                $output .= tep_href_link($productURL,$productParam . $row->id) . $Separator;
        }else{
				$output .= $row->product_url . $Separator;
        }

		if($output_dummy_desc =='true') {

			if($row->description == "") {

				if($dummy_desc == "") {
					$row->description = $row->name;
				} else {
					$row->description = $dummy_desc;
				}

			}

		}

		$output .= preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . $Separator . 
		preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . $Separator .
		$feed_exp_date . $Separator .
		$row->price . $Separator .
		$row->image_url . $Separator .
		$catIndex[$row->prodCatID] . $Separator .
		$row->id . $Separator .
		$row->weight . $WeightUnit;

	//Now add the optional values to the end of the line
	if($optional_sec =='true')
	{
		if($shipping =='true')
			$output .= $Separator . $lowestShipping;
		if($manufacturer =='true')
			$output .= $Separator . $row->mfgName;
		if($upc =='true')
			$output .= $Separator . $row->upc;			// Change prodModel to upc
		if($mpn =='true')
			$output .= $Separator . $row->mpn;			// Change prodModel to manu_part_no
		if($isbn =='true')
			$output .= $Separator . $row->isbn;			// Change prodModel to isbn
		if($payment_accepted =='true')
			$output .= $Separator . $default_payment_methods;
		if($product_type =='true')
		{
			$catNameTemp = strtolower($catName);
			$output .= $Separator . $row->catName;
		}
		if($currency =='true')
			$output .= $Separator . $default_currency;
        if($location==1)
            $output .= $Separator . $default_location;
		if($tax =='true')
			$output .= $Separator . $default_tax_code;

		if($feed_quantity =='true') {

			if($feed_quantity_method == 1) {
				$output .= $Separator . $row->quantity;
			} elseif($feed_quantity_method == 2) {
				if($row->quantity > 0) {
					$output .= $Separator . $row->quantity;
				}else{
					$output .= $Separator . $default_feed_quantity;
				}
			} else {
				$output .= $Separator . $default_feed_quantity;
			}
			
		}
			// Now add some manatory fields
            $output .= $Separator . $row->mfgName;
			$output .= $Separator . $default_condition;
			$output .= $Separator . $default_age_range;
			$output .= $Separator . $default_made_in;
	}
	$output .= "\n";
	}
	$already_sent[$row->id] = 1;
}
echo $output;
?>