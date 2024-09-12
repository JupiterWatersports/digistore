<?php
/*
  $Id: password_forgotten.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Password Forgotten');

define('HEADING_TITLE', 'I\'ve Forgotten My Password!');

//define('TEXT_MAIN', 'If you\'ve forgotten your password, enter your e-mail address below and we\'ll send you an e-mail message containing your new password.');
define('TEXT_MAIN', 'Wait! Before you add your \'E-Mail Address\' below, first add customersupport@jupiterkiteboarding.com as a contact to your email account. This will guarantee delivery of our emails to your inbox.  If for any reason our email is not in your inbox, check your SPAM folder. You can also call +1-561-427-0240  or text +1-561-677-0323 if you are still having trouble.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'Error: The E-Mail Address was not found in our records, please try again.');

define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - New Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A new password was requested from ' . $REMOTE_ADDR . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('SUCCESS_PASSWORD_SENT', 'Success: A new password has been sent to your e-mail address.');
?>