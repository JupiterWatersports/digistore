<?php
/*
  $Id: quick_stock_update.php,v 2.5 2005/04/19 12:45:05 harley_vb Exp $
  MODIFIED by Günter Geisler / http://www.highlight-pc.de
  RE-WRITTEN by Azrin Aris / http://www.free-fliers.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick-Stock-Updater');
define('QUICK_HEAD1', 'Quick-Stock-Updater');
define('QUICK_HEAD2', 'Please choose your category below');
define('QUICK_MODEL', 'Model');
define('QUICK_ID', 'ID');
define('QUICK_NAME', 'Product Description');
define('QUICK_NEW_STOCK', 'New Stock<br>');
define('QUICK_PRICE', 'Price<br>( RM )');
define('QUICK_WEIGHT', 'Weight<br>( Kg )');
define('QUICK_STOCK', 'In Stock');
define('QUICK_STATUS', 'Item Status');
define('QUICK_ACTIVE', 'Active');
define('QUICK_INACTIVE', 'Not Active');
define('QUICK_TEXT', 'Check to set status on each individual product based on items in stock<br><i>( one or more in stock will become <font color="009933"><b>Active</b></font> / zero in stock will become <font color="ff0000"><b>Not Active</b></font> )</i><p>');
define('QUICK_MODIFIED', '');
define('QUICK_CATEGORY','Category');
define('QUICK_MANUFACTURER', 'Manufacturer');

define('QUICK_DIR_TEMP',DIR_FS_CATALOG . 'tmp/');

define('QUICK_MSG_SUCCESS','Success:');
define('QUICK_MSG_WARNING','Warning:');
define('QUICK_MSG_ERROR','Error:');
define('QUICK_MSG_NOITEMUPDATED','No record was updated.');
define('QUICK_MSG_ITEMSUPDATED','%d item(s) has been updated.');
define('QUICK_MSG_UPDATETIME','Update process time : %.4f seconds');
define('QUICK_MSG_UPDATEERROR','Unable to update record(s) - Please check directory varibles and/or permission');
?>
