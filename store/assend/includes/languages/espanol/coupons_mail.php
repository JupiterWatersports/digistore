<?php
/*
  $Id: mail.php,v 1.9 2002/01/19 22:44:34 harley_vb Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Send coupon to customer');

define('TEXT_CUSTOMER', 'Cliente:');
define('TEXT_SUBJECT', 'Tema:');
define('TEXT_FROM', 'Remitente:');
define('TEXT_CODE', 'Código:');
define('TEXT_WERT', 'Valor:');
define('TEXT_MESSAGE', 'Mensaje:');
define('TEXT_SELECT_CUSTOMER', 'Seleccione al cliente');
define('TEXT_ALL_CUSTOMERS', 'Todos los clientes');
define('TEXT_NEWSLETTER_CUSTOMERS', 'Todo el CustomersTo todos los abonnents del boletín de noticias');

define('TEXT_SUBJECT_VALUE', 'Cupón de XXX');
define('TEXT_MESSAGE_VALUE', 'Estimadas señoras y caballeros,

Con este correo usted recibe una cupón a partir de XXX con un valor de <!WERT>.

Código de la cupón: <!CODE>

¡Para redimir la cupón, visite por favor nuestro almacén de la tela en XXX!
En el panel derecho usted consiguió la posibilidad para introducir el código enumerado arriba.

Los atentamente,');

define('NOTICE_EMAIL_SENT_TO', 'Aviso: el email se ha enviado a: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Error: no se ha seleccionado a ninguÌn cliente.');

define('TEXT_INFO_VARIABLES', 'Usted puede utilizar las dos variables siguientes dentro del texto del email: <ul><li><strong>&lt;!WERT&gt;</strong> (Exhibe el valor de la cupón )</strong></li><li><strong>&lt;!CODE&gt;</strong> (Exhibe el código de la cupón)</li></ul>');
?>