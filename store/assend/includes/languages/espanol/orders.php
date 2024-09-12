<?php
/*
  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Pedidos');
define('HEADING_TITLE_SEARCH', 'Pedido:');
define('HEADING_TITLE_STATUS', 'Estado:');

define('TABLE_HEADING_COMMENTS', 'Comentarios');
define('TABLE_HEADING_CUSTOMERS', 'Clientes');
define('TABLE_HEADING_ORDER_TOTAL', 'Total Pedido');
define('TABLE_HEADING_DATE_PURCHASED', 'Fecha de Compra');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Acci&oacute;n');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_TAX', 'Impuesto');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Precio (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Precio (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente Notificado');
define('TABLE_HEADING_DATE_ADDED', 'A&ntilde;adido el');

define('ENTRY_CUSTOMER', 'Cliente:');
define('ENTRY_SOLD_TO', 'Cliente:');
define('ENTRY_DELIVERY_TO', 'Enviar A:');
define('ENTRY_SHIP_TO', 'Enviar A:');
define('ENTRY_SHIPPING_ADDRESS', 'Direcci&oacute; de Env&iacute;o:');
define('ENTRY_BILLING_ADDRESS', 'Direcci&oacute; de Facturaci&oacute;n:');
define('ENTRY_PAYMENT_METHOD', 'M&eacute;todo de Pago:');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_OWNER', 'Titular Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_NUMBER', 'N&uacute;mero Tarjeta Credito:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Caducidad Tarjeta Credito:');
define('ENTRY_SUB_TOTAL', 'Subtotal:');
define('ENTRY_TAX', 'Impuestos:');
define('ENTRY_SHIPPING', 'Gastos de Env&iacute;o:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Fecha de Compra:');
define('ENTRY_STATUS', 'Estado:');
define('ENTRY_DATE_LAST_UPDATED', 'Ultima Modificaci&oacute;n:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notificar Cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'A&ntilde;adir Comentarios:');
define('ENTRY_PRINTABLE', 'Imprimir Factura');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Eliminar Pedido');
define('TEXT_INFO_DELETE_INTRO', 'Seguro que quiere eliminar este pedido?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'A&ntilde;adir productos al almacen');
define('TEXT_DATE_ORDER_CREATED', 'A&ntilde;adido el:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Modificado:');
define('TEXT_INFO_PAYMENT_METHOD', 'M&eacute;todo de Pago:');

define('TEXT_ALL_ORDERS', 'Todos');
define('TEXT_NO_ORDER_HISTORY', 'No hay hist&oacute;rico');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Actualizaci&oacute;n del Pedido');
define('EMAIL_TEXT_ORDER_NUMBER', 'N&uacute;mero de Pedido:');
define('EMAIL_TEXT_INVOICE_URL', 'Pedido Detallado:');
define('EMAIL_TEXT_DATE_ORDERED', 'Fecha del Pedido:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Su pedido ha sido actualizado al siguiente estado.' . "\n\n" . 'Nuevo estado: %s' . "\n\n" . 'Por favor responda a este email si tiene alguna pregunta que hacer.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Los comentarios sobre su pedido son' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: No existe pedido.');
define('SUCCESS_ORDER_UPDATED', 'Exito: Pedido actualizado correctamente.');
define('WARNING_ORDER_NOT_UPDATED', 'Advertencia: No se ha actualizado el pedido, no habia nada que actualizar.');

//BEGIN SEND HTML MAIL//

// Email style
define('STORE_LOGO', 'logo.jpg'); // Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Header background image.
define('COLOR_TOP_EMAIL', '#999999');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body
define('COLOR_TABLE', '#f9f9f9');         //background color of the email body (only visible if no background image)

//First section of text
define('EMAIL_TEXT_DEAR', '<br><br>Estimado');        
define('EMAIL_MESSAGE_GREETING', 'Quisiéramos notificarle que el estado de su orden se ha puesto al día.'); 

//Email Footer
define('EMAIL_TEXT_FOOTER', 'Este email address nos fue dado por usted o por uno de nuestros clientes. Si usted siente que usted ha recibido este email en error, envíe por favor un email a');    
define('EMAIL_TEXT_COPYRIGHT', 'Derechos reservados © 2008 ');


//Define Variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="stylesheetmail.css">');   //location of email css file.
define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');   //Do not change
define('VARMAILFOOTER', '' . EMAIL_TEXT_FOOTER . '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER_EMAIL_ADDRESS . '</a><br>' . EMAIL_TEXT_COPYRIGHT . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. STORE_NAME .'</a> ');  //footer
define('VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');   //logo
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '"> ');   //Header table formatting

define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting

//END SEND HTML MAIL//
define('ENTRY_IPADDRESS', 'El IP address para esta orden<br>(Chasque encendido para los detalles)');
define('ENTRY_IPISP', 'ISP:');
define('TEXT_PRODUCTS_BY_BUNDLE', 'Productos adentro');
?>
