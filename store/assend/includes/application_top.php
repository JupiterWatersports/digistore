<?php
// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());
// Set the level of error reporting
//error_reporting(E_ALL^ E_WARNING);  
//ini_set('display_errors', 1);
ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);

// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  /*if (function_exists('ini_get')) { // Register Globals MOD - http://www.magic-seo-url.com
    ini_get('register_globals') or exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory.');
  }*/

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');
//require(DIR_FS_CATALOG . 'includes/osc_sec.php');

// Define the project version
  define('PROJECT_VERSION', 'oscommerce');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

  // set php_self in the local scope
  if( !isset( $PHP_SELF ) ) {
    if ( @phpversion() >= "5.0.0" && ( !ini_get("register_long_arrays" ) || @ini_get("register_long_arrays" ) == "0" || strtolower(@ini_get("register_long_arrays" ) ) == "off" ) ) $HTTP_SERVER_VARS = $_SERVER;
    $PHP_SELF = ( ( ( strlen( ini_get('cgi.fix_pathinfo' ) ) > 0 ) && ( ( bool ) ini_get('cgi.fix_pathinfo' ) == false ) ) || !isset( $HTTP_SERVER_VARS['SCRIPT_NAME' ] ) ) ? basename( $HTTP_SERVER_VARS[ 'PHP_SELF' ] ) : basename( $HTTP_SERVER_VARS[ 'SCRIPT_NAME' ] );
  }
// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');
// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');


// include the list of project filenames
 require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 200); // how wide the boxes should be in pixels (default: 125)

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from configuration');
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');
  // BOF edit pages 
require(DIR_WS_FUNCTIONS . 'pages.php');
// EOF edit pages	

//Admin begin
/////  require(DIR_WS_FUNCTIONS . 'password_funcs.php');
//Admin end

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// set the session name and save path
  //tep_session_name('osCAdminID');
  //tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }
@ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);
	// coupons addon start
  	define('FILENAME_COUPONS', 'coupons.php');
  	define('FILENAME_COUPONS_MAIL', 'coupons_mail.php');
  	define('FILENAME_COUPONS_STATUS', 'coupons_status.php');
  	define('TABLE_COUPONS', 'coupons');
  	define('TABLE_COUPONS_SALES', 'coupons_sales');
	// coupons addon end
  require(DIR_WS_CLASSES . 'navigation_history.php');
// lets start our session
  tep_session_start();

     if ( (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

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
      if (empty($lng)) {
        $lng->set_language(DEFAULT_LANGUAGE);
      }
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }


 if(!isset($language)) $language='english';
// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// include the text encrypt/decrypt functions
  require(DIR_WS_FUNCTIONS . 'en_de_crypt.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results-ajax.php');

// split-page-results pagination
  require(DIR_WS_CLASSES . 'split_page_results-paginate.php');

// entry/item info classes
  require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// file uploading class
  require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
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

// default open navigation box
  if (!tep_session_is_registered('selected_box')) {
    tep_session_register('selected_box');
    $selected_box = 'configuration';
  }

  if (isset($HTTP_GET_VARS['selected_box'])) {
    $selected_box = $HTTP_GET_VARS['selected_box'];
  }

// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
                       );

// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }
//Admin begin
 
 if (basename($PHP_SELF) != FILENAME_LOGIN && basename($PHP_SELF) != FILENAME_PASSWORD_FORGOTTEN) {
 tep_admin_check_login();
 }

  define ('DIGISTORE_COPYRIGHT','&copy;  Digistore Ecommerce 2005 - 2006');
//Admin end



$logo_name = 'https://jupiterkiteboarding.com/store/images/jkb-logo-black.png';

global $logo_name;
?>
