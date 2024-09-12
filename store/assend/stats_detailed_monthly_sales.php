<?
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');

$taxcollected = $HTTP_GET_VARS['tax'];

$taxcollected = array();
  $taxcollected[] = array('id' => 1, 'text' => 'Yes');
  $taxcollected[] = array('id' => 2, 'text' => 'No');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
	</head>
<!-- body //-->
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<style>
.table>thead>tr>th {vertical-align:middle;}
</style>
<div id="wrapper">
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
		<div style="height:20px;"></div>
								<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
								<div style="height:20px;"></div>
	<div id="responsive-table">
<table class="table table-striped table-bordered table-hover dataTable" >						
<?
if ( ! $_REQUEST['print'] ) {
}
?>
  		
  			
  				<?
if ( isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) ) {
	if ( ! in_array( $_REQUEST['month'], explode( ',', MONTH_LIST ) ) ) {
?>
						<tr>
							<td class="pageHeading">Error!</td>
						</tr>
						<tr>
							<td class="dataTableContent">'<?= $_REQUEST['month'] ?>' is not a valid month!</td>
						</tr>
<?
	} else {
?>
						
							<h1 class="pageHeading" colspan="6"><?= $_REQUEST['year'] ?> &raquo; <?= $_REQUEST['month'] ?></h1>
						
							<div class="col-xs-12" style="padding:8px 0px; margin-bottom:15px;">
							<div class="col-xs-4"><a href="stats_detailed_monthly_sales.php">Back</a></div>
<div class="col-xs-4"><?php echo tep_draw_form('filter', 'stats_detailed_monthly_sales.php','year='.$_REQUEST['year'].'&month=' . $_REQUEST['month'], 'post');?> filter:<select name="taxdropdown" onChange="this.form.submit();">
<option value="all">All</option>
<option value="tax">With Tax</option>
<option value="no tax">No Tax</option>
</select></form>
</div>
							<div class="col-xs-4"><? if ( ! $_REQUEST['print'] ) { ?><a href="?year=<?= $_REQUEST['year'] ?>&amp;month=<?= $_REQUEST['month'] ?>&amp;print=true" target="_blank">Printer Friendly Version (New Window)</a></div><? } ?>
					</div>
					       <div style="height:20px;">&nbsp;</div>                 
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" widtd="5%">Order #</td>
							<td class="dataTableHeadingContent">Customer</td>
							<td class="dataTableHeadingContent">Date</td>
							<td class="dataTableHeadingContent">Status</td>
							<td class="dataTableHeadingContent">Order Subtotal</td>
							<td class="dataTableHeadingContent">Shipping Total</td>
							<td class="dataTableHeadingContent">Tax Total</td>
							<td class="dataTableHeadingContent">Order Total</td>
						</tr>

<?
$tax_choice = $_POST['taxdropdown'];
 if ($tax_choice == 'no tax'){
	$orders_query = tep_db_query( "SELECT DISTINCT(o.orders_id), o.customers_id, o.customers_name, o.orders_status, o.date_purchased FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE o.orders_status = '3'  and (ot.class ='ot_tax' and ot.value <='0') and year( date_purchased ) = " . $_REQUEST['year'] . " AND monthname( date_purchased ) = '" . $_REQUEST['month'] . "'  ORDER BY date_purchased DESC" ); } 

	elseif ($tax_choice == 'tax'){
		$orders_query = tep_db_query( "SELECT DISTINCT(o.orders_id), o.customers_id, o.customers_name, o.orders_status, o.date_purchased FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE o.orders_status ='3' and (ot.class ='ot_tax' and ot.value >'0') and year( date_purchased ) = " . $_REQUEST['year'] . " AND monthname( date_purchased ) = '" . $_REQUEST['month'] . "'  ORDER BY date_purchased DESC" ); } 
	else {
	$orders_query = tep_db_query( "SELECT DISTINCT(o.orders_id), o.customers_id, o.customers_name, o.orders_status, o.date_purchased FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE o.orders_status ='3' and year( date_purchased ) = " . $_REQUEST['year'] . " AND monthname( date_purchased ) = '" . $_REQUEST['month'] . "' ORDER BY date_purchased DESC" ); 
	}

	$running_net_total = 0;
	$running_tax_total = 0;
	$running_shipping_total = 0;
	while ( $orders = tep_db_fetch_array( $orders_query ) ) {
		$net_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = " . $orders['orders_id'] . " AND class = 'ot_subtotal'" );
		$net_total = tep_db_fetch_array( $net_total_query );
		$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = " . $orders['orders_id'] . " AND class = 'ot_shipping'" );
		$shipping_total = tep_db_fetch_array( $shipping_total_query );
		$tax_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total WHERE orders_id = " . $orders['orders_id'] . " AND class = 'ot_tax'" );
		$tax_total = tep_db_fetch_array( $tax_total_query );
		$running_net_total += $net_total['total'];
		$running_shipping_total += $shipping_total['total'];
		$running_tax_total += $tax_total['total'];
?>
						<tr class="dataTableRow">
							<td class="dataTableContent"><a href="invoice.php?oID=<?= $orders['orders_id'] ?>" target="_blank"><?= $orders['orders_id'] ?><a href="invoice.php?oID=<?= $orders['orders_id'] ?>"></td>
							<td class="dataTableContent"><?= $orders['customers_name'] ?></td>
							<td class="dataTableContent"><?= date( 'm/d/Y', strtotime( $orders['date_purchased'] ) ) ?></td>
<?php 
$orders_status_query = tep_db_query( "select orders_status_id, orders_status_name from orders_status where orders_status_id =".$orders['orders_status']." and language_id =1");
while ( $orders_status = tep_db_fetch_array( $orders_status_query) ) {
?>
							<td class="dataTableContent"><?= $orders_status['orders_status_name']; ?></td>
<?php } ?>
							<td class="dataTableContent">$<?= number_format( $net_total['total'], 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format( $shipping_total['total'], 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format( $tax_total['total'], 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format( $net_total['total'] + $shipping_total['total'] + $tax_total['total'], 2 ) ?></td>
						</tr>
<?
	}
?>
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" colspan="4">&nbsp;</td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_net_total, 2 ) ?></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_shipping_total, 2 ) ?></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_tax_total, 2 ) ?></td>
							<td class="dataTableHeadingContent">$<?= number_format( $running_net_total + $running_shipping_total + $running_tax_total, 2 ) ?></td>
						</tr>
<?
	}
} else {
	$count = 0;
	$years_query = tep_db_query( "SELECT DISTINCT( year( o.date_purchased ) ) AS y FROM orders o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE (o.orders_status <>4 or o.orders_status <>109) ORDER BY date_purchased DESC" );
	while ( $years = tep_db_fetch_array( $years_query ) ) {
		if ( $count > 0 ) {
?>
						<tr>
							<td class="dataTableContent">&nbsp;
						</tr>	
<?
		}
?>
						
							<td class="pageHeading" colspan="4" style="border:0px;"><?= $years['y'] ?></td>
						
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_MONTH ?></td>
							<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_TOTAL_SALES_NET ?></td>
							<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_TOTAL_SHIPPING ?></td>
							<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_TOTAL_TAX ?></td>
							<td class="dataTableHeadingContent" width="20%"><?= TABLE_HEADING_TOTAL_SALES_GROSS ?></td>
						</tr>
<?
			$months_query = tep_db_query( "SELECT DISTINCT( monthname( o.date_purchased ) ) AS month, month( o.date_purchased ) AS m FROM orders o /*left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id)*/ WHERE o.orders_status = '3' and date_purchased LIKE '" . $years['y'] . "-%' ORDER BY date_purchased DESC" );
		while ( $months = tep_db_fetch_array( $months_query ) ) {
			if (($years['y'] > '2014') && ($months['m'] > '7') || ($years['y'] > '2015')){
			
			$total_query = tep_db_query( "SELECT SUM(oph.payment_value) AS total FROM orders o, orders_payment_history oph  WHERE o.orders_id = oph.orders_id and (o.orders_status <>4 or o.orders_status <>109) and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $years['y'] . " AND month( oph.date_paid ) = " . $months['m'] . " " );
			$total = tep_db_fetch_array( $total_query );
			
			$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and (o.orders_status <>4 or o.orders_status <>109) and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $years['y'] . " AND month( oph.date_paid ) = " . $months['m'] . "  AND ot.class = 'ot_shipping'" );
			$shipping_total = tep_db_fetch_array( $shipping_total_query );
			$tax_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o, orders_payment_history oph  WHERE ot.orders_id = o.orders_id AND o.orders_id= oph.orders_id  and (o.orders_status <>4 or o.orders_status <>109) and oph.payment_type_id <> 5 and year( oph.date_paid ) = " . $years['y'] . " AND month( oph.date_paid ) = " . $months['m'] . "  AND ot.class = 'ot_tax'" );
			$tax_total = tep_db_fetch_array( $tax_total_query );
			} else {
			$net_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND (o.orders_status <>4 or o.orders_status <>109) and year( o.date_purchased ) = " . $years['y'] . " AND month( o.date_purchased ) = " . $months['m'] . "  AND ot.class = 'ot_subtotal'" );
			$net_total = tep_db_fetch_array( $net_total_query );
			$shipping_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND (o.orders_status <>4 or o.orders_status <>109) and year( o.date_purchased ) = " . $years['y'] . " AND month( o.date_purchased ) = " . $months['m'] . "  AND ot.class = 'ot_shipping'" );
			$shipping_total = tep_db_fetch_array( $shipping_total_query );
			$tax_total_query = tep_db_query( "SELECT SUM( value ) AS total FROM orders_total ot, orders o WHERE ot.orders_id=o.orders_id AND (o.orders_status <>4 or o.orders_status <>109) and year( o.date_purchased ) = " . $years['y'] . " AND month( o.date_purchased ) = " . $months['m'] . "  AND ot.class = 'ot_tax'" );
			$tax_total = tep_db_fetch_array( $tax_total_query ); }
?>
					
						<tr class="dataTableRow">
							<td class="dataTableContent"><a href="?year=<?= $years['y'] ?>&amp;month=<?= $months['month'] ?>"><?= $months['month'] ?></a></td>
                            <?php if (($years['y'] > '2014') && ($months['m'] > '7') || ($years['y'] > '2015')){ ?>
                            <td class="dataTableContent">$<?= number_format( $total['total'] - $shipping_total['total'] - $tax_total['total'], 2 ) ?></td>
                            <?php } else { ?> <td class="dataTableContent">$<?= number_format( $net_total['total'], 2 ) ?></td>
                            <?php } ?>
							<td class="dataTableContent">$<?= number_format( $shipping_total['total'], 2 ) ?></td>
							<td class="dataTableContent">$<?= number_format( $tax_total['total'], 2 ) ?></td>
							 <?php if (($years['y'] > '2014') && ($months['m'] > '7') || ($years['y'] > '2015')){ ?>
                             <td class="dataTableContent">$<?= number_format( $total['total'], 2 ) ?></td>
                            <?php } else { ?> <td class="dataTableContent">$<?= number_format( $net_total['total'] + $shipping_total['total'] + $tax_total['total'], 2 ) ?></td>
                            <?php } ?>
						</tr>
					
<? 
			$count ++;
		}
	}
}
?>
					</table>
  		
        </div>
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>
  		
