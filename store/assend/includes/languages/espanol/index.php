<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Clientes');
define('HEADING_TITLE_SEARCH', 'B�squeda:');
define('TABLE_HEADING_FIRSTNAME', 'Nombre');
define('TABLE_HEADING_LASTNAME', 'Apellido');
define('TABLE_HEADING_ACCOUNT_CREATED', 'La cuenta cre�');
define('TABLE_HEADING_ACTION', 'Acci�n');
define('TEXT_DATE_ACCOUNT_CREATED', 'La cuenta cre�:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Conexi�n pasada:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'N�mero de conexiones:');
define('TEXT_INFO_COUNTRY', 'Pa�s:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'N�mero de revisiones:');
define('TEXT_DELETE_INTRO', 'Es usted que usted quiere sure suprimir a este cliente?');
define('TEXT_DELETE_REVIEWS', 'Revisi�n de la cancelaci�n(s) %s');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Cliente de la cancelaci�n');
define('TYPE_BELOW', 'Mecanograf�e abajo');
define('PLEASE_SELECT', 'Seleccione uno');

define('HEADING_TITLE', '�rdenes');
define('HEADING_TITLE_SEARCH', '�rdenes ID:');
define('HEADING_TITLE_STATUS', 'Estado:');
define('TABLE_HEADING_COMMENTS', 'Comentarios');
define('TABLE_HEADING_CUSTOMERS', 'Clientes');
define('TABLE_HEADING_ORDER_TOTAL', 'Total de la orden');
define('TABLE_HEADING_DATE_PURCHASED', 'La fecha compr�');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Acci�n');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_TAX', 'Impuesto');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Precio (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'El cliente notific�');
define('TABLE_HEADING_DATE_ADDED', 'La fecha agreg�');
define('ENTRY_CUSTOMER', 'Cliente:');
define('ENTRY_SOLD_TO', 'VENDIDO A:');
define('ENTRY_DELIVERY_TO', 'Entrega a:');
define('ENTRY_SHIP_TO', 'NAVE A:');
define('ENTRY_SHIPPING_ADDRESS', 'Direcci�n de env�o:');
define('ENTRY_BILLING_ADDRESS', 'Direcci�n de facturaci�n:');
define('ENTRY_PAYMENT_METHOD', 'M�todo del pago:');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo de la tarjeta de cr�dito:');
define('ENTRY_CREDIT_CARD_OWNER', 'Due�o de la tarjeta de cr�dito:');
define('ENTRY_CREDIT_CARD_NUMBER', 'N�mero de tarjeta de cr�dito:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'De la tarjeta de cr�dito expira:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Impuesto:');
define('ENTRY_SHIPPING', 'Env�o:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'La fecha compr�:');
define('ENTRY_STATUS', 'Estado:');
define('ENTRY_DATE_LAST_UPDATED', 'El �ltimo de la fecha se puso al d�a:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifique al cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'A�ada los comentarios:');
define('ENTRY_PRINTABLE', 'Factura de la impresi�n');
define('TEXT_INFO_HEADING_DELETE_ORDER', 'Orden de la cancelaci�n');
define('TEXT_INFO_DELETE_INTRO', 'Es usted que usted quiere sure suprimir esta orden?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Vuelva a surtir la cantidad del producto');
define('TEXT_DATE_ORDER_CREATED', 'La fecha cre�:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'M�todo del pago:');
define('TEXT_ALL_ORDERS', 'Todas las �rdenes');
define('TEXT_NO_ORDER_HISTORY', 'Ninguna historia de la orden disponible');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Actualizaci�n de la orden');
define('EMAIL_TEXT_ORDER_NUMBER', 'N�mero de orden:');
define('EMAIL_TEXT_INVOICE_URL', 'Factura detallada:');
define('EMAIL_TEXT_DATE_ORDERED', 'La fecha orden�:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Su orden se ha puesto al d�a al estado siguiente.' . "\n\n" . 'Nuevo estado: %s' . "\n\n" . 'Conteste por favor a este email si usted tiene cualesquiera preguntas.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Los comentarios para su orden son' . "\n\n%s\n\n");
define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: La orden no existe.');
define('SUCCESS_ORDER_UPDATED', '�xito: La orden se ha puesto al d�a con �xito.');
define('WARNING_ORDER_NOT_UPDATED', 'Advertencia: Nada cambiar. La orden no era actualizada.');

define('HEADING_TITLE', 'qui�n\'s En l�nea');
define('TABLE_HEADING_ONLINE', 'En l�nea');
define('TABLE_HEADING_CUSTOMER_ID', 'ID');
define('TABLE_HEADING_FULL_NAME', 'Nombre completo');
define('TABLE_HEADING_IP_ADDRESS', 'IP Direcci�n');
define('TABLE_HEADING_ENTRY_TIME', 'Tiempo de la entrada');
define('TABLE_HEADING_LAST_CLICK', 'Tecleo pasado');
define('TABLE_HEADING_LAST_PAGE_URL', 'URL pasado');
define('TABLE_HEADING_ACTION', 'Acci�n');
define('TABLE_HEADING_SHOPPING_CART', 'Carro de compras de los usuarios');
define('TEXT_SHOPPING_CART_SUBTOTAL', 'Subtotal');
define('TEXT_NUMBER_OF_CUSTOMERS', 'Hay actualmente clientes de %s en l�nea');
?>


