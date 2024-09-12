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

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body style="background:#fff" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<script>
$('#close').click(function () {
		$(this).hide();
		$('#success').hide();
	});
	</script>
<div id="close" style="float:right; margin-right:15px; cursor:pointer;"><i class="fa fa-times" style="font-size:35px;"></i></div>

<form id="dropship">
<div style="text-align:center;">Ready to Send?<button style="width: 150px; height: 50px; font-size: 20px;">Yes</button> <span></span></div>
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
              <tr><td align="center"><h2 style="text-transform:uppercase;">Order</h2></td></tr>
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
                <td style="text-align:center;"><b><?php echo 'Shipping Address'; ?></b></td>
              </tr>
            
              <tr> 
                <td style="text-align:center;">1500 N US HWY 1<br>
                JUPITER, FL 33469</td>
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
      <td width="7%"></td>
        <td class="dataTableHeadingContent" ><?php echo 'Products'; ?></td>
        <td class="dataTableHeadingContent" colspan="2"><?php echo 'UPC'; ?></td>
        
      </tr>
<?php
     foreach($_POST['pid'] as $pid => $pID){
	$qty = $_POST['qty'][$pID]; 
	 
	 $products_query = tep_db_query ("select pd.products_name, p.products_upc from products p, products_description pd where p.products_id = pd.products_id and p.products_id = '".$pID."' ");
	 $products = tep_db_fetch_array($products_query);
	 
      echo '      <tr class="dataTableRow">' . "\n";
	 if (isset($_POST['pattr'])) { echo'<td class="dataTableContent" valign="top" align="right">'.'<span style="float:left;"></span><input type="hidden" name="qty['.$pID.']" value='.$qty.'></td>' . "\n"; }
	 else { echo '<td class="dataTableContent" valign="top" align="right">'.'<span style="float:left;">x</span><input type="hidden" name="qty['.$pID.']" value='.$qty.'>'.$qty.'</td>' . "\n" ; }
          echo '<td class="dataTableContent" valign="top">' . $products['products_name']. '<input type="hidden" name="pid['.$pID.']" value="'.$pID.'">' ;

      if (isset($_POST['pattr'])) {
	foreach ($_POST['pattr'] as $patid => $pattID){
	$attributes_query = tep_db_query("select pa.products_id, popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $pID . "' and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id and pa.products_attributes_id = '".$pattID."'");
        while($attributes = tep_db_fetch_array($attributes_query)){
		if ($attributes['products_id'] == $pID){		  
		$qty1 = $_POST['qty'][$pattID]; 
			   
          echo '<br><nobr> - ' . $attributes['products_options_name'] . ': ' . $attributes['products_options_values_name'] . ' &nbsp;x' . $qty1.'';
          echo '</nobr><input type="hidden" name="pattr['.$pattID.']" value="'.$pattID.'"><input type="hidden" name="attQty['.$pattID.']" value="'.$qty1.'">';
        }
		  }
	}
      }
      echo '        </td><td class="dataTableContent">'.$products['products_upc'].'</td>' . "\n" .
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
            <td style="color:#000; font-family: Verdana,Arial,sans-serif; font-size: 14px; text-align:center;"><h3>When order has shipped please email tracking info to <a href="mailto:customersupport@jupiterkiteboarding.com">customersupport@jupiterkiteboarding.com</a></h3></td>
            </tr>
</table>
</form>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
