<?php

/*

  $Id: reviews.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);



  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_REVIEWS));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />

<title>Reviews</title>

<meta name="Keywords" content="<?php echo $keywordtag; ?>" />

<meta name="Description" content="<?php echo $description; ?>" />

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>

<h1>Reviews</h1>



<div class="clear"></div>

<?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, p.products_image_med, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);



  if ($reviews_split->number_of_rows > 0) {

    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {

?>
<!--count reviews-->
<div class="grid_4 alpha smalltext"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
<!--page count/link-->
<div class="grid_4 alpha right-align smalltext"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></div>
<div class="clear"></div>
<?php

    }



    $reviews_query = tep_db_query($reviews_split->sql_query);

    while ($reviews = tep_db_fetch_array($reviews_query)) {

?>
<!--open container-->
<div class="grid_8 alpha">
	
	<!--product name -->
	<div class="grid_4 alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">'.$reviews['products_name'] . '</a>'; ?>
	</div>
	<!--reviewer name-->
	<div class="grid_4 right-align omega">
	<?php echo  '<span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>'; ?>
	</div>
	<div class="clear"></div>
	<!--date added-->
	<div class="grid_8 alpha smalltext">
	<?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?>
	</div>
	<!--product image-->          
	<div class="grid_2 alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $reviews['products_image_med'], $reviews['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?>
	</div>
	<!--review text-->
	<div class="grid_6 alpha">
	<?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br />') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') ; ?>
		<!--review rating/stars-->
		<div class="right-align">
		<?php echo '<i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?>
		</div>
	</div>
	<div class="clear spacer"></div>
	<hr />
	
	<div class="spacer"></div>
	<!--close container-->
	</div>
<div class="clear"></div>                 		

<?php

    }

?>

<?php

  } else {

     echo '<p>'.TEXT_NO_REVIEWS.'</p>'; 

  }



  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {

?>
<!--count reviews-->
<div class="grid_4 alpha smalltext"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
<!--page count/link-->
<div class="grid_4 alpha right-align smalltext"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></div>
<div class="clear"></div>
<?php

  }

?>

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
