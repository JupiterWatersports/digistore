<?php
/*
  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<?php
if (isset($pw_mispell)){ //added for search enhancements mod
?>
<div id="product-listing-container">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr><tr><td><?php echo $pw_string; ?></td></tr></tr>
</table>
<?php
 } //end added search enhancements mod
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
// fix counted products

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '2') ) ) {
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="result">
  <tr>
     <td class="main"><b><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></b></td>
    <td class="main" style="text-align:right;"><b><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></b></td>
  </tr>
</table>

<div id="product-listing-block">
<?php
  }

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

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

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
            $lc_text = '&nbsp;<a style="productTitleSmall" href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if (tep_not_null($listing['specials_new_products_price'])) {
           $p_price =  '<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp; <span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
            } else {
           $p_price = '<span class="productSpecialPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>'; }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_quantity'] . '&nbsp;';
            break;
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
        }
		
$product_query = tep_db_query("select products_description, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$listing['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);
	  

/*		$list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => '',
                                               'text'  => $lc_text); */

 }
		
		
echo '<div id="product-block" align="center" class="smallText">
<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '"><img id="img-borders" ' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</img><br />'.
'<div id="product-block-nameprice">' . $listing['products_name'],'<br />'. $p_price  .' </div></a><br />'.'<div id="products-add-tocart">'.'<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">'.
'<button class="mobile-cssbutton addtocart" style="border:none;"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . 'Buy Now' . '</button></form> '.'</div></div>';

    $my_col ++;
    if ($my_col > 2) {
      $my_col = 0;
	echo '</tr><tr>';
 	$my_row ++;
      }
	}
echo '</tr><br />'; 

//    new productListingBox($list_box_contents);
 } else {  ?>

<br style="line-height:11px;">

<?php  /*  echo tep_draw_infoBox_top();  */ ?>


				<table cellpadding="0" cellspacing="0" class="product">
					<tr><tr><td class="padd_22"><?php echo TEXT_NO_PRODUCTS ?></td></tr></tr>
				</table>


<br style="line-height:1px;">
<br style="line-height:10px;">					
<?php
	
 /*  echo tep_draw_infoBox_bottom();  */
			
			
  }
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="result">
  <tr>
    <td class="main"><b><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></b></td>
    <td class="main" style="text-align:right;"><b><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></b></td>
  </tr>
</table>

<?php
  }
?>
