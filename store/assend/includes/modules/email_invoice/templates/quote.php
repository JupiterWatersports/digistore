<?php
/*
  $Id: invoice.php,v 1.6 2003/06/20 00:37:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$comments = $_GET['comments'];
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style type="text/css">
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
<!-- body_text //-->
<table border="0" style="width:700px;" align="center" cellspacing="0" cellpadding="2">
<tr>
<td>
<div style="float:left; width:50%;"><img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></div>
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
            <td class="dataTableContent"><b><?php echo 'Sold To'; ?></b></td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo $order->customer['telephone']; ?></td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo $order->customer['email_address'] ; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="dataTableContent"><b><?php echo 'Ship To'; ?></b></td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
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
<td class="dataTableHeadingContent" style="color: #fff;"><?php echo 'Products'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Unit Price'; ?></td>
<td class="dataTableHeadingContent" align="right" style="color: #fff;"><?php echo 'Quantity'; ?></td>
        </tr>
       <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n";
         if (($order->products[$i]['id'] == '3658') || ($order->products[$i]['id'] == '4132')){
			 echo ' <td class="dataTableContent" valign="top"><b style="text-transform:uppercase;">' . $order->products[$i]['name'].'</b>'; }
			 else { echo
           '        <td class="dataTableContent" valign="top"><a style="text-decoration:underline;" href="http://www.jupiterkiteboarding.com/store/product_info.php?products_id='.$order->products[$i]['id'] . '"><b style="text-transform:uppercase;">' . $order->products[$i]['name'].'</b></a>'; }

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

			 if ((!$order->products[$i]['msrp'] == NULL) && $order->products[$i]['msrp'] > 0 && $order->products[$i]['msrp']<>$order->products[$i]['price'] ) {
			
			  echo '<tr class="dataTableRow"><td>&nbsp;</td><td><div class="dataTableContent" style="font-size:9pt;"><span>MSRP:  </span>$'.@number_format($order->products[$i]['msrp'],2,'.','').'</div>   </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			  }   
			   ?>
                
                 <?php } ?>
        <tr style="border-top: 1px solid #ccc;">
        <td class="dataTableContent" align="right" colspan="8">
        <table border="0" cellspacing="0" cellpadding="2">
        <tbody>
<?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
      </tbody>  </table>
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
				} ?>
<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?>
<p style="text-align:center;">The prices listed above all come with our <a style="font-size:15px; font-family: Verdana, Arial, sans-serif; font-weight:normal;" target="_new" href="http://www.jupiterkiteboarding.com/store/pricematch"><u>Price Match Guarantee</u>.</a>We can only hold these prices for 48 hours so please contact us immediately if you would like to proceed.</p>
            
    <?php $get_order_total_query = tep_db_query("select ROUND(value, 2) as val from orders_total where orders_id = '".$oID."' and class='ot_total'");
        $get_order_total = tep_db_fetch_array($get_order_total_query); ?>        
            
            <h2 style="text-align:center; text-transform: uppercase;"><a href="https://www.paypal.me/jupiterkiteboarding/<?php echo $get_order_total['val'];?>">Click Here to Pay Now</a></h2>
            <p>&nbsp;</p>
           <hr>
           
            </td>
</tr>
</table>
<tr><td>   
<p style="text-align:left;">Additional Comments:</p>
<p>&nbsp;</p>
<p style="text-align:left;"><?php echo nl2br($comments); ?>  </p>
            <p>&nbsp;</p>
<hr></td>
</tr>
<tr>
<td  style="text-align:center; color:#000;" class="dataTableContent"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
</tr>


</table>
<!-- body_text_eof //-->

<br>
</body>
</html>

