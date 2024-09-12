<?php 
require 'includes/application_top.php';

	$startDate3 = strtotime($_POST['start-date']);
	
if($_POST['action'] == 'update_balance'){
//Check for differences in pay
	
	
	
	$startDate3 = strtotime($_POST['start-date']);
	$date = date('Y-m-d', $startDate3);
	$check_for_previous_entry = tep_db_query("SELECT * FROM daily_report_total WHERE date = '".$date."'");
	
	if(tep_db_num_rows($check_for_previous_entry) > 0){
		$update = array(
			'cc_total' => $_POST['cc_total'],
			'paypal_total' => $_POST['paypal_total'],
			'cash_total' => $_POST['cash_total'],
			'cash_drawer_total' => $_POST['cash_drawer_total'],
			'ebay_total' => $_POST['ebay_payments_total'],
			'signature' => $_POST['signature']
		);
		
		tep_db_perform("daily_report_total", $update, 'update', "date = '".$date."'");
		
		tep_redirect(tep_href_link('update_daily_balances.php', "start-date=$date"));
		
	} else {
	
		$update = array(
		'date' => $date,
		'cc_total' => $_POST['cc_total'],
		'paypal_total' => $_POST['paypal_total'],
		'cash_total' => $_POST['cash_total'],
		'cash_drawer_total' => $_POST['cash_drawer_total'],
		'signature' => $_POST['signature']);
		
		tep_db_perform("daily_report_total", $update); 
		tep_redirect(tep_href_link('update_daily_balances.php', "start-date=$date"));
	}
} else {
	$startDate3 = strtotime($_GET['start-date']);
}

	$endDate = strtotime('+1 day', $startDate3);

	$payment_status_query = tep_db_query("select payment_type_id, payment_type  from ".TABLE_ORDERS_PAYMENT_STATUS."");
	while ($payment_status = tep_db_fetch_array($payment_status_query)) {
		$payment_total_this_status_query = tep_db_query("SELECT sum(payment_value) as total FROM orders_payment_history WHERE payment_type_id =".$payment_status['payment_type_id']." AND date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate3)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");

		$payment_total_this_status = tep_db_fetch_array($payment_total_this_status_query);
	
        switch($payment_status['payment_type']){
            case 'Paid Credit':
                $credit_debit_total += $payment_total_this_status['total'];
                break;
            case 'Paid Debit':
                $credit_debit_total += $payment_total_this_status['total'];
                break;
            case 'Paid Cash':
                $cash_total += $payment_total_this_status['total'];
                break;
            case 'Paid Paypal':
                $paypal_total += $payment_total_this_status['total'];
                break;
			case 'Paid Ebay':
				$ebay_total += $payment_total_this_status['total'];
				break;
		}
	}

	$current_credit_total_query = tep_db_query("SELECT cc_total as cc FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");
	$current_credit_total = tep_db_fetch_array($current_credit_total_query);
			
	$cash_putIN_safe_query = tep_db_query("SELECT cash_total as cash FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");
	$cash_putIN_safe = tep_db_fetch_array($cash_putIN_safe_query);
			
	$current_paypal_total_query = tep_db_query("SELECT paypal_total as pp FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");
	$current_paypal_total = tep_db_fetch_array($current_paypal_total_query);
			
	$current_credit_total_query = tep_db_query("SELECT cc_total as cc FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");
	$current_credit_total = tep_db_fetch_array($current_credit_total_query);
	
	$cash_putIN_safe_query = tep_db_query("SELECT cash_drawer_total as cd_total FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");		
	$current_cash_in_drawer = tep_db_fetch_array($cash_putIN_safe_query);
			
	$current_ebay_total_query = tep_db_query("SELECT ebay_total as ebay FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate3)."'");
	$current_ebay_total = tep_db_fetch_array($current_ebay_total_query);
		
	$get_signature_query = tep_db_query("SELECT signature FROM daily_report_total WHERE date ='".date("Y-m-d", $startDate3)."'");
	$get_signature = tep_db_fetch_array($get_signature_query);	
			
	$datere = date('Y-m-d', $startDate);
	$startDate2 = strtotime('-1 day' ,$datere);
			
	$cash_date = date('Y-m-d' , $startDate3 + $startDate2);
						
	$previous_day_cash_query = tep_db_query("SELECT cash_drawer_total AS cash FROM daily_report_total WHERE date = '".$cash_date."'");
	$previous_day_cash = tep_db_fetch_array($previous_day_cash_query);	
		
	//Check for differences between cash drawer and cash put in safe
	$cash_difference = round($cash_total - $cash_putIN_safe['cash']);
?>

<h2 style="line-height: 55px;">Daily Balance Sheet</h2>
		<form method="POST" id="balances">
		<table class="table table-striped table-hover dataTable col-sm-6">				
            <thead><tr class="dataTableHeadingRow" bgcolor="silver">
            <td class="dataTableHeadingContent" align="center">Payment Method</td>
            <td class="dataTableHeadingContent" align="center">Total Collected</td>
			<td class="dataTableHeadingContent" align="center">Daily Balance</td>
				</tr></thead>
			<tbody>
				<tr>
					<td>Credit/Debit</td>
					<td><?php echo number_format($credit_debit_total,2); ?></td>
					<td><input name="cc_total" class="form-control" placeholder="From CC Machine" value="<?php echo $current_credit_total['cc'];?>"></td>
				</tr>
				<tr>
					<td>PayPal</td>
					<td><?php echo number_format($paypal_total,2); ?></td>
					<td><input name="paypal_total" class="form-control" placeholder="Balance from PayPal" value="<?php echo $current_paypal_total['pp'];?>"></td>
				</tr>
				<tr>
					<td>Cash</td>
					<td><?php echo number_format($cash_total,2); ?></td>
					<td><input name="cash_total" class="form-control" placeholder="Amount put in Safe" value="<?php echo $cash_putIN_safe['cash'];?>"></td>
				</tr>
				<tr>
					<td>Cash in Drawer</td>
					<td><?php echo $previous_day_cash['cash']; ?></td>
					<td><input name="cash_drawer_total" class="form-control" placeholder="Counted Bills in Drawer" value="<?php echo $current_cash_in_drawer['cd_total'];?>"></td>
				</tr>
				<tr>
					<td>Ebay Payments</td>
					<td><?php echo number_format($ebay_total,2); ?></td>
					<td><input name="ebay_payments_total" class="form-control" placeholder="Balance from Ebay" value="<?php echo $current_ebay_total['ebay'];?>"</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="action" value="update_balance">
		<div class="col-xs-12 form-group">
			<?php 
			$difference = $previous_day_cash['cash']+$cash_difference;
			
			echo 'There should be $'. $difference .' in the drawer';
			
			if($difference > $current_cash_in_drawer['cd_total'] || $difference < $current_cash_in_drawer['cd_total']){
				echo ' <i style="color: #D9534F;" class="fa fa-times-circle"></i>';
			} else {
				echo ' <i style="color: #0C0;" class="fa fa-check-circle"></i>';
			} ?>	
		</div>	
			
		<div class="col-xs-12 form-group">
			<div class="row">
				<label class="col-sm-9">Enter initials including first, middle, and last name of who recorded values</label>
				<div class="col-xs-3">	
				<input class="form-control" name="signature" placeholder="ABC" value="<?php echo $get_signature['signature']; ?>">
				</div>
			</div>
		</div>	
			
		<input type="hidden" name="start-date" value="<?php echo date('m/d/Y', $startDate3); ?>">	
		<button type="submit" class="btn btn-sm btn-primary" id="submitBalances">Submit</button>
			<div id="submitted" style="display:none; color: #0C0;"><i class="fa fa-check-circle"></i> Updated</div>	
	</form>

	<script>
$('#submitBalances').on("click", function(e){
	var confirmed = $('#submitted');
	e.preventDefault();
	$.ajax({
		type : 'POST',
		url  : 'update_daily_balances.php',
		data : $('#balances').serialize(),
		success :  function(data) {
			$('#daily_balance').html(data);
			$('#submitted').css('display', 'inline-block');
			setTimeout(function(){$('#submitted').hide();}, 1500);  
			
		}
	})
})
</script>
	