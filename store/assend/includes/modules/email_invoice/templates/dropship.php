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


 $comments = $_GET['comments'];


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>DropShip Request #<?php echo $oID; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style type="text/css">
#productsTable, #productsTable-nobrder{ width:100%; margin-bottom: 20px; border-collapse:collapse; border-spacing:0px;}
#productsTable>thead>tr>th, #productsTable-nobrder>thead>tr>th {text-align:left;line-height: 1.42857143;}
#productsTable>thead>tr>td,#productsTable>tbody>tr>td{padding:8px;line-height:1.428571429;vertical-align:top;border-top:1px solid #ddd}
#productsTable-nobrder>thead>tr>td,#productsTable-nobrder>tbody>tr>td{padding:8px;vertical-align:top;}
.dataTableContent {
    font-family: Verdana, Arial, sans-serif;
    font-size: 10pt;
    color: #000000;
}
    .pageHeading img{width:205px; height:auto;}
</style>

<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
 
  <tr>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
   
              <tr>
            <td class="main" align="center"><b><img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></b></td>
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
                <td><b><?php echo 'Shipping Address'; ?></b></td>
              </tr>
            
              <tr> 
                <td><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
               <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
                <tr> 
                <td><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr> 
                <td><?php echo $order->customer['email_address']; ?></td>
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
  foreach($_GET['pid'] as  $pID ) {	 
    $orders_products_query = tep_db_query("select op.orders_products_id, op.products_id, op.products_name, op.products_model,  op.products_msrp, op.products_price, op.products_tax, op.products_quantity, op.final_price, p.products_image from " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_PRODUCTS." p where orders_id = '" . $oID . "' and op.products_id=  p.products_id and op.orders_products_id ='".$pID. "'");
  while ($orders_products = tep_db_fetch_array($orders_products_query)){
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . '&nbsp;x'.$orders_products['products_quantity']. '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $orders_products['products_name'];

      $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix, serial_no from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
         while ($attributes = tep_db_fetch_array($attributes_query)){
	
          echo '<br><nobr><small>&nbsp;<i> - ' . $attributes['products_options'] . ': ' . $attributes['products_options_values'] . '';
          echo '</i></small></nobr>';
        }
      }
      echo '        </td>' . "\n" .
           '      </tr>' . "\n";
    } }
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
<p style="text-align:left; margin-top:15px; font-weight:bold;">Additional Comments:</p>
<p>&nbsp;</p>
<p style="text-align:left;"><?php echo nl2br($comments); ?></p>
<p>&nbsp;</p>
<hr></td>
</tr>
            <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
            <td style="color:#000; font-family: Verdana,Arial,sans-serif; font-size: 14px; text-align:center;"><h3>When item has shipped please email tracking info to customersupport@jupiterkiteboarding.com</h3></td>
            </tr>
</table>
</form>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
