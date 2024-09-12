<?php
/*
  $Id: header.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

// START STS 4.1
$sts->restart_capture ('applicationtop2header');
// END STS 4.1

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// give the visitors a message that the website will be down at ... time
  if ((WARN_BEFORE_DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE == 'false')) {
    $messageStack->add('header', (TEXT_BEFORE_DOWN_FOR_MAINTENANCE . PERIOD_BEFORE_DOWN_FOR_MAINTENANCE), 'warning');
  }
// this will let the admin know that the website is DOWN FOR MAINTENANCE to the public
  if ((DOWN_FOR_MAINTENANCE == 'true') && (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE == getenv('REMOTE_ADDR'))) {
    $messageStack->add('header', TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }
// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
?>

<style>
body {
	background-color:#000;
}
</style>

<body>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td height="65" valign="middle"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'logo.jpg', (tep_not_null($header_tags_array['logo_text']) ? $header_tags_array['logo_text'] : STORE_NAME)) . '</a>'; ?></td>
<?php
  if ($banner = tep_banner_exists('dynamic', '718x112')) {
   echo '<td>'. tep_display_banner('static', $banner) . '</td>'; 
  }
?>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr class="headerNavigation">
    <td align="left" class="headerNavigation" height="15" style="padding:0px 10px 0px 10px" bgcolor='<?php echo TITLE_BAR_BG; ?>'><?php if (tep_session_is_registered('customer_id')) { ?><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_LOGOFF; ?></a> &nbsp;|&nbsp; <?php } ?>
	 <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CHECKOUT; ?></a> &nbsp; | &nbsp;<a href="<?php echo tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'); ?>" class="headerNavigation"><?php echo FOOTER_SPECIALS; ?></a> 
    &nbsp; | &nbsp;<a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL'); ?>" class="headerNavigation"><?php echo FOOTER_PRODUCTS_NEW; ?></a> &nbsp; | &nbsp;<a href="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL'); ?>" class="headerNavigation"><?php echo FOOTER_SEARCH; ?></a>&nbsp; | &nbsp;<a href="<?php echo tep_href_link(FILENAME_CONTACT_US, '', 'SSL'); ?>" class="headerNavigation"><?php echo FOOTER_CONTACT; ?></a>&nbsp; | </td>

<td align="right" bgcolor='<?php echo TITLE_BAR_BG; ?>' valign="middle" height="15">
          <table cellpadding="0" cellspacing="0" border="0" style="padding:0px 0px 0px 10px"><tr><td valign="middle">
         <td valign="middle" style="padding:4px 10px 0px 10px"></td></tr></table></td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerCrumb">
    <td height="28" class="headerCrumb" valign="middle" style="padding:0px 10px 0px 2px">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
<td  align="right" style="padding:0px 11px 0px 10px" class="headerCrumb" valign="middle">
                     <table cellpadding="0" cellspacing="0" border="0"><tr><td valign="middle"> 	<!-- search_box_oef //-->
					<?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get') . tep_draw_hidden_field('search_in_description','1') . tep_draw_input_field('keywords', 'Enter keywords here', 'size="10" maxlength="30" style="width: 135px; height:20px; font-size:12px;" onclick="this.value=\'\'"') . '<td  style="vertical-align: top; padding:0px 0px 0px 10px;">' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH). '</a></form></td></tr></table></td>'; ?>
</td>
  </tr>
</table>

  </tr>
</table>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
