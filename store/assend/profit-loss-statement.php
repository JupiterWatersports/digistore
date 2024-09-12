<?php 

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'template-top.php');
require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

$date = date('Y');

if(isset($_POST['year'])){
    $selected_date = $_POST['year'];
} else {
    $selected_date = $date;
}

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
<link rel="stylesheet" href="css/bootstrap-grid.css" />
<style>
    .fa-caret-right{display:none;}
    .hiddden .fa-caret-down{display:none;}
    .hiddden .fa-caret-right{display:inline-block;}
    td i{width:10px; text-align: center;}
</style>
<div style="clear:both"></div>
<div class="column-12 form-horizontal form-group">
    <h2>Profit Loss Statement</h2>
</div>

<div class="column-12 form-group">
    <label>Select Year</label>
    <form id="form" method="post">
    <?php echo tep_draw_pull_down_menu('year', $years_array, $selected_date, 'style="width:150px;" class="form-control" onchange="submitForm();"'); ?>
    </form>
    
</div>

<script>
function submitForm(){
    $('#form').submit();
}
    
</script>

<?php // Get all totals Start // 
// Income Total    
$get_payments_query = tep_db_query("select ops.payment_type as name, SUM(oph.payment_value) as payment, SUM(oph.tax_value) as tax from orders_payment_history oph, orders_payment_status ops where year(oph.date_paid) = '".$start_date."' and oph.payment_type_id = ops.payment_type_id GROUP BY oph.payment_type_id ORDER BY oph.payment_type_id ASC LIMIT 7 ");
    $total_income = 0;
    $total_tax = 0;

while($get_payments1 = tep_db_fetch_array($get_payments_query)){
    $total_income += $get_payments1['payment'];
    $total_tax += $get_payments1['tax']; 
}

// Cost of Goods Sold Total
$get_cogs_query = tep_db_query("select account, SUM(amount) as total FROM transactions WHERE type ='Cost of Goods Sold' and amount > 0 and year(date) >= '".$start_date."' and year(date) < '".$end_date."'GROUP BY account"); 
                
    $cogs_total = 0;
while($get_cogs1 = tep_db_fetch_array($get_cogs_query)){
    $cogs_total += $get_cogs1['total'];    
}

// Expenses Total
$get_expenses_query = tep_db_query("select account, SUM(amount) as total FROM transactions WHERE type ='expense' and amount > 0 and year(date) >= '".$start_date."' and year(date) < '".$end_date."'GROUP BY account"); 
    
    $expenses_total = 0;
while($get_expenses1 = tep_db_fetch_array($get_expenses_query)){
    $expenses_total += $get_expenses1['total'];
}
$expensess_total = $expenses_total + $total_tax;

?>
<div style="clear:both"></div>
<div class="column-12" style="margin-bottom: 50px; background: #fff; max-width:800px;">
    <div class="row">
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td class="income-click">
                        <i class="fa fa-caret-down"></i>
                        <i class="fa fa-caret-right"></i> Income</td>
                    <td style="text-align:right; display: none;" class="income-upper"><b><?php echo $currencies->format($total_income,'');?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="income-accordion column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <?php $get_payments_query = tep_db_query("select ops.payment_type as name, SUM(oph.payment_value) as payment, SUM(oph.tax_value) as tax from orders_payment_history oph, orders_payment_status ops where year(oph.date_paid) >= '".$start_date."' and oph.date_paid < '".$end_date."' and oph.payment_type_id = ops.payment_type_id GROUP BY oph.payment_type_id ORDER BY oph.payment_type_id ASC LIMIT 7");
                    
                while($get_payments = tep_db_fetch_array($get_payments_query)){
                    echo '<tr>
                            <td style="padding-left:30px;">'.$get_payments['name'].'</td>
                            <td style="text-align:right;">'.$currencies->format($get_payments['payment'],'').'
                            </td>
                        </tr>';
                    
                    //$total_income += $get_payments['payment'];
                    //$total_tax += $get_payments['tax']; 
                }
                ?>
                <tr style="border-top:1.5px solid #CCC;">
                    <td style="padding-left:20px;"><b>Total Income</b></td>
                    <td style="text-align: right;"><b><?php echo $currencies->format($total_income,'');?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td class="cogs-click">
                        <i class="fa fa-caret-down"></i>
                        <i class="fa fa-caret-right"></i>
                        Cost of Goods Sold
                    </td>
                    <td style="text-align:right; display: none;" class="cogs-upper"><b><?php echo $currencies->format($cogs_total,'');?></b></td>
                </tr>
            </tbody>
        </table>
    </div>    
    <div class="cog-accordion column-12">
        <table class="table-no-border" style="margin-bottom: 0px;">
            <tbody>
                <?php $get_cogs_query = tep_db_query("select account, SUM(amount) as total FROM transactions WHERE type ='Cost of Goods Sold' and amount > 0 and year(date) >= '".$start_date."' and year(date) < '".$end_date."'GROUP BY account"); 
        
                while($get_cogs = tep_db_fetch_array($get_cogs_query)){
                    echo '<tr>
                            <td style="padding-left:30px;">'.$get_cogs['account'].'</td>
                            <td style="text-align:right;"><a href="transactions.php?date='.$selected_date.'&filter='.urlencode($get_cogs['account']).'">'.$get_cogs['total'].'</a></td>
                        </tr>';
                }
                ?>
                <tr style="border-top:1.5px solid #CCC;">
                    <td style="padding-left:20px;"><b>Total Cost of Goods Sold</b></td>
                    <td style="text-align: right;"><b><?php echo $currencies->format($cogs_total,'');?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px; border-top:2px solid #ccc;">
            <tbody>
                <tr>
                    <td>GROSS PROFIT</td>
                    <td style="text-align:right"><b><?php
                        $gross_profit = $total_income - $cogs_total; echo $currencies->format($gross_profit, ''); ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td class="expenses-click"><i class="fa fa-caret-down"></i><i class="fa fa-caret-right"></i> Expenses</td>
                    <td style="text-align:right; display: none;" class="expenses-upper"><b><?php
                        echo $currencies->format($expensess_total,''); ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="expenses-accordion column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <?php $get_expenses_query = tep_db_query("select account, SUM(amount) as total FROM transactions WHERE type ='expense' and amount > 0 and year(date) >= '".$start_date."' and year(date) < '".$end_date."'GROUP BY account"); 
                
                while($get_expenses = tep_db_fetch_array($get_expenses_query)){
                    echo '<tr>
                            <td style="padding-left:30px;">'.$get_expenses['account'].'</td>
                            <td style="text-align:right;"><a href="transactions.php?date='.$selected_date.'&filter='.urlencode($get_expenses['account']).'">'.$get_expenses['total'].'</a></td>
                        </tr>';   
                }
                ?>
                <tr>
                    <td style="padding-left:30px;">Sales Tax</td>
                    <td style="text-align: right"><?php echo $total_tax; ?></td>
                </tr>
                <tr style="border-top:1px solid;">
                    <td style="padding-left:20px;"><b>Total Expenses</b></td>
                    <td style="text-align: right;"><b><?php
                        echo $currencies->format($expensess_total,''); ?></b></td>
                </tr>
                </tbody>
        </table>
    </div>
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px; border-top:2px solid; border-bottom: 1px solid;">
            <tbody>
                <tr>
                    <td>NET OPERATING INCOME</td>
                    <td style="text-align:right"><b><?php echo $currencies->format($gross_profit - $expensess_total, ''); ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="column-12" style="border-bottom: 1.5px solid;">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td class=""><i class="fa fa-caret-down"></i>
                        <i class="fa fa-caret-right"></i>
                         Other Expenses</td>
                    <td style="text-align:right">$0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="column-12" style="border-bottom: 1px solid;">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td>NET OTHER INCOME</td>
                    <td style="text-align:right">$0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="column-12">
        <table class="table-no-border" style="margin-bottom:0px;">
            <tbody>
                <tr>
                    <td>NET INCOME</td>
                    <td style="text-align:right"><b><?php echo $currencies->format($gross_profit - $expensess_total, ''); ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div> 
</div>

<script>
    $(".income-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".income-accordion").toggle();
        $(".income-upper").toggle();
    });
    
    $(".cogs-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".cog-accordion").toggle();
        $(".cogs-upper").toggle();
    });
    
    $(".expenses-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".expenses-accordion").toggle();
        $(".expenses-upper").toggle();
    });
    
    $(".other-click").on("click", function(){
        $(this).toggleClass("hiddden");
        $(".other-accordion").toggle();
    });
</script>


 <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>