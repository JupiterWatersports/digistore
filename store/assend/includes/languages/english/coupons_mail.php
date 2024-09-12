<?php
/*
  $Id: mail.php,v 1.9 2002/01/19 22:44:34 harley_vb Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Send coupon to customer');

define('TEXT_CUSTOMER', 'Customer:');
define('TEXT_SUBJECT', 'Subject:');
define('TEXT_FROM', 'Sender:');
define('TEXT_CODE', 'Code:');
define('TEXT_WERT', 'Value:');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECT_CUSTOMER', 'Select customer');
define('TEXT_ALL_CUSTOMERS', 'All customers');
define('TEXT_NEWSLETTER_CUSTOMERS', 'To all newsletter abonnents');

define('TEXT_SUBJECT_VALUE', 'Coupon from XXX');
define('TEXT_MESSAGE_VALUE', 'Dear Ladies and Gentlemen,

With this mail you receive a coupon from XXX with a value of <!WERT>.

Coupon code: <!CODE>

To redeem the coupon, please visit our web store at XXX!
On the right panel you got the possibility to enter the code listed above.

Kindest regards,');

define('NOTICE_EMAIL_SENT_TO', 'Notice: e-mail has been sent to: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Error: no customer has been selected.');

define('TEXT_INFO_VARIABLES', 'You can use the following two variables within the e-mail text: <ul><li><strong>&lt;!WERT&gt;</strong> (Displays the value of the coupon )</strong></li><li><strong>&lt;!CODE&gt;</strong> (Displays the code of the coupon)</li></ul>');
?>