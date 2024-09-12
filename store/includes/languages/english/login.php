<?php
/*
  $Id: login.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', 'New Customers - Create Account');
define('HEADING_EXPRESS_CHECKOUT', 'Express Checkout - Without Account ');
define('TEXT_EXPRESS_CHECKOUT','Checkout without creating an account with ' . STORE_NAME . '');
//define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Awesome! Let\'s get you registered. Add customersupport@jupiterkiteboarding.com as a contact to your email account. This will guarantee delivery of our emails to your inbox. If for any reason our email isn\'t in your inbox, check your SPAM folder. You may call +1-561-427-0240  or text +1-561-677-0323 for additional help.');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'Existing customer sign in:');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');

define('TEXT_GUEST_INTRODUCTION', '<b>Do not want to register?</b><br /><br />You can checkout now without an account through this express checkout!');
?>