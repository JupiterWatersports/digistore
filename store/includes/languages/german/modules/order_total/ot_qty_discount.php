<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  define('MODULE_QTY_DISCOUNT_TITLE', 'Globales Mengenrabatt');
  define('MODULE_QTY_DISCOUNT_DESCRIPTION', 'Spezifischer Skontoprozentsatz der Quantität oder Pauschalpreis - spezifizieren Sie den Diskontsatz, der auf der Zahl Einzelteilen in der Karre basiert (global über allen Produkten).');
  define('SHIPPING_NOT_INCLUDED', ' [Versenden nicht eingeschlossen]');
  define('TAX_NOT_INCLUDED', ' [Steuer nicht eingeschlossen]');
  
  define('MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION', ' (%s%%)'); // %s ist der Prozentdiskont als Zahl; %%zeigt a an % Zeichen
  define('MODULE_QTY_DISCOUNT_FORMATED_TITLE', '<strong>Mengenrabatt%s:</strong>'); // %s ist die Platzierung von MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION
  define('MODULE_QTY_DISCOUNT_FORMATED_TEXT', '<strong>-%s</strong>'); // %s ist die Diskontmenge, die für die Währung formated ist
?>