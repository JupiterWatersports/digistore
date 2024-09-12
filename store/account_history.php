<?php
/*
  $Id: account_history.php 1739 2007-12-20 00:52:16Z hpdl $
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
  
  $customer_info_query = tep_db_query("select o.customers_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_id = '". (int)$HTTP_GET_VARS['order_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1'");
  $customer_info = tep_db_fetch_array($customer_info_query);
 
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);
require(DIR_WS_CLASSES . 'order.php');
 
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<style>
.account{ padding:0px 10px !important; vertical-align:top;}
.history-heading{background:#f6f6f6; border:1px solid #ddd; display:table; width:100%;}
.history-heading:after, .history-heading-inner:after{content:""; display:table; clear:both;}
a:hover{color:#09f;}

@media only screen and (max-width :599px) {.order-history{margin-left:-10px; margin-right:-10px;}}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Order History</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>

<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div class="clear"></div>         

<?php
  $orders_total = tep_count_customer_orders();

  if ($orders_total > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'  order by orders_id DESC";
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
$order = new order($history['orders_id']);
?>
	<div class="order-history form-group" id="responsive-table2" style="border: 1px solid #ddd;">
	<div class="history-heading">
    <div class="history-heading-inner" style="padding:15px;">
    <div class="row" style="margin:0 -10px;">
	  <table style="margin-bottom:0;">
    <tr>
    <td class="account" width="30%">
	<?php echo '<span>'.TEXT_ORDER_DATE . '</span><p>'. tep_date_long($history['date_purchased']).'</p>';?>
	</td>
   
	<td class="account">
	<?php echo '<span>Total</span><p>'. strip_tags($history['order_total']).'</p>'; ?>
     </td>
     
     <td class="account">
	<?php echo '<span>Status</span><p>'.$history['orders_status_name'].'</p>'; ?>
	</td>
     
    <td class="account">
	<?php echo '<span>'.$order_type .'</span><p>' .tep_output_string_protected($order_name).'</p>'; ?>
	</td> 
     
	<td class="account" style="vertical-align:top">
	<?php echo '<span>'.TEXT_ORDER_NUMBER . $history['orders_id'].'</span><br>'; ?>
     <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">View</a>'; ?>
	</td>
	</tr>
	</table>
    </div>
    </div>
	</div>
    <div class="order-details">
    <div class="form-horizontal"> 		
               
<?php

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
  
   echo '          <div class="form-group">' . "\n";
	 if ($order->products_image[$i]['image'] !='') { 
      echo   '            <div class="account col-sm-20 col-xs-5"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $order->products[$i]['id']) . '">'. tep_image(DIR_WS_IMAGES . $order->products_image[$i]['image'], $order->products[$i]['name'], 100, 100). '</a></div>'.$products_image . "\n".
		  '            <div class="account col-sm-9 col-xs-7"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $order->products[$i]['id']) . '">' . $order->products[$i]['name'].'</a>'; 
	} else { echo '<div class="account col-sm-20 col-xs-5">&nbsp;<br><br><br></div><div class="account col-sm-9 col-xs-7">' . $order->products[$i]['name'];}
        

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }

    echo '</br><span style="">'. $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) .'</span></div>' . "\n";

    

    echo 
         '          </div>' . "\n";
  }
?>
   
</div>
    </div>
	</div>
    
    
    
   
    
    
<?php

    }

  } else {

?>
           
<div class="alpha"> 
<p><?php echo TEXT_NO_PURCHASES; ?></p>
</div>

<?php

  }
?>
<div class="clear spacer"></div>
<?php

  if ($orders_total > 0) {

?>

      <div class="grid_4 alpha">
      <?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?>
      </div>
      <div class="grid_4 omega right">
      <?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
      </div>
      <div class="clear spacer"></div> 

<?php
  }
?>
      <div class="grid_4 alpha" style="margin-top:15px;"> 
            <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">'.'<button class="button-blue-small">Back</button>'.'</a>'; ?>
      </div>
      <div class="clear spacer-tall"></div> 
      

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
