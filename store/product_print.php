<?php
/*
   $Id: product_print.php, 2006/03/25    
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

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_PRINT);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  /* Meta tag information */
   $product_meta_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model,              p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

  $product_meta = tep_db_fetch_array($product_meta_query);
  $product_meta['products_description'] =  strip_tags($product_meta['products_description']);
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php echo tep_js_clear_searchbox(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_meta['products_name'] . ' / ' . TITLE; ?></title>
<META NAME="DESCRIPTION" CONTENT="<? echo $product_meta['products_description']; ?>">
<META NAME="KEYWORDS" CONTENT="<? echo $product_meta['products_description']; ?>">
<META NAME="RATING" CONTENT="General">
<META NAME="ROBOTS" CONTENT="noindex">
<META NAME="REVISIT-AFTER" CONTENT="7 days">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=200,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<p>
  <?php
  
   /* Get Product Information */

    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    /* Special Product Price */
    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> ' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></font>';
    } else {
	 /* Non Special Price */
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice"><font color="' . STORE_SPECIAL_PRICES . '">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></font>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br />['. TEXT_PRODUCT_CODE . ' ' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> ' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>
  
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="25"><table width="92%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td class="main1"><?php
		  /* Show Store Name, Address, Location & Phone */	
		 echo tep_display_print(); ?><br />
                <br />
                <HR></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="5" cellpadding="5">
            <tr>
              <td>
			  
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="50%"><table width="61%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><?php 
    if (tep_not_null($product_info['products_image'])) {
?>
                            <table border="0" cellspacing="0" cellpadding="2" align="center">
                              <tr>
                                <td align="center" class="smallText">
<?php
/* Product Image */	
echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?>

                                 
                                </td>
                              </tr>
                            </table>
                          <?php
    }
?>
                        </td>
                      </tr>
                    </table></td>
                    <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td><table width="0" border="0" cellspacing="0" cellpadding="0" align="right">
                           
                        </table></td>
                      </tr>
                      <tr valign="top">
                        <td>&nbsp;</td>
                      </tr>
                      <tr valign="top">
                        <td class="productprintHeader"><?php echo $products_name; ?>                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td >&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="productprintPrice"><?php echo $products_price; ?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td class="main1"><?php
						   /* Display Product Description */	
						   echo stripslashes($product_info['products_description']); ?><br /><br />
                            <HR></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td>
						  
						  <!-- Show Print & Close Window Buttons --!>	
						  <form style="float:left;"><input type='button' value='<?php echo TITLE_PRINT ?>' Onclick='print()';></form>
						  <form style="float:left;"><input type=button value='<?php echo TITLE_CLOSE ?>' onClick='javascript:window.close();'>
						  </form>
<?php echo'<form style="float:left;" name="buy_now_' . $listing['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '"><input type="hidden" name="products_id" value="' . $product_info['products_id'] . '">' . tep_image_submit('button_buy_now.gif', 'Buy Now' . $listing_values['products_name'] . 'Buy Now') . '</form>'; 
echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $product_info['products_id']) . '">Back to product'; ?></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="main"><?php echo DIGISTORE_INFORMATION; ?>                          </td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="50">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
