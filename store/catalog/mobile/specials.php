<?php
require_once('includes/application_top.php');

    $listing_sql = 	"select distinct	p.products_id,  
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
    						and pd.language_id = '" . (int)$languages_id . "' 
    						and s.status = '1'";    						
 
    if (isset($HTTP_GET_VARS['manufacturers_id'])) 
        $listing_sql .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
        
    $classic_site = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_SPECIALS . (tep_not_null(tep_get_all_get_params())? '?' . tep_get_all_get_params(): '');
    require(DIR_MOBILE_INCLUDES . 'header.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);
    $headerTitle->write();
    
?>
<div id="iphone_content">
<div id="cms">
<?php
	require(DIR_MOBILE_MODULES . 'products.php');
?>
</div>
<div class="cms">
	
	<?php 


echo tep_button_jquery(IMAGE_BUTTON_BACK,tep_mobile_link(FILENAME_CATALOG_MB),'b','button','data-icon="back"');


?>  
	
</div>
<?php
	require(DIR_MOBILE_INCLUDES . 'footer.php'); 
?>
