<?php
/*
  $Id: admin_notes.php,v 2.2RC2 2008/09/08 11:25:32 Black Jack 21 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

_________________________________________________________________
Admin Notes MODULE for osC Admin
By PopTheTop of www.popthetop.com
Original Code By: Robert Hellemans of www.RuddlesMills.com 
These are LIVE SHOPS - So please, no TEST accounts etc...
We will report you to your ISP if you abuse our websites!

*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADMIN_NOTES);
  
  function tep_set_admin_query_status($contr_id, $status) {
    if ($status == '0') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '0', date_status_change = NULL where contr_id = '" . $contr_id . "'");
    } elseif ($status == '1') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '1', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } elseif ($status == '2') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '2', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } elseif ($status == '3') {
      return tep_db_query("update " . TABLE_ADMIN_NOTES . " set status = '3', date_status_change = now() where contr_id = '" . $contr_id . "'");
    } else {
      return -1;
    }
  }
  switch ($_GET['action']) {
    case 'setflag':
      tep_set_admin_query_status($_GET['id'], $_GET['flag']);
      break;
    case 'insert':
      if ($_POST['category_new'] != '') { tep_db_query("insert into " . TABLE_ADMIN_NOTES_TYPE . " (type_id, type_name, status) values ('1', '" . $_POST['category_new'] . "','1')"); }
      tep_db_query("insert into " . TABLE_ADMIN_NOTES . " (contr_id, category, admin_note, config_comments, note_created, status, last_update) values ('','" . $_POST['file_type_id'] . "','" . $_POST['admin_note_new'] . "','" . $_POST['config_comments'] . "', now(), '2', '" . $_POST['last_update'] . "' )");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, '&sID=' . $contr_id));
      break;

    case 'copy_to':
      $product_query = tep_db_query("select p.contr_id, p.category, p.admin_note, p.config_comments, p.status, p.last_update from " . TABLE_ADMIN_NOTES . " p where p.contr_id = '" . $_GET['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $corrected_admin_note = preg_replace("/[']/", "\'", $product['admin_note']);
      $corrected_config_comments = preg_replace("/[']/", "\'", $product['config_comments']);
      tep_db_query("insert into " . TABLE_ADMIN_NOTES . " (contr_id, category, admin_note, config_comments, note_created, status, last_update) values ('','" . $product['category'] . "','" . $corrected_admin_note . ' ' . TEXT_ADDED_COPY . "','" . $corrected_config_comments . "', now(), '3', '" . $product['last_update'] . "')");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&sID=' . $_GET['sID']));
      break;

    case 'update':
// Custom - Fix date_status_change
		  if ($_POST['date_status_change']) tep_db_query("update " . TABLE_ADMIN_NOTES . " set date_status_change = now() " . " where contr_id = '" . $_POST['contr_id'] . "'");
// Custom - Use $_POST with register_globals = off
 	  tep_db_query("update " . TABLE_ADMIN_NOTES . " set contr_last_modified = now(), status = '" . $_POST['status'] . "', admin_note = '" . $_POST['admin_note_new'] . "', category =  '" . $_POST['file_type_id'] . "', config_comments = '" . $_POST['config_comments'] . "', last_update = '" . $_POST['last_update'] . "' where contr_id = '" . $_POST['contr_id'] . "'");
	  tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_POST['page'] . '&sID=' . $_POST['contr_id'] . '&sort=' . $_POST['sort']));
      break;

    case 'deleteconfirm':
      $contr_id = tep_db_prepare_input($_GET['sID']);
      tep_db_query("delete from " . TABLE_ADMIN_NOTES . " where contr_id = '" . tep_db_input($contr_id) . "'");
      tep_redirect(tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sort=' . $_GET['sort']));
      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
// set sub title
      if ( $_GET['action'] == 'readonly') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_READONLY; }
      if ( $_GET['action'] == 'delete') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_DELETE; }
      if ( $_GET['action'] == 'update') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_EDIT; }
      if ( $_GET['action'] == 'edit') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_EDIT; }
      if ( $_GET['action'] == 'copy_to') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_COPYTO; }
      if ( $_GET['action'] == 'new') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_INSERT; }
      if ( $_GET['action'] == 'setflag') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE_SETFLAG; }
      if ( $_GET['action'] == '') { $HEADING_SUB_TITLE = HEADING_SUB_TITLE; }
?>
</head>
<style>
@media (min-width: 768px){
    .notes-text{width:100%;}
}

    .green-active{color:#1dd943}
    .red-active{color:#ec0000;}
    .yellow-active{color:#ffb848}
    
    .green-inactive, .red-inactive, .yellow-inactive{color:#bbb;}
    .form-group{padding-bottom: 15px;}
</style>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<?php
  require(DIR_WS_INCLUDES . 'template-top.php');
?>
  
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <?php if (!isset($_GET['action'])) { ?>
          
            <td class="pageHeading"><?php echo 'Notes'; ?></td>
            </tr>
            
            <tr>
            <td>Please remember the purpose of this is to make notes for everyone to see. Not to single out one person and make a To Do list for them that could be easily be sent in a text. Please use this for reminders that need to be done and could be possibly forgotten. Or as alternative to Jeremy's favorite notepad on the desk. </td>
			<?php } else { ?>
                <td class="pageHeading" ><?php echo HEADING_TITLE . ': ' . $HEADING_SUB_TITLE; ?> </td>
            <?php } ?>
            
            <td class="pageHeading" align="right">
                <?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
            </td>
<?php 
if ($_GET['action'] != 'new' || $_GET['action'] != 'edit') {
    $file_type_array = array(array('id' => '', 'text' => '- ' . HEADING_TITLE_SELECT_CATEGORY . ' -'));
    $file_type_query = tep_db_query("select type_name from " . TABLE_ADMIN_NOTES_TYPE . " order by type_name");
    while ($file_type_search = tep_db_fetch_array($file_type_query)) {
      $file_type_array[] = array('id' => $file_type_search['type_name'],
                                     'text' => $file_type_search['type_name']);
    }
if (htmlspecialchars(StripSlashes(@$_GET["search"])) == '')
        { $searchquery = 'enter search query'; } else { $searchquery = htmlspecialchars(StripSlashes(@$_GET["search"])) ;}
?>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('search', FILENAME_ADMIN_NOTES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ': ' . tep_draw_input_field('search', $searchquery, "onFocus=\"if (this.value == 'enter search query') { this.value='' }\""); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('search_type', FILENAME_ADMIN_NOTES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo TEXT_SELECT_CATEGORY . ': ' . tep_draw_pull_down_menu('file_type_id', $file_type_array, $file_type_search['category'], 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
<?php } ?>            
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($_GET['action'] == 'new') || ($_GET['action'] == 'edit') ) {

    $file_type_array = array(array('id' => '', 'text' => TEXT_SELECT_CATEGORY));
    $file_type_query = tep_db_query("select type_name from " . TABLE_ADMIN_NOTES_TYPE . " order by type_name");
    while ($file_type = tep_db_fetch_array($file_type_query)) {
      $file_type_array[] = array('id' => $file_type['type_name'],
                                     'text' => $file_type['type_name']);
    }

    $form_action = 'insert';
                
    if ( ($_GET['action'] == 'edit') && ($_GET['sID']) ) {
          $form_action = 'update';
                
      $product_query = tep_db_query("select p.contr_id, p.category, p.admin_note, p.status, p.config_comments, p.last_update from " . TABLE_ADMIN_NOTES . " p where p.contr_id = '" . $_GET['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());
      $contr_array = array();
    }
                        
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var LastUpdate = new ctlSpiffyCalendarBox("LastUpdate", "new_contr", "last_update","btnDate1","<?php echo $sInfo->last_update; ?>",scBTNMODE_CUSTOMBLUE);
</script>

<style>
    .table tr td{vertical-align: middle;}
</style>
  <?php
  // Custom - Initial "Status" setting
  	$initial_status = $sInfo->status;
  // Custom - Fix date_status_change in 1st line of form statement below
 ?>
      <tr><form name="new_contr" <?php echo 'action="' . tep_href_link(FILENAME_ADMIN_NOTES, tep_get_all_get_params(array('action', 'info', 'sID', 'date_status_change')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('contr_id', $_GET['sID']); if($initial_status != $_GET['status']) echo tep_draw_hidden_field('date_status_change', 'true') ?>
       <td><table class="table" >
         <tr class="dataTableRow">
            <td class=""><?php echo TEXT_CATEGORY . ': '; ?></td>
            <td class=""><?php echo tep_draw_pull_down_menu('file_type_id', $file_type_array, $sInfo->category, 'class="form-control" style="width:250px;"'); ?></td>
          </tr>
          <tr class="dataTableRow">
            <td class="">Title:</td>
            <td class=""><?php echo tep_draw_input_field('admin_note_new', $sInfo->admin_note, 'size=50 maxlength=255 class="form-control"'); ?></td>
          </tr>
          <tr class="dataTableRow">
            <td class=""><?php echo TEXT_INFO_TO_REMEMBER . ': '; ?></td>
            <td class="">
              <script language="javascript">LastUpdate.writeControl(); LastUpdate.dateFormat="yyyy-MM-dd";</script>
              <?php echo '&nbsp;(' . TEXT_INFO_TO_REMEMBER_HELP . ')'; ?>
            </td>
          </tr>
          <tr class="dataTableRow">
            <td valign="top" class=""><?php echo TEXT_CONFIG_COMMENTS . ': '; ?></td>
            <td class=""><?php echo tep_draw_textarea_field('config_comments', 'soft', '70', '15', ($sInfo->config_comments), 'class="form-control"') ; ?></td>
          </tr>
<?php
if ( $_GET['action'] == 'edit') {
?>                                        
          <tr class="dataTableRow">
            <td class=""><?php echo TEXT_CONFIG_STATUS . ': '; ?></td>
            <td class="">
              <?php echo tep_draw_input_field('status', $sInfo->status, 'size=2 maxlength=1 class="form-control"'); ?></td>
                  <td class="" valign="middle" style="white-space:nowrap;">0 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_NOTES_ICON_STATUS_RED, 10, 10); ?>&nbsp;<?php echo IMAGE_NOTES_ICON_STATUS_RED; ?></td>
                  <td class="" width="10">&nbsp;</td>
                  <td class="" valign="middle" style="white-space:nowrap;">1 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_NOTES_ICON_STATUS_GREEN, 10, 10); ?>&nbsp;<?php echo IMAGE_NOTES_ICON_STATUS_GREEN; ?></td>
                  <td class="" width="10">&nbsp;</td>
                  <td class="" valign="middle" style="white-space:nowrap;">2 = <?php echo tep_image(DIR_WS_IMAGES . 'icon_status_yellow.gif', IMAGE_NOTES_ICON_STATUS_YELLOW, 10, 10); ?>&nbsp;<?php echo IMAGE_NOTES_ICON_STATUS_YELLOW; ?></td>
                  
            
          </tr>
<?php } ?>          
       </table>
           
     <?php echo '      
        <div class="column-12">
            <div class="row">
                <div class="column-auto">
                     '.(($form_action == 'insert') ? '<button type="save" class="btn btn-primary btn-sm"><i class="fa fa-save" style="margin-right:5px;"></i>Save </button>' : '<button type="save" class="btn btn-primary btn-sm"><i class="fa fa-save" style="margin-right:5px;"></i>Update </button>').'   
                </div>
                <div class="column-auto">
                    <a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $_GET['sID']) . '&sort=' . $_GET['sort'] . '"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel </button></a> 
                </div>
                <div class="column-auto">
                    <a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'sID=' . $_GET['sID']) . '&action=' . $_GET['action'] . '" onclick="reset();"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-refresh" style="margin-right:5px;"></i>Reset </button></a>   
                </div>
                
            </div>
        </div>';
     ?>
      </form>
      
<?php
  } elseif ($_GET['action'] != 'readonly') {
?>

             <table class="table table-hover">
                 <thead>
                 <tr class="dataTableHeadingRow">
                  <th class="dataTableHeadingContent" align="left" width="40%"><?php echo TABLE_HEADING_NAME; ?></th>
                  <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></th>
                  <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_REMINDER; ?></th>
                  <th class="dataTableHeadingContent"><?php echo 'Added'; ?></th>
                  <th class="dataTableHeadingContent"><?php echo 'Last Changed'; ?></th>
                  <th class="dataTableHeadingContent" align="middle"><?php echo TABLE_HEADING_ACTION; ?></th>
               
                </tr>
               </thead>
<?php
          switch ($sort) {
              case "2a":
               $order_it_by = "admin_note, status ";
               break;
              case "2d":
               $order_it_by = "admin_note DESC, status ";
               break;
              case "4a":
               $order_it_by = "status , admin_note";
               break;
              case "4d":
               $order_it_by = "status DESC, admin_note";
               break;
              case "5a":
               $order_it_by = "last_update , admin_note";
               break;
              case "5d":
               $order_it_by = "last_update DESC, admin_note";
               break;
              default:
               $order_it_by = "status , admin_note";
                  }
    if ($_GET['search']) {
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " where admin_note like '%" . $_GET['search'] . "%' or category like '%" . $_GET['search'] . "%' order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);            
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="" align="left">' . TEXT_NO_DATA . '</td>';
    } elseif ($_GET['file_type_id']) {
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " where category = '" . $_GET['file_type_id'] . "' order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="">' . TEXT_EMPTY_CATEGORY . '</td>';
    } else {            
    $admin_query_query_raw = "select contr_id, admin_note, category, status, note_created , contr_last_modified , date_status_change , last_update from " . TABLE_ADMIN_NOTES . " order by " . $order_it_by . " ";
    $admin_query_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $admin_query_query_raw, $admin_query_query_numrows);
    $admin_query_query = tep_db_query($admin_query_query_raw);
    if (tep_db_num_rows($admin_query_query) == 0) echo '<td  class="">' . TEXT_EMPTY_DATABASE . '</td>';
    }
    while ($admin_quer = tep_db_fetch_array($admin_query_query)) {
      if ( ((!$_GET['sID']) || ($_GET['sID'] == $admin_quer['contr_id'])) && (!$sInfo) ) {
// Fix bug 2006-08-21
//        $sInfo_array = array_merge($admin_quer, '');
	  $sInfo_array = array_merge($admin_quer, (array)'');
// Fix bug 2006-08-21
        $sInfo = new objectInfo($sInfo_array);
      }
if ($_GET['search'] == '') {
      if ( (is_object($sInfo)) && ($admin_quer['contr_id'] == $sInfo->contr_id)) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $_GET['sort'] . '&action=readonly') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id']) . '&sort=' . $_GET['sort'] . '\'">' . "\n"; }
} else {
    $searchresult_query_query_raw = "select contr_id from " . TABLE_ADMIN_NOTES . " order by " . $order_it_by . " ";
    $searchresult_query_query = tep_db_query($searchresult_query_query_raw);
    $searchresult_page= round($searchresult_query_query_numrows / MAX_DISPLAY_SEARCH_RESULTS)-1;

      if ( (is_object($sInfo)) && ($admin_quer['contr_id'] == $sInfo->contr_id)) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $_GET['sort'] . '&action=readonly') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $searchresult_page . '&sID=' . $admin_quer['contr_id']) . '&sort=' . $_GET['sort'] . '\'">' . "\n"; }
}
?>

                <td  class=""><?php echo '<a name="' . $admin_quer['admin_note'] . '" title="' . $admin_quer['category'] . '">' . $admin_quer['admin_note']; ?></a></td>                                                                
                <td  class="">
<?php
      if ($admin_quer['status'] == '0') {
          echo '<i class="fa fa-circle red-active"></i>
                <a style="margin-left:15px;" href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle green-inactive"></i>
                </a>
                <a style="margin-left:15px;" href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle yellow-inactive"></i></a>';
      } elseif ($admin_quer['status'] == '1') {
          echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle red-inactive"></i></a>
         <i style="margin-left:15px;" class="fa fa-circle green-active"></i>
          <a style="margin-left:15px;" href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle yellow-inactive"></i></a>';
      } elseif ($admin_quer['status'] == '2') {
          echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle red-inactive"></i></a>
          <a style="margin-left:15px;" href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '"><i class="fa fa-circle green-inactive"></i></a>
          <i style="margin-left:15px;" class="fa fa-circle yellow-active"></i>';
      } else {
                if ($admin_quer['status'] == '3')  {
                       echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=0&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_NOTES_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=1&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_NOTES_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=setflag&flag=2&id=' . $admin_quer['contr_id'] . '&page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', IMAGE_NOTES_ICON_STATUS_YELLOW_LIGHT, 10, 10) . '</a>';
                }
      }
?></td>
                <td class=""><?php $now_time = time(); $reminder_time = ($now_time - strtotime($admin_quer['last_update'])); if ($reminder_time > 1) { echo '<font color="#ff0000">' . tep_date_short($admin_quer['last_update']) . '</font>'; } else { echo tep_date_short($admin_quer['last_update']); } ?></td>
                <td class="" align="right"><?php echo tep_date_short($sInfo->note_created); ?></td>
                <td class="" align="right"><?php if ($sInfo->contr_last_modified != '0000-00-00 00:00:00') echo tep_date_short($sInfo->contr_last_modified); ?></td>
                <td class="" align="right">
				<?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'] . '&sort=' . $_GET['sort'] . '&action=readonly').'"  data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info">'.'<i class="fa fa-eye">'.'</i>'.'</a>'; ?>
          		<?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'] . '&sort=' . $_GET['sort'] . '&action=edit') .'" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary" style="background-color:#FFB848;">'.'<i class="fa fa-pencil">'.'</i>'.'</a>'; ?>
                <?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id']. '&sort=' . $_GET['sort'] . '&action=copy_to') .'" data-toggle="tooltip" data-placement="top" title="Copy" class="btn btn-primary">'.'<i class="fa fa-files-o"">'.'</i>'.'</a>'; ?>
                <?php echo '<a  href="' .  tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $admin_quer['contr_id'] . '&sort=' . $_GET['sort'] . '&action=delete') .'" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger" >'.'<i class="fa fa-trash-o">'.'</i>'.'</a>'; ?>
				</td>
                
      </tr>
      
    <script>
function myFunction(id) {
    var page = <?php echo $_GET['page']; ?>;
    var sID = id;
   // var sort = <?php echo $_GET['sort']; ?>;
     
    if(confirm("Delete this Note?")){
        location.href = 'admin_notes.php?page='+page+'&sID='+sID+'&action=delete';
        alert('hi');
    } else {
        
    }
    
}
</script>
<?php
    }
?>
             <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $admin_query_split->display_count($admin_query_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_RECORDS); ?></td>
                    <td class="smallText" align="right"><?php echo $admin_query_split->display_links($admin_query_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], 'sID=' . $sInfo->contr_id . '&sort=' . $_GET['sort']); ?></td>
                  </tr>
<?php
  if (($_GET['action'] != 'edit') && ($_GET['action'] != 'delete')) {
?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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

  switch ($_GET['action']) {

    case 'delete':
      $heading[] = array('align' => 'center', 'text' => '<b>' . TEXT_INFO_HEADING_DELETE . '</b>');
      $contents = array('form' => tep_draw_form('install_contr_del', FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $_GET['sort'] . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br>' . TABLE_HEADING_NAME . ': <b>' . $sInfo->admin_note . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>
      <div class="column-12">
        <div class="row">
      <div class="column-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-trash" style="margin-right:10px;"></i>Delete</button></div>
      <div class="column-auto"><a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->contr_id) . '&sort=' . $_GET['sort'] . '">
      <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-times" style="margin-right:10px;"></i>Cancel</button></a></div>
      </div>
      </div>');
      break;
    
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";
    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
  }
} elseif ($_GET['action'] == 'readonly') {
// BOF readonly
      $product_query = tep_db_query("select contr_id, category, admin_note, status, config_comments, date_status_change, note_created, contr_last_modified, last_update from " . TABLE_ADMIN_NOTES . " where contr_id = '" . $_GET['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $sInfo = new objectInfo($product);
      if ($sInfo->status == '0') $status_desc = IMAGE_NOTES_ICON_STATUS_RED;
      if ($sInfo->status == '1') $status_desc = IMAGE_NOTES_ICON_STATUS_GREEN;      
      if ($sInfo->status == '2') $status_desc = IMAGE_NOTES_ICON_STATUS_YELLOW;
?>
      <tr>
        <td>
          <table width="100%" cellspacing="0" cellpadding="0" class="formArea">
            <tr>
              <div class="column-12" style="border: 1px solid #ccc; padding: 15px; background: #fff;">
                  <div class="row">
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b>Title:</b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php echo $sInfo->admin_note; ?></div>
                      
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_CATEGORY . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php echo $sInfo->category; ?></div>
                            
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_CONFIG_COMMENTS . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php echo nl2br($sInfo->config_comments); ?></div>
                      
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_INFO_TO_REMEMBER . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php echo tep_date_short($sInfo->last_update); ?></div>
                      
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_INFO_DATE_ADDED . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php echo tep_date_short($sInfo->note_created); ?></div>
                      
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_INFO_LAST_MODIFIED . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php if ($sInfo->contr_last_modified != '0000-00-00 00:00:00') echo tep_date_short($sInfo->contr_last_modified); ?></div>
                      
                      <div class="column-3 form-group" style="border-bottom:1px dashed;"><b><?php echo TEXT_INFO_STATUS . ': '; ?></b></div>
                      <div class="column-9 form-group" style="border-bottom:1px dashed;"><?php if ($sInfo->status == '0') { 
          echo '<i class="fa fa-circle red-active"></i>' . '&nbsp;' . '(' . $status_desc . ')'; 
      } if ($sInfo->status == '1') { 
          echo '<i class="fa fa-circle green-active"></i>' . '&nbsp;' . '(' . $status_desc . ')'; 
      } if ($sInfo->status == '2') { 
          echo '<i class="fa fa-circle yellow-active"></i>' . '&nbsp;' . '(' . $status_desc . ')'; } ?></div>
                  
                  <div class="column-3 form-group"><b><?php echo TEXT_INFO_STATUS_CHANGE . ': '; ?></b></div>
                  <div class="column-9 form-group"><?php if ($sInfo->date_status_change != '0000-00-00 00:00:00') echo tep_date_short($sInfo->date_status_change); ?></div>
                  </div>
                  </div>
                  </table></td>
            </tr>
          </table>

<div class="column-12" style="margin-top:15px;">
    <?php echo '<a href="' . tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->contr_id . '&sort=' . $_GET['sort'], 'NONSSL') . '"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-left" style="margin-right:5px;"></i>Go Back </button></a>'; ?>
</div>
       
<?php          
} // EOF readonly         
?>

    
<!-- body_text_eof //-->

<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
