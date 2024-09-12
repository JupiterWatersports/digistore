<?php

/*

  $Id: database_tables.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2007 osCommerce



  Released under the GNU General Public License

*/

//Admin begin
  define('TABLE_ADMIN', 'admin');
  define('TABLE_ADMIN_FILES', 'admin_files');
  define('TABLE_ADMIN_GROUPS', 'admin_groups');
//Admin end

// define the database table names used in the project

  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_ADMINISTRATORS', 'administrators');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_NEWSLETTERS', 'newsletters');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');

// get_1_free

  define('TABLE_GET_1_FREE', 'get_1_free');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

  // Bulk Mailer newsletter
  define('TABLE_CUSTOMERS_TEMP', 'customers_temp'); 

  // infoBox Admin
  define('TABLE_THEME_CONFIGURATION', 'theme_configuration');
  define('TABLE_SEARCH_QUERIES', 'search_queries');
  define('TABLE_SCART', 'scart');
// BOF Bundled Products
  define('TABLE_PRODUCTS_BUNDLES', 'products_bundles');
// EOF Bundled Products

  define('TABLE_PRODUCTS_XSELL', 'products_xsell');
  define('TABLE_DISCOUNTPLUS','products_discountplus');
  define('TABLE_SEO_GOOGLE', 'seo_google');
  define('TABLE_SEO_MSN', 'seo_msn');
  define('TABLE_SEO_YAHOO', 'seo_yahoo');
  /*** Begin Header Tags SEO ***/
  define('TABLE_HEADERTAGS', 'headertags');
  define('TABLE_HEADERTAGS_CACHE', 'headertags_cache');
  define('TABLE_HEADERTAGS_DEFAULT', 'headertags_default');
  define('TABLE_HEADERTAGS_KEYWORDS', 'headertags_keywords');
  define('TABLE_HEADERTAGS_SEARCH', 'headertags_search');
  define('TABLE_HEADERTAGS_SILO', 'headertags_silo');
  /*** End Header Tags SEO ***/
  //Extra Product Fields
  define('TABLE_EPF', 'extra_product_fields');
  define('TABLE_EPF_LABELS', 'extra_field_labels');
  define('TABLE_EPF_VALUES', 'extra_field_values');
  define('TABLE_EPF_EXCLUDE', 'extra_value_exclude');
  define('TABLE_PTYPES', 'product_types');
  define('TABLE_PTYPE_DESC', 'product_type_descriptions');
define('TABLE_PRODUCTS_BARCODES', 'products_barcodes');
define('TABLE_PRODUCTS_BARCODES_TO_OPTIONS', 'products_barcodes_to_options');
define('TABLE_BARCODE_CONFIG', 'barcode_config');
///Mail Manager
define('TABLE_MM_RESPONSEMAIL', 'mm_responsemail');
define('TABLE_MM_RESPONSEMAIL_RESTORE', 'mm_responsemail_backup');
define('TABLE_MM_RESPONSEMAIL_RESET', 'mm_responsemail_reset');
define('TABLE_MM_TEMPLATES', 'mm_templates');
define('TABLE_MM_NEWSLETTERS', 'mm_newsletters');
  // Discount Code 2.9 - start
  define('TABLE_CUSTOMERS_TO_DISCOUNT_CODES', 'customers_to_discount_codes');
  define('TABLE_DISCOUNT_CODES', 'discount_codes');
  // Discount Code 2.9 - end
  // BOF edit pages 
define('TABLE_PAGES', 'pages');
define('TABLE_PAGES_DESCRIPTION', 'pages_description');
// EOF edit pages	
/* Optional Related Products (ORP) */
define('TABLE_PRODUCTS_RELATED_PRODUCTS', 'products_related_products');
//ORP: end
//BOC Admin Notes
  define('TABLE_ADMIN_NOTES','admin_notes');
  define('TABLE_ADMIN_NOTES_TYPE','admin_notes_type');
  //EOC Admin Notes
  
  define('TABLE_ORDERS_PAYMENT_STATUS','orders_payment_status');
  define('TABLE_ORDERS_PAYMENT_HISTORY','orders_payment_history');
  
  define('TABLE_PRODUCT_FEED_COUNT','product_feed_count');
?>
