<?php
/*
  $Id: account_password.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_PASSWORD);

  if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'process')) {
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
 } 
    $password_current = tep_db_prepare_input($_REQUEST['password_current']);
    $password_new = tep_db_prepare_input($_REQUEST['password_new']);
    $password_confirmation = tep_db_prepare_input($_REQUEST['password_confirmation']);

    $error = false;

    //if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
     // $error = true;

     // $messageStack->add('account_password', ENTRY_PASSWORD_CURRENT_ERROR);
    //} else
    
    if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', "sdsd".ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);

    }



    if ($error == false) {
      $check_customer_query = tep_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (tep_validate_password($password_current, $check_customer['customers_password'])) {
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$customer_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

        $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      } else {
        $error = true;

        $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Change Password</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php echo $stylesheet; ?>

<?php require('includes/form_check.js.php'); ?>
 

<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>

<?php echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onSubmit="return check_form(account_password);"') . tep_draw_hidden_field('action', 'process'); ?>

<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>       
<?php
  if ($messageStack->size('account_password') > 0) {  
  $messageStack->output('account_password'); 
  }
  
?>
<div class="grid_4 alpha">
 
   		<div class="form-group">
				<label class="control-label"><?php echo ENTRY_PASSWORD_CURRENT; ?></label>
				<?php echo tep_draw_password_field('password_current','','class="form-control required"'); ?>                     
    			</div>   	
    	            
     			<div class="form-group">
				<label class="control-label"><?php echo ENTRY_PASSWORD_NEW; ?></label>
     			<?php echo tep_draw_password_field('password_new','','class="form-control required"'); ?>                  
        		</div> 
                  
        		<div class="form-group">
				<label class="control-label"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
     			<?php echo tep_draw_password_field('password_confirmation','','class="form-control required"'); ?>                  
				</div> 
<div class="form-group">			
<div class="g-recaptcha" id="rcaptcha" data-sitekey="6LcfLg4TAAAAAJJwCtP3bHW3n2iXVFRtqPPRE0zU"></div>
<span id="captcha" style="color:red"></span>
</div>
</div>


<div class="clear"></div>			
<button class="button-blue-small required-continue">Continue</button>
     </form> 				    
      		
<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">'.'<button class="button-blue-small required-back">Back</button>'.'</a>'; ?>
<div class="clear"></div>           
</form>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
