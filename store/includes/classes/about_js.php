<?php
require_once(DIR_WS_CLASSES . 'common_js.php');

class aboutJS extends commonJS{
    
    function buildXML() {
    	$cat = $this->createMainCategory();

    $info_string = '';

    $infomenuquery = tep_db_query('SELECT si_id, si_sort, si_heading FROM information WHERE language_id = "' . ($languages_id) . '" AND si_sort <>0 ORDER BY si_sort');
    $numrows = tep_db_num_rows($infomenuquery);   
      $i=1;
      while ($infomenu = tep_db_fetch_array($infomenuquery)) {
      
        if (isset($_GET['info_id']) && ($_GET['info_id'] == $infomenu['si_id'])) { 
	   $cat->append_child($this->nodeFromFile($i,array($infomenu['si_heading']),tep_href_link('information.php?info_id=' .  $infomenu['si_id'])));  
          } else {
          $cat->append_child($this->nodeFromFile($i,$infomenu['si_heading'],tep_href_link('information.php?info_id=' .  $infomenu['si_id'])));  
          }
	$i=$i+1;
      }  // while 
       $i=$i+1;
	 $cat->append_child($this->nodeFromFile($i,BOX_INFORMATION_TRACKING,tep_href_link(FILENAME_TRACKING)));
       $i=$i+1;
	 $cat->append_child($this->nodeFromFile($i,BOX_INFORMATION_CONTACT,tep_href_link(FILENAME_CONTACT_US)));


    }
}
?>

<script language="javascript" src="<?php echo DIR_WS_INCLUDES; ?>categories.js"></script>
<script type="text/javascript">
<!--
var mobile_img_dir = '<?php echo DIR_WS_IMAGES; ?>';
var catNav = new CategoriesNavigator('<?php echo FILENAME_ABOUT; ?>','<?php $categoriesJS = new aboutJS(); echo str_replace("\n",'',$categoriesJS->getText()); ?>');
function onWindowLoad(){
	catNav.run();
}
window.onload=onWindowLoad;
//-->
</script>
