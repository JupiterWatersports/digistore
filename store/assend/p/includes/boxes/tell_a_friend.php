<?php
/*
  $Id: tell_a_friend.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<!--start tell_a_friend -->
          
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_TELL_A_FRIEND);

  new cssinfoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'),
                           //    'align' => 'center',
                               'text' => tep_draw_input_field('to_email_address', 'enter email address', 'size="15"') . '<div class="divider-short"></div><p>' . tep_draw_separator('pixel_trans.gif', '10', '1').tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH). tep_draw_hidden_field('products_id', $HTTP_GET_VARS['products_id']) . tep_hide_session_id() . '</p><div class="divider-short"></div>' . BOX_TELL_A_FRIEND_TEXT);

  new cssinfoBox($info_box_contents);
?>