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
.dataTableHeadingContent {
    color: #ffffff;
    font-weight: bold;
}
.dataTableHeadingRow {background-color: #3D464E;}
body{font-size:13px;}

</style>

<table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
 
  <tr>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
   
              <tr>
            <td class="main" align="center"><b><img style="width:100%; max-width:250px;" src="<?php echo $logo_name; ?>" alt="logo"></img></b></td>
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
                <td style="text-align:center;"><b><?php echo 'Shipping Address'; ?></b></td>
              </tr>
            
              <tr> 
                <td style="text-align:center;">1500 N US HWY 1<br>
                JUPITER, FL 33469</td>
              </tr>
               
            </table></td>
      </tr>
    
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
 foreach($_GET['pid'] as $pid => $pID){
	$qty = $_GET['qty'][$pID]; 
	 
	 $products_query = tep_db_query ("select pd.products_name, p.products_upc from products p, products_description pd where p.products_id = pd.products_id and p.products_id = '".$pID."' ");
	 $products = tep_db_fetch_array($products_query);
	 
     echo '<tr class="dataTableRow">' . "\n";
	 $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pID . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
          echo '<td class="dataTableContent">&nbsp;</td>
		  <td class="dataTableContent" valign="top"><b>'.$products['products_name'].'</b>' ;
		  if ($products_attributes['total'] > 0) {  
		  	echo'<span style="float:left;"></span>' . "\n"; }
	 	  else { 
		  	echo '<span style="">&nbsp;&nbsp;x</span>'.$qty.'' . "\n" ; }

	if (isset($_GET['pattr'])) {
		foreach ($_GET['pattr'] as $patid => $pattID){
	$attributes_query = tep_db_query("select pa.products_id, popt.products_options_name, poval.products_options_values_name, pa.options_serial_no from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $pID . "' and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id and pa.products_attributes_id = '".$pattID."'");
        	while($attributes = tep_db_fetch_array($attributes_query)){
				if ($attributes['products_id'] == $pID){		  
				$qty1 = $_GET['attQty'][$pattID]; 
			   
          		echo '<br><nobr> - ' . $attributes['products_options_name'] . ': ' . $attributes['products_options_values_name'] . ' &nbsp;x' . $qty1.'';
          		echo '</nobr>';
        		}
			
			}
		}
	}
	  	$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pID . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    	if ($products_attributes['total'] > 0) {
			echo '</td><td class="dataTableContent">';
			foreach ($_GET['pattr'] as $patid => $pattID){
	$attributes_query = tep_db_query("select pa.products_id, popt.products_options_name, poval.products_options_values_name, pa.options_serial_no from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $pID . "' and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id and pa.products_attributes_id = '".$pattID."'");
        		while($attributes = tep_db_fetch_array($attributes_query)){
					if ($attributes['products_id'] == $pID){	
					$options_serial_no = $attributes['options_serial_no'];		  
					echo '<br><nobr>'.$options_serial_no.'</nobr>' . "\n";
					}
				}
			}
		
		} else {
      echo '        </td><td class="dataTableContent">'.$products['products_upc'].'</td>' . "\n";
		}
	  
          echo '      </tr>' . "\n";
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
<p style="text-align:left; margin-top:15px; font-weight:bold;">Additional Comments:</p>
<p ><?php echo nl2br($comments); ?></p>
<p>&nbsp;</p>
<hr></td>
</tr>
            <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
            <td style="color:#000; font-family: Verdana,Arial,sans-serif; font-size: 14px; text-align:center;"><h3>When order has shipped please email tracking info to customersupport@jupiterkiteboarding.com</h3></td>
            </tr>
</table>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
