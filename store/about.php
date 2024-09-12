<?php
  require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'mobile_header.php');
$headerTitle->write(STORE_NAME);
if(AJAX_ENABLED && $curl_installed)
	//include(DIR_WS_CLASSES . 'about_js.php');
?>
<!-- about //-->
<table width="100%" cellpadding="0" cellspacing="0"  class="categories">
<?php
    $info_string = '';

    $infomenuquery = tep_db_query('SELECT si_id, si_sort, si_heading FROM information WHERE language_id = "' . ($languages_id) . '" AND si_sort <>0 ORDER BY si_sort');
    $numrows = tep_db_num_rows($infomenuquery);      
      while ($infomenu = tep_db_fetch_array($infomenuquery)) {
        $info_string .='<li><a href="';
        if (isset($_GET['info_id']) && ($_GET['info_id'] == $infomenu['si_id'])) { 
          echo tep_mobile_selection(tep_href_link('information.php?info_id=' .  $infomenu['si_id']) , array($infomenu['si_heading'] ));   
          } else {
          echo tep_mobile_selection(tep_href_link('information.php?info_id=' .  $infomenu['si_id']), array($infomenu['si_heading'] ));   
          }
   
      }  // while 
         echo tep_mobile_selection(tep_href_link(FILENAME_TRACKING), array(BOX_INFORMATION_TRACKING));
	echo tep_mobile_selection(tep_href_link(FILENAME_CONTACT_US), array(BOX_INFORMATION_CONTACT));
?>
	</table>
</div>
</div>
<!-- about_eof //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
