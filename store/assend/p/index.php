<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on CSS Grid960 v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');
// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
echo $doctype;
?>
<html lang="en">
<head>
<meta charset="UTF-8">


<?php if ((!$_GET['oID'] == '') || (!$_GET['cName'] == '')) {$idorder = $_GET['oID']; $cname = $_GET['cName'];} 



/*** Begin Header Tags SEO ***/
if ((preg_match("/index.php/", $PHP_SELF)) && (isset($HTTP_GET_VARS['cPath']))) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  
<?php if(is_null($title))  $title='Welcome to Jupiter Kiteboarding';
?>
  <title>VS #<?php echo $idorder;?></title>
 <meta name="Description" content="Gear, rental, repairs and instruction for kiteboarding, paddleboarding and wakeboarding. Call for kiteboarding lessons, paddleboarding rentals or to purchase gear from the leading manufacturers. One of our watermen can answer any of your questions!">
 <meta name="Keywords" content="kiteboarding,kitesurfing,paddleboarding,paddlesurfing,wakeboarding,kite,kites,kiteboard,kiteboarding lessons,paddleboarding rentals,paddleboarding lessons,cabrinha kiteboarding,north kiteboarding,slingshot kiteboarding,wainman hawaii,twin-tip,used kites">
<?php
}
/*** End Header Tags SEO ***/
?>
<style>
	#product-category-image img{height:auto !important;width:100% !important; max-width: 200px;}
</style>


<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php');
	
	$manufacturers_count_index_query = tep_db_query ("select count(m.manufacturers_name) as total from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $current_category_id . "' GROUP BY m.manufacturers_name order by m.manufacturers_name ASC ");
	
	$manufacturers_count_index = tep_db_fetch_array($manufacturers_count_index_query);
	if($manufacturers_count_index['total'] > 0){
		$filters_style = 'class="col-sm-9" style="padding-left:0px; padding-right:0px;"';
		$column_left = "";
	} else {
		$filters_style = '';
		$column_left = 'display:none !important;"';
	}
	?>
<div class="col-sm-3" id="column_left" style="margin-left:0px; <?php echo $column_left; ?>">
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
</div>
<?php
 
    /*** Begin Header Tags SEO ***/
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    /*** end Header Tags SEO ***/
     $category = tep_db_fetch_array($category_query);

 ?>
<span class="leftfloat"><h1><?php echo HEADING_TITLE; ?></h1></span>
<span class="rightfloat">
<?php 
//image opposite title
// echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); 
?>
</span>
<div class="clear spacer"></div> 
<?php
echo tep_draw_separator('pixel_trans.gif', '100%', '10'); 
    
// check to see if there are deeper categories within the current category
   

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>
  <div class="clear"></div>
 <!--- BEGIN Header Tags SEO Social Bookmarks -->
<?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') 
 include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
?>
<!--- END Header Tags SEO Social Bookmarks -->  
<?php

// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,

                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,

                         //'PRODUCT_LIST_DESCRIPTION' => PRODUCT_LIST_DESCRIPTION,

// BOF Product Sort
						 'PRODUCT_SORT_ORDER' => PRODUCT_SORT_ORDER); 
// EOF Product Sort


    
echo '<style>.container-fluid{}</style>';?>
<div id="content" <?php echo $filters_style; ?>>
<style>.grid_2{display:none;}</style>
<?php


  
		    // check to see if there are deeper categories within the current category		  	
		  	$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $cat_to_search . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name LIMIT 8");

			    if (tep_db_num_rows($categories_query) > 0 ) {

				    $rows = 0;
					while ($categories = tep_db_fetch_array($categories_query)) {

					    $rows++;

						$cPath_new = tep_get_path($categories['categories_id']);

						$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

						echo '<div id="product-category-block2" class="col-sm-4 col-xs-6">'
						.'<div class="product-category-block2-upper">'
						.'<div style="background:#E1E1E1; padding: 5px 0 5 10px; height:20px;" id="product-category-headline" class="form-group"><a href="' . tep_href_link('main.php?catt='.$categories['categories_id'].'') . '">'. $categories['categories_name'] . '</a></div>';

					echo	'<div style="vertical-align:top; width:30%; float:left; text-align:right; padding-bottom:5px;" id="product-category-image"><a href="' . tep_href_link('main.php?catt='.$categories['categories_id'].'') . '">' . tep_image(DIR_WS_IMAGES . 
$categories['categories_image'], $categories['categories_name'], '100', '100').'</a></div></div>';

$sub_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
echo '<div class="category-links" style="float:left; width:65%; font-size:11px; font-weight : normal; line-height: 16px; padding-left:5px; text-align:left; padding-bottom:5px;" id="product-category-links">';
					while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {
		$cPath_sub_new = tep_get_path($sub_categories['categories_id']);
echo '<br /><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_sub_new) . '">' .$sub_categories['categories_name'] . '</a>';
					}
echo '</div>';
echo '</div>';
					}
				}
								
    ?>



  <?php 

//	echo tep_draw_separator('pixel_trans.gif', '100%', '10');
   


include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS); 
  
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
