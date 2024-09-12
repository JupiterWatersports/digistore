<?php

/*

  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2002 osCommerce



  Released under the GNU General Public License

*/



define('HEADING_TITLE', 'Orders');

define('HEADING_TITLE_SEARCH', 'Order ID:');

define('HEADING_TITLE_STATUS', 'Status:');



define('TABLE_HEADING_COMMENTS', 'Comments');

define('TABLE_HEADING_CUSTOMERS', 'Customers');

define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');

define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');

define('TABLE_HEADING_STATUS', 'Status');

define('TABLE_HEADING_ACTION', 'Action');

define('TABLE_HEADING_QUANTITY', 'Qty.');

define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');

define('TABLE_HEADING_PRODUCTS', 'Products');

define('TABLE_HEADING_TAX', 'Tax');

define('TABLE_HEADING_TOTAL', 'Total');

define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');

define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');

define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');

define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');



define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');

define('TABLE_HEADING_DATE_ADDED', 'Date Added');



define('ENTRY_CUSTOMER', 'Customer:');

define('ENTRY_SOLD_TO', 'SOLD TO:');

define('ENTRY_DELIVERY_TO', 'Delivery To:');

define('ENTRY_SHIP_TO', 'SHIP TO:');

define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');

define('ENTRY_BILLING_ADDRESS', 'Billing Address:');

define('ENTRY_PAYMENT_METHOD', 'Payment Method:');

define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');

define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');

define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');

define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');

define('ENTRY_SUB_TOTAL', 'Sub-Total:');

define('ENTRY_TAX', 'Tax:');

define('ENTRY_SHIPPING', 'Shipping:');

define('ENTRY_TOTAL', 'Total:');

define('ENTRY_DATE_PURCHASED', 'Date Purchased:');

define('ENTRY_STATUS', 'Status:');

define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');

define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');

define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');

define('ENTRY_PRINTABLE', 'Print Invoice');



define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');

define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');

define('TEXT_DATE_ORDER_CREATED', 'Date Created:');

define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');

define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');



define('TEXT_ALL_ORDERS', 'All Orders');

define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');



define('EMAIL_SEPARATOR', '------------------------------------------------------');

define('EMAIL_TEXT_SUBJECT', 'Order Update');

define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');

define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');

define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');

define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");

define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n\n\n");



define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');

define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');

define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');



//BEGIN SEND HTML MAIL//



// Email style

define('STORE_LOGO', 'jup-kitepaddlewake.png'); // Your shop logo (location: /catalog/images).

define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Header background image.

define('COLOR_TOP_EMAIL', '#f9f9f9');         //Background color of the email header (only visible if no background image)

define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body

define('COLOR_TABLE', '#f9f9f9');         //background color of the email body (only visible if no background image)



//First section of text

define('EMAIL_TEXT_DEAR', '<br><br>Dear');        

define('EMAIL_MESSAGE_GREETING', 'We would like to notify you that the status of your order has been updated.'); 



//Email Footer

define('EMAIL_TEXT_FOOTER', 'This email address was given to us by you or by one of our customers. If you feel that you have received this email in error, please send an email to ');    

define('EMAIL_TEXT_COPYRIGHT', 'Copyright &#169; 2017 ');





//Define Variables

define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="stylesheetmail.css">');   //location of email css file.

define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');   //Do not change

define('VARMAILFOOTER', '' . EMAIL_TEXT_FOOTER . '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER_EMAIL_ADDRESS . '</a><br>' . EMAIL_TEXT_COPYRIGHT . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. STORE_NAME .'</a> ');  //footer

define('VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');   //logo

define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '"> ');   //Header table formatting



define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting



//END SEND HTML MAIL//

define('ENTRY_IPADDRESS', 'The IP address for this order<br>(Click on for details)');

define('ENTRY_IPISP', 'ISP:');

define('TEXT_PRODUCTS_BY_BUNDLE', 'Products in');
/*Tracking contribution begin*/
define('TABLE_HEADING_UPS_TRACKING', 'UPS Tracking Number');
define('TABLE_HEADING_USPS_TRACKING', 'USPS Tracking Number');
define('TABLE_HEADING_FEDEX_TRACKING', 'Fedex Tracking Number');
/*Tracking contribution end*/
/*Tracking contribution begin*/
define('EMAIL_TEXT_TRACKING_NUMBER', 'You can track your packages by clicking the link below.');
/*Tracking contribution end*/
define('TEXT_INFO_SHIPPING_METHOD','Shipping method');



//---- Batch Update Status v1.2
     define('BUS_HEADING_TITLE','Batch Update Status');
     define('BUS_TEXT_NEW_STATUS', 'Select New Status');
     define('BUS_NOTIFY_CUSTOMERS', 'Notify customer(s)');
     define('BUS_SELECT_ALL', 'Select All');
     define('BUS_SELECT_NONE', 'Select None');
     define('BUS_SUBMIT', 'Update Status');
     define('BUS_CBUTTON_RESET','Reset');
     define('BUS_ENABLE_DELETE',0); // Set to 1 to enable delete option, 0 to remove it
     define('BUS_DELETE_TEXT','!! Delete Orders !!');
     define('BUS_DELETE_WARNING', 'You have selected '.BUS_DELETE_TEXT.'. Marked orders will be PERMANENTLY deleted if you click '.BUS_SUBMIT.'.');
     // list here button names and their corresponding comment value
     $bus_cbuttons = array(
       'Shipped' => 'Your order has been shipped today.',
       'Payed'   => 'We have received your payment, thank you.',
       'Miscellaneous' => 'All your base belong to us:-D'
     );
   //----- eof Batch Update Status v1.2
?>
