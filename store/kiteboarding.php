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
<title>Kiteboarding</title>

 <meta name="description" content="Here you will find all everything about Kiteboarding">
 <meta name="keywords" content="Kiteboarding, Kitesurfing, gear">
 <meta http-equiv="Content-Language" content="en-US">
 <meta name="googlebot" content="all">
 <meta name="robots" content="index, follow">
 <link rel="stylesheet" type="text/css" href="base.css">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<div class="container">

<div class="clear"></div>
	

<div style="display:block; position:relative;" >
<style>.grid_2{display:none;}
.container{width:100%;}
 #menu ul li a { padding:10px 15px; display:block; color:#000; font-weight:bold; text-decoration:none; font-size:18px; } 
 @media (min-width: 1024px) {.container-fluid{width:1024px !important;}}
 .less-pad-right{padding-right:7.5px !important;}
 .less-pad-left{padding-left:7.5px !important;}
</style>

<div class="col-xs-3">
<ul>
<li class="form-group">
<h3 style="line-height:25px;">Trainer Kites and Lessons</h3>
<ul>

<li><a href="<?php echo tep_href_link('trainer-kites-packages-c-611_587_52.html');?>">Trainer Kites & Packages</a></li>
<li><a href="<?php echo tep_href_link('kiteboarding-lessons-c-611_578.html?osCsid=ef2832ba3b5557d24d07d1bd82015e6c'); ?>">Lessons</a></li>

</ul>
</li>


<li class="form-group">
<h3>Kites</h3>
<ul>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=68&sort=products_sort_order'); ?>">Airush</a></li>
<li><a href="<?php echo tep_href_link('cabrinha-kites'); ?>">Cabrinha</a></li>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=11&sort=products_sort_order'); ?>">North</a></li>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=35&sort=products_sort_order'); ?>">Wainman Hawaii</a></li>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=135&sort=products_sort_order'); ?>">F-One</a></li>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=29&sort=products_sort_order'); ?>">Ozone</a></li>
<li><a href="<?php echo tep_href_link('kitesurfing-kites-c-611_45.html?filter_id=163&sort=products_sort_order'); ?>">Ocean Rodeo</a></li>
<li><a href="<?php echo tep_href_link('kiteboarding-kiteboarding-packages-c-611_49.html'); ?>">Packages</a></li>  
</ul>
</li>

<li class="form-group">
<h3>Boards</h3>
<ul>
<li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566.html'); ?>">Twin Tips</a></li>
<li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567.html'); ?>">Kite Surfboards</a></li>
<li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-foil-boards-c-611_305_680.html'); ?>">Foil Boards</a></li>
<li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182.html'); ?>">Pads &amp; Straps</a></li>
<li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206.html'); ?>">Fins</a></li>
<li><a href="<?php echo tep_href_link('used-kiteboards'); ?>">Used Boards</a></li>
</ul>
</li>

<li class="form-group">
<h3>Harnesses</h3>
<ul>
<li><a href="<?php echo tep_href_link('harnesses-waist-harness-c-611_312_568.html'); ?>">Waist</a></li>
<li><a href="<?php echo tep_href_link('harnesses-seat-harness-c-611_312_569.html'); ?>">Seat</a></li>
<li><a href="<?php echo tep_href_link('harnesses-impact-vests-impact-harnesses-c-611_312_255.html'); ?>">Impact Vests</a></li>
<li><a href="<?php echo tep_href_link('harnesses-kite-harness-accessories-c-611_312_463.html'); ?>">Accessories</a></li>
</ul>
</li>

<li class="form-group">
<h3>Control Bars</h3>
<ul>
<li><a href="<?php echo tep_href_link('complete-bars-c-611_62_681.html'); ?>">Complete Bars</a></li>
<li><a href="<?php echo tep_href_link('control-bars-lines-replacement-lines-c-611_62_48.html'); ?>">Replacement Lines</a></li>
<li><a href="<?php echo tep_href_link('control-bars-lines-safety-leashes-c-611_62_230.html'); ?>">Safety Leashes</a></li>
<li><a href="<?php echo tep_href_link('control-bars-lines-replacement-parts-c-611_62_615.html'); ?>">Parts</a></li> 
</ul>
</li>

<li class="form-group">
<h3>Accessories</h3>
<ul>
<li><a href="<?php echo tep_href_link('accessories-kite-board-bags-c-611_36_66.html'); ?>">Kite &amp; Board Bags</a></li>
<li><a href="<?php echo tep_href_link('accessories-helmets-c-611_36_193.html'); ?>">Helmets</a></li>
<li><a href="<?php echo tep_href_link('accessories-kite-pumps-c-611_36_224.html'); ?>">Pumps</a></li>
<li><a href="<?php echo tep_href_link('accessories-wind-meters-c-611_36_505.html'); ?>">Wind Meter</a></li>   
</ul>
</li>

<li class="form-group">
<h3>Repair</h3>
<ul>
<li><a href="<?php echo tep_href_link('kite-bladder-board-repair-c-611_494.html'); ?>">Kite, Board, & Bladder</a></li>
<li><a href="<?php echo tep_href_link('leading-edge-bladder-c-611_502.html'); ?>">Leading Edge</a></li>
<li><a href="<?php echo tep_href_link('strut-bladder-c-611_601.html'); ?>">Strut Bladder</a></li>
<li><a href="<?php echo tep_href_link('replacement-valves-for-bladders-c-611_500.html'); ?>">Valves</a></li>
</ul>
</li>





</ul>
</div>


<div class="col-xs-9" >


<div class="form-group row">
<!-- 66% column image 570 x 240px  or 570px x 300px  -->
<div class="col-xs-8 less-pad-right"><a href="http://www.jupiterkiteboarding.com/store/kitesurfing-kites-c-611_45.html?filter_id=68&sort=products_sort_order" target="_self"><img src="http://jupiterkiteboarding.com/ads/kiteboarding/airush_wave_450.jpg" width="450" height="240"></a></div>

<!-- 33% column image 270px x 240px or 270px x 300px  -->
<div class="col-xs-4 less-pad-left"><a href="http://www.jupiterkiteboarding.com/store/woo-kiteboarding-performance-tracker-p-5538.html" target="_self"><img src="http://jupiterkiteboarding.com/ads/kiteboarding/woo_212.jpg" width="212" height="240"></a></div>
</div>

<div class="form-group row">
<!-- 33% column image 270px x 240px or 270px x 300px  -->
<div class="col-xs-4 less-pad-right"><a href="http://www.jupiterkiteboarding.com/store/foil-boards-c-611_305_680.html" target="_self"><img src="http://jupiterkiteboarding.com/ads/kiteboarding/double_212.jpg" width="212" height="240"></a></div>

<!-- 66% column image 570 x 240px  or 570px x 300px  -->
<div class="col-xs-8 less-pad-left"><a href="http://www.jupiterkiteboarding.com/store/kitesurfing-kites-c-611_45.html?filter_id=35&sort=products_sort_order" target="_self"><img src="http://jupiterkiteboarding.com/ads/kiteboarding/wainman_450.jpg" width="450" height="240"></a></div>
</div>
<div class="form-group">
<!-- full width image 690px wide  -->
<div class="col-xs-12" style="padding:0px;"><a href="http://www.jupiterkiteboarding.com/store/waist-harness-c-611_312_568.html?filter_id=118&sort=products_sort_order" target="_self"><img src="http://www.jupiterkiteboarding.com/ads/kiteboarding/vertex_700.jpg" width="700" height="240"></a></div>
</div>

</div>
  <style>
*{box-sizing:border-box;}
.col-xs-9 img{width:100%; height:auto;}
li a:hover{color:#09f !important;}

</style>


<br style="line-height:11px;">



				<table cellpadding="0" cellspacing="0" class="product">
					<tr><tr><td class="padd_22"></td></tr></tr>
				</table>


<br style="line-height:1px;">
<br style="line-height:10px;">					


</div>
<!-- close content -->
<div class="clear"></div>
</div>
<div class="clear"></div>
	<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>