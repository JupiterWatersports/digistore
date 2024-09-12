<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');

  $cart->reset();
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
	<div id="cms">
		<?php echo TEXT_MAIN; ?>
		<div id="bouton">
<?php 
			echo tep_button_jquery(IMAGE_BUTTON_CONTINUE , tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL'), 'b' , 'button' , ' data-icon="arrow-r" data-iconpos="right" data-ajax="false"' );
?>
		</div>
	</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
