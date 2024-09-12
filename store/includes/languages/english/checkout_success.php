<?php
/*
  $Id: checkout_success.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Success');

define('HEADING_TITLE', 'Your Order Has Been Submitted!');

define('TEXT_SUCCESS', 'Your order has been successfully submitted! Your products will arrive at their destination within 2-5 working days.');
define('TEXT_NOTIFY_PRODUCTS', 'Please notify me of updates to the products I have selected below:');
define('TEXT_SEE_ORDERS', 'You can view your order history by going to the <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'My Account\'</a> page and by clicking on <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'History\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'If you have any questions feel free to <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">Contact Us </a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Thanks for shopping with us online!');

define('TABLE_HEADING_COMMENTS', 'Enter a comment for the order processed');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry date: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' downloads remaining');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');
?>