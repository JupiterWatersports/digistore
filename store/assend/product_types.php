<?php
/*
  $Id: product_types.php ver 1.0 by Kevin L. Shelton 2011-02-12
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  $vid = (isset($HTTP_GET_VARS['vid']) ? $HTTP_GET_VARS['vid'] : '');
  $confirm = (isset($HTTP_GET_VARS['confirm']) ? $HTTP_GET_VARS['confirm'] : '');
  $parent_id = (isset($HTTP_GET_VARS['parent']) ? $HTTP_GET_VARS['parent'] : 0);
  $languages = tep_get_languages();
  $lang = array();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) { // build array accessed directly by language id
    $lang[$languages[$i]['id']] = array ('name' => $languages[$i]['name'],
                                         'code' => $languages[$i]['code'],
                                         'image' => $languages[$i]['image'],
                                         'directory' => $languages[$i]['directory']);
  }
  if (tep_not_null($action)) {
    $messages = array();
    $error = false;
    switch ($action) {
      case 'insert':
        $order = (isset($HTTP_POST_VARS['sort_order']) ? tep_db_prepare_input($HTTP_POST_VARS['sort_order']) : 0);
        $value = array();
        foreach ($lang as $id => $info) {       
          $value[$id] = (isset($HTTP_POST_VARS['value' . $id]) ? tep_db_prepare_input($HTTP_POST_VARS['value' . $id]) : '');
          if (!tep_not_null($value[$id])) { // validate form
            $error = true;
            $messages[] = ERROR_VALUE . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $info['directory'] . '/images/' . $info['image'], $info['name']);
            $action = 'new';
          }
        }
        $data_array = array('parent_id' => (int)$parent_id,
                            'sort_order' => (int)$order);
        if (!$error) {
          tep_db_perform(TABLE_PTYPES, $data_array);
          $vid = tep_db_insert_id();
          foreach ($lang as $id => $info) {
            $data_array = array('ptype_id' => (int)$vid,
                                'languages_id' => (int)$id,
                                'ptype_description' => $value[$id]);
           tep_db_perform(TABLE_PTYPE_DESC, $data_array);
          }
          tep_redirect(tep_href_link(FILENAME_PTYPES, 'vid=' . $vid));
        }
        break;
      case 'update': // validate form
        $order = (isset($HTTP_POST_VARS['sort_order']) ? tep_db_prepare_input($HTTP_POST_VARS['sort_order']) : 0);
        $value = array();
        foreach ($lang as $id => $info) {       
          $value[$id] = (isset($HTTP_POST_VARS['value' . $id]) ? tep_db_prepare_input($HTTP_POST_VARS['value' . $id]) : '');
          if (!tep_not_null($value[$id])) { // validate form
            $error = true;
            $messages[] = ERROR_VALUE . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $info['directory'] . '/images/' . $info['image'], $info['name']);
            $action = 'edit';
          }
        }
        $data_array = array('sort_order' => (int)$order);
        if (!$error) {
          tep_db_perform(TABLE_PTYPES, $data_array, 'update', 'ptype_id = ' . (int)$vid);
          foreach ($lang as $id => $info) {
            $data_array = array('ptype_description' => $value[$id]);
            tep_db_perform(TABLE_PTYPE_DESC, $data_array, 'update', 'ptype_id = ' . (int)$vid . ' and languages_id = ' . (int)$id);
          }
          tep_redirect(tep_href_link(FILENAME_PTYPES, 'vid=' . $vid));
        }
        break;
      case 'delete':
        if ($confirm == 'yes') {
          if (isset($HTTP_GET_VARS['used']) && ($HTTP_GET_VARS['used'] > 0)) {
            $double_check = 'yes';
          } else {
            $affected = implode(',', epf_get_ptype_children($vid));
            tep_db_query('delete from ' . TABLE_PTYPE_DESC . ' where ptype_id in (' . tep_db_input($affected) . ")");
            tep_db_query('delete from ' . TABLE_PTYPES . ' where ptype_id in (' . tep_db_input($affected) . ")");
            tep_db_query("update " . TABLE_PRODUCTS . " set products_type = 0 where products_type in (" . tep_db_input($affected) . ")");
            $linked_fields = array();
            $check_query = tep_db_query("select epf_id from " . TABLE_EPF . " where epf_has_linked_field = 2");
            while ($check = tep_db_fetch_array($check_query)) {
              $linked_fields[] = $check['epf_id'];
            }
            if (!empty($linked_fields)) {
              tep_db_query("update " . TABLE_EPF_VALUES . " set value_depends_on = 0 where epf_id in (" . tep_db_input(implode(',', $linked_fields)) . ") and value_depends_on in (" . tep_db_input($affected) . ")");
            }
            $check_query = tep_db_query("select epf_id, epf_ptype_ids from " . TABLE_EPF . " where length(epf_ptype_ids) > 0");
            $affected = explode(',', $affected);
            while ($check = tep_db_fetch_array($check_query)) {
              $is_used = false;
              $new_used = array();
              $types_used = explode('|', $check['epf_ptype_ids']);
              foreach ($types_used as $id) {
                if (in_array($id, $affected)) {
                  $is_used = true;
                } else {
                  $new_used[] = $id;
                }
              }
              if ($is_used) {
                if (empty($new_used)) {
                  tep_db_query("update "  . TABLE_EPF . " set epf_all_ptypes = 1, epf_ptype_ids = null where epf_id = " . (int)$check['epf_id']);
                } else {
                  tep_db_query("update "  . TABLE_EPF . " set epf_ptype_ids = '" . tep_db_input(implode('|', $new_used)) . "' where epf_id = " . (int)$check['epf_id']);
                }
              }
            }
            tep_redirect(tep_href_link(FILENAME_PTYPES));
          }
        } else {
          $double_check = 'no';
        }
        break;
      case 'edit':
        $check_query = tep_db_query("select count(products_id) as total from " . TABLE_PRODUCTS . " where products_type = " . (int)$vid);
        $check = tep_db_fetch_array($check_query);
        if ($check['total'] > 0) $messages[] = sprintf(WARNING_VALUE_USED, $check['total']);
        $check_query = tep_db_query("select epf_ptype_ids from " . TABLE_EPF . " where length(epf_ptype_ids) > 0");
        $epf_requires = 0;
        while ($check = tep_db_fetch_array($check_query)) {
          $types_used = explode('|', $check['epf_ptype_ids']);
          if (in_array($vid, $types_used)) $epf_requires++;
        }
        if ($epf_requires > 0) $messages[] = sprintf(WARNING_VALUE_REQ, $epf_requires);
        $linked_fields = array();
        $check_query = tep_db_query("select epf_id from " . TABLE_EPF . " where epf_has_linked_field = 2");
        while ($check = tep_db_fetch_array($check_query)) {
          $linked_fields[] = $check['epf_id'];
        }
        if (!empty($linked_fields)) {
          $check_query = tep_db_query("select count(value_id) as total from " . TABLE_EPF_VALUES . " where epf_id in (" . tep_db_input(implode(',', $linked_fields)) . ") and value_depends_on = " . (int)$vid);
          if ($check['total'] > 0) $messages[] = sprintf(WARNING_VALUE_LINKED, $check['total']);
        }
        break;
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' ' . HEADING_TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/general.js"></script>
<style>.error {color: black; background-color: red;}</style>
<style>.warning {color: black; background-color: yellow;}</style>
</head>
<body bgcolor="#FFFFFF" onload='showCountDown("5"); return true'>
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr> 
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
 <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          <?php if ($action == '') {
          ?>
          <tr>
            <td colspan=2 align="right" class="main"><?php echo tep_draw_form('new', FILENAME_PTYPES, 'action=new') . tep_draw_input_field('new', BUTTON_NEW, 'alt="' . BUTTON_NEW . '"', false, 'submit') . '</form> &nbsp;&nbsp; ' . tep_draw_form('sub', FILENAME_PTYPES, "", 'get') . tep_draw_hidden_field('action', 'new') . BUTTON_SUBTYPE . ': ' . tep_draw_pull_down_menu('parent', epf_build_ptype_pulldown(0, array(array('id' => 0, 'text' => TEXT_NONE))), 0, 'onChange="this.form.submit();"') . '</form>&nbsp;&nbsp;'; ?></td>
          </tr>
          <?php } ?>
        </table></td>
      </tr>
      <?php if (($action == 'new') || ($action =='edit')) {
      $descs = array();
      if ($action == 'edit') {
        $query = tep_db_query("select * from " . TABLE_PTYPE_DESC . " where ptype_id = " . (int)$vid);
        while ($value = tep_db_fetch_array($query)) {
          $descs[$value['languages_id']] = $value['ptype_description'];
        }
        $query = tep_db_query("select sort_order from " . TABLE_PTYPES . " where ptype_id = " . (int)$vid);
        $value = tep_db_fetch_array($query);
        echo '<tr><td><p class="pageHeading">' . HEADING_EDIT . $vid . ': ' . epf_get_ptype_desc_extended($vid) . "</p>\n";
      } else {
        echo '<tr><td><p class="pageHeading">' . HEADING_NEW;
        echo ($parent_id > 0 ? '<br>' . TABLE_HEADING_PARENT . $parent_id . ' ' . TEXT_EXT_DESC . epf_get_ptype_desc_extended($parent_id) : '') . "</p>\n";
      }
      if (!empty($messages)) {
        echo '<table ' . ($error ? 'class="error"' : 'class="warning"') . ' width="100%">' . "\n";
        foreach ($messages as $message) {
          echo '<tr><td>' . $message . "</td></tr>\n";
        }
        echo "</table>\n";
      }
      echo tep_draw_form('value_entry', FILENAME_PTYPES, 'action=' . (($action == 'new') ? 'insert' : 'update') . '&vid=' . $vid . '&parent=' . $parent_id . ($confirmation_needed ? '&confirm=yes' : ''), 'post', 'enctype="multipart/form-data"');
      echo '<p>' . ENTRY_ORDER . tep_draw_input_field('sort_order', $value['sort_order']) . "</p>\n";
      foreach ($lang as $id => $info) {
        echo '<p>' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $info['directory'] . '/images/' . $info['image'], $info['name']) . '&nbsp;' .  ENTRY_VALUE . tep_draw_input_field('value' . $id, $descs[$id], 'size=64 maxlength=64') . "</p>\n";
      }
      echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PTYPES, 'list_id=' . $list_id . "&vid=" . $vid) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . "</a>\n"
      ?>
      </form></td></tr>
      <?php } elseif ($action == 'delete') {
        echo '<tr><td><p class="pageHeading">' . HEADING_DELETE . $vid . "<br>\n";
        echo epf_get_ptype_desc_extended($vid) . "</p>\n";
        $affected = epf_get_ptype_children($vid);
        if (sizeof($affected) > 1) {
          echo '<p><b>' . TEXT_SUBTYPES_DELETED . "</b><br>\n";
          foreach ($affected as $id) {
            if ($id != $vid) echo epf_get_ptype_desc_extended($id) . "<br>\n";
          }
          echo "</p>\n";
        }
        $check_query = tep_db_query("select count(products_id) as total from " . TABLE_PRODUCTS . " where products_type in (" . tep_db_input(implode(',', $affected)) . ")");
        $check = tep_db_fetch_array($check_query);
        echo '<p>' . sprintf(TEXT_FIELD_DATA, $check['total']) . "</p>\n";
        $used = $check['total'];
        $linked_fields = array();
        $check_query = tep_db_query("select epf_id from " . TABLE_EPF . " where epf_has_linked_field = 2");
        while ($check = tep_db_fetch_array($check_query)) {
          $linked_fields[] = $check['epf_id'];
        }
        if (!empty($linked_fields)) {
          $check_query = tep_db_query("select count(value_id) as total from " . TABLE_EPF_VALUES . " where epf_id in (" . tep_db_input(implode(',', $linked_fields)) . ") and value_depends_on in (" . tep_db_input(implode(',', $affected)) . ")");
          $check = tep_db_fetch_array($check_query);
          echo '<p>' . sprintf(TEXT_FIELD_DATA2, $check['total']) . "</p>\n";
          $used += $check['total'];
        }
        $check_query = tep_db_query("select epf_ptype_ids from " . TABLE_EPF . " where length(epf_ptype_ids) > 0");
        $epf_requires = 0;
        while ($check = tep_db_fetch_array($check_query)) {
          $is_used = false;
          $types_used = explode('|', $check['epf_ptype_ids']);
          foreach ($affected as $id) {
            if (in_array($id, $types_used)) $is_used = true;
          }
          if ($is_used) $epf_requires++;
        }
        $used += $epf_requires;
        echo '<p>' . sprintf(TEXT_FIELD_DATA3, $epf_requires) . "</p>\n";
        
        if ($double_check == 'no') {
          echo '<p>' . TEXT_ARE_SURE . (sizeof($affected) > 1 ? TEXT_VALUES_GONE : '') . "</p>\n";
          echo '<p>' . tep_draw_form('yes', FILENAME_PTYPES, 'confirm=yes&action=delete&vid=' . $vid . '&used=' . $used) . tep_draw_input_field('yes', TEXT_YES, 'alt="' . TEXT_YES . '"', false, 'submit') . '</form>&nbsp;&nbsp;';
          echo tep_draw_form('no', FILENAME_PTYPES, 'vid=' . $vid) . tep_draw_input_field('no', TEXT_NO, 'alt="' . TEXT_NO . '"', false, 'submit') . "</form></p>\n";
        } else {
          echo '<p><b>' . TEXT_CONFIRM_DELETE . (sizeof($affected) > 1 ? TEXT_VALUES_GONE : '') . "</b></p>\n";
          echo '<p>' . tep_draw_form('yes', FILENAME_PTYPES, 'confirm=yes&action=delete&vid=' . $vid) . tep_draw_input_field('yes', TEXT_YES, 'alt="' . TEXT_YES . '"', false, 'submit') . '</form>&nbsp;&nbsp;';
          echo tep_draw_form('no', FILENAME_PTYPES, 'vid=' . $vid) . tep_draw_input_field('no', TEXT_NO, 'alt="' . TEXT_NO . '"', false, 'submit') . "</form></p>\n";
        }
        echo "</td></tr>\n";
      } else { /* display list of values */?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PARENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
$query = tep_db_query("select * from " . TABLE_PTYPES . " t, " . TABLE_PTYPE_DESC . " d where t.ptype_id = d.ptype_id and d.languages_id = " . (int)$languages_id . " order by t.parent_id, t.sort_order, d.ptype_description");
$selected = array();
while ($value = tep_db_fetch_array($query)) {
  if ($vid == '') $vid = $value['ptype_id'];
  if ($value['ptype_id'] == $vid) {
    echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PTYPES, 'vid=' . $vid . '&action=edit') . '\'">' . "\n";
    $selected = $value;
  } else {
    echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PTYPES, 'vid=' . $value['ptype_id']) . '\'">' . "\n";
  }
?>
                <td class="dataTableContent"><?php echo $value['ptype_id']; ?></td>
                <td class="dataTableContent"><?php echo $value['ptype_description']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $value['parent_id']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $value['sort_order']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($value['value_id'] == $vid) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PTYPES, 'vid=' . $value['value_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
}
?>
              </table>
              <p class="main"><?php echo TEXT_PREVIEW . tep_draw_pull_down_menu('preview', epf_build_ptype_pulldown()); ?></p>
            </td>
<?php // build information box contents
  $heading = array();
  $contents = array();
  if (!empty($selected)) {
    $heading[] = array('text' => TABLE_HEADING_ID . $selected['ptype_id'] . ' ' . $selected['ptype_description']);
    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PTYPES, 'vid=' . $vid . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PTYPES, 'vid=' . $vid . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
    $contents[] = array('align' => 'center', 'text' => tep_draw_form('subtype', FILENAME_PTYPES, 'parent=' . $selected['ptype_id'] . "&action=new" ) . tep_draw_input_field('subtypes', BUTTON_SUBTYPE, 'alt="' . BUTTON_SUBTYPE . '"', false, 'submit') . '</form>');
    if ($selected['parent_id'] > 0)
      $contents[] = array('align' => 'center', 'text' => tep_draw_form('goparent', FILENAME_PTYPES, 'vid=' . $selected['parent_id'] ) . tep_draw_input_field('gotoparent', BUTTON_SELECT_PARENT, 'alt="' . BUTTON_SELECT_PARENT . '"', false, 'submit') . '</form>');
    $query = tep_db_query("select ptype_id from " . TABLE_PTYPES . " where parent_id = " . (int)$selected['ptype_id']);
    $clist = array(array('id' => 0, 'text' => TEXT_NONE));
    while ($val = tep_db_fetch_array($query)) {
      $clist[] = array('id' => $val['ptype_id'], 'text' => epf_get_ptype_desc($val['ptype_id']));
    }
    if (sizeof($clist) > 1) {
      $contents[] = array('text' => TEXT_CHILD . tep_draw_form('subtype', FILENAME_PTYPES, "", 'get') . tep_draw_pull_down_menu('vid', $clist, 0, 'onChange="this.form.submit();" style="font-size: 10px; width: 100px"') . '</form>&nbsp;&nbsp;');
    }
    $contents[] = array('text' => TABLE_HEADING_PARENT . ': ' . $selected['parent_id']);
    $contents[] = array('text' => TABLE_HEADING_ORDER . ': ' . $selected['sort_order']);
    $contents[] = array('text' => ENTRY_VALUE);
    foreach ($lang as $id => $info) {
      $contents[] = array('text' => tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $info['directory'] . '/images/' . $info['image'], $info['name']) . '&nbsp;' . epf_get_ptype_desc($selected['ptype_id'], $id));
    }
    $contents[] = array('text' => TEXT_EXT_DESC);
    foreach ($lang as $id => $info) {
      $contents[] = array('text' => tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $info['directory'] . '/images/' . $info['image'], $info['name']) . '&nbsp;' . epf_get_ptype_desc_extended($selected['ptype_id'], $id));
    }

  }
// display information box if it exists
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
      <?php } ?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
