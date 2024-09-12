<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SSL_CHECK);
$breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_SSL_CHECK));

require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<div class="cms">
<h1><?php echo BOX_INFORMATION_HEADING; ?></h1><br/>
<?php echo BOX_INFORMATION; ?>
</div>
<div class="cms">
<?php echo TEXT_INFORMATION; ?>
<div class="bouton">
<?php echo tep_button_jquery(IMAGE_BUTTON_BACK,'#','b','button','data-rel="back" data-icon="back"'); ?>  
</div>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
