<?php

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template-top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$date = date('Y');

$selected_date = $_POST['year'];

if(isset($selected_date)){
	$selected_year = $selected_date;
    $start_date = $selected_date;
    $end_date = $selected_date + 1;
} else {
	$selected_year = $date;
    $start_date = $date;
    $end_date = $date + 1;
}

$years_array = array();
    for($i=0; $i<6; $i++){
        if($i == 0){
            $years_array[] = array('id'=> $date, 'text'=> $date);
        } else {
            $years_array[] = array('id'=> $date-$i, 'text'=> $date-$i);
        }
    }

if($_POST['action']== 'insert'){
	$check_for_existing_query = tep_db_query("SELECT * FROM balance_sheet WHERE year = '".$_POST['year']."'");

	if(tep_db_num_rows($check_for_existing_query) > 0){
		$data_array = array(
        'year' => $_POST['year'],
        'cash' => $_POST['cash'],
        'inventory' => $_POST['inventory'],
        'loans_to' => $_POST['loans_to'],
				'loans_from' => $_POST['loans_from'],
				'stocks' => $_POST['stocks'],
				'bank_loans' => $_POST['bank_loans'],
        'retained' => $_POST['retained'],
				'amazon' => $_POST['amazon'],
				'actual_amazon' => $_POST['actual_amazon'],
        'brokerage' => $_POST['brokerage'],
				'credit_card' => $_POST['credit_card'],
				'ebay' => $_POST['ebay'],
				'actual_ebay' => $_POST['actual_ebay'],
				'paypal' => $_POST['paypal'],
				'actual_paypal' => $_POST['actual_paypal']
    );

		tep_db_perform('balance_sheet', $data_array, 'update', "year = '".$_POST['year']."'");

	} else {
    	$data_array = array(
        'year' => $_POST['year'],
        'cash' => $_POST['cash'],
        'inventory' => $_POST['inventory'],
        'loans_to' => $_POST['loans_to'],
				'stocks' => $_POST['stocks'],
				'bank_loans' => $_POST['bank_loans'],
				'loans_from' => $_POST['loans_from'],
        'retained' => $_POST['retained'],
				'amazon' => $_POST['amazon'],
				'actual_amazon' => $_POST['actual_amazon'],
        'brokerage' => $_POST['brokerage'],
				'credit_card' => $_POST['credit_card'],
				'ebay' => $_POST['ebay'],
				'actual_ebay' => $_POST['actual_ebay'],
				'paypal' => $_POST['paypal'],
				'actual_paypal' => $_POST['actual_paypal']
    );

    tep_db_perform('balance_sheet', $data_array);
	}
}
/*
1 = Credit   0
2 = Debit    1
3 = Cash     2
4 = Paypal   3
8 = Amazon   4
10 = Ebay    5
*/

$payment_array = array('1','2','3','4','8','10');
$payment_totals = array();
for($i=0; $i<6; $i++){
//foreach($payment_array as $value){
	$get_totals_query = tep_db_query("SELECT SUM(payment_value) as total FROM `orders_payment_history` WHERE YEAR(date_paid) = '".$selected_year."' AND payment_type_id = '".$payment_array[$i]."'");
	$get_totals = tep_db_fetch_array($get_totals_query);

	$payment_totals[$i] = $get_totals['total'];
}
?>
<title>Balance Sheet</title>
<style>
    table{text-align: left; background-color:#fff;}
    .fa-caret-right{display:none;}
    .hiddden .fa-caret-down{display:none;}
    .hiddden .fa-caret-right{display:inline-block;}
    td i{width:10px; text-align: center;}
</style>

<div class="col-xs-12 form-horizontal form-group">
    <h2>Balance Sheet</h2>
</div>

<div class="col-xs-12 form-group">
    	<form id="form" method="POST">
    	<?php echo tep_draw_pull_down_menu('year', $years_array, $selected_date, 'style="width:150px;" class="form-control" onchange="submitForm();"'); ?>
    	</form>

</div>

<script>
function submitForm(){
    $('#form').submit();
}

</script>

<?php $value_query = tep_db_query("SELECT * FROM balance_sheet WHERE year = '".$selected_year."'");
	$value = tep_db_fetch_array($value_query);

	//Get Outstanding Liabilities

	$out_liab_query = tep_db_query("SELECT amount FROM transactions WHERE year(date) = '".$selected_year."' AND payment_method = 'outstanding'");
	$out_liab = tep_db_fetch_array($out_liab_query);
?>

<form id="whole-form" method="post">
	<input type="hidden" name="year" value="<?php echo $selected_year; ?>">
<div class="col-xs-12" style="margin-bottom: 50px; width:650px;">

    <div class="col-xs-12 form-group">
        <table class="table" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td><b>Assets</b></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Cash</td>
                    <td><input name="cash" class="form-control" value="<?php echo $value['cash'];?>"></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px; vertical-align: middle;"><a target="_blank" href="inventory_cost.php">Inventory</a></td>
                    <td><input name="inventory" class="form-control" value="<?php echo $value['inventory'];?>"></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Loans to Shareholders</td>
                    <td><input name="loans_to" class="form-control" value="<?php echo $value['loans_to'];?>"></td>
                </tr>
				<tr>
					<td style="padding-left: 30px;  vertical-align:middle;">Stocks</td>
					<td><input name="stocks" class="form-control" value="<?php echo $value['stocks'];?>"></td>
                <tr>
                    <td>Total</td>
                    <td><?php echo $currencies->format($value['cash']+$value['inventory']+$value['loans_to']+$value['stocks']);?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 form-group">
        <table class="table" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td><b>Liabilities</b></td>
                </tr>
				<tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Bank Loans</td>
                    <td><input name="bank_loans" class="form-control" value="<?php echo $value['bank_loans'];?>"></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Loans from Shareholders</td>
                    <td><input name="loans_from" class="form-control" value="<?php echo $value['loans_from'];?>"></td>
                </tr>
								<tr>
				<?php echo '<td style="padding-left: 30px; vertical-align:middle;"><a href="transactions.php?date='.$selected_year.'&pay_method=outstanding">Outstanding Liabilities</a></td>';?>
										<td><?php echo $currencies->format($out_liab ['amount']);?></td>
								</tr>
                <tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Retained Earnings</td>
                    <td><input name="retained" class="form-control" value="<?php echo $value['retained'];?>"></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?php echo $currencies->format($value['bank_loans']+$value['loans_from']+$value['retained']+$out_liab ['amount']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

	<div class="col-xs-12 form-group">
        <table class="table form-inline" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td><b>1099s</b></td>
                </tr>
				<tr style="vertical:middle;">
                    <td style="padding-left: 30px; vertical-align:middle;">Amazon</td>
                    <td style="vertical-align:middle;"><?php echo $currencies->format($payment_totals['4']);?></td>
										<td><label style="display:inline-block;">Actual</label>
											<input name="actual_amazon" class="form-control" style="width:100px; margin-left:10px;"></input>
										</td>
                </tr>
				<tr>
                    <td style="padding-left: 30px; vertical-align:middle;">Brokerage</td>
                    <td><input name="brokerage" class="form-control" value="<?php echo $value['brokerage'];?>">
										</td>
                </tr>
				<tr>
                    <td style="padding-left: 30px;">Credit Card</td>
                    <td><?php echo $currencies->format($payment_totals['0']);?></td>
                </tr>
				<tr>
                    <td style="padding-left: 30px; vertical-align:middle;">eBay</td>
                    <td style="vertical-align:middle;"><?php echo $currencies->format($payment_totals['5']);?>
										</td>
										<td><label style="display:inline-block" style="">Actual</label>
											<input name="actual_ebay" class="form-control" style="width:100px; margin-left:10px;"></input>
										</td>
                </tr>
				<tr>
                    <td style="padding-left:30px; vertical-align:middle;">PayPal</td>
                    <td style="vertical-align:middle;"><?php echo $currencies->format($payment_totals['3']);?>
										</td>
										<td><label style="display:inline-block" style="">Actual</label>
											<input name="actual_paypal" class="form-control" style="width:100px; margin-left:10px;"></input>
										</td>
                </tr>
				<tr>
                    <td>Total</td>
                    <td><?php echo $currencies->format($payment_totals['4']+$value['brokerage']+$payment_totals['0']+$payment_totals['5']+$payment_totals['3']); ?></td>
                </tr>
			</tbody>
		</table>
	</div>
    <input type="hidden" name="action" value="insert">
    <button class="btns btn btn-primary" value="Submit">Submit</button>
    </form>

</div>

<script>
    $(".income-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".income-accordion").toggle();
    });

    $(".cog-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".cog-accordion").toggle();
    });

    $(".expenses-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".expenses-accordion").toggle();
    });

    $(".other-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".other-accordion").toggle();
    });
</script>


 <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
