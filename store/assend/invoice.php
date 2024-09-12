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
echo "<script type='text/javascript'>alert('Invoice has been emailed');</script>";
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
	require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . FILENAME_EMAIL_INVOICE);
}

$save_query = tep_db_query("select products_msrp, products_price, products_id from orders_products where orders_id= '".$oID."' and products_id<>3658");
    
        $prices_array = array();
    while ($save = tep_db_fetch_array($save_query)){
        if ($save['products_msrp'] > $save['products_price']){
            $prices_array[] = array('id' => $save['products_id'], 'msrp'=> $save['products_msrp']);
        } else {
            $prices_array[] = array('id' => $save['products_id'], 'msrp' => '0');
        }
    }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo STORE_NAME; ?>Inoice Date><?php echo date("y"); ?>invoice no. <?php echo $oID; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->
<script type="text/javascript">
<!--
window.print();
//-->
</script>	
	
<!-- body_text //-->
<table border="0" width="800px;" align="center" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading" align="center">
        <img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></td>
        </tr>
        <tr>
      <td align="center"><strong><?php echo 'Order Number:&nbsp;' .$oID; ?></strong></td></tr>
      <tr>
      <td align="center"><strong><?php echo'Order Date:&nbsp;'. tep_date_short($order->info['date_purchased']); ?></strong></td></tr>
      <tr><td>&nbsp;</td>
        
      </tr>
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
            <td class="main"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
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
            <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
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
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
      <?php 
$amount_paid_query = tep_db_query("select oph.orders_id, sum(`payment_value`) as total_paid, oph.payment_type_id, ops.payment_type from ".TABLE_ORDERS_PAYMENT_HISTORY." oph , ".TABLE_ORDERS_PAYMENT_STATUS." ops where orders_id ='" . $oID. "' and ops.payment_type_id = oph.payment_type_id");
while ($amount_paid = tep_db_fetch_array($amount_paid_query)) {
 $paid_name = $amount_paid['payment_type'];
}
?>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $paid_name; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
<tr>
<td><table id="productsTable-nobrder">
<tr class="dataTableHeadingRow" style="background-color: #3D464E;">
<td class="dataTableHeadingContent" style="color: #fff;"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
<td class="dataTableHeadingContent" style="color: #fff;"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Unit Price'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Quantity'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Total'; ?></td>
</tr>
       <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n".
           '        <td class="dataTableContent" valign="top"><b style="text-transform:uppercase;">' . $order->products[$i]['name'].'</b>';

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          
      echo ' '. ($order->products[$i]['attributes'][$j]['serial_no']?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
          '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
	 '        <td class="dataTableContent" valign="top" align="center">' . '&nbsp;x'.$order->products[$i]['qty'] . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
      echo '      </tr>' . "\n";

			if ((!$order->products[$i]['msrp'] == NULL) && $order->products[$i]['msrp'] > 0 && $order->products[$i]['msrp']<>$order->products[$i]['price'] ) {
			
			  echo '<tr class="dataTableRow"><td>&nbsp;</td><td><div class="dataTableContent" style="font-size:9pt;"><span>MSRP:  </span>$'.@number_format($order->products[$i]['msrp'],2,'.','').'</div>   </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			  }  
			   ?>
                
                 <?php } ?>
        <tr>
        <td class="dataTableContent" align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    $title = strpos($order->totals[$i]['title'],'FedEx') !== 0 ? $order->totals[$i]['title']  : 'Shipping Fee:' ;
    echo '          <tr>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $title . '</td>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table>
  <?php   $check_save_query = tep_db_query("select sum(products_msrp) as msrptot, sum(final_price) as finprice, products_msrp, final_price from orders_products where orders_id= '".$oID."' and products_msrp<>final_price  and products_id<>3658");
			  $check_save= tep_db_fetch_array($check_save_query);
		      if ((!$check_save['products_msrp'] == NULL) && (!($check_save['products_msrp'] ==  $check_save['final_price']))) {
			  		$save_query = tep_db_query("select * from orders_products where orders_id= '".$oID."' and products_msrp <> final_price and products_id<>3658");
				  
				  echo '<div style="text-align:right; margin-right:10px;" id="savings" class="form-horizontal form-group"><span>You Save:  </span>$';
				  $savings = 0;
			  		while($save= tep_db_fetch_array($save_query)){
				  
				   $savings += $save['products_quantity'] * ($save['products_msrp'] - $save['final_price']);
			 			if($savings > 0 ){
			  			
			  			}
					}
					echo @number_format($savings,2,'.','').'</div>';
				} ?></div>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        <tr>
         <td colspan="2"><?php echo tep_draw_separator(); ?></td>
         </tr>
         <tr>
          <td style="color:#000;" align="center"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
          </tr>


</table>
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
