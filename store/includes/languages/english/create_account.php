<?php

/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License
*/
define('NAVBAR_TITLE', 'Create an Account');
define('NAVBAR_TITLE_PWA', 'Enter Billing & Shipping Information');
define('HEADING_TITLE_PWA', 'Billing & Shipping Information');
define('HEADING_TITLE', 'My Account Information');
define('TEXT_ORIGIN_LOGIN', '<small style="color:#FF0000;font-weight:bold;">NOTE:</small> If you already have an account with us, please login at the <a href="%s"><u>login page</u></a>.');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");

define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");

define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

//BEGIN SEND HTML MAIL//
// Email style

define('STORE_LOGO', 'logo.jpg');      //Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Background image. 
define('COLOR_TOP_EMAIL', '#f9f9f9');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body
define('COLOR_TABLE', '#f9f9f9');         //background color of the email body  (only visible if no background image)

//Account Gender True:    
define('EMAILGREET_MR', '<b>Dear Mr. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n"); 
define('EMAILGREET_MS', '<b>Dear Ms. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n");
//Account Gender False:
define('EMAILGREET_NONE', '<b>Dear ' . stripslashes($HTTP_POST_VARS['firstname'] . ' ' . $HTTP_POST_VARS['lastname'].'</b>') . ',' . "\n");
//Email Body
define('EMAILWELCOME', 'We welcome you to ' . STORE_NAME . '<br /><br /> '. "\n\n");  
define('EMAILTEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");  
define('EMAILCONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' .  "\n" . '<br /><br />' . "\n\n");  
define('EMAILWARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send a email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
//Email Footer.  

define('EMAIL_SEPARATOR', '' . "\n");  //Define Email Separator
define('EMAIL_TEXT_FOOTER', '');     //Footer Text 

// Prepare Variables

define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="'. HTTP_SERVER . DIR_WS_CATALOG . ' stylesheetmail.css">');   //Define CSS Stylesheet to use
define('VARLOGO', ' <a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> '); //Define Logo location.  DO NOT CHANGE
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '" > ' ) ; //Header Table 
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting
//END SEND HTML MAIL//
?>
