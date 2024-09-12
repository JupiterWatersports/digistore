<?php
 require('includes/application_top.php');

	$optionid		= explode(",",$_GET['option_id']);
	$product_id 	= $_GET['product_id'];	
	$product_opt 	= explode(",",$_GET['product_opt']);
	$modified_price	= $_GET['price'];
	$sp_price	= $_GET['sp_price'];
	//$special_price = '0';
	//$modified_price	= 0;
	
	$product_info_query = tep_db_query("select p.products_id, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$product_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
	$product_info_ajx = tep_db_fetch_array($product_info_query);
	
	$new_price = tep_get_products_special_price($product_info_ajx['products_id']);
	

	for($t=0,$k=0;$t<count($optionid);$t++,$k++)
	{

	$product_new_price_query = tep_db_query("select * from " .TABLE_PRODUCTS_ATTRIBUTES. " where products_id = '" . (int)$product_id . "' and options_id = '".    $optionid[$t]."' AND options_values_id ='" . $product_opt[$k] . "'");	

	$product_new_price = tep_db_fetch_array($product_new_price_query);
	
			$sp_price = $sp_price + intval($product_new_price['price_prefix'].$product_new_price['options_values_price']);

	    $modified_price = $modified_price + intval($product_new_price['price_prefix'].$product_new_price['options_values_price']);
}


	
	
 if ($new_price = tep_get_products_special_price($product_info_ajx['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($modified_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($sp_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($modified_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id']));
    }	
	
echo '<td class="main" align="right" colspan=3 id="display_price"><h3>' . $products_price . '</h3></td>';
