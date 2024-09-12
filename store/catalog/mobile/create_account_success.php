<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_mobile_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
  } else {
    $origin_href = tep_mobile_link(FILENAME_DEFAULT);
  }
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<div id="cms">
<?php echo TEXT_MOBILE_ACCOUNT_CREATED; ?>
      <div id="bouton">
      <?php 
	echo  tep_button_jquery(IMAGE_BUTTON_CONTINUE , tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL'), 'b' , 'button' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"' );
      ?>		
      </div>

</div>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
