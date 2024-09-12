<?php
/*
  $Id: admin_members.php,v 1.2 2005/05/04 20:11:09 tropic Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Incluye La Contribución:
  Tenga acceso con la cuenta llana (v. 2.2a) para el área del Admin del osCommerce (MS2

  Este archivo puede ser suprimido si inhabilita la contribución antedicha
*/

if ($HTTP_GET_VARS['gID']) {
  define('HEADING_TITLE', 'Grupos de Admin');
} elseif ($HTTP_GET_VARS['gPath']) {
  define('HEADING_TITLE', 'Definicion Grupos');
} else {
  define('HEADING_TITLE', 'Miembros de Admin');
}


// BOF: KategorienAdmin / OLISWISS
define('TEXT_INFO_CATEGORIEACCESS','Accesso a categorías:');
define('TEXT_RIGHTS_CNEW','Crear Categoría');
define('TEXT_RIGHTS_CEDIT','Editar Categoría');
define('TEXT_RIGHTS_CMOVE','Mover Categoría');
define('TEXT_RIGHTS_CDELETE','Borrar categoría');
define('TEXT_RIGHTS_PNEW','Crear Producto');
define('TEXT_RIGHTS_PEDIT','Editar Producto');
define('TEXT_RIGHTS_PMOVE','Mover Producto');
define('TEXT_RIGHTS_PCOPY','Editar Producto');
define('TEXT_RIGHTS_PDELETE','Borrar Producto');
// EOF: KategorienAdmin / OLISWISS

define('TEXT_COUNT_GROUPS', 'Grupos: ');

define('TABLE_HEADING_NAME', 'Nombre');
define('TABLE_HEADING_EMAIL', 'Email');
define('TABLE_HEADING_PASSWORD', 'Password');
define('TABLE_HEADING_CONFIRM', 'Confirma Password');
define('TABLE_HEADING_GROUPS', 'Nivel Grupos');
define('TABLE_HEADING_CREATED', 'Cuenta Creada');
define('TABLE_HEADING_MODIFIED', 'Cuenta Creada');
define('TABLE_HEADING_LOGDATE', 'Ultimo Acceso');
define('TABLE_HEADING_LOGNUM', 'LogNum');
define('TABLE_HEADING_LOG_NUM', 'Log Numero');
define('TABLE_HEADING_ACTION', 'Actión');

define('TABLE_HEADING_GROUPS_NAME', 'Nombre Grupos');
define('TABLE_HEADING_GROUPS_DEFINE', 'Selección de cajas y Ficheros');
define('TABLE_HEADING_GROUPS_GROUP', 'Nivel');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Permisos y Categorias');


define('TEXT_INFO_HEADING_DEFAULT', 'Miembros de Admin ');
define('TEXT_INFO_HEADING_DELETE', 'Cancelar Permiso ');
define('TEXT_INFO_HEADING_EDIT', 'Cambia Categoria / ');
define('TEXT_INFO_HEADING_NEW', 'Nuevo Miembro Admin ');

define('TEXT_INFO_DEFAULT_INTRO', 'grupo Miembro');
define('TEXT_INFO_DELETE_INTRO', 'Borrar <nobr><b>%s</b></nobr> de <nobr>Miembros de Admin?</nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'No puedes eliminar el <nobr>grupo %s!</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Configura nivel de permiso aqui: ');

define('TEXT_INFO_FULLNAME', 'Nombre completo: ');
define('TEXT_INFO_FIRSTNAME', 'Nombre: ');
define('TEXT_INFO_LASTNAME', 'Apellido: ');
define('TEXT_INFO_EMAIL', 'Email: ');
define('TEXT_INFO_PASSWORD', 'Password: ');
define('TEXT_INFO_CONFIRM', 'Confirma Password: ');
define('TEXT_INFO_CREATED', 'Cuenta Creada: ');
define('TEXT_INFO_MODIFIED', 'Cuenta Modificada: ');
define('TEXT_INFO_LOGDATE', 'Ultimo Acceso: ');
define('TEXT_INFO_LOGNUM', 'Log Numero: ');
define('TEXT_INFO_GROUP', 'Nivel Grupo: ');
define('TEXT_INFO_ERROR', '<font color="red">Direccion de Email ya utilizada!</font>');

define('JS_ALERT_FIRSTNAME', '- Obligatorio: Nombre \n');
define('JS_ALERT_LASTNAME', '- Obligatorio: Apellido \n');
define('JS_ALERT_EMAIL', '- Obligatorio: Dirección Email \n');
define('JS_ALERT_EMAIL_FORMAT', '- Formado Email invalido! \n');
define('JS_ALERT_EMAIL_USED', '- Direccion Email ya utilizado! \n');
define('JS_ALERT_LEVEL', '- Obligatorio: Miembro Grupo \n');

define('ADMIN_EMAIL_SUBJECT', 'Nuevo Miembro Administrador en %s: %s %s');
define('ADMIN_EMAIL_TEXT', 'Hola %s,' . "\n\n" . 'Puedes entrar en el Panel de administracion con la siguiente password. Nada más entrar puedes cambiar tu password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Gracias!' . "\n" . '%s' . "\n\n" . "$s\n\n" . 'Esto es un mail automatico, no respondas!');
define('ADMIN_EMAIL_EDIT_SUBJECT', 'Cambios en la página %s para el administrador %s %s');
define('ADMIN_EMAIL_EDIT_TEXT', 'Hola %s,' . "\n\n" . 'Tus datos personales,password o nivel de acceso han sido modificados por el administrador.' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Gracias!' . "\n" . '%s' . "\n\n" . 'Esto es un mail automatico, no respondas!');

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Grupo Admin ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Eliminar Grupo ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>NOTA:</b><li><b>edita:</b> edita nombre grupo.</li><li><b>eliminar:</b> elimina grupo.</li><li><b>definir:</b> para definir acceso grupo.</li>');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'Esto elimina tambien los miembros del grupo. Quieres eliminar el <nobr>grupo <b>%s</b>?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'No puedes eliminar estos grupos!');
define('TEXT_INFO_GROUPS_INTRO', 'Elija un nombre de grupo univoco. Clica <b>sigue</b> para enviar.');
define('TEXT_INFO_EDIT_GROUPS_INTRO', 'Elija un nombre de grupo univoco. Clica <b>sigue</b> para enviar.');

define('TEXT_INFO_HEADING_EDIT_GROUP', 'Grupo Admin');
define('TEXT_INFO_HEADING_GROUPS', 'Nuevo Grupo');
define('TEXT_INFO_GROUPS_NAME', ' <b>Nombre Grupo:</b><br>Elije un nombre de grupo univoco. Después, clica <b>sigue</b> para enviar.<br>');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>ERROR:</b> El nombre de grupo tiene que ser mas largo de 5 caracteres!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>ERROR:</b> Nombre Grupo ya utilizado!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Nivel Grupo: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Permiso  Boxes:</b><br>Das acceso al box seleccionado.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Incluye ficheros guardados en: ');

define('TEXT_INFO_HEADING_DEFINE', 'Definir Grupo');
if ($HTTP_GET_VARS['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>No puedes cambiar los Permisos a los File por este grupo.<br><br>');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Cambia permiso a este grupo seleccionando o deseleccionando los boxes y los files. Click <b>grabar</b> para guardar cambios.<br><br>');
}
?>