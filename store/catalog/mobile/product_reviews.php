<?php
require_once('includes/application_top.php');
require(DIR_MOBILE_INCLUDES . 'header.php');

  if (!isset($HTTP_GET_VARS['products_id'])) {
        tep_redirect(tep_mobile_link(FILENAME_REVIEWS));
  }

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
  	  tep_redirect(tep_mobile_link(FILENAME_REVIEWS));
  } else {
  	  $product_info = tep_db_fetch_array($product_info_query);
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
  	  $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
  	  $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
  	  $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
  	  $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);
  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
  $headerTitle->write($product_info['products_name']);
  require(DIR_MOBILE_CLASSES . 'split_page_results_mobile.php');
?>
<div id="iphone_content">
   <div class="ui-grid-a ui-responsive">
	<div class="ui-block-a">
	<?php
	require(DIR_MOBILE_MODULES. "product_header.php");
	  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";
	  $reviews_split = new splitPageResultsMobile($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
	  ?>
	</div>
	<div class="ui-block-b">
	  <div id="ficheProdMid" >
	    <div class="description" >
	    <?php
	    if ($reviews_split->number_of_rows > 0) {
	  	  if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
	  	  	  ?>
	  	  	  <div id="results">
	  	  	  <?php 
	  	  	  echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);
	  	  	  echo '<div data-role="controlgroup" data-type="horizontal" data-mini="true">' . $reviews_split->display_links_mobile(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))) . '</div>';
	  	  	  ?></div><?php
	  	  }

		    $reviews_query = tep_db_query($reviews_split->sql_query);
		    while ($reviews = tep_db_fetch_array($reviews_query)) {
		    	    ?>
			      <div class="main">
			        <?php echo '<a href="' . tep_mobile_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><u><b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</b></u></a><br />'; ?>
			      </div>
			      <div class="smallText">
			        <?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?><br />
			      </div>
			      <div class="review">
			        <?php
			        $review_text = str_replace("\n", "<br>", tep_output_string_protected($reviews['reviews_text']));
			        if($HTTP_GET_VARS['reviews_id'] == $reviews['reviews_id'] || strlen($reviews['reviews_text']) < 100)
			      	        echo $review_text; 
			        else 
			        echo substr($review_text,0,strpos($review_text, ' ' , 50)) . ' ...';
			        echo '<br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>';
			        ?>
			        </div>
		      	    <hr class="separator">
		      	  <?php
		    }
	    } else {
	    	    ?>
	    	    <div class="main">
	    	      <?php echo TEXT_NO_REVIEWS; ?>
	    	    </div>
	    <?php
	    }

	    if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
	    	?>
		<div id="results">
		<?php 
		echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);
		echo '<div data-role="controlgroup" data-type="horizontal" data-mini="true">' . $reviews_split->display_links_mobile(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))) . '</div>';
		?></div><?php
            }
            ?>
</div>

            <div class="bouton">
              <?php echo tep_button_jquery(IMAGE_BUTTON_BACK,tep_mobile_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()),'b','button',' data-inline="true" data-icon="back" ') . tep_button_jquery(IMAGE_BUTTON_WRITE_REVIEW,tep_mobile_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()),'b','button','data-icon="edit" data-iconpos="right" data-inline="true"'); ?>  
            </div>
       </div>
</div>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
