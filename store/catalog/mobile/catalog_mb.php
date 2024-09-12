<?php
require_once('includes/application_top.php');
	$listing_sql = 	"select  distinct	p.products_id,  
    						pd.products_name, 
    						p.manufacturers_id, 
    						p.products_price, 
    						p.products_image, 
    						p.products_tax_class_id, 
    						IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, 
    						IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . 
    						TABLE_PRODUCTS_DESCRIPTION . " pd," .
    						TABLE_PRODUCTS . " p left join " . 
    						TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . 
    						TABLE_SPECIALS . " s on p.products_id = s.products_id, " . 
    						TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
    						where p.products_status = '1' 
    						and p.products_id = p2c.products_id 
    						and pd.products_id = p2c.products_id 
    						and pd.language_id = '" . (int)$languages_id . "'";
    if (isset($HTTP_GET_VARS['cPath']))
        $listing_sql .= " and p2c.categories_id = '" . (int)$current_category_id . "'";
    if (isset($HTTP_GET_VARS['manufacturers_id'])) 
        $listing_sql .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
        
    $listing_sql .= " order by pd.products_name";

// calculate category path
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

$list = array();
$parent_id = (tep_not_null($cPath) == true) ?  (strpos($cPath, '_')?(ltrim(strrchr($cPath, '_' ), '_')):$cPath) : 0;
$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = " . $parent_id . " and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . $path_cond . "' order by sort_order, cd.categories_name");
while ($categories = tep_db_fetch_array($categories_query))  {
	$list[] = $categories;
}

// check if there are manufacturers
$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");

// check if there are specials
$specials_count_query = tep_db_query("select count(*) as total from " . TABLE_SPECIALS . " where status = '1'");
$specials_count = tep_db_fetch_array($specials_count_query);

// set the link for classic site
$url_replace_from = array('%' . DIR_WS_HTTP_MOBILE .'%', '/-mc-/', '/-mm-/');
$url_replace_to = array(DIR_WS_HTTP_CATALOG, '-c-', '-m-');
$url = preg_replace($url_replace_from, $url_replace_to, $_SERVER['REQUEST_URI']);
$classic_site =  HTTP_SERVER . str_replace('catalog_mb.php', 'index.php', $url);

require(DIR_MOBILE_INCLUDES . 'header.php');
require(DIR_WS_LANGUAGES . $language . '/index.php');
$headerTitle->write($headerTitleText);

?>
<!-- categories //-->
<div id="iphone_content">
<?php

// BOF manufacturers menu
if ($number_of_rows = tep_db_num_rows($manufacturers_query) && SHOW_MANUFACTURERS_CATALOG_MENU == 'true') {
	?>
	<div class="cms">
	<?php
	$manufacturers_array = array();
	if (MAX_MANUFACTURERS_LIST < 2) {
		$manufacturers_array[] = array('id' => '', 'text' => (($HTTP_GET_VARS['manufacturers_id'] > 0)? TEXT_MOBILE_BACK_TO_CATEGORIES : PULL_DOWN_DEFAULT));
	}

	while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
		$manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
		$manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                	                       'text' => $manufacturers_name);
        }

        echo tep_draw_form('manufacturers', tep_mobile_link(FILENAME_CATALOG_MB, '', 'NONSSL', false), 'get') .
             TEXT_MOBILE_MANUFACTURERS . ':&nbsp;&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($HTTP_GET_VARS['manufacturers_id']) ? $HTTP_GET_VARS['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" data-theme="a" ') . tep_hide_session_id();
echo '</form></div>';
}
// EOF manufacturers menu

if ($HTTP_GET_VARS['manufacturers_id'] == '' ) {
	if (isset($HTTP_GET_VARS['cPath']) != TRUE) {
		?><div class="cms"><?php
		echo tep_button_jquery(TEXT_MOBILE_PRODUCTS_NEW,tep_mobile_link(FILENAME_PRODUCTS_NEW),'a','button','data-icon="arrow-r" data-iconpos="right"');
		if ($specials_count['total'] > 0 ) {
			echo tep_button_jquery(TEXT_MOBILE_SPECIALS,tep_mobile_link(FILENAME_SPECIALS),'a','button','data-icon="arrow-r" data-iconpos="right"');
		}
		?></div><?php
	}
	
   if (!empty($list)) {	
	?><div class="cms"><?php
	if (CATEGORY_IMAGES_LISTING == 'True') {	
		?><ul data-role="listview" data-inset="true"><?php
		foreach ($list as $item ) {
			$path = tep_mobile_link(FILENAME_CATALOG_MB, tep_get_path($item['categories_id']));
			$img = strlen($item['categories_image']) > 0 ? tep_image(DIR_WS_IMAGES . $item['categories_image'], $item['categories_name']) : tep_image(DIR_MOBILE_IMAGES . 'placeholder_trans.gif') ;
			echo  '<li><a href="' . tep_mobile_link(FILENAME_CATALOG_MB, tep_get_path($item['categories_id'])) . '">' . $img . '<h2 style="margin-top:19px">' . $item['categories_name'] . '</h2></a></li>';
		}
		?></ul><?php
	} else {
		foreach ($list as $item ) {
			echo tep_button_jquery($item['categories_name'], tep_mobile_link(FILENAME_CATALOG_MB, tep_get_path($item['categories_id'])),'a','button','data-icon="arrow-r" data-iconpos="right"');
		}
	}
	?></div><?php
   }
}
	if ($cateqories_products['total'] > 0 || isset($HTTP_GET_VARS['manufacturers_id'])) {
		echo '<div class="prodFrame">';		
		include(DIR_MOBILE_MODULES . 'products.php');
		echo '</div>';	
	} else if ($category_depth == 'products') {
		echo '<div class="cms">' . TEXT_NO_PRODUCTS . '</div>';	
	}
?>
<div class="bouton">
<?php 
echo tep_button_jquery(IMAGE_BUTTON_BACK,'#','b','button','data-rel="back" data-inline="true" data-icon="back"');
?>  
</div>
<?php
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
