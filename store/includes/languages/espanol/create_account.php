<?php
/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Crear una Cuenta');
define('NAVBAR_TITLE_2', 'Proceso');
define('HEADING_TITLE', 'Datos de Mi Cuenta');
define('NAVBAR_TITLE_PWA', 'Incorpore la información de la facturación y del envío');
define('HEADING_TITLE_PWA', 'Información de la facturación y del envío');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTA:</b></font></small> Si ya ha pasado por este proceso y tiene una cuenta, por favor <a href="%s"><u>entre</u></a> en ella.');

define('EMAIL_SUBJECT', 'Bienvenido a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Estimado ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Estimado ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Estimado ' . stripslashes($HTTP_POST_VARS['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'Le damos la bienvenida a <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Ahora puede disfrutar de los <b>servicios</b> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><b>Carrito Permanente</b> - Cualquier producto añadido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><b>Libro de Direcciones</b> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumpleaños directamente a la persona que cumple años.' . "\n" . '<li><b>Historia de Pedidos</b> - Vea la relacion de compras que ha realizado con nosotros.' . "\n" . '<li><b>Comentarios</b> - Comparta su opinion sobre los productos con otros clientes.' . "\n\n");
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Nota:</b> Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
//BEGIN SEND HTML MAIL//
// Email style
define('STORE_LOGO', 'logo.jpg');      //Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Background image. 
define('COLOR_TOP_EMAIL', '#999999');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body
define('COLOR_TABLE', '#f9f9f9');         //background color of the email body  (only visible if no background image)

 
//Account Gender True:    
define('EMAILGREET_MR', '<b>Estimado. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n"); 
define('EMAILGREET_MS', '<b>Estimado. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n");

//Account Gender False:
define('EMAILGREET_NONE', '<b>Estimado ' . stripslashes($HTTP_POST_VARS['firstname'] . ' ' . $HTTP_POST_VARS['lastname'].'</b>') . ',' . "\n");

//Email Body
define('EMAILWELCOME', 'Bienvenido a ' . STORE_NAME . '<br /><br /> '. "\n\n");  
define('EMAILTEXT', 'Usted puede ahora participar en los varios <b>servicios </b> del que tenemos que ofrecerle. Algunos de estos servicios incluyen:' . "\n\n" . '<li><b>Carro permanente</b> - Cualquier producto agregó a su carro en línea permanece allí hasta que usted lo quite, o lo comprueba hacia fuera.' . "\n" . '<li><b>Agenda</b> - ¡Podemos ahora entregar sus productos a otra dirección con excepción el suyo! Esto es perfecto enviar los regalos de cumpleaños directos a la cumpleaños-persona ellos mismos.' . "\n" . '<li><b>Order History</b> - Vea su historia de las compras que usted ha hecho con nosotros.' . "\n" . '<li><b>Revisiones de los productos</b> - Comparta sus opiniones sobre productos con nuestros otros clientes.' . "\n\n");  
define('EMAILCONTACT', 'Para la ayuda con cualesquiera de nuestros servicios onlines, envíe por correo electrónico por favor al store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' .  "\n" . '<br /><br />' . "\n\n");  
define('EMAILWARNING', '<b>Nota:</b> Este email address nos fue dado por uno de nuestros clientes. Si usted no inscripción era un miembro, envíe por favor un email a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

//Email Footer.  
define('EMAIL_SEPARATOR', '' . "\n");  //Define Email Separator
define('EMAIL_TEXT_FOOTER', '');     //Footer Text 
 

// Prepare Variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="'. HTTP_SERVER . DIR_WS_CATALOG . ' stylesheetmail.css">');   //Define CSS Stylesheet to use
define('VARLOGO', ' <a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> '); //Define Logo location.  DO NOT CHANGE
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '" > ' ) ; //Header Table 
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting

//END SEND HTML MAIL//
?>