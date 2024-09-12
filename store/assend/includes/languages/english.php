<?php
/*
  $Id: english.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/
// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'en_US.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}
// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');
// charset for web pages and emails
define('CHARSET', 'iso-8859-1');
// page title
define('TITLE', 'Digistore Admin');
define('HEADING_TITLE2', '10 best viewed products');
define('TABLE_HEADING_VIEWED2', 'Viewed');
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
define('HEADER_WARNING', 'Here you can put a warning for your clients <br>Warning! Please take a database backup before change these settings. ');
// admin welcome text
define('TEXT5', 'You have ');
define('TEXT6', ' customers in total and ');
define('TEXT7', ' products in your store. ');
define('TEXT8', ' of your products has been reviewed.');
define('DO_USE', 'You can use the quick navigation at the top of the page to manage your orders.');
define('WELCOME_BACK', 'Welcome back ');
define('STOCK_TEXT_WARNING1', '<b><font   color="#990000">Warning!</font></b> you have ');
define('STOCK_TEXT_WARNING2', ' product(s) that´s running out of stock. Click here  ');
define('STOCK_TEXT_WARNING3', ' to see your stock status.');
define('STOCK_TEXT_OK1', 'Your stock status is good');
define('STOCK_TEXT_OK2', ' to see your stock status.');
// admin welcome text end
// summary info v1.1 plugin by conceptlaboratory.com
define('TEXT_SUMMARY_INFO_WHOS_ONLINE', 'Users Online: %s');
define('TEXT_SUMMARY_INFO_CUSTOMERS', 'Total Customers: %s, Today: %s');
define('TEXT_SUMMARY_INFO_ORDERS', 'Your Order Status Is: <br> %s, <b>Today:</b> %s');
define('TEXT_SUMMARY_INFO_REVIEWS', 'Total Reviews: %s, Today: %s');
define('TEXT_SUMMARY_INFO_TICKETS', 'Ticket Status %s');
define('TEXT_SUMMARY_INFO_ORDERS_TOTAL', 'Your Order Total is: <br> %s,<b> Today: </b>%s');
define('TEXT_SUMMARY_ORDERS_TOTAL', '%s<b></b>%s');
// summary info v1.1 plugin by conceptlaboratory.com eof
// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT', 'Support Site');
define('HEADER_TITLE_VIEWSTORE', 'View Store');
define('HEADER_TITLE_SIGNOFF', 'Log Out'); 
define('HEADER_TITLE_CREDITS', 'Credits');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');
// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');
// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');
// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Setup');
define('BOX_CONFIGURATION_ADMINISTRATORS', 'Administrators'); 
define('BOX_CONFIGURATION_SETUP', 'General Setup'); 
define('BOX_CONFIGURATION_MYSTORE', 'My Store'); 
define('BOX_CONFIGURATION_TEMPLATE', 'Default Template'); 
define('BOX_CONFIGURATION_MINIMUM_VALUES', 'Minimum Values');
define('BOX_CONFIGURATION_MAXIMUM_VALUES', 'Maximum Values');
define('BOX_CONFIGURATION_IMAGES', 'Images Setup');
define('BOX_CONFIGURATION_CUSTOMER_DETAILS', 'Customer Details');
define('BOX_CONFIGURATION_MODULE_OPTIONS', 'Module Options');
define('BOX_CONFIGURATION_SHIPPING_PACKING', 'Shipping/Packing');
define('BOX_CONFIGURATION_PRODUCT_LISTING', 'Product Listing');
define('BOX_CONFIGURATION_STOCK', 'Stock Control');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');
define('BOX_CONFIGURATION_EMAIL_OPTIONS', 'E-Mail Options');
define('BOX_CONFIGURATION_DOWNLOAD', 'Download');
define('BOX_CONFIGURATION_GZIP_COMPRESSION', 'GZip Compression');
define('BOX_CONFIGURATION_SESSIONS', 'Sessions');
define('BOX_CONFIGURATION_META_TAGS', 'Meta Tags');
define('BOX_CONFIGURATION_SEO', 'SEO URLs');
define('BOX_CONFIGURATION_URL_VALIDATION', 'URL Validation');
define('BOX_CONFIGURATION_HOMEPAGE_AD', 'Homepage Advert');
define('BOX_TITLE_ORDERS', 'Orders');
define('BOX_TITLE_STATISTICS', 'Statistics');
define('BOX_ENTRY_SUPPORT_SITE', 'Support Site');
define('BOX_ENTRY_SUPPORT_FORUMS', 'Support Forums');
define('BOX_ENTRY_CONTRIBUTIONS', 'Contributions');
define('BOX_ENTRY_CUSTOMERS', 'Customers:');
define('BOX_ENTRY_PRODUCTS', 'Products:');
define('BOX_ENTRY_REVIEWS', 'Reviews:');
define('BOX_CONNECTION_PROTECTED', 'You are protected by a %s secure SSL connection.');
define('BOX_CONNECTION_UNPROTECTED', 'You are <font color="#ff0000">not</font> protected by a secure SSL connection.');
define('BOX_CONNECTION_UNKNOWN', 'unknown');
define('CATALOG_CONTENTS', 'Contents');
define('REPORTS_PRODUCTS', 'Products');
define('REPORTS_ORDERS', 'Orders');
define('TOOLS_BACKUP', 'Backup');
define('TOOLS_BANNERS', 'Banners');
define('TOOLS_FILES', 'Files');
define('TEXT_LINK_RECENTLY_VIEWED','Recently Viewed Products');
define('MATC_HEADING_CONDITIONS', 'Accept Terms and Conditions ');
// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');
// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI', 'Multiple Products Manager');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick-Stock-Updater');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_COUPONS', 'Coupons');
// get_1_free
define('BOX_CATALOG_GET_1_FREE', 'Get 1 Free');
// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers');
define('BOX_TOOLS_BATCH_CENTER', 'Batch Print Center');
define('BOX_CUSTOMERS_CUSTOMERS', 'View Customers');
//Orders text for menu header Orders
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_CUSTOMERS_ORDERS_PENDING', 'View Pending');
define('BOX_CUSTOMERS_ORDERS_PROCESSING', 'View Processing');
define('BOX_CUSTOMERS_ORDERS_DELIVERED', 'View Delivered');
define('BOX_CUSTOMERS_ORDERS_STATUS', 'Order Status');
define('BOX_CUSTOMERS_ORDERS_EDITOR', 'Order Editor Setup');
// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');
// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders Total');
define('BOX_REPORTS_KEYWORD_LIST', 'Keyword Searches');
define('BOX_REPORTS_RECOVER_CART_SALES', 'Recovered Sales Results');
// Monthly sales
define('BOX_REPORTS_SALES', 'Monthly sales');
//begin Inactive User Report
define('BOX_REPORTS_INACTIVE_USER', 'Inactive User');
//end Inactive User Report
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_FILE_MANAGER', 'File Manager');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');
define('BOX_TOOLS_DOWN_FOR_MAINTAINANCE', 'Down for maintenance');
define('BOX_TOOLS_RECOVER_CART', 'Recover Cart Sales');
define('BOX_BASKET_PASSWORD', 'Change Basket Password');
// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');
// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');
define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');
define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');
define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');
define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');
define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');
define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_OPTIONS', 'Options');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');
// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_ORDERS_LABEL', 'Label');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');
// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');
define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');
define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');
define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');
define('TEXT_CACHE_CATEGORIES', 'Categories Box');
define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');
define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');
define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');
// coupons addon start
define('BOX_CATALOG_COUPONS', 'Coupons');
// coupons addon end
// infoBox Admin
define('BOX_HEADING_BOXES', 'Infobox Admin');
define('BOX_HEADING_HOMEPAGE', 'Homepage Advert');
define('BOX_CONTENT_HOMEPAGE', 'Homepage Ad Manager');
// bof shopinfo textblocks
define('BOX_HEADING_SHOPINFO', 'CMS Info Pages');
define('BOX_HEADING_ADD_SHOPINFO', 'Add New Page'); 
  // just for admin/index.php:
  define('BOX_AGB_SHOPINFO', 'Terms and conditions');
  define('BOX_PRIVACY_SHOPINFO', 'Privacy');
  define('BOX_ABOUTUS_SHOPINFO', 'About Us');
  //
// eof shopinfo 
// eof shopinfo textblocks
// This copyright notice CAN NOT be REMOVED or MODIFIED as required in the license agreement.
define('COPYRIGHT_NOTICE',' <font color="#999999" size="1"><br>
      Digistore is based on the osCommerce Engine: Copyright &copy; 2003 osCommerce<br>
      <br>
      This program is distributed in the hope that it will be useful, but WITHOUT 
      ANY WARRANTY;<br>
      without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR 
      PURPOSE<br>
      and is redistributable under the <a href="http://www.gnu.org/" target="_blank">GNU 
      General Public License</a>');
define('DIGIADMIN_VERSION', 'Digistore V4');
define('MENU_CONFIGURATION_TEMPLATES', 'Templates'); 
//START STS 4.1
define('BOX_MODULES_STS', 'Additional Templates STS  ');
//END STS 4.1
define('TEXT_DISPLAY_NUMBER_OF_KEYWORDS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> keywords)');
// Down for Maintenance Admin reminder
define ('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'The site is currently set Down for maintenance to the public.  Remember to bring it up when you are done!');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');
// Xsell cache
define('TEXT_CACHE_XSELL_PRODUCTS', 'Cross Sell Products');
define('PAID_NO_ORDER', 'Paid but no order ');
// sitemonitor text in includes/boxes/sitemonitor.php
define('BOX_HEADING_SECURITY', 'Security');
define('BOX_HEADING_SITEMONITOR', 'Site Monitor');
define('BOX_SITEMONITOR_ADMIN', 'Site Monitor Admin');
define('BOX_SITEMONITOR_CONFIG_SETUP', 'Site Monitor Configure');
define('IMAGE_EXCLUDE', 'Exclude');
define('BOX_HEADING_FWR_SECURITY', 'FWR Security Pro');
define('BOX_HEADING_OT_MODULE', '<br><br>NOTES: If you have 2 modules installed with same sort order they wont work, make sure the sort orders of the TOTALIZATION modules is correct. Normally is:<br><br>Subtotal: 1<br>Global Quantity Discount: 2<br>Coupon 3<br>Taxes: 4<br>Shipping: 5<br>Total: 6');
define('TEXT_DISCOUNTPLUS_SETUP', 'Per Product Discount Setup');
// seo assistant start
define('BOX_TOOLS_SEO_ASSISTANT', 'SEO Assistant');
//seo assistant end
//Feeder Systems
define('BOX_FEEDERS_GOOGLE', 'Google Base');
define('TEXT_FEEDERS_GOOGLE', 'Create and Upload a GoogleBase datafeed');
/*** Begin Header Tags SEO ***/
// header_tags_seo text in includes/boxes/header_tags_seo.php
define('BOX_HEADING_HEADER_TAGS_SEO', 'Header Tags SEO');
define('BOX_HEADER_TAGS_ADD_A_PAGE', 'Page Control');
define('BOX_HEADER_TAGS_FILL_TAGS', 'Fill Tags');
define('BOX_HEADER_TAGS_KEYWORDS', 'Keywords');
define('BOX_HEADER_TAGS_SILO', 'Silo Control');
define('BOX_HEADER_TAGS_TEST', 'Test');
/*** End Header Tags SEO ***/
// BOF Product Sort
define('BOX_CATALOG_PRODUCTS_SORTER', 'Sort Order');
// EOF Product Sort
// Google product feed
define('BOX_TOOLS_GOOGLE_FEED', 'Google Product Feed');
// Extra Product Fields
define('TEXT_NOT_APPLY', 'Does Not Apply');
define('BOX_CATALOG_PRODUCTS_EXTRA_FIELDS', 'Extra Product Fields');
define('BOX_CATALOG_PRODUCTS_EXTRA_VALUES', 'Extra Field Values');
define('BOX_CATALOG_PRODUCTS_PTYPES', 'Product Types');
define('TEXT_PTYPE', 'Product Type:');
  // Shipping labels
  define('BOX_MODULES_SHIPPING_LABELS', "Shipping labels");
  // Shipping labels
// BOF Order Maker
define('IMAGE_CREATE_ORDER', 'Create');
define('BOX_CUSTOMERS_CREATE_ORDER', 'Create Order');
// EOF Order Maker
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');
//mail manager
define('BOX_HEADING_MAIL_MANAGER', 'Mail Manager');
define('BOX_MM_BULKMAIL', 'BulkMail Manager');
define('BOX_MM_TEMPLATES', 'Template Manager');
define('BOX_MM_EMAIL', 'Send Email');
define('BOX_MM_RESPONSEMAIL', 'Response Mail');
// Discount Code 2.9 - start
define('BOX_CATALOG_DISCOUNT_CODE', 'Discount Codes');
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> discount codes)');
define('IMAGE_NEW_DISCOUNT_CODE', 'New Discount Code');
// Discount Code 2.9 - end
/* Optional Related Products (ORP) */
define('BOX_CATALOG_CATEGORIES_RELATED_PRODUCTS', 'Related Products');
define('IMAGE_BUTTON_NEW_INSTALL_SQL', 'Install SQL for New Install of Related Products, Version 4.0');
define('IMAGE_BUTTON_UPGRADE_SQL', 'Update SQL for Upgrade Install of Related Products, Version 4.0');
define('IMAGE_BUTTON_REMOVE_SQL', 'Remove SQL for all versions of Related Products');
/***********************************/
define('TEXT_LOGIN_ERROR', 'Incorrect Username/Password');
define('TEXT_LOGOFF', 'You are now logged off');
// Google SiteMap BOF
define('BOX_GOOGLE_SITEMAP', 'Google SiteMaps');
// Google SiteMap END
// BOF edit pages 
define('BOX_HEADING_PAGES', 'Page Editor');
define('PAGES_ADD_PAGE', 'Create New Page');
define('PAGES_LIST_PAGES', 'List Pages');
// EOF edit pages	
//BOF Admin Notes 
define('WARNING_ADMIN_NOTES_TIME', '<b>Attention:</b> The reminder date for a note was reached. <a href="admin_notes.php">(Click here to display the notes page!)</a>');
define('BOX_ADMIN_NOTES', 'Admin Notes');
//EOF Admin Notes 


// Random Things //
define('IMAGE_ORDERS_USPS_SHIP', 'Ship with USPS');
define('GC_STATE_PROCESSING', '');

define('MODULE_PAYMENT_MONEYORDER_PAYTO', 'Order Pay To');
define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER', ' Order Sort Order');
define('MODULE_PAYMENT_MONEYORDER_STATUS' , 'Order Status');
define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID' , 'Order Status ID');
define('MODULE_PAYMENT_PAYPAL_STANDARD_SORT_ORDER' , 'Standard Sort Order');
define('MODULE_PAYMENT_PAYPAL_STANDARD_STATUS', 'Standard Status');
define('MODULE_PAYMENT_PAYPAL_STANDARD_PREPARE_ORDER_STATUS_ID', 'Prepare Order Status ID');
define('MODULE_PAYMENT_PAYPAL_STANDARD_GATEWAY_SERVER', 'Gateway Server');

?>
