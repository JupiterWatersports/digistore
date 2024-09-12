<?php
require_once('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {
    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);

    if (tep_validate_email($email_address)) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      tep_redirect(tep_mobile_link(FILENAME_CONTACT_US, 'action=success'));
    } else {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  }

$breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_CONTACT_US));
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<div id="messageStack">
<?php
  if ($messageStack->size('contact') > 0) {
?>
                <?php echo $messageStack->output('contact'); ?>
<?php
  }
?>
</div>
<div id="contactForm">
<?php
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) { ?>
         <?php echo TEXT_SUCCESS; ?>
         <div id="bouton">
	         <?php echo  tep_button_jquery( IMAGE_BUTTON_CONTINUE ,tep_mobile_link(FILENAME_DEFAULT), 'b' , 'button' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"' ); ?>
         </div>
         <?php
  } else {
         echo tep_draw_form('contact_us', tep_mobile_link(FILENAME_CONTACT_US, 'action=send')); ?>
         <label for="name" ><?php echo ENTRY_NAME; ?></label>	  
         <?php echo tep_input_jquery('name'); ?>
	 
	  <label for="email" ><?php echo ENTRY_EMAIL; ?></label>
	  <?php echo tep_input_jquery('email'); ?>

	  <label for="enquiry" ><?php echo ENTRY_ENQUIRY; ?></label>
	  <?php echo tep_draw_textarea_field('enquiry', '', 30, 5,'','id="enquiry" data-theme="a"'); ?>
	

	  <div id="bouton">
	  <?php 
	  
	  echo  tep_button_jquery( IMAGE_BUTTON_CONTINUE , '#', 'b' , 'submit' , 'data-icon="arrow-r" data-iconpos="right" data-inline="true"');
          ?>
	  </div>
	  </form>
<?php
  }
?>
	  
	  <br /><br />
	  <?php echo STORE_OWNER . '&nbsp;' . STORE_NAME_ADDRESS; ?>
</div>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
