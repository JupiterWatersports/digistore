<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

 require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
	 
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
 }  
    
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);

    $error = false;

   

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (!checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
        $error = true;
        $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (!tep_validate_email($email_address)) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;
      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);

    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname);

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");

// reset the session variables
      $customer_first_name = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }

  $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $account = tep_db_fetch_array($account_query);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Edit Account Info</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php echo $stylesheet; ?>
<style>
@media only screen and (max-width: 975px){
.container_12, #account-details {width:100%;}}
@media only screen and (max-width: 767px) {.container_12{padding-bottom:0px; width:100%;}}
@media only screen and (max-width: 800px) and (orientation :landscape) {.container_12{padding-bottom:0px;}}
@media only screen and (min-width: 960px) and (max-width: 1280px) and (orientation : landscape) {.container_12{padding-bottom:0px;}}
@media only screen and (min-height: 767px) and (max-height: 1200px) and (orientation : portrait) {.container_12{padding-bottom:0px;}}
@media only screen and (min-height: 1201px) and (max-height: 1280px) and (orientation : portrait) {.container_12{padding-bottom: 92px;}}
*{box-sizing:border-box;}
</style>
<?php require('includes/form_check.js.php'); ?> 
<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>
<?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . tep_draw_hidden_field('action', 'process'); ?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>    
<?php
  if ($messageStack->size('account_edit') > 0) {
  echo $messageStack->output('account_edit'); 
     }
?>

      <div id="account-details" class="col-xs-12" style="margin-top: 20px; padding-bottom: 20px;">
    <div class="row">               
<div class="form-group"><label class="control-label" for="FIRST_NAME">First Name</label>
<?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'class="form-control"'); ?>
</div>

<div class="form-group"><label class="control-label" for="LAST_NAME">Last Name</label>
<?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'class="form-control"'); ?>
</div>

             
<div class="form-group"><label class="control-label">E-Mail Address</label>
<?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'class="form-control"'); ?>
</div>

<div class="form-group"><label class="control-label">Phone</label>
<?php echo tep_draw_input_field('telephone', $account['customers_telephone'], 'class="form-control"'); ?>
</div>



<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($account['customers_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>

<p class="spacer-forms">
<?php echo ENTRY_GENDER; ?><br/>
<?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement smalltext">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
</p>
<?php
  }
?>
    
<div class="form-group">			
<div class="g-recaptcha" id="rcaptcha" data-sitekey="6LcfLg4TAAAAAJJwCtP3bHW3n2iXVFRtqPPRE0zU"></div>
<span id="captcha" style="color:red"></span>
</div>
</div><?php echo '<a class="button-blue-small" style="float:left; margin-right:20px;" type="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">Back</a>'; ?>      
<button class="button-blue-small" style="float:left">Continue</button>
</div>

     </form> 				      		
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>