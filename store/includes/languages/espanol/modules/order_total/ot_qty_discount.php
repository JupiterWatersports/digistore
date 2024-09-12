<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  define('MODULE_QTY_DISCOUNT_TITLE', 'Descuento de cantidad global');
  define('MODULE_QTY_DISCOUNT_DESCRIPTION', 'Porcentaje o la tarifa única específico de descuento de la cantidad - especifique el tipo de descuento basado en el número de artículos en el carro (global a través de todos los productos).');
  define('SHIPPING_NOT_INCLUDED', ' [Envío no incluido]');
  define('TAX_NOT_INCLUDED', ' [Impuesto no incluido]');
  
  define('MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s está el descuento del por ciento como número; %% exhibe a % muestra
  define('MODULE_QTY_DISCOUNT_FORMATED_TITLE', '<strong>Descuento de cantidad%s:</strong>'); // %s es la colocación del MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_QTY_DISCOUNT_FORMATED_TEXT', '<strong>-%s</strong>'); // %s es la cantidad del descuento formated para la moneda
?>