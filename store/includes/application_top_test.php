<?php

/*

  $Id: application_top.php 1785 2008-01-10 15:07:07Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2008 osCommerce



  Released under the GNU General Public License

*/



// Register Globals MOD - http://www.magic-seo-url.com



  if (version_compare(phpversion(), "4.1.0", "<") === true) {

    $_GET &= $HTTP_GET_VARS;

    $_POST &= $HTTP_POST_VARS;

    $_SERVER &= $HTTP_SERVER_VARS;

    $_FILES &= $HTTP_POST_FILES;

    $_ENV &= $HTTP_ENV_VARS;

    if (isset($HTTP_COOKIE_VARS)) $_COOKIE &= $HTTP_COOKIE_VARS;

  }



  if (!ini_get("register_globals")) {

    extract($_GET, EXTR_SKIP);

    extract($_POST, EXTR_SKIP);

    extract($_COOKIE, EXTR_SKIP);

  }

if ((strstr($_SERVER['HTTP_ACCEPT'], 'vnd.wap.xhtml')) || (strstr($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml')))
	{
	if ((!preg_match("/product_info/", $PHP_SELF)) && (!preg_match("/index/", $PHP_SELF)))
	{
		$filename = 'mobile_'.$PHP_SELF;
		tep_redirect(tep_href_link($filename, '', 'NONSSL'));
 	} else {
		tep_redirect(tep_href_link('mobile_index.php', '', 'NONSSL'));
	}
 }



// start the timer for the page parse time log

  define('PAGE_PARSE_START_TIME', microtime());



// set the level of error reporting

  error_reporting(E_ERROR);
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);



// Check if register_globals is enabled.

// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.

  /*if (function_exists('ini_get')) { // Register Globals MOD - http://www.magic-seo-url.com

    ini_get('register_globals') or exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory.');

  }*/



// Set the local configuration parameters - mainly for developers

  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');



// include server parameters

  require('includes/configure.php');
  require(DIR_FS_CATALOG . 'includes/osc_sec.php');
    define('ACCOUNT_VALIDATION','false');

  if (strlen(DB_SERVER) < 1) {

    if (is_dir('install')) {

      header('Location: install/index.php');

    }

  }



// define the project version

  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.2 RC2');



// some code to solve compatibility issues

  require(DIR_WS_FUNCTIONS . 'compatibility.php');



// set the type of request (secure or not)

  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';



  // set php_self in the local scope
  if( !isset( $PHP_SELF ) ) {
    if ( @phpversion() >= "5.0.0" && ( !ini_get("register_long_arrays" ) || @ini_get("register_long_arrays" ) == "0" || strtolower(@ini_get("register_long_arrays" ) ) == "off" ) ) $HTTP_SERVER_VARS = $_SERVER;
    $PHP_SELF = ( ( ( strlen( ini_get('cgi.fix_pathinfo' ) ) > 0 ) && ( ( bool ) ini_get('cgi.fix_pathinfo' ) == false ) ) || !isset( $HTTP_SERVER_VARS['SCRIPT_NAME' ] ) ) ? basename( $HTTP_SERVER_VARS[ 'PHP_SELF' ] ) : basename( $HTTP_SERVER_VARS[ 'SCRIPT_NAME' ] );
  }


  if ($request_type == 'NONSSL') {

    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);

  } else {

    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);

  }



// include the list of project filenames

  require(DIR_WS_INCLUDES . 'filenames.php');



// include the list of project database tables

  require(DIR_WS_INCLUDES . 'database_tables.php');



// customization for the design layout

//  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)



// include the database functions

  require(DIR_WS_FUNCTIONS . 'database.php');



// make a connection to the database... now

  tep_db_connect() or die('Unable to connect to database server!');



// set the application parameters

  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);

  while ($configuration = tep_db_fetch_array($configuration_query)) {

    define($configuration['cfgKey'], $configuration['cfgValue']);

  }



// FWR Media Security Pro

if ( defined('FWR_SECURITY_PRO_ON') && FWR_SECURITY_PRO_ON === 'true' ) {

$fwr_security_excludes = array();

if ( defined('FWR_SECURITY_PRO_FILE_EXCLUSIONS_ON') && FWR_SECURITY_PRO_FILE_EXCLUSIONS_ON === 'true' )

$fwr_security_excludes = explode(',', FWR_SECURITY_PRO_FILE_EXCLUSIONS);

if ( !in_array(basename($_SERVER['PHP_SELF']), $fwr_security_excludes) )

include('includes/functions/security.php');

}

if ( function_exists('tep_clean_get__recursive') ) {

// Recursively clean $HTTP_GET_VARS and $_GET

// There is no legitimate reason for these to contain anything but ..

// A-Z a-z 0-9 -(hyphen).(dot)_(underscore) {} space

$HTTP_GET_VARS = tep_clean_get__recursive($HTTP_GET_VARS);

$_GET = tep_clean_get__recursive($_GET);

$_REQUEST = $_GET + $_POST; // $_REQUEST now holds the cleaned $_GET and std $_POST. $_COOKIE has been removed.

fwr_clean_global($_GET); // Change the $GLOBALS value to the cleaned value

}

// END - FWR Media Security Pro





// if gzip_compression is enabled, start to buffer the output

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {

    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {

      if (PHP_VERSION >= '4.0.4') {

        ob_start('ob_gzhandler');

      } else {

        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');

        ob_start();

        ob_implicit_flush();

      }

    } else {

      ini_set('zlib.output_compression_level', GZIP_LEVEL);

    }

  }



// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled

  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {

    if (strlen(getenv('PATH_INFO')) > 1) {

      $GET_array = array();

      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);

      $vars = explode('/', substr(getenv('PATH_INFO'), 1));

      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {

        if (strpos($vars[$i], '[]')) {

          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];

        } else {

          $HTTP_GET_VARS[$vars[$i]] = $vars[$i+1];

        }

        $i++;

      }



      if (sizeof($GET_array) > 0) {

        while (list($key, $value) = each($GET_array)) {

          $HTTP_GET_VARS[$key] = $value;

        }

      }

    }

  }



// define general functions used application-wide

  require(DIR_WS_FUNCTIONS . 'general.php');

  require(DIR_WS_FUNCTIONS . 'html_output.php');



// set the cookie domain

  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);

  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);



// include cache functions if enabled

  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');



// include shopping cart class

  require(DIR_WS_CLASSES . 'shopping_cart.php');



// include navigation history class

  require(DIR_WS_CLASSES . 'navigation_history.php');



// check if sessions are supported, otherwise use the php3 compatible session class

  if (!function_exists('session_start')) {

    define('PHP_SESSION_NAME', 'osCsid');

    define('PHP_SESSION_PATH', $cookie_path);

    define('PHP_SESSION_DOMAIN', $cookie_domain);

    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);



    include(DIR_WS_CLASSES . 'sessions.php');

  }



// define how the session functions will be used

  require(DIR_WS_FUNCTIONS . 'sessions.php');



// set the session name and save path

  tep_session_name('osCsid');

  tep_session_save_path(SESSION_WRITE_DIRECTORY);



// set the session cookie parameters

   if (function_exists('session_set_cookie_params')) {

    session_set_cookie_params(0, $cookie_path, $cookie_domain);

  } elseif (function_exists('ini_set')) {

    ini_set('session.cookie_lifetime', '0');

    ini_set('session.cookie_path', $cookie_path);

    ini_set('session.cookie_domain', $cookie_domain);

  }



// set the session ID if it exists

   if (isset($HTTP_POST_VARS[tep_session_name()])) {

     tep_session_id($HTTP_POST_VARS[tep_session_name()]);

   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()]) ) {

     tep_session_id($HTTP_GET_VARS[tep_session_name()]);

   }



// start the session

  $session_started = false;

  if (SESSION_FORCE_COOKIE_USE == 'True') {

    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);



    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {

      tep_session_start();

      $session_started = true;

    }

  } elseif (SESSION_BLOCK_SPIDERS == 'True') {

    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));

    $spider_flag = false;



    if (tep_not_null($user_agent)) {

      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');



      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {

        if (tep_not_null($spiders[$i])) {

          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {

            $spider_flag = true;

            break;

          }

        }

      }

    }



    if ($spider_flag == false) {

      tep_session_start();

      $session_started = true;

    }

  } else {

    tep_session_start();

    $session_started = true;

  }



  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {

    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);

  }



// Register Globals MOD - http://www.magic-seo-url.com

  if (!ini_get("register_globals")) {

    if (version_compare(phpversion(), "4.1.0", "<") === true) {

      if (isset($HTTP_SESSION_VARS)) $_SESSION &= $HTTP_SESSION_VARS;

    }

    extract($_SESSION, EXTR_SKIP);

  }



// set SID once, even if empty

  $SID = (defined('SID') ? SID : '');



// verify the ssl_session_id if the feature is enabled

  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {

    $ssl_session_id = getenv('SSL_SESSION_ID');

    if (!tep_session_is_registered('SSL_SESSION_ID')) {

      $SESSION_SSL_ID = $ssl_session_id;

      tep_session_register('SESSION_SSL_ID');

    }



    if ($SESSION_SSL_ID != $ssl_session_id) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));

    }

  }



// verify the browser user agent if the feature is enabled

  if (SESSION_CHECK_USER_AGENT == 'True') {

    $http_user_agent = getenv('HTTP_USER_AGENT');

    if (!tep_session_is_registered('SESSION_USER_AGENT')) {

      $SESSION_USER_AGENT = $http_user_agent;

      tep_session_register('SESSION_USER_AGENT');

    }



    if ($SESSION_USER_AGENT != $http_user_agent) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));

    }

  }



// verify the IP address if the feature is enabled

  if (SESSION_CHECK_IP_ADDRESS == 'True') {

    $ip_address = tep_get_ip_address();

    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {

      $SESSION_IP_ADDRESS = $ip_address;

      tep_session_register('SESSION_IP_ADDRESS');

    }



    if ($SESSION_IP_ADDRESS != $ip_address) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));

    }

  }



// create the shopping cart & fix the cart if necesary

  if (tep_session_is_registered('cart') && is_object($cart)) {

    if (PHP_VERSION < 4) {

      $broken_cart = $cart;

      $cart = new shoppingCart;

      $cart->unserialize($broken_cart);

    }

  } else {

    tep_session_register('cart');

    $cart = new shoppingCart;

  }



// include currencies class and create an instance

  require(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();



// include the mail classes

  require(DIR_WS_CLASSES . 'mime.php');

  require(DIR_WS_CLASSES . 'email.php');



// set the language

  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {

    if (!tep_session_is_registered('language')) {

      tep_session_register('language');

      tep_session_register('languages_id');

    }



    include(DIR_WS_CLASSES . 'language.php');

    $lng = new language();



    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {

      $lng->set_language($HTTP_GET_VARS['language']);

    } else {

      $lng->get_browser_language();

    }



    $language = $lng->language['directory'];

    $languages_id = $lng->language['id'];

  }



// include the language translations

  require(DIR_WS_LANGUAGES . $language . '.php');



// Ultimate SEO URLs v2.1

  if (SEO_ENABLED == 'true') {

    include_once(DIR_WS_CLASSES . 'seo.class.php');

        if ( !is_object($seo_urls) ){

                $seo_urls = new SEO_URL($languages_id);

        }

}



################################################

// fwrmedia.co.uk mod to check SEO link validity

if ( is_object($seo_urls) && (strpos($_SERVER['REQUEST_URI'], '.html') !== false) && (defined('FWR_VALIDATION_ON') && FWR_VALIDATION_ON === 'true') ) { // SEO URLS is active and there is .html in the querystring

tep_validate_seo_urls();

}

################################################

// END fwrmedia.co.uk mod to check SEO link validity



// currency

  if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {

    if (!tep_session_is_registered('currency')) tep_session_register('currency');



    if (isset($HTTP_GET_VARS['currency']) && $currencies->is_set($HTTP_GET_VARS['currency'])) {

      $currency = $HTTP_GET_VARS['currency'];

    } else {

      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

    }

  }



// navigation history

  if (tep_session_is_registered('navigation')) {

    if (PHP_VERSION < 4) {

      $broken_navigation = $navigation;

      $navigation = new navigationHistory;

      $navigation->unserialize($broken_navigation);

    }

  } else {

    tep_session_register('navigation');

    $navigation = new navigationHistory;

  }

  $navigation->add_current_page();



// BOF: Down for Maintenance except for admin ip

if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR')){

	if (DOWN_FOR_MAINTENANCE=='true' and !strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) { tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME)); }

	}

// do not let people get to down for maintenance page if not turned on

if (DOWN_FOR_MAINTENANCE=='false' and strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) {

    tep_redirect(tep_href_link(FILENAME_DEFAULT));

}

// EOF: WebMakers.com Added: Down for Maintenance

// PWA - Begin
if (tep_session_is_registered('customer_id') && (isset($HTTP_GET_VARS['products_id']) || isset($HTTP_POST_VARS['products_id']))) {
$query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_id = " . (int)$customer_id);
if (tep_db_num_rows($query) == 0) {
tep_session_unregister('customer_id');
}
}
// PWA - End

// Shopping cart actions

  if (isset($HTTP_GET_VARS['action'])) {

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled

    if ($session_started == false) {

      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));

    }



    if (DISPLAY_CART == 'true') {

      $goto =  FILENAME_SHOPPING_CART;

      $parameters = array('action', 'cPath', 'products_id', 'pid');

    } else {

      $goto = basename($PHP_SELF);

      if ($HTTP_GET_VARS['action'] == 'buy_now') {

        $parameters = array('action', 'pid', 'products_id');

      } else {

        $parameters = array('action', 'pid');

      }

    }



    if ($HTTP_GET_VARS['action'] == 'update_product') {

        $parameters = array('action', 'pid');

        $goto = basename($PHP_SELF);

    }



    switch ($HTTP_GET_VARS['action']) {

      // customer wants to update the product quantity in their shopping cart

      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {

                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {

                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);

                                } else {

                                  if (PHP_VERSION < 4) {

                                    // if PHP3, make correction for lack of multidimensional array.

                                    reset($HTTP_POST_VARS);

                                    while (list($key, $value) = each($HTTP_POST_VARS)) {

                                      if (is_array($value)) {

                                        while (list($key2, $value2) = each($value)) {

                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {

                                            $id2[$var[1]][$var[2]] = $value2;

                                          }

                                        }

                                      }

                                    }

                                    $attributes = ($id2[$HTTP_POST_VARS['products_id'][$i]]) ? $id2[$HTTP_POST_VARS['products_id'][$i]] : '';

                                  } else {

                                    $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';

                                  }

// Start get 1 free

                                  if ($HTTP_POST_VARS['free'][$i] != 1) {

                                    $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);

                                  }

// End get 1 free

                                }

					// BOF: Who's Shopping modification

					if(!sizeof($cart->contents)) {

					if($_REQUEST["osCsid"]) {  tep_db_query("update " . TABLE_WHOS_ONLINE . " set shopping='E' where session_id  = '" . $_REQUEST["osCsid"] ."'"); }

					}

					//EOF: Who's Shopping modification

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

      // customer adds a product from the products page

      case 'add_product' :    if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {

                                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id']);

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;
      case 'mobile_add_product' :    if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
                                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id']);
                              }
                              tep_redirect(tep_mobile_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page

      ///// SID-KILLER ( change ) /////////// all $HTTP_GET_VARS in "case 'buy_now'" exchanged to $HTTP_POST_VARS cuz BTN-mode was changed to a form instead of a link.

       case 'buy_now' :        if (isset($HTTP_POST_VARS['products_id'])) {

                                if (tep_has_product_attributes($HTTP_POST_VARS['products_id'])) {

                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id']));

                                } else {

                                  $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity($HTTP_POST_VARS['products_id'])+1);

                                }

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;
      case 'mobile_buy_now' :        if (isset($HTTP_POST_VARS['products_id'])) {
                                if (tep_has_product_attributes($HTTP_POST_VARS['products_id'])) {
                                  tep_redirect(tep_mobile_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id']));
                                } else {
                                  $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity($HTTP_POST_VARS['products_id'])+1);
                                }
                              }
                              tep_redirect(tep_mobile_link($goto, tep_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if (tep_session_is_registered('customer_id')) {

                                if (isset($HTTP_GET_VARS['products_id'])) {

                                  $notify = $HTTP_GET_VARS['products_id'];

                                } elseif (isset($HTTP_GET_VARS['notify'])) {

                                  $notify = $HTTP_GET_VARS['notify'];

                                } elseif (isset($HTTP_POST_VARS['notify'])) {

                                  $notify = $HTTP_POST_VARS['notify'];

                                } else {

                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

                                }

                                if (!is_array($notify)) $notify = array($notify);

                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {

                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify[$i] . "' and customers_id = '" . $customer_id . "'");

                                  $check = tep_db_fetch_array($check_query);

                                  if ($check['count'] < 1) {

                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");

                                  }

                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

                              } else {

                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

                              }

                              break;

      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {

                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");

                                $check = tep_db_fetch_array($check_query);

                                if ($check['count'] > 0) {

                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");

                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));

                              } else {

                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

                              }

                              break;

      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {

                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {

                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid']));

                                } else {

                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);

                                }

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

    }

  }



// include the who's online functions

  require(DIR_WS_FUNCTIONS . 'whos_online.php');

  tep_update_whos_online();



// include the password crypto functions

  require(DIR_WS_FUNCTIONS . 'password_funcs.php');



// include validation functions (right now only email address)

  require(DIR_WS_FUNCTIONS . 'validations.php');



// split-page-results

  require(DIR_WS_CLASSES . 'split_page_results.php');



// infobox

  require(DIR_WS_CLASSES . 'boxes.php');



// auto activate and expire banners

  require(DIR_WS_FUNCTIONS . 'banner.php');

  tep_activate_banners();

  tep_expire_banners();



// auto expire special products

  require(DIR_WS_FUNCTIONS . 'specials.php');

  tep_expire_specials();



// calculate category path

  if (isset($HTTP_GET_VARS['cPath'])) {

    $cPath = $HTTP_GET_VARS['cPath'];

  } elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {

    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);

  } else {

    $cPath = '';

  }



  if (tep_not_null($cPath)) {

    $cPath_array = tep_parse_category_path($cPath);

    $cPath = implode('_', $cPath_array);

    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];

  } else {

    $current_category_id = 0;

  }



// BOF Meta tags on the fly///

// include the breadcrumb class and start the breadcrumb trail with mata tags



  require(DIR_WS_CLASSES . 'breadcrumb.php');

if ( ($HTTP_GET_VARS['currency']) ) {

   tep_session_register('kill_sid');

   $kill_sid=false;

  }

if (basename($_SERVER['HTTP_REFERER']) == 'allprods.php' ) $kill_sid = true;

if ( ( !tep_session_is_registered('customer_id') ) && ( $cart->count_contents()==0 ) && (!tep_session_is_registered('kill_sid') ) ) $kill_sid = true;

if ((basename($PHP_SELF) == FILENAME_LOGIN) && ($HTTP_GET_VARS['action'] == 'process') ) $kill_sid = false;

if (basename($PHP_SELF) == FILENAME_CREATE_ACCOUNT_PROCESS) $kill_sid = false;

// Uncomment line bellow to disable SID Killer

// $kill_sid = false; 





  $breadcrumb = new breadcrumb;

$titletag = TITLE;

$keywordtag = KEYWORD_TAG;

$description = DESCRIPTION_TAG;

$seperator = '-';

$keywordsep = ', ';

  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);

  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));



// add category names or the manufacturer name to the breadcrumb trail

  if (isset($cPath_array)) {

    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($categories_query) > 0) {

        $categories = tep_db_fetch_array($categories_query);

        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));

	$titletag .= $seperator . $categories['categories_name'];

	$keywordtag .= $keywordsep . $categories['categories_name'];  

      } else {

        break;

      }

    }

  } elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {

    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

    if (tep_db_num_rows($manufacturers_query)) {

      $manufacturers = tep_db_fetch_array($manufacturers_query);

      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));

	$titletag .= $seperator . $manufacturers['manufacturers_name'];

	$keywordtag .= $keywordsep . $manufacturers['manufacturers_name'];

    }

  }



// add the products name to the breadcrumb trail
 if (isset($_GET['products_id'])) {
  $products_query = tep_db_query("select pd.products_head_title_tag from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.language_id ='" .  (int)$languages_id . "' LIMIT 1");
  if (tep_db_num_rows($products_query)) {
    $products = tep_db_fetch_array($products_query);
 $breadcrumb->add($name['products_name'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));

	$keywordtag .= $keywordsep . $name['products_name'];

	$titletag .= $seperator;

	$titletag .= substr(preg_replace('[^a-zA-Z0-9]',' ',(strip_tags($name['products_name']))),0,100);  

	$description = substr(preg_replace('[^a-zA-Z0-9]',' ',(strip_tags($name['products_description']))),0,300);

// In above code change 300 to the length you want the description tag to be.

     }

  }

/*** End Header Tags SEO ***/



// START STS 4.5

require (DIR_WS_CLASSES.'sts.php');

$sts= new sts();

$sts->start_capture();

// END STS




$doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

$stylesheet='
<link rel="stylesheet" href="../reset.css" />
<link rel="stylesheet" href="../960.css" />
<link rel="stylesheet" href="../text.css" />
<link rel="stylesheet" href="../styles.css" />
<link rel="stylesheet" href="css/css-buttons.css" />
<link rel="stylesheet" href="css/superfish.css" />
<link rel="stylesheet" href="css/json-addtocart-themes.css" />
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="javascript/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="javascript/superfish.js"></script>
<script type="text/javascript" src="javascript/osc_cart.js"></script>
';

// initialize the message stack for output messages

  require(DIR_WS_CLASSES . 'message_stack.php');

  $messageStack = new messageStack;



// set which precautions should be checked

  define('WARN_INSTALL_EXISTENCE', 'true');

  define('WARN_CONFIG_WRITEABLE', 'true');

  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');

  define('WARN_SESSION_AUTO_START', 'true');

  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');



	// coupons addon start

  	require('includes/application_coupon.php');

	// coupons addon end

	// PWA BOF

  if (tep_session_is_registered('customer_id') && tep_session_is_registered('customer_is_guest') && substr(basename($PHP_SELF),0,7)=='account') tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

// PWA EOF
/*--------------------------------------------------------*\
#	Page cache contribution - by Chemo
#	Define the pages to be cached in the $cache_pages array
\*--------------------------------------------------------*/
$cache_pages = array('index.php', 'product_info.php');
if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true') {
	# Start the output buffer for the shopping cart
	ob_start();
	require(DIR_WS_BOXES . 'shopping_cart.php');
	$cart_cache = ob_get_clean();
	# End the output buffer for cart and save as $cart_cache string

	# Loop through the $cache_pages array and start caching if found
	foreach ($cache_pages as $index => $page){
		if ( strpos($_SERVER['PHP_SELF'], $page) ){
			include_once(DIR_WS_CLASSES . 'page_cache.php');
			$page_cache = new page_cache($cart_cache);
			# The cache timelife is set globally 
			# in the admin control panel settings
			# Example below overrides the setting to 60 minutes
			# Leave blank to use default setting
			# $page_cache->cache_this_page(60);
			$page_cache->cache_this_page();
		} # End if
	} # End foreach
} # End if
?>
