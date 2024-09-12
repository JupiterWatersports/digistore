<?php
/*
  $Id: login.php,v 1.2 2005/05/04 20:11:09 tropic Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Incluye La Contribución:
  Tenga acceso con la cuenta llana (v. 2.2a) para el área del Admin del osCommerce (MS2

  Este archivo puede ser suprimido si inhabilita la contribución antedicha
*/

// Translation by Piero Trono http://php-multishop.com


define('NAVBAR_TITLE', 'Acceso a Oleopolis');
define('HEADING_TITLE', 'Bienvenido, puedes entrar en tu parte de administración');
define('TEXT_STEP_BY_STEP', 'paso a paso'); // should be empty


define('HEADING_RETURNING_ADMIN', 'Panel de Login:');
define('HEADING_PASSWORD_FORGOTTEN', 'Password Olvidada:');
define('TEXT_RETURNING_ADMIN', 'Solo Staff!');
define('ENTRY_EMAIL_ADDRESS', 'Direccion E-Mail:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_FIRSTNAME', 'Nombre:');
define('IMAGE_BUTTON_LOGIN', 'Enviar');

define('TEXT_PASSWORD_FORGOTTEN', 'Password olvidada?');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Nombre de usuario o password errónea!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Nombre o password no encontrada!');
define('TEXT_FORGOTTEN_FAIL', 'Ya has intentado acceder más de 3 veces. Por seguridad contacta tu Administrador para obtener una nueva password.<br>&nbsp;<br>&nbsp;');
define('TEXT_FORGOTTEN_SUCCESS', 'La nueva password ha sido enviada a tu correo elecctronico. Utilízala para hacer el login.<br>&nbsp;<br>&nbsp;');

define('ADMIN_EMAIL_SUBJECT', 'Nueva Password en %s para el administrador %s %s');
define('ADMIN_EMAIL_TEXT', 'Hola %s,' . "\n\n" . 'Puedes entrar en tu área de administración de nuestra web acon la seguiente password. Despues de entrar, es mejor cambiar tu password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Gracias!' . "\n" . '%s' . "\n\n" . 'Esto es un mail automatico, por favor no respondas!');
?>