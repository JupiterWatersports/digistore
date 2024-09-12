<?php
/*
  $Id: packingslip.php,v 1.7 2005/11/01 00:40:10 hpdl Exp $   
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
 $action = $_GET['action'];
 $outEmailAddr = $_GET['email-to'];
 $comments = $_GET['comments'];

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
if(isset($_GET['pid'])){  if (($action == 'duh')){ 

   
  require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . 'email_dropship.php');
  echo '<h3>Email Sent</h3>';
  tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input('1') . "', 
				now(), 
				" . tep_db_input('3') . ", 
				'" . 'Drop Ship Request Sent'  . "')");
				
				tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '1', 
                      last_modified = now() 
                      WHERE orders_id = '" . $oID . "'"); ?>
  <script src="ext/jquery/jquery.js" type="text/javascript"></script>
    <script>
localStorage.setItem("update", "3");

$(document).ready(function() {
localStorage.setItem("update", "4");
window.location.href="dropshiprequest.php?oID=<?php echo $oID;  ?>";
    })
	</script> <?php 
 }} 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>DropShip Request #<?php echo $oID; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">



<form id="dropship">
<div style="text-align:center;">Ready to Send?<button style="width: 150px; height: 50px; font-size: 20px;">Yes</button> <span></span></div>
<input type="hidden" name="oID" value="<?php echo $oID;?>">
<input type="hidden" name="action" value="duh">
<!-- body_text //-->
<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
   <tr><td>Email To:&nbsp;<input name="email-to" class="form-control" placeholder="Enter Email Address Here" required="required"></td></tr>
    </table></td>
  </tr>
  <tr>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
   
              <tr>
            <td class="main" align="center"><b><img src="images/jup-kitepaddlewake.png" alt="Jupiter" width="200"></img></b></td>
              </tr>
              <tr><td align="center"><h2 style="text-transform:uppercase;">Drop Ship Request</h2></td></tr>
        </td>
   
    </table></td>
  </tr>




  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><?php echo tep_draw_separator(); ?></td>
      </tr>
      <tr>
   
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
             
              <tr> 
                <td style="font-size: 13px;"><b><?php echo 'Shipping Address'; ?></b></td>
              </tr>
            
              <tr> 
                <td style="font-size: 13px;"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
               <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
                <tr> 
                <td style="font-size: 13px;"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr> 
                <td style="font-size: 13px;"><?php echo $order->customer['email_address']; ?></td>
              </tr>
            </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>

  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2" id="productsTable-nobrder">
      <tr class="dataTableHeadingRow">
      <td width="5%"></td>
        <td class="dataTableHeadingContent" colspan="2"><?php echo 'Products'; ?></td>
        
      </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
	   '        <td class="dataTableContent" valign="top" align="right">'.tep_draw_checkbox_field('pid['.$order->products[$i]['oPid'].']', ''.$order->products[$i]['oPid'].'', '', "1").'</td>' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . '&nbsp;x'.$order->products[$i]['qty']. '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j=0, $k=sizeof($order->products[$i]['attributes']); $j<$k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '  ' . ($order->products[$i]['attributes'][$j]['serial_no']?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }
      echo '        </td>' . "\n" .
           '      </tr>' . "\n";
    }
?>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><?php echo tep_draw_separator(); ?></td>
  </tr><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?>
<tr><td>
<p style="text-align:left; margin-top:15px;">Additional Comments:</p>
<p style="text-align:center;"><textarea name="comments" style="width:100%; height:150px;"></textarea></p>
            <p>&nbsp;</p>
<hr></td>
</tr>
            <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
            <td style="color:#000; font-family: Verdana,Arial,sans-serif; font-size: 14px; text-align:center;"><h3>When item has shipped please email tracking info to <a href="mailto:customersupport@jupiterkiteboarding.com">customersupport@jupiterkiteboarding.com</a></h3></td>
            </tr>
</table>
</form>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
