<?php
/*
  $Id: theme_boxes.php,v 1.4 2002/03/16 00:20:11 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_BOXES,
                     'link'  => tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=1', 'NONSSL') . '" class="menuBoxContentLink');


   
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_eof //-->
