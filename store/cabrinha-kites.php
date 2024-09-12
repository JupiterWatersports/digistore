<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

$breadcrumb->add('Cabrinha Kites', tep_href_link('cabrinha-kites'));
echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Cabrinha Kites</title>

 <meta name="description" content="Here you will find all our Cabrinha Kites from 2016, 2015, 2014, and 2013">
 <meta name="keywords" content="Cabrinha Kitesurfing Kiteboarding Kites">
 <meta http-equiv="Content-Language" content="en-US">
 <meta name="googlebot" content="all">
 <meta name="robots" content="noodp">
 <meta name="slurp" content="noydir">
 <meta name="robots" content="index, follow">
 <link rel="canonical" href="http://www.jupiterkiteboarding.com/store/cabrinha-kites">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<style>
.cab-logo{width:100%;}
* {box-sizing:border-box;}
#product-listing-container:after{content:""; display:block; clear:both;}
</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<img class="cab-logo" src="images/cabrinha-logo-lg.jpg">
<div class="grid_7" id="content">
   

<?php
  $products_new_array = array();
  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc," . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and p.products_id = ptc.products_id and ptc.categories_id='45' and m.manufacturers_id=10 and pd.language_id = '" . (int)$languages_id . "' order by p.products_sort_order ASC, pd.products_name";
  $products_new_split = new splitPageResults2($products_new_query_raw, MAX_DISPLAY_SEARCH_RESULTS);

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?> 
<!--products count-->
<div class="grid_4 smalltext alpha"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
<!--page count/links-->
<div class="grid_4 right-align smalltext omega"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
<div class="clear"></div>        
<?php
  }
?>
<div class="clear"></div>
<div id="product-listing-container" class="col-xs-12">   
    

<div id="product-listing-block">
<?php
$info_box_contents = array();
  $list_box_contents = array();
$my_row = 0;
$my_col = 0;
echo '';

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = TABLE_HEADING_MANUFACTURER;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = TABLE_HEADING_IMAGE;
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = TABLE_HEADING_BUY_NOW;
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($products_new_split->number_of_rows > 0) {
    $rows = 0;
   $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($listing = tep_db_fetch_array($products_new_query)) {
      $rows++;
      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
           case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($_GET['manufacturers_id'])) {
            $p_name = $lc_text = '<a style="productTitleSmall" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
 
            /*** Begin Header Tags SEO ***/
            $lc_add = '';
            $hts_listing_query = tep_db_query("select products_head_listing_text, products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = " . (int)$listing['products_id'] . " and language_id = " . (int)$languages_id);
            if (tep_db_num_rows($hts_listing_query) > 0) {              
                $hts_listing = tep_db_fetch_array($hts_listing_query);
                if (tep_not_null($hts_listing['products_head_listing_text'])) {
                    $lc_add .= '<div class="hts_listing_text">' . $hts_listing['products_head_listing_text'] . '...<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . (int)$listing['products_id']) . '"><span style="color:red;">' . TEXT_SEE_MORE . '</span></a></div>';
                } else if (HEADER_TAGS_ENABLE_AUTOFILL_LISTING_TEXT == 'true') {
                    $text = sprintf("%s...%s", substr(stripslashes(strip_tags($hts_listing['products_description'])), 0, 100), '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . (int)$listing['products_id']) . '"><span style="color:red;">' . TEXT_SEE_MORE . '</span></a>');
                    $lc_add .= '<div class="hts_listing_text">' . $text . '</div>';
                }
            }  
            } else {
            $p_name  = '<a style="productTitleSmall" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
		// display prices
 /*         case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if ($listing['products_msrp'] > $listing['products_price']) {
              if (tep_not_null($listing['specials_new_products_price'])) {
               $p_price  = '<li class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="productSpecialPrice" style="bold">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>
			   <li class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              } else {
                 $p_price = '<li class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="pricenow">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              }
            } else {
              if (tep_not_null($listing['specials_new_products_price'])) {
                  $p_price = '<li class="oldPrice">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              } else {
                 $p_price = '<li class="regPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              }            
            }
            break; */
          // BOF Bundled Products
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $StockChecker = tep_get_products_stock($listing['products_id']);	
            $lc_text = TEXT_QUANTITY .'&nbsp;(' . $StockChecker	.')';
            break;
          // EOF Bundled Products
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;
			
      case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id'])) {
              $p_pic = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], '100', '100') . '</a>';
            } else {
              $p_pic = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], '100', '100') . '</a>';
            }
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
            $lc_text = '<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . tep_image_submit('button_buy_now.gif', TEXT_BUY . $listing_values['products_name'] . TEXT_NOW) . '</form> ';
            break;
          // EOF Bundled Products
// End Buy Now button mod
// BOF Product Sort
		  case 'PRODUCT_SORT_ORDER';
            $lc_align = 'center';
            $lc_text = '&nbsp;' . $listing['products_sort_order'] . '&nbsp;';
            break;
// EOF Product Sort
      }

  

/*		$list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => '',
                                               'text'  => $lc_text); */

 }
            $lc_align = 'right';
            if ($listing['products_msrp'] > $listing['products_price']) {
              if (tep_not_null($listing['specials_new_products_price'])) {
               $p_price  = '<li class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="productSpecialPrice" style="bold">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>
			   <li class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              } else {
                 $p_price = '<li class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="pricenow">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              }
            } else {
              if (tep_not_null($listing['specials_new_products_price'])) {
                  $p_price = '<li class="oldPrice">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              } else {
                 $p_price = '<li class="regPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              }            
            }		
		
echo '<div id="product-block" class="col-sm-4" style="text-align:center;">
<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">' .'<div class="col-xs-4 listingimg">'. tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</img></div>'.
'<div id="product-block-nameprice" class="col-xs-8"><span style="font-size:15px; font-weight:700;" class="product-block-name">' . $listing['products_name'],'</span><br />'.'<ul class="prices" style="margin-top:10px;">' .$p_price .'</ul>';
echo'<div class="mobile-only">';
if ($listing['products_price'] > 99){
echo '<span style="font-size:13px; margin-top:15px;" class="form-group">Free Shipping</span>';}
else{ echo'';}

if (($quantity['products_quantity'] < 4) && ($quantity['products_quantity'] > 0)) { echo '<span style="color:red; font-size:13px;">Only&nbsp;'.$quantity['products_quantity'] .'&nbsp;in stock</span>';}
else {};

echo '</div></div></a><br />'.'<div id="products-add-tocart" style="display:none;">';
if ($quantity['products_quantity'] > 0) {
echo '<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">'.
'<button class="cssButton buynow" style="border:none;"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . 'Buy Now' . '</button></form>'; }
else { echo'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '" class="cssButton buynow" style="border:none; display: inline-block; color: #fff; line-height: 24px; font-size:15px;">View Product</a>';}

 echo '</div></div>';


    $my_col ++;
    if ($my_col > 2) {
      $my_col = 0;
	echo '</div><div id="product-listing-block">';
 	$my_row ++;
      }
	}
echo '</div>'; 

    
//if no products
  } else {
?>
	<p><?php echo TEXT_NO_NEW_PRODUCTS; ?></p>
<?php
  }

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>


<div class="lower-filters col-xs-12 form-group">
   <div class="col-sm-6 numberprod"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
    <div class="col-sm-6"><?php echo ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
 </div>
 </div>        
<?php
  }

require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
