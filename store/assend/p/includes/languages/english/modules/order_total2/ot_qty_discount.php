<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  define('MODULE_QTY_DISCOUNT_TITLE', 'Global Quantity Discount (old version)');
  define('MODULE_QTY_DISCOUNT_DESCRIPTION', 'Quantity specific discount percentage or flat rate - Specify discount rate based on the number of items in the cart (global across all products).');
  define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  define('TAX_NOT_INCLUDED', ' [Tax not included]');
  
  define('MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s is the percent discount as a number; %% displays a % sign
  define('MODULE_QTY_DISCOUNT_FORMATED_TITLE', '<strong>Quantity Discount%s:</strong>'); // %s is the placement of the MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_QTY_DISCOUNT_FORMATED_TEXT', '<strong>-%s</strong>'); // %s is the discount amount formated for the currency
?>