<?php
/*
  $Id: product_attributes.php v1.0 20100321 kymation $
  $From: product_info.php 1739 2007-12-20 00:52:16Z hpdl $
  $Loc: catalog/includes/modules/ $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
 
?>
<!-- Product_attributes_BOF  //-->
<?php
    $products_id = ( int )$_GET['products_id'];
    $options_array = tep_get_products_attributes( $products_id, ( int )$languages_id, $product_info['products_tax_class_id'] );
    if( $options_array != false && is_array( $options_array ) && count ($options_array) > 0 ) {
?>
                      <tr>
                        <td colspan=2><table border="0" cellspacing="0" cellpadding="0" width="100%">
                          <tr id="variantsBlock">
                            <td colspan=2 id="variantsBlockTitle"><?php echo TEXT_PRODUCT_OPTIONS; ?></td>
                          </tr>
                          <tr>
                            <td width="10"><?php echo tep_draw_separator ('pixel_trans.gif', '10', '1'); ?></td>
                            <td id="variantsBlockData"><table border="0" cellspacing="0" cellpadding="2">
<?php
              foreach( $options_array as $options ) {
                echo tep_select_attributes( $products_id, $options, $languages_id, $product_info['products_tax_class_id'] );
              }
?>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
<?php
  }
?>
<!-- Product_attributes_EOF  //-->