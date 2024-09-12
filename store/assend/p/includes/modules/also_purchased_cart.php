<!-- xsell_cart //-->
<?php
  
  //Start an array of items being suggested.


  //Start to build the HTML that will display the xsell box.
  $also_purch_box_contents = '';

  //Go through each item in the cart, and look for xsell products.
  foreach ($products AS $product_id_in_cart) {
  $id = tep_get_prid($product_id_in_cart['id']);
    //Main XSELL Query
    $also_purch_query = tep_db_query("select p.products_id, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = " . $id . " and opa.orders_id = opb.orders_id and opb.products_id != " . $id . " and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by opb.products_quantity desc limit 1" . MAX_DISPLAY_ALSO_PURCHASED);

    //Cycle through each suggested product and add to box, if there are none
    //go to the next product in the cart.
    $also_purch_contents_array = array();
	while ($also_purch = tep_db_fetch_array($also_purch_query)) {
$also_purch['products_name'] = tep_get_products_name($also_purch['products_id']);

      //If the xsell item is already being suggested, we don't need
      //to suggest it again.  Keep track of xsell items I've already dealt
      //with.
      if (!in_array($also_purch['products_id'], $also_purch_contents_array)) {

        //Add this xsell product to the list of xsell products dealt with. 
        array_push($also_purch_contents_array, $also_purch['products_id']);  

        //If a suggested product is already in the cart, we don't need to
        //suggest it again. 
        if (!$cart->in_cart($also_purch['products_id'])) {  


          //Create the box contents.
          $also_purch_box_contents .= '<div class="ab-cart-products">';
        $also_purch_box_contents .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $also_purch['products_id']) . '">' .tep_image(DIR_WS_IMAGES . $also_purch['products_image'], $also_purch['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        $also_purch_box_contents .= '<div class="xtra-products-info"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $also_purch['products_id']) . '">' . $also_purch['products_name'] . '</a></td>';
      if ($also_purch_price = tep_get_products_special_price($also_purch['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($also_purch['products_price'], tep_get_tax_rate($also_purch['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($also_purch_price, tep_get_tax_rate($also_purch['products_tax_class_id'])) . '</span>';
       } else {
        $products_price = $currencies->display_price($also_purch['products_price'], tep_get_tax_rate($also_purch['products_tax_class_id']));
       }
       $also_purch_box_contents .= '<span class="alsop-product-price">' . $products_price . '</span></div>';
	    $also_purch_box_contents .= '<form name="buy_now_' . $also_purch['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">'.
'<button class="cssButton ab-buynow" style="border:none;"><input type="hidden" name="products_id" value="' . $also_purch['products_id'] . '" >' . 'Add to Cart' . '</button></form> '.'</div>';
      }  //END OF IF ALREADY IN CART
    }  // END OF IF ALREADY SUGGESTED
  }  //END OF WHILE QUERY STILL HAS ROWS
  }//END OF FOREACH PRODUCT IN CART LOOP

//Only draw the table if there are suggested products.
if (isset($also_purch_box_contents)) {
  echo '<h3>Customers Also Bought</h3><br>';
  echo '<div class="also-bought-cart-container">';
  echo $also_purch_box_contents .'</div>' ;
}
?>
<!-- xsell_cart_eof //-->
