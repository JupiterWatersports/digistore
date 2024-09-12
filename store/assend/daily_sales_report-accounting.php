<?php
/*
  $Id: stats_sales_report2.php,v 1.00 2003/03/08 19:02:22 Exp $

  Charly Wilhelm  charly@yoshi.ch
  
  Released under the GNU General Public License

  Copyright (c) 2003 osCommerce
  
  possible options (srDetail):
  0 no detail
  1 show details (products)
  2 show details only (products)
  
  export
  0 normal view
  1 csv
  
  sort
  0 no sorting
  1 product description asc
  2 product description desc
  3 #product asc, product descr asc
  4 #product desc, product descr desc
  5 revenue asc, product descr asc
  6 revenue desc, product descr desc
  
*/

  // set the default values

  // default detail no detail
  $srDefaultDetail = 0;
  // default view (daily)
  $srDefaultView = 4;
  // default export
  $srDefaultExp = 0;
  // default sort
  $srDefaultSort = 4;
  // default max
  $srDefaultMax = 0;
  // default status
  $srDefaultStatus = 0;
  // default compare
  $srDefaultCompare = 0;

  define('TEMPLATE_DEFAULT', 'includes/sales_report/template_daily_sales_accounting.php');
  define('TEMPLATE_CSV', 'includes/sales_report/template_csv.php');

// it is not necessary to edit below this line  
//--------------------------------------------------------


  define('SR_DETAIL_NO', '0');
  define('SR_DETAIL_WITH', '1');
  define('SR_DETAIL_EXT', '2');

  define('SR_EXPORT_NO', '0');
  define('SR_EXPORT_CSV', '1');

  define('SR_SORT_NO', '0');
  define('SR_SORT_PROD_ASC', '1');
  define('SR_SORT_PROD_DESC', '2');
  define('SR_SORT_PROD_AMOUNT_ASC', '3');
  define('SR_SORT_PROD_AMOUNT_DESC', '4');
  define('SR_SORT_REVENUE_ASC', '5');
  define('SR_SORT_REVENUE_DESC', '6');

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if ( isset($HTTP_GET_VARS['cID']) && (tep_not_null($HTTP_GET_VARS['cID'])) ) {
		tep_session_register('cID');
		$cID = $HTTP_GET_VARS['cID'];
	} else { $cID=''; }


  // detail
  if ( ($HTTP_GET_VARS['detail']) && (tep_not_null($HTTP_GET_VARS['detail'])) ) 
{    $srDetail = $HTTP_GET_VARS['detail'];
  }
  if ($srDetail < SR_DETAIL_NO || $srDetail > SR_DETAIL_EXT) {
    $srDetail = $srDefaultDetail;
  }
  
  // export
  if ( ($HTTP_GET_VARS['export']) && (tep_not_null($HTTP_GET_VARS['export'])) ) 
{    $srExp = $HTTP_GET_VARS['export'];
  }
  if ($srExp < SR_EXPORT_NO || $srExp > SR_EXPORT_CSV) {
    $srExp = $srDefaultExp;
  }
  
  // item_level
  if ( ($HTTP_GET_VARS['max']) && (tep_not_null($HTTP_GET_VARS['max'])) ) {
    $srMax = $HTTP_GET_VARS['max'];
  }
  if (!is_numeric($srMax)) {
    $srMax = $srDefaultMax;
  }
      
  // order status
  if ( ($HTTP_GET_VARS['status']) && (tep_not_null($HTTP_GET_VARS['status'])) ) 
{    $srStatus = $HTTP_GET_VARS['status'];
  }
  if (!is_numeric($srStatus)) {
    $srStatus = $srDefaultStatus;
  }
  
  // sort
  if ( ($HTTP_GET_VARS['sort']) && (tep_not_null($HTTP_GET_VARS['sort'])) ) {
    $srSort = $HTTP_GET_VARS['sort'];
  }
  if ($srSort < SR_SORT_NO || $srSort > SR_SORT_REVENUE_DESC) {
    $srSort = $srDefaultSort;
  }
    
    // check start and end Date

if(isset($_GET['starter_date'])){
	$startDate = $_GET['starter_date'];
} else {
	$startDate = $_GET['start-date'] ? strtotime($_GET['start-date']) : strtotime('today midnight');
}
//$endDate = strtotime(date('Y/m/d'));

  require(DIR_WS_CLASSES . 'daily_sales_report.php');
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srFilter);

  $startDate = $sr->startDate;
  $endDate = $sr->endDate;  
  
  if ($srExp == SR_EXPORT_CSV) {
    require(TEMPLATE_CSV);
  } else {
    require(TEMPLATE_DEFAULT);
  }

if($_GET['action'] == 'update_balance'){
	$date = date("Y-m-d", strtotime($_GET['start-date']));
	
	$check_for_previous_entry = tep_db_query("SELECT * FROM daily_report_total WHERE date = '".$date."'");
	
	if(tep_db_num_rows($check_for_previous_entry) > 1 ){
		$update = array(
			'cc_total' => $_GET['cc_total'],
			'paypal_total' => $_GET['paypal_total'],
			'cash_total' => $_GET['cash_total'],
			'cash_drawer_total' => $_GET['cash_drawer_total'],
			'ebay_total' => $_GET['ebay_payments_total'],
			'signature' => $_GET['signature']
		);
		
		tep_db_perform("daily_report_total", $update, 'update', "date = '".$date."' and"); 
		
	} else {
	
	
	$update = array(
	'date' => $date,
	'cc_total' => $_GET['cc_total'],
	'paypal_total' => $_GET['paypal_total'],
	'cash_total' => $_GET['cash_total'],
	'cash_drawer_total' => $_GET['cash_drawer_total'],
	'signature' => $_GET['signature']);
		
	tep_db_perform("daily_report_total", $update, 'and'); 
	}
?>
<script>
	date = <?php echo strtotime($_GET['start-date']); ?>;
	//alert(date);
	//correctDate =((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();
	
	url = 'daily_sales_report.php?starter_date='+date;
	//alert(correctDate);
	window.location.href = url;
	//location.load('daily_sales_report.php');
</script>
<?php
	
	//tep_redirect(tep_href_link('daily_sales_report.php', 'start-date='.$_GET['start-date'].''));
}
?>