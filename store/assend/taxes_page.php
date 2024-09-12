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
		<title>Taxes Page</title>
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
								<h1 class="pageHeading form-group"><?php echo 'Taxes Page'; ?></h1>
								<div class="form-group"><a target="_blank" href="https://ritx-fl-sales.bswa.net">Click here to Pay Taxes</a></br>
                                </br><span>Username:&nbsp;af1345570501</span></br><span>pw:&nbsp;13631571</div>
	<div class="form-group">
	<span>View Previously Paid Taxes
		<a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i>
			<span>1. Click "Sales and Use Tax" button</br>
			2. Click "Reprint Confirmation Page(s)"</br>
		3. Click on corresponding Confirmation</br>&nbsp;&nbsp;&nbsp;&nbsp;Number link for desired month</span>
		</a>
		</span>
	</div>
<form id="datePick">
<div class="col-xs-12">
    <div class="row">
        
    <?php $year = date("Y");
        $z = $year - 2007;
        $years_array = array();
        for ($i = 0; $i<$z+1; $i++){
            $correct_year = $year - $i;
            $years_array[] = array('id' => $correct_year, 'text' => $correct_year);
        }
            
        echo tep_draw_pull_down_menu('year', $years_array, $_GET['date'], 'id="selectYear" class="form-control" style="width:250px;"');  
    ?>        
        
    </div>
</div>
</form>

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
		while ( $months = tep_db_fetch_array( $months_query ) ) {
		$orders1_query = tep_db_query("SELECT DISTINCT(o.orders_id) FROM " . TABLE_ORDERS. " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE (o.orders_status <>4 or o.orders_status <>109)  and (ot.class ='ot_tax' and ot.value >'0') and year(o.date_purchased ) = '".$year_selected."' AND monthname( o.date_purchased ) = '".$months['month']."' ORDER BY date_purchased  DESC" );
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
			
			
					
	    $net_total_query = tep_db_query( "SELECT SUM(oph.payment_value) AS total FROM orders o, orders_payment_history oph  WHERE o.orders_id = oph.orders_id and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $year_selected . " AND month( oph.date_paid ) = " . $months['m'] . "");
		$net_total = tep_db_fetch_array( $net_total_query );
		
		
		/* if (($year_selected > '2016')){
		$taxable_total_query = tep_db_query("SELECT sum(oph.payment_value) as total FROM  orders_payment_history oph, orders o WHERE o.orders_id = oph.orders_id and (o.orders_status <>4 or o.orders_status <>109) and oph.tax_value > '0.00' and oph.payment_type_id <> 5 and year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']."");
		$taxable_total = tep_db_fetch_array($taxable_total_query);
		
		} else {
		$taxable_total_query = tep_db_query("SELECT sum(oph.payment_value) as total FROM  orders_payment_history oph, orders_total ot, orders o WHERE ot.orders_id  = oph.orders_id and o.orders_id = oph.orders_id and (o.orders_status <>4 or o.orders_status <>109) and (ot.class ='ot_tax' and ot.value >'0') and oph.payment_type_id <> 5 and year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']."");
		$taxable_total = tep_db_fetch_array($taxable_total_query);
		} */
		
		if (($year_selected > '2016')){

		$tax_total_query = tep_db_query( "SELECT SUM(oph.tax_value) AS total FROM  orders o, orders_payment_history oph  WHERE  o.orders_id= oph.orders_id  and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $year_selected . " AND month( oph.date_paid ) = " . $months['m'] . "" );
		$tax_total = tep_db_fetch_array( $tax_total_query );
		} else {
		$tax_total_query = tep_db_query( "SELECT SUM( ot.value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $year_selected . " AND month( oph.date_paid ) = " . $months['m'] . "  AND ot.class = 'ot_tax'" );
		$tax_total = tep_db_fetch_array( $tax_total_query );
		}
		
		if (($year_selected > '2016')){
		$no_tax_total_query =  tep_db_query( "SELECT sum(oph.payment_value) as total FROM  orders_payment_history oph, orders o WHERE o.orders_id = oph.orders_id and o.orders_status NOT IN ('4', '109') and oph.tax_value = '0.00' and oph.payment_type_id <> 5 and year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']."" );
		$no_tax_total = tep_db_fetch_array($no_tax_total_query );
		
		$get_messed_up_order_info_query = tep_db_query("select o.orders_id, sum(oph.payment_value) as payment, ot.value, sum(oph.tax_value), oph.orders_payment_history_id, ot.orders_total_id, oph.tax_rate from orders o, orders_payment_history oph, orders_total ot where year( oph.date_paid ) = ".$year_selected." AND month( oph.date_paid )  = ".$months['m']." and o.orders_status NOT IN ('4', '109') and o.orders_id = oph.orders_id and oph.tax_value > '0' and o.orders_id = ot.orders_id and ot.class = 'ot_tax' GROUP BY o.orders_id
ORDER BY o.orders_id  DESC");
		
		$real_six_total = 0;
		$real_six_point_five_tax_total = 0;
		$real_seven_tax_total = 0;
		$real_seven_point_five_tax_total = 0;
		$real_eight_tax_total = 0;
		while($get_messed_up_order_info = tep_db_fetch_array($get_messed_up_order_info_query)){
			$estimated_tax_rate_round1 = (100 * (($get_messed_up_order_info['payment'])/($get_messed_up_order_info['payment'] - $get_messed_up_order_info['value']))) - 100;
			$estimated_tax_rate = @number_format($estimated_tax_rate_round1, 2,'.','');
			
			if(($estimated_tax_rate == '6.0') || ($get_messed_up_order_info['tax_rate'] == '6.5')){
				$real_six_total += $get_messed_up_order_info['payment'];	
			}
			
			
			if(($estimated_tax_rate == '6.5') || ($get_messed_up_order_info['tax_rate'] == '6.5')){
				$real_six_point_five_tax_total += $get_messed_up_order_info['payment'];
				
			}
			
			if(($estimated_tax_rate == '7.0') || ($get_messed_up_order_info['tax_rate'] == '7.0')){
				$real_seven_tax_total += $get_messed_up_order_info['payment'];
				
			}
			
			if(($estimated_tax_rate == '7.5') || ($get_messed_up_order_info['tax_rate'] == '7.5')){
				$real_seven_point_five_tax_total += $get_messed_up_order_info['payment'];
				
			}
			
			if(($estimated_tax_rate == '8.0') || ($get_messed_up_order_info['tax_rate'] == '8.0')){
				$real_eight_tax_total += $get_messed_up_order_info['payment'];
				
			}
		}
		
		$fifteen_c_total = $real_six_point_five_tax_total + $real_seven_point_five_tax_total + $real_eight_tax_total;
		$fifteen_c_prime_total = ($real_six_point_five_tax_total * 0.005) + ($real_seven_point_five_tax_total * 0.0015) + ($real_eight_tax_total * 0.02);
		
		
			
		} else {
			$no_tax_total_query =  tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status <>4 and o.orders_status <>109 and year( o.date_purchased ) = " . $year_selected . " AND month( o.date_purchased )  = " . $months['m'] . "  AND (ot.class ='ot_tax' and ot.value > '0')" );
		$no_tax_total = tep_db_fetch_array($no_tax_total_query );
		
		
		
		}

		$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and o.orders_status NOT IN ('4', '109') and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $year_selected . " AND month( oph.date_paid ) = " . $months['m'] . "  AND ot.class = 'ot_shipping'");
		$shipping_total = tep_db_fetch_array($shipping_total_query );

		
		
		
		} else {
		
		$net_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status NOT IN ('4', '109') and year( o.date_purchased ) = " . $year_selected . " AND month( o.date_purchased ) = " . $months['m'] . "  AND ot.class = 'ot_subtotal' " );
		$net_total = tep_db_fetch_array( $net_total_query );

		$no_tax_total_query =  tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status NOT IN ('4', '109') and year( o.date_purchased ) = " . $year_selected . " AND month( o.date_purchased )  = " . $months['m'] . "  AND (ot.class ='ot_tax' and ot.value > '0')" );
		$no_tax_total = tep_db_fetch_array( $no_tax_total_query );

		$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status NOT IN ('4', '109') and year( o.date_purchased ) = " . $year_selected . " AND month( o.date_purchased ) = " . $months['m'] . "  AND ot.class = 'ot_shipping'" );
		$shipping_total = tep_db_fetch_array( $shipping_total_query );

		$tax_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND o.orders_status NOT IN ('4', '109') and year( o.date_purchased ) = " . $year_selected . " AND month( o.date_purchased )  = " . $months['m'] . "  AND ot.class = 'ot_tax'" );
		$tax_total = tep_db_fetch_array( $tax_total_query );
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
							<td class="dataTableContent"><?= $months['month'] ?></td>
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
		}
		echo '<tr>
							<td class="dataTableContent">Total</td>
							<td class="dataTableContent">$'.number_format($total_total,2).'</td>
						</tr>	';
/*	} */

?>
        </table>
  		
        </div>

    <script>
        $('#selectYear').on("change",  function(){
            var form = $('#datePick');
            form.submit();
        })
    </script>
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>
  		
