<?php
/*
  $Id: account.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

 if (tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
echo $doctype;
?><head>
<meta charset="UTF-8">
<title>My Account</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>

<script  type="text/javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}
function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>

<div id="json">
<!--JSON services -->
<script type="text/javascript" src="<?=DIR_WS_INCLUDES?>/osc_cart.js"></script>
	<script type="text/javascript">
		iCart = new osc_cart('<?=DIR_WS_HTTP_CATALOG?>'); 
		jQuery(iCart.osc_init);
	</script>
</div>
<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>

<?php $grid_half = 'grid_6' ; ?>


<h1><?php echo HEADING_TITLE; ?></h1>

<?php 

if ($messageStack->size('account') > 0) {
		 echo $messageStack->output('account');
	  }
?>

<div class="clear spacer-tall"></div>	 
<?php

  if (tep_count_customer_orders() > 0) {
?>
<div id="responsive-table">
	<table class="account"> 
	<tr><th class="account" colspan="6"><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></th></tr>
			<?php
    			$orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'  order by orders_id desc limit 3");
    			while ($orders = tep_db_fetch_array($orders_query)) {
      			if (tep_not_null($orders['delivery_name'])) {
        			$order_name = $orders['delivery_name'];
        			$order_country = $orders['delivery_country'];
     		 		} else {
        			$order_name = $orders['billing_name'];
        			$order_country = $orders['billing_country'];
      			}
			?>							
	<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">
		<td class="account"><?php echo tep_date_short($orders['date_purchased']); ?></td>
		<td class="account"><?php echo '#' . $orders['orders_id']; ?></td>
		<td class="account"><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
		<td class="account"><?php echo $orders['orders_status_name']; ?></td>
		<td class="account right-align" ><?php echo $orders['order_total']; ?></td>
		<td class="account" ><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '">' . tep_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
	</tr>                         
		<?php
   	 }
?>      			
   </table>	
   </div>
<?php
  }  
 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
