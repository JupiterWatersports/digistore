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

$breadcrumb->add('Sale', tep_href_link('sale'));
echo $doctype;

$original_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if($_GET['cat'] == 'kite'){
	$whatineed = '611';
	$cat = '?cat=kite';
}
elseif($_GET['cat'] == 'paddle'){
	$whatineed = '612';
	$cat = '?cat=paddle';
}
elseif($_GET['cat'] == 'wake'){
	$whatineed = '200';
	$cat = '?cat=wake';
}

if (isset($_GET['cat'])){
	$filterstring = "and p2c.master_category = '".$whatineed."'";
}
if (isset($_GET['cat'])){
$cat = 'cat='.$_GET['cat']. '&';
}

if (isset($_GET['category'])){
$categories_stringy = '';
$categories = $_GET['category'];
	foreach($categories as $categfilter => $categvalue){
	$categories_stringy .= 'category['.$categfilter.']='.$categvalue.'&';
	}
}

if (isset($_GET['brand'])){
$brands_stringy = '';
$brands = $_GET['brand'];
	foreach($brands as $filter => $value){
	$brands_stringy .= 'brand['.$filter.']='.$value.'&';
	}
}

if (isset($_GET['range'])){
$prices_stringy = '';
$pricess = $_GET['range'];
	foreach($pricess as $pfilter => $pvalue){
	$prices_stringy .= 'range['.$pfilter.']='.$pvalue.'&';
	}
}

if (isset($_GET['filter_id'])){
	$filter = 'filter_id='.$_GET['filter_id'].'&';
}

$url2 = $cat. $categories_stringy. $brands_stringy .$filter . $prices_stringy;

?>
<html lang="en">
<head>
<meta charset="utf-8" /> 
 <title>Sale</title>

 <meta name="description" content="Here you will find all our Cabrinha Kites from 2016, 2015, 2014, and 2013">
 <meta name="keywords" content="Cabrinha Kitesurfing Kiteboarding Kites">
 <meta http-equiv="Content-Language" content="en-US">
 <meta name="googlebot" content="all">
 <meta name="robots" content="noodp">
 <meta name="slurp" content="noydir">
 <meta name="robots" content="index, follow">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<style>
* {box-sizing:border-box;}
#product-listing-container:after{content:""; display:block; clear:both;}
@media (min-width: 1200px) {
	.container-fluid {
    width: 1200px !important;
}
}
.facet{font-size:13px; padding:3px 0px;}
.remove-filter{display:inline-block; margin-left:10px; background-color:#eaeaea; padding:5px; border-radius:4px;}
.clear-filter-container{padding-bottom:30px;}

/* Large Devices, Wide Screens */
@media only screen and (max-width : 1200px) {

}

/* Medium Devices, Desktops */
@media only screen and (max-width : 992px) {

}

/* Small Devices, Tablets */
@media only screen and (max-width : 768px) {

}

/* Extra Small Devices, Phones */ 
@media only screen and (max-width : 480px) {

}

/* Custom, iPhone Retina */ 
@media only screen and (max-width : 320px) {

}

</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<div class="col-sm-3" id="column_left" style="margin-left:0px;">
	<?php require(DIR_WS_INCLUDES . 'column_left-sale.php'); ?>
</div>	
<div class="grid_7 col-sm-9" id="content" style="padding-left:0px; padding-right:0px;">
   

<?php
  $products_new_array = array();
  if(isset($_GET['filter_id'])){
	/// sort by sort order ////
	if(($_GET['filter_id']) == '1'){
	$sortby = 'order by p.products_sort_order ASC, pd.products_name';	
	}
	
	/// sort by price low to high ////
	if(($_GET['filter_id']) == '2'){
	$sortby = 'order by p.products_price ASC';	
	}
	
	/// sort by price high to low ////
	if(($_GET['filter_id']) == '3'){
	$sortby = 'order by p.products_price DESC';	
	}
	
	/// sort by price best sellers ////
	if(($_GET['filter_id']) == '4'){
	$sortby = 'order by p.products_ordered DESC';
	}	
  } else {
	$sortby = 'order by p.products_sort_order ASC, pd.products_name';	
  }
  
  $brand_string ='';

  if(isset($_GET['brand']) && tep_not_null($_GET['brand'])){
	$brands = $_GET['brand'];
	$str = '';
		foreach($brands as $filter => $value){
	    $str .= $value."','";
		}
		$result = rtrim($str,"','");
	
		if (count($brands) > 1){
		$brand = "m.manufacturers_name IN ('".$result."')"; 
		} else {
		$brand = "m.manufacturers_name = '".$result."' "; 
		}

		$brand_string .= ' and ' .$brand;

   } 
   
   $category_string ='';

  if(isset($_GET['category']) && tep_not_null($_GET['category'])){
	$categoryy = $_GET['category'];
	$str5 = '';
		foreach($categoryy as $filter5 => $value5){
	    $str5 .= $filter5."','";
		}
		$result5 = rtrim($str5,"','");
	
		if (count($categoryy) > 1){
		$category = "p2c.categories_id IN ('".$result5."')"; 
		} else {
		$category = "p2c.categories_id = '".$result5."' "; 
		}

		$category_string .= ' and ' .$category;
   } 
   
   
   //////////////////////////----Price-----///////////////////////

$price_string ='';
$result2 = '';
$price_deux = '';
$i = 0;
	if (isset($HTTP_GET_VARS['range']) && tep_not_null($HTTP_GET_VARS['range'])) {
		$get_range2 = $_GET['range'];
		
		  foreach($get_range2 as $filter => $value){
			
			$result2 = str_replace(array('-', ' '),"'AND'", $value);
				$str= str_replace(array('-', ' '), ',', $value);
				$result23 .= $str.',';	  	  
			$i++;
				  
			 $arr1 = preg_split("/''/", $value);
			 if ($i > 1){
			 $price_deux .= " OR p.products_price BETWEEN '" .$result2."'";
			  
			 }
		 
		  }
		    
			 $result_almost = rtrim($result23,"','");
		     $result_maybe =  explode(',', $result_almost);
			  
				  if(count($get_range2) > 1){
					  
			  $price = "(p.products_price BETWEEN '" .$result_maybe[0]. "' AND '".$result_maybe[1]."'".$price_deux.")";
				  } else {
			  $price = "p.products_price BETWEEN '".$result2."'"; 
				  }
			  
			  
			  $price_string .= ' and '.$price;
		} 
		
//////////////////////////////////////////////////////////////////
  
   $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, p.products_date_added, p.old_products_price from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_description pd, products_to_categories p2c where p.products_status = '1' and p.products_id = pd.products_id and p.products_msrp > p.products_price and p.products_id = p2c.products_id ".$filterstring." ".$brand_string." ".$category_string." ".$price_string." ".$sortby."";
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

<div class="upper-filters col-xs-12 form-group">
     <div class="col-sm-4 numberprod"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
     <div class="col-xs-6" id="showfilter"><i class="fa fa-filter" aria-hidden="true" style="margin-right:10px;"></i><label class="control-label" style="display:inline-block; margin-bottom:0px;">Filter</label></div>
<?php // start optional Product List Filter


	if (isset($HTTP_GET_VARS['filter_id'])) {
	  echo tep_draw_hidden_field('filter_id', $HTTP_GET_VARS['filter_id']);
	}
	echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
		echo tep_hide_session_id();

echo '<div class="col-sm-4 col-xs-6 sortby" style="text-align:center; float:right;">' . '<form name="filter" id="filter-form">' . '<label class="control-label"  style="margin:5px 0px; display:inline-block; padding-right:10px;" id="mobile-control">Sort By</label>';

		  $options = array(
array('id' => '1', 'text' => 'Featured'),
array('id' => '2', 'text' => 'Price: Low to High'),
array('id' => '3', 'text' => 'Price: High to Low'),
array('id' => '4', 'text' => 'Best Sellers')
);

if (isset($_GET['cat'])){
echo '<input type="hidden" name="cat" value="'.$_GET['cat'].'">';
}

if(isset($_GET['brand']) && tep_not_null($_GET['brand'])){
	$brands = $_GET['brand'];
	$str = '';
		foreach($brands as $filter => $value){
	    echo '<input type="hidden" name="brand['.$filter.']" value="'.$value.'">';
		}

}

if(isset($_GET['category']) && tep_not_null($_GET['category'])){
	$categories = $_GET['category'];
		foreach($categories as $filter3 => $value3){
	    echo '<input type="hidden" name="category['.$filter3.']" value="'.$value3.'">';
		}

}

if(isset($_GET['range']) && tep_not_null($_GET['range'])){
	$rangee = $_GET['range'];
		foreach($rangee as $Rfilter3 => $Rvalue3){
	    echo '<input type="hidden" name="range['.$Rfilter3.']" value="'.$Rvalue3.'">';
		}

}

echo ''.tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()" class="form-control" style="width:150px; display:inline-block" ');
        echo tep_hide_session_id() . '</form></div>' . "\n";

?>
</div>


<?php if ((isset($_GET['brand'])) || (isset($_GET['range'])) || (isset($_GET['brand'])) || (isset($_GET['category']))){ 
echo '<div class="col-xs-12 clear-filter-container">';

$example = $_SERVER['QUERY_STRING'];

$url_complete = parse_url($original_url);

echo '<label><b>Filters:</b> </label>';

	// Remove category facets //
	if(isset($_GET['category'])){
		foreach($_GET['category'] as $Cfilter => $Cvalue){
		
		/* $qStr = $pString; //$_SERVER['QUERY_STRING']
		$key= 'category['.$Cfilter.']='.$Cvalue_fixed.'';
		parse_str($qStr,$ar);
		$back_category_url = http_build_query(array_diff_key($ar,array($key=>"")));	 */
		
		parse_str($url_complete['query'], $query); //grab the query part
		unset($query['category'][array_search($Cvalue, $query["category"])]);   //remove a parameter from query
		$dest_query = http_build_query($query); //rebuild new query
		$dest_url = $url_complete['path'] .'?'.$dest_query;	
			
		echo'<div class="remove-filter"><span class="" style="margin-bottom:10px;"><a href="'.$dest_url.'"><i class="fa fa-times" aria-hidden="true" style="margin-right:5px; font-size:16px; color:#D9534F;"></i></a>'.$Cvalue.'</span></div>';
		}
	}
	
	
	// Remove brand facets //
	if(isset($_GET['brand'])){
		foreach($_GET['brand'] as $Bfilter => $Bvalue){
			
		parse_str($url_complete['query'], $Bquery); //grab the query part
		unset($Bquery['brand'][array_search($Bvalue, $Bquery["brand"])]);   //remove a parameter from query
		$brand_query = http_build_query($Bquery); //rebuild new query
		$brand_trim_url = $url_complete['path'] .'?'.$brand_query;		
			
		  echo'<div class="remove-filter"><span class="" style="margin-bottom:10px;"><a href="'.$brand_trim_url.'"><i class="fa fa-times" aria-hidden="true" style="margin-right:5px; font-size:16px; color:#D9534F;"></i></a>'.$Bvalue.'</span></div>';
		}
	}
	
	// Remove range facets //
	if(isset($_GET['range'])){
		foreach($_GET['range'] as $Rfilter => $Rvalue){
		
		parse_str($url_complete['query'], $Rquery); //grab the query part
		unset($Rquery['range'][array_search($Rvalue, $Rquery["range"])]);   //remove a parameter from query
		$range_query = http_build_query($Rquery); //rebuild new query
		$range_trim_url = $url_complete['path'] .'?'.$range_query;
			
		  echo'<div class="remove-filter"><span class="" style="margin-bottom:10px;"><a href="'.$range_trim_url.'"><i class="fa fa-times" aria-hidden="true" style="margin-right:5px; font-size:16px; color:#D9534F;"></i></a>'.$Rvalue.'</span></div>';
		}
	}
	
	echo '<a href="sale.php?'.$cat.'" style="margin-left:15px; color:#2A9DD4">Remove All</a></div>';
	
}
?>



<div id="product-listing-container" class="col-xs-12">   
    

<div class="product-listing-block">
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
		
echo '<div class="product-block col-sm-4" style="text-align:center;">
<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">' .'<div class="col-xs-4 listingimg">'. tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</div>'.
'<div class="product-block-nameprice col-xs-8"><span class="product-block-name">' . $listing['products_name'],'</span><br />'.'<ul class="prices" style="margin-top:10px;">' .$p_price .'</ul>';
echo'<div class="mobile-only">';
if ($listing['products_price'] > 99){
echo '<span style="font-size:13px; margin-top:15px;" class="form-group">Free Shipping</span>';}
else{ echo'';}

if (($quantity['products_quantity'] < 5) && ($quantity['products_quantity'] > 0)) { echo '<span style="color:red; font-size:13px;">Only&nbsp;'.$quantity['products_quantity'] .'&nbsp;in stock</span>';}
else {};

echo '</div></div></a><br />'.'<div class="products-add-tocart" style="display:none;">';
if ($quantity['products_quantity'] > 0) {
echo '<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">'.
'<button class="cssButton buynow" style="border:none;"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . 'Buy Now' . '</button></form>'; }
else { echo'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '" class="cssButton buynow" style="border:none; display: inline-block; color: #fff; line-height: 24px; font-size:15px;">View Product</a>';}

 echo '</div></div>';


    $my_col ++;
    if ($my_col > 2) {
      $my_col = 0;
	echo '</div><div class="product-listing-block">';
 	$my_row ++;
      }
	}
echo '</div>'; 

    
//if no products
  } else {	
  
	if(($_GET['brand']) || ($_GET['range']) || ($_GET['category'])){
		
		$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		echo '<div class="col-xs-12"><h3>Sorry but there are no products to display with that combination</h3></br>
			<div><span>Try going back</span><a style="font-size:20px;" href='.$url.'><i class="fa fa-arrow-circle-left" aria-hidden="true" style="margin-left:5px;"></i></a>
 <span>and adjust your filters above.</span></div></div></div></div>';
	}else { 
	 ?>  <p><?php echo 'Sorry but there are no products to display'; ?></p></div></div>
<?php
  }
  }

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>


<div class="lower-filters col-xs-12 form-group">
   <div class="col-sm-6 numberprod"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
    <div class="col-sm-6"><?php echo ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, $url2); ?></div>
 </div>
 </div> 
 
 <script>
 
 



</script>       
<?php
  }

require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
