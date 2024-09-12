<?php
/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');
// BOF Anti Robot Registration v3.0
  if (ACCOUNT_CREATE_VALIDATION == 'true') {
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_VALIDATION);
    include_once('includes/functions/' . FILENAME_ACCOUNT_VALIDATION);
  }
// EOF Anti Robot Registration v3.0
 // +Country-State Selector
require(DIR_WS_FUNCTIONS . 'ajax.php');
  if (isset($HTTP_POST_VARS['action']) && $HTTP_POST_VARS['action'] == 'getStates' && isset($HTTP_POST_VARS['country'])) {
ajax_get_zones_html(tep_db_prepare_input($HTTP_POST_VARS['country']), true);
} else {

// -Country-State Selector


// PWA EOF

  if (isset($HTTP_GET_VARS['guest']) && $cart->count_contents() < 1) tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
// PWA BOF
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
// BOF Anti Robot Registration v3.0
  if (ACCOUNT_CREATE_VALIDATION == 'true') {
    $antirobotreg = tep_db_prepare_input($HTTP_POST_VARS['antirobotreg']);
  }
// EOF Anti Robot Registration v3.0


    $error = false;



//-----   BEGINNING OF ADDITION: MATC   -----// 

	if (tep_db_prepare_input($HTTP_POST_VARS['TermsAgree']) != 'true' and MATC_AT_REGISTER != 'false') {

        $error = true;

        $messageStack->add('create_account', MATC_ERROR);

    }

//-----   END OF ADDITION: MATC   -----//



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
      if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
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

      // PWA BOF 2b

      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and guest_account != '1'");

      // PWA EOF 2b

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
      // +Country-State Selector
      if ($zone_id == 0) {
      // -Country-State Selector

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
// BOF Anti Robotic Registration v3.0
    $validated = $_POST['validated'];
    if (ACCOUNT_CREATE_VALIDATION == 'true') {
      include(DIR_WS_MODULES . FILENAME_CHECK_VALIDATION);
      if ($entry_antirobotreg_error == true) $messageStack->add('create_account', $text_antirobotreg_error);
    }
// EOF Anti Robotic Registration v3.0


// PWA BOF

    if (!isset($HTTP_GET_VARS['guest']) && !isset($HTTP_POST_VARS['guest'])) {

// PWA EOF



    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);

	    }

// PWA BOF

} 

// PWA EOF



    if ($error == false) {

		// PWA BOF 2b

		if (!isset($HTTP_GET_VARS['guest']) && !isset($HTTP_POST_VARS['guest']))

		{

			$dbPass = tep_encrypt_password($password);

			$guestaccount = '0';

		}else{

			$dbPass = 'null';

			$guestaccount = '1';

		}

		// PWA EOF 2b

      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,

                              // PWA BOF 2b

                              'customers_password' => $dbPass,

                              'guest_account' => $guestaccount);

                              // PWA EOF 2b



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



// PWA BOF

     if (isset($HTTP_GET_VARS['guest']) or isset($HTTP_POST_VARS['guest']))

       tep_session_register('customer_is_guest');

// PWA EOF



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



// PWA BOF

      if (isset($HTTP_GET_VARS['guest']) or isset($HTTP_POST_VARS['guest'])) tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));

// PWA EOF



// restore cart contents
      $cart->restore_contents();



// BEGIN SEND HTML MAIL//



$name = $firstname . " " . $lastname;

$Varlogo = ' '.VARLOGO.' ' ;



$Vartable1 = ' '.VARTABLE1.' '  ;

$Vartable2 = ' '.VARTABLE2.' '  ;

$Vartextmail = EMAILWELCOME . EMAILTEXT . EMAILCONTACT . EMAILWARNING;

$Vartrcolor = ' '. TRCOLOR . '  ' ;

$Varmailfooter = '  ' . EMAIL_TEXT_FOOTER . ' <br /><br /> '  ;



      if (ACCOUNT_GENDER == 'true') {

       if ($HTTP_POST_VARS['gender'] == 'm') {

         $Vargendertext = EMAILGREET_MR;

         } else {

         $Vargendertext = EMAILGREET_MS;

       }

      } else {

      $Vargendertext = EMAILGREET_NONE;

    }





require(DIR_WS_MODULES . 'email/html_create_account.php');

$email_text = $html_email_text ;



if (EMAIL_USE_HTML == 'true') {



$email_text;



} 



else



 {



if (ACCOUNT_GENDER == 'true') {

       if ($HTTP_POST_VARS['gender'] == 'm') {

         $email_text = EMAILGREET_MR;

       } else {

         $email_text = EMAILGREET_MS;

       }

    } else {

      $email_text = EMAILGREET_NONE;

    }



$email_text .=  EMAILWELCOME . "\n\n" . EMAILTEXT ."\n\n" . EMAILCONTACT . 

                EMAIL_TEXT_FOOTER . "\n\n\n" .

                EMAIL_SEPARATOR . "\n" .

                EMAILWARNING . "\n\n" ;

$email_text .=  HTTP_SERVER . DIR_WS_CATALOG . "\n" . 

                EMAIL_TEXT_FOOTERR . "\n" ; 

    

  }





//END SEND HTML EMAIL//



// Skips create account success - Begin	  

 //*******start mail manager**************//
if (file_exists(DIR_WS_MODULES.'mail_manager/create_account.php')){
include(DIR_WS_MODULES.'mail_manager/create_account.php');
}else{
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
//*******end mail manager****************// 
					   // Add MailChimp Subscription
if (MAILCHIMP_ENABLE == 'true') {
  require DIR_WS_FUNCTIONS . 'mailchimp_functions.php';
  mc_add_email($email_address, $email_format);
} // end if 

// BOF: MOD - Separate Pricing Per Customer: alert shop owner of account created by a company
// if you would like to have an email when either a company name has been entered in
// the appropriate field or a tax id number, or both then uncomment the next line and comment the default
// setting: only email when a tax_id number has been given
//    if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) { 
      if ( ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) { 
        $alert_email_text = "Please note that " . $firstname . " " . $lastname . " of the company: " . $company . " has created an account.";
        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Company account created', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
// EOF: MOD - Separate Pricing Per Customer: alert shop owner of account created by a company



            if ($cart->count_contents() == 0) {

      tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));

      }

      else {

          tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

    }

   }

  }

// Skips create account success - End





 // PWA BOF

 if (!isset($HTTP_GET_VARS['guest']) && !isset($HTTP_POST_VARS['guest'])){

 // +Country-State Selector 
 if (!isset($country)) $country = DEFAULT_COUNTRY;
 // -Country-State Selector

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
 }else{

   $breadcrumb->add(NAVBAR_TITLE_PWA, tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL'));
 }
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("cache-Control: no-cache");
  header("cache-control: no-store");
  header("pragma: no-cache");
// PWA EOF

echo $doctype;
?><html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title>Create Account</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<?php require('includes/form_check.js.php'); 
require(DIR_WS_INCLUDES . 'ajax.js.php');
?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, (isset($HTTP_GET_VARS['guest'])? 'guest=guest':''), 'SSL'), 'post', 'onsubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process'); ?>  <div id="indicator"> <?php echo tep_image(DIR_WS_IMAGES . 'indicator.gif'); ?> </div>
<div style="padding-left:60px;">
<h1>
 <?php
            // PWA BOF
            if (!isset($HTTP_GET_VARS['guest']) && !isset($HTTP_POST_VARS['guest'])){
              echo HEADING_TITLE; 
            }else{ 
              echo HEADING_TITLE_PWA; 
             }
            // PWA EOF 
            ?>
</h1>


<div class="clear"></div>

<p class="smallText"><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></p>
            

        <p><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></p>

<?php    

  if ($messageStack->size('create_account') > 0) {
	 echo $messageStack->output('create_account');
 	 }
?>
<div class="right-align inputrequirement"><?php echo FORM_REQUIRED_INFORMATION; ?></div>


<div id="account-details" >
                   
<div class="firstname"><label for="FIRST_NAME">First Name</label>
<?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?>
</div>

<div class="lastname"><label for="LAST_NAME">Last Name</label>
<?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>
</div>

             
<div class="email"><label>E-Mail Address</label><br />
<?php echo tep_draw_input_field('email_address') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?>
</div>

<div id="password">
   
<div class="spacer-forms"><label>Password</label><br>
<?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?>
</div>
<div class="spacer-forms"><label>Password Confirmation</label>
<?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?>
</div> 
</div>
</div>





<div id="address">
      <div class="account-heading">
      	<?php echo CATEGORY_ADDRESS; ?>
      </div>

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      
<div class="spacer-forms"><label>Company Name</label>
<?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?>
</div>
            
<?php
  }
?>

<p class="spacer-forms"><label>Street Address 1</label>
<?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?>
</p>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 
<p class="spacer-forms"><label>Street Address 2</label>
<?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?>
</p>              
<?php
  }
?>

<div class="city"><label>City</label><br>
<?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>
</div>

<div class="country"><label>Country</label><br />
<?php echo tep_get_country_list('country',$country,'onChange="getStates(this.value, \'states\');"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' .' * '. '</span>': ''); ?>

</div>

<?php } ?>

<?php
  if (ACCOUNT_STATE == 'true') {
?>
              
<div class="state"><label>States</label><br />
<div id="states">
                          <?php
				// +Country-State Selector
				echo ajax_get_zones_html($country,'',false);
				// -Country-State Selector
				?>
                        </div>
</div>

<div class="zipcode"><label>ZIP Code</label><br />
<?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?>
</div>



<div class="grid_4 alpha">
      <div class="account-heading"><?php echo CATEGORY_CONTACT; ?></div>
<p class="spacer-forms"><label>Phone</label><br />
<?php echo tep_draw_input_field('telephone') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?>
</p>
</div>



      <div class="account-heading"><?php echo CATEGORY_OPTIONS; ?></div>
<p class="spacer-forms"><?php echo ENTRY_NEWSLETTER; ?><br />
<?php echo tep_draw_checkbox_field('newsletter', '1') . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?>
</p>
</div>
<div class="clear"></div>
 

       

<div class="clear spacer-tall"></div>
<div class="grid_8 right-align"> 
<!-- // BOF Anti Robot Registration v3.0-->
<?php 
  if (strstr($PHP_SELF,'create_account') &&  ACCOUNT_CREATE_VALIDATION == 'true') include(DIR_WS_MODULES . FILENAME_DISPLAY_VALIDATION); ?>
<!-- // EOF Anti Robot Registration v3.0-->
<?php

//-----   BEGINNING OF ADDITION: MATC   -----// 

if(MATC_AT_REGISTER != 'false'){

	require(DIR_WS_MODULES . 'matc.php');

}

//-----   END OF ADDITION: MATC   -----//

?>        
<?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
</div> 
</form>
<div class="clear spacer-tall"></div>
</div>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
// +Country-State Selector 

}

// -Country-State Selector 
?>

