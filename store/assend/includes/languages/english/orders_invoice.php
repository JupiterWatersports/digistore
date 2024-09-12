<?php
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


define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
const FILENAME_ACCOUNT_HISTORY_INFO = 'account_history.php';
const EMAIL_TEXT_PRODUCTS = 'Products';
const EMAIL_TEXT_DELIVERY_ADDRESS = 'Shipping Address';
const EMAIL_TEXT_BILLING_ADDRESS = 'Billing Address';
const EMAIL_TEXT_PAYMENT_METHOD = 'Payment Method';


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

?>
