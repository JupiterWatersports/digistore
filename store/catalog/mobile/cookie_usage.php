<?php
require_once('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_COOKIE_USAGE);

require(DIR_MOBILE_INCLUDES . 'header.php');
    $headerTitle->write(TEXT_ABOUT);

echo '<div id="iphone_content">
<div id="cms">';
 
echo '<h2>' . HEADING_TITLE . ' </h2>'; 
echo TEXT_INFORMATION; 


echo '
<div id="bouton">

'.tep_button_jquery( IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL') , 'b' , 'button' , 'data-inline="true" data-icon="back"' ).'
	
</div>

</div>';

require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
