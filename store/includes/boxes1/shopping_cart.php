<?php
if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache') ) {
      echo "<%CART_CACHE%>";
      } else {
      	require(DIR_WS_BOXES . 'shopping_cart_box.php');
      }
?>
