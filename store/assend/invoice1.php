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

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
$email_text='
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html '. HTML_PARAMS .'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset='. CHARSET.'">
<title>'. TITLE.'</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->
<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>'.tep_draw_separator() .'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
            <td style="color: #89C238; font-family: Verdana,Arial,sans-serif; font-size: 16px; font-weight: bold">'. nl2br(STORE_NAME_ADDRESS).'</td>

        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
            <td class="main" width="40%"><b><img src="../../images/store_logo.png" alt="Jupiter"></img></b></td>
              </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>




  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2">'. tep_draw_separator().'</td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="main"><b>'. ENTRY_SOLD_TO.'</b></td>
              </tr>
              <tr> 
                <td class="main">'. tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>').'</td>
              </tr>
              <tr> 
                <td>'. tep_draw_separator('pixel_trans.gif', '1', '5').'</td>
              </tr>
              <tr> 
                <td class="main">'. $order->customer['telephone'].'</td>
              </tr>
              <tr> 
                <td class="main"><a style="text-decoration:none;" href="#" onclick="email();">' . $order->customer['email_address'] . '</a></td>
              </tr>
		<tr>
		<td class="main"><b>'. ENTRY_IPADDRESS.'</b> '.$order->customer['ipaddy'].'</td>
		</tr>
		<tr>
		<td class="main"><b>'. ENTRY_IPISP.'</b> '. $order->customer['ipisp'].'</td>
		</tr>
            </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
              <tr> 
                <td class="main"><b>'. ENTRY_SHIP_TO.'</b></td>
              </tr>
              <tr> 
                <td class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>').'</td>
              </tr>
            </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>'. tep_draw_separator('pixel_trans.gif', '1', '10').'</td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b>'. ENTRY_PAYMENT_METHOD.'</b></td>
        <td class="main">'. $order->info['payment_method'].'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>'. tep_draw_separator('pixel_trans.gif', '1', '10').'</td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow"> 
          <td class="dataTableHeadingContent" colspan="2">'. TABLE_HEADING_PRODUCTS.'</td>
          <td class="dataTableHeadingContent">'. TABLE_HEADING_PRODUCTS_MODEL.'</td>
          <td class="dataTableHeadingContent" align="right">'. TABLE_HEADING_TAX.'</td>
          <td class="dataTableHeadingContent" align="right">'. TABLE_HEADING_PRICE_EXCLUDING_TAX.'</td>
          <td class="dataTableHeadingContent" align="right">'. TABLE_HEADING_PRICE_INCLUDING_TAX.'</td>
          <td class="dataTableHeadingContent" align="right">'. TABLE_HEADING_TOTAL_EXCLUDING_TAX.'</td>
          <td class="dataTableHeadingContent" align="right">'. TABLE_HEADING_TOTAL_INCLUDING_TAX.'</td>
        </tr>';

    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      $email_text .='<tr class="dataTableRow">
             <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]["qty"] . '&nbsp;x</td>
              <td class="dataTableContent" valign="top">' . $order->products[$i]["name"];

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
           $email_text .= "<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']";
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          $email_text .= '</i></small></nobr>';
        }
      }

      $email_text .= '</td>
                 <td class="dataTableContent" valign="top">' . $order->products[$i]["model"] . '</td>
               <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]["tax"]) . '%</td>';

      $email_text .= '<td class="dataTableContent" align="right" valign="top"><b>';
 $email_text .= $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']); 

 $email_text .='</b></td>';

         $email_text .= ' <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]["final_price"], $order->products[$i]["tax"]), true, $order->info["currency"], $order->info["currency_value"]) . '</b></td>
              <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]["final_price"] * $order->products[$i]["qty"], true, $order->info["currency"], $order->info["currency_value"]) . '</b></td>
                 <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]["final_price"], $order->products[$i]["tax"]) * $order->products[$i]["qty"], true, $order->info["currency"], $order->info["currency_value"]) . '</b></td>
     </tr>';
    }
$email_text .= '
          <td align="right" colspan="8">&nbsp;</td>
        </tr>
        <tr> 
          <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">';

  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
   $email_text .= '          <tr>
                 <td align="right" class="smallText">' . $order->totals[$i]["title"] . '</td>
                <td align="right" class="smallText">' . $order->totals[$i]["text"] . '</td>
                </tr>';
  }

 $email_text .= '           </table>
            <p>&nbsp;</p>'. tep_draw_separator().'</td>
        </tr>
      </table></td>
  </tr>
</table>
<!-- body_text_eof //-->

<br>
</body>
</html>';
echo $email_text;
 require(DIR_WS_INCLUDES . 'application_bottom.php') ?>
<script type="text/javascript">  
function email(){  
<?php tep_mail($order['customers_name'], $order->customer['email_address'], 'Invoice Order number '. $oID , $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); ?>
}
</script> 
