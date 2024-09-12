<?php
/*
  $Id: coupons_status.php,v 1.0 2005/05/16 00:37:51 tabsl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>
  
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>

<body bgcolor="#f0f0f0">

<br>
<div class="main">&nbsp;
<strong><?php echo TEXT_COUPON ." ". $_GET["code"];?></strong>
</div><br>

<table border="0" width="97%" cellspacing="0" cellpadding="3" align="center">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER; ?></td>
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER; ?></td>
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?>&nbsp;&nbsp;</td>
	</tr>
	<?php
	function date2german($fdate) {
		list($jahr, $monat, $tag) = explode("-", $fdate);
		return sprintf("%02d.%02d.%04d", $tag, $monat, $jahr);
	}
	
	$res = tep_db_query("SELECT * FROM " . TABLE_COUPONS_SALES. " WHERE coupons_code = '" . $_GET["code"] . "' ORDER BY orders_id");
	if(!tep_db_num_rows($res)) {
		echo '<tr height="40">';
		echo '	<td colspan="4" class="main" align="center"><em>'.TEXT_NOCOUPON.'</em></td>';
		echo '</tr>';
	} else {
		while($row = tep_db_fetch_array($res)) {
			if ($bg == "#ffffff") { $bg = "#EEF7FD"; } else { $bg = "#ffffff"; }
			$res2 = tep_db_query("SELECT customers_firstname, customers_lastname FROM customers WHERE customers_id = ".$row["customers_id"]);
			$row2 = tep_db_fetch_array($res2);
			echo '<tr bgcolor="'.$bg.'">';
			echo '	<td class="main">'.$row2["customers_firstname"].' '.$row2["customers_lastname"].'</td>';
			echo '	<td class="main">'.($row["orders_id"]+2000).'</td>';
			echo '	<td class="main">'.date2german($row["date_purchased"]).'</td>';
			echo '</tr>';
		}
	}
	?>
</table>
<br>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
