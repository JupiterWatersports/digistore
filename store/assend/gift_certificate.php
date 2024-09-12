<?php
/*
  $Id: invoice.php,v 1.6 2005/11/01 00:37:30 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce
   http://www.oscommerce.com   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
	  
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);	 
$date = date('M d, Y');
$action='';
$action = $_GET['action']; 

if ($action=='email') { 
echo "<script type='text/javascript'>alert('Gift Certificate has been emailed');</script>";
////

// Return a formatted address

// TABLES: customers, address_book

  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {

    if (is_array($address_id) && !empty($address_id)) {

      return tep_address_format($address_id['address_format_id'], $address_id, $html, $boln, $eoln);

    }



    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");

    $address = tep_db_fetch_array($address_query);



    $format_id = tep_get_address_format_id($address['country_id']);



    return tep_address_format($format_id, $address, $html, $boln, $eoln);

  }


	require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . 'email_certificate.php');
 ?> 
     <script src="ext/jquery/jquery.js" type="text/javascript"></script>
     <script>
     $(document).ready(function() {
window.location.href="gift_certificate.php?oID=<?php echo $oID;  ?>";
    })
	</script> <?php
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>Gift Certificate</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php echo tep_draw_form('send_gift','gift_certificate.php', 'oID=' . $oID. '&action=email'); ?><div style="text-align:center;">Ready to Send?<button style="width: 150px; height: 50px; font-size: 20px;">Yes</button> <span></span></div></form>
<!-- body_text //-->
<table border="0" cellpadding="0" cellspacing="0" width="1000px" align="center">
<tbody>
<tr>
<td align="right" class="hide_this" style="vertical-align: text-top;">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="100%">
<tbody>
<tr>
<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="379px">
<tr><td style="text-align:right;" valign="top"><img alt="" border="0" class="hide_this" height="100%" src="http://www.jupiterkiteboarding.com/images/gift_certificate-left.jpg" style="display: inline" width="100%"></td>
</tr>
</table>
</td>

<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="557px"> 
<tr><td><img alt="" border="0" class="hide_this" height="238" src="http://www.jupiterkiteboarding.com/images/gift_certificate-upper.jpg" style="display: inline" width="100%"></td></tr>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" style="height:397px">
<tr><td style="padding-top:15px; padding-left:20px;"><span style="font-size:20px;">To:</span>&nbsp;<?php echo '<span style="font-size:20px;">'.$order->delivery['name'].'</span>'; ?></td></tr>
<tr><td style="padding-top:20px; padding-left:20px;"><span style="font-size:14px;">Congratulations on receiving this Gift Certificate. Visit our web site at <a style="font-size:14px; color:#000;" href="http://www.jupiterkiteboarding.com"><u>www.jupiterkiteboarding.com</u></a> to see all of our products!  Please present this certificate when you are ready to redeem it! See you soon! - Jupiter Kiteboarding</span></td></tr>
<tr><td></td></tr>
<tr><td height="138" style="padding-left:20px;"><?php for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
echo '<span class="dataTableContent" style="vertical-align: text-top; font-size:20px;">' . $order->products[$i]['name']. '</span>';} ?>

<?php $total_query = tep_db_query("select class, text FROM " . TABLE_ORDERS_TOTAL . "  WHERE orders_id = '" . (int)$oID . "' AND class='ot_total'");
$total =  tep_db_fetch_array($total_query);
echo '<span style="font-size:40px; float:right;">' .$total['text'].'</span>';
 ?></td></tr>
<tr><td style="padding-top:10px;">Expires 1 year from:&nbsp;<?php echo tep_date_short($order->info['date_purchased']); ?>
</td></tr>
<tr><td style="padding-top:15px;">Order Number:&nbsp;<?php echo $oID; ?></td></tr>
<tr><td style="">&nbsp;</td></tr>
</table>
</td></tr>
<tr><td style="vertical-align: text-top;">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="100%">
<tr>
<td>
<img alt="" border="0" class="hide_this" style="height"183px;" src="http://www.jupiterkiteboarding.com/images/gift_certificate-lower.jpg" style="display: inline" width="100%"></td></tr>
</table>
</td>
</tr>
</table>
</td>
<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="159px">
<tr>
<td><img alt="" border="0" class="hide_this" height="100%" src="http://www.jupiterkiteboarding.com/images/gift_certificate-right.jpg" style="display: inline" width="100%">
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
