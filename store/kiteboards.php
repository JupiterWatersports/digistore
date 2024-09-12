<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/
  require('includes/application_top.php');
$breadcrumb->add('Kiteboarding', tep_href_link('kiteboarding'));
echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Kiteboards</title>

 <meta name="description" content="Here you will find all our Cabrinha Kites from 2016, 2015, 2014, and 2013">
 <meta name="keywords" content="Cabrinha Kitesurfing Kiteboarding Kites">
 <meta http-equiv="Content-Language" content="en-US">
 <meta name="googlebot" content="all">
 <meta name="robots" content="noodp">
 <meta name="slurp" content="noydir">
 <meta name="robots" content="noindex, nofollow">
 <link rel="stylesheet" type="text/css" href="base.css">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<div class="container">

<div class="clear"></div>
	

<div style="display:block; position:relative;" >
<style>.grid_2{display:none;}
.container{width:100%; margin-top:15px;}
 #menu ul li a { padding:10px 15px; display:block; color:#000; font-weight:bold; text-decoration:none; font-size:18px; } 
  @media only screen and (max-width: 768px){.listingimg2 img{max-width:160px !important;} .categories-container{padding-left:0px;padding-right:0px;} }
 .listingimg2 img{width:100%; height:auto; max-width:200px;}
 #product-block2{padding:15px 0px; text-align:center; float:none; display:inline-block; vertical-align:top;}
 h2{font-size:27px;}
 h4{font-weight:100; font-size:16px;}
 @media (min-width: 1200px){
.container-fluid {
    width: 1200px !important;
}
 }
 @media only screen and (min-width: 768px){
.listingimg2, #product-block-nameprice {
    width: 100%; padding:0px 15px;
}}
 
 .cssButton{width: 205px;
    height: 35px; display:inline-block; line-height:35px; opacity:0.9;}
	
.side-headline{font-size:20px; font-weight:bold; display:block; margin-bottom:20px;}
.side-headline:hover, #product-block2-nameprice span:hover{color:#09f;}

 
</style>

<div class="col-xs-12 col-sm-2">
<ul>

<a class="side-headline" href="<?php echo tep_href_link('kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566.html'); ?>">Twin Tips</a>
<a class="side-headline" href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567.html'); ?>">Kite Surfboards</a>
<a class="side-headline" href="<?php echo tep_href_link('kiteboards-fins-pads-straps-foil-boards-c-611_305_680.html'); ?>">Foil Boards</a>
<a class="side-headline" href="<?php echo tep_href_link('kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182.html'); ?>">Pads &amp; Straps</a>
<a class="side-headline" href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206.html'); ?>">Fins</a>
<a class="side-headline" href="<?php echo tep_href_link('used-kiteboards'); ?>">Used Boards</a>

</ul>
</div>


<div class="col-xs-12 col-sm-10 categories-container" >

<?php 
$headline_ids = array("566", "567", "680", "182", "206");  
$headlines = array("Twin Tips", "Kite Surfboards", "Foil Boards", "Pads & Straps", "Fins");

	
for ($i = 0; $i < count($headline_ids); ++$i) {	

	
echo '<div class="col-xs-12 form-group">
<div class="row">
<h2 style="text-align:center; text-transform:uppercase; margin-bottom:15px;">'.$headlines[$i].'</h2>
<hr>
<div class="col-xs-12 form-group" style="margin-top:10px; text-align:center; font-size:14px;">';
 $description_query = tep_db_query("select categories_htc_description from categories_description where categories_id = '".$headline_ids[$i]."'");
$description = tep_db_fetch_array($description_query);
echo '<h4>'.$description['categories_htc_description'].'</h4>';
?>
</div>

<?php 
$products_query = tep_db_query("select p.products_id, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, pd.products_name from products p, products_to_categories p2c, products_description pd where (p2c.categories_id = '".$headline_ids[$i]."') and p.products_id = p2c.products_id and p.products_id = pd.products_id and p.products_status='1' ORDER BY p.products_ordered DESC LIMIT 4 ");
while($products = tep_db_fetch_array($products_query)){
	
	
$p_price = '<li class="regPrice">' . $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id'])) . '</li>';
         

echo '<div id="product-block2" class="col-sm-3 col-xs-6">
<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">'
 .'<div class="col-xs-12 listingimg2">'. tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</img></div>'.
'<div id="product-block2-nameprice" class="col-xs-12"><span style="font-size:15px; font-weight:700;" class="product-block2-name">' . $products['products_name'],'</span><br />'.'<ul class="prices" style="margin-top:10px;">' .$p_price .'</ul>';


echo '</div></a></div>';


} ?>
</div>
<div class="col-xs-12 form-group" style="text-align:center; margin-bottom:25px;"><a class="cssButton addtocart" href="index.php?cPath=<?php echo $headline_ids[$i]; ?>">Shop All&nbsp;<?php echo $headlines[$i]; ?></a></div>
</div>
 <?php 
}  
?>




</div>
  <style>
*{box-sizing:border-box;}
</style>

</div>
<!-- close content -->
<div class="clear"></div>
</div>
<div class="clear"></div>
	<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>