<!-- xsell_cart //-->
<?php
  
  //Start an array of items being suggested.


  //Start to build the HTML that will display the xsell box.
  $xsell_box_contents = '';

  //Go through each item in the cart, and look for xsell products.
  foreach ($products AS $product_id_in_cart) {
  $id = tep_get_prid($product_id_in_cart['id']);
    //Main XSELL Query
    $xsell_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id FROM  " . TABLE_PRODUCTS . " AS p, " . TABLE_PRODUCTS_DESCRIPTION . " AS pd, " . TABLE_PRODUCTS_XSELL . " AS px WHERE px.products_id = " . $id . " AND px.xsell_id = p.products_id AND px.xsell_id = pd.products_id AND p.products_status = '1' AND pd.language_id = '" . (int)$languages_id . "' ORDER BY p.products_ordered DESC");

    //Cycle through each suggested product and add to box, if there are none
    //go to the next product in the cart.

	
$xsell_contents_array = array();
while ($xsell = tep_db_fetch_array($xsell_query)) {

      //If the xsell item is already being suggested, we don't need
      //to suggest it again.  Keep track of xsell items I've already dealt
      //with.
      if (!in_array($xsell['products_id'], $xsell_contents_array)) {

        //Add this xsell product to the list of xsell products dealt with. 
        array_push($xsell_contents_array, $xsell['products_id']);  

        //If a suggested product is already in the cart, we don't need to
        //suggest it again. 
        if (!$cart->in_cart($xsell['products_id'])) {  


          //Create the box contents.
          $xsell_box_contents .= '<div class="xsell-products">';
        $xsell_box_contents .= '<div>' .tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '';
        $xsell_box_contents .= '<div class="xtra-products-info"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a></td>';
      if ($xsell_price = tep_get_products_special_price($xsell['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($xsell_price, tep_get_tax_rate($xsell['products_tax_class_id'])) . '</span>';
       } else {
        $products_price = $currencies->display_price($xsell['products_price'], tep_get_tax_rate($xsell['products_tax_class_id']));
       }
       $xsell_box_contents .= '' . $products_price . '</div></div>';
	    $xsell_box_contents .= '</div>';
      }  //END OF IF ALREADY IN CART
    }  // END OF IF ALREADY SUGGESTED
  }  //END OF WHILE QUERY STILL HAS ROWS
  }//END OF FOREACH PRODUCT IN CART LOOP

//Only draw the table if there are suggested products.
if (isset($xsell_box_contents)) {
  echo '<br><br><br><h3>Recommeded Accessories</h3><br>';
  echo $xsell_box_contents ;
}
?>
<!-- xsell_cart_eof //-->
