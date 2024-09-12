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
$comments = $_GET['comments'];
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
echo "<script type='text/javascript'>alert('Quote has been emailed');</script>";
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


	require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . FILENAME_EMAIL_QUOTE);
	
tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . tep_db_input($_GET['oID']) . "', '" . tep_db_input('122') . "', now(), " . tep_db_input('4') . ", '" . ($_GET['comments'])  . "')");

$check_status_query = tep_db_query("SELECT customers_name, customers_email_address, orders_status, date_purchased  FROM " . TABLE_ORDERS . "  WHERE orders_id = '" . $_GET['oID'] . "'");
						  
$check_status = tep_db_fetch_array($check_status_query);
if (($check_status['orders_status'] == 4 || $check_status['orders_status'] == 109)) {
				tep_restock_order((int)$oID,'remove'); }
				
				tep_db_query("UPDATE " . TABLE_ORDERS . " SET  orders_status = '122',  last_modified = now() WHERE orders_id = '" . $_GET['oID'] . "'");
                 ?>               
<script src="ext/jquery/jquery.js" type="text/javascript"></script>
<script>
localStorage.setItem("update", "3");

$(document).ready(function() {
localStorage.setItem("update", "4");
window.location.href="quote.php?oID=<?php echo $oID;  ?>";
    })
	</script><?php	
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>Quote #<?php echo $oID; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<form id="quote">
<input type="hidden" name="oID" value="<?php echo $oID;?>">
<input type="hidden" name="action" value="email">
<div style="text-align:center;">Ready to Send?<button style="width: 150px; height: 50px; font-size: 20px;">Yes</button> <span></span></div>
<!-- body_text //-->
<table border="0" width="800px;" align="center" cellspacing="0" cellpadding="2">
<tr>
<td>
<div style="float:left; width:50%;">
    <img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></div>
<div style="float:right width:50%; text-align:right;"><h1 style="font-size:46px; margin:10px 0px;">QUOTE</h1><strong>Order No. <?php echo $oID; ?></strong>
</div>
                     
</td>
</tr>

  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
       <td colspan="2"><hr></td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo 'Sold To'; ?></b></td>
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
            <td class="main"><?php echo $order->customer['email_address'] ; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo 'Ship To'; ?></b></td>
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
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
   
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
<td><table id="productsTable-nobrder">
<tr class="dataTableHeadingRow" style="background-color: #3D464E;">
<td class="dataTableHeadingContent" style="color: #fff;"><?php echo 'Model'; ?></td>
<td class="dataTableHeadingContent" style="color: #fff;"><?php echo 'Products'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Unit Price'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Quantity'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Total'; ?></td>
        </tr>
       <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n".
           '        <td class="dataTableContent" valign="top"><a style="text-decoration:underline;" href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'product_info.php?products_id='.$order->products[$i]['id'] . '"><b style="text-transform:uppercase;">' . $order->products[$i]['name'].'</b></a>';

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
    echo '          <tr>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table>
          <?php  $check_save_query = tep_db_query("select sum(products_msrp) as msrptot, sum(final_price) as finprice, products_msrp, final_price from orders_products where orders_id= '".$oID."' and products_msrp<>final_price  and products_id<>3658");
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
					echo $savings.'</div>';
				} ?></div>
<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?>
<p style="text-align:center;">The prices listed above all come with our <a style="font-size:15px; font-family: Verdana, Arial, sans-serif; font-weight:normal;" target="_new" href="http://www.jupiterkiteboarding.com/store/pricematch"><u>Price Match Guarantee</u>.</a>We can only hold these prices for 48 hours so please contact us immediately if you would like to proceed.</p>
            
            <p style="text-align:center;">For paypal payments please send payment to Jeremy@jupiterkiteboarding.com</p>
			<p style="text-align:center;">Please notify us if you would like to pay via credit card and we can send over a secure credit card authorization form.</p>
            <p>&nbsp;</p>
            <hr>
      
<p style="text-align:left;">Additional Comments:</p>
<p>&nbsp;</p>
<p style="text-align:center;"><textarea name="comments" style="width:100%; height:150px;"></textarea></p>
            <p>&nbsp;</p>
<hr></td>
</tr>
</table></td>
</tr>
<tr>
<td align="center" style="text-align:center; color:#000;"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
</tr>


</table>
</form>
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
