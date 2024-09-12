<?php
require_once('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  $process = false;
  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['gender'])) {
        $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    if (isset($HTTP_POST_VARS['newsletter'])) {
      $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if ((is_numeric(tep_date_raw($dob)) == false) || (@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false)) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');

// reset session token
      $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

// restore cart contents
      $cart->restore_contents();

// build the message content
      $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      tep_redirect(tep_mobile_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<?php echo tep_draw_form('create_account', tep_mobile_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process'); ?>
<div id="messageStack">
<?php
  if ($messageStack->size('create_account') > 0) {
  echo $messageStack->output('create_account');
  }
?>
</div>
	<div id="inscription">

      <h1><?php echo CATEGORY_PERSONAL; ?></h1>
	  <div class="obligatoire"><?php echo FORM_REQUIRED_INFORMATION; ?></div><br />
	  
<?php
  if (ACCOUNT_GENDER == 'true') {
		$gender_array[0] = array('id' => false,
					'text' => PULL_DOWN_DEFAULT);        
		$gender_array[1] = array('id' => 'm',
					'text' => MALE);
		$gender_array[2] = array('id' => 'f',
					'text' => FEMALE);        
?>
			
              <label for="gender" ><?php echo ENTRY_GENDER . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_GENDER_TEXT . '</span>': ''); ?></label>
		<?php echo tep_draw_pull_down_menu('gender', $gender_array, '','id="gender" data-theme="a" '); ?>
			<?php
  }
?>
     
			  <label for="firstname" ><?php echo ENTRY_FIRST_NAME . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('firstname','','class="input"'); ?>
		
			
			  <label for="lastname" ><?php echo ENTRY_LAST_NAME . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('lastname'); ?>
			
			  
<?php
  if (ACCOUNT_DOB == 'true') {
?>	
	
              <label for="dob" ><?php echo ENTRY_DATE_OF_BIRTH . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('dob','','','date'); ?>
  
<?php
  }
?>			
              <label for="email_address" ><?php echo ENTRY_EMAIL_ADDRESS . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('email_address','','','email')?>
			
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
			  <h1><?php echo CATEGORY_COMPANY; ?></h1>
              <label for="company" ><?php echo ENTRY_COMPANY . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('company'); ?>
			 
<?php
  }
?>
			
			  <h1><?php echo CATEGORY_ADDRESS; ?></h1>
              <label for="street_address" ><?php echo ENTRY_STREET_ADDRESS . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('street_address'); ?>
			 
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>			
              <label for="suburb" ><?php echo ENTRY_SUBURB . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('suburb'); ?>
			
<?php
  }
?>			
              <label for="postcode" ><?php echo ENTRY_POST_CODE . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('postcode'); ?>
			
			
			  <label for="city" ><?php echo ENTRY_CITY . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_CITY_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('city'); ?>
			
<?php
  if (ACCOUNT_STATE == 'true') {
?>
            
			  <label for="state" ><?php echo ENTRY_STATE . (tep_not_null(ENTRY_STATE_TEXT)? '<span class="inputRequirement">&nbsp;' . ENTRY_STATE_TEXT . '</span>': ''); ?></label>
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array,'','id="state" data-theme="a" ');
      } else {
        echo tep_input_jquery('state');
      }
    } else {
      echo tep_input_jquery('state');
    }
?><?php
  }
?>			
		
              		  <label for="country" ><?php echo ENTRY_COUNTRY . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_get_country_list('country', STORE_COUNTRY,'id="country", data-theme="a"'); ?>
	
			  <label for="telephone" ><?php echo ENTRY_TELEPHONE_NUMBER . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('telephone'); ?>

			  <label for="fax" ><?php echo ENTRY_FAX_NUMBER . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('fax'); ?>

			  <h1><?php echo CATEGORY_PASSWORD; ?></h1>
			  <label for="password" ><?php echo ENTRY_PASSWORD . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_draw_password_field('password','','id="password" input data-theme="a"'); ?>

			  <label for="confirmation" ><?php echo ENTRY_PASSWORD_CONFIRMATION . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_draw_password_field('confirmation','','id="confirmation" input data-theme="a"')?>

		
			  <h1><?php echo CATEGORY_OPTIONS; ?></h1>
<?php
	echo '<fieldset data-role="controlgroup">
        
	'.tep_checkbox_jquery('newsletter',false,'a',1,$option = '' ).'
	<label for="newsletter">'.ENTRY_NEWSLETTER.'</label>
	</fieldset>';
?>
			<div id="bouton">
				<?php 
				echo  tep_button_jquery( IMAGE_BUTTON_CREATE_ACCOUNT , '', 'b' , 'submit' , 'data-iconpos="right" data-icon="check" data-inline="true"' );
				?> 
			</div>
</div>
</form>
<?php
if( JQUERY_MOBILE_VALIDATE == 'True')
require(DIR_MOBILE_INCLUDES. 'form_check.js.php');
else
require(DIR_WS_INCLUDES . 'form_check.js.php');


require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
