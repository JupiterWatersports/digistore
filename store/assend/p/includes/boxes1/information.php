<?php
/*
  $Id: information.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

   
    $info_string = '';

    $infomenuquery = tep_db_query('SELECT si_id, si_sort, si_heading FROM information WHERE language_id = "' . ($languages_id) . '" AND si_sort <>0 ORDER BY si_sort');
    $numrows = tep_db_num_rows($infomenuquery);      
      while ($infomenu = tep_db_fetch_array($infomenuquery)) {
        $info_string .='<a href="';
        if (isset($HTTP_GET_VARS['info_id']) && ($HTTP_GET_VARS['info_id'] == $infomenu['si_id'])) { 
          $info_string .= tep_href_link('information.php','info_id=' .  $infomenu['si_id'])  . '"  class="menuBoxLinkActive">' . $infomenu['si_heading'] . '</a><br />';  
          } else {
          $info_string .= tep_href_link('information.php','info_id=' .  $infomenu['si_id'])  . '"  class="menuBoxLink">' . $infomenu['si_heading'] . '</a><br />';  
          }
   
      }  // while 
    $info_string .= '<a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '">' . BOX_INFORMATION_CONTACT . '</a><br /><a href="' . tep_href_link(FILENAME_SITEMAP, '', 'NONSSL') . '">' . BOX_INFORMATION_SITEMAP . '</a><br /><a href="' . tep_href_link(FILENAME_TRACKING, '', 'NONSSL') . '">' . BOX_INFORMATION_TRACKING . '</a><br />';

?>
<!-- shopinfo //-->
          <tr>
            <td>
<?php
  $info_header = array();
  $info_header[] = array('text' => BOX_HEADING_INFORMATION,
                         'link' => tep_href_link('Information.php') );

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $info_string );
  new infoBoxHeading($info_header, false, false);
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->
