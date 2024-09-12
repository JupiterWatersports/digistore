<?php

/* $Id: shopping_cart.php,v 1.25 2006/25/04 01:13:58 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce
   http://www.oscommerce.com   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================


*/
?>

<!-- shopping_cart //-->
          <tr>
            <td>
<?php

  $info_box_contents = array();
  $info_box_contents[] = array('text' => tep_infobox_header(HEADER_TITLE_SHOPPINGCART,DIR_WS_IMAGES));

  new infoBoxHeading($info_box_contents, false, true, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		$cartnumber=sizeof($products);
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    $cart_contents_string .= '</table>';
  } else {


// Less than one product display email cart message
  $cart_contents_string .= '&nbsp;' . TITLE_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);


// If more than one item in the cart list the number and amount
  if ($cart->count_contents() > 0) {
    $info_box_contents[] = array('text' => '&nbsp;(' . $cartnumber . ')&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL')  . '">' . TITLE_CART_ITEMS . '</a>');
	
    $info_box_contents[] = array('align' => 'left',
                                 'text' => '&nbsp;' . TITLE_CART_SUBTOTAL . '&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL')  . '"><font color="' . STORE_PRICES_COLOUR . '">' . $currencies->  format($cart->show_total()) . '</A>');
  }
  
  
  new infoBox($info_box_contents);
  ?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
