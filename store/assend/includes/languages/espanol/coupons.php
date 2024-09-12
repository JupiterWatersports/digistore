<?php
/*
  $Id: coupons.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Coupons');

define('TABLE_HEADING_SORT', 'Orden');
define('TABLE_HEADING_CODE', 'C�digo');
define('TABLE_HEADING_NOTICE', 'Comentario');
define('TABLE_HEADING_VALUE', 'Valor');
define('TABLE_HEADING_MIN_ORDER', 'Orden m�nima');
define('TABLE_HEADING_DATE', 'Fecha de vencimiento');
define('TABLE_HEADING_ACTION', 'Acci�n');

define('TEXT_COUPONS_CODE_LENGTH', 'Cifre la longitud');
define('TEXT_COUPONS_CODE', 'C�digo de la cup�n');
define('TEXT_COUPONS_NOTICE', 'Comentario');
define('TEXT_COUPONS_VALUE', 'Valor de la cup�n');
define('TEXT_COUPONS_MIN_ORDER', 'Orden m�nima');
define('TEXT_COUPONS_DATE', 'V�lido hasta:<br><small>(dd.mm.yyyy)</small>');
define('TEXT_COUPONS_TIP', '<b>Comentario:</b><ul><li>Usted puede incorporar el valor con el punto o la coma como separador decimal</li><li>La cup�n valora m�s bajo que <i>0,01</i><b>no</b> ac�ptese</li><li>Deje el campo <b>\'V�lido hasta\'</b> vac�o, si la cup�n es solamente v�lida<b>hoy</b></li><li>Para cambiar el c�digo de una nueva cup�n, apenas restaure su ventana de hojeador</ul>');

define('ERROR_COUPON_EXIST', 'Error creating the code: Code already exists.');
define('ERROR_COUPON_CODE', 'Error creating the code: invalid code..');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displays <b>%d</b> to <b>%d</b> (of total <b>%d</b> coupons)');

define('TEXT_INFO_HEADING_DELETE_COUPONS', 'Delete coupon');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure, that you want to delete this coupon?');
?>
