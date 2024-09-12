<?php
/*
  $Id: mobile_version.php 187 2010-12-01 11:12:10Z Rob $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_MOBILE_VERSION);

  new infoBoxHeading($info_box_contents, false, false, tep_href_link('mobile_index.php'));

 	 global $mobile_url;
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    	    $mobile_url .= (strpos($mobile_url,'language=') > 0) ? '' : ((strpos($mobile_url,'?') > 0) ? '&language=' . $current_lang_key : '?language=' . $current_lang_key ) ;
  }
  $mobile_version_link = '<a href="' . $mobile_url . '">' . tep_image(DIR_WS_IMAGES . 'icons/iphone_logo.gif') . '</a>' ;

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text' => $mobile_version_link);

  new infoBox($info_box_contents);
?>
            </td>
          </tr>