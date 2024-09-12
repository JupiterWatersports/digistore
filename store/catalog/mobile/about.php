<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
$classic_site = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . (tep_not_null(tep_get_all_get_params())? '?' . tep_get_all_get_params(): '');
require(DIR_MOBILE_INCLUDES . 'header.php');
    $headerTitle->write(TEXT_ABOUT);

?>
<!-- about //-->
<div id="iphone_content">
<div id="cms">

<div data-role="controlgroup">

<?php
	echo tep_mobile_selection(tep_mobile_link(FILENAME_SHIPPING), array(BOX_INFORMATION_SHIPPING)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_PRIVACY), array(BOX_INFORMATION_PRIVACY)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_CONDITIONS), array(BOX_INFORMATION_CONDITIONS)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_CONTACT_US), array(BOX_INFORMATION_CONTACT)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_REVIEWS), array(BOX_HEADING_REVIEWS)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';

?>

</div>
      <div id="bouton">
      <?php 
	echo  tep_button_jquery(IMAGE_BUTTON_BACK , tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL'), 'b' , 'button' , 'data-icon="back" data-inline="true"' );
      ?>		
      </div>
</div>

<?php 
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
