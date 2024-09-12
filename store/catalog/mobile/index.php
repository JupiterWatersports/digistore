<?php
require_once('includes/application_top.php');
require(DIR_MOBILE_INCLUDES . 'header.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
$headerTitle->write($headerTitleText);
?>
<!-- categories //-->
<div id="iphone_content">
<div id="cms">
  <div id="bouton">
	<?php
	if (!tep_session_is_registered('customer_id')) { 
		echo '<span style="float:left;">' . tep_draw_button(IMAGE_BUTTON_CREATE_ACCOUNT, 'triangle-1-e', tep_mobile_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')) . '</span>' .  
		     '<span style="float:right;">' . tep_draw_button(IMAGE_BUTTON_LOGIN, 'key', tep_mobile_link(FILENAME_LOGIN, '', 'SSL'), 'primary') . '</span>';
	} else { 
		echo tep_draw_button(IMAGE_BUTTON_LOGOFF, 'cancel', tep_mobile_link(FILENAME_LOGOFF, '', 'SSL'), 'primary');
	}

	?>
  </div>
</div>
<div class="cms">			 
	<?php echo TEXT_WELCOME; ?>
</div>
<?php 
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
