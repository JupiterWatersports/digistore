<?php
/*
  $Id: admin_files.php,v 1.2 2005/05/04 20:11:09 tropic Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Incluye La Contribución:
  Tenga acceso con la cuenta llana (v. 2.2a) para el área del Admin del osCommerce (MS2

  Este archivo puede ser suprimido si inhabilita la contribución antedicha
*/

define('HEADING_TITLE', 'Menu de Administyración "Boxes"');

define('TABLE_HEADING_ACTION', 'Actión');
define('TABLE_HEADING_BOXES', 'Boxes');
define('TABLE_HEADING_FILENAME', 'Nombre File');
define('TABLE_HEADING_GROUPS', 'Grupos');
define('TABLE_HEADING_STATUS', 'Estado');

define('TEXT_COUNT_BOXES', 'Boxes: ');
define('TEXT_COUNT_FILES', 'File(s): ');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Boxes: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Simplemente haga click sobre el boton verde para instalar el box, rojo para deinstalar.<br><br><b>NOTA:</b> Si deinstallas el box, los files incluidos seran borrados!');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' instalado');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' no instalado');

define('STATUS_BOX_INSTALLED', 'Instalado');
define('STATUS_BOX_NOT_INSTALLED', 'No Instalado');
define('STATUS_BOX_REMOVE', 'Remover');
define('STATUS_BOX_INSTALL', 'Instalar');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'File: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Confirmacion Remover');
define('TEXT_INFO_HEADING_NEW_FILE', 'Insertar Files');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Click el boton <b>insertar files</b> para insertar un nuevo file el el curriente box: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Remover <font color="red"><b>%s</b></font> desde <b>%s</b> box? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Controla el <font color="red"><b>menu izquierdo</b></font> para asegurarte de insertar los files corectamente.');

define('TEXT_INFO_NEW_FILE_BOX', 'Box Curriente: ');

?>