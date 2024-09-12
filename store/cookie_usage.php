<?php
/*
  $Id: cookie_usage.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_COOKIE_USAGE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_COOKIE_USAGE));
echo $doctype;
?><html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>  
<h1><?php echo HEADING_TITLE; ?></h1>

<?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT,'','stock-image'); ?>
<div class="clear"></div>
<div class="grid_4 alpha">
<?php echo TEXT_INFORMATION; ?>
</div>
<div class="grid_4 alpha">
<?php new cssinfoBoxHeading(array(array('text' => BOX_INFORMATION_HEADING))); ?>
<?php new cssinfoBox(array(array('text' => BOX_INFORMATION))); ?>
</div>

 <div class="clear spacer-tall"></div>  
 <div class="grid_8 right-align alpha"> 
                <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
  </div>                
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
