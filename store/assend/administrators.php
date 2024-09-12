<?php
/*
  $Id: administrators.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce
  
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        require('includes/functions/password_funcs.php');

        $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($username) . "' limit 1");

        if (tep_db_num_rows($check_query) < 1) {
          tep_db_query("insert into " . TABLE_ADMINISTRATORS . " (user_name, user_password) values ('" . tep_db_input($username) . "', '" . tep_db_input(tep_encrypt_password($password)) . "')");
        } else {
          $messageStack->add_session(ERROR_ADMINISTRATOR_EXISTS, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS));
        break;
      case 'save':
        require('includes/functions/password_funcs.php');

        $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($admin['username']) . "'");
        $check = tep_db_fetch_array($check_query);

        if ($admin['id'] == $check['id']) {
          $admin['username'] = $username;
        }

        tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_name = '" . tep_db_input($username) . "' where id = '" . (int)$HTTP_GET_VARS['aID'] . "'");

        if (tep_not_null($password)) {
          tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_password = '" . tep_db_input(tep_encrypt_password($password)) . "' where id = '" . (int)$HTTP_GET_VARS['aID'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . (int)$HTTP_GET_VARS['aID']));
        break;
      case 'deleteconfirm':
        $id = tep_db_prepare_input($HTTP_GET_VARS['aID']);

        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($admin['username']) . "'");
        $check = tep_db_fetch_array($check_query);

        if ($id == $check['id']) {
          tep_session_unregister('admin');
        }

        tep_db_query("delete from " . TABLE_ADMINISTRATORS . " where id = '" . (int)$id . "'");

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS));
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
</head>
<body  onload="SetFocus();">
<div id="wrapper">
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
 <div id="heading-block">
 <p class="pageHeading"><?php echo HEADING_TITLE; ?></p>
       </div>
  <div id="responsive-table">      
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ADMINISTRATORS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $admins_query = tep_db_query("select id, user_name from " . TABLE_ADMINISTRATORS . " order by user_name");
  while ($admins = tep_db_fetch_array($admins_query)) {
    if ((!isset($HTTP_GET_VARS['aID']) || (isset($HTTP_GET_VARS['aID']) && ($HTTP_GET_VARS['aID'] == $admins['id']))) && !isset($aInfo) && (substr($action, 0, 3) != 'new')) {
      $aInfo = new objectInfo($admins);
    }

    if ( (isset($aInfo) && is_object($aInfo)) && ($admins['id'] == $aInfo->id) ) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $admins['user_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($aInfo) && is_object($aInfo)) && ($admins['id'] == $aInfo->id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ADMINISTRATOR . '</b>');

      $contents = array('form' => tep_draw_form('administrator', FILENAME_ADMINISTRATORS, 'action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_USERNAME . '<br />' . tep_draw_input_field('username'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_PASSWORD . '<br />' . tep_draw_password_field('password'));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMINISTRATORS) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . $aInfo->user_name . '</b>');

      $contents = array('form' => tep_draw_form('administrator', FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_USERNAME . '<br />' . tep_draw_input_field('username', $aInfo->user_name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_NEW_PASSWORD . '<br />' . tep_draw_password_field('password'));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . $aInfo->user_name . '</b>');

      $contents = array('form' => tep_draw_form('administrator', FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $aInfo->user_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($aInfo) && is_object($aInfo)) {
        $heading[] = array('text' => '<b>' . $aInfo->user_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
      }
      break;
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
    </table>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
