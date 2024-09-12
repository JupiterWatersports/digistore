<?php
/*
  $Id: create_account_success.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
  } else {
    $origin_href = tep_href_link(FILENAME_DEFAULT);
  }
echo $doctype;
?><html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<span class="leftfloat"><h1><?php echo HEADING_TITLE; ?></h1></span>
</span><div class="divider-pageheading"></div>
<p><?php echo TEXT_ACCOUNT_CREATED; ?></p>
<div class="divider-tall"></div>
 <div class="pagebox">
 <div class="rightfloat">
 <?php echo '<a href="' . $origin_href . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
 
 </div>
 <div class="divider"></div>
 </div>
              
<?php require(DIR_WS_INCLUDES . 'template-bottom-simple.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
