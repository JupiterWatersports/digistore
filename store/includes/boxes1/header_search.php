<?php
/*
  $Id: search.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
?>
<!-- search //-->
<?php

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get') . tep_draw_hidden_field('search_in_description','1') . tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: 140px"') . '<td valign="middle" style="padding:0px 0px 0px 10px">' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH). '</a></form></td></tr></table></td>');

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- search_eof //-->
