<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuevos Productos');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Pr&oacute;ximamente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lanzamiento');
define('TABLE_HEADING_DEFAULT_SPECIALS', 'Specials para');

if ( ($category_depth == 'products') || (isset($HTTP_GET_VARS['manufacturers_id'])) ) {
  define('HEADING_TITLE', '');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modelo');
  define('TABLE_HEADING_PRODUCTS', 'Productos');
  define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
  define('TABLE_HEADING_QUANTITY', 'Cantidad');
  define('TABLE_HEADING_PRICE', 'Precio');
  define('TABLE_HEADING_WEIGHT', 'Peso');
  define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');
  define('TEXT_NO_PRODUCTS', 'No hay productos en esta categoria.');
  define('TEXT_NO_PRODUCTS2', 'No hay productos de este fabricante.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'N&uacute;mero de Productos: ');
  define('TEXT_SHOW', '<b>Mostrar:</b>');
  define('TEXT_BUY', 'Compre 1 \'');
  define('TEXT_NOW', '\' ahora');
  define('TEXT_ALL_CATEGORIES', 'Todas');
  define('TEXT_ALL_MANUFACTURERS', 'Todos');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categorias');
}
?>


