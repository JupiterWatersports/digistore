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
    
//find all the reviews 
$reviews_query_raw = "select r.reviews_id, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.date_added desc";
  	$reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
    		$reviews_query = tep_db_query($reviews_split->sql_query); 
    		
//display the reviews
	while ($reviews = tep_db_fetch_array($reviews_query)) {
    					
    		$reviews_customer_name = sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])); 
    		$reviews_link ='<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">';    						
    		$reviews_date_added=sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added']));
    		$reviews_text = '<div class="review-text" style="margin-right:200px; margin-top:15px; margin-bottom:25px;">'. tep_output_string_protected($reviews['reviews_text']).'</div>';
             $reviews_rating = '<i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; 
	
   
 		?>     
        <div> 						
			  <div class="leftfloat"><?php echo $reviews_rating; ?>
			  <div class="left-align"><?php echo $reviews_customer_name; ?>
			   <?php echo $reviews_date_added; ?></div>
			   </div>
			  
	  		  <div class="clear spacer"></div>
	  		  <?php echo $reviews_text; ?>  		  
  		 	 <div class="clear"></div> 
	  		  </div>
	  		  <?php
	          }
	  		  echo '<a rel="nofollow" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';	
				
    		    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    			$reviews = tep_db_fetch_array($reviews_query);
    			if ($reviews['count'] > 0) {

 					?>
 				<p class="right-align">Customer Reviews <?php echo $reviews_link ; ?> (view all <?php echo '<span>'.$reviews['count']. $reviews_no.'</span>'; ?> reviews)</a></p>		    
    			    <?php
    				}
    			
?>	</div>

<style>
.leftfloat img{margin-bottom:-10px; width:130px; height:auto;}
.tab_content .left-align{margin-top:6px;}
</style>