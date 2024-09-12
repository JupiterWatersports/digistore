<?php

/*

  $Id: coupons.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2006 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  require(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();



  $code_chars = array(array('id' => 'mixed', 'text' => '0.9 + a.Z'), array('id' => 'chars', 'text' => 'a...Z'), array('id' => 'digits', 'text' => '0...9'));



  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



  if (tep_not_null($action)) {

    switch ($action) {

      case 'length':

        $length = tep_db_prepare_input($HTTP_GET_VARS['length']);

        $chars = tep_db_prepare_input($HTTP_GET_VARS['chars']);

        if ($length>3 && $length<256) {

          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($length) . "' where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH' limit 1");

        }

        if (MODULE_ORDER_TOTAL_COUPON_CODE_CHARS!=$chars && in_array($chars, array('mixed', 'chars', 'digits'))) {

          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($chars) . "' where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_CODE_CHARS' limit 1");

        }

        tep_redirect(tep_href_link(FILENAME_COUPONS, 'action=new'));

      case 'insert':

        $coupons_code = tep_db_prepare_input($HTTP_POST_VARS['coupons_code']);

        $coupons_min_order = intval(tep_db_prepare_input($HTTP_POST_VARS['coupons_min_order']));

        if ($coupons_code!=tep_db_input($coupons_code) || ctype_graph($coupons_code)==false || $coupons_code=='' || strlen($coupons_code)>32) {

          $messageStack->add(ERROR_COUPON_CODE, 'error');

          $action = 'new';

        } else {

          $check_query = tep_db_query("select coupons_id from " . TABLE_COUPONS . " where coupons_code = '" . tep_db_input($coupons_code) . "'");

          if (tep_db_num_rows($check_query)) {

            $messageStack->add(ERROR_COUPON_EXIST, 'error');

            $action = 'new';

          } else {

            $coupons_value = str_replace(",", ".", tep_db_prepare_input($HTTP_POST_VARS['coupons_value'])) + 0;

            $day = tep_db_prepare_input($HTTP_POST_VARS['day']);

            $month = tep_db_prepare_input($HTTP_POST_VARS['month']);

            $year = tep_db_prepare_input($HTTP_POST_VARS['year']);

            $expires_date = 'now()';

            if (tep_not_null($day) && tep_not_null($month) && tep_not_null($year)) {

              $expires_date = $year;

              $expires_date .= (strlen($month) == 1) ? '0' . $month : $month;

              $expires_date .= (strlen($day) == 1) ? '0' . $day : $day;

              $expires_date = "'" . tep_db_input($expires_date) . "'";

            }

            if ($coupons_value>0) {

              tep_db_query("insert into " . TABLE_COUPONS . " (coupons_code, coupons_value, coupons_notice, coupons_min_order, coupons_date) values ('" . tep_db_input($coupons_code) . "', '" . tep_db_input($coupons_value) . "', '" . tep_db_input($coupons_notice) . "', '" . (int)tep_db_input($coupons_min_order) . "', " . $expires_date . ")");

            }

          tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page']));

          }

        }

        break;

      case 'update':

        $coupons_id = tep_db_prepare_input($HTTP_POST_VARS['coupons_id']);

        $coupons_min_order = intval(tep_db_prepare_input($HTTP_POST_VARS['coupons_min_order']));

        $coupons_value = str_replace(",", ".", tep_db_prepare_input($HTTP_POST_VARS['coupons_value'])) + 0;

		$coupons_notice = $HTTP_POST_VARS['coupons_notice'];

        $day = tep_db_prepare_input($HTTP_POST_VARS['day']);

        $month = tep_db_prepare_input($HTTP_POST_VARS['month']);

        $year = tep_db_prepare_input($HTTP_POST_VARS['year']);

        $expires_date = 'now()';

        if (tep_not_null($day) && tep_not_null($month) && tep_not_null($year)) {

          $expires_date = $year;

          $expires_date .= (strlen($month) == 1) ? '0' . $month : $month;

          $expires_date .= (strlen($day) == 1) ? '0' . $day : $day;

          $expires_date = "'" . tep_db_input($expires_date) . "'";

        }

        if ($coupons_value>0) {

          tep_db_query("update " . TABLE_COUPONS . " set coupons_value = '" . tep_db_input($coupons_value) . "', coupons_notice = '" . tep_db_input($coupons_notice) . "', coupons_min_order = '" . (int)tep_db_input($coupons_min_order) . "', coupons_date = " . $expires_date . " where coupons_id = '" . (int)$coupons_id . "' limit 1");

        }

        tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $coupons_id));

        break;

      case 'deleteconfirm':

        $coupons_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("delete from " . TABLE_COUPONS . " where coupons_id = '" . (int)$coupons_id . "'");

        tep_db_query("optimize table " . TABLE_COUPONS);

        tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page']));

        break;

    }

  }



  $length = array();

  for ($i=4; $i<=32; $i++) $length[] = array('id' => $i, 'text' => $i);

  if (!defined('MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH')) {

    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_title, configuration_description, configuration_group_id, date_added) values ('MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH', '8', '', '', '6', now())");

    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_title, configuration_description, configuration_group_id, date_added) values ('MODULE_ORDER_TOTAL_COUPON_CODE_CHARS', 'mixed', '', '', '6', now())");

    define('MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH', 8);

    define('MODULE_ORDER_TOTAL_COUPON_CODE_CHARS', 'mixed');

  }

?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script type="text/javascript" src="includes/general.js"></script>

<?php

  if ( ($action == 'new') || ($action == 'edit') ) {

?>

<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">

<script type="text/javascript" src="includes/javascript/calendarcode.js"></script>

<?php

  }

?>

</head>

<body>

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

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

          </tr>

        </table></td>

      </tr>

<?php

  if ($action == 'new' || $action == 'edit') {

    if ($action == 'edit' && isset($HTTP_GET_VARS['cID']) ) {

      $form_action = 'update';

      $coupon_query = tep_db_query("select coupons_id, coupons_code, coupons_notice, coupons_value, coupons_min_order, coupons_date from " . TABLE_COUPONS . " where coupons_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");

      $coupon = tep_db_fetch_array($coupon_query);

      $cInfo = new objectInfo($coupon);

    } else {

      $form_action = 'insert';

?>

      <tr>

        <td>

          <form name="codelength" <?php echo 'action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'info', 'cID')), 'NONSSL') . '"'; ?> method="get">

          <table border="0" cellspacing="0" cellpadding="2">

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_CODE_LENGTH; ?>:</td>

              <td><?php echo tep_draw_hidden_field('action', 'length') . tep_draw_pull_down_menu('length', $length, MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH, 'onChange="this.form.submit();"') . ' &nbsp; ' . tep_draw_pull_down_menu('chars', $code_chars, MODULE_ORDER_TOTAL_COUPON_CODE_CHARS, 'onChange="this.form.submit();"');?></td>

            </tr>

          </table>

          </form>

        </td>

      </tr>

<?php

      $cInfo = new objectInfo(array('coupons_code' => coupon_code_generate(MODULE_ORDER_TOTAL_COUPON_CODE_LENGTH, MODULE_ORDER_TOTAL_COUPON_CODE_CHARS)));

    }

?>

      <tr>

        <td>

          <br>

          <form name="new_coupon" <?php echo 'action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'info', 'cID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post">

          <table border="0" cellspacing="0" cellpadding="2">

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_CODE; ?>:</td>

              <td class="main"><?php echo (($form_action == 'update')?tep_draw_hidden_field('coupons_id', $cInfo->coupons_id) . '<b>' . $cInfo->coupons_code . '</b>' : tep_draw_input_field('coupons_code', $cInfo->coupons_code, 'style="width:100%;"')); ?></td>

            </tr>

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_NOTICE; ?>:</td>

              <td class="main"><?php echo tep_draw_input_field('coupons_notice', (isset($cInfo->coupons_notice)?$cInfo->coupons_notice:'')); ?></td>

            </tr>

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_VALUE; ?>:</td>

              <td class="main"><?php echo tep_draw_input_field('coupons_value', (isset($cInfo->coupons_value)?$cInfo->coupons_value:'0')); ?></td>

            </tr>

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_MIN_ORDER; ?>:</td>

              <td class="main"><?php echo tep_draw_input_field('coupons_min_order', (isset($cInfo->coupons_min_order)?$cInfo->coupons_min_order:'0')); ?></td>

            </tr>

            <tr>

              <td width="150" class="main"><?php echo TEXT_COUPONS_DATE; ?>:</td>

              <td class="main"><?php echo tep_draw_input_field('day', (isset($cInfo->coupons_date)?substr($cInfo->coupons_date, 8, 2):''), 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('month', (isset($cInfo->coupons_date) ? substr($cInfo->coupons_date, 5, 2) : ''), 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('year', (isset($cInfo->coupons_date) ? substr($cInfo->coupons_date, 0, 4) : ''), 'size="4" maxlength="4" class="cal-TextBox"'); ?><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_coupon','dteWhen','BTN_date');return false;"><?php echo tep_image(DIR_WS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="absmiddle" name="BTN_date"'); ?></a></td>

            </tr>

            <tr>

              <td>&nbsp;</td>

              <td class="main" align="center" valign="top"><br><?php echo (($form_action == 'insert')?tep_image_submit('button_insert.gif', IMAGE_INSERT):tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['cID'])?'&cID=' . $HTTP_GET_VARS['cID']:'')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>

            </tr>

          </table>

          </form>

        </td>

      </tr>

      <tr>

        <td class="main"><br><?php echo TEXT_COUPONS_TIP; ?></td>

      </tr>

<?php

  } else {

    $sort_array = array('coupons_id', 'coupons_code', 'coupons_value', 'coupons_date');

    $page = (isset($HTTP_GET_VARS['page']) && $HTTP_GET_VARS['page']>0)? $HTTP_GET_VARS['page']:1;

    $sort = (isset($HTTP_GET_VARS['sort']) && in_array($HTTP_GET_VARS['sort'], $sort_array))? $HTTP_GET_VARS['sort']:$sort_array[0];

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr class="dataTableHeadingRow">

                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'sort=coupons_code') . '" title=" ' . TABLE_HEADING_SORT . ' ' . TABLE_HEADING_CODE . ' ">' . TABLE_HEADING_CODE . '</a>'; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'sort=coupons_value') . '" title=" ' . TABLE_HEADING_SORT . ' ' . TABLE_HEADING_VALUE . ' ">' . TABLE_HEADING_VALUE . '</a>'; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'sort=coupons_date') . '" title=" ' . TABLE_HEADING_SORT . ' ' . TABLE_HEADING_DATE . ' ">' . TABLE_HEADING_DATE . '</a>'; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTICE; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MIN_ORDER; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;&nbsp;</td>

              </tr>

<?php

    $coupons_query_raw = "select coupons_id, coupons_code, coupons_notice, coupons_value, coupons_min_order, coupons_date from " . TABLE_COUPONS . " order by " . $sort;

    $coupons_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $coupons_query_raw, $coupons_query_numrows);

    $coupons_query = tep_db_query($coupons_query_raw);

    while ($coupons = tep_db_fetch_array($coupons_query)) {

      if ((isset($HTTP_GET_VARS['cID']) && $coupons['coupons_id']==$HTTP_GET_VARS['cID']) || (!isset($HTTP_GET_VARS['cID']) && $actual==false)) {

        $actual = true;

        $actual_name = $coupons['coupons_code'];

        $actual_date = $coupons['coupons_date'];

      } else {

        $actual = false;

      }

?>

              <tr class="dataTableRow" onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);" onclick="document.location.href='<?php echo tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $coupons['coupons_id']); ?>';">

                <td class="dataTableContent"><?php echo (($actual)?'<b>':'') . $coupons['coupons_code'] . (($actual)?'</b>':''); ?></td>

                <td class="dataTableContent" align="right"><?php echo (($actual)?'<b>':'') . $currencies->format($coupons['coupons_value']) . (($actual)?'</b>':''); ?></td>

                <td class="dataTableContent" align="right"><?php echo (($actual)?'<b>':'') . $coupons['coupons_date'] . (($actual)?'</b>':''); ?></td>

                <td class="dataTableContent" align="right"><?php echo (($actual)?'<b>':'') . $coupons['coupons_notice'] . (($actual)?'</b>':''); ?></td>

                <td class="dataTableContent" align="right"><?php echo (($actual)?'<b>':'') . $currencies->format($coupons['coupons_min_order']) . (($actual)?'</b>':''); ?></td>

                <td class="dataTableContent" align="right"><?php

				echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $coupons['coupons_id'] . '&action=delete') . '">' . tep_image(DIR_WS_IMAGES . 'icons/delete.gif', IMAGE_DELETE) . '</a> &nbsp; ' .

				'<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $coupons['coupons_id'] . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'icon_edit.gif', IMAGE_EDIT) . '</a> &nbsp; ' .

				'<a href="#" onClick="window.open(\''.tep_href_link(FILENAME_COUPONS_STATUS, 'code='.$coupons['coupons_code']).'\', \'anmerkung\', \'width=500, height=300, scrollbars=yes, toolbar=no, resizeable=no\');">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', 'Status') . '</a> &nbsp; ' .

				'<a href="' . tep_href_link(FILENAME_COUPONS_MAIL, 'code=' . $coupons['coupons_code'] . '&wert=' . $currencies->format($coupons['coupons_value']) . '') . '">' . tep_image(DIR_WS_IMAGES . 'send_mail.gif', 'eMail') . '</a>'; ?>&nbsp;&nbsp;</td>

              </tr>

<?php

    }

?>

              <tr>

                <td colspan="6"><table border="0" width="100%" cellpadding="0"cellspacing="2">

                  <tr>

                    <td class="smallText" valign="top"><?php echo $coupons_split->display_count($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?></td>

                    <td class="smallText" align="right"><?php echo $coupons_split->display_links($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>

                  </tr>

<?php

    if (empty($action)) {

?>

                  <tr>

                    <td colspan="2" align="right"><br><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>

                  </tr>

<?php

    }

?>

                </table></td>

              </tr>

            </table></td>

<?php

    $heading = array();

    $contents = array();

    switch ($action) {

      case 'delete':

        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUPONS . '</b>');

        $contents = array('form' => tep_draw_form('coupons', FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=deleteconfirm'));

        $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);

        $contents[] = array('text' => '<br><b>' . $actual_name . ' - ' . $actual_date . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;

      default:

//        $heading[] = array('text' => '');

//        $contents[] = array('align' => 'center', 'text' => tep_draw_input_field('dummy', $actual_name, 'style="width:100%;"'));

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

<?php

  }

?>

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php

require(DIR_WS_INCLUDES . 'application_bottom.php');

  function coupon_code_generate($length=16, $type = 'mixed') {

    if ($length<4 || $length>255) $length = 16;

    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';

    while (strlen($rand_value) < $length) {

      if ($type == 'digits') {

        $char = tep_rand(0,9);

      } else {

        $char = chr(tep_rand(0,255));

      }

      if ($type == 'mixed') {

        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;

      } elseif ($type == 'chars') {

        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;

      } elseif ($type == 'digits') {

        if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;

      }

    }

    return $rand_value;

  }

?>

