<?php

  
  $oID = tep_db_prepare_input($_GET['oID']);
	  
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

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
<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style>
.form-group{margin-bottom: 15px;}
.col-xs-6{width:50%; float:left;}
#productsTable, #productsTable-nobrder{ width:100%; margin-bottom: 20px; border-collapse:collapse; border-spacing:0px;}
#productsTable>thead>tr>th, #productsTable-nobrder>thead>tr>th {text-align:left;line-height: 1.42857143;}
#productsTable>thead>tr>td,#productsTable>tbody>tr>td{padding:8px;line-height:1.428571429;vertical-align:top;border-top:1px solid #ddd}
#productsTable-nobrder>thead>tr>td,#productsTable-nobrder>tbody>tr>td{padding:6px;vertical-align:top;}
.dataTableContent {
    font-family: Verdana, Arial, sans-serif;
    font-size: 10pt;
    color: #000000;
}
    .pageHeading img{width:205px; height:auto;}
</style>


  <div style="width:600px; display:table; margin:0px auto;">
  
	<div style="width:100%; text-align:center;"><img style="width:100%; max-width:250px; text-align: center" src="<?php echo $logo_name; ?>"></div>
 
 <div class="col-xs-12">
<h1 style="text-align:center; text-transform:uppercase;">Consignment Agreement</h1>
<div class="form-group" style="margin-bottom:10px;">Effective Date:     	<b><?php echo $date; ?></b> </div>


<div class="form-group" style="margin-bottom:20px;">Between     	<b>Jupiter Kiteboarding</b>, further referred to as "[Seller]", </div>

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
<div><span style="margin-bottom: 5px; display: block; font-size: 20px; font-weight: bold;">Jupiter Kiteboarding</span></div>
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
				<img style="max-width:300px; height:auto;" src="<?php echo $check_sig['signature']; ?>"/>
			</div>
            
		
	
  
        <div style="display:table-cell; width:50%;"><div style="margin-bottom: 5px;"><?php echo $date; ?></div>
        </div>
        </div>
        <div class="col-xs-6" style="margin-top:10px;">Signature of Consignee</div>
        <div class="col-xs-6" style="margin-top:10px;">Date</div>
        </div>


</div>
</div>

<div style="float:left; width: 100%; margin-bottom:25px;"></div>
<table id="productsTable-nobrder">
<tr class="dataTableHeadingRow">

<td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_PRODUCTS; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Price'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Quantity'; ?></td>
</tr>
       <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow" style="line-height: 20px">' . "\n" .
         
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
	 '        <td class="dataTableContent" valign="top" align="center">' . '&nbsp;x'.$order->products[$i]['qty'] . '</td>' . "\n";
      echo '      </tr>' . "\n";

			  
			   ?>
                
                 <?php } ?>
       
     
        <tr>
         <td colspan="2"><?php echo tep_draw_separator(); ?></td>
         </tr>
         </table>
         <div style="float:left; width: 100%; margin-bottom:25px; text-align:center;"><?php echo nl2br(STORE_NAME_ADDRESS); ?></div>
        
<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
