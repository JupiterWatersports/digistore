<?php
/*
  $Id: admin_account.php,v 1.2 2005/05/04 20:11:09 tropic Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Incluye La Contribución:
  Tenga acceso con la cuenta llana (v. 2.2a) para el área del Admin del osCommerce (MS2

  Este archivo puede ser suprimido si inhabilita la contribución antedicha
*/

// Translation by Piero Trono http://php-multishop.com

define('HEADING_TITLE', 'Cuenta de Admin');

define('TABLE_HEADING_ACCOUNT', 'Mi Cuenta');

define('TEXT_INFO_FULLNAME', '<b>Nombre completo: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Nombre: </b>');
define('TEXT_INFO_LASTNAME', '<b>Apellido: </b>');
define('TEXT_INFO_EMAIL', '<b>Dirección Email: </b>');
define('TEXT_INFO_PASSWORD', '<b>Password: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Ocultado-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Confirma Password: </b>');
define('TEXT_INFO_CREATED', '<b>Cuenta Creada: </b>');
define('TEXT_INFO_LOGDATE', '<b>Ultimo Acceso: </b>');
define('TEXT_INFO_LOGNUM', '<b>Log Numero: </b>');
define('TEXT_INFO_GROUP', '<b>Nivel Grupo: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Dirección Email ya utilizada!.</font>');
define('TEXT_INFO_MODIFIED', 'Modificado: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Cambia Cuenta ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Confirma Password ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Password:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>ERROR:</b> password invalida!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Click <b>editar</b> para cambiar tu cuenta.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br><b>WARNING:</b><br>Hola <b>%s</b>, acabas de entrar por primera vez. Te recomendamos que cambies tu password!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br><b>WARNING:</b><br>Hello <b>%s</b>, we recommend you to change your email (<font color="red">admin@localhost</font>) and password!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Todos los campos son obligatorios. Click <b>grabar</b> para guardar tus cambios.');

define('JS_ALERT_FIRSTNAME',        '- Obligatorio: Nombre \n');
define('JS_ALERT_LASTNAME',         '- Obligatorio: Apellido \n');
define('JS_ALERT_EMAIL',            '- Obligatorio: Dirección Email \n');
define('JS_ALERT_PASSWORD',         '- Obligatorio: Password \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Nombre largo mas que ');
define('JS_ALERT_LASTNAME_LENGTH',  '- Apellido má largo de ');
define('JS_ALERT_PASSWORD_LENGTH',  '- Password más largo de ');
define('JS_ALERT_EMAIL_FORMAT',     '- Dirección Email invalido! \n');
define('JS_ALERT_EMAIL_USED',       '- Direccón Email ya utilizada! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- Confirma tu Password! \n');

define('ADMIN_EMAIL_SUBJECT', 'Cambio de Informacion Personal en %s para %s %s');
define('ADMIN_EMAIL_TEXT', 'Hola %s,' . "\n\n" . 'Tus datos personales, tu password o tu nivel de acceso a las categorías de la página han sido modificados. Si esto no era tu intencion, contacta con el administrador inmediatamente!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Gracias!' . "\n" . '%s' . "\n\n" . 'Esto es un mail automatico, por favor no respondas!');
?>