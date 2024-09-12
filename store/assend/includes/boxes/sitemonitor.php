<?php
/*
  $Id: sitemonitor.php,v 1.00 2006/09/24 by Jack_mcs

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- sitemonitor_bof //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SITEMONITOR,
                     'link'  => tep_href_link(FILENAME_SITEMONITOR_ADMIN, 'selected_box=sitemonitor'));

  if ($selected_box == 'sitemonitor') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SITEMONITOR_ADMIN, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_SITEMONITOR_ADMIN . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SITEMONITOR_CONFIG_SETUP, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_SITEMONITOR_CONFIG_SETUP . '</a>');
 
                                   
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- sitemonitor_eof //-->
