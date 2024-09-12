<?php
/*
  $Id:Batch_print.php, hpdl Exp $
*/
define('TEXT_ORDER_NUMBERS_RANGES', 'Número(s) de orden, uno # o gama, # - #, or #,#,#');
define('HEADING_TITLE', 'Centro de la impresión de hornada');
define('TABLE_HEADING_COMMENTS', 'Comentarios');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_TAX', 'Impuesto');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Precio (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Precio (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('ENTRY_SOLD_TO', 'VENDIDO A:');
define('ENTRY_SHIP_TO', 'NAVE A:');
define('ENTRY_PAYMENT_METHOD', 'Método del pago:');
define('ENTRY_PAYMENT_TYPE', 'De la tarjeta de crédito:');
define('PAYMENT_TYPE', 'De la tarjeta de crédito');
define('ENTRY_CC_OWNER', 'Dueño de la tarjeta de crédito:');
define('ENTRY_CC_NUMBER', 'Número de tarjeta de crédito:');
define('ENTRY_CC_EXP', 'Fecha de vencimiento:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_PHONE', 'Teléfono:');
define('ENTRY_EMAIL', 'E-Mail:');
define('ENTRY_TAX', 'Impuesto:');
define('ENTRY_SHIPPING', 'Envío:');
define('ENTRY_TOTAL', 'Total:');
define('TEXT_ORDER_NUMBER','Número de orden:');
define('TEXT_ORDER_DATE','Fecha de la orden:');
define('TEXT_ORDER_FORMAT','F j, Y');
define('TEXT_CHOOSE_TEMPLATE','Elija la plantilla del archivo que usted desea imprimir');
define('TEXT_CHOOSE_TEMPLATE','Cualquiera incorpora por favor los números de orden/las gamas que usted quiere extraído a PDF:<br>(eg. 2577,2580-2585,2588)');
define('TEXT_DATES_ORDERS_EXTRACTRED','O incorpore las fechas de órdenes que usted quiere extraído al pdf: <br> (incorpore la fecha adentro YYYY-MM-DD formato)');
define('TEXT_FROM','De:');
define('TEXT_TO','Entrega: ');
define('TEXT_PRINTING_LABELS_BILLING_DELIVERY','Cuando etiquetas de la impresión :- Utilice la dirección de facturación o la dirección de entrega?');
define('TEXT_DELIVERY','Entrega: ');
define('TEXT_BILLING','Facturación: ');
define('TEXT_POSITION_START_PRINTING', 'Posición para comenzar la impresión de: <br> (0 posiciones son etiqueta izquierda superior, ellos aumentan de izquierda a derecha entonces de de arriba a abajo)');
define('TEXT_INCLUDE_ORDERS_STATUS', 'Incluya solamente las órdenes con el estado: el <br>if ningunos, todas las órdenes será incluido)');
define('TEXT_SHOW_ORDER','Demuestre la fecha de la orden?');
define('TEXT_SHOW_PHONE_NUMBER','Demuestre el número de teléfono del cliente?');
define('TEXT_SHOW_EMAIL_CUSTOMER','Demuestre a clientes el email address?');
define('TEXT_PAYMENT_INFORMATION','Demuestre la información del pago?');
define('TEXT_SHOW_CREDIT_CARD_NUMBER','Demuestre el número de tarjeta de crédito? (para las órdenes de la tarjeta de crédito solamente)');
define('TEXT_AUTOMACILLLY_CHANGE_ORDER','Automáticamente estados de la orden de cambio a: <br> (si no se cambia ningunos, ningunos estados.)');
define('TEXT_SHOW_OREDERS_COMMENTS','Demuestre las órdenes sin comentarios? <br> (no demostrará orden con los comentarios puestos por el cliente en la época de la orden.)');
define('TEXT_NOTIFY_CUSTOMER','Notifique al cliente vía email? <br> (esto notificará al cliente vía email con los comentarios en el archivo de la lengua de la impresión de hornada.)');
define('TEXT_BANK','Banco: ');
define('TEXT_POST','Poste: ');
define('TEXT_SALES','Ventas: ');
define('TEXT_PACKED_BY','Embalado cerca:  ______________________');
define('TEXT_VERIFIED_BY','Verificado cerca:  ______________________');
define('TEXT_DEAR',' ');
define('TEXT_THX_CHRISMAS','Gracias por su ayuda continua -----');
define('TEXT_RETURNS_LABEL', 'Orden de la etiqueta de las vueltas: ');
define('TEXT_SHIPPING_LABEL', 'Orden de la etiqueta de envío: ');
define('SHIP_FROM_COUNTRY', '');  //eg. 'United Kingdom'
define('WEBSITE', 'www.Your site.com');
define('TEXT_RETURNS', 'Esperamos que usted pongan \ la 'necesidad de t él pero hemos proporcionado una etiqueta de las vueltas apenas en caso de que. Vea por favor nuestra política de las vueltas en WWW. Su site.com/shipping.php');
define('TEXT_TO', 'A:');

// Change this to a general comment that you would like
define('BATCH_COMMENTS','Notificación automática de la actualización de la orden.');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Actualización de la orden');
define('EMAIL_TEXT_ORDER_NUMBER', 'Número de orden:');
define('EMAIL_TEXT_INVOICE_URL', 'Factura detallada:');
define('EMAIL_TEXT_DATE_ORDERED', 'Fecha pedida:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Su orden se ha puesto al día al estado siguiente. '. "\ n \ n". "Nuevo estado: %s". " \ n \ n ". 'Conteste por favor a este email si usted tiene cualesquiera preguntas.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Los comentarios para su orden son' . "\n\n%s\n\n");

// RGB Colors
define('BLACK', '0,0,0');
define('GREY', '0.9,0.9,0.9');
define('DARK_GREY', '0.7,0.7,0.7');

// Error and Messages
$error['ERROR_INVALID_INPUT'] = 'Error interno: Entrada desconocida o inválida de la escritura.';
$error['ERROR_BAD_DATE'] =  'La fecha inválida, incorpora por favor una fecha válida en Año-Mes-Día (0000-00-00) formato.';
$error['ERROR_BAD_INVOICENUMBERS'] =  'Los números de factura inválidos, incorporan por favor un formato válido. (eg. 2577,2580-2585,2588)';
$error['NO_ORDERS'] =  'No había órdenes seleccionadas para la exportación, intento que cambiaba sus opciones de la orden.';
$error['SET_PERMISSIONS'] = 'El canto escribe al directorio!  Fije por favor los permisos de su carpeta del temp_pdf al CHMOD 0777 ';
$error['FAILED_TO_OPEN'] = 'Could not open file for writing, make sure correct permissions are set';

// PDF FONT SIZES
define('COMPANY_HEADER_FONT_SIZE','14');
define('SUB_HEADING_FONT_SIZE','11');
define('GENERAL_FONT_SIZE', '11');
define('GENERAL_LEADING', '12');
define('PRODUCT_TOTALS_LEADING', '11');
define('PRODUCT_TOTALS_FONT_SIZE', '10');
define('PRODUCT_ATTRIBUTES_FONT_SIZE', '8');
define('GENERAL_FONT_COLOR', BLACK);

// Margins and Page Size

// This works best with A4, could work with diffferent page sizes,
// However it would require playing with the table values and font values to fit properly
//define('PAGE','A4');
//define('LEFT_MARGIN','30');
// The small indents in the Sold to: Ship to: Text blocks
//define('TEXT_BLOCK_INDENT', '5');
//define('SHIP_TO_COLUMN_START','300');
// This changes the 'Total', 'Sub-Total', 'Tax', and 'Shipping Method' text block
// position, for example if you choose to make the text a bigger font size you need to 
// tweak this value in order to prevent the text from clashing together
//define('PRODUCT_TOTAL_TITLE_COLUMN_START','400');
//define('RIGHT_MARGIN','30');

// Batch Print Misc Vars
define('BATCH_PRINT_INC', DIR_WS_MODULES . 'batch_print/');
define('BATCH_PDF_DIR', BATCH_PRINT_INC . 'temp_pdf/');
//define('LINE_LENGTH', '552');
// If you have attributes for certain products, you can have the text wrap
// or just be written completely on one line, with the text wrap disabled
// it makes the tables smaller appear much better, of course that is only my opinion
// so I made this variable if anyone would like it to wrap.
//define('PRODUCT_ATTRIBUTES_TEXT_WRAP', false);
// This sets the space size between sections
//define('SECTION_DIVIDER', '15');
// Main File
define('BATCH_PRINT_FILE', 'batch_print.php');
// TEMP PDF FILE
define('BATCH_PDF_FILE', 'batch_orders.pdf');

// Product table Settings
//define('TABLE_HEADER_FONT_SIZE', '9');
//define('TABLE_HEADER_BKGD_COLOR', DARK_GREY);
//define('PRODUCT_TABLE_HEADER_WIDTH', '552');
// This is more like cell padding, it moves the text the number
// of points specified to make the rectangle appear padded
//define('PRODUCT_TABLE_BOTTOM_MARGIN', '2');
// Tiny indent right before the product name, again more like
// the cell padding effect
//define('PRODUCT_TABLE_LEFT_MARGIN', '2');
// Height of the product listing rectangles
//define('PRODUCT_TABLE_ROW_HEIGHT', '11');

// The column sizes are where the product listing columns start on the
// PDF page, if you make the TABLE HEADER FONT SIZE any larger you will
// need to tweak these values to prevent text from clashing together
//define('PRODUCTS_COLUMN_SIZE', '172');
//define('PRODUCT_LISTING_BKGD_COLOR',GREY);
//define('MODEL_COLUMN_SIZE', '37');
//define('PRICING_COLUMN_SIZES', '67');
?>