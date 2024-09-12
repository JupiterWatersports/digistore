<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);

    $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);

      $new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($new_password);

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_db_input($crypted_password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");

      tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $new_password), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      $messageStack->add_session('login', SUCCESS_PASSWORD_SENT, 'success');

      tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_mobile_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));
  require(DIR_MOBILE_INCLUDES . 'header.php');
  $headerTitle->write();
  ?>
<!-- header_eof //-->
<div id="iphone_content">
<!-- body //-->
    <?php echo tep_draw_form('password_forgotten', tep_mobile_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL'));
 	 if ($messageStack->size('password_forgotten') > 0) {
 	 	 echo '<div id="messageStack">' . $messageStack->output('password_forgotten') . '</div>'; 
	  }
	  ?>
	  
	  <div id="returning_cust">
                <?php echo TEXT_MAIN; ?>
				<br /><br />
                <label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
				<?php echo tep_input_jquery('email_address'); ?>
				<br />
		<div id="bouton">
			<?php 
			echo  tep_button_jquery( IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_LOGIN, '', 'NONSSL'), 'b', 'button', 'data-icon="back" data-inline="true"');
			echo  tep_button_jquery( IMAGE_BUTTON_CONTINUE, '', 'b', 'submit', 'data-icon="arrow-r" data-iconpos="right" data-inline="true" data-ajax="false"');
			?>		
		</div>
	</div>
</form>
<?php
require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
