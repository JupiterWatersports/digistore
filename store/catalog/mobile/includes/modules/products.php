<?php
require(DIR_MOBILE_CLASSES . 'split_page_results_mobile.php');
$listing_split = new splitPageResultsMobile($listing_sql, 12, 'p.products_id');
?>
<!-- products //-->
<div class="ui-grid-b ui-responsive">
<?php
$num_of_columns = 3;
	$row = 0;
  	$col = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
		if ($col >= $num_of_columns) {
			$col = 0;
		} 
		
    	$path = '<a href="' . tep_mobile_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">';
    	$img = tep_image(DIR_WS_IMAGES .$listing['products_image'], $listing['products_name'], MOBILE_IMAGE_WIDTH, MOBILE_IMAGE_HEIGHT);

	$img = str_replace( 'border="0"','',$img); // W3C VALID HTML 5
	
    	if (tep_not_null($listing['specials_new_products_price'])) {
    		$price = '<s>' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
        } else {
        	$price = $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id']));
        }
       

    $buy_button = tep_button_jquery(IMAGE_BUTTON_IN_CART,tep_mobile_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']),'b','button',' data-mini="true"');     

    if ($col == 0) {
    	    echo '<div class="ui-block-a">';
    } else if ($col == 1) {
    	    echo '<div class="ui-block-b">';
    } else if ($col == 2) {
    	    echo '<div class="ui-block-c">';
    }

    ?>			
    <div class="prodCell">
    	<div class="ui-bar">
		<?php echo '<div class="prodImage">' . $path . $img . '</a></div>'; ?>
		<div class="prodName">
		<?php echo $path;
		if (strlen($listing['products_name']) > MOBILE_PRODUCT_NAME_LENGTH) {
			echo substr($listing['products_name'], 0, MOBILE_PRODUCT_NAME_LENGTH) . '...';
		} else {
			echo $listing['products_name'];
		}
		?>
		</a>
		</div>
		<div class="prodPrice">
		<?php echo $price; ?>
		</div>
		<div class="prodButton">
		<?php echo $buy_button; ?></div></div></div></div>
		<?php	
	$col++;
    } 
?>

</div>

<?php
	if ($listing_split->number_of_rows > 0 ) {
		?>
		<div id="results">
		<?php 
		echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
		echo '<div data-role="controlgroup" data-type="horizontal" data-mini="true">' . $listing_split->display_links_mobile(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))) . '</div>';
		?></div><?php
	} else {
		echo '<div class="cms">' . ((basename($PHP_SELF) == 'advanced_search_result.php')? TEXT_NO_PRODUCTS : TEXT_NO_PRODUCTS2) . '</div>';	
	}
?>
