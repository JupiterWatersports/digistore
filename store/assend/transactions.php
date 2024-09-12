<?php

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template-top.php');
require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();



$selected_date = $_GET['date'];

if(isset($selected_date)){
    $start_date = $selected_date;
    $end_date = $selected_date + 1;
} else {
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

?>
<title><?php echo $_GET['filter'];?> Transactions</title>
<style>
    .fa-caret-right{display:none;}
    .hiddden .fa-caret-down{display:none;}
    .hiddden .fa-caret-right{display:inline-block;}
    td i{width:10px; text-align: center;}
    .table{background:#fff;}
    .table .thead-dark th {
color: #fff;
background-color: #343a40;
border-color: #454d55;
}
</style>

<div class="col-xs-12 form-horizontal form-group">
    <div class="col-xs-12 form-group"><a href="profit-loss-statement.php">Return to report summary</a>
    </div>
    <h2 style="text-align: center;">Transactions: &nbsp;<?php echo $_GET['filter'];?></h2>
</div>

<script>
function submitForm(){
    $('#form').submit();
}

</script>

<div class="col-xs-12" style="margin-bottom: 50px;">

    <div class="col-xs-12" style="border-bottom: 1px solid;">
        <table class="table table-bordered" style="margin-bottom:0px;">
            <thead class="thead-dark">
                <th>Date</th>
                <th>Account</th>
                <th>Num</th>
                <th>Name</th>
                <th>Description</th>
                <th style="width:15%">Payment Method</th>
                <th>Amount</th>
            </thead>

            <tbody>

          <?php if(isset($_GET['filter'])){
              $get_transactions_query_raw = "SELECT * from transactions where year(date) >= '".$start_date."' and year(date) < '".$end_date."' and account = '".$_GET['filter']."'";
            } elseif (isset($_GET['pay_method'])){
              $get_transactions_query_raw = "SELECT * from transactions where year(date) >= '".$start_date."' and year(date) < '".$end_date."' and payment_method = '".$_GET['pay_method']."'";
            } else {

          }

                $transactions_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $get_transactions_query_raw, $get_transactions_num);

                $get_transactions_query = tep_db_query($get_transactions_query_raw);

                while($get_transactions = tep_db_fetch_array($get_transactions_query)){
                echo '<tr>
                    <td><a data-id="'.$get_transactions['id'].'" onclick="showExpense('.$get_transactions['id'].')">'.date("m/d/Y", strtotime($get_transactions['date'])).'</a></td>
                    <td>'.$get_transactions['account'].'</td>
                    <td>'.$get_transactions['num'].'</td>
                    <td>'.$get_transactions['name'].'</td>
                    <td>'.$get_transactions['description'].'</td>
                    <td>'.$get_transactions['payment_method'].'</td>
                    <td>$'.@number_format($get_transactions['amount'],'2','.','').'</td>

                </tr>';
                }
                ?>
            </tbody>
        </table>

        <?php echo '<div style="margin-top:20px;" class="col-xs-12 form-group">
            <div class="col-sm-6 form-group">'.$transactions_split->display_count($get_transactions_num, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
            <div style="text-align: right;" class="col-sm-6 form-group"><?php echo $transactions_split->display_links($get_transactions_num, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?>
            </div>
    </div>



    <div class="col-xs-12" id="expense-container" style=" display:none; height:100%; position: fixed; top:55px; left: 0px; z-index: 1000; background: #fff;">
    </div>

<script>
    function showExpense(id){
        jQuery.ajax({
			url: 'add_expense2.php',
			type:'POST',
            data:{id:id},

			success: function(data){
                $('#expense-container').html(data);
                $('#expense-container').show();
			}
		});
    }

    function submitForm2(){
        var data = $('#popUpForm').serialize();
        $.ajax({
        type : 'POST',
        url  : 'add_expense2.php?action=update',
        data : data,
        success :  function(data) {
           $("#expense-container").html(data);

           window.location.href = 'transactions.php?date=<?php echo $_GET['date'];?>&filter=<?php echo $_GET['filter'];?>';
	   }
        });
    };


    function deleteThis(){
        var data = $('#popUpForm').serialize();
        $.ajax({
        type : 'POST',
        url  : 'add_expense2.php?action=delete',
        data : data,
        success :  function(data) {
           $("#expense-container").html(data);

           window.location.href = 'transactions.php?date=<?php echo $_GET['date'];?>&filter=<?php echo $_GET['filter'];?>';

	   }
        });
    };
</script>


 <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
