<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
  
  ORDER TRACKING for osCommerce v2.3.1
  Originally Created by: Kieth Waldorf (14 Sep 2003)
  
Updated by: Eric Covert
- Added repeat customer
Updated by: Alan
- Added Localization work for English and Brazilian Portugese
Updated by: Robert Hellemans (v2.2)
- Added v2.2
Updated by: Jared Call (v2.1, v2.3, v2.4, v2.5, v2.6)
- Added tracking by current order status (Admin >> Reports >> Status Tracking).
- This shows current database $$ totals (not just for this year) for orders in each order status.  For example, if you currently have 20 orders in Pending status, for $20 each, status_tracking.php will show Pending :: 20 :: $400.
- Added MySQL indexes for better performance
- Apostrophes are now stripped in Zone names.  This means that orders_tracking_zones.php now works for countries like M'Sila, Côte d'Ivoire, etc.
Updated by: Jared Call (v2.6a)
- Fixed typo in MySQL command (step 5)
Updated by: Kornie (v2.6b)
- Fixed only the select queries in orders_tracking.php, so FROM orders became FROM " . TABLE_ORDERS . " and included the german language files.
Updated by: Jared Call (v2.6c)
- Added Spanish translation
- Added fix from form (Thanks, Young!) where stats would not show up for months with only one order.
Updated by: Jared Call (v2.7)
- Added Orders Tracking by Postal Code (with US zip lookup)
- Thanks to Luqi for sponsoring this feature
- Simplified date code (old code still there, just commented)
Updated by: Jared Call (v2.7a)
- Included the correct orders_tracking_zones.php file
Updated by: David Radford (v2.8)
- Changed the way the store currency is determined
- Added orders_tracking_countries.php
- Incorporated the simplified database query suggested in the forums  
Updated by: Steel Shadow (v2.8b)
- Fixed date error causing "Yesterday" info not to work
- Changed default profit rate from 30% to 60%
Updated by: Keith W (v2.9) May 15, 2007
- Fixed repeat customers per year
- Readded estimated inventory value
- Changed % profit back to closer to national retail average (20%)
Updated by: Keith W (v2.9a) Oct 18, 2007
- Fixed todays and yesterdays sales. Previous version took the total # of orders yesterday and subtracted that from the current orders_id #. This caused it to incorrectly list todays and yesterdays sales # as oscommerce by default creates and deletes blank orders if a transaction fails hence the total # of orders may be 50 but the actual last order # from yesterday was 60 orders later because oscommerce deleted some of those orders. Now this is fixed.
Updated by: Tomcat (v2.9b) Sept 29, 2008
- Fix to orders_tracking_countries.php
Updated by: Keith W (v2.9c) Sept 29, 2008
- Two significant new features added including monthly & yearly ship charge totals and monthly repeat visitor totals.
Updated by: Andy Nguyen (andyn@microheli.com) Jan 17-2011 (v3.0)
- Fixed error: 1054 - Unknown column 'p.products_cost' in 'field list'
- Fixed Profit % not was updated
- Fixed Estimated Inventory Value was not updated
- Added Total Value of Inventory
- Added Total Items in Inventory
- Highlighted clickable items  
- Joined all Order Tracking by Country, by Zone, by Postal and by Status in 1 page
- Updated Tables format
- Cleaned up codes
(English version only. Needs to update more languages)	
*/

  require('includes/application_top.php');

// get main currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '" . DEFAULT_CURRENCY . "' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);

setlocale(LC_MONETARY, 'en_US');

function get_month($mo, $yr) {
    $query = "SELECT * FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$yr-$mo%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $month=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $month++;
    }
    mysql_free_result($result);
    return $month;
}

function get_order_total($mo, $yr) {
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  ORDER by orders_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $i=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    mysql_free_result($result);

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" and class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
                $total=$col_value;
        }
    }
    mysql_free_result($result);
    return $total;
}

# Function get shipping charges
function get_ship_total($mo, $yr) {
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  ORDER by orders_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $i=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    mysql_free_result($result);

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" and class = \"ot_shipping\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
                $total=$col_value;
        }
    }
    mysql_free_result($result);
    return $total;
}

# Function count repeat customers
function get_repeats($mo, $yr) {
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  ORDER by orders_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $i=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    mysql_free_result($result);

    $query = "SELECT COUNT(orders_id) as order_count, customers_id FROM " . TABLE_ORDERS . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" GROUP BY customers_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $repeats = 0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['order_count']>1)
		{
			$total++;
		}
    }
    mysql_free_result($result);
    return $total;
}

function get_status($type) {
    $query = "SELECT orders_status FROM " . TABLE_ORDERS . " WHERE orders_status = \"$type\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $orders_this_status=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           $orders_this_status++;
  	}
    }
    mysql_free_result($result);
    return $orders_this_status;
}

   
# Get total value of inventory 
	$query = "SELECT products_quantity, products_price FROM " . TABLE_PRODUCTS . " WHERE products_quantity > '0'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	$inventory_total=0;    
	while ($col = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$inventory_product = ($col['products_price'] * $col['products_quantity']);
		$inventory_total = $inventory_product + $inventory_total;      		
	}		
		$inventory_total = number_format($inventory_total,2,'.',',');   
	mysql_free_result($result);
	
# Get total items in inventory		
	$query = "SELECT sum(products_quantity) FROM " . TABLE_PRODUCTS . " WHERE products_quantity > '0'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	    
	while ($col = mysql_fetch_array($result, MYSQL_ASSOC)) {		
		foreach ($col as $col_value) {
		$items_total=$col_value;
		}
	}	
	mysql_free_result($result);
	
# Get total number new customers for the month
	$mo = date('m');
    $year = date('Y');	
    $query = "SELECT customers_info_date_account_created FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_date_account_created like \"$year-$mo%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $mnewcust=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$mnewcust++;
    }
    mysql_free_result($result);	

# Get total dollars in orders
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $grand_total=$col_value;
        }
    }
    mysql_free_result($result);

# Get total shipping charges
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_shipping\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $shipping=$col_value;
        }
    }
    mysql_free_result($result);

# Get total number of customers
    $query = "SELECT * FROM " . TABLE_CUSTOMERS . "";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $customer_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$customer_count++;
    }
    mysql_free_result($result);

# Get total number new customers
    $like = date('Y-m-d');
    $query = "SELECT customers_info_date_account_created FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_date_account_created like \"$like%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $newcust=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$newcust++;
    }
    mysql_free_result($result);

# Whos online
    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . "";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $whos_online=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$whos_online++;
    }
    mysql_free_result($result);

# Whos online again
    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . " WHERE customer_id != \"0\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $who_again=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$who_again++;
    }
    mysql_free_result($result);

# How many orders today total
    $date = date('Y-m-d'); #2003-09-07%
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$date%\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $today_order_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$today_order_count++;
    }
    mysql_free_result($result);

# How many orders yesterday
    $mo = date('m');
    $today = date('d');
    $year = date('Y');
    $last_month = $mo-1;
    if ( $last_month == 0) $last_month = 12; //if jan, then last month is dec (12th mo, not 0th month)
    $yesterday = date('d') - 1;
    if ($yesterday == "0") //today is the first day of the month, now "Thirty days hath November . . ." for the prev month
     { $first_day_of_month=1;
       if ( ($last_month == 1) OR ($last_month == 3) OR ($last_month == 5) OR ($last_month == 7) OR ($last_month == 8) OR ($last_month == 10) OR ($last_month == 12) )
          $yesterday = "31";
        elseif  ( ($last_month == 4) OR ($last_month == 6) OR ($last_month == 9) OR ($last_month == 11) )
          $yesterday = "30";

//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
        else {
              if ( ($year % 4) != 0) $yesterday = "28";
               elseif ( ($year % 400) == 0) $yesterday = "29";
               elseif ( ($year % 100) == 0) $yesterday = "28";
               else $yesterday = "29";
              }
     }

// set $yesterday_month so that we can properly run stats for yesterday, not the first day of last month
    if ($first_day_of_month == 1)
       $yesterday_month = $last_month;
    else $yesterday_month = $mo;

// set $yesterday_year so that we can properly run stats for yesterday, not the first day of last year or this month last year
    if ( ($yesterday_month == 12) && ($first_day_of_month == 1) )
      $yesterday_year = $year - 1;
    else
      $yesterday_year = $year;

// next line to normalize $yesterday format to 2 digits
    if ($yesterday <10) {$yesterday = "0$yesterday";}
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\" order by orders_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $yesterday_order_count=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

// Get first orders_id yesterday
    $yesterday_last_order_id = $line['orders_id'];
    $yesterday_order_count++;
    }
    mysql_free_result($result);

// Get last orders_id yesterday
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\" order by orders_id desc";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    // Get last orders_id yesterday
    $twodaysago_last_order_id = $line['orders_id'];
    }
    mysql_free_result($result);

# Get the last order_id
    $query = "SELECT orders_id FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\" ORDER BY orders_id ASC";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
		$latest_order_id=$col_value;
	}
    }
    mysql_free_result($result);

# Grab the sum of all orders greater than $yesterday_last_order_id
# In other words, how much have we done so far in sales today?
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id > \"$yesterday_last_order_id\" and class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_today=$col_value;
        }
    }
    mysql_free_result($result);

# Grab the sum of all orders greater than $twodaysago_last_order_id and less than yesterday_last_order_id
# In other words, how much did we do in sales yesterday?
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$twodaysago_last_order_id\" and orders_id <= \"$yesterday_last_order_id\" and class = \"ot_total\"";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_yesterday=$col_value;
        }
    }
    mysql_free_result($result);

# repeat customers
    if (isset($HTTP_GET_VARS['year']) && $HTTP_GET_VARS['year'] != '') $yearRepeat=$HTTP_GET_VARS['year'];
      else $yearRepeat = date('Y'); #current year

// create array of all customers who have repeat ordered
	$repeat_custs=array();
	$query = "SELECT COUNT(orders_id) as order_count, customers_id FROM orders WHERE date_purchased BETWEEN '" . $yearRepeat. "-01-01 00:00:00' AND '" . $yearRepeat. "-12-31 23:59:59' GROUP BY customers_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['order_count']>1)
		{
			$repeat_custs[]=$line['customers_id'];
		}
    }
    mysql_free_result($result);

# total repeat customers
	$repeats=count($repeat_custs);

# How many repeat orders today total
    $date = date('Y-m-d');
	$repeat_custs_string=implode(",", $repeat_custs);
    $query = "SELECT COUNT(orders_id) AS repeat_count FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$date%\" AND customers_id IN (\"".$repeat_custs_string."\")";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $repeat_orders=0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$repeat_orders=$line['repeat_count'];
    }
    mysql_free_result($result);

// if a profit rate has been entered as part of the URL, use that profit rate, else 20%
    if (isset($HTTP_GET_VARS['profit_rate']) && $HTTP_GET_VARS['profit_rate'] != '') {
	    $profit_rate=$HTTP_GET_VARS['profit_rate'];
    }
    else {
	    $profit_rate="20";
    }
    if ($profit_rate=="") {
	    $profit_rate="20";
    }
    $profit_rate_display=$profit_rate;

// divide profit_rate by 100 to get correct multiplier value
    $profit_rate = $profit_rate / 100;

# How many per month
// if a year has been entered as part of the URL, use that year instead
//  commented out and replaced by following line as per forum suggestion
//  if (isset($HTTP_GET_VARS['year'])) $year=$HTTP_GET_VARS['year'];

    if (isset($HTTP_GET_VARS['year']) && $HTTP_GET_VARS['year'] != '') $year=$HTTP_GET_VARS['year'];
	else $year = date('Y'); #current year
    $month = date('M'); #current month
    $dec = get_month("12", $year);
    $nov = get_month("11", $year);
    $oct = get_month("10", $year);
    $sep = get_month("09", $year);
    $aug = get_month("08", $year);
    $jul = get_month("07", $year);
    $jun = get_month("06", $year);
    $may = get_month("05", $year);
    $apr = get_month("04", $year);
    $mar = get_month("03", $year);
    $feb = get_month("02", $year);
    $jan = get_month("01", $year);
    $current_month = get_month($mo, $year);

# Only Process Month Info if Month has info to process
# Always tally totals, even if zero
# while ($i < 13)
# (
#   $month_avg = $month_total / $current_month;
#   $current_month_total = get_order_total($i, $year);
#   $order = $order + $current_month_total;
#   )
#   $i++;
$order = '';

$jan_total = get_order_total("01", $year);
if ($jan != 0)   $jan_avg = $jan_total / $jan;
$order = $order + $jan_total;
$jan_ship_total = get_ship_total("01", $year);
$year_ship_total += $jan_ship_total;
$jan_repeat_total = get_repeats("01", $year);

$feb_total = get_order_total("02", $year);
if ($feb != 0)  $feb_avg = $feb_total / $feb;
$order = $order + $feb_total;
$feb_ship_total = get_ship_total("02", $year);
$year_ship_total += $feb_ship_total;
$feb_repeat_total = get_repeats("02", $year);

$mar_total = get_order_total("03", $year);
if ($mar != 0)   $mar_avg = $mar_total / $mar;
$order = $order + $mar_total;
$mar_ship_total = get_ship_total("03", $year);
$year_ship_total += $mar_ship_total;
$mar_repeat_total = get_repeats("03", $year);

$apr_total = get_order_total("04", $year);
if ($apr != 0)   $apr_avg = $apr_total / $apr;
$order = $order + $apr_total;
$apr_ship_total = get_ship_total("04", $year);
$year_ship_total += $apr_ship_total;
$apr_repeat_total = get_repeats("04", $year);

$may_total = get_order_total("05", $year);
if ($may != 0)   $may_avg = $may_total / $may;
$order = $order + $may_total;
$may_ship_total = get_ship_total("05", $year);
$year_ship_total += $may_ship_total;
$may_repeat_total = get_repeats("05", $year);

$jun_total = get_order_total("06", $year);
if ($jun != 0)   $jun_avg = $jun_total / $jun;
$order = $order + $jun_total;
$jun_ship_total = get_ship_total("06", $year);
$year_ship_total += $jun_ship_total;
$jun_repeat_total = get_repeats("06", $year);

$jul_total = get_order_total("07", $year);
if ($jul != 0)   $jul_avg = $jul_total / $jul;
$order = $order + $jul_total;
$jul_ship_total = get_ship_total("07", $year);
$year_ship_total += $jul_ship_total;
$jul_repeat_total = get_repeats("07", $year);

$aug_total = get_order_total("08", $year);
if ($aug != 0)   $aug_avg = $aug_total / $aug;
$order = $order + $aug_total;
$aug_ship_total = get_ship_total("08", $year);
$year_ship_total += $aug_ship_total;
$aug_repeat_total = get_repeats("08", $year);

$sep_total = get_order_total("09", $year);
if ($sep != 0)   $sep_avg = $sep_total / $sep;
$order = $order + $sep_total;
$sep_ship_total = get_ship_total("09", $year);
$year_ship_total += $sep_ship_total;
$sep_repeat_total = get_repeats("09", $year);

$oct_total = get_order_total("10", $year);
if ($oct != 0)   $oct_avg = $oct_total / $oct;
$order = $order + $oct_total;
$oct_ship_total = get_ship_total("10", $year);
$year_ship_total += $oct_ship_total;
$oct_repeat_total = get_repeats("10", $year);

$nov_total = get_order_total("11", $year);
if ($nov != 0)   $nov_avg = $nov_total / $nov;
$order = $order + $nov_total;
$nov_ship_total = get_ship_total("11", $year);
$year_ship_total += $nov_ship_total;
$nov_repeat_total = get_repeats("11", $year);

$dec_total = get_order_total("12", $year);
if ($dec != 0)   $dec_avg = $dec_total / $dec;
$order = $order + $dec_total;
$dec_ship_total = get_ship_total("12", $year);
$year_ship_total += $dec_ship_total;
$dec_repeat_total = get_repeats("12", $year);

$current_month_total = get_order_total($mo, $year);
if ($current_month != 0)   $current_month_avg = $current_month_total / $current_month;

# Daily Averages
if ($today_order_count !=0 ) 	$today_avg = $orders_today / $today_order_count;
  else $today_avg = 0;
if ($yesterday_order_count != 0) $yesterday_avg = $orders_yesterday / $yesterday_order_count;
  else ($yesterday_avg = 0);

$daily = $current_month / $today;
$daily_total = $current_month_total / $today;

if ($daily) $daily_avg = $daily_total / $daily;
  else ($daily_avg = 0);

# Calculate days in this month for accurate sales projection
if ( ($mo == 1) OR ($mo == 3) OR ($mo == 5) OR ($mo == 7) OR ($mo == 8) OR ($mo == 10) OR ($mo == 12) )
      $days_this_month = "31";
  elseif ( ($mo == 4) OR ($mo == 6) OR ($mo == 9) OR ($mo == 11) )
           $days_this_month = "30";

//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
    else {
          if ( ($year % 4) != 0) $days_this_month = "28";
          elseif ( ($year % 400) == 0) $days_this_month = "29";
          elseif ( ($year % 100) == 0) $days_this_month = "28";
              else $days_this_month = "29";
         }

# Projected Profits this month
$projected = $daily * $days_this_month;
$projected_total = $daily_total * $days_this_month;
$gross_profit = $grand_total * $profit_rate;
$year_profit = $order * $profit_rate;

If ($newcust != 0) $close_ratio = $today_order_count / $newcust;
  else $close_ratio = 0;

# format test into current
	$total_orders = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
if ($total_orders != 0)   $total = $order / $total_orders;
	$total = number_format($total,2,'.',',');
	$order = number_format($order,2,'.',',');
	$grand_total = number_format($grand_total,2,'.',',');
	$gross_profit = number_format($gross_profit,2,'.',',');
	$year_profit = number_format($year_profit,2,'.',',');
	$projected = number_format($projected,0,'.',',');
	$projected_total = number_format($projected_total,2,'.',',');
	$close_ratio = number_format($close_ratio,2,'.',',');
	$yesterday_avg = number_format($yesterday_avg,2,'.',',');

	$dec_total = number_format($dec_total,2,'.',',');
	$nov_total = number_format($nov_total,2,'.',',');
	$oct_total = number_format($oct_total,2,'.',',');
	$sep_total = number_format($sep_total,2,'.',',');
	$aug_total = number_format($aug_total,2,'.',',');
	$jul_total = number_format($jul_total,2,'.',',');
	$jun_total = number_format($jun_total,2,'.',',');
	$may_total = number_format($may_total,2,'.',',');
	$apr_total = number_format($apr_total,2,'.',',');
	$mar_total = number_format($mar_total,2,'.',',');
	$feb_total = number_format($feb_total,2,'.',',');
	$jan_total = number_format($jan_total,2,'.',',');

	$dec_ship_total = number_format($dec_ship_total,2,'.',',');
	$nov_ship_total = number_format($nov_ship_total,2,'.',',');
	$oct_ship_total = number_format($oct_ship_total,2,'.',',');
	$sep_ship_total = number_format($sep_ship_total,2,'.',',');
	$aug_ship_total = number_format($aug_ship_total,2,'.',',');
	$jul_ship_total = number_format($jul_ship_total,2,'.',',');
	$jun_ship_total = number_format($jun_ship_total,2,'.',',');
	$may_ship_total = number_format($may_ship_total,2,'.',',');
	$apr_ship_total = number_format($apr_ship_total,2,'.',',');
	$mar_ship_total = number_format($mar_ship_total,2,'.',',');
	$feb_ship_total = number_format($feb_ship_total,2,'.',',');
	$jan_ship_total = number_format($jan_ship_total,2,'.',',');	
	$year_ship_total = number_format($year_ship_total,2,'.',',');

	$orders_today = number_format($orders_today,2,'.',',');
	$orders_yesterday = number_format($orders_yesterday,2,'.',',');

	$dec_avg = number_format($dec_avg,2,'.',',');
	$nov_avg = number_format($nov_avg,2,'.',',');
	$oct_avg = number_format($oct_avg,2,'.',',');
	$sep_avg = number_format($sep_avg,2,'.',',');
	$aug_avg = number_format($aug_avg,2,'.',',');
	$jul_avg = number_format($jul_avg,2,'.',',');
	$jun_avg = number_format($jun_avg,2,'.',',');
	$may_avg = number_format($may_avg,2,'.',',');
	$apr_avg = number_format($apr_avg,2,'.',',');
	$mar_avg = number_format($mar_avg,2,'.',',');
	$feb_avg = number_format($feb_avg,2,'.',',');
	$jan_avg = number_format($jan_avg,2,'.',',');
	$today_avg = number_format($today_avg,2,'.',',');

if ($total_orders !=0) $shipping_avg = $shipping / $total_orders;
	else $shipping_avg = 0;

	$shipping_avg = number_format($shipping_avg,2,'.',',');
	$shipping = number_format($shipping,2,'.',',');

	$daily = number_format($daily,2,'.',',');
	$daily_total = number_format($daily_total,2,'.',',');
	$daily_avg = number_format($daily_avg,2,'.',',');
	
# Order Tracking by Country	
 // Note: all orders assumed to be in the default currency
  $o_min_status =1; //schoose minimum order status

// get default currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '" . DEFAULT_CURRENCY . "' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);
# Order Tracking by Country  
  	
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body>
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>                       
							<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
						</tr>
					</table>
				</td>
			</tr>
      		<tr>
				<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" valign="top">
<!-- #### BEGIN ORDER TRACKING MODULE #### -->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="dataTableContent" align="center" valign="middle"><br>
			<?php
			echo tep_draw_form('search', FILENAME_STATS_ORDERS_TRACKING, '', 'get');
			echo HEADING_SELECT_YEAR . ' ' . tep_draw_input_field('year', '', 'size="4"');
			echo tep_draw_separator('pixel_trans.gif', '10', '1') . HEADING_SELECT_MONTH . ' ' . tep_draw_input_field('month', '', 'size="2"');
			echo tep_draw_separator('pixel_trans.gif', '10', '1') . HEADING_SELECT_POSTPREFIX . ' ' . tep_draw_input_field('postcode_prefix', '', 'size="3"');		
			echo tep_draw_separator('pixel_trans.gif', '10', '1') . HEADING_SELECT_PROFIT_RATE . ' ' . tep_draw_input_field('profit_rate', '', 'size="2"');
			echo tep_draw_separator('pixel_trans.gif', '5', '1') . '%' . tep_draw_separator('pixel_trans.gif', '10', '1') . '<input type="submit" value="' . HEADING_TITLE_RECALCULATE . '">';
			echo '</form></td>';
			?>
		</td>
	</tr>
	<tr>
		<td class="dataTableContent" align="center" valign="middle">
			<?php echo tep_draw_separator('pixel_trans.gif', '5', '10'); ?>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" bgcolor="#f1f1f1">
				<tr>
					<td class="dataTableContent" align="center" bgcolor="#ccccff"><b><?php echo HEADING_TITLE_SALES_SUMMARY; ?></b></td>
					<td class="dataTableContent" align="center" bgcolor="#ccccff"><b><?php echo HEADING_TITLE_COUNTRY; ?></b></td>
					<td class="dataTableContent" align="center" bgcolor="#ccccff"><b><?php echo HEADING_TITLE_ZONE; ?></b></td>
					<td class="dataTableContent" align="center" bgcolor="#ccccff"><b><?php echo HEADING_TITLE_POSTCODE; ?></b></td>
				</tr>
				<tr>
					<td align="center" valign="top">
<!-- Begin Order_Tracking //-->
						<table border="1" cellspacing="1" cellpadding="5">
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_DESCRIPTION; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ORDERS; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_INCOME; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_AVERAGE; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_SHIPPING; ?>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><a href="orders.php?selected_box=customers&status=1"><b><?php echo BOX_TODAY; ?> <?php echo "$mo-$today"; ?></b></a></td>
								<td class="dataTableContent"><a href="orders.php?selected_box=customers&status=1"><b><?php echo "$today_order_count ($repeat_orders)"; ?> *<b></a></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $orders_today ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $today_avg ?></td>
								<td class="dataTableContent" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><?php echo BOX_YESTERDAY; ?> <?php echo "$yesterday_month-$yesterday"; ?></td>
								<td class="dataTableContent"><?php echo $yesterday_order_count ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $orders_yesterday ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $yesterday_avg ?></td>
								<td class="dataTableContent" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><?php echo BOX_DAILY_AVERAGE; ?> <?php echo $month ?></td>
								<td class="dataTableContent"><?php echo $daily ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $daily_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $daily_avg ?></td>
								<td class="dataTableContent" align="right">&nbsp;</td>
							</tr>
								<tr class="dataTableRow">
								<td class="dataTableContent"><?php echo BOX_PROJECTION; ?> <?php echo $month ?></td>
								<td class="dataTableContent"><?php echo $projected ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $projected_total ?></td>
								<td class="dataTableContent" align="right">&nbsp;</td>
								<td class="dataTableContent" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableContent" class="dataTableHeadingContent" colspan="5" align="center"><br><b><?php echo BOX_TITLE_MONTH_TOTAL; ?></b></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_1_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $jan . ' (' . $jan_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jan_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jan_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jan_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_2_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $feb . ' (' . $feb_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $feb_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $feb_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $feb_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_3_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $mar . ' (' . $mar_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $mar_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $mar_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $mar_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_4_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $apr . ' (' . $apr_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $apr_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $apr_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $apr_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_5_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $may . ' (' . $may_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $may_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $may_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $may_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_6_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $jun . ' (' . $jun_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jun_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jun_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jun_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_7_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $jul . ' (' . $jul_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jul_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jul_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $jul_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_8_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $aug . ' (' . $aug_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $aug_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $aug_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $aug_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_9_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $sep . ' (' . $sep_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $sep_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $sep_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $sep_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_10_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $oct . ' (' . $oct_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $oct_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $oct_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $oct_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_11_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $nov . ' (' . $nov_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $nov_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $nov_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $nov_ship_total ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent" ><?php echo MONTH_12_TITLE; ?> <?php echo $year ?></td>
								<td class="dataTableContent" ><?php echo $dec . ' (' . $dec_repeat_total . ')'?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $dec_total ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $dec_avg ?></td>
								<td class="dataTableContent" align="right"><?php echo $store_currency_symbol . $dec_ship_total ?></td>
							</tr>
							<tr class="dataTableRow" >
								<td class="dataTableContent" nowrap><b><?php echo BOX_TOTAL; ?> <?php echo $year ?></b></td>
								<td class="dataTableContent"><b><?php echo "$total_orders / $repeats"; ?> *</b></td>
								<td class="dataTableContent" align="right"><b><?php echo $store_currency_symbol . $order ?></b></td>
								<td class="dataTableContent" align="right"><b><?php echo $store_currency_symbol . $total ?></b></td>
								<td class="dataTableContent" align="right"><b><?php echo $store_currency_symbol . $year_ship_total ?></b></td>
							</tr>
							<tr class="dataTableRow" >
								<td class="dataTableContent"><b><?php echo $year ?> <?php echo BOX_PROFIT; ?> <?php echo $profit_rate_display ?>%</b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $year_profit ?></b></td>
								<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableContent" class="dataTableHeadingContent" colspan="5" align="center"><br><b><?php echo BOX_TITLE_CUSTOMER_INFO; ?></b></td>
							</tr>
							<tr class="dataTableRow" >
								<td class="dataTableContent"><a href="customers.php"><b><?php echo BOX_CUSTOMER_TOTAL; ?></b></a></td>
								<td class="dataTableContent"><?php echo $customer_count ?></td>
								<td class="dataTableContent"><a href="whos_online.php"><b><?php echo BOX_CUSTOMERS_ONLINE; ?></b></a></td>
								<td class="dataTableContent"><?php echo "$whos_online / $who_again"; ?> *</td>
								<td class="dataTableContent">&nbsp;</td>
							</tr>
							<tr class="dataTableRow" >
								<td class="dataTableContent"><?php echo BOX_NEW_CUSTOMERS_TODAY; ?></td>
								<td class="dataTableContent"><?php echo $newcust ?></td>
								<td class="dataTableContent"><?php echo BOX_CLOSE_RATIO; ?></td>
								<td class="dataTableContent"><?php echo $close_ratio ?>%</td>
								<td class="dataTableContent">&nbsp;</td>
							</tr>
							<tr class="dataTableRow" >
								<td class="dataTableContent"><?php echo BOX_NEW_CUSTOMERS_THIS_MONTH; ?></td>
								<td class="dataTableContent"><?php echo $mnewcust ?></td>
								<td class="dataTableContent">&nbsp;</td>
								<td class="dataTableContent">&nbsp;</td>
								<td class="dataTableContent">&nbsp;</td>
							</tr>
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableContent" class="dataTableHeadingContent" colspan="5" align="center"><br><b><?php echo BOX_TITLE_ORDER_STATUS; ?></b></td>
							</tr>
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableHeadingContent" align="center"><?php echo BOX_TITLE_STATUS; ?></td>
								<td class="dataTableHeadingContent" align="center"><?php echo BOX_TITLE_ORDERS; ?></td>
								<td class="dataTableHeadingContent" align="center"><?php echo BOX_TITLE_TOTAL; ?></td>
								<td class="dataTableHeadingContent" colspan="2" align="center">&nbsp;</td>
							</tr>
<?php
	$orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
	while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	$orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
	$orders_pending = tep_db_fetch_array($orders_pending_query);    
	
	$current_status = $orders_status['orders_status_id'];
	
	$orders_total_this_status_query_raw = "select sum(ot.value) as total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_total' AND o.orders_status = $current_status";
	$orders_total_this_status_query = tep_db_query($orders_total_this_status_query_raw);
	$orders_total_this_status = tep_db_fetch_array($orders_total_this_status_query);  
	$orders_contents .= '<tr class="dataTableRow">
							<td class="dataTableContent"><a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=orders&status=' . $orders_status['orders_status_id']) . '"><b>' . $orders_status['orders_status_name'] . '</b></a></td>
							<td class="dataTableContent">' . $orders_pending['count'] . '</td>
							<td class="dataTableContent" align="right">' . $store_currency_symbol . number_format($orders_total_this_status['total'],2) . '</td>
							<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
						</tr>';    
	}
echo $orders_contents;
?>																		
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableContent" class="dataTableHeadingContent" colspan="5" align="center"><br><b><?php echo BOX_TITLE_GRAND_TOTAL; ?></b></td>
							</tr>
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<td class="dataTableHeadingContent" align="center"><?php echo BOX_TITLE_DESCRIPTION; ?></td>
								<td class="dataTableHeadingContent" colspan="2" align="center"><?php echo BOX_TITLE_TOTAL; ?></td>
								<td class="dataTableHeadingContent" colspan="2" align="center"><?php echo BOX_TITLE_AVERAGE; ?></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><b><?php echo BOX_GRAND_TOTAL; ?></b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $grand_total ?></b></td>
								<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><b><?php echo BOX_GROSS_PROFIT; ?> <?php echo $profit_rate_display ?>%</b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $gross_profit ?></b></td>
								<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><b><?php echo BOX_SHIPPING_TOTAL; ?></b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $shipping ?></b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $shipping_avg ?></b></td>
							</tr>
							<tr class="dataTableRow">
								<td class="dataTableContent"><b><?php echo BOX_INVENTORY_TOTAL; ?> <?php echo $items_total ?> <?php echo BOX_ITEMS; ?></b></td>
								<td class="dataTableContent" colspan="2" align="right"><b><?php echo $store_currency_symbol . $inventory_total ?></b></td>
								<td class="dataTableContent" colspan="2" align="right">&nbsp;</td>
							</tr>
						</table>
<!-- End Order_Tracking //-->
					</td>
					<td align="center" valign="top">
<!-- Begin Order_Tracking_Country //-->
						<table border="1" cellspacing="1" cellpadding="5">
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_COUNTRY; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ORDER_COUNT; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ORDER_VALUE; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_PCT_VALUE; ?>
							</tr>
<?php
// if no year has been selected, use current year
  if (isset($HTTP_GET_VARS['year'])) {
	  $year=$HTTP_GET_VARS['year'];
  }
  else {
	  $year = date('Y'); #current year
  }

// if selected a month but not year, assume current year    
if ($HTTP_GET_VARS['year'] == '') {
	  $year = date('Y'); #current year
  }      

// if a month has been selected, set the date range for just that month
  if (isset($HTTP_GET_VARS['month'])) {
	  $startmonth=$HTTP_GET_VARS['month'];
	  $endmonth=$startmonth;
  }

// if no month has been selected, we want entire year of data
  if ($HTTP_GET_VARS['month'] == '') {
	  $startmonth=01;
	  $endmonth=12;
  } 
 $total_query = tep_db_query("select sum(value) as amount, count(value) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status");
  while  ($thetotal = tep_db_fetch_array($total_query)) {
    if ( $thetotal['count'] != 0 ) //if there are orders for this period find the totals for calculating the percentages later
    {
     $total_orders = $thetotal['count'];
   	 $total_amount = $thetotal['amount'];
	   }
  }
  $pcttot=0;
  $location_query = tep_db_query("select customers_country, currency, sum(value) as amount, count(*) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status group by customers_country order by customers_country");
  while  ($location = tep_db_fetch_array($location_query)) {
    if ( $location['count'] != 0 ) //if there are orders for this country, print the country,count, amount and percentage of total
    {
   	   $pct = $location['amount'] * 100 / $total_amount ;
   	   $amount = number_format($location['amount'],2,'.',',');
       $pcttot += $pct;
    	 $pct = number_format($pct,1,'.',',');
       $location_contents .= '<tr class="dataTableRow">
       							<td class="dataTableContent">' . $location['customers_country'] . '</td>
       							<td class="dataTableContent">' . $location['count']  . '</td>
       							<td class="dataTableContent"  align="right">' . $store_currency_symbol. $amount . '</td>
       							<td class="dataTableContent"  align="right">' . $pct . '%</td>
       						  </tr>';
   	}
  }
  echo $location_contents;
  $total_amount = number_format($total_amount,2,'.',',');
  $pcttot = number_format($pcttot,2,'.',',');
  echo '<tr class="dataTableRow">
			<td class="dataTableContent"><b>' . BOX_TOTAL . '</b></td>
			<td class="dataTableContent">' . $total_orders. '</td>
			<td class="dataTableContent"  align="right">' . $store_currency_symbol . $total_amount. '</td>
			<td class="dataTableContent" align="right">' .$pcttot. '%</td>
		</tr>';
?>
						</table>
<!-- End Order_Tracking_Country //-->
					</td>
					<td align="center" valign="top">
<!-- Begin Order_Tracking_Zone //-->
						<table border="1" cellspacing="1" cellpadding="5">
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ZONE; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ORDER_COUNT; ?>
							</tr>
<?php
// if no year has been selected, use current year
  if (isset($HTTP_GET_VARS['year'])) {
	  $year=$HTTP_GET_VARS['year'];
  }
  else {
	  $year = date('Y'); #current year
  }

// if selected a month but not year, assume current year    
if ($HTTP_GET_VARS['year'] == '') {
	  $year = date('Y'); #current year
  }      

// if a month has been selected, set the date range for just that month
  if (isset($HTTP_GET_VARS['month'])) {
	  $startmonth=$HTTP_GET_VARS['month'];
	  $endmonth=$startmonth;
  }

// if no month has been selected, we want entire year of data
  if ($HTTP_GET_VARS['month'] == '') {
	  $startmonth=01;
	  $endmonth=12;
  } 
      
  $location_query = tep_db_query("select zone_name, zone_country_id from " . TABLE_ZONES . " order by zone_country_id DESC");    
  while ($customers_location = tep_db_fetch_array($location_query)) {
    $location_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where customers_state = '". addslashes($customers_location['zone_name']) ." ' and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' ");
    $location_pending = tep_db_fetch_array($location_pending_query);
    if ( $location_pending['count'] != 0 ) //if there are orders in this zone, print the zone and the count 
    {
	        $location_info .= '<tr class="dataTableRow">
	        <td class="dataTableContent">' . $customers_location['zone_name'] . '</td>
	        <td class="dataTableContent">' . $location_pending['count'] . '</td>
	        </tr>';
	}
  }
echo $location_info;
?>
						</table>
<!-- End Order_Tracking_Zone //-->
					</td>
					<td align="center" valign="top">
<!-- Begin Order_Tracking_PostCode //-->
						<table border="1" cellspacing="1" cellpadding="5">
							<tr class="dataTableHeadingRow" bgcolor="silver">
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_POST; ?>
								<th class="dataTableHeadingContent"><?php echo BOX_TITLE_ORDER_COUNT; ?>
							</tr>
<?php
// if no year has been selected, use current year
  if (isset($HTTP_GET_VARS['year'])) {
	  $year=$HTTP_GET_VARS['year'];
  }
  else {
	  $year = date('Y'); #current year
  }

// if selected a month but not year, assume current year    
if ($HTTP_GET_VARS['year'] == '') {
	  $year = date('Y'); #current year
  }      

// if a month has been selected, set the date range for just that month
  if (isset($HTTP_GET_VARS['month'])) {
	  $startmonth=$HTTP_GET_VARS['month'];
	  $endmonth=$startmonth;
  }

// if no month has been selected, we want entire year of data
  if ($HTTP_GET_VARS['month'] == '') {
	  $startmonth=01;
	  $endmonth=12;
  } 
  
  if ( (isset($HTTP_GET_VARS['postcode_prefix'])) && ($HTTP_GET_VARS['postcode_prefix'] != '') ) {
	  $prefix = '"' . $HTTP_GET_VARS['postcode_prefix'] . '"';
	  $postcode_query = tep_db_query("SELECT count(*) AS count, customers_postcode FROM " . TABLE_ORDERS . " WHERE substring(customers_postcode,1,3) = $prefix and customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' GROUP BY customers_postcode ORDER BY customers_postcode DESC LIMIT 50");	  
	  while ($customers_location = tep_db_fetch_array($postcode_query)) {
	        $location_contents .= '<tr class="dataTableRow"><td class="dataTableContent">' . $customers_location['customers_postcode'] . '</font>';
	        if ( is_numeric($customers_location['customers_postcode']) ) {
		        $location_postcode .= '&nbsp;&nbsp;<a href="' . ZIP_URL . $customers_location['customers_postcode'] . '" target="_blank">' . POST_CODE_LOOKUP . '</a>';
	        }
	        $location_postcode .= '</td><td class="dataTableContent">' . $customers_location['count'] . '</td></tr>';
        }
  } else {  
  $location_query = tep_db_query("SELECT count(*) as count, substring(customers_postcode,1,3) as customers_postcode from " . TABLE_ORDERS . " WHERE customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' group by customers_postcode ORDER BY customers_postcode ASC");     
  while ($customers_location = tep_db_fetch_array($location_query)) {
	        $location_postcode .= '<tr class="dataTableRow">
	        <td class="dataTableContent"><a href="' . FILENAME_STATS_ORDERS_TRACKING . '?postcode_prefix=' . $customers_location['customers_postcode'] . '&year=' . $year . '&month='. $month . '"><b>' . $customers_location['customers_postcode'] . 'xxx</b></a></td>
	        <td class="dataTableContent">' . $customers_location['count'] . '</td>
	       	</tr>';	        
        }
    }          
echo $location_postcode;
?>

						</table>
<!-- End Order_Tracking_PostCode //-->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<br />
			<font size="-1">
			<?php echo NOTE_1; ?><br />
			<?php echo NOTE_2; ?><br />
			<?php echo NOTE_3; ?><br />
			</font>
			<br />
		</td>
	</tr>
</table>																	
<!-- #### END ORDER TRACKING MODULE #### -->																	
				</td>
			</tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
</body>
</html>
