<?php

/*

  $Id: espanol.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2007 osCommerce



  Released under the GNU General Public License

*/



// look in your $PATH_LOCALE/locale directory for available locales..

// on RedHat6.0 I used 'es_ES'

// on FreeBSD 4.0 I use 'es_ES.ISO_8859-1'

// this may not work under win32 environments..

setlocale(LC_TIME, 'es_ES.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()

define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()

define('DATE_FORMAT', 'd/m/Y');  // this is used for date()

define('PHP_DATE_TIME_FORMAT', 'd/m/Y H:i:s'); // this is used for date()

define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');



////

// Return date in raw format

// $date should be in format mm/dd/yyyy

// raw date is in format YYYYMMDD, or DDMMYYYY

function tep_date_raw($date, $reverse = false) {

  if ($reverse) {

    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);

  } else {

    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);

  }

}



// Global entries for the <html> tag

define('HTML_PARAMS','dir="ltr" lang="es"');



// charset for web pages and emails

define('CHARSET', 'iso-8859-1');



// page title

define('TITLE', 'Digistore Admin');



define('HEADING_TITLE2', '10 los mejores productos vistos');

define('TABLE_HEADING_VIEWED2', 'Visto');



define('BOX_REPORTS_STOCK_LEVEL', 'Informe común bajo');



define('HEADER_WARNING', 'Here you can put a warning for your clients <br>Warning! Please take a database backup before change these settings. ');



// admin welcome text

define('TEXT5', 'Usted tiene ');

define('TEXT6', ' clientes en total y ');

define('TEXT7', ' productos en su almacén. ');

define('TEXT8', ' de sus productos se ha repasado.');

define('DO_USE', 'Usted puede utilizar la navegación rápida en la tapa de la página para manejar sus órdenes.');

define('WELCOME_BACK', 'Recepción detrás ');

define('STOCK_TEXT_WARNING1', '<b><font   color="#990000">Advertencia!</font></b> usted tiene ');

define('STOCK_TEXT_WARNING2', ' producto(s) eso está funcionando hacia fuera - de - la acción. Chasque aquí  ');

define('STOCK_TEXT_WARNING3', ' para ver su estado común.');

define('STOCK_TEXT_OK1', '<font color="#009900 ">Su estado común es bueno</font> y ningunos nuevos productos necesitan ser pedidos. Chasque aquí');

define('STOCK_TEXT_OK2', ' para ver su estado común.');

// admin welcome text end





// summary info v1.1 plugin by conceptlaboratory.com

define('TEXT_SUMMARY_INFO_WHOS_ONLINE', 'Usuarios en línea: %s');

define('TEXT_SUMMARY_INFO_CUSTOMERS', 'Clientes totales: %s, Hoy: %s');

define('TEXT_SUMMARY_INFO_ORDERS', 'Su estado de la orden es: <br> %s, <b>Hoy:</b> %s');

define('TEXT_SUMMARY_INFO_REVIEWS', 'Revisiones totales: %s, Hoy: %s');

define('TEXT_SUMMARY_INFO_TICKETS', 'Estado del boleto %s');

define('TEXT_SUMMARY_INFO_ORDERS_TOTAL', 'Su total de la orden es: <br> %s,<b> Hoy: </b>%s');

// summary info v1.1 plugin by conceptlaboratory.com eof





// header text in includes/header.php

define('HEADER_TITLE_TOP', 'Administraci&oacute;n');

define('HEADER_TITLE_SUPPORT', 'Soporte');

define('HEADER_TITLE_VIEWSTORE', 'Almacén de la visión');

define('HEADER_TITLE_SIGNOFF', 'Firme hacia fuera'); 

define('HEADER_TITLE_ONLINE_CATALOG', 'Cat&aacute;logo');

define('HEADER_TITLE_ADMINISTRATION', 'Administraci&oacute;n');



// text for gender

define('MALE', 'Var&oacute;n');

define('FEMALE', 'Mujer');



// text for date of birth example

define('DOB_FORMAT_STRING', 'dd/mm/aaaa');



// configuration box text in includes/boxes/configuration.php

define('BOX_HEADING_CONFIGURATION', 'Disposicion');

define('BOX_CONFIGURATION_ADMINISTRATORS', 'Administrators'); 

define('BOX_CONFIGURATION_SETUP', 'Disposición general'); 

define('BOX_CONFIGURATION_MYSTORE', 'Mi Tiends'); 

define('BOX_CONFIGURATION_TEMPLATE', 'Plantilla del defecto'); 

define('BOX_CONFIGURATION_MINIMUM_VALUES', 'Valores del minio');

define('BOX_CONFIGURATION_MAXIMUM_VALUES', 'Valores máximos');

define('BOX_CONFIGURATION_IMAGES', 'Imágenes');

define('BOX_CONFIGURATION_CUSTOMER_DETAILS', 'Detalles del cliente');

define('BOX_CONFIGURATION_MODULE_OPTIONS', 'Opciones del módulo');

define('BOX_CONFIGURATION_SHIPPING_PACKING', 'Envío/embalaje');

define('BOX_CONFIGURATION_PRODUCT_LISTING', 'Listado del producto');

define('BOX_CONFIGURATION_STOCK', 'Acción Control');

define('BOX_CONFIGURATION_LOGGING', 'Registración');

define('BOX_CONFIGURATION_CACHE', 'Cach&eacute');

define('BOX_CONFIGURATION_EMAIL_OPTIONS', 'E-Mail Opciones');

define('BOX_CONFIGURATION_DOWNLOAD', 'Transferencia directa');

define('BOX_CONFIGURATION_GZIP_COMPRESSION', 'GZip Compresión');

define('BOX_CONFIGURATION_SESSIONS', 'Sesiones');

define('BOX_CONFIGURATION_META_TAGS', 'Meta Etiquetas');

define('BOX_CONFIGURATION_SEO', 'SEO URLs');

define('BOX_CONFIGURATION_URL_VALIDATION', 'URL Validación');

define('BOX_CONFIGURATION_HOMEPAGE_AD', 'Anuncio homepage');

define('BOX_TITLE_ORDERS', 'Órdenes');

define('BOX_TITLE_STATISTICS', 'Estadísticas');

define('BOX_ENTRY_SUPPORT_SITE', 'Sitio de la ayuda');

define('BOX_ENTRY_SUPPORT_FORUMS', 'Foros de la ayuda');

define('BOX_ENTRY_CONTRIBUTIONS', 'Contribuciones');

define('BOX_ENTRY_CUSTOMERS', 'Clientes:');

define('BOX_ENTRY_PRODUCTS', 'Productos:');

define('BOX_ENTRY_REVIEWS', 'Revisiones:');

define('BOX_TOOLS_BANNER_MANAGER', 'Encargado de la bandera');

define('BOX_CONNECTION_PROTECTED', 'Una conexión segura del SSL de %s le protege.');

define('BOX_CONNECTION_UNPROTECTED', 'Usted es <font color="#ff0000">no</font> protegido por una conexión segura del SSL.');

define('BOX_CONNECTION_UNKNOWN', 'desconocido');

define('CATALOG_CONTENTS', 'Contenido');

define('REPORTS_PRODUCTS', 'Productos');

define('REPORTS_ORDERS', 'Órdenes');

define('TOOLS_BACKUP', 'Respaldo');

define('TOOLS_BANNERS', 'Banderas');

define('TOOLS_FILES', 'Archivos');

define('TEXT_LINK_RECENTLY_VIEWED','Productos recientemente vistos');

define('MATC_HEADING_CONDITIONS', 'Acepte las condiciones');



// modules box text in includes/boxes/modules.php

define('BOX_HEADING_MODULES', 'M&oacute;dulos');

define('BOX_MODULES_PAYMENT', 'Pago');

define('BOX_MODULES_SHIPPING', 'Env&iacute;o');

define('BOX_MODULES_ORDER_TOTAL', 'Totalizaci&oacute;n');



// categories box text in includes/boxes/catalog.php

define('BOX_HEADING_CATALOG', 'Cat&aacute;logo');

define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categorias/Productos');

define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI', 'Cualidades de producto multi');

define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Atributos');

define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Rápido-Acción-Updater');

define('BOX_CATALOG_MANUFACTURERS', 'Fabricantes');

define('BOX_CATALOG_REVIEWS', 'Comentarios');

define('BOX_CATALOG_SPECIALS', 'Ofertas');

define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Pr&oacute;ximamente');

// get_1_free

define('BOX_CATALOG_GET_1_FREE', 'Consiga 1 libre');



// customers box text in includes/boxes/customers.php

define('BOX_HEADING_CUSTOMERS', 'Clientes');

define('BOX_TOOLS_BATCH_CENTER', 'Centro de la impresión de hornada');

define('BOX_CUSTOMERS_CUSTOMERS', 'Visión Clientes');



//Orders text for menu header Orders

define('BOX_CUSTOMERS_ORDERS', 'Órdenes');

define('BOX_CUSTOMERS_ORDERS_PENDING', 'Visión pendiente');

define('BOX_CUSTOMERS_ORDERS_PROCESSING', 'Proceso de la visión');

define('BOX_CUSTOMERS_ORDERS_DELIVERED', 'Visión entregada');

define('BOX_CUSTOMERS_ORDERS_STATUS', 'Estado de la orden');

define('BOX_CUSTOMERS_ORDERS_EDITOR', 'Disposición del redactor de la orden');



// taxes box text in includes/boxes/taxes.php

define('BOX_HEADING_LOCATION_AND_TAXES', 'Zonas/Impuestos');

define('BOX_TAXES_COUNTRIES', 'Paises');

define('BOX_TAXES_ZONES', 'Provincias');

define('BOX_TAXES_GEO_ZONES', 'Zonas de Impuestos');

define('BOX_TAXES_TAX_CLASSES', 'Tipos de Impuestos');

define('BOX_TAXES_TAX_RATES', 'Impuestos');



// reports box text in includes/boxes/reports.php

define('BOX_HEADING_REPORTS', 'Informes');

define('BOX_REPORTS_PRODUCTS_VIEWED', 'Los Mas Vistos');

define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Los Mas Comprados');

define('BOX_REPORTS_ORDERS_TOTAL', 'Total por Cliente');

define('BOX_REPORTS_KEYWORD_LIST', 'Búsquedas de palabra clave');

// Monthly sales

define('BOX_REPORTS_SALES', 'Ventas mensuales');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Resultados de ventas recuperados');

//begin Inactive User Report

define('BOX_REPORTS_INACTIVE_USER', 'usuario inactivo');

//end Inactive User Report

define('BOX_REPORTS_STOCK_LEVEL', 'Informe común bajo');



// tools text in includes/boxes/tools.php

define('BOX_HEADING_TOOLS', 'Herramientas');

define('BOX_TOOLS_BACKUP', 'Copia de Seguridad');

define('BOX_TOOLS_BANNER_MANAGER', 'Banners');

define('BOX_TOOLS_CACHE', 'Control de Cach&eacute;');

define('BOX_TOOLS_DEFINE_LANGUAGE', 'Definir Idiomas');

define('BOX_TOOLS_FILE_MANAGER', 'Archivos');

define('BOX_TOOLS_MAIL', 'Enviar Email');

define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Boletines');

define('BOX_TOOLS_SERVER_INFO', 'Informaci&oacute;n');

define('BOX_TOOLS_WHOS_ONLINE', 'Usuarios conectados');

define('BOX_TOOLS_DOWN_FOR_MAINTAINANCE', 'Abajo para el mantenimiento');

define('BOX_TOOLS_RECOVER_CART', 'Recupere las ventas del carro');

define('BOX_BASKET_PASSWORD', 'Cambie la contraseña de la cesta');



// localizaion box text in includes/boxes/localization.php

define('BOX_HEADING_LOCALIZATION', 'Localizaci&oacute;n');

define('BOX_LOCALIZATION_CURRENCIES', 'Monedas');

define('BOX_LOCALIZATION_LANGUAGES', 'Idiomas');

define('BOX_LOCALIZATION_ORDERS_STATUS', 'Estado Pedidos');



// javascript messages

define('JS_ERROR', 'Ha habido errores procesando su formulario!\nPor favor, haga las siguientes modificaciones:\n\n');



define('JS_OPTIONS_VALUE_PRICE', '* El atributo necesita un precio\n');

define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* El atributo necesita un prefijo para el precio\n');



define('JS_PRODUCTS_NAME', '* El producto necesita un nombre\n');

define('JS_PRODUCTS_DESCRIPTION', '* El producto necesita una descripci&oacute;n\n');

define('JS_PRODUCTS_PRICE', '* El producto necesita un precio\n');

define('JS_PRODUCTS_WEIGHT', '* Debe especificar el peso del producto\n');

define('JS_PRODUCTS_QUANTITY', '* Debe especificar la cantidad\n');

define('JS_PRODUCTS_MODEL', '* Debe especificar el modelo\n');

define('JS_PRODUCTS_IMAGE', '* Debe suministrar una imagen\n');



define('JS_SPECIALS_PRODUCTS_PRICE', '* Debe rellenar el precio\n');



define('JS_GENDER', '* Debe elegir un \'Sexo\'.\n');

define('JS_FIRST_NAME', '* El \'Nombre\' debe tener al menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.\n');

define('JS_LAST_NAME', '* El \'Apellido\' debe tener al menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.\n');

define('JS_DOB', '* La \'Fecha de Nacimiento\' debe tener el formato: xx/xx/xxxx (dia/mes/a&ntilde;o).\n');

define('JS_EMAIL_ADDRESS', '* El \'E-Mail\' debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.\n');

define('JS_ADDRESS', '* El \'Domicilio\' debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.\n');

define('JS_POST_CODE', '* El \'C&oacute;digo Postal\' debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.\n');

define('JS_CITY', '* La \'Ciudad\' debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.\n');

define('JS_STATE', '* Debe indicar la \'Provincia\'.\n');

define('JS_STATE_SELECT', '-- Seleccione Arriba --');

define('JS_ZONE', '* La \'Provincia\' se debe seleccionar de la lista para este pais.');

define('JS_COUNTRY', '* Debe seleccionar un \'Pais\'.\n');

define('JS_TELEPHONE', '* El \'Telefono\' debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.\n');

define('JS_PASSWORD', '* La \'Contrase&ntilde;a\' y \'Confirmaci&oacute;n\' deben ser iguales y tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.\n');



define('JS_ORDER_DOES_NOT_EXIST', 'El n&uacute;mero de pedido %s no existe!');



define('CATEGORY_PERSONAL', 'Personal');

define('CATEGORY_ADDRESS', 'Domicilio');

define('CATEGORY_CONTACT', 'Contacto');

define('CATEGORY_COMPANY', 'Empresa');

define('CATEGORY_OPTIONS', 'Opciones');



define('ENTRY_GENDER', 'Sexo:');

define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">obligatorio</span>');

define('ENTRY_FIRST_NAME', 'Nombre:');

define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras</span>');

define('ENTRY_LAST_NAME', 'Apellidos:');

define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras</span>');

define('ENTRY_DATE_OF_BIRTH', 'Fecha de Nacimiento:');

define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(p.ej. 21/05/1970)</span>');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');

define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras</span>');

define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">Su Email no parece correcto!</span>');

define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">email ya existe!</span>');

define('ENTRY_COMPANY', 'Nombre empresa:');

define('ENTRY_STREET_ADDRESS', 'Direcci&oacute;n:');

define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras</span>');

define('ENTRY_SUBURB', '');

define('ENTRY_POST_CODE', 'C&oacute;digo Postal:');

define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras</span>');

define('ENTRY_CITY', 'Poblaci&oacute;n:');

define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' letras</span>');

define('ENTRY_STATE', 'Provincia:');

define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">obligatorio</span>');

define('ENTRY_COUNTRY', 'Pa&iacute;s:');

define('ENTRY_TELEPHONE_NUMBER', 'Tel&eacute;fono:');

define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras</span>');

define('ENTRY_FAX_NUMBER', 'Fax:');

define('ENTRY_NEWSLETTER', 'Bolet&iacute;n:');

define('ENTRY_NEWSLETTER_YES', 'suscrito');

define('ENTRY_NEWSLETTER_NO', 'no suscrito');



// images

define('IMAGE_ANI_SEND_EMAIL', 'Enviando E-Mail');

define('IMAGE_BACK', 'Volver');

define('IMAGE_BACKUP', 'Copiar');

define('IMAGE_CANCEL', 'Cancelar');

define('IMAGE_CONFIRM', 'Confirmar');

define('IMAGE_COPY', 'Copiar');

define('IMAGE_COPY_TO', 'Copiar A');

define('IMAGE_DETAILS', 'Detalle');

define('IMAGE_DELETE', 'Eliminar');

define('IMAGE_EDIT', 'Editar');

define('IMAGE_EMAIL', 'Email');

define('IMAGE_FILE_MANAGER', 'Archivos');

define('IMAGE_ICON_STATUS_GREEN', 'Activado');

define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Activar');

define('IMAGE_ICON_STATUS_RED', 'Desactivado');

define('IMAGE_ICON_STATUS_RED_LIGHT', 'Desactivar');

define('IMAGE_ICON_INFO', 'Datos');

define('IMAGE_INSERT', 'Insertar');

define('IMAGE_LOCK', 'Bloqueado');

define('IMAGE_MODULE_INSTALL', 'Instalar M&oacute;dulo');

define('IMAGE_MODULE_REMOVE', 'Quitar M&oacute;dulo');

define('IMAGE_MOVE', 'Mover');

define('IMAGE_NEW_BANNER', 'Nuevo Banner');

define('IMAGE_NEW_CATEGORY', 'Nueva Categoria');

define('IMAGE_NEW_COUNTRY', 'Nuevo Pais');

define('IMAGE_NEW_CURRENCY', 'Nueva Moneda');

define('IMAGE_NEW_FILE', 'Nuevo Fichero');

define('IMAGE_NEW_FOLDER', 'Nueva Carpeta');

define('IMAGE_NEW_LANGUAGE', 'Nueva Idioma');

define('IMAGE_NEW_NEWSLETTER', 'Nuevo Bolet&iacute;n');

define('IMAGE_NEW_PRODUCT', 'Nuevo Producto');

define('IMAGE_NEW_TAX_CLASS', 'Nuevo Tipo de Impuesto');

define('IMAGE_NEW_TAX_RATE', 'Nuevo Impuesto');

define('IMAGE_NEW_TAX_ZONE', 'Nueva Zona');

define('IMAGE_NEW_ZONE', 'Nueva Zona');

define('IMAGE_ORDERS', 'Pedidos');

define('IMAGE_ORDERS_INVOICE', 'Factura');

define('IMAGE_ORDERS_PACKINGSLIP', 'Albar&aacute;n');

define('IMAGE_PREVIEW', 'Ver');

define('IMAGE_RESET', 'Resetear');

define('IMAGE_RESTORE', 'Restaurar');

define('IMAGE_SAVE', 'Grabar');

define('IMAGE_SEARCH', 'Buscar');

define('IMAGE_SELECT', 'Seleccionar');

define('IMAGE_SEND', 'Enviar');

define('IMAGE_SEND_EMAIL', 'Send Email');

define('IMAGE_UNLOCK', 'Desbloqueado');

define('IMAGE_UPDATE', 'Actualizar');

define('IMAGE_UPDATE_CURRENCIES', 'Actualizar Cambio de Moneda');

define('IMAGE_UPLOAD', 'Subir');



define('ICON_CROSS', 'Falso');

define('ICON_CURRENT_FOLDER', 'Directorio Actual');

define('ICON_DELETE', 'Eliminar');

define('ICON_ERROR', 'Error');

define('ICON_FILE', 'Fichero');

define('ICON_FILE_DOWNLOAD', 'Descargar');

define('ICON_FOLDER', 'Carpeta');

define('ICON_LOCKED', 'Bloqueado');

define('ICON_PREVIOUS_LEVEL', 'Nivel Anterior');

define('ICON_PREVIEW', 'Ver');

define('ICON_STATISTICS', 'Estadisticas');

define('ICON_SUCCESS', 'Exito');

define('ICON_TICK', 'Verdadero');

define('ICON_UNLOCKED', 'Desbloqueado');

define('ICON_WARNING', 'Advertencia');



// constants for use in tep_prev_next_display function

define('TEXT_RESULT_PAGE', 'P&aacute;gina %s de %d');

define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> banners)');

define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> paises)');

define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> clientes)');

define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> monedas)');

define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> idiomas)');

define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> fabricantes)');

define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> boletines)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> pedidos)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> estado de pedidos)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos esperados)');

define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> comentarios)');

define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> ofertas)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> zonas de impuestos)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> porcentajes de impuestos)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> tipos de impuesto)');

define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> zonas)');



define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');

define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');



define('TEXT_DEFAULT', 'predeterminado/a');

define('TEXT_SET_DEFAULT', 'Establecer como predeterminado/a');

define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Obligatorio</span>');



define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: No hay moneda predeterminada. Por favor establezca una en: Herramientas de Administracion->Localizaci&oacute;n->Monedas');



define('TEXT_CACHE_CATEGORIES', 'Categorias');

define('TEXT_CACHE_MANUFACTURERS', 'Fabricantes');

define('TEXT_CACHE_ALSO_PURCHASED', 'Tambi&eacute;n Han Comprado');



define('TEXT_NONE', '--ninguno--');

define('TEXT_TOP', 'Principio');



define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destino no existe.');

define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: No se puede escribir en el destino.');

define('ERROR_FILE_NOT_SAVED', 'Error: El archivo subido no se ha guardado.');

define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: Extension de fichero no permitida.');

define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Exito: Fichero guardado con &eacute;xito.');

define('WARNING_NO_FILE_UPLOADED', 'Advertencia: No se ha subido ningun archivo.');

define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: Se ha desactivado la subida de archivos en el fichero de configuraci&oacute;n php.ini.');

	// coupons addon start

  	define('BOX_CATALOG_COUPONS', 'Cupones');

	// coupons addon end

// infoBox Admin

define('BOX_HEADING_BOXES', 'Infobox Admin');



define('BOX_HEADING_HOMEPAGE', 'Anuncio del homepage');

define('BOX_CONTENT_HOMEPAGE', 'Encargado de anuncio del homepage');



// bof shopinfo textblocks

define('BOX_HEADING_ADD_SHOPINFO', 'nueva página'); 

define('BOX_HEADING_SHOPINFO', 'Páginas Info');

  // just for admin/index.php: 

  define('BOX_AGB_SHOPINFO', 'Condiciones');

  define('BOX_PRIVACY_SHOPINFO', 'Confidencialidad');

  define('BOX_ABOUTUS_SHOPINFO', 'Sobre nosotros');

  //

// eof shopinfo 



// eof shopinfo textblocks

// This copyright notice CAN NOT be REMOVED or MODIFIED as required in the license agreement.

define('COPYRIGHT_NOTICE',' <font color="#999999" size="1"><br>

      Digistore se basa en el motor del osCommerce: Derechos reservados &copy; 2003 osCommerce<br>

      <br>

      Este programa se distribuye con la esperanza de que sea útil, pero FUERA 

      CUALQUIE GARANTÍA;<br>

      sin incluso la garantía implicada del MERCHANTABILITY o de la APTITUD PARA UN DETALLE 

      PROPÓSITO<br>

      y es redistributable debajo de <a href="http://www.gnu.org/" target="_blank">GNU 

      Licencia el público en general</a>');

define('DIGIADMIN_VERSION', 'Digistore V4');



define('MENU_CONFIGURATION_TEMPLATES', 'Plantillas'); 

//START STS 4.1

define('BOX_MODULES_STS', 'Plantillas adicionales STS  ');

//END STS 4.1

define('TEXT_DISPLAY_NUMBER_OF_KEYWORDS', 'Exhibición <b>%d</b> a <b>%d</b> (de <b>%d</b> palabras claves)');

// Down for Maintenance Admin reminder

define ('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'El sitio se establece actualmente para el mantenimiento al Público.  Recuerde traerlo para arriba cuando le hacen!');

define('BOX_CATALOG_XSELL_PRODUCTS', 'Venda en cross-sell los productos');

// Xsell cache

define('TEXT_CACHE_XSELL_PRODUCTS', 'Venda en cross-sell los productos');

define('PAID_NO_ORDER', 'Pagado pero ninguna orden ');

// sitemonitor text in includes/boxes/sitemonitor.php

define('BOX_HEADING_SECURITY', 'Seguridad');

define('BOX_HEADING_SITEMONITOR', 'Monitor del sitio');

define('BOX_SITEMONITOR_ADMIN', 'Monitor del sitio Admin');

define('BOX_SITEMONITOR_CONFIG_SETUP', 'Monitor del sitio Configure');

define('IMAGE_EXCLUDE', 'Excluya');

define('BOX_HEADING_SECURITY', 'FWR Seguridad Pro');

define('BOX_HEADING_OT_MODULE', '<br><br>NOTAS: Si usted tiene 2 módulos instalados con la misma orden de la clase que no trabajarán, cerciórese de que las pedidos de la clase de los módulos de TOTALIZATION estén correctas. Está normalmente: <br><br>Subtotal: descuento de cantidad 1<br>Global: 2<br>Coupon 3<br>Taxes: 4<br>Shipping: 5<br>Total: 6');

define('TEXT_DISCOUNTPLUS_SETUP', 'Por la disposición del descuento del producto');

// seo assistant start

define('BOX_TOOLS_SEO_ASSISTANT', 'Ayudante de SEO');

//seo assistant end

?>