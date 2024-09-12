<?
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');

if(isset($_GET['year'])){
    $year_selected = $_GET['year'];
} else {
    $year_selected = date("Y");
}

?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title>Jeremy's First 3 Days Taxes Page</title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
	</head>
<!-- body //-->
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style>
.table>thead>tr>th {vertical-align:middle;}
a.tooltip span {
    z-index: 10;
    display: none;
    padding: 14px 20px;
    margin-top: -30px;
    margin-left: 28px;
    width: 340px;
    line-height: 16px;
}
a.tooltip:hover span {
    display: inline;
    position: absolute;
    color: #111;
    border: 1px solid #DCA;
    background: #fffAF0;
}
</style>
<div id="wrapper-edit-order">
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
		<div style="height:20px; clear: both;"></div>
								<h1 class="pageHeading form-group"><?php echo 'Jeremy\'s First 3 Days Taxes Page'; ?></h1>
								<div class="form-group"></br>
                                </br></div>


	<div id="responsive-table">
<table class="table table-striped table-bordered table-hover dataTable" >						
<?
if ( ! $_REQUEST['print'] ) {
}
?>

<?
/*	$years_query = tep_db_query( "SELECT DISTINCT( year( o.date_purchased ) ) AS y FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE o.orders_status <>4 and o.orders_status <>109 ORDER BY date_purchased DESC" );
	while ( $years = tep_db_fetch_array( $years_query ) ) { */
		if ( $count > 0 ) {

		}
?>
    <td class="pageHeading" colspan="4" style="border:0px;"><?php echo $year_selected; ?></td>
						
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="8.33%">Month</td>
							<td class="dataTableHeadingContent" width="8.33%">1. Gross Sales (A)</td>
							<td class="dataTableHeadingContent" width="8.33%">2. Exempt Sales (A)</td>
                            <td class="dataTableHeadingContent" width="8.33%">3. Taxable Sales/Purchases (A)</td>
							<td class="dataTableHeadingContent" width="8.33%">4. Tax Collected (A)</td>
                            <td class="dataTableHeadingContent" width="8.33%">(5) Total Tax Collected</td>
                            <td class="dataTableHeadingContent" width="8.33%">(6) Less Lawful Deductions</td>
                            <td class="dataTableHeadingContent" width="8.33%">(7) Total Tax Due</td>
                            <td class="dataTableHeadingContent" width="8.33%">(8) </td>
                            <td class="dataTableHeadingContent" width="8.33%">(9) </td>
                            <td class="dataTableHeadingContent" width="8.33%">(10) Amount Due</td>
                            <td class="dataTableHeadingContent" width="8.33%">15(a). </td>
							<td class="dataTableHeadingContent" width="8.33%">15(b). </td>  
                            <td class="dataTableHeadingContent" width="8.33%">15(c). </td>   
                            <td class="dataTableHeadingContent" width="8.33%">15(d). </td>
							
						</tr>
<?

        $months_query = tep_db_query( "SELECT DISTINCT( monthname( o.date_purchased ) ) AS month, month( o.date_purchased ) AS m FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE (o.orders_status <>4 or o.orders_status <>109) and date_purchased LIKE '" . $year_selected . "-%'  ORDER BY date_purchased DESC" );

        $total_total = 0;
		//while ( $months = tep_db_fetch_array( $months_query ) ) {
		$orders1_query = tep_db_query("SELECT DISTINCT(o.orders_id) FROM orders o left join orders_total ot on (o.orders_id = ot.orders_id) WHERE (o.orders_status <>4 or o.orders_status <>109) and (ot.class ='ot_tax' and ot.value >'0') AND o.date_purchased >= '2021-09-01 00:00:00' AND o.date_purchased < '2021-09-04 00:00:00' ORDER BY date_purchased DESC" );
		$running1_net_total = 0;
		$running1_tax_total = 0;
		$running1_shipping_total = 0;	
			while ($orders1 = tep_db_fetch_array( $orders1_query )){
		$net1_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = '".$orders1['orders_id']."' AND  class = 'ot_subtotal' " );
		$net1_total = tep_db_fetch_array( $net1_total_query );
		$shipping1_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = '" . $orders1['orders_id'] . "' AND class = 'ot_shipping'" );
		$shipping1_total = tep_db_fetch_array( $shipping1_total_query );
		$tax1_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = '" . $orders1['orders_id'] . "' AND class = 'ot_tax'" );
		$tax1_total = tep_db_fetch_array( $tax1_total_query );
		$running1_net_total += $net1_total['total'];
		$running1_shipping_total += $shipping1_total['total'];
		$running1_tax_total += $tax1_total['total'];
			
			}	
			
			
		if (($year_selected > '2014') && ($months['m'] > '7') || ($year_selected > '2015')){
			
			
					
	    $net_total_query = tep_db_query( "SELECT SUM(oph.payment_value) AS total FROM orders o, orders_payment_history oph  WHERE o.orders_id = oph.orders_id and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 AND o.date_purchased >= '2021-09-01 00:00:00' AND o.date_purchased < '2021-09-04 00:00:00'");
		$net_total = tep_db_fetch_array( $net_total_query );
		
		
		/* if (($year_selected > '2016')){
		$taxable_total_query = tep_db_query("SELECT sum(oph.payment_value) as total FROM  orders_payment_history oph, orders o WHERE o.orders_id = oph.orders_id and (o.orders_status <>4 or o.orders_status <>109) and oph.tax_value > '0.00' and oph.payment_type_id <> 5 and year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']."");
		$taxable_total = tep_db_fetch_array($taxable_total_query);
		
		} else {
		$taxable_total_query = tep_db_query("SELECT sum(oph.payment_value) as total FROM  orders_payment_history oph, orders_total ot, orders o WHERE ot.orders_id  = oph.orders_id and o.orders_id = oph.orders_id and (o.orders_status <>4 or o.orders_status <>109) and (ot.class ='ot_tax' and ot.value >'0') and oph.payment_type_id <> 5 and year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']."");
		$taxable_total = tep_db_fetch_array($taxable_total_query);
		} */
		
		
		$tax_total_query = tep_db_query( "SELECT SUM( ot.value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 AND o.date_purchased >= '2021-09-01 00:00:00' AND o.date_purchased < '2021-09-04 00:00:00' AND ot.class = 'ot_tax'" );
		$tax_total = tep_db_fetch_array( $tax_total_query );
		
		
		 
			$no_tax_total_query =  tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status <>4 and o.orders_status <>109 AND o.date_purchased >= '2021-09-01 00:00:00' AND o.date_purchased < '2021-09-04 00:00:00' AND (ot.class ='ot_tax' and ot.value > '0')" );
		$no_tax_total = tep_db_fetch_array($no_tax_total_query );
		
		

		$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 AND o.date_purchased >= '2021-09-01 00:00:00' AND o.date_purchased < '2021-09-04 00:00:00' AND ot.class = 'ot_shipping'");
		$shipping_total = tep_db_fetch_array($shipping_total_query );

		
		
		
		} 
		
		
		if (($year_selected > '2014') && ($months['m'] > '7') || ($year_selected > '2015')){
		$gross_sales = $net_total['total'] - $tax_total['total'];
		
			if (($year_selected > '2016')){
				
			$taxabletotal = $gross_sales - $no_tax_total['total'];
				
			$salesnotax = $no_tax_total['total'];
			
			} else {	
			$taxabletotal = $gross_sales - $no_tax_total['total'];
			$salesnotax = $no_tax_total['total'];
			}
		$discretionary_tax = $six_point_five_tax_total + $seven_tax_total;
		} else {
		$gross_sales = $net_total['total'] + $shipping_total['total'];
		$taxabletotal = $running1_net_total + $shipping_total['total'];
		$salesnotax = $gross_sales - $taxabletotal;
		}
		
		$loadacrap = (($taxabletotal - $real_six_total - $fifteen_c_total)) * 0.01;
		$fifteen_d_total = $loadacrap + $fifteen_c_prime_total;				
		$total_total += $gross_sales;
?>
					
						<tr class="dataTableRow">
							<td class="dataTableContent">September 1st-3rd</td>
							<td class="dataTableContent">$<?= number_format( $gross_sales, 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format( $salesnotax, 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format($taxabletotal, 2 ) ?></td>
                            <td class="dataTableContent">$<?= number_format($tax_total['total'], 2 ) ?></td>
                            <td class="dataTableContent">$<?= number_format($tax_total['total'], 2 ) ?></td>
                            <td class="dataTableContent">N/A</td>
                            <td class="dataTableContent">$<?= number_format($tax_total['total'], 2 ) ?></td>
                            <td class="dataTableContent">N/A</td>
                            <td class="dataTableContent">N/A</td>
                            <td class="dataTableContent"><?= number_format($tax_total['total'], 2 ) ?></td><?php //  6% // ?> 
                            <td class="dataTableContent">N/A</td><?php //  15(a) // ?> 
                            <td class="dataTableContent">$<?= number_format($real_six_total,2); ?></td><?php //  6% // ?> 
                            <td class="dataTableContent">$<?= number_format($fifteen_c_total,2); ?></td><?php // everything but 7% total // ?>
                            <td class="dataTableContent">$<?= number_format($fifteen_d_total, 2); ?></td>
                            
                            
                            
                            
						</tr>
					
                    
<? 
			$count ++;
		//}
		echo '<tr>
							<td class="dataTableContent">Total</td>
							<td class="dataTableContent">$'.number_format($total_total,2).'</td>
						</tr>	';
/*	} */

?>
        </table>
  		
        </div>

    
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>
  		
