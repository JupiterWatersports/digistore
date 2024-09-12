<?php
/*
reviews_tabs.php
Released under the GNU General Public License
OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/
define('TEXT_OF_5_STARS', '%s of 5');

 // find reviews count
 $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_info['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query); 
  
  ?>
<div>
          
<?php 
 $reviews_query_raw = "select r.reviews_id, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.date_added desc";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {

?>
	<div class="grid_3 smalltext alpha">
		<?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
	</div>
	<div class="grid_3 smalltext right-align alpha">
		<?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
	</div>
	<div class="divider-tall"></div>

<?php
    }
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
	<div class="leftfloat">
    <?php echo '<i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?>
	<div class="left-align"><?php echo sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])). '&nbsp;&nbsp;&nbsp;'. sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></div>
	</div>
	<div class="clear spacer"></div>
	<?php echo '<div class="review-text" style="margin-right:15%; margin-top:10px; margin-bottom:35px;">'. tep_output_string_protected($reviews['reviews_text']).'</div>';?>
	<div class="clear"></div>                                             

<?php
    }
?>

<?php
  } else {
?>
<div class="grid_6">
		<?php echo TEXT_NO_REVIEWS; ?>
        <div class="divider-tall" style="height:20px;"></div>
        <?php echo '<a rel="nofollow" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>   
</div>
	<div class="clear"></div>
<?php
  }
  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

<div class="grid_3 left-align">
		<?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
</div>
<p class="right-align"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></p>
<div class="clear"></div>        
<?php echo '<a rel="nofollow" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>   
<?php
  } 
?></div>

<style>
.leftfloat img{margin-bottom:-10px; width:130px; height:auto;}
.tab_content .left-align{margin-top:6px;}
</style>