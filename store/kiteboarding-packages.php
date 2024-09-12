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
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

$breadcrumb->add('Kiteboarding Packages', tep_href_link('kiteboarding-packages'));
echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Kiteboarding Packages</title>

 <meta name="description" content="Here you will find our Used Kiteboards">
 <meta name="keywords" content="Used Kitesurf Kiteboards">
 <meta http-equiv="Content-Language" content="en-US">
 <meta name="googlebot" content="all">
 <meta name="robots" content="noodp">
 <meta name="slurp" content="noydir">
 <meta name="robots" content="noindex, nofollow">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>

 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>

<div class="grid_7" id="content">
   
   <h3 style="line-height:30px;">
   <p>In order to provide a better experience for our customers we have done away with premade packages as they really do hinder what options for products we may advertise for a package. We know every person has different needs and a different budget so we will tailor a custom package for you, all we ask is either a little background about yourself (eg. experience level) or what you are looking for.</p>
   Thanks,</br>
   Jupiter Kiteboarding</h2>
     
   </p>
   <div style="width:100%; text-align:center; margin-top:40px; display:block; margin-bottom:20px;"><a target="_blank" href="http://www.jupiterkiteboarding.com/store/contact-us.php" style="font-size:30px; text-decoration:underline; ">Contact Us</a></div>

 </div>        
<?php
  

require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
