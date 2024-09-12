<?php
/*
  $Id: shipping.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License

*/

  require('includes/application_top.php');
  $breadcrumb->add(Shipping, tep_href_link(FILENAME_SHIPPING));
 echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Shipping Info</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
</head>
<?php require(DIR_WS_INCLUDES . 'template-top-info-pages.php');  ?>

<h1>Shipping &amp; Returns Info</h1>
<br>
<p><strong><span style="font-family:Tahoma;font-weight:normal;mso-bidi-font-weight:
bold">We require for all online orders that the shipping address match the billing address of the provided credit card. This is for your and our protection, if this is not the case we will contact you to confirm your information before your order is processed.<br />
<br />
</span></strong><strong><span style="font-family:Tahoma">Free Shipping:</span></strong><strong><span style="font-family:Tahoma;font-weight:normal;mso-bidi-font-weight:bold"><br />
Jupiter Kiteboarding</span></strong><strong><span style="font-family:Tahoma"> </span></strong><span style="font-family:Tahoma">offers free shipping on any order over $99 within the mainland United States, excluding Puerto Rico,&nbsp;Alaska, Guam&nbsp;and Hawaii. Items with a weight or dimensional weight of more than 40lbs will have a shipping surcharge. Most surfboards and kite surfboards will have a shipping surcharge which we will confirm with you. We will always contact you with an estimate if your shipment is outside of these guidelines. We are committed to provide the most reliable shipping services at the lowest cost.&nbsp;</span></p>
<p><strong><span style="font-family:Tahoma">Shipping Paddleboards:</span></strong><span style="font-family:Tahoma"> <br />
We offer free shipping up to $100 on all new paddleboards and packages within the mainland United States, used boards will have a shipping surcharge. We will always confirm your total before charging you. </span><span style="font-family:
Tahoma;mso-fareast-font-family:&quot;Times New Roman&quot;">All paddleboards must be inspected by the buyer upon delivery, check over all contents before signing off on the shipment. Do not sign for your shipment if there is any damage to your board. Refuse the shipment and we will resolve the issue in a timely manner. Once you sign for the board you assume all responsibility for any damage.</span></p>
<p><strong><span style="font-family:Tahoma">Expedited Shipping:&nbsp;</span></strong><span style="font-family:Tahoma"> <br />
Please contact us via email, phone&nbsp;or make a note during checkout and we will contact you immediately with the different options.</span></p>
<p><strong><span style="font-family:Tahoma">International Shipping:&nbsp;</span></strong><span style="font-family:Tahoma"> <br />
Please contact us via email, phone or make a note during checkout and we will confirm the cost and details before charging or shipping.</span></p>
<p><strong><span style="font-family:Tahoma">Returns</span></strong><span style="font-family:Tahoma">:&nbsp; <br />
If you would like to return an item please contact us prior to sending it back.&nbsp; We will issue instructions on returning your item(s).&nbsp; We have a 6% restocking fee on all returns.&nbsp;We do not accept returns on International Orders. </span></p>
<p class="MsoNormal"><b style="mso-bidi-font-weight:normal"><span style="font-size:10.0pt;font-family:Tahoma">Shipping Methods:<br />
</span></b><span style="font-size:10.0pt;font-family:Tahoma">We primarily use FedEx<b style="mso-bidi-font-weight:normal"> </b>for most of our orders but will ship via UPS or USPS upon request. All international orders are primarily shipped with USPS.</span></p>

<div class="right-align alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>	
</div>

<div class="clear spacer-tall"></div> 

<?php
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

