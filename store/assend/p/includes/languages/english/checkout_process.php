<?php
/*
  $Id: checkout_process.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('EMAIL_TEXT_SUBJECT', 'Jupiter Kiteboarding - Order Confirmation');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_PRODUCTS', 'Products');
define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
define('EMAIL_TEXT_TAX', 'Tax:        ');
define('EMAIL_TEXT_SHIPPING', 'Shipping: ');
define('EMAIL_TEXT_TOTAL', 'Total:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'via');

//BEGIN SEND HTML MAIL

// Email style
define('STORE_LOGO', 'jup-kitepaddlewake.png'); // Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Header background image.
define('COLOR_TOP_EMAIL', '#f9f9f9');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');    //Detail section background image.
define('COLOR_TABLE', '#f9f9f9');         //Detail section background color of the email header (only visible if no background image)

//First section of text
define('EMAIL_TEXT_DEAR', '<br /><br />Dear');        
define('EMAIL_MESSAGE_GREETING', ' Thank you for shopping with us today.<br />Please find below the details of your order:'); 

//Table Heading
define('EMAIL_TEXT_PRODUCTS_QTY', 'Quantity');
define('EMAIL_TEXT_PRODUCTS_ARTICLES', 'Item');
define('EMAIL_TEXT_PRODUCTS_MODELE', 'Model');
define('EMAIL_NO_MODEL', ''); //What text to enter in model column if no model description available

//Table Footer
define('DETAIL', '');  //text to go at the bottom of table


//Email Footer
define('EMAIL_TEXT_FOOTER', 'This email address was given to us by you or by one of our customers. If you feel that you have received this email in error, please send an email to ');    
define('EMAIL_TEXT_COPYRIGHT', 'Copyright � 2015 ');


//Define Variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="stylesheetmail.css">');   //location of email css file.
define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');   //Do not change
define('VARMAILFOOTER', '' . EMAIL_TEXT_FOOTER . '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER_EMAIL_ADDRESS . '</a><br />' . EMAIL_TEXT_COPYRIGHT . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. STORE_NAME .'</a> ');  //footer
define('VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');   //logo
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '"> ');   //Header table formatting
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting
//END SEND HTML MAIL
define('EMAIL_TEXT_CONFIRM', 'has recommended');
define('TEXT_FROM', 'from');
?>
