<?php
/*
  $Id: account_history_info.php 1739 2007-12-20 00:52:16Z hpdl $
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

  if (!isset($HTTP_GET_VARS['order_id']) || (isset($HTTP_GET_VARS['order_id']) && !is_numeric($HTTP_GET_VARS['order_id']))) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  
  $customer_info_query = tep_db_query("select o.customers_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_id = '". (int)$HTTP_GET_VARS['order_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1'");
  $customer_info = tep_db_fetch_array($customer_info_query);
  if ($customer_info['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $HTTP_GET_VARS['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'], 'SSL'));

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<script type="text/javascript">
  iCart = new osc_cart('<?=DIR_WS_HTTP_CATALOG?>');
  jQuery(iCart.osc_init);
  iCart.findShoppingCart = function() { return $('table', '#AddToCart').first(); }; 
</script>

<script type="text/javascript"> 
    $(document).ready(function() { 
        $('ul.sf-menu').superfish(); 
    });  
</script>
</head>
<body>
<style>
*{box-sizing:border-box;} .account-heading{padding-left:0px;}
a:hover{color:#09f;}
@media only screen and (max-width :599px) {.order-history{margin-left:-10px; margin-right:-10px;}}
</style>

<div id="header-wrapper">		
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</header><!--end header-->
<?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
</div>

<div class="clear"></div>
<div class="container-fluid">
<div id="breadcrumb" class="col-xs-12">
    <ul class="breadcrumb">
      <?php echo $breadcrumb->trail(' &raquo; '); ?>
    </ul>
</div>

<div class="col-xs-12 form-group">


<h1 style="margin-top:20px;"><?php echo HEADING_TITLE; ?></h1>

<div class="col-xs-12">
<?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?>
</div>

<div class="col-xs-12">
<?php echo sprintf(HEADING_ORDER_NUMBER, $HTTP_GET_VARS['order_id']) .' status: '. ( $order->info['orders_status']); ?>
</div>



<div class="clear spacer"></div> 


<div class="form-group form-horizontal">
<div class="row">

<?php
  if ($order->delivery != false) {
?>
  	<div class="col-sm-3 form-group" >   <div class="account-heading"><?php echo HEADING_DELIVERY_ADDRESS; ?></div>
                
         <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?>            
    </div>
<?php
    if (tep_not_null($order->info['shipping_method'])) {
?>
   <div class="col-sm-3 form-group" style="display:none;">   <div class="account-heading"><?php echo HEADING_SHIPPING_METHOD; ?></div>
         <?php echo $order->info['shipping_method']; ?>
         
    </div>
<?php
    }

  }
?>


   <div class="col-sm-3 form-group">   <div class="account-heading"><?php echo HEADING_BILLING_ADDRESS; ?></div>
      	
 	  	<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
 	 </div>
 	 <div class="col-sm-3 form-group">   <div class="account-heading"><?php echo HEADING_PAYMENT_METHOD; ?></div>
 	  	     	 
 	  	<?php echo $order->info['payment_method']; ?>
 	  	
    </div> 
    <div class="col-sm-3 form-group">   
    <table class="account">
    <tr ><td width="100%"><b style="font-size:15px;">Order Summary</b></td></tr>
		<?php
  		for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
       echo '              <tr>' . "\n" .
			'                <td >' . $order->totals[$i]['title'] . '</td>' . "\n" .
            '                <td>' . $order->totals[$i]['text'] . '</td>' . "\n" .
     	    '              </tr>' . "\n";
  }
?>
           
       </table>
       </div> 
</div>
</div>
 
 <h3>Products Ordered</h3>
 <div class="order-history form-group col-xs-12" style="border: 1px solid #ddd;">      
    <div class="history-heading-inner">		
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

<?php if (($order->info['ups_track_num'] <> '') || ($order->info['fedex_track_num'] <> '') || ($order->info['usps_track_num'] <> '')){
    
    if($order->info['ups_track_num'] <> ''){
        $tracking = $order->info['ups_track_num'];
        $order_tracking = '<a target="_blank" style="text-decoration:underline;" href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$tracking.'">Tracking Via UPS</a>';
    }
    
    if($order->info['usps_track_num'] <> ''){
        $tracking = $order->info['usps_track_num'];
        $order_tracking = '<a target="_blank" style="text-decoration:underline;" href="https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels='.$tracking.'">Tracking Via USPS</a>';
    }
    
    if($order->info['fedex_track_num'] <> ''){
        $tracking = $order->info['fedex_track_num'];
        $order_tracking = '<a target="_blank" style="text-decoration:underline;" href="https://www.fedex.com/apps/fedextrack/index.html?action=track&tracknumbers='.$tracking.'">Track Via Fedex</a>';
    }
    
echo '<div class="col-xs-12 form-group">
    <h3>Order Tracking</h3>
    '.$order_tracking.'
    </div>';
}
    ?>    

<div class="form-group col-xs-12" id="responsive-table2">
<div class="row">
<table class="account">
<tr><td class="account" colspan="3"><h3><?php echo HEADING_ORDER_HISTORY; ?></h3></td></tr>
		<?php
  $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' and os.public_flag = '1' order by osh.date_added");
  while ($statuses = tep_db_fetch_array($statuses_query)) {
    echo '              <tr>' . "\n" .
         '                <td class="account">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .
         '                <td class="account">' . $statuses['orders_status_name'] . '</td>' . "\n" .
         '                <td class="account">' . (empty($statuses['comments']) ? '&nbsp;' : nl2br(tep_output_string_protected($statuses['comments']))) . '</td>' . "\n" .
         '              </tr>' . "\n";
  }
?>
 	</table>
</div>
</div> 
 	
<?php
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php');
?>
   	             
<div class="col-xs-12">
<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
</div>
<div class="clear"></div>

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
