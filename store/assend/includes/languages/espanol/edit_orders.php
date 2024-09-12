<?php
/*
  $Id: edit_orders.php v5.0 08/05/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Corrija la orden #%s of %s');
define('ADDING_TITLE', 'AdiciÛn del producto(s) a la orden #%s');

define('ENTRY_UPDATE_TO_CC', '(ActualizaciÛn a ' . ORDER_EDITOR_CREDIT_CARD . ' para ver campos del cc.)');
define('TABLE_HEADING_COMMENTS', 'Comentarios');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_NEW_STATUS', 'Nuevo estado');
define('TABLE_HEADING_ACTION', 'AcciÛn');
define('TABLE_HEADING_DELETE', 'CancelaciÛn?');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_TAX', 'Impuesto');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_BASE_PRICE', 'Precio<br>(base)');
define('TABLE_HEADING_UNIT_PRICE', 'Precio<br>(excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Precio<br>(incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total<br>(excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total<br>(incl.)');
define('TABLE_HEADING_OT_TOTALS', 'Totales de la orden:');
define('TABLE_HEADING_OT_VALUES', 'Valor:');
define('TABLE_HEADING_SHIPPING_QUOTES', 'Cotizaciones de envÌo:');
define('TABLE_HEADING_NO_SHIPPING_QUOTES', 'No hay cotizaciones del envÌo a exhibir!');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente<br>Notificado');
define('TABLE_HEADING_DATE_ADDED', 'La fecha agregÛ');

define('ENTRY_CUSTOMER', 'Cliente');
define('ENTRY_NAME', 'Nombre:');
define('ENTRY_CITY_STATE', 'Ciudad, estado:');
define('ENTRY_SHIPPING_ADDRESS', 'DirecciÛn de envÌo');
define('ENTRY_BILLING_ADDRESS', 'DirecciÛn de facturaciÛn');
define('ENTRY_PAYMENT_METHOD', 'MÈtodo del pago');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo de tarjeta:');
define('ENTRY_CREDIT_CARD_OWNER', 'DueÒo de la tarjeta:');
define('ENTRY_CREDIT_CARD_NUMBER', 'N˙mero de tarjeta:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'La tarjeta expira:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TYPE_BELOW', 'MecanografÌe abajo');

//the definition of ENTRY_TAX is important when dealing with certain tax components and scenarios
define('ENTRY_TAX', 'Impuesto');
//no utilice dos puntos (:) en el defintion, el IE ìIVAî es aceptable, pero ìIVA: î no es

define('ENTRY_SHIPPING', 'EnvÌo:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Estado:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifique al cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'EnvÌe los comentarios:');
define('ENTRY_CURRENCY_TYPE', 'Moneda');
define('ENTRY_CURRENCY_VALUE', 'Valor de la moneda');

define('TEXT_INFO_PAYMENT_METHOD', 'MÈtodo del pago:');
define('TEXT_NO_ORDER_PRODUCTS', 'Esta orden no contiene ninguÃÅn producto');
define('TEXT_ADD_NEW_PRODUCT', 'Agregue los productos');
define('TEXT_PACKAGE_WEIGHT_COUNT', 'Peso del paquete: %s  |  Qty del producto: %s');

define('TEXT_STEP_1', '<b>Paso 1:</b>');
define('TEXT_STEP_2', '<b>Paso 2:</b>');
define('TEXT_STEP_3', '<b>Paso 3:</b>');
define('TEXT_STEP_4', '<b>Paso 4:</b>');
define('TEXT_SELECT_CATEGORY', '- Elija una categorÌa de la lista -');
define('TEXT_PRODUCT_SEARCH', '<b>- O incorpore un tÈrmino de la b˙squeda a la caja abajo para ver fÛsforos potenciales -</b>');
define('TEXT_ALL_CATEGORIES', 'Todos los productos de /All de las categorÌas');
define('TEXT_SELECT_PRODUCT', '- Elija un producto -');
define('TEXT_BUTTON_SELECT_OPTIONS', 'Seleccione estas opciones');
define('TEXT_BUTTON_SELECT_CATEGORY', 'Seleccione esta categorÌa');
define('TEXT_BUTTON_SELECT_PRODUCT', 'Seleccione este producto');
define('TEXT_SKIP_NO_OPTIONS', '<em>Ningunas opciones - saltadas...</em>');
define('TEXT_QUANTITY', 'Cantidad:');
define('TEXT_BUTTON_ADD_PRODUCT', 'Agregue a la orden');
define('TEXT_CLOSE_POPUP', '<u>Cierre</u> [x]');
define('TEXT_ADD_PRODUCT_INSTRUCTIONS', 'Subsistencia que agrega productos hasta que le hagan.<br>Entonces cierre esta leng¸eta/ventana, vuelva a la leng¸eta principal/la ventana, y presiona "actualizaciÛn" botÛn.');
define('TEXT_PRODUCT_NOT_FOUND', '<b>Producto no encontrado<b>');
define('TEXT_SHIPPING_SAME_AS_BILLING', 'Enviando iguales que la direcciÛn de facturaciÛn');
define('TEXT_BILLING_SAME_AS_CUSTOMER', 'Mandando la cuenta iguales que la direcciÛn del cliente');

define('IMAGE_ADD_NEW_OT', 'Inserte el nuevo total de la orden de encargo despuÈs Èste');
define('IMAGE_REMOVE_NEW_OT', 'Quite este componente del total de la orden');
define('IMAGE_NEW_ORDER_EMAIL', 'EnvÌe el nuevo email de la confirmaciÛn de la orden');

define('TEXT_NO_ORDER_HISTORY', 'Ninguna historia de la orden disponible');

define('PLEASE_SELECT', 'Seleccione por favor');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Se ha puesto al dÌa su orden');
define('EMAIL_TEXT_ORDER_NUMBER', 'N˙mero de orden:');
define('EMAIL_TEXT_INVOICE_URL', 'Factura detallada:');
define('EMAIL_TEXT_DATE_ORDERED', 'Fecha pedida:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Gracias tanto por su orden con nosotros!' . "\n\n" . 'El estado de su orden se ha puesto al dÌa.' . "\n\n" . 'Nuevo estado: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'Si usted tiene preguntas, conteste por favor a este email.' . "\n\n" . 'Con respetos calientes de sus amigos en ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Los comentarios para su orden son' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Orden %s no existe.');
define('ERROR_NO_ORDER_SELECTED', 'Usted no ha seleccionado una orden para corregir, o la variable de la identificaciÛn de la orden no se ha fijado.');
define('SUCCESS_ORDER_UPDATED', '…xito: La orden se ha puesto al dÌa con Èxito.');
define('SUCCESS_EMAIL_SENT', 'Terminado: La orden era actualizada y un email con la nueva informaciÛn fue enviado.');

//the hints
define('HINT_UPDATE_TO_CC', 'Set payment method to ' . ORDER_EDITOR_CREDIT_CARD . ' Fije el toand del mÈtodo del pago que los otros campos ser·n exhibidos autom·ticamente. Se ocultan los campos del cc si se selecciona cualquier otro mÈtodo del pago.  El nombre del mÈtodo del pago que, cuando est· seleccionado, exhibir· los campos del cc es configurable en el ·rea del redactor de la orden de la secciÛn de las Ûrdenes del panel de la administraciÛn.');
define('HINT_UPDATE_CURRENCY', 'El cambio de la moneda har· las cotizaciones del envÌo y los totales de la orden recalcular y recargar.');
define('HINT_SHIPPING_ADDRESS', 'Si usted cambia el estado del envÌo, prefijo postal, o paÌs le dar·n la opciÛn del independientemente de si recalcular los totales y recargar las cotizaciones del envÌo.');
define('HINT_TOTALS', 'Sienta libre de dar descuentos agregando valores negativos. El subtotal, el total del impuesto, y los campos del importe total no son editable. Al agregar en componentes del total de la orden de encargo vÌa AJAX cerciÛrese de que usted introducir el tÌtulo primero o el cÛdigo no reconocer· la entrada (el IE, un componente con un tÌtulo en blanco se suprime de la orden).');
define('HINT_PRESS_UPDATE', 'Chasque por favor encendido la ìactualizaciÛnî para ahorrar todos los cambios.');
define('HINT_BASE_PRICE', 'Precio (base) est· el precio de los productos antes de las cualidades de productos (IE, el precio de cat·logo del artÌculo)');
define('HINT_PRICE_EXCL', 'Precio (excl) es el precio bajo m·s cualquier precio de las cualidades de producto que pueda existir');
define('HINT_PRICE_INCL', 'Precio (incl) es Precio (excl) impuesto de las Èpocas');
define('HINT_TOTAL_EXCL', 'Total (excl) es Precio (excl) mide el tiempo del qty');
define('HINT_TOTAL_INCL', 'Total (incl) es Precio (excl) los tiempos gravan y qty');
//end hints

//new order confirmation email- this is a separate email from order status update
define('ENTRY_SEND_NEW_ORDER_CONFIRMATION', 'Nueva confirmaciÛn de la orden:');
define('EMAIL_TEXT_DATE_MODIFIED', 'La fecha se modificÛ:');
define('EMAIL_TEXT_PRODUCTS', 'Productos');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'DirecciÛn de entrega');
define('EMAIL_TEXT_BILLING_ADDRESS', 'DirecciÛn de facturaciÛn');
define('EMAIL_TEXT_PAYMENT_METHOD', 'MÈtodo del pago');
// Si usted quiere incluir la informaciÛn del pago adicional, incorpore el texto abajo (<br> del uso para la lÌnea roturas):
//define('EMAIL_TEXT_PAYMENT_INFO', ''); //por quÈ esto serÌa ˙til???
// Si usted quiere incluir el texto del pie, incorpore el texto abajo (<br> del uso para la lÌnea roturas):
define('EMAIL_TEXT_FOOTER', '');
//end email

//add-on for downloads
define('ENTRY_DOWNLOAD_COUNT', 'Transferencia directa #');
define('ENTRY_DOWNLOAD_FILENAME', 'Nombre de fichero');
define('ENTRY_DOWNLOAD_MAXDAYS', 'DÌas del vencimiento');
define('ENTRY_DOWNLOAD_MAXCOUNT', 'El permanecer de las transferencias directas');

//add-on for Ajax
define('AJAX_CONFIRM_PRODUCT_DELETE', 'Es usted que usted quiere sure suprimir este producto de la orden?');
define('AJAX_CONFIRM_COMMENT_DELETE', 'Es usted que usted quiere sure suprimir este comentario de la historia del estado de las Ûrdenes?');
define('AJAX_MESSAGE_STACK_SUCCESS', '…xito! \' + %s + \' se ha puesto al dÌa');
define('AJAX_CONFIRM_RELOAD_TOTALS', 'Usted ha cambiado una cierta informaciÛn del envÌo. Usted tiene gusto de recalcular los totales de la orden y las cotizaciones del envÌo?');
define('AJAX_CANNOT_CREATE_XMLHTTP', 'No puede crear XMLHTTP caso');
define('AJAX_SUBMIT_COMMENT', 'Someta los nuevos comentarios y/o estado');
define('AJAX_NO_QUOTES', 'No hay cotizaciones del envÌo a exhibir.');
define('AJAX_SELECTED_NO_SHIPPING', 'Usted ha seleccionado un mÈtodo del envÌo para esta orden pero aparece que no hay una almacenada ya en la base de datos.  Usted tiene gusto de agregar esta carga de envÌo a la orden?');
define('AJAX_RELOAD_TOTALS', 'El nuevo componente del envÌo se ha escrito a la base de datos pero los totales todavÌa no se han recalculado.  Ahora chasque la autorizaciÛn para recalcular los totales de la orden.  Si su conexiÛn es lenta espere todos los componentes para cargar antes de chascar la autorizaciÛn.');
define('AJAX_NEW_ORDER_EMAIL', 'Es usted que usted quiere sure enviar un nuevo email de la confirmaciÛn de la orden para esta orden?');
define('AJAX_INPUT_NEW_EMAIL_COMMENTS', 'Entre por favor cualquier comentario que usted pueda tener aquÌ.  Es aceptable dejar este espacio en blanco si usted no desea incluir comentarios.  Recuerde por favor como usted mecanografÌa eso que golpea la tecla de "ENTRE" dar· lugar a someter los comentarios mientras que aparecen.  No es todavÌa posible incluir la lÌnea roturas.');
define('AJAX_SUCCESS_EMAIL_SENT', '…xito!  Un nuevo email de la confirmaciÛn de la orden fue enviado a %s');
define('AJAX_WORKING', 'El trabajo, espera por favor....');
?>
