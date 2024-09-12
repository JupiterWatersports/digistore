<?php
/*
  $Id: shipping.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License

*/

  require('includes/application_top.php');
  $breadcrumb->add(PriceMatch, tep_href_link('pricematch.php'));
 echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Price Match Guarantee</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
</head>
<?php require(DIR_WS_INCLUDES . 'template-top-info-pages.php');  ?>

<h1>Price Match Guarantee</h1>
<br>
<p class="MsoNormal"><span style="font-size:10.0pt;font-family:Tahoma">We are committed to you and to provide the best service we possibly can. We will match our competition&rsquo;s price and do not require a printed ad for proof. Simply call or email us with a link to where you found the price and after verification we will match it; online or in store we will match the price. Items purchased must be identical to competition (year, brand, model, size, etc.) No substitutions. </span></p>
<p class="MsoNormal"><b style="mso-bidi-font-weight:normal"><span style="font-size:10.0pt;font-family:Tahoma">Exceptions:</span></b></p>
<ul style="list-style:inherit; padding-left:15px;">
<li><span style="font-size:10.0pt;font-family:Tahoma">Competitor&rsquo;s item is being sold in auction (Ebay) or by sites such as Craigslist <br />
</span></li>
<li><span style="font-size:10.0pt;font-family:Tahoma"> Competitor&rsquo;s item is out of stock/not available for immediate shipment</span></li>
<li><span style="font-size:10.0pt;font-family:Tahoma">Competitor&rsquo;s item is advertised below Jupiter Kiteboarding&rsquo;s actual cost</span></li>
<li><span style="font-size:10.0pt;font-family:Tahoma">Competitor&rsquo;s advertised price is a typographical error. </span></li>
<li><span style="font-size:10.0pt;font-family:Tahoma">Any item which is subject to MAP Pricing (most GoPro products)</span></li>
</ul>
<div class="clear spacer-tall"></div> 
<div class="right-align alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>	
</div>

<div class="clear spacer-tall"></div> 

<?php
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

