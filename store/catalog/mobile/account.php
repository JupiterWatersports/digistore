<?php
require_once('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);
  
  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'));
  
  require(DIR_MOBILE_INCLUDES . 'header.php');
  $headerTitle->write(NAVBAR_TITLE);
?>
<div id="iphone_content">
	<div id="cms">
		<div data-role="controlgroup">
<?php    
		echo tep_mobile_selection(tep_mobile_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'), array(TEXT_MY_ORDERS)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), array(HEADER_TITLE_MY_ACCOUNT)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_ADDRESS_BOOK), array(IMAGE_BUTTON_ADDRESS_BOOK)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_ACCOUNT_PASSWORD), array(CATEGORY_PASSWORD)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_ACCOUNT_NEWSLETTERS), array(EMAIL_NOTIFICATIONS_NEWSLETTERS)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_ACCOUNT_NOTIFICATIONS), array(EMAIL_NOTIFICATIONS_PRODUCTS)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';
	echo tep_mobile_selection(tep_mobile_link(FILENAME_LOGOFF), array(HEADER_TITLE_LOGOFF)).'<div class="fleche"><img src="' . DIR_MOBILE_IMAGES . 'arrow_select.png" /></div>';

?>    
		</div>
	</div>
<?php 
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
