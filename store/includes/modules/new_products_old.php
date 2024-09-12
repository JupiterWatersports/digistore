<?php
/*
  $Id: new_products.php,v 1.34 2005/11/01 22:49:58 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
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
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/
  
?>
<!-- new_products //-->

<?php
  $productnumber = (HOMEPAGE_PRODUCTS/2)-1;
  $height=SMALL_IMAGE_HEIGHT * 1.8;

if (DISPLAY_SPECIALS != "disable") 
  {
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  new contentBoxHeading($info_box_contents);
  }

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, pd.products_description, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, pd.products_description, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }

  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($new_products_query)) {

// Strip Description HTML tags and reduce products description length - Currently set at 90 characters
	$new_products['products_description'] = strip_tags($new_products['products_description']);
	$new_products['products_description'] = substr($new_products['products_description'], 0, 90);

            if ($new_products['products_msrp'] > $new_products['products_price']) {
              if (tep_not_null($new_products['specials_new_products_price'])) {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($new_products['products_msrp'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '&nbsp;&nbsp;' .  $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;';
              } else {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($new_products['products_msrp'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;&nbsp;' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '&nbsp;';
              }
            } else {
              if (tep_not_null($new_products['specials_new_products_price'])) {
                $lc_text = '&nbsp;<span class="oldPrice">' .  $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>&nbsp;';
              } else {
                $lc_text = '&nbsp;' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '&nbsp;';
              }            
            }


    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" valign="top"',
                                           'text' => '
<table width="275" cellspacing="0" cellpadding="0" class="newproductListing-heading" height="' . $height .'">
<tr>
<td width="100" height="10" bgcolor="#ffffff" align="center" class="newproductListing">
										   <p style="padding-top:5px;padding-bottom:5px;">
										   <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
										   </p>
										  
										
										   
</td>
<td width="10"></td>
<td width="175" valign="top">
<p style="font-size:10px;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;padding-top:5px;padding-right:5px;">										   

										   <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a>
										   
</p>
<p style="font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;padding-top:5px;height:40px;margin:0;" class="newproductdata">		

					' . $new_products['products_description'] .'<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">[...]</a>
</p><br />	
</td>
</tr>
  <tr>
    <td width="100" height="18" align="center" bgcolor="#ffffff">
	<p style="font-size:12px;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;padding-bottom:5px;color:#56b8c7;">										  
										   
										  ' . $lc_text . '
</p>
	</td>
    <td width="10"></td>
<td width="175" valign="bottom">
   
	<p>
<span  style="font-size:18px;font-family:Verdana, Arial, Helvetica, sans-serif;padding-bottom:5px;color:#ffff00; valign=top">
   '. tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')).'
																<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']).'">'.tep_image_button('button_details.gif', IMAGE_BUTTON_DETAILS).'</a>'.tep_draw_hidden_field('products_id', $new_products['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW).'
																	  </form>										
</span>

</p>
	</td>
  </tr>
</table>	
										  '
										   
										   
										   
										   );

    $col ++;
    if ($col > 1) {
    $col = 0;
    $row ++;
	if ($row > $productnumber)
	break;
    }
  }

  new contentBox($info_box_contents);
?>
<!-- new_products_eof //-->
