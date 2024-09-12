<?php
require_once('includes/application_top.php');
require(DIR_MOBILE_INCLUDES . 'header.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRIVACY);
  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_PRIVACY));
	$headerTitle->write();
?>
<div id="iphone_content">

<div id="cms">
<?php echo TEXT_INFORMATION; ?>


<div id="bouton">
	<?php 
	 echo  tep_button_jquery( IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_ABOUT, '', 'NONSSL') , 'b' , 'button' , 'data-inline="true" data-icon="back"' );
	?>		
</div>
</div>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
