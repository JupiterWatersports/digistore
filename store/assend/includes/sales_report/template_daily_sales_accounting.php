<!doctype html >
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title>Daily Sales Report</title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker.min.css">  
</head>
<style>
.table{max-width:100%; width:auto;}
table.dataTable{clear:none; float:left;}
.ordersRow{border-top:2px dashed #000;}

</style>

<?php require 'includes/template-top.php'; ?>

<div style="height:10px; clear: both;"></div>
<h1 class="pageHeading" style="text-align: center;">Daily Sales Report</h1>
<div style="height:20px;"></div>

<form action="" method="get">
    <div class="col-xs-12 col-sm-2 form-group">
        
    </div>
    
    <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="xs-12 form-group">
<?php echo 'Start Date';
	$current_date = date('m/d/Y');		
			
	if(isset($_GET['starter_date'])){
		$start_date_val = date("m/d/Y", $_GET['starter_date']);
	
	} else {	
		$start_date_val = $_GET['start-date'] ?? $current_date;
	}
						
	echo '<br>
            <div class="input-append" id="datetimepicker" >
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
      <?php  /*
        <div class="xs-12 form-group" style="display:none;">
 echo 'End Date';
$end_date_val = $_GET['end-date'] ?? '';			
			
	echo '</br>
            <div class="input-append" id="datetimepicker2" style="display:none;">
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
                </div> */ ?>
                </div>
                
                <div class="col-xs-12 col-sm-3 col-md-2">
                <div class="xs-8 sm-12 form-group" style="width:183px;">
                    Detail<br>
                    <select name="detail" size="1" class="form-control">
                      <option value="0"<?php if ($srDetail == 0) echo " selected"; ?>>No Details</option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>>Show Details</option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>>Details with amount</option>
                    </select>
                  </div>  
                    
               
              <div class="col-xs-4 col-sm-12">
              <div class="row">
              <?php echo 'View By:'; ?><br>
                    <select name="viewby" size="1" class="form-control">
                    <option value="0" <?php if ($_GET['viewby'] == 0) echo " selected"; ?>>Products</option>
                    <option selected value="1" <?php if ($_GET['viewby'] == 1) echo " selected"; ?>>Orders</option>
                    </select>
                    </div>
              </div>    
                    </div>
                   
                 <div class="col-xs-12 col-sm-3">
               <div class="md-12 form-group">
                    Status<br>
                    <select name="status" size="1" class="form-control">
                      <option value="0">All</option>
<?php
                        foreach ($sr->status as $value) {
?>
                      <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) echo " selected"; ?>><?php echo $value["orders_status_name"] ; ?></option>
<?php
                         }
?>
                    </select></div>
            
                    </div>
                    
             <div class="col-xs-12 col-md-2">
                <div class="md-12 form-group">
                    Sort<br>
                    <select name="sort" size="1"  class="form-control">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>>Standard</option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>>Description</option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>>Description Desc</option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>>#Items</option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>>#Items Desc</option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>>Revenue</option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>>Revenue Desc</option>
                    </select>
                  </div>
                
            <div class="md-12 form-group">
          
        </div>
        <div class="md-12 form-group"><input type="submit" value="<?php echo 'Update'; ?>"></div></div>
                               
            </form>
<div id="responsive-table">
	<table class="table table-striped table-hover dataTable">
           <thead>
                    <tr class="dataTableHeadingRow">
                      <th class="dataTableHeadingContent" align="left" style="width:15%;">Date</th>
                      <th class="dataTableHeadingContent" align="left" style="width:20%;">#Orders</th>
                      <th class="dataTableHeadingContent" align="right" style="width:5%;">#Items</th>
                      <th class="dataTableHeadingContent" align="right" style="width:10%;"><?php echo  'Projected Revenue';?></th>
                      <th class="dataTableHeadingContent" align="right" style="width:10%;">Payment Collected</th>
						<th class="dataTableHeadingContent" align="right" style="width:10%;">Tax Collected</th>
                    </tr>
                    </thead>
<?php
		$payment_total_query = tep_db_query ("select sum(payment_value) as total FROM orders_payment_history WHERE date_paid >= '" . tep_db_input(date("Y-m-d", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d", $endDate)) . "'");	
	
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
if($_GET['viewby'] == '0'){
  if ($srDetail) {
    for ($i = 0; $i < $last; $i++) {
      if ($srMax == 0 or $i < $srMax) {
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                    <td class="dataTableContent">&nbsp;</td>
                    <td class="dataTableContent" align="left">
						<a href="<?php echo tep_catalog_href_link("categories.php?pID=" . $info[$i]['pid'].'&action=new_product') ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
<?php
  if (is_array($info[$i]['attr'])) {
    $attr_info = $info[$i]['attr'];
    foreach ($attr_info as $attr) {
      echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
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
} else {
	$filterString = "";
    	if ($_GET['status'] > 0) {
			$filterString .= " AND o.orders_status = " . $_GET['status'] . " ";
      	}
	  
  		if (isset($_GET['cID']) && ($_GET['cID'] !=='Select Sales Person') ) {
			$filterString .= " AND o.customer_service_id ='".$_GET['cID']."'";
		}
	
		// Create array of orders per the start-end date to compare to orders with payments in the same range
		$orders_info_array = array();
	 	$order_numbers = array();
		$get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased from orders o WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' " . $filterString. "");
		
		while($get_orders = tep_db_fetch_array($get_orders_query)){
			$orders_info_array[] = array('orders_id' => $get_orders['orders_id'],
								 'date_purchased' => $get_orders['date_purchased']);
			
			$order_numbers[] = $get_orders['orders_id'];
		}
		
		// Display Orders before first date //
		$get_additional_pmnts_query = tep_db_query("SELECT SUM(oph.payment_value) as total, SUM(oph.tax_value) AS taxtotal, o.ipaddy, o.orders_id, o.date_purchased FROM orders_payment_history oph, orders o WHERE oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'" .$filterString." AND o.orders_id = oph.orders_id AND o.orders_id NOT IN ('".implode( "', '" , $order_numbers)."') GROUP BY o.orders_id ORDER BY o.date_purchased ASC");
	
	if(tep_db_num_rows($get_additional_pmnts_query) > '1'){
		while($get_additional_pmnts = tep_db_fetch_array($get_additional_pmnts_query)){
			$oID = $get_additional_pmnts['orders_id'];
			
			if ($get_additional_pmnts['ipaddy'] > '0'){
				$inoutstore = 'Online Order';
			} else {
				$inoutstore = 'In Store';
			}
			
            $order_total_query = tep_db_query("SELECT value from orders o, orders_total ot WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = ot.orders_id  and o.orders_id = ".$oID." and ot.class = 'ot_total' " . $filterString. "");
            $order_total = tep_db_fetch_array($order_total_query);
			
            $get_payments_query = tep_db_query("SELECT SUM(oph.payment_value) as total, SUM(oph.tax_value) AS taxtotal, ops.payment_type FROM orders o, orders_payment_history oph, orders_payment_status ops WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = oph.orders_id and ops.payment_type_id = oph.payment_type_id and o.orders_id = ".$oID." " . $filterString. "");
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
		'<td class="dataTableContent">
		<a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'"><b>'.$oID.'</b></a> ('.$inoutstore.')</td>'. 
		'<td class="dataTableContent">&nbsp;</td>'.
		'<td class="dataTableContent" align="right"><b style="color:rgba(0, 0, 0, 0.72);">'.$currencies->format($order_total['value']).'</b></td>';
			if($get_additional_pmnts['total'] > 0){
				echo '<td class="dataTableContent" align="right"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'#payment-method-block-inner"><b>'.$currencies->format($get_additional_pmnts['total']).'</b>'.$payment_icon.'</a></td>
				<td class="dataTableContent" align="right"><b>'.$currencies->format($get_additional_pmnts['taxtotal']).'</b></td>';
			} else {
				echo'
				<td class="dataTableContent" align="right">&nbsp;</td>
				<td class="dataTableContent" align="right">&nbsp;</td>';
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
                    <td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>';
						
          			} else { 
					echo'<td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>'; ?>
                    
                    
		 	  <?php } ?>
                    
<?php
	 			 
			}
			echo '</tr>';
		
		'</tr>';
		}
	} 
		
	
		// Show orders beginning at start date // 
		$get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased, o.ipaddy from orders o WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' " . $filterString. " ");
		
		while($get_orders = tep_db_fetch_array($get_orders_query)){
			$oID = $get_orders['orders_id'];
			if ($get_orders['ipaddy'] > '0'){
				$inoutstore = 'Online Order';
			} else {
				$inoutstore = 'In Store';
			}
			
			
            $order_total_query = tep_db_query("SELECT value from orders o, orders_total ot WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = ot.orders_id  and o.orders_id = ".$oID." and ot.class = 'ot_total' " . $filterString. "");
            $order_total = tep_db_fetch_array($order_total_query);

            $get_payments_query = tep_db_query("SELECT sum(oph.payment_value) as total, SUM(oph.tax_value) AS taxtotal, ops.payment_type from orders o, orders_payment_history oph, orders_payment_status ops WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' and ops.payment_type_id = oph.payment_type_id AND o.orders_id = ".$oID." " . $filterString. "");
            $get_payments = tep_db_fetch_array($get_payments_query);
			
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
				
		$date = $get_orders['date_purchased'];
		$date1 = new DateTime($date);
		$date2 = $date1->format('m-d-Y');	
		
		echo'<tr class="dataTableRow ordersRow" onMouseOver="this.className="dataTableRowOver";this.style.cursor="hand"" onMouseOut="this.className="dataTableRow"">'.
		'<td class="dataTableContent" align="right"><div style="float:left;">'.$date2.'</div>Order #</td>'.
		'<td class="dataTableContent"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'"><b>'.$oID.'</b></a> ('.$inoutstore.')</td>'. 
		'<td class="dataTableContent">&nbsp;</td>'.
		'<td class="dataTableContent" align="right"><b style="color:rgba(0, 0, 0, 0.72);">'.$currencies->format($order_total['value']).'</b></td>';
			if($get_payments['total'] > 0){
				echo '<td class="dataTableContent" align="right"><a onclick="return !window.open(this.href);" href="edit_orders.php?oID='.$oID.'#payment-method-block-inner"><b>'.$currencies->format($get_payments['total']).'</b>'.$payment_icon.'</a></td>
				<td class="dataTableContent" align="right"><b>'.$currencies->format($get_payments['taxtotal']).'</b></td>';
			} else {
				echo'<td class="dataTableContent" align="right">&nbsp;</td>
				<td class="dataTableContent" align="right">&nbsp;</td>';
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
						$final_price = $currencies->format(tep_add_tax($get_products['final_price'], $get_products['products_tax']));
						
                 echo'<td class="dataTableContent" align="right">'. $final_price.'</td>
                    <td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>';
						
          			} else { 
					echo'<td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent">&nbsp;</td>'; ?>
                    
                    
		 	  <?php } ?>
                    
<?php
	 			 
			} echo'
                  </tr>';
		
		'</tr>';
		}
	
	
}
}

?>
</table>

	
</div>
<div class="col-xs-12">
	<div class="col-sm-6">
		<table class="table table-striped table-hover dataTable">
            <thead><tr class="dataTableHeadingRow" bgcolor="silver">
                <th class="dataTableHeadingContent" align="center">Order Type</th>
                <th class="dataTableHeadingContent" align="center">Orders</th>
                <th class="dataTableHeadingContent" align="center">Total</th>
            </tr></thead>
<?php 
	  $orders_status_query = tep_db_query("select * FROM orders o, orders_payment_history oph WHERE o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'"); 
		
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
	</div>
	<div class="col-sm-6">
		<table class="table table-striped table-hover dataTable col-sm-6">				
            <thead><tr class="dataTableHeadingRow" bgcolor="silver">
            <td class="dataTableHeadingContent" align="center">Payment Method</td>
            <td class="dataTableHeadingContent" align="center">Orders</td>
            <td class="dataTableHeadingContent" align="center">Total Collected</td>
            <td class="dataTableHeadingContent" colspan="2" align="center">&nbsp;</td>
            </tr></thead>
<?php

$payment_contents = '';
$credit_debit_total = 0;
$cash_total = 0;
$paypal_total = 0;			

$payment_status_query = tep_db_query("select payment_type_id, payment_type  from ".TABLE_ORDERS_PAYMENT_STATUS."");
	while ($payment_status = tep_db_fetch_array($payment_status_query)) {
		
		if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
			$payment_pending_query = tep_db_query("select count(*) as count from orders_payment_history oph, orders o  WHERE oph.payment_type_id ='".$payment_status['payment_type_id']."' AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' and o.orders_id = oph.orders_id and o.customer_service_id = '".$_GET['cID']."'");
		} else {
			$payment_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PAYMENT_HISTORY . " WHERE payment_type_id ='".$payment_status['payment_type_id']."' AND date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'"); 
		}
	$payment_pending = tep_db_fetch_array($payment_pending_query);    

	$current_status = $payment_status['payment_type_id'];
		if (isset($_GET['cID']) && $_GET['cID'] <> 'Select Sales Person') {
			$payment_total_this_status_query_raw = "select sum(payment_value) as total FROM orders_payment_history oph, orders o WHERE oph.payment_type_id =".$current_status." AND oph.date_paid >= '" . tep_db_input(date("Y-m-d", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' and o.orders_id = oph.orders_id and o.customer_service_id = '".$_GET['cID']."'";
		} else {
			$payment_total_this_status_query_raw = "select sum(payment_value) as total FROM orders_payment_history WHERE payment_type_id =".$current_status." AND date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'";
		}
		
		$payment_total_this_status_query = tep_db_query($payment_total_this_status_query_raw);
		$payment_total_this_status = tep_db_fetch_array($payment_total_this_status_query);
		
		$payment_icon = '';
        switch($payment_status['payment_type']){
            case 'Paid Credit':
                $payment_icon = '<i style="margin-right:10px;" class="fa fa-credit-card"></i>';
				$credit_debit_total += $payment_total_this_status['total'];
                break;
            case 'Paid Debit':
                $payment_icon = '<i style="margin-right:10px;" class="fa fa-cc-visa"></i>';
				$credit_debit_total += $payment_total_this_status['total'];
                break;
            case 'Paid Cash':
                $payment_icon = '<i style="margin-right:10px;" class="fa fa-money"></i>';
				$cash_total += $payment_total_this_status['total'];
                break;
            case 'Paid Paypal':
                $payment_icon = '<i style="margin-right:10px;" class="fa fa-paypal"></i>';
				$paypal_total += $payment_total_this_status['total'];
                break;	
			case 'Ebay':
				$ebay_total += $payment_total_this_status['total'];
				break;	
        }
			  
	$payment_contents .= '<tr class="dataTableRow">
							<td class="dataTableContent">'.$payment_icon.$payment_status['payment_type'] . '</td>
							<td class="dataTableContent">' . $payment_pending['count'] . '</td>
							<td class="dataTableContent" align="right">' . $store_currency_symbol . number_format($payment_total_this_status['total'],2) . '</td>
							<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
						</tr>';
		
	} 
echo $payment_contents;	
?>
		</table>
		
<?php $current_credit_total_query = tep_db_query("SELECT cc_total as cc FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");
	$current_credit_total = tep_db_fetch_array($current_credit_total_query);
			
	$current_cash_total_query = tep_db_query("SELECT cash_total as cash FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");
	$current_cash_total = tep_db_fetch_array($current_cash_total_query);
			
	$current_paypal_total_query = tep_db_query("SELECT paypal_total as pp FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");
	$current_paypal_total = tep_db_fetch_array($current_paypal_total_query);
			
	$current_credit_total_query = tep_db_query("SELECT cc_total as cc FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");
	$current_credit_total = tep_db_fetch_array($current_credit_total_query);
	
	$current_cash_total_query = tep_db_query("SELECT cash_drawer_total as cd_total FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");		
	$current_cash_in_drawer = tep_db_fetch_array($current_cash_total_query);
			
	$current_ebay_total_query = tep_db_query("SELECT ebay_total as ebay FROM daily_report_total WHERE date = '".date("Y-m-d", $startDate)."'");
	$current_ebay_total = tep_db_fetch_array($current_ebay_total_query);
		
	$get_signature_query = tep_db_query("SELECT signature FROM daily_report_total WHERE date ='".date("Y-m-d", $startDate)."'");
	$get_signature = tep_db_fetch_array($get_signature_query);	
			
	$datere = date('Y-m-d', $startDate);
	$startDate2 = strtotime('-1 day' ,$datere);
			
	$cash_date = date('Y-m-d' , $startDate + $startDate2);
						
	$previous_day_cash_query = tep_db_query("SELECT cash_drawer_total AS cash FROM daily_report_total WHERE date = '".$cash_date."'");
	$previous_day_cash = tep_db_fetch_array($previous_day_cash_query);	
?>
	</div>
</div>
<script language="JavaScript" src="js/bootstrap-datetimepicker.min.js"></script>
<script>
$('#submitBalances').on("click", function(e){
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
<script src="js/autosize.js"></script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
