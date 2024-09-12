<?php 
  require('includes/application_top.php');

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/order.php');
  include(DIR_WS_LANGUAGES . $language. '/' . FILENAME_ORDERS_EDIT);
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = $_GET['oID'];
  $order_id = $_GET['oID'];
  
  $order = new manualOrder($oID);
  
  $payment_status_array = array();
  $payment_status_query = tep_db_query("SELECT payment_type_id, payment_type 
                                       FROM " . TABLE_ORDERS_PAYMENT_STATUS . "");
									   
  while ($payment_status = tep_db_fetch_array($payment_status_query)) {
    $payment_statuses[] = array('id' => $payment_status['payment_type_id'],
                               'text' => $payment_status['payment_type']);
    
  $payment_status_array[$payment_status['payment_type_id']] = $payment_status['payment_type'];
  }
  ?>



<?php
         /* if (isset($_GET['date_paid'])) {
	  $datepaid= $_GET['date_paid'];
          $datepaid = (date('Y-m-d') < $datepaid) ? $datepaid : 'null';
	  tep_db_query("update " . TABLE_ORDERS." set date_paid ='".$datepaid."' where orders_id ='".$oID."'"); 
	} */

    $order_date_paid_query = tep_db_query("SELECT orders_id, date_paid FROM " . TABLE_ORDERS . " WHERE orders_id = '" . $oID . "'");			  
          while ($order_date_paid = tep_db_fetch_array($order_date_paid_query)) {  
		if ((!is_null($order_date_paid['date_paid'])) && ($order_date_paid['date_paid']!='0000-00-00 00:00:00')) {
		 $date_paid = $order_date_paid['date_paid'];
		 } else {
		 $date_paid = '';
		}		
 }
?>
<link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">
<div id="payment-method-block-inner" style="float:left; width:100%">
	<div class="dataTableHeadingContent" style="background-color: #3D464E; text-align: center; padding:0.75rem; color:#fff; width:100%;">Payment History</div>
    <div id="payment-date-block" style="float:left; background-color: #F0F1F1; padding:0px;" class="col-xs-12">
        <div class="inner-pay-stuff" style="display:flex; flex-wrap:wrap;">
            <div class="column-12 column-sm-6 column-lg-7 column-xl-6 form-group">
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <div class="row">
                            <label class="column-5 column-sm-4 column-lg-5 col-form-label" style="display:inline-block;">Date Paid<br/><small>(YYYY-MM-DD)</small>
                            </label>
                            <div class="column-7 column-sm-8 column-lg-7">
                                <div id="datetimepicker" class="input-group">
                                    <input type="text" name="date_paid" id="datetimepicker-input" class="form-control"></input>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar "></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
<script type="text/javascript">
  $(function() {
	var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();
		var output = d.getFullYear() + '-' +
(month<10 ? '0' : '') + month + '-' +
(day<10 ? '0' : '') + day;
		$("#datetimepicker-input").val(output);
        $('#datetimepicker-input').datetimepicker({
        	format: 'YYYY-MM-DD',
		})
  })
</script>
				</div>
                    <div class="col-xs-12">
						<div class="row">
							<label class="column-6 column-sm-4 column-lg-5 col-form-label" style="display:inline-block;">Time Paid<br /><small>(HH:MM:SS)</small></label>
							<div class="column-6 column-sm-8  column-lg-7"><input id="time_paid" class="form-control" value="<?php echo $date_paid==''?date('H:i:s'):date('H:i:s',strtotime($date_paid)); ?>" style="max-width:90px;" />
							</div>
						</div>
                    </div>
				</div>
    		</div>
			<div class="column-12 column-sm-6 column-lg-5 column-xl-6">
				<div class="row">
         <?php
		    $total_paid_query = tep_db_query("select SUM(payment_value), SUM(tax_value) 
                                            FROM orders_payment_history 
									        WHERE orders_id = '" . $oID . "'
									        ");
			  $total_paid =  tep_db_fetch_array($total_paid_query);
			  $total_paid_contents .= number_format($total_paid['payment_value']) ;
	
    
		 
  for ($i=0; $i<sizeof($order->totals); $i++) {
	 
	 if($order->info['date_purchased'] < '2017-06-25 00:00:00'){ 
	 	 $get_tax_rate_from_products_query = tep_db_query("select products_tax from orders_products where orders_id = '".$oID."'");
		 $get_tax_rate_from_products = tep_db_fetch_array($get_tax_rate_from_products_query);
		 
		 $tax_rate = $get_tax_rate_from_products['products_tax'];
		 	 
	 } else {
	 
		 $tax_query1 = tep_db_query("select tax_rate from tax_rates where tax_rates_id = '" . $order->delivery['zone_id'] . "'");
		 $tax1 = tep_db_fetch_array($tax_query1);
		 
		 /*$check_for_tax_query = tep_db_query("select * from orders_total where orders_id = ".$oID." and class = 'ot_tax'");
		 $check_for_tax = tep_db_fetch_array($check_for_tax_query); */
		 
		 if($order->info['delivery_location'] == '1'){
			$tax_rate = '7.00';
		  }
		  elseif($order->info['delivery_location'] == '2'){
			$tax_rate = $tax1['tax_rate'];
		  }
		  elseif($order->info['delivery_location'] == '3'){
			$tax_rate = '0.00';
		  }
		  elseif($order->info['delivery_location'] == ''){
			$tax_rate = '0.00';
		  }
	 }
	
	if($total_paid['SUM(payment_value)'] > 0){
	$charged = number_format($order->totals[$i]['value'] - $total_paid['SUM(payment_value)'], 2,'.','');
	$tax_num = number_format(($order->totals[$i]['value'] - $total_paid['SUM(payment_value)']) - (($order->totals[$i]['value'] - $total_paid['SUM(payment_value)']) /  (1 + ($tax_rate / 100))) , 2,'.','');
}
	else{$charged = number_format($order->totals[$i]['value'], 2,'.','');
	$tax_num = number_format(($order->totals[$i]['value'] - ($order->totals[$i]['value'] / (1 + ($tax_rate / 100)))) , 2,'.','');
}	 
	 
    $id = $order->totals[$i]['class'];
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ( ($order->totals[$i]['class'] == 'ot_total') /*||  ($order->totals[$i]['class'] == 'ot_loworderfee')*/ ) {
      echo '<div class="column-12 form-group">
	  			<div class="row">
	  				<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Amount Charged</label>
	  				<div class="column-6 column-sm-5"><input style="width:90px; display:inline-block;" class="form-control" id="paytotalbox" value="'. $charged . '" onChange="refreshTax();"></input>
					</div>
				</div>
			</div>
			<div class="col-xs-12 form-group">
				<div class="row">
					<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Tax Charged</label>
					<div class="column-6 column-sm-5"><input style="width:90px; display:inline-block;" class="form-control" id="taxtotalbox" value="'. $tax_num . '"></input>
					</div>
				</div>
			</div>
	  		<div class="col-xs-12 form-group">
				<div class="row">
					<label class="column-6 column-sm-7 column-lg-5 column-xl-6 col-form-label" style="display:inline-block;">Tax Rate</label>
					<div class="column-6 column-sm-5 column-lg-7 column-xl-5"><input style="width:60px; display:inline-block;" class="form-control" id="taxrate" value="'.$tax_rate.'"> %
					</div>
					<input name="update_totals_xx['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '">
				</div>
	  		</div>
			</div>
	  </div>
	  <div class="column-12 column-sm-6 column-md-4 column-lg-12 column-xl-12">
	  	<div class="row">	  ';	
	  echo '<div class="col-xs-12 column-xl-6 form-group"><b>Current Total Paid:</b>&nbsp;&nbsp;'.'
	  <div style="width:90px; display:inline-block;">$'.number_format( $total_paid['SUM(payment_value)'], 2,'.','').'</div>
	  </div>
	  <div class="col-xs-12 column-xl-6"><b>Current Tax Paid:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'
	  <div style="width:90px; display:inline-block;">$'.number_format( $total_paid['SUM(tax_value)'], 2,'.','').'
	  </div></div>
	  </div>';
	?>
     <script>
	 function refreshTax(){
	if ($("#paytotalbox").val() !== <?php echo number_format($order->totals[$i]['value'], 2,'.',''); ?>)
	var newval = $("#paytotalbox").val() - ($("#paytotalbox").val() / (1 + <?php echo ($tax1['tax_rate'] / 100) ;?>))
	$("#taxtotalbox").val(newval.toFixed(2));
	 }
	 </script>
     
     <?php
     }
  }
?>
				</div>
		</div>
     
  	<div class="col-xs-12">&nbsp;</div>
    <div style="text-align:center; width:100%; margin:0px auto 20px; overflow:auto;" id="payment-status" name="payment-status">
         <div class="form-group"><textarea name="payment-comments" cols="20" rows="3" placeholder="Payment Comments" class="form-control" id="payment-comments" style="width:60%; margin:0px auto;"></textarea></div>
         <div class="col-xs-12">
             <div class="row">
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(1)">Paid Credit</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(2)">Paid Debit</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(3)">Paid Cash</button></div> 
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(4)">Paid Paypal</button></div>
				 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(10)">Paid Ebay</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(7)">Paid Check</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(8)">Amazon Order</button></div>
                 <div class="column-auto column-sm-3 form-group"><button class="btn btn-outline-info" type="button" onClick="updateOrdersPaymentHistoryField(9)">Warranty</button></div>
             </div>
         </div>
    </div>
  
	   <div id="commentsTable_wrap">
         <table style="border: 1px solid #C9C9C9; width:100%; background-color:transparent;" cellspacing="0" cellpadding="2" class="dataTableRow table" id="commentsTable">
            <thead>
                <tr class="dataTableHeadingRow">
                    <th class="dataTableHeadingContent" align="left" style="width:10%">Delete?</th>
                    <th class="dataTableHeadingContent" align="center" style="width:22%">Date Paid</th>
                    <th class="dataTableHeadingContent" align="center" style="width:20%">Payment</th>
                    <th class="dataTableHeadingContent" align="center" style="">Amount</th>
                    <th class="dataTableHeadingContent" align="center" style="width:30%">Comments</th>
                </tr>
            </thead>
    
     <?php
	 $payment_history_query = tep_db_query("SELECT  orders_payment_history_id, date_paid, payment_type_id, payment_value, payment_comments, tax_value 
                                            FROM " . TABLE_ORDERS_PAYMENT_HISTORY . " 
									        WHERE orders_id = '" . (int)$oID . "' 
									       order by orders_payment_history_id desc ");
     
        if (tep_db_num_rows($payment_history_query)) {
          while ($payment_history = tep_db_fetch_array($payment_history_query)) {
          
		   $r++;
           $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        
	     
		   echo '  <tr class="' . $rowClass . '" id="commentRow' . $payment_history['orders_payment_history_id'] . '" >' . "\n" .
         '	  <td class="" align="center"><div><input name="update_comments[' . $payment_history['orders_payment_history_id'] . '][delete]" type="checkbox" onClick="deletePaymentHistory( \'' . $payment_history['orders_payment_history_id'] . '\',  this)"></div></td>' . "\n" . 
		
         '    <td class="" align="center">' . tep_datetime_short($payment_history['date_paid']) . '</td>' . "\n". 
             '<td class="" align="center">' . $payment_status_array[$payment_history['payment_type_id']] . '</td>' . 
			 '<td class="" align="center">'.$currencies->format($payment_history['payment_value'],'').'</td>';
        echo 
             '    <td class="" align="left"><textarea cols="25" rows="4" onchange="savePHC(' . $payment_history['orders_payment_history_id'] . ',this.value);">';
				echo htmlspecialchars($payment_history['payment_comments']);
				echo '</textarea>';
       /* if (ORDER_EDITOR_USE_AJAX == 'true') { 
		echo tep_draw_textarea_field(" update_comments[" . $payment_history['orders_payment_history_id'] . "][payment_comments]", "soft", "40", "5", 
  "" .	tep_db_output($payment_history['payment_comments']) . "", "onChange=\"updateCommentsField('update', '" . $payment_history['orders_payment_history_id'] . "', 'false', encodeURIComponent(this.value))\",''") . '' ."\n";
		 } else {
		 echo tep_draw_textarea_field("update_comments[" . $payment_history['orders_payment_history_id'] . "][payment_comments]", "soft", "40", "5", 
  "" .	tep_db_output($payment_history['payment_comments']) . "") . '' . "\n";
		 }*/
		echo  '    </td>' . "\n";
        echo '  </tr>' . "\n";
  
        }
       } else {
       echo '  <tr>' . "\n" .
            '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
            '  </tr>' . "\n";
       }

    ?>
    
      

  </table> 
  </div>
         
         </div></div>