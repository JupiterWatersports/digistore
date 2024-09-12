<?php
/*
  $Id: search.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!--start search //-->
          
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SEARCH);

  new cssinfoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'),
                               
                               'text' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'SSL', false), 'get').'<div class="search-box">'.tep_draw_input_field('keywords', '', 'size="10" maxlength="25" class="search-field" value="Search..." onFocus="clearDefault(this)" onBlur="Default(this)"').'<input type="hidden" name="search_in_description" value="1" /><input type="hidden" name="inc_subcat" value="1" />'.tep_hide_session_id() .'<input type="submit" name="search" value="" class="search-go" border="0" width="79" height="25"></div></form><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '">' . BOX_SEARCH_ADVANCED_SEARCH . '</a>');

  new cssinfoBox($info_box_contents);

?>