<?php
/*
  $Id: advanced_search.php,v 1.13 2002/05/27 13:57:38 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Infobox Ayuda');
define('TEXT_INFO_HEADING_NEW_INFOBOX', 'Infobox Ayuda');

define('TEXT_INFOBOX_HELP_FILENAME', 'Esto debe representar el nombre del archivo de caja que usted ha puesto en su <u>catalog/includes/boxes</u> carpeta.<br><br> Debe ser minúsculo, pero puede tener espacios en vez de usar la raya(_)<br><br>Por ejemplo:<br>Se nombra su nuevo Infobox <b>new_box.php</b>, usted mecanografiaría adentro aquí "<b>nueva caja</b>"<br><br>Otro ejemplo sería <b>cuáles_nuevo</b> caja.<br> Obviuosly se nombra<b> cuáles_nuevo.php </b>, usted podría mecanografiar adentro aquí <b>cuál es nuevo</b>');

define('TEXT_INFOBOX_HELP_HEADING', 'Esto es absolutamente simplemente qué será exhibida sobre el Infobox en su catálogo.<br>');

define('TEXT_INFOBOX_HELP_DEFINE', 'Un ejemplo de esto sería: <b>BOX_HEADING_WHATS_NEW</b>.<br> Esto entonces se utiliza con "main logic" de su almacén como esto: <b> define(\'BOX_HEADING_WHATS_NEW\', \'What\'s New?\');</b><br><br> Si usted abre el archivo <u>catalog/includes/languages/espanol.php</u> usted puede ver el un montón de ejemplos, los que contienen BOX_HEADING se necesitan no más mientras que ahora se almacenan dentro de la base de datos y se definen en los archivos <b>column_left.php</b> y <b>column_right.php</b>.<br> Pero no hay necesidad de suprimirlos!! ');

define('TEXT_INFOBOX_HELP_COLUMN', '¡El fácil esto!! Entre en cualquiera <b>left</b> o <b>right</b><br> Si usted quiere el Infobox exhibido en la columna izquierda -- entre <b>Left</b> o si usted lo quiere la columna derecha -- entre <b>Right</b><br><br><br> Para ser honesto quise utilizar <b>tep_cfg_select_option</b>, pero como lo estoy utilizando para activar el Infobox por alguna razón no podría.<br> Seleccionaría solamente uno de ellos.<br><br> Si cualquier persona puede verter alguna luz en porqué, sería el más agradecido para el Info.<a href="mailto:paul_langford@btopenworld.com"> envíeme</a>');



define('TEXT_INFOBOX_HELP_POSITION', 'Incorpore cualquier número que usted tenga gusto adentro aquí. Más alto es el número la llanura más baja es la columna seleccionada el Infobox aparecerá.<br><br> Si usted incorpora el mismo número para más de un Infobox se exhiben alfabéticamente primero');
define('TEXT_INFOBOX_HELP_ACTIVE', 'Otra vez cualquiera selecto <b>yes</b> or <b>no</b>. <b>yes</b> exhibirá el Infobox y <b>no</b> no permitirá que el Infobox sea exhibido.');
define('TEXT_CLOSE_WINDOW', '<u>Ventana cercana</u> [x]');

?>
