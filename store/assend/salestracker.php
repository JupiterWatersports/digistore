<?php
/*
  $Id: categories.php,v 1.26 2003/07/11 14:40:28 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
#############################################
# File: salestracker.php    
# Description: Shows sales today or by  
# sales by date range. Used with 
# supertracker it also shows referer and   
# ad campaign info.     
# author: Robert Fisher aka babygurgles    
# site: www.fwrmedia.co.uk 
# version: 1.0.2 d          
# date: 12th August 2007                  
# additional files:                      
# languages/english/salestracker.php        
#############################################
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/salestracker.php');
  $SALESTRACKER_error = array();

//User defined parameters
// List of referers (This could get quite long)
// These are used by seeing if they exist in the $_SERVER['HTTP_REFERER'] predefined variable

// Ad campaign
// This is based on the fact that you ad a querystring to your paid for PPC ads
// e.g. www.mysite.com/thisproduct.html?src=googlead&keyw=thisproduct
// You will probably use something different to ?src=xxx&keyw=xxx so change these to your own versions
// The PPC source
$source = 'src='; // Change to your own
// The specific ad
$keyword = 'keyw='; // Change to your own
// Currency symbol
 require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$mycurrency = '$ '; // Change to your own currency symbol

  if (isset($HTTP_GET_VARS['startdate'])) {
    $startdate = $HTTP_GET_VARS['startdate'];
  } else {
    $startdate = date('d-m-Y');
  }

  if (isset($HTTP_GET_VARS['enddate'])) {
    $enddate = $HTTP_GET_VARS['enddate'];
  } else {
    $enddate = date('d-m-Y');
  }

##### IMPORTANT - ONE CHANGE NEEDED IN THE SCRIPT ###############
//Search this file for PPC querystring
// You will need to add/change values here to match your campaign
#################################################################
// END user defined parameters
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
var dateAvailable=new ctlSpiffyCalendarBox("dateAvailable", "daterange", "startdate","btnDate1","<?php echo $startdate; ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable1=new ctlSpiffyCalendarBox("dateAvailable1", "daterange", "enddate","btnDate2","<?php echo $enddate; ?>",scBTNMODE_CUSTOMBLUE);

</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php 
	if ($printable != 'on') {
		require(DIR_WS_INCLUDES . 'header.php');
	}
?>
<!-- header_eof //-->
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
  <?php if ($printable != 'on') {;?>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table>
	<?php }; ?>
	</td>
<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo SALESTRACKER_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<tr>

<?php
// Code switches now to PHP parsing the html .. why? .. because I prefer it :)
if (!isset($_GET['raction'])) $raction = 'none';
else $raction = $_GET['raction'];

                    echo '
                    
                    <form name="daterange" action="' . HTTP_SERVER . DIR_WS_ADMIN . 'salestracker.php?selected_box=reports&raction=basic" method="post">
                    <td>
                    <table><tr class="dataTableContent">
						<td> ' . SALESTRACKER_FROM . ' </td><td><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="dd-MM-yyyy";</script></td>
                    
						<td> ' . SALESTRACKER_TO . ' </td><td><script language="javascript">dateAvailable1.writeControl(); dateAvailable1.dateFormat="dd-MM-yyyy";</script></td>
                    
						<td> ' . ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print). ' </td>
					
						<td> ' . tep_image_submit('button_send.gif', IMAGE_SEND_EMAIL) . ' </td>
                    </tr></table>
                    </td></form>
                    </tr><tr>';

                     //Get order status names
                     $orders_status_result = tep_db_query("SELECT orders_status_id, orders_status_name FROM orders_status order by language_id = " . (int)$languages_id);
                     while ($status_array = tep_db_fetch_array($orders_status_result)) {
                       $status[$status_array['orders_status_id']] = $status_array['orders_status_name'];
                     }
                     tep_db_free_result($orders_status_result);
                     if (isset($_POST['startdate'])) {
                     $startdate = $_POST['startdate'];
                     list($day,$month,$year) = explode('-', $startdate);
                     $startdate = mktime(0, 0, 0, $month, $day, $year);
                     $startdate = date("Y-m-d", $startdate);
                     } else $startdate = date("Y-m-d");
                     $startdate = tep_db_prepare_input($startdate);
                     if (isset($_POST['enddate'])) {
                     $enddate = $_POST['enddate'];
                     list($day,$month,$year) = explode('-', $enddate);
                     $enddate = $_POST['enddate'];
                     $enddate = mktime(0, 0, 0, $month, $day, $year);
                     $enddate = date("Y-m-d", $enddate);
                     } else $enddate = date("Y-m-d");;
                     $enddate = tep_db_prepare_input($enddate);
                     $orders_result = tep_db_query("
                     SELECT o.orders_id, o.customers_id, o.customers_name, o.payment_method, o.date_purchased, o.orders_status, ot.value
                     FROM orders as o
                     LEFT JOIN orders_total as ot
                     ON o.orders_id = ot.orders_id
                     WHERE date_purchased between '" . tep_db_input($startdate) . "'
                     AND '" . tep_db_input($enddate) . " 23:59:59'
                     AND ot.class = 'ot_total'
                     ORDER BY orders_id DESC");
                     $num_rows = mysql_num_rows($orders_result);
                     $rows = 0;
                     $total_sales = 0;
                     $numorders = 0;
                     while ($orders_array = tep_db_fetch_array($orders_result)) {
                     // Build the list of orders_ids
                     if (!isset($orders_id_list)) {
                       if ($num_rows == 1)
                       $orders_id_list = "'" . $orders_array['orders_id'] . "'";
                       else $orders_id_list = "'" . $orders_array['orders_id'] . "',";
                     }
                     elseif ($rows == ($num_rows-1)) $orders_id_list .= "'" . $orders_array['orders_id'] . "'";
                     else $orders_id_list .= "'" . $orders_array['orders_id'] . "',";
                     $rows++;
                     $orderslist[$orders_array['orders_id']] = array(
                                                                     'customers_id'   => $orders_array['customers_id'],
                                                                     'customers_name' => $orders_array['customers_name'],
                                                                     'payment_method' => $orders_array['payment_method'],
                                                                     'date_purchased' => date("j F, Y", strtotime($orders_array['date_purchased'])),
                                                                     'orders_status'  => $status[$orders_array['orders_status']],
                                                                     'referrer'       => '',
                                                                     'landing_page'   => '',
                                                                     //'cart_total'     => $mycurrency . number_format($orders_array['value'], 2));
								     'cart_total'     => $currencies->display_price($orders_array['value'],0) );
                     $total_sales = ($total_sales + $orders_array['value']);
                     $numorders++;
                     }
                     tep_db_free_result($orders_result);
                     if ($num_rows != 0) {
                     $tracker_result = tep_db_query("SELECT order_id, referrer, landing_page  FROM supertracker where order_id IN ($orders_id_list)  AND completed_purchase = 'true' ORDER BY order_id DESC");
                      while ($tracker_array = tep_db_fetch_array($tracker_result)) {
                      $orderslist[$tracker_array['order_id']]['referrer'] = strtolower(str_replace('http://www.', '', $tracker_array['referrer']));

                      // Find the refering site
                      if ( $orderslist[$tracker_array['order_id']]['referrer'] != '' ) {
                      $referer_strip = array('http://', 'https://', 'www.');
                      $referer = str_replace($referer_strip, '', $orderslist[$tracker_array['order_id']]['referrer']);
                      $orderslist[$tracker_array['order_id']]['referrer'] = str_replace(strstr($referer, '/'), '', $referer);
                      } else $orderslist[$tracker_array['order_id']]['referrer'] = SALESTRACKER_UNKNOWN;

                      // PPC querystring
                      if (eregi($source . 'googlead', $tracker_array['landing_page']))    $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="blue"><b>google</b></font>';
                      elseif (eregi($source . 'shopping', $tracker_array['landing_page']))    $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="red"><b>shopping</b></font>';
                      elseif (eregi($source . 'yahoo', $tracker_array['landing_page']))       $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="purple"><b>yahoo</b></font>';
                      elseif (eregi($source . 'shopzilla', $tracker_array['landing_page']))   $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="orange"><b>shopzilla</b></font>';
                      //Add more here
                      //elseif (eregi($source . 'thisPPC', $tracker_array['landing_page']))   $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="thiscolor"><b>thisPPC</b></font>';
                      else   $orderslist[$tracker_array['order_id']]['landing_page'] = '<font color="green"><b>' . SALESTRACKER_ORGANIC . '</b></font>';
                      // END PPC querystring
                      // Find the specific ads from the querystring
                      $ad_querystring = strtolower($tracker_array['landing_page']);
                      $keyword_corrected =  str_replace('&ovkey', $keyword, $ad_querystring);
                      $keyword_corrected =  strstr($keyword_corrected, $keyword);
                                         if (strstr($keyword_corrected, $keyword)) {
                                         $keyword_corrected = str_replace($keyword, '', $keyword_corrected);
                                         $keyword_corrected = str_replace('=', '', $keyword_corrected); //yahoo adds a 2nd =
                                         $orderslist[$tracker_array['order_id']]['landing_page'] .= ' : ' . str_replace(stristr ( $keyword_corrected, '&' ), '', $keyword_corrected);
                                         }
                      unset($keyword_corrected);
                      }
                      tep_db_free_result($tracker_result);
                      }
                      if (!isset($total_sales)) $total_sales = 0;
		       //' . SALESTRACKER_TOTAL_SALES . '<font color="#000055"><b>' . $mycurrency . number_format($total_sales, 2) . '</b></font>' . SALESTRACKER_FOR_PERIOD . date("j F, Y", strtotime($startdate)) . SALESTRACKER_TO2 . date("j F, Y", strtotime($enddate)) . '<br />
                      echo '
                      <td>
                         <table><tr>
                         <td colspan="8" style="padding: 5px; color: #818181; border: 1px solid #818181;">                        
			 ' . '<font color="black"><b>' .SALESTRACKER_TOTAL_SALES . '<font color="red">' . $currencies->display_price($total_sales,0) . '</font>' . SALESTRACKER_FOR_PERIOD . date("j F, Y", strtotime($startdate)) . SALESTRACKER_TO2 . date("j F, Y", strtotime($enddate)) . '<br />
                         ' . SALESTRACKER_ORDERNUM .'<font color="red">' . $numorders . '</font></b></font><p />
                         </td>
                         </tr><tr class="dataTableHeadingRow">
                         <td class="dataTableHeadingContent" width="10%">' . SALESTRACKER_ORDER . '</td><td class="dataTableHeadingContent" width="15%">' . SALESTRACKER_NAME . '</td><td class="dataTableHeadingContent" width="15%">' . SALESTRACKER_PAYMENT . '</td><td class="dataTableHeadingContent" width="15%">' . SALESTRACKER_PURCHASED . '</td><td class="dataTableHeadingContent" width="5%">' . SALESTRACKER_STATUS . '</td><td class="dataTableHeadingContent" width="10%">' . SALESTRACKER_REFERER . '</td><td class="dataTableHeadingContent" width="20%">' . SALESTRACKER_CAMPAIGN . '</td><td class="dataTableHeadingContent" width="10%">' . SALESTRACKER_TOTAL . '</td>
                         </tr>';
                      if ($num_rows != 0) {
                      foreach($orderslist as $key => $value){
                      // No tracker data was available so set to unknown
                      if ( ($orderslist[$key]['referrer'] == '') ) $orderslist[$key]['referrer'] = SALESTRACKER_NODATA;
                      if ( ($orderslist[$key]['landing_page'] == '') ) $orderslist[$key]['landing_page'] = SALESTRACKER_NODATA;
                      // End no tracker data
                      echo '<tr class="dataTableRow">
                      <td class="dataTableContent"><a href="' . FILENAME_ORDERS . '?oID=' . $key . '&action=edit" title="' . SALESTRACKER_VIEW_ORDER . '">' . $key . '</a></td>
                      <td class="dataTableContent"><a href="' . FILENAME_CUSTOMERS . '?selected_box=customers&cID=' . $orderslist[$key]['customers_id'] . '&action=edit" title="' . SALESTRACKER_VIEW_CUSTOMER . '">' . $orderslist[$key]['customers_name'] . '</a></td>
                      <td class="dataTableContent">' . $orderslist[$key]['payment_method'] . '</td>
                      <td class="dataTableContent">' . $orderslist[$key]['date_purchased'] . '</td>
                      <td class="dataTableContent"><a href="' . FILENAME_ORDERS . '?oID=' . $key . '&action=edit"  title="' . SALESTRACKER_CHANGE_STATUS . '">' . $orderslist[$key]['orders_status'] . '</a></td>
                      <td class="dataTableContent">' . $orderslist[$key]['referrer'] . '</td>
                      <td class="dataTableContent">' . $orderslist[$key]['landing_page'] . '</td>
                      <td class="dataTableContent">' . $orderslist[$key]['cart_total'] . '</td>
                      </tr>';
                     }
                      } else echo '<font color="red">' . SALESTRACKER_NOSALES . '</font>';
                     echo '
                     </table></td>
     </tr>
          </table>
     </td>';
	if ($printable != 'on') {
	echo '<td valign="top" class="columnLeft" width="' . BOX_WIDTH . '">
          <div style="font-weight: bold; padding: 3px;" class="infoBoxHeading">' . SALESTRACKER_INFORMATION . '</div>
          <div class="infoBoxContent">
          <b>' . SALESTRACKER_ORDER2 . '</b><br />
          ' . SALESTRACKER_CLICK_ORDER . '<p />
          <b>' . SALESTRACKER_CUSTOMER_NAME . '</b><br />
          ' . SALESTRACKER_CLICK_CUSTOMER_DETAILS . '<p />
          <b>' . SALESTRACKER_STATUS . '</b><br />
          ' . SALESTRACKER_CLICK_STATUS . '<p />
          <hr width="75%" align="center" /><p />
          ' . SALESTRACKER_SETUP_INFORMATION . '
          </div>
          </td>
         </tr></table>
                   </td>
               </form>
         </tr></table>
     </td>';
	}
	echo '
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->';
if ($printable != 'on') {
	require(DIR_WS_INCLUDES . 'footer.php');
}
echo '
<!-- footer_eof //-->
</body>
</html>';
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>