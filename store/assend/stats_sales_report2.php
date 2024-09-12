<?php
/*
  $Id: stats_sales_report2.php,v 1.00 2003/03/08 19:02:22 Exp $

  Charly Wilhelm  charly@yoshi.ch

  Released under the GNU General Public License

  Copyright (c) 2003 osCommerce

  possible views (srView):
  1 yearly
  2 monthly
  3 weekly
  4 daily

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

  compare
  0 no compare
  1 compare with the values a month ago
  2 compare with the values a week ago
  3 compare with the values a month ago
  4 compare with the values a year ago

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

define('TEMPLATE_DEFAULT', 'includes/sales_report/template_default.php');
define('TEMPLATE_CSV', 'includes/sales_report/template_csv.php');
// it is not necessary to edit below this line
//--------------------------------------------------------

// define the constants
define('SR_VIEW_YEARLY', '1');
define('SR_VIEW_MONTHLY', '2');
define('SR_VIEW_WEEKLY', '3');
define('SR_VIEW_DAILY', '4');

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

define('SR_COMPARE_NO', '0');
define('SR_COMPARE_DAY', '1');
define('SR_COMPARE_MONTH', '2');
define('SR_COMPARE_YEAR', '3');

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

if ( isset($_GET['cID']) && (tep_not_null($_GET['cID'])) ) {
	tep_session_register('cID');
	$cID = $_GET['cID'];
} else {
	$cID='';
}

// report views (1: yearly 2: monthly 3: weekly 4: daily)
if ( ($_GET['report']) && (tep_not_null($_GET['report'])) ) {
	$srView = $_GET['report'];
}

if ($srView < SR_VIEW_YEARLY || $srView > SR_VIEW_DAILY) {
	$srView = $srDefaultView;
}

// detail
if (($_GET['detail']) && (tep_not_null($_GET['detail']))){
	$srDetail = $_GET['detail'];
}

if ($srDetail < SR_DETAIL_NO || $srDetail > SR_DETAIL_EXT) {
	$srDetail = $srDetail;
}

// export
if ( ($_GET['export']) && (tep_not_null($_GET['export']))){
	$srExp = $_GET['export'];
}

if ($srExp < SR_EXPORT_NO || $srExp > SR_EXPORT_CSV) {
	$srExp = $srDefaultExp;
}

// item_level
if ( ($_GET['max']) && (tep_not_null($_GET['max']))){
	$srMax = $_GET['max'];
}

if (!is_numeric($srMax)) {
	$srMax = $srDefaultMax;
}

  // order status
  if ( ($_GET['status']) && (tep_not_null($_GET['status']))){
	  $srStatus = $_GET['status'];
  }
  if (!is_numeric($srStatus)) {
    $srStatus = $srDefaultStatus;
  }

  // sort
  if ( ($_GET['sort']) && (tep_not_null($_GET['sort'])) ) {
    $srSort = $_GET['sort'];
  }
  if ($srSort < SR_SORT_NO || $srSort > SR_SORT_REVENUE_DESC) {
    $srSort = $srDefaultSort;
  }

  // compare
  if ( ($_GET['compare']) && (tep_not_null($_GET['compare'])) ) {
    $srCompare = $_GET['compare'];
  }
  if ($srCompare < SR_COMPARE_NO || $srCompare > SR_COMPARE_YEAR) {
    $srCompare = $srDefaultCompare;
  }


  // check start and end Date
$startDate = $_GET['start-date'] ? strtotime($_GET['start-date']) : strtotime('today midnight');

$endDate = strtotime($_GET['end-date']) ? strtotime($_GET['end-date']. "+24 hours") : mktime(0, 0, 0, date("m", $startDate), date("d", $startDate) + 1, date("Y", $startDate));

//$endDate = $_GET['end-date'] ? strtotime($_GET['end-date']) : mktime(0, 0, 0, date("m", $startDate), date("d", $startDate) + 1, date("Y", $startDate));

require 'includes/classes/sales_report2.php';
$sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srFilter);


  if ($srCompare > SR_COMPARE_NO) {
    if ($srCompare == SR_COMPARE_DAY) {
      $compStartDate = mktime(0, 0, 0, date("m", $startDate), date("d", $startDate) - 1, date("Y", $startDate));
      $compEndDate = mktime(0, 0, 0, date("m", $endDate), date("d", $endDate) - 1, date("Y", $endDate));
    } else if ($srCompare == SR_COMPARE_MONTH) {
      $compStartDate = mktime(0, 0, 0, date("m", $startDate) - 1, date("d", $startDate), date("Y", $startDate));
      $compEndDate = mktime(0, 0, 0, date("m", $endDate) - 1, date("d", $endDate), date("Y", $endDate));
    } else if ($srCompare == SR_COMPARE_YEAR) {
      $compStartDate = mktime(0, 0, 0, date("m", $startDate), date("d", $startDate), date("Y", $startDate) - 1);
      $compEndDate = mktime(0, 0, 0, date("m", $endDate), date("d", $endDate), date("Y", $endDate) - 1);
    }
    if ($compStartDate != $startDate) {
      $sr2 = new sales_report($srView, $compStartDate, $compEndDate, $srSort, $srStatus, $srFilter);
      $compStartDate = $sr2->startDate;
      $compEndDate = $sr2->endDate;
    }
  }
  $startDate = $sr->startDate;
  $endDate = $sr->endDate;
  
  file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - require template\n", FILE_APPEND);

  if ($srExp == SR_EXPORT_CSV) {
    require(TEMPLATE_CSV);
  } else {
    require(TEMPLATE_DEFAULT);
  }
  
  file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - done\n", FILE_APPEND);

	echo  date("Y-m-d", $endDate);
  
  /*$con_array = [];
  
    $con_query = tep_db_query("SHOW VARIABLES LIKE 'character_set%';");
    while ($conrow = tep_db_fetch_array($con_query)) {
      $con_array[] = $conrow;
    }
    
    $con_query2 = tep_db_query("SHOW VARIABLES LIKE 'collation%';");
    while ($conrow = tep_db_fetch_array($con_query2)) {
      $con_array[] = $conrow;
    }
  
    error_log(date('d-m-y h:i:s')."  - DB - ".json_encode($con_array)."\n", 3, "/home/live/log/mysql-error.log");*/   
?>