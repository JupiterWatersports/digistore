<?php
/*
  $Id: product_listing.php,v 1.44 2006/05/01 22:49:59 hpdl Exp $

   ========================================
   DIGISTORE ECOMMERCE OPEN SOURCE VER 3.0
   ========================================

   (c)2005-2006
   The Digistore Developing Team NZ
   http://www.digistore.co.nz

   SUPPORT & PROJECT UPDATES:
   http://www.digistore.co.nz/support/

   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com
   http://www.digistore.co.nz

   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.

   ========================================

*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
    </tr>
</table>
<?php
  }

  $list_box_contents = array();
  // generate columns
  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = '';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = '';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = '';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = '';
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_DESCRIPTION':
        $lc_text = '';
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = '';
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = 'B';
        $lc_align = 'center';
        break;
// BOF Product Sort
	  case 'PRODUCT_SORT_ORDER':
		$lc_text = TABLE_HEADING_PRODUCT_SORT;
		$lc_align = 'center';
		break;
// EOF Product Sort
	    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
	    $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
			// display products name & description
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<table width="230" border="0" cellspacing="0" cellpadding="0"><tr><td class="main"><B><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>'.'<br /></B>'.substr(strip_tags($listing['products_description']), 0, 125).'..&nbsp;<B><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '"><span class="moreLink">More</span></A></B><br /><HR></td></tr></table>';
            } else {
              $lc_text = '<table width="230" border="0" cellspacing="0" cellpadding="0"><tr><td class="main"><B><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;'.'</B><br />'.substr(strip_tags($listing['products_description']), 0, 125).'..&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '"><span class="moreLink">More</span></A><br /><HR></td></tr></table>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
			// display prices
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if ($listing['products_msrp'] > $listing['products_price']) {
              if (tep_not_null($listing['specials_new_products_price'])) {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice" style="bold">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
              } else {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($listing['products_msrp'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="pricenow">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;</span>';
              }
            } else {
              if (tep_not_null($listing['specials_new_products_price'])) {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
              } else {
                $lc_text = '&nbsp;<span class="regPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;</span>';
              }            
            }
            break;
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
          case 'PRODUCT_LIST_DESCRIPTION':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_description'] . '&nbsp;';
            break;
			
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
            }
            break;
 // Begin Buy Now button mod
          // BOF Bundled Products
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
            $StockChecker1 = tep_get_products_stock($listing['products_id']);
	          if (( $StockChecker1 <> 0) || (STOCK_ALLOW_CHECKOUT =='true')) {
                $lc_text = '<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '">' . tep_image_submit('button_buy_now.gif', TEXT_BUY . $listing_values['products_name'] . TEXT_NOW) . '</form> ';
	          }else{    
                $lc_text = tep_image_button('button_out_of_stock.gif', IMAGE_BUTTON_OUT_OF_STOCK) . '&nbsp;';
	          }
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

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="productListing-data"',
                                               'text'  => $lc_text );
      }
    }

    new productListingBox($list_box_contents);
  } else {
    /*$list_box_contents = array();

    $list_box_contents[0] = array('params' => '');
    $list_box_contents[0][] = array('params' => '',
                                   'text' => tep_header(TEXT_NO_PRODUCTS));

    new productListingBox($list_box_contents); */

  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
