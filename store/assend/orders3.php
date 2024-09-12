<?php
/*
  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');



  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  
  // Start Batch Update Status v1.2 (with Comment buttons & e-mail notification)
 if ('BUS_ENABLE_DELETE' == 1){ // add Delete to select box
  $orders_statuses = array_merge($orders_statuses,array(
                                 array('id' => 'X', 'text' => BUS_DELETE_TEXT)
                                    )
              );
 }
if (isset($_POST['submit'])){
 if (($_POST['submit'] == BUS_SUBMIT)&&(isset($_POST['new_status']))){ // Fair enough, let's update ;)
  $status = tep_db_prepare_input($_POST['new_status']);
  if ($status == '') { // New status not selected
     tep_redirect(tep_href_link('orders3.php'),tep_get_all_get_params());
  }
  // see if we're deleteing
  if ($status == 'X'){
   foreach ($_POST['update_oID'] as $this_orderID){
     foreach ($_POST['update_oID'] as $this_orderID){
       tep_remove_order((int)$this_orderID, $_POST['bus_restock']);
     }
     tep_redirect(tep_href_link('orders3.php'),tep_get_all_get_params());
   }
  }
  // end deleting
  foreach ($_POST['update_oID'] as $this_orderID){
    $order_updated = false;
    $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$this_orderID . "'");
    $check_status = tep_db_fetch_array($check_status_query);
    $comments = tep_db_prepare_input($_POST['comments']);
    if ($check_status['orders_status'] != $status) {
       tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$this_orderID . "'");
       $customer_notified ='1';
          if (isset($_POST['notify'])) {
            $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $this_orderID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $this_orderID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $customer_notified = '0';
          }
          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$this_orderID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");
          $order_updated = true;
    }
    if ($order_updated == true) {
       $messageStack->add_session("Order $this_orderID updated.", 'success');
    } else {
       $messageStack->add_session("Order $this_orderID not updated.", 'warning');
    }
  } // End foreach ID loop
 }
   tep_redirect(tep_href_link('orders3.php'),tep_get_all_get_params());
}
// End Batch Update Status v1.2 (with Comment buttons & e-mail notification)

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);
/*Tracking contribution begin*/
      $ups_track_num = tep_db_prepare_input($_POST['ups_track_num']);
      $usps_track_num = tep_db_prepare_input($_POST['usps_track_num']);
      $fedex_track_num = tep_db_prepare_input($_POST['fedex_track_num']);
/*Tracking contribution end*/

        $order_updated = false;
        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, fedex_track_num, ups_track_num, usps_track_num, date_purchased, ipaddy, ipisp from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);
          if (($check_status['orders_status'] != 4 && $check_status['orders_status'] != 109) && ($status == 4 || $status == 109)) {
            tep_restock_order((int)$oID,'add');
          } else if (($check_status['orders_status'] == 4 || $check_status['orders_status'] == 109) && ($status != 4 && $status != 109)) {
            tep_restock_order((int)$oID,'remove');
          }
        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
          //restock cancelled and refunded orders

         //Package Tracking Plus BEGIN

          $customer_notified = '1';

          if ($HTTP_POST_VARS['notify'] == 'on' & ($usps_track_num == '' &  $ups_track_num == '' & $fedex_track_num == '') ) {

            $notify_comments = '';

            if ($HTTP_POST_VARS['notify_comments'] == 'on') {

              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n";

              if ($comments == null)

                $notify_comments = '';

            }






            $email = 'Dear '  . ',' . "\n\n" . STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . "<a HREF='" . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "'>" .  'order_id=' . (int)$oID . "</a>\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_TRACKING_NUMBER . "\n" . $usps_text . $usps_track .  $ups_text . $ups_track . $fedex_text . $fedex_track  . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1. (int)$oID . EMAIL_TEXT_SUBJECT_2 . $orders_status_array[$status], $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '0';



          }else if ($HTTP_POST_VARS['notify'] == 'on' & (tep_not_null($usps_track_num) & tep_not_null($usps_track_num2) & tep_not_null($ups_track_num) & tep_not_null($ups_track_num2) & tep_not_null($fedex_track_num) & tep_not_null($fedex_track_num2) & tep_not_null($dhl_track_num) & tep_not_null($dhl_track_num2) ) ) {

            $notify_comments = '';

            if ($HTTP_POST_VARS['notify_comments'] == 'on') {

              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n";

              if ($comments == null)

                $notify_comments = '';

            }

            $usps_text = 'USPS(1): ';

            $usps_track_num_noblanks = str_replace(' ', '', $usps_track_num);

            $usps_link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $usps_track_num_noblanks;

            $usps_track = '<a target="_blank" href="' . $usps_link . '">' . $usps_track_num . '</a>' . "\n";

      
            $ups_text = 'UPS(1): ';

            $ups_track_num_noblanks = str_replace(' ', '', $ups_track_num);

            $ups_link = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';

            $ups_track = '<a target="_blank" href="' . $ups_link . '">' . $ups_track_num . '</a>' . "\n";

            $ups_text2 = 'UPS(2): ';

            $ups_track_num2_noblanks = str_replace(' ', '', $ups_track_num2);

            $ups_link2 = 'http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=' . $ups_track_num2_noblanks . '&InquiryNumber2=&InquiryNumber3=&InquiryNumber4=&InquiryNumber5=&TypeOfInquiryNumber=T&UPS_HTML_Version=3.0&IATA=us&Lang=en&submit=Track+Package ';

            $ups_track2 = '<a target="_blank" href="' . $ups_link2 . '">' . $ups_track_num2 . '</a>' . "\n";

            $fedex_text = 'Fedex(1): ';

            $fedex_track_num_noblanks = str_replace(' ', '', $fedex_track_num);

            $fedex_link = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num_noblanks . '&action=track&language=english&cntry_code=us';

            $fedex_track = '<a target="_blank" href="' . $fedex_link . '">' . $fedex_track_num . '</a>' . "\n";

            $fedex_text2 = 'Fedex(2): ';

            $fedex_track_num2_noblanks = str_replace(' ', '', $fedex_track_num2);

            $fedex_link2 = 'http://www.fedex.com/Tracking?tracknumbers=' . $fedex_track_num2_noblanks . '&action=track&language=english&cntry_code=us';

            $fedex_track2 = '<a target="_blank" href="' . $fedex_link2 . '">' . $fedex_track_num2 . '</a>' . "\n";

           


            $email = 'Dear ' . $check_status['customers_name'] . ',' . "\n\n" . STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . "<a HREF='" . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "'>" .  'order_id=' . (int)$oID . "</a>\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_TRACKING_NUMBER . "\n" . $usps_text . $usps_track . $ups_text . $ups_track . $fedex_text . $fedex_track  . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], STORE_NAME . ' ' . EMAIL_TEXT_SUBJECT_1 . (int)$oID . EMAIL_TEXT_SUBJECT_2 . $orders_status_array[$status], $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';

          }

//Package Tracking Plus END



          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");
          $order_updated = true;

        }

  //Package Tracking Plus BEGIN

        tep_db_query("update " . TABLE_ORDERS . " set usps_track_num = '" . tep_db_input($usps_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");

   

        tep_db_query("update " . TABLE_ORDERS . " set ups_track_num = '" . tep_db_input($ups_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");

    

        tep_db_query("update " . TABLE_ORDERS . " set fedex_track_num = '" . tep_db_input($fedex_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");

      

        $order_updated = true;

//Package Tracking Plus END


        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link('orders3.php', tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($_GET['oID']);

        tep_remove_order($oID, $_POST['restock']);

        tep_redirect(tep_href_link('orders3.php', tep_get_all_get_params(array('oID', 'action'))));
        break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  include(DIR_WS_CLASSES . 'order2.php');
?>
<?php
  require(DIR_WS_INCLUDES . 'template-top.php');
?>

<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
<!-- orders page 2 header -->
<div id="heading-block">
            <!-- PWA BOF -->
             <h1 class="pageHeading"><?php echo HEADING_TITLE . (($order->customer['is_dummy_account'])? ' <b>no account!</b>':''); ?></h1>
            <!-- PWA EOF -->
         <ul class="heading-links-7"><?php echo '<li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a></li>
		 <li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
		 <li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
		 <li class="l-link"><a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a></li>
		 <li class="m-link"><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a></li>
		 <li class="m-link"><a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a></li>
		 <li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a></li> '; ?></ul>
  </div>

 
<!-- orders page 2 body -->      
<title><?php echo 'Order&nbsp;#'.$_GET['oID']; ?></title>
            <div class="col-30">
              
                <div class="orders-col-heading-pad"><?php echo ENTRY_CUSTOMER; ?></div>
                <div class=""><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br />'); ?></div>
             	 <div class="Row">&nbsp;</div>
              
              
                <div class="orders-col-heading col-xs-55"><?php echo ENTRY_TELEPHONE_NUMBER; ?></div>
                <div class="col-xs-45"><?php echo $order->customer['telephone']; ?></div>
              <div class="Row">&nbsp;</div>
              
                <div class="orders-col-heading col-xs-5"><?php echo ENTRY_EMAIL_ADDRESS; ?></div>
                <div class="col-xs-7"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></div>
              <div class="Row">&nbsp;</div>
<?php
$sales_person_query = tep_db_query("SELECT customer_service_id FROM orders where orders_id =" . $_GET['oID']."");
		 while ($sales_person = tep_db_fetch_array($sales_person_query)) {
		if ($sales_person['customer_service_id']<>'') {
?>
              <div class="col-xs-12">
                <div class="orders-col-heading col-xs-6">Order created by</div>
                <div class="col-xs-6"><?php echo $sales_person['customer_service_id']; ?></div></div>
              
<?php } }?>
               
                <div class="Row">&nbsp;</div>
               
               
                <div class="orders-col-heading"><?php echo ENTRY_IPADDRESS; ?></div>
                <div style="margin-top:10px;"><?php //echo $order->customer['ipaddy']; ?>

			<a HREF="<?php echo 'http://www.infosniper.net/index.php?ip_address=' . $order->customer['ipaddy'].'&map_source=1&overview_map=1&lang=1&map_type=1&zoom_level=7';?>" target="_blank">

			<font color="<?php echo $fg_color; ?>">
				<?php 	echo $order->customer['ipaddy']; ?>
				</font></a> </div>
				

		<div class="orders-col-heading col-xs-2" style="margin-top:10px;"><?php echo ENTRY_IPISP; ?></div>
		 <div style="margin-top:10px;"><?php echo $order->customer['ipisp']; ?></div>
		
            </div>
            

             <div class="col-30">
              <div class="orders-col-heading-pad"><?php echo ENTRY_SHIPPING_ADDRESS; ?></div>
                <div class="Row"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'); ?></div>
              </div>

         <div class="col-30">
              <div class="orders-col-heading-pad"><?php echo ENTRY_BILLING_ADDRESS; ?></div>
                <div class="Row"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'); ?></div>
            </div>
   <div id="responsive-table"> 
    <table width="100%">     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php 
$amount_paid_query = tep_db_query("select orders_id, payment_value from orders_payment_history where orders_id ='" . $_GET['oID']. "'");
$paid =0;
while ($amount_paid = tep_db_fetch_array($amount_paid_query)) {
 $paid = $paid + $amount_paid['payment_value'];
}
?>
          <tr>
            <td class="main"><b>Amount Charged</b></td>
            <td class="main">$<?php echo number_format((float)$paid, 2, '.', '');  ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
<?php
    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
    }
?>

          <tr>
            <td class="main"><?php echo 'Client Signature'; ?></td>
            <td class="main"><?php if($order->info['payment_signature']!='') { ?><img style="width:200px;" src="<?php echo $order->info['payment_signature']; ?>"/><?php } ?> </td>
          </tr>
          <tr>
            <td class="main"><?php echo 'Send Contract'; ?></td>
            <td class="main">
				<strong>Send Contract Email</strong><input type="checkbox" class="send-contract-email-input" <?php if( $order->info['send_contract_mail'] != 0 ) echo 'checked disabled'; ?> onClick="<?php if( $order->info['send_contract_mail'] == 0 ) echo 'window.location.href = \'' . tep_href_link(FILENAME_ORDERS, 'status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&send_contract_mail=1' ) .'\''; else echo 'alert(\'Contract Email already send.\')';?>"/>	
				<br/>
			
				<strong>Send Conditions of Use Email</strong><input type="checkbox" <?php if( $order->info['conditions_of_use_email'] != 0 ) echo 'checked disabled'; ?> onClick="<?php if( $order->info['conditions_of_use_email'] == 0 ) echo 'window.location.href = \'' . tep_href_link(FILENAME_ORDERS, 'status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&conditions_of_use_email=1' ) .'\''; else echo 'alert(\'Contract Email already send.\')';?>"/>
			
			</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><div id="responsive-table"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']. ': ' . $order->products[$i]['attributes'][$j]['serial_no'] ;
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')'.($order->products[$i]['attributes'][$j]['serial_no']!=''?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>

          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="mdText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="mdText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>

                  <tr>
                    <td class="main" colspan="5">
                    <?php
$product_id_query = tep_db_query("select orders_id, products_id from " . TABLE_ORDERS_PRODUCTS . "   where orders_id = " . $oID );
$product_id = tep_db_fetch_array($product_id_query);
$products_id = $product_id['products_id'];

    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description,  p.products_bundle from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" .  $products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

    $product_info = tep_db_fetch_array($product_info_query);
		if ($product_info['products_bundle'] == "yes") {           
		              $products_bundle = $product_info['products_bundle'];
		              echo TEXT_PRODUCTS_BY_BUNDLE . " " . $product_info['products_name'] . "</td></tr>";
		              $bundle_query = tep_db_query(" SELECT pd.products_name, pb.*, p.products_bundle, p.products_id, p.products_price, p.products_image
											         FROM products p
											         INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
											         ON p.products_id=pd.products_id
											         INNER JOIN " . TABLE_PRODUCTS_BUNDLES . " pb
											         ON pb.subproduct_id=pd.products_id
											         WHERE pb.bundle_id = " . $products_id . " and language_id = '" . (int)$languages_id . "'");
		              while ($bundle_data = tep_db_fetch_array($bundle_query)) {
		                if ($bundle_data['products_bundle'] == "yes") {
		                  // uncomment the following line to display subproduct qty
		                  echo "<br>&raquo; <b>" . $bundle_data['subproduct_qty'] . " x " . $bundle_data['products_name'] . "</b>";
		                  echo "<br>&raquo; <b> " . $bundle_data['products_name'] . "</b>";
		                  $bundle_query_nested = tep_db_query("SELECT pd.products_name, pb.*, p.products_bundle, p.products_id, p.products_price
						                                       FROM products p
						                                       INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd
						                                       ON p.products_id=pd.products_id
						                                       INNER JOIN " . TABLE_PRODUCTS_BUNDLES . " pb
						                                       ON pb.subproduct_id=pd.products_id
						                                       WHERE pb.bundle_id = " . $bundle_data['products_id'] . " and language_id = '" . (int)$languages_id . "'");
                                   
		                  /*     $bundle_query_nested = tep_db_query("select pb.subproduct_id, pb.subproduct_qty, p.products_model, p.products_quantity, p.products_bundle, p.products_price, p.products_tax_class_id
													from " . TABLE_PRODUCTS_BUNDLES . " pb
													LEFT JOIN " . TABLE_PRODUCTS . " p
													ON p.products_id=pb.subproduct_id
													where pb.bundle_id = '" . $bundle_data['subproduct_id'] . "'");      */
 
		                  while ($bundle_data_nested = tep_db_fetch_array($bundle_query_nested)) {
		                    // uncomment the following line to display subproduct qty
		                    echo "<br><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $bundle_data_nested['subproduct_qty'] . " x " . $bundle_data_nested['products_name'] . "</i>";
		                    echo "<br><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $bundle_data_nested['products_name'] . "</i>";
		                    $bundle_sum += $bundle_data_nested['products_price']*$bundle_data_nested['subproduct_qty'];
		                  }
		                } else {
		               
		                  echo "<tr><td class=main valign=top>" ;
		                  echo "</td><td class=main >&raquo; <b>" . $bundle_data['subproduct_qty'] . " x " . $bundle_data['products_name'] . "</b>&nbsp;&nbsp;&nbsp;</td></b></td></tr>";
		                  //	echo "<br>&raquo; <b> " . $bundle_data['products_name'] . "</b>";
		                  $bundle_sum += $bundle_data['products_price']*$bundle_data['subproduct_qty'];
		                }
		              }
		              $bundle_saving = $bundle_sum - $product_info['products_price'];
		              $bundle_sum = $currencies->display_price($bundle_sum, tep_get_tax_rate($product_info['products_tax_class_id']));
		              $bundle_saving =  $currencies->display_price($bundle_saving, tep_get_tax_rate($product_info['products_tax_class_id']));
		         		              
		       } 
		            ?>
                   </td>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
        </table></div></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="main" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="main" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="main" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="main">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="main">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="main" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr></table><div class="cf"></div></div>
      
      <div class="orders-comments-track-status">
      
      <div class="order-comments" style="width:100%; clear:both; margin-top:20px;"><?php echo TABLE_HEADING_COMMENTS; ?></b>
        <?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?>
      <?php echo tep_draw_form('status', 'orders3.php', tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
       <textarea name="comments" cols="60" rows="5" class="form-control"></textarea>
        </div>
      
<?php
/*Tracking contribution begin*/
?>
<div class="form-horizontal">
<div class="form-group">
<label class="control-label col-157"><?php echo TABLE_HEADING_UPS_TRACKING; ?></label><div class="col-157"><?php echo tep_draw_textbox_field('ups_track_num', '24', '18', '', $order->info['ups_track_num']); ?></div>
</div>

<div class="form-group">
<label class="control-label col-157"><?php echo TABLE_HEADING_FEDEX_TRACKING; ?></label><div class="col-157"><?php echo tep_draw_textbox_field('fedex_track_num', '24', '18', '', $order->info['fedex_track_num']); ?></div>
</div>    
<div class="form-group">
<label class="control-label col-157"><?php echo TABLE_HEADING_USPS_TRACKING; ?></label><div class="col-157"><?php echo tep_draw_textbox_field('usps_track_num', '24', '24', '', $order->info['usps_track_num']); ?></div>
</div>
</div>
<?php
/*Tracking contribution end*/
?>


              <ul id="orders2-status-options"> 
                <li class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></li>
                <li class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <span class="notify"><?php echo tep_draw_checkbox_field('notify', '', true); ?></span></li>
                <li class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <span><?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></span></li>
                <li class="orders2-status-update"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></li>  </ul>   

  
       </div>
      </form>

 
          <?php if (MODULE_SHIPPING_LABEL_UPSWS_STATUS == "true") : ?>
              <ul class="links-7"><div style="display:none;"><?php echo '<a href="">' . tep_image_button('button_export_to_ups.gif', Export_To_UPS) .'</a>' ?></div>
              <?php echo '<li><a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a></li> 
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a></li> 
			  <li><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a></li> '; ?></ul>
              
              <div style="display:none;">
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=GND' . '" style="font-size:larger;">' . 'UPS Ground' . '</a>'; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=3DS' . '">' . 'UPS 3 Day Select' . '</a>'; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=2DA' . '">' . 'UPS 2nd Day Air' . '</a>'; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=2DM' . '">' . 'UPS 2nd Day Air A.M.' . '</a>'; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DP' . '">' . 'UPS Next Day Air Saver' . '</a>'; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DA' . '">' . 'UPS Next Day Air' . '</a> '; ?></div>
              <div class="Row"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DM' . '">' . 'UPS Next Day Air Early A.M.' . '</a>'; ?></div></div>
         
          <?php else: ?>
            <?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a><a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?>
          <?php endif; ?>
      
  
<?php
  } else {
?>
<!-- orders page 1 header -->

<title>Orders</title>
<style>.dataTableRow{height:40px;}
</style>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

        <h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
              <?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
              <div class="col-xs-12 form-group">
  <div id="search-cust-email" class="smallText col-sm-4 "><?php echo tep_draw_form('search', FILENAME_ORDERS, '', 'get') . "\n"; ?><?php echo '<input type="text" name="search" class="form-control" placeholder="Search Customer Name/Email" autocomplete="off">' . "\n"; ?> </div>      
            <?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
               
                <div id="order-status" class="smallText  col-sm-4"><?php echo '<div style="display:inline-block; text-align:right; margin-right:10px;">'. HEADING_TITLE_STATUS . '</div>' . '<div style="display:inline-block; width:80%;">'. tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'.'class="form-control"'); ?></div>
              <?php echo tep_hide_session_id(); ?></div></form>
              <div class="col-sm-4"><a style="width:100%; height:30px; display:block; margin-top:2px;" href="client_search.php" ><div class="orders-searchproducts">Search Orders by Product</div></a></div>
              </div>
              
              <div class="select-all" style="display:table;">
              <button class="select-all-btn1 status-btn-show" style="cursor:pointer;">Reveal Multiple Status Update</button>
              <button class="select-all-btn2 status-btn-hide" style="cursor:pointer;">Hide Multiple Status Update</button>
              
      
			  <script type="text/javascript">
		$(document).ready(function() {
			
 	var $menulink1 = $('.select-all-btn1');
		$menulink2 = $('.select-all-btn2');
	    $statuslink = $('.multiple-status-hide');

	$menulink1.click(function(e) {
		e.preventDefault();
		$statuslink.addClass('multiple-status-show'),
		$menulink1.addClass('status-btn-hide'),
		$menulink1.removeClass('status-btn-show'),
		$menulink2.removeClass('status-btn-hide'),
		$menulink2.addClass('status-btn-show');
	});
	$menulink2.click(function(e) {
		e.preventDefault();
		$statuslink.removeClass('multiple-status-show'),
		$menulink1.addClass('status-btn-show'),
		$menulink1.removeClass('status-btn-hide'),
		$menulink2.removeClass('status-btn-show'),
		$menulink2.addClass('status-btn-hide');
	});
});
			  </script>
              </div>
              
 <div id="orders-container" style="display:table;">  
     <!-- Start Batch Update Status v1.2 (with Comment buttons & e-mail notification) -->
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
    
            <td valign="top">
<?php 
echo tep_draw_form('UpdateStatus', FILENAME_ORDERS, tep_get_all_get_params()); ?>
<script language="javascript">
function checkAll(){
  var el = document.getElementsByName('update_oID[]')
  for(i=0;i<el.length;i++){
    el[i].checked = true;
  }
}
function uncheckAll(){
  var el = document.getElementsByName('update_oID[]')
  for(i=0;i<el.length;i++){
    el[i].checked = false;
  }
}

</script>
<table class="table-orders table-orders-bordered table-hover">
<thead>
             <tr class="dataTableHeadingRow">
              	<td class="dataTableHeadingContent multiple-status-hide" style="width:1%;"><?php echo '' ?></td>
                <td class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="center" style="width:10%; text-align:center;">OID</td>
                <td class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
		        <td class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo 'Amount Charged' ?></td>
                <td class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo 'Payment Method' ?></td>
                <td class="dataTableHeadingContent" align="right" style="width:12%; text-align:center;"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center" style="min-width:155px;"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              </thead>

<?php
$search = '';

// Setup column sorting
if($orderby == 'order_numbers') {
   $db_orderby = 'o.orders_id';
} elseif($orderby == 'customers') {
   $db_orderby = 'o.customers_name ' ;	
} elseif($orderby == 'order_totals') {
   $db_orderby = 'order_total';
} elseif($orderby == 'date_purchased') {
   $db_orderby = 'o.date_purchased';
} elseif($orderby == 'order_status') {
   $db_orderby = 's.orders_status_name';
} else {
   $db_orderby = 'o.orders_id';
}
if(!$sort) $sort = 'DESC';

    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' and o.customers_name like '%" . $keywords . "%' ";
    } else {
	  $search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' ";  
    }
    
    if (isset($_GET['cID'])) {
	    // search on order id
      $cID = tep_db_prepare_input($_GET['cID']);
//      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' . order by ". $db_orderby . " " . $sort ;
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";

    } elseif (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0)) {
	    // search on status of order
      $status = tep_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by ". $db_orderby  . " " . $sort ;
    } else {
	    // search on orders statur and customer name ( if search field is filled )
           $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, o.customers_email_address from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s 
            ". $search ."  order by ". $db_orderby . " " . $sort ;
    }
    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
    if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

 // Start Batch Update Status v1.2 (with Comment buttons & e-mail notification)
      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
        echo '              <tr id="defaultSelected"  onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
        $onclick = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'"';
      } else {
        echo '              <tr onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
        $onclick = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'"';
      }
?>

               <td class="dataTableContent multiple-status-hide"><input type="checkbox" name="update_oID[]" value="<?php echo $orders['orders_id'];?>"></td>
                <td class="dataTableContent"><?php echo '<div style="padding-left:10px;"><a href="' .  tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'">' . $orders['customers_name'].'</a></div>'; ?></td>
                <td class="dataTableContent" align="right" style="text-align:center;"><?php echo $orders['orders_id']; ?></td>
                <td class="dataTableContent" align="center" ><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="center"><?php echo strip_tags($orders['order_total']); ?></td>
<?php 
$amount_paid_query = tep_db_query("select oph.orders_id, sum(`payment_value`) as total_paid, oph.payment_type_id, ops.payment_type from ".TABLE_ORDERS_PAYMENT_HISTORY." oph , ".TABLE_ORDERS_PAYMENT_STATUS." ops where orders_id ='" . $orders['orders_id']. "' and ops.payment_type_id = oph.payment_type_id");
while ($amount_paid = tep_db_fetch_array($amount_paid_query)) {
 $paid = $amount_paid['total_paid'];
 $paid_name = $amount_paid['payment_type'];
}
?>
                <td class="dataTableContent" align="center" ><?php if ($paid > 0) {
				echo '$'.@number_format($paid,'2','.','');}
				else {;} ?></td>
                <td class="dataTableContent" align="center" ><?php echo $paid_name; ?></td>
                 
                 <td class="dataTableContent" align="right" style="text-align:center; font-weight:600;" ><?php echo $orders['orders_status_name']; ?></td>
                 <td class="dataTableContent" align="right"><?php echo '<a href="'. tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit')  .'" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="View">'.'<i class="fa fa-eye">'.'</i>'.'</a>'; ?>
          		<?php echo '<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary">'.'<i class="fa fa-pencil">'.'</i>'.'</a>'; ?>
                <?php echo '<a href="' .  tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') .'" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger" >'.'<i class="fa fa-trash-o">'.'</i>'.'</a>'; ?>
				</td>
              </tr>
<?php
    }
?>
</table>

<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br /><br /><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

        
        $contents[] = array('text' => '<br />' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br />' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
        if (MODULE_SHIPPING_LABEL_UPSWS_STATUS == "true") {
          $rqShipping = tep_db_query("SELECT title FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = " . $oInfo->orders_id . " AND class = 'ot_shipping'");
          $shipping = tep_db_fetch_array($rqShipping);
          $ship_method = substr(preg_replace(array('/^United States Postal Service/', '/^US Postal Service/'),'USPS',$shipping['title']),0,-1);
          $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_METHOD . ' ' . (preg_match('/Priority/',$ship_method)? $ship_method : '<b>' . $ship_method . '</b>'));
 if (preg_match('/^USPS/',$ship_method)) {
	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_USPS_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a>');
	}
          if (preg_match('/^United/',$ship_method)) {
            $contents[] = array('align' => 'center', 'text' => '
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=GND' . '">' . 'UPS Ground' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=3DS' . '">' . 'UPS 3 Day Select' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=2DA' . '">' . 'UPS 2nd Day Air' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=2DM' . '">' . 'UPS 2nd Day Air A.M.' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DP' . '">' . 'UPS Next Day Air Saver' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DA' . '">' . 'UPS Next Day Air' . '</a><br />
            <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . '&action=ups_export') . '&ups_method=1DM' . '">' . 'UPS Next Day Air Early A.M.' . '</a><br />');
          }
        }
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="10%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
</tr>
            
<table class="multiple-status-hide">

 <td colspan="2" nobr="nobr">
  <script type="text/javascript" language="javascript">
   function checkForDelete(){
    rs = document.getElementById('busRestock');
    if (document.UpdateStatus.new_status.value == 'X'){
     alert("<?php echo BUS_DELETE_WARNING; ?>");
     rs.style.display = 'block';
     document.UpdateStatus.bus_restock.disabled = false;
     document.UpdateStatus.notify.disabled = true;
    } else {
     rs.style.display = 'none';
     document.UpdateStatus.bus_restock.disabled = true;
     document.UpdateStatus.notify.disabled = false;
    }
   }
  </script>
  <?php //echo tep_draw_form('status', FILENAME_ORDERS, '', 'get');
   echo BUS_TEXT_NEW_STATUS . ': ' . tep_draw_pull_down_menu('new_status', array_merge(array(array('id' => '', 'text' => 'Select')), $orders_statuses), '', ' onchange="checkForDelete();"');
  ?>
 <?php if ('BUS_ENABLE_DELETE' == 1){ ?>
  <div id="busRestock" style="display:none"><?php echo tep_draw_checkbox_field('bus_restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY; ?></div>
 <?php } ?>
 </td>
 <td colspan="2" nobr="nobr">
 <div style="width:200px; margin-top:1px; margin-left:5px;">
  <?php echo tep_draw_checkbox_field('notify','1',false) . ' ' . BUS_NOTIFY_CUSTOMERS  ; ?></div>
 </td>
</tr>
<tr class="dataTableContent" align="center">
<td colspan="2" nobr="nobr">
<script language="javascript"><!--
    var usrdate = '';
   function updateComment(obj,statusnum) {
            var textareas = document.getElementsByTagName('textarea');
            var myTextarea = textareas.item(0);
            {
            myTextarea.value = obj;
            }
            var selects = document.getElementsByTagName('select');
            var theSelect = selects.item(0);
            theSelect.selectedIndex = statusnum;

            return false;

   }

   function killbox() {
            var box = document.getElementsByTagName('textarea');
            var killbox = box.item(0);
            killbox.value = '';
            return false;

    }
//--></script>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
       <td align="center">
       <!-- Button Section -->
       </td>
      </tr>
    </table>
 </td>
</tr>
 <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
</tr>

<tr class="dataTableContent" align="center">
<td colspan="6" nobr="nobr">
<?php echo  '<div style="width:90px; float:left;">'.tep_draw_input_field('select_all',BUS_SELECT_ALL,'class="cbutton" onclick="checkAll(); return false;"','','submit') .'</div>'.
            '<div style="width:100px; float:left;">'.tep_draw_input_field('select_none',BUS_SELECT_NONE,'class="cbutton" onclick="uncheckAll(); return false;"','','submit') .'</div>'.
            '<div style="width:120px; float:left;">'.tep_draw_input_field('submit',BUS_SUBMIT,'class="cbutton"','','submit') .'</div>' ;
        
?>
</td>
</tr>
</form>
</table>         <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </table>
               </td>
              </tr>
        </table>
     </td>
<?php // End Batch Update Status v1.2 (with Comment buttons & e-mail notification) ?>


          </tr>
        </table></td>
      </tr></div>
<?php
  }
?>
    </table></td>
    
<!-- body_text_eof //--></tr>
</table>
<!-- body_eof //-->

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
