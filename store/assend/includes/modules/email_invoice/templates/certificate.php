<?php
/*
  $Id: invoice.php,v 1.6 2003/06/20 00:37:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<link rel="stylesheet" type="text/css" href="<?php echo $ei_css_path; ?>stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" style="background:#fff; font-size:14px;">

<!-- body_text //-->
<table border="0" cellpadding="0" cellspacing="0" width="1000px" align="center">
<tbody>
<tr>
<td align="right" class="hide_this" style="vertical-align: text-top;">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="100%">
<tbody>
<tr>
<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="379px">
<tr><td style="text-align:right;" valign="top"><img alt="" border="0" class="hide_this" height="100%" src="http://www.jupiterkiteboarding.com/images/gift_certificate-left.jpg" style="display: inline" width="100%"></td>
</tr>
</table>
</td>

<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="557px"> 
<tr><td><img alt="" border="0" class="hide_this" height="238" src="http://www.jupiterkiteboarding.com/images/gift_certificate-upper.jpg" style="display: inline" width="100%"></td></tr>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" style="height:397px">
<tr><td style="padding-top:15px; padding-left:20px;"><span style="font-size:20px;">To:</span>&nbsp;<?php echo '<span style="font-size:20px;">'.$order->delivery['name'].'</span>'; ?></td></tr>
<tr><td style="padding-top:20px; padding-left:20px;"><span style="font-size:14px;">Congratulations on receiving this Gift Certificate. Visit our web site at <a style="font-size:14px; color:#000;" href="http://www.jupiterkiteboarding.com"><u>www.jupiterkiteboarding.com</u></a> to see all of our products!  Please present this certificate when you are ready to redeem it! See you soon! - Jupiter Kiteboarding</span></td></tr>
<tr><td></td></tr>
<tr><td height="138" style="padding-left:20px;"><?php for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
echo '<span class="dataTableContent" style="vertical-align: text-top; font-size:20px;">' . $order->products[$i]['name']. '</span>';} ?>

<?php $total_query = tep_db_query("select class, text FROM " . TABLE_ORDERS_TOTAL . "  WHERE orders_id = '" . (int)$oID . "' AND class='ot_total'");
$total =  tep_db_fetch_array($total_query);
echo '<span style="font-size:40px; float:right;">' .$total['text'].'</span>';
 ?></td></tr>
<tr><td style="padding-top:10px;">Expires 1 year from:&nbsp;<?php echo tep_date_short($order->info['date_purchased']); ?>
</td></tr>
<tr><td style="padding-top:15px;">Order Number:&nbsp;<?php echo $oID; ?></td></tr>
<tr><td style="">&nbsp;</td></tr>
</table>
</td></tr>
<tr><td style="vertical-align: text-top;">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="100%">
<tr>
<td>
<img alt="" border="0" class="hide_this" style="height"183px;" src="http://www.jupiterkiteboarding.com/images/gift_certificate-lower.jpg" style="display: inline" width="100%"></td></tr>
</table>
</td>
</tr>
</table>
</td>
<td align="left" valign="top">
<table border="0" cellpadding="0" cellspacing="0" class="hide_this" width="159px">
<tr>
<td><img alt="" border="0" class="hide_this" height="100%" src="http://www.jupiterkiteboarding.com/images/gift_certificate-right.jpg" style="display: inline" width="100%">
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- body_text_eof //-->

<br>
</body>
</html>
