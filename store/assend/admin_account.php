<?php
/*
  $Id: admin_account.php,v 1.29 2002/03/17 17:52:23 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Includes Contribution:
    Access with Level Account (v. 2.2a) for the Admin Area of osCommerce (MS2)

  This file may be deleted if disabling the above contribution

*/

  require('includes/application_top.php');
include 'includes/functions/admin_password_funcs.php'; 

  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'check_password':
        $check_pass_query = tep_db_query("select admin_password as confirm_password from " . TABLE_ADMIN . " where admin_id = '" . $HTTP_POST_VARS['id_info'] . "'");
        $check_pass = tep_db_fetch_array($check_pass_query);

        // Check that password is good
        if (!tep_validate_password($HTTP_POST_VARS['password_confirmation'], $check_pass['confirm_password'])) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=check_account&error=password'));
        } else {
          //$confirm = 'confirm_account';
          tep_session_register('confirm_account');
		  $GLOBALS['confirm_account'] = 1;
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process'));
        }

        break;
      case 'save_account':
        $admin_id = tep_db_prepare_input($HTTP_POST_VARS['id_info']);
        $admin_email_address = tep_db_prepare_input($HTTP_POST_VARS['admin_email_address']);
        $stored_email[] = 'NONE';
        $hiddenPassword = '-hidden-';

        $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
        while ($check_email = tep_db_fetch_array($check_email_query)) {
          $stored_email[] = $check_email['admin_email_address'];
        }

        if (in_array($HTTP_POST_VARS['admin_email_address'], $stored_email)) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=email'));
        } else {
          $sql_data_array = array('admin_firstname' => tep_db_prepare_input($HTTP_POST_VARS['admin_firstname']),
                                  'admin_lastname' => tep_db_prepare_input($HTTP_POST_VARS['admin_lastname']),
                                  'admin_email_address' => tep_db_prepare_input($HTTP_POST_VARS['admin_email_address']),
                                  'admin_password' => tep_encrypt_password(tep_db_prepare_input($HTTP_POST_VARS['admin_password'])),
                                  'admin_modified' => 'now()');

          tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

          tep_mail($HTTP_POST_VARS['admin_firstname'] . ' ' . $HTTP_POST_VARS['admin_lastname'], $HTTP_POST_VARS['admin_email_address'], sprintf(ADMIN_EMAIL_SUBJECT, STORE_NAME, $HTTP_POST_VARS['admin_firstname'], $HTTP_POST_VARS['admin_lastname']), sprintf(ADMIN_EMAIL_TEXT, $HTTP_POST_VARS['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $HTTP_POST_VARS['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		  
		  tep_mail(STORE_OWNER, 'warren@jupiterkiteboarding.com', sprintf(ADMIN_EMAIL_SUBJECT, STORE_NAME, $HTTP_POST_VARS['admin_firstname'], $HTTP_POST_VARS['admin_lastname']), sprintf(ADMIN_EMAIL_TEXT, $HTTP_POST_VARS['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $HTTP_POST_VARS['admin_email_address'], tep_db_prepare_input($HTTP_POST_VARS['admin_password']), STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $admin_id));
        }
        break;
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php require('includes/account_check.js.php'); ?>
</head>
<body onLoad="SetFocus();">
<div id="wrapper">
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
 <div id="heading-block">
      <?php if ($HTTP_GET_VARS['action'] == 'edit_process') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=save_account', 'post', 'enctype="multipart/form-data"'); } elseif ($HTTP_GET_VARS['action'] == 'check_account') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_password', 'post', 'enctype="multipart/form-data"'); } else { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_account', 'post', 'enctype="multipart/form-data"'); } ?>
      
       <p class="pageHeading"><?php echo HEADING_TITLE; ?></p>
       </div>
   
   <div id="responsive-table" style="clear:both;">
       <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top">
<?php
  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $login_id . " and g.admin_groups_id= " . $login_groups_id . "");
  $myAccount = tep_db_fetch_array($my_account_query);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACCOUNT; ?>
                </td>
              </tr>
              <tr class="dataTableRow">
                <td>
                  <table border="0" cellspacing="0" cellpadding="3">
<?php
    if ( ($HTTP_GET_VARS['action'] == 'edit_process') && (tep_session_is_registered('confirm_account')) ) {
?>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FIRSTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_firstname', $myAccount['admin_firstname']); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_lastname', $myAccount['admin_lastname']); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php if ($HTTP_GET_VARS['error']) { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_INFO_ERROR . '</nobr>'; } else { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']); } ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_password_field('admin_password'); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD_CONFIRM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_password_field('admin_password_confirm'); ?></td>
                    </tr>
<?php
    } else {
    if (tep_session_is_registered('confirm_account')) {
      tep_session_unregister('confirm_account');
    }
?>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FULLNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_email_address']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                    </tr>
                    <tr class="dataTableRowSelected">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_GROUP; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_groups_name']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_CREATED; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_created']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGNUM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_lognum']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGDATE; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_logdate']; ?></td>
                    </tr>
<?php
  }
?>
                  </table>
                </td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td align="right"><?php if ($HTTP_GET_VARS['action'] == 'edit_process') { echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; if (tep_session_is_registered('confirm_account')) { echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"'); } } elseif ($HTTP_GET_VARS['action'] == 'check_account') { echo '&nbsp;'; } else { echo tep_image_submit('button_edit.gif', IMAGE_EDIT); } ?></td><tr></table></td>
              </tr>
            </table>


            </td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'edit_process':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');

      $contents[] = array('text' => TEXT_INFO_INTRO_EDIT_PROCESS . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM, 'onClick="validateForm();return document.returnValue"') . '<br>&nbsp');
      break;
    case 'check_account':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</b>');

      $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      if ($HTTP_GET_VARS['error']) {
        $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR);
      }
      $contents[] = array('align' => 'center', 'text' => tep_draw_password_field('password_confirmation'));
      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM) . '<br>&nbsp');
      break;
    default:
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');

      $contents[] = array('text' => TEXT_INFO_INTRO_DEFAULT);
      //$contents[] = array('align' => 'center', 'text' => tep_image_submit('button_edit.gif', IMAGE_EDIT) . '<br>&nbsp');
      if ($myAccount['admin_email_address'] == 'admin@localhost') {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br>&nbsp');
      } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1) ) {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br>&nbsp');
      }

  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></form><div class="clearfix"></div>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
