<?php
/*
  $Id: checkout.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Checkout');
define('NAVBAR_TITLE_1', 'Checkout');

define('HEADING_TITLE', 'Checkout');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TABLE_HEADING_BILLING_ADDRESS', 'Billing Address');

define('TABLE_HEADING_PRODUCTS_MODEL', 'Products Model');
define('TABLE_HEADING_PRODUCTS_NAME', 'Products Name');
define('TABLE_HEADING_PRODUCTS_QTY', 'Quantity');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Price Each');
define('TABLE_HEADING_PRODUCTS_FINAL_PRICE', 'Total Price');

define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');

define('HEADING_NEW_CUSTOMER', 'New Customers - Create Account');
define('HEADING_EXPRESS_CHECKOUT', 'Express Checkout - Without Account ');
define('TEXT_EXPRESS_CHECKOUT','Checkout without creating an account with ' . STORE_NAME . '');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');

define('ENTRY_TELEPHONE', 'Telephone: ');
define('TEXT_MAKE_CHANGES','Make Changes?');
define('HEADING_EXISTING_CUSTOMER', 'Already have an account?');
define('HEADING_DIFFERENT_BILLING_ADDRESS', 'Different from billing address?');
define('ENTRY_CREATE_ACCOUNT', 'If you would like to create an account please enter a password below ');
define('ENTRY_NEWSLETTER_CHECKOUT', 'Newsletter: ');
define('TEXT_ENTER_BILLING_ADDRESS_PAYMENT', 'Please fill in your <b>billing address</b> for payment options.');
define('TEXT_ENTER_BILLING_ADDRESS_SHIPPING', 'Please fill in <b>at least</b> your billing address to get shipping quotes.');

define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Please choose from your address book where you would like the items to be delivered to.');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');

define('TITLE_SHIPPING_ADDRESS', 'Shipping Address:');
define('TITLE_BILLING_ADDRESS', 'Billing Address:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping Method');
define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');
define('TITLE_PLEASE_SELECT', 'Please Select');

define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please select the preferred shipping method to use on this order.');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please select the preferred payment method to use on this order.');


define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');
define('TABLE_HEADING_COMMENTS', 'Add Comments About Your Order');

define('HEADING_FOR_ACCOUNT_PASSWORD', 'If you would like to create an account please enter a password below');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to confirm this order.');

define('TEXT_EDIT', 'Edit');

define('TEXT_SELECTED_SHIPPING_DESTINATION', 'This is the currently selected shipping address where the items in this order will be delivered to.');
define('TABLE_HEADING_NEW_ADDRESS', 'New Address');
define('TABLE_HEADING_EDIT_ADDRESS', 'Edit Address');
define('TEXT_CREATE_NEW_SHIPPING_ADDRESS', 'Please use the following form to create a new shipping address to use for this order.');
define('TABLE_HEADING_ADDRESS_BOOK_ENTRIES', 'Address Book Entries');

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
define('COLOR_TOP_EMAIL', '#999999');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body
define('COLOR_TABLE', '#f9f9f9');         //background color of the email body  (only visible if no background image)

 
//Account Gender True:    
define('EMAILGREET_MR', '<b>Dear Mr. ' . stripslashes($_POST['lastname'].'</b><br />') . ',' . "\n"); 
define('EMAILGREET_MS', '<b>Dear Ms. ' . stripslashes($_POST['lastname'].'</b><br />') . ',' . "\n");

//Account Gender False:
define('EMAILGREET_NONE', '<b>Dear ' . stripslashes($_POST['firstname'] . ' ' . $_POST['lastname'].'</b>') . ',' . "\n");

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

// Start - CREDIT CLASS Gift Voucher Contribution
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
                                        ' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
// End - CREDIT CLASS Gift Voucher Contribution

define('TEXT_AGREE_TO_TERMS', 'I agree to the terms and conditions');

define('WINDOW_BUTTON_CANCEL', 'Cancel');
define('WINDOW_BUTTON_CONTINUE', 'Continue');
define('WINDOW_BUTTON_NEW_ADDRESS', 'New Address');
define('WINDOW_BUTTON_EDIT_ADDRESS', 'Edit Address');
define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');
define('TEXT_PLEASE_SELECT', 'Please Select');
?>