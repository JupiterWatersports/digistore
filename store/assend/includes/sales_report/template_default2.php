<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE; ?></title>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
   <link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
</head>
<style> 
	@media screen and (min-width: 992px){
		.col-send {width:16%;}
	}
.table{max-width:100%; width:auto;}
table.dataTable{clear:none; float:left; margin-left:20px;}
</style>
<body>
	
<?php $original_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url_complete = parse_url($original_url);
	if (is_numeric($_GET['add_employee'])){
		$get_admin_query = tep_db_query ("UPDATE admin SET piechart = '1' where admin_id = '".$_GET['add_employee']."'");
		parse_str($url_complete['query'], $query); //grab the query part
		unset($query['add_employee']);   //remove a parameter from query
		$dest_query = http_build_query($query); //rebuild new query
		$dest_url = $url_complete['path'] .'?'.$dest_query;	
		
		tep_redirect($dest_url);	
	}
	
	if (is_numeric($_GET['remove_employee'])){
		$get_admin_query = tep_db_query ("UPDATE admin SET piechart = '0' where admin_id = '".$_GET['remove_employee']."'");
		parse_str($url_complete['query'], $query); //grab the query part
		unset($query['remove_employee']);   //remove a parameter from query
		$dest_query = http_build_query($query); //rebuild new query
		$dest_url = $url_complete['path'] .'?'.$dest_query;	
		
		tep_redirect($dest_url);	
	}
	
	?>
	
<div id="wrapper"> 
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<div style="height:20px;"></div>
<h1 class="pageHeading"><?php echo  'Employee Report'; ?></h1>
<div style="height:20px;"></div>
<div class="form-group" style="margin-bottom:20px;">
            <form action="" method="get">
           
                  <div class="col-xs-12 col-sm-2 form-group">
                   <div class="xs-3 sm-12"> <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo 'Yearly'; ?><br></div>
                    <div class="xs-3 sm-12"><input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo 'Monthly'; ?><br></div>
                   <div class="xs-3 sm-12"> <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo 'Weekly'; ?><br></div>
                   <div class="xs-3 sm-12"> <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo 'Daily'; ?><br></div>
                  </div>
                  
                  <div class="col-xs-12 col-sm-4 col-md-3">
                  <div class="xs-12 form-group">
<?php echo 'from date'; ?><br>
                    <select name="startD" size="1">
<?php
      if ($startDate) {
        $j = date("j", $startDate);
      } else {
        $j = 1;
      }
      for ($i = 1; $i < 32; $i++) {
?>
                        <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
      }
?>
                    </select>
                    <select name="startM" size="1">
<?php
      if ($startDate) {
        $m = date("n", $startDate);
      } else {
        $m = 1;
      }
      for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
      }
?>
                    </select>
                    <select name="startY" size="1">
<?php
      if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
      } else {
        $y = 0;
      }
      for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
<?php
    }
?>
                    </select>
                  </div>
                  
                  <div class="xs-12 form-group">
<?php echo 'to date (inclusive)'; ?><br>
                    <select name="endD" size="1">
<?php
    if ($endDate) {
      $j = date("j", $endDate - 60* 60 * 24);
    } else {
      $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
    }
?>
                    </select>
                    <select name="endM" size="1">
<?php
    if ($endDate) {
      $m = date("n", $endDate - 60* 60 * 24);
    } else {
      $m = date("n");
    }
    for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
    }
?>
                    </select>
                    <select name="endY" size="1">
<?php
    if ($endDate) {
      $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
    } else {
      $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
                    </select>
                </div>
                </div>
				
				<div class="col-xs-12 col-sm-3">
                                    
     <?php  $get_all_employees_query = tep_db_query("select * from admin where piechart = '0'");
				$employees_array = array(array('id' => '', 'text' => 'Select'));
			while($get_all_employees = tep_db_fetch_array($get_all_employees_query)){
				
				$employees_array[]= array('id' => $get_all_employees['admin_id'], 'text' => $get_all_employees['admin_firstname'] . '&nbsp;' .$get_all_employees['admin_lastname']);
			}	
				echo      
	'<span style="font-size:16px;">Add New Employee</span>'.
		tep_draw_pull_down_menu('add_employee', $employees_array, '', 'style="width:100%; max-width:300px;" class="form-control"') .'
	</div>'; ?>
                
              <div class="col-xs-12 col-sm-3 col-md-2 col-send">
                
                 <div class="md-12 form-group form-horizontal"><input class="form-control" style="width:100px;" type="submit" value="<?php echo 'Send'; ?>">
				</div>   
         
              </div>
					
			<div class="col-xs-12 col-sm-2">
			<span style="font-size:16px; margin-bottom: 10px; display: block;">Remove Employees</span>
			<?php 
			$get_all_employees3_query = tep_db_query("select * from admin where piechart = '1'");
			while($get_all_employees3 = tep_db_fetch_array($get_all_employees3_query)){
				echo'<div class="remove-filter" style="margin-bottom:10px;"><span class="" style="margin-bottom:10px;"><a href="'.$original_url.'&remove_employee='.$get_all_employees3['admin_id'].'"><i class="fa fa-times" aria-hidden="true" style="margin-right:5px; font-size:16px; color:#D9534F;"></i></a>'.$get_all_employees3['admin_firstname'] . '&nbsp;' .$get_all_employees3['admin_lastname'].'</span></div>';
		} ?>	
			</div>	
	 </form>
            </div>
<style>
audio, canvas, video {
    display: inline-block;
}
 .flot-chart {
    display: block;
    height: 400px;
}

.flot-chart-content {
    width: 100%;
    height: 100%;
}
.dount{
	margin-top:30px;
	margin-bottom:10px;	
}
.dount p span{
	font-family: 'Calibri';
	font-size:12px;		
}
.dount p{
	box-shadow:0px 1px 5px #CCCCCC;
	padding:8px;
	color:#333;	
	width:100%;
}




.panel-body:before, .panel-body:after {
    display: table;
    content: " ";
}
</style>            
            
<div class="panel-body form-group">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-pie-chart"></div>
                            </div>
                        </div>  
                        
                        
                        <div class="panel-body form-group" style="display:none;">
                            <div id="morris-donut-chart"></div>
                        </div>         
            



<?php
$online_numbers_query = tep_db_query("select SUM(oph.payment_value) as total FROM orders_payment_history oph, orders o WHERE o.orders_id = oph.orders_id  and (o.orders_status <>4 or o.orders_status <>109) and o.ipaddy <> '' and oph.payment_type_id <> 5 and  oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");
$online_numbers = tep_db_fetch_array($online_numbers_query);
	
if ($online_numbers['total'] > 0){ $online = $online_numbers['total']; } else { $online = '0'; }
	
	
$get_all_employees2_query = tep_db_query("select * from admin where piechart = '1'");
	$arr = array(array('label'=> "Online Sales", 'data'=> $online));
	while($get_all_employees2 = tep_db_fetch_array($get_all_employees2_query)){
		
	$employee_name = $get_all_employees2['admin_firstname'] . ' ' .$get_all_employees2['admin_lastname'];	
	
$employees_sales2_query = tep_db_query("select SUM(oph.payment_value) as total FROM orders_payment_history oph, orders o WHERE o.orders_id = oph.orders_id  and (o.orders_status <>4 or o.orders_status <>109 ) and o.customer_service_id = '".$employee_name."' and oph.payment_type_id <> 5 and  oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'");
		
$employees_sales_numbers = tep_db_fetch_array($employees_sales2_query);
			
	$arr[] = array('label'=> "$employee_name", 'data' => $employees_sales_numbers['total']);	

	}
    
?>
            
            
<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
<script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="js/plugins/morris/morris.js"></script>
    
     
     <script>
	//Flot Pie Chart
$(function() {

	var dataset1 = <?php echo json_encode($arr); ?>;
	
    
    var plotObj = $.plot($("#flot-pie-chart"), dataset1, {
        series: {
            pie: {
            innerRadius: 0.4,
            show: true,
			label: {
        show: true,
      
        formatter: function(label, series){
          var percent = Math.round(series.percent);
          var number = series.data[0][1]; //kinda weird, but this is what it takes
           return ('&nbsp;<b style="font-size:17px;">' + label + '</b><br/>$<span class="points">' + number +'</span>');
        }
      }
			}
        },
        
        tooltip: true,
        tooltipOpts: {
            content: "%x.0%, %s", // show percentages, rounding to 2 decimal places
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: false
        }
    });
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}
 $('.points').each(function(){
    var v_pound = $(this).html();
    v_pound = numberWithCommas(v_pound);

    $(this).html(v_pound)
        
        })
});

</script>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
