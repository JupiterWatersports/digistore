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
echo "<script type='text/javascript'>alert('Agreement has been emailed');</script>";
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


	require('includes/modules/email_invoice/email_consign_agreement.php');
	tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input('120') . "', 
				now(), 
				" . tep_db_input('3') . ", 
				'" .'Consignment Agreement Sent'. "')");
				
tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '120', last_modified = now() WHERE orders_id = '" . $_GET['oID'] . "'"); 
?>
 <script src="ext/jquery/jquery.js" type="text/javascript"></script>
    <script>
localStorage.setItem("update", "3");

$(document).ready(function() {
localStorage.setItem("update", "4");
window.location.href="consign-invoice.php?oID=<?php echo $_GET['oID'] ;  ?>";
    })
	</script> 
<?php }

$check_sig_query = tep_db_query("select * from consign_table where order_id = ".$_GET['oID']."");
$check_sig = tep_db_fetch_array($check_sig_query);  

if (tep_db_num_rows($check_sig_query) !==0){
$date1 = $check_sig['signature_date'];
$middle = strtotime($date1);
$date = date('m/d/Y', $middle);
} else {
$date = date('m/d/Y');
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

<form id="quote">
<input type="hidden" name="oID" value="<?php echo $oID;?>">
<input type="hidden" name="action" value="email">
<input type="hidden" name="date" value="<?php echo $date; ?>">
<input type="hidden" name="cName" value="<?php echo $order->customer['name']; ?>">
<input type="hidden" name="signature" value="<?php $check_sig['signature'];?>">
<div style="text-align:center;">Ready to Send?<button style="width: 150px; height: 50px; font-size: 20px;">Yes</button> <span></span></div>
</form>
<!-- body_text //-->
<!-- body_text //-->
  <div style="width:600px; display:table; margin:0px auto;">
	<div style="width:100%; text-align:center;"><img style="width:100%; max-width:250px; text-align:center;"" src="images/jup-kitepaddlewake.png" alt="Jupiter"></div>
  <a class="close agree" style="font-size:16px; float:right;" onClick="toggleOverlay();"><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>
 <div class="col-xs-12">
<h1 style="text-align:center; text-transform:uppercase;">Consignment Agreement</h1>
<div class="form-group">Effective Date:     	<b><?php echo $date; ?></b> </div>


<div class="form-group" style="margin-bottom:20px;">Between     	<b>Jupiter Kiteboarding Inc.</b>, further referred to as "[Seller]", </div>

<?php //// <div class="form-group">A     	[State] [Type of legal entity],</div> ////

/// <div class="form-group">Located at     	[Address]      	[City], [State] [Zip Code]</div> //// ?>


<div class="form-group" style="margin-bottom:20px;">And	     	<b><?php echo $order->customer['name']; ?></b>, further referred to as "[Consignee]"</div>

<?php /// <div class="form-group">A	     	[State] [Type of legal entity],</div> \\\\

/////---<div class="form-group">Located at     	[Address]      	[City], [State] [Zip Code]</div> ?>


<div class="form-group">Both parties agree to the following terms:</div>
<ul>

<li><div class="form-group">The Seller agrees to handle all procedures pertaining to selling product(s) and deal with any and all shipping matters. </div></li>

<li><div class="form-group">The Seller is entitled to retain <b>40% of the sale price</b>.</div></li>

<li><div class="form-group">The Seller shall submit a check for <b>60% of the sale price</b> to the Consignee <u>unless (a) or (b) are applicable</u>,  within <b>15</b> business days upon completion of the sale.</div>
<ol type="a">
<li><div class="form-group">If item has already been listed on Ebay and Consignee wants product back a $20 removal fee will be charged and items will remain in possession of Seller until fee is paid. Seller reserves its statutory and any other lawful liens for unpaid charges.</div></li>

<li><div class="form-group">If item has to be repaired repair cost will be deducted from Consignee's percentage of sale price. Permitting approval of repair from Consignee before said repair is done by email.</div></li> 
</ol>

</li>

<li><div class="form-group">The Seller agrees to uphold the minimum price set by the consignee for each item sold, and will accept nothing less than the minimum agreed price for the consigned merchandise unless otherwise agreed upon with Consignee.</div></li>

<li><div class="form-group">The Seller will maintain insurance for any damage or theft that may occur to items left with the Seller. While the consigned items are in the possession of the Seller those items will be covered under the Seller's insurance policy.</div></li>

<li><div class="form-group">The Consignee agrees to leave merchandise with the Seller for a minimum of <b>14</b> Days.</div></li>

<li><div class="form-group">The Consignee further agrees to present only a high quality product to the Seller.</div></li>
 
<li><div class="form-group">All merchandise that is not sold at the end of the consignment timeframe will be evaluated by both the Seller and Consignee. The Seller and Consignee do hereby agree to the terms set forth above by their signatures found below.</div></li>
</ul>

<div class="form-group" style="text-align:center;"><h3>Applicable Law</h3></div>


<div class="form-group">This contract shall be governed by the laws of the State of Florida and any applicable Federal Law.</div>


<div class="form-group">
<div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid; position:relative; display:table;">
<div style="display:table-cell; width:50%;" >
<div><span style="margin-bottom: 5px; display: block; font-size: 20px; font-weight: bold;">Jupiter Kiteboarding Inc</span></div>
</div>
<div style="display:table-cell; width:50%;">
<?php echo $date; ?>
</div>
</div>
<div class="col-xs-6">
Signature of Seller</div>
<div class="col-xs-6">
Date
</div>

<div class="form-group" style="float: left; width: 100%; position:relative;"> 
<div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid; display:table;">


	
			<div style="text-align:left; margin-left:-5%; margin-bottom:-15px; display:table-cell; width:50%;">
				<img style="max-width:360px; height:auto;" src="<?php echo $check_sig['signature']; ?>"/>
			</div>
            
		
	
  
        <div style="display:table-cell; width:50%;"><div style="margin-bottom: 5px;"><?php echo $date; ?></div>
        </div>
        </div>
        <div class="col-xs-6" style="margin-top:10px;">Signature of Consignee</div>
        <div class="col-xs-6" style="margin-top:10px;">Date</div>
        </div>


</div>
</div>

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

			  $save_query = tep_db_query("select products_msrp from orders_products where orders_id= '".$oID."' and products_msrp<>final_price and products_id<>3658 and products_id ='".$order->products[$i]['id']."' ");
			  while ($save= tep_db_fetch_array($save_query)){
		      if ((!$save['products_msrp'] == NULL) && (($save['products_msrp'] > 0))) {
			
			  echo '<tr class="dataTableRow"><td>&nbsp;</td><td><div class="dataTableContent" style="font-size:9pt;"><span>MSRP:  </span>$'.@number_format($save['products_msrp'],2,'.','').'</div>   </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			  } } 
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
  <?php   $save_query = tep_db_query("select sum(products_msrp) as msrptot, sum(final_price) as finprice, products_msrp, final_price from orders_products where orders_id= '".$oID."' and products_msrp<>final_price and products_id<>3658 ");
while ($save = tep_db_fetch_array($save_query)){
if ((!$save['products_msrp'] == NULL) && (!($save['products_msrp'] == $save['final_price']))) {
$savings = $save['msrptot'] - $save['finprice'];
?>
<div class="form-group" style="padding-top:20px;"><span>You Save:  </span>
<?php echo '$'.@number_format($savings,2,'.',''); }} ?></div>
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
