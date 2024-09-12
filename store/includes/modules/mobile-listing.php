<?php
// mobile-listing.php, http://www.css-oscommerce.com
  // plisttableBox builds listings in includes/modules/product_listing.php and table in shopping_cart.php.





$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<div class="clear"></div><div class="page"><div class="paginate"><?php echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div></div><div class="vspace"></div><div class="clear"></div> 
<?php
  }
  $list_box_contents = array();
  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL;
        $lc_class = 'm-headermodel';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS;
        $lc_class = 'm-headername';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = TABLE_HEADING_MANUFACTURER;
        $lc_class = 'm-headermanu';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE;
        $lc_class = 'm-headerprice';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_class = 'm-headerquantity';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_class = 'm-headerweight';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = TABLE_HEADING_IMAGE;
        $lc_class = 'm-headerimage';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = TABLE_HEADING_BUY_NOW;
        $lc_class  = 'm-headerbuynow';
        break;
    }
    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
    }   
  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;
      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="m product-listing"');
      } else {
        $list_box_contents[] = array('params' => 'class="m product-listing"');
      }
      $cur_row = sizeof($list_box_contents) - 1;
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        switch ($column_list[$col]) {        	
          case 'PRODUCT_LIST_MODEL':           
            $lc_class = 'm-model';
            $lc_text = $listing['products_model'];
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_class = 'm-name';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_MOBILE_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            } else {
              $lc_text = '<a href="' . tep_href_link(FILENAME_MOBILE_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_class = 'm-manu';
            $lc_text = '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_class = 'm-price';
            if (tep_not_null($listing['specials_new_products_price'])) {
              $lc_text = '<span class="m-specialpricestrike">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="m-specialprice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
            } else {
              $lc_text = $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) ;
            }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_class = 'm-quantity';
            $lc_text = $listing['products_quantity'];
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_class = 'm-weight';
            $lc_text = $listing['products_weight'];
            break;
          case 'PRODUCT_LIST_IMAGE':
            
            $lc_class = 'm-image';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {                                         
              $lc_text = tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], '60', '60');
            } else {
              $lc_text = tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], '60', '60');                                                                                                                         
            }            
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_class = 'm-buynow';
            $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'products_id')) . 'action=buy_now&products_id=' . $listing['products_id']) . '" class="button-small">buy</a>';   
            break;
        }			
        $list_box_contents[$cur_row][] = array('class' => $lc_class,                                          
                                               'text'  => $lc_text.'');
      }
      $list_box_contents[] = array('text' => '');
    }	
    new plistBox($list_box_contents);
  } else {
 			echo '<p>No Products</p>';
  }
if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<div class="clear"></div><div class="page"><div class="paginate"><?php echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div></div> <div class="vspace"></div><div class="clear"></div>  
<?php
  }
 }
?>