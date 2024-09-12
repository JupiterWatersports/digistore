<?php
/*
  $Id: logoff.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  

  // PWA BOF 2b

  if (tep_session_is_registered('customer_is_guest')){

    //delete the temporary account

    tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");

  }

  // PWA EOF 2b
	tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');

// PWA BOF
  tep_session_unregister('customer_is_guest');
// PWA EOF

// recently_viewed
  tep_session_unregister('recently_viewed'); // for customer's security, this line of code removes the recently_viewed info after logoff

  // Discount Code 2.9 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true' && tep_session_is_registered('sess_discount_code')) {
    tep_session_unregister('sess_discount_code');
  }
  // Discount Code 2.9 - end

  $cart->reset();
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta name="description" content="Click here to logoff." >
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<style>
.container_12{padding-bottom:400px;}
@media only screen and (max-width: 767px) {.container_12{padding-bottom:0px;}}
@media only screen and (max-height: 1024px) and (orientation : portrait) {.container_12{padding-bottom:139px;}}
@media only screen and (max-width: 800px) and (orientation :landscape) {.container_12{padding-bottom:0px;}}
@media only screen and (min-width: 960px) and (max-width: 1280px) and (orientation : landscape) {.container_12{padding-bottom:0px;}}
@media only screen and (min-height: 980px) and (max-width: 1280px) and (orientation : landscape) {.container_12{padding-bottom:57px;}}
</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>  

<h1><?php echo HEADING_TITLE; ?></h1>
<div class="grid_4 alpha">
<?php echo TEXT_MAIN; ?>
</div>
<div class="grid_8 alpha" style="float:left; margin-top:30px;">
      <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
</div>
<div class="clear"></div>
              
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
