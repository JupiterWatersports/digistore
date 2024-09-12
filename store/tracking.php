<?php
/*
  $Id: privacy.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License 
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TRACKING);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_TRACKING, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<TITLE><?php echo $titletag; ?></TITLE>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<META NAME="Keywords" content="<?php echo $keywordtag; ?>">

<META NAME="Description" content="<?php echo $description; ?>"><base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?> 
<h1><?php echo HEADING_TITLE; ?></h1>

<div class="clear spacer"></div>

<div class="grid_8 alpha">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_UPS; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION_FEDEX; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_FEDEX; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION_USPS; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_USPS; ?></td>
          </tr>

        </table></td>
      </tr>
    </table>
</div>

<div class="clear spacer-tall"></div>

<div class="grid_8 right-align alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>	
</div>

<div class="clear spacer-tall"></div> 

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
