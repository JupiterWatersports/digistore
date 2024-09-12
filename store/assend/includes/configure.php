<?php
  define('HTTP_SERVER', 'https://www.jupiterkiteboarding.com');
  define('HTTPS_SERVER', 'https://www.jupiterkiteboarding.com');
  define('HTTP_CATALOG_SERVER', 'https://www.jupiterkiteboarding.com');
  define('HTTPS_CATALOG_SERVER', 'https://www.jupiterkiteboarding.com');
  define('ENABLE_SSL_CATALOG', 'true');
  define('DIR_FS_DOCUMENT_ROOT', '/home/live/public/store/');
  define('DIR_WS_ADMIN', '/store/assend/');
  define('DIR_FS_ADMIN', '/home/live/public/store/assend/');
  define('DIR_WS_CATALOG', '/store/');
  define('DIR_FS_CATALOG', '/home/live/public/store/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_FS_BASE_IMAGES_SLIDER', '/home/live/public/images/slider/');
  define('DIR_BASE_IMAGES_SLIDER', '/images/slider/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_IMAGES_SLIDER', DIR_FS_CATALOG . 'images/slider/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

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
