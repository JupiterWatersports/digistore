<?php
/*
	$Id: low_stock report.php,v 2.MS2.rev0 2006/03/23  hpdl Exp $

	(v 1.1 by Alexandros Polydorou 2003/04/24; v 1.11 by Eric Lowe 2004/03/30; v 1.12 by Rob Woodgate 2004/04/01; v 1.15 by Aaron Hiatt 2004/11/09; v 1.16 by Rob Woodgate 2004/12/17; v 2.0 & v2.01 by Keith W. 2005/08/11; v 2.02 by Keith W. 2006/01/09)

	(v 2.MS2.rev0 by hakre 2006-03-23)

	Digistore v4.0,  Open Source E-Commerce Solutions
	http://www.digistore.co.nz

	Copyright (c) 2002 osCommerce

	Released under the GNU General Public License
*/
	require('includes/application_top.php');

	    if ($action=='setflag') {
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }
	}
/*
* first of all a class is introduced to encapsulate all needed functions. 
* this is made to minimize crashes into the fragile OSC and contribs namespace
*/

	require('stats_low_stock_class.php');

	$slsc = new stats_low_stock_class();

/*
* calculate start_date and end_date
* start default is now minus 2 month = 60 days = 1440 hours = 86400 minutes = 5184000 seconds
* 1 month is equal to 2592000
* end default is now
*
* edit: for what this period is used for and why is not documented.
*/

	$pastMonths = 2; //edit: if this is zero, the script throws warnings

	//edit: class variables?
	$start_date	= $slsc->httpGetVars('start_date', date('Y-m-d', time() -  $pastMonths * 2592000));

	$end_date		= $slsc->httpGetVars('end_date', date('Y-m-d'));

?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>Attributes Stock Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">	
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
<style>
.form-control{width:auto; display:inline-block;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>
<!-- header_eof //-->
	
							<div style="height:20px;"></div>
								<h1 class="pageHeading"><?php echo(HEADING_TITLE.' <font size="2">(&lt; ' . STOCK_REORDER_LEVEL . ')</font>'); ?></h1>
								<div style="height:20px;"></div>
<!-- listing -->
<?php
/* read in order and sorting values for the listing and sql query */

	$sorted = $slsc->httpGetVars('sorted', 'ASC', array('ASC', 'DESC'));

	$orderby = $slsc->httpGetVars('orderby', 'stock');

	//db_orderby based on orderby
	switch($orderby)
	{
		case 'stock':
		default:
			$orderby  = 'stock';
			$db_orderby = 'p.products_quantity';
			break;

		case 'model':
			$db_orderby = 'p.products_model';
			break;

		case 'name':
			$db_orderby = 'pd.products_name';
			break;
			
		case 'status':
			$db_orderby = 'pd.products_status';
			break;
	}
?>
<style>
		 a.tooltip:hover{color:#000;}
		 a.tooltip span {
    z-index:10;display:none; padding:14px 20px;
    margin-top:-30px; margin-left:28px;
    width:300px; line-height:16px;
}
a.tooltip:hover span{
    display:inline; position:absolute; color:#111;
    border:1px solid #DCA; background:#fffAF0;}
	a.tooltip:hover span img{width:100%;}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}
</style>

<div id="responsive-table">
<table class="table table-striped table-bordered table-hover dataTable" id="dataTables-low-stock">
	 <thead>
    <tr class="dataTableHeadingRow">
		<th class="dataTableHeadingContent"><?php echo( TABLE_HEADING_NUMBER ); ?></th>
		<th class="dataTableHeadingContent"><?php echo( TABLE_HEADING_ATTRIBUTES);	?><a class="tooltip"><i class="fa fa-question-circle-o" style="font-size:18px; margin-left:5px;     color: #fff;"></i><span>Click below to search orders for products with this attribute</span></a></th>
  		<th class="dataTableHeadingContent"><?php echo( TABLE_HEADING_PRODUCTS);	?><a class="tooltip"><i class="fa fa-question-circle-o" style="font-size:18px; margin-left:5px;     color: #fff;"></i><span>Click below to view product</span></a></th>
		<th class="dataTableHeadingContent"><?php echo( TABLE_HEADING_QTY_LEFT); ?>&nbsp;</th>
		<th class="dataTableHeadingContent" align="left"><?php echo(TABLE_HEADING_SALES); ?>&nbsp;</th>
		<th class="dataTableHeadingContent"><?php echo( $slsc->htmlCaptionSortLink('status', FILENAME_STATS_LOW_STOCK, TABLE_HEADING_STATUS) ); ?>&nbsp;</th>
	</tr>
    </thead>
    <tbody>
<?php
	$rows = ((int)$HTTP_GET_VARS['page'] > 1) ? ( (int)$HTTP_GET_VARS['page'] - 1) * 30 : 0;

/* SQL: setup query */

	// select query incl. orderby
	
	$attributes_query_raw = sprintf("select p.products_id, pd.products_name, p.products_status, pa.options_quantity, pov.products_options_values_name, pov.products_options_values_id from ".TABLE_PRODUCTS." p,  ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS_ATTRIBUTES." pa,  ".TABLE_PRODUCTS_OPTIONS_VALUES." pov where p.products_id = pd.products_id and  p.products_id = pa.products_id and  pa.options_values_id = pov.products_options_values_id and pa.options_quantity REGEXP ('^[(-9)-0]') and pa.options_quantity <= 0 and p.products_status =1 order by pa.options_quantity");

	//limit results
	
	//execute database query
	$products_query = tep_db_query($attributes_query_raw);

	while ($products = tep_db_fetch_array($products_query))
	{
		$rows++;
		$products_id = $products['products_id'];

		/* get category path of item */

			// find the products category
			$last_category_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = $products_id");
			$last_category = tep_db_fetch_array($last_category_query);
			$p_category = $last_category["categories_id"];

			// store and find the parent until reaching root
			$p_category_array = array();		
			do
			{
				$p_category_array[] = $p_category;
								if  ($p_category == ""){
					//Dont run query this time, it will error. Skip to next record. 
				}else {
				$last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = $p_category");
				$last_category = tep_db_fetch_array($last_category_query);
				$p_category = $last_category["parent_id"];
				}
			}	while ($p_category);
			$cPath_array = array_reverse($p_category_array);
			unset($p_category_array);

		/* done */


// Sold in Last x Months Query
  $productSold_query = tep_db_query("select sum(op.products_quantity) as quantitysum FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op WHERE o.date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND o.orders_id = op.orders_id AND op.products_id = $products_id GROUP BY op.products_id ORDER BY quantitysum DESC, op.products_id");
  $productSold = tep_db_fetch_array($productSold_query);

// Calculating days stock
if ($products['products_quantity'] > 0) {
	$StockOnHand = $products['products_quantity'];
	$SalesPerDay = $productSold['quantitysum'] / ($pastMonths * 30);

	round ($SalesPerDay, 2);
	$daysSupply = 0;
	$display = 'y';
	if ($SalesPerDay > 0) {
	$daysSupply = $StockOnHand / $SalesPerDay;
	}

	round($daysSupply);
	if ($daysSupply <= '20') {
	  $daysSupply = '<font color=red><b>' . round($daysSupply) . ' ' . DAYS . '</b></font>';
	} else {
	  $daysSupply = round($daysSupply) . ' ' . DAYS;
	}

	if (($SalesPerDay == 0) && ($StockOnHand > 1)) {
	  $display = 'n';
	  $daysSupply = '+60 '. DAYS;
	}

	if ($daysSupply > ($pastMonths * 30)) {
	$display = 'n';
	}

} else {
$daysSupply = '<font color=red><b>NA</b></font>';
$display = 'y';
}

	//edit: skip, because I had no time to check the code above
	$display = 'y';
	if ($display == 'y') {

		// diverse urls used in output
		
		$url_product = "categories.php?pID=" . $products['products_id']."&action=new_product";
		$url_orders = tep_href_link('client_search.php', 'cPath=' . $cPath . '&pID=' . $products['products_id'].'&product_options_id='.$products['products_options_values_id'].'');

		// some tweaking to make the output just looking better
		$prodsold = ($productSold['quantitysum'] > 0) ? (int)$productSold['quantitysum'] : 0;
		$prodmodel = trim((string)$products['products_model']);
		$prodmodel = (strlen($prodmodel)) ? htmlspecialchars($prodmodel) : '&nbsp;';

		// make negative qtys red b/c people have backordered them
		$productsQty = (int) $products['options_quantity'];
		$productsQty = ($productsQty < 0) ? sprintf('<font color="red"><b>%d</b></font>', $productsQty) : (string) $productsQty;

?>
        <tr class="dataTableRow" onClick="document.location.href='<?php echo($url_newproduct); ?>'">
		<td style="text-align:center;" class="dataTableContent"><?php echo $rows; ?>.</td>
        <td class="dataTableContent"><?php echo '<a onclick="return !window.open(this.href);" href="' . $url_orders . '" class="blacklink">' . $products['products_options_values_name'] . '</a>'; ?></td>
		<td class="dataTableContent"><?php echo '<a onclick="return !window.open(this.href);" href="' . $url_product . '" class="blacklink">' . $products['products_name'] . '</a>'; ?></td>
		<td class="dataTableContent"><?php echo $productsQty; ?></td>
		<td align="left" class="dataTableContent"><?php echo($prodsold); ?></td>
		<td align="center" class="dataTableContent">
		<?php		
		if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath . '&page=' .$HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';  //.'&limite_1='.$limite_1.'&limite_2='.$limite_2
      } else {
        echo '<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath . '&page=' .$HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);  // .'&limite_1='.$limite_1.'&limite_2='.$limite_2
      }
?>
	</td></tr>
   
<?php
  unset($cPath_array);
  	}
  }
?>
 </tbody>
 </table>
 </div>
<!-- listing end // -->



       
    <script src="js/jquery-1.10.2.js"></script>
	<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <script>
    $(document).ready(function() {
        $('#dataTables-low-stock').dataTable({
		 order: [[ 3, 'asc' ], [ 2, 'asc' ]]} )
    });
    </script>
	
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
