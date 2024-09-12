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

$breadcrumb->add('Used Kiteboards', tep_href_link('used-kiteboards'));
echo $doctype;
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Used Kiteboards</title>

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
   
   <h3>We are currently listing all of our used equipment on Ebay so feel free to click the link below to see what we have and happy bidding.</h3>
   
   <div style="width:100%; text-align:center; margin-top:30px; display:block;"><a target="_blank" href="http://www.ebay.com/sch/jupiterkiteboarding/m.html?_nkw=&_armrs=1&_ipg=&_from="><img src="images/ebay-logo.jpg" style="width:70%; max-width:500px;"></a></div>

 </div>        
<?php
  

require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
