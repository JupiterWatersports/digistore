<?php
  define('HTTP_SERVER', 'http://www.jupiterkiteboarding.com');
  define('HTTPS_SERVER', 'https://www.jupiterkiteboarding.com');
  define('ENABLE_SSL', 'true');
  define('HTTP_COOKIE_DOMAIN', 'www.jupiterkiteboarding.com');
  define('HTTPS_COOKIE_DOMAIN', 'www.jupiterkiteboarding.com');
  define('HTTP_COOKIE_PATH', '/store/');
  define('HTTPS_COOKIE_PATH', '/store/');
  define('DIR_WS_HTTP_CATALOG', '/store/');
  define('DIR_WS_HTTPS_CATALOG', '/store/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/home/live/public/store/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'live');
  define('DB_SERVER_PASSWORD', '9Y2tVNYxP22LNp5G');
  define('DB_DATABASE', 'live');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');

// Credit Card Number Encryption
// ** W A R N I N G ** TEXT_ENCRYPTION_PW cannot be changed after first use, the risk of data loss due to encryption
// Password stored in value TEXT_ENCRYPTION_PW can be anything from a phrase or your pets name
  define('TEXT_ENCRYPTION_PW','digicartsurfboarders');
  define('USE_ENCRYPTION', true);  // to use encryption change value to TRUE
?>
