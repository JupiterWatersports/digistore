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

 
 // +Country-State Selector
require(DIR_WS_FUNCTIONS . 'ajax.php');
  if (isset($_POST['action']) && $_POST['action'] == 'getStates' && isset($_POST['country'])) {
ajax_get_zones_html(tep_db_prepare_input($_POST['country']), true);
} else {

// -Country-State Selector


// PWA EOF

  if (isset($_GET['guest']) && $cart->count_contents() < 1) tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
// PWA BOF
// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
	
    $process = true;

    
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
    $email_address = tep_db_prepare_input($_POST['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    $street_address = addslashes(tep_db_prepare_input($_POST['street_address']));
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $zone_id = tep_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($_POST['country']);
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    if (isset($_POST['newsletter'])) {
      $newsletter = tep_db_prepare_input($_POST['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);



    $error = false;
$captcha=$_POST['g-recaptcha-response'];
 if(!empty($captcha))
 {
 $errMsg= '';
 $google_url="https://www.google.com/recaptcha/api/siteverify";
 $secret= '6LcfLg4TAAAAADiuwD12x27DXiVjPgB28BRLH_7W';
 $ip=$_SERVER['REMOTE_ADDR'];
 $captchaurl=$google_url."?secret=".$secret."&response=".$captcha."&remoteip=".$ip;
 
 $curl_init = curl_init();
 curl_setopt($curl_init, CURLOPT_URL, $captchaurl);
 curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curl_init, CURLOPT_TIMEOUT, 10);
 $results = curl_exec($curl_init);
 curl_close($curl_init);
 
 $results= json_decode($results, true);
 if($responseData->success){
 }
 else{
$errMsg = 'Robot verification failed, please try again.';}
 
 
 $results= json_decode($results, true);}
 

  // AKISMET SPAM CHECK
   
  $akismetKey = '840f6de1bc3e';

  $aParameters['blog'] = 'https://www.jupiterkiteboarding.com';
  $aParameters['permalink'] = 'https://www.jupiterkiteboarding.com/store/create_account';
  $aParameters['comment_type'] = 'signup';

  $aParameters['comment_author'] = $firstame . ' ' . $lastname;
  $aParameters['comment_author_email'] = $email_address;
  
  //$aParameters['comment_author'] = 'viagra-test-123'; // spam
  //$aParameters['user_role'] = 'administrator'; // ham

  $content = 'Name: ' . $aParameters['comment_author'] . "\n"
    . 'Email: ' . $email_address . "\n"
    . (!empty($company) ? 'Company: ' . $company . "\n" : '')
    . "Address: \n" 
    . $street_address . "\n"
    . (!empty($suburb) ? $suburb . "\n" : '')
    . $postcode . ' ' . $city . "\n"
    . (!empty($state) ? $state . "\n" : '')
    . $country . "\n"
    . "Phone: " . $telephone . "\n"
    . "Fax: " . $fax;

  $aParameters['comment_content'] = $content;

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $ips = array_map('trim', $ips);
      $aParameters['user_ip'] = $ips[0];
  } elseif (isset($_SERVER['REMOTE_ADDR'])) {
      $aParameters['user_ip'] = $_SERVER['REMOTE_ADDR'];
  } else $aParameters['user_ip'] = '';

  if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $aParameters['user_agent'] = (string) $_SERVER['HTTP_USER_AGENT'];
  } else $aParameters['user_agent'] = '';

  if (isset($_SERVER['HTTP_REFERER'])) {
      $aParameters['referrer'] = (string) $_SERVER['HTTP_REFERER'];
  }

  foreach ($_SERVER as $key => $value) {
      $aKeysToSend = [
        'CONTENT_LENGTH',
        'CONTENT_TYPE',
        'HTTP_HOST',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_KEEP_ALIVE',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTP_FORWARDED',
        'HTTP_FORWARDED_FOR', 
        'HTTP_X_FORWARDED', 
        'HTTP_X_FORWARDED_FOR', 
        'HTTP_CLIENT_IP',
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_URI'
      ];

      if (in_array($key, $aKeysToSend)) $aParameters[$key] = $value;
  }
  
  error_log("Akismet parameter array: ".json_encode($aParameters));

  $url = 'https://'.$akismetKey.'.rest.akismet.com/1.1/comment-check';
  
  $curl_options = [];
  $curl_options[CURLOPT_URL] = $url;
  $curl_options[CURLOPT_USERAGENT] = 'jupiterkite-akismet-custom';
  if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
      $curl_options[CURLOPT_FOLLOWLOCATION] = true;
  }
  $curl_options[CURLOPT_RETURNTRANSFER] = true;
  $curl_options[CURLOPT_TIMEOUT] = 20;
  $curl_options[CURLOPT_POST] = true;
  $curl_options[CURLOPT_POSTFIELDS] = $aParameters;

  $curl = curl_init();
  curl_setopt_array($curl, $curl_options);

  $response = curl_exec($curl);
  $headers = curl_getinfo($curl);
  $errorNumber = curl_errno($curl);
  $errorMessage = curl_error($curl);

  curl_close($curl);

  $akismetMsg = '';

  if (!in_array($headers['http_code'], array(0, 200))) {
      error_log("Akismet error - status code: ".$headers['http_code']);
      $akismetMsg = "We have encountered a problem while checking for spam. Please try later.";
      $error = true;
  }

  if ((int) $errorNumber > 0) {
      error_log("Akismet error - curl: $errorNumber - $errorMessage");
      $akismetMsg = "We have encountered a problem while checking for spam. Please try later.";
      $error = true;
  }

  if ($response === 'true') {
    $akismetMsg = "Registration failed spam check";
    $error = true;
  }
  
  ///////////////////////////////

   

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

          if (isset($_GET['guest']) or isset($_POST['guest'])) {
                tep_db_query("update " . TABLE_CUSTOMERS . " set guest_account = '1' where customers_email_address = '" . tep_db_input($email_address) . "'");
            } else {
                $error = true;
                $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
            }
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
// PWA BOF

    if (!isset($_GET['guest']) && !isset($_POST['guest'])) {

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



    if ($error == false && ($_POST['gender'] == '')) {

		// PWA BOF 2b

		if (!isset($_GET['guest']) && !isset($_POST['guest']))

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
                'customers_password' => $dbPass,
                'customers_newsletter' => $newsletter,
                'mmstatus' => '',
                // PWA BOF 2b
                'guest_account' => $guestaccount,
                'customers_notes' => '',
                'verified' => '0');

            // PWA EOF 2b
		
      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                'entry_firstname' => $firstname,
                'entry_lastname' => $lastname,
                'entry_street_address' => $street_address,
                'entry_postcode' => $postcode,
                'entry_city' => $city,
                'entry_country_id' => $country);


            if (ACCOUNT_COMPANY == 'true')
                $sql_data_array['entry_company'] = $company;
            if (ACCOUNT_SUBURB == 'true')
                $sql_data_array['entry_suburb'] = $suburb;
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
     if (isset($_GET['guest']) or isset($_POST['guest']))
             tep_session_register('customer_is_guest');

            // PWA EOF
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

            $address_id = tep_db_insert_id();

            tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int) $address_id . "' where customers_id = '" . (int) $customer_id . "'");

            tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int) $customer_id . "', '0', now())");

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
            if (isset($_GET['guest']) or isset($_POST['guest']))
                tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));
            // PWA EOF
            // restore cart contents
            $cart->restore_contents();
            // BEGIN SEND HTML MAIL//

            $name = $firstname . " " . $lastname;
            $Varlogo = ' ' . VARLOGO . ' ';
            $Vartable1 = ' ' . VARTABLE1 . ' ';
            $Vartable2 = ' ' . VARTABLE2 . ' ';
            $Vartextmail = EMAILWELCOME . EMAILTEXT . EMAILCONTACT . EMAILWARNING;
            $Vartrcolor = ' ' . TRCOLOR . '  ';
            $Varmailfooter = '  ' . EMAIL_TEXT_FOOTER . ' <br /><br /> ';


            $Vargendertext = EMAILGREET_NONE;


            require(DIR_WS_MODULES . 'email/html_create_account.php');

            $email_text = $html_email_text;

            if (EMAIL_USE_HTML == 'true') {

                $email_text;
            } else {

                if (ACCOUNT_GENDER == 'true') {
                    if ($_POST['gender'] == 'm') {
                        $email_text = EMAILGREET_MR;
                    } else {
                        $email_text = EMAILGREET_MS;
                    }
                } else {
                    $email_text = EMAILGREET_NONE;
                }

                $email_text .= EMAILWELCOME . "\n\n" . EMAILTEXT . "\n\n" . EMAILCONTACT .
                        EMAIL_TEXT_FOOTER . "\n\n\n" .
                        EMAIL_SEPARATOR . "\n" .
                        EMAILWARNING . "\n\n";
                $email_text .= HTTP_SERVER . DIR_WS_CATALOG . "\n" .
                        EMAIL_TEXT_FOOTERR . "\n";
            }
//END SEND HTML EMAIL//
	


// Skips create account success - Begin	  

 //*******start mail manager**************//
if (file_exists(DIR_WS_MODULES.'mail_manager/create_account.php')){
include(DIR_WS_MODULES.'mail_manager/create_account.php');
}else{
      //tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
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
        //tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Company account created', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
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


 // +Country-State Selector 
 if (!isset($country)) $country = DEFAULT_COUNTRY;
 // -Country-State Selector


  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("cache-Control: no-cache");
  header("cache-control: no-store");
  header("pragma: no-cache");
// PWA EOF
  
echo $doctype;
?><html <?php echo HTML_PARAMS; ?>>
<head>
  <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '234965059596038');
fbq('track', 'CompleteRegistration');
</script>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Create Account</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<?php require('includes/form_check.js.php'); 
require(DIR_WS_INCLUDES . 'ajax.js.php');
?>

<style>
*{box-sizing:border-box;}

@media only screen and (min-width :1024px) {.col-xs-12{float:none;}}
</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple2.php'); ?>  
<?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, (isset($_GET['guest'])? 'guest=guest':''), 'SSL'), 'post', 'onSubmit="return check_form(create_account);" id="forms"') . tep_draw_hidden_field('action', 'process'); ?>  <div id="indicator"> <?php echo tep_image(DIR_WS_IMAGES . 'indicator.gif'); ?> </div>
<h1 class="simple-headline">
 <?php
            // PWA BOF
            if (!isset($_GET['guest']) && !isset($_POST['guest'])){
              echo HEADING_TITLE; 
            }else{ 
              echo HEADING_TITLE_PWA; 
             }
            // PWA EOF 
            ?>
</h1>


<?php    

  if ($messageStack->size('create_account') > 0) {
	 echo $messageStack->output('create_account');
 	 }
   
   if ( $akismetMsg ) {
     ?>
      <div><?php echo $akismetMsg; ?><br><br></div>
      <?php /*
      <div><?php echo $aParameters['comment_content']; ?><br></div>
      <div><?php echo json_encode($aParameters); ?><br></div>
      <div><?php echo json_encode($response); ?><br></div>
      */?>
     <?php
   }
?>

<div id="account-details">

<div class="clear"></div>

<p class="smallText"><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></p>
            
        <p><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></p>

<div class="form-group">
<div class="form-group name">              
<div class="form-group"><label class="control-label">First Name</label>
<?php echo tep_draw_input_field('firstname','','class="form-control"' ); ?>
</div>

<div class="form-group"><label class="control-label">Last Name</label>
<?php echo tep_draw_input_field('lastname','','class="form-control"'); ?>
</div>
    
</div>
             
<div class="form-group"><label class="control-label">E-Mail Address</label>
<?php echo tep_draw_input_field('email_address','','class="form-control"'); ?>
</div>
<?php
// PWA BOF
  if (!isset($_GET['guest']) && !isset($_POST['guest'])) {
// PWA EOF
?>
<div id="password">
   
<div class="form-group"><label class="control-label">Password</label>
<?php echo tep_draw_password_field('password','','class="form-control"'); ?>
</div>
<div class="form-group"><label class="control-label">Password Confirmation</label>
<?php echo tep_draw_password_field('confirmation','','class="form-control"'); ?>
</div> 
</div>

<?php } ?>

<div id="address" style="margin-top:10px;">

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      
<div class="form-group"><label class="control-label">Company Name</label>
<input type="text" name="company" placeholder="Optional" class="form-control">
</div>
            
<?php
  }
?>

<div class="form-group"><label class="control-label">Street Address 1</label>
<?php echo tep_draw_input_field('street_address','','class="form-control"'); ?>
</div>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 
<div class="form-group"><label class="control-label">Street Address 2</label>
<input type="text" name="suburb" placeholder="Ex. Suite#, Building Name, or P.O. Box" class="form-control">
</div>              
<?php
  }
?>

<div class="form-group"><label class="control-label">City</label>
<?php echo tep_draw_input_field('city' ,'','class="form-control"'); ?>
</div>
          
<div class="form-group"><label class="control-label">State/ Providence/ Region</label>
    
<?php $cust_country='223'; if($address['entry_country_id']) $cust_country=$address['entry_country_id']; ?>
<?php  echo '<div id="states">';

$zones_array = array();    
$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '".$cust_country."' order by zone_name");
echo '<select id="zone_id" class="inputBox form-control" name="zone_id" required>';
echo '<option disabled selected>Select State</option>';
while ($zones_values = tep_db_fetch_array($zones_query)) {
  $zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
  echo '<option value="'.$zones_values['zone_id'].'">'.$zones_values['zone_name'].'</option>';
}
echo '</select>';
//$customer_zone_query = tep_db_query("select zone_name from zones where zone_country_id = '".$cust_country."' and zone_id = '".$address['entry_zone_id']. "'");
//$customer_zone = tep_db_fetch_array($customer_zone_query);
//if(isset($_GET['customer_name'])) {
        //  $state = $customer_zone['zone_name'];
        //} else {
         // $state = 'Florida (Palm Beach County)';
       // }

  //echo tep_draw_pull_down_menu('zone_id', $zones_array, $state,' class="inputBox form-control" ');

echo '</div>'; ?>
    </div>

 
<div class="form-group"><label class="control-label">ZIP Code</label>
<?php echo tep_draw_input_field('postcode' ,'','class="form-control"'); ?>
</div>

<div class="form-group"><label class="control-label">Country</label>
<?php // +Country-State Selector ?>
<?php echo tep_get_country_list('country', '223', 'onchange="loadXMLDoc(this.value);" class="form-control" id="country"'); ?>
<?php // -Country-State Selector ?>
</div> 

<div class="form-group"><label class="control-label">Phone</label>
<?php echo tep_draw_input_field('telephone' ,'','class="form-control"'); ?>
</div>

<div class="form-group">
<?php echo tep_draw_checkbox_field('newsletter', '1') . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': '&nbsp;&nbsp;'); ?><?php echo 'Subscribe to our Newsletter'; ?>
</div>

<div class="g-recaptcha" id="rcaptcha" data-sitekey="6LcfLg4TAAAAAJJwCtP3bHW3n2iXVFRtqPPRE0zU"></div>
<span id="captcha" style="color:red"></span>
       
<button style="float: left; margin-top: 40px;" class="button-blue-small" type="submit">Continue</button></div>
</div> 
</form>
</div>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
// +Country-State Selector 

}

// -Country-State Selector 
?>
