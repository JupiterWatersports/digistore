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

/* if ($new_price = tep_get_products_special_price($product_info_ajx['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($modified_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($sp_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($modified_price, tep_get_tax_rate($product_info_ajx['products_tax_class_id']));
    }	
*/
   $query = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url,  p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_bundle from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $product_info_ajx['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $product_info_query = tep_db_query($query);
    // end Product Extra Fields
// EOF MaxiDVD: Modified For Ultimate Images Pack!

    $product_info = tep_db_fetch_array($product_info_query);
	// BEGIN  Discount 
     $specialprice = true;
     // END Discount 
            $products_price = '<table id="display_price" class="" align="right" border="0" width="100%" cellspacing="0" cellpadding="0">';
            $new_price = tep_get_products_special_price($product_info['products_id']);
            if ($product_info['products_msrp'] > $product_info['products_price'])

            $products_price .= '<tr class="PriceListBIG"><td align="left">' . TEXT_PRODUCTS_MSRP  . $currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';
            if ($new_price != '')
            $products_price .= '<tr class="usualpriceBIG"><td align="left">'. TEXT_PRODUCTS_USUALPRICE . '';
             else
            $products_price .= '<tr class="pricenowBIG"><td align="left">'. TEXT_PRODUCTS_OUR_PRICE .   '';
            
            $products_price .=  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';

            if ($new_price != '')
               {$products_price .= '<tr class="pricenowBIG"><td align="left">' . TEXT_PRODUCTS_PRICENOW .  $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}
            if ($product_info['products_msrp'] > $product_info['products_price'])
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td align="left" >' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_msrp'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}
              else
                {$products_price .= '<tr class="savingBIG"><td ="left" >' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($product_info['products_msrp'] -  $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($product_info['products_price'] / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}}
            else
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td align="left" >' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_price'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_price']) * 100)) . '%)</td></tr>';}}
            $products_price .= '</table>';
	
 echo $products_price;