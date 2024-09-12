<?php
require_once('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_mobile_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<div class="cms">

<?php
  $orders_total = tep_count_customer_orders();

  if ($orders_total > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
    $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = tep_db_query($history_split->sql_query);

    while ($history = tep_db_fetch_array($history_query)) {
      $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      if (tep_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history['billing_name'];
      }

      $order_text = '
            			' . TEXT_ORDER_NUMBER . '
            			' . $history['orders_id'] .'<br />
						' . TEXT_ORDER_DATE . '
						' . tep_date_short($history['date_purchased']) .'<br />
						' . TEXT_ORDER_STATUS . '
						'. $history['orders_status_name'] . '<br />
						' . TEXT_ORDER_COST . '
						'. strip_tags($history['order_total']) . '
			  ';

	echo tep_button_jquery($order_text,tep_mobile_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL'),'a','button');

      
    }
  } else {
?>
                  <div class="main"><?php echo TEXT_NO_PURCHASES; ?></div>
<?php
  }
?>
<br/>
<?php
  if ($orders_total > 0) {
?>
<div id="results">          
		  <?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?><br />
          <?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
</div>
<?php
  }
?>



<div class="bouton">
<?php 
	echo tep_button_jquery(IMAGE_BUTTON_BACK,tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'),'b','button',' data-inline="true" data-icon="back" ');
	
?>
</div>

</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
