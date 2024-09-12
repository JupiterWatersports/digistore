<?php
/*
  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php'); 
 $check_signature_query = tep_db_query("select payment_signature from orders where orders_id= '".$_GET['oID']."'");
$check_signature = tep_db_fetch_array($check_signature_query);  
  function getContractText(){
	return "Boat Demo/Paddleboard/Kite Gear Rental Agreement
		Agreement is between Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop herein called LESSOR and the undersigned herein called RENTER
		I represent and agree as follows:
		1. Renter assumes full responsibility for the equipment listed on this Rental Agreement and the associated accessories.
		2. Renter agrees to obey all state and local boating regulations, and all lawful directives from appropriate emergency or law enforcement personnel, while operating or renting the watercraft. In the event of a citation for violation of these rules, the Renter shall be solely responsible. Renter agrees to notify Lessor of any incidents or injuries occurring while renting watercraft.
		3. Renter represents that he or she is capable of operating the equipment and finds it in good working order, condition, and repair.
		4. Renter shall bear all risk of damage or loss of the equipment, or any portion thereof, including but not limited to damage and theft, and shall pay Lessor the cost of repair or replacement. Renter hereby authorizes Lessor to charge the credit card taken during the reservation process for cost of repair or replacement in the event of but not limited to damage, loss, or theft of the equipment.
		5. Renter understands that he or she is liable for all dirty, damaged, lost, or stolen equipment and any fees associated with the listed equipment, and that all equipment must be returned in good condition as determined by Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop.
		6. Renter understands that he or she is responsible for returning the listed Equipment at the agreed upon time on the due date of the rental as shown on the Rental Agreement, and that a late fee will be charged for items that are past due.
		7. Renter agrees that it is his or her responsibility to ensure proper transportation of the equipment on his or her vehicle and that watercraft must be transported using proper restraint devices.
		8. Renter acknowledges that improper loading and attachment of the equipment may result in damage to his or her vehicle, other vehicles, and the equipment.
		9. Renter releases Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop from all liability for any assistance provided in loading or securing equipment.
		10. Renter releases Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop from all liability for any damage that may occur to Renters vehicle while loading, unloading, or transporting the Equipment.
		11. RECOGNITION OF RISK: Renter expressly acknowledges that stand up paddling is an activity with inherent risks of injury to persons and property. Renter is aware of those risks and understands them. Renter acknowledges that United States Coast Guard regulations stipulate that all paddlers are required to have a US Coast Guard approved Personal Floatation Device (PFD) and that Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop requires all Renters to wear an approved PFD at all times while on the water . Renter understands that use of a Personal Floatation Device does not remove all risks of injury, nor does it make stand up paddling a safe activity. Renter alone has determined the sufficiency of any safety gear or other precautions that the renter decides to take to minimize the risks of the activity. No party related to the Lessor, including owners and employees, and its agents, has made any representation regarding the safety of, or the risks of the activity. Renter expressly assumes the risks of the activity. Renter understands the dangers of boating on tidal or fresh water and understands that weather can play a factor in boating safety and that such activities are subject to the unpredictable forces of nature.
		12. RELEASE OF LIABILITY: Renter hereby releases Lessor, its agents, contractors, employees, owners Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop, from liability for negligence and holds harmless the Lessor, its agents, contractors, employees, owners, and Jupiter Kiteboarding Inc., Jupiter Paddleboarding, The Kite Shop from any loss, expense, or cost, including attorney fees, arising out of damages or injuries, whether to persons or property, including those not listed on this agreement, occurring as a result of the rental or use of said boat and Equipment.
		13. This agreement constitutes the entire agreement between Lessor and Renter and no term may be waived or modified, including any provision against oral modification, except in writing signed by both parties. There are no warranties, expressed or implied, by Lessor to Renter, except as contained herein, and Lessor shall not be liable for any loss or injury to Renter nor to anyone else, of any kind or however caused. This agreement is one of bailment only and Renter is not Lessor�s agent while using said Equipment. The laws of the State of Florida shall govern this agreement.
		14. I have read this agreement and understand it, and I sign it of my own free will. I am aware that this includes a release of liability and is binding on me, heirs, executors, administrators and assigns, or any person claiming by or through me. References to �I� shall include family members.";
  }
  
  function getConditonsText(){
	return "<strong>Jupiter Kiteboarding Inc. DBA The Kite Shop, Jupiter Paddleboarding, Jupiter Wakeboarding WATER SPORTS WAIVER</strong>

			<strong>SPORT PARTICIPANT RELEASE OF LIABILITY, WAIVER OF CLAIMS, EXPRESS ASSUMPTION OF RISK AND INDEMNITY AGREEMENT</strong>
			<strong>Please read and be certain you understand the implications of signing. By signing below, you are confirming your agreement and understanding of what is stated below.</strong>
			<strong>Express Assumption of Risk Associated with Sport, Venue Use and Related Activities.</strong>

			<strong>I</strong> do hereby affirm and acknowledge that I have been fully informed of the inherent hazards and risks associated with<strong><u>Kiteboarding, Paddleboarding, Wakeboarding, the use of Skateboards or Segways</u></strong>, transportation of equipment related to the activities, and traveling to and from activity sites in which I am about to engage. <strong>Inherent hazards and risks include but are not limited to:</strong>
			1. Risk of injury from the activity and equipment utilized is significant including the potential for <strong>broken bones</strong>, severe <strong>injuries to the head, neck, back and/or surfers� myelopathy, drowning, or other bodily injuries</strong> that my result in <strong>permanent disability or death</strong>.
			2. Possible <strong>equipment failure</strong> and/or malfunction or misuse of my own or others� equipment, which may result in injury, including those injuries described above.
			3. I AGREE THAT I WILL WEAR APPROVED PROTECTIVE GEAR AS DECREED BY THE GOVERNING BODY OF THE SPORT I AM PARTICIPATING IN. However, I understand that protective gear cannot guarantee the participant�s safety. I further agree that no helmet can protect the wearer againstall potential head injuries or prevent injury to: the wearer�s <strong>face, neck or spinal cord or from surfers� myelopathy</strong>.
			4. Variation in terrain, wind, temperature and water conditions, including but not limited to waves, currents, shore break, tides, marine life, blowing sand, trees, rocks, other persons and their equipment, and other natural and man-made hazards.
			5. My <strong>own negligence</strong> and/or the <strong>negligence of others</strong>, including but not limited to <strong>operator error</strong> and instructor/guide decision-making including misjudging ocean conditions, weather, equipment or obstacles.
			6. Exposure to the elements and temperature extremes may result in heat exhaustion, heat stroke, sunburn, hypothermia and dehydration.
			7. Dangers associated with exposure to natural elements included but not limited to tsunami, hurricane, inclement weather, thunder and lightning, severe and/or varied winds, temperature, sea conditions and marine life.
			8. Fatigue, exhaustion, chill, and/or dizziness, which may diminish my/our reaction time and increase the risk of accident.
			9. <strong>Impact or collision</strong> with other participants, athletes, spectators, employees, pedestrians, motor vehicles, and cyclists.
			<strong>*I understand the description of these risks is not complete and unknown or unanticipated risks may result in injury, illness, or death.</strong>

			<strong>Release of Liability, Waiver of Claims and Indemnity Agreement</strong>

			In consideration for being permitted to participate in the above described activity(ies) and related activities, I hereby agree, acknowledge and appreciate that:

			1. <strong>I HEREBY RELEASE AND HOLD HARMLESS WITH RESPECT TO ANY AND ALL INJURY, DISABILITY, DEATH</strong>, or loss or damage to person or property, <strong>WHETHER CAUSED BY NEGLIGENCE OR OTHERWISE</strong>, the following named persons or entities, herein referred to as releasees.
			Jupiter Kiteboarding Inc.I agree to release the releasees, their officers, directors, employees, representatives, agents, and volunteers from any and all liability and responsibility whatsoever and for any claims or causes of action that I, my estate, heirs, survivors, executors, or assigns may have for personal injury, property damage, or wrongful death arising from the above activities whether caused by active or passive negligence of the releasees or otherwise. By executing this document, I agree to hold the releasees harmless and indemnify them in conjunction with any injury, disability, death, or loss or damage to person or property that may occur as a result of my engaging in the above activities.
			2. By entering into this Agreement, I am not relying on any oral or written representation or statements made by the releasees, other than what is set forth in this Agreement.
			3. This agreement shall apply to any and all injury, disability, death, or loss or damage to person or property occurring at any time after the execution of this agreement.

			This release shall be binding to the fullest extent permitted by law. If any provision of this release is found to be unenforceable, the remaining terms shall be enforceable.

			<strong>I HAVE READ THIS RELEASE OF LIABILITY AND ASSUMPTION OF RISK AGREEMENT, I FULLY UNDERSTAND ITS TERMS, I UNDERSTAND THAT I HAVE GIVEN UP LEGAL RIGHTS BY ACCEPTING THIS DISCLAIMER FREELY AND VOLUNTARILY WITHOUT ANY INDUCEMENT.</strong>";
  }
    require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  include(DIR_WS_CLASSES . 'order.php');
  if(  isset($_GET['print_all']) ){
  
		$order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . $_GET['oID'] . "'");
		$order = tep_db_fetch_array($order_query);
		
		$send_contract_mail=getContractText()."
		<img width='300px' src='".$check_signature['payment_signature']."' />
		".$order['customers_name']."
		".date('m/d/Y', $order['signature_date'])."
		";
		
		
		$conditions_of_use_email=getConditonsText()."			
		<img width='300px' src='".$check_signature['payment_signature']."' />
		".$order['customers_name']."
		".date('m/d/Y', $order['signature_date'])."
		";
		
		
		echo '<html><head><title></title><style> html,body{margin:0;padding:0;} body{font-family:arial;font-size:11px;} </style></head><body><h2>Contract</h2>';
		echo nl2br($send_contract_mail);
			echo '<div style="page-break-after: always;"></div><div style="font-size:10px;">';
			echo nl2br($conditions_of_use_email);
			echo '</div>';
		echo '<script> window.print(); </script>';
		echo '</body></html>';
		
		
		exit;
  }
  

  	if($_GET['send_contract_mail']=='1' || isset($_GET['print_contract']) ){
	
		$order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . $_GET['oID'] . "'");
		$order = tep_db_fetch_array($order_query);
	
		$send_contract_mail=getContractText()."
		<img width='300px' src='".$check_signature['payment_signature']."' />
		".$order['customers_name']."
		".date('m/d/Y', $order['signature_date'])."
		";
		
		if(isset($_GET['print_contract'])){
			echo '<html><head><title></title><style> html,body{margin:0;padding:0;} body{font-family:arial;font-size:11px;} </style></head><body><h2>Contract</h2>';
			echo nl2br($send_contract_mail);
			echo '<script> window.print(); </script>';
			echo '</body></html>';
			exit;
		}
		
		//if(1 /*$order['send_contract_mail']!=1*/){
			
			tep_mail($order['customers_name'], $order['customers_email_address'] , 'Order number '. $_GET['oID'] . ' Contract Email ' , $send_contract_mail, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			tep_db_query("update " . TABLE_ORDERS . " set send_contract_mail = '1' where orders_id = '" . tep_db_input($_GET['oID']) . "'");
			
		//}
	}
  	if( (isset($_GET['conditions_of_use_email']) && $_GET['conditions_of_use_email']=='1') || isset($_GET['print_conditions']) ){
	
		$order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . $_GET['oID'] . "'");
		$order = tep_db_fetch_array($order_query);
		
			$conditions_of_use_email=getConditonsText()."			
			<img width='300px' src='".$check_signature['payment_signature']."' />
			".$order['customers_name']."
			".date('m/d/Y', $order['signature_date'])."
			";
			
		
		if(isset($_GET['print_conditions'])){
			echo '<html><head><title></title><style> html,body{margin:0;padding:0;} body{font-family:arial;font-size:10px;} </style></head><body>';
			echo nl2br($conditions_of_use_email);
			echo '<script> window.print(); </script>';
			echo '</body></html>';
			exit;
		}
		if(1 /*$order['conditions_of_use_email']!=1*/){
			
			tep_mail($order['customers_name'], $order['customers_email_address'] , 'Order number '. $_GET['oID'] . ' Conditions of Use Email ' , $conditions_of_use_email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			tep_db_query("update " . TABLE_ORDERS . " set conditions_of_use_email = '1' where orders_id = '" . tep_db_input($_GET['oID']) . "'");
		}
	}


  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");
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
     tep_redirect(tep_href_link(FILENAME_ORDERS),tep_get_all_get_params());
  }
  // see if we're deleteing
  
   foreach ($_POST['update_oID'] as $this_orderID){
	    $check_status_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . (int)$this_orderID . "'");
        $check_status = tep_db_fetch_array($check_status_query);
          if (($check_status['orders_status'] != 4 && $check_status['orders_status'] != 109) && ($_POST['new_status'] == '4') || ($_POST['new_status'] == '109')) {
             $status = tep_db_prepare_input($_POST['new_status']); 
			tep_restock_order((int)$this_orderID,'add');
          } else if (($check_status['orders_status'] == 4 || $check_status['orders_status'] == 109) && ($status != 4 && $status != 109)) {
             $status = tep_db_prepare_input($_POST['new_status']); 
			tep_restock_order((int)$this_orderID,'remove');
          }
   
	 if ($status == '') {
     tep_redirect(tep_href_link(FILENAME_ORDERS),tep_get_all_get_params()); }
   }
  // end deleting
  foreach ($_POST['update_oID'] as $this_orderID){
    $order_updated = false;
    $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$this_orderID . "'");
    $check_status = tep_db_fetch_array($check_status_query);
    $comments = tep_db_prepare_input($_POST['comments']);
    if ($check_status['orders_status'] != $status) {
       tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$this_orderID . "'");
       $customer_notified ='0';
          if (isset($_POST['notify'])) {
            $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $this_orderID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $this_orderID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $customer_notified = '1';
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
   tep_redirect(tep_href_link(FILENAME_ORDERS),tep_get_all_get_params());
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

          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

            //BEGIN SEND HTML MAIL//

//Prepare variables for html email//
$Varlogo = ''.VARLOGO.'' ;
$Vartable1 = ''.VARTABLE1.'' ;
$Vartable2 = ''.VARTABLE2.'' ;

$Vartext1 = ' <b>' . EMAIL_TEXT_DEAR . ' ' . $check_status['customers_name'] .' </b><br>' . EMAIL_MESSAGE_GREETING ;
$Vartext2 = '    ' . EMAIL_TEXT_ORDER_NUMBER . ' <STRONG> ' . $oID . '</STRONG><br>' . EMAIL_TEXT_DATE_ORDERED . ': <strong>' . strftime(DATE_FORMAT_LONG) . '</strong><br><a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'account_history_info.php?order_id=' . $oID .'">' . EMAIL_TEXT_INVOICE_URL . '</a>' ; 

$Varbody = EMAIL_TEXT_COMMENTS_UPDATE . ' ' . $comments . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

$Varmailfooter = ''.VARMAILFOOTER.'' ;

$Varhttp = ''.VARHTTP.'';
$Varstyle = ''.VARSTYLE.'';

//Check if HTML emails is set to true
if (EMAIL_USE_HTML == 'true') {	

//Prepare HTML email
require(DIR_WS_MODULES . 'email/html_orders.php');
$email = $html_email_orders;
} else {		

//Send text email
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'account_history_info.php?order_id=' . $oID .'">' . EMAIL_TEXT_INVOICE_URL . '</a>' . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . EMAIL_TEXT_COMMENTS_UPDATE . ' ' . $comments . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
}

//END SEND HTML MAIL//

tep_mail($check_status['customers_name'], $check_status['customers_email_address'], 'Order number '. $oID . ' Status ' . $orders_status_array[$status], $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }


      if (tep_not_null($ups_track_num)) {
        tep_db_query("update " . TABLE_ORDERS . " set ups_track_num = '" . tep_db_input($ups_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
        $order_updated = true;
      }

      if (tep_not_null($usps_track_num)) {
        tep_db_query("update " . TABLE_ORDERS . " set usps_track_num = '" . tep_db_input($usps_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
        $order_updated = true;
      }

      if (tep_not_null($fedex_track_num)) {
        tep_db_query("update " . TABLE_ORDERS . " set fedex_track_num = '" . tep_db_input($fedex_track_num) . "' where orders_id = '" . tep_db_input($oID) . "'");
        $order_updated = true;
      }
        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($_GET['oID']);

        tep_remove_order($oID, $_POST['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
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

?>
<?php
	require(DIR_WS_INCLUDES . 'template-top.php');
?>
<link rel="stylesheet" href="css/bootstrap-grid.css">

<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
<!-- orders page 2 header -->
<style>
.highlight {
background-color:#9FFF9F;
}
</style>
<div id="heading-block">
            <!-- PWA BOF -->
             <h1 class="pageHeading"><?php echo HEADING_TITLE . (($order->customer['is_dummy_account'])? ' <b>no account!</b>':''); ?></h1>
            <!-- PWA EOF -->
         <ul class="heading-links-7"><?php echo '<li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a></li>
		 <li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
		 <li class="s-link"><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
		 <li class="l-link"><a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a></li>
		 <li class="m-link"><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a></li>
		 <li class="m-link"><a href="' . tep_href_link('http://www.usps.com') . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a></li>
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
              
<?php   }
      }?>
               
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
    <table width="100%" >     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" style="display:none;">
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
        <td><div id="responsive-table">
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
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
	
	$highl = '';	
	if(isset($_GET['highlight-serial'])){
		if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
				
			if ($_GET['highlight-serial'] == $order->products[$i]['attributes'][$j]['serial_no']) {
			$highl = 'highlight';
			}
		}
		}
		
	} elseif(isset($_GET['highlight-att'])){
			
	 $attributes_query = tep_db_query("select * from products_attributes where products_id = '" . $order->products[$i]['id'] . "' and options_values_id = '".$_GET['highlight-att']."'");
	 $attributes = tep_db_fetch_array($attributes_query);
	
		if (tep_db_num_rows($attributes_query) > 0 ) {
		$highl = 'highlight';
		}
	}
	
	elseif(isset($_GET['highlight'])){
		if($_GET['highlight'] == $order->products[$i]['id'] ) {
			$highl = 'highlight';
		}
	} else {
			$highl = '';
		}
	
		
      echo '          <tr class="dataTableRow '.$highl.'">' . "\n" .
           '            <td class="" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
			
          echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']. ': ' . $order->products[$i]['attributes'][$j]['serial_no'] ;
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')'.($order->products[$i]['attributes'][$j]['serial_no']!=''?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>

          <tr>
            <td align="right" colspan="8">
				<table border="0" cellspacing="0" cellpadding="2">
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
        <td class="main">
		<table cellspacing="0" cellpadding="5" class="table table-bordered" style="max-width:600px; border: 1px solid #aaa;">
          <thead>
			<tr>
            <th class="main" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></th>
            <th class="main" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></th>
            <th class="main" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></th>
            <th class="main" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></th>
          </tr>
	   </thead>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="main" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="main" align="center">';
        if ($orders_history['customer_notified'] == '3') {
      echo '<i class="fa fa-envelope-o" style="margin-right:5px;"></i></td>'."\n";
    } 
	 elseif ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    }else {
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
      <?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
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
<!-- *** BEGIN GOOGLE CHECKOUT *** -->
<?php 
//require_once(DIR_FS_CATALOG . 'googlecheckout/inserts/admin/orders3.php');
?>
<!-- *** END GOOGLE CHECKOUT *** -->
  
       </div>
      </form>

 
          <?php if (MODULE_SHIPPING_LABEL_UPSWS_STATUS == "true") : ?>
              <ul class="links-7">
				 
              <?php echo '<li><a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a></li> 
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a></li>
			  <li><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a></li>
			  <li><a href="' . tep_href_link('http://www.usps.com') . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a></li> 
			  <li><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a></li> '; ?></ul>
              
             
         
          <?php else: ?>
            <?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, 'oID=' . $_GET['oID']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']. '&action=email') . '" TARGET="_blank">' . tep_image_button('button_send.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a><a href="' . tep_href_link(FILENAME_ORDERS_FEDEX_SHIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_fedex_shipping.gif', 'Ship with Fedex') . '</a><a href="' . tep_href_link('http://www.usps.com') . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?>
          <?php endif; ?>
      
  
<?php
  } else {
?>
<!-- orders page 1 header -->

<title>Orders</title>
<style>.dataTableRow{height:40px;}
    form{margin: 0px;}
</style>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<h1 class="pageHeading" style="display:inline-block;"><?php echo HEADING_TITLE; ?></h1>
        <div class="form-group column-12">
            <div class="row">
            <?php echo tep_draw_form('search-cname', FILENAME_ORDERS, '', 'get', 'class="column-sm-4"'); ?>
            <div id="search-cust-email" class="smallTex">
                <?php echo '<input type="text" name="search-cname" class="form-control" placeholder="Search by Customer Name" autocomplete="off">' . "\n"; ?>
            </div>  <?php echo'</form>'; ?>
            <?php echo tep_draw_form('search-cemail', FILENAME_ORDERS, '', 'get', 'class="column-sm-4"'); ?>
                <div id="search-cust-email" class="smallText">
                    <?php echo '<input type="text" name="search-cemail" class="form-control" placeholder="Search by Customer Email" autocomplete="off">' . "\n"; ?> </div>   
            </form>
            <?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get', 'class="column-sm-4"'); ?>
            
            <div id="order-status" style="width:100%; display:table;">
                <?php echo '<div style="display:table-cell; margin-right:10px;">'. HEADING_TITLE_STATUS . '</div>' . '<div style="display:table-cell;">'. tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'.'class="form-control"'); ?></div>
            <?php echo tep_hide_session_id(); ?></div></form>
            </div> 
        </div>
<div class="column-12">
    <div class="row">
            <div class="column-sm-5 column-md-4 form-group"><a style="display:block;" href="client_search.php" class="orders-searchproducts"><div >Search Orders by Product</div></a></div>

            <div class="column-sm-4 column-md-3 form-group">
                <?php echo tep_draw_form('search-amount', FILENAME_ORDERS, '', 'get'); ?>
                    <input name="search-amount" class="form-control" placeholder="Search by Amount" autocomplete="off" />
                </form>
            </div>
    </div>
</div>
              
              <div class="select-all" style="display:none;">
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
              
 <div id="orders-container" class="table-responsive">  
  
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
<table class="table-orders table-bordered table-hover table table-striped" id="dataTables">
<thead>
             <tr class="dataTableHeadingRow">
              	<th class="dataTableHeadingContent multiple-status-hide" style="width:1%;"><?php echo '' ?></th>
                <th class="dataTableHeadingContent" align="center" ><?php echo TABLE_HEADING_CUSTOMERS; ?></th>
                <th class="dataTableHeadingContent" align="center" style="text-align:center;">OID</th>
                <?php if ($_GET['status'] == '112'){
						echo '<th class="dataTableHeadingContent" align="center" >Tracking Sent</th>'; } else {
						echo '<th class="dataTableHeadingContent" align="center">'.TABLE_HEADING_DATE_PURCHASED.'</th>'; }?>
                <th class="dataTableHeadingContent" align="center" ><?php echo TABLE_HEADING_ORDER_TOTAL; ?></th>
		        <th class="dataTableHeadingContent" align="center" ><?php echo 'Amount Charged' ?></th>
                <th class="dataTableHeadingContent" align="center" ><?php echo 'Payment Method' ?></th>
                <th class="dataTableHeadingContent" align="right" style="text-align:center;"><?php echo TABLE_HEADING_STATUS; ?></th>
                <td class="dataTableHeadingContent" align="center" style="min-width:200px;"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
              </tr>
              </thead>

<?php
$search = '';
		  
if(isset($_GET['year'])){
	$year = 'year(o.date_purchased) = '.$_GET['year'];
} else {
	$year = 'year(o.date_purchased) > 2019';
}		  

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

    if (isset($_GET['search-cname']) && tep_not_null($_GET['search-cname'])) {
      	$keywords = tep_db_input(tep_db_prepare_input($_GET['search-cname']));
     	$search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' and (o.customers_name like '%" . $keywords . "%' or o.delivery_name like '%" . $keywords . "%') ";
    } elseif (isset($_GET['search-cemail'])) {
      	$keywords = tep_db_input(tep_db_prepare_input($_GET['search-cemail']));
      	$search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' and o.customers_email_address like '%" . $keywords . "%' ";  
    } elseif(isset($_GET['search-amount'])) {
		$search = "WHERE o.orders_status = s.orders_status_id AND ot.class = 'ot_total' AND ot.value = '".$_GET['search-amount']."'";      
    } else {
	  	$search = "WHERE $year AND o.orders_status = s.orders_status_id AND ot.class='ot_total' ";  
    }
    
     if ($_GET['status'] == '112') {
		$orders_query_raw = "select o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '112' and ot.class = 'ot_total' order by ". $db_orderby  . " " . $sort ;
	  
      $orders_query_raw2 = "select o.ups_track_num, o.usps_track_num, o.fedex_track_num, o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, osh.date FROM (SELECT orders_id, MAX(date_added) as date FROM orders_status_history GROUP BY orders_id) osh LEFT JOIN orders o ON o.orders_id = osh.orders_id left join orders_total ot on (o.orders_id = ot.orders_id), orders_status s  where o.orders_status = '112' and o.orders_status = s.orders_status_id and ot.class = 'ot_total' ORDER BY osh.date DESC";
	  
	 } elseif (isset($_GET['cID'])) {
	    // search on order id
      $cID = tep_db_prepare_input($_GET['cID']);
//      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' . order by ". $db_orderby . " " . $sort ;
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";

    } elseif (isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0) && (!($_GET['status'] =='112')) && ($check_admin['admin_groups_id'] != '9') ) {
		 // search on status of order
		 $status = tep_db_prepare_input($_GET['status']);
		 $orders_query_raw = "select DISTINCT o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by ". $db_orderby  . " " . $sort ;
    } elseif(isset($_GET['status']) && is_numeric($_GET['status']) && ($_GET['status'] > 0) && (!($_GET['status'] =='112')) && ($check_admin['admin_groups_id'] == '9')) {
      $status = tep_db_prepare_input($_GET['status']);
      $user_email = $check_admin['admin_email_address'];
      $orders_query_raw = "select DISTINCT o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' and o.customers_email_address = '". $user_email."' order by ". $db_orderby  . " " . $sort ;
    } elseif($check_admin['admin_groups_id'] == '9') {
      $user_email = $check_admin['admin_email_address'];
      $orders_query_raw = "SELECT o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, o.customers_email_address from orders o LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), orders_status s ".$search." and o.customers_email_address = '". $user_email."' and s.orders_status_id = 137 order by ". $db_orderby . " " . $sort;
    } else {
		 // search on orders statur and customer name ( if search field is filled )
		 $orders_query_raw = "SELECT o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.date_paid, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, o.customers_email_address from orders o LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), orders_status s ".$search."  order by ". $db_orderby . " " . $sort;
    }
    if ($_GET['status'] == '112'){
        $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw2, $orders_query_numrows);
	   $orders_query = tep_db_query($orders_query_raw2); 
	} else {
        $orders_split = new splitPageResultsPagin($orders_query_raw, MAX_DISPLAY_SEARCH_RESULTS, 'o.orders_id', $_GET['page']);    
        $orders_query = tep_db_query($orders_split->sql_query);
  }
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

<?php  $customer_name = $orders['customers_name'];
	   $_customer_name  = tep_db_input($customer_name);

	  $cust_order_created_query = tep_db_query('select date_purchased from orders where customers_id = "'.$orders['customers_id'].'" and orders_status = "3" LIMIT 1');
	  $cust_order_created = tep_db_fetch_array($cust_order_created_query);
	  
	  $verified_range = date('Y-m-d h:m:s', strtotime("-90 days"));
        
        $check_if_verified_query = tep_db_query("select c.verified from customers c, orders o where c.customers_id = o.customers_id and o.orders_id = '".$orders['orders_id']."'");
        $check_if_verified = tep_db_fetch_array($check_if_verified_query);
        
        if($check_if_verified['verified'] == '1'){
            $cust_check_placeholder = '
            <td>
                <div style="display:table;">
                    <div style="display:table-cell">
                        <i class="fa fa-check-circle" style="color: #0C0;"></i>
                    </div>
                    <div style="display:table-cell; padding-left:7px;">
                        <a href="'.tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'"><span>' . $orders['customers_name'].'</span></a>
                    </div>
               </div>
            </td>';   
        } elseif ($check_if_verified['verified'] == '2'){
            $cust_check_placeholder = '
            <td>
                <div style="display:table;">
                    <div style="display:table-cell">
                        <i class="fa fa-question-circle" style="color:#FFB848;"></i>
                    </div>
                    <div style="display:table-cell; padding-left:7px;">
                        <a href="'.tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'"><span>' . $orders['customers_name'].'</span></a>
                    </div>
               </div>
            </td>';
        } else {
            if ($verified_range > $cust_order_created['date_purchased'] && tep_db_num_rows($cust_order_created_query) > 0){
                $cust_check_placeholder = '
                <td>
                    <div style="display:table;">
                        <div style="display:table-cell">
                            <i class="fa fa-check-circle" style="color: #0C0;"></i>
                        </div>
                        <div style="display:table-cell; padding-left:7px;">
                            <a href="'.tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'"><span>' . $orders['customers_name'].'</span></a>
                        </div>
                   </div>
                </td>';
        
            } else {
                $cust_check_placeholder = '
                <td>
                    <div style="padding-left:10px;">
                        <a href="'.tep_href_link(FILENAME_ORDERS_EDIT,'oID='.$orders['orders_id']).'"><span>'. $orders['customers_name'].'</span>
                        </a>
                    </div>
                </td>';
            }
        }
	  ?>
      

            <td class=" multiple-status-hide">
                <input type="checkbox" name="update_oID[]" value="<?php echo $orders['orders_id'];?>">
            </td>
          <?php echo $cust_check_placeholder; ?>
    
                <td class="" align="right" style="text-align:center;"><?php echo $orders['orders_id']; ?></td>
                <?php if ($_GET['status'] == '112'){
					$shipping_date_query = tep_db_query("SELECT o.orders_id, MAX(osh.date_added) as date2 FROM orders_status_history osh LEFT JOIN orders o ON o.orders_id = osh.orders_id where o.orders_status = '112' and o.orders_id = '".$orders['orders_id']."' ORDER BY osh.date_added ASC");
					$shipping_date = tep_db_fetch_array($shipping_date_query);
				
					$check_date3 = new DateTime($shipping_date['date2']);
					
					$check_date4 = $check_date3->format('m/d/yy');
					echo '<td class="" align="center" >'. $check_date4.'</td>';
					} else { echo'<td class="" align="center" >'.tep_datetime_short($orders['date_purchased']) .'</td>'; } ?>
                <td class="" align="center"><?php echo strip_tags($orders['order_total']); ?></td>
<?php 
$amount_paid_query = tep_db_query("select oph.orders_id, sum(`payment_value`) as total_paid, oph.payment_type_id, ops.payment_type from ".TABLE_ORDERS_PAYMENT_HISTORY." oph , ".TABLE_ORDERS_PAYMENT_STATUS." ops where orders_id ='" . $orders['orders_id']. "' and ops.payment_type_id = oph.payment_type_id");
while ($amount_paid = tep_db_fetch_array($amount_paid_query)) {
 $paid = $amount_paid['total_paid'];
 $paid_name = $amount_paid['payment_type'];
}
        $carrier = '';
if ($orders['ups_track_num'] !== NULL){
	$carrier = '<a onclick="return !window.open(this.href);" href="https://wwwapps.ups.com/WebTracking/track?trackNums='.$orders['ups_track_num'].'&track.x=Track"><b>Track Via UPS</b></a>';
}
if ($orders['usps_track_num'] !== NULL){
	$carrier = '<a onclick="return !window.open(this.href);" href="https://tools.usps.com/go/TrackConfirmAction?tLabels='.$orders['usps_track_num'].'"><b>USPS</b></a>';
}
if ($orders['fedex_track_num'] !== NULL){
	$carrier = '<a onclick="return !window.open(this.href);" href="https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber='.$orders['fedex_track_num'].'&cntry_code=us"><b>FedEx</b></a>';
	
}
?>
                <td class="" align="center" ><?php if ($paid > 0) {
				echo '$'.@number_format($paid,'2','.','');}
				else {;} ?></td>
                <td class="" align="center" ><?php echo $paid_name; ?></td>
                <?php if ($_GET['status'] == '112'){ 
                 echo'<td class="" align="right" style="text-align:center;">'.$orders['orders_status_name'].'<span style="font-weight:bold; padding-top:10px; display:block;">'.$carrier.'</span></td>';
				} else {
                 echo'<td class="" align="right" style="text-align:center; font-weight:600;" >'.$orders['orders_status_name'].'</td>'; } ?>
                 <td class="" align="right"><?php echo '
                 <div class="row">
                    <div class="column-4">
                        <a href="'. tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit')  .'" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="View">'.'<i class="fa fa-eye">'.'</i>'.'</a>
                    </div>
                    <div class="column-4">
                        <a href="' .  tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary">'.'<i class="fa fa-pencil">'.'</i>'.'</a>
                    </div>
                    <div class="column-4">
                        <a href="' .  tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') .'" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger" >'.'<i class="fa fa-trash-o">'.'</i>'.'</a>
                    </div>
                </div>'; ?>
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
      /*if (isset($oInfo) && is_object($oInfo)) {
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
	$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link('http://www.usps.com') . '" TARGET="_blank">' . tep_image_button('button_labelshipping.gif', IMAGE_ORDERS_USPS_SHIP) . '</a>');
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
      } */
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="10%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }

$get_dates_query = tep_db_query("SELECT YEAR(date_purchased) AS year FROM orders GROUP BY year ORDER BY year DESC");
		  while($get_dates = tep_db_fetch_array($get_dates_query)){
			  $years[] = array('id'=> $get_dates['year'], 'text'=> $get_dates['year']);
		  }
		  
?>
</div>
</form>
<form id="yearPage" method="get">
<div class="column-12">
	<div class="row">
		<div class="column-12 column-md-6" style="line-height:65px;">
        	<?php if ($_GET['status'] == '112'){
			  echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
		  } else {
			  echo $orders_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS);
		  }?>
		</div>
		<div class="column-12 column-md-6">
        <?php 
		if ($_GET['status'] == '112'){
			echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action')));
		} else {
		  echo $orders_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(['page', 'info', 'x', 'y'])).' 
			
			<div class="" style="text-align:right;"><label>Year</label> '. tep_draw_pull_down_menu('year', $years, '' ,'class="form-control yearChange" style="display:inline-block; width:95px"').'</div>';
		}
		?>
		</div>
	</div>
</div>
</form>
<script>
$('.yearChange').on("change", function(){
	var form = $('#yearPage');
	form.submit();
})
</script>
	

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
