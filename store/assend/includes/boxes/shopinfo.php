<?php
/*
  $Id: tools.php,v 1.21 2003/07/09 01:18:53 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shopinfo //-->
          <tr>
            <td valign="top">
<?php
   $infomenuquery = tep_db_query('SELECT si_id, si_heading FROM information WHERE language_id = ' . $languages_id  . ' ORDER BY si_sort');
    $numrows = tep_db_num_rows($infomenuquery);

  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SHOPINFO,
                     'link'  => tep_href_link('shopinfo.php', tep_get_all_get_params(array('selected_box')) . 'selected_box=shopinfo'));

  if ($selected_box == 'shopinfo') {
      while ($infomenu = tep_db_fetch_array($infomenuquery)) {
          $info_string .='<a href="';
          $info_string .= tep_href_link('shopinfo.php', '&siid=' . $infomenu['si_id'] . '&selected_box=shopinfo') . '" class="menuBoxContentLink">' . $infomenu['si_heading'] . '</a><br>';  
        
      }  // while 
       $contents[] = array('text' => $info_string );
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
