<?php
/*
  $Id: stats_sales.php 2008-08-16 $

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if ($HTTP_GET_VARS['month'] == '') {
    $month = date("m");
    $year = '20' . date("y");
  } else {
    $month = $HTTP_GET_VARS['month'];
    $year = $HTTP_GET_VARS['year'];
  }

//Set to 0.00 in order to avoid display of commission  
  $commission_percentage = 0.00;
//Set to 0.05 means 5%, 0.10 means 10% a.s.o.
  
  $months = array();
  $months[] = array('id' => 1, 'text' => TEXT_NAME_JANUARY);
  $months[] = array('id' => 2, 'text' => TEXT_NAME_FEBRUARY);
  $months[] = array('id' => 3, 'text' => TEXT_NAME_MARCH);
  $months[] = array('id' => 4, 'text' => TEXT_NAME_APRIL);
  $months[] = array('id' => 5, 'text' => TEXT_NAME_MAY);
  $months[] = array('id' => 6, 'text' => TEXT_NAME_JUNE);
  $months[] = array('id' => 7, 'text' => TEXT_NAME_JULY);
  $months[] = array('id' => 8, 'text' => TEXT_NAME_AUGUST);
  $months[] = array('id' => 9, 'text' => TEXT_NAME_SEPTEMBER);
  $months[] = array('id' => 10, 'text' => TEXT_NAME_OCTOBER);
  $months[] = array('id' => 11, 'text' => TEXT_NAME_NOVEMBER);
  $months[] = array('id' => 12, 'text' => TEXT_NAME_DECEMBER);

  $years = array();

  $years[] = array('id' => 2007, 'text' => '2007');
  $years[] = array('id' => 2008, 'text' => '2008');
  $years[] = array('id' => 2009, 'text' => '2009');
  $years[] = array('id' => 2010, 'text' => '2010');
  $years[] = array('id' => 2011, 'text' => '2011');
  $years[] = array('id' => 2012, 'text' => '2012');
  $years[] = array('id' => 2013, 'text' => '2013');
  $years[] = array('id' => 2014, 'text' => '2014');
  $years[] = array('id' => 2015, 'text' => '2015');
  $years[] = array('id' => 2016, 'text' => '2016');
  $years[] = array('id' => 2017, 'text' => '2017');
  $years[] = array('id' => 2018, 'text' => '2018');

  $status = (int)$HTTP_GET_VARS['status'];

  $statuses_query = tep_db_query("select * from ". TABLE_ORDERS_STATUS ." where language_id = $languages_id order by orders_status_name");
  $statuses = array();
  $statuses[] = array('id' => 0, 'text' => TEXT_SHOW_ALL);
  while ($st = tep_db_fetch_array($statuses_query)) {
     $statuses[] = array('id' => $st['orders_status_id'], 'text' => $st['orders_status_name']);
  }

  if ($status != 0)  {
    $os = " and o.orders_status = " . $status . " ";
  } else {
    $os = '';
  }

  switch ($HTTP_GET_VARS['by']){
  default:
  case 'product':
    $sales_products_query = tep_db_query("select sum(op.final_price*op.products_quantity) as daily_prod, sum(op.final_price*op.products_quantity*(1+op.products_tax/100)) as withtax, o.date_purchased, op.products_name, sum(op.products_quantity) as qty, op.products_model from ". TABLE_ORDERS ." as o, ". TABLE_ORDERS_PRODUCTS ." as op where o.orders_status <>4 and o.orders_status <>109 and o.orders_id = op.orders_id and month(o.date_purchased) = " . $month . " and year(o.date_purchased) = " . $year . $os . " GROUP by products_id ORDER BY daily_prod DESC");
  break;
  case 'name':
  	$sales_products_query = tep_db_query("select sum(op.final_price*op.products_quantity) as daily_prod, sum(op.final_price*op.products_quantity*(1+op.products_tax/100)) as withtax, o.date_purchased, op.products_name, sum(op.products_quantity) as qty, op.products_model from ". TABLE_ORDERS ." as o, ". TABLE_ORDERS_PRODUCTS ." as op where o.orders_status <>4 and o.orders_status <>109 and o.orders_id = op.orders_id and month(o.date_purchased) = " . $month . " and year(o.date_purchased) = " . $year . $os . " GROUP by products_id ORDER BY op.products_name");
  break;
  case 'units':
  	$sales_products_query = tep_db_query("select sum(op.final_price*op.products_quantity) as daily_prod, sum(op.final_price*op.products_quantity*(1+op.products_tax/100)) as withtax, o.date_purchased, op.products_name, sum(op.products_quantity) as qty, op.products_model from ". TABLE_ORDERS ." as o, ". TABLE_ORDERS_PRODUCTS ." as op where o.orders_status <>4 and o.orders_status <>109 and o.orders_id = op.orders_id and month(o.date_purchased) = " . $month . " and year(o.date_purchased) = " . $year . $os . " GROUP by products_id ORDER BY qty DESC");
  break;
  case 'date':
    $sales_products_query = tep_db_query("select sum(op.final_price*op.products_quantity) as daily_prod, sum(op.final_price*op.products_quantity*(1+op.products_tax/100)) as withtax, o.date_purchased, op.products_name, sum(op.products_quantity) as qty, op.products_model from ". TABLE_ORDERS ." as o, ". TABLE_ORDERS_PRODUCTS ." as op where o.orders_status <>4 and o.orders_status <>109 and o.orders_id = op.orders_id and month(o.date_purchased) = " . $month . " and year(o.date_purchased) = " . $year . $os . " GROUP by dayofmonth(o.date_purchased), products_id");
  break;
    }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<style>
select{width:100%;}
.dataTableHeadingContent a{color:#fff;}
.dataTableHeadingContent a:hover{text-decoration:underline;}
</style>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- body //-->
<div id="wrapper">
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
		<div style="height:20px;"></div>
        <h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
        <div style="height:20px;"></div>
	
		
		<form action="stats_sales.php" method=get>
 
           <div class="col-xs-12 form-group">
           <div class="col-xs-4"><?php echo TEXT_MONTH; ?>
           <?php echo tep_draw_pull_down_menu('month', $months, $month, 'onchange=\'this.form.submit();\''); ?></div>
             <div class="col-xs-4"><?php echo TEXT_YEAR; ?>
       <?php echo tep_draw_pull_down_menu('year', $years, $year, 'onchange=\'this.form.submit();\''); ?></div>
               <div class="col-xs-4"><?php echo TEXT_STATUS; ?>
             <?php echo tep_draw_pull_down_menu('status', $statuses, $status, 'onchange=\'this.form.submit();\''); ?></div>
            
            </div>
      
		<input type="hidden" name="by" value="<?=$HTTP_GET_VARS['by']?>">
		</form>
      
          <div class="col-xs-12 form-group">
           <div class="col-xs-20"><label><?php echo TEXT_SORT_BY; ?></label></div>
           <div class="col-xs-20"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=date', 'NONSSL') . '">' . TEXT_BY_DATE . '</a>'; ?></div>
           <div class="col-xs-20"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=product', 'NONSSL') . '">' . TEXT_BY_AMOUNT . '</a>'; ?></div>
           <div class="col-xs-20"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=units', 'NONSSL') . '">' . TEXT_BY_UNITS_SOLD . '</a>'; ?></div>
           <div class="col-xs-20"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=name', 'NONSSL') . '">' . TEXT_BY_NAME . '</a>'; ?></div>
          </div>
         
<?php

  if (tep_db_num_rows($sales_products_query) > 0) {
    $dp = '';
    $total=0;
	$total_wtax=0;
    while ($sales_products = tep_db_fetch_array($sales_products_query)) {
      if ($HTTP_GET_VARS['by']=='product' || $HTTP_GET_VARS['by'] == 'units' || $HTTP_GET_VARS['by'] == 'name' ) {
	    $ddp='Product';
		$table_title = '';
	  } else {
	    $ddp = tep_date_short($sales_products['date_purchased']);
        $table_title = tep_date_long($sales_products['date_purchased']);
	  }
        if (($dp != $ddp)) { //if day has changed (or first day)
          if ($dp != '') { //close previous day if not first one
?>
       
       
        </table><br>
      </div>

<?php
        }
?>


     <div id="responsive-table">
    <b><?php echo $table_title; ?></b>
 
            <table class="table table-striped table-bordered dataTable">
              <tr class="dataTableHeadingRow">
			    <td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_MODEL; ?></td>
                <td class="dataTableHeadingContent" width="40%"><a href=<?php echo tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=name', 'NONSSL') ?>><?php echo TABLE_HEADING_NAME; ?></a></td>
                <td class="dataTableHeadingContent" align="center" width="15%"><a href=<?php echo tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=units', 'NONSSL') ?>><?php echo TABLE_HEADING_QUANTITY; ?></a></td>
                <td class="dataTableHeadingContent" align="center" width="15%"><a href=<?php echo tep_href_link(FILENAME_STATS_SALES, tep_get_all_get_params(array('by')).'&by=product', 'NONSSL') ?>><?php echo TABLE_HEADING_TOTAL; ?></a></td>
				<td class="dataTableHeadingContent" align="right" width="15%"><?php echo TABLE_HEADING_TOTAL_TAX; ?>&nbsp;</td>
              </tr>
<?php } ?>
              <tr class="dataTableRow">
			    <td class="dataTableContent" width="15%"><?php echo $sales_products ['products_model']; ?></td>
                <td class="dataTableContent" width="40%"><?php echo $sales_products ['products_name']; ?></td>
                <td class="dataTableContent" align="center" width="15%"><?php echo $sales_products ['qty']; ?></td>
                <td class="dataTableContent" align="center" width="15%"><?php echo $currencies->display_price($sales_products ['daily_prod'],0); ?>&nbsp;</td>
				<td class="dataTableContent" align="right" width="15%"><?php echo $currencies->display_price($sales_products ['withtax'],0); ?>&nbsp;</td>
              </tr>
              
<?php 
	  $total+=$sales_products ['daily_prod'];
	  $total_wtax+=$sales_products ['withtax'];
	  $commission = ($total * $commission_percentage);
	  $commission_display = $commission_percentage * 100;
      $dp = $ddp;
    }
?>



</table>
      </div>
<?php
    if ($status == 0) { echo '<div class="col-xs-12 form-group form-horizontal">' . TEXT_MONTHLY_TOTAL_SALES . '&nbsp;<b>' . $currencies->display_price($total,0) . '</b></div>'; } else { echo '<div class="col-xs-12 form-group form-horizontal">' . TEXT_MONTHLY_SALES . '&nbsp;<b>' . $currencies->display_price($total,0) . '</b></div>'; }
	if ($status == 0) { echo '<div class="col-xs-12 form-group">' . TEXT_MONTHLY_TOTAL_SALES_TAX . '&nbsp;<b>' . $currencies->display_price($total_wtax,0) . '</b></div>'; } else { echo '<<div class="col-xs-12 form-group">' . TEXT_MONTHLY_SALES_TAX . '&nbsp;<b>' . $currencies->display_price($total_wtax,0) . '</b></div>'; }
	if (($commission_percentage != 0) && ($status == 0)) { echo '<div class="col-xs-12 form-group">' . sprintf(TEXT_MONTHLY_COMMISSION, $commission_display) . '&nbsp;<b>' . $currencies->display_price($commission,0) . '</b></div>'; }
    
   } else {
?>
  <tr>
    <td class=main><?php echo '<b>' . TEXT_NO_RECORDS . '</b>'; ?></td>
  </tr>
<?php
   }
?>
        


    
<!-- body_text_eof //-->

  
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
