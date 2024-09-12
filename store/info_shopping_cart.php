<?php
/*
  $Id: info_shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
  Released under the GNU General Public License  
*/
  require("includes/application_top.php");

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART);
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 <?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>  

<h1><?php echo HEADING_TITLE; ?></h1>

<div class="grid_4 alpha"><b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br /><?php echo SUB_HEADING_TEXT_1; ?></div>
<div class="grid_4 omega"><b><i><?php echo SUB_HEADING_TITLE_2; ?></i></b><br /><?php echo SUB_HEADING_TEXT_2; ?></div>
<div class="clear spacer-tall"></div>
<div class="grid_4 alpha"><b><i><?php echo SUB_HEADING_TITLE_3; ?></i></b><br /><?php echo SUB_HEADING_TEXT_3; ?></div>

<p align="right" class="main"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
</body>
</html>
<?php
 require("includes/counter.php");
  require(DIR_WS_INCLUDES . 'template-bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
