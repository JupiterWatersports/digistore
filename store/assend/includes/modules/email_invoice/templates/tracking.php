<?php
/*
  $Id: invoice.php,v 1.6 2003/06/20 00:37:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" style="background:#fff; font-size:14px;">

<!-- body_text //-->
<style>.dataTableContent, .main{font-size:14px;}
    .pageHeading img{width:205px; height:auto;}
</style>
<!-- body_text //-->
<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
<td>
      <div style="float:left; width:40%;"><img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></div>
      <div style="float:right; width:40%; text-align:right; padding-right:15px;"><h2 style=" margin:10px 0px;">Shipping Confirmation</h2><strong>Order No. <a target="_new" style="font-family:Verdana, Arial, sans-serif; font-size:14px; text-decoration:underline; color:#006699;" href="https://www.jupiterkiteboarding.com/store/account_history_info.php?order_id=<?php echo $oID; ?>"><?php echo $oID; ?></a></strong></div>
</td>
</tr>     

           <tr>
      <td width="100%"><hr size="2"></td>
                  </tr>
 
 
<tr>
<td>Thank you for shopping with us,<br/><br/>You will find your tracking number below and please note that it may take 24 hours for any updates to be available.</td>
</tr>
<tr><td>&nbsp;</td></tr>
<td>
<?php 
if(!empty($order->info['fedex_track_num'])) { 
echo $fedex_text . $fedex_track; }
if(!empty($order->info['usps_track_num'])) {
echo $usps_text . $usps_track;}
if(!empty($order->info['ups_track_num']))
{echo $ups_text . $ups_track;}
elseif (empty($order->info['fedex_track_num'])& empty($order->info['usps_track_num'])& empty($order->info['ups_track_num']) ){echo'Sorry we forgot your tracking number.';}?>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td><span style="color: #f44336;">All packages must be inspected upon delivery, check over all contents before signing off on the shipment. Do not sign for your shipment if there is any damage to your items, refuse the shipment and we will resolve the issue in a timely manner. Once you sign for your package(s) you assume all responsibility for any damage.</span></td></tr>

  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><hr></td>
      </tr>
      <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="main"><b><?php echo 'Shipped To'; ?></b></td>
              </tr>
              <tr> 
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="main"><b><?php echo 'Billed To'; ?></b></td>
              </tr>
              <tr> 
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr> 
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr> 
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr> 
                <td class="main"><?php echo $order->customer['email_address']; ?></td>
              </tr>
		
            </table></td>
       
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
   
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
     <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow" width="100%" style="height:30px;"> 
          <td width="100%" style="background-color: #39F; color:#FFF; padding-left:15px;">Order Summary</td>
        </tr>
        <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr><td style="border-bottom:1px solid #ddd; padding:13px 0px;"><table width="100%"><tr>';
 
   echo       '        <td class="dataTableContent" valign="top" width="70%"><span style="font-size:14px; display:block; margin-bottom:5px;">' . $order->products[$i]['name'].'</span>';
      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          
      echo ' '. ($order->products[$i]['attributes'][$j]['serial_no']?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }


      echo 
	 '        <td class="dataTableContent" valign="top" align="center"  width="15%">' . '&nbsp;x'.$order->products[$i]['qty'] . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top" width="15%"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '   </tr></table>  </td></tr>' . "\n";
    }
?>
        <tr>
          <td align="right" colspan="8"></td>
        </tr>
        <tr > 
          <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
              <?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr >' . "\n" .
         '            <td align="right" >' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" >' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '       </tr>' . "\n";
  }
?>
            </table>
            <p>&nbsp;</p><?php echo tep_draw_separator(); ?></td>
        </tr>
        
      </table></td>
  </tr><tr>
          <td  style="color:#000;" align="center"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
          </tr>
</table>
<!-- body_text_eof //-->

<br>
</body>
</html>
