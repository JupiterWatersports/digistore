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

define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Rápido-Acción-Updater');
define('QUICK_HEAD1', 'Rápido-Acción-Updater');
define('QUICK_HEAD2', 'Elija por favor su categoría abajo');
define('QUICK_MODEL', 'Modelo');
define('QUICK_ID', 'ID');
define('QUICK_NAME', 'Descripción de producto');
define('QUICK_NEW_STOCK', 'Nueva acción<br>');
define('QUICK_PRICE', 'Precio<br>( RM )');
define('QUICK_WEIGHT', 'Peso<br>( Kg )');
define('QUICK_STOCK', 'En la acción');
define('QUICK_STATUS', 'Estado del artículo');
define('QUICK_ACTIVE', 'Activo');
define('QUICK_INACTIVE', 'No Active');
define('QUICK_TEXT', 'Compruebe para fijar estado en cada producto individual basado en artículos en stock<br><i> (uno o más en la acción se convertirán <font color="009933"><b>Active</b></font> / zero in stock will become <font color="ff0000"><b>Not Active</b></font> )</i><p>');
define('QUICK_MODIFIED', '');
define('QUICK_CATEGORY','Categoría');
define('QUICK_MANUFACTURER', 'Fabricante');

define('QUICK_DIR_TEMP',DIR_FS_CATALOG . 'tmp/');

define('QUICK_MSG_SUCCESS','Éxito:');
define('QUICK_MSG_WARNING','Advertencia:');
define('QUICK_MSG_ERROR','Error:');
define('QUICK_MSG_NOITEMUPDATED','No hay expediente actualizado.');
define('QUICK_MSG_ITEMSUPDATED','%d artículo(s) se ha puesto al día.');
define('QUICK_MSG_UPDATETIME','Tiempo de proceso de la actualización: %.4f segundos');
define('QUICK_MSG_UPDATEERROR','Incapaz de poner al día el expediente(s) - Compruebe por favor los varibles y/o el permiso del directorio');
?>
