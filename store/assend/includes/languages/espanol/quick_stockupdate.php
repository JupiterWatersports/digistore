<?php
/*
  $Id: quick_stock_update.php,v 2.5 2005/04/19 12:45:05 harley_vb Exp $
  MODIFIED by G�nter Geisler / http://www.highlight-pc.de
  RE-WRITTEN by Azrin Aris / http://www.free-fliers.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('BOX_CATALOG_QUICK_STOCKUPDATE', 'R�pido-Acci�n-Updater');
define('QUICK_HEAD1', 'R�pido-Acci�n-Updater');
define('QUICK_HEAD2', 'Elija por favor su categor�a abajo');
define('QUICK_MODEL', 'Modelo');
define('QUICK_ID', 'ID');
define('QUICK_NAME', 'Descripci�n de producto');
define('QUICK_NEW_STOCK', 'Nueva acci�n<br>');
define('QUICK_PRICE', 'Precio<br>( RM )');
define('QUICK_WEIGHT', 'Peso<br>( Kg )');
define('QUICK_STOCK', 'En la acci�n');
define('QUICK_STATUS', 'Estado del art�culo');
define('QUICK_ACTIVE', 'Activo');
define('QUICK_INACTIVE', 'No Active');
define('QUICK_TEXT', 'Compruebe para fijar estado en cada producto individual basado en art�culos en stock<br><i> (uno o m�s en la acci�n se convertir�n <font color="009933"><b>Active</b></font> / zero in stock will become <font color="ff0000"><b>Not Active</b></font> )</i><p>');
define('QUICK_MODIFIED', '');
define('QUICK_CATEGORY','Categor�a');
define('QUICK_MANUFACTURER', 'Fabricante');

define('QUICK_DIR_TEMP',DIR_FS_CATALOG . 'tmp/');

define('QUICK_MSG_SUCCESS','�xito:');
define('QUICK_MSG_WARNING','Advertencia:');
define('QUICK_MSG_ERROR','Error:');
define('QUICK_MSG_NOITEMUPDATED','No hay expediente actualizado.');
define('QUICK_MSG_ITEMSUPDATED','%d art�culo(s) se ha puesto al d�a.');
define('QUICK_MSG_UPDATETIME','Tiempo de proceso de la actualizaci�n: %.4f segundos');
define('QUICK_MSG_UPDATEERROR','Incapaz de poner al d�a el expediente(s) - Compruebe por favor los varibles y/o el permiso del directorio');
?>
