<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
    $navigation->set_snapshot();

// set the link for classic site
$classic_site = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . (tep_not_null(tep_get_all_get_params())? '?' . tep_get_all_get_params(): '');

require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write(IMAGE_BUTTON_SEARCH);
?>
<div id="iphone_content">
<!-- search //-->
<?php
  if ($messageStack->size('search') > 0) {
?>
<div id="messageStack">
<?php echo $messageStack->output('search'); ?>
</div>
<?php
  }
?>
<div id="cms">
<?php echo tep_draw_form('quick_find', tep_mobile_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)) ?>
	<label for="keywords" ><?php echo TEXT_KEYWORDS . ':'; ?></label>
 	<?php echo tep_input_search_jquery('keywords', '',INPUT_SEARCH, 'search'); ?>
	<?php echo tep_button_jquery(IMAGE_BUTTON_SEARCH,'', 'b','submit','data-icon="search" data-inline="false" data-iconpos="right"'); ?>
</form>
<?php
	if (SHOW_MANUFACTURERS_SEARCH_MENU == 'true' || SHOW_CATEGORIES_SEARCH_MENU == 'true' || SHOW_SEARCH_BY_PRICE_RANGE == 'true' || SHOW_SEARCH_BY_DATE_RANGE == 'true') {
		echo tep_button_jquery( IMAGE_BUTTON_ADVANCED_SEARCH, tep_mobile_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL'), 'b' , 'button' , 'data-icon="search" data-inline="false" data-iconpos="right"' );
	}
?>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
