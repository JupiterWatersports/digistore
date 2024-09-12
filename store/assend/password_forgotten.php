<?php
  /*
  $Id: password_forgotten.php, v1.0 2003/04/14 14:14:14 waza04_ Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com / Warren Ashcroft

  Support:
  oscdev@ukcomputersystems.com
  waza04@hotmail.com (MSN Messenger)

  Paypal Donations:
  paypal@ukcomputersystems.com

  Web:
  http://www.ukcomputersystems.com/

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  ////
// This function makes a new password from a plaintext password.
if(!function_exists('tep_encrypt_password')){
  function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
 }
  
   function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value)<$length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }


define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - New Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A new password was requested for your account at ' . STORE_NAME . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT_TO_CUST', 'New Password Sent To The Customers E-Mail Address');

$check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['cID'] . "'");
$check_customer = tep_db_fetch_array($check_customer_query);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($newpass);
      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . $crypted_password . "' where customers_id = '" . $check_customer['customers_id'] . "'");

      tep_mail($check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], $check_customer['customers_email_address'], EMAIL_PASSWORD_REMINDER_SUBJECT, nl2br(sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass)), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_CUSTOMERS, 'info_message=' . urlencode(TEXT_PASSWORD_SENT_TO_CUST)));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
