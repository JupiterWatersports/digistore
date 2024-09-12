<?php
/*
  $Id: stats_customers.php,v 1.9 2002/03/30 15:03:59 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('SR_HEADING_TITLE', 'Sales Report');

define('SR_REPORT_TYPE_YEARLY', 'Yearly');
define('SR_REPORT_TYPE_MONTHLY', 'Monthly');
define('SR_REPORT_TYPE_WEEKLY', 'Weekly');
define('SR_REPORT_TYPE_DAILY', 'Daily');
define('SR_REPORT_START_DATE', 'from date');
define('SR_REPORT_END_DATE', 'to date (inclusive)');
define('SR_REPORT_DETAIL', 'detail');
define('SR_REPORT_MAX', 'show top');
define('SR_REPORT_ALL', 'all');
define('SR_REPORT_SORT', 'sort');
define('SR_REPORT_EXP', 'export');
define('SR_REPORT_SEND', 'send');
define('SR_EXP_NORMAL', 'normal');
define('SR_EXP_HTML', 'HTML only');
define('SR_EXP_CSV', 'CSV');

define('SR_TABLE_HEADING_DATE', 'Date');
define('SR_TABLE_HEADING_ORDERS', '#Orders');
define('SR_TABLE_HEADING_ITEMS', '#Items');
define('SR_TABLE_HEADING_REVENUE', 'Revenue');
define('SR_TABLE_HEADING_SHIPPING', 'Shipping');

define('SR_DET_HEAD_ONLY', 'no details');
define('SR_DET_DETAIL', 'show details');
define('SR_DET_DETAIL_ONLY', 'details with amount');

define('SR_SORT_VAL0', 'standard');
define('SR_SORT_VAL1', 'description');
define('SR_SORT_VAL2', 'description desc');
define('SR_SORT_VAL3', '#Items');
define('SR_SORT_VAL4', '#Items desc');
define('SR_SORT_VAL5', 'Revenue');
define('SR_SORT_VAL6', 'Revenue desc');

define('SR_REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '\n\r');

define('SR_TEXT_COMPARE', 'compared to:');

define('SR_REPORT_COMP_FILTER', 'compare to');
define('SR_REPORT_COMP_NO', 'no compare');
define('SR_REPORT_COMP_DAY', 'previous day');
define('SR_REPORT_COMP_MONTH', 'previous month');
define('SR_REPORT_COMP_YEAR', 'previous year');

?>
