<?php
/*
  $Id: products_multi.php, v 2.0

  autor: sr, 2003-07-31 / sr@ibis-project.de

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Encargado de productos m�ltiple');
define('HEADING_TITLE_SEARCH', 'B�squeda:');
define('HEADING_TITLE_GOTO', 'Vaya a:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_CHOOSE', 'Elija');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categor�as / productos');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_ACTION', 'Acci�n');
define('TABLE_HEADING_PRODUCTS_QUANTITY', 'Cantidad');
define('TABLE_HEADING_MANUFACTURERS_NAME', 'Fabricante');
define('TABLE_HEADING_STATUS', 'Estado');

define('DEL_DELETE', 'producto de la cancelaci�n');
define('DEL_CHOOSE_DELETE_ART', 'C�mo suprimir?');
define('DEL_THIS_CAT', 'solamente en esta categor�a');
define('DEL_COMPLETE', 'suprima el producto completo');

define('TEXT_NEW_PRODUCT', 'Nuevo producto adentro &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categor�as:');
define('TEXT_ATTENTION_DANGER', '<br><br><span class="dataTableContentRedAlert">!!! Atenci�n !!! le�do por favor!!!</span><br><br><span class="dataTableContentRed">Esta herramienta altera las tablas "products_to_categories" (y en caso de \'suprima el producto completo\' incluso "products" and "products_description" entre otros; cf. function \'tep_remove_product\') - tan un respaldo de estas tablas antes de que cada uso de la herramienta est� altamente - recomendado, porque:<br><br>Esta herramienta suprime, los movimientos o las copias todos v�a productos seleccionados caja de cheque sin ningu�n paso provisional o advertencia, eso medios inmediatamente despu�s de chascar en el ir-bot�n.</span><br><br><span class="dataTableContentRedAlert">Tome por favor el cuidado:</span><ul><li>Atenci�n muy grande de la paga al usar <strong>\'suprima el producto completo\'</strong>. Esta funci�n suprime todos los productos seleccionados inmediatamente, sin paso provisional o la advertencia, y totalmente de todas las tablas a donde estos productos pertenecen.</strong></li><li>Mientras que elige <strong>\'producto de la cancelaci�n solamente en esta categor�a\'</strong>, no se suprime ningunos productos totalmente, pero solamente sus acoplamientos a la categor�a realmente abierta - incluso cuando su el �nico categor�a-acoplamiento del producto, y sin la advertencia, eso significa: tenga cuidado con esta herramienta de la cancelaci�n tambi�n.</li><li>Mientras que<strong>copying</strong>, los productos no se duplican, ellos se ligan solamente a la nueva categor�a elegida.</li><li>Los productos est�n solamente <strong>moved</strong> resp. <strong>copiado</strong> a una nueva categor�a en caso de que no existan all� ya.</li></ul>');
define('TEXT_MOVE_TO', 'mu�vase a');
define('TEXT_CHOOSE_ALL', 'elija todos');
define('TEXT_CHOOSE_ALL_REMOVE', 'quite elegido');
define('TEXT_SUBCATEGORIES', 'Subcategor�as:');
define('TEXT_PRODUCTS', 'Productos:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Precio:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Clase del impuesto:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Grado medio:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Cantidad:');
define('TEXT_DATE_ADDED', 'La fecha agreg�:');
define('TEXT_DATE_AVAILABLE', 'Fecha disponible:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'LA IMAGEN NO EXISTE');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Inserte por favor una nueva categor�a o producto adentro<br>&nbsp;<br><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Para m�s informaci�n, visite por favor este los productos <a href="http://%s" target="blank"><u>Web page</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'Este producto fue agregado a nuestro cat�logo encendido %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Este producto estar� en la acci�n prendido %s.');

define('TEXT_EDIT_INTRO', 'Realice por favor cualquier cambio necesario');
define('TEXT_EDIT_CATEGORIES_ID', 'Identificaci�n de la categor�a:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Nombre de la categor�a:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Imagen de la categor�a:');
define('TEXT_EDIT_SORT_ORDER', 'Orden de la clase:');

define('TEXT_INFO_COPY_TO_INTRO', 'Elija por favor una nueva categor�a que usted desea copiar este producto a');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Categor�as actuales:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Nueva categor�a');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Corrija la categor�a');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Categor�a de la cancelaci�n');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Mueva la categor�a');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Producto de la cancelaci�n');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Mueva el producto');
define('TEXT_INFO_HEADING_COPY_TO', 'Copie a');
define('LINK_TO', 'Acoplamiento a');

define('TEXT_DELETE_CATEGORY_INTRO', 'Es usted que usted quiere sure suprimir esta categor�a?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Es usted que usted quiere sure suprimir permanentemente este producto?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ADVERTENCIA:</b> Hay %s (child-)las categor�as todav�a ligaron a esta categor�a!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ADVERTENCIA:</b> Hay %s los productos todav�a ligaron a esta categor�a!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Seleccione por favor que la categor�a usted desea <b>%s</b> para residir adentro');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Seleccione por favor que la categor�a usted desea <b>%s</b> para residir adentro');
define('TEXT_MOVE', 'Movimiento <b>%s</b> a:');

define('TEXT_NEW_CATEGORY_INTRO', 'Complete por favor la informaci�n siguiente para la nueva categor�a');
define('TEXT_CATEGORIES_NAME', 'Nombre de la categor�a:');
define('TEXT_CATEGORIES_IMAGE', 'Imagen de la categor�a:');
define('TEXT_SORT_ORDER', 'Orden de la clase:');

define('TEXT_PRODUCTS_STATUS', 'Estado de los productos:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Fecha disponible:');
define('TEXT_PRODUCT_AVAILABLE', 'En Stock');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Hacia fuera - de - acci�n');
define('TEXT_PRODUCTS_MANUFACTURER', 'Fabricante de los productos:');
define('TEXT_PRODUCTS_NAME', 'Nombre de productos:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Descripci�n de productos:');
define('TEXT_PRODUCTS_QUANTITY', 'Cantidad de los productos:');
define('TEXT_PRODUCTS_MODEL', 'Modelo de los productos:');
define('TEXT_PRODUCTS_IMAGE', 'Imagen de productos:');
define('TEXT_PRODUCTS_URL', 'URL de los productos:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(sin el HTTP://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Precio de los productos:');
define('TEXT_PRODUCTS_WEIGHT', 'Peso de los productos:');
define('TEXT_NONE', '--ningunos--');

define('EMPTY_CATEGORY', 'Categor�a vac�a');

define('TEXT_HOW_TO_COPY', 'Copie el m�todo:');
define('TEXT_COPY_AS_LINK', 'Producto del acoplamiento');
define('TEXT_COPY_AS_DUPLICATE', 'Producto duplicado');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: No puede ligar productos en la misma categor�a.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: El directorio de las im�genes del cat�logo no es writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: El directorio de las im�genes del cat�logo no existe: ' . DIR_FS_CATALOG_IMAGES);
?>
