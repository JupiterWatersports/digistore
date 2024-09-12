<!doctype html >
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title>Sales Report</title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
   <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

</head>
<style>
.table{max-width:100%; width:auto;}
table.dataTable{clear:none; float:left;}
.ordersRow{border-top:2px dashed #000;}
#unpaid-orders{    margin: 15px 0px;}
.unpaid-orders-inner, .unpaid-orders-heading .fa-caret-up, #sa-2{display:none;}
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

	<?php require 'includes/template-top.php'; ?>

<div style="height:20px;"></div>
<h1 class="pageHeading"><?php echo  SR_HEADING_TITLE; ?></h1>
<div style="height:20px;"></div>

<form action="" method="get">
    <div class="col-xs-12 col-sm-2 form-group">
        <div class="xs-3 sm-12">
            <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo SR_REPORT_TYPE_YEARLY; ?><br>
        </div>
        <div class="xs-3 sm-12">
            <input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo SR_REPORT_TYPE_MONTHLY; ?><br></div>
        <div class="xs-3 sm-12">
            <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo SR_REPORT_TYPE_WEEKLY; ?><br>
        </div>
        <div class="xs-3 sm-12">
            <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo SR_REPORT_TYPE_DAILY; ?><br>
        </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="xs-12 form-group">
<?php echo SR_REPORT_START_DATE;
	$start_date_val = $_GET['start-date'] ?? '';

	echo '<br>
            <div class="input-append" id="datetimepicker">
                <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
                <input name="start-date" id="datetimepicker-input" class="form-control" style="width:200px; border-radius: 5px 0px 0px 5px; height:38px;" value="'.$start_date_val.'">
                <span class="add-on" style="padding:5px 10px; height:38px;">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar"></i>
                </span>
            </div>';
	?>
            <script>
            $(function() {
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();

            var output =
            (month<10 ? '0' : '') + month + '/' +
            (day<10 ? '0' : '') + day + '/' + d.getFullYear();
				$('#datetimepicker').datetimepicker({
                pickTime: false,
                autoclose: true
                }).on('changeDate', function (ev) {
                    $(this).datetimepicker('hide');
                });

				//$("#datetimepicker-input").val(output);
            });
            </script>
        </div>

        <div class="xs-12 form-group">
<?php echo SR_REPORT_END_DATE;
$end_date_val = $_GET['end-date'] ?? '';

	echo '</br>
            <div class="input-append" id="datetimepicker2">
                <label class="form-name" style="display: inline-block; margin-bottom: .5rem;">Payment Date</label>
                <input name="end-date" id="datetimepicker-input2" class="form-control" style="width:200px; border-radius: 5px 0px 0px 5px; height:38px;" value ="'.$end_date_val.'">
                <span class="add-on" style="padding:5px 10px; height:38px;">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar"></i>
                </span>
            </div>';
	?>
            <script>
            $(function() {
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();

            var output = d.getFullYear() + '-' +
            (month<10 ? '0' : '') + month + '-' +
            (day<10 ? '0' : '') + day;

            //$("#datetimepicker-input2").val(output);
                $('#datetimepicker2').datetimepicker({
                pickTime: false,
                autoclose: true
                }).on('changeDate', function (ev) {
                    $(this).datetimepicker('hide');
                });
            });
            </script>
                </div>
                </div>

                <div class="col-xs-12 col-sm-3 col-md-2">
                <div class="xs-8 sm-12 form-group" style="width:183px;">
                    <?php echo SR_REPORT_DETAIL; ?><br>
                    <select name="detail" size="1" class="form-control">
                      <option value="0"<?php if ($srDetail == 0) echo " selected"; ?>><?php echo  SR_DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>><?php echo  SR_DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>><?php echo  SR_DET_DETAIL_ONLY; ?></option>
                    </select>
                  </div>


            <div class="xs-4 sm-12 form-group">
                    <?php echo SR_REPORT_MAX; ?><br>
                    <select name="max" size="1" class="form-control">
                      <option value="0"><?php echo SR_REPORT_ALL; ?></option>
                      <option<?php if ($srMax == 1) echo " selected"; ?>>1</option>
                      <option<?php if ($srMax == 3) echo " selected"; ?>>3</option>
                      <option<?php if ($srMax == 5) echo " selected"; ?>>5</option>
                      <option<?php if ($srMax == 10) echo " selected"; ?>>10</option>
                      <option<?php if ($srMax == 25) echo " selected"; ?>>25</option>
                      <option<?php if ($srMax == 50) echo " selected"; ?>>50</option>
                    </select>
                  </div>

              <div class="col-xs-4 col-sm-12">
              <div class="row">
              <?php echo 'View By:'; ?><br>
                    <select name="viewby" size="1" class="form-control">
                    <option value="0" <?php if ($_GET['viewby'] == 0) echo " selected"; ?>>Products</option>
                    <option value="1" <?php if ($_GET['viewby'] == 1) echo " selected"; ?>>Orders</option>
                    </select>
                    </div>
              </div>
                    </div>




                 <div class="col-xs-12 col-sm-3">
               <div class="md-12 form-group">
                    <?php echo SR_REPORT_STATUS_FILTER; ?><br>
                    <select name="status" size="1" class="form-control">
                      <option value="0"><?php echo SR_REPORT_ALL; ?></option>
<?php
                        foreach ($sr->status as $value) {
?>
                      <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) echo " selected"; ?>><?php echo $value["orders_status_name"] ; ?></option>
<?php
                         }
?>
                    </select></div>
            <div class="md-12 form-group">
             <?php echo SR_REPORT_COMP_FILTER; ?><br>
                    <select name="compare" size="1" class="form-control">
                      <option value="0" <?php if ($srCompare == SR_COMPARE_NO) echo "selected"; ?>><?php echo SR_REPORT_COMP_NO; ?></option>
                      <option value="1" <?php if ($srCompare == SR_COMPARE_DAY) echo "selected"; ?>><?php echo SR_REPORT_COMP_DAY; ?></option>
                      <option value="2" <?php if ($srCompare == SR_COMPARE_MONTH) echo "selected"; ?>><?php echo SR_REPORT_COMP_MONTH; ?></option>
                      <option value="3" <?php if ($srCompare == SR_COMPARE_YEAR) echo "selected"; ?>><?php echo SR_REPORT_COMP_YEAR; ?></option>
                    </select>
                    </div>
                    </div>

             <div class="col-xs-12 col-md-2">
                <div class="md-12 form-group">
                    <?php echo SR_REPORT_SORT; ?><br>
                    <select name="sort" size="1"  class="form-control">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo  SR_SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo  SR_SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo  SR_SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo  SR_SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo  SR_SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo  SR_SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo  SR_SORT_VAL6; ?></option>
                    </select>
                  </div>
                  <div class="md-12 form-group">
                    <?php echo SR_REPORT_EXP; ?><br>
                    <select name="export" size="1" class="form-control">
                      <option value="0" selected><?php echo  SR_EXP_NORMAL; ?></option>
                      <option value="1"><?php echo  SR_EXP_CSV; ?></option>
                    </select>
            </div>
            <div class="md-12 form-group">
            <select name="cID" class="form-control"><option><?php echo 'Select Sales Person'; ?></option>

		<?php
		$sales_person_query = tep_db_query("SELECT customer_service_id FROM orders group by customer_service_id");
                while ($sales_person = tep_db_fetch_array($sales_person_query)) {
                    if ($sales_person['customer_service_id'] <> ''){ ?>
                <option value="<?php echo $sales_person['customer_service_id'];?>"<?php if ($_GET['cID'] == $sales_person['customer_service_id']){ echo " selected";} ?>><?php echo $sales_person['customer_service_id'] ; ?> </option>
	<?php          }
                } ?>

		</select>
        </div>
        <div class="md-12 form-group"><input type="submit" value="<?php echo 'Update'; ?>"><input type="button" onclick="ExportToExcel('xlsx')" value="Export table to excel"></div></div>

            </form>
<div id="responsive-table">
            <table class="table table-striped table-hover dataTable" id="table_to_excel">
           <thead>
                    <tr class="dataTableHeadingRow">
                      <th class="dataTableHeadingContent" align="left" style="width:20%;"><?php echo  SR_TABLE_HEADING_DATE; ?></th>
                      <th class="dataTableHeadingContent" align="left" style="width:20%;"><?php echo  SR_TABLE_HEADING_ORDERS;?></th>
                      <th class="dataTableHeadingContent" align="right" style="width:10%;"><?php echo  SR_TABLE_HEADING_ITEMS; ?></th>
                      <th class="dataTableHeadingContent" align="right" style="width:10%;"><?php echo  'Projected Revenue';?></th>
                      <th class="dataTableHeadingContent" align="right" style="width:10%;">Payment Collected</th>
                      <th class="dataTableHeadingContent" align="center" style="width: 10%">Tax Collected</th>
                    </tr>
                    </thead>
<?php
	if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
		$payment_total_query = tep_db_query("select sum(payment_value) as total FROM orders_payment_history oph, orders o WHERE oph.date_paid >= '" . tep_db_input(date("Y-m-d", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d", $endDate)) . "' and o.orders_id = oph.orders_id and o.customer_service_id = '".$_GET['cID']."'");
  } else {
		$payment_total_query = tep_db_query ("select sum(payment_value) as total FROM orders_payment_history WHERE date_paid >= '" . tep_db_input(date("Y-m-d", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d", $endDate)) . "'");
	}
	$payment_total = tep_db_fetch_array($payment_total_query);
  $sum = 0;

  while ($sr->hasNext()) {

    $info = $sr->next();
    $last = sizeof($info) - 1;
?>
                    <tr class="dataTableRow" style="border-top:1px solid #bbb; background:#fff; padding:8px;">
<?php
    switch ($srView) {
      case '3':
?>
                      <td class="dataTableContent" align="left"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
        break;
      case '4':
?>
                      <td class="dataTableContent" align="left"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
<?php
        break;
      default;
?>
                      <td class="dataTableContent" align="left"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
    }
?>


                      <td class="dataTableContent" align="left"><?php echo $info[0]['order']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $info[$last - 1]['totitem']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last - 1]['totsum']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['totalpymnt']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['totaltax']);?></td>
                    </tr>
<?php
	if($_GET['viewby'] == '1'){
		  $filterString = "";
    	if ($_GET['status'] > 0) {
        $filterString .= " AND o.orders_status = " . $_GET['status'] . " ";
      }

  		if (isset($_GET['cID']) && ($_GET['cID'] !=='Select Sales Person') ) {
        $filterString .= " AND o.customer_service_id ='".$_GET['cID']."'";
      }

      $end = date("Y-m-d", $sr->showDate);
      $end_date = date("Y-m-d\TH:i:s", strtotime($end.'+24 hours'));

      $orders_info_array = array();
  	 	$order_numbers = array();
  		$get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased from orders o WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND o.date_purchased < '" . tep_db_input($endDate) . "' " . $filterString. "");

  		while($get_orders = tep_db_fetch_array($get_orders_query)){
  			$orders_info_array[] = array('orders_id' => $get_orders['orders_id'],
  								 'date_purchased' => $get_orders['date_purchased']);

  			$order_numbers[] = $get_orders['orders_id'];
  		}

///// ORDERS PLACED BEFORE BUT PAID ON THIS DATE  /////
/*
    $get_additional_pmnts_query = tep_db_query("SELECT SUM(oph.payment_value) as total, o.orders_id, o.date_purchased FROM orders_payment_history oph, orders o WHERE oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND oph.date_paid < '" . tep_db_input($end_date) . "'" .$filterString." AND o.orders_id = oph.orders_id AND o.orders_id NOT IN ('".implode( "', '" , $order_numbers)."') GROUP BY o.orders_id ORDER BY o.date_purchased ASC");



    if(tep_db_num_rows($get_additional_pmnts_query) > '1'){
  		while($get_additional_pmnts = tep_db_fetch_array($get_additional_pmnts_query)){
  			$oID = $get_additional_pmnts['orders_id'];

              $order_total_query = tep_db_query("SELECT value from orders o, orders_total ot WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = ot.orders_id  and o.orders_id = ".$oID." and ot.class = 'ot_total' " . $filterString. "");
              $order_total = tep_db_fetch_array($order_total_query);

              $get_payments_query = tep_db_query("SELECT sum(oph.payment_value) as total, ops.payment_type FROM orders o, orders_payment_history oph, orders_payment_status ops WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = oph.orders_id and ops.payment_type_id = oph.payment_type_id and o.orders_id = ".$oID." " . $filterString. "");
              $get_payments = tep_db_fetch_array($get_payments_query);

              $date = $get_additional_pmnts['date_purchased'];
              $date1 = new DateTime($date);
              $date2 = $date1->format('m-d-Y');


  			$payment_icon = '';
  			if(tep_db_num_rows($get_payments_query) > 0){
                  switch($get_payments['payment_type']){
                      case 'Paid Credit':
                          $payment_icon = '<i style="margin-left:10px;" class="fa fa-credit-card"></i>';
                          break;
                      case 'Paid Debit':
                          $payment_icon = '<i style="margin-left:10px;" class="fa fa-cc-visa"></i>';
                          break;
                      case 'Paid Cash':
                          $payment_icon = '<i style="margin-left:10px;" class="fa fa-money"></i>';
                          break;
                      case 'Paid Paypal':
                          $payment_icon = '<i style="margin-left:10px;" class="fa fa-paypal"></i>';
                          break;
                  }
  			}

  		echo'<tr class="dataTableRow ordersRow" onMouseOver="this.className="dataTableRowOver";this.style.cursor="hand"" onMouseOut="this.className="dataTableRow"">'.
  		'<td class="dataTableContent" align="right"><div style="float:left;">'.$date2.'</div>Order #</td>'.
  		'<td class="dataTableContent"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'"><b>'.$oID.'</b></a></td>'.
  		'<td class="dataTableContent">&nbsp;</td>'.
  		'<td class="dataTableContent" align="right"><b style="color:rgba(0, 0, 0, 0.72);">'.$currencies->format($order_total['value']).'</b></td>';
  			if($get_additional_pmnts['total'] > 0){
  				echo '<td class="dataTableContent" align="right"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'#payment-method-block-inner"><b>'.$currencies->format($get_additional_pmnts['total']).'</b>'.$payment_icon.'</a></td>';
  			} else {
  				echo'<td class="dataTableContent" align="right">&nbsp;</td>';
  			}
  		$get_products_query = tep_db_query("select * from orders_products where orders_id = ".$oID."");
  			while($get_products = tep_db_fetch_array($get_products_query)){
  ?>
                      <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent" align="left"><?php echo'<a onclick="return !window.open(this.href);" href="client_search.php?product_name='.$get_products['products_name'].'">'.$get_products['products_name']; ?></a>
  <?php
    $get_attributes_query = tep_db_query("select * from orders_products_attributes where orders_products_id = ".$get_products['orders_products_id']."");

    				if (tep_db_num_rows($get_attributes_query) !==0){

  	 				while($get_attributes = tep_db_fetch_array($get_attributes_query)){

       			 	echo '<div>&nbsp;&nbsp;&nbsp;';
        //  $attr['options'] . ': '
        			 	echo '&nbsp;'. $get_attributes['products_options']. ':&nbsp;'. $get_attributes['products_options_values'] ;
       				echo '</div>';
  	 				}
  				}
  ?>                  </td>
                      <td class="dataTableContent" align="right"><?php echo $get_products['products_quantity']. $resp[$i]['products_id']; ?></td>
  <?php
            			if ($srDetail == 2) {
  						$final_price1 = $currencies->format(tep_add_tax($get_products['final_price'], $get_products['products_tax']));

                   echo'<td class="dataTableContent" align="right">'. $final_price1.'</td>
                      <td class="dataTableContent">&nbsp;</td>';

            			} else {
  					echo'<td class="dataTableContent">&nbsp;</td>
  					<td class="dataTableContent">&nbsp;</td>'; ?>


  		 	  <?php } ?>

  <?php

  			} echo'
                    </tr>';

  		'</tr>';
  		}
  	}*/
//// END PREVIOUS ORDERS ////////

		//$get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased, o.ipaddy from orders o WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND o.date_purchased < '" . tep_db_input($end_date) . "' " . $filterString. " and");

    $get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased, o.ipaddy FROM orders o, orders_payment_history oph WHERE o.orders_status <>4 AND o.orders_status <>109 AND o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND oph.date_paid < '" . tep_db_input($end_date) . "' " . $filterString. " ORDER BY o.date_purchased ASC");

		while($get_orders = tep_db_fetch_array($get_orders_query)){
			$oID = $get_orders['orders_id'];

			if ($get_orders['ipaddy'] > '0'){
				$inoutstore = 'Online Order';
			} else {
				$inoutstore = 'In Store';
			}

		$order_total_query = tep_db_query("SELECT value from orders o, orders_total ot WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = ot.orders_id  and o.orders_id = ".$oID." and ot.class = 'ot_total' " . $filterString. "");
		$order_total = tep_db_fetch_array($order_total_query);

		$get_payments_query = tep_db_query("SELECT sum(oph.payment_value) AS total, SUM(oph.tax_value) AS taxtotal FROM orders o, orders_payment_history oph WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND oph.date_paid < '" . tep_db_input($end_date) . "' and o.orders_id = ".$oID." " . $filterString. "");
		$get_payments = tep_db_fetch_array($get_payments_query);

		$date = $get_orders['date_purchased'];
		$date1 = new DateTime($date);
		$date2 = $date1->format('m-d-Y');

		echo'<tr class="dataTableRow ordersRow" onMouseOver="this.className="dataTableRowOver";this.style.cursor="hand"" onMouseOut="this.className="dataTableRow"">'.
		'<td class="dataTableContent" align="right"><div style="float:left;">'.$date2.'</div>Order #</td>'.
		'<td class="dataTableContent"><b><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'">'.$oID.' ('.$inoutstore.')</a></b></td>'.
		'<td class="dataTableContent">&nbsp;</td>'.
		'<td class="dataTableContent" align="right"><b style="color:rgba(0, 0, 0, 0.72);">'.$currencies->format($order_total['value']).'</b></td>';
			//if($get_payments['total'] > 0){
			echo '<td class="dataTableContent" align="right"><b>'.$currencies->format($get_payments['total']).'</b></td>
			<td class="dataTableContent" align="right"><b>'.$currencies->format($get_payments['taxtotal']).'</b></td>';
			/*} else {
			echo'<td class="dataTableContent" align="right">&nbsp;</td>
			<td class="dataTableContent" align="right">&nbsp;</td>';
    } */
		$get_products_query = tep_db_query("select * from orders_products where orders_id = ".$oID."");
			while($get_products = tep_db_fetch_array($get_products_query)){
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                    <td class="dataTableContent">&nbsp;</td>
                    <td class="dataTableContent" align="left"><?php echo'<a onclick="return !window.open(this.href);" href="client_search.php?product_name='.$get_products['products_name'].'">'.$get_products['products_name']; ?></a>
<?php
  $get_attributes_query = tep_db_query("select * from orders_products_attributes where orders_products_id = ".$get_products['orders_products_id']."");
        $attribute_price = [];
  				if (tep_db_num_rows($get_attributes_query) !==0){

	 				while($get_attributes = tep_db_fetch_array($get_attributes_query)){
     			 	echo '<div>&nbsp;&nbsp;&nbsp;';
      //  $attr['options'] . ': '
      			 	echo '&nbsp;'. $get_attributes['products_options']. ':&nbsp;'. $get_attributes['products_options_values'].'
						</br>&nbsp;&nbsp;&nbsp;Serial No: '.$get_attributes['serial_no'].
     				'</div>';


	 				}
				}
?>                  </td>
                    <td class="dataTableContent" align="right"><?php echo $get_products['products_quantity']. $resp[$i]['products_id']; ?></td>
<?php
          			if ($srDetail == 2) {?>
                    <td class="dataTableContent" align="right"><?php echo $currencies->format($get_products['final_price']); ?></td>
                    <td class="dataTableContent"><?php echo $currencies->format($get_products['products_price']); ?></td>
					<td>&nbsp;</td>
<?php
          			} else {
					echo'<td class="dataTableContent">test</td>
					<td class="dataTableContent">&nbsp;</td>'; ?>


		 	  <?php } ?>

<?php

			} echo'
                  </tr>';

		'</tr>';
		}

	} else {

  if ($srDetail) {
    for ($i = 0; $i < $last; $i++) {
      if ($srMax == 0 or $i < $srMax) {
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                    <td class="dataTableContent">&nbsp;</td>
                    <td class="dataTableContent" align="left"><b><a href="<?php echo tep_catalog_href_link("categories.php?pID=" . $info[$i]['pid'].'&action=new_product') ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a></b>
<?php
  if (is_array($info[$i]['attr'])) {
    $attr_info = $info[$i]['attr'];

	 //echo '<pre>';
	 //echo print_r($attr_info);
	 //echo '</pre>';

    foreach ($attr_info as $attr) {
      echo '<div style="font-style:italic;margin-bottom: 10px;">&nbsp;' . $attr['quant'] . 'x ' ;
      //  $attr['options'] . ': '
      $flag = 0;
      foreach ($attr['options_values'] as $value) {
        if ($flag > 0) {
          echo "," . $value;
        } else {
          echo $value;
          $flag = 1;
        }
      }
      $price = 0;
      foreach ($attr['price'] as $value) {
        $price += $value;
      }
      if ($price != 0) {
        echo ' (';
        if ($price > 0) {
          echo "+";
        }
        echo $currencies->format($price). ')';
      }
		echo '</br>Serial No: '.$attr['serial_no'];
      echo '</div>';
    }
  }
?>                    </td>
                    <td class="dataTableContent" align="right"><?php echo $info[$i]['pquant']. $resp[$i]['products_id']; ?></td>
<?php
          if ($srDetail == 2) {?>
                    <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
<?php
          } else { ?>
                    <td class="dataTableContent">&nbsp;</td>
<?php
          }
?>
                    <td class="dataTableContent">&nbsp;</td>
                  </tr>
<?php
      }
    }
  }
}
}

if ($srCompare > SR_COMPARE_NO) {
?>
                    <tr>
                      <td colspan="5" class="dataTableContent"><?php echo SR_TEXT_COMPARE; ?></td>
                    </tr>
<?php

  $sum = 0;
  while ($sr2->hasNext()) {
    $info = $sr2->next();
    $last = sizeof($info) - 1;
  ?>
                      <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
  <?php
      switch ($srView) {
        case '3':
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr2->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDateEnd)); ?></td>
  <?php
          break;
        case '4':
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr2->showDate)); ?></td>
  <?php
          break;
        default;
  ?>
                        <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr2->showDateEnd)); ?></td>
  <?php
      }
  ?>
                        <td class="dataTableContent" align="right"><?php echo $info[0]['order']; ?></td>
                        <td class="dataTableContent" align="right"><?php echo $info[$last - 1]['totitem']; ?></td>
                        <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last - 1]['totsum']);?></td>
                        <td class="dataTableContent" align="right"><?php echo $payment_total['total'];?></td>
                      </tr>
  <?php
    if ($srDetail) {
      for ($i = 0; $i < $last; $i++) {
        if ($srMax == 0 or $i < $srMax) {
  ?>
                      <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent" align="left"><a href="<?php echo tep_catalog_href_link("categories.php?" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
  <?php
    if (is_array($info[$i]['attr'])) {
      $attr_info = $info[$i]['attr'];

		echo '<pre>';
		print_r($attr_info);
		echo '</pre>';
      foreach ($attr_info as $attr) {
        echo '<div style="">&nbsp;' . $attr['quant'] . 'x ' ;
        //  $attr['options'] . ': '
        $flag = 0;
        foreach ($attr['options_values'] as $value) {
          if ($flag > 0) {
            echo "," . $value;
          } else {
            echo $value;
            $flag = 1;
          }
        }
        $price = 0;
        foreach ($attr['price'] as $value) {
          $price += $value;
        }
        if ($price != 0) {
          echo ' (';
          if ($price > 0) {
            echo "+";
          }
          echo $currencies->format($price). ')';
        }
        echo '</div>';
      }
    }
  ?>                    </td>
                      <td class="dataTableContent" align="right"><?php echo $info[$i]['pquant']; ?></td>
  <?php
            if ($srDetail == 2) {?>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
  <?php
            } else { ?>
                      <td class="dataTableContent">&nbsp;</td>
  <?php
            }
  ?>
                      <td class="dataTableContent">&nbsp;</td>
                    </tr>
  <?php
        }
      }
    }
  }
}
?>
</table>


</div>
   <table class="table table-striped table-hover dataTable">
							<thead><tr class="dataTableHeadingRow" bgcolor="silver">
								<th class="dataTableHeadingContent" align="center">Order Status</th>
								<th class="dataTableHeadingContent" align="center">Orders</th>
								<th class="dataTableHeadingContent" align="center">Total</th>
							</tr></thead>
<?php
	  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_id IN (1 , 3 , 4, 5, 106, 110, 111, 112 , 112 , 114, 115, 116, 117, 118, 119, 122, 123, 125, 126, 127, 129) and language_id = '1'");

/*
	while ($orders_status = tep_db_fetch_array($orders_status_query)) {

if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
	$orders_pending_query = tep_db_query("select count(*) as count from orders o, orders_payment_history oph where o.customer_service_id = '".$_GET['cID']."' and o.orders_id = oph.orders_id and o.orders_status = '" . $orders_status['orders_status_id'] . "' and oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");
	$orders_pending = tep_db_fetch_array($orders_pending_query);

	$current_status = $orders_status['orders_status_id'];

	$orders_total_this_status_query_raw = "select sum(oph.payment_value) as total FROM " . TABLE_ORDERS . " o, orders_payment_history oph WHERE  o.orders_id = oph.orders_id AND o.customer_service_id = '".$_GET['cID']."' and o.orders_status = '". $current_status."' and oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'";

	} else {
	$orders_pending_query = tep_db_query("select count(*) as count from orders o, orders_payment_history oph where o.orders_id = oph.orders_id and o.orders_status = '" . $orders_status['orders_status_id'] . "' and oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");
	$orders_pending = tep_db_fetch_array($orders_pending_query);

	$current_status = $orders_status['orders_status_id'];

	$orders_total_this_status_query_raw = "select sum(oph.payment_value) as total FROM " . TABLE_ORDERS . " o, orders_payment_history oph WHERE o.orders_id = oph.orders_id  and o.orders_status = '". $current_status."' and oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'";
	}

	$orders_total_this_status_query = tep_db_query($orders_total_this_status_query_raw);
	$orders_total_this_status = tep_db_fetch_array($orders_total_this_status_query);
	$orders_contents .= '<tr class="dataTableRow">
							<td class="dataTableContent" align="left"><a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=orders&status=' . $orders_status['orders_status_id']) . '"><b>' . $orders_status['orders_status_name'] . '</b></a></td>
							<td class="dataTableContent" align="center">' . $orders_pending['count'] . '</td>
							<td class="dataTableContent" align="center">' . $store_currency_symbol . number_format($orders_total_this_status['total'],2) . '</td>

						</tr>';
	}
echo $orders_contents;
*/

		//Count in store purchases//
		$instore_purchase_query = tep_db_query("SELECT SUM(oph.payment_value) AS total, COUNT(o.orders_id) AS count FROM orders o, orders_payment_history oph WHERE o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' AND o.ipaddy < '0'");
		$instore_purchase = tep_db_fetch_array($instore_purchase_query);
		//Count online purchases//
		$online_purchase_query = tep_db_query("SELECT SUM(oph.payment_value) AS total, COUNT(o.orders_id) AS count FROM orders o, orders_payment_history oph WHERE o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' AND o.ipaddy > '0'");
		$online_purchase = tep_db_fetch_array($online_purchase_query);

echo '<tr>
		<td align="center">In Store Orders</td>
		<td align="center">'.$instore_purchase['count'].'</td>
		<td align="center">'.$currencies->format($instore_purchase['total']).'</td>
	</tr>
	<tr>
		<td align="center">Online Orders</td>
		<td align="center">'.$online_purchase['count'].'</td>
		<td align="center">'.$currencies->format($online_purchase['total']).'</td>
	</tr>';

?>
</table>
 <table class="table table-striped table-hover dataTable">


								<thead><tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableHeadingContent" align="center">Payment Method</td>
								<td class="dataTableHeadingContent" align="center">Orders</td>
								<td class="dataTableHeadingContent" align="center">Total Collected</td>
								<td class="dataTableHeadingContent" colspan="2" align="center">&nbsp;</td>
								</tr></thead>
<?php

$payment_contents='';

$payment_status_query = tep_db_query("select payment_type_id, payment_type  from ".TABLE_ORDERS_PAYMENT_STATUS."");
	while ($payment_status = tep_db_fetch_array($payment_status_query)) {

		if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
	$payment_pending_query = tep_db_query("select count(*) as count from orders_payment_history oph, orders o  WHERE oph.payment_type_id ='".$payment_status['payment_type_id']."' AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' and o.orders_id = oph.orders_id and o.customer_service_id = '".$_GET['cID']."'"); }
		else {
	$payment_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PAYMENT_HISTORY . " WHERE payment_type_id ='".$payment_status['payment_type_id']."' AND date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");
	}
	$payment_pending = tep_db_fetch_array($payment_pending_query);

	$current_status = $payment_status['payment_type_id'];
		if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
		$payment_total_this_status_query_raw = "select sum(payment_value) as total FROM orders_payment_history oph, orders o WHERE oph.payment_type_id =".$current_status." AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' and o.orders_id = oph.orders_id and o.customer_service_id = '".$_GET['cID']."'"; }
		else {
		$payment_total_this_status_query_raw = "select sum(payment_value) as total FROM orders_payment_history WHERE payment_type_id =".$current_status." AND date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'";
	}




	$payment_total_this_status_query = tep_db_query($payment_total_this_status_query_raw);
	$payment_total_this_status = tep_db_fetch_array($payment_total_this_status_query);
	$payment_contents .= '<tr class="dataTableRow">
							<td class="dataTableContent">' . $payment_status['payment_type'] . '</td>
							<td class="dataTableContent">' . $payment_pending['count'] . '</td>
							<td class="dataTableContent" align="right">' . $store_currency_symbol . number_format($payment_total_this_status['total'],2) . '</td>
							<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>

						</tr>';
	}
echo $payment_contents;
?>
                  </table>

<script language="JavaScript" src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/autosize.js"></script>
<script>
  function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('table_to_excel');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('SalesReportTableExport.' + (type || 'xlsx')));
    }
  </script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
