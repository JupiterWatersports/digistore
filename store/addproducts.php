<?php
	require('includes/application_top.php');
	
	$allProducts = filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_STRING);
	
	$products = explode(',', $allProducts);
	
	foreach ($products as $product)
	{
		if (strpos($product, '{')) {
			sscanf($product, '%d{%d}%d', $productID, $productOptionID, $optionValueID);
			$cart->add_cart($productID, 1, array($productOptionID => $optionValueID));
		}
		else {
			$productID = $product;
			$cart->add_cart($productID);
		}
		$productID = 0;
		$productOptionID = 0;
		$optionValueID = 0;
	}
	
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
?>