<?php require_once('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title>Unpaid Orders</title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
</head>
<style>
.table{max-width:100%; width:auto;}
table.dataTable{float:left;}
.ordersRow{border-top:2px dashed #000;}
#unpaid-orders{    margin: 15px 0px;}
    .unpaid-orders-inner, .unpaid-orders-heading .fa-caret-up, #sa-2{}
#unpaid-orders input:checked ~ .unpaid-orders-inner{-webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
}
#unpaid-orders input:checked ~ .unpaid-orders-inner{display:block}
#unpaid-orders input:checked ~ .unpaid-orders-heading .fa-caret-up{display:inline-block}
#unpaid-orders input:checked ~ .unpaid-orders-heading .fa-caret-down{display:none;}
</style>
<body>
<div id="wrapper"> 
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<div style="height:20px;"></div>
<h1 class="pageHeading"><?php echo  'Unpaid Orders'; ?></h1>
<div style="height:20px;"></div>

          
<div id="responsive-table">
           
		<div style="float:left;" id="unpaid-orders">
            <label for="sa-2" class="unpaid-orders-heading"><span style="margin-right:15px;">Unpaid Orders</span><i class="fa fa-caret-down" style="float:right;"></i><i class="fa fa-caret-up" style="float:right;"></i>
            </label>
<div class="unpaid-orders-inner">
<table class="table table-striped table-hover dataTable">
<thead>
                    <tr class="dataTableHeadingRow">
                      <th class="dataTableHeadingContent" align="left" style="width:20%;">Date Purchased</th>
                      <th class="dataTableHeadingContent" align="left" style="width:20%;">Order Num</th>
                      <th class="dataTableHeadingContent" align="left" style="width:20%;">Order Total</th>
                      <th class="dataTableHeadingContent" align="left" style="width:20%;">Payment Collected</th>
                    </tr>
                    </thead>
    <tbody>
                    
 <?php 
    // slow part //
    /*$unpaid_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased, ot.value FROM orders o JOIN orders_total ot ON o.orders_id = ot.orders_id  WHERE year(o.date_purchased) > '2016' and o.orders_status IN ('113', '6', '118', '128', '119', '130', '3', '112') AND ot.class= 'ot_total' GROUP BY o.orders_id order by o.orders_id ASC ");*/
        
    //$unpaid_orders_query = tep_db_query("SELECT * FROM unpaid_orders_count ORDER BY orders_id ASC");    
 $unpaid_orders_query = tep_db_query("SELECT * FROM unpaid_orders_count JOIN orders on orders.orders_id = unpaid_orders_count.orders_id WHERE date(orders.date_purchased) > '2021-09-08' AND orders_status NOT IN (4,109,3) ORDER BY orders.orders_id ASC");    
        
    $i = 0;    
	   //while($unpaid_orders = tep_db_fetch_array($unpaid_orders_query)){
        
        while($unpaid_orders = tep_db_fetch_array($unpaid_orders_query )){
            /*
		   $payment_sum = $unpaid_orders['total'];
		   
		   $unpaid_orders3_query = tep_db_query("select sum(oph.payment_value) as total from orders o, orders_payment_history oph, orders_total ot where o.orders_id = oph.orders_id and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_id = '".$unpaid_orders['orders_id']."'");  
		   $unpaid_orders3 = tep_db_fetch_array($unpaid_orders3_query);
           */
		   
		   $date = $unpaid_orders['date_purchased'];
		   $date1 = new DateTime($date);
		   $date2 = $date1->format('m-d-Y');
		   
            /*
		   	$order_total = round($unpaid_orders['value'], 2);
		   
           if((!tep_db_num_rows($unpaid_orders3_query) > 0) || $order_total > $unpaid_orders3['total']) {
            $check_query = tep_db_query("SELECT * FROM unpaid_orders_count WHERE orders_id = '".$unpaid_orders['orders_id']."' ");
               
            if(tep_db_num_rows($check_query) < 1){
                
                $array = array('orders_id' => $unpaid_orders['orders_id'],
                               'date_purchased' => $unpaid_orders['date_purchased'],
                               'order_total' => $unpaid_orders['value'],
                               'total_paid' => $unpaid_orders3['total']);
               
               //$update_unpaid_orders_table = tep_db_perform('unpaid_orders_count', $array);
            }
            */
            
            
		    echo'<tr class="dataTableRow ordersRow" onMouseOver="this.className="dataTableRowOver";this.style.cursor="hand"" onMouseOut="this.className="dataTableRow"">
		    <td class="dataTableContent" align="right"><div style="float:left;">'.$date2.'</div>Order #</td>'.
		    '<td class="dataTableContent"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$unpaid_orders['orders_id'].'"><b>'.$unpaid_orders['orders_id'].'</b></a></td>
		    <td class="dataTableContent" align="left"><b >'.$currencies->format($unpaid_orders['order_total']).'</b></td>
		    <td class="dataTableContent" align="left"><b >'.$currencies->format($unpaid_orders['total_paid']).'</b></td></tr>';
           $i++;   
           //}
		  
		
	   }
?>              
</tbody>
</table>

</div>
</div>
</div>
  
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>