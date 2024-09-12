<?php
/*
  $Id: password_forgotten.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License  
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

$captcha=$_POST['g-recaptcha-response'];
 if(!empty($captcha))
 {
 $errMsg= '';
 $google_url="https://www.google.com/recaptcha/api/siteverify";
 $secret= '6Lc9JjUeAAAAAGUYEJTm4eJuzz6J9cBNfCaYcsWU';
 $ip=$_SERVER['REMOTE_ADDR'];
 $captchaurl=$google_url."?secret=".$secret."&response=".$captcha."&remoteip=".$ip;
 
 $curl_init = curl_init();
 curl_setopt($curl_init, CURLOPT_URL, $captchaurl);
 curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curl_init, CURLOPT_TIMEOUT, 10);
 $results = curl_exec($curl_init);
 curl_close($curl_init);
 
 $results= json_decode($results, true);}

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {


    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);

    $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);

      $new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($new_password);

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_db_input($crypted_password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");



     //*******start mail manager
if (file_exists(DIR_WS_MODULES.'mail_manager/password_forgotten.php')){
include(DIR_WS_MODULES.'mail_manager/password_forgotten.php');
}else{
      tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $new_password), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
//*******end mail manager


      $messageStack->add_session('login', SUCCESS_PASSWORD_SENT, 'success');

      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);

    }

  }

// BOF Anti Robotic Registration v3.0	
  
  header('cache-control: no-store, no-cache, must-revalidate');
  header("Pragma: no-cache");
// EOF Anti Robotic Registration v3.0

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Forgot Password</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
.input-style{width:310px; float:left;margin:25px 0px 0px -93px;}
@media only screen and (max-width:767px) {.validation{margin-bottom:40px;}}
</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<?php echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>
<?php 

?>
<h1 class="simple-headline"><?php echo HEADING_TITLE; ?></h1>
<div class="clear" ></div> 
<?php
  if ($messageStack->size('password_forgotten') > 0) {
	echo $messageStack->output('password_forgotten');
  }
?>
<p><?php echo TEXT_MAIN; ?></p>

<?php
if(isset($_GET['debug'])) {}
?>
	<div class="form-group">
	<?php echo '<label class="control-label">' . ENTRY_EMAIL_ADDRESS . '</label> ' . tep_draw_input_field('email_address','','class="form-control" style="width:300px;"'); ?>
	</div><div class="clear"></div>   <!-- // BOF Anti Robot Registration v3.0-->
    
<div class="form-group">			
<div class="g-recaptcha" id="rcaptcha" data-sitekey="6Lc9JjUeAAAAAAcuptQesN_IdypQU6NdOPhRSgw_"></div>
<span id="captcha" style="color:red"></span>
</div>
<!-- // EOF Anti Robot Registration v3.0-->
<button class="button-blue-small required-continue">Continue</button>
</form>
<div><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">'.'<button class="button-blue-small required-back">Back</button>'.'</a>'; ?></div>
</div>
<div class="clear"></div>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
