<?php
/*
  $Id: google_products.php,v 1.1 2011/06/28 by Kevin L. Shelton
  Released under the GNU General Public License
*/

    require('includes/application_top.php');
    // Include currencies class
    require(DIR_WS_CLASSES . 'currencies.php');

    function GenerateSubmitURL(){
		$url = urlencode(HTTP_SERVER . DIR_WS_CATALOG . 'sitemapindex.xml');
		return htmlspecialchars(utf8_encode('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url));
	} # end function

// controllo delle lingue	
        $controllo = $languages_id;
		$query = "SELECT languages_id, code FROM " . TABLE_LANGUAGES . " WHERE languages_id = $controllo";
    	
		$result = tep_db_query($query);
    	
		while ($row = tep_db_fetch_array($result)){
            $codice = $row['code']; 
        }
	
	$file = 'sitemaps.index.php?language=';
	$url = $file . $codice;

  $currencies = new currencies();
// html entity name to ISO-8859-1 character code
$trans_table = array('&apos;' => '&#39;', '&nbsp;' => '&#160;', '&iexcl;' => '&#161;', '&cent;' => '&#162;', '&pound;' => '&#163;', '&curren;' => '&#164;', '&yen;' => '&#165;', '&brvbar;' => '&#166;', '&sect;' => '&#167;', '&uml;' => '&#168;', '&copy;' => '&#169;', '&ordf;' => '&#170;', '&laquo;' => '&#171;', '&not;' => '&#172;', '&shy;' => '&#173;', '&reg;' => '&#174;', '&macr;' => '&#175;', '&deg;' => '&#176;', '&plusmn;' => '&#177;', '&sup2;' => '&#178;', '&sup3;' => '&#179;', '&acute;' => '&#180;', '&micro;' => '&#181;', '&para;' => '&#182;', '&middot;' => '&#183;', '&cedil;' => '&#184;', '&sup1;' => '&#185;', '&ordm;' => '&#186;', '&raquo;' => '&#187;', '&frac14;' => '&#188;', '&frac12;' => '&#189;', '&frac34;' => '&#190;', '&iquest;' => '&#191;', '&Agrave;' => '&#192;', '&Aacute;' => '&#193;', '&Acirc;' => '&#194;', '&Atilde;' => '&#195;', '&Auml;' => '&#196;', '&Aring;' => '&#197;', '&AElig;' => '&#198;', '&Ccedil;' => '&#199;', '&Egrave;' => '&#200;', '&Eacute;' => '&#201;', '&Ecirc;' => '&#202;', '&Euml;' => '&#203;', '&Igrave;' => '&#204;', '&Iacute;' => '&#205;', '&Icirc;' => '&#206;', '&Iuml;' => '&#207;', '&ETH;' => '&#208;', '&Ntilde;' => '&#209;', '&Ograve;' => '&#210;', '&Oacute;' => '&#211;', '&Ocirc;' => '&#212;', '&Otilde;' => '&#213;', '&Ouml;' => '&#214;', '&times;' => '&#215;', '&Oslash;' => '&#216;', '&Ugrave;' => '&#217;', '&Uacute;' => '&#218;', '&Ucirc;' => '&#219;', '&Uuml;' => '&#220;', '&Yacute;' => '&#221;', '&THORN;' => '&#222;', '&szlig;' => '&#223;', '&agrave;' => '&#224;', '&aacute;' => '&#225;', '&acirc;' => '&#226;', '&atilde;' => '&#227;', '&auml;' => '&#228;', '&aring;' => '&#229;', '&aelig;' => '&#230;', '&ccedil;' => '&#231;', '&egrave;' => '&#232;', '&eacute;' => '&#233;', '&ecirc;' => '&#234;', '&euml;' => '&#235;', '&igrave;' => '&#236;', '&iacute;' => '&#237;', '&icirc;' => '&#238;', '&iuml;' => '&#239;', '&eth;' => '&#240;', '&ntilde;' => '&#241;', '&ograve;' => '&#242;', '&oacute;' => '&#243;', '&ocirc;' => '&#244;', '&otilde;' => '&#245;', '&ouml;' => '&#246;', '&divide;' => '&#247;', '&oslash;' => '&#248;', '&ugrave;' => '&#249;', '&uacute;' => '&#250;', '&ucirc;' => '&#251;', '&uuml;' => '&#252;', '&yacute;' => '&#253;', '&thorn;' => '&#254;', '&yuml;' => '&#255;', '&OElig;' => '&#338;', '&oelig;' => '&#339;', '&Scaron;' => '&#352;', '&scaron;' => '&#353;', '&Yuml;' => '&#376;', '&fnof;' => '&#402;', '&circ;' => '&#710;', '&tilde;' => '&#732;', '&Alpha;' => '&#913;', '&Beta;' => '&#914;', '&Gamma;' => '&#915;', '&Delta;' => '&#916;', '&Epsilon;' => '&#917;', '&Zeta;' => '&#918;', '&Eta;' => '&#919;', '&Theta;' => '&#920;', '&Iota;' => '&#921;', '&Kappa;' => '&#922;', '&Lambda;' => '&#923;', '&Mu;' => '&#924;', '&Nu;' => '&#925;', '&Xi;' => '&#926;', '&Omicron;' => '&#927;', '&Pi;' => '&#928;', '&Rho;' => '&#929;', '&Sigma;' => '&#931;', '&Tau;' => '&#932;', '&Upsilon;' => '&#933;', '&Phi;' => '&#934;', '&Chi;' => '&#935;', '&Psi;' => '&#936;', '&Omega;' => '&#937;', '&alpha;' => '&#945;', '&beta;' => '&#946;', '&gamma;' => '&#947;', '&delta;' => '&#948;', '&epsilon;' => '&#949;', '&zeta;' => '&#950;', '&eta;' => '&#951;', '&theta;' => '&#952;', '&iota;' => '&#953;', '&kappa;' => '&#954;', '&lambda;' => '&#955;', '&mu;' => '&#956;', '&nu;' => '&#957;', '&xi;' => '&#958;', '&omicron;' => '&#959;', '&pi;' => '&#960;', '&rho;' => '&#961;', '&sigmaf;' => '&#962;', '&sigma;' => '&#963;', '&tau;' => '&#964;', '&upsilon;' => '&#965;', '&phi;' => '&#966;', '&chi;' => '&#967;', '&psi;' => '&#968;', '&omega;' => '&#969;', '&thetasym;' => '&#977;', '&upsih;' => '&#978;', '&piv;' => '&#982;', '&ensp;' => '&#8194;', '&emsp;' => '&#8195;', '&thinsp;' => '&#8201;', '&zwnj;' => '&#8204;', '&zwj;' => '&#8205;', '&lrm;' => '&#8206;', '&rlm;' => '&#8207;', '&ndash;' => '&#8211;', '&mdash;' => '&#8212;', '&lsquo;' => '&#8216;', '&rsquo;' => '&#8217;', '&sbquo;' => '&#8218;', '&ldquo;' => '&#8220;', '&rdquo;' => '&#8221;', '&bdquo;' => '&#8222;', '&dagger;' => '&#8224;', '&Dagger;' => '&#8225;', '&bull;' => '&#8226;', '&hellip;' => '&#8230;', '&permil;' => '&#8240;', '&prime;' => '&#8242;', '&Prime;' => '&#8243;', '&lsaquo;' => '&#8249;', '&rsaquo;' => '&#8250;', '&oline;' => '&#8254;', '&frasl;' => '&#8260;', '&euro;' => '&#8364;', '&image;' => '&#8465;', '&weierp;' => '&#8472;', '&real;' => '&#8476;', '&trade;' => '&#8482;', '&alefsym;' => '&#8501;', '&larr;' => '&#8592;', '&uarr;' => '&#8593;', '&rarr;' => '&#8594;', '&darr;' => '&#8595;', '&harr;' => '&#8596;', '&crarr;' => '&#8629;', '&lArr;' => '&#8656;', '&uArr;' => '&#8657;', '&rArr;' => '&#8658;', '&dArr;' => '&#8659;', '&hArr;' => '&#8660;', '&forall;' => '&#8704;', '&part;' => '&#8706;', '&exist;' => '&#8707;', '&empty;' => '&#8709;', '&nabla;' => '&#8711;', '&isin;' => '&#8712;', '&notin;' => '&#8713;', '&ni;' => '&#8715;', '&prod;' => '&#8719;', '&sum;' => '&#8721;', '&minus;' => '&#8722;', '&lowast;' => '&#8727;', '&radic;' => '&#8730;', '&prop;' => '&#8733;', '&infin;' => '&#8734;', '&ang;' => '&#8736;', '&and;' => '&#8743;', '&or;' => '&#8744;', '&cap;' => '&#8745;', '&cup;' => '&#8746;', '&int;' => '&#8747;', '&there4;' => '&#8756;', '&sim;' => '&#8764;', '&cong;' => '&#8773;', '&asymp;' => '&#8776;', '&ne;' => '&#8800;', '&equiv;' => '&#8801;', '&le;' => '&#8804;', '&ge;' => '&#8805;', '&sub;' => '&#8834;', '&sup;' => '&#8835;', '&nsub;' => '&#8836;', '&sube;' => '&#8838;', '&supe;' => '&#8839;', '&oplus;' => '&#8853;', '&otimes;' => '&#8855;', '&perp;' => '&#8869;', '&sdot;' => '&#8901;', '&lceil;' => '&#8968;', '&rceil;' => '&#8969;', '&lfloor;' => '&#8970;', '&rfloor;' => '&#8971;', '&lang;' => '&#9001;', '&rang;' => '&#9002;', '&loz;' => '&#9674;', '&spades;' => '&#9824;', '&clubs;' => '&#9827;', '&hearts;' => '&#9829;', '&diams;' => '&#9830;');
$translate_from = array();
$translate_to = array();
foreach ($trans_table as $entity => $code) {
  $translate_from[] = $entity;
  $translate_to[] = $code;
}
// the following characters that might be found in the database must be translated since converting directly to the character code causes a feed error
$chars_for_coding = array(chr(32) . chr(28) => '&ldquo;', chr(32) . chr(29) => '&rdquo;', chr(32) . chr(24) => '&lsquo;', chr(32) . chr(25) => '&rsquo;', chr(128) => '&euro;', chr(130) => '&bsquo;', chr(131) => '&fnof;', chr(132) => '&bdquo;', chr(133) => '&hellip;', chr(134) => '&dagger;', chr(135) => '&Dagger;', chr(136) => '&circ;', chr(137) => '&permil;', chr(138) => '&Scaron;', chr(139) => '&lsaquo;', chr(140) => '&OElig;', chr(145) => '&lsquo;', chr(146) => '&rsquo;', chr(147) => '&ldquo;', chr(148) => '&rdquo;', chr(149) => '&bull;', chr(150) => '&ndash;', chr(151) => '&mdash;', chr(152) => '&tilde;', chr(153) => '&trade;', chr(154) => '&scaron;', chr(155) => '&rsaquo;', chr(156) => '&oelig;', chr(159) => '&Yuml;');
$from_char = array();
$char_code = array();
foreach ($chars_for_coding as $char => $entity) {
  $from_char[] = $char;
  $char_code[] = $entity;
}

function create_text_description($str, $length = 9997) {
	// Strip HTML and Truncate to create a META description, Google doesn't care about META tags.
	$base_str = simple_strip_tags($str);
	$description = truncate_string($base_str, $length);
	if (strlen($base_str) > strlen($description)) {
		$description .= "...";
	}
	return tep_output_string($description);
}
function simple_strip_tags($str) {
// Strip HTML Tags function
  global $translate_from, $translate_to, $from_char, $char_code;
  $str = str_replace($from_char, $char_code, $str);
	$untagged = "";
	$skippingtag = false;
	for ($i = 0; $i < strlen($str); $i++) {
		if ($skippingtag) {
			if ($str[$i] == ">") {
				$untagged .= " ";
				$skippingtag = false;
			}
		} else {
			if ($str[$i] == "<") {
				$skippingtag = true;
			} elseif ($str[$i] <= " ") {
				$untagged .= " ";
		  } elseif ($str[$i] == '>') {
		    $untagged .= '&gt;';
		  } elseif ($str[$i] == '"') {
		    $untagged .= '&quot;';
		  } elseif ($str[$i] == '&') {
		    $x = $i + 1;
		    while (preg_match("/[A-Za-z0-9#]/", $str[$x])) $x++; // skip characters found in entity tags
		    if ($str[$x] == ';') { // found html entity
		      $untagged .= '&';
		    } else { // found ampersand
		      $untagged .= '&amp;';
		    }
		  } elseif ($str[$i] > '~') { // convert character to entity
		    $untagged .= '&#' . ord($str[$i]) . ';';
			} else {
				$untagged .= $str[$i];
			}
		}
	}
	$untagged = preg_replace("/[\n\r\t\s ]+/i", " ", $untagged); // remove multiple spaces, returns, tabs, etc.
	$untagged = trim($untagged); // remove space from beginning & end of string
	$untagged = str_replace($translate_from, $translate_to, $untagged);
	return $untagged;
}

function truncate_string($string, $length = 70)
// This function will truncate a string to a specified length.
{
  if (strlen($string) > $length) {
	$split = preg_split("/\n/", wordwrap($string, $length));
	return ($split[0]);
  }
  return ($string);
}
// Return all subcategory IDs
  function get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id, categories_status from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "' and categories_status = '1'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'process':
        $condition = tep_db_prepare_input($HTTP_POST_VARS['condition']);
        if (!in_array($condition, array('new', 'used'))) $condition = 'new';
        break;
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv=Content-Type content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel=stylesheet type=text/css href="includes/stylesheet.css">
    <script language=javascript src="includes/general.js"></script>
      <link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">
  </head>
<body>
<style>#boxes{display:none !important;}</style>
<!-- body //-->
<div id="wrapper">
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
        <!-- body_text //-->
        <?php if ($action == 'process') {
        ?>
        <td width=100% valign=top>
          <table border=0 width=100% cellspacing=0 cellpadding=2>
            <tr>
              <td width=100%>
                <table border=0 width=100% cellspacing=0 cellpadding=0>
                  <tr>
                    <td class=pageHeading><?php echo HEADING_TITLE2; ?></td>
                    <td class=pageHeading align=right><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width=100% class="main"><br>
              <b>ID &rarr; Name</b><br>
            <?php
$sql_data_array = array('feed_date_updated' => tep_db_prepare_input('now()'),
						'feed_updated' => tep_db_prepare_input('1'));
tep_db_perform(TABLE_PRODUCT_FEED_COUNT, $sql_data_array, 'update', "feed_updated = '1'");			
			
//create Google products listing feed
$xml_head = '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>' . "\n";
$xml_head .= '<title>' . simple_strip_tags(STORE_NAME) . "</title>\n";
$xml_head .= '<link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . "</link>\n";
$xml_head .= '<description>' . simple_strip_tags(STORE_DESCRIPTION) . "</description>\n";
$xml_head .= '<pubDate>' . date('r') . "</pubDate>\n";
$xml_foot = "</channel>
</rss>";
$sm = DIR_FS_CATALOG . 'google_product_feed.xml';
$fh = fopen($sm, 'w') or die(ERROR_PRODUCTS_FILE);
fwrite($fh, $xml_head);

//create Google Shopping Actions Feed
$xml_head_sa = '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>' . "\n";
$xml_head_sa .= '<title>' . simple_strip_tags(STORE_NAME) . "</title>\n";
$xml_head_sa .= '<link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . "</link>\n";
$xml_head_sa .= '<description>' . simple_strip_tags(STORE_DESCRIPTION) . "</description>\n";
$xml_head_sa .= '<pubDate>' . date('r') . "</pubDate>\n";
$xml_foot_sa = "</channel>
</rss>";
$sm_sa = DIR_FS_CATALOG . 'google_SA_product_feed.xml';
$fh_sa = fopen($sm_sa, 'w') or die(ERROR_PRODUCTS_FILE);
fwrite($fh_sa, $xml_head_sa);    
    
//create Google supplemental products listing feed
$xml_head_supp = '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>' . "\n";
$xml_head_supp .= '<title>' . simple_strip_tags(STORE_NAME) . "</title>\n";
$xml_head_supp .= '<link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . "</link>\n";
$xml_head_supp .= '<description>' . simple_strip_tags(STORE_DESCRIPTION) . "</description>\n";
$xml_head_supp .= '<pubDate>' . date('r') . "</pubDate>\n";
$xml_foot_supp = "</channel>
</rss>";
$sm_supp = DIR_FS_CATALOG . 'google_supp_product_feed.xml';
$fh_supp = fopen($sm_supp, 'w') or die(ERROR_PRODUCTS_FILE);
fwrite($fh_supp, $xml_head_supp);
    
$cnt = 0;
// check for existance of hidden categories
$hidehiddencatprods = (!defined('HIDE_HIDDEN_CAT_PRODS') || (HIDE_HIDDEN_CAT_PRODS == 'true'));
$hiddencats = array();
$check_query = tep_db_query("select * from " . TABLE_CATEGORIES); // look for category status variables
$check = tep_db_fetch_array($check_query);
if (isset($check['status_categ'])) { // skips if this is not set to avoid SQL error
  $hcquery = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where status_categ = 0");
  while ($cat = tep_db_fetch_array($hcquery)) {// build array of hidden categories and their subcategories
    $hiddencats[] = $cat['categories_id'];
    get_subcategories($hiddencats, $cat['categories_id']);
  }
} elseif (isset($check['categories_status'])) { // skips if this is not set to avoid SQL error
  $hcquery = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where categories_status = 0");
  while ($cat = tep_db_fetch_array($hcquery)) {// build array of hidden categories and their subcategories
    $hiddencats[] = $cat['categories_id'];
    get_subcategories($hiddencats, $cat['categories_id']);
  }
}
// begin Extra Product Fields
$epf = array();
$extra_fields = '';
$epf_query = tep_db_query("select * from " . TABLE_EPF . " e join " . TABLE_EPF_LABELS . " l where e.epf_status and (e.epf_id = l.epf_id) and (l.languages_id = " . (int)$languages_id . ") and l.epf_active_for_language order by epf_order");
while ($e = tep_db_fetch_array($epf_query)) {  // retrieve all active extra fields
  $field = 'extra_value';
  if ($e['epf_uses_value_list']) {
    if ($e['epf_multi_select']) {
      $field .= '_ms';
    } else {
      $field .= '_id';
    }
  }
  $field .= $e['epf_id'];
  $epf[] = array('id' => $e['epf_id'],
                 'label' => $e['epf_label'],
                 'uses_list' => $e['epf_uses_value_list'],
                 'multi_select' => $e['epf_multi_select'],
                 'display_type' => $e['epf_value_display_type'],
                 'show_chain' => $e['epf_show_parent_chain'],
                 'field' => $field);
}
foreach ($epf as $e) {
  $extra_fields .= ", pd." . $e['field'];
}
// end extra product fields
if ($hidehiddencatprods && !empty($hiddencats)) { // if products found only in hidden categories should be hidden and hidden categories exist
  $query = tep_db_query("select p.products_id, if(products_last_modified > products_date_added, products_last_modified, products_date_added) as rev_date, pd.products_name, pd.products_description, p.products_model, p.products_upc, p.products_quantity, p.products_image, p.products_price, m.manufacturers_name,  p.products_weight, p.products_date_available, p.products_type, p.gender, p.products_shipping_label, p.age_group, p.size, p.colour, p.goods " . $extra_fields . " from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id = p.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = 1 and p.products_quantity > '0' and pd.products_id = p.products_id and pd.language_id = " . (int)$languages_id . " and p.products_id = p2c.products_id and (not (p2c.categories_id in (" . implode(',', $hiddencats) . "))) group by p2c.products_id order by rev_date desc");
} else {
  $query = tep_db_query("select p.products_id, if(products_last_modified > products_date_added, products_last_modified, products_date_added) as rev_date, pd.products_name, pd.products_description, p.products_model, p.products_upc, p.products_quantity, p.products_image, p.products_price, m.manufacturers_name, p.products_weight, p.products_date_available, p.products_shipping_label, p.products_type, p.gender, p.age_group, p.size, p.colour, p.goods " . $extra_fields . " from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id = p.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = 1 and p.products_quantity > '0' and pd.products_id = p.products_id and pd.language_id = " . (int)$languages_id . " order by rev_date desc");
}
$query_first = $query;
$query_second = $query;    
while($product = tep_db_fetch_array($query)) { // list all in stock items that aren't hidden
  $output = "<item>\n";
  $output .= "<g:id>" . $product['products_id'] . "</g:id>\n";
  $output .= '<title>' . simple_strip_tags($product['products_name']) . "</title>\n";
  $url = tep_catalog_href_link("product_info.php", "products_id=" . $product['products_id']); // url to the product page
  $output .= '<link>'. $url . "</link>\n";
  $output .= '<g:price>' . $product['products_price'] . "</g:price>\n";
  $prod_desc = $product['products_description'];
// begin Extra Product Fields
$prod_desc .= "\n";
if ((PTYPE_ON_INFO_PAGE != 'off') && ($product['products_type'] > 0)) {
  $prod_desc .= '<p class="main"><b>' . TEXT_PTYPE . ' </b>';
  if (PTYPE_ON_INFO_PAGE == 'basic') { 
    $prod_desc .= epf_get_ptype_desc($product['products_type']);
  } else {
    $prod_desc .= epf_get_ptype_desc_extended($product['products_type']);
  }
  $prod_desc .= "</p>\n";
}
$extra = '';
foreach ($epf as $e) {
  $mt = ($e['uses_list'] && !$e['multi_select'] ? ($product[$e['field']] == 0) : !tep_not_null($product[$e['field']]));
  if (!$mt) { // only display if information is set for product
    $extra .= '<tr><td class=main><b>' . $e['label'] . ': </b>';
    if ($e['uses_list']) {
      if ($e['multi_select']) {
        $values = explode('|', trim($product[$e['field']], '|'));
        $listing = array();
        foreach ($values as $val) {
          $listing[] = tep_get_extra_field_list_value($val, $e['show_chain'], $e['display_type']);;
        }
        $extra .= implode(', ', $listing);
      } else {
        $extra .= tep_get_extra_field_list_value($product[$e['field']], $e['show_chain'], $e['display_type']);
      }
    } else {
      $extra .= $product[$e['field']];
    }
    $extra .= "</td></tr>\n";
  }
}
if (tep_not_null($extra)) $prod_desc .= '<table>' . $extra . "</table>\n";
// end Extra Product Fields
$output .= '<description>';
$attri='';
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $product['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {  
     	 $X=0;
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $product['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $product['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
		while ($products_options = tep_db_fetch_array($products_options_query)) {
		  $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
		  if ($products_options['options_values_price'] != '0') {
		    $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
			 if ($products_options['price_prefix'] == '+') {
			   $price_with_attribute = ($product_info['products_price'] + $products_options['options_values_price']);
			} else {
			   $price_with_attribute = ($product_info['products_price'] - $products_options['options_values_price']);
			   } //end if

 $attri .= $products_options_name['products_options_name'].'&nbsp;'.$products_options['products_options_values_name'].'&nbsp;'.$currencies->display_price($price_with_attribute, tep_get_tax_rate($product_info['products_tax_class_id']))."\n";

      $x++;	 
	  	} //end if
            } //end while
	}// end while
    }

  $output .= "\n".create_text_description($prod_desc) . "</description>\n";
  $output .= create_text_description($attri)."\n";
  $output .= '<g:condition>' . $condition . "</g:condition>\n";
  $output .= '<g:mpn>' . simple_strip_tags($product['products_model']) . "</g:mpn>\n";
  if (tep_not_null($product['products_image'])) {
    $output .= '<g:image_link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . rawurlencode($product['products_image']) . "</g:image_link>\n";
  }
  if ($product['products_type'] > 0) {
    $output .= '<g:google_product_category>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:google_product_category>\n";
    $output .= '<g:product_type>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:product_type>\n";
  }

  if (($product['products_date_available'] == '') || ($product['products_date_available'] == '0000-00-00 00:00:00') || (($product['products_date_available'] != '') && ($product['products_date_available'] != '0000-00-00 00:00:00') && ($product['products_date_available'] < date('Y-m-d H:i:s')))) {
    $output .= '<g:quantity>' . $product['products_quantity'] . "</g:quantity>\n";
    if ($product['products_quantity'] < 1) {
      $output .= "<g:availability>out of stock</g:availability>\n";
    } else {
      $output .= "<g:availability>in stock</g:availability>\n";
    }
  } else { // if date available is set and greater than today then not assume not in stock
    $output .= "<g:quantity>0</g:quantity>\n<g:availability>out of stock</g:availability>\n";
  }
  $output .= '<g:manufacturer>' . simple_strip_tags($product['manufacturers_name']) . "</g:manufacturer>\n";
  $output .= '<g:brand>' . simple_strip_tags($product['manufacturers_name']) . "</g:brand>\n";

  if (tep_not_null($product['gender'])) {
  	$output .= '<g:gender>' . simple_strip_tags($product['gender']) . "</g:gender>\n";
	}
  if (tep_not_null($product['age_group'])) {
  	$output .= '<g:age_group>' . simple_strip_tags($product['age_group']) . "</g:age_group>\n";
	}
  if (tep_not_null($product['colour'])) {
  	$output .= '<g:color>' . simple_strip_tags($product['colour']) . "</g:color>\n";
	}
  if (tep_not_null($product['size'])) {
  	$output .= '<g:size>' . simple_strip_tags($product['size']) . "</g:size>\n";
	}


 /* if (tep_not_null($product['products_image'])) {
  	$output .= '<g:goods>' . simple_strip_tags($product['goods']) . "</g:condition>\n";
	} */


  $output .= '<g:shipping_weight>' . $product['products_weight'] . " pounds</g:shipping_weight>\n"; // osCommerce 2 shipping modules assume the weight is in pounds
  $sale_query = tep_db_query("select * from " . TABLE_SPECIALS . " where products_id = '" . (int)$product['products_id'] . "' and status order by specials_new_products_price");
  if (tep_db_num_rows($sale_query) > 0) { // product is on sale
    $sale = tep_db_fetch_array($sale_query);
    $output .= '<g:sale_price>' . $sale['specials_new_products_price'] . "</g:sale_price>\n";
    if (($sale['begins_date'] != '') && ($sale['begins_date'] != '0000-00-00 00:00:00')) {
      $begin = $sale['begins_date'];
    } else { // if beginning date not available set it to now
      $begin = date('Y-m-d H:i:s');
    }
    $begin = str_replace(' ', 'T', $begin);
    if (($sale['expires_date'] != '') && ($sale['expires_date'] != '0000-00-00 00:00:00')) {
      $end = $sale['expires_date'];
    } else { // if no ending date set it to 10 years from now
      $end = (date('Y') + 10) . date('-m-d H:i:s');
    }
    $end = str_replace(' ', 'T', $end);
    $output .= '<g:sale_price_effective_date>' . $begin . 'Z/' . $end . "Z</g:sale_price_effective_date>\n";
  }
//$output .= '<g:expiration_date>2064-08-19T00:00:00</g:expiration_date>\n';
  $output .= "</item>\n";
  echo $product['products_id'] . ' &rarr; ' . $product['products_name'] .'<br>';
	fwrite($fh, $output);
	$cnt++;

/////  Start Shopping Actions Feed ////////    
   $attri='';
    $prod_desc = $product['products_description'];
    $products_attributes_query = tep_db_query("select * from products_attributes where products_id='" . $product['products_id'] . "' group by options_values_id");
    if (tep_db_num_rows($products_attributes_query) > 0) { 
        $output_att = "";
        while($products_attributes = tep_db_fetch_array($products_attributes_query)){
            
            $sku_count_query = tep_db_query ("select sum(options_quantity) AS total from products_attributes where options_values_id= '".$products_attributes['options_values_id']."' and products_id= '".$product['products_id']."'");
            $sku_count = tep_db_fetch_array($sku_count_query);
            
            $products_options2_query = tep_db_query("select * from products_attributes pa, products_options_values pov, products_options po where pa.products_id = '" . $product['products_id'] . "' and pa.products_attributes_id = '".$products_attributes['products_attributes_id']."' and pa.options_values_id = pov.products_options_values_id and pa.options_id = po.products_options_id");
		    $products_options2 = tep_db_fetch_array($products_options2_query);
            
            $check_for_size = strpos($products_options2['$products_options_name'], 'Size');
            
            if($check_for_size !== false){
               $attribute_size = strtok($products_options2['products_options_values_name'], " "); 
                
            }
            
            if($products_options2['products_options_name'] == 'Color'){
                $attribute_color = $products_options2['products_options_values_name'];        
            } else {
                $attribute_color = ltrim($products_options2['products_options_values_name'], "$attribute_size ");
            }
			
				if ($products_options2['options_values_msrp'] > '0') {
				$final_msrp = ($products_options2['options_values_msrp']);
				$final_price = ($products_options2['options_values_price']);
				
				} else { 
				$final_msrp = ($product['products_msrp'] + $products_options2['options_values_price']);
				$final_price = ($product['products_price'] + $products_options2['options_values_price']);
				}
            $check_for_images_query = tep_db_query("select variants_image_xl_1, variants_image_zoom_1 from variants_images where options_values_id = '".$products_options2['options_values_id']."' and parent_id = '".$product['products_id']."'");
            $check_for_images = tep_db_fetch_array($check_for_images_query);
            
            if($check_for_images['variants_image_xl_1'] <> '' && $sku_count['total'] > 0){
            
            $url_SA = tep_catalog_href_link('product_info.php', 'products_id='.$product['products_id'].'%26opt='.$products_options2['options_values_id'].''); // url to the product page
            
            $output_SA .= "<item>\n";
            $output_SA .= "<g:id>" . $products_attributes['products_attributes_id'] . "</g:id>\n";
            $output_SA .= "<g:item_group_id>".$product['products_id']."</g:item_group_id>\n";
            $output_SA .= "<title>" . simple_strip_tags($product['products_name']) . " -" . htmlspecialchars($products_options2['products_options_values_name']). "</title>\n";
            $output_SA.= '<link>'. $url_SA . "</link>\n";
            
            if($check_for_images['variants_image_zoom_1'] <> ''){
            $output_SA .= '<g:image_link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . rawurlencode($check_for_images['variants_image_zoom_1']) . "</g:image_link>\n";
            
            } elseif ($check_for_images['variants_image_xl_1'] <> ''){
            $output_SA .= '<g:image_link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . rawurlencode($check_for_images['variants_image_xl_1']) . "</g:image_link>\n";
            }
            
            if($final_msrp > $final_price){
               $output_SA .= "<g:price>" . $final_msrp . " USD</g:price>\n";
               $output_SA .= "<g:sale_price>" . $final_price . " USD</g:sale_price>\n";
            } else {
               $output_SA .= "<g:price>" . $final_price . " USD</g:price>\n";
            }             
            if($products_options2['google_size_name'] <> ''){
                $output_SA .="<g:size>".$products_options2['google_size_name']."</g:size>\n";
            } /*elseif($attribute_size <> ''){
                $output_SA .= "<g:size>".$attribute_size."</g:size>\n";
            } */
            if($products_options2['google_color_name'] <> ''){
                $output_SA .="<g:color>".$products_options2['google_color_name']."</g:color>\n";
            } /*elseif($attribute_color <> ''){
                $output_SA .= "<g:color>".$attribute_color."</g:color>\n";
            }*/
            $output_SA .= "<g:mpn>".$products_attributes['options_serial_no']."</g:mpn>\n";
            $output_SA .= "<g:quantity>".$sku_count['total']. "</g:quantity>\n";
            if ($sku_count['total'] < 1) {
                $output_SA .= "<g:availability>out of stock</g:availability>\n";
            } else {
                $output_SA.= "<g:availability>in stock</g:availability>\n";
            }
            $output_SA .= '<description>';
            $output_SA .= "\n".create_text_description($prod_desc) . "</description>\n";
            if ($product['products_type'] > 0) {
            $output_SA .= '<g:google_product_category>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:google_product_category>\n";
            $output_SA .= '<g:product_type>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:product_type>\n";
            }
            $output_SA .= "<g:gtin>". simple_strip_tags($products_attributes['options_upc']) . "</g:gtin>\n";    
            $output_SA .= '<g:manufacturer>' . simple_strip_tags($product['manufacturers_name']) . "</g:manufacturer>\n";
            $output_SA .= '<g:brand>' . simple_strip_tags($product['manufacturers_name']) . "</g:brand>\n";
            
          $output_SA .= "</item>\n";  
        } else {
            // Nothing    
        } 
        
        }
        
    } else {
  if($product['products_quantity'] > 0) {      

  $output_SA = "<item>\n";
  $output_SA .= "<g:id>" . $product['products_id'] . "</g:id>\n";
  $output_SA .= "<title>" . simple_strip_tags($product['products_name']) . "</title>\n";
  $output_SA.= '<link>'. $url . "</link>\n";
  $output_SA .= '<description>';
  $output_SA .= "\n".create_text_description($prod_desc) . "</description>\n";
  
  $output_SA .= "<g:condition>" . $condition . "</g:condition>\n";
  $output_SA .= "<g:mpn>" . simple_strip_tags($product['products_model']) . "</g:mpn>\n";
  $output_SA .= '<g:price>' . $product['products_price'] . "</g:price>\n";

  if (tep_not_null($product['products_image'])) {
    $output_SA .= '<g:image_link>' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . rawurlencode($product['products_image']) . "</g:image_link>\n";
  }
  if ($product['products_type'] > 0) {
    $output_SA .= '<g:google_product_category>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:google_product_category>\n";
    $output_SA .= '<g:product_type>' . str_replace('&#8594;', '&gt;', simple_strip_tags(epf_get_ptype_desc_extended($product['products_type']))) . "</g:product_type>\n";
  }

  if (($product['products_date_available'] == '') || ($product['products_date_available'] == '0000-00-00 00:00:00') || (($product['products_date_available'] != '') && ($product['products_date_available'] != '0000-00-00 00:00:00') && ($product['products_date_available'] < date('Y-m-d H:i:s')))) {
    $output .= '<g:quantity>' . $product['products_quantity'] . "</g:quantity>\n";
    if ($product['products_quantity'] < 1) {
      $output_SA .= "<g:availability>out of stock</g:availability>\n";
    } else {
      $output_SA.= "<g:availability>in stock</g:availability>\n";
    }
  } else { // if date available is set and greater than today then not assume not in stock
    $output .= "<g:quantity>0</g:quantity>\n<g:availability>out of stock</g:availability>\n";
  }
  $output_SA .= '<g:manufacturer>' . simple_strip_tags($product['manufacturers_name']) . "</g:manufacturer>\n";
  $output_SA .= '<g:brand>' . simple_strip_tags($product['manufacturers_name']) . "</g:brand>\n";

  if (tep_not_null($product['gender'])) {
  	$output_SA .= '<g:gender>' . simple_strip_tags($product['gender']) . "</g:gender>\n";
	}
  if (tep_not_null($product['age_group'])) {
  	$output_SA .= '<g:age_group>' . simple_strip_tags($product['age_group']) . "</g:age_group>\n";
	}
  if (tep_not_null($product['colour'])) {
  	$output_SA .= '<g:color>' . simple_strip_tags($product['colour']) . "</g:color>\n";
	}
  if (tep_not_null($product['size'])) {
  	$output_SA .= '<g:size>' . simple_strip_tags($product['size']) . "</g:size>\n";
	}


 /* if (tep_not_null($product['products_image'])) {
  	$output_SA .= '<g:goods>' . simple_strip_tags($product['goods']) . "</g:condition>\n";
	} */
if($product['products_shipping_label'] == 'oversized'){
        $output_SA .= "<g:shipping_label>".$product['products_shipping_label']."</g:shipping_label>\n";
    } else {}

  $output_SA .= '<g:shipping_weight>' . $product['products_weight'] . " pounds</g:shipping_weight>\n"; // osCommerce 2 shipping modules assume the weight is in pounds
  $sale_query = tep_db_query("select * from " . TABLE_SPECIALS . " where products_id = '" . (int)$product['products_id'] . "' and status order by specials_new_products_price");
  if (tep_db_num_rows($sale_query) > 0) { // product is on sale
    $sale = tep_db_fetch_array($sale_query);
    $output_SA .= '<g:sale_price>' . $sale['specials_new_products_price'] . "</g:sale_price>\n";
    if (($sale['begins_date'] != '') && ($sale['begins_date'] != '0000-00-00 00:00:00')) {
      $begin = $sale['begins_date'];
    } else { // if beginning date not available set it to now
      $begin = date('Y-m-d H:i:s');
    }
    $begin = str_replace(' ', 'T', $begin);
    if (($sale['expires_date'] != '') && ($sale['expires_date'] != '0000-00-00 00:00:00')) {
      $end = $sale['expires_date'];
    } else { // if no ending date set it to 10 years from now
      $end = (date('Y') + 10) . date('-m-d H:i:s');
    }
    $end = str_replace(' ', 'T', $end);
    $output_SA .= '<g:sale_price_effective_date>' . $begin . 'Z/' . $end . "Z</g:sale_price_effective_date>\n";
  }
//$output .= '<g:expiration_date>2064-08-19T00:00:00</g:expiration_date>\n';
  $output_SA .= "</item>\n";
} else {
      // Do Nothing
  }
    }
    fwrite($fh_sa, $output_SA); 
    
/////  End Shopping Actions Feed ////////    
    
    
    
    
    $supp_output = "<item>\n";
    $supp_output .= "<g:id>" . $product['products_id'] . "</g:id>\n";
    if($product['products_shipping_label'] == 'oversized'){
        $supp_output .= "<g:shipping_label>".$product['products_shipping_label']."</g:shipping_label>\n";
    } else {}
    $supp_output .= "<g:gtin>" . simple_strip_tags($product['products_upc']) . "</g:gtin>\n";
    $supp_output .= "</item>\n";
    
	fwrite($fh_supp, $supp_output);
}    
 
fwrite($fh, $xml_foot);
fclose($fh);
    
fwrite($fh_sa, $xml_foot);
fclose($fh_sa);    

fwrite($fh_supp, $xml_foot_supp);
fclose($fh_supp);    
    
echo '<br><b>' . $cnt . TEXT_TOTAL_PRODUCTS . '</b>';
?>
              </td>
            </tr>
          </table>
        </td>
        <?php
        } else { // not processing, show initial page
        ?>
    <div id="heading-block" style="margin-bottom:40px;">
        <h1 class="pageHeading" style="font-size: 2rem;">Submit Google Product Feed</h1>
    </div>
    
    <div class="column-12">
        <div class="row">
            <div class="column-12" style="margin-bottom:25px;">
                <strong style="color:#FF0000">STEP 1: </strong>Click
            <a href="javascript:(void 0)" class="splitPageLink" onClick="window.open('<?php echo $HTTP_SERVER . DIR_WS_CATALOG . $url;?>','google','resizable=0,statusbar=5,width=960,height=310,top=0,left=50,scrollbars=yes')">
                <strong>[HERE]</strong>
                </a>
                to create/update the sitemaps.
            </div>
            
            <div class="column-12" style="margin-bottom:25px;">
                <strong style="color:#FF0000">STEP 2: </strong>Click  
                <a href="javascript:(void 0)"  onClick="window.open('<?php echo $returned_url = GenerateSubmitURL();?>','google','resizable=1,statusbar=5,width=400,height=200,top=0,left=50,scrollbars=yes')" class="splitPageLink"><strong>[HERE]</strong></a>
                to PING google server to notify of updates
            </div>
        </div>
        <hr>
                   
                  <div class="Table">
                   <?php echo tep_draw_form('preparation', FILENAME_GOOGLE_FEED, 'selected_box=tools&action=process');
          $condition = array('new' => TEXT_NEW,
                        'used' => TEXT_USED); ?>
                        
                 <div class="Row"><strong style="color:#FF0000">STEP 3: </strong><?php echo TEXT_CHOOSE_CONDITION; ?></div><br>
                 <div class="Row"><?php
            foreach ($condition as $name => $text) {
              echo tep_draw_radio_field('condition', $name, ($name == 'new'));
              echo $text.'<br><br>'; } ?></div>
                   
                     
                      <p>
                          <button type="submit" name="submit" class="btn btn-primary btn-sm">Submit Feed</button>
                      </form>
              <p></p>
                 </div>
                 
       
        <?php } // end else on if action is process
        ?>
      </tr>
    </table>
   <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
   </div>
  </body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
