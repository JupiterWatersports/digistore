<?php
/*
  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$original_url = "$_SERVER[REQUEST_URI]";
$url_complete = parse_url($original_url);
$cPath_new = tep_get_path($current_category_id);
$url_needed_part = basename($original_url);
if (strpos($url_needed_part, '?') !== false) {
$url_needed = substr($url_needed_part, 0, strpos($url_needed_part, '?'));
} else {
	$url_needed = $url_needed_part;
}

?>
<style>
* {box-sizing:border-box;}
@media (min-width: 768px){
.upper-filters .col-sm-4:last-child {
    float: right;}
    .bread2{
            display:none;
        }
}



#product-listing-container:after{content:""; display:block; clear:both;}
@media (min-width: 1200px) {
	.container-fluid {
    
}
    .upper-links li {flex:0;}
}
.facet{font-size:14px; padding:3px 0px;}
.remove-filter{display:inline-block; margin-left:10px; background-color:#eaeaea; padding:5px; border-radius:4px;}
.clear-filter-container{padding-bottom:30px;}
	#sortBY{display:inline-block;}



/* Small Devices, Tablets */
@media only screen and (max-width : 768px) {
	#sortBY{display:none ;}

}
    .hilight a{color:#09f;}    
</style>

<?php

if (isset($pw_mispell)){ //added for search enhancements mod
?>

<?php
 } //end added search enhancements mod
  $listing_split = new splitPageResults2($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
// fix counted products

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '2') ) ) {

	  
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
	$filter_stringy = 'filter_id='.$_GET['filter_id'].'&';
}
	  
$url2 = $url_needed.'?' .$cat. $categories_stringy. $brands_stringy .$filter_stringy . $prices_stringy;

// Get Description //      
$get_description_query = tep_db_query("select categories_htc_description as description, learn_more from categories_description where categories_id = '$desired_cID'");
$get_description = tep_db_fetch_array($get_description_query);
    if($get_description['learn_more'] == '1'){  
    $learn_more = '<a style="margin-left:10px; color:#0540e5; text-decoration:underline;">Learn More</a>';}
      else {$learn_more = '';}
    if ($get_description['description'] <> ''){  
    echo '<div class="col-xs-12" style="padding: 15px; font-weight: 600; color: #040fbe; color:#038f78; font-size: 1rem; margin-bottom:5px;"><span>'.$get_description['description'].'</span>'.$learn_more.'</div>';
    } else {}
?>
<div class="upper-filters col-xs-12 form-group">
     <div class="col-sm-4 numberprod"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
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

echo ''.tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()" class="form-control" style="width:150px;" id="sortBY" ');
        echo tep_hide_session_id() . '</form></div>' . "\n";

?>
</div>

<?php if ((isset($_GET['brand'])) || (isset($_GET['range'])) || (isset($_GET['brand'])) || (isset($_GET['category']))){ 
echo '<div class="col-xs-12 clear-filter-container">';

$example = $_SERVER['QUERY_STRING'];

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
			
	// Remove gender facets //
	if(isset($_GET['gender'])){
		foreach($_GET['gender'] as $Rfilter => $Rvalue){
		if($Rvalue == 'male'){
			$genvalue = 'Men\'s';	
		}	
		elseif($Rvalue == 'female'){
			$genvalue = 'Women\'s';
		}	
		
		parse_str($url_complete['query'], $Rquery); //grab the query part
		unset($Rquery['gender'][array_search($Rvalue, $Rquery["gender"])]);   //remove a parameter from query
		$gender_query = http_build_query($Rquery); //rebuild new query
		$gender_trim_url = $url_complete['path'] .'?'.$gender_query;
			
		  echo'<div class="remove-filter"><span class="" style="margin-bottom:10px;"><a href="'.$gender_trim_url.'"><i class="fa fa-times" aria-hidden="true" style="margin-right:5px; font-size:16px; color:#D9534F;"></i></a>'.$genvalue.'</span></div>';
		}
	}		
	
	echo '<a href="'.$url_complete['path'].'" style="margin-left:15px; color:#2A9DD4">Remove All</a></div>';
	
}
?>

<div id="product-listing-container" class="col-xs-12">
<div id="product-listing description">
<?php  $catStr_query = tep_db_query("select categories_htc_title_tag as htc_title_tag, categories_htc_description, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" .  $categories['categories_id'] . "' and language_id = '" . (int)$languages_id . "'");
	while ($catStr = tep_db_fetch_array($catStr_query)) {
           echo '<div style="vertical-align:top; width:100%; float:left; text-align:left; padding-top:20px;" id="product-category-description">'.$catStr['categories_htc_description'].'<br /><br /></div>';
          }?></div>

<?php
  }

$info_box_contents = array();
  $list_box_contents = array();
$my_row = 0;
$my_col = 0;
echo '';

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    


    
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
		
      $rows++;
      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
       
		  if ($listing['products_msrp'] > $listing['products_price']) {
            
                 $p_price = '<li class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li><li class="pricenow">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              
            } else {
              
                 $p_price = '<li class="regPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</li>';
              }            
            
      

     $product_query = tep_db_query("select products_description, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$listing['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);
	  
	  

/*		$list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => '',
                                               'text'  => $lc_text); */

 }
	  $quantity_query = tep_db_query("select products_quantity from products  WHERE products_id= '" . (int)$listing['products_id'] . "'");
	  $quantity = tep_db_fetch_array($quantity_query);
	  
	  $check_hd_image_query = tep_db_query("select products_image_hd from products where products_id = '".$listing['products_id']."'");
	  $check_hd_image = tep_db_fetch_array($check_hd_image_query);
	  
	  if($check_hd_image['products_image_hd'] <> ''){
	  $srcset_image = 'srcset="'.DIR_WS_IMAGES . $listing['products_image'].' 1x, '.DIR_WS_IMAGES . $listing['products_image_hd'].' 2x"';	}
	  else { $srcset_image = '';
	  }
		
echo '<div id="product-block" class="col-md-3 col-sm-4" style="display:inline-block; text-align:center;">
		<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id'],'SSL') . '">' .'<div class="listingimg">
		<img src="'.DIR_WS_IMAGES . $listing['products_image'].'" '.$srcset_image.' width="'.SMALL_IMAGE_WIDTH.'" height="'.SMALL_IMAGE_HEIGHT.'" alt="'.$listing['products_name'].'"></img></div>'.
'<div id="product-block-nameprice" class="col-xs-8"><span style="font-weight:700;" class="product-block-name">' . $listing['products_name'],'</span><br />'.'<ul class="prices" style="margin-top:10px;">' .$p_price .'</ul>';
echo'<div class="mobile-only">';
if ($listing['products_price'] > 99){
echo '<span style="font-size:13px; margin-top:15px;" class="form-group">Free Shipping</span>';}
else{ echo'';}

if (($quantity['products_quantity'] < 4) && ($quantity['products_quantity'] > 0)) { echo '<span style="color:red; font-size:13px;">Only&nbsp;'.$quantity['products_quantity'] .'&nbsp;in stock</span>';}
else {};

echo '</div></div></a><br />'.'<div id="products-add-tocart" style="display:none;">';
if ($quantity['products_quantity'] > 0) {
echo '<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'SSL') . '">'.
'<button class="cssButton buynow" style="border:none;"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . 'Buy Now' . '</button></form>'; }
else { echo'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '" class="cssButton buynow" style="border:none; display: inline-block; color: #fff; line-height: 24px; font-size:15px;">View Product</a>';}

 echo '</div></div>';

    $my_col ++;
    if ($my_col > 2) {
      $my_col = 0;
	echo '';
 	$my_row ++;
      }
	}
echo '</div>'; 

//    new productListingBox($list_box_contents);
 } else {  ?>

<br style="line-height:11px;">

<?php  /*  echo tep_draw_infoBox_top();  */ ?>


				<table cellpadding="0" cellspacing="0" class="product">
					<tr><tr><td class="padd_22"><?php echo 'No Products' ?></td></tr></tr>
				</table>


<br style="line-height:1px;">
<br style="line-height:10px;">					
<?php
	
 /*  echo tep_draw_infoBox_bottom();  */
			
			
  }
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
</div>
<div class="lower-filters col-xs-12 form-group">
    <div class="col-sm-6 numberprod"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
   <div class="col-sm-6"><?php echo ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, $url2); ?></div>
</div>

<?php
  }
?>
