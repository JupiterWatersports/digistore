<?php
  /*
  $Id: conditions.php,v 1.3 2001/12/20 14:14:14 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  Mod Created by Craig Harrison
*/
// bof shopinfo - Erzeugen der html-files

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS. '/shopinfo.php');
  require(DIR_WS_LANGUAGES . $language . '/shopinfo.php'  );
 // include('includes/functions/shopinfo.php');
// funktionen für shopinfo
  
  $languages = tep_get_languages();  
  $action = (isset($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : '');
  $si_key = (isset($HTTP_GET_VARS['info']) ? $HTTP_GET_VARS['info'] : 'si-angebot');
  $si_id = (isset($HTTP_GET_VARS['siid']) ? $HTTP_GET_VARS['siid'] : 0);
  $submit = (isset($HTTP_POST_VARS['submit']) ? $HTTP_POST_VARS['submit'] : '');
    switch ($submit) { 
      case "save":
        for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
          $si_headingname = 'si_heading' . $i;
          $si_heading = $$si_headingname;
          $si_contentname = 'si_content' . $i;
          $si_content = $$si_contentname;
          $si_urlname = 'si_url' . $i;
          $si_url = $$si_urlname;
          $si_sortname = 'si_sort' . $i;
          $si_sort = $$si_sortname;
          $si_iframename = 'si_iframe' . $i;
          $si_iframe = $$si_iframename;                 
          $si_id = si_save_set($si_id, $i, $si_name, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_type);              
        } //for
      break;
      case "writehtml":
        for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
          si_writehtml($si_id, $i);
        }
      break;
      case "savenew":
        for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
          $si_headingname = 'si_heading' . $i;
          $si_heading = $$si_headingname;                 
          $si_contentname = 'si_content' . $i;
          $si_content = $$si_contentname;
          $si_urlname = 'si_url' . $i;
          $si_url = $$si_urlname;
          $si_sortname = 'si_sort' . $i;
          $si_sort = $$si_sortname;
          $si_iframename = 'si_iframe' . $i;
          $si_iframe = $$si_iframename;
          $si_id = si_save_new_set($si_id, $i, $si_name, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_type); 
        }
      break;
      case "delete":
        tep_db_query("DELETE FROM information WHERE si_id='" . $si_id . "'");
        $si_id = 0;
        $HTTP_GET_VARS['siid'] = 0;
      break; 
      case "drop":
              //  Do nothing
      break;       
      default: 
      break;
    } //switch
  $si_query = array();
  $si_row = array();  
  for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
    $si_query[$i] = tep_db_query('SELECT si_id, si_sort, si_heading, si_content, si_type, si_url, si_name, si_stamp, si_iframe FROM information WHERE si_id="' . $si_id . '" AND language_id="' . $i . '"');
    $si_row[$i]=tep_db_fetch_array($si_query[$i]);
  } 
?>


<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
<td>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<form name="aboutusform" method="Post" action="<?php echo tep_href_link(basename($PHP_SELF),'siid=' . $si_id . '&selected_box=shopinfo'); ?> ">
<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" id="left"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="columnLeft">
<!-- left_navigation //-->
<?php //require(DIR_WS_INCLUDES . 'column_left.php'); 
?> 
<!-- left_navigation_eof //-->
  
 </table>
    </td>
     <td class="pageHeading" valign="top">
 <br>
<div class="Title"><?php echo BOX_HEADING_SHOPINFO ;?></div>
<br>
<table width="98%" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td height="10" colspan="5" align="left" valign="middle">&nbsp;<br> 
  <?php echo tep_draw_separator('hd_bg.gif', 700, 5); ?><br>&nbsp;
  </td>
</tr>
<tr style="border: 1px; ">
  <td colspan="5" valign="middle"  margin="10px" border="1px">
    <b><?php echo BOX_ALLLANG_SHOPINFO ;?></b><br><br>
    <?php
      for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
        echo '<a href="#intern' . $i . '">'. tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i-1]['directory'] . '/images/' . $languages[$i-1]['image'] , $languages[$i-1]['name']) . '&nbsp;' . $languages[$i-1]['directory'] . '</a>' ;
      }
    ?>  
  </td>
</tr>
<tr>
  <td colspan="2" valign="bottom"><b><?php echo BOX_NAME_SHOPINFO ;?></b>
  </td>
  <td valign="bottom" width="19%" text-align="center"><b><?php echo BOX_STAMP_SHOPINFO ;?></b>
  </td>
  <td valign="bottom" width="19%"><b><?php echo BOX_TYPE_SHOPINFO ;?></b>
  </td>
</tr>
<tr>
  <td valign="middle" colspan="2"><b>
    <?php echo tep_draw_input_field('si_name', $si_row[$languages_id]['si_name']); ?></b>
   </td>
  <td valign="middle"><b>
    <?php echo tep_date_short($si_row[$languages_id]['si_stamp']); ?><br>&nbsp;
  </b></td>
  <td valign="middle"><b>
  <?php echo tep_draw_input_field('si_type', $si_row[$languages_id]['si_type']); ?>
  </b></td>
</tr>
<tr>
  <td height="20" colspan="5" align="left" valign="middle">&nbsp;<br> 
  <?php echo tep_draw_separator('hd_bg.gif', 700, 5); ?><br>&nbsp;
  </td>
</tr>
<tr>
  <td height="20" colspan="5" align="left" valign="middle"><b> 
  <?php echo BOX_SPECLANG_SHOPINFO; ?><br>&nbsp;</b>
  </td>
</tr>

<?php
    for ($i=1, $n=sizeof($languages); $i<=$n; $i++) {
?>
<tr>
  <td margin ="10px" colspan="2"> <b><?php
   echo '<a name="intern' . $i . '"></a>';
   echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i-1]['directory'] . '/images/' . $languages[$i-1]['image'] , $languages[$i-1]['name']) .'&nbsp; '. $languages[$i-1]['name']; ?></b>
  </td>
</tr>
<tr>
  <td valign="bottom" colspan="2">
    <b><?php echo BOX_HEADER_SHOPINFO ;?></b>
  </td>
  <td valign="bottom">
    <b><?php echo BOX_SORT_SHOPINFO ;?></b>
  </td>
</tr>
<tr>
  <td valign="middle"  colspan="2">
    <b><?php 
      $si_headingname = "si_heading" . $i;
      echo tep_draw_input_field($si_headingname, $si_row[$i]['si_heading'] ); 
      ?>
    </b>
  </td>
  <td valign="middle" ><b>
    <?php 
   $si_sortname = "si_sort" . $i;
   echo tep_draw_input_field($si_sortname, $si_row[$i]['si_sort'] ); ?></b>
    </td>  
</tr>
<tr>
  <td colspan="5" width="500px" valign="top"><br><b><?php echo BOX_CONTENT_SHOPINFO ;?></b><br>&nbsp;<?php echo BOX_CONTENTSUB_SHOPINFO ;?> <br>&nbsp;<?php 
   $si_contentname = "si_content" . $i;
   echo tep_draw_fckeditor($si_contentname, '580', '400', $si_row[$i]['si_content'] ); ?>
  </td>
</tr>
<tr>
  <td colspan="4" width="500px" valign="top"><b><?php echo BOX_URL_SHOPINFO ;?><br> 
   <?php   
     $si_urlname = "si_url" . $i;
     echo tep_draw_input_field($si_urlname, $si_row[$i]['si_url'] ); 
   ?>
   </b>  
  </td>
</tr>
<tr>
  <td colspan="4" valign="top"><br> <?php
   $si_iframename = "si_iframe" . $i;
   if ($si_row[$i]['si_iframe'] == 0) {$checked = false;} else {$checked = true;}
   echo tep_draw_checkbox_field($si_iframename, 1, $checked) . '&nbsp;';   
   echo BOX_IFRAME_SHOPINFO ;?>
  </td>
</tr>
<tr>
  <td height="20" colspan="5" align="left" valign="middle">&nbsp;<br> 
  <?php echo tep_draw_separator('hd_bg.gif', 700, 5); ?><br>&nbsp;
  </td>
</tr>
<?php
}
?>
<tr>
  <td colspan="5">&nbsp;
    <input type="hidden" name="info" value="<?php echo $si_key;?>" >  
    <input type="hidden" name="siid" value="<?php echo $si_row[1]['si_id']; ?>" >
    <input type="hidden" name="selected_box" value="shopinfo" >
    </td>
</tr>
<tr>
  <td align="center"><?php echo BOX_DROP_SHOPINFO; ?></td>
  <td align="center"><?php echo BOX_DELETE_SHOPINFO; ?></td>
  <td align="center"><?php echo BOX_SAVENEW_SHOPINFO; ?></td>
  <td align="center"><?php echo BOX_SAVE_SHOPINFO; ?></td>
  <td align="center"><?php echo BOX_WRITEHTML_SHOPINFO; ?></td>
</tr>
<tr>
  <td align="center">    <input type="submit" name="submit2" value="drop" style="width: 70px"> </td>
  <td align="center">
    <input type="submit" name="submit" value="delete" style="width: 70px"> </td>
  <td align="center">
    <input type="submit" name="submit" value="savenew" style="width: 70px"></td>
  <td align="center">
    <input type="submit" name="submit" value="save" style="width: 70px"></td>
  <td align="center">
    <input type="submit" name="submit" value="writehtml" style="width: 70px"></td>
</tr>
<tr>
  <td height="20" colspan="5" align="left" valign="middle">&nbsp;<br> 
  <?php echo tep_draw_separator('hd_bg.gif', 700, 5); ?><br>&nbsp;
  </td>
</tr>
</table>
<br>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>