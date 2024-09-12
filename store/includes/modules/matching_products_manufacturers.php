<?php
/*
  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<style>
.container_12{width:1100px;}
#column_left{display:block;}
</style>
<head>
<title> Soemthing more specific</title></head>
<?php
if (isset($pw_mispell)){ //added for search enhancements mod
?>
<?php 
if ($javacart == 100) { ?>
<script type="text/javascript">
//var div = document.getElementById('shoppingcart-contents');
document.getElementById("shoppingcart-contents").style.display = "";
function doSomething() {
   document.getElementById("shoppingcart-contents").style.display = "none"
}
setTimeout(doSomething, 3000);
</script>
<?php } ?>

<?php
 } //end added search enhancements mod
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
// fix counted products

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '2') ) ) {
	 
?>

<div class="upper-filters">
     <div class="col-sm-4"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
<?php // start optional Product List Filter

if (PRODUCT_LIST_FILTER > 0) {
      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '<div class="right col-sm-3" style="text-align:center;">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';

        if (isset($HTTP_GET_VARS['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);

          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }

        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {

          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);

        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo tep_hide_session_id() . '</form></div>' . "\n";
      }
    }

// end optional Product List Filter  ?>
     <div class="col-sm-4"><?php echo  ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
</div>


<div id="product-listing-container">
<div id="product-listing description">
<?php  $catStr_query = tep_db_query("select categories_htc_title_tag as htc_title_tag, categories_htc_description, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" .  $categories['categories_id'] . "' and language_id = '" . (int)$languages_id . "'");
	while ($catStr = tep_db_fetch_array($catStr_query)) {
           echo '<div style="vertical-align:top; width:100%; float:left; text-align:left; padding-top:20px;" id="product-category-description">'.$catStr['categories_htc_description'].'<br /><br /></div>';
          }?></div>
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
          case 'PRODUCT_LIST_PRICE':
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

     $product_query = tep_db_query("select products_description, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$listing['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);
	  

/*		$list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => '',
                                               'text'  => $lc_text); */

 }
		
		
echo '<div id="product-block" align="center" class="smallText">
<div><a class="product-block-image" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '"><img id="img-borders" ' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</img></a></div>'.
'<h3><a>'. $listing['products_name'].'</a></h3>'.'<div id="product-block-price">'.'<ul class="prices">' .$p_price .'</ul>'.'</div></a><br />'.'<div id="products-add-tocart">'.'<form name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">'.
'<button class="cssButton buynow" style="border:none;"><input type="hidden" name="products_id" value="' . $listing['products_id'] . '" >' . 'Buy Now' . '</button></form> '.'</div></div>';

    $my_col ++;
    if ($my_col > 2) {
      $my_col = 0;
	echo '</div><div id="product-listing-block">';
 	$my_row ++;
      }
	}
echo '</div><br />'; 

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
<div class="lower-filters">
   <div class="col-sm-6"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
    <div class="col-sm-6"><?php echo  ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
</div>

<?php
  }
?>
