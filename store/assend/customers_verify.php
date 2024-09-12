b<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body>
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Verifiable Customers'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Number</td>
                <td class="dataTableHeadingContent">Customers Name</td>
                <td class="dataTableHeadingContent" align="right">Total Purchased&nbsp;</td>
              </tr>
<?php
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

// selects all customers even without payment history
/* $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_lastname, sum(ot.value) as ordersum from customers c, orders_total ot, orders o where c.customers_id = o.customers_id and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = '3' and ot.value > '1' group by o.customers_id HAVING COUNT(o.orders_id) > 0 order by ordersum DESC";  */

/* super specific by only payment history 
   $customers_query_raw = " select c.customers_id, c.customers_firstname, c.customers_lastname, sum(oph.payment_value) as ordersum from customers c, orders_payment_history oph, orders o where c.customers_id = o.customers_id and o.orders_id = oph.orders_id and o.orders_status = '3' group by o.customers_id HAVING COUNT(o.orders_id) > 0 AND SUM(oph.payment_value) > 0
ORDER BY `ordersum`  DESC "; */
                                
// First Get all customers //
                
    /* per big dogs instructions */
    $checker_date = new DateTime($get_all_customers['data']);
    $checker_date->sub(new DateInterval('P90D'));
    $verified_range = $checker_date->format('Y-m-d') . "\n";    
     
//completely weed out query        
 $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_lastname, sum(oph.payment_value) as ordersum, MAX(o.date_purchased) as date, ci.customers_info_date_account_created from customers c, orders_payment_history oph, orders o, customers_info ci where c.customers_id = o.customers_id and c.customers_id = ci.customers_info_id and o.orders_id = oph.orders_id and o.orders_status = '3' group by o.customers_id HAVING COUNT(o.orders_id) > 0 AND SUM(oph.payment_value) > 0 AND DATE_ADD(date, INTERVAL -120 DAY) > ci.customers_info_date_account_created ORDER BY ordersum DESC";  

// questionable query                 
 /* $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_lastname, sum(oph.payment_value) as ordersum, MAX(o.date_purchased) as date, ci.customers_info_date_account_created from customers c, orders_payment_history oph, orders o, customers_info ci where c.customers_id = o.customers_id and c.customers_id = ci.customers_info_id and o.orders_id = oph.orders_id and o.orders_status = '3' group by o.customers_id HAVING COUNT(o.orders_id) > 0 AND SUM(oph.payment_value) > 0 ORDER BY ordersum DESC";  */ 
                
// fix counted customers
  $customers_query_numrows = tep_db_query("select count(c.customers_id) from customers c, orders_payment_history oph, orders o where c.customers_id = o.customers_id and o.orders_id = oph.orders_id and o.orders_status = '3' group by o.customers_id HAVING COUNT(o.orders_id) > 0 AND SUM(oph.payment_value) > 0 and MAX(o.date_purchased) > '".$verified_range."'");
  $customers_query_numrows = tep_db_num_rows($customers_query_numrows);

  $rows = 0;
  $customers_query = tep_db_query($customers_query_raw);
  while ($customers = tep_db_fetch_array($customers_query)) {
	$check_ver_query = tep_db_query("SELECT verified FROM customers WHERE customers_id = '".$customers['customers_id']."'");  
	$check_ver = tep_db_fetch_array($check_ver_query);  
     
	if($check_ver['verified'] !== '1'){
		$verify_customers = tep_db_query("UPDATE customers SET verified = '1' where customers_id = '".$customers['customers_id']."'"); 
	}
    
    $weed_out_script = tep_db_query('select count(orders_id) as number from orders where customers_id = "'.$customers['customers_id'].'" and orders_status = "3"');
    $weed_out = tep_db_fetch_array($weed_out_script);
      
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }

                
               ?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL') . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($customers['ordersum']); ?>&nbsp;</td>
              </tr>
<?php
            
  }

?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
